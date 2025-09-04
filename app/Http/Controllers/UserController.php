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
        $user = User::findOrFail($id);
        return response()->json($user);
    }



public function update(Request $request, $id)
{
    // تحقق من أن المستخدم الحالي هو نفسه صاحب الحساب
    if (Auth::id() !== (int) $id) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // تحقق من صحة البيانات المدخلة
    $validated = $request->validate([
        'user_name' => 'required|string|max:255',
        'email'     => 'required|email|max:255',
        'password'  => 'nullable|string|min:8',
        'dark_mode' => 'nullable|boolean',
        'language'  => 'required|in:ar,en',
    ]);

    // جلب المستخدم
    $user = User::findOrFail($id);

    // تحديث البيانات (مع تشفير كلمة المرور إن وُجدت)
    if (isset($validated['password'])) {
        $validated['password'] = bcrypt($validated['password']);
    }

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

