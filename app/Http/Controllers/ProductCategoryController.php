<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCategoryRequest;
use App\Http\Resources\ProductCategoryResource;
use App\Models\ProductCategory;
use App\Services\ResponseAPI;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class ProductCategoryController extends Controller
{
    protected $response;
    public function __construct(ResponseAPI $responseAPI)
    {
        $this->middleware('auth:api', ['except' => ['index']]);
        $this->response = $responseAPI;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit');
        // Danh sách các cột cho phép sắp xếp
        $sortableFields = ['name', 'is_featured', 'created_at', 'updated_at'];
        $sortBy = in_array($request->input('sort_by'), $sortableFields) ? $request->input('sort_by') : 'created_at';
        $sortOrder = $request->input('sort_order', 'desc');
        // Bắt đầu query
        $query = ProductCategory::query();
        // Lọc theo tên (tìm kiếm)
        $query->when($request->filled('q'), function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->input('q') . '%');
        });

        if (!auth()->check() || auth()->user()->role !== 'admin') {
            $query->where('is_hidden', 0);
        }

        // Sắp xếp
        $query->orderBy($sortBy, $sortOrder);
        $query->with('products');
        // Phân trang kết quả
        $categories = $query->paginate($limit);


        // Tạo cấu trúc dữ liệu trả về
        $data = [
            'items' => ProductCategoryResource::collection($categories),
            'meta' => [
                'total' => $categories->total(),
                'current_page' => $categories->currentPage(),
                'per_page' => $categories->perPage(),
                'last_page' => $categories->lastPage(),
            ],
        ];
        // Trả về response chuẩn
        return $this->response->responseAPI(
            true,
            'Get product categories successfully',
            $data,
            200
        );
    }

    public function show(ProductCategory $category)
    {
        $data = new ProductCategoryResource($category);
        return $this->response->responseAPI(true, 'Get product-catalog successfully', ['item' => $data], 200);
    }

    public function store(ProductCategoryRequest $request)
    {
        $data = $request->only([
            "name",
            "is_featured",
            "is_hidden"
        ]);
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = uniqid('category_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('categories', $filename, 'public');
            $data['thumbnail'] = 'categories/' . $filename;
        }
        $category = ProductCategory::create($data);
        return $this->response->responseAPI(true, 'Created product catalog successfully', $category, 201);
    }

    public function update(ProductCategoryRequest $request, ProductCategory $category)
    {
        $data = $request->only([
            "name",
            "is_featured",
            "is_hidden"
        ]);
        if ($request->hasFile('photo')) {
            if (!empty($category->thumbnail) && Storage::disk('public')->exists($category->thumbnail)) {
                Storage::disk('public')->delete($category->thumbnail);
            }

            $file = $request->file('photo');
            $filename = uniqid('category_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('categories', $filename, 'public');
            $data['thumbnail'] = 'categories/' . $filename;
        }

        $category->update($data);

        return $this->response->responseAPI(true, 'Updated product catalog successfully', null, 200);
    }

    public function delete(ProductCategory $category)
    {
        if ($category->products()->count() > 0) {
            return $this->response->responseAPI(false, 'Không thể xóa danh mục vì vẫn còn sản phẩm liên quan.', null, 409);
        }
        $category->delete();
        return $this->response->responseAPI(true, 'Deleted product catalog successfully', null, 200);
    }
}