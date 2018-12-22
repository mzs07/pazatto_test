@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Vendors</div>

        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif

          @if ($customer == null)

          <div class="card-header mb-4">Provide a name for further services</div>
          <form method="POST" action="customer">
            @csrf

            <div class="form-group row">
              <label for="firstname" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>

              <div class="col-md-6">
                <input id="firstname" type="text" class="form-control{{ $errors->has('firstname') ? ' is-invalid' : '' }}" name="firstname" value="{{ old('firstname') }}" required autofocus>

                @if ($errors->has('firstname'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('firstname') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <div class="form-group row">
              <label for="lastname" class="col-md-4 col-form-label text-md-right">{{ __('Last Name') }}</label>

              <div class="col-md-6">
                <input id="lastname" type="text" class="form-control{{ $errors->has('lastname') ? ' is-invalid' : '' }}" name="lastname" value="{{ old('lastname') }}" required autofocus>

                @if ($errors->has('lastname'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('lastname') }}</strong>
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

          @else

          @if (count($vendor) > 0)
          <div id="products" class="row view-group">

            @foreach($vendor as $v)

            <div class="item col-xs-4 col-lg-4">
              <div class="thumbnail card">
                <div class="img-event">
                  <img class="group list-group-image img-fluid" src="#" alt="" />
                </div>
                <div class="caption card-body">
                  <h4 class="group card-title inner list-group-item-heading">
                    {{$v->name}}</h4>
                    <div class="row">
                          <div class="col-xs-12 col-md-6">
                            <a class="btn btn-success" href="/menu/{{$v->id}}">Browse</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  @endforeach
                </div>
                @endif

                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
      @endsection
