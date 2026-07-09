<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class DashboardController
 *
 * Routes authenticated users to their role-specific dashboard.
 *
 * OOP Polymorphism in action: the same `index()` method returns different
 * Views based on the user's role, without if/else in routes.
 *
 * @package App\Http\Controllers
 */
class DashboardController extends Controller
{
    /**
     * Show the appropriate dashboard for the logged-in user.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isCompany()) {
            return $this->companyDashboard($user);
        }

        return $this->jobseekerDashboard($user);
    }

    /**
     * Build and return the Company dashboard view.
     * Shows: the company's job listings + all applications received.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    private function companyDashboard($user)
    {
        $company  = $user->company;
        $jobs     = $company ? $company->jobs()->withCount('applications')->latest()->get() : collect();
        $recentApplications = $company
            ? \App\Models\Application::whereHas('job', fn($q) => $q->where('company_id', $company->id))
                                     ->with(['user', 'job'])
                                     ->latest()
                                     ->take(10)
                                     ->get()
            : collect();

        return view('dashboard.company', compact('user', 'company', 'jobs', 'recentApplications'));
    }

    /**
     * Build and return the Jobseeker dashboard view.
     * Shows: all applications submitted by this user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    private function jobseekerDashboard($user)
    {
        $applications = $user->applications()->with('job.company')->latest()->paginate(10);

        return view('dashboard.jobseeker', compact('user', 'applications'));
    }
}
