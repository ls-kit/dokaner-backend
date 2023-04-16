<div class="card mb-3">
    {{ html()->form('POST', route('admin.setting.socialStore'))->open() }}
    <div class="card-header with-border">
      <h3 class="card-title">API Configuration</h3>
    </div>
    <div class="card-body">
      <div class="form-group">
        {{html()->label('API URL')->for('image_search_api_url')}}
        {{html()->text('image_search_api_url', get_setting('image_search_api_url'))
        ->class('form-control')
        ->required(false)
        ->placeholder('API URL')}}
      </div> <!-- form-group-->
      <div class="form-group">
        {{html()->label('API KEY')->for('image_search_api_key')}}
        {{html()->text('image_search_api_key', get_setting('image_search_api_key'))
        ->class('form-control')
        ->required(false)
        ->placeholder('API KEY')}}
      </div> <!-- form-group-->
      <div class="form-group">
        {{html()->label('API Secret')->for('image_search_api_secret')}}
        {{html()->text('image_search_api_secret', get_setting('image_search_api_secret'))
        ->class('form-control')
        ->required(false)
        ->placeholder('API Secret')}}
      </div> <!-- form-group-->
    </div> <!--  .card-body -->


    <div class="card-footer">
      <div class="form-group">
        {{html()->button('Update')->class('btn btn-block  btn-primary')}}
      </div> <!-- form-group-->
    </div> <!--  .card-footer -->
    {{ html()->form()->close() }}
</div> <!--  .card -->
