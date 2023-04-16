@component('mail::message')
# Dear {{$notifiable->full_name}}

{!! $data !!}

Thanks,<br>
<a href="https://1688cart.com">
{{ config('app.name') }}
</a>
@endcomponent
