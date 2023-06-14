<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Timelog Report</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }
            h1 {
                text-align: center;
                color: #333;
                margin: 0px;
            }
            h3 {
                color: #444;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }
            th, td {
                padding: 10px;
                border: 1px solid #ddd;
                text-align: left;
            }
            th {
                background-color: #FA6731;
                color: #fff;
            }
            tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            p {
                color: #222;
            }
            hr {
                border: none;
                border-top: 1px solid #ddd;
                margin-top: 30px;
                margin-bottom: 15px;
            }
            .date-range {
                text-align: center;
                margin-bottom: 35px;
            }
            .date-range p {
                font-weight: bold;
                color: #555;
            }
            .totalTime {
                background-color: #d9d4d4;
                padding: 5px;
            }
            .proshore-logo {
                height: 70px;
            }
            .ticker-logo {
                height: 30px;
                float: right;
            }
            .billable {
                height: 25px;
            }
        </style>
    </head>
    <body>
        <div class="logo">
            <img src="./img/proshore-logo.png" alt="" class="proshore-logo">
            <img src="./img/ticker-logo.png" alt="" class="ticker-logo">
        </div>

        <h1>User Report</h1>

        <div class="date-range">
            <p>From: {{ $start_date }} / To: {{ $end_date }}</p>
        </div>

        @foreach($reports as $report)
            <hr>

            <div class="report-section">
                <h3>Name: {{ $report['user_name'] }}</h3>

                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Activity</th>
                            <th>Project</th>
                            <th>Client</th>
                            <th>Billable</th>
                            <th>Date</th>
                            <th>Total Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($report['activities'] as $activity)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $activity['activity'] }}</td>
                            <td>{{ $activity['project'] }}</td>
                            <td>{{ $activity['client'] }}</td>

                            @if ($activity['billable'] === 1)
                                <td>
                                    <img src="./img/billable.png" alt="billable" class="billable">
                                </td>
                            @endif
                            @if ($activity['billable'] === 0) 
                                <td>
                                    <img src="./img/non-billable.png" alt="non-billable" class="billable">
                                </td>
                            @endif

                            <td>{{ $activity['date'] }}</td>
                            <td>{{ $activity['total_time'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="totalTime"> Total Time: <b>{{ $report['total_time'] }}</b></p>
        @endforeach
    </body>
</html>
