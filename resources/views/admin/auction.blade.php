@section('title', __('Auction'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Auction') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="admin-page">
                <div class="p-6 text-gray-900">

                    <div class="auction_header flex justify-between items-center mb-3 dark:text-white text-lg ">
                        <h2>{{ __('messages.auction_league', ['league_name' => $leagueName]) }}</h2>
                        <form method="POST" action="{{ route('auction.index') }}" id="form_auction_category">
                            @csrf
                            <x-popup-form-input type="select" id="auction_category_id" name="category_id"
                                label="{{ __('Category Name:') }}" value="{{ $categoryId }}" />
                        </form>
                    </div>

                    <div class="slider_wrapper">

                        <div class="slider">
                            <div class="slider-track">
                                @foreach ($players as $player)
                                    <div class="slider-item" data-id="{{ $player->id }}">
                                        <x-player-slide :player="$player" />
                                    </div>
                                @endforeach
                            </div>
                            <div class="slider-nav">
                                <div><button class="slider-arrow" id="prev">❮</button></div>
                                <div><button class="slider-arrow" id="next">❯</button></div>
                            </div>
                        </div>

                        <div class="auction-form text-center mt-10">
                            <form id="start_bidding" method="POST">
                                <input type="hidden" name="league_id" id="league_id"
                                    value="{{ Session::get('league_id') }}">
                                <input type="hidden" name="player_id" id="player_id" value="">

                                <button type="submit"
                                    class="ripple--btn relative overflow-hidden px-6 py-3 bg-[#3b82f6] hover:bg-[#06b6d4] text-white rounded-lg shadow-md focus:outline-none focus:normal-case dark:bg-gray-900 dark:text-white">
                                    {{ __('Start Bidding') }}
                                </button>
                            </form>
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
