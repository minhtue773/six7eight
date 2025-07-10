<?php

namespace App\Http\Controllers;


use App\Models\PostCategory;
use App\Services\ResponseAPI;
use Illuminate\Routing\Controller;
use Request;

class PostCategoryController extends Controller
{
    protected $response;
    public function __construct(ResponseAPI $responseAPI)
    {
        $this->middleware('auth:api', ['except' => ['index']]);
        $this->response = $responseAPI;
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $sortBy = $request->input('sortBy', 'created_at');
        $sortOrder = $request->input('sortOrder', 'desc');

        $query = PostCategory::query();

        // Lọc theo tên (tìm kiếm)
        $query->when($request->filled('q'), function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->input('q') . '%');
        });
        // Lọc theo trạng thái
        $query->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->input('status'));
        });

        // Sắp xếp
        $query->orderBy($sortBy, $sortOrder);

        $categories = $query->paginate($limit);
        $data = [
            "items" => $categories->items(),
            'meta' => [
                'total' => $categories->total(),
                'current_page' => $categories->currentPage(),
                'per_page' => $categories->perPage(),
                'last_page' => $categories->lastPage(),
            ],
        ];

        return $this->response->responseAPI(true, 'Get data Catalog Blog successfully', $data, 200);
    }

    public function show(PostCategory $postCategory)
    {
        return $this->response->responseAPI(true, 'Get data Catalog successfully', ['item' => $postCategory], 200);
    }

    public function store(Request $request)
    {
        //
    }

    public function update(Request $request, PostCategory $postCategory)
    {
        //
    }


    public function destroy(PostCategory $postCategory)
    {
        //
    }
}