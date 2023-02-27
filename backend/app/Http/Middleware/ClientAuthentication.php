<?php

namespace App\Http\Middleware;

use App\Models\V1\Backend\UserModel;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class ClientAuthentication
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
   * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
   */
  public function handle(Request $request, Closure $next)
  {
    $token = $request->bearerToken();
    $user = UserModel::whereHas('session_users', function ($query) use ($token) {
      $query->where('session_users.token', '=', $token);
    })->first();

    if ($user) {
      if ($user->session_users->token_expired > Carbon::now()) {
        if ($user->level === 1 && $user->status === "active") {
          return $next($request);
        } else {
          return response()->json([
            'status-code' => 403,
            'errors' => "Không có quyền truy cập!"
          ], 403);
        }
      } else {
        return response()->json([
          'status-code' => 401,
          'errors' => "Vui lòng đăng nhập lại!"
        ], 401);
      }
    } else {
      return response()->json([
        'status-code' => 403,
        'errors' => "Truy cập không hợp lệ!"
      ], 403);
    }
  }
}
