@extends('backend.layouts.app')

@section('title', 'Footer Brand Image Settings')

@php
    $required = html()
        ->span('*')
        ->class('text-danger');
    $demoImg = 'img/backend/front-logo.png';
@endphp

@section('content')

    {{ html()->form('POST', route('admin.front-setting.payment-qr-codes-store'))->class('form-horizontal')->attribute('enctype', 'multipart/form-data')->open() }}

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title">Footer Brand Image Settings<small class="ml-2">(update information
                            anytime)</small></h3>
                </div>
                <div class="card-body">

                    <p class="mb-4 text-center text-danger">Click the Image for updating Process</p>

                    <div class="form-group row mb-4">
                        {{ html()->label('bKash QR Code')->class('col-md-4 form-control-label text-right')->for('qr_code_bkash') }}
                        <div class="col-md-8">
                            @php($aLogo = get_setting('qr_code_bkash') ?? $demoImg)
                            <label for="qr_code_bkash">
                                <img src="{{ asset($aLogo) }}" class="border img-fluid rounded holder"
                                    style="max-width: 200px" alt="Image upload">
                            </label>
                            {{ html()->file('qr_code_bkash')->class('form-control-file image d-none')->id('qr_code_bkash')->acceptImage() }}
                        </div> <!-- col-->
                    </div> <!-- form-group-->

                    <div class="form-group row mb-4">
                        {{ html()->label('Nagad QR Code')->class('col-md-4 form-control-label text-right')->for('qr_code_nagad') }}
                        <div class="col-md-8">
                            @php($aLogo = get_setting('qr_code_nagad') ?? $demoImg)
                            <label for="qr_code_nagad">
                                <img src="{{ asset($aLogo) }}" class="border img-fluid rounded holder"
                                    style="max-width: 200px" alt="Image upload">
                            </label>
                            {{ html()->file('qr_code_nagad')->class('form-control-file image d-none')->id('qr_code_nagad')->acceptImage() }}
                        </div> <!-- col-->
                    </div> <!-- form-group-->

                    <div class="form-group">
                        {{ html()->label('Bank Details')->for('payment_bank_details') }}
                        <textarea name="payment_bank_details" class="editor form-control" id="payment_bank_details">{{ get_setting('payment_bank_details') }}</textarea>
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
    {!! script('assets/plugins/tinymce/jquery.tinymce.min.js') !!}
    {!! script('assets/plugins/tinymce/tinymce.min.js') !!}
    {!! script('assets/plugins/tinymce/editor-helper.js') !!}
    {!! script('assets/plugins/moment/moment.js') !!}
    {!! script('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') !!}
    {{ script('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js') }}

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

        // $(document).ready(function() {
        //     simple_editor('.editor', 450);
        //     $('#datepicker-autoclose').datepicker({
        //         format: "dd/mm/yyyy",
        //         clearBtn: true,
        //         autoclose: true,
        //         todayHighlight: true,
        //     });
        // });
    </script>
@endpush
