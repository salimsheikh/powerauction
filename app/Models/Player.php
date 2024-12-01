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

    

    // Define custom arrays for mapping
    private static $types = [
        "batsman" => 'Batsman',
        "bowler" => 'Bowler',
        "all-rounder" => 'All Rounder'
    ];

    private static $profileTypes = [
        "men" => 'Men',
        "women" => 'Women',
        "senior-citizen" => 'Senior Citizen'
    ];

    private static $styles = [
        "Left Hand Batsman" => 'Left Hand Batsman',
        "Right Hand Batsman" => 'Right Hand Batsman',
        "Left Hand Bowler" => 'Left Hand Bowler',
        "Right Hand Bowler" => 'Right Hand Bowler',
        "heft_hand_batsman" => 'Left Hand Batsman',
        "right_hand_batsman" => 'Right Hand Batsman',
        "left_hand_bowler" => 'Left Hand Bowler',
        "right_hand_bowler" =>'Right Hand Bowler'
    ];

    // Accessor for type
    public function getTypeLabelAttribute(): ?string
    {
        return self::$types[$this->type] ?? $this->type;
    }

    // Accessor for profile_type
    public function getProfileTypeLabelAttribute(): ?string
    {
        return self::$profileTypes[$this->profile_type] ?? $this->profile_type;
    }

    // Accessor for profile_type
    public function getStyleLabelAttribute(): ?string
    {
        return self::$styles[$this->style] ?? $this->style;
    }
    
}
