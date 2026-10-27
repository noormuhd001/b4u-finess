<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SendOtpRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Http\Requests\Auth\UserStoreRequest;
use App\Jobs\Auth\SendForgotPasswordJob;
use App\Mail\Auth\RegistrationOtpMail;
use App\Models\MembershipPlan;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        // Validate incoming request
        // $request->validate([
        //     'email' => 'required|email',
        //     'password' => 'required|min:6',
        // ]);

        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials + ['status' => true])) {
                $request->session()->regenerate();
                $user = Auth::user();

                if ($user->role == 1) {
                    return redirect()->route('admin.dashboard');
                } else {
                    return redirect()->route('dashboard');
                }
                // return redirect()->intended('/dashboard')->with('success', 'Login successful!');
            }

            $unverifiedUser = User::where('email', $request->input('email'))
                ->where('status', false)
                ->whereNotNull('registration_otp_hash')
                ->first();
            if ($unverifiedUser && Hash::check($request->input('password'), $unverifiedUser->password)) {
                return redirect()->route('registration.verify', ['email' => $unverifiedUser->email])
                    ->with('error', 'Verify your email address to activate your account.');
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
            $membershipPlans = MembershipPlan::where('is_active', true)->orderBy('price')->get();

            return view('auth.create', compact('membershipPlans'));
        } catch (Exception $e) {
            report($e);

            return back()->with('error', 'Membership plans could not be loaded. Please try again.');
        }
    }

    public function register(UserStoreRequest $request)
    {
        try {
            $otp = (string) random_int(100000, 999999);
            $user = User::create([
                'name' => $request->input('username'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'membership_plan_id' => $request->input('membership_plan_id'),
                'cash_payment_status' => 'pending',
                'status' => false,
            ]);
            $user->registration_otp_hash = Hash::make($otp);
            $user->registration_otp_expires_at = now()->addMinutes(10);
            $user->save();

            Mail::to($user->email)->send(new RegistrationOtpMail($user->name, $otp));

            return redirect()->route('registration.verify', ['email' => $user->email])
                ->with('success', 'Your account is registered. Enter the code sent to your email to activate it.')
                ->with('otp_sent', true);
        } catch (\Exception $e) {
            report($e);

            if (isset($user)) {
                return redirect()->route('registration.verify', ['email' => $user->email])
                    ->with('error', 'We could not send your verification code. Please try again later or contact support if the problem continues.');
            }

            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function showRegistrationVerification(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        return view('auth.verify-registration', ['email' => $validated['email']]);
    }

    public function verifyRegistration(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'otp' => ['required', 'digits:6'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (
            ! $user ||
            $user->status ||
            ! $user->registration_otp_hash ||
            ! $user->registration_otp_expires_at ||
            $user->registration_otp_expires_at->isPast() ||
            ! Hash::check($validated['otp'], $user->registration_otp_hash)
        ) {
            return back()->withErrors(['otp' => 'The verification code is invalid or expired.'])->withInput();
        }

        $user->status = true;
        $user->email_verified_at = now();
        $user->registration_otp_hash = null;
        $user->registration_otp_expires_at = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Your email is verified and your account is active. You can now log in.');
    }

    public function resendRegistrationOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);
        $user = User::where('email', $validated['email'])
            ->where('status', false)
            ->whereNotNull('registration_otp_hash')
            ->first();

        if (! $user) {
            return back()->with('error', 'No pending account was found for that email.');
        }

        try {
            $otp = (string) random_int(100000, 999999);
            $user->registration_otp_hash = Hash::make($otp);
            $user->registration_otp_expires_at = now()->addMinutes(10);
            $user->save();
            Mail::to($user->email)->send(new RegistrationOtpMail($user->name, $otp));
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'We could not send a new verification code. Please try again later or contact support if the problem continues.');
        }

        return redirect()->route('registration.verify', ['email' => $user->email])
            ->with('success', 'A new verification code has been sent.')
            ->with('otp_sent', true);
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
            if (! $user) {
                return back()->with('error', 'If the email is registered with us, we will send a link to reset the password.');
            }

            $token = Str::random(60);
            User::where('email', $email)
                ->update([
                    'forgot_token' => $token,
                    'expires_at' => now()->addMinutes(5),
                    'updated_at' => now(),
                ]);

            $resetUrl = url('reset-password/'.$token);

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
            if (! $user || $user->expires_at < now()) {
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

            if (! $user || $user->expires_at < now()) {
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

    public function privacyPolicy()
    {
        try {
            return view('auth.privacypolicy');
        } catch (Exception $e) {
            report($e);

            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function termsAndConditions()
    {
        try {
            return view('auth.termsandconditions');
        } catch (Exception $e) {
            report($e);

            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login');
        } catch (Exception $e) {
            report($e);

            return back()->with('error', 'Something went wrong. Please try again.');
        }
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
