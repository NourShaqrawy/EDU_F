<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    
  public function index()
{
    // تحميل الكورسات مع علاقة الناشر لتجنب N+1
    $courses = Course::with('publisher')->get();

    // تحويل كل كورس إلى مصفوفة منظمة
    $formattedCourses = $courses->map(function ($course) {
        return [
            'id' => $course->id,
            'category_id' => $course->category_id,
            'title' => $course->title,
            'description' => $course->description,
            'publisher_name' => $course->publisher->user_name ?? 'غير معروف',
        ];
    });

    return response()->json($formattedCourses, 200);
}


    
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'category_id' => 'required|exists:categories,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string'
    ]);

   
    $validatedData['publisher_id'] = Auth::id(); 

    $course = Course::create($validatedData);

    return response()->json($course, 201);
}
 
   public function show($id)
{
    $course = Course::with(['videos', 'publisher'])->findOrFail($id);

    return response()->json([
        'id' => $course->id,
        'category_id'=>$course->category_id,
        'title' => $course->title,
        'description' => $course->description,
        'publisher_name' => $course->publisher->user_name ?? 'غير معروف',
        'videos' => $course->videos,
    ], 200);
}

    
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        
       
        if (Auth::user()->role !== 'admin' && Auth::id() !== $course->publisher_id) {
            return response()->json(['message' => 'غير مصرح بالتعديل على هذا الكورس'], 403);
        }

        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
         
        ]);

        $course->update($validatedData);
        return response()->json($course);
    }


    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        
        return response()->json(['message' => 'تم حذف الكورس بنجاح']);
    }
}