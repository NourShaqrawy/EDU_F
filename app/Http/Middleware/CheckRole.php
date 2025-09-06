<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
       
        if (!Auth::guard('sanctum')->check()) {
            return response()->json([
                'message' => 'غير مصرح بالوصول. يلزم تسجيل الدخول.'
            ], 401);
        }

      
        $user = Auth::guard('sanctum')->user();

        // 3. التحقق من الصلاحية
        if (!$this->checkUserRole($user, $role)) {
            return response()->json([
                'message' => 'ليس لديك الصلاحية للوصول إلى هذا المورد.'
            ], 403);
        }

        return $next($request);
    }

  protected function checkUserRole($user, $requiredRole)
{
    // السماح دائماً للمشرف
    if ($user->role === 'admin') {
        return true;
    }

    // تقسيم الأدوار المسموحة
    $roles = explode('|', $requiredRole);

    // التحقق من وجود دور المستخدم ضمن الأدوار المسموحة
    return in_array($user->role, $roles);
}

}