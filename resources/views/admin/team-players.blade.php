@section('title', __('Players'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Team') }} : {{ $team_name }}
            
        </h2>
    </x-slot>

    <!-- Add Item Popup -->
    <x-popup-add-item-model title="{{__('Add Player')}}" formType="add" formID="popupAddForm" popupClasses="column-1" actionButtonLabel="{{__('Add Player')}}">
        <x-popup-form-input type="select" id="player_id" name="player_id" label="{{ __('Player:') }}" value="" class="required focus_first" />
        <input type="hidden" name="team_id" id="team_id" value="{{ $team_id }}" />
    </x-popup-add-item-model>

    <!-- Add Item Popup -->
    <x-popup-delete-item-model title="{{__('Remove Player')}}" buttonTitle="{{ __('Remove') }}">
        {{ __('Do you want to remove player from ' . $team_name .' team?' ) }} {{ $team_name }}
    </x-popup-delete-item-model>    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="admin-page">
                <div class="p-6 text-gray-900">
                    <div class="table-header-search">
                        <div>
                            <button id="buttonPopupShowAddItemModel"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                {{ __('Add Player') }}
                            </button>
                        </div>
                        <div class="search-input">
                            <label for="table-search" class="sr-only">{{ __('Search') }}</label>
                            <div class="relative">
                                <form id="formSearch" method="GET">
                                    <div id="btnSearchText" class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 cursor-pointer outline-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" id="table-search"
                                        class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="{{ __('Search for items') }}">                                    
                                <form>
                            </div>
                        </div>
                    </div>                   

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <div class="table-container hscorll" id="tableContainer">
                            <table class="custom-table">
                                <thead id="table-head">
                                    <tr>
                                        <th>{{ __('#ID') }}</th>
                                        <th>{{ __('Unique Id') }}</th>
                                        <th>{{ __('Profile') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Profile Type') }}</th>
                                        <th>{{ __('Style') }}</th>
                                        <th>{{ __('Age') }}</th>
                                        <th>{{ __('Category') }}</th>
                                        <th class="view_actions">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body">
                                    <tr>
                                        <td colspan="9">
                                            <p class="text-center text-gray-800 dark:text-white">{{__('Please wait! loading table.')}}</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>                            
                        </div>
                    </div>
                    <div class="my-4 lg:flex justify-between items-center text-gray-900 dark:text-white">
                        <div id="pagination-info"></div>
                        <ul class="custom-pagination" id="pagination"></ul>                        
                    </div>                    
                </div>
            </div>
        </div>
    </div>
    <script>
        const lang = @json(getJSLang('team-player'));
        const BASE_API_URL = "{{ url('/api/backend/team/players/') }}";
        const image_url = "{{ url('/storage') }}";
        const master_id = "{{ $team_id }}";
        const player_ids = @json($player_ids);
        const autoCloseAddPopup = false;
    </script>
</x-app-layout>