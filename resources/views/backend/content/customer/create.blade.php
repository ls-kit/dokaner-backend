@extends('backend.layouts.app')

@section('title', ' Package Management | Create new')

@section('content')
{{ html()->form('POST', route('admin.package.store'))->class('form-horizontal')->attribute('enctype', 'multipart/form-data')->open() }}

<div class="row">
  <div class="col-md-9">
    <div class="card">
      <div class="card-header with-border">
        <h3 class="card-title">Package Management <small class="ml-2">Create</small></h3>
      </div>
      <div class="card-body">
        <div class="form-group @error('name') has-error @enderror">
          <label for="name" class="sr-only">Package Name</label>
          <input type="text" name="name" id="post_title" value="{{old('name')}}" class="form-control"
            placeholder="Package Name" aria-describedby="nameError">
          @error('name')
          <span id="nameError" class="sr-only">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group d-none">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text">{{ url('/p')}}/</div>
            </div>
            <input type="text" name="slug" class="form-control" id="post_slug" placeholder="slug"
              value="{{old('slug')}}">
          </div>
        </div> <!-- form-group -->
        <div class="packageOffer">
          @if(old('attach'))
          @foreach(old('attach') as $index => $attach)
          <div class="row">
            <div class="form-group col-sm-5">
              <label for="option_name" class="sr-only">Option Name</label>
              <input type="text" name="attach[{{$index}}][option]" id="option_name"
                value="{{old('attach')[$index]['option']}}" class="form-control" placeholder="Option Name"
                aria-describedby="option_nameError">
            </div> <!-- form-group -->
            <div class="form-group col-sm-7">
              <label for="option_value" class="sr-only">Option Value</label>
              <input type="text" name="attach[{{$index}}][value]" id="option_value"
                value="{{old('attach')[$index]['value']}}" class="form-control float-left" placeholder="Option Value"
                style="width: 85%;" aria-describedby="option_valueError">
              <button type="button"
                class="btn btn-danger float-right @if($index > 0) remove-option @else disabled @endif" title="Remove">
                <i class="fa fa-times"></i>
              </button>
            </div> <!-- form-group -->
          </div> <!-- row -->
          @endforeach

          @else
          <div class="row">
            <div class="form-group col-sm-5">
              <label for="option_name" class="sr-only">Option Name</label>
              <input type="text" name="attach[0][option]" id="option_name" value="{{old('option_name[]')}}"
                class="form-control" placeholder="Option Name" aria-describedby="option_nameError">
            </div> <!-- form-group -->
            <div class="form-group col-sm-7">
              <label for="option_value" class="sr-only">Option Value</label>
              <input type="text" name="attach[0][value]" id="option_value" value="{{old('option_value[]')}}"
                class="form-control float-left" placeholder="Option Value" style="width: 85%;"
                aria-describedby="option_valueError">
              <button type="button" class="btn btn-danger float-right disabled" title="Remove"><i
                  class="fa fa-times"></i></button>
            </div> <!-- form-group -->
          </div> <!-- row -->
          @endif
        </div>
        <div class="form-group">
          <button type="button" class="btn btn-success add-option">Add more</button>
        </div>
        <div class="form-group">
          <textarea name="content" class="form-control editor">{{old('content')}}</textarea>
          @error('content')
          <p class="text-danger margin-bottom-none">{{$message}}</p>
          @enderror
        </div>
        <div class="form-group">
          <label for="excerpt" class="sr-only">Excerpt</label>
          <textarea name="excerpt" rows="2" class="form-control" placeholder="excerpt">{{old('excerpt')}}</textarea>
          @error('excerpt')
          <p class="text-danger margin-bottom-none">{{$message}}</p>
          @enderror
        </div>
        <div class="row">
          <div class="form-group col-sm-6">
            <label for="regular_price">Regular Price</label>
            <input type="text" name="regular_price" class="form-control" value="{{old('regular_price')}}"
              placeholder="Regular Price">
            @error('regular_price')
            <p class="text-danger margin-bottom-none">{{$message}}</p>
            @enderror
          </div>
          <div class="form-group col-sm-6">
            <label for="current_price">Current Price</label>
            <input type="text" name="current_price" class="form-control" value="{{old('current_price')}}"
              placeholder="Current Price">
            @error('current_price')
            <p class="text-danger margin-bottom-none">{{$message}}</p>
            @enderror
          </div>
        </div>
      </div> <!-- .card-body -->
      <div class="card-footer">
        {{ form_submit(__('buttons.general.crud.create')) }}
        {{ form_cancel(route('admin.package.index'), __('buttons.general.cancel')) }}
      </div> <!--  .card-body -->

    </div> <!--  .card -->
  </div> <!-- .col-md-9 -->

  <div class="col-sm-3">
    <div class="card">
      <div class="card-header with-border">
        <h3 class="card-title">Publishing Tools</h3>
      </div>
      <div class="card-body p-3">
        <div class="form-group">
          @php $status = old('status');@endphp
          <div class="form-check form-check-inline">
            <input type="radio" class="form-check-input" name="status" value="publish" id="publish" class="checking"
              checked>
            <label class="form-check-label" for="publish">Publish</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="radio" class="form-check-input" name="status" value="draft" id="draft" class="checking"
              @if($status==='draft' ) checked @endif>
            <label class="form-check-label" for="draft">Draft</label>
          </div>
        </div> <!-- form-group -->

        <div class="form-group">
          <div class="card-title">Thumbnail</div>
          {{html()->select('package_type_id',$types)->class('form-control')->id('package_type_id')}}
        </div> <!-- form-group -->

        <div class="form-group">
          <div class="row">
            <div class="card-title col">Thumbnail</div>
          </div> <!-- row -->
          <hr class="mt-0">
          <div class="form-check form-check-inline">
            <input type="radio" class="form-check-input" name="thumb_status" value="1" id="new" class="checking"
              checked>
            <label class="form-check-label" for="new">Upload Image</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="radio" class="form-check-input" name="thumb_status" value="0" id="off" class="checking">
            <label class="form-check-label" for="off">Image Off</label>
          </div>
        </div> <!-- form-group -->
        <div class="form-group m-0 @if(old('thumb_status')==='off') d-none @endif" id="for_New_upload">
          <label for="image">
            <img src="{{asset('img/backend/no-image.gif')}}" class="img-fluid" id="holder" alt="Image upload">
          </label>
          <input type="file" name="image" class="d-none" id="image">
        </div> <!-- form-group -->
      </div> <!--  card-body -->
    </div> <!-- /.card -->
  </div> <!-- .col-md-3 -->
