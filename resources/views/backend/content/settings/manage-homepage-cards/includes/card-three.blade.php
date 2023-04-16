<div class="card mb-3">
    {{ html()->form('POST', route('admin.front-setting.manage.homepage-cards.store'))->attribute('enctype', 'multipart/form-data')->open() }}
    <div class="card-header with-border">
        <h3 class="card-title">Card Three</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            <div class="form-check form-check-inline">
                {{ html()->radio('hp_card_three_active', get_setting('hp_card_three_active') === 'enable', 'enable')->id('hp_card_three_enable')->class('form-check-input') }}
                {{ html()->label('Card Enable')->class('form-check-label')->for('hp_card_three_enable') }}
            </div>
            <div class="form-check form-check-inline">
                {{ html()->radio('hp_card_three_active', get_setting('hp_card_three_active') === 'disable', 'disable')->id('hp_card_three_disable')->class('form-check-input') }}
                {{ html()->label('Card Disable')->class('form-check-label')->for('hp_card_three_disable') }}
            </div>
        </div> <!-- form-group-->

        <div class="form-group">
            <div class="col-md-12">
                @php
                    $card_images = get_setting('hp_card_three_image');
                @endphp
                @php($asmLogo = $card_images ?? $demoImg )

                <label for="hp_card_three_image">
                    <img src="{{asset($asmLogo)}}" class="border img-fluid rounded holder" alt="Image upload">
                </label>
            </div>

            {{ html()->file('hp_card_three_image')->class('form-control-file image d-none')->acceptImage() }}
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->label('Card Title')->for('hp_card_three_title') }}
            {{ html()->text('hp_card_three_title', get_setting('hp_card_three_title'))->class('form-control')->placeholder('Title') }}
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->label('Button Name')->for('hp_card_three_btn_name') }}
            {{ html()->text('hp_card_three_btn_name', get_setting('hp_card_three_btn_name'))->class('form-control')->placeholder('Name') }}
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->label('Button URL')->for('hp_card_three_url') }}
            {{ html()->text('hp_card_three_url', get_setting('hp_card_three_url'))->class('form-control')->placeholder('https://') }}
        </div> <!-- form-group-->


        <div class="form-group">
            {{ html()->button('Update')->class('btn w-25 btn-primary') }}
        </div> <!-- form-group-->

    </div> <!--  .card-body -->
    {{ html()->form()->close() }}
</div> <!--  .card -->
