@extends('backend.layouts.app')

@section('title', ' Manage Coupon')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="row">
      <div class="col-sm-5">
        <h4 class="card-title mb-0"> Coupon Management </h4>
      </div> <!-- col-->

      <div class="col-sm-7 pull-right">
        @include('backend.content.coupon.includes.header-buttons')
      </div> <!-- col-->
    </div> <!-- row-->

    <div class="row mt-4">
      <div class="col">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr class="vertical-middle">
                <th style="min-width:80px" class="text-center">Code</th>
                <th style="min-width:80px" class="text-center">Type</th>
                <th style="min-width:75px" class="text-center">Amount</th>
                <th style="min-width:90px" class="text-center">Min spend</th>
                <th style="min-width:90px" class="text-center">Max spend</th>
                <th style="min-width:108px" class="text-center">Limit Coupon</th>
                <th style="min-width:90px" class="text-center">Limit User</th>
                <th style="min-width:100px" class="text-center">Expiry Date</th>
                <th style="min-width:80px" class="text-center">Status</th>
                <th style="min-width:100px" class="text-center">Created By</th>
                <th style="min-width:100px" class="text-center">Create Date</th>
                <th style="min-width:100px" class="text-center">@lang('labels.general.actions')</th>
              </tr>
            </thead>
            <tbody>
              @foreach($coupons as $coupon)
              <tr class="vertical-middle">
                <td class="text-center">{{ $coupon->coupon_code }}</td>
                <td class="text-center text-capitalize">{{ str_replace('_', ' ', $coupon->coupon_type)}}</td>
                <td class="text-center">{{ $coupon->coupon_amount ? $coupon->coupon_amount : 'N/A' }}</td>
                <td class="text-center">{{ $coupon->minimum_spend ? $coupon->minimum_spend : 'N/A' }}</td>
                <td class="text-center">{{ $coupon->maximum_spend ? $coupon->maximum_spend : 'N/A' }}</td>
                <td class="text-center">{{ $coupon->limit_per_coupon ? $coupon->limit_per_coupon : 'N/A' }}</td>
                <td class="text-center">{{ $coupon->limit_per_user ? $coupon->limit_per_user : 'N/A' }}</td>
                <td class="text-center">{{ date('d-M-Y', strtotime($coupon->expiry_date))}}</td>
                <td class="text-center">{{$coupon->active ? 'Active' : 'Inactive'}}</td>
                <td class="text-center">{{ $coupon->user->full_name}}</td>
                <td class="text-center">{{ date('d-M-Y', strtotime($coupon->created_at))}}</td>
                <td class="text-center">
                  @include('backend.content.coupon.includes.actions', ['coupon' => $coupon])
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div> <!-- col-->
    </div> <!-- row-->
    <div class="row">
      <div class="col-7">
        <div class="float-left">
          {!! $coupons->total() !!}
          {{ trans_choice('Banner', $coupons->total()) }}
        </div>
      </div> <!-- col-->

      <div class="col-5">
        <div class="float-right">
          {!! $coupons->render() !!}
        </div>
      </div> <!-- col-->
    </div> <!-- row-->
  </div> <!-- card-body-->
</div> <!-- card-->
@endsection