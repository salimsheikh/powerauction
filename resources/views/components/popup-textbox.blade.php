<!-- {{$label}}-->
<div class="flex flex-col md:flex-row md:items-center">
    <label for="{{$name}}" class="block md:w-1/3 mb-1 md:mb-0 md:text-left pr-4 dark:text-white">{{$label}}</label>
    @if($type == 'color')
        <input type="{{$type}}" id="{{$name}}" name="{{$name}}" value="{{$value}}" maxlength="10" class="{{$name}} {{$class}} textbox w-full md:w-2/3 focus:outline-none" />
    @else
        <input type="{{$type}}" id="{{$name}}" name="{{$name}}" value="{{$value}}" maxlength="{{$maxlength}}" class="{{$name}} {{$class}} textbox w-full md:w-2/3 px-3 py-1 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring focus:ring-blue-400 dark:text-white" />
    @endif    
</div>