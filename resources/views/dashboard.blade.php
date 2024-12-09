@section('title', __('Dashboard'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="admin-page">
                <div class="p-6 text-gray-900 dark:text-white">
                    <style>
                        .dashboard-icon span {
                            line-height: 1.3;
                        }
                    </style>                    

                    <div class="mt-4">
                        <div class="flex flex-wrap -mx-6">
                            <x-dashboard-card title="{{ __('Current League Name') }}"
                                value="{{ html_entity_decode($data['current_league_name']) }}" icon="sports_soccer"
                                iconBgColor="bg-indigo-600" wrapperClass="sm:w-1/2 xl:w-1/2" />

                            <x-dashboard-card title="{{ __('Total No. of Teams') }}" value="{{ $data['total_teams'] }}"
                                icon="group_add" iconBgColor="bg-orange-600" wrapperClass="sm:w-1/2 xl:w-1/2" />
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="flex flex-wrap -mx-6">
                            <x-dashboard-card title="{{ __('Total No. of Players') }}"
                                value="{{ $data['total_players'] }}" icon="person" iconBgColor="bg-pink-600"
                                wrapperClass="sm:w-1/2 xl:w-1/3" />

                            <x-dashboard-card title="{{ __('Total No. of old Players') }}"
                                value="{{ $data['sold_players'] }}" icon="shopping_cart" iconBgColor="bg-yellow-500"
                                wrapperClass="sm:w-1/2 xl:w-1/3" />

                            <x-dashboard-card title="{{ __('Total No. of Unsold Players') }}"
                                value="{{ $data['unsold_players'] }}" icon="highlight_off" iconBgColor="bg-blue-600"
                                wrapperClass="sm:w-1/2 xl:w-1/3" />
                            </div>
                        </div>
    
                        <div class="mt-4">
                            <div class="flex justify-center items-center -mx-6">
                            <x-dashboard-card title="{{ __('Total No. of Sponsors') }}"
                                value="{{ $data['total_sponsors'] }}" icon="handshake" iconBgColor="bg-teal-500"
                                wrapperClass="sm:w-1/2 xl:w-1/3" />
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>


</x-app-layout>
