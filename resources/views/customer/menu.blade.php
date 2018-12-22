@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">{{$vendor->name}}'s Menu</div>

        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif

          @if (count($vendor->food) > 0)
          <div id="products" class="row view-group ">

            @foreach($vendor->food as $food)

            <div class="item col-xs-4 col-lg-4">
              <div class="thumbnail card">
                <div class="img-event">
                  <img class="group list-group-image img-fluid" src="#" alt="" />
                </div>
                <div class="caption card-body">
                  <h4 class="group card-title inner list-group-item-heading">
                    {{$food->name}}</h4>
                    <p class="group inner list-group-item-text">
                      {{ str_limit($food->description, 20)}}</p>
                      <div class="row">
                        <div class="col-xs-12 col-md-6">
                          <p class="lead">
                            Rs {{$food->price}}</p>
                          </div>
                        <div class="col-xs-12 col-md-6">
                          <p class="lead">
                            {{$food->calorie}} Calories</p>
                          </div>
                          <div class="col-xs-12 col-md-6">

                            @if ($food->quantity == 0)
                            <form method="POST" action="/cart">
                              @csrf
                              <input type="hidden" value="{{$food->id}}" name='food_id'>
                              <button type="submit" class="btn btn-success">
                                {{ __('Add to Cart') }}
                              </button>
                            </form>
                            @else
                            <form method="POST" action="{{route('cart.update',[$food->quantity])}}">
                              @csrf
                              <input type="hidden" value="{{$food->id}}" name='food_id'>
                              @method('PUT')
                              <button type="submit" class="btn btn-success">
                                {{ __('Add to Cart') }}
                              </button>
                            </form>
                              <p class="lead small">
                                In Cart : {{$food->quantity}}</p>
                            @endif
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  @endforeach
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
      @endsection
