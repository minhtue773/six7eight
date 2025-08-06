<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Str;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'refresh', 'verifyEmail']]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'name.max' => 'Họ tên không được vượt quá 255 ký tự.',

            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'email.unique' => 'Email này đã được đăng ký.',

            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
        $data['status'] = 0;
        $data['role'] = 'customer';
        $data['verify_token'] = Str::random(64);
        $user = User::create($data);
        // Tạo đường dẫn xác minh (giả sử đang xác minh bằng ID)
        $verifyUrl = '/verify-email?token=' . $user->verify_token;
        // Gửi mail
        Mail::to($user->email)->send(new VerifyEmail($user, $verifyUrl));

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký thành công!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản không tồn tại.'
            ], 404);
        }

        if ($user->status == 2) {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản đã bị khóa. Vui lòng liên hệ quản trị viên.'
            ], 403);
        }

        if ($user->status == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản của bạn chưa được kích hoạt. Vui lòng kiểm tra email hoặc liên hệ hỗ trợ.'
            ], 403);
        }

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email hoặc mật khẩu không đúng.'
            ], 422);
        }

        return $this->respondWithToken($token);
    }


    public function profile()
    {
        return response()->json([
            'success' => true,
            'user' => auth()->user()
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công.'
        ]);
    }

    // public function refresh()
    // {
    //     try {
    //         $newToken = auth()->refresh();
    //         return $this->respondWithToken($newToken);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Làm mới token thất bại.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function update(UserRequest $request, User $user)
    {
        if (auth()->id() !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Không có quyền.'], 403);
        }

        $data = $request->only([
            'name',
            'phone_number',
            'address',
            'gender',
            'birthday'
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        if ($request->hasFile('photo')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['avatar'] = $file->storeAs('avatars', $filename, 'public');
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành công!',
            'user' => $user
        ]);
    }

    public function delete(User $user)
    {
        if (auth()->id() !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Không có quyền.'], 403);
        }

        $user->delete();
        auth()->logout();

        return response()->json([
            'success' => true,
            'message' => 'Xóa người dùng thành công!'
        ]);
    }

    protected function respondWithToken($token)
    {
        $user = auth()->user();

        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'role' => $user->role,
                'avatar' => $user->avatar,
                'status' => $user->status,
            ]
        ]);
    }

    public function verifyEmail(Request $request)
    {
        $token = $request->query('token');
        $user = User::where('verify_token', $token)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Token không hợp lệ.'], 400);
        }

        $user->email_verified_at = now();
        $user->verify_token = null; // Vô hiệu hóa token
        $user->status = 1; // Cập nhật trạng thái nếu muốn
        $user->save();

        return response()->json(['success' => true, 'message' => 'Xác minh email thành công.']);
    }

}