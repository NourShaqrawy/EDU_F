<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Video;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
 
public function store(Request $request)
{
    $request->validate([
        'video_id'     => 'required|exists:videos,id|unique:questions,video_id',
        'question_text'=> 'required|string',
        'options'      => 'required|array|min:2|max:10',
        'correct_answer' => [
            'required',
            'string',
            function ($attribute, $value, $fail) use ($request) {
                $options = $request->options;
                if (!in_array($value, $options)) {
                    $fail("الإجابة الصحيحة يجب أن تكون ضمن الخيارات.");
                }
                if (count(array_keys($options, $value)) > 1) {
                    $fail("الإجابة الصحيحة يجب أن تكون فريدة وغير مكررة.");
                }
            }
        ]
    ]);

    $question = Question::create([
        'video_id'       => $request->video_id,
        'question_text'  => $request->question_text,
        'options'        => json_encode($request->options),
        'correct_key'    => $request->correct_answer 
    ]);

    return response()->json([
        'id'             => $question->id,
        'video_id'       => $question->video_id,
        'question_text'  => $question->question_text,
        'options'        => json_decode($question->options, true),
        'correct_answer' => $question->correct_key,
        'created_at'     => $question->created_at,
        'updated_at'     => $question->updated_at
    ], 201);
}



   
  public function show($id)
{
    $question = Question::findOrFail($id);

    return response()->json([
        'id'             => $question->id,
        'video_id'       => $question->video_id,
        'question_text'  => $question->question_text,
        'options'        => json_decode($question->options, true),
        'correct_answer' => $question->correct_key,
        'created_at'     => $question->created_at,
        'updated_at'     => $question->updated_at
    ]);
}


  public function update(Request $request, $id)
{
    $question = Question::findOrFail($id);

    $request->validate([
        'question_text' => 'sometimes|required|string',
        'options' => 'sometimes|required|array|min:2|max:10',
        'correct_answer' => [
            'sometimes',
            'string',
            function ($attribute, $value, $fail) use ($request, $question) {
                $options = $request->options ?? json_decode($question->options, true);
                if (!in_array($value, $options)) {
                    $fail("الإجابة الصحيحة يجب أن تكون ضمن الخيارات.");
                }
                if (count(array_keys($options, $value)) > 1) {
                    $fail("الإجابة الصحيحة يجب أن تكون فريدة وغير مكررة.");
                }
            }
        ]
    ]);

    $question->update([
        'question_text' => $request->question_text ?? $question->question_text,
        'options'       => $request->options ? json_encode($request->options) : $question->options,
        'correct_key'   => $request->correct_answer ?? $question->correct_key
    ]);

    return response()->json([
        'id'             => $question->id,
        'video_id'       => $question->video_id,
        'question_text'  => $question->question_text,
        'options'        => json_decode($question->options, true),
        'correct_answer' => $question->correct_key,
        'created_at'     => $question->created_at,
        'updated_at'     => $question->updated_at
    ]);
}

   public function destroy($id)
{
    $question = Question::find($id);

    if (!$question) {
        return response()->json([
            'message' => 'السؤال غير موجود أو تم حذفه مسبقًا'
        ], 404);
    }

    $question->delete();

    return response()->json([
        'message' => 'تم حذف السؤال بنجاح'
    ]);
}


    

    // 📋 عرض كل الأسئلة (اختياري)
   public function index()
{
    $questions = Question::all()->map(function ($question) {
        return [
            'id'             => $question->id,
            'video_id'       => $question->video_id,
            'question_text'  => $question->question_text,
            'options'        => json_decode($question->options, true),
            'correct_answer' => $question->correct_key,
            'created_at'     => $question->created_at,
            'updated_at'     => $question->updated_at
        ];
    });

    return response()->json($questions);
}

}
