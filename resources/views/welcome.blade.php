<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Train with purpose at NR Fitness. Choose your membership, build your routine, and track your progress.">
    <title>NR Fitness | Build your strongest self</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon-rounded.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>

<body class="landing-page">
    <header class="landing-header">
        <a class="landing-brand" href="{{ route('home') }}" aria-label="NR Fitness home">
            <img src="{{ asset('img/logo-rm.png') }}" alt="">
            <span>NR <b>FITNESS</b></span>
        </a>
        <button class="landing-menu-toggle" type="button" aria-expanded="false" aria-controls="landing-nav" aria-label="Open navigation">
            <i class="bi bi-list"></i>
        </button>
        <nav class="landing-nav" id="landing-nav" aria-label="Main navigation">
            <a class="active" href="{{ route('home') }}">Home</a>
            <a href="#promo">Gym tour</a>
            <a href="#training">Training</a>
            <a href="#membership">Membership</a>
            <a href="{{ route('about-us') }}">About</a>
            <a class="landing-signin" href="{{ route('login') }}">Sign in <i class="bi bi-arrow-up-right"></i></a>
        </nav>
    </header>

    <main>
        <section class="landing-hero">
            <div class="hero-backdrop" role="img" aria-label="Athlete training in a gym"></div>
            <div class="hero-content">
                <span class="hero-eyebrow"><span></span> YOUR WORK. YOUR NEXT LEVEL.</span>
                <h1>FORGE YOUR<br><span>STRENGTH.</span><br>OWN YOUR<br>JOURNEY.</h1>
                <p>Train with purpose, build lasting habits, and make progress you can see—one session at a time.</p>
                <div class="hero-actions">
                    <a class="landing-button landing-button-primary" href="{{ route('signUp') }}">Get started <i class="bi bi-arrow-up-right"></i></a>
                    <a class="landing-button landing-button-outline" href="#training">Explore training</a>
                </div>
                <div class="hero-proof">
                    <span><i class="bi bi-check2-circle"></i> Progress tracking</span>
                    <span><i class="bi bi-check2-circle"></i> Member check-ins</span>
                </div>
            </div>
            <div class="hero-side-note"><span>01</span> TRAIN WITH INTENTION</div>
        </section>

        <section class="landing-stats" aria-label="What members can do">
            <div><span class="stats-icon"><i class="bi bi-activity"></i></span><p><strong>Train</strong><small>Explore guided workouts</small></p></div>
            <div><span class="stats-icon"><i class="bi bi-graph-up-arrow"></i></span><p><strong>Progress</strong><small>Keep your goals in view</small></p></div>
            <div><span class="stats-icon"><i class="bi bi-calendar-check"></i></span><p><strong>Show up</strong><small>Track your gym visits</small></p></div>
            <div><span class="stats-icon"><i class="bi bi-person-check"></i></span><p><strong>Stay consistent</strong><small>Build a routine that lasts</small></p></div>
        </section>

        <section class="promo-section landing-section" id="promo">
            <div class="section-heading">
                <div><span class="section-kicker">TAKE A CLOSER LOOK</span><h2>Step inside <span>NR Fitness.</span></h2></div>
                <p>Get a feel for the space and see where your next training session can take you.</p>
            </div>
            <div class="promo-video-frame">
                <video class="promo-video" autoplay muted loop preload="auto" playsinline aria-label="NR Fitness promotional video" poster="{{ asset('img/login-background.webp') }}">
                    <source src="{{ asset('vedeo/nr-promo.mp4') }}" type="video/mp4">
                    Your browser does not support the video element.
                </video>
                <span class="promo-video-label"><i class="bi bi-play-circle-fill"></i> INSIDE NR FITNESS</span>
            </div>
        </section>

        <section class="training-section landing-section" id="training">
            <div class="section-heading">
                <div><span class="section-kicker">MAKE EVERY SESSION COUNT</span><h2>Training built around <span>your goals.</span></h2></div>
                <p>Start where you are. Choose a workout, track your sessions, and keep moving forward at your own pace.</p>
            </div>
            <div class="training-grid">
                <article class="training-card training-card-large">
                    <img src="{{ asset('img/deadlift.jpg') }}" alt="Strength training with a barbell" loading="lazy">
                    <div class="training-card-shade"></div>
                    <div class="training-card-copy"><span>01 / STRENGTH</span><h3>Build your base.</h3><p>Develop strength with focused, trackable workouts.</p></div>
                    <i class="bi bi-arrow-up-right training-card-arrow"></i>
                </article>
                <article class="training-card">
                    <img src="{{ asset('img/bench-press.jpg') }}" alt="Bench press workout" loading="lazy">
                    <div class="training-card-shade"></div>
                    <div class="training-card-copy"><span>02 / TRAINING</span><h3>Move with purpose.</h3><p>Explore exercises for your routine.</p></div>
                    <i class="bi bi-arrow-up-right training-card-arrow"></i>
                </article>
                <article class="training-card">
                    <img src="{{ asset('img/squats.jpg') }}" alt="Squat exercise" loading="lazy">
                    <div class="training-card-shade"></div>
                    <div class="training-card-copy"><span>03 / PROGRESS</span><h3>See how far you go.</h3><p>Log workouts and celebrate the work.</p></div>
                    <i class="bi bi-arrow-up-right training-card-arrow"></i>
                </article>
            </div>
        </section>

        <section class="membership-section landing-section" id="membership">
            <div class="section-heading">
                <div><span class="section-kicker">YOUR MEMBERSHIP, YOUR MOVE</span><h2>Find your <span>starting point.</span></h2></div>
                <p>Choose a plan that fits your training. Cash payments are confirmed in person by the gym manager.</p>
            </div>
            @if ($membershipPlans->isNotEmpty())
                <div class="membership-grid">
                    @foreach ($membershipPlans as $plan)
                        <article class="membership-card">
                            <span class="plan-index">0{{ $loop->iteration }}</span>
                            <h3>{{ $plan->name }}</h3>
                            <p>{{ $plan->description ?: 'A membership to support your training routine.' }}</p>
                            <div class="plan-price"><strong>₹{{ number_format((float) $plan->price, 0) }}</strong><span>/ {{ $plan->duration_months }} {{ \Illuminate\Support\Str::plural('month', $plan->duration_months) }}</span></div>
                            <a href="{{ route('signUp') }}">Choose this plan <i class="bi bi-arrow-right"></i></a>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="membership-empty">Membership options are being updated. Please check back soon.</div>
            @endif
        </section>

        <section class="landing-cta">
            <div><span class="section-kicker">THE FIRST REP STARTS HERE</span><h2>Ready to make<br>your move?</h2></div>
            <a class="landing-button landing-button-primary" href="{{ route('signUp') }}">Join NR Fitness <i class="bi bi-arrow-up-right"></i></a>
        </section>
    </main>

    <footer class="landing-footer">
        <a class="landing-brand" href="{{ route('home') }}"><img src="{{ asset('img/logo-rm.png') }}" alt=""><span>NR <b>FITNESS</b></span></a>
        <p>Train with purpose. Progress with confidence.</p>
        <div><a href="{{ route('about-us') }}">About</a><a href="{{ route('termsAndConditions') }}">Terms</a><a href="{{ route('privacyPolicy') }}">Privacy</a><a href="{{ route('login') }}">Sign in</a></div>
        <small>© {{ now()->year }} NR Fitness</small>
    </footer>

    <script>
        const landingToggle = document.querySelector('.landing-menu-toggle');
        const landingNav = document.querySelector('.landing-nav');

        landingToggle.addEventListener('click', function () {
            const isOpen = landingNav.classList.toggle('is-open');
            landingToggle.setAttribute('aria-expanded', String(isOpen));
            landingToggle.setAttribute('aria-label', isOpen ? 'Close navigation' : 'Open navigation');
        });

        landingNav.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                landingNav.classList.remove('is-open');
                landingToggle.setAttribute('aria-expanded', 'false');
                landingToggle.setAttribute('aria-label', 'Open navigation');
            });
        });
    </script>
</body>

</html>
