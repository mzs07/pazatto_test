<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use App\Vendor;

class CustomerController extends Controller
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
     public function store(Request $request)
     {
       if(auth()->user()->type == "vendor") {
         return redirect('\home');
       }

       $this->validate($request, [
         'firstname' => 'required',
         'lastname' => 'required'
       ]);

       $customer = new customer;
       $customer->firstname = $request->input('firstname');
       $customer->lastname = $request->input('lastname');
       $customer->user_id = auth()->user()->id;
       $customer->save();

       return redirect('/home');
     }


    /**
     * Display the specified resource.
     *
     * @param  \App\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    public function vendor($id)
    {
        if(auth()->user()->type == "vendor") {
          return redirect('home');
        }
        $vendor = Vendor::find($id);

        if($vendor != null ) {
          $cart = auth()->user()->customer->cart;

          foreach ($cart as $value) {
            foreach ($vendor->food as $key) {
              $key->quantity*= 1;
              if($key->id == $value->food_id) {
                $key->quantity = $value->quantity;
                break;
              }
            }
          }

          return view('customer.menu')->with('vendor', $vendor)
                                      ->with('cart', $cart);
        } else {

          return view('customer.menu_error');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
