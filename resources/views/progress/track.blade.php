@extends('layouts.app')

@push('styles')
    <style>
        /* Buttons */
        .btn {
            padding: 12px 25px;
            background: #FF8C00;
            color: #fff;
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

        /* Search Input */
        #searchInput {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .dark-mode #searchInput,
        .dark-mode input[type="date"] {
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
    <!-- Back -->
    <a href="{{ route('progress.index') }}" class="btn mb-3">
        <span class="material-symbols-outlined">arrow_back</span> Back
    </a>

    <h2 class="mb-4">Workout History</h2>

    @if ($progressList->isEmpty())
        <p>You have not logged any workouts yet.</p>
    @else
        <!-- 🔹 Filter Section -->
        <div class="row my-2 g-3 align-items-end">
            <!-- 🔍 Search -->
            <div class="col-md-3">
                <label for="searchInput" class="form-label">Search</label>
                <input type="text" id="searchInput" placeholder="Search by date or weight..." class="form-control">
            </div>

            <!-- 📅 Start Date -->
            <div class="col-md-3">
                <label for="startDate" class="form-label">Start Date</label>
                <input type="date" id="startDate" class="form-control">
            </div>

            <!-- 📅 End Date -->
            <div class="col-md-3">
                <label for="endDate" class="form-label">End Date</label>
                <input type="date" id="endDate" class="form-control">
            </div>

            <!-- 🔎 Filter Button -->
            <div class="col-md-1">
                <button id="filterBtn" class="btn btn-custom w-100">Filter</button>
            </div>

            <!-- ⬇ Export -->
            <div class="col-md-2 d-flex ">
                <form action="{{ route('progress.export') }}" method="GET" id="exportForm" class="m-0">
                    <input type="hidden" name="search" id="exportSearch">
                    <input type="hidden" name="start_date" id="exportStart">
                    <input type="hidden" name="end_date" id="exportEnd">
                    <button type="submit" class="btn-right">
                        <span class="material-symbols-outlined">download</span> Export
                    </button>
                </form>
            </div>
        </div>

        <!-- 🔹 Table -->
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

<!-- 🔹 Scripts -->
<script>
    const searchInput = document.getElementById('searchInput');
    const startDateEl = document.getElementById('startDate');
    const endDateEl = document.getElementById('endDate');
    const filterBtn = document.getElementById('filterBtn');

    const exportStart = document.getElementById('exportStart');
    const exportEnd = document.getElementById('exportEnd');

    function parseRowDate(dateText) {
        const parts = dateText.split(' ');
        const day = parts[0].padStart(2, '0');
        const monthStr = parts[1];
        const year = parts[2];

        const months = {
            Jan: '01', Feb: '02', Mar: '03', Apr: '04',
            May: '05', Jun: '06', Jul: '07', Aug: '08',
            Sep: '09', Oct: '10', Nov: '11', Dec: '12'
        };

        return new Date(`${year}-${months[monthStr]}-${day}`);
    }

    function filterTable() {
        let searchVal = searchInput.value.toLowerCase().trim();
        let startDate = startDateEl.value ? new Date(startDateEl.value) : null;
        let endDate = endDateEl.value ? new Date(endDateEl.value) : null;

        let terms = searchVal ? searchVal.split(",").map(t => t.trim()).filter(t => t) : [];

        let rows = document.querySelectorAll("#workoutTable tbody tr");
        rows.forEach(row => {
            let rowText = row.textContent.toLowerCase();
            let dateText = row.cells[0].innerText;
            let rowDate = parseRowDate(dateText);

            let matchesSearch = terms.length === 0 || terms.some(term => rowText.includes(term));
            let matchesStart = startDate ? rowDate >= startDate : true;
            let matchesEnd = endDate ? rowDate <= endDate : true;

            row.style.display = (matchesSearch && matchesStart && matchesEnd) ? "" : "none";
        });

        // Update export fields
        exportStart.value = startDateEl.value;
        exportEnd.value = endDateEl.value;
    }

    searchInput.addEventListener('keyup', filterTable);
    filterBtn.addEventListener('click', filterTable);
</script>

@endsection
