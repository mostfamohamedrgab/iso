<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function files()
    {
        return $this->hasMany(TaskFile::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class,'task_id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
   

}
