<div class="btn-group btn-group-sm" role="group" aria-label="@lang('labels.backend.access.users.user_actions')">
  <a href="{{ route('admin.customer.edit', $customer) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top"
     title="@lang('buttons.general.crud.edit')">
    <i class="fa fa-edit"></i>
  </a>

  @hasrole('administrator')
  <a href="{{ route('admin.customer.destroy', $customer) }}" data-method="delete"
     data-trans-button-cancel="@lang('buttons.general.cancel')"
     data-trans-button-confirm="@lang('buttons.general.crud.delete')"
     data-trans-title="Are You Sure ?" class="btn btn-danger" data-toggle="tooltip"
     data-placement="top" title="@lang('buttons.general.crud.delete')">
    <i class="fa fa-trash"></i>
  </a>
  @endhasrole
</div>