<div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">

  <a href="{{ route('admin.export', 'orders') }}" class="btn btn-success ml-1" data-toggle="tooltip" title="Full Export">
    @lang('Full Export')
  </a>

  @if (request('status') === 'incomplete')
    <a href="{{ route('admin.order.index') }}" class="btn btn-success ml-1" data-toggle="tooltip" title="View Processing">
      Recent Orders
    </a>
  @else
    <a href="{{ route('admin.order.index',['status' => 'incomplete']) }}" class="btn btn-primary ml-1"
       data-toggle="tooltip" title="View Incomplete">
      Incomplete Orders
    </a>
  @endif

  @hasrole('administrator')
  <a href="{{ route('admin.order.trashed') }}" class="btn btn-danger ml-1" data-toggle="tooltip" title="View Trashed">
    <i class="fa fa-trash"></i> Trashed Orders
  </a>
  @endhasrole

</div>