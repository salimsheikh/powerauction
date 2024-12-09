@section('title', __('Dashboard'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="admin-page">
                <div class="p-6 text-gray-900 dark:text-white">
                    <style>
                        .dashboard-icon span{
                            line-height: 1.3;
                        }
                    </style>
                    @php
                        // $data['sold_players'] = 0;
                    @endphp

                    {{ $data }}

                    <div class="mt-4">
                        <div class="flex flex-wrap -mx-6">
                            <x-dashboard-card title="{{ __('Current League Name') }}"
                                value="{{ $data['sold_players'] }}" icon="sports_soccer" iconBgColor="indigo-600"
                                wrapperClass="sm:w-1/2 xl:w-1/2" />

                            <x-dashboard-card title="{{ __('Total No. of Teams') }}"
                                value="{{ $data['sold_players'] }}" icon="group_add" iconBgColor="orange-600"
                                wrapperClass="sm:w-1/2 xl:w-1/2" />
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="flex flex-wrap -mx-6">
                            <x-dashboard-card title="{{ __('Total No. of Players') }}"
                                value="{{ $data['total_players'] }}" icon="person" iconBgColor="pink-600"
                                wrapperClass="sm:w-1/2 xl:w-1/3" />

                            <x-dashboard-card title="{{ __('Total No. of Sold Players') }}"
                                value="{{ $data['sold_players'] }}" icon="shopping_cart" iconBgColor="yellow-500"
                                wrapperClass="sm:w-1/2 xl:w-1/3" />

                            <x-dashboard-card title="{{ __('Total No. of Unsold Players') }}"
                                value="{{ $data['sold_players'] }}" icon="highlight_off" iconBgColor="blue-600"
                                wrapperClass="sm:w-1/2 xl:w-1/3" />
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
