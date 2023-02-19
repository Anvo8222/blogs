<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\V1\Client\ClientAccountModel;
use App\Http\Requests\Client\Auth\RegisterRequest;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\RegisterMail;
use App\Models\V1\Client\SessionUserModel;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Client\Auth\ChangePasswordRequest;
use Carbon\Carbon;
use Mail;

class ClientAccountController extends Controller
{
  public function register(RegisterRequest $request)
  {
    $token = Str::random(20);
    $user = ClientAccountModel::create([
      'email' => $request->email,
      'name' => $request->name,
      'level' => 1,
      'password' => Hash::make($request->password),
      'token' => $token,
    ]);
    $data = [
      'name' => $request->name,
      'token' => $token,
      'userId' => $user->id,
    ];
    try {
      Mail::to($request->email)->send(new RegisterMail($data));
      return response()->json([
        'register_success' => 'Đăng ký thành công! Vui lòng kiểm tra Email để kích hoạt tài khoản!',
        'user' => $user
      ], 200);
    } catch (Exception $e) {
    }
  }
  public function active($id, $token)
  {
    $user = ClientAccountModel::where('id', $id)->first();
    if ($user) {
      if ($user->token == $token) {
        $user->status = 'active';
        if ($user->update()) {
          //delete token
          $user->token = null;
          $user->update();
          return response()->json([
            'active_success' => 'Kích hoạt tài khoản thành công! Vui lòng đăng nhập!',
          ], 201);
        }
      } else {
        return response()->json([
          'active_error' => 'Kích hoạt tài khoản không thành công! Vui lòng thử lại!',
        ], 400);
      }
    } else {
      return response()->json([
        'active_error' => 'Kích hoạt tài khoản không thành công! Vui lòng thử lại!',
      ], 400);
    }
  }

  public function login(Request $request)
  {
    // dd($request->all());
    $login = [
      'email' => $request->email,
      'password' => $request->password,
      'level' => 1,
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
          // 'data' => ["token" => $userSession->token, "expire_token" => $userSession->token_expired],
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
  }

  public function forgotPassword(Request $request)
  {
    $user = ClientAccountModel::where('email', $request->email)->first();

    if ($user) {
      if ($user->status == 'active') {
        $token = Str::random(20);
        $user->token = $token;
        if ($user->save()) {
          $data = [
            'name' => $user->name,
            'token' => $token,
            'userId' => $user->id
          ];

          try {
            Mail::to($request->email)->send(new ForgotPasswordMail($data));
            return response()->json([
              'forgot_success' => 'Xác nhận thành công! Vui lòng kiểm tra Email để lấy lại mật khẩu!',
            ], 201);
          } catch (Exception $th) {
            return response()->json([
              'forgot_error' => 'Lỗi không xác định, vui lòng thử lại!',
            ], 502);
          }
        }
      } else {
        return response()->json([
          'forgot_error' => 'Tài khoản chưa được kích hoạt, vào mail để kích hoạt tài khoản!',
        ], 403);
      }
    } else {
      return response()->json([
        'forgot_error' => 'Tài khoản không khớp trong hệ thống, vui lòng nhập lại!',
      ], 400);
    }
  }
  public function changePasswordForgot(ChangePasswordRequest $request, $id, $token)
  {
    $user = ClientAccountModel::where('id', $id)->first();
    if ($user) {
      if ($user->token == $token) {
        $user->password = Hash::make($request->password);
        if ($user->update()) {
          //delete token
          $user->token = null;
          $user->update();
          return response()->json([
            'change_success' => 'Thay đổi mật khẩu thành công! Vui lòng đăng nhập',
          ], 201);
        }
      } else {
        return response()->json([
          'change_error' => 'Có lỗi đã xảy ra, vui lòng thử lại!',
        ], 400);
      }
    } else {
      return response()->json([
        'change_error' => 'Có lỗi đã xảy ra, vui lòng thử lại!',
      ], 400);
    }
  }
  public function logout()
  {
    if (Auth::logout()) {
      return response()->json([
        'change_error' => 'logout thanh cong!',
      ], 204);
    } else {
      return response()->json([
        'change_error' => 'logout khong thanh cong!',
      ], 400);
    }
  }
}
