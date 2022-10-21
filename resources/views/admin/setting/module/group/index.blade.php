<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@Include('layouts.links.admin.title') | Modules Groups</title>
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
                                <h1 class="m-0">Modules Groups</h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Modules Groups</li>
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
                                    <a class="btn btn-primary" type="button" href="#" data-toggle="modal"
                                        data-target="#module_group_create">
                                        Add Module Group</a>
                                    <!-- Modal -->
                                    <form action="{{ route('admin.modules_groups.create') }}" method="POST">
                                        @csrf
                                        <div class="modal fade" id="module_group_create">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Module Group</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="" method="post">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="module_group_name"
                                                                    class="text-capitalize">Name</label>
                                                                <input type="hidden" name="create_module_group_id"
                                                                    id="create_module_group_id" value="">
                                                                <input type="text" name="create_module_group_name"
                                                                    id="create_module_group_name" class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="module_group_icon"
                                                                    class="text-capitalize">Icon</label>
                                                                <input type="text" name="create_module_group_icon"
                                                                    id="create_module_group_icon" class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="module_group_status" class="text-capitalize">
                                                                    <input type="checkbox"
                                                                        name="create_module_group_status me-2"
                                                                        id="create_module_group_status"
                                                                        value="0">Status</label>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">Close</button>
                                                            <button type="submit"
                                                                class="btn btn-primary create">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        <!-- /.modal -->
                                    </form>
                                </div>

                                {{-- </ul> --}}
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
                                        <h3 class="card-title">Modules Groups List</h3>

                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body datatable_data">
                                        <table id="example2" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Icon</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="role_data">
                                                @php
                                                    $counter = 1;
                                                @endphp
                                                @foreach ($modules_groups as $module_group)
                                                    <tr>
                                                        <td>{{ $counter++ }}<input type="hidden"
                                                                name="db_module_group_id" class="db_module_group_id"
                                                                value="{{ $module_group->id }}">
                                                        </td>
                                                        <td>{{ $module_group->name }}<input type="hidden"
                                                                name="db_module_group_name" class="db_module_group_name"
                                                                value="{{ $module_group->name }}"></td>
                                                        <td>{{ $module_group->icon }}<input type="hidden"
                                                                name="db_module_group_icon" class="db_module_group_icon"
                                                                value="{{ $module_group->icon }}"></td>
                                                        <td>
                                                            @if ($module_group->status == '1')
                                                                <span class="badge badge-success">Active</span>
                                                            @else
                                                                <span class="badge badge-danger">InActive</span>
                                                            @endif
                                                            <input type="hidden" name="db_module_group_status"
                                                                class="db_module_group_status"
                                                                value="{{ $module_group->status }}">
                                                        </td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button class="btn btn-secondary dropdown-toggle"
                                                                    type="button" id="dropdownMenuButton"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                    Action
                                                                </button>
                                                                <div class="dropdown-menu edit_module_group"
                                                                    aria-labelledby="dropdownMenuButton">
                                                                    <a class="dropdown-item" type="button"
                                                                        href="#" data-toggle="modal"
                                                                        data-target="#module_group_update">
                                                                        Edit</a>
                                                                    <li>
                                                                        <form
                                                                            action="{{ route('admin.modules_groups.delete') }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="id"
                                                                                class="id"
                                                                                value="{{ $module_group->id }}">
                                                                            <button class="dropdown-item delete"
                                                                                type="submit">
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
                                                    <th>Icon</th>
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
        <div class="modal fade" id="module_group_update">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Module Group</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="module_group_name" class="text-capitalize">Name</label>
                                <input type="hidden" name="module_group_id" id="module_group_id" value="">
                                <input type="text" name="module_group_name" id="module_group_name"
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="module_group_icon" class="text-capitalize">Icon</label>
                                <input type="text" name="module_group_icon" id="module_group_icon"
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="module_group_status" class="text-capitalize">
                                    <input type="checkbox" name="module_group_status me-2"
                                        id="module_group_status">Status</label>
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
        @Include('layouts.links.datatable.foot')

        <script type="text/javascript">
            $(function() {
                $('#module_group_status').click(function() {
                    if ($(this).is(':checked')) {
                        $(this).attr("checked", true)
                        $(this).val(this.checked ? 1 : 0);
                    } else {
                        $(this).attr('checked', false);
                        $(this).val(this.checked ? 1 : 0);
                    }
                });
                $('#create_module_group_status').click(function() {
                    if ($(this).is(':checked')) {
                        $(this).attr("checked", true)
                        $(this).val(this.checked ? 1 : 0);
                    } else {
                        $(this).attr('checked', false);
                        $(this).val(this.checked ? 1 : 0);
                    }
                });
            });
            $('.edit_module_group').on('click', function() {
                var _this = $(this).parents('tr');
                $('#module_group_id').val(_this.find('.db_module_group_id').val());
                $('#module_group_name').val(_this.find('.db_module_group_name').val());
                $('#module_group_icon').val(_this.find('.db_module_group_icon').val());
                $('#module_group_status').val(_this.find('.db_module_group_status').val());
                var status = $('#module_group_status').val();
                if (status == 1) {
                    $('#module_group_status').prop('checked', true);
                } else {
                    $('#module_group_status').prop('checked', false);
                }
            });
            $(document).ready(function() {
                $('.create').click(function(e) {
                    e.preventDefault();
                    var name = $('#create_module_group_name').val();
                    var icon = $('#create_module_group_icon').val();
                    var status = $('#create_module_group_status').val();

                    console.log(name);
                    console.log(icon);
                    console.log(status);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: "post",
                        url: "{{ route('admin.modules_groups.create') }}",
                        data: {
                            "name": name,
                            "icon": icon,
                            "status": status,
                        },
                        success: function(response) {
                            $('#module_group_create').modal('hide');
                            $('#success').show();
                            $('#success strong').html("Inserted Successfully");
                            $("#example2 tbody").load(
                                location
                                .href +
                                "#example2 tbody tr");
                            window.setInterval(function() {
                                $('#success').slideUp('slow');
                                $('#success').empty();
                            }, 4000);
                        },
                        error: (error) => {
                            console.log(JSON.stringify(error));
                        }
                    });
                });
                $('.update').click(function(e) {
                    e.preventDefault();
                    var id = $('#module_group_id').val();
                    var name = $('#module_group_name').val();
                    var icon = $('#module_group_icon').val();
                    var status = $('#module_group_status').val();

                    console.log(id);
                    console.log(name);
                    console.log(icon);
                    console.log(status);
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
                                    url: "{{ route('admin.modules_groups.edit') }}",
                                    data: {
                                        "id": id,
                                        "name": name,
                                        "icon": icon,
                                        "status": status,
                                    },
                                    success: function(response) {
                                        swal("Data Successfully Updated.!", {
                                            icon: "success",
                                        }).then((result) => {

                                            $('#module_group_update').hide();
                                            $('#success').show();
                                            $('#success strong').html(
                                                "Updated Successfully");
                                            $('#module_group_update').modal('hide');
                                            $("#example2 tbody").load(
                                                location
                                                .href +
                                                "#example2 tbody tr");
                                            window.setInterval(function() {
                                                $('#success').slideUp('slow');
                                                $('#success').empty();
                                            }, 4000);
                                        });
                                    },

                                    error: (error) => {
                                        console.log(JSON.stringify(error));
                                    }
                                });
                            }
                        });

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
                    swal({
                            title: "Are you sure?",
                            text: "Once deleted, you will not be able to recover this imaginary file!",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((willDelete) => {
                            if (willDelete) {
                                $.ajax({
                                    type: "post",
                                    url: "{{ route('admin.modules_groups.delete') }}",
                                    data: {
                                        "id": id,
                                    },
                                    success: function(response) {
                                        $('#success').html(
                                            "Deleted Successfully");
                                        $('#success').css('color', 'red');
                                        $('#success').show();
                                        swal("Data successfully Deleted.!", {
                                            icon: "success",
                                        }).then((result) => {
                                            $('#success').html(
                                                "Deleted Successfully");
                                            $('#success').css('color', 'red');
                                            $('#success').show();
                                            $(el).closest('tr').css(
                                                'background', 'tomato');
                                            $(el).closest('tr').fadeOut(800,
                                                function() {
                                                    $(this).remove();
                                                    $('#success')
                                                        .slideUp(
                                                            'slow');
                                                    $('#success')
                                                        .empty();
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
    {{-- @Include('layouts.links.modals.foot') --}}
</body>

</html>
