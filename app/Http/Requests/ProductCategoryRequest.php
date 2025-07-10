<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductCategoryRequest extends FormRequest
{

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
        $id = $this->route('category')?->id;
        $isUpdate = $id ? true : false;
        return [
            'name' => ($isUpdate ? 'sometimes|required' : 'required') . '|string|unique:categories,name,' . ($id ?? ''),
            'photo' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,webp,gif',
            'is_featured' => 'nullable|integer',
            'is_hidden' => 'nullable|boolean',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Tên danh mục là bắt buộc.',
            'name.string' => 'Tên danh mục phải là chuỗi ký tự.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
            'is_featured.integer' => 'Nổi bật phải là số nguyên',
            'is_hidden.boolean' => 'Ẩn phải là true/false',
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