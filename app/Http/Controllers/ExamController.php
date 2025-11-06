<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam;
use Illuminate\Validation\ValidationException;

class ExamController extends Controller
{
    // ✅ عرض جميع الامتحانات
    public function index()
    {
        return Exam::with('video')->get();
    }

    // ✅ إنشاء امتحان جديد
    public function store(Request $request)
    {
        $validated = $request->validate([
            'video_id' => 'required|exists:videos,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'required|integer|min:0|max:100',
        ]);

        $exam = Exam::create($validated);

        return response()->json($exam, 201);
    }

    // ✅ عرض امتحان محدد
    public function show($id)
    {
        $exam = Exam::with('video')->findOrFail($id);
        return response()->json($exam);
    }

    // ✅ تعديل امتحان
    public function update(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'sometimes|required|integer|min:0|max:100',
        ]);

        $exam->update($validated);

        return response()->json($exam);
    }

    // ✅ حذف امتحان
    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->delete();

        return response()->json(['message' => 'تم حذف الامتحان بنجاح']);
    }

    // ✅ عرض امتحان حسب الفيديو
    public function byVideo($videoId)
    {
        $exam = Exam::where('video_id', $videoId)->first();

        if (!$exam) {
            return response()->json(['message' => 'لا يوجد امتحان لهذا الفيديو'], 404);
        }

        return response()->json($exam);
    }
}
