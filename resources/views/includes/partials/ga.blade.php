@if(config('analytics.google-analytics') && config('analytics.google-analytics') !== 'UA-XXXXX-X')
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ config('analytics.google-analytics') }}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '{{ config('analytics.google-analytics') }}');
</script>
@endif