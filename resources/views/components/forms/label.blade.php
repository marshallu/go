@props(['required' => false, 'value'])

<label {{ $attributes->merge(['class' => 'uppercase font-semibold text-sm block']) }}>
    {{ $value ?? $slot }}
</label>
