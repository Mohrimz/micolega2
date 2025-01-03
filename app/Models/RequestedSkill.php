<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestedSkill extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_request_skills')
                    ->withTimestamps()
                    ->withPivot('id');
    }
    public function preferences()
{
    return $this->belongsToMany(Preference::class, 'user_request_skills')
                ->withPivot('id');
}

}
