<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 
        'description', 
        'owner'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner'); //un projet appartient a un user
    }
    
    public function tasks()
    {
        return $this->hasMany(Task::class);//un projet has many tasks
    }


}
