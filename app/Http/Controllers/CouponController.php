<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coupon;
use App\Offer;
use App\Http\Controllers\OfferController;
use Illuminate\Support\Facades\Session;

class CouponController extends Controller
{
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
      if(!(auth()->user()->type == "vendor")) {
        return redirect('home');
      } else {

        $this->validate($request, [
          'code' => 'required',
        ]);

        if (Coupon::where('code', strtoupper($request->input('code')))->exists()) {

            Session::flash('status', 'Code already exists');

            return back();
        }

        $coupon = new Coupon;
        $coupon->code = strtoupper($request->input('code'));
        $coupon->limit = $request->input('limit');
        $coupon->save();


        $data['offer_id'] = $coupon->id;
        $data['type'] = 'App\coupon';

        $offer = new OfferController;
        $offer->store($request, $data);

        return back();
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function apply(Request $request)
    {

      Session::forget('offer');
      Session::forget('discount');
      Session::forget('total');

      if(Coupon::where('code', strtoupper($request->input('code')))->exists()) {

        $offers = Offer::where('offer_type','App\coupon')->get();

        foreach ($offers as $value) {
            if($value->offer->code == $request->input('code')) {
              $offer = $value;
              break;
            }
        }

        $offer_object = new OfferController;
        $offer_object->discount($offer, $request);

      } else {
        Session::flash('status', 'Invalid Code');
      }

      return back();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
