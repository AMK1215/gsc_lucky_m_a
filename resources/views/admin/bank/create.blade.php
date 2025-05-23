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


        .custom-form-group {
            margin-bottom: 20px;
        }

        .custom-form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .custom-form-group input,
        .custom-form-group select {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #e1e1e1;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
        }

        .custom-form-group input:focus,
        .custom-form-group select:focus {
            border-color: #d33a9e;
            box-shadow: 0 0 5px rgba(211, 58, 158, 0.5);
        }

        .submit-btn {
            background-color: #d33a9e;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
        }

        .submit-btn:hover {
            background-color: #b8328b;
        }
        .custom-select-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .custom-select-wrapper::after {
            content: '\25BC'; /* Unicode character for "downwards black arrow" */
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            pointer-events: none; /* This makes sure clicks pass through to the select element underneath */
        }

        .form-control.custom-select {
            appearance: none; /* This removes default browser styling */
            -webkit-appearance: none; /* For Safari */
            -moz-appearance: none; /* For Firefox */
            padding-right: 30px; /* Make space for custom arrow */
        }

        /* Add more styling here for the select and wrapper elements as needed */

    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
@endsection
@section('content')
    <div class="container text-center mt-4">
        <div class="row">
            <div class="col-12 col-md-8 mx-auto">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header pb-0">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Create New Bank</h5>

                            </div>
                            <div class="ms-auto my-auto mt-lg-0 mt-4">
                                <div class="ms-auto my-auto">
                                    <a class="btn btn-icon btn-2 btn-primary" href="{{ route('admin.bank.index') }}">
                                        <span class="btn-inner--icon mt-1"><i class="material-icons">arrow_back</i>Back</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form role="form" method="POST" class="text-start" action="{{ route('admin.bank.store') }}">
                            @csrf
                            <div class="custom-form-group">
                                <label for="title">Payment Type <span class="text-danger">*</span></label>
                                <select name="payment_type_id" id="">
                                    <option value="">Select Payment Type</option>
                                    @foreach($paymentTypes as $paymentType)
                                        <option value="{{$paymentType->id}}">{{$paymentType->name}}</option>
                                    @endforeach
                                </select>
                                @error('payment_type_id')
                                <span class="text-danger d-block">*{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="custom-form-group">
                                <label for="title">Account Name <span class="text-danger">*</span></label>
                                <input type="text"  name="account_name" class="form-control" value="{{old('account_name')}}" placeholder="Enter Bank Account Name">
                                @error('account_name')
                                <span class="text-danger d-block">*{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="custom-form-group">
                                <label for="title">Account Number <span class="text-danger">*</span></label>
                                <input type="text"  name="account_number" class="form-control" value="{{old('account_number')}}" placeholder="Enter Bank Account Number">
                                @error('account_number')
                                <span class="text-danger d-block">*{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="custom-form-group">
                                <button class="btn btn-info" type="button" id="resetFormButton">Cancel</button>

                                <button type="submit" class="btn btn-primary" type="button">Submit</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <script src="{{ asset('admin_app/assets/js/plugins/choices.min.js') }}"></script>
    <script src="{{ asset('admin_app/assets/js/plugins/quill.min.js') }}"></script>

    <script>
        var errorMessage = @json(session('error'));
        var successMessage = @json(session('success'));
        var url = 'https://www.luckym.online/login';
        var name = @json(session('username'));
        var pw = @json(session('password'));
        var deposit_amount = @json(session('amount'));

        @if(session() -> has('success'))
        Swal.fire({
            title: successMessage,
            icon: "success",
            showConfirmButton: false,
            showCloseButton: true,
            html: `
  <table class="table table-bordered" style="color: #fff;">
  <tbody>

    <tr>
    <td>Url</td>
    <td id=""> ${url}</td>
  </tr>

  <tr>
    <td>Username</td>
    <td id="tusername"> ${name}</td>
  </tr>
  <tr>
    <td>Password</td>
    <td id="tpassword"> ${pw}</td>
  </tr>
  <tr>
    <td>Transfer Amount</td>
    <td id="tdeposit">${deposit_amount ?? '0'}</td>
</tr>


  <tr>
    <td></td>
    <td><a href="#" onclick="copy()" class="btn btn-sm btn-primary">copy</a></td>
  </tr>
 </tbody>
  </table>
  `
        });
        @elseif(session()->has('error'))
        Swal.fire({
            icon: 'error',
            title: errorMessage,
            background: 'hsl(230, 40%, 10%)',
            showConfirmButton: false,
            timer: 1500
        })
        @endif
        function copy() {
            var username= $('#tusername').text();
            var password= $('#tpassword').text();
            var tdeposit= $('#tdeposit').text();
            var copy = "url : "+url+"\nusername : "+username+"\npw : "+password + "\nTransfer Amount :" + tdeposit ;
            copyToClipboard(copy)
        }
        function copyToClipboard(v) {
            var $temp = $("<textarea>");
            $("body").append($temp);
            var html = v;
            $temp.val(html).select();
            document.execCommand("copy");
            $temp.remove();
        }

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('resetFormButton').addEventListener('click', function () {
                var form = this.closest('form');
                form.querySelectorAll('input[type="text"]').forEach(input => {
                    // Resets input fields to their default values
                    input.value = '';
                });
                form.querySelectorAll('select').forEach(select => {
                    // Resets select fields to their default selected option
                    select.selectedIndex = 0;
                });
                // Add any additional field resets here if necessary
            });
        });
    </script>

@endsection
