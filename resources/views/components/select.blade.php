@props([
    'disabled' => false,
    'options' => [],
    'selected' => '',
])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!}>
    @foreach ($options as $value => $label)
        <option value="{{ $value }}" {{ $value == $selected ? 'selected' : '' }}>{{ $label }}</option>
    @endforeach
</select>
