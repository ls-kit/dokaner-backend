<div class="btn-group btn-group-sm" role="group" aria-label="@lang('labels.backend.access.users.user_actions')">

    {{-- @if ($order->status == 'partial-paid')
        <a href="{{ route('admin.order.makeAsFullPayment', $order) }}" class="btn btn-info" data-toggle="tooltip"
            data-placement="top" title="Make Full Paid">
            Make Full Paid
        </a>
    @endif

    @isset($incomplete)
        <a href="{{ route('admin.order.makeAsPayment', $order) }}" class="btn btn-info" data-toggle="tooltip"
            data-placement="top" title="Make Partial">
            Make Partial
        </a>
    @endif --}}
        <a href="{{ route('admin.order.show', $order) }}" class="btn btn-secondary" data-method="show" data-toggle="tooltip"
            data-placement="top" title="order details">
            <i class="fa fa-file-o"></i>
        </a>
    @if (($order->id !== 1) & $logged_in_user->isAdmin())
        <a href="{{ route('admin.order.destroy', $order) }}" data-method="delete"
            data-trans-button-cancel="@lang('buttons.general.cancel')" data-trans-button-confirm="@lang('buttons.general.crud.delete')"
            data-trans-title="Are you sure ?" class="btn btn-danger" data-toggle="tooltip" data-placement="top"
            title="@lang('buttons.general.crud.delete')">
            <i class="fa fa-trash-o"></i>
        </a>
    @endif
</div>
