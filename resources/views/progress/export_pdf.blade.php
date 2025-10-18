<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Workout Progress Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            font-size: 13px;
            color: #333;
            margin: 25px;
            background-color: #fff;
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
            color: #FF5C00;
            font-size: 24px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .section-title {
            font-size: 16px;
            color: #FF5C00;
            margin-bottom: 10px;
            border-bottom: 2px solid #FF5C00;
            display: inline-block;
            padding-bottom: 4px;
        }

        .user-info {
            background: #FFF4E0;
            border: 1px solid #FFB56B;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 25px;
        }

        .user-info p {
            margin: 4px 0;
            font-size: 13px;
        }

        .user-info strong {
            display: inline-block;
            width: 100px;
            color: #FF5C00;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        th,
        td {
            border: 1px solid #FFB56B;
            padding: 8px;
            text-align: center;
        }

        th {
            background: #FF5C00;
            color: #FFF8E6;
            font-weight: 500;
        }

        tbody tr:nth-child(even) {
            background: #FFF4E0;
        }

        .workout-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        .workout-table th,
        .workout-table td {
            border: 1px solid #FFB56B;
            padding: 4px;
            font-size: 12px;
        }

        .workout-table th {
            background: #FF8728;
            color: #FFF8E6;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 11px;
            color: #FF5C00;
            border-top: 1px solid #FFB56B;
            padding-top: 8px;
        }

        /* Ensure clean page breaks for PDF */
        .page-break {
            page-break-after: always;
        }

        /* Optional logo styling */
        .logo {
            display: block;
            margin: 0 auto 20px;
            max-width: 120px;
        }
    </style>
</head>

<body>
    @if (isset($logoPath))
        <img src="{{ $logoPath }}" alt="Logo" class="logo">
    @endif

    <h1>Workout Progress Report</h1>

    <!-- User Info -->
    <div class="user-info">
        <h2 class="section-title">User Information</h2>
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Gender:</strong> {{ config('constant.gender.' . $user->gender) }}</p>
        <p><strong>Height:</strong> {{ $user->height }} cm</p>
        <p><strong>Weight:</strong> {{ $user->weight }} kg</p>
        <p><strong>Goal:</strong> {{ config('constant.goal.' . $user->goal) }}</p>
    </div>

    <!-- Progress Table -->
    <h2 class="section-title">Progress Summary</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Weight (kg)</th>
                <th>Avg Kcal Burned</th>
                <th>Workout Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($progressList as $progress)
                <tr>
                    <td>{{ $progress->workout_on->format('d M Y') }}</td>
                    <td>{{ $progress->current_weight }}</td>
                    <td>{{ $progress->avg_kcal_burned }}</td>
                    <td>
                        <table class="workout-table">
                            <thead>
                                <tr>
                                    <th>Workout</th>
                                    <th>Set</th>
                                    <th>Reps</th>
                                    <th>Weight (kg)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($progress->logs as $log)
                                    <tr>
                                        <td>{{ $log->workout->workout_name ?? 'N/A' }}</td>
                                        <td>{{ $log->set_number }}</td>
                                        <td>{{ $log->reps }}</td>
                                        <td>{{ $log->weight }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">No workout logs available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('d M Y, h:i A') }}
    </div>
</body>

</html>
