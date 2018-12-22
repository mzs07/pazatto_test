<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Offer;
use App\Food;
use App\User;
use Carbon\Carbon;
use App\Discount;
use Illuminate\Support\Facades\Session;


class OfferController extends Controller
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
        $offer = Offer::orderBy('expiry', 'DESC')->get();

        return view('vendor.offer')->with('offer', $offer);
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
     public function store(Request $request, $data=0)
     {
       if(!(auth()->user()->type == "vendor")) {
         return redirect('home');
       } else {

         $this->validate($request, [
           'description' => 'required',
           'amount' => 'required',
           'expiry' => 'required',
         ]);

         $offer = new Offer;
         $offer->description = $request->input('description');
         $offer->offer_id = $data == 0 ? 0 : $data['offer_id'];
         $offer->offer_type = $data == 0 ? $request->input('type') : $data['type'];
         $offer->amount = $request->input('amount');
         $offer->begin = $request->input('begin');
         $offer->end = $request->input('end');
         $offer->expiry = $request->input('expiry');
         $offer->min_order = $request->input('min');
         $offer->save();

         $users = User::where('type', 'customer')->get();
         foreach ($users as $value) {
              $value->customer->offer()->sync($offer->id, false);
         }


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

    public function discount($offer, $request)
    {
        $valid = true;
        $now = Carbon::now();
        $user_id = auth()->user()->id;
        $subtotal = $request->input('subtotal');
        $time = $request->input('time');

        if($offer->begin != null && $offer->end != null) {

            if(!($now->format('H:i:s') > $offer->begin && $now->format('H:i:s') < $offer->end)) {

              $valid = false;
            }
        }

        if($now > $offer->expiry) {
          $valid = false;
        }

        if($offer->min_order != null && $subtotal < $offer->min_order) {
            $valid = false;
        }

        if($offer->offer_type == "App\coupon") {
          //return $this->limit($valid, $offer);
          $valid = $this->limit($valid, $offer);
        }

        if ($valid == true) {
          if($offer->offer_type == "percentage") {
            $discount = round((($offer->amount)/100)*$subtotal,2);
          } else {
            $discount = $offer->amount;
          }
        } else {
          $discount = 0;
        }


        if($discount!=0) {
          Session::put('offer', $offer->id);
          Session::put('discount', $discount);
          $total = $request->input('subtotal') + 30 - $discount;
          Session::put('total', $total);
        } else {
          Session::flash('status', 'Invalid Offer');
          return 0;
        }

    }

    public function limit($valid, $offer) {

      if($offer->offer->limit != null) {
        $offers = Offer::with('customer')->get();

        foreach ($offers as $value) {

            if($value->id == $offer->id) {
              $limit = $value->customer->find(User::find(auth()->user()->id)->customer->id)->pivot->limit;
              if(!($limit == null || $limit < $offer->offer->limit)) {
                $valid = false;
              }
              break;
            }
        }
      }

      if($offer->vendor != null) {
        foreach ($offer->vendor as $vendor) {
          return 'hb';

          $valid = false;
          if($vendor->vendor_id == $vendor_id) {
            $valid = true;
            break;
          }
        }
      } else {
        $valid = false;
      }
      return $valid;
    }

    public function addOffer(Request $request)
    {
      if(!(auth()->user()->type == "vendor")) {
        return redirect('home');
      } else {
        User::find(auth()->user()->id)->vendor->offer()->sync($request->input('id'),false);
        return back();
      }
    }

    public function apply(Request $request)
    {
      if(!(auth()->user()->type == "customer")) {
        return redirect('home');
      } else {

        Session::forget('offer');
        Session::forget('discount');
        Session::forget('total');

        $offer = Offer::find($request->input('id'));

        $this->discount($offer, $request);

        return back();
      }
    }

    public function instant(Request $request)
    {
        $instant = new Discount;
        $instant->vendor_id = User::find(auth()->user()->id)->vendor->id;
        $instant->save();

        $data['type'] = 'discount';
        $data['offer_id'] = $instant->id;

        $this->store($request, $data);
        return $this->addinstant($request, $instant->id);
    }

    public function addinstant(Request $request, $id = 0)
    {
        if ($id == 0) {
          $instant_id = Offer::find($request->input('id'))->offer_id;
        } else {
          $instant_id = $id;
        }

        $amount = Offer::where('offer_id', $instant_id)->first()->amount;


        if(!Food::where('vendor_id', User::find(auth()->user()->id)->vendor->id)->exists()) {
          Session::flash('status', 'Add food to assign offers');
        } else if (Food::where('vendor_id', User::find(auth()->user()->id)->vendor->id)->first()->discount_id != null){
            $foods = Food::where('vendor_id', User::find(auth()->user()->id)->vendor->id)->get();

            foreach ($foods as $food) {
               $price = $food->original_price;

               $discount = round(($amount/100) * $price,2);

               $food->price = $price - $discount;
               $food->discount_id = $instant_id;
               $food->save();

               Session::flash('success', 'Offer updated');
            }
        } else {
          $foods = Food::where('vendor_id', User::find(auth()->user()->id)->vendor->id)->get();

          foreach ($foods as $food) {
             $price = $food->price;

             $discount = round(($amount/100) * $price,2);

             $food->price = $price - $discount;
             $food->original_price = $price;
             $food->discount_id = $instant_id;
             $food->save();
          }

          Session::flash('success', 'Offer updated');
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
