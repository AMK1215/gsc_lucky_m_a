<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <meta http-equiv="X-UA-Compatible" content="ie=edge">
 <title>Lucky M</title>
 <style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f9f9f9;
        margin: 20px;
        color: #333;
    }

    .btn-back {
        display: inline-block;
        padding: 8px 16px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        margin-bottom: 20px;
        transition: background-color 0.2s ease;
    }

    .btn-back:hover {
        background-color: #0056b3;
    }

    h1 {
        font-size: 26px;
        color: #333;
        text-align: center;
        margin-bottom: 30px;
        border-bottom: 2px solid #007bff;
        padding-bottom: 10px;
        display: inline-block;
        width: 100%;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .table th, .table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
        font-size: 14px;
    }

    .table th {
        background-color: #007bff;
        color: white;
        font-weight: bold;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f2f8ff;
    }

    .table tbody tr:hover {
        background-color: #e6f0ff;
    }

    .pagination {
        float: right;
        margin-top: 15px;
    }
</style>
</head>
<body>

<a href="{{ url('admin/auth-agent-win-lose-report')}}" class="btn-back">← Back</a>

<h1>Agent Detail Report for {{ $details->first()->agent_name }} ({{ \Carbon\Carbon::parse($details->first()->created_at)->format('F Y') }})</h1>

<table class="table">
    <thead>
        <tr>
            <th>Date</th>
            <th>WagerID</th>
            <th>Bet Amount</th>
            <th>Valid Amount</th>
            <th>Payout Amount</th>
            <th>Commission Amount</th>
            <th>Jack Pot Amount</th>
            <th>JP Bet</th>
            <th>Agent Commission</th>
            <th>Win/Lose</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($details as $detail)
        <tr>
            <td>{{ \Carbon\Carbon::parse($detail->created_at)->format('d-m-Y H:i') }}</td>
            <td>
             <a href="https://prodmd.9977997.com/Report/BetDetail?agentCode=E820&WagerID={{ $detail->wager_id }}" target="_blank" style="color: #6f42c1; text-decoration: underline;">{{ $detail->wager_id }}</a>
            </td>
            <td>{{ number_format($detail->bet_amount, 2) }}</td>
            <td>{{ number_format($detail->valid_bet_amount, 2) }}</td>
            <td>{{ number_format($detail->payout_amount, 2) }}</td>
            <td>{{ number_format($detail->commission_amount, 2) }}</td>
            <td>{{ number_format($detail->jack_pot_amount, 2) }}</td>
            <td>{{ number_format($detail->jp_bet, 2) }}</td>
            <td>{{ $detail->agent_comm }} %</td>
            <td>{{ number_format($detail->win_or_lose, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="pagination">
    {{ $details->links() }}
</div>

</body>
</html>
