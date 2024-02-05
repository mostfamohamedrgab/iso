<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UploadTrait;

class TaskFile extends Model
{
    use HasFactory, UploadTrait;
    protected $guarded = [];


    public function setFileAttribute($value)
    {
        if($value){
          return $this->attributes['file'] = $this->StoreFile('files' , $value);
        }
    }

    public function getFileAttribute($value)
    {
        return asset('storage/'.$value);
    }
}
