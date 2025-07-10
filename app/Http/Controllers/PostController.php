<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResouce;
use App\Models\Post;
use App\Services\ResponseAPI;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{
    protected $response;
    public function __construct(ResponseAPI $responseAPI)
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
        $this->response = $responseAPI;
    }

    public function index(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:255',
            'category_id' => 'nullable|integer|exists:post_categories,id',
            'user_id' => 'nullable|integer|exists:users,id',
            'sort_by' => 'nullable|in:created_at,title,view',
            'sort_order' => 'nullable|in:asc,desc',
            'limit' => 'nullable|integer|min:1'
        ]);

        $query = Post::with(['user', 'category']);

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->input('q') . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        // Sắp xếp theo view
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $per_page = $request->input('limit', 10);

        $posts = $query->paginate($per_page);

        $data = [
            'items' => PostResouce::collection($posts),
            'meta' => [
                'total' => $posts->total(),
                'current_page' => $posts->currentPage(),
                'per_page' => $posts->perPage(),
                'last_page' => $posts->lastPage(),
            ]
        ];

        return $this->response->responseAPI(true, 'Get data posts successfully', $data, 200);
    }

    public function show(Post $post)
    {
        return $this->response->responseAPI(true, 'Get post successfully', $post, 200);
    }

    public function store(PostRequest $request)
    {
        $data = $request->only([
            'category_id',
            'slug',
            'title',
            'description',
            'content',
            'status',
            'view'
        ]);
        $data['user_id'] = auth()->id();
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = uniqid('post_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('posts', $fileName, 'public');
            $data['image'] = 'posts/' . $fileName;
        }
        $post = Post::create($data);

        return $this->response->responseAPI(true, 'Created post successfully', $post, 201);
    }

    public function update(PostRequest $request, Post $post)
    {
        $data = $request->only([
            'category_id',
            'title',
            'slug',
            'description',
            'content',
            'status',
            'view'
        ]);
        $data['user_id'] = auth()->id();
        if ($request->hasFile('photo')) {
            if (!empty($post->image) && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }

            $file = $request->file('photo');
            $fileName = $fileName = uniqid('post_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('posts', $fileName, 'public');
            $data['image'] = 'posts/' . $fileName;
        }
        $post->update($data);

        return $this->response->responseAPI(true, 'Updated post successfully', null, 200);
    }

    public function delete(Post $post)
    {
        $post->delete();
        return $this->response->responseAPI(true, 'Moved to trash successfully', null, 200);
    }

    public function destroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return $this->response->responseAPI(false, 'No post ids provided', null, 400);
        }

        $posts = Post::whereIn('id', $ids)->get();
        foreach ($posts as $post) {
            $post->delete();
        }

        return $this->response->responseAPI(true, 'Moved posts to trash successfully', null, 200);
    }

    public function restore(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return $this->response->responseAPI(false, 'No post ids provided', null, 400);
        }

        $posts = Post::withTrashed()->whereIn('id', $ids)->get();
        foreach ($posts as $post) {
            $post->restore();
        }

        return $this->response->responseAPI(true, 'Restored posts successfully', null, 200);
    }

    public function forceDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return $this->response->responseAPI(false, 'No post ids provided', null, 400);
        }

        $posts = Post::withTrashed()->whereIn('id', $ids)->get();
        foreach ($posts as $post) {
            // Xóa ảnh nếu có
            if (!empty($post->image) && \Storage::disk('public')->exists($post->image)) {
                \Storage::disk('public')->delete($post->image);
            }
            $post->forceDelete();
        }

        return $this->response->responseAPI(true, 'Deleted posts successfully', null, 200);
    }
}