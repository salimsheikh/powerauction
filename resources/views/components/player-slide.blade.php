
<div class="auction_container">    
    <div class="image-container-wrap">
        <div class="image-container">
            <img src="{{ url('/storage') ."/players/". $player->image }}" alt="{{ $player->player_name  }}" class="max-h-64" />
            @if ($player->sold_status)
                <div class="badge {{ $player->sold_status }}">{{ $player->sold_status }}</div>    
            @endif
        </div>
    </div>
    <div class="flex items-center">
        <div class="rounded-lg p-5 bg-gradient-to-r from-cyan-500 to-blue-500 dark:from-slate-900 dark:to-slate-700">
            <div class="player-name text-white">{{ $player->player_name }}</div>
            {{-- {{ $player }} --}}
            <div class="player-details flex items-center justify-between gap-5">                
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

{{-- I have Team model and Player Model
One team cotent the multiple Players
Team and Player are relation made in SoldPlayer model
now we need loop teams, each team players
Create query for laravel --}}