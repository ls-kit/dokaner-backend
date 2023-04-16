@extends('backend.layouts.app')

@section('title', 'Create Category')

@section('content')

    <div class="row justify-content-center">
        <div class="col-sm-8">
            {{ html()->form('POST', route('admin.taxonomy.store'))->class('form-horizontal')->attribute('enctype', 'multipart/form-data')->open() }}
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <h2 class="card-title"> Manage Categories <small class="text-muted">Create Category</small></h2>
                        </div> <!-- col -->
                    </div> <!-- row -->
                    <hr>
                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <div class="form-check">
                                {{html()->checkbox('active', old('active', true), date('Y-m-d H:i:s'))->class('form-check-input')}}
                                {{ html()->label(ucwords('active'))->class('form-check-label')->for('active') }}
                            </div>
                        </div> <!-- col-->
                    </div> <!-- form-group-->
                    <div class="form-group row">
                        {{html()->label('Name '. html()->span('*')->class('text-danger'))->class('col-md-2 form-control-label')->for('name')}}
                        <div class="col-md-10">
                            {{html()->text('name')->class('form-control')->placeholder('Name')->required()->attribute('autofocus')}}
                        </div> <!-- col-->
                    </div> <!-- form-group-->
                    <div class="form-group row">
                        {{html()->label('Icon '. html()->span('*')->class('text-danger'))->class('col-md-2 form-control-label')->for('icon')}}
                        <div class="col-md-10">
                            {{html()->text('icon')->class('form-control')->placeholder('Icon')}}
                        </div> <!-- col-->
                    </div> <!-- form-group-->
                    <div class="form-group row">
                        {{html()->label('Parent')->class('col-md-2 form-control-label')->for('parent_id')}}
                        <div class="col-md-10">
                            {{html()->select('parent_id', $taxonomies->prepend('- Select Parent -', ''), '')->class('form-control selectTable')}}
                        </div> <!-- col-->
                    </div> <!-- form-group-->
                    <div class="form-group row">
                        {{html()->label('Picture')->class('col-md-2 form-control-label')->for('picture')}}
                        <div class="col-md-3">
                            <label for="picture" title="Upload Picture">
                                <img src="{{asset('img/backend/no-image.gif')}}" class="img-fluid" id="holder" alt="upload-picture">
                            </label>
                            <input type="file" name="picture" id="picture" class="d-none">
                        </div> <!-- col-->
                    </div> <!-- form-group-->
                    <div class="form-group row">
                        {{html()->label('Description')->class('col-md-2 form-control-label')->for('description')}}
                        <div class="col-md-10">
                            {{ html()->textarea('description')->class('form-control')->attribute('rows', 4)->placeholder('Description') }}
                        </div> <!-- col-->
                    </div> <!-- form-group-->
                    <div class="form-group row">
                        <div class="col-md-10 offset-md-2">
                            {{ form_submit(__('buttons.general.crud.create')) }}
                            {{ form_cancel(route('admin.taxonomy.index'), __('buttons.general.cancel')) }}
                        </div> <!-- col -->
                    </div> <!-- form-group-->
                </div> <!-- card-body-->
            </div> <!-- card -->
            {{ html()->form()->close() }}
        </div> <!-- col-sm-8-->
    </div> <!-- row-->
@endsection

@push('after-scripts')
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
