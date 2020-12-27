<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function all() {
        $products = Product::all();

        return response() -> json($products);
    }

    public function get($id) {
        $product = Product::find($id);
        return response($status=200) -> json($product);
    }

    public function create(Request $request) {
        $product = new Product();

        $product->name = $request->name;
        $product->price = $request->price;
        $product->category = $request->category;

        $product->save();

        return response($status=201)->json('');
    }

    public function update(Request $request, $id){
        $product = Product::find($id);

        $product->name = $request->name;
        $product->price = $request->price;
        $product->category = $request->category;

        $product->update();

        return response($status=200)->json($product);
    }

    public function delete($id) {
        $product = Product::find($id);

        $product->delete();

        return response($status=204)->json('');
    }


}
