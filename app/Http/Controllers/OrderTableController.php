<?php

namespace App\Http\Controllers;

use App\OrderTable;
use Illuminate\Http\Request;
use App\User;
use App\Food;

class OrderTableController extends Controller
{

  public function __construct()
  {
      $this->middleware('auth');
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($data, $request)
    {
      if(auth()->user()->type == "vendor") {
        return redirect('home');
      }

        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $cart = $user->customer->cart;

        foreach ($cart as $value) {
            $value->food = Food::find($value->food_id);

            if($value->food->vendor_id == $request->input('vendor_id')) {

              $order = new OrderTable;

              $order->food_id = $value->food_id;
              $order->order_id = $data->id;
              $order->quantity = $value->quantity;
              $order->originalprice = ($value->food->price)*($value->quantity);
              $order->save();

              $value->delete();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OrderTable  $orderTable
     * @return \Illuminate\Http\Response
     */
    public function show(OrderTable $orderTable)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OrderTable  $orderTable
     * @return \Illuminate\Http\Response
     */
    public function edit(OrderTable $orderTable)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OrderTable  $orderTable
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrderTable $orderTable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OrderTable  $orderTable
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderTable $orderTable)
    {
        //
    }
}
