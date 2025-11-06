<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;




class AuthController extends Controller
{

    // app/Http/Controllers/AuthController.php

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
        ]);

        $otp = random_int(100000, 999999);

        DB::table('email_otps')->updateOrInsert(
            ['email' => $request->email],
            ['otp' => $otp, 'expires_at' => now()->addMinutes(10)]
        );

        Mail::to($request->email)->send(new SendOtpMail($otp));

        return response()->json([
            'message' => 'تم إرسال رمز التحقق إلى بريدك الإلكتروني.',
            'email' => $request->email
        ], 200);
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = $request->user();
        $token = $user->createToken('authToken')->plainTextToken;
        $user->refresh();
        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->user_name,
                'email' => $user->email,
                'role' => $user->role,
                'darK_mode' => $user->dark_mode,
                'language' => $user->language
            ]
        ]);
    }
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'user_name' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,publisher,student',
            'otp' => 'required|string'
        ]);

        $record = DB::table('email_otps')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return response()->json(['message' => 'رمز التحقق غير صالح أو منتهي الصلاحية'], 422);
        }

        $user = User::create([
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        DB::table('email_otps')->where('email', $request->email)->delete();

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'token' => $token,
            'message' => 'تم التحقق من البريد وإنشاء الحساب بنجاح.',
            'user' => $user,
            'dark_mode' => $user->dark_mode,
            'language' => $user->language
        ], 201);
    }


    public function user(Request $request)
    {
        return response()->json($request->user());
    }


    public function logout(Request $request)
    {
        try {
            // حذف التوكن الحالي فقط
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'تم تسجيل الخروج بنجاح',
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'فشل تسجيل الخروج',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
