<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['category_name'];

    public function skills()
    {
        return $this->hasMany(Skill::class); 
    }
    public function requestedSkills()
{
    return $this->hasMany(RequestedSkill::class);
}

}

