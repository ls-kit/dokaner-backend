@extends('frontend.layouts.app')

@section('title', __('labels.frontend.auth.login_box_title'))

@section('content')
<div class="main_content">
  <!-- START LOGIN SECTION -->
  <div class="section" style="padding-bottom: 50px">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-xl-5 col-md-5">
          <div class="login_wrap loginSubmitCard">
            <div class="padding_eight_all bg-white">
              <div class="heading_s1">
                <h3>Login</h3>
              </div>

              @include('frontend.auth.includes.socialite')

              <div class="different_login">
                <span> or</span>
              </div>

              <div class="loginWithEmail">
                {{ html()->form('POST', route('frontend.auth.login.post'))->open() }}
                <div class="form-group">
                  {{ html()->email('email')
                          ->class('form-control')
                          ->placeholder(__('validation.attributes.frontend.email'))
                          ->attribute('maxlength', 191)
                          ->required() }}
                </div>
                <div class="form-group">
                  {{ html()->password('password')
                        ->class('form-control')
                        ->placeholder(__('validation.attributes.frontend.password'))
                        ->required() }}
                </div>
                <div class="login_footer form-group">
                  {{html()->hidden('remember')->value(1)}}
                  <a
                    href="{{ route('frontend.auth.password.reset') }}">@lang('labels.frontend.passwords.forgot_password')</a>
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-fill-out btn-block" name="login">Login
                  </button>
                </div>
                {{ html()->form()->close() }}
              </div>

              <div class="different_login">
                <span> or</span>
              </div>

              <div class="form-note text-center">New to {{app_name()}}? <a class="btn btn-link p-0"
                href="{{route('frontend.auth.register')}}">Create an account</a></div>
            </div>
          </div>

          <div class="login_wrap otpSubmitCard  d-none">
            <div class="padding_eight_all bg-white">
              <div class="heading_s1">
                <h4>
                  <button type="button" id="backToLoginCard" class="btn btn-default text-danger p-0 mr-2">
                    <i class="icon-arrow-left"></i> Back
                  </button>
                  Verify Your Phone Number
                </h4>
              </div>

              <div class="otp_submit_form">
                <h6 class="text-center"> We just sent you an SMS with an OTP code. </h6>
                <p class="text-center"> To complete your phone number login, please enter the 4-digit OTP code below.
                </p>
                <div class="form-group">
                  <input type="hidden" name="userId" class="userId">
                  <input type="hidden" name="userPhone" class="userPhone">
                  <input type="text" name="otp_code" class="form-control text-center otp_code" placeholder="----"
                    maxlength="4" required="true" autofocus="true">
                  <small id="phone" class="form-text text-muted text-center">e.g: 1234</small>
                </div> <!-- form-group -->
                <div class="form-group">
                  <button type="submit" id="otpCodeSubmitBtn" class="btn btn-fill-out btn-block">Resend Code
                    30</button>
                </div>
              </div>

            </div>
          </div>

        </div>
      </div>
    </div>
  </div> <!-- END LOGIN SECTION -->



</div>
@endsection