<?php

namespace App\Http\Requests\Client\Post;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'name' => 'required|max:30|unique:posts',
            'nameAuthor' => 'required|max:30',
            'categories' => 'required|array',
            'categories*' => 'required', 'integer', 'exists:categories,id',
            'image' => 'image|mimes:jpeg,png,jpg|max:500',
            'type' => 'integer|between:0,2',
            'is_done' => 'integer|between:0,2',
            'description' => 'max:1000',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Tên bài viết không được để trống!',
            'name.max' => 'Tên bài viết không được vượt quá 30 ký tự!',
            'name.unique' => 'Tên bài viết đã bị trùng!',

            'nameAuthor.required' => 'Tên tác giả không được để trống!',
            'nameAuthor.max' => 'Tên tác giả không được vượt quá 30 ký tự!',

            'categories.required' => "Danh mục rỗng!",
            'categories.array' => "Danh ít nhất 1 ",

            'image.max' => 'Kích thước nhỏ hơn 500kb',
            'image.required' => 'Ảnh không được để trống!',
            'image.mimes' => 'Ảnh phải là (jpeg,png,jpg,gif)',
            'mimes' => 'Supported file format for :attribute are :mimes',

            'type.integer' => 'Loại không hợp lệ!',
            'type.between' => 'Loại không hợp lệ!',

            'is_done.integer' => 'Tình trạng không hợp lệ!',
            'is_done.between' => 'Tình trạng không hợp lệ!',

            'description.max' => 'Mô tả không được quá :max ký tự!',
        ];
    }
}
