@extends('customer.user_dash')

@section('product')
<div id="products" class="row view-group">
  <div class="item col-xs-4 col-lg-4">
    <div class="thumbnail card">
      <div class="img-event">
        <img class="group list-group-image img-fluid" src="#" alt="" />
      </div>
      <div class="caption card-body">
        <h4 class="group card-title inner list-group-item-heading">
          Product title</h4>
          <p class="group inner list-group-item-text">
            Product description... Lorem ipsum dolor sit amet, consectetuer adipiscing elit,
            sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>
            <div class="row">
              <div class="col-xs-12 col-md-6">
                <p class="lead">
                  $21.000</p>
                </div>
                <div class="col-xs-12 col-md-6">
                  <a class="btn btn-success" href="#">Add to cart</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endsection
