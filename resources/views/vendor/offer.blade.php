@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">Offer Creation Centre</div>

        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-danger" role="alert">
            {{ session('status') }}
          </div>
          @endif

          @if($offer != null)

          @if(count($offer)>0)
          <table class="table table-bordered" style="height:70px; overflow-y: scroll;">
            <thead>
              <tr>
                <th>Description</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Minimum Purchase</th>
                <th>UpTime</th>
                <th>Expiry</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach($offer as $value)
              <tr>
                <td>{{$value->description}}</td>
                @if ($value->offer_type == 'amount')
                <td>Amount</td>
                @elseif ($value->offer_type == 'percentage')
                <td>Percentage</td>
                @else
                <td>Coupon</td>
                @endif
                <td>{{$value->amount}}</td>
                @if ($value->min_order)
                <td>{{$value->min_order}}</td>
                @else
                <td>undefined</td>
                @endif
                @if ($value->begin && $value->end)
                <td>{{$value->begin}} to {{$value->end}}</td>
                @else
                <td>undefined</td>
                @endif
                <td>{{$value->expiry}}</td>
                <td>
                <form class="" action="/addOffer" method="post">
                @csrf
                <input type="hidden" name="id" value="{{$value->id}}">
                <button class="btn btn-primary" type="submit">{{__('+')}}</button>
                </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          @endif

          <div class="container">

              <div class="panel-group" id="accordion">
                <div class="panel panel-default border m-3">
                  <div class="panel-heading" class="panel-title" data-toggle="collapse" data-parent="#accordion" href="#offerMaker">
                      <h5 class="text-center"><a>Offer Maker</a></h5>
                  </div>
                  <div id="offerMaker" class="panel-collapse collapse in">
                    <div class="panel-body">
                      <div>
                        <hr>
                        <form method="POST" action="offer">
                          @csrf

                          <div class="form-group row">
                            <label for="type" class="col-md-4 col-form-label text-md-right">{{ __('Offer Type') }}</label>

                            <div class="col-md-6">
                              <select class="form-control" id="type" name = 'type'>
                                <option value="amount">Discount Amount</option>
                                <option value="percentage">Discount Percentage</option>
                              </select>

                              @if ($errors->has('type'))
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('type') }}</strong>
                              </span>
                              @endif
                            </div>
                          </div>

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
                            <label for="amount" class="col-md-4 col-form-label text-md-right">{{ __('Discount ') }}</label>

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
                            <label for="min" class="col-md-4 col-form-label text-md-right">{{ __('Min Purchase') }}</label>

                            <div class="col-md-6">
                              <input id="min" type="number" placeholder ="Optional" class="form-control{{ $errors->has('min') ? ' is-invalid' : '' }}" name="min" value="{{ old('min') }}">

                              @if ($errors->has('min'))
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('min') }}</strong>
                              </span>
                              @endif
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="begin" class="col-md-4 col-form-label text-md-right">{{ __('Up Time (Optional)') }}</label>

                            <div class="col-md-6">
                              <input id="begin" type="time" class="form-control{{ $errors->has('begin') ? ' is-invalid' : '' }}" name="begin" value="{{ old('begin') }}">
                              to
                              <input id="end" type="time" class="form-control{{ $errors->has('end') ? ' is-invalid' : '' }}" name="end" value="{{ old('end') }}">

                              @if ($errors->has('begin'))
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('begin') }}</strong>
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
                <div class="panel panel-default border m-3">
                  <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#couponMaker">
                      <h5 class="text-center panel-title"><a>Coupon Maker</a></h5>
                  </div>
                  <div id="couponMaker" class="panel-collapse collapse in">
                    <div class="panel-body">
                      <div>
                        <hr>
                        <form method="POST" action="coupon">
                          @csrf

                          <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                            <div class="col-md-6">
                              <textarea id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" rows="5" name="description" value="{{ old('description') }}" required></textarea>
                              @if ($errors->has('description'))
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('description') }}</strong>
                              </span>
                              @endif
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="code" class="col-md-4 col-form-label text-md-right">{{ __('Code') }}</label>

                            <div class="col-md-6">
                              <input id="code" type="text" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" name="code" value="{{ old('code') }}" required>

                              @if ($errors->has('code'))
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('code') }}</strong>
                              </span>
                              @endif
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="limit" class="col-md-4 col-form-label text-md-right">{{ __('Usage Limit') }}</label>

                            <div class="col-md-6">
                              <input id="limit" type="number" placeholder="Optional" class="form-control{{ $errors->has('limit') ? ' is-invalid' : '' }}" name="limit" value="{{ old('limit') }}">

                              @if ($errors->has('limit'))
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('limit') }}</strong>
                              </span>
                              @endif
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="amount" class="col-md-4 col-form-label text-md-right">{{ __('Discount ') }}</label>

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
                            <label for="min" class="col-md-4 col-form-label text-md-right">{{ __('Min Purchase') }}</label>

                            <div class="col-md-6">
                              <input id="min" type="number" placeholder ="Optional" class="form-control{{ $errors->has('min') ? ' is-invalid' : '' }}" name="min" value="{{ old('min') }}">

                              @if ($errors->has('min'))
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('min') }}</strong>
                              </span>
                              @endif
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="begin" class="col-md-4 col-form-label text-md-right">{{ __('Up Time (Optional)') }}</label>

                            <div class="col-md-6">
                              <input id="begin" type="time" class="form-control{{ $errors->has('begin') ? ' is-invalid' : '' }}" name="begin" value="{{ old('begin') }}">
                              to
                              <input id="end" type="time" class="form-control{{ $errors->has('end') ? ' is-invalid' : '' }}" name="end" value="{{ old('end') }}">

                              @if ($errors->has('begin'))
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('begin') }}</strong>
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

                          <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                              <button type="submit" class="btn btn-primary">
                                {{ __('Create Coupon') }}
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
  </div>
</div>
@endsection
