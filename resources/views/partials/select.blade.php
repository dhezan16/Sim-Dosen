{{--
    Dropdown untuk relasi referensi.
    Parameter: $name, $label, $items (koleksi model), $kolom (kolom yang ditampilkan),
               $value (nilai terpilih), $required (bool, default true), $disabled (bool, default false)
--}}
@php
    $required = $required ?? true;
    $disabled = $disabled ?? false;
@endphp
<div class="form-group">
    <label for="{{ $name }}">{{ $label }} @if ($required)<span class="required">*</span>@endif</label>
    <select id="{{ $name }}" name="{{ $name }}" @class(['form-control', 'is-invalid' => $errors->has($name)]) @required($required) @disabled($disabled)>
        <option value="">-- Pilih {{ $label }} --</option>
        @foreach ($items as $item)
            <option value="{{ $item->id }}" @selected(old($name, $value) == $item->id)>{{ $item->{$kolom} }}</option>
        @endforeach
    </select>
    @error($name)
        <small class="field-error">{{ $message }}</small>
    @enderror
</div>
