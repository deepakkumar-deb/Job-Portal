<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobApplication extends Model
{
    use HasFactory;
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    protected $fillable = [
        'job_id',
        'user_id',
        'employer_id',
        'applied_date'
    ];

    protected $casts = [
        'applied_date' => 'datetime', // ← add this
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
