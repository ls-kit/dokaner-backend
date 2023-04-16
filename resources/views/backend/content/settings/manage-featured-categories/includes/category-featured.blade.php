<div class="card mb-3">
    {{ html()->form('POST', route('admin.front-setting.manage.featured-categories.store'))->attribute('enctype', 'multipart/form-data')->open() }}
    <div class="card-header with-border">
        <h3 class="card-title">Featured Category</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            {{ html()->label('Category Banner')->for('hp_cat_feat_banner') }}

            <div class="col-md-12">
                @php
                    $card_images = get_setting('hp_cat_feat_banner');
                @endphp
                @php
                    $asmLogo = $card_images ?? $demoImg
                @endphp

                <label for="hp_cat_feat_banner">
                    <img src="{{asset($asmLogo)}}" class="border img-fluid rounded holder" alt="Image upload">
                </label>
            </div>

            {{ html()->file('hp_cat_feat_banner')->class('form-control-file image d-none')->acceptImage() }}
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->label('Category Name')->for('hp_cat_feat_name') }}
            {{ html()->text('hp_cat_feat_name', get_setting('hp_cat_feat_name'))->class('form-control')->placeholder('Name') }}
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->label('Category URL')->for('hp_cat_feat_url') }}
            {{ html()->text('hp_cat_feat_url', get_setting('hp_cat_feat_url'))->class('form-control')->placeholder('https://') }}
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->label('Section One Banner')->for('hp_cat_feat_section_one_banner') }}

            <div class="col-md-12">
                @php
                    $card_images = get_setting('hp_cat_feat_section_one_banner');
                @endphp
                @php
                    $asmLogo = $card_images ?? $demoImg
                @endphp

                <label for="hp_cat_feat_section_one_banner">
                    <img src="{{asset($asmLogo)}}" class="border img-fluid rounded holder" alt="Image upload">
                </label>
            </div>

            {{ html()->file('hp_cat_feat_section_one_banner')->class('form-control-file image d-none')->acceptImage() }}
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->label('Section One URL')->for('hp_cat_feat_section_one_url') }}
            {{ html()->text('hp_cat_feat_section_one_url', get_setting('hp_cat_feat_section_one_url'))->class('form-control')->placeholder('https://') }}
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->label('Section Two Banner')->for('hp_cat_feat_section_two_banner') }}

            <div class="col-md-12">
                @php
                    $card_images = get_setting('hp_cat_feat_section_two_banner');
                @endphp
                @php
                    $asmLogo = $card_images ?? $demoImg
                @endphp

                <label for="hp_cat_feat_section_two_banner">
                    <img src="{{asset($asmLogo)}}" class="border img-fluid rounded holder" alt="Image upload">
                </label>
            </div>

            {{ html()->file('hp_cat_feat_section_two_banner')->class('form-control-file image d-none')->acceptImage() }}
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->label('Section Two URL')->for('hp_cat_feat_section_two_url') }}
            {{ html()->text('hp_cat_feat_section_two_url', get_setting('hp_cat_feat_section_two_url'))->class('form-control')->placeholder('https://') }}
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->label('Section Three Banner')->for('hp_cat_feat_section_three_banner') }}

            <div class="col-md-12">
                @php
                    $card_images = get_setting('hp_cat_feat_section_three_banner');
                @endphp
                @php
                    $asmLogo = $card_images ?? $demoImg
                @endphp

                <label for="hp_cat_feat_section_three_banner">
                    <img src="{{asset($asmLogo)}}" class="border img-fluid rounded holder" alt="Image upload">
                </label>
            </div>

            {{ html()->file('hp_cat_feat_section_three_banner')->class('form-control-file image d-none')->acceptImage() }}
        </div> <!-- form-group-->

        <div class="form-group">
            {{ html()->label('Section Three URL')->for('hp_cat_feat_section_three_url') }}
            {{ html()->text('hp_cat_feat_section_three_url', get_setting('hp_cat_feat_section_three_url'))->class('form-control')->placeholder('https://') }}
        </div> <!-- form-group-->


        <div class="form-group">
            {{ html()->button('Update')->class('btn w-25 btn-primary') }}
        </div> <!-- form-group-->

    </div> <!--  .card-body -->
    {{ html()->form()->close() }}
</div> <!--  .card -->
