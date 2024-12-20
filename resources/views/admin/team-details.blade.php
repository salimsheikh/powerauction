@section('title', 'Team Details')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Team Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="admin-page">
                <div class="p-6 text-gray-900 dark:text-white">
                    <!-- Slider Container -->
                    <div id="slider" class="relative overflow-hidden group">

                        <!-- Slides -->
                        <div class="inset-0 flex transition-transform duration-700" id="autoSlides">
                            @foreach ($teams as $team)
                                <div class="flex-shrink-0 pb-8" style="width: 100%">
                                    <!-- First Row -->
                                    <div class="container mx-auto space-y-4">
                                        <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-4">

                                            <!-- Left Column (Image) -->
                                            <div class="flex flex-col items-center md:items-start gap-4">
                                                <h1 class="text-2xl font-bold text-center">{{ $team['team_name'] }}</h1>
                                                <img src="{{ url('/storage') }}/teams/{{ $team['team_logo'] }}"
                                                    alt="{{ $team['team_name'] }}" class="rounded-lg shadow-lg">
                                            </div>

                                            <!-- Right Column (Four Gradient Boxes) -->
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div
                                                    class="p-4 bg-gradient-to-br from-purple-500 via-pink-500 to-red-500 text-white rounded-lg shadow-md">
                                                    <label
                                                        class="text-sm font-bold block">{{ __('Virtual Points') }}</label>
                                                    <p class="text-lg font-semibold mt-1">{{ $team['virtual_point'] }}
                                                    </p>
                                                </div>
                                                <div
                                                    class="p-4 bg-gradient-to-br from-blue-500 via-green-500 to-teal-500 text-white rounded-lg shadow-md">
                                                    <label
                                                        class="text-sm font-bold block">{{ __('Points Spent') }}</label>
                                                    <p class="text-lg font-semibold mt-1">{{ $team['points_spent'] }}
                                                    </p>
                                                </div>
                                                <div
                                                    class="p-4 bg-gradient-to-br from-yellow-400 via-orange-500 to-red-500 text-white rounded-lg shadow-md">
                                                    <label
                                                        class="text-sm font-bold block">{{ __('Points Remaining') }}</label>
                                                    <p class="text-lg font-semibold mt-1">
                                                        {{ $team['points_remaining'] }}</p>
                                                </div>
                                                <div
                                                    class="p-4 bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-600 text-white rounded-lg shadow-md">
                                                    <label
                                                        class="text-sm font-bold block">{{ __('Exceeded By') }}</label>
                                                    <p class="text-lg font-semibold mt-1">{{ $team['exceeded_by'] }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        @if (count($team['players']) > 0)
                                            <div class="overflow-x-auto team-players">
                                                <table class="custom-table">
                                                    <thead>
                                                        <tr class="">
                                                            @foreach ($columns as $column_name => $column_label)
                                                                @switch($column_name)
                                                                    @case('base_price')
                                                                    @case('sold_price')
                                                                        <th class="{{ $column_name }} text-right">
                                                                            {{ $column_label }}</th>
                                                                    @break

                                                                    @default
                                                                        <th class="{{ $column_name }}">{{ $column_label }}
                                                                        </th>
                                                                    @break
                                                                @endswitch
                                                            @endforeach
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($team['players'] as $player)
                                                            <tr>
                                                                @foreach ($columns as $column_name => $column_label)
                                                                    @switch($column_name)
                                                                        @case('image_thumb')
                                                                            <td class="{{ $column_name }}">
                                                                                <img class="profile-image rounded-full shadow-md"
                                                                                    src="{{ url('/storage') }}/players/thumbs/{{ $player['image_thumb'] }}"
                                                                                    alt="{{ $player['player_name'] }}">
                                                                            </td>
                                                                        @break

                                                                        @case('base_price')
                                                                        @case('sold_price')
                                                                            <td class="{{ $column_name }} text-right">
                                                                                {{ $player[$column_name] }}</td>
                                                                        @break

                                                                        @default
                                                                            <td class="{{ $column_name }}">
                                                                                {{ $player[$column_name] }}</td>
                                                                        @break
                                                                    @endswitch
                                                                @endforeach
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="flex items-center justify-center h-52">
                                                <div class="text-center">
                                                    {{ __('Player not added') }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                        </div>

                        <!-- Hover Button -->
                        <button id="slider-toggle"
                            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-black/50 text-white px-6 py-2 rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            {{ __('Pause') }}
                        </button>

                        <!-- Navigation Dots -->
                        <div class="absolute bottom-4 w-full flex justify-center space-x-2">
                            @foreach ($teams as $key => $team)
                                <div class="slideDot w-3 h-3 bg-gray-500 rounded-full cursor-pointer"
                                    data-index="{{ $key }}"></div>
                            @endforeach
                            <!-- Add more slideDots as needed for additional autoSlides -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const lang = @json(getJSLang('page'));
    </script>
</x-app-layout>
