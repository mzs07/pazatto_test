<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\User;
use App\Vendor;
use App\Offer;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        if(Auth::user()->type == "customer")
        {

            return view('customer.user_dash')->with('customer', $user->customer)
                                                 ->with('vendor', Vendor::all());

        } else if(Auth::user()->type == "vendor")
        {

            if ($user->vendor == null) {
              return view('vendor.vendor_dash')->with('vendor', $user->vendor);
            }
            $user->vendor->food;
            return view('vendor.vendor_dash')->with('vendor', $user->vendor)
                                             ->with('offer', Offer::where('offer_type', 'discount')
                                             ->orderBy('expiry', 'DESC')->get());
        }

    }
}
