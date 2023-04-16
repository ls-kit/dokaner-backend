@extends('backend.layouts.app')
@section('title', ' Manage Featured Categories')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Manage Featured Categories
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-4 col-sm-3">
                            <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist"
                                aria-orientation="vertical">
                                <a class="nav-link active" id="vert-tabs-One-tab" data-toggle="pill" href="#vert-tabs-One"
                                    role="tab" aria-controls="vert-tabs-One" aria-selected="true">Featured Category</a>
                                <a class="nav-link" id="vert-tabs-Two-tab" data-toggle="pill" href="#vert-tabs-Two"
                                    role="tab" aria-controls="vert-tabs-Two" aria-selected="false">Category One</a>
                                <a class="nav-link" id="vert-tabs-Three-tab" data-toggle="pill" href="#vert-tabs-Three"
                                    role="tab" aria-controls="vert-tabs-Three" aria-selected="false">Category Two</a>
                                <a class="nav-link" id="vert-tabs-Four-tab" data-toggle="pill" href="#vert-tabs-Four"
                                    role="tab" aria-controls="vert-tabs-Four" aria-selected="false">Category Three</a>
                                <a class="nav-link" id="vert-tabs-Five-tab" data-toggle="pill" href="#vert-tabs-Five"
                                    role="tab" aria-controls="vert-tabs-Five" aria-selected="false">Category Four</a>
                                <a class="nav-link" id="vert-tabs-Six-tab" data-toggle="pill" href="#vert-tabs-Six"
                                    role="tab" aria-controls="vert-tabs-Six" aria-selected="false">Category Five</a>
                                <a class="nav-link" id="vert-tabs-Seven-tab" data-toggle="pill" href="#vert-tabs-Seven"
                                    role="tab" aria-controls="vert-tabs-Seven" aria-selected="false">Category Six</a>
                            </div>
                        </div>
                        <div class="col-8 col-sm-9">
                            <div class="tab-content" id="vert-tabs-tabContent">

                                @php
                                    $demoImg = 'img/backend/front-logo.png';
                                @endphp

                                <div class="tab-pane fade show active" id="vert-tabs-One" role="tabpanel"
                                    aria-labelledby="vert-tabs-One-tab">
                                    @include('backend.content.settings.manage-featured-categories.includes.category-featured')
                                </div>

                                <div class="tab-pane fade" id="vert-tabs-Two" role="tabpanel"
                                    aria-labelledby="vert-tabs-Two-tab">
                                    @include('backend.content.settings.manage-featured-categories.includes.category-one')
                                </div>

                                <div class="tab-pane fade" id="vert-tabs-Three" role="tabpanel"
                                    aria-labelledby="vert-tabs-Three-tab">
                                    @include('backend.content.settings.manage-featured-categories.includes.category-two')
                                </div>

                                <div class="tab-pane fade" id="vert-tabs-Four" role="tabpanel"
                                    aria-labelledby="vert-tabs-Four-tab">
                                    @include('backend.content.settings.manage-featured-categories.includes.category-three')
                                </div>

                                <div class="tab-pane fade" id="vert-tabs-Five" role="tabpanel"
                                    aria-labelledby="vert-tabs-Five-tab">
                                    @include('backend.content.settings.manage-featured-categories.includes.category-four')
                                </div>

                                <div class="tab-pane fade" id="vert-tabs-Six" role="tabpanel"
                                    aria-labelledby="vert-tabs-Six-tab">
                                    @include('backend.content.settings.manage-featured-categories.includes.category-five')
                                </div>

                                <div class="tab-pane fade" id="vert-tabs-Seven" role="tabpanel"
                                    aria-labelledby="vert-tabs-Seven-tab">
                                    @include('backend.content.settings.manage-featured-categories.includes.category-six')
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
