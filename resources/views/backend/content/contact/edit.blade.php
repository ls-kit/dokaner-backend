@extends('backend.layouts.app')

@section('title', 'Taxonomy Management | Edit of Taxonomy' )

@section('content')
    {{ html()->modelForm($taxonomy, 'PATCH', route('admin.taxonomy.update', $taxonomy))->class('form-horizontal')->attribute('enctype', 'multipart/form-data')->open() }}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0"> Category <small class="text-muted">Edit Category</small></h4>
                </div> <!-- col-->
            </div> <!-- row-->

            <hr>

            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        <div class="col-md-10 offset-md-2">
                            <div class="checkbox d-flex align-items-center">
                                {{ html()->label( html()->checkbox('active', $taxonomy->active, $taxonomy->active ?? date('Y-m-d H:i:s') )
                                                    ->class('switch-input')
                                                    ->id('active')
                                                . '<span class="switch-slider" data-checked="on" data-unchecked="off"></span>')
                                            ->class('switch switch-label switch-pill switch-primary mr-2')
                                        ->for('active') }}
                                {{ html()->label(ucwords('active'))->for('active') }}
                            </div>
                        </div> <!--  col-->
                    </div> <!--  form-group-->
                    <div class="form-group row">
                        {{html()->label('Name')->class('col-md-2 form-control-label')->for('name')}}
                        <div class="col-md-10">
                            {{html()->text('name')->class('form-control')->id('name')->placeholder('Category Name')
                            ->attribute('maxlength', 255)
                            ->required()
                            ->autofocus()
                            }}
                        </div> <!--  col-->
                    </div> <!--  form-group-->
                    <div class="form-group row">
                        {{html()->label('Parent')->class('col-md-2 form-control-label')->for('parent_id')}}
                        <div class="col-md-3">
                            {{html()->select('parent_id',$taxonomies->prepend('- Select Parent -', ''), $taxonomy->parent_id)->class('form-control')->id('parent_id')}}
                        </div> <!--  col-->
                    </div> <!--  form-group-->

                    <div class="form-group row" id="for_New_upload">
                        {{html()->label('Picture')->class('col-md-2 form-control-label')->for('picture')}}
                        <div class="col-md-3">
                            <label for="picture">
                                @php($imgUrl = $taxonomy->picture ?? 'img/backend/no-image.gif')
                                <img src="{{asset($imgUrl)}}" class="border img-fluid" id="holder" alt="Image upload">
                            </label>
                            {{html()->file('picture')->class('form-control-file d-none')->id('picture')->acceptImage()}}
                        </div> <!--  col-->
                    </div> <!--  form-group-->

                    <div class="form-group row">
                        {{html()->label('Description')->class('col-md-2 form-control-label')->for('description')}}
                        <div class="col-md-10">
                            {{html()->textarea('description')->class('form-control')->id('description')->placeholder('About Category')
                            ->attributes(['maxlength'=> 800, 'rows' => 4])
                            ->required()
                            ->autofocus()
                            }}
                        </div> <!-- col-->
                    </div> <!-- form-group-->
                </div> <!-- col-->
            </div> <!-- row-->

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
@endsection

@push('after-styles')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>

@endpush

@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <script !src="">
        function readImageURL(input, preview) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    preview.attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $(document).ready(function () {
            $("#picture").change(function () {
                readImageURL(this, $('#holder'));
            });
        });
    </script>
@endpush

