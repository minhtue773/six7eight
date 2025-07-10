<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\ResponseAPI;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    protected $response;
    public function __construct(ResponseAPI $responseAPI)
    {
        $this->middleware('auth:api');
        $this->response = $responseAPI;
    }

    public function index(Request $request)
    {
        $q = $request->input('q', '');
        $limit = $request->input('limit', 10);
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        $query = User::query();

        // Tìm kiếm theo tên, địa chỉ hoặc email
        if (!empty($q)) {
            $query->where(function ($query) use ($q) {
                $query->where('name', 'like', '%' . $q . '%')
                    ->orWhere('address', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%')
                    ->orWhere('phone_number', 'like', '%' . $q . '%');
            });
        }

        // Lọc theo status nếu có
        if ($request->filled('status')) {
            $status = $request->input('status');
            $status = is_string($status) ? explode(',', $status) : (array) $status;
            $query->whereIn('status', $status);
        }

        // Lọc theo role nếu có
        if ($request->filled('role')) {
            $role = $request->input('role');
            $role = is_string($role) ? explode(',', $role) : (array) $role;
            $query->whereIn('role', $role);
        }

        if ($request->filled('sort_by') && $request->filled('sort_order')) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }


        $users = $query->paginate($limit);

        $data = [
            'items' => $users->items(),
            'meta' => [
                'total' => $users->total(),
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'last_page' => $users->lastPage(),
            ]
        ];

        return $this->response->responseAPI(true, "Get data users successfully", $data, 200);
    }

    public function show(User $user)
    {
        return response()->json([
            'success' => true,
            'message' => 'Get data user successfully',
            'data' => $user
        ]);
    }

    public function store(UserRequest $request)
    {
        $data = $request->only([
            'name',
            'email',
            'password',
            'role',
            'status',
            'phone_number',
            'address',
            'gender',
            'birthday',
        ]);

        $data['password'] = Hash::make($request->input('password'));
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('avatars', $filename, 'public');
            $data['avatar'] = $path;
        }
        $user = User::create($data);
        return response()->json([
            'success' => true,
            'message' => "Created user successfully",
            'data' => $user
        ], 200);
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->only([
            'name',
            'role',
            'status',
            'phone_number',
            'address',
            'gender',
            'birthday',
        ]);
        // Nếu không có dữ liệu gửi lên, trả về lỗi
        if (empty($data) && !$request->hasFile('photo')) {
            return response()->json([
                'success' => false,
                'message' => 'No data provided for update'
            ], 400);
        }
        // Chỉ cập nhật password nếu có truyền lên
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        // Xử lý upload ảnh mới
        if ($request->hasFile('photo')) {
            if (!empty($user->avatar) && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('avatars', $filename, 'public');
            $data['avatar'] = $path;
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => "Updated user successfully"
        ], 200);
    }

    public function delete(User $user)
    {
        if (!empty($user->avatar) && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => "Deleted user successfully"
        ], 200);
    }

    public function destroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No user IDs provided'
            ], 400);
        }
        User::whereIn('id', $ids)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Deleted users successfully'
        ], 200);
    }

    public function forceDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No user IDs provided'
            ], 400);
        }
        $users = User::withTrashed()->whereIn('id', $ids)->get();
        foreach ($users as $user) {
            if (!empty($user->avatar) && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->forceDelete();
        }
        return response()->json([
            'success' => true,
            'message' => 'Force deleted users successfully'
        ], 200);
    }

    public function restore(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No user IDs provided'
            ], 400);
        }
        User::withTrashed()->whereIn('id', $ids)->restore();
        return response()->json([
            'success' => true,
            'message' => 'Restored users successfully'
        ], 200);
    }
}