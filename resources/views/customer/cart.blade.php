@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">{{$customer->firstname}}'s Cart</div>

        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif

          @if (!count($customer->cart) > 0)
          <div class="content">
              <div class="m-b-md" style="text-align: center">
                {{ __('SUCH EMPTINESS')}}
              </div>
            </div>
          @else
          <div id="products" class="row view-group ">

            @foreach($customer->cart as $cart)
            <div class="item col-xs-4 col-lg-4">
              <div class="thumbnail card">
                <div class="img-event">
                  <img class="group list-group-image img-fluid" src="#" alt="" />
                </div>
                <div class="caption card-body">
                  <h4 class="group card-title inner list-group-item-heading">
                    {{$cart->food->name}}</h4>
                    <p class="group inner list-group-item-text">
                      {{ str_limit($cart->food->description, 20)}}</p>
                      <div class="row">
                        <div class="col-xs-12 col-md-6">
                          <p class="lead">
                            Rs {{$cart->price}}</p>
                          </div>
                        <div class="col-xs-12 col-md-6">
                          <p class="lead">
                            {{$cart->food->calorie}} Calories</p>
                          </div>
                          <div class="col-xs-12 col-md-6">

                            <form method="POST" action="{{route('cart.update',[$cart->quantity])}}">
                              @csrf
                              <input type="hidden" value="{{$cart->food->id}}" name='food_id'>
                              @method('PUT')
                              <button type="submit" class="btn btn-success">
                                {{ __('Add to Cart') }}
                              </button>
                            </form>
                              <p class="lead small">
                                In Cart : {{$cart->quantity}}</p>
                          </div>
                          <div class="col-xs-2 col-md-2 ml-2">

                            <form method="POST" action="{{route('cart.destroy',[$cart])}}">
                              @csrf
                              <input type="hidden" value="{{$cart->food->id}}" name='food_id'>
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger">
                                {{ __('X') }}
                              </button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
                <div class="col-md-8 offset-6 m-4 container">
                  <a class="btn btn-primary col-md-8 offset-4" href="/order/{{$customer->id}}">Order Now</a>
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
      @endsection
