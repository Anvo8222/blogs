<?php

namespace App\Http\Controllers\Api\V1\Backend;

use App\Http\Controllers\Controller;
use App\Models\V1\Backend\SessionUserModel;
use App\Models\V1\Backend\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;


class Authen extends Controller
{

  public function loginAdmin(Request $request)
  {
    $login = [
      'email' => $request->email,
      'password' => $request->password,
      'level' => 0,
    ];
    if (Auth::attempt($login)) {
      $user = Auth::user();
      if ($user->status === 'active') {
        $checkToken = SessionUserModel::where('user_id', Auth::id())->first();
        if (empty($checkToken)) {
          $userSession = SessionUserModel::create([
            'token' => Str::random(200),
            'refresh_token' => Str::random(200),
            'token_expired' => date("Y-m-d", strtotime('+ 7 days')),
            'refresh_token_expired' => date("Y-m-d", strtotime('+ 360 days')),
            'user_id' => Auth::id(),
          ]);
        } else {
          // kiểm tra token còn hạn hay không còn thì:
          if ($checkToken->token_expired > Carbon::now()) {
            $userSession = $checkToken;
          } else {
            // cập nhật lại token
            $checkToken->update([
              'token' => Str::random(200),
              'refresh_token' => Str::random(200),
              'token_expired' => date("Y-m-d", strtotime('+ 7 days')),
              'refresh_token_expired' => date("Y-m-d", strtotime('+ 360 days')),
            ]);
            // lấy thông tin sau khi update
            $userSession = SessionUserModel::where('user_id', Auth::id())->first();
          }
        }
        return response()->json([
          'code' => 200,
          'user' => ["name" => $user->name, "level" => $user->level, "status" => $user->status, "email" => $user->email, "avatar" => $user->avatar, "token" => $userSession->token, "expire_token" => $userSession->token_expired],
          'login_success' => 'Đăng nhập thành công!',
        ], 200);
      } else {
        return response()->json([
          'code' => 401,
          'login_error' => 'Vui lòng kích hoạt tài khoản!',
        ], 401);
      }
    } else {
      return response()->json([
        'code' => 401,
        'login_error' => 'Tài khoản hoặc mật khẩu không chính xác!',
      ], 401);
    }
    dd($request->all());
  }
  public function checkLevelUser(Request $request)
  {
    $token = $request->bearerToken();
    $levelUser = UserModel::whereHas('session_users', function ($query) use ($token) {
      $query->where('session_users.token', '=', $token);
    })->first(['level']);

    if ($levelUser) {
      return response()->json([
        'level' => $levelUser->level,
      ], 200);
    } else {
      return response()->json([
        'error' => "Lỗi!",
      ], 401);
    }
  }
}
