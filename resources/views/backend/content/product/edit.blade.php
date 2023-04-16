@extends('backend.layouts.app')

@section('title', ' Post Management | Edit Post')

@section('content')

  {{ html()->modelForm($post, 'PATCH', route('admin.post.update', $post))->class('form-horizontal')->attribute('enctype', 'multipart/form-data')->open() }}

  <div class="row">
    <div class="col-md-9">
      <div class="card">
        <div class="card-header with-border">
          <h3 class="card-title">Post Management <small class="ml-2">Create New Post</small></h3>
        </div>
        <div class="card-body">
          <div class="form-group">
            {{html()->text('post_title')->class('form-control cash')->placeholder('Post Title')}}
            <p class="text-danger margin-bottom-none" id="post_error">
              @error('post_title') {{$message}}@enderror
            </p>
          </div> <!-- form-group -->

          <div class="form-group">
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text">{{ url('/p')}}/</div>
              </div>
              {{html()->text('post_slug')->class('form-control')->placeholder('slug')}}
            </div>
          </div> <!-- form-group -->

          <div class="form-group">
            {{html()->text('post_content')->class('editor form-control')}}
            @error('post_content')
            <p class="text-danger margin-bottom-none">{{$message}}</p>
            @enderror
          </div> <!-- form-group -->

          <div class="form-group">
            {{html()->textarea('post_excerpt')->class('form-control')->placeholder('Excerpt') }}
            @error('post_excerpt')
            <p class="text-danger margin-bottom-none">{{$message}}</p>
            @enderror
          </div> <!-- form-group -->
        </div> <!--  .card-body -->

        <div class="card-footer">
          {{ form_submit(__('Update Post')) }}
          {{ form_cancel(route('admin.post.index'), __('buttons.general.cancel')) }}
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
            @php $status = old('post_status', $post->post_status);@endphp
            <div class="form-check form-check-inline">
              <input type="radio" class="form-check-input" name="post_status" value="publish" id="publish"
                     class="checking" checked>
              <label class="form-check-label" for="publish">Publish</label>
            </div>
            <div class="form-check form-check-inline">
              <input type="radio" class="form-check-input" name="post_status" value="draft" id="draft" class="checking"
                     @if($status==='draft' ) checked @endif>
              <label class="form-check-label" for="draft">Draft</label>
            </div>
            <div class="form-check form-check-inline">
              <input type="radio" class="form-check-input" name="post_status" value="schedule" id="schedule"
                     class="checking" @if($status==='schedule' ) checked @endif>
              <label class="form-check-label" for="schedule">Schedule</label>
            </div>
          </div> <!-- form-group -->

          <div class="row">
            <div class="card-title col">
              Category
              <a href="{{ route('admin.taxonomy.create') }}" class="ml-2 small" data-toggle="tooltip"
                 title="Add New Category">
                <i class="fa fa-plus-circle"></i> Add New
              </a>
            </div>
          </div> <!-- row -->
          <hr class="mt-0">
          <div class="form-group">
            @php
              $checkClass = 'form-check-input ml-0 position-relative';
              $postTaxo = $post->taxonomies->pluck('id')->toArray();
              $postTags = $post->keywords->pluck('id')->toArray();
            @endphp
            <ul class="nested" id="nested">
              @foreach($taxonomies as $taxonomy)
                @php
                  $iteration = $loop->iteration;
                @endphp
                <li>
                  {{html()->checkbox('taxonomy[]', in_array($taxonomy->id, $postTaxo))->value($taxonomy->id)->id($taxonomy->id)->class($checkClass)}}
                  {{html()->label($taxonomy->name)->class('form-check-label')->for($taxonomy->id)}}
                  @if($taxonomy->childrens->isNotEmpty())
                    <ul>
                      @foreach($taxonomy->childrens as $childrenOne)
                        <li>
                          {{html()->checkbox('taxonomy[]', in_array($childrenOne->id, $postTaxo))->value($childrenOne->id)->id($childrenOne->id)->class
                          ($checkClass)}}
                          {{html()->label($childrenOne->name)->class('form-check-label')->for($childrenOne->id)}}

                          @if($childrenOne->childrens->isNotEmpty())
                            <ul>
                              @foreach($childrenOne->childrens as $childrenTwo)
                                <li>
                                  {{html()->checkbox('taxonomy[]', in_array($childrenTwo->id, $postTaxo))->value($childrenTwo->id)->id($childrenTwo->id)->class($checkClass)}}
                                  {{html()->label($childrenTwo->name)->class('form-check-label')->for($childrenTwo->id)}}
                                </li>
                              @endforeach
                            </ul>
                          @endif
                        </li>
                      @endforeach
                    </ul>
                  @endif
                </li>
              @endforeach

            </ul>
          </div> <!-- form-group -->

          <div class="row">
            <div class="card-title col">
              Tags
              <a href="{{ route('admin.keyword.create') }}" class="ml-2 small" data-toggle="tooltip"
                 title="Add New Tag">
                <i class="fa fa-plus-circle"></i> Add New
              </a>
            </div>
          </div> <!-- row -->
          <hr class="mt-0">
          <div class="form-group">
            {{html()->multiselect('tags[]', $tags, $postTags)->class('form-control select2')}}
          </div> <!-- form-group -->


          <div class="form-group @if($status !=='schedule' ) d-none @endif" id="scheduleDate">
            <div class="form-group">
              <div class="input-group">
                <input type="text" name="schedule_time" class="form-control" placeholder="dd/mm/yyyy"
                       id="datepicker-autoclose" value="{{old('schedule_time')}}">
                <div class="input-group-append bg-custom b-0">
                  <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                </div>
              </div><!-- input-group -->
            </div>
          </div> <!-- form-group -->
          <div class="row">
            <div class="card-title col">Thumbnail</div>
          </div> <!-- row -->
          <hr class="mt-0">

          <div class="form-group">
            <div class="form-check form-check-inline">
              {{html()->radio('thumb_status', true )->value(1)->class('checking form-check-input')->id('new')}}
              <label class="form-check-label" for="new">Upload Image</label>
            </div>
            <div class="form-check form-check-inline">
              {{html()->radio('thumb_status', $post->thumb_status === 0 ? true : false )->value(0)->class('checking form-check-input')->id('off')}}
              <label class="form-check-label" for="off">Image Off</label>
            </div>
          </div> <!-- form-group -->

          <div class="form-group @if(old('thumb_status', $post->thumb_status) === 0) d-none @endif" id="for_New_upload">
            <label for="image">
              <img src="{{asset('img/backend/no-image.gif')}}" class="img-fluid" id="holder" alt="Image upload">
            </label>
            <input type="file" name="image" class="d-none" id="image">
          </div> <!-- form-group -->
        </div> <!--  card-body -->
      </div> <!-- /.card -->
    </div> <!-- .col-md-3 -->
  </div> <!-- .row -->

  {{ html()->closeModelForm() }}

