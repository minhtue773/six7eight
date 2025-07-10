<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('post')?->id;
        $isUpdate = $id ? true : false;

        return [
            'category_id' => $isUpdate ? 'sometimes|required|integer|exists:post_categories,id' : 'required|integer|exists:post_categories,id',
            'title' => $isUpdate ? 'sometimes|required|string|max:255' : 'required|string|max:255',
            'slug' => $isUpdate ? 'sometimes|required|string|max:255|unique:posts,slug,' . $id : 'required|string|max:255|unique:posts,slug',
            'description' => 'nullable|string',
            'content' => $isUpdate ? 'sometimes|required|string' : 'required|string',
            'photo' => $isUpdate ? 'sometimes|nullable|image|max:2048|mimes:jpeg,png,jpg,gif,webp' : 'required|image|max:2048|mimes:jpeg,png,jpg,gif,webp',
            'status' => $isUpdate ? 'sometimes|required|in:0,1' : 'required|in:0,1', // 0: công khai, 1: riêng tư
            'view' => 'nullable|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'Danh mục bài viết là bắt buộc.',
            'category_id.integer' => 'Danh mục bài viết không hợp lệ.',
            'category_id.exists' => 'Danh mục bài viết không tồn tại.',
            'title.required' => 'Tiêu đề bài viết là bắt buộc.',
            'title.string' => 'Tiêu đề bài viết phải là chuỗi ký tự.',
            'title.max' => 'Tiêu đề bài viết không được vượt quá 255 ký tự.',
            'content.required' => 'Nội dung bài viết là bắt buộc.',
            'photo.required' => 'Ảnh bài viết là bắt buộc.',
            'photo.image' => 'File tải lên phải là ảnh.',
            'photo.max' => 'Ảnh không được vượt quá 2MB.',
            'photo.mimes' => 'Chỉ chấp nhận các định dạng: jpeg, png, jpg, gif, webp.',
            'status.required' => 'Trạng thái bài viết là bắt buộc.',
            'status.in' => 'Trạng thái bài viết không hợp lệ.',
            'view.integer' => 'Lượt xem phải là số nguyên.',
            'view.min' => 'Lượt xem không được nhỏ hơn 0.',
            'slug.required' => 'Slug bài viết là bắt buộc',
            'slug.unique' => 'Slug bài viết này đã tồn tại',
        ];
    }
}