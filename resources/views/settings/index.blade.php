@extends('layouts.app')

@section('content')
    <div class="container my-4">
        <h3>Settings</h3>

        <div class="form-check form-switch mt-3">
            <input class="form-check-input" type="checkbox" id="darkModeToggle"
                {{ auth()->user()->dark_mode ? 'checked' : '' }}>
            <label class="form-check-label" for="darkModeToggle">Enable Dark Mode</label>
        </div>

        {{-- Error alert placeholder --}}
        <div id="errorAlert" class="alert alert-danger mt-3 d-none"></div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const toggle = $('#darkModeToggle');
            const errorAlert = $('#errorAlert');

            // Initial state
            if (toggle.is(':checked')) {
                $('body').addClass('dark-mode');
            }

            toggle.change(function() {
                let enabled = $(this).is(':checked') ? 1 : 0;

                if (enabled) {
                    $('body').addClass('dark-mode');
                } else {
                    $('body').removeClass('dark-mode');
                }

                // AJAX call to persist user preference
                $.ajax({
                    url: "{{ route('settings.darkmode') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        dark_mode: enabled
                    },
                    success: function(res) {
                        // hide error if previously shown
                        errorAlert.addClass('d-none').text('');
                    },
                    error: function() {
                        errorAlert.removeClass('d-none').text('⚠️ Failed to update dark mode. Please try again.');
                    }
                });
            });
        });
    </script>
@endpush
