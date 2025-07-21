<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourseImage;

class CourseImageController extends Controller
{
    // جلب الصورة
    public function show($id)
    {
        $course = CourseImage::findOrFail($id);

        if (!$course->image_data) {
            return response()->json(['message' => 'لا توجد صورة'], 404);
        }

        return response($course->image_data)->header('Content-Type', 'image/jpeg');
    }

    // تخزين أو تحديث الصورة
    public function storeOrUpdate(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        $course = CourseImage::findOrFail($id);
        $imageBinary = file_get_contents($request->file('image')->getRealPath());

        $course->image_data = $imageBinary;
        $course->save();

        return response()->json(['message' => 'تم حفظ الصورة']);
    }

    // حذف الصورة
    public function destroy($id)
    {
        $course = CourseImage::findOrFail($id);
        $course->image_data = null;
        $course->save();

        return response()->json(['message' => 'تم حذف الصورة']);
    }
}
