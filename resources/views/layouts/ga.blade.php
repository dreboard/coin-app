<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ config('ga.property') }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '{{ config('ga.property') }}', {'user_id': '{{ auth()->user()->id }}'});
    gtag('set', 'user_properties', { 'crm_id' : '{{ auth()->user()->id }}' });
</script>
