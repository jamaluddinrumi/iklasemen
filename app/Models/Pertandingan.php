<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Pertandingan extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'pertandingans';

    protected $fillable = ['home_team_id', 'away_team_id', 'home_team_score', 'away_team_score'];

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    protected $hidden = ['updated_at'];
}
