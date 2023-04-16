<div class="card mb-3">
    {{ html()->form('POST', route('admin.front-setting.manage.product-page-cards.store'))->attribute('enctype', 'multipart/form-data')->open() }}
    <div class="card-header with-border">
        <h3 class="card-title">Card One (Top)</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            <div class="form-check form-check-inline">
                {{ html()->radio('card_one_active', get_setting('card_one_active') === 'enable', 'enable')->id('card_one_enable')->class('form-check-input') }}
                {{ html()->label('Card Enable')->class('form-check-label')->for('card_one_enable') }}
            </div>
            <div class="form-check form-check-inline">
                {{ html()->radio('card_one_active', get_setting('card_one_active') === 'disable', 'disable')->id('card_one_disable')->class('form-check-input') }}
                {{ html()->label('Card Disable')->class('form-check-label')->for('card_one_disable') }}
            </div>
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->label('Card Content')->for('card_one_content') }}
            <textarea name="card_one_content" class="editor form-control" id="card_one_content">{{ get_setting('card_one_content') }}</textarea>
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->label('Card Content Image')->for('card_one_image') }}
            {{ html()->file('card_one_image', get_setting('card_one_image'))->class('form-control-file') }}
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->button('Update')->class('btn w-25 btn-primary') }}
        </div> <!-- form-group-->

    </div> <!--  .card-body -->
    {{ html()->form()->close() }}
</div> <!--  .card -->


