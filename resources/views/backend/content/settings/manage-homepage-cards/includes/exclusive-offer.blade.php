<div class="card mb-3">
    {{ html()->form('POST', route('admin.front-setting.manage.homepage-cards.store'))->attribute('enctype', 'multipart/form-data')->open() }}
    <div class="card-header with-border">
        <h3 class="card-title">Exclusive Offer Card</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            <div class="form-check form-check-inline">
                {{ html()->radio('hp_card_five_active', get_setting('hp_card_five_active') === 'enable', 'enable')->id('hp_card_five_enable')->class('form-check-input') }}
                {{ html()->label('Card Enable')->class('form-check-label')->for('hp_card_five_enable') }}
            </div>
            <div class="form-check form-check-inline">
                {{ html()->radio('hp_card_five_active', get_setting('hp_card_five_active') === 'disable', 'disable')->id('hp_card_five_disable')->class('form-check-input') }}
                {{ html()->label('Card Disable')->class('form-check-label')->for('hp_card_five_disable') }}
            </div>
        </div> <!-- form-group-->

        <div class="form-group">
            <div class="col-md-12">
                @php
                    $card_images = get_setting('hp_card_five_image');
                @endphp
                @php($asmLogo = $card_images ?? $demoImg )

                <label for="hp_card_five_image">
                    <img src="{{asset($asmLogo)}}" class="border img-fluid rounded holder" alt="Image upload">
                </label>
            </div>

            {{ html()->file('hp_card_five_image')->class('form-control-file image d-none')->acceptImage() }}
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->label('Card Text')->for('hp_card_five_text') }}
            {{ html()->text('hp_card_five_text', get_setting('hp_card_five_text'))->class('form-control')->placeholder('Banner Text') }}
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->label('Product One ID')->for('hp_card_five_product_one_id') }}
            {{ html()->text('hp_card_five_product_one_id', get_setting('hp_card_five_product_one_id'))->class('form-control')->placeholder('Product ID') }}
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->label('Product Two ID')->for('hp_card_five_product_two_id') }}
            {{ html()->text('hp_card_five_product_two_id', get_setting('hp_card_five_product_two_id'))->class('form-control')->placeholder('Product ID') }}
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->label('Product Three ID')->for('hp_card_five_product_three_id') }}
            {{ html()->text('hp_card_five_product_three_id', get_setting('hp_card_five_product_three_id'))->class('form-control')->placeholder('Product ID') }}
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->label('Product Four ID')->for('hp_card_five_product_four_id') }}
            {{ html()->text('hp_card_five_product_four_id', get_setting('hp_card_five_product_four_id'))->class('form-control')->placeholder('Product ID') }}
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->label('Product Five ID')->for('hp_card_five_product_five_id') }}
            {{ html()->text('hp_card_five_product_five_id', get_setting('hp_card_five_product_five_id'))->class('form-control')->placeholder('Product ID') }}
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->label('Product Six ID')->for('hp_card_five_product_six_id') }}
            {{ html()->text('hp_card_five_product_six_id', get_setting('hp_card_five_product_six_id'))->class('form-control')->placeholder('Product ID') }}
        </div> <!-- form-group-->


        <div class="form-group">
            {{ html()->button('Update')->class('btn w-25 btn-primary') }}
        </div> <!-- form-group-->

    </div> <!--  .card-body -->
    {{ html()->form()->close() }}
</div> <!--  .card -->
