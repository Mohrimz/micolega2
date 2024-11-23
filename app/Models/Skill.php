<?php

namespace App\Models;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'category_id',
    ];

    /**
     * Relationship with the Category model.
     * A skill belongs to a category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship with the Course model.
     * A skill can have many courses associated with it.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Relationship with the User model through a pivot table (skill_user).
     * A skill can be associated with many users, and a user can have many skills.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'skill_user', 'skill_id', 'user_id');
    }

    /**
     * Count the number of users associated with this skill for each level.
     *
     * @return array
     */
    public function getLevelDemandAttribute(): array
    {
        return [
            'L4' => $this->users->where('level', 'L4')->count(),
            'L5' => $this->users->where('level', 'L5')->count(),
            'L6' => $this->users->where('level', 'L6')->count(),
        ];
    }
}
