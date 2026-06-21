@props(['label' => null, 'name', 'error' => null, 'options' => [], 'placeholder' => 'Pilih...'])

<div class="space-y-1">

    @if($label)
        <label for="{{ $name }}"
               class="block text-sm font-medium text-gray-700">
            {{ $label }}
        </label>
    @endif

    <select
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->merge([
            'class' => 'w-full rounded-lg border px-3 py-2 text-sm shadow-sm transition
                        focus:outline-none focus:ring-2 focus:ring-indigo-500
                        ' . ($error ? 'border-red-400 bg-red-50' : 'border-gray-300')
        ]) }}
    >
        <option value="">{{ $placeholder }}</option>

        @foreach($options as $value => $label)
            <option value="{{ $value }}"
                {{ old($name) == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>

    @if($error)
        <p class="text-xs text-red-500">{{ $error }}</p>
    @endif

</div>