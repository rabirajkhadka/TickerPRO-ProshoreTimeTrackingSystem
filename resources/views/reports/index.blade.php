<!-- reports.index.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>User Timelog Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>Weekly Report</h1>

    <p>User: {{ $user->name }}</p>
    <p>Start Date: {{ $start_date->toDateString() }}</p>
    <p>End Date: {{ $end_date->toDateString() }}</p>

    <table>
        <thead>
            <tr>
                <th>Activity</th>
                <th>Date</th>
                <th>Project</th>
                <th>Total Hours</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($report as $item)
            <tr>
                <td>{{ $item['activity'] }}</td>
                <td>{{ $item['date'] }}</td>
                <td>{{ $item['project'] }}</td>
                <td>{{ $item['total_hours'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
