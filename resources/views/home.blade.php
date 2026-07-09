@extends('layouts.app')

@section('title', 'Elevate Workforce Solutions — Find Your Dream Job in Nepal')

@section('content')

{{-- ============================================================
     HERO SECTION
     ============================================================ --}}
<section class="hero-section bg-primary text-white py-5">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="display-4 fw-bold mb-3">
                    Find Your <span class="text-warning">Dream Job</span><br />in Nepal
                </h1>
                <p class="lead mb-4 opacity-75">
                    Elevate Workforce Solutions connects top talent with leading companies.
                    Transparent, equal-opportunity hiring — powered by technology.
                </p>

                {{-- Search Bar --}}
                <form action="{{ route('jobs.index') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-lg shadow-sm"
                           placeholder="Job title, keyword..." value="{{ request('search') }}" />
                    <button type="submit" class="btn btn-warning btn-lg fw-bold px-4">
                        <i class="bi bi-search"></i> Search
                    </button>
                </form>
            </div>
            <div class="col-lg-5 d-none d-lg-flex justify-content-center">
                <i class="bi bi-people-fill" style="font-size: 10rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     STATS BANNER
     ============================================================ --}}
<section class="bg-warning py-3">
    <div class="container">
        <div class="row text-center g-3">
            <div class="col-4">
                <div class="fw-bold fs-4">{{ \App\Models\Job::active()->count() }}+</div>
                <div class="small">Active Jobs</div>
            </div>
            <div class="col-4">
                <div class="fw-bold fs-4">{{ \App\Models\Company::count() }}+</div>
                <div class="small">Companies</div>
            </div>
            <div class="col-4">
                <div class="fw-bold fs-4">{{ \App\Models\User::where('role','jobseeker')->count() }}+</div>
                <div class="small">Job Seekers</div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     FEATURED JOBS
     ============================================================ --}}
<section class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Featured Jobs</h2>
        <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary">
            View All Jobs <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    @if($featuredJobs->isEmpty())
        <div class="text-center text-muted py-5">
            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
            No jobs posted yet. <a href="{{ route('register') }}">Register as a company</a> to post the first one!
        </div>
    @else
        <div class="row g-4">
            @foreach($featuredJobs as $job)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm job-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="company-avatar bg-primary text-white rounded me-3 d-flex align-items-center justify-content-center"
                                 style="width:48px;height:48px;font-size:1.3rem;flex-shrink:0;">
                                {{ strtoupper(substr($job->company->name, 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="card-title mb-0 fw-bold">{{ $job->title }}</h6>
                                <small class="text-muted">{{ $job->company->name }}</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <span class="badge bg-light text-dark border me-1">
                                <i class="bi bi-geo-alt me-1"></i>{{ $job->location }}
                            </span>
                            <span class="badge bg-primary-subtle text-primary">
                                {{ ucfirst(str_replace('-', ' ', $job->type)) }}
                            </span>
                        </div>

                        @if($job->salary_range)
                        <p class="text-success fw-semibold small mb-2">
                            <i class="bi bi-currency-rupee"></i> {{ $job->salary_range }}
                        </p>
                        @endif

                        <p class="text-muted small mb-3">{{ Str::limit($job->description, 100) }}</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-4">
                        <a href="{{ route('jobs.show', $job) }}" class="btn btn-outline-primary btn-sm w-100">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</section>

{{-- ============================================================
     HOW IT WORKS
     ============================================================ --}}
<section class="bg-light py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">How It Works</h2>
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="bg-white rounded-3 shadow-sm p-4 h-100">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:60px;height:60px;font-size:1.5rem;">
                        <i class="bi bi-person-plus"></i>
                    </div>
                    <h5 class="fw-bold">1. Register</h5>
                    <p class="text-muted small">Create a free account as a jobseeker or company. Quick and secure registration process.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white rounded-3 shadow-sm p-4 h-100">
                    <div class="bg-warning text-dark rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:60px;height:60px;font-size:1.5rem;">
                        <i class="bi bi-search"></i>
                    </div>
                    <h5 class="fw-bold">2. Search & Apply</h5>
                    <p class="text-muted small">Browse thousands of jobs, filter by category, type, and location. Apply in minutes.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white rounded-3 shadow-sm p-4 h-100">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:60px;height:60px;font-size:1.5rem;">
                        <i class="bi bi-trophy"></i>
                    </div>
                    <h5 class="fw-bold">3. Get Hired</h5>
                    <p class="text-muted small">Companies review your application and contact you directly. Your journey to success starts here.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA SECTION --}}
@guest
<section class="container py-5 text-center">
    <h2 class="fw-bold mb-3">Ready to take the next step?</h2>
    <p class="text-muted mb-4">Join thousands of professionals already using Elevate to advance their careers.</p>
    <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-3">
        <i class="bi bi-person-plus me-2"></i>Create Free Account
    </a>
    <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary btn-lg">
        Browse Jobs
    </a>
</section>
@endguest

@endsection
