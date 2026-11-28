<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NR Fitness - Register')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/favicon-rounded.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@400;500;600;700;800&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body class="auth-page signup-page d-flex align-items-center justify-content-center login-container">

    <div class="login-wrapper">
        <!-- Left Section: Form -->
        <div class="login-left">
            <div class="login-card" id="mobileview">
                <a class="auth-brand text-decoration-none" href="{{ route('login') }}">
                    <img src="{{ asset('img/logo-rm.png') }}" alt="">
                    <span>NR FITNESS</span>
                </a>
                <div class="auth-heading">
                    <h1>Create your account</h1>
                    <p>Start building a stronger, more consistent routine.</p>
                </div>

                <form method="post" action="{{ route('register') }}">
                    @csrf

                    <!-- Name Field -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Full Name</label>
                        <input type="text" name="username"
                            class="form-control rounded-3 @error('username') is-invalid @enderror" id="username"
                            placeholder="Enter your name" value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" name="email"
                            class="form-control rounded-3 @error('email') is-invalid @enderror" id="email"
                            placeholder="Enter your email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="plan-picker-title">
                            <span class="form-label d-block">Choose your membership</span>
                            <small>{{ $membershipPlans->count() }} plans available</small>
                        </div>
                        <div class="plan-options @error('membership_plan_id') is-invalid @enderror" role="radiogroup" aria-label="Choose your membership">
                            @foreach ($membershipPlans as $plan)
                                <label class="plan-option">
                                    <input type="radio" name="membership_plan_id" value="{{ $plan->id }}"
                                        @checked((string) $selectedPlanId === (string) $plan->id) required>
                                    <span class="plan-option-card">
                                        <span class="plan-radio"><i class="bi bi-check"></i></span>
                                        <span class="plan-option-details">
                                            <span class="plan-option-name">{{ $plan->name }}</span>
                                            <span class="plan-option-description">{{ $plan->description ?: 'A plan for your fitness journey' }}</span>
                                        </span>
                                        <span class="plan-option-price">₹{{ number_format((float) $plan->price, 0) }}<small>/ month</small></span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @if ($membershipPlans->isEmpty())
                            <p class="text-muted small mb-0">Membership plans are temporarily unavailable. Please try again later.</p>
                        @endif
                        @error('membership_plan_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label" for="duration-months">Subscription duration</label>
                                <select class="form-select @error('duration_months') is-invalid @enderror" id="duration-months" name="duration_months" required>
                                    @foreach ([1, 3, 6, 12] as $months)
                                        <option value="{{ $months }}" @selected((int) old('duration_months', 1) === $months)>{{ $months }} {{ \Illuminate\Support\Str::plural('month', $months) }}{{ $months === 12 ? ' (1 year)' : '' }}</option>
                                    @endforeach
                                </select>
                                @error('duration_months')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="membership-offer">Available offer</label>
                                <select class="form-select @error('offer_id') is-invalid @enderror" id="membership-offer" name="offer_id">
                                    <option value="">No offer</option>
                                    @foreach ($availableOffers as $offer)
                                        <option value="{{ $offer->id }}"
                                            data-plan-ids="{{ $offer->plans->pluck('id')->join(',') }}"
                                            data-durations="{{ implode(',', $offer->applicable_durations) }}"
                                            @selected((string) old('offer_id') === (string) $offer->id)>
                                            {{ $offer->title }}{{ $offer->coupon_code ? ' · '.$offer->coupon_code : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('offer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="membership-coupon">Or enter a coupon code</label>
                                <div class="input-group">
                                    <input class="form-control @error('coupon_code') is-invalid @enderror" id="membership-coupon" name="coupon_code" value="{{ old('coupon_code') }}" maxlength="50" autocomplete="off">
                                    <button class="btn btn-outline-secondary" id="apply-membership-coupon" type="button">Apply</button>
                                </div>
                                @error('coupon_code')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="membership-quote mt-3" aria-live="polite">
                            <div><span>Original price</span><strong id="membership-subtotal">₹0.00</strong></div>
                            <div><span>Discount</span><strong id="membership-discount">−₹0.00</strong></div>
                            <div class="membership-quote-total"><span>Amount due</span><strong id="membership-total">₹0.00</strong></div>
                            <p id="membership-quote-message" class="small text-muted mb-0">Choose a plan to see your price.</p>
                        </div>
                        <p class="plan-payment-note"><i class="bi bi-cash-coin me-1"></i>Payment is made in cash at the gym. Your membership starts after an administrator confirms payment.</p>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password"
                            class="form-control rounded-3 @error('password') is-invalid @enderror" id="password"
                            placeholder="Enter your password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password"
                            class="form-control rounded-3 @error('confirm_password') is-invalid @enderror"
                            id="confirm_password" placeholder="Re-enter your password" required>
                        @error('confirm_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn w-100" id="create-account-button" @disabled($membershipPlans->isEmpty())>Create account</button>

                    <p class="mt-3 text-center small">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-decoration-none">Log In</a>
                    </p>

                    @if (session('error'))
                        <div class="alert alert-warning mt-3">{{ session('error') }}</div>
                    @endif
                </form>

                <div class="text-center mt-4 small">
                    <a href="{{ route('termsAndConditions') }}" class="text-muted text-decoration-none">Terms</a>
                    <span>·</span>
                    <a href="{{ route('privacyPolicy') }}" class="text-muted text-decoration-none">Privacy</a>
                </div>
            </div>
        </div>

        <!-- Right Section: Fitness Image -->
        <div class="login-right">
            <img src="{{ asset('img/login-background.webp') }}" alt="Fitness Image" class="login-image">
            <div class="auth-visual-copy">
                <span>Make progress personal</span>
                <h2>Small steps.<br>Lasting strength.</h2>
                <p>Find your rhythm, record your workouts, and see how far you can go.</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("form");
            const submitButton = form.querySelector("button[type='submit']");

            form.addEventListener("submit", function() {
                // Disable the button immediately
                submitButton.disabled = true;
                submitButton.innerText = "Submitting...";

                // Optional: Add spinner (Bootstrap-style)
                submitButton.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                Submitting...
            `;
            });
        });
    </script>
    <script>
        const membershipForm = document.querySelector('form[action="{{ route('register') }}"]');
        const planInputs = [...membershipForm.querySelectorAll('input[name="membership_plan_id"]')];
        const durationInput = document.getElementById('duration-months');
        const offerInput = document.getElementById('membership-offer');
        const couponInput = document.getElementById('membership-coupon');
        const quoteMessage = document.getElementById('membership-quote-message');
        const submitButton = document.getElementById('create-account-button');
        const quoteUrl = @json(route('membership.quote'));
        const currency = new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' });
        let quoteReady = false;
        let quoteRequest = 0;

        function selectedPlanId() {
            return membershipForm.querySelector('input[name="membership_plan_id"]:checked')?.value;
        }

        function refreshAvailableOffers() {
            const planId = selectedPlanId();
            const duration = durationInput.value;
            [...offerInput.options].forEach((option) => {
                if (!option.value) return;
                const eligible = option.dataset.planIds.split(',').includes(planId)
                    && option.dataset.durations.split(',').includes(duration);
                option.hidden = !eligible;
                option.disabled = !eligible;
                if (!eligible && option.selected) offerInput.value = '';
            });
        }

        async function refreshQuote() {
            const planId = selectedPlanId();
            if (!planId) {
                quoteReady = false;
                quoteMessage.textContent = 'Choose a plan to see your price.';
                return;
            }

            const requestId = ++quoteRequest;
            quoteReady = false;
            quoteMessage.textContent = 'Calculating your price…';
            const couponCode = couponInput.value.trim();
            const selectedOfferId = offerInput.value;
            const payload = {
                plan_id: planId,
                duration_months: durationInput.value,
                offer_id: selectedOfferId || null,
                coupon_code: selectedOfferId ? null : (couponCode || null),
            };

            try {
                const response = await fetch(quoteUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify(payload),
                });
                const result = await response.json();
                if (requestId !== quoteRequest) return;
                if (!response.ok) {
                    const errors = result.errors || {};
                    throw new Error(Object.values(errors).flat()[0] || 'Unable to calculate this offer.');
                }

                document.getElementById('membership-subtotal').textContent = currency.format(result.subtotal);
                document.getElementById('membership-discount').textContent = '−' + currency.format(result.discount);
                document.getElementById('membership-total').textContent = currency.format(result.total);
                quoteMessage.textContent = result.offer_title
                    ? result.offer_title + ' applied.'
                    : 'Pay in cash at the gym after creating your account.';
                quoteMessage.classList.remove('text-danger');
                quoteReady = true;
            } catch (error) {
                if (requestId !== quoteRequest) return;
                quoteMessage.textContent = error.message;
                quoteMessage.classList.add('text-danger');
            }
        }

        planInputs.forEach((input) => input.addEventListener('change', () => {
            refreshAvailableOffers();
            refreshQuote();
        }));
        durationInput.addEventListener('change', () => {
            refreshAvailableOffers();
            refreshQuote();
        });
        offerInput.addEventListener('change', () => {
            couponInput.value = '';
            refreshQuote();
        });
        couponInput.addEventListener('input', () => {
            if (couponInput.value.trim()) offerInput.value = '';
            quoteReady = false;
        });
        document.getElementById('apply-membership-coupon').addEventListener('click', refreshQuote);
        membershipForm.addEventListener('submit', (event) => {
            if (!quoteReady) {
                event.preventDefault();
                refreshQuote();
            }
        });

        refreshAvailableOffers();
        refreshQuote();
    </script>
</body>

</html>
