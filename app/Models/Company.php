<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Company
 *
 * Represents a company entity in the job portal.
 * Demonstrates OOP principles:
 *   - Encapsulation: data and related behaviour in one class
 *   - Inheritance: extends Eloquent Model (which extends base PHP class)
 *   - Association: relationships with User and Job models
 *
 * @package App\Models
 */
class Company extends Model
{
    use HasFactory;

    /**
     * Mass-assignable fields for this model.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'logo',
        'description',
        'website',
        'location',
        'industry',
        'company_size',
    ];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * A Company belongs to one User (the company account owner).
     * OOP Composition — Company has a User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A Company has many Jobs (one-to-many).
     * Deleting a company cascades to its jobs (set in migration).
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    // =========================================================================
    // ACCESSORS / HELPER METHODS
    // =========================================================================

    /**
     * Get the full URL of the company logo.
     * Encapsulates logo path resolution logic inside the model.
     *
     * @return string
     */
    public function getLogoUrlAttribute(): string
    {
        return $this->logo
            ? asset('storage/' . $this->logo)
            : asset('images/default-company.png');
    }

    /**
     * Count active job listings for this company.
     *
     * @return int
     */
    public function activeJobsCount(): int
    {
        return $this->jobs()->where('status', 'active')->count();
    }
}
