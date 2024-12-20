@section('title', __('Teams'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Teams') }}
        </h2>
    </x-slot>

    <!-- Add Item Popup -->
    <x-popup-add-item-model title="{{ __('Add new Team') }}" formType="add" formID="popupAddForm" popupClasses="column-1"
        actionButtonLabel="{{ __('Add Team') }}">
        <x-popup-form-input type="text" id="team_name" name="team_name" label="{{ __('Team Name:') }}"
            class="focus_first team_name required" maxlength="50" value="" />
        <x-popup-form-input type="file" id="team_logo" name="team_logo" label="{{ __('Team Profile:') }}"
            class="required team_logo" value="" />
        <x-popup-form-input type="select" id="league_id" name="league_id" label="{{ __('League:') }}" value=""
            class="required" />
        <x-popup-form-input type="text" id="owner_name" name="owner_name" label="{{ __('Owner Name:') }}"
            value="" class="required" />
        <x-popup-form-input type="text" id="owner_email" name="owner_email" label="{{ __('Owner Email:') }}"
            value="" class="required email_validate" />
        <x-popup-form-input type="text" id="owner_phone" name="owner_phone" label="{{ __('Owner Phone:') }}"
            value="" class="required" />
        <x-popup-form-input type="password" id="owner_password" name="owner_password"
            label="{{ __('Owner Password:') }}" value="" class="required" />
    </x-popup-add-item-model>

    <!-- Add Item Popup -->
    <x-popup-delete-item-model title="{{ __('Delete Team') }}">
        {{ __('Do you want to delete Team?') }}
    </x-popup-delete-item-model>

    <!-- Update Item Popup -->
    <x-popup-update-item-model title="{{ __('Update Team') }}" formType="update" formID="popupUpdateForm"
        popupClasses="column-1" actionButtonLabel="{{ __('Update Team') }}">
        <x-popup-form-input type="text" id="update_team_name" name="team_name" label="{{ __('Team Name:') }}"
            class="focus_first team_name required" maxlength="50" value="" />
        <x-popup-form-input type="file" id="update_team_logo" name="team_logo" label="{{ __('Team Profile:') }}"
            class="team_logo" value="" />
        <x-popup-form-input type="select" id="update_league_id" name="league_id" label="{{ __('League:') }}"
            value="" class="required" />
        <x-popup-form-input type="text" id="update_owner_name" name="owner_name" label="{{ __('Owner Name:') }}"
            value="" class="required" />
        <x-popup-form-input type="text" id="update_owner_email" name="owner_email" label="{{ __('Owner Email:') }}"
            value="" class="required email_validate" readOnly="readonly" />
        <x-popup-form-input type="text" id="update_owner_phone" name="owner_phone" label="{{ __('Owner Phone:') }}"
            value="" class="required" />
        <x-popup-form-input type="password" id="update_owner_password" name="owner_password"
            label="{{ __('Owner Password:') }}" value="" class="update_owner_password" />
    </x-popup-update-item-model>


    <x-popup-view-item-model title="{{ __('View Team') }}" popupClasses="column-1">
        <div id="viewItems">
            <div class="alert model-body-alert alert-hidden">Team successfully found.</div>
            <div class="flex justify-center mb-4">
                <img src="{{ url('/img') }}/profile.png" alt="Profile Image"
                    class="w-24 h-24 rounded-full shadow-md">
            </div>
            <table class="custom-table view">
                <tbody></tbody>
            </table>
            <div class="flex justify-around mt-4">
                <button
                    class="popupCloseModel px-4 py-2 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    {{ __('Close') }}
                </button>
            </div>
        </div>
    </x-popup-view-item-model>

    <x-popup-view-item-model popupId="popupBoosterModal" title="{{ __('Booster') }}" popupClasses="column-1">
        <form id="popupBoosterForm" method="post" class="space-y-4" enctype="multipart/form-data">
            <div class="alert model-body-alert alert-hidden"></div>
            <div class="popup-fields">
                <x-popup-form-input type="select" id="plan_type" name="plan_type" label="{{ __('Plan Type:') }}" class="focus_first plan_type required" value="" />
                <x-popup-form-input type="text" id="plan_amount" name="plan_amount" label="{{ __('Virtual Point:') }}" class="plan_amount virtual_point required" value="" maxlength="10" placeholder="{{ __('Virtual Amount') }}" />
            </div>

            <div class="modal-body-footer">
                <button type="button" class="popupCloseModel px-4 py-2 bg-gray-600 text-gray-200 rounded hover:bg-gray-700">{{ __('Close') }}</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed ms-3">
                    {{ __('Save') }}
                </button>
            </div>
        </form>

        <div class="vscorll" id="teamTransaction">
            <table id="transactionTable" class="custom-table">
                <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Created At') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be dynamically appended here -->
                </tbody>
            </table>
        </div>
    </x-popup-view-item-model>

    <x-grid-page addButtonLabel="{{ __('Add Team')}}" addButtonPermission="team-create" searchTextboxPlaceholder="{{ __('Search for teams') }}" />    

    <script>
        const lang = @json(getJSLang('team'));
        const BASE_API_URL = "{{ url('/api/backend/teams/') }}";
        const TRANS_API_URL = "{{ url('/api/backend/transactions/') }}";
        const image_url = "{{ url('/storage') }}";
        const booster_plans = @json($plans);
        const team_players = "{{ route('team.players.index','#teamid#') }}";
        const autoCloseAddPopup = true;
    </script>
</x-app-layout>
