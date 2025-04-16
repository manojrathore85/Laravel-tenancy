<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $orders = Order::with('plan')->get();
            return response()->json($orders, 200);    
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'message' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
        // return response()->json($request);
        try {
            $order  = Order::create([
                'plan_id' => $request->plan_id,
                'name' => $request->name,
                'email' => $request->email,
                'domain' => $request->domain,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'password' => $request->password,
                'status' => 0,
                'payment_status' => 0,
            ]);
            return response()->json([
                'status', 'success',
                'message' => 'Order created successfully',
                'order' => $order,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'message' => $th->getMessage(),
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ids)
    {
        if (empty($ids)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Ids not found',
            ], 400);
        }
        try {
            $idsArray = explode(',', $ids);

            Order::destroy($idsArray);
            return response()->json([
                'status' => 'success',
                'message' => 'Orders Deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
        }
    }
    
    public function checkDomain(Request $request)
    {
        $request->validate([
            'domain' => 'required|string',
        ]);        
        $exists = DB::table('domains')
            ->where('domain', $request->domain.'.'.config('app.domain'))
            ->exists();
    
        return response()->json(['exists' => $exists]);
    }
}
