<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use App\Services\ResponseAPI;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BrandController extends Controller
{
    protected $response;
    public function __construct(ResponseAPI $responseAPI)
    {
        $this->middleware('auth:api', ['except' => ['index']]);
        $this->response = $responseAPI;
    }

    public function index(Request $request)
    {
        $q = $request->input('q');
        $limit = $request->input('limit', 10);
        $sortBy = in_array($request->input('sort_by'), ['name', 'created_at']) ? $request->input('sort_by') : 'created_at';
        $sortOrder = $request->input('sort_order', 'desc');

        $query = Brand::query();

        if ($q) {
            $query->where('name', 'like', '%' . $q . '%');
        }

        if (!auth()->check() || auth()->user()->role !== 'admin') {
            $query->where('is_hidden', 0);
        }

        $query->orderBy($sortBy, $sortOrder);
        $brands = $query->paginate($limit);

        $data = [
            'items' => $brands->items(),
            'meta' => [
                'total' => $brands->total(),
                'current_page' => $brands->currentPage(),
                'per_page' => $brands->perPage(),
                'last_page' => $brands->lastPage(),
            ]
        ];

        return $this->response->responseAPI(true, 'Get brands successfully', $data, 200);
    }

    public function show(Brand $brand)
    {
        return $this->response->responseAPI(true, 'Get brand detail successfully', ['item' => $brand], 200);
    }

    public function store(BrandRequest $request)
    {
        $data = $request->only(['name', 'is_hidden', 'is_featured']);
        $brand = Brand::create($data);

        return $this->response->responseAPI(true, 'Created brand successfully', $brand, 201);
    }

    public function update(BrandRequest $request, Brand $brand)
    {
        $data = $request->only(['name', 'is_hidden', 'is_featured']);
        $brand->update($data);

        return $this->response->responseAPI(true, 'Updated brand successfully', $brand, 200);
    }

    public function delete(Brand $brand)
    {
        $brand->delete();
        return $this->response->responseAPI(true, 'Deleted brand successfully', null, 200);
    }
}