@extends('layouts.app')

@push('styles')
    <style>
        /* Buttons */
        .btn {
            padding: 12px 25px;
            background: #FF8C00;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            transition: 0.3s;
        }

        .btn-custom:hover {
            background-color: #e07b00;
            color: #fff;
        }

        .btn-right {
            float: right;
            padding: 12px 25px;
            background: #0056b3;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            font-size: 16px;
        }



        /* Table Styles */
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 15px;
        }

        .custom-table th,
        .custom-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
        }

        .custom-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: left;
        }

        .custom-table tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Dark Mode */
        .dark-mode .custom-table th {
            background-color: #495057;
            color: #f8f9fa;
        }

        .dark-mode .custom-table td {
            color: #f1f1f1;
            border-bottom: 1px solid #6c757d;
        }

        .dark-mode .custom-table tbody tr:hover {
            background-color: #6c757d;
        }

        /* Search bar */
        #searchInput {
            width: 300px;
            padding: 8px 12px;
            margin-top: 15px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .dark-mode #searchInput {
            background: #495057;
            border: 1px solid #666;
            color: #fff;
        }

        .action-btn {
            padding: 6px 12px;
            background: #e07b00;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
        }

        .action-btn:hover {
            background: #FF8C00;
        }

        .material-symbols-outlined {
            vertical-align: middle;
            font-size: 20px;
            margin-right: 4px;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <a href="{{ route('progress.index') }}" class="btn">
            <span class="material-symbols-outlined">arrow_back</span> Back
        </a>
        <a href="{{ route('progress.export') }}" class="btn-right">
            <span class="material-symbols-outlined">download</span> Export
        </a>

        <h2 class="my-4">Workout History</h2>

        @if ($progressList->isEmpty())
            <p>You have not logged any workouts yet.</p>
        @else
            <!-- Search Bar -->
            <input type="text" id="searchInput" placeholder="Search by date or weight...">

            <table class="custom-table" id="workoutTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Weight (kg)</th>
                        <th>Avg Kcal Burned</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($progressList as $progress)
                        <tr>
                            <td>{{ $progress->workout_on->format('d M Y') }}</td>
                            <td>{{ $progress->current_weight }}</td>
                            <td>{{ $progress->avg_kcal_burned }}</td>
                            <td>
                                <a href="{{ route('progress.trackDetailById', $progress->id) }}" class="action-btn">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Search Filter Script -->
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll("#workoutTable tbody tr");

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });
    </script>
@endsection
