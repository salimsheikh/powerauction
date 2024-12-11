<!-- {{ $label }}-->
<div class="flex flex-col md:flex-row md:items-center inputblock">    
    @if ($type == 'color')
        <label for="{{ $id }}" class="popup-label block md:w-1/3 mb-1 md:mb-0 md:text-left pr-4 dark:text-white">{{ $label }}</label>
        <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}"
            maxlength="10" class="{{ $name }} {{ $class }} textbox w-full md:w-2/3 focus:outline-none"
            placeholder="{{ $placeholder }}" />
    @elseif($type == 'file')
        <label for="{{ $id }}" class="popup-label block md:w-1/3 mb-1 md:mb-0 md:text-left pr-4 dark:text-white">{{ $label }}</label>
        <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}"
            value="{{ $value }}" maxlength="{{ $mexlength }}"
            class="{{ $name }} {{ $class }} inputbox" accept="image/png, image/gif, image/jpeg"
            placeholder="{{ $placeholder }}" />
    @elseif($type == 'textarea')
        <label for="{{ $id }}" class="popup-label block md:w-1/3 mb-1 md:mb-0 md:text-left pr-4 dark:text-white">{{ $label }}</label>
        <textarea name="{{ $name }}" id="{{ $id }}" cols="30" rows="3"
            class="{{ $name }} {{ $class }} inputbox" maxlength="{{ $mexlength }}"
            placeholder="{{ $placeholder }}">{{ $value }}</textarea>
    @elseif($type == 'select')
        <label for="{{ $id }}" class="popup-label block md:w-1/3 mb-1 md:mb-0 md:text-left pr-4 dark:text-white">{{ $label }}</label>
        <select id="{{ $id }}" name="{{ $name }}"
            class="{{ $name }} {{ $class }} inputbox">
            <option value="">{{ $firstOption }}</option>
            @foreach ($options as $option_name => $option_value)
                <option value="{{ $option_name }}"
                    {{ request($name) == $option_name || $value == $option_name ? 'selected' : '' }}>
                    {{ $option_value }}</option>
            @endforeach
        </select>
    @elseif($type == 'checkbox')
        <div>
            <div class="my-2 dark:text-white">{{ $label }} <a href="#" id="{{ $id }}" class="selectAllPermission" data-checkbox="{{ $id }}">{{ __('Select All') }}</a></div>
            <ul class="grid w-full gap-6 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4">
                @foreach ($options as $option_name => $option_value)
                    <li>
                        <input type="checkbox" id="{{ $id }}_{{ $option_name }}" name="{{ $name }}[]"
                            class="hidden peer {{ $id }}" value="{{ $option_value }}" />
    
                        <label for="{{ $id }}_{{ $option_name }}"
                            class="inline-flex items-center justify-between w-full px-2 py-1 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 peer-checked:border-blue-600 hover:text-gray-600 dark:peer-checked:text-gray-300 peer-checked:text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                            <div class="block">
                                <div class="w-full text-lg font-semibold">
                                    {{ Str::title(str_replace('-', ' ', $option_value)) }}</div>
                            </div>
                        </label>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <label for="{{ $id }}" class="popup-label block md:w-1/3 mb-1 md:mb-0 md:text-left pr-4 dark:text-white">{{ $label }}</label>
        <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}"
            value="{{ $value }}" class="{{ $name }} {{ $class }} inputbox"
            maxlength="{{ $mexlength }}" placeholder="{{ $placeholder }}" {{ $readOnly }} />
    @endif
</div>
