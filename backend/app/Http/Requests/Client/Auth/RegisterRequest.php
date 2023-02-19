<?php

namespace App\Http\Requests\Client\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, mixed>
   */
  public function rules()
  {
    return [
      'name' => 'required|max:30',
      'email' => 'required|email|max:255|unique:users',
      'password' => 'required|min:5',
      'confirm_password' => 'required|same:password'
    ];
  }
  public function messages()
  {
    return [
      'name.required' => 'Tên không được để trống!',
      'email.required' => 'Email không được để trống!',
      'password.required' => 'Mật khẩu không được để trống!',
      'name.max' => 'Tên không được quá :max ký tự!',
      'email.max' => 'Email không được quá :max ký tự!',
      'password.min' => 'Mật khẩu không nhỏ hơn :min ký tự!',
      'email' => ':attribute không hợp lệ!',
      'unique' => 'Email tồn tại!',
      'confirm_password.same' => 'Mật khẩu không khớp!',
    ];
  }
}
