<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;


class ProductController extends Controller
{
     public function index(){ return response()->json(Product::all()); }
    public function store(Request $req){
        $data = $req->validate([
            'name'=>'required|string',
            'description'=>'nullable|string',
            'price'=>'required|numeric|min:0',
            'stock'=>'required|integer|min:0'
        ]);
        $product = Product::create($data);
        return response()->json($product,201);
    }
    public function show($id){
        $p = Product::findOrFail($id);
        return response()->json($p);
    }
    public function update(Request $req,$id){
        $p = Product::findOrFail($id);
        $data = $req->validate([
            'name'=>'sometimes|required|string',
            'description'=>'nullable|string',
            'price'=>'sometimes|required|numeric|min:0',
            'stock'=>'sometimes|required|integer|min:0'
        ]);
        $p->update($data);
        return response()->json($p);
    }
    public function destroy($id){
        Product::findOrFail($id)->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
