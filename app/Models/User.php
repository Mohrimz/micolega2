<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_token',
        'level', 
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationships
     */

    // Relationship to the UserRole model
    public function userRoles()
    {
        return $this->hasMany(UserRole::class);
    }

    // Relationship to the Role model through UserRole
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    // Helper function to check if the user has a specific role
    public function hasRole($roleName)
    {
        // Load roles if not already loaded
        if (!$this->relationLoaded('roles')) {
            $this->load('roles');
        }

        // Check if the user has the role
        return $this->roles->contains('RoleName', $roleName);
    }

    // Relationship to the Availability model
    public function availabilities(): BelongsToMany
    {
        return $this->belongsToMany(Availability::class, 'availability_user', 'user_id', 'availability_id');
    }

    // Relationship to the Skill model
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'skill_user', 'user_id', 'skill_id');
    }

    // Relationship to ProofDocument model
    public function proofDocuments()
    {
        return $this->hasMany(ProofDocument::class);
    }
    
}
