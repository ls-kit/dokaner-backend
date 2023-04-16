@if (config('services.bitbucket.active'))
<a href='{{ route('frontend.auth.social.login', 'bitbucket') }}' class='btn btn-sm btn-outline-info m-1'><i
    class='fab fa-bitbucket'></i> @lang('labels.frontend.auth.login_with', ['social_media' => 'BitBucket'])</a>
@endif

@if (config('services.facebook.active'))
<a href='{{ route('frontend.auth.social.login', 'facebook') }}' class="css-zqtbdk">
  <span class="css-1vqao0l">
    <span class="css-t5emrf">
      <img src="{{asset('images/facebook-logo.svg')}}" alt=""
        style="height: 18px; margin-right: 8px; width: 18px;"><span>Continue with Facebook</span>
    </span>
  </span>
</a>
@endif

@if (config('services.google.active'))
<a href='{{ route('frontend.auth.social.login', 'google') }}' class="css-zqtbdk">
  <span class="css-1vqao0l">
    <span class="css-t5emrf">
      <img src="{{asset('images/google-logo.svg')}}" alt=""
        style="height: 18px; margin-right: 8px; width: 18px;"><span>Continue with Google</span>
    </span>
  </span>
</a>
@endif

@if (config('services.github.active'))
<a href='{{ route('frontend.auth.social.login', 'github') }}' class='btn btn-sm btn-outline-info m-1'><i
    class='fab fa-github'></i> @lang('labels.frontend.auth.login_with', ['social_media' => 'Github'])</a>
@endif

@if (config('services.linkedin.active'))
<a href='{{ route('frontend.auth.social.login', 'linkedin') }}' class='btn btn-sm btn-outline-info m-1'><i
    class='fab fa-linkedin'></i> @lang('labels.frontend.auth.login_with', ['social_media' => 'LinkedIn'])</a>
@endif

@if (config('services.twitter.active'))
<a href='{{ route('frontend.auth.social.login', 'twitter') }}' class='btn btn-sm btn-outline-info m-1'><i
    class='fab fa-twitter'></i> @lang('labels.frontend.auth.login_with', ['social_media' => 'Twitter'])</a>
@endif