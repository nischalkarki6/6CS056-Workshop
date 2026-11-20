@props(['name', 'label', 'type' => 'text', 'value' => ''])
<div class="form-group">
    <label for="{{ $name }}">{{ $label }}</label>
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}" {{ $attributes }}>
    @error($name)
        <p class="error">{{ $message }}</p>
    @enderror
</div>
