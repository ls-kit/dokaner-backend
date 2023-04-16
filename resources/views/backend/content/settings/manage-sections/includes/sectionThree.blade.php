<div class="card mb-3">
  {{ html()->form('POST', route('admin.front-setting.manage.section.store'))->open() }}
  <div class="card-header with-border">
    <h3 class="card-title">Section Three</h3>
  </div>
  <div class="card-body">
    <div class="form-group">
      <div class="form-check form-check-inline">
        {{html()->radio('section_three_active', get_setting('section_three_active') === 'enable', 'enable')
        ->id('section_three_enable')
        ->class('form-check-input')}}
        {{ html()->label("Section Enable")->class('form-check-label')->for('section_three_enable') }}
      </div>
      <div class="form-check form-check-inline">
        {{html()->radio('section_three_active', get_setting('section_three_active') === 'disable', 'disable')
        ->id('section_three_disable')
        ->class('form-check-input')}}
        {{ html()->label("Section Disable")->class('form-check-label')->for('section_three_disable') }}
      </div>
    </div> <!-- form-group-->

    <div class="form-group">
      {{html()->label('Section Title')->for('section_three_title')}}
      {{html()->text('section_three_title',
      get_setting('section_three_title'))->class('form-control')->placeholder('Section Title')->required(true)}}
    </div> <!-- form-group-->


    <div class="form-group">
      <div class="form-check form-check-inline">
        {{html()->radio('section_three_query_type', get_setting('section_three_query_type', true),
        'cat_query')->id('section_three_cat_query')->class('form-check-input')}}
        {{ html()->label("Category Query")->class('form-check-label')->for('section_three_cat_query') }}
      </div>
      <div class="form-check form-check-inline">
        {{html()->radio('section_three_query_type', get_setting('section_three_query_type') == 'search_query',
        'search_query')->id('section_three_search_query')->class('form-check-input')}}
        {{ html()->label("Search Query")->class('form-check-label')->for('section_three_search_query') }}
      </div>
    </div> <!-- form-group-->

    <div class="form-group">
      {{html()->label('Query last url after slash')->for('section_three_query_url')}}
      {{html()->text('section_three_query_url',
      get_setting('section_three_query_url'))->class('form-control')->placeholder('/cat-slug?page=3 or
      /bag?page=3')->required(true)}}
    </div> <!-- form-group-->


    <div class="form-group">
      {{html()->label('Query Limit')->for('section_three_query_limit')}}
      {{html()->number('section_three_query_limit',
      get_setting('section_three_query_limit'))->class('form-control')->placeholder('50')->required(true)}}
    </div> <!-- form-group-->

    <div class="form-group">
      {{html()->button('Update')->class('btn w-25 btn-primary')}}
    </div> <!-- form-group-->

  </div> <!--  .card-body -->
  {{ html()->form()->close() }}
</div> <!--  .card -->