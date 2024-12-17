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

    <x-grid-page addButtonLabel="{{ __('Add User')}}" addButtonPermission="user-create" searchTextboxPlaceholder="{{ __('Search for users') }}" />    

    <script>
        const lang = @json(getJSLang('users'));
        const BASE_API_URL = "{{ route('admin.users.index') }}";
        const autoCloseAddPopup = true;
    </script>
</x-app-layout>