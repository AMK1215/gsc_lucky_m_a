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
          <h5 class="mb-0 text-dark">Transfer Logs</h5>
          {{-- Optional Add Button (if you want to add transfer logs manually) --}}
          {{-- <a href="#" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> New Transfer
          </a> --}}
        </div>

        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
              <thead class="table-light text-center">
                <tr>
                  <th>#</th>
                  <th>To User</th>
                  <th>Amount</th>
                  <th>Type</th>
                  <th>Note</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                @if(isset($transferLogs) && count($transferLogs) > 0)
                  @foreach ($transferLogs as $log)
                    <tr class="text-center">
                      <td>{{ ($transferLogs->currentPage() - 1) * $transferLogs->perPage() + $loop->iteration }}</td>
                      <td>{{ $log->targetUser->name }}</td>
                      <td class="{{ $log->type == 'withdraw' ? 'text-success' : 'text-danger' }}">
                        {{ number_format(abs($log->amountFloat)) }}
                      </td>
                      <td>
                        <span class="badge bg-{{ $log->type == 'withdraw' ? 'success' : 'danger' }}">
                          {{ $log->type == 'withdraw' ? 'Deposit' : 'Withdraw' }}
                        </span>
                      </td>
                      <td>{{ $log->note == 'null' ? '' : $log->note }}</td>
                      <td>{{ $log->created_at->format("d/m/Y H:i:s") }}</td>
                    </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                      There are no transfer logs available.
                    </td>
                  </tr>
                @endif
              </tbody>
            </table>
            <div class="d-flex justify-content-end mt-3">
            {{$transferLogs->links()}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
@section('scripts')
<script src="{{ asset('admin_app/assets/js/plugins/datatables.js') }}"></script>
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
