@extends('backend.layouts.app')

@section('title', 'Pop-up Banner Settings')

@php
    $required = html()
        ->span('*')
        ->class('text-danger');
    $demoImg = 'img/backend/front-logo.png';
@endphp

@section('content')

    {{ html()->form('POST', route('admin.front-setting.footer-brand-settings-store'))->class('form-horizontal')->attribute('enctype', 'multipart/form-data')->open() }}

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title">Pop-up Banner Settings<small class="ml-2">(update information
                            anytime)</small></h3>
                </div>
                <div class="card-body">

                    <p class="mb-4 text-center text-danger">Click the Image for updating Process</p>

                    <div class="form-group ml-5">
                        <div class="form-check form-check-inline">
                            {{ html()->radio('popup_banner_active', get_setting('popup_banner_active') === 'enable', 'enable')->id('popup_banner_enable')->class('form-check-input') }}
                            {{ html()->label('Banner Enable')->class('form-check-label')->for('popup_banner_enable') }}
                        </div>
                        <div class="form-check form-check-inline">
                            {{ html()->radio('popup_banner_active', get_setting('popup_banner_active') === 'disable', 'disable')->id('popup_banner_disable')->class('form-check-input') }}
                            {{ html()->label('Banner Disable')->class('form-check-label')->for('popup_banner_disable') }}
                        </div>
                    </div> <!-- form-group-->

                    <div class="form-group row mb-4">
                        {{ html()->label('Pop-up Banner')->class('col-md-4 form-control-label text-right')->for('popup_banner') }}
                        <div class="col-md-8">
                            @php($aLogo = get_setting('popup_banner') ?? $demoImg)
                            <label for="popup_banner">
                                <img src="{{ asset($aLogo) }}" class="border img-fluid rounded holder"
                                    style="max-width: 200px" alt="Image upload">
                            </label>
                            {{ html()->file('popup_banner')->class('form-control-file image d-none')->id('popup_banner')->acceptImage() }}
                        </div> <!-- col-->
                    </div> <!-- form-group-->

                    <div class="form-group row mb-4">
                        <div class="col-md-8 offset-md-4">
                            {{ html()->button('Update')->class('btn btn-sm btn-success') }}
                        </div> <!-- col-->
                    </div> <!-- form-group-->
                </div> <!--  .card-body -->
            </div> <!--  .card -->
        </div> <!-- .col-md-9 -->
    </div> <!-- .row -->

    {{ html()->form()->close() }}
@endsection




@push('after-scripts')
    {{ script('assets/js/jscolor.js') }}

    <script>
        function readImageURL(input, preview) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $(document).ready(function() {
            $(".image").change(function() {
                holder = $(this).closest('.form-group').find('.holder');
                readImageURL(this, holder);
            });
        });
    </script>
@endpush