@endsection



@push('after-styles')
  {{ style('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}
  {{style('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css')}}
  {{style('https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css')}}
  <style>
    .nested {
      height: 220px;
      overflow-y: scroll;
      list-style: none;
      padding: 10px;
      border: 1px solid rgb(204 204 204);
    }

    .nested ul {
      list-style: none;
      padding-left: 20px;
    }

    .nested ul li {
    }

    .nested ul li input {
      margin-top: .3rem;
      margin-left: -1.25rem;
    }

    /* width */
    .nested::-webkit-scrollbar {
      width: 6px;
    }

    /* Track */
    .nested::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    /* Handle */
    .nested::-webkit-scrollbar-thumb {
      background: #888;
    }

    /* Handle on hover */
    .nested::-webkit-scrollbar-thumb:hover {
      background: #555;
    }


  </style>
@endpush

@push('after-scripts')
  {!! script('assets/plugins/tinymce/jquery.tinymce.min.js') !!}
  {!! script('assets/plugins/tinymce/tinymce.min.js') !!}
  {!! script('assets/plugins/tinymce/editor-helper.js') !!}
  {!! script('assets/plugins/moment/moment.js') !!}
  {!! script('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') !!}
  {{script('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js')}}

  <script>
     $(document).ready(function () {

        $('.select2').select2();

        simple_editor('.editor', 450);
        $('#datepicker-autoclose').datepicker({
           format: "dd/mm/yyyy",
           clearBtn: true,
           autoclose: true,
           todayHighlight: true,
        });

        $('.nested li').click(function () {
           // $(this).parent().find('li input[type=checkbox]').prop('checked', $(this).is(':checked'));
           var sibs = false;
           $(this).closest('ul').children('li').each(function () {
              if ($('input[type=checkbox]', this).is(':checked')) sibs = true;
           })
           $(this).parents('ul').prev().prev().prop('checked', sibs);
        });

        $("#image").change(function () {
           readImageURL(this, $('#holder'));
        });
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
