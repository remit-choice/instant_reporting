<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@Include('layouts.links.admin.title') | Transaction</title>
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
    @Include('layouts.favicon')
    @Include('layouts.links.admin.head')
    @Include('layouts.links.datatable.head')
    {{-- @Include('layouts.links.modals.head') --}}
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>


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
                                <h1 class="m-0">Currencies</h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Currencies</li>
                                </ol>
                            </div><!-- /.col -->
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
                                        <h3 class="card-title">Currencies List</h3>

                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-capitalize">#</th>
                                                    <th class="text-capitalize">name</th>
                                                    <th class="text-capitalize">iso</th>
                                                    <th class="text-capitalize">iso3</th>
                                                    <th class="text-capitalize">dial</th>
                                                    <th class="text-capitalize">currency</th>
                                                    <th class="text-capitalize">currency_name</th>
                                                    <th class="text-capitalize">min rate</th>
                                                    <th class="text-capitalize">max rate</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $counter = 1;
                                                @endphp
                                                @foreach ($currencies as $currency)
                                                    <tr>
                                                        <td>{{ $counter++ }} <input type="hidden" name="db_currency_id"
                                                                class="db_currency_id" value="{{ $currency->id }}">
                                                        </td>
                                                        <td>{{ $currency->name }}</td>
                                                        <td>{{ $currency->iso }}</td>
                                                        <td>{{ $currency->iso3 }}</td>
                                                        <td>{{ $currency->dial }}</td>
                                                        <td>{{ $currency->currency }}</td>
                                                        <td>{{ $currency->currency_name }}</td>
                                                        <td id="min_rate{{ $currency->id }}">
                                                            {{ $currency->min_rate }} <input type="hidden"
                                                                name="db_currency_id" class="db_currency_min_rate"
                                                                value="{{ $currency->min_rate }}"></td>
                                                        <td id="max_rate{{ $currency->id }}">
                                                            {{ $currency->max_rate }} <input type="hidden"
                                                                name="db_currency_id" class="db_currency_max_rate"
                                                                value="{{ $currency->max_rate }}"></td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button class="btn btn-secondary dropdown-toggle"
                                                                    type="button" id="dropdownMenuButton"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                    Action
                                                                </button>
                                                                <div class="dropdown-menu edit_currency"
                                                                    aria-labelledby="dropdownMenuButton">
                                                                    <a class="dropdown-item" type="button" href="#"
                                                                        data-toggle="modal" data-target="#currency_update">
                                                                        Edit</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="text-capitalize">#</th>
                                                    <th class="text-capitalize">name</th>
                                                    <th class="text-capitalize">iso</th>
                                                    <th class="text-capitalize">iso3</th>
                                                    <th class="text-capitalize">dial</th>
                                                    <th class="text-capitalize">currency</th>
                                                    <th class="text-capitalize">currency_name</th>
                                                    <th class="text-capitalize">min rate</th>
                                                    <th class="text-capitalize">max rate</th>
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
        <div class="modal fade" id="currency_update">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Currency</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.currencies.update') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="currency_id" id="currency_id" value="">
                            <div class="form-group row">
                                <div class="col-6">
                                    <label for="currency_rate" class="text-capitalize">min rate</label>
                                    <input type="text" name="currency_rate" id="currency_min_rate"
                                        class="form-control">
                                </div>
                                <div class="col-6">
                                    <label for="currency_rate" class="text-capitalize">max rate</label>
                                    <input type="text" name="currency_rate" id="currency_max_rate"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary update">Update</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <script type="text/javascript">
            $('.edit_currency').on('click', function() {
                var _this = $(this).parents('tr');
                $('#currency_min_rate').val(_this.find('.db_currency_min_rate').val());
                $('#currency_max_rate').val(_this.find('.db_currency_max_rate').val());
                $('#currency_id').val(_this.find('.db_currency_id').val());
            });
            $(document).ready(function() {
                $('.update').click(function(e) {
                    e.preventDefault();
                    var id = $('#currency_id').val();
                    var min_rate = $('#currency_min_rate').val();
                    var max_rate = $('#currency_max_rate').val();

                    console.log(id);
                    console.log(min_rate);
                    console.log(max_rate);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    swal({
                            title: "Are you sure?",
                            text: "Once Update, you will not be able to recover this imaginary rate!",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((willDelete) => {
                            if (willDelete) {

                                $.ajax({
                                    type: "post",
                                    url: "{{ route('admin.currencies.update') }}",
                                    data: {
                                        "id": id,
                                        "min_rate": min_rate,
                                        "max_rate": max_rate,
                                    },
                                    success: function(response) {
                                        if (response == '1') {
                                            $('#currency_update').modal('hide');
                                            $("#min_rate" + id).load(location.href +
                                                " #min_rate" + id);
                                            $("#min_rate" + id).addClass('cur-rate td');
                                            $("#max_rate" + id).load(location.href +
                                                " #max_rate" + id);
                                            $("#max_rate" + id).addClass('cur-rate td');
                                        }
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
    @Include('layouts.links.datatable.foot')
    {{-- @Include('layouts.links.modals.foot') --}}
</body>

</html>
