@auth

@php
$name = $logged_in_user->name;
$email = $logged_in_user->email;
$phone = $logged_in_user->phone;

$email = explode('@', $email);
$validEmail = in_array('otpLogin.com', $email) ? false : true;

@endphp
@if ((!$name || !$validEmail || !$phone) && !Route::is('frontend.index'))

<div class="container">
  <div class="alert alert-secondary fixed-bottom m-0 py-4 text-center" role="alert">
    <h4>Update your name, phone or email. Its help you to future process.</h4>
    <a class="btn btn-link" href="{{route('frontend.user.update.information',['redirect' => url()->current()])}}">Click
      here for update!</a>
  </div>
</div>

@endif

@endauth