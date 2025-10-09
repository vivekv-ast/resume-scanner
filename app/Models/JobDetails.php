<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobDetails extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description'];


    public function resumeScores()
    {
        return $this->hasMany(ResumeScore::class, 'job_id');
    }
}
