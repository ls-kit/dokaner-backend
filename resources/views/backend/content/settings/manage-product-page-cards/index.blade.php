@extends('backend.layouts.app')
@section('title', ' Manage Product Page Cards ')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-10">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title">
          Manage Product Page Cards
        </h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-4 col-sm-3">
            <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
              <a class="nav-link active" id="vert-tabs-One-tab" data-toggle="pill" href="#vert-tabs-One" role="tab"
                aria-controls="vert-tabs-One" aria-selected="true">Card One (Top)</a>
              <a class="nav-link" id="vert-tabs-Two-tab" data-toggle="pill" href="#vert-tabs-Two" role="tab"
                aria-controls="vert-tabs-Two" aria-selected="false">Card Two (By Air)</a>
              <a class="nav-link" id="vert-tabs-Three-tab" data-toggle="pill" href="#vert-tabs-Three" role="tab"
                aria-controls="vert-tabs-Three" aria-selected="false">Card Three (By Sea)</a>
            </div>
          </div>
          <div class="col-8 col-sm-9">
            <div class="tab-content" id="vert-tabs-tabContent">

              <div class="tab-pane fade show active" id="vert-tabs-One" role="tabpanel"
                aria-labelledby="vert-tabs-One-tab">
                @include("backend.content.settings.manage-product-page-cards.includes.card-one")
              </div>

              <div class="tab-pane fade" id="vert-tabs-Two" role="tabpanel" aria-labelledby="vert-tabs-Two-tab">
                @include("backend.content.settings.manage-product-page-cards.includes.card-two")
              </div>

              <div class="tab-pane fade" id="vert-tabs-Three" role="tabpanel" aria-labelledby="vert-tabs-Three-tab">
                @include("backend.content.settings.manage-product-page-cards.includes.card-three")
              </div>

            </div>
          </div>
        </div>
      </div>
      <!-- /.card -->
    </div>
  </div> <!-- col -->


</div> <!-- .row -->

@endsection


@push('after-scripts')
    {!! script('assets/plugins/tinymce/jquery.tinymce.min.js') !!}
    {!! script('assets/plugins/tinymce/tinymce.min.js') !!}
    {!! script('assets/plugins/tinymce/editor-helper.js') !!}
    {!! script('assets/plugins/moment/moment.js') !!}
    {!! script('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') !!}
    {{ script('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js') }}

    <script>
        $(document).ready(function() {
            simple_editor('.editor', 450);
            $('#datepicker-autoclose').datepicker({
                format: "dd/mm/yyyy",
                clearBtn: true,
                autoclose: true,
                todayHighlight: true,
            });
        });
    </script>
@endpush
