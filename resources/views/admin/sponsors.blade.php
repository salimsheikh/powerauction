<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Sponsors') }}
        </h2>
    </x-slot>

    <!-- Add Item Popup -->
    <x-popup-add-item-model title="{{__('Add new Sponsor')}}" formType="add" formID="popupAddForm" popupClasses="column-2" actionButtonLabel="{{__('Add Sponsor')}}">
        <x-popup-form-input type="text" id="sponsor_name" name="sponsor_name" label="{{ __('Sponsors Name:') }}" class="focus_first sponsor_name required"
            maxlength="50" value="" />
        <x-popup-form-input type="file" id="image" name="image" label="{{ __('Sponsor Profile:') }}"
            class="required image" value="" />
        <x-popup-form-input type="select" id="profile_type" name="profile_type" label="{{ __('Profile Type:') }}" class="required profile_type"
            maxlength="255" value="" />
        <x-popup-form-input type="select" id="type" name="type" label="{{ __('Type:') }}" value=""
            class="required" />
        <x-popup-form-input type="select" id="style" name="style" label="{{ __('Style:') }}" value=""
            class="required" />
        <x-popup-form-input type="date" id="dob" name="dob" label="{{ __('DOB:') }}" value=""
            class="required" />
        <x-popup-form-input type="select" id="category_id" name="category_id" label="{{ __('Category:') }}" value=""
            class="required" />
        <x-popup-form-input type="text" id="nickname" name="nickname" label="{{ __('Nick Name:') }}" value=""
            class="required" />
        <x-popup-form-input type="text" id="last_played_league" name="last_played_league" label="{{ __('Last Played League:') }}" value=""
            class="required" />
        <x-popup-form-input type="textarea" id="address" name="address" label="{{ __('Address:') }}" value=""
            class="required" />
        <x-popup-form-input type="text" id="city" name="city" label="{{ __('City:') }}" value=""
            class="required" />
        <x-popup-form-input type="text" id="email" name="email" label="{{ __('Email:') }}" value=""
            class="required email_validate" />
    </x-popup-add-item-model>

    <!-- Add Item Popup -->
    <x-popup-delete-item-model title="{{__('Delete Sponsor')}}">
        {{ __('Do you want to delete Sponsor?') }}
    </x-popup-delete-item-model>

    <!-- Update Item Popup -->
    <x-popup-update-item-model title="{{__('Update Sponsor')}}" formType="update" formID="popupUpdateForm" popupClasses="column-2" actionButtonLabel="{{__('Update Sponsor')}}">
        <x-popup-form-input type="text" id="update_sponsor_name" name="sponsor_name" label="{{ __('Sponsors Name:') }}" class="focus_first sponsor_name required"
            maxlength="50" value="" />
        <x-popup-form-input type="file" id="update_image" name="image" label="{{ __('Sponsor Profile:') }}"
            class="image" maxlength="10" value="" />
        <x-popup-form-input type="select" id="update_profile_type" name="profile_type" label="{{ __('Profile Type:') }}" class="required profile_type"
            maxlength="255" value="" />
        <x-popup-form-input type="select" id="update_type" name="type" label="{{ __('Type:') }}" value=""
            class="required" />
        <x-popup-form-input type="select" id="update_style" name="style" label="{{ __('Style:') }}" value=""
            class="required" />
        <x-popup-form-input type="date" id="update_dob" name="dob" label="{{ __('DOB:') }}" value=""
            class="required" />
        <x-popup-form-input type="select" id="update_category_id" name="category_id" label="{{ __('Category:') }}" value=""
            class="required" />
        <x-popup-form-input type="text" id="update_nickname" name="nickname" label="{{ __('Nick Name:') }}" value=""
            class="required" />
        <x-popup-form-input type="text" id="update_last_played_league" name="last_played_league" label="{{ __('Last Played League:') }}" value=""
            class="required" />
        <x-popup-form-input type="textarea" id="update_address" name="address" label="{{ __('Address:') }}" value=""
            class="required" />
        <x-popup-form-input type="text" id="update_city" name="city" label="{{ __('City:') }}" value=""
            class="required" />
        <x-popup-form-input type="text" id="update_email" name="email" label="{{ __('Email:') }}" value=""
            class="required email_validate" />
    </x-popup-update-item-model>


    <x-popup-view-item-model title="{{__('View Sponsor')}}" popupClasses="column-1">
        <div id="viewItems">
            <div class="alert model-body-alert alert-hidden">Sponsor successfully found.</div>
            <div class="flex justify-center mb-4">
                <img src="{{ url('/img') }}/profile.png" alt="Profile Image" class="w-24 h-24 rounded-full shadow-md">
            </div>            
            <table class="custom-table view"><tbody></tbody></table>
            <div class="flex justify-around mt-4">
                <button class="popupCloseModel px-4 py-2 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                {{ __('Close') }}
                </button>
            </div>
        </div>
    </x-popup-view-item-model>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg dark:bg-slate-800 dark:border-gray-600">                
                <div class="p-6 text-gray-900">
                    <div class="table-header-search">
                        <div>
                            <button id="buttonPopupShowAddItemModel"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                {{ __('Add Sponsor') }}
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
                        <div class="table-container" id="tableContainer">
                            <table class="custom-table">
                                <thead id="table-head">
                                    <tr>
                                        <th>{{ __('Sr.') }}</th>
                                        <th>{{ __('Logo') }}</th>
                                        <th>{{ __('Sponsors Name') }}</th>
                                        <th>{{ __('Sponsors Description') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th class="actions">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body">
                                    <tr>
                                        <td colspan="6">
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
        const lang = @json(getJSLang('sponsor'));
        const BASE_API_URL = "{{ url('/api/backend/sponsors/') }}";
        const image_url = "{{ url('/storage') }}";
    </script>
</x-app-layout>