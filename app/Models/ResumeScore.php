<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumeScore extends Model
{
    use HasFactory;

    protected $fillable = ['job_id', 'resume_id', 'score', 'feedback'];

    public function job()
    {
        return $this->belongsTo(JobDetails::class, 'job_id','id');
    }

    public function resume()
    {
        return $this->belongsTo(Resume::class, 'resume_id','id');
    }
}
