@props(['data' => []])
{{-- Structured data. Encoded with @json so values are escaped safely. --}}
<script type="application/ld+json">
{!! json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
