<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\SendOtpRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Http\Requests\Auth\UserStoreRequest;
use App\Jobs\Auth\SendForgotPasswordJob;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                return redirect()->intended('/dashboard')->with('success', 'Login successful!');
            }
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function signUp()
    {
        try {
            return view('auth.create');
        } catch (Exception $e) {
            report($e);
        }
    }


    public function register(UserStoreRequest $request)
    {
        try {
            $user = User::create([
                'name'     => $request->input('username'),
                'email'    => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            // // Login the user automatically after registration
            // Auth::login($user);

            // Redirect to dashboard
            if ($user) {
                return redirect()->route('login')->with('success', 'Welcome, your account has been created!');
            }
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function forgotPassword()
    {
        try {
            return view('auth.forgotpassword');
        } catch (Exception $e) {
            report($e);
            return back()->with('error', 'Something went wrong.please try again.');
        }
    }

    public function sendOtp(SendOtpRequest $request)
    {
        try {
            $email = $request->input('email');

            $user = User::where('email', $email)->first();
            if (!$user) {
                return back()->with('error', 'If the email is registered with us, we will send a link to reset the password.');
            }

            $token = Str::random(60);
            User::where('email', $email)
                ->update([
                    'forgot_token' => $token,
                    'expires_at' => now()->addMinutes(5),
                    'updated_at'   => now(),
                ]);

            $resetUrl = url('reset-password/' . $token);

            SendForgotPasswordJob::dispatch($user, $resetUrl);

            return back()->with('success', 'If the email is registered with us, we will send a link to reset the password.');
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function resetPassword($token)
    {
        try {
            $user = User::where('forgot_token', $token)->first();
            if (!$user || $user->expires_at < now()) {
                return redirect()->route('forgotPassword')->with('error', 'This password reset link is invalid or has expired. Please request a new one.');
            }
            return view('auth.resetpassword', compact('token'));
        } catch (Exception $e) {
            report($e);
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }


    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $token = $request->input('token');
            $user = User::where('forgot_token', $token)->first();

            if (!$user || $user->expires_at < now()) {
                return redirect()->route('forgotPassword')->with('error', 'This password reset link is invalid or has expired. Please request a new one.');
            }

            $user->password = Hash::make($request->password);
            $user->forgot_token = null;
            $user->expires_at = null;
            $user->save();

            return redirect()->route('login')->with('success', 'Your password has been updated successfully. You can now log in with your new password.');
        } catch (Exception $e) {
            report($e);
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login'); 
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
