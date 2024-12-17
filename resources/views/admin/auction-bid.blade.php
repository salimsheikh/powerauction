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
                        <form method="POST" action="{{ route('auction.index') }}" id="form_auction_category">
                            @csrf
                            <x-popup-form-input type="select" id="auction_category_id" name="category_id" label="{{ __('Category Name:') }}" value="{{ $categoryId }}" />
                        </form>                        
                    </div>
                    
                    <div class="slider">
                        <div class="slider-track">
                            @foreach ($players as $player)
                                <div class="slider-item" data-id="{{ $player->id  }}">                                    
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
                        <div class="timer" id="auctionBidTimer">{{ $remainingMinutes }}</div>
                        <form id="startBiddingForm" method="POST">
                            <input type="hidden" name="league_id" id="league_id" value="{{ Session::get('league_id') }}">
							<input type="hidden" name="player_id" id="player_id" value="{{ $player_id }}">

                            <input type="hidden" name="session_id" id="session_id" value="{{ $session_id }}">
                            <input type="hidden" name="team_id" id="team_id" value="{{ $team_id }}">
                            
                            <div class="bidding-input-group">
                                <input type="text" class="form-control plan_amount" name="amount" id="amount" value="">

                                <button type="submit" class="ripple-btn shadow-md focus:outline-none focus:normal-case ">
                                    {{ __('Start Bidding') }}
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>            
        </div>
    </div>
    <script>
        const lang = @json(getJSLang('bidding'));
        const BASE_API_URL = "{{ url('/api/backend/bidding/') }}";
        const autoCloseAddPopup = true;
        const bidID = {{ $session_id }};
        const bidWinURL = "{{ route('admin.bidding.bid-win',$session_id) }}";
        const sessionStartTime = "{{ $sessionStartTime }}";
        const sessionEndTime = "{{ $sessionEndTime }}";
        const serverTime = "{{ $serverTime }}";
    </script>
    <script src="{{ asset('js/auction.js') }}"></script>
</x-app-layout>
