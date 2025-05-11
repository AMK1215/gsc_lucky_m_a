@extends('admin_layouts.app')
@section('styles')
    <style>
        .transparent-btn {
            background: none;
            border: none;
            padding: 0;
            outline: none;
            cursor: pointer;
            box-shadow: none;
            appearance: none;
            /* For some browsers */
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
@endsection
@section('content')
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header pb-0">
                    <div class="d-lg-flex">
                        <div>
                            <h5 class="mb-0">Winlose Reports</h5>
                        </div>
                    </div>
                    <form role="form" class="text-start mt-4"
                    action="" method="GET">
                    <div class="row ml-5">

                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label text-dark fw-bold" for="inputEmail1">From Date</label>
                                <input type="date" class="form-control border border-1 border-secondary px-2"
                                    name="start_date" value="{{ request()->start_date }}">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label text-dark fw-bold" for="inputEmail1">To Date</label>
                                <input type="date" class="form-control border border-1 border-secondary px-2"
                                    id="" name="end_date" value="{{ request()->end_date }}">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <button type="submit" class="btn btn-primary" style="margin-top: 32px;">Search</button>
                            <a href="" class="btn btn-warning"
                                style="margin-top: 32px;">Refresh</a>
                        </div>
                    </div>
                </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered border-1" >
                        <thead class=" text-center text-bold text-dark table-info">
                            <tr>
                                <th>AgentName</th>
                                <th>UserName</th>
                                <th>TotalStake</th>
                                <th>TotalBet</th>
                                <th>TotalWin</th>
                                <th>TotalNetWin</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class=" text-center">
                            @foreach ($report as $row)
                                <tr class="text-center">
                                    <td>{{ $row->user_name }}</td>
                                    <td>{{ $row->user_name }}</td>
                                    <td>{{ $row->total_count }}</td>
                                    <td class="">
                                        {{ number_format($row->total_bet_amount, 2) }}
                                    </td>
                                    <td class="">
                                        {{ number_format($row->total_payout_amount, 2) }}
                                    </td>
                                    <?php
                                    $net_win = $row->total_payout_amount - $row->total_bet_amount;
                                    ?>
                                    <td class="{{ $net_win >= 0 ? 'text-success' : 'text-danger' }}">

                                        {{ number_format($net_win, 2) }}
                                    </td>
                                    <td><a href="{{ route('admin.reports.details', $row->user_name) }}">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="text-center">
                            <th></th>
                            <th>Total Stake</th>
                            <th>{{ $total['totalstake'] }}</th>
                            <th>Total Bet Amt</th>
                            <th>{{ $total['totalBetAmt'] }}</th>
                            <th>Total Win Amt</th>
                            <th>{{ $total['totalWinAmt'] }}</th>

                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <script src="{{ asset('admin_app/assets/js/plugins/datatables.js') }}"></script>
    <script>
        if (document.getElementById('banners-search')) {
            const dataTableSearch = new simpleDatatables.DataTable("#banners-search", {
                searchable: true,
                fixedHeight: false,
                perPage: 7
            });
        };
    </script>
@endsection
