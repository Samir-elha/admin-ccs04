@props([
    'type' => 'success',
    'dismissible' => true,
    'closeSelf' => 0,
])

@php
    $dismissible = filter_var($dismissible, FILTER_VALIDATE_BOOLEAN);
    $options = [
        'success' => 'text-emerald-900 bg-emerald-100 border-emerald-300',
        'danger' => 'text-red-900 bg-red-100 border-red-300',
        'info' => 'text-sky-900 bg-sky-100 border-sky-300',
        'warning' => 'text-orange-900 bg-orange-100 border-orange-300',
    ];
    $style = $options[$type] ?? $options['success'];
@endphp

<div wire:key="{{ rand() }}" {{ $attributes->merge(['class' => "$style flex gap-4 p-4 my-4 rounded border"]) }}
    x-data="{ open: true }" x-show="open" x-transition.duration.300ms
    @if ($closeSelf > 0) x-init="setTimeout(() => open = false, {{ $closeSelf }})" @endif>

    <div class="flex-1">
        {{ $slot }}
    </div>
    @if ($dismissible)
        <div class="w-6 h-6 flex-none cursor-pointer {{ $style }}" @click="open = false">

            <x-heroicon-s-x-circle />
        </div>
    @endif
</div>
