<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'skill_name',
        'skill_id',
        'level',
        'date',
        'time',
        'created_by',
    ];
    public function skill()
    {
        return $this->belongsTo(Skill::class, 'skill_id');
    }
public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}
}
