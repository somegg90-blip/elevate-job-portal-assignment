<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Class ApplicationController
 *
 * Handles job applications by jobseekers, and status updates by companies.
 *
 * MVC Role: CONTROLLER
 *
 * @package App\Http\Controllers
 */
class ApplicationController extends Controller
{
    /**
     * Submit an application for a specific job.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job           $job
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Job $job)
    {
        // Prevent company users from applying to jobs
        if (Auth::user()->isCompany()) {
            return back()->with('error', 'Companies cannot apply for jobs.');
        }

        // Prevent duplicate applications
        $alreadyApplied = Application::where('user_id', Auth::id())
                                      ->where('job_id', $job->id)
                                      ->exists();

        if ($alreadyApplied) {
            return back()->with('error', 'You have already applied for this job.');
        }

        $validated = $request->validate([
            'cover_letter' => ['nullable', 'string', 'max:2000'],
            'resume'       => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:2048'],
        ]);

        // Handle resume file upload
        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'private');
        }

        Application::create([
            'user_id'      => Auth::id(),
            'job_id'       => $job->id,
            'cover_letter' => $validated['cover_letter'] ?? null,
            'resume_path'  => $resumePath,
            'status'       => 'pending',
        ]);

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Your application has been submitted successfully!');
    }

    /**
     * Update the status of an application (by the company).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Application   $application
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Application $application)
    {
        // Ensure the logged-in company owns the job this application is for
        $company = Auth::user()->company;

        if (!$company || $application->job->company_id !== $company->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => ['required', 'in:pending,reviewed,shortlisted,rejected'],
        ]);

        $application->update(['status' => $request->input('status')]);

        return back()->with('success', 'Application status updated.');
    }
}
