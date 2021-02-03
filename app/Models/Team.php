<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Team extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'teams';

    protected $fillable = ['name'];

    protected $hidden = ['created_at', 'updated_at'];
}
