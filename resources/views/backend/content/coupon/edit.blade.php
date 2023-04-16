@extends('backend.layouts.app')

@section('title', 'Coupon banner | Edit Coupon')

@php

$required = html()->span('*')->class('font-weight-bold ml-1 text-danger');

@endphp
@section('content')
{{ html()->modelForm($coupon, 'PATCH', route('admin.coupon.update', $coupon))->open() }}
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header with-border">
        <h3 class="card-title">Coupon Management <small class="ml-2">Create New</small></h3>
      </div>
      <div class="card-body">

        <div class="form-group">
          <div class="checkbox d-flex align-items-center">
            {{ html()->label( html()->checkbox('active', $coupon->active)
                                      ->value(date('Y-m-d H:i:s'))
                                      ->class('switch-input')
                                      ->id('active')
                                  . '<span class="switch-slider" data-checked="on" data-unchecked="off"></span>')
                              ->class('switch switch-label switch-pill switch-primary mr-2')
                          ->for('active') }}
            {{ html()->label(ucwords('active'))->for('active') }}
          </div>
        </div> <!--  form-group-->


        <div class="form-group">
          {{html()->label('Coupon Code'.$required)->for('coupon_code')}}
          <div class="input-group">
            {{html()->text('coupon_code')->class('form-control')->required(true)->attribute('aria-describedby','inputCodeAppend')->placeholder('Coupon Code')}}
            <div class="input-group-append">
              <span class="input-group-text" id="inputCodeAppend">Generate</span>
            </div>
          </div>
        </div> <!-- form-group -->

        <div class="form-group">
          {{html()->label('Coupon Type'.$required)->for('coupon_type')}}
          <br>
          @php
          $coupon_type = old('coupon_type', $coupon->coupon_type);
          @endphp
          <div class="form-check form-check-inline">
            <input type="radio" class="form-check-input" name="coupon_type" value="flat_cart_discount"
              id="flat_cart_discount" class="checking" checked="checked">
            <label class="form-check-label" for="flat_cart_discount">Flat Cart Discount</label>
          </div> <!-- form-check  -->
          <div class="form-check form-check-inline">
            <input type="radio" class="form-check-input" name="coupon_type" value="perchantage_discount"
              id="perchantage_discount" class="checking" @if($coupon_type==='perchantage_discount' ) checked @endif>
            <label class="form-check-label" for="perchantage_discount">Perchantage Discount</label>
          </div> <!-- form-check  -->
          <div class="form-check form-check-inline">
            <input type="radio" class="form-check-input" name="coupon_type" value="free_shipping" id="free_shipping"
              class="checking" @if($coupon_type==='free_shipping' ) checked @endif>
            <label class="form-check-label" for="free_shipping">Free Shipping</label>
          </div> <!-- form-check  -->
        </div> <!-- form-group -->


        <div class="form-group">
          {{html()->label('Coupon Amount')->for('coupon_amount')}}
          {{html()->number('coupon_amount')->class('form-control')->attribute('min', 0)->placeholder('Flat Amount')}}
          <p class="text-danger m-0"> @error('coupon_amount') {{$message}} @enderror </p>
        </div> <!-- form-group -->

        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              {{html()->label('Minimum Spend')->for('minimum_spend')}}
              {{html()->number('minimum_spend')->class('form-control')->attribute('min', 0)->placeholder('No minimum')}}
              <p class="text-danger m-0"> @error('minimum_spend') {{$message}} @enderror </p>
            </div> <!-- form-group -->
          </div> <!-- col-sm-6 -->
          <div class="col-sm-6">
            <div class="form-group">
              {{html()->label('Maximum Spend')->for('maximum_spend')}}
              {{html()->number('maximum_spend')->class('form-control')->attribute('min', 0)->placeholder('No maximum')}}
              <p class="text-danger m-0"> @error('maximum_spend') {{$message}} @enderror </p>
            </div> <!-- form-group -->
          </div> <!-- col-sm-6 -->
        </div> <!-- row -->

        <div class="form-group">
          {{html()->label('Usage Limit Per Coupon')->for('limit_per_coupon')}}
          {{html()->number('limit_per_coupon')->class('form-control')->attribute('min', 0)->placeholder('No limit')}}
          <p class="text-danger m-0"> @error('limit_per_coupon') {{$message}} @enderror </p>
        </div> <!-- form-group -->

        <div class="form-group">
          {{html()->label('Usage Limit Per User')->for('limit_per_user')}}
          {{html()->number('limit_per_user')->class('form-control')->attribute('min', 0)->placeholder('No limit')}}
          <p class="text-danger m-0"> @error('limit_per_user') {{$message}} @enderror </p>
        </div> <!-- form-group -->

        <div class="form-group">
          {{html()->label('Coupon Expiry Date'.$required)->for('expiry_date')}}
          <div class="input-group">
            @php
            $expiry_date = date('Y-m-d', strtotime($coupon->expiry_date));
            @endphp
            {{html()->text('expiry_date', old('expiry_date',$expiry_date))->class('form-control')->required(true)->attribute('aria-describedby','inputExpiryAppend')->placeholder('YYYY-MM-DD')->required(true)}}
            <div class="input-group-append">
              <span class="input-group-text" id="inputExpiryAppend"><i class="fa fa-calendar"></i></span>
            </div>
          </div>
        </div> <!-- form-group -->


      </div> <!--  .card-body -->
      <div class="card-footer">
        {{ form_submit(__('buttons.general.crud.update')) }}
        {{ form_cancel(route('admin.banner.index'), __('buttons.general.cancel')) }}
      </div> <!--  .card-body -->
    </div> <!--  .card -->
  </div> <!-- .col-md-9 -->

</div> <!-- .row -->


{{ html()->closeModelForm() }}
@endsection



@push('after-styles')
{{ style(asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')) }}
<style>
  #inputCodeAppend {
    cursor: pointer;
  }
</style>
@endpush

@push('after-scripts')
{!! script(asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')) !!}
<script>
  function makeid(length) {
   var result           = '';
   var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
   var charactersLength = characters.length;
   for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
}

  $(document).ready(function () {
    $('#expiry_date').datepicker({
        format: "yyyy-mm-dd",
        clearBtn: true,
        autoclose: true,
        todayHighlight: true,
    });

    $('#inputCodeAppend').click(function(){
      $('#coupon_code').val(makeid(8));
    });


    $('input[name="coupon_type"]').click(function(){
      var type = $(this).val();
      var amount = $('#coupon_amount');
      if(type === 'free_shipping'){
        amount.attr('disabled', 'disabled');
        amount.attr('placeholder', 'No Amount');
      }else{  
        amount.removeAttr('disabled');
        amount.removeAttr('max');
      }

      if(type === 'perchantage_discount'){
        amount.attr('placeholder', 'Perchantage');
        amount.attr('max', '100');
      }else if(type === 'flat_cart_discount'){
        amount.attr('placeholder', 'Flat Amount');
        amount.removeAttr('max');
      }
  });
  });


</script>
@endpush