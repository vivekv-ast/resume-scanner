<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    use HasFactory;

    protected $fillable = ['file_name', 'file_path'];

    public function scores()
    {
        return $this->hasMany(ResumeScore::class, 'resume_id');
    }

    public function job()
    {
        return $this->hasOneThrough(JobDetails::class,ResumeScore::class,'resume_id','id','id','job_id');
    }
}
