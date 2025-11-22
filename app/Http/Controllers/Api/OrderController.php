<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function index(){
        $orders = auth()->user()->orders()->with('items.product')->get();
        return response()->json($orders);
    }

    public function store(Request $req){
        $data = $req->validate(['address'=>'required|string','phone'=>'required|string']);
        $user = auth()->user();
        $cart = $user->cart()->with('items.product')->first();
        if(!$cart || $cart->items->isEmpty()){
            return response()->json(['message'=>'Cart is empty'],400);
        }

        // check stock
        $insufficient = [];
        foreach($cart->items as $item){
            $product = $item->product;
            if($product->stock < $item->quantity){
                $insufficient[] = [
                    'product_id'=>$product->id,
                    'name'=>$product->name,
                    'available'=>$product->stock,
                    'requested'=>$item->quantity
                ];
            }
        }
        if(count($insufficient)){
            return response()->json(['message'=>'Insufficient stock for items','errors'=>$insufficient],422);
        }

        $orderResult = null;
        DB::transaction(function() use($user,$cart,$data,&$orderResult){
            $order = Order::create([
                'order_number'=>'ORD-'.time().'-'.Str::random(4),
                'user_id'=>$user->id,
                'address'=>$data['address'],
                'phone'=>$data['phone'],
                'total'=>0
            ]);
            $total = 0;
            $itemsSummary = [];
            foreach($cart->items as $item){
                $product = $item->product;
                $subtotal = $product->price * $item->quantity;
                OrderItem::create([
                    'order_id'=>$order->id,
                    'product_id'=>$product->id,
                    'quantity'=>$item->quantity,
                    'price'=>$product->price
                ]);
                // decrease stock
                $product->stock -= $item->quantity;
                $product->out_of_stock = $product->stock == 0;
                $product->save();

                $total += $subtotal;
                $itemsSummary[] = [
                    'product_id'=>$product->id,
                    'name'=>$product->name,
                    'quantity'=>$item->quantity,
                    'price'=>number_format($product->price,2),
                    'subtotal'=>number_format($subtotal,2)
                ];
            }
            $order->total = $total;
            $order->save();
            // clear cart
            $cart->items()->delete();

            $orderResult = [
                'order_number'=>$order->order_number,
                'total'=>number_format($total,2),
                'items'=>$itemsSummary
            ];
        });

        return response()->json($orderResult,201);
    }

    public function show($id){
        $order = auth()->user()->orders()->with('items.product')->where('id',$id)->firstOrFail();
        return response()->json($order);
    }
}
