<?php

namespace App\Http\Controllers;

use App\Cart;
use Illuminate\Http\Request;
use App\Food;
use App\User;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
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
      if(auth()->user()->type == "vendor") {
        return redirect('home');
      }
        // if(session('offer')) {
          Session::forget('offer');
          Session::forget('discount');
          Session::forget('total');
        // }

        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        foreach ($user->customer->cart as $value) {
            $value->food = Food::find($value->food_id);
        }

        return view('customer.cart')->with('customer', $user->customer);
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
          'food_id' => 'required'
        ]);

        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $food = Food::find($request->input('food_id'));

        $cart = new Cart;
        $cart->food_id = $request->input('food_id');
        $cart->quantity = 1;
        $cart->price = $food->price;
        $cart->customer_id = $user->customer->id;
        $cart->save();

        return redirect('menu/'.$food->vendor_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $quantity)
    {
      if(auth()->user()->type == "vendor") {
        return redirect('home');
      }

        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $user_id = $user->customer->id;
        $food = Food::find($request->input('food_id'));
        $quantity++;

        $cart = Cart::where([
          'food_id' => $request->input('food_id'),
          'customer_id' => $user_id
        ])->update([
            'quantity' => $quantity,
            'price' => ($quantity * $food->price)
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $cart)
    {

        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        $cart = Cart::where([
          'food_id' => $request->input('food_id'),
          'customer_id' => $user->customer->id
        ])->delete();

        return back();
    }
}
