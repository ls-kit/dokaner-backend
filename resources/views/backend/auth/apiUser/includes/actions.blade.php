<div class="btn-group" role="group" aria-label="@lang('labels.backend.access.users.user_actions')">
    <a href="{{ route('admin.auth.api.user.log', $user) }}" data-toggle="tooltip" data-placement="top" title="API Log" class="btn btn-info">
        <i class="fa fa-list-ol"></i>
    </a>

    <a href="{{ route('admin.auth.api.user.edit', $user) }}" data-toggle="tooltip" data-placement="top" title="@lang('buttons.general.crud.edit')" class="btn btn-primary">
        <i class="fa fa-edit"></i>
    </a>
</div>