<div class="card mb-3">
  {{ html()->form('POST', route('admin.setting.socialStore'))->open() }}
  <div class="card-header with-border">
    <h3 class="card-title">API Configuration</h3>
  </div>
  <div class="card-body">
    <div class="form-group">
      {{html()->label('MyBD API URL')->for('mybd_api_url')}}
      {{html()->text('mybd_api_url', get_setting('mybd_api_url'))
      ->class('form-control')
      ->required(true)
      ->placeholder('MyBD API URL')}}
    </div> <!-- form-group-->
    <div class="form-group">
      {{html()->label('MyBD API Token')->for('mybd_api_token')}}
      {{html()->text('mybd_api_token', get_setting('mybd_api_token'))
      ->class('form-control')
      ->required(true)
      ->placeholder('MyBD API Token')}}
    </div> <!-- form-group-->
  </div> <!--  .card-body -->


  <div class="card-footer">
    <div class="form-group">
      {{html()->button('Update')->class('btn btn-block  btn-primary')}}
    </div> <!-- form-group-->
  </div> <!--  .card-footer -->
  {{ html()->form()->close() }}
</div> <!--  .card -->