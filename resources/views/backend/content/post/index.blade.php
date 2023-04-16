@extends('backend.layouts.app')

@section('title', app_name() . ' | Post Management')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="row">
      <div class="col-sm-5">
        <h4 class="card-title mb-0">
          Post Management
        </h4>
      </div> <!-- col-->

      <div class="col-sm-7 pull-right">
        @include('backend.content.post.includes.header-buttons')
      </div> <!-- col-->
    </div> <!-- row-->

    <div class="row mt-4">
      <div class="col">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr class="vertical-middle">
                <th>Title</th>
                <th style="width:200px" class="text-center">Categories</th>
                <th style="width:120px" class="text-center">Author</th>
                <th style="width:150px" class="text-center">Date</th>
                <th style="width:150px" class="text-center">@lang('labels.general.actions')</th>
              </tr>
            </thead>
            <tbody>
              @foreach($posts as $post)
              <tr class="vertical-middle">
                <td>{{ ucwords($post->post_title) }}</td>
                <td class="text-center">
                  {{ implode(', ', $post->taxonomies->pluck('name')->toArray()) ?? ''}}
                </td>
                <td class="text-center">{{ $post->user->full_name}}</td>
                <td class="text-center">
                  <p class="m-0">{{$post->post_status}}</p>
                  {{ date('d-M-Y', strtotime($post->created_at)) }}
                </td>
                <td class="text-center">@include('backend.content.post.includes.actions', ['post' => $post])</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div> <!-- col-->
    </div> <!-- row-->
    <div class="row">
      <div class="col-7">
        <div class="float-left">
          {!! $posts->total() !!}
          {{ trans_choice('Posts', $posts->total()) }}
        </div>
      </div> <!-- col-->

      <div class="col-5">
        <div class="float-right">
          {!! $posts->render() !!}
        </div>
      </div> <!-- col-->
    </div> <!-- row-->
  </div> <!-- card-body-->
</div> <!-- card-->
@endsection