@extends('frontend.layouts.app')

@section('title', app_name() . ' | Order Your Package ')

@section('content')

<section class="contact-form pt-2">
  <div class="container">
    <div class="row">
      <div class="col col-sm-6">
        <div class="card shadow-none">
          <div class="card-body">
            <div class="faq-box mb-5">
              <div class="faq-ques rounded">
                <h5 class="p-3">{{$package->name}} / {{$type->name}}</h5>
              </div>
              <table class="table table-bordered">
                <tr>
                  <th>Package Name</th>
                  <td>{{$package->name}}</td>
                </tr>
                <tr>
                  <th>Package Features</th>
                  <td class="package-option">
                    @foreach($package->packageOption as $option)
                    <p @if($loop->last) class="mb-0" @endif><span class="mr-2"><i class="fas fa-check"></i></span>
                      {{$option->option_value}}</p>
                    @endforeach
                  </td>
                </tr>
                <tr>
                  <th>Regular Price</th>
                  <td><span>&#2547;</span> {{$package->regular_price}} / Yearly</td>
                </tr>
                <tr>
                  <th>Package Discount</th>
                  <td><span></span> {{100 - round($package->current_price / $package->regular_price * 100)}} %</td>
                </tr>
                <tr>
                  <th>Package Cost</th>
                  <td><span>&#2547;</span> {{$package->current_price}} / Yearly</td>
                </tr>
              </table> <!-- table -->
            </div> <!-- faq-box -->
          </div> <!-- card-body-->
        </div> <!-- card-->
      </div><!-- col-->

      <div class="col col-sm-6">
        <div class="card shadow-none">
          <div class="card-body">
            <div class="faq-box mb-5">
              <div class="faq-ques rounded">
                <h5 class="p-3">Placed Your Order</h5>
              </div>

              <div class="alert alert-warning" role="alert">
                <strong>Attention!</strong> You can be geting help from <a href="tel:8801670233470">+8801670233470</a>.
              </div>

              {{ html()->form('POST', route('admin.order.store'))->open() }}

              <input type="hidden" name="type" value="{{request('type')}}">
              <input type="hidden" name="plan" value="{{request('plan')}}">
              <input type="hidden" name="package" value="{{request('package')}}">


              <div class="form-group">
                {{ html()->text('domain')
                                                  ->class('form-control')
                                                  ->placeholder('Your Domain')
                                                  ->attribute('maxlength', 191)
                                                  ->required() }}
              </div> <!-- form-group-->

              <div class="form-group row">
                <label for="payment" class="col-sm-2">Payment</label>
                <div class="col-sm-10">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input payment" type="radio" name="payment_method" id="bkash" value="bkash"
                      checked>
                    <label class="form-check-label" for="bkash">Bkash</label>
                  </div> <!-- bkash -->
                  <div class="form-check form-check-inline">
                    <input class="form-check-input payment" type="radio" name="payment_method" id="rocket"
                      value="rocket">
                    <label class="form-check-label" for="rocket">Rocket</label>
                  </div> <!-- rocket -->
                </div> <!-- col-sm-10 -->
              </div> <!-- form-group-->


              <div class="form-group">
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text">Received agent</div>
                  </div>
                  {{ html()->text('agent_account', '01734905649')
                                                    ->class('form-control')
                                                    ->readonly()
                                                    ->attribute('maxlength', 14)
                                                    ->required() }}
                </div>
              </div> <!-- form-group-->


              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    {{ html()->select('subs_year', [1 => '1 Year', 2 => '2 Year', 3 => '3 Year', 4 => '4 Year', 5 => '5 Year'])
                                            ->class('form-control')
                                            ->id('subs_year') }}
                  </div> <!-- form-group-->
                </div> <!-- col -->
                <div class="col-6">
                  <div class="form-group">
                    <div class="input-group mb-2">
                      <div class="input-group-prepend">
                        <div class="input-group-text">Price <span class="ml-2">&#2547;</span></div>
                      </div>
                      <span class="d-none" id="rate">{{$package->current_price}}</span>
                      {{ html()->text('subs_total', $package->current_price)
                                                        ->class('form-control')
                                                        ->id('subs_total')
                                                        ->readonly()
                                                        ->attribute('maxlength', 6)
                                                        ->required() }}
                    </div>
                  </div> <!-- form-group-->
                </div> <!-- col -->
              </div> <!-- row-->

              <div class="row">
                <div class="col">
                  <div class="form-group">
                    {{ html()->text('client_account')
                                                      ->class('form-control')
                                                      ->placeholder('Your Account No')
                                                      ->attribute('maxlength', 14)
                                                      ->required() }}
                  </div> <!-- form-group-->
                </div> <!-- col -->
              </div> <!-- row-->


              <div class="row">
                <div class="col">
                  <div class="form-group">
                    {{ html()->text('transaction_no')
                                                      ->class('form-control')
                                                      ->placeholder('Transaction No')
                                                      ->attribute('maxlength', 20)
                                                      ->required() }}
                  </div> <!-- form-group-->
                </div> <!-- col -->
              </div> <!-- row-->

              @if(config('access.captcha.contact'))
              <div class="row">
                <div class="col">
                  @captcha
                  {{ html()->hidden('captcha_status', 'true') }}
                </div>
                <!--col-->
              </div> <!-- row-->
              @endif
              <div class="row">
                <div class="col">
                  <div class="form-group mb-0 clearfix">
                    {{ form_submit('Placed Order') }}
                  </div>
                  <!--form-group-->
                </div>
                <!--col-->
              </div> <!-- row-->
              {{ html()->form()->close() }}

            </div>
          </div> <!-- card-body -->
        </div> <!-- card-->
      </div> <!-- col-->
    </div> <!-- row-->
  </div> <!-- container-->
</section>
@endsection

@push('after-scripts')
@if(config('access.captcha.contact'))
@captchaScripts
@endif

<script>
  $(function () {
            $('#subs_year').change(function () {
                var year = $(this).val();
                var reate = $('#rate').text();
                $('#subs_total').val(reate * year);
            });

            $('.payment_method').change(function () {
                var method = $(this).val();
                // alert(year);
                // var reate = $('#rate').val();
                // $('#amount').val(reate*year);
            });

        });
</script>
@endpush