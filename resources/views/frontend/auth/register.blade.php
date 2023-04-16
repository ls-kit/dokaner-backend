@extends('frontend.layouts.app')

@section('title', __('labels.frontend.auth.register_box_title'))

@section('content')

<div class="main_content">
  <div class="login_register_wrap section"  style="padding-bottom: 50px">
    <div class="container">
      <div class="row justify-content-center">

        <div class="col-xl-5 col-md-5">
          <div class="card">
            <div class="card-body">
              <div class="padding_eight_all bg-white">
                <div class="heading_s1">
                  <div class="text-center border-0">
                    <h3 class="my-2" style="text-transform: none;">@lang('Create an account')</h3>
                    <p class="login_info_msg">to continue to <b>{{app_name()}}</b></p>
                  </div>
                </div>

                @include('frontend.auth.includes.socialite')

                <div class="different_login">
                  <span> or</span>
                </div>

                {{ html()->form('POST', route('frontend.auth.register.post'))->open() }}
                <div class="form-group">
                  {{ html()->label(__('First Name'))->for('first_name') }}
                  {{ html()->text('first_name')
                        ->class('form-control')
                        ->placeholder(__('First Name'))
                        ->attribute('maxlength', 191)
                        ->required()}}
                </div> <!-- form-group-->
                <div class="form-group">
                  {{ html()->label(__('Last Name'))->for('last_name') }}
                  {{ html()->text('last_name')
                        ->class('form-control')
                        ->placeholder(__('Last Name'))
                        ->attribute('maxlength', 191)
                        ->required()}}
                </div> <!-- form-group-->

                <div class="form-group">
                  {{ html()->label(__('validation.attributes.frontend.email'))->for('email') }}
                  {{ html()->email('email')
                            ->class('form-control')
                            ->placeholder(__('validation.attributes.frontend.email'))
                            ->attribute('maxlength', 191)
                            ->required() }}
                </div> <!-- form-group -->

                <div class="form-group">
                  {{ html()->label(__('validation.attributes.frontend.password'))->for('password') }}
                  {{ html()->password('password')
                          ->class('form-control')
                          ->placeholder(__('validation.attributes.frontend.password'))
                          ->attribute('autocomplete', 'new-password')
                          ->required() }}
                </div> <!-- form-group -->

                <div class="form-group">
                  {{ html()->label(__('validation.attributes.frontend.password_confirmation'))->for('password_confirmation') }}
                  {{ html()->password('password_confirmation')
                          ->class('form-control')
                          ->placeholder(__('validation.attributes.frontend.password_confirmation'))
                          ->attribute('autocomplete', 'new-password')
                          ->required() }}
                </div> <!-- form-group -->

                <div class="form-group">
                  @if(config('access.captcha.registration'))
                  @captcha
                  {{ html()->hidden('captcha_status', 'true') }}
                  @endif
                </div> <!-- form-group -->

                <div class="form-group">
                  <button type="submit" class="btn btn-block btn-fill-out"> @lang('Register')</button>
                </div> <!-- form-group// -->
                {{ html()->form()->close() }}

                <div class="form-group text-center">
                  <p class="login_info_msg">@lang('Have an Account?') <a href="{{route('frontend.auth.login')}}"
                      class="btn p-0 text-primary">@lang('Login')</a>
                  </p>
                </div> <!-- form-group -->

              </div>
            </div>
          </div>
        </div> <!-- col -->


      </div> <!-- row -->
    </div> <!-- container -->
  </div> <!-- login_register_wrap -->
</div> <!-- login -->
@endsection

@push('after-styles')
<style>
  .login {
    background-image: url('{{asset('img/frontend/carousel/carousel-2.jpg')}}');
    background-position: center;
    background-size: cover;
    height: 100vh;
    padding: 50px 0;
  }

  .get-started {
    display: none;
  }
</style>
@endpush

{{-- @push('after-scripts')
@if(config('access.captcha.registration'))
@captchaScripts
@endif
@endpush --}}