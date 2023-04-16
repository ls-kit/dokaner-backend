@extends('backend.layouts.app')

@section('title', __('API User Created'))


@section('content')
  {{ html()->form('POST', route('admin.auth.api.user.store'))->attributes(['id' => 'api_assign_form'])->class('form-horizontal')->open() }}
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-sm-5">
          <h4 class="card-title mb-0">
            @lang('labels.backend.access.users.management')
            <small class="text-muted">@lang('labels.backend.access.users.create')</small>
          </h4>
        </div><!--col-->
      </div><!--row-->

      <hr>

      <div class="row mt-4 mb-4">
        <div class="col">
          <div class="form-group row">
            {{ html()->label('API Customer')->class('col-md-2 form-control-label')->for('api_customer') }}
            <div class="col-md-10">
              {{ html()->select('api_customer', $assignable)
                  ->class('form-control')
                  ->required() }}
            </div><!--col-->
          </div><!--form-group-->
          <div class="form-group row">
            {{ html()->label('API Token')->class('col-md-2 form-control-label')->for('api_token') }}
            <div class="col-md-10">
              <div class="input-group">
                <input type="text" name="api_token" class="form-control" value="{{old('api_token')}}" id="api_token" placeholder="API Token" readonly="readonly"
                       required="required">
                <div class="input-group-append">
                  <div class="btn btn-danger" id="generate_key">Generate</div>
                </div>
              </div>
            </div><!--col-->
          </div><!--form-group-->
        </div><!--col-->
      </div><!--row-->
    </div><!--card-body-->

    <div class="card-footer clearfix">
      <div class="row">
        <div class="col-sm-10 offset-sm-2">
          {{ form_submit(__('buttons.general.crud.create')) }}
          {{ form_cancel(route('admin.auth.api.user.index'), __('buttons.general.cancel')) }}
        </div><!--col-->
      </div><!--row-->
    </div><!--card-footer-->
  </div><!--card-->
  {{ html()->form()->close() }}
@endsection



@push('after-scripts')

    <script>

       function generateUUID()
       {
          var d = new Date().getTime();
          if( window.performance && typeof window.performance.now === "function" )
          {
             d += performance.now();
          }
          return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
             var r = (d + Math.random() * 16) % 16 | 0;
             d = Math.floor(d / 16);
             return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
          });
       }

        $(document).on('click', '#generate_key', function(){
           var api_key = generateUUID();
           $('#api_token').val(api_key);
        });

        $(document).on('submit', '#api_assign_form', function(event){
            var api_token = $('#api_token').val();
            if(api_token.length === 0){
                event.preventDefault();
                Swal.fire({
                    text:'API token must not empty',
                });
            }
        })


    </script>

@endpush