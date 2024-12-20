<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Player extends Model
{
    use HasFactory;

    protected $table = 'players'; // Specifies the table name
    protected $primaryKey = 'id'; // Specifies the primary key
    public $timestamps = true; // Enables timestamp columns (created_at and updated_at)

    
    // Columns that are mass assignable
    protected $fillable = [
        'uniq_id',
        'player_name',
        'nickname',
        'mobile',
        'email',
        'category_id',
        'dob',
        'image',
        'image_thumb',
        'bat_type',
        'ball_type',
        'type',
        'profile_type',
        'style',
        'last_played_league',
        'address',
        'city',
        'order_id',
        'status',
        'created_by',
        'updated_by',
    ];

    // Columns that should not be mass assignable
    protected $guarded = [
        // You can add columns that should be protected here (if any)
    ];

    // Many-to-One relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Accessor for category_name
    public function getCategoryNameAttribute(): ?string
    {
        return $this->category?->category_name; // Safely fetch category name
    }


    // Accessor for player_nickname
    public function getPlayerNicknameAttribute(): string
    {
        return $this->player_name . ' (' . $this->nickname.")";
    }

    // Accessor for age
    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->dob)->age; // Calculate age using Carbon
    }

    public function soldPlayer()
    {
        return $this->hasOne(SoldPlayer::class, 'player_id', 'id');
    }

    public function soldPlayers()
    {
        return $this->hasMany(SoldPlayer::class);
    }

    public function playerStyle(){
        return $this->belongsTo(PlayerStyle::class, 'style', 'style');
    }

    public function playerType(){
        return $this->belongsTo(PlayerType::class, 'type', 'type');
    }

    public function playerProfile(){
        return $this->belongsTo(PlayerProfileType::class, 'profile_type','profile_type');
    }

    // Accessor for player_style_name
    public function getPlayerStyleNameAttribute(){
        return $this->playerStyle->name;
    }

    // Accessor for player_type_name
    public function getPlayerTypeNameAttribute(){
        return $this->playerType->name;
    } 
    
    // Accessor for player_profile_name
    public function getPlayerProfileNameAttribute(){
        return $this->playerProfile->name;
    }

    public static function getPlayers($categoryId = 0,$playerId = 0, $leagueId = 0){
        
        $playersQuery = Player::with([
            'category:id,category_name,base_price',
            'soldplayer:player_id,team_id',
        ]);

        if ($categoryId > 0) {
            $playersQuery->where('category_id', $categoryId);
        }

        if($playerId > 0){
            $playersQuery->where('id', $playerId);
        }

        return $playersQuery->get()->map(function ($player) use ($leagueId) {
            return self::formatPlayer($player, $leagueId);
        });
    }

    private static function formatPlayer($player, $leagueId){
        $player->team_id = optional($player->soldplayer)->team_id ?? 0;
        $player->sold_status = self::getPlayerSoldStatus($player->team_id, $player->category_id, $leagueId);
        return $player;
    }

    public static function getPlayerSoldStatus($teamId = 0, $categoryId = 0, $leagueId = 0){
        $sold_status = $teamId > 0 ? 'sold' : '';
        if($sold_status == '' && $leagueId > 0){            
            $category = self::getLeagueCategory($leagueId);
            if (!empty($categoryId) && !empty($category)) {
                $count_arr = array_count_values($category);
                $count_curr_category = isset($count_arr[$categoryId]) ? $count_arr[$categoryId] : 0;
                if (in_array($categoryId, $category) && $count_curr_category > 1) {
                    $sold_status = 'unsold';
                }
            }
        }
        return $sold_status;
    }

    private static function getLeagueCategory($leagueId){

        $prevCategory = League::where('id', $leagueId)->value('category');

        $categories = $prevCategory ? json_decode($prevCategory, true) : [];

        if (json_last_error() !== JSON_ERROR_NONE) {
            $categories = [];
        }

        return $categories;
    }
    
}
