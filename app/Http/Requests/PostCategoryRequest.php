<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PostCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        $id = $this->route('category')?->id;
        $isUpdate = $id ? true : false;
        return [
            'name' => ($isUpdate ? 'sometimes|required' : 'required') . '|string|unique:post_categories,name,' . ($id ?? ''),
            'photo' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,webp,gif',
            'is_featured' => 'nullable|boolean',
            'status' => ($isUpdate ? 'sometimes|required' : 'required') . '|in:0,1',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Tên danh mục là bắt buộc.',
            'name.string' => 'Tên danh mục phải là chuỗi ký tự.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
            'is_featured.boolean' => 'Nổi bật phải là true/false',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'photo.image' => 'File tải lên phải là ảnh.',
            'photo.max' => 'Ảnh sản phẩm không được vượt quá 2MB.',
            'photo.mimes' => 'Chỉ chấp nhận các định dạng: jpeg, png, jpg, webp, gif.',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}