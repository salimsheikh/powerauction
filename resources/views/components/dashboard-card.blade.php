<div class="w-full mt-6 px-6 {{ $wrapperClass }} xl:mt-0">
    <div class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white dark:bg-black">
        <div class="dashboard-icon p-3 rounded-full bg-{{ $iconBgColor }} bg-opacity-75 flex items-center text-center">
            <span class="material-icons h-8 w-8 text-white leading-snug">{{ $icon }}</span>
        </div>

        <div class="mx-5">
            <h4 class="text-2xl font-semibold text-gray-700 dark:text-white">{!! $value !!}</h4>
            <div class="text-gray-500  dark:text-white">{{ $title }}</div>
        </div>
    </div>
</div>