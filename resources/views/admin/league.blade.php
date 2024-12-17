@section('title', __('Leagues'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Leagues') }}
        </h2>
    </x-slot>

    <!-- Add Item Popup -->
    <x-popup-add-item-model title="{{__('Add new League')}}" formType="add" formID="popupAddForm" popupClasses="column-1" actionButtonLabel="{{__('Add League')}}">
        <x-popup-form-input type="text" id="league_name" name="league_name" label="{{ __('League Name:') }}" class="focus_first league_name required"
            maxlength="50" value="" />
        <x-popup-form-input type="textarea" id="description" name="description" label="{{ __('Description:') }}" value=""
            class="required" />
    </x-popup-add-item-model>

    <!-- Add Item Popup -->
    <x-popup-delete-item-model title="{{__('Delete League')}}">
        {{ __('Do you want to delete League?') }}
    </x-popup-delete-item-model>

    <!-- Update Item Popup -->
    <x-popup-update-item-model title="{{__('Update League')}}" formType="update" formID="popupUpdateForm" popupClasses="column-1" actionButtonLabel="{{__('Update League')}}">
        <x-popup-form-input type="text" id="update_league_name" name="league_name" label="{{ __('League Name:') }}" class="focus_first league_name required"
            maxlength="50" value="" />
        <x-popup-form-input type="textarea" id="update_description" name="description" label="{{ __('Description:') }}" value=""
            class="required" />
    </x-popup-update-item-model>

    <x-grid-page addButtonLabel="{{ __('Add League')}}" addButtonPermission="league-create" searchTextboxPlaceholder="{{ __('Search for leagues') }}" />
    
    <script>
        const lang = @json(getJSLang('leagues'));
        const BASE_API_URL = "{{ url('/api/backend/leagues/') }}";
        const image_url = "{{ url('/storage') }}";
        const set_league_id_url = "{{ route('set.league.id','#leagueid#')}}"
        const autoCloseAddPopup = true;
    </script>
</x-app-layout>