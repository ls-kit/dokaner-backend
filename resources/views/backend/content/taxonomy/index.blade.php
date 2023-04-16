@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('Manage Categories'))

@section('content')

<span class="d-none " id="mainCategories">@json($mainCategories)</span>

<div id="collapseFilter" class="collapse">
  <div class="card">
    <div class="card-body">
      <form get="?" class="form-inline">
        <label class="sr-only" for="category">Category</label>
        <select name="category" data-active="{{request("category")}}" class="form-control mb-2 mr-sm-2" id="category">
          <option value="">- Select --</option>
        </select>

        <label class="sr-only" for="subCategory">Sub Category1</label>
        <select name="subCategory" data-active="{{request("subCategory")}}" class="form-control mb-2 mr-sm-2" id="subCategory">
          <option value="">- Select --</option>
        </select>
        <button type="submit" class="btn btn-primary mb-2">Filter Submit</button>
      </form>
    </div>
  </div>
</div>

<div class="card card-default">
  <div class="card-header">
    <h3 class="card-title">Manage Categories</h3>

    <div class="dropdown d-inline">
      <button class="btn btn-tool text-cyan dropdown-toggle" type="button" id="allActionButton" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-cog"></i> All Action
      </button>
      <div class="dropdown-menu" aria-labelledby="allActionButton">
        <a class="dropdown-item markAsTop" data-top="1" href="#">Mark as top</a>
        <a class="dropdown-item markAsTop" data-top="0" href="#">Remove from top</a>
        <a class="dropdown-item markAsActive" data-active="1" href="#">Mark as Active</a>
        <a class="dropdown-item markAsActive"  data-active="0" href="#">Mark as Inactive</a>
        <a class="dropdown-item text-danger makeDelete" href="#">Mark as delete</a>
      </div>
    </div>


    <a class="btn btn-tool text-cyan collapsed" data-toggle="collapse" href="#collapseFilter">
      <i class="fa fa-filter"></i> Filter Categories
    </a>
    <a class="btn btn-tool @if(request('only-parent')) text-success @endif" href="/admin/taxonomy?only-parent=true">
      <i class="fa fa-check-square-o"></i> Only Parents
    </a>
    <a class="btn btn-tool @if(request('only-is-top')) text-success @endif" href="/admin/taxonomy?only-is-top=true">
      <i class="fa fa-check-square-o"></i> Only Top
    </a>
    <a class="btn btn-tool @if(request('only-active')) text-success @endif" href="/admin/taxonomy?only-active=true&only-parent={{request('only-parent')}}&only-is-top={{request('only-is-top')}}">
      <i class="fa fa-check-square-o"></i> Only Active
    </a>

    @if(request('only-parent') || request('only-is-top') || request('only-active'))
    <a class="btn btn-tool  text-danger " href="/admin/taxonomy">
      <i class="fa fa-check-square-o"></i> Clear
    </a>
    @endif

    <div class="card-tools">
      @can('create-category')
      @include('backend.content.taxonomy.includes.header-buttons')
      @endcan
    </div>
  </div>

  <div class="card-body pt-0">
    @livewire('backend.category-table', [
    'isParent' => request('only-parent'),
    'category' => request('category'),
    'subCategory' => request('subCategory'),
    'isTop' => request('only-is-top'),
    'active' => request('only-active')
    ])
  </div> <!-- card-body-->

</div> <!-- card -->
@endsection


@push('after-styles')
@livewireStyles
@endpush

@push('after-scripts')
@livewireScripts
@endpush