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

    .pagination {
        float: inline-end;
    }

    .pagination .page-item.active .page-link {
        color: #ffffff;
    }
</style>
@endsection
@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <!-- Card header -->
            <div class="card-header pb-0">
                <div class="d-lg-flex">
                    <div>
                        <h5 class="mb-2">Withdraw Requested Lists</h5>
                    </div>
                </div>
                <form role="form" class="text-start mt-4"
                action="{{route('admin.agent.deposit')}}" method="GET">
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
                        <a href="{{route('admin.agent.withdraw')}}" class="btn btn-warning"
                            style="margin-top: 32px;">Refresh</a>
                    </div>
                </div>
            </form>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered border-1" >
                    <thead class=" text-center text-bold text-dark table-info">
                        <th>#</th>
                        <th>PlayerName</th>
                        <th>Requested Amount</th>
                        <th>Payment Method</th>
                        <th>Bank Account Name</th>
                        <th>Bank Account Number</th>
                        <th>Status</th>
                        <th>Created_at</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach ($withdraws as $withdraw)
                        <tr  class="text-center" style="font-size :13px !important">
                            <td>{{ ($withdraws->currentPage() - 1) * $withdraws->perPage() + $loop->iteration }}</td>
                            <td>
                                <span class="d-block">{{ $withdraw->user->name }}</span>
                            </td>
                            <td>{{ number_format($withdraw->amount) }}</td>
                            <td>{{ $withdraw->user?->paymentType?->name }}</td>
                            <td>{{$withdraw->user->account_name}}</td>
                            <td>{{$withdraw->user->account_number}}</td>
                            <td>
                                @if ($withdraw->status == 0)
                                <span class="badge text-bg-warning text-white mb-2">Pending</span>
                                @elseif ($withdraw->status == 1)
                                <span class="badge text-bg-success text-white mb-2">Approved</span>
                                @elseif ($withdraw->status == 2)
                                <span class="badge text-bg-danger text-white mb-2">Rejected</span>
                                @endif
                            </td>


                            <td>{{ $withdraw->created_at->format('d-m-Y H:i:s') }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <form action="{{ route('admin.agent.withdrawStatusUpdate', $withdraw->id) }}" method="post">
                                        @csrf
                                        <input type="hidden" name="amount" value="{{ $withdraw->amount }}">
                                        <input type="hidden" name="status" value="1">
                                        <input type="hidden" name="player" value="{{ $withdraw->user_id }}">
                                        @if($withdraw->status == 0)
                                        <button class="btn btn-success p-1 me-1" type="submit">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        @endif
                                    </form>

                                    <form action="{{ route('admin.agent.withdrawStatusreject', $withdraw->id) }}" method="post">
                                        @csrf
                                        <input type="hidden" name="status" value="2">
                                        @if($withdraw->status == 0)
                                        <button class="btn btn-danger p-1 me-1" type="submit">
                                            <i class="fas fa-xmark"></i>
                                        </button>
                                        @endif
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{$withdraws->links()}}
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('admin_app/assets/js/plugins/datatables.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var errorMessage = @json(session('error'));
        var successMessage = @json(session('success'));


        @if(session() -> has('success'))
        Swal.fire({
            icon: 'success',
            title: successMessage,
            text: '{{ session('
            SuccessRequest ') }}',
            background: 'hsl(230, 40%, 10%)',
            timer: 3000,
            showConfirmButton: false
        });
        @elseif(session() -> has('error'))
        Swal.fire({
            icon: 'error',
            title: '',
            text: errorMessage,
            background: 'hsl(230, 40%, 10%)',
            timer: 3000,
            showConfirmButton: false
        });
        @endif
    });
</script>

<script>
    if (document.getElementById('users-search')) {
        const dataTableSearch = new simpleDatatables.DataTable("#users-search", {
            searchable: true,
            fixedHeight: false,
        });

        document.querySelectorAll(".export").forEach(function(el) {
            el.addEventListener("click", function(e) {
                var type = el.dataset.type;

                var data = {
                    type: type,
                    filename: "material-" + type,
                };

                if (type === "csv") {
                    data.columnDelimiter = "|";
                }

                dataTableSearch.export(data);
            });
        });
    };
</script>
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endsection
