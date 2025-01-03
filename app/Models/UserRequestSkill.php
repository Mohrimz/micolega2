<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRequestSkill extends Model
{
    protected $fillable = ['user_id', 'skill_id', 'preference_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function preference()
    {
        return $this->belongsTo(Preference::class);
    }
}
