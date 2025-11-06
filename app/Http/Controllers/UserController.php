<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class UserController extends Controller
{



    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function show($id)
    {
        $user=User::all();
        $user = User::findOrFail($id);
        return response()->json($user);
    }



public function update(Request $request, $id)
{
    // تحقق من أن المستخدم الحالي هو نفسه صاحب الحساب
    if (Auth::id() !== (int) $id) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // تحقق من صحة البيانات المدخلة (بشكل اختياري لكل حقل)
    $validated = $request->validate([
        'user_name' => 'sometimes|required|string|max:255',
        'email'     => 'sometimes|required|email|max:255',
        'password'  => 'sometimes|nullable|string|min:8',
        'dark_mode' => 'sometimes|boolean',
        'language'  => 'sometimes|required|in:ar,en',
    ]);

    // جلب المستخدم
    $user = User::findOrFail($id);

    // تشفير كلمة المرور إن وُجدت
    if (isset($validated['password'])) {
        $validated['password'] = bcrypt($validated['password']);
    }

    // تحديث البيانات المدخلة فقط
    $user->update($validated);

    return response()->json([
        'message' => 'User updated successfully',
        'user'    => $user
    ]);
}




    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}

