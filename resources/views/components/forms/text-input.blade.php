@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'form-input rounded mt-1 w-full focus:ring-green focus:border-green']) !!}>
