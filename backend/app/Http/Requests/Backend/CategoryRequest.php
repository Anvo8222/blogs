<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'name' => 'required|max:30|unique:categories',
            'desription' => 'max:255',
            'slug' => 'required|max:40|unique:categories',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Tên không được để trống!',
            'name.max' => 'Tên không được quá :max ký tự!',
            'name.unique' => 'Tên đã tồn tại!',
            'desription.max' => 'Mô tả không được quá :max ký tự!',
            'slug.required' => 'Slug không được để trống!',
            'slug.max' => 'Slug không được quá :max ký tự!',
            'slug.unique' => 'Slug đã tồn tại!',
        ];
    }
}
