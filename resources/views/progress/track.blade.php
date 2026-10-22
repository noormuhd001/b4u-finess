@extends('layouts.app')

@section('title', 'Workout history')

@section('content')
    <div class="container">
        <div class="user-page-heading">
            <div><h1>Your workout history</h1><p>Every session is a step forward. Review your check-ins and training days.</p></div>
            <a href="{{ route('progress.index') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Log progress</a>
        </div>

        @if ($progressList->isEmpty())
            <section class="card">
                <div class="card-body py-5 text-center">
                    <span class="today-stat-icon mx-auto mb-3"><i class="bi bi-clock-history"></i></span>
                    <h2 class="h5">Your history starts with one session</h2>
                    <p class="text-muted mb-3">Log a workout and your progress will be saved here.</p>
                    <a href="{{ route('progress.index') }}" class="btn btn-primary">Log your first workout</a>
                </div>
            </section>
        @else
            <section class="card">
                <div class="card-body p-4">
                    <div class="dashboard-panel-title">
                        <div><h2>Progress entries</h2><p>{{ $progressList->count() }} {{ \Illuminate\Support\Str::plural('session', $progressList->count()) }} recorded</p></div>
                    </div>

                    <div class="row g-3 align-items-end mb-3">
                        <div class="col-md-4">
                            <label for="searchInput" class="form-label">Search history</label>
                            <input type="search" id="searchInput" placeholder="Search date, weight, or calories" class="form-control">
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label for="startDate" class="form-label">From</label>
                            <input type="date" id="startDate" class="form-control">
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label for="endDate" class="form-label">To</label>
                            <input type="date" id="endDate" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <form action="{{ route('progress.export') }}" method="GET" id="exportForm" class="m-0">
                                <input type="hidden" name="start_date" id="exportStart">
                                <input type="hidden" name="end_date" id="exportEnd">
                                <button type="submit" class="btn btn-outline-secondary w-100"><i class="bi bi-download"></i> Export</button>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="progress-table" id="workoutTable">
                            <thead><tr><th>Date</th><th>Weight</th><th>Calories</th><th class="text-end">Details</th></tr></thead>
                            <tbody>
                                @foreach ($progressList as $progress)
                                    <tr data-date="{{ $progress->workout_on->format('Y-m-d') }}">
                                        <td><strong>{{ $progress->workout_on->format('M d, Y') }}</strong></td>
                                        <td>{{ $progress->current_weight }} kg</td>
                                        <td>{{ number_format((float) $progress->avg_kcal_burned) }} kcal</td>
                                        <td class="text-end"><a href="{{ route('progress.trackDetailById', $progress->id) }}" class="btn btn-outline-secondary btn-sm" aria-label="View workout details"><i class="bi bi-arrow-up-right"></i> View</a></td>
                                    </tr>
                                @endforeach
                                <tr id="emptyFilterResult" hidden><td colspan="4" class="text-center text-muted py-4">No sessions match these filters.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const table = document.getElementById('workoutTable');
            if (!table) return;

            const searchInput = document.getElementById('searchInput');
            const startDate = document.getElementById('startDate');
            const endDate = document.getElementById('endDate');
            const emptyResult = document.getElementById('emptyFilterResult');
            const exportStart = document.getElementById('exportStart');
            const exportEnd = document.getElementById('exportEnd');

            function filterHistory() {
                const term = searchInput.value.trim().toLowerCase();
                let visibleCount = 0;

                table.querySelectorAll('tbody tr[data-date]').forEach(function (row) {
                    const matchesText = row.textContent.toLowerCase().includes(term);
                    const date = row.dataset.date;
                    const matchesStart = !startDate.value || date >= startDate.value;
                    const matchesEnd = !endDate.value || date <= endDate.value;
                    const visible = matchesText && matchesStart && matchesEnd;
                    row.hidden = !visible;
                    if (visible) visibleCount++;
                });

                emptyResult.hidden = visibleCount > 0;
                exportStart.value = startDate.value;
                exportEnd.value = endDate.value;
            }

            [searchInput, startDate, endDate].forEach(input => input.addEventListener('input', filterHistory));
            filterHistory();
        });
    </script>
@endpush
