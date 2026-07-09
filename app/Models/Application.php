<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Application
 *
 * Represents a jobseeker's application for a specific job.
 * Acts as a join/pivot entity between User and Job with extra attributes.
 *
 * @package App\Models
 */
class Application extends Model
{
    use HasFactory;

    /**
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'job_id',
        'resume_path',
        'cover_letter',
        'status',
    ];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * An Application belongs to a User (the applicant).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * An Application belongs to a Job.
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    // =========================================================================
    // HELPER METHODS
    // =========================================================================

    /**
     * Get a human-readable status badge class for Bootstrap.
     *
     * @return string
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending'     => 'warning',
            'reviewed'    => 'info',
            'shortlisted' => 'success',
            'rejected'    => 'danger',
            default       => 'secondary',
        };
    }
}
