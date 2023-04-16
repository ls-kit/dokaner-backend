<div class="card mb-3">
    {{ html()->form('POST', route('admin.front-setting.manage.section.store'))->open() }}
    <div class="card-header with-border">
      <h3 class="card-title">Section Below Banner</h3>
    </div>
    <div class="card-body">
      <div class="form-group">
        <div class="form-group">
          <div class="form-check form-check-inline">
            {{html()->radio('section_seven_active', get_setting('section_seven_active') === 'enable', 'enable')
            ->id('section_seven_enable')
            ->class('form-check-input')}}
            {{ html()->label("Section Enable")->class('form-check-label')->for('section_seven_enable') }}
          </div>
          <div class="form-check form-check-inline">
            {{html()->radio('section_seven_active', get_setting('section_seven_active') === 'disable', 'disable')
            ->id('section_seven_disable')
            ->class('form-check-input')}}
            {{ html()->label("Section Disable")->class('form-check-label')->for('section_seven_disable') }}
          </div>
        </div> <!-- form-group-->
      </div>

      <div class="form-group">
        {{html()->label('Query Product')->for('section_seven_search')}}
        {{html()->text('section_seven_search',
        get_setting('section_seven_search'))->class('form-control')->placeholder('Search string')->required(true)}}
      </div> <!-- form-group-->

      <div class="form-group">
        {{html()->button('Update')->class('btn w-25 btn-primary')}}
      </div> <!-- form-group-->

    </div> <!--  .card-body -->
    {{ html()->form()->close() }}
  </div> <!--  .card -->
