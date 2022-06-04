<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function team()
    {
        return $this->belongsTo(Team::class,'team_id','id');
    }
    // public function league()
    // {
    //     return $this->belongsTo(Team::class,'team_id','id');
    // }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
