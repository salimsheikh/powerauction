@section('title', __('Auction'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Auction') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="admin-page no-slider">
                <div class="p-6 text-gray-900">

                    <div class="auction_header flex justify-between items-center mb-3 dark:text-white text-lg ">
                        <h2>{{ __('messages.auction_league', ['league_name' => $leagueName]) }}</h2>
                       
                    </div>
                    
                    <div class="slider">
                        <div class="slider-track">
                            @foreach ($players as $player)
                                <div class="slider-item" data-id="{{ $player->id  }}">                                    
                                    <x-player-slide :player="$player" />
                                </div>    
                            @endforeach                            
                        </div>
                    </div> 
                </div>
            </div>            
        </div>
    </div>
    <script>
        const lang = @json(getJSLang('bidding'));
        const BASE_API_URL = "{{ url('/api/backend/bidding/') }}";
        const autoCloseAddPopup = true;
    </script>
    <script src="{{ asset('js/auction.js') }}"></script>
</x-app-layout>
