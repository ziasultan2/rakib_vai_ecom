<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Laravue\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $searchParams = $request->all();
        $productQuery = Product::query();
        $limit = Arr::get($searchParams, 'limit');
        $keyword = Arr::get($searchParams, 'keyword', '');
        if(!empty($keyword)){
            $productQuery->where('name', 'LIKE', '%'.$keyword.'%');
        }
        return ProductResource::collection($productQuery->orderBy('id', 'desc')->paginate($limit));
    }

    public function store(Request $request)
    {
        return Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'quantity' => $request->quantity,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->all());
        return $product;
    }

    public function destroy(Product $product)
    {
        return $product->delete();
    }
}
