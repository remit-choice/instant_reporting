<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@Include('layouts.links.admin.title') | Roles</title>
    <style>
        .flex-wrap {
            float: right !important;
        }

        .cur-role>td {
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
                            <h1 class="m-0">Roles</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Roles</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            {{-- <ul class="nav nav-pills text-center"> --}}
                                <div id="success" class="alert alert-default-success alert-dismissible fade show"
                                    role="alert" style="display: none">
                                    <strong class="">{{ session('success') }}</strong>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 mt-2">
                                    <!-- Button trigger modal -->
                                    <a type="button" href="#" data-toggle="modal" data-target="#role_create"
                                        class="border px-2 btn" style="background-color: #091E3E;color: white">
                                        Add Role
                                    </a>
                                    <!-- Modal -->
                                    <form action="{{ route('admin.role.create') }}" method="POST">
                                        @csrf
                                        <div class="modal fade" id="role_create">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Role</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="role_name" class="text-capitalize">Name</label>
                                                            <div class="input-group">
                                                                <input type="hidden" name="create_role_id"
                                                                    id="create_role_id" value="">
                                                                <input type="text" name="create_role_name"
                                                                    id="create_role_name" class="form-control">
                                                            </div>
                                                            <span class="invalid-feedback" id="create_role_name_error">
                                                            </span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="create_role_status" class="text-capitalize">
                                                                <div class="input-group">
                                                                    <input type="checkbox"
                                                                        name="create_role_status me-2"
                                                                        id="create_role_status" value="0">Status
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
                                    <h3 class="card-title">Roles List</h3>

                                </div>
                                <!-- /.card-header -->
                                <div class="card-body datatable_data">
                                    <table id="example2" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="role_data">
                                            @php
                                            $counter = 1;
                                            @endphp
                                            @foreach ($roles as $role)
                                            <tr>
                                                <td>{{ $counter++ }}<input type="hidden" name="db_role_id"
                                                        class="db_role_id" value="{{ $role->id }}">
                                                </td>
                                                <td>{{ $role->name }}<input type="hidden" name="db_role_name"
                                                        class="db_role_name" value="{{ $role->name }}"></td>
                                                <td>
                                                    @if ($role->status == '1')
                                                    <span class="badge badge-success">Active</span>
                                                    @else
                                                    <span class="badge badge-danger">InActive</span>
                                                    @endif
                                                    <input type="hidden" name="db_role_status" class="db_role_status"
                                                        value="{{ $role->status }}">
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                                            id="dropdownMenuButton" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <div class="dropdown-menu edit_role"
                                                            aria-labelledby="dropdownMenuButton">
                                                            <li>
                                                                <a class="dropdown-item" type="button" href="#"
                                                                    data-toggle="modal" data-target="#role_update">
                                                                    Edit</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('admin.role.permission.create', ['id' => $role->id]) }}"
                                                                    class="dropdown-item">Create
                                                                    Permissions
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('admin.role.permission.edit', ['id' => $role->id]) }}"
                                                                    class="dropdown-item">Change
                                                                    Module
                                                                    Permissions
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('admin.role.delete') }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="id" class="id"
                                                                        value="{{ $role->id }}">
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
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Status</th>
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
    <div class="modal fade" id="role_update">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Role</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="role_name" class="text-capitalize">Name</label>
                            <div class="input-group">
                                <input type="hidden" name="role_id" id="role_id" value="">
                                <input type="text" name="role_name" id="role_name" class="form-control">
                            </div>
                            <span class="invalid-feedback" id="role_name_error">
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="role_status" class="text-capitalize">
                                <div class="input-group">
                                    <input type="checkbox" name="role_status me-2" id="role_status" value="0">Status
                                </div>
                            </label>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="border px-2 btn update"
                                style="background-color: #091E3E;color: white">Update</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    @Include('layouts.links.admin.foot')
    @Include('layouts.links.datatable.foot')
    @Include('layouts.links.sweet_alert.foot')
    <script type="text/javascript">
        $('#role_status').click(function() {
                    if ($(this).is(':checked')) {
                        $(this).attr("checked", true)
                        $(this).val(this.checked ? 1 : 0);
                    } else {
                        $(this).attr('checked', false);
                        $(this).val(this.checked ? 1 : 0);
                    }
                });
                $('#create_role_status').click(function() {
                    if ($(this).is(':checked')) {
                        $(this).attr("checked", true)
                        $(this).val(this.checked ? 1 : 0);
                    } else {
                        $(this).attr('checked', false);
                        $(this).val(this.checked ? 1 : 0);
                    }
                });
            $('.edit_role').on('click', function() {
                var _this = $(this).parents('tr');
                $('#role_id').val(_this.find('.db_role_id').val());
                $('#role_name').val(_this.find('.db_role_name').val());
                $('#role_status').val(_this.find('.db_role_status').val());
                var status = $('#role_status').val();
                if (status == 1) {
                    $('#role_status').prop('checked', true);
                } else {
                    $('#role_status').prop('checked', false);
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
                    var name = $('#create_role_name').val();
                    var status = $('#create_role_status').val();

                    console.log(name);
                    console.log(status);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    if (name != '') {
                    $.ajax({
                        type: "post",
                        url: "{{ route('admin.role.create') }}",
                        data: {
                            "name": name,
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
                }else {
                        $('#create_role_name_error').show();
                        $('#create_role_name_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#create_role_name_error').slideUp('slow');
                            $('#create_role_name_error').empty();
                        }, 4000);
                }
                });
                $('.update').click(function(e) {
                    e.preventDefault();
                    var id = $('#role_id').val();
                    var name = $('#role_name').val();
                    var status = $('#role_status').val();

                    console.log(id);
                    console.log(name);
                    console.log(status);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    if (name != '') {
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
                                    url: "{{ route('admin.role.edit') }}",
                                    data: {
                                        "id": id,
                                        "name": name,
                                        "status": status,
                                    },
                                    success: function(response) {
                                        Swal.fire(
                                        'Updated!',
                                        'Data Successfully Updated.!',
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
                        $('#role_name_error').show();
                        $('#role_name_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#role_name_error').slideUp('slow');
                            $('#role_name_error').empty();
                        }, 4000);
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
                                    url: "{{ route('admin.role.delete') }}",
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