<div class="btn-group btn-group-sm" role="group" aria-label="@lang('labels.backend.access.users.user_actions')">
  <a href="{{ route('admin.customer.restore', $customer) }}" class="btn btn-primary" data-toggle="tooltip"
     data-placement="top" title="Restore">
    <i class="fa fa-repeat"></i>
  </a>

  @hasrole('administrator')
  <a href="{{ route('admin.customer.destroy', $customer) }}" data-method="delete"
     data-trans-button-cancel="@lang('buttons.general.cancel')"
     data-trans-button-confirm="@lang('buttons.general.crud.delete')"
     data-trans-title="Are You Sure ?" class="btn btn-danger" data-toggle="tooltip"
     data-placement="top" title="Permanent Delete">
    <i class="fa fa-trash"></i>
  </a>
  @endhasrole
</div>