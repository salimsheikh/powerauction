@section('title', __('Users'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Users') }}
        </h2>
    </x-slot>
    
     <!-- Add Item Popup -->
     <x-popup-add-item-model title="{{__('Add new User')}}" formType="add" formID="popupAddForm" popupClasses="column-1" actionButtonLabel="{{__('Add User')}}">    
        <x-popup-form-input type="text" name="name" label="{{ __('Name:') }}" class="focus_first name required" maxlength="50" value="" />
        <x-popup-form-input type="text" name="phone" label="{{ __('Phone:') }}" class="phone " maxlength="15" value="" />
        <x-popup-form-input type="text" name="address" label="{{ __('Address:') }}" class="address " maxlength="150" value="" />
        <x-popup-form-input type="text" name="email" label="{{ __('Email:') }}" class="email_validate required" maxlength="150" value="" />

        <x-popup-form-input type="password" id="password" name="password"
            label="{{ __('Password:') }}" value="" class="required password" />

        <x-popup-form-input type="checkbox" name="roles[]" id="add_permission" label="{{ __('Roles:') }}" />

    </x-popup-add-item-model>

    <!-- Add Item Popup -->
    <x-popup-delete-item-model title="{{__('Delete Category')}}">
        {{ __('Do you want to delete category?') }}
    </x-popup-delete-item-model>

    <!-- Update Item Popup -->
    <x-popup-update-item-model title="{{__('Update User')}}" formType="update" formID="popupUpdateForm" popupClasses="column-1" actionButtonLabel="{{__('Update User')}}">    
        <x-popup-form-input type="text" name="name" id="update_name" label="{{ __('Name:') }}" class="focus_first name required"
        maxlength="50" value="" />        
        <x-popup-form-input type="text" name="phone" id="update_phone" label="{{ __('Phone:') }}" class=" phone " maxlength="15" value="" />
        <x-popup-form-input type="text" name="address" id="update_adderss" label="{{ __('Address:') }}" class=" address " maxlength="150" value=""  />
        <x-popup-form-input type="text" name="email" id="update_email" label="{{ __('Email:') }}" class=" email_validate" maxlength="150" value=""  readOnly="readonly"  />
        <x-popup-form-input type="password" id="update_password" name="password"
            label="{{ __('Password:') }}" value="" class="update_password" />

        <x-popup-form-input type="checkbox" name="roles[]" id="update_permission" label="{{ __('Roles:') }}" />

    </x-popup-update-item-model>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="admin-page">
                <div class="p-6 text-gray-900">
                    <div class="table-header-search">
                        <div>
                            <button id="buttonPopupShowAddItemModel"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                {{ __('Add User') }}
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
                                        <th>{{ __('Name') }}</th>
                                        <th class="actions">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body">
                                    <tr>
                                        <td colspan="3">
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
        const lang = @json(getJSLang('users'));
        const BASE_API_URL = "{{ route('admin.users.index') }}";
        const autoCloseAddPopup = true;
    </script>
</x-app-layout>