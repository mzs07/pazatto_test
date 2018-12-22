@extends('layouts.app')

@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Order Confirm</div>

        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-danger" role="alert">
            {{ session('status') }}
          </div>
          @endif
          @if(!count($customer->cart) > 0)
          <div class="content">
              <div class="m-b-md" style="text-align: center">
                {{ __('SUCH EMPTINESS')}}
              </div>
          </div>
          @else
          <ul class="list-group">
          @foreach ($customer->cart as $cart)
          @if($cart->food->vendor_id == $vendor_id)
          <li class="list-group-item">
          <div class="">
            <a href="" class="pull-left">
              <img src="" class="media-photo">
            </a>
            <div class="container  p-4">
              <h5><div class="float-right badge badge-primary badge-pill">Rs {{($cart->price)}}</div></h5>
              <h4 class="title">
                <span class="pull-right">{{$cart->food->name}}</span>
              </h4>
              <p class="summary">{{str_limit($cart->food->description,140)}}</p>
            </div>
          </div>
          </li>
          @endif
          @endforeach
          </ul>
          <hr>
          <div class="container p-4">
            <div class="float-right badge badge-success">{{ __('Calorie Count : ')}} {{$customer->cart->calorie}}</div>
            <div class="form-group mt-5" >
            <p class="title">
              {{ __('Subtotal : ')}}<span class="pull-right form-control">Rs {{$customer->cart->subtotal}}</span>
            </p>
            <p class="title">
              {{ __('Delivery Charges : ')}}<span class="pull-right form-control">Rs 30</span>
            <p>

            @if(session('offer'))
            <p class="title">
              {{ __('Discount : ')}}<span class="pull-right form-control">- {{session('discount')}}</span>
            <p>
            @endif

            <h4 class="title">
              {{ __('Total : ')}}<span class="pull-right form-control">Rs {{(session('offer') ? session('total') : $customer->cart->total)}}</span>
            </h4>
          </div>

            <form class="form" action="{{route('order.store')}}" method="post">
              @csrf
              <div class="form-group row">
                <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('User Address') }}</label>

                <div class="col-md-6">
                  <textarea id="address" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" rows="5" name="address" value="{{ old('address') }}" required></textarea>
                  @if ($errors->has('address'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('address') }}</strong>
                  </span>
                  @endif
                </div>
              </div>

              <input type="hidden" name="vendor_id" value="{{$vendor_id}}">
              <input type="hidden" name="total" value="{{(session('offer') ? session('total') : $customer->cart->total)}}">

              <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                  <button type="submit" class="btn btn-primary">
                    {{ __('Confirm') }}
                  </button>
                </div>
              </div>
            </form>
          </div>
          </div>
          @endif
        </div>
      </div>
      <div class="card col-md-4">
        <div class="card-header">Instant Discounts</div>
        <div class="card-body">
          @if(count($offers)>0)
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Description</th>
                <th>Amount</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach($offers as $value)
              @foreach($value->vendor as $vendor)
              @if($vendor->id == $vendor_id)
              @if($value->offer_type != 'App\coupon' && $value->offer_type != 'App\discount')
              <tr>
                <td>{{$value->description}}</td>
                <td>{{$value->amount}}</td>
                <td>
                <form class="" action="/getoffer" method="post">
                @csrf
                <input type="hidden" name="id" value="{{$value->id}}">
                <input type="hidden" name="subtotal" value="{{$customer->cart->subtotal}}">
                <input type="hidden" name="time" value="{{Carbon\Carbon::now()}}">

                <button class="btn btn-primary" type="submit">{{__('+')}}</button>
                </form>
                </td>
              </tr>
              @endif
              @endif
              @endforeach
              @endforeach
            </tbody>
          </table>
          @else
            <h4 style ="text-align:center">Such Emptyness</h4>
          @endif
          <div class="container">
              <button type="button" class="btn btn-info btn-block" data-toggle="collapse" data-target="#discount">Coupon</button>
              <div id="discount" class="collapse m-4">
                <form method="POST" action="/apply">
                @csrf

                <div class="form-group row">
                  <label for="code" class="col-md-4 col-form-label text-md-right">{{ __('Code') }}</label>

                  <div class="col-md-6">
                    <input id="code" type="text" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" rows="5" name="code" value="{{ old('code') }}" required >
                    @if ($errors->has('code'))
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('code') }}</strong>
                    </span>
                    @endif
                  </div>
                </div>

                <input type="hidden" name="subtotal" value="{{$customer->cart->subtotal}}">
                <input type="hidden" name="time" value="{{Carbon\Carbon::now()}}">

                <div class="form-group row mb-0">
                  <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                      {{ __('Apply Coupon') }}
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
