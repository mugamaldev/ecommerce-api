<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;

class CartController extends Controller
{
    public function index(){
        $cart = auth()->user()->cart()->with('items.product')->first();
        return response()->json($cart);
    }

    public function add(Request $req){
        $data = $req->validate(['product_id'=>'required|exists:products,id','quantity'=>'required|integer|min:1']);
        $cart = auth()->user()->cart;
        $product = Product::findOrFail($data['product_id']);
        if($product->stock < $data['quantity']){
            return response()->json(['message'=>'Insufficient stock','available'=>$product->stock],422);
        }
        // if exists, update quantity
        $item = $cart->items()->where('product_id',$product->id)->first();
        if($item){
            $item->quantity += $data['quantity'];
            $item->price = $product->price;
            $item->save();
        } else {
            $cart->items()->create([
                'product_id'=>$product->id,
                'quantity'=>$data['quantity'],
                'price'=>$product->price
            ]);
        }
        return response()->json(['message'=>'Added to cart']);
    }

    public function remove(Request $req, $itemId){
        $cart = auth()->user()->cart;
        $item = $cart->items()->where('id',$itemId)->firstOrFail();
        $item->delete();
        return response()->json(['message'=>'Removed']);
    }

    public function clear(){
        $cart = auth()->user()->cart;
        $cart->items()->delete();
        return response()->json(['message'=>'Cart cleared']);
    }
}
