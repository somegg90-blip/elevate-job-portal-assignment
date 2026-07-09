<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Class AuthController
 *
 * Handles all authentication actions: register, login, logout.
 *
 * MVC Role: CONTROLLER
 *   - Receives HTTP requests (from Views/routes)
 *   - Interacts with Models (User, Company)
 *   - Returns responses / redirects to Views
 *
 * OOP Principles:
 *   - Encapsulation: all auth logic is encapsulated in this class
 *   - Single Responsibility: this controller only handles authentication
 *
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    // =========================================================================
    // REGISTRATION
    // =========================================================================

    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration form submission.
     * Validates input, creates the User, then creates a Company profile if role is 'company'.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Step 1: Validate incoming data
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role'     => ['required', 'in:jobseeker,company'],
            // Extra fields only required for company registration
            'company_name'     => ['required_if:role,company', 'nullable', 'string', 'max:255'],
            'company_location' => ['required_if:role,company', 'nullable', 'string', 'max:255'],
            'company_industry' => ['required_if:role,company', 'nullable', 'string', 'max:255'],
        ]);

        // Step 2: Create the User model (Eloquent ORM)
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        // Step 3: If company, also create the Company profile
        if ($user->isCompany()) {
            Company::create([
                'user_id'  => $user->id,
                'name'     => $validated['company_name'],
                'location' => $validated['company_location'],
                'industry' => $validated['company_industry'],
            ]);
        }

        // Step 4: Log the user in automatically after registration
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Account created successfully! Welcome to Elevate Workforce Solutions.');
    }

    // =========================================================================
    // LOGIN
    // =========================================================================

    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login form submission.
     * Uses Laravel's Auth facade for secure credential checking.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Auth::attempt() checks credentials and creates a session if valid
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate(); // Prevent session fixation attacks

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        // Return back with a single generic error (don't reveal which field was wrong)
        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }

    // =========================================================================
    // LOGOUT
    // =========================================================================

    /**
     * Log the authenticated user out.
     * Invalidates the session and regenerates the CSRF token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out successfully.');
    }
}
