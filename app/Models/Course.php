<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'skill_id',
        'tutor_id',
        'start_time',
        'duration',
        'recurrence',
        'google_meet_link',
        'calendar_event_id',
    ];

    // Relationship with Skill model (a course belongs to one skill)
    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    // Relationship with User model (a course has one tutor)
    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }
}
