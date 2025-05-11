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
@endsection
@section('content')
<div class="row mt-4">
    <div class="col-12">
      <div class="card shadow-sm border-0">
        <!-- Card header -->
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center bg-light border-bottom py-3">
          <h5 class="mb-0 text-dark">Player Dashboards</h5>
          <a href="{{ route('admin.player.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Create Player
          </a>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" >
              <thead class="table-light text-center">
                <tr>
                  <th>#</th>
                  <th>PlayerID</th>
                  <th>Name</th>
                  <th>Phone</th>
                  <th>Status</th>
                  <th>Balance</th>
                  <th>Created At</th>
                  <th>Actions</th>
                  <th>Transaction</th>
                </tr>
              </thead>
              <tbody>
                @if(isset($users) && count($users) > 0)
                  @foreach ($users as $user)
                    <tr class="text-center" style="font-size: 15px !important">
                      <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                      <td>{{ $user->user_name }}</td>
                      <td>{{ $user->name }}</td>
                      <td>{{ $user->phone }}</td>
                      <td>
                        <span class="badge bg-{{ $user->status == 1 ? 'success' : 'danger' }}">
                          {{ $user->status == 1 ? 'Active' : 'Inactive' }}
                        </span>
                      </td>
                      <td>{{ number_format($user->balanceFloat, 2) }}</td>
                      <td>{{ $user->created_at->setTimezone('Asia/Yangon')->format('d-m-Y H:i:s') }}</td>
                      <td>
                        <div class="d-flex justify-content-center gap-2">
                          <a onclick="event.preventDefault(); document.getElementById('banUser-{{ $user->id }}').submit();" href="#" class="btn btn-outline-{{ $user->status == 1 ? 'success' : 'danger' }} btn-sm" data-bs-toggle="tooltip" title="{{ $user->status == 1 ? 'Active Player' : 'Inactive Player' }}">
                            <i class="fa-solid {{ $user->status == 1 ? 'fa-user' : 'fa-user-slash' }}"></i>
                          </a>
                          <form id="banUser-{{ $user->id }}" action="{{ route('admin.player.ban', $user->id) }}" method="post" class="d-none">
                            @csrf
                            @method('PUT')
                          </form>

                          <a href="{{ route('admin.player.getChangePassword', $user->id) }}" class="btn btn-outline-info btn-sm" data-bs-toggle="tooltip" title="Change Password">
                            <i class="fa fa-lock"></i>
                          </a>

                          <a href="{{ route('admin.player.edit', $user->id) }}" class="btn btn-outline-info btn-sm" data-bs-toggle="tooltip" title="Edit Player">
                            <i class="fa fa-pen-to-square"></i>
                          </a>
                        </div>
                      </td>
                      <td>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                          <a href="{{ route('admin.player.getCashIn', $user->id) }}" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Deposit to Player">
                            <i class="fas fa-plus text-white me-1"></i> Dep
                          </a>
                          <a href="{{ route('admin.player.getCashOut', $user->id) }}" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Withdraw from Player">
                            <i class="fa fa-minus text-white me-1"></i> WDL
                          </a>
                          <a href="{{ route('admin.logs', $user->id) }}" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Logs">
                            <i class="fa fa-right-left text-white me-1"></i> Logs
                          </a>
                          <a href="{{ route('admin.transferLogDetail', $user->id) }}" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" title="Transfer Logs">
                            <i class="fas fa-right-left text-white me-1"></i> Transfer
                          </a>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="9" class="text-center py-4 text-muted">
                      There are no players available.
                    </td>
                  </tr>
                @endif
              </tbody>
            </table>
            <div class="d-flex justify-content-end mt-3">
            {{$users->links()}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
<script src="{{ asset('admin_app/assets/js/plugins/datatables.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

{{-- <script>
    const dataTableSearch = new simpleDatatables.DataTable("#datatable-search", {
      searchable: true,
      fixedHeight: true
    });
  </script> --}}
<script>
  if (document.getElementById('users-search')) {
    const dataTableSearch = new simpleDatatables.DataTable("#users-search", {
      searchable: true,
      fixedHeight: false,
      perPage: 7
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
