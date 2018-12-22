@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Dashboard</div>

        <div class="card-body">
          @if (session('success'))
          <div class="alert alert-success" role="alert">
            {{ session('success') }}
          </div>
          @endif

          @if (session('status'))
          <div class="alert alert-danger" role="alert">
            {{ session('status') }}
          </div>
          @endif

          @if($vendor != null)

          @if(count($vendor->food)>0)
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Calorie Count</th>
                <th>Price</th>
                <th>Sale price</th>
              </tr>
            </thead>
            <tbody>
              @foreach($vendor->food as $food)
              <tr>
                <td>{{$food->name}}</td>
                <td>{{$food->description}}</td>
                <td>{{$food->calorie}}</td>
                @if($food->discount_id != null)
                <td>{{$food->original_price}}</td>
                <td>{{$food->price}}</td>
                @else
                <td>{{$food->price}}</td>
                <td>-</td>
                @endif
              </tr>
              @endforeach
            </tbody>
          </table>
          @endif

          <div class="card">
            <div class="card-title">Add New</div>

            <div class="card-body">
              <form method="POST" action="food">
                @csrf

                <div class="form-group row">
                  <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Item Name') }}</label>

                  <div class="col-md-6">
                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required >

                    @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('name') }}</strong>
                    </span>
                    @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Item Description') }}</label>

                  <div class="col-md-6">
                    <textarea id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" rows="5" name="description" value="{{ old('description') }}" required ></textarea>
                    @if ($errors->has('description'))
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('description') }}</strong>
                    </span>
                    @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label for="calorie" class="col-md-4 col-form-label text-md-right">{{ __('Item calorie') }}</label>

                  <div class="col-md-6">
                    <input id="calorie" type="number" class="form-control{{ $errors->has('calorie') ? ' is-invalid' : '' }}" name="calorie" value="{{ old('calorie') }}" required >

                    @if ($errors->has('calorie'))
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('calorie') }}</strong>
                    </span>
                    @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label for="price" class="col-md-4 col-form-label text-md-right">{{ __('Item price') }}</label>

                  <div class="col-md-6">
                    <input id="price" type="number" class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" name="price" value="{{ old('price') }}" required >

                    @if ($errors->has('price'))
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('price') }}</strong>
                    </span>
                    @endif
                  </div>
                </div>

                <div class="form-group row mb-0">
                  <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                      {{ __('Add Item') }}
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          @else
          <div class="card-header mb-4">Provide a name for further services</div>
          <form method="POST" action="vendor">
            @csrf

            <div class="form-group row">
              <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Vendor Name') }}</label>

              <div class="col-md-6">
                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required >

                @if ($errors->has('name'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <div class="form-group row mb-0">
              <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                  {{ __('Submit') }}
                </button>
              </div>
            </div>
          </form>
          @endif
        </div>
      </div>
    </div>
    <div class="card col-md-4">
      <div class="card-header">Instant Discounts</div>
      <div class="card-body">
        @if(count($offer)>0)
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Description</th>
              <th>Amount</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($offer as $value)
            <tr>
              <td>{{$value->description}}</td>
              <td>{{$value->amount}}</td>
              <td>
              <form class="" action="/addinstant" method="post">
              @csrf
              <input type="hidden" name="id" value="{{$value->id}}">
              <button class="btn btn-primary" type="submit">{{__('+')}}</button>
              </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @else
          <h4 style ="text-align:center">Such Emptyness</h4>
        @endif
        <div class="container">
            <button type="button" class="btn btn-info btn-block" data-toggle="collapse" data-target="#discount">Add Discount</button>
            <div id="discount" class="collapse m-4">
              <form method="POST" action="/discount">
              @csrf

              <div class="form-group row">
                <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                <div class="col-md-6">
                  <textarea id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" rows="5" name="description" value="{{ old('description') }}" required ></textarea>
                  @if ($errors->has('description'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('description') }}</strong>
                  </span>
                  @endif
                </div>
              </div>

              <div class="form-group row">
                <label for="amount" class="col-md-4 col-form-label text-md-right">{{ __('Discount  %') }}</label>

                <div class="col-md-6">
                  <input id="amount" type="number" class="form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}" name="amount" value="{{ old('amount') }}" required>

                  @if ($errors->has('amount'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('amount') }}</strong>
                  </span>
                  @endif
                </div>
              </div>

              <div class="form-group row">
                <label for="expiry" class="col-md-4 col-form-label text-md-right">{{ __('Offer Expiry') }}</label>

                <div class="col-md-6">
                  <input id="expiry" type="date" class="form-control{{ $errors->has('expiry') ? ' is-invalid' : '' }}" name="expiry" value="{{(old('expiry')!=null) ? old('expiry') : Carbon\Carbon::now()->format('Y-m-d') }}" required>

                  @if ($errors->has('expiry'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('expiry') }}</strong>
                  </span>
                  @endif
                </div>
              </div>

              <input type="hidden" name='type' value ='discount'>

              <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                  <button type="submit" class="btn btn-primary">
                    {{ __('Add Offer') }}
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
@endsection
