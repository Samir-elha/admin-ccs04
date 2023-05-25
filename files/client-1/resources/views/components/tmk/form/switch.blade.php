@props([
    'checked' => false,
    'disabled' => false,
    'value' => 'true',
    'name' => null,
    'id' => null,
    'colorOff' => 'bg-gray-200',
    'colorOn' => 'bg-green-300',
    'textOff' => '✘',
    'textOn' => '✓',
])

@php
    $checked = filter_var($checked, FILTER_VALIDATE_BOOLEAN);
    $disabled = filter_var($disabled, FILTER_VALIDATE_BOOLEAN);
    $name = $name ?? 'checkbox_' . rand();
    $id = $id ?? $name;
    $cursor = $disabled ? 'cursor-not-allowed opacity-50' : 'cursor-pointer';
@endphp

<label
    {{ $attributes->merge(['class' => "$cursor border border-gray-300 rounded-md text-center font-semibold uppercase text-xs h-9 w-16 inline-flex flex-wrap overflow-hidden"]) }}>
    <input type="checkbox" class="hidden peer" name="{{ $name }}" id="{{ $name }}"
        value="{{ $value }}" {{ $checked ? 'checked' : '' }} {{ $disabled ? 'disabled' : '' }}
        wire:model="{{ $attributes->get('wire:model') }}">
    <span data-content="{{ $textOff }}"
        class="
            self-stretch w-full h-full flex justify-center items-center
            {{ $colorOff }}
            before:content-[attr(data-content)]
            peer-checked:-translate-x-full
            transition duration-300 ease-in-out"></span>
    <span data-content="{{ $textOn }}"
        class="
            self-stretch w-full h-full flex justify-center items-center
            {{ $colorOn }}
            before:content-[attr(data-content)]
            -translate-y-full translate-x-full
            peer-checked:-translate-x-0
            transition duration-300 ease-in-out"></span>
</label>
