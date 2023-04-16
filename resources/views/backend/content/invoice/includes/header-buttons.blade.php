@hasrole('administrator')
<div class="btn-group float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
  <a href="{{ route('admin.export', 'invoices') }}" class="btn btn-warning" data-toggle="tooltip" title="Full Export">
    <i class="fa fa-download"></i> Export
  </a>
  <a href="{{ route('admin.invoice.trashed') }}" class="btn btn-danger" data-toggle="tooltip" title="Trashed Invoice">
    <i class="fa fa-trash"></i>
  </a>
</div>
@endhasrole