@extends('layouts.app')

@section('title', 'Login — Elevate Workforce Solutions')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <i class="bi bi-lock-fill fs-2 d-block mb-2"></i>
                    <h4 class="mb-0">Welcome Back</h4>
                    <p class="small opacity-75 mb-0">Sign in to your Elevate account</p>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('login') }}" method="POST" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}"
                                   placeholder="you@example.com" required autofocus />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" placeholder="Your password" required />
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember" />
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-lg fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </button>
                    </form>
                </div>

                <div class="card-footer text-center py-3 bg-light">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-primary fw-semibold">Register here</a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
