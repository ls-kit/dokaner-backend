<div class="btn-group btn-group-sm" role="group">
  <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
          aria-expanded="false">
    <i class="fas fa-cog"></i>
  </button>
  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
    <a class="dropdown-item" href="{{route('frontend.customer.order.details', $order)}}">Details</a>

    @if($order->status == 'Waiting for Payment')
      <a class="dropdown-item" href="{{route('frontend.user.failedOrderPayNow', $order->transaction_id)}}">Pay Now</a>
      <a class="dropdown-item"
         data-method="delete"
         data-trans-title="Are you sure?"
         data-trans-button-confirm="Confirm"
         data-trans-button-cancel="Cancel"
         href="{{route('frontend.ajax.customer.incomplete.order.delete', $order)}}">Delete</a>
    @endif
  </div>
</div>