@extends('layouts.app')

@push('styles')
    <style>
        .dashboard-title {
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .card {
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .card h4 {
            font-weight: 600;
            margin-bottom: 1rem;
        }



        .leader-item {
            background: #fff;
            border-radius: 12px;
            margin-bottom: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }

        .leader-item:hover {
            transform: scale(1.02);
        }

        .rank-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .rank-circle.highlight {
            background: linear-gradient(135deg, #ff8a00, #e52e71);
            color: #fff;
        }

        .material-symbols-outlined {
            vertical-align: middle;
            margin-right: 6px;
            font-size: 22px;
            color: #FF8C00;
        }

        nav .material-symbols-outlined {
            color: white;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
    <div class="container my-4">
        <div class="row g-4">
        </div>
        <div class="leader-list mt-3">
            <div class="card p-4">
                <h4 class="mb-3">
                    <span class="material-symbols-outlined">trophy</span>
                    Top Lifts Leaderboard
                </h4>
                @forelse($leaderboard as $index => $record)
                    <div class="leader-item d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rank-circle {{ $index < 3 ? 'highlight' : '' }}">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $record->user_name }}</h6>
                                <small class="text-muted">{{ $record->workout_name }}</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <h6 class="fw-bold text-danger mb-0">{{ $record->weight }} kg</h6>
                            <small>{{ $record->set_number }}×{{ $record->reps }}</small>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center">No records yet</p>
                @endforelse
            </div>
        </div>

    @endsection
