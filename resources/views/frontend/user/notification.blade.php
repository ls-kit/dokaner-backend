@extends('frontend.layouts.app')

@section('title', __('navs.frontend.dashboard') )

@section('content')
  <div class="main_content">
    <div class="section pb_70">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-8">

            <div class="list-group">
              @forelse($notifications as $notification)
                @if($notification->type == "App\Notifications\InvoicePaymentInformation")
                  <a href="{{ route('frontend.user.invoice-details', $notification->data['invoice_id']) }}" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                      <h5 class="mb-1">Your invoice ready for payment!</h5>
                      <small>{{\Carbon\Carbon::parse($notification->created_at)->diffForHumans()}}</small>
                    </div>
                    <p class="mb-1">Your product on the way. you can pay from here </p>
                    <small>Thank you!</small>
                  </a>
                @elseif($notification->type = "App\Notifications\OrderAuthInformation")
                  <a href="#" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                      <h5 class="mb-1">Customer placed a order !</h5>
                      <small>{{\Carbon\Carbon::parse($notification->created_at)->diffForHumans()}}</small>
                    </div>
{{--                    <p class="mb-1">Some placeholder content in a paragraph.</p>--}}
{{--                    <small>And some small print.</small>--}}
                  </a>
                @endif
              @empty
                <a href="#" class="list-group-item list-group-item-action active">
                  <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">You have no notifications</h5>
                    <small>Just now</small>
                  </div>
                  <p class="mb-1">Something happening you will get notification here</p>
                  <small>Thank you!</small>
                </a>
              @endforelse
            </div>

          </div>
        </div>
      </div>
    </div>
  </div> <!-- END MAIN CONTENT -->
@endsection


@push('after-styles')
  @livewireStyles
@endpush

@push('after-scripts')

  @livewireScripts

  @if (request('payment') === 'successful')
    <script>
       window.localStorage.removeItem('TB0pQDcfjymepm6vmhwp66LASgLgSHaoDU2');
       window.localStorage.removeItem('summary');


    </script>
  @endif


  <script>


     $(function () {
        $(document).on('click', '.nav-tabs li a', function (event) {
           event.preventDefault();
           var href = $(this).attr('href');
           href = href.replace("#", '');
           window.history.replaceState({}, "", 'dashboard?tab=' + href);
        })
     })


  </script>

@endpush