</div> <!-- .row -->

{{ html()->form()->close() }}
@endsection




@push('after-styles')
{{ style(asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')) }}
@endpush

@push('after-scripts')
{!! script(asset('assets/plugins/tinymce/jquery.tinymce.min.js')) !!}
{!! script(asset('assets/plugins/tinymce/tinymce.min.js')) !!}
{!! script(asset('assets/plugins/tinymce/editor-helper.js')) !!}
{!! script(asset('assets/plugins/moment/moment.js')) !!}
{!! script(asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')) !!}

<script>
  $(document).ready(function () {
    simple_editor('.editor', 350);
    $('#datepicker-autoclose').datepicker({
        format: "dd/mm/yyyy",
        clearBtn: true,
        autoclose: true,
        todayHighlight: true,
    });

    $("#image").change(function () {
        readImageURL(this, $('#holder'));
    });
  });

  $(document).on('click', '.add-option', function () {
    let divArea = $('.packageOffer');
    let formGroup = divArea.find('.row').last().find('.form-group');
    var newForm = '';
    let formLength = $('.single-form').length;
if(formLength < 10){
  formGroup.each(function () {
      var data = $(this).html();
      let name = $(this).find('.form-control').attr('name');
      var newData;
      if (name) {
        let current = parseInt(name.slice(7, 8));
        let newIndex = current + 1;
        newData = data.replace('attach[' + current + ']', 'attach[' + newIndex + ']');
      } else {
        newData = data;
      }
      newForm += '<div class="' + $(this).attr('class') + '">';
      newForm += newData;
      newForm += '</div>';
    });
    newForm = '<div class="single-form row">' + newForm + '</div>';
    divArea.append(newForm);
    divArea.find('.single-form').last().find('.disabled').addClass('remove-attachment').removeClass('disabled');
}else{
  alert('Out of Limit')
}
    
  });

    $(document).on('click', '.remove-option', function () {
      $(this).closest('.row').remove();
    });

    $(document).on('blur', "#post_title", function () {
      let postField = $(this);
      let post_title = postField.val();
      if (post_title) {
        ajax_slug_url(post_title);
        setTimeout(update, 1000); // 30 seconds
        $("#post_error").empty();
        postField.removeClass('is-invalid');
      } else {
        $("#post_error").text('Title must not empty');
        postField.addClass('is-invalid');
      }
    });

    $(function () {
      $(".form-check-input").click(function () {
        const status = $(this).val();
        if (status === "schedule") {
          $("#scheduleDate").removeClass("d-none");
        } else if (status === "1") {
          $("#for_New_upload").removeClass("d-none");
        } else if (status === "0") {
          $("#for_New_upload").addClass("d-none");
        } else {
          $("#scheduleDate").addClass("d-none");
        }
      });

    });
</script>

@endpush