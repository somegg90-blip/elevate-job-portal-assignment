<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * Represents an application user. Extends Laravel's Authenticatable base class
 * which itself extends Model — demonstrating OOP Inheritance.
 *
 * Roles:
 *  - 'jobseeker' : can browse and apply for jobs
 *  - 'company'   : can post, edit, delete jobs and view applicants
 *  - 'admin'     : full system access
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * Protects against mass-assignment vulnerabilities (security best practice).
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * Ensures sensitive data (passwords, tokens) are never accidentally exposed.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     * Automatically converts 'email_verified_at' to a Carbon date object.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // =========================================================================
    // RELATIONSHIPS (OOP Association)
    // =========================================================================

    /**
     * A user (company role) has one Company profile.
     * Demonstrates a one-to-one OOP relationship.
     */
    public function company()
    {
        return $this->hasOne(Company::class);
    }

    /**
     * A user (jobseeker role) has many Applications.
     * Demonstrates a one-to-many OOP relationship.
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    // =========================================================================
    // HELPER METHODS (OOP Encapsulation)
    // =========================================================================

    /**
     * Check if the user has a company role.
     *
     * @return bool
     */
    public function isCompany(): bool
    {
        return $this->role === 'company';
    }

    /**
     * Check if the user has a jobseeker role.
     *
     * @return bool
     */
    public function isJobseeker(): bool
    {
        return $this->role === 'jobseeker';
    }

    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
