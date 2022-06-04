<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function league()
    {
        return $this->belongsTo(League::class,'league_id','id');
    }
    public function players()
    {
        return $this->hasMany(Player::class);
    }
    public function coaches()
    {
        return $this->hasMany(coache::class);
    }
}
