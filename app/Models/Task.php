<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description','team_id', 'status', 'start_date', 'end_date', 'project_id', 'owner', 'type', 'parent_task'];//les colonnes qui peuvent être remplies via un formulaire
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner'); 
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class,'task_tag'); //relation pmusieyr a plusieur
    }

    public function subtasks()
    {
        return $this->hasMany(Task::class, 'parent_task'); //has many==> relation un a plusueyr
    }
    
    public function media()
    {
        return $this->belongsToMany(Media::class, 'task_media');//Cette méthode définit une relation Many-to-Many avec le modèle Task via la table pivot task_media
    }
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

}
