@extends('layouts.app')

@section('title', 'Register — Elevate Workforce Solutions')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <i class="bi bi-person-plus-fill fs-2 d-block mb-2"></i>
                    <h4 class="mb-0">Create Your Account</h4>
                    <p class="small opacity-75 mb-0">Join Elevate Workforce Solutions today</p>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('register') }}" method="POST" novalidate>
                        @csrf

                        {{-- ROLE SELECTION --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">I am registering as:</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="role" id="role_jobseeker"
                                           value="jobseeker" {{ old('role', 'jobseeker') === 'jobseeker' ? 'checked' : '' }}
                                           onchange="toggleCompanyFields(false)" />
                                    <label class="btn btn-outline-primary w-100" for="role_jobseeker">
                                        <i class="bi bi-person-badge d-block fs-3 mb-1"></i>
                                        Job Seeker
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="role" id="role_company"
                                           value="company" {{ old('role') === 'company' ? 'checked' : '' }}
                                           onchange="toggleCompanyFields(true)" />
                                    <label class="btn btn-outline-primary w-100" for="role_company">
                                        <i class="bi bi-building d-block fs-3 mb-1"></i>
                                        Company
                                    </label>
                                </div>
                            </div>
                            @error('role')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- BASIC INFO --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}"
                                   placeholder="Your full name" required />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}"
                                   placeholder="you@example.com" required />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" placeholder="Minimum 8 characters" required />
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation"
                                   placeholder="Repeat password" required />
                        </div>

                        {{-- COMPANY-ONLY FIELDS (shown/hidden by JS) --}}
                        <div id="companyFields" class="{{ old('role') === 'company' ? '' : 'd-none' }}">
                            <hr />
                            <h6 class="fw-bold text-muted mb-3"><i class="bi bi-building me-2"></i>Company Details</h6>

                            <div class="mb-3">
                                <label for="company_name" class="form-label fw-semibold">Company Name</label>
                                <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                       id="company_name" name="company_name" value="{{ old('company_name') }}"
                                       placeholder="Your company name" />
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="company_location" class="form-label fw-semibold">Location</label>
                                <input type="text" class="form-control @error('company_location') is-invalid @enderror"
                                       id="company_location" name="company_location" value="{{ old('company_location') }}"
                                       placeholder="e.g. Kathmandu, Nepal" />
                                @error('company_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="company_industry" class="form-label fw-semibold">Industry</label>
                                <select class="form-select @error('company_industry') is-invalid @enderror"
                                        id="company_industry" name="company_industry">
                                    <option value="">— Select Industry —</option>
                                    @foreach(['Technology', 'Finance & Banking', 'Healthcare', 'Education', 'Construction', 'Hospitality', 'Manufacturing', 'Retail', 'NGO / INGO', 'Other'] as $ind)
                                        <option value="{{ $ind }}" {{ old('company_industry') === $ind ? 'selected' : '' }}>{{ $ind }}</option>
                                    @endforeach
                                </select>
                                @error('company_industry')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-lg fw-semibold">
                            <i class="bi bi-check-circle me-2"></i>Create Account
                        </button>
                    </form>
                </div>

                <div class="card-footer text-center py-3 bg-light">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-primary fw-semibold">Login here</a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleCompanyFields(show) {
    const fields = document.getElementById('companyFields');
    fields.classList.toggle('d-none', !show);
}
// On page load, check if company was previously selected
document.addEventListener('DOMContentLoaded', function () {
    const companyRadio = document.getElementById('role_company');
    if (companyRadio && companyRadio.checked) {
        toggleCompanyFields(true);
    }
});
</script>
@endpush
