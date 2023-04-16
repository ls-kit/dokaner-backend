@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('Manage Categories'))

@section('content')
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-sm-5">
          <h4 class="card-title mb-0">Manage Categories</h4>
        </div> <!-- col -->
        <div class="col-sm-7 pull-right">
          <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
            <a href="{{ route('admin.taxonomy.index') }}" class="btn btn-success ml-1" data-toggle="tooltip"
               title="Show Pages"><i class="fa fa-list-alt"></i></a>
          </div>
        </div> <!-- col -->
      </div> <!-- row -->

      <div class="row mt-4">
        <div class="col">
          <div class="table-responsive">
            <table class="table text-center">
              <thead>
              <tr>
                <th>#Id</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Parent</th>
                <th>Status</th>
                <th>Picture</th>
                <th>@lang('labels.general.actions')</th>
              </tr>
              </thead>
              <tbody>
              @forelse($taxonomies as $taxonomy)
                <tr>
                  <td class="align-middle">{{$taxonomy->id}}</td>
                  <td class="align-middle">{{ $taxonomy->name }}</td>
                  <td class="align-middle">{{ $taxonomy->slug }}</td>
                  <td class="align-middle">{{ $taxonomy->parent->name ?? 'N/A' }}</td>
                  <td class="align-middle">
                    {{ $taxonomy->active ? html()->span('Active')->class('text-success') : html()->span('Inactive')->class('text-danger') }}
                  </td>
                  <td class="align-middle" style="width: 100px">
                    <img src="{{asset($taxonomy->picture)}}" alt="{{$taxonomy->name}}" class="img-fluid">
                  </td>
                  <td class="align-middle">
                    @include('backend.content.taxonomy.includes.actions-trash', ['taxonomy' => $taxonomy])
                  </td>
                </tr>
              @empty
                <tr>
                  <td class="text-center bg-danger" colspan="7">
                    Trashed not found
                  </td>
                </tr>
              @endforelse

              </tbody>
            </table>
          </div>
        </div>
        <!--col-->
      </div>
      <!--row-->
      <div class="row">
        <div class="col-7">
          <div class="float-left">
            {!! $taxonomies->total() !!} {{ trans_choice('Category Total', $taxonomies->total()) }}
          </div>
        </div> <!-- col -->

        <div class="col-5">
          <div class="float-right">
            {!! $taxonomies->render() !!}
          </div>
        </div> <!-- col -->
      </div> <!-- row -->
    </div> <!-- card-body -->
  </div> <!-- card -->
@endsection
