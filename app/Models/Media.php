<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = ['path']; //les colonnes qui peuvent être remplies via un formulaire n7ottouhom f fillable

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_media');
    }
}
