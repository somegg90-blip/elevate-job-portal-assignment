<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Job
 *
 * Represents a job listing posted by a company.
 *
 * OOP Principles demonstrated:
 *   - Encapsulation: all job-related data and logic encapsulated here
 *   - Inheritance: extends Eloquent Model
 *   - Polymorphism: via Eloquent scopes (reusable query logic)
 *
 * @package App\Models
 */
class Job extends Model
{
    use HasFactory;

    /**
     * Mass-assignable attributes.
     *
     * @var array<string>
     */
    protected $fillable = [
        'company_id',
        'title',
        'description',
        'requirements',
        'location',
        'type',
        'salary_range',
        'category',
        'status',
        'deadline',
    ];

    /**
     * Attribute casting map.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'deadline' => 'date',
    ];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * A Job belongs to a Company.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * A Job has many Applications (from jobseekers).
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    // =========================================================================
    // QUERY SCOPES (OOP Polymorphism via reusable query objects)
    // =========================================================================

    /**
     * Scope: only return active jobs.
     * Usage: Job::active()->get()
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: filter by job type.
     * Usage: Job::ofType('full-time')->get()
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: filter by category.
     */
    public function scopeInCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope: search by keyword (title or description).
     */
    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%");
        });
    }

    // =========================================================================
    // HELPER METHODS
    // =========================================================================

    /**
     * Check if this job is still accepting applications.
     *
     * @return bool
     */
    public function isOpen(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }
        if ($this->deadline && $this->deadline->isPast()) {
            return false;
        }
        return true;
    }

    /**
     * Get the number of applications received for this job.
     *
     * @return int
     */
    public function applicationCount(): int
    {
        return $this->applications()->count();
    }
}
