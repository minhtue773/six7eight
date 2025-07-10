<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('product')?->id;
        $isUpdate = $id ? true : false;

        return [
            // product table
            'name' => $isUpdate ? 'sometimes|required|string|max:255' : 'required|string|max:255',
            'description' => 'nullable|string',
            'brand_id' => $isUpdate ? 'sometimes|required|exists:brands,id' : 'required|exists:brands,id',
            'category_id' => $isUpdate ? 'sometimes|required|exists:categories,id' : 'required|exists:categories,id',
            'gender' => 'nullable|in:male,female,unisex',
            'status' => 'nullable|in:0,1',
            'view' => 'nullable|integer',
            // main image
            'main_image' => $isUpdate ? 'sometimes|nullable|image|max:2048|mimes:jpeg,png,jpg,gif,webp' : 'required|image|max:2048|mimes:jpeg,png,jpg,gif,webp',

            // additional images
            'images' => 'sometimes|nullable|array',
            'images.*.image' => 'required|image|max:2048|mimes:jpeg,png,jpg,gif,webp',
            'images.*.color_id' => 'nullable|exists:colors,id',
            'images.*.is_main' => 'nullable|boolean',

            // variants
            'variants' => $isUpdate ? 'sometimes|array' : 'required|array|min:1',
            'variants.*.size_id' => $isUpdate ? 'sometimes|required|exists:sizes,id' : 'required|exists:sizes,id',
            'variants.*.color_id' => $isUpdate ? 'sometimes|required|exists:colors,id' : 'required|exists:colors,id',
            'variants.*.price' => $isUpdate ? 'sometimes|required|numeric|min:0' : 'required|numeric|min:0',
            'variants.*.stock' => $isUpdate ? 'sometimes|required|integer|min:0' : 'required|integer|min:0',
            'variants.*.sku' => [
                $isUpdate ? 'sometimes|required' : 'required',
                'string',
                'distinct',
                'min:3',
                'unique:product_variants,sku',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'brand_id.required' => 'Vui lòng chọn thương hiệu.',
            'category_id.required' => 'Vui lòng chọn danh mục sản phẩm.',
            'gender.in' => 'Giới tính không hợp lệ (male, female, unisex).',
            'status.in' => 'Trạng thái không hợp lệ.',
            'view.integer' => 'Lượt xem phải là số',

            'main_image.required' => 'Ảnh chính là bắt buộc.',
            'main_image.image' => 'Ảnh chính phải là file hình ảnh.',
            'main_image.max' => 'Ảnh chính không được vượt quá 2MB.',

            'images.array' => 'Danh sách ảnh chi tiết phải là mảng.',
            'images.*.image.required' => 'Mỗi ảnh chi tiết là bắt buộc.',
            'images.*.image.image' => 'Tệp ảnh không hợp lệ.',
            'images.*.image.max' => 'Mỗi ảnh chi tiết không được quá 2MB.',
            'images.*.color_id.exists' => 'Màu không tồn tại.',
            'images.*.is_main.boolean' => 'Trường is_main phải là true hoặc false.',

            'variants.required' => 'Vui lòng thêm ít nhất một biến thể.',
            'variants.*.size_id.required' => 'Biến thể cần size.',
            'variants.*.size_id.exists' => 'Size không tồn tại.',
            'variants.*.color_id.required' => 'Biến thể cần màu.',
            'variants.*.color_id.exists' => 'Màu không tồn tại.',
            'variants.*.price.required' => 'Biến thể cần giá.',
            'variants.*.price.numeric' => 'Giá phải là số.',
            'variants.*.price.min' => 'Giá phải ≥ 0.',
            'variants.*.stock.required' => 'Biến thể cần tồn kho.',
            'variants.*.stock.integer' => 'Tồn kho phải là số nguyên.',
            'variants.*.stock.min' => 'Tồn kho phải ≥ 0.',
            'variants.*.sku.required' => 'Biến thể cần mã SKU.',
            'variants.*.sku.string' => 'SKU phải là chuỗi.',
            'variants.*.sku.min' => 'SKU tối thiểu 3 ký tự.',
            'variants.*.sku.distinct' => 'Mỗi SKU phải duy nhất trong danh sách.',
            'variants.*.sku.unique' => 'SKU này đã tồn tại trong hệ thống.',
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