<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@Include('layouts.links.admin.title') | Buyers Payment Methods</title>
    <style>
        .flex-wrap {
            float: right !important;
        }

        .cur-rate>td {
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
        }

        .dropdown-menu {
            min-width: 0 !important;
            padding: 0.375rem 0.75rem !important;
        }
    </style>
    @Include('layouts.links.admin.head')
    @Include('layouts.links.datatable.head')
    @Include('layouts.links.toastr.head')
    {{-- @Include('layouts.links.selectpciker.head') --}}
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    @extends('layouts.admin.master')
    @section('content')
    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">{{ $buyer_name->name }} Payment Methods</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">{{ $buyer_name->name }} Payment Methods</li>
                            </ol>
                        </div><!-- /.col -->
                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            {{-- <ul class="nav nav-pills text-center"> --}}
                                <div id="success" class="alert alert-default-success alert-dismissible fade show"
                                    buyer="alert" style="display: none">
                                    <strong class="">{{ session('success') }}</strong>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 mt-2">
                                    <!-- Button trigger modal -->
                                    <a type="button" href="#" data-toggle="modal" data-target="#buyer_pay_method_create"
                                        class="border px-2 btn" style="background-color: #091E3E;color: white">
                                        Add Buyer Currency
                                    </a>
                                    <!-- Modal -->
                                    <form action="{{route('admin.buyer.pay_method.create',['id'=>$id])}}" method="POST" id="buyer_pay_method_create_form">
                                        @csrf
                                        <div class="modal fade" id="buyer_pay_method_create">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Buyer Payment Method</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="currency_row">
                                                            <div class="row">
                                                                <div class="form-group col-12">
                                                                    <label for="buyer_name" class="text-capitalize d-flex justify-content-between">Name<i class="fas fa-plus rounded-pill bg-primary p-1 append_button" role="button"></i></label>
                                                                    <input type="hidden" name="buyer_id" value="{{ $id }}">
                                                                    <select class="form-control" name="payment_methods[]" required>
                                                                        <option selected hidden disabled>SELECT</option>
                                                                        @foreach ($payment_methods as $payment_method)
                                                                            <option value="{{ $payment_method->id }}">{{ $payment_method->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <span class="invalid-feedback" id="create_buyer_name_error">
                                                                    </span>
                                                                </div>
                                                                <div class="form-group col-12">
                                                                    <label for="buyer_name" class="text-capitalize d-flex justify-content-between">Country</label>
                                                                    <input type="hidden" name="buyer_id" value="{{ $id }}">
                                                                    <select class="form-control" name="countries[]" required>
                                                                        <option selected hidden disabled>SELECT</option>
                                                                        @foreach ($currencies as $currency)
                                                                            <option value="{{ $currency->name }}">{{ $currency->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <span class="invalid-feedback" id="create_buyer_name_error">
                                                                    </span>
                                                                </div>
                                                                <div class="form-group col-6">
                                                                    <label for="buyer_name" class="text-capitalize">Currency</label>
                                                                    <select class="form-control" name="currencies[]" required>
                                                                        <option selected hidden disabled>SELECT</option>
                                                                        @foreach ($currencies as $currency)
                                                                            <option value="{{ $currency->id }}">{{ $currency->name }} | {{ $currency->iso3 }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <span class="invalid-feedback" id="create_buyer_name_error">
                                                                    </span>
                                                                </div>
                                                                <div class="form-group col-6">
                                                                    <label for="buyer_name" class="text-capitalize">Rate</label>
                                                                    <input type="number" name="rates[]" id="" class="form-control" step="any" required>
                                                                    <span class="invalid-feedback" id="create_buyer_name_error">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="create_buyer_status" class="text-capitalize">
                                                                <div class="input-group">
                                                                    <input type="checkbox"
                                                                        name="create_buyer_status me-2"
                                                                        id="create_buyer_status" value="0">Status
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" class="border px-2 btn create"
                                                            style="background-color: #091E3E;color: white">Save</button>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        <!-- /.modal -->
                                    </form>
                                </div>

                                {{--
                            </ul> --}}
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Payment Methods List</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-capitalize">#</th>
                                                <th class="text-capitalize">Payment Method</th>
                                                <th class="text-capitalize">Country</th>
                                                <th class="text-capitalize">Currency</th>
                                                <th class="text-capitalize">Charges</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $counter = 1;
                                            @endphp
                                            @foreach ($buyers as $buyer)
                                                @foreach ($buyer->buyer_payment_methods as $buyer_payment_method)
                                                
                                                    <tr>
                                                        <td>{{ $counter++ }} <input type="hidden" name="db_buyer_id"
                                                                class="db_buyer_id" value="{{ $buyer->id }}">
                                                                <input type="hidden" name="db_buyer_payment_method_id"
                                                                class="db_buyer_payment_method_id" value="{{ $buyer_payment_method->id }}">
                                                        </td>
                                                        <td>{{ $buyer_payment_method['payment_methods']->name }}<input type="hidden" name="" class="db_payment_method_id" value="{{ $buyer_payment_method['payment_methods']->id }}"></td>
                                                        <td>{{ $buyer_payment_method->country }}<input type="hidden" name="" class="db_payment_method_country" value="{{ $buyer_payment_method->country }}"></td>
                                                        <td>{{ $buyer_payment_method['currencies']->iso3 }}<input type="hidden" name="" class="db_c_id" value="{{ $buyer_payment_method['currencies']->id }}"></td>
                                                        <td>{{ $buyer_payment_method->rate }}<input type="hidden" name="" class="db_buyer_payment_method_rate" value="{{ $buyer_payment_method->rate }}"></td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                                                    id="dropdownMenuButton" data-toggle="dropdown"
                                                                    aria-haspopup="true" aria-expanded="false">
                                                                    Action
                                                                </button>
                                                                <div class="dropdown-menu edit_buyer_pay_method"
                                                                    aria-labelledby="dropdownMenuButton">
                                                                    {{-- <a class="dropdown-item" type="button" href="#"
                                                                        data-toggle="modal" data-target="#buyer_pay_method_update">
                                                                        Edit</a> --}}
                                                                        <li>
                                                                            <a class="dropdown-item" type="button" href="#"
                                                                            data-toggle="modal" data-target="#buyer_pay_method_update">
                                                                            Edit</a>
                                                                        </li>
                                                                        <li>
                                                                            <form action="{{route('admin.buyer.pay_method.delete',['id'=>$id])}}"
                                                                                method="POST" class="buyer_pay_method_delete_form">
                                                                                @csrf
                                                                                <input type="hidden" name="id" class="id"
                                                                                    value="{{ $buyer_payment_method->id }}">
                                                                                <button class="dropdown-item delete" type="submit">
                                                                                    Delete
                                                                                </button>
                                                                            </form>
                                                                        </li>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class="text-capitalize">#</th>
                                                <th class="text-capitalize">Payment Method</th>
                                                <th class="text-capitalize">Country</th>
                                                <th class="text-capitalize">Currency</th>
                                                <th class="text-capitalize">Charges</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
    </div>
    <form action="{{route('admin.buyer.pay_method.update',['id'=>$id])}}" method="POST" id="buyer_pay_method_update_form">
        @csrf
        <div class="modal fade" id="buyer_pay_method_update">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Buyer Payment Method</h4>
                        <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="buyer_name" class="text-capitalize">Name</label>
                                <input type="hidden" name="buyer_id" value="{{ $id }}">
                                <input type="hidden" name="id" id="edit_buyer_payment_method_id" value="">
                                <select class="form-control" name="payment_methods" id="edit_payment_method_id" required>
                                    <option selected hidden disabled>SELECT</option>
                                    @foreach ($payment_methods as $payment_method)
                                        <option value="{{ $payment_method->id }}">{{ $payment_method->name }}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback" id="edit_buyer_name_error">
                                </span>
                            </div>
                                <div class="form-group col-12">
                                <label for="buyer_name" class="text-capitalize d-flex justify-content-between">Country</label>
                                <input type="hidden" name="buyer_id" value="{{ $id }}">
                                <select class="form-control" name="countries" id="edit_payment_method_countries" required>
                                    <option selected hidden disabled>SELECT</option>
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency->name }}">{{ $currency->name }}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback" id="create_buyer_name_error">
                                </span>
                            </div>
                            <div class="form-group col-6">
                                <label for="buyer_name" class="text-capitalize">Currency</label>
                                <select class="form-control" name="currencies" id="edit_buyer_payment_method_currency" required>
                                    <option selected hidden disabled>SELECT</option>
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency->id }}">{{ $currency->name }} | {{ $currency->iso3 }}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback" id="edit_buyer_name_error">
                                </span>
                            </div>
                            <div class="form-group col-6">
                                <label for="buyer_name" class="text-capitalize">Rate</label>
                                <input type="number" name="rates" id="edit_buyer_payment_method_rate" class="form-control" step="any" required>
                                <span class="invalid-feedback" id="edit_buyer_name_error">
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_buyer_status" class="text-capitalize">
                                <div class="input-group">
                                    <input type="checkbox"
                                        name="edit_buyer_status me-2"
                                        id="edit_buyer_status" value="0">Status
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default"
                            data-dismiss="modal">Close</button>
                        <button type="submit" class="border px-2 btn update"
                            style="background-color: #091E3E;color: white">Update</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </form>
    <!-- /.modal -->
    @Include('layouts.links.admin.foot')
    @Include('layouts.links.datatable.foot')
    @Include('layouts.links.sweet_alert.foot')
    {{-- @Include('layouts.links.selectpciker.foot') --}}
    <script type="text/javascript">
        $('.edit_buyer_pay_method').on('click', function() {
            var _this = $(this).parents('tr');
            $('#edit_buyer_id').val(_this.find('.db_buyer_id').val());
            $('#edit_buyer_payment_method_id').val(_this.find('.db_buyer_payment_method_id').val());
            $('#edit_payment_method_id').val(_this.find('.db_payment_method_id').val());
            $('#edit_payment_method_countries').val(_this.find('.db_payment_method_country').val());
            $('#edit_buyer_payment_method_currency').val(_this.find('.db_c_id').val());
            $('#edit_buyer_payment_method_rate').val(_this.find('.db_buyer_payment_method_rate').val());
            $('#edit_buyer_status').val(_this.find('.db_buyer_status').val());
            var status = $('#edit_buyer_status').val();
            if (status == 1) {
                $('#edit_buyer_status').prop('checked', true);
            } else {
                $('#edit_buyer_status').prop('checked', false);
            }
        });
        $('.append_button').on('click', function() {
            var _this = $(this).parents('label').parents('div').parents('div');
            $('.currency_row').append('<div class="row"><div class="form-group col-12"><label for="buyer_name" class="text-capitalize d-flex justify-content-between">Name<i class="fas fa-minus rounded-pill bg-danger p-1 minus_button" role="button"></i></label><div class="input-group"><input type="hidden" name="create_buyer_id" id="create_buyer_id" value=""><select class="form-control" name="payment_methods[]" required><option selected hidden disabled>SELECT</option>@foreach ($payment_methods as $payment_method)<option value="{{ $payment_method->id }}">{{ $payment_method->name }}</option>@endforeach</select></div><span class="invalid-feedback" id="create_buyer_name_error"></span></div><div class="form-group col-12"><label for="buyer_name" class="text-capitalize d-flex justify-content-between">Country</label><input type="hidden" name="buyer_id" value="{{ $id }}"><select class="form-control" name="countries[]" required><option selected hidden disabled>SELECT</option>@foreach ($currencies as $currency)<option value="{{ $currency->name }}">{{ $currency->name }}</option>@endforeach</select><span class="invalid-feedback" id="create_buyer_name_error"></span></div><div class="form-group col-6"><label for="buyer_name" class="text-capitalize">Currency</label><select class="form-control" name="currencies[]" required><option selected hidden disabled>SELECT</option>@foreach ($currencies as $currency)<option value="{{ $currency->id }}">{{ $currency->name }} | {{ $currency->iso3 }}</option>@endforeach</select><span class="invalid-feedback" id="create_buyer_name_error"></span></div><div class="form-group col-6"><label for="buyer_name" class="text-capitalize">Rate</label><input type="number" name="rates[]" id="" class="form-control" required><span class="invalid-feedback" id="create_buyer_name_error"></span></div></div>')
        });
        $('body').on('click', '.minus_button', function() {
            var _this = $(this).closest('.row').remove();
        });
        
        $(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            $('#buyer_pay_method_create_form').submit(function(e) {
                    e.preventDefault();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "post",
                        url: $(this).attr('action'),
                        data: $(this).serialize(),
                        success: function(response) {
                            Swal.fire(
                                'Done!',
                                'Inserted Successfully!',
                                'success'
                            ).then((result) => {
                                location.reload();
                            });
                        },
                        error: (error) => {
                            console.log(JSON.stringify(error));
                        }
                    });
                });

            $('#buyer_pay_method_update_form').submit(function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                Swal.fire({
                    title: 'Are you sure?',
                    icon: 'warning',
                    confirmButtonColor: '#e64942',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: `No`,
                }).then((result) => {
                    if (result.isConfirmed) {
                            $.ajax({
                                type: "post",
                                url: $(this).attr('action'),
                                data: $(this).serialize(),
                                success: function(response) {
                                    Swal.fire(
                                    'Done!',
                                    'Updated Successfully!',
                                    'success'
                                    ).then((result) => {
                                        location.reload();
                                    });
                                },
                                error: (error) => {
                                    console.log(JSON.stringify(error));
                                }
                            });
                        }
                    });
            });
            $('.buyer_pay_method_delete_form').submit(function(e) {
                    e.preventDefault();
                    var el = this;
                    var id = $(this).closest("tr").find('.id').val();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    Swal.fire({
                    title: 'Are you sure?',
                    text: "Once Deleted, you will not be able to recover this record!!",
                    icon: 'warning',
                    confirmButtonColor: '#e64942',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: `No`,
                }).then((result) => {
                    if (result.isConfirmed) {
                                $.ajax({
                                    type: "post",
                                    url: $(this).attr('action'),
                                    data: $(this).serialize(),
                                    success: function(response) {
                                        Swal.fire(
                                    'Deleted!',
                                    'Data Successfully Updated.!',
                                    'success'
                                ).then((result) => {
                                    $(el).closest('tr').css(
                                        'background', 'tomato');
                                    $(el).closest('tr').fadeOut(800,
                                        function() {
                                            $(this).remove();
                                        });
                                });
                                    },
                                    error: (error) => {
                                        console.log(JSON.stringify(error));
                                    }
                                });
                            }
                        });
                });
        });

    </script>
    @endsection
</body>

</html>