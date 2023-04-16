@extends('backend.layouts.app')

@section('title', 'Coupon User log')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="row">
      <div class="col-sm-5">
        <h3 class="card-title">Coupon User log</h3>
      </div> <!-- col-->
    </div> <!-- row-->

    <div class="row mt-4">
      <div class="col">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr class="vertical-middle">
                <th style="min-width:80px" class="text-center">Code</th>
                <th style="min-width:100px" class="text-center">Coupon Winner</th>
                <th style="min-width:75px" class="text-center">Order Amount</th>
                <th style="min-width:75px" class="text-center">Win Amount</th>
                <th style="min-width:100px" class="text-center">Wining Date</th>
              </tr>
            </thead>
            <tbody>
              @foreach($logs as $log)
              <tr class="vertical-middle">
                <td class="text-center">{{ $log->coupon_code }}</td>
                <td class="text-center">{{ $log->user->full_name}}</td>
                <td class="text-center">{{ $log->order ? $log->order->amount : 'N/A' }}</td>
                <td class="text-center">{{ $log->win_amount }}</td>
                <td class="text-center">{{ date('d-M-Y', strtotime($log->created_at))}}</td>
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
          {!! $logs->total() !!}
          {{ trans_choice('Coupon Logs', $logs->total()) }}
        </div>
      </div> <!-- col-->
      <div class="col-5">
        <div class="float-right">
          {!! $logs->render() !!}
        </div>
      </div> <!-- col-->
    </div> <!-- row-->
  </div> <!-- card-body-->
</div> <!-- card-->
@endsection