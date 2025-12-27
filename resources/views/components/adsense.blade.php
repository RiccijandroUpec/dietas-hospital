@php
    $client = config('services.adsense.client');
    $enabled = (bool) config('services.adsense.enabled');
    $slot = $slot ?? null;
@endphp

@if($enabled && $client && $slot)
    <div class="my-4">
        <ins class="adsbygoogle"
            style="display:block"
            data-ad-client="{{ $client }}"
            data-ad-slot="{{ $slot }}"
            data-ad-format="{{ $format ?? 'auto' }}"
            data-full-width-responsive="{{ ($responsive ?? true) ? 'true' : 'false' }}"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>
@else
    {{-- AdSense deshabilitado o sin configuración --}}
@endif
