@section('title', __('User Role'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('User Role') }}
        </h2>
    </x-slot>
    
     <!-- Add Item Popup -->
     <x-popup-add-item-model title="{{__('Add new Role')}}" formType="add" formID="popupAddForm" popupClasses="column-6" actionButtonLabel="{{__('Add Role')}}">    
        <x-popup-form-input type="text" name="name" label="{{ __('Role Name:') }}" class="focus_first name required role_name"
        maxlength="50" value="" />            
        
        <x-popup-form-input type="checkbox" name="permission[]" id="add_permission" label="{{ __('Permissions:') }}" />
    </x-popup-add-item-model>

    <!-- Add Item Popup -->
    <x-popup-delete-item-model title="{{__('Delete Role')}}">
        {{ __('Do you want to delete role?') }}
    </x-popup-delete-item-model>

    <!-- Update Item Popup -->
    <x-popup-update-item-model title="{{__('Update Role')}}" formType="update" formID="popupUpdateForm" popupClasses="column-6" actionButtonLabel="{{__('Update Role')}}">    
        <x-popup-form-input type="text" name="name" id="update_name" label="{{ __('Role Name:') }}" class="focus_first name required role_name" maxlength="50" />
        <x-popup-form-input type="checkbox" name="permission[]" id="update_permission" label="{{ __('Permissions:') }}" />

    </x-popup-update-item-model>

    <x-grid-page addButtonLabel="{{ __('Add Role')}}" addButtonPermission="user-role-create" searchTextboxPlaceholder="{{ __('Search for user roles') }}" />

    <script>
        const lang = @json(getJSLang('user-roles'));
        const BASE_API_URL = "{{ route('admin.user-roles.index') }}";
        const autoCloseAddPopup = true;
    </script>
</x-app-layout>