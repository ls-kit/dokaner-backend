@component('mail::message')
# Customer Placed a Order

Mr./Mrs. {{$full_name}} Placed a Order.

@php 
$amount = floating($amount);
$needToPay = floating($needToPay);
@endphp

## Amount: {{$amount}}
## Paid: {{$needToPay}}
## Due: {{$dueForProducts ? $dueForProducts : ($amount - $needToPay)}}


@component('mail::button', ['url' => url("/admin/order/{$order_id}")])
View Order
@endcomponent

Thanks,<br>
<a href="{{url('/')}}">
  {{ config('app.name') }}
</a>
@endcomponent
