<div class="auction_container">
    <div class="">
        <div class="image-container">
            <img src="http://localhost/next-cricket/uploads/players_image/players_1.jpg" alt="Player Image" />
            <div class="badge star">{{ __('Sold') }}</div>
        </div>
    </div>
    
    <div class="flex items-center">
        <div class="rounded-lg p-5 bg-gradient-to-r from-cyan-500 to-blue-500 dark:from-slate-900 dark:to-slate-700">
            <div class="player-name text-white">{{ $player->player_name }}</div>
            <div class=" flex items-center justify-between gap-5">                
                <div class="column">                    
                    <div class="info-row shadow-xl">{{ __('messages.player_unique_id',['name'=> $player->uniq_id]) }}</div>
                    <div class="info-row shadow-xl">{{ __('messages.player_category',['name'=> $player->category_name]) }}</div>
                    <div class="info-row shadow-xl">{{ __('messages.player_type',['name'=> $player->type_label]) }}</div>
                </div>
                <div class="column">
                    <div class="info-row shadow-xl">{{ __('messages.player_style',['name'=> $player->style_label]) }}</div>
                    <div class="info-row shadow-xl">{{ __('messages.player_age',['name'=> $player->age]) }}</div>
                    <div class="info-row shadow-xl">{{ __('messages.player_base_price',['name'=> $player?->category?->base_price]) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>