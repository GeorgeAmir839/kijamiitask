<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referee extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function contest()
    {
        return $this->belongsTo(contest::class);
    }
}
