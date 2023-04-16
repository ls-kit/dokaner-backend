@extends('frontend.layouts.app')

@section('title', __('labels.frontend.contact.box_title'))

@section('content')

<div class="breadcrumb_section bg_gray page-title-mini">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6">
        <div class="page-title">
          <h1>Contact</h1>
        </div>
      </div>
      <div class="col-md-6">
        <ol class="breadcrumb justify-content-md-end">
          <li class="breadcrumb-item"><a href="{{route('frontend.index')}}">Home</a></li>
          <li class="breadcrumb-item active">Contact</li>
        </ol>
      </div>
    </div>
  </div>
</div> <!-- END SECTION BREADCRUMB -->

<!-- START MAIN CONTENT -->
<div class="main_content">

  <div class="section">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="heading_s1">
            <h2>Get In touch</h2>
          </div>
          {!! $contact->post_content !!}
          <div class="field_form">

            {{ html()->form('POST', route('frontend.contact.send'))->open() }}

            <div class="form-group">
              {{ html()->text('name', optional(auth()->user())->name)
                        ->class('form-control')
                        ->placeholder(__('validation.attributes.frontend.name'))
                        ->attribute('maxlength', 191)
                        ->required()
                        ->autofocus() }}
            </div>
            <div class="form-group">
              {{ html()->email('email', optional(auth()->user())->email)
                        ->class('form-control')
                        ->placeholder(__('validation.attributes.frontend.email'))
                        ->attribute('maxlength', 191)
                        ->required() }}
            </div>
            <div class="form-group">
              {{ html()->text('phone')
                        ->class('form-control')
                        ->placeholder(__('validation.attributes.frontend.phone'))
                        ->attribute('maxlength', 191)
                        ->required() }}
            </div>
            <div class="form-group">
              {{ html()->textarea('message')
                        ->class('form-control')
                        ->placeholder(__('validation.attributes.frontend.message'))
                        ->attribute('rows', 5)
                        ->required() }}
            </div>
            <div class="form-group">
              <button type="submit" title="Submit Your Message!" class="btn btn-block btn-fill-out" name="submit"
                value="Submit">Send Message
              </button>
            </div>
            <div class="col-md-12">
              <div id="alert-msg" class="alert-msg text-center"></div>
            </div>

            {{ html()->form()->close() }}

          </div>
        </div>
        <div class="col-lg-6 pt-2 pt-lg-0 mt-4 mt-lg-0">
          <div class="embed-responsive embed-responsive-4by3">
            <iframe class="embed-responsive-item"
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1534.8709398149958!2d90.3504066255784!3d23.798717478137135!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c0e600466c37%3A0x1908c05422acd56a!2sFair%20Plaza!5e0!3m2!1sen!2sbd!4v1612178462583!5m2!1sen!2sbd"></iframe>
          </div>
          {{-- <div id="map" class="contact_map2" data-zoom="16" data-latitude="23.7987773" data-longitude="90.349518"
            data-icon="{{asset('assets/images/marker.png')}}">
        </div> --}}
      </div>
    </div>
  </div>
</div>
<!-- END SECTION CONTACT -->


</div>
<!-- END MAIN CONTENT -->

@endsection

@push('before-scripts')
{{-- {{script('https://maps.googleapis.com/maps/api/js?key=AIzaSyD7TypZFTl4Z3gVtikNOdGSfNTpnmq-ahQ&amp;callback=initMap')}}
--}}
@endpush

@push('after-scripts')
@if(config('access.captcha.contact'))
{{-- @captchaScripts --}}
@endif
@endpush