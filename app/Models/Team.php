<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    protected $fillable = [
        // Include only the columns that exist in the database table
        'name', 
        'description', 
    ];
    public function users() {
        return $this->belongsToMany(User::class, 'user__teams', 'ID_team', 'ID_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }
}
