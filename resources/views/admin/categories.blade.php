@section('title', __('Categories'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Category') }}
        </h2>
    </x-slot>    

    <!-- Add Item Popup -->
    <x-popup-add-item-model title="{{__('Add new Category')}}" formType="add" formID="popupAddForm" popupClasses="column-1" actionButtonLabel="{{__('Add Category')}}">    
        <x-popup-form-input type="text" name="category_name" label="{{ __('Category Name:') }}" class="focus_first category_name required"
            maxlength="50" value="" />
        <x-popup-form-input type="text" name="base_price" label="{{ __('Base Price:') }}"
            class="required price_validate" maxlength="10" value="" />
        <x-popup-form-input type="textarea" name="description" label="{{ __('Description:') }}" class="required"
            maxlength="255" value="" />
        <x-popup-form-input type="color" name="color_code" label="{{ __('Color Code:') }}" value="#000001"
            class="color_code" />
    </x-popup-add-item-model>

    <!-- Add Item Popup -->
    <x-popup-delete-item-model title="{{__('Delete Category')}}">
        {{ __('Do you want to delete category?') }}
    </x-popup-delete-item-model>

    <!-- Update Item Popup -->
    <x-popup-update-item-model title="{{__('Update Category')}}" formType="update" formID="popupUpdateForm" popupClasses="column-1" actionButtonLabel="{{__('Update Category')}}">    
        <x-popup-form-input type="text" name="category_name" id="update_category_name" label="{{ __('Category Name:') }}" class="focus_first category_name required"
            maxlength="50" value="" />
        <x-popup-form-input type="text" name="base_price" id="update_base_price" label="{{ __('Base Price:') }}"
            class="required price_validate base_price" maxlength="10" value="" />
        <x-popup-form-input type="textarea" name="description" id="update_description" label="{{ __('Description:') }}" class="required description"
            maxlength="255" value="" />
        <x-popup-form-input type="color" name="color_code" id="update_color_code" label="{{ __('Color Code:') }}" value="#000001"
            class="color_code" />
    </x-popup-update-item-model>

    <x-grid-page addButtonLabel="{{ __('Add Category')}}" addButtonPermission="category-create" searchTextboxPlaceholder="{{ __('Search for categories') }}" />

    <script>
        const lang = @json(getJSLang('category'));
        const BASE_API_URL = "{{ url('/api/backend/categories/') }}";
        const autoCloseAddPopup = true;
    </script>
</x-app-layout>