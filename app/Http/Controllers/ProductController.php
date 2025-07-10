<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\UploadImageService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\ResponseAPI;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    protected $response;
    protected $uploadService;

    public function __construct(ResponseAPI $responseAPI, UploadImageService $uploadImageService)
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
        $this->response = $responseAPI;
        $this->uploadService = $uploadImageService;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $query = Product::query();
        $query->with(['brand', 'category', 'variants', 'images']);


        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where('name', 'like', "%$q%");
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->whereIn('status', (array) $request->status);
        }

        if ($request->filled('gender')) {
            $query->whereIn('gender', (array) $request->gender);
        }

        $products = $query->paginate($limit);

        return $this->response->responseAPI(true, 'Fetched products successfully', [
            'items' => ProductResource::collection($products->items()),
            'meta' => [
                'total' => $products->total(),
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'last_page' => $products->lastPage(),
            ],
        ]);
    }


    public function show(Product $product)
    {
        $product->load(['brand', 'category', 'variants.size', 'variants.color', 'images']);
        return $this->response->responseAPI(true, 'Fetched product detail successfully', new ProductDetailResource($product));
    }

    public function store(ProductRequest $request)
    {
        \DB::beginTransaction();

        try {
            // 1. Tạo sản phẩm
            $product = Product::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,
                'gender' => $request->gender ?? 'unisex',
                'status' => $request->status ?? 1,
                'view' => $request->view ?? 0,
            ]);

            // 2. Upload ảnh chính
            if ($request->hasFile('main_image')) {
                $mainImagePath = $this->uploadService->upload($request->file('main_image'), 'products');
                $product->images()->create([
                    'image_url' => $mainImagePath,
                    'is_main' => true
                ]);
            }

            // 3. Upload các ảnh phụ (photos[]), có thể liên kết với màu
            if ($request->has('photos')) {
                foreach ($request->photos as $photoData) {
                    if (isset($photoData['image']) && $photoData['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $imagePath = $this->uploadService->upload($photoData['image'], 'products');
                        $product->images()->create([
                            'image_url' => $imagePath,
                            'color_id' => $photoData['color_id'] ?? null,
                            'is_main' => false,
                        ]);
                    }
                }
            }

            // 4. Lưu biến thể sản phẩm
            if ($request->has('variants')) {
                foreach ($request->variants as $variant) {
                    $product->variants()->create([
                        'size_id' => $variant['size_id'],
                        'color_id' => $variant['color_id'],
                        'price' => $variant['price'],
                        'stock' => $variant['stock'],
                        'sku' => $variant['sku'],
                    ]);
                }
            }

            \DB::commit();

            return $this->response->responseAPI(true, "Tạo sản phẩm thành công!", new ProductDetailResource($product), 201);

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->response->responseAPI(false, "Đã xảy ra lỗi khi tạo sản phẩm: " . $e->getMessage(), null, 500);
        }
    }

    public function update(ProductRequest $request, Product $product)
    {
        \DB::beginTransaction();

        try {
            // 1. Cập nhật thông tin sản phẩm
            $product->update([
                'name' => $request->input('name', $product->name),
                'slug' => $request->input('slug', $product->slug),
                'description' => $request->input('description', $product->description),
                'brand_id' => $request->input('brand_id', $product->brand_id),
                'category_id' => $request->input('category_id', $product->category_id),
                'gender' => $request->input('gender', $product->gender),
                'status' => $request->input('status', $product->status),
                'view' => $request->input('view', $product->view),
            ]);

            // 2. Cập nhật ảnh chính (nếu có)
            if ($request->hasFile('main_image')) {
                // Xóa ảnh chính cũ
                $oldMainImage = $product->images()->where('is_main', true)->first();
                if ($oldMainImage && \Storage::disk('public')->exists($oldMainImage->image_url)) {
                    \Storage::disk('public')->delete($oldMainImage->image_url);
                    $oldMainImage->delete();
                }

                // Upload ảnh mới
                $mainImagePath = $this->uploadService->upload($request->file('main_image'), 'products');
                $product->images()->create([
                    'image_url' => $mainImagePath,
                    'is_main' => true
                ]);
            }

            // 3. Cập nhật ảnh phụ (nếu có - ghi đè toàn bộ)
            if ($request->has('photos')) {
                // Xóa ảnh phụ cũ
                $oldPhotos = $product->images()->where('is_main', false)->get();
                foreach ($oldPhotos as $img) {
                    if (\Storage::disk('public')->exists($img->image_url)) {
                        \Storage::disk('public')->delete($img->image_url);
                    }
                    $img->delete();
                }

                // Upload ảnh phụ mới
                foreach ($request->photos as $photoData) {
                    if (isset($photoData['image']) && $photoData['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $imagePath = $this->uploadService->upload($photoData['image'], 'products');
                        $product->images()->create([
                            'image_url' => $imagePath,
                            'color_id' => $photoData['color_id'] ?? null,
                            'is_main' => false,
                        ]);
                    }
                }
            }

            // 4. Cập nhật các biến thể (xóa cũ → thêm mới)
            if ($request->has('variants')) {
                $product->variants()->delete();

                foreach ($request->variants as $variant) {
                    $product->variants()->create([
                        'size_id' => $variant['size_id'],
                        'color_id' => $variant['color_id'],
                        'price' => $variant['price'],
                        'stock' => $variant['stock'],
                        'sku' => $variant['sku'],
                    ]);
                }
            }

            \DB::commit();

            return $this->response->responseAPI(true, "Cập nhật sản phẩm thành công", new ProductDetailResource($product), 200);
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->response->responseAPI(false, "Lỗi khi cập nhật sản phẩm: " . $e->getMessage(), null, 500);
        }
    }

    public function delete(Product $product)
    {
        if ($product->trashed()) {
            return $this->response->responseAPI(false, 'Sản phẩm đã bị xóa trước đó.', null, 400);
        }

        $product->delete();

        return $this->response->responseAPI(true, 'Đã xóa mềm sản phẩm.', null, 200);
    }

    public function destroy(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return $this->response->responseAPI(false, 'Vui lòng cung cấp danh sách ID sản phẩm.', null, 422);
        }

        Product::whereIn('id', $ids)->delete();

        return $this->response->responseAPI(true, 'Đã xóa mềm các sản phẩm.', null, 200);
    }

    public function forceDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return $this->response->responseAPI(false, 'Vui lòng cung cấp danh sách ID sản phẩm.', null, 422);
        }

        $products = Product::withTrashed()->whereIn('id', $ids)->get();

        foreach ($products as $product) {
            // Xóa ảnh chính và phụ nếu có
            foreach ($product->images as $img) {
                if (\Storage::disk('public')->exists($img->image_url)) {
                    \Storage::disk('public')->delete($img->image_url);
                }
            }

            $product->forceDelete();
        }

        return $this->response->responseAPI(true, 'Đã xóa vĩnh viễn các sản phẩm.', null, 200);
    }

    public function restore(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return $this->response->responseAPI(false, 'Vui lòng cung cấp danh sách ID sản phẩm.', null, 422);
        }

        Product::withTrashed()->whereIn('id', $ids)->restore();

        return $this->response->responseAPI(true, 'Khôi phục sản phẩm thành công.', null, 200);
    }

}