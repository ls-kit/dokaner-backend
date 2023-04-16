<div class="card mb-3">
  {{ html()->form('POST', route('admin.front-setting.manage.section.store'))->open() }}
  <div class="card-header with-border">
    <h3 class="card-title">Section Five</h3>
  </div>
  <div class="card-body">
    <div class="form-group">
      <div class="form-group">
        <div class="form-check form-check-inline">
          {{html()->radio('section_five_active', get_setting('section_five_active') === 'enable', 'enable')
          ->id('section_five_enable')
          ->class('form-check-input')}}
          {{ html()->label("Section Enable")->class('form-check-label')->for('section_five_enable') }}
        </div>
        <div class="form-check form-check-inline">
          {{html()->radio('section_five_active', get_setting('section_five_active') === 'disable', 'disable')
          ->id('section_five_disable')
          ->class('form-check-input')}}
          {{ html()->label("Section Disable")->class('form-check-label')->for('section_five_disable') }}
        </div>
      </div> <!-- form-group-->
    </div>
    <div class="form-group">
      {{html()->label('Section Title')->for('section_five_title')}}
      {{html()->text('section_five_title', get_setting('section_five_title'))->class('form-control')->placeholder('Section Title')->required(true)}}
    </div> <!-- form-group-->


    <div class="form-group">
      <div class="form-check form-check-inline">
        {{html()->radio('section_five_query_type', get_setting('section_five_query_type', true),
        'cat_query')->id('section_five_cat_query')->class('form-check-input')}}
        {{ html()->label("Category Query")->class('form-check-label')->for('section_five_cat_query') }}
      </div>
      <div class="form-check form-check-inline">
        {{html()->radio('section_five_query_type', get_setting('section_five_query_type') == 'search_query',
        'search_query')->id('section_five_search_query')->class('form-check-input')}}
        {{ html()->label("Search Query")->class('form-check-label')->for('section_five_search_query') }}
      </div>
    </div> <!-- form-group-->

    <div class="form-group">
      {{html()->label('Query last url after slash')->for('section_five_query_url')}}
      {{html()->text('section_five_query_url',
      get_setting('section_five_query_url'))->class('form-control')->placeholder('/cat-slug?page=3 or
      /bag?page=3')->required(true)}}
    </div> <!-- form-group-->


    <div class="form-group">
      {{html()->label('Query Limit')->for('section_five_query_limit')}}
      {{html()->number('section_five_query_limit',
      get_setting('section_five_query_limit'))->class('form-control')->placeholder('50')->required(true)}}
    </div> <!-- form-group-->

    <div class="form-group">
      {{html()->button('Update')->class('btn w-25 btn-primary')}}
    </div> <!-- form-group-->

  </div> <!--  .card-body -->
  {{ html()->form()->close() }}
</div> <!--  .card -->