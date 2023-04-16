@component('mail::message')

  Mr./Mrs. {{$full_name}}, Payment for Due Invoice .

  @php
    $amount = floating($amount);
  @endphp

  ## Payment Amount: {{$amount}}


  @component('mail::button', ['url' => url("/admin/invoice/{$invoice_id}")])
    View Invoice
  @endcomponent

  Thanks,<br>
  <a href="{{url('/')}}">
    {{ config('app.name') }}
  </a>
@endcomponent
