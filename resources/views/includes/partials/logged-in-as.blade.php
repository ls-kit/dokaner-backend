@impersonating
<div class="alert alert-warning logged-in-as m-0 sticky-top">
  <div class="container text-center">
    You are currently logged in as {{ auth()->user()->name }}. <a href="{{ route('impersonate.leave') }}">Return to your
      account</a>.
  </div>
</div>
<!--alert alert-warning logged-in-as-->
@endImpersonating
