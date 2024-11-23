<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // Define the table name (optional, as Laravel uses plural form by default)
    protected $table = 'roles';
    // Specify the primary key for the 'roles' table
    protected $primaryKey = 'RoleID';

    // Allow mass assignment on the 'RoleName' column (not 'name' anymore)
    protected $fillable = ['RoleName'];
    // Relationship to the UserRole model
    public function userRoles()
    {
        return $this->hasMany(UserRole::class);
    }

    // Relationship to the User model through UserRole
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }
}

