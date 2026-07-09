<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class JobController
 *
 * Handles all job-related operations: listing, creating, editing, deleting.
 *
 * MVC Role: CONTROLLER
 *   - Receives HTTP requests
 *   - Delegates data operations to the Job MODEL
 *   - Passes data to Blade VIEWS for rendering
 *
 * OOP Principles:
 *   - Encapsulation: all job CRUD logic is contained here
 *   - Inheritance: extends the base Controller class
 *
 * @package App\Http\Controllers
 */
class JobController extends Controller
{
    /**
     * Display a paginated listing of all active jobs.
     * Supports keyword search and filtering.
     *
     * MVC: Controller fetches from Model → passes to View.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Job::with('company')->active()->latest();

        // Apply keyword search (uses Job::scopeSearch)
        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        // Apply type filter
        if ($request->filled('type')) {
            $query->ofType($request->input('type'));
        }

        // Apply category filter
        if ($request->filled('category')) {
            $query->inCategory($request->input('category'));
        }

        // Apply location filter
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->input('location') . '%');
        }

        // Paginate results — 10 per page (assignment requirement)
        $jobs = $query->paginate(10)->withQueryString();

        return view('jobs.index', compact('jobs'));
    }

    /**
     * Display a single job's detail page.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\View\View
     */
    public function show(Job $job)
    {
        // Check if the logged-in user has already applied
        $hasApplied = false;
        if (Auth::check() && Auth::user()->isJobseeker()) {
            $hasApplied = Application::where('user_id', Auth::id())
                                      ->where('job_id', $job->id)
                                      ->exists();
        }

        return view('jobs.show', compact('job', 'hasApplied'));
    }

    /**
     * Show the form for creating a new job listing.
     * Only accessible to authenticated company users.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('jobs.create');
    }

    /**
     * Store a newly created job in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['required', 'string'],
            'requirements' => ['required', 'string'],
            'location'     => ['required', 'string', 'max:255'],
            'type'         => ['required', 'in:full-time,part-time,contract,internship,remote'],
            'salary_range' => ['nullable', 'string', 'max:100'],
            'category'     => ['required', 'string', 'max:100'],
            'deadline'     => ['nullable', 'date', 'after:today'],
        ]);

        // Associate job with the logged-in company
        $company = Auth::user()->company;

        $job = $company->jobs()->create($validated);

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Job listing created successfully!');
    }

    /**
     * Show the form for editing an existing job.
     * Authorization: only the owning company can edit.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\View\View
     */
    public function edit(Job $job)
    {
        // Authorization — ensure the logged-in company owns this job
        $this->authorizeJobOwnership($job);

        return view('jobs.edit', compact('job'));
    }

    /**
     * Update the specified job in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job           $job
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Job $job)
    {
        $this->authorizeJobOwnership($job);

        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['required', 'string'],
            'requirements' => ['required', 'string'],
            'location'     => ['required', 'string', 'max:255'],
            'type'         => ['required', 'in:full-time,part-time,contract,internship,remote'],
            'salary_range' => ['nullable', 'string', 'max:100'],
            'category'     => ['required', 'string', 'max:100'],
            'status'       => ['required', 'in:active,closed'],
            'deadline'     => ['nullable', 'date'],
        ]);

        $job->update($validated);

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Job listing updated successfully!');
    }

    /**
     * Remove the specified job from the database.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Job $job)
    {
        $this->authorizeJobOwnership($job);

        $job->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Job listing deleted successfully.');
    }

    // =========================================================================
    // PRIVATE HELPERS (OOP Encapsulation)
    // =========================================================================

    /**
     * Verify that the authenticated company user owns the given job.
     * Aborts with 403 Forbidden if unauthorized.
     *
     * @param  \App\Models\Job  $job
     * @return void
     */
    private function authorizeJobOwnership(Job $job): void
    {
        $company = Auth::user()->company;

        if (!$company || $job->company_id !== $company->id) {
            abort(403, 'Unauthorized action. You do not own this job listing.');
        }
    }
}
