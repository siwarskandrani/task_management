<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description','team_id', 'status', 'start_date', 'end_date', 'project_id', 'owner', 'type', 'parent_task'];//les colonnes qui peuvent être remplies via un formulaire
    
    const NOT_STARTED = 1;
    const IN_PROGRESS = 2;
    const COMPLETED = 3;
    const STATUS = [self::NOT_STARTED, self::IN_PROGRESS, self::COMPLETED];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class /*',owner'*/); 
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

    // une ftc pour que f tasks.index le column status m'affiche la valeur par chaine pas comme il est dans la BD par entier (1,2,3)
    public function getStatusLabelAttribute()
    {
        $statusLabels = [
            self::NOT_STARTED => 'Not Started',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed',
        ];
    
        return $statusLabels[$this->status] ?? 'Unknown';
    }
}
