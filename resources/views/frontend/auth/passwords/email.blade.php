@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.frontend.passwords.reset_password_box_title'))

@section('content')
<div class="main_content">
  <div class="login_register_wrap section">
    <div class="container">
      <div class="row justify-content-center align-items-center">

        <div class="col-xl-6 col-md-6">
          <div class="login_wrap">
            <div class="padding_eight_all bg-white">
              <div class="heading_s1">
                <h3>Reset Password</h3>

                @if(session('status'))
                <div class="alert alert-success">
                  {{ session('status') }}
                </div>
                @endif
              </div>

              {{ html()->form('POST', route('frontend.auth.password.email.post'))->open() }}

              <div class="form-group">
                {{ html()->label(__('validation.attributes.frontend.email'))->for('email') }}
                {{ html()->email('email')
                          ->class('form-control')
                          ->placeholder(__('validation.attributes.frontend.email'))
                          ->attribute('maxlength', 191)
                          ->required()
                          ->autofocus() }}
              </div>
              <div class="form-group">
                {{ form_submit(__('labels.frontend.passwords.send_password_reset_link_button'),'btn btn-fill-out') }}
              </div>
              {{ html()->form()->close() }}
              <div class="different_login">
                <span> or</span>
              </div>
              <div class="form-note text-center"> Have an Account? <a href="{{route('frontend.auth.login')}}">Sign In
                  now</a></div>
            </div>
          </div>
        </div>


      </div><!-- row -->
    </div>
  </div>
</div>
@endsection