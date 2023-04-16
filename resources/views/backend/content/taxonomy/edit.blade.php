@extends('backend.layouts.app')

@section('title', 'Taxonomy Management | Edit of Taxonomy' )

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-8">


            {{ html()->modelForm($taxonomy, 'PATCH', route('admin.taxonomy.update', $taxonomy))->class('form-horizontal')->attribute('enctype', 'multipart/form-data')->open() }}
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-5">
                            <h4 class="card-title mb-0"> Category <small class="text-muted">Edit Category</small></h4>
                        </div> <!-- col-->
                    </div> <!-- row-->

                    <hr>

                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            @php
                                $active = $taxonomy->active ? $taxonomy->active : date('Y-m-d H:i:s');
                                $isTop = $taxonomy->is_top ? $taxonomy->is_top : date('Y-m-d H:i:s');
                            @endphp
                            <div class="form-check form-check-inline">
                                {{html()->checkbox('active', old('active', $taxonomy->active), $active)->class('form-check-input')}}
                                {{ html()->label("Active")->class('form-check-label')->for('active') }}
                            </div>
                            <div class="form-check form-check-inline">
                                {{html()->checkbox('is_top', old('is_top', $taxonomy->is_top), $isTop)->class('form-check-input')}}
                                {{ html()->label("Is Top Categories")->class('form-check-label')->for('is_top') }}
                            </div>
                        </div>
                    </div> <!-- form-group row-->

                    <div class="form-group row">
                        {{html()->label('Name')->class('col-md-2 form-control-label text-right')->for('name')}}
                        <div class="col-md-10">
                            {{html()->text('name')
                                    ->class('form-control')
                                    ->id('name')
                                    ->placeholder('Category Name')
                                    ->attribute('maxlength', 255)
                                    ->required()
                                    ->autofocus()}}
                        </div> <!--  col-->
                    </div> <!--  form-group-->
                    <div class="form-group row">
                        {{html()->label('Keyword')->class('col-md-2 form-control-label text-right')->for('keyword')}}
                        <div class="col-md-10">
                            {{html()->text('keyword')
                            ->class('form-control')
                            ->id('keyword')
                            ->placeholder('Search Keyword')
                            ->attribute('maxlength', 255)
                            ->required()}}
                        </div> <!--  col-->
                    </div> <!--  form-group-->

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                @php
                                    $imgUrl = $taxonomy->picture ?? 'img/backend/no-image.gif';
                                @endphp
                                {{html()->label('Picture 130x130')->for('picture')}}
                                <label for="picture">
                                    <img src="{{asset($imgUrl)}}" class="border img-fluid img_holder" alt="Image upload">
                                </label>
                                {{html()->file('picture')->class('img_upload_field d-none')->acceptImage()}}
                            </div> <!--  col-->
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                @php
                                    $iconUrl = $taxonomy->icon ?? 'img/backend/no-image.gif';
                                @endphp
                                {{html()->label('Icon 50x50')->for('icon')}}
                                <label for="icon">
                                    <img src="{{asset($iconUrl)}}" class="border img-fluid img_holder" alt="Image upload">
                                </label>
                                {{html()->file('icon')->class('img_upload_field d-none')->id('icon')->acceptImage()}}
                            </div> <!--  col-->
                        </div>
                    </div> <!-- row -->

                    <div class="form-group row">
                        {{html()->label('Description')->class('col-md-2 form-control-label  text-right')->for('description')}}
                        <div class="col-md-10">
                            {{html()->textarea('description')
                                    ->class('form-control')
                                    ->id('description')
                                    ->placeholder('About Category')
                                    ->attributes(['maxlength'=> 800, 'rows' => 4])
                                    ->autofocus()}}
                        </div> <!-- col-->
                    </div> <!-- form-group-->

                </div> <!-- card-body-->

                <div class="card-footer">
                    <div class="row">
                        <div class="col offset-sm-2">
                            {{ form_submit(__('buttons.general.crud.update')) }}
                            {{ form_cancel(route('admin.taxonomy.index'), __('buttons.general.cancel')) }}
                        </div> <!-- col-->
                        <div class="col text-right">
                        </div> <!-- col-->
                    </div> <!-- row-->
                </div> <!-- card-footer-->
            </div> <!-- card -->
            {{ html()->closeModelForm() }}

        </div>
    </div>
@endsection


@push('after-scripts')
    <script>
        function readImageURL(input, preview) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    preview.attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $(document).ready(function () {
            $(".img_upload_field").change(function () {
                var holder = $(this).closest('.form-group').find(".img_holder");
                readImageURL(this, holder);
            });
        });
    </script>
@endpush