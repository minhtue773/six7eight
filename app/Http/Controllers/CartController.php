<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Services\ResponseAPI;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CartController extends Controller
{

    protected $response;
    public function __construct(ResponseAPI $responseAPI)
    {
        $this->middleware('auth:api');
        $this->response = $responseAPI;
    }

    public function index()
    {
        $cart = auth()->user()->cart()->with('items.variant.product')->first();

        if (!$cart) {
            return response()->json(['success' => true, 'items' => []]);
        }

        return $this->response->responseAPI(true, 'Get Cart successfully', [
            'items' => $cart->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product' => $item->variant->product->name ?? '',
                    'sku' => $item->variant->sku,
                    'price' => $item->variant->price,
                    'color' => $item->variant->color->name,
                    'size' => $item->variant->size->name,
                    'quantity' => $item->quantity,
                    'image' => optional($item->variant->product->mainImage)->image_url,
                ];
            })
        ], 200);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = auth()->user()->cart()->firstOrCreate([]);

        $item = $cart->items()->where('product_variant_id', $request->product_variant_id)->first();

        if ($item) {
            $item->increment('quantity', $request->quantity);
        } else {
            $cart->items()->create([
                'product_variant_id' => $request->product_variant_id,
                'quantity' => $request->quantity,
            ]);
        }

        return $this->response->responseAPI(true, 'Đã thêm vào giỏ hàng', [], 201);
    }

    public function update(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartItem::findOrFail($request->item_id);
        $item->update(['quantity' => $request->quantity]);

        return response()->json(['success' => true, 'message' => 'Đã cập nhật số lượng']);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id',
        ]);

        CartItem::findOrFail($request->item_id)->delete();

        return response()->json(['success' => true, 'message' => 'Đã xóa khỏi giỏ hàng']);
    }

    public function clear()
    {
        $cart = auth()->user()->cart;
        if ($cart) {
            $cart->items()->delete();
        }

        return response()->json(['success' => true, 'message' => 'Đã xóa toàn bộ giỏ hàng']);
    }

}