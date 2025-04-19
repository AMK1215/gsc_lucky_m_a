<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <meta http-equiv="X-UA-Compatible" content="ie=edge">
 <title>Lucky M</title>
 <script src="//code.jquery.com/jquery-1.12.3.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script
    src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet"
    href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet"
    href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
</head>
<body>
 <style>
    h1 {
        font-family: Arial, sans-serif;
        font-size: 24px;
        color: #333;
        margin-bottom: 20px;
        text-align: center;
        font-weight: bold;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .table th, .table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    .table th {
        background-color: #f2f2f2;
        color: #333;
        font-weight: bold;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .table tbody tr:hover {
        background-color: #f1f1f1;
    }
</style>
<section>
<h1>Agent Detail Report</h1>
<table class="table table-bordered data-table">
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

</table>
</section>

   

<script type="text/javascript">

  $(function () {
    var path = window.location.pathname;
    
    var id = path.split('/').pop();

    var ajaxUrl = "{{ url('admin/agent/wldetails') }}/" + id;

    var table = $('.data-table').DataTable({

        processing: true,
        serverSide: true,
        pageLength: 20,
        ajax: {
            url: ajaxUrl
        },
        columns: [

            {data: 'created_at', name: 'created_at'},
            {
                data: 'wager_id',
                name: 'wager_id',
                render: function(data, type, row) {
                    return `<a href="https://prodmd.9977997.com/Report/BetDetail?agentCode=E829&WagerID=${data}" target="_blank" style="color: blueviolet; text-decoration: underline;">${data}</a>`;
                }
            },
            {data: 'bet_amount', name: 'bet_amount'},

            {data: 'valid_bet_amount', name: 'valid_bet_amount'},

            {data: 'payout_amount', name: 'payout_amount'},

            {data: 'commission_amount', name: 'commission_amount'},
            
            {data: 'jack_pot_amount', name: 'jack_pot_amount'},
            {data: 'jp_bet', name: 'jp_bet'},
            {data: 'agent_comm', name: 'agent_comm'},
            {data: 'win_or_lose' , name: 'win_or_lose'}
        ]

    });

    

  });

</script>
</body>
</html>