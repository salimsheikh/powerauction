@section('title', __('Players'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Players') }}
        </h2>
    </x-slot>

    <!-- Add Item Popup -->
    <x-popup-add-item-model title="{{__('Add new Player')}}" formType="add" formID="popupAddForm" popupClasses="column-2" actionButtonLabel="{{__('Add Player')}}">
        <x-popup-form-input type="text" id="player_name" name="player_name" label="{{ __('Players Name:') }}" class="focus_first player_name required"
            maxlength="50" value="" />
        <x-popup-form-input type="file" id="image" name="image" label="{{ __('Player Profile:') }}"
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
    <x-popup-delete-item-model title="{{__('Delete Player')}}">
        {{ __('Do you want to delete Player?') }}
    </x-popup-delete-item-model>

    <!-- Update Item Popup -->
    <x-popup-update-item-model title="{{__('Update Player')}}" formType="update" formID="popupUpdateForm" popupClasses="column-2" actionButtonLabel="{{__('Update Player')}}">
        <x-popup-form-input type="text" id="update_player_name" name="player_name" label="{{ __('Players Name:') }}" class="focus_first player_name required"
            maxlength="50" value="" />
        <x-popup-form-input type="file" id="update_image" name="image" label="{{ __('Player Profile:') }}"
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


    <x-popup-view-item-model popupId="popupViewItemModal" title="{{__('View Player')}}" popupClasses="column-1">
        <div id="viewItems">
            <div class="alert model-body-alert alert-hidden">Player successfully found.</div>
            <div class="flex justify-center mb-4">
                <img src="{{ url('/img') }}/profile.png" alt="Profile Image" class="w-24 h-24 rounded-full shadow-md">
            </div>            
            <table class="custom-table view"><tbody></tbody></table>
            <div class="flex justify-around mt-4">
                <button class="popupCloseModel ripple-btn px-4 py-2 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                {{ __('Close') }}
                </button>
            </div>
        </div>
    </x-popup-view-item-model>

    <x-grid-page addButtonLabel="{{ __('Add Player')}}" addButtonPermission="player-create" searchTextboxPlaceholder="{{ __('Search for players') }}" />

    <script>
        const lang = @json(getJSLang('player'));
        const BASE_API_URL = "{{ url('/api/backend/players/') }}";
        const image_url = "{{ url('/storage') }}";
        const autoCloseAddPopup = true;
    </script>
</x-app-layout>