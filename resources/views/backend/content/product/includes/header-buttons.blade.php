<div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
  @hasrole('administrator')
  <a href="{{ route('admin.product.trashed') }}" class="btn btn-danger ml-1" data-toggle="tooltip"
     title="View Trashed"><i class="fa fa-trash-o"></i></a>
  @endhasrole
</div>