<!DOCTYPE html>
<html>
<head>
    <title>Agent Monthly Report</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        h1 {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        table th {
            background-color: #343a40;
            color: white;
            vertical-align: middle;
        }
        .summary {
            background-color: #ffeeba;
            font-weight: bold;
        }
        .win {
            color: green;
            font-weight: bold;
        }
        .lose {
            color: red;
            font-weight: bold;
        }
        .filter-label {
            font-weight: 500;
        }
    </style>
</head>
<body>
<div class=" my-4 mx-4">
    <h1 class="text-center text-primary">Agent Monthly Report</h1>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white text-center">
            Agent Win / Lose Filter (By Month or Date)
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.AuthAgentWinLose') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="start_date" class="filter-label">Start Date:</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="filter-label">End Date:</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="month_year" class="filter-label">Month/Year:</label>
                    <input type="month" class="form-control" id="month_year" name="month_year" value="{{ request('month_year') }}">
                </div>
                <div class="col-md-1 d-grid">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm ">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover table-sm align-middle">
                    <thead>
                    <tr>
                        <th rowspan="2" class="text-dark">Month</th>
                        <th rowspan="2" class="text-dark">Account</th>
                        <th rowspan="2" class="text-dark">Name</th>
                        <th rowspan="2" class="text-dark">Bet Amount</th>
                        <th rowspan="2" class="text-dark">Valid Amount</th>
                        <th rowspan="2" class="text-dark">Stake Count</th>
                        <th rowspan="2" class="text-dark">Gross Comm</th>
                        <th colspan="3" class="text-dark">Member</th>
                        <th colspan="3" class="text-dark">Downline</th>
                        <th colspan="3" class="text-dark">Myself</th>
                        <th colspan="3" class="text-dark">Upline</th>
                        <th rowspan="2" class="text-dark">Detail</th>
                    </tr>
                    <tr>
                        <th class="text-dark">W/L</th>
                        <th class="text-dark">Comm</th>
                        <th class="text-dark">Total</th>
                        <th class="text-dark">W/L</th>
                        <th class="text-dark">Comm</th>
                        <th class="text-dark">Total</th>
                        <th class="text-dark">W/L</th>
                        <th class="text-dark">Comm</th>
                        <th class="text-dark">Total</th>
                        <th class="text-dark">W/L</th>
                        <th class="text-dark">Comm</th>
                        <th class="text-dark">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($agentReports as $report)
                        <tr>
                            <td>{{ $report->report_month_year }}</td>
                            <td>Qty: {{ $report->qty }}</td>
                            <td>{{ $report->agent_name }}</td>
                            <td>{{ number_format($report->total_bet_amount, 2) }}</td>
                            <td>{{ number_format($report->total_valid_bet_amount, 2) }}</td>
                            <td>{{ $report->stake_count }}</td>
                            <td>0</td>

                            <td class="{{ $report->win_or_lose < 0 ? 'lose' : 'win' }}">
                                {{ number_format($report->win_or_lose, 2) }}
                            </td>
                            <td>0</td>
                            <td>{{ number_format($report->win_or_lose + $report->total_commission_amount, 2) }}</td>

                            <td>--</td><td>0</td><td>--</td>

                            <td class="{{ $report->win_or_lose < 0 ? 'lose' : 'win' }}">
                                {{ number_format($report->win_or_lose, 2) }}
                            </td>
                            <td>0</td>
                            <td>{{ number_format($report->win_or_lose + $report->total_commission_amount, 2) }}</td>

                            <td class="{{ $report->win_or_lose < 0 ? 'lose' : 'win' }}">
                                {{ number_format($report->win_or_lose, 2) }}
                            </td>
                            <td>0</td>
                            <td>{{ number_format($report->win_or_lose + $report->total_commission_amount, 2) }}</td>

                            <td>
                                <a href="{{ route('admin.authagent_winLdetails', ['agent_id' => $report->agent_id, 'month' => $report->report_month_year]) }}" class="btn btn-sm btn-info">
                                    View Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    <tr class="summary">
                        <td colspan="3">Summary</td>
                        <td>{{ number_format($agentReports->sum('total_bet_amount'), 2) }}</td>
                        <td>{{ number_format($agentReports->sum('total_valid_bet_amount'), 2) }}</td>
                        <td>--</td>
                        <td>{{ number_format($agentReports->sum('total_commission_amount'), 2) }}</td>

                        <td class="{{ $agentReports->sum('win_or_lose') < 0 ? 'lose' : 'win' }}">
                            {{ number_format($agentReports->sum('win_or_lose'), 2) }}
                        </td>
                        <td>0</td>
                        <td>{{ number_format($agentReports->sum('win_or_lose') + $agentReports->sum('total_commission_amount'), 2) }}</td>

                        <td>--</td><td>0</td><td>--</td>

                        <td class="{{ $agentReports->sum('win_or_lose') < 0 ? 'lose' : 'win' }}">
                            {{ number_format($agentReports->sum('win_or_lose'), 2) }}
                        </td>
                        <td>0</td>
                        <td>{{ number_format($agentReports->sum('win_or_lose') + $agentReports->sum('total_commission_amount'), 2) }}</td>

                        <td class="{{ $agentReports->sum('win_or_lose') < 0 ? 'lose' : 'win' }}">
                            {{ number_format($agentReports->sum('win_or_lose'), 2) }}
                        </td>
                        <td>0</td>
                        <td>{{ number_format($agentReports->sum('win_or_lose') + $agentReports->sum('total_commission_amount'), 2) }}</td>

                        <td></td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
