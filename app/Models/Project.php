<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UploadTrait;

class Project extends Model
{
    use HasFactory , UploadTrait;
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function rates()
    {
        return $this->hasMany(ProjectRate::class);
    }

    public function projectUsers()
    {
        return $this->hasMany(ProjectUser::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_users');
    }

    public function userTasks()
    {
        return $this->hasMany(UserTask::class);
    }

    public function files()
    {
        return $this->hasMany(ProjectFile::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function setImageAttribute($value)
    {
        if($value){
          return $this->attributes['image'] = $this->StoreFile('images' , $value);
        }
    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            asset('storage/'.$value);
        }
        return false;
    }


}
