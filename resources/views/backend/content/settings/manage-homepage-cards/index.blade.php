@extends('backend.layouts.app')
@section('title', ' Manage Homepage Cards ')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Manage Homepage Cards
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-4 col-sm-3">
                            <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist"
                                aria-orientation="vertical">
                                <a class="nav-link active" id="vert-tabs-One-tab" data-toggle="pill" href="#vert-tabs-One"
                                    role="tab" aria-controls="vert-tabs-One" aria-selected="true">Card One</a>
                                <a class="nav-link" id="vert-tabs-Two-tab" data-toggle="pill" href="#vert-tabs-Two"
                                    role="tab" aria-controls="vert-tabs-Two" aria-selected="false">Card Two</a>
                                <a class="nav-link" id="vert-tabs-Three-tab" data-toggle="pill" href="#vert-tabs-Three"
                                    role="tab" aria-controls="vert-tabs-Three" aria-selected="false">Card Three</a>
                                <a class="nav-link" id="vert-tabs-Four-tab" data-toggle="pill" href="#vert-tabs-Four"
                                    role="tab" aria-controls="vert-tabs-Four" aria-selected="false">Card Four</a>
                                <a class="nav-link" id="vert-tabs-Five-tab" data-toggle="pill" href="#vert-tabs-Five"
                                    role="tab" aria-controls="vert-tabs-Five" aria-selected="false">Exclusive Offer (Sign-in Section)</a>
                            </div>
                        </div>
                        <div class="col-8 col-sm-9">
                            <div class="tab-content" id="vert-tabs-tabContent">

                                @php
                                    $demoImg = 'img/backend/front-logo.png';
                                @endphp

                                <div class="tab-pane fade show active" id="vert-tabs-One" role="tabpanel"
                                    aria-labelledby="vert-tabs-One-tab">
                                    @include('backend.content.settings.manage-homepage-cards.includes.card-one')
                                </div>

                                <div class="tab-pane fade" id="vert-tabs-Two" role="tabpanel"
                                    aria-labelledby="vert-tabs-Two-tab">
                                    @include('backend.content.settings.manage-homepage-cards.includes.card-two')
                                </div>

                                <div class="tab-pane fade" id="vert-tabs-Three" role="tabpanel"
                                    aria-labelledby="vert-tabs-Three-tab">
                                    @include('backend.content.settings.manage-homepage-cards.includes.card-three')
                                </div>

                                <div class="tab-pane fade" id="vert-tabs-Four" role="tabpanel"
                                    aria-labelledby="vert-tabs-Four-tab">
                                    @include('backend.content.settings.manage-homepage-cards.includes.card-four')
                                </div>

                                <div class="tab-pane fade" id="vert-tabs-Five" role="tabpanel"
                                    aria-labelledby="vert-tabs-Five-tab">
                                    @include('backend.content.settings.manage-homepage-cards.includes.exclusive-offer')
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
