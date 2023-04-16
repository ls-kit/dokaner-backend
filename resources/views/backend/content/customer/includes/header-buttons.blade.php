@hasrole('administrator')

<div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
  <a href="{{ route('admin.customer.trashed') }}" class="btn btn-danger ml-1" data-toggle="tooltip"
    title="View Trashed">
    <i class="fa fa-trash"></i>
  </a>
</div>

@endhasrole