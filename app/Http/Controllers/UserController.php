<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class UserController extends Controller
{
    public $apikey;
    
    // عرض جميع المستخدمين
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // عرض مستخدم واحد
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    // إنشاء مستخدم جديد
    
// public function store(Request $request)
// {
//     $data = $request->validate([
//         'user_name' => 'required|string|max:150',
//         'email' => 'required|email|max:100|unique:users,email',
//         'address' => 'nullable|string|max:200',
//         'role' => ['required', Rule::in(['student', 'publisher', 'admin'])],
//         'password' => 'required|string|min:6',
//         'language' => 'required|string|max:10',
//         'dark_mode' => 'boolean',
//     ]);

//     $data['password'] = Hash::make($data['password']);
//     $data['dark_mode'] = (bool) ($data['dark_mode'] ?? false);

//     $user = User::create($data);

//     return response()->json($user, 201);
// }

    // تحديث مستخدم
   
public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $data = $request->validate([
        'user_name' => 'sometimes|required|string|max:150',
        'email' => [
            'sometimes','required','email','max:100',
            Rule::unique('users', 'email')->ignore($user->id),
        ],
        'address' => 'sometimes|nullable|string|max:200',
        'role' => ['sometimes','required', Rule::in(['student', 'publisher', 'admin'])],
        'password' => 'sometimes|nullable|string|min:6',
        'language' => 'sometimes|required|string|max:10',
        'dark_mode' => 'sometimes|boolean',
    ]);

    if (array_key_exists('password', $data)) {
        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']); // لا تحدّث كلمة المرور إن كانت فارغة في الفورم
        }
    }

    $user->update($data);

    return response()->json($user);
}
    // حذف مستخدم
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
    
}
