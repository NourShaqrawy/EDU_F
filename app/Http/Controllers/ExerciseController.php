<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Video;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ExerciseController extends Controller
{
    /**
     * عرض جميع الاختبارات (للمشرفين فقط)
     */
    public function index()
    {
        $exercises = Exercise::with('video')->get();
        return response()->json($exercises);
    }

    /**
     * عرض اختبار محدد
     */
    public function show($id)
    {
        $exercise = Exercise::with('video')->find($id);
        
        if (!$exercise) {
            return response()->json(['message' => 'الاختبار غير موجود'], 404);
        }
        
        return response()->json($exercise);
    }

    /**
     * إنشاء اختبار جديد (للمشرفين والناشرين)
     */
    public function store(Request $request, $videoId)
{
    // البحث عن الفيديو مع معالجة الاستثناءات
    try {
        $video = Video::findOrFail($videoId);
    } catch (ModelNotFoundException $e) {
        return response()->json([
            'message' => 'الفيديو المطلوب غير موجود',
            'video_id' => $videoId
        ], 404);
    }

    // التحقق من وجود تمرين موجود بالفعل
    if ($video->exercise) {
        return response()->json([
            'message' => 'هذا الفيديو يحتوي بالفعل على تمرين',
            'exercise_id' => $video->exercise->id
        ], 409); // Conflict status code
    }

    // تحقق من الصحة مع رسائل مخصصة
    $validator = Validator::make($request->all(), [
        'pause_time' => 'required|integer|min:0',
        'display_duration' => 'required|integer|min:1',
        'content' => 'required|json',
        'question_type' => [
            'required', 
            Rule::in(['multiple_choice', 'true_false'])
        ],
    ], [
        'pause_time.required' => 'حقل وقت التوقف مطلوب',
        'content.json' => 'يجب أن يكون المحتوى بصيغة JSON صالحة',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'بيانات غير صالحة',
            'errors' => $validator->errors()
        ], 422); // Unprocessable Entity
    }

    // إنشاء التمرين مع معالجة الأخطاء المحتملة
    try {
        $exercise = $video->exercises()->create();
        
        return response()->json([
            'message' => 'تم إنشاء التمرين بنجاح',
            'data' => $exercise
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'حدث خطأ أثناء إنشاء التمرين',
            'error' => $e->getMessage()
        ], 500);
    }
}
    /**
     * تحديث اختبار (للمشرفين والناشرين)
     */
    public function update(Request $request, $id)
    {
        $exercise = Exercise::find($id);
        
        if (!$exercise) {
            return response()->json(['message' => 'الاختبار غير موجود'], 404);
        }

        $validator = Validator::make($request->all(), [
            'pause_time' => 'integer|min:0',
            'display_duration' => 'integer|min:1',
            'content' => 'json',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $exercise->update($request->all());
        
        return response()->json($exercise);
    }

    /**
     * حذف اختبار (للمشرفين والناشرين)
     */
    public function destroy($id)
    {
        $exercise = Exercise::find($id);
        
        if (!$exercise) {
            return response()->json(['message' => 'الاختبار غير موجود'], 404);
        }

        $exercise->delete();
        
        return response()->json(['message' => 'تم حذف الاختبار بنجاح']);
    }

    /**
     * الحصول على اختبارات فيديو معين
     */
    public function getExercisesByVideo($videoId)
    {
        $video = Video::with('exercises')->find($videoId);
        
        if (!$video) {
            return response()->json(['message' => 'الفيديو غير موجود'], 404);
        }
        
        return response()->json($video->exercises);
    }

    /**
     * تسليم إجابة على اختبار (للطلاب)
     */
    public function submitAnswer(Request $request, $exerciseId)
    {
        $exercise = Exercise::find($exerciseId);
        if (!$exercise) {
            return response()->json(['message' => 'الاختبار غير موجود'], 404);
        }

        $validator = Validator::make($request->all(), [
            'answers' => 'required|array',
            'answers.selected_option' => 'required',
            'answers.time_taken' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // هنا يمكنك إضافة منطق تصحيح الإجابة وحساب النتيجة
        $isCorrect = $this->checkAnswer($exercise, $request->answers['selected_option']);
        
        return response()->json([
            'exercise_id' => $exerciseId,
            'is_correct' => $isCorrect,
            'correct_answer' => $exercise->content['correct_answer'] ?? null,
            'message' => $isCorrect ? 'إجابة صحيحة!' : 'إجابة خاطئة'
        ]);
    }

    /**
     * دالة مساعدة لفحص الإجابة
     */
    private function checkAnswer($exercise, $selectedOption)
    {
        $correctAnswer = $exercise->content['correct_answer'] ?? null;
        
        if ($exercise->content['type'] === 'multiple_choice') {
            return $selectedOption == $correctAnswer;
        }
        elseif ($exercise->content['type'] === 'true_false') {
            return $selectedOption === $correctAnswer;
        }
        elseif ($exercise->content['type'] === 'multiple_answers') {
            sort($selectedOption);
            sort($correctAnswer);
            return $selectedOption == $correctAnswer;
        }
        
        return false;
    }
}