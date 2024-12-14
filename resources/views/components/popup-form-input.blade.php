<!-- {{ $label }}-->
<div class="flex flex-col md:flex-row md:items-center inputblock">
    @if ($type == 'color')
        <label for="{{ $id }}"
            class="popup-label block md:w-1/3 mb-1 md:mb-0 md:text-left pr-4 dark:text-white">{{ $label }}</label>
        <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}"
            maxlength="10" class="{{ $name }} {{ $class }} textbox w-full md:w-2/3 focus:outline-none"
            placeholder="{{ $placeholder }}" />
    @elseif($type == 'file')
        <label for="{{ $id }}"
            class="popup-label block md:w-1/3 mb-1 md:mb-0 md:text-left pr-4 dark:text-white">{{ $label }}</label>
        <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}"
            value="{{ $value }}" maxlength="{{ $maxlength }}"
            class="{{ $name }} {{ $class }} inputbox" accept="image/png, image/gif, image/jpeg"
            placeholder="{{ $placeholder }}" />
    @elseif($type == 'textarea')
        <label for="{{ $id }}"
            class="popup-label block md:w-1/3 mb-1 md:mb-0 md:text-left pr-4 dark:text-white">{{ $label }}</label>
        <textarea name="{{ $name }}" id="{{ $id }}" cols="30" rows="3"
            class="{{ $name }} {{ $class }} inputbox" maxlength="{{ $maxlength }}"
            placeholder="{{ $placeholder }}">{{ $value }}</textarea>
    @elseif($type == 'select')
        <label for="{{ $id }}"
            class="popup-label block md:w-1/3 mb-1 md:mb-0 md:text-left pr-4 dark:text-white">{{ $label }}</label>
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
        <div class="w-full">
            <div class="my-2 dark:text-white flex items-center justify-between">
                <div>{{ $label }} <a href="#" id="{{ $id }}"
                        class="selectAllPermission select-all-{{ $id }}"
                        data-checkbox="{{ $id }}">{{ __('Select All') }}</a></div>
                <div class="flex items-center justify-between gap-1">
                    @if ($name == 'permission[]')
                        <div class="popup-search-box">
                            <input type="text" name="search-tag" value=""
                                class="search-tags inputbox textbox-{{ $id }}" autocomplete="off">
                            <span class="popup-search-clear" data-checkbox="{{ $id }}">x</span>
                        </div>
                        <button type="button"
                            class="px-4 py-1 bg-gray-600 text-gray-200 rounded hover:bg-gray-700 btn-search-tags button-{{ $id }}"
                            data-checkbox="{{ $id }}">{{ __('Search') }}</button>
                    @endif
                </div>
            </div>
            {{-- grid w-full gap-4 grid-cols-2 sm:grid-cols-2 md:grid-cols-6 lg:grid-cols-4 --}}
            <ul class="grid w-full gap-4 popup-checkbox-grid items-{{ $id }}">
                @foreach ($options as $option_name => $option_value)
                    <li style="display: block">
                        <input type="checkbox" id="{{ $id }}_{{ $option_name }}"
                            name="{{ $name }}" class="hidden peer {{ $id }}"
                            value="{{ $option_value }}" />

                        <label for="{{ $id }}_{{ $option_name }}" class="btn-checkbox">
                            <div class="block">
                                <div class="w-full text-sm font-semibold">
                                    {{ Str::title(str_replace('-', ' ', $option_value)) }}</div>
                            </div>
                        </label>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <label for="{{ $id }}"
            class="popup-label block md:w-1/3 mb-1 md:mb-0 md:text-left pr-4 dark:text-white">{{ $label }}</label>
        <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}"
            value="{{ $value }}" class="{{ $name }} {{ $class }} inputbox"
            maxlength="{{ $maxlength }}" placeholder="{{ $placeholder }}" {{ $readOnly }} />
    @endif
</div>
