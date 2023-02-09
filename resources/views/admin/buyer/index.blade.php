<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@Include('layouts.links.admin.title') | Buyers</title>
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
                            <h1 class="m-0">Buyers</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Buyers</li>
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
                                    <a type="button" href="#" data-toggle="modal" data-target="#buyer_create"
                                        class="border px-2 btn" style="background-color: #091E3E;color: white">
                                        Add Buyer
                                    </a>
                                    <!-- Modal -->
                                    <form action="{{ route('admin.buyer.create') }}" method="POST">
                                        @csrf
                                        <div class="modal fade" id="buyer_create">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Buyer</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="buyer_name" class="text-capitalize">Name</label>
                                                            <div class="input-group">
                                                                <input type="hidden" name="create_buyer_id"
                                                                    id="create_buyer_id" value="">
                                                                <input type="text" name="create_buyer_name"
                                                                    id="create_buyer_name" class="form-control">
                                                            </div>
                                                            <span class="invalid-feedback" id="create_buyer_name_error">
                                                            </span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="create_buyer_type" class="text-capitalize">Dealing Type</label>
                                                            <select name="create_buyer_type" id="create_buyer_type" class="form-control" required>
                                                                <option selected hidden disabled>SELECT</option>
                                                                <option value="1">Percentage</option>
                                                                <option value="2">Fixed Amount</option>
                                                            </select>
                                                            <span class="invalid-feedback" id="create_buyer_type_error">
                                                            </span>
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
                                    <h3 class="card-title">Buyers List</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-capitalize">#</th>
                                                <th class="text-capitalize">Name</th>
                                                <th class="text-capitalize">Dealing Type</th>
                                                <th class="text-capitalize">Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $counter = 1;
                                            @endphp
                                            @foreach ($buyers as $buyer)
                                            <tr>
                                                <td>{{ $counter++ }} <input type="hidden" name="db_buyer_id"
                                                        class="db_buyer_id" value="{{ $buyer->id }}">
                                                </td>
                                                <td>{{ $buyer->name }}<input type="hidden" name="db_buyer_name"
                                                        class="db_buyer_name" value="{{ $buyer->name }}"></td>
                                                <td>
                                                    @if ($buyer->type==1)
                                                        <span class="bg-secondary badge"><i class="fa fa-percent" aria-hidden="true"></i> Percentage</span>
                                                    @elseif ($buyer->type==2)
                                                        <span class="bg-secondary badge"><i class="fas fa-sort-amount-down" aria-hidden="true"></i> Fixed Amount</span>
                                                    @endif
                                                    <input type="hidden" name="db_buyer_type"
                                                        class="db_buyer_type" value="{{ $buyer->type }}">
                                                </td>
                                                <td>
                                                    @if ($buyer->status==1)
                                                        <span class="bg-success badge">Active</span>
                                                    @else
                                                        <span class="bg-danger badge">InActive</span>
                                                    @endif
                                                    <input type="hidden" name="db_buyer_status"
                                                        class="db_buyer_status" value="{{ $buyer->status }}">
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                                            id="dropdownMenuButton" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <div class="dropdown-menu edit_buyer"
                                                            aria-labelledby="dropdownMenuButton">
                                                            <li>
                                                                <a class="dropdown-item" type="button" href="#"
                                                                data-toggle="modal" data-target="#buyer_update">
                                                                Edit</a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" type="button" href="{{ route('admin.buyer.pay_method.index', ['id' => $buyer->id]) }}">
                                                                Add Payment Method</a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" type="button" href="{{ route('admin.buyer.report.index', ['id' => $buyer->id]) }}">
                                                                View Report</a>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('admin.buyer.delete') }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="id" class="id"
                                                                        value="{{ $buyer->id }}">
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

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class="text-capitalize">#</th>
                                                <th class="text-capitalize">Name</th>
                                                <th class="text-capitalize">Dealing Type</th>
                                                <th class="text-capitalize">Status</th>
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
    <div class="modal fade" id="buyer_update">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Buyers</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.currencies.edit') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="buyer_name" class="text-capitalize">Name</label>
                            <div class="input-group">
                                <input type="hidden" name="edit_buyer_id"
                                    id="edit_buyer_id" value="">
                                <input type="text" name="edit_buyer_name"
                                    id="edit_buyer_name" class="form-control">
                            </div>
                            <span class="invalid-feedback" id="edit_buyer_name_error">
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="buyer_name" class="text-capitalize">Dealing Type</label>
                            <select name="edit_buyer_type" id="edit_buyer_type" class="form-control" required>
                                <option value="1">Percentage</option>
                                <option value="2">Fixed Amount</option>
                            </select>
                            <span class="invalid-feedback" id="edit_buyer_type_error">
                            </span>
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
    @Include('layouts.links.admin.foot')
    @Include('layouts.links.datatable.foot')
    @Include('layouts.links.sweet_alert.foot')
    <script type="text/javascript">
        $('.edit_buyer').on('click', function() {
            var _this = $(this).parents('tr');
            $('#edit_buyer_id').val(_this.find('.db_buyer_id').val());
            $('#edit_buyer_name').val(_this.find('.db_buyer_name').val());
            $('#edit_buyer_type').val(_this.find('.db_buyer_type').val());
            $('#edit_buyer_status').val(_this.find('.db_buyer_status').val());
            var status = $('#edit_buyer_status').val();
            if (status == 1) {
                $('#edit_buyer_status').prop('checked', true);
            } else {
                $('#edit_buyer_status').prop('checked', false);
            }
        });
        $(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            $('.create').click(function(e) {
                    e.preventDefault();
                    var name = $('#create_buyer_name').val();
                    var type = $('#create_buyer_type').val();
                    
                    if ($('#create_buyer_status').prop('checked')) {
                        var status = 1;
                    } else {
                        var status = 0;
                    }
                    console.log(name);
                    console.log(type);
                    console.log(status);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    if (name != '' && type != '') {
                        $.ajax({
                            type: "post",
                            url: "{{ route('admin.buyer.create') }}",
                            data: {
                                "name": name,
                                "type": type,
                                "status": status,
                            },
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
                    } else {
                        if(name = ''){
                        $('#create_buyer_name_error').show();
                        $('#create_buyer_name_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#create_buyer_name_error').slideUp('slow');
                            $('#create_buyer_name_error').empty();
                        }, 4000);
                    }else if(type = ''){
                        $('#create_buyer_type_error').show();
                        $('#create_buyer_type_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#create_buyer_type_error').slideUp('slow');
                            $('#create_buyer_type_error').empty();
                        }, 4000);
                    }else{}
                    }
                });

            $('.update').click(function(e) {
                e.preventDefault();
                var id = $('#edit_buyer_id').val();
                var name = $('#edit_buyer_name').val();
                var type = $('#edit_buyer_type').val();
                var status = $('#edit_buyer_status').val();
                if ($('#edit_buyer_status').prop('checked')) {
                    var status = 1;
                } else {
                    var status = 0;
                }
                console.log(id);
                console.log(name);
                console.log(type);
                console.log(status);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                if (name != '' && type != '') {
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
                                url: "{{ route('admin.buyer.edit') }}",
                                data: {
                                    "id": id,
                                    "name": name,
                                    "type": type,
                                    "status": status,
                                },
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
                }else {
                    if (name != '') {
                    $('#edit_buyer_name_error').show();
                    $('#edit_buyer_name_error').html(
                        "Required!");
                    window.setInterval(function() {
                        $('#edit_buyer_name_error').slideUp('slow');
                        $('#edit_buyer_name_error').empty();
                    }, 4000);
                }else if (type != '') {}else{
                    $('#edit_buyer_type_error').show();
                    $('#edit_buyer_type_error').html(
                        "Required!");
                    window.setInterval(function() {
                        $('#edit_buyer_type_error').slideUp('slow');
                        $('#edit_buyer_type_error').empty();
                    }, 4000);
                }
                }
            });
            $('.delete').click(function(e) {
                    e.preventDefault();
                    var el = this;
                    var id = $(this).closest("tr").find('.id').val();
                    console.log(id);
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
                                    url: "{{ route('admin.buyer.delete') }}",
                                    data: {
                                        "id": id,
                                    },
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