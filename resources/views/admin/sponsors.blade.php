@section('title', __('Sponsors'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Sponsors') }}
        </h2>
    </x-slot>

    <!-- Add Item Popup -->
    <x-popup-add-item-model title="{{__('Add new Sponsor')}}" formType="add" formID="popupAddForm" popupClasses="column-1" actionButtonLabel="{{__('Add Sponsor')}}">
        <x-popup-form-input type="text" id="sponsor_name" name="sponsor_name" label="{{ __('Sponsor Name:') }}" class="focus_first sponsor_name required"
            maxlength="50" value="" />
        <x-popup-form-input type="file" id="sponsor_logo" name="sponsor_logo" label="{{ __('Logo:') }}"
            class="required sponsor_logo" value="" />
        <x-popup-form-input type="textarea" id="sponsor_description" name="sponsor_description" label="{{ __('Sponsor Description:') }}" value=""
            class="required" />
        <x-popup-form-input type="text" id="sponsor_website_url" name="sponsor_website_url" label="{{ __('Sponsor website url:') }}" value=""
            class="required" />        
        <x-popup-form-input type="select" id="sponsor_type" name="sponsor_type" label="{{ __('Type:') }}" value=""
            class="required" />
    </x-popup-add-item-model>

    <!-- Add Item Popup -->
    <x-popup-delete-item-model title="{{__('Delete Sponsor')}}">
        {{ __('Do you want to delete Sponsor?') }}
    </x-popup-delete-item-model>

    <!-- Update Item Popup -->
    <x-popup-update-item-model title="{{__('Update Sponsor')}}" formType="update" formID="popupUpdateForm" popupClasses="column-1" actionButtonLabel="{{__('Update Sponsor')}}">
        <x-popup-form-input type="text" id="update_sponsor_name" name="sponsor_name" label="{{ __('Sponsor Name:') }}" class="focus_first sponsor_name required"
            maxlength="50" value="" />
        <x-popup-form-input type="file" id="update_sponsor_logo" name="sponsor_logo" label="{{ __('Logo:') }}"
            class="sponsor_logo" value="" />
        <x-popup-form-input type="textarea" id="update_sponsor_description" name="sponsor_description" label="{{ __('Sponsor Description:') }}" value=""
            class="required" />
        <x-popup-form-input type="text" id="update_sponsor_website_url" name="sponsor_website_url" label="{{ __('Sponsor website url:') }}" value=""
            class="required" />        
        <x-popup-form-input type="select" id="update_sponsor_type" name="sponsor_type" label="{{ __('Type:') }}" value=""
            class="required" />
    </x-popup-update-item-model>

    <x-grid-page addButtonLabel="{{ __('Add Sponsor')}}" addButtonPermission="sponsor-create" searchTextboxPlaceholder="{{ __('Search for sponsors') }}" />    

    <script>
        const lang = @json(getJSLang('sponsor'));
        const BASE_API_URL = "{{ url('/api/backend/sponsors/') }}";
        const image_url = "{{ url('/storage') }}";
        const autoCloseAddPopup = true;
    </script>
</x-app-layout>