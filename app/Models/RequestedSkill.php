<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestedSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'proof',
        'status',
        'user_id', // Assuming each requested skill belongs to a user
        'category_id', // Add this field if it exists in the database
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with the Category model (if applicable)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
