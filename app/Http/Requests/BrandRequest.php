<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->route('brand') ? true : false;

        return [
            'name' => $isUpdate ? 'sometimes|required|string|max:255' : 'required|string|max:255',
            'is_featured' => 'nullable|integer|in:0,1',
            'is_hidden' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên thương hiệu là bắt buộc.',
            'name.max' => 'Tên không được vượt quá :max ký tự.',
            'is_featured.in' => 'Trường nổi bật chỉ được nhận 0 hoặc 1.',
            'is_hidden.boolean' => 'Trường ẩn phải là true/false hoặc 0/1.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422));
    }
}