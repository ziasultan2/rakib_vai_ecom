<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Laravue\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $searchParams = $request->all();
        $orderQuery = Order::query();
        $limit = Arr::get($searchParams, 'limit');
        $keyword = Arr::get($searchParams, 'keyword', '');
        if(!empty($keyword)){
            $orderQuery->orWhere('name', 'LIKE', '%'.$keyword.'%');
        }
        return OrderResource::collection($orderQuery->orderBy('id', 'desc')->paginate($limit));
    }

    public function store(Request $request)
    {
        return Order::create([
            'user_id' => Auth::user()->id,
            'customer_name' => $request->customer_name,
            'phone' => $request->phone,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'discount' => $request->discount,
            'total' => $request->total,
            'address' => $request->address,
            'due' => $request->due,
            'status' => $request->due ? 'pending' : 'paid',
        ]);
    }
    public function update(Request $request, $id)
    {
        //
    }
}
