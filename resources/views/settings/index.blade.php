@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <div class="container">
        <div class="user-page-heading">
            <div><h1>Settings</h1><p>Make your NR Fitness experience feel right for you.</p></div>
        </div>

        <section class="card">
            <div class="card-body p-4">
                <div class="settings-card">
                    <div class="d-flex align-items-center gap-3">
                        <span class="today-stat-icon"><i class="bi bi-moon-stars"></i></span>
                        <div>
                            <h2>Dark appearance</h2>
                            <p class="mb-0">Use a darker color scheme across your member experience.</p>
                        </div>
                    </div>
                    <div class="form-check form-switch m-0">
                        <input class="form-check-input" type="checkbox" role="switch" id="darkModeToggle"
                            {{ auth()->user()->dark_mode ? 'checked' : '' }} aria-label="Enable dark appearance">
                    </div>
                </div>

                <div id="errorAlert" class="alert alert-danger mt-3 d-none" role="alert"></div>
                <div id="successAlert" class="alert alert-success mt-3 d-none" role="status">Your appearance preference was saved.</div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('darkModeToggle');
            const errorAlert = document.getElementById('errorAlert');
            const successAlert = document.getElementById('successAlert');
            const token = document.querySelector('meta[name="csrf-token"]').content;

            toggle.addEventListener('change', async function () {
                const enabled = this.checked ? 1 : 0;
                const previous = !this.checked;
                document.body.classList.toggle('dark-mode', Boolean(enabled));
                errorAlert.classList.add('d-none');
                successAlert.classList.add('d-none');

                try {
                    const response = await fetch(@json(route('settings.darkmode')), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                        body: JSON.stringify({ dark_mode: enabled }),
                    });

                    if (!response.ok) throw new Error('Preference update failed');
                    successAlert.classList.remove('d-none');
                } catch {
                    this.checked = previous;
                    document.body.classList.toggle('dark-mode', previous);
                    errorAlert.textContent = 'Your preference could not be saved. Please try again.';
                    errorAlert.classList.remove('d-none');
                }
            });
        });
    </script>
@endpush
