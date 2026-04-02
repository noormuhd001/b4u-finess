@extends('layouts.admin.app')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <style>
        .btn {
            padding: 12px 25px;
            background: #FF8C00;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            font-size: 16px;
        }

        .btn:hover {
            background: #e07b00;
        }

        .workout-item {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
        }

        .workout-item .form-label {
            font-weight: 500;
        }

        .removeWorkoutBtn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: red
        }

        .material-symbols-outlined {
            vertical-align: middle;
            margin-right: 6px;
            font-size: 22px;
        }

        .dark-mode .card,
        .dark-mode .workout-item,
        .dark-mode .modal-content {
            background-color: #1e1e1e;
            color: #e0e0e0;
            border: 1px solid #333;
        }

        .dark-mode .form-label,
        .dark-mode label {
            color: #e0e0e0;
        }

        .dark-mode .form-control,
        .dark-mode .form-select {
            background-color: #2c2c2c;
            color: #e0e0e0;
            border: 1px solid #555;
        }

        .dark-mode .form-control:focus,
        .dark-mode .form-select:focus {
            background-color: #2c2c2c;
            color: #fff;
            border-color: #FF8C00;
            box-shadow: none;
        }

        .dark-mode .btn-orange,
        .dark-mode .btn {
            background-color: #FF8C00;
            color: #121212;
        }

        .dark-mode .btn-orange:hover,
        .dark-mode .btn:hover {
            background-color: #e07b00;
            color: #fff;
        }

        .dark-mode .btn-outline-secondary {
            color: #e0e0e0;
            border-color: #555;
        }

        .dark-mode .btn-outline-secondary:hover {
            background-color: #333;
            color: #fff;
        }

        .dark-mode .list-group-item {
            background-color: #1e1e1e;
            color: #e0e0e0;
            border-color: #333;
        }

        .dark-mode .material-symbols-outlined.text-primary {
            color: #4dabf7 !important;
        }

        .dark-mode .material-symbols-outlined.text-danger {
            color: #ff6b6b !important;
        }

        .dark-mode .material-symbols-outlined.text-info {
            color: #17a2b8 !important;
        }

        .dark-mode .material-symbols-outlined.text-secondary {
            color: #adb5bd !important;
        }

        .badge-modal {
            background: radial-gradient(circle at center, #fff 0%, #f8f9fa 100%);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            animation: fadeInScale 0.6s ease-out;
        }

        .badge-icon {
            width: 150px;
            filter: drop-shadow(0 0 10px #ffd700);
            animation: popIn 0.8s ease forwards, glowPulse 2s infinite alternate;
        }

        .text-gradient {
            background: linear-gradient(90deg, #ff8a00, #e52e71);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes popIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }

            70% {
                transform: scale(1.2);
                opacity: 1;
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes glowPulse {
            from {
                filter: drop-shadow(0 0 8px #ffd700);
            }

            to {
                filter: drop-shadow(0 0 18px #ffed4a);
            }
        }
    </style>
@endpush

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <span class="material-symbols-outlined text-primary">fitness_center</span>
                Progress Reviews
            </h5>
        </div>

        <div class="card-body">
            @if($progress->count())
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Weight (kg)</th>
                                <th>Workout Date</th>
                                <th>Avg Kcal Burned</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($progress as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        <span class="fw-semibold">
                                            {{ $item->user_name }}
                                        </span>
                                    </td>

                                    <td>{{ $item->current_weight }}</td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($item->workout_on)->format('d M Y') }}
                                    </td>

                                    <td>{{ $item->avg_kcal_burned }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    No progress records found.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <!-- Confetti Script -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
@endpush
