@extends('backend.layouts.app')

@section('title', app_name() . ' | Trashed pages')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="row">
      <div class="col-sm-5">
        <h4 class="card-title mb-0">
          Trashed Pages
        </h4>
      </div> <!-- col-->
      <div class="col-sm-7 pull-right">
        <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
          <a href="{{ route('admin.faq.index') }}" class="btn btn-success ml-1" data-toggle="tooltip"
            title="Show FAQs"><i class="fa fa-list-alt"></i></a>
        </div>
      </div> <!-- col-->
    </div> <!-- row-->

    <div class="row mt-4">
      <div class="col">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr class="vertical-middle">
                <th>Title</th>
                <th style="width:120px" class="text-center">Author</th>
                <th style="width:150px" class="text-center">Date</th>
                <th style="width:150px" class="text-center">@lang('labels.general.actions')</th>
              </tr>
            </thead>
            <tbody>
              @forelse($faqs as $faq)
              <tr class="vertical-middle">
                <td>{{ ucwords($faq->post_title) }}</td>
                <td class="text-center">{{ $faq->user->first_name }} {{$faq->user->last_name}}</td>
                <td class="text-center">
                  <p class="m-0">{{$faq->post_status}}</p>
                  {{ date('d-M-Y', strtotime($faq->created_at)) }}
                </td>
                <td class="text-center">@include('backend.content.faq.includes.actions-trash', ['faq' => $faq])</td>
              </tr>
              @empty
              <tr class="vertical-middle">
                <td colspan="4" class="bg-danger">No Trashed Found</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div> <!-- col-->
    </div> <!-- row-->
    <div class="row">
      <div class="col-7">
        <div class="float-left">
          {!! $faqs->total() !!}
          {{ trans_choice('FAQs', $faqs->total()) }}
        </div>
      </div> <!-- col-->

      <div class="col-5">
        <div class="float-right">
          {!! $faqs->render() !!}
        </div>
      </div> <!-- col-->
    </div> <!-- row-->
  </div> <!-- card-body-->
</div> <!-- card-->
@endsection