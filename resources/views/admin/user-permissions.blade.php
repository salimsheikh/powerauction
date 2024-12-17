@section('title', __('User Permission'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('User Permission') }}
        </h2>
    </x-slot>
    
     <!-- Add Item Popup -->
     <x-popup-add-item-model title="{{__('Add new Permission')}}" formType="add" formID="popupAddForm" popupClasses="column-1" actionButtonLabel="{{__('Add Permission')}}">    
        <x-popup-form-input type="text" name="name" label="{{ __('Permission Name:') }}" class="focus_first name required role_name"
        maxlength="30" value="" />
    </x-popup-add-item-model>

    <!-- Add Item Popup -->
    <x-popup-delete-item-model title="{{__('Delete Permission')}}">
        {{ __('Do you want to delete permission?') }}
    </x-popup-delete-item-model>

    <!-- Update Item Popup -->
    <x-popup-update-item-model title="{{__('Update Permission')}}" formType="update" formID="popupUpdateForm" popupClasses="column-1" actionButtonLabel="{{__('Update Permission')}}">    
        <x-popup-form-input type="text" name="name" id="update_name" label="{{ __('Permission Name:') }}" class="focus_first name required role_name" maxlength="30" />
    </x-popup-update-item-model>

    <x-grid-page addButtonLabel="{{ __('Add Permission')}}" addButtonPermission="user-permission-create" searchTextboxPlaceholder="{{ __('Search for user permissions') }}" />

    <script>
        const lang = @json(getJSLang('user-permissions'));
        const BASE_API_URL = "{{ route('admin.user-permissions.index') }}";
        const autoCloseAddPopup = true;
    </script>
</x-app-layout>