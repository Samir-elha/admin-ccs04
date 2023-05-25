@props([
    'type' => 'ul',
])

@php
    $options = [
        'ul' => 'list-disc',
        'ol' => 'list-decimal',
        'group' => 'border border-gray-300 divide-y',
    ];
    $style = $options[$type] ?? $options['ul'];
@endphp

<ul {{ $attributes->merge(['class' => "$style list-inside"]) }}>
    {{ $slot }}
</ul>
