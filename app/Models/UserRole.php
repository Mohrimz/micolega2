<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    // Mass assignable attributes
    protected $fillable = ['user_id', 'role_id'];

    // Relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to the Role model
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    // Helper function to check if the user has a specific role
    public function hasRole($roleName)
    {
        // Use 'RoleName' instead of 'name'
        return $this->roles->contains('RoleName', $roleName);
    }
}
