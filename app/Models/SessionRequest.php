<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionRequest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'tutor_id', 'skill_id', 'status'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function tutor() {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function skill() {
        return $this->belongsTo(Skill::class);
    }
}
