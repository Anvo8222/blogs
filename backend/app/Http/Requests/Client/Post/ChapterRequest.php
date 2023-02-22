<?php

namespace App\Http\Requests\Client\Post;

use Illuminate\Foundation\Http\FormRequest;

class ChapterRequest extends FormRequest
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
            'chapterNumber' => 'required|integer',
            'title' => 'required|max:40',
            'content' => 'required|min:100',
        ];
    }
    public function messages()
    {
        return [
            'chapterNumber.required' => 'Số của chương không được để trống!',
            'chapterNumber.integer' => 'Số của chương phải là kiểu số!',
            'title.required' => 'Tiêu đề chương không được để trống!',
            'title.max' => 'Tiêu đề chương không được vượt quá :max ký tự!',
            'content.required' => 'Nội dung chương không được để trống!',
            'content.min' => 'Nội dung chương không được nhỏ hơn :min ký tự!',
        ];
    }
}
