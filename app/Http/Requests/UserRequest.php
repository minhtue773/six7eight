<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
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
        $userId = $this->route('user') ? $this->route('user')->id : null;
        $isUpdate = $userId ? true : false;

        return [
            'email' => [
                $isUpdate ? 'sometimes|required' : 'required',
                'email',
                Rule::unique('users')->ignore($userId),
            ],
            'password' => $isUpdate ? 'sometimes|nullable|string|min:6' : 'required|string|min:6',
            'name' => $isUpdate ? 'sometimes|required|string|max:255' : 'required|string|max:255',
            'photo' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone_number' => [
                'sometimes',
                'nullable',
                'string',
                'max:20',
                'regex:/^(0|\+84)(3[2-9]|5[6|8|9]|7[0|6-9]|8[1-5|8|9]|9[0-9])[0-9]{7}$/'
            ],
            'address' => 'sometimes|nullable|string',
            'role' => $isUpdate ? 'sometimes|required|in:admin,customer' : 'required|in:admin,customer',
            'gender' => 'sometimes|nullable|in:male,female,other',
            'status' => $isUpdate ? 'sometimes|required|integer|in:0,1,2' : 'required|integer|in:0,1,2',
            'birthday' => 'sometimes|nullable|date',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Vui lòng cung cấp địa chỉ email của bạn.',
            'email.email' => 'Định dạng email không hợp lệ. Hãy kiểm tra lại.',
            'email.unique' => 'Email này đã được đăng ký. Vui lòng sử dụng email khác.',

            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu cần ít nhất :min ký tự để đảm bảo an toàn.',

            'name.required' => 'Vui long nhập tên của bạn.',
            'name.max' => 'Tên không được vượt quá :max ký tự. Hãy rút ngắn lại.',

            'photo.image' => 'Vui lòng tải lên một tệp hình ảnh hợp lệ.',
            'photo.mimes' => 'Chỉ chấp nhận các định dạng: jpeg, png, jpg, gif.',
            'photo.max' => 'Kích thước hình ảnh không được vượt quá :max KB.',

            'phone_number.max' => 'Số điện thoại quá dài. Vui lòng nhập tối đa :max ký tự.',
            'phone_number.regex' => 'Số điện thoại không đúng định dạng Việt Nam.',

            'role.required' => 'Vui lòng chọn vai trò của người dùng.',
            'role.in' => 'Vai trò không hợp lệ. Vui lòng chọn lại.',

            'status.required' => 'Vui lòng chọn trạng thái tài khoản.',
            'status.in' => 'Trạng thái tài khoản không hợp lệ.',

            'birthday.date' => 'Ngày sinh không đúng định dạng. Hãy sử dụng định dạng ngày tháng hợp lệ.',
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