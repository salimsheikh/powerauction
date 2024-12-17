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

    <x-grid-page addButtonLabel="{{ __('Add Player')}}" addButtonPermission="team-add-player" searchTextboxPlaceholder="{{ __('Search for players') }}" />    

    <script>
        const lang = @json(getJSLang('team-player'));
        const BASE_API_URL = "{{ url('/api/backend/team/players/') }}";
        const image_url = "{{ url('/storage') }}";
        const master_id = "{{ $team_id }}";
        const player_ids = @json($player_ids);
        const autoCloseAddPopup = false;
    </script>
</x-app-layout>