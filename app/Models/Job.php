<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'category_id',
        'job_type_id',
        'user_id',
        'vacancy',
        'salary',
        'location',
        'description',
        'qualifications',
        'experience',
        'company_name',
        'company_location',
        'company_website',
        'status',
        'is_featured',
    ];

    public function jobType()
    {
        return $this->belongsTo(JobType::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // App/Models/Job.php
    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
