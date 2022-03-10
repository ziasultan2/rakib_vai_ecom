<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Laravue\Models\Order;
use App\Laravue\Models\OrderItem;
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
        $order = Order::create([
            'user_id' => Auth::user()->id,
            'customer_name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'discount' => $request->discount,
            'total' => $request->total,
            'due' => $request->due,
            'paid' => $request->paid,
            'status' => $request->due > 0 ? 'due' : 'paid',
        ]);
        foreach ($request['list'] as $key => $value) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $value['product']['id'],
                'price' => $value['product']['price'],
                'quantity' => $value['quantity'],
                'discount' => $value['discount'],
                'total' => $value['total'],
                'paid' => $value['paid']
            ]);
        }
        return response()->json(['success' => true]);
    }
    public function update(Request $request, $id)
    {
        //
    }
}
