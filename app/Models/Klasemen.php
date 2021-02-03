<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Klasemen extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'klasemens';

    protected $fillable = ['team_id', 'point'];

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    protected $hidden = ['created_at', 'updated_at'];
}
