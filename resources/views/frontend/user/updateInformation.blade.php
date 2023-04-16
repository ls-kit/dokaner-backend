@extends('frontend.layouts.app')

@section('title', 'Update Your Information')

@section('content')
<div class="row justify-content-center align-items-center mb-3">
  <div class="col col-sm-5 align-self-center">
    <div class="card">
      <div class="card-header">
        <strong>
          @lang('Information Update Form')
        </strong>
      </div>
      <div class="card-body">
        @php
        $name = $logged_in_user->name;
        $name = $name == ' ' ? '' : $name;
        $email = $logged_in_user->email;
        $phone = $logged_in_user->phone;

        $explodeEmail = explode('@', $email);
        $validEmail = in_array('otpLogin.com', $explodeEmail) ? false : true;
        $email = $validEmail ? $email : '';
        @endphp

        {{ html()->form('POST', route('frontend.user.update.information.store'))->open() }}

        {{html()->hidden('redirect')->value($redirect)}}
        <div class="form-group">
          {{html()->label('Your Name')->for('name')}}
          {{ html()->text('name', $name)
                    ->class('form-control')
                    ->placeholder('Your Name')
                    ->attribute('maxlength', 191)
                    ->required() }}
        </div>
        @if(!$email)
        <div class="form-group">
          {{html()->label('Your Email')->for('email')}}
          {{ html()->email('email',$email )
                    ->class('form-control')
                    ->placeholder('Your Email')
                    ->attribute('maxlength', 191)
                    ->required() }}
        </div>
        @endif
        @if(!$phone)
        <div class="form-group">
          {{html()->label('Your Phone')->for('phone')}}
          {{ html()->text('phone', $phone)
                    ->class('form-control')
                    ->placeholder(__('validation.attributes.frontend.phone'))
                    ->attribute('maxlength', 191)
                    ->required() }}
        </div>
        @endif
        <div class="form-group">
          <button type="submit" title="Update" class="btn btn-fill-out" name="submit" value="Submit">Update
          </button>
        </div>
        <div class="col-md-12">
          <div id="alert-msg" class="alert-msg text-center"></div>
        </div>

        {{ html()->form()->close() }}
      </div>
      <!--card body-->
    </div><!-- card -->
  </div><!-- col-xs-12 -->
</div><!-- row -->
@endsection