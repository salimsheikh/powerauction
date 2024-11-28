<!-- {{ $label }}-->
<div class="flex flex-col md:flex-row md:items-center inputblock">
    <label for="{{ $id }}"
        class="block md:w-1/3 mb-1 md:mb-0 md:text-left pr-4 dark:text-white">{{ $label }}</label>
    @if ($type == 'color')
        <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}"
            maxlength="10" class="{{ $name }} {{ $class }} textbox w-full md:w-2/3 focus:outline-none"
            placeholder="{{ $placeholder }}" />
    @elseif($type == 'file')
        <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}"
            value="{{ $value }}" maxlength="{{ $mexlength }}"
            class="{{ $name }} {{ $class }} inputbox" accept="image/png, image/gif, image/jpeg"
            placeholder="{{ $placeholder }}" />
    @elseif($type == 'textarea')
        <textarea name="{{ $name }}" id="{{ $id }}" cols="30" rows="3"
            class="{{ $name }} {{ $class }} inputbox" maxlength="{{ $mexlength }}"
            placeholder="{{ $placeholder }}">{{ $value }}</textarea>
    @elseif($type == 'select')
        <select id="{{ $id }}" name="{{ $name }}"
            class="{{ $name }} {{ $class }} inputbox">
            <option value="">{{ $firstOption }}</option>
            @foreach ($options as $option_name => $value)
                <option value="{{ $option_name }}">{{ $value }}</option>
            @endforeach
        </select>
    @else
        <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}"
            value="{{ $value }}" class="{{ $name }} {{ $class }} inputbox"
            maxlength="{{ $mexlength }}" placeholder="{{ $placeholder }}"
            {{ $readOnly }} />
    @endif
</div>
