<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use App\User;
use App\Cart;
use App\Food;
use App\Vendor;
use App\Offer;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\OrderTableController;

class OrderController extends Controller
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
    public function store(Request $request)
    {
      if(auth()->user()->type == "vendor") {
        return redirect('home');
      }
        $this->validate($request, [
          'address' => 'required'
        ]);


        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        if (!Cart::where('customer_id', $user->customer->id)->exists()) {
          return redirect('\home');
        }

        $order = new Order;
        $order->address = $request->input('address');
        $order->price = $request->input('total');
        $order->customer_id = $user->customer->id;
        $order->save();

        $table = new OrderTableController;

        $table->store($order, $request);

        if(Session::has('offer')) {
          $offers = User::find(auth()->user()->id)->customer->offer;
          foreach ($offers as $offer) {
             if ($offer->id == Session::get('offer')) {
               $limit = $offer->pivot->limit;

               if ($limit != null) {
                 $limit = $limit + 1;
               } else {
                 $limit = 1;
               }
               User::find(auth()->user()->id)->customer->offer()->updateExistingPivot($offer->id, ['limit' => $limit]);
             }

            }
        }

        Session::forget('offer');
        Session::forget('discount');
        Session::forget('total');

        return view('vendor.order_complete');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      if(auth()->user()->type == "vendor") {
        return redirect('home');
      }

        $calorie = 0;
        $subtotal = 0;

        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        if (!Cart::where('customer_id', $user->customer->id)->exists()) {
          return redirect('\home');
        }

        $food = $user->customer->cart->first()->food;

        foreach ($user->customer->cart as $value) {
            $value->food = Food::find($value->food_id);
              if($value->food->vendor_id == $food->vendor_id) {
                $calorie += ($value->food->calorie) * $value->quantity;
                $subtotal += $value->price;
              }
        }

        $offers = Offer::with('vendor')->get();

        $user->customer->cart->calorie = $calorie;
        $user->customer->cart->subtotal = $subtotal;
        $user->customer->cart->total = $subtotal + 30;

        return view('customer.order')->with('customer', $user->customer)
                                     ->with('offers', $offers)
                                     ->with('vendor_id', $food->vendor_id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
