<div class="card mb-3">
    {{ html()->form('POST', route('admin.front-setting.manage.product-page-cards.store'))->attribute('enctype', 'multipart/form-data')->open() }}
    <div class="card-header with-border">
        <h3 class="card-title">Card Three (By Sea)</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            <div class="form-check form-check-inline">
                {{ html()->radio('card_three_active', get_setting('card_three_active') === 'enable', 'enable')->id('card_three_enable')->class('form-check-input') }}
                {{ html()->label('Card Enable')->class('form-check-label')->for('card_three_enable') }}
            </div>
            <div class="form-check form-check-inline">
                {{ html()->radio('card_three_active', get_setting('card_three_active') === 'disable', 'disable')->id('card_three_disable')->class('form-check-input') }}
                {{ html()->label('Card Disable')->class('form-check-label')->for('card_three_disable') }}
            </div>
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->label('Shipping Delivery Time')->for('card_three_delivery') }}
            {{ html()->text('card_three_delivery', get_setting('card_three_delivery'))->class('form-control')->placeholder('Estimated Delivery Time') }}
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->label('Price Per KG')->for('card_three_weight_price') }}
            {{ html()->text('card_three_weight_price', get_setting('card_three_weight_price'))->class('form-control')->placeholder('Estimated Price per kg') }}
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->label('Card Content')->for('card_three_content') }}
            <textarea class="editor form-control" name="card_three_content" id="card_three_content">{{ get_setting('card_three_content') }}</textarea>
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->label('Card Content Image')->for('card_three_image') }}
            {{ html()->file('card_three_image', get_setting('card_three_image'))->class('form-control-file') }}
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->button('Update')->class('btn w-25 btn-primary') }}
        </div> <!-- form-group-->

    </div> <!--  .card-body -->
    {{ html()->form()->close() }}
</div> <!--  .card -->
