<?php

namespace App\Http\Controllers;

use App\Food;
use Illuminate\Http\Request;
use App\User;

class FoodController extends Controller
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
      if(auth()->user()->type == "customer") {
        return redirect('home');
      }

      $this->validate($request, [
        'name' => 'required',
        'description' => 'required',
        'calorie' => 'required',
        'price' => 'required',
      ]);

      $user_id = auth()->user()->id;
      $user = User::find($user_id);

      $food = new Food;
      $food->name = $request->input('name');
      $food->description = $request->input('description');
      $food->calorie = $request->input('calorie');
      $food->price = $request->input('price');
      $food->vendor_id = $user->vendor->id;
      $food->save();

      $user->vendor->food;

      return redirect('/home')->with('vendor', $user->vendor);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function show(Food $food)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function edit(Food $food)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Food $food)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function destroy(Food $food)
    {
        //
    }
}
