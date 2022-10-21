<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@Include('layouts.links.admin.title') | Modules</title>
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
    @Include('layouts.links.sweetalert.head')
    {{-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> --}}
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
                                <h1 class="m-0">Modules </h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Modules</li>
                                </ol>
                            </div><!-- /.col -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                {{-- <ul class="nav nav-pills text-center"> --}}
                                <div id="success" class="alert alert-default-success alert-dismissible fade show"
                                    role="alert" style="display: none">
                                    <strong class=""></strong>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 mt-2">
                                    <!-- Button trigger modal -->
                                    <a class="btn btn-primary" type="button" href="#" data-toggle="modal"
                                        data-target="#module_create">
                                        Add Module</a>
                                    <!-- Modal -->
                                    <form action="{{ route('admin.module.create') }}" method="POST"
                                        id="module_create_form">
                                        @csrf
                                        <div class="modal fade" id="module_create">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Module</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="module_name" class="text-capitalize">Name</label>
                                                            <input type="hidden" name="create_module_id"
                                                                id="create_module_id" value="">
                                                            <input type="text" name="create_module_name"
                                                                id="create_module_name" class="form-control">
                                                            <span class="name_status" style="color: #dc3545"></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="module_icon" class="text-capitalize">Icon</label>
                                                            <input type="text" name="create_module_icon"
                                                                id="create_module_icon" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="m_g_id" class="text-capitalize">Module
                                                                Group</label>
                                                            <select id="create_m_g_id" data-live-search="true"
                                                                title="Select" class="selectpicker show-tick form-control"
                                                                name="m_g_id" required>
                                                                <option value="" selected hidden disabled>Select
                                                                </option>
                                                                @foreach ($modules_groups as $modules_group)
                                                                    <option value="{{ $modules_group->id }}">
                                                                        {{ $modules_group->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="create_type" class="text-capitalize">Type</label>
                                                            <select id="create_type" data-live-search="true" title="Select"
                                                                class="selectpicker show-tick form-control"
                                                                name="create_type" required>
                                                                <option value="" selected hidden disabled>Select
                                                                </option>
                                                                <option value="0">SideBar</option>
                                                                <option value="1">Inside Page</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="module_status" class="text-capitalize">
                                                                <input type="checkbox" name="create_module_status me-2"
                                                                    id="create_module_status"
                                                                    value="0">Status</label>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary create"
                                                            id="add_module_btn">Save</button>
                                                    </div>
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
                                        <h3 class="card-title">Modules List</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body datatable_data">
                                        <table id="example2" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Group Name</th>
                                                    <th>Name</th>
                                                    <th>Icon</th>
                                                    <th>Type</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="role_data">
                                                @php
                                                    $counter = 1;
                                                @endphp
                                                @foreach ($modules as $module)
                                                    <tr>
                                                        <td>{{ $counter++ }}<input type="hidden" name="db_module_id"
                                                                class="db_module_id" value="{{ $module->id }}">
                                                        </td>
                                                        <td>{{ $module->modules_group['name'] }}
                                                            <input type="hidden" name="" class="m_g_name"
                                                                value="{{ $module->modules_group['name'] }}">
                                                            <input type="hidden" name="" class="m_g_id"
                                                                value="{{ $module->modules_group['id'] }}">
                                                        </td>
                                                        <td>{{ $module->name }}<input type="hidden" name="db_module_name"
                                                                class="db_module_name" value="{{ $module->name }}"></td>
                                                        <td>{{ $module->icon }}<input type="hidden"
                                                                name="db_module_icon" class="db_module_icon"
                                                                value="{{ $module->icon }}"></td>
                                                        <td>
                                                            @if ($module->type == '1')
                                                                <span class="badge badge-info">Inside Page</span>
                                                                <input type="hidden" name="db_module_type_name"
                                                                    class="db_module_type_name" value="Inside Page">
                                                            @else
                                                                <span class="badge badge-dark">SideBar</span>
                                                                <input type="hidden" name="db_module_type_name"
                                                                    class="db_module_type_name" value="SideBar">
                                                            @endif
                                                            <input type="hidden" name="db_module_type"
                                                                class="db_module_type" value="{{ $module->type }}">
                                                        </td>
                                                        <td>
                                                            @if ($module->status == '1')
                                                                <span class="badge badge-success">Active</span>
                                                            @else
                                                                <span class="badge badge-danger">InActive</span>
                                                            @endif
                                                            <input type="hidden" name="db_module_status"
                                                                class="db_module_status" value="{{ $module->status }}">
                                                        </td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button class="btn btn-secondary dropdown-toggle"
                                                                    type="button" id="dropdownMenuButton"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                    Action
                                                                </button>
                                                                <div class="dropdown-menu edit_module"
                                                                    aria-labelledby="dropdownMenuButton">
                                                                    <li>
                                                                        <a class="dropdown-item" type="button"
                                                                            href="#" data-toggle="modal"
                                                                            data-target="#module_update">
                                                                            Edit</a>
                                                                    </li>
                                                                    <li>
                                                                        <form action="{{ route('admin.module.delete') }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="id"
                                                                                class="id"
                                                                                value="{{ $module->id }}">
                                                                            <button class="dropdown-item delete"
                                                                                type="submit">
                                                                                Delete
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                    <li>
                                                                        <a href="{{ route('admin.module.url.index', [$module->id]) }}"
                                                                            class="dropdown-item
                                                                            edit_module">
                                                                            Create URL
                                                                        </a>
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
                                                    <th>Group Name</th>
                                                    <th>Name</th>
                                                    <th>Icon</th>
                                                    <th>Type</th>
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

        <form action="" method="post" id="module_update_form">
            @csrf
            <div class="modal fade" id="module_update">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Module</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="module_name" class="text-capitalize">Name</label>
                                <input type="hidden" name="module_id" id="module_id" value="">
                                <input type="text" name="module_name" id="module_name" class="form-control">
                                <span class="edit_name_status" style="color: #dc3545"></span>
                            </div>
                            <div class="form-group">
                                <label for="module_icon" class="text-capitalize">Icon</label>
                                <input type="text" name="module_icon" id="module_icon" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="m_g_id" class="text-capitalize">Module Group</label>
                                <select id="m_g_id" data-live-search="true" title="Select"
                                    class="selectpicker show-tick form-control" name="m_g_id" required>
                                    <option id="edit_m_g_id" selected hidden value="">
                                        @foreach ($modules_groups as $modules_group)
                                    <option value="{{ $modules_group->id }}">
                                        {{ $modules_group->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="module_type" class="text-capitalize">Type</label>
                                <select id="module_type" data-live-search="true" title="Select"
                                    class="selectpicker show-tick form-control" name="module_type" required>
                                    <option id="edit_module_type" selected hidden value="">
                                    <option value="0">SideBar</option>
                                    <option value="1">Inside Page</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="module_status" class="text-capitalize">
                                    <input type="checkbox" name="module_status me-2" id="module_status"
                                        value="0">Status</label>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary update" id="">Update</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </form>

        @Include('layouts.links.datatable.foot')

        <script type="text/javascript">
            $(function() {
                $('#module_status').click(function() {
                    if ($(this).is(':checked')) {
                        $(this).attr("checked", true)
                        $(this).val(this.checked ? 1 : 0);
                    } else {
                        $(this).attr('checked', false);
                        $(this).val(this.checked ? 1 : 0);
                    }
                });
                $('#create_module_status').click(function() {
                    if ($(this).is(':checked')) {
                        $(this).attr("checked", true)
                        $(this).val(this.checked ? 1 : 0);
                    } else {
                        $(this).attr('checked', false);
                        $(this).val(this.checked ? 1 : 0);
                    }
                });
            });
            $('.edit_module').on('click', function() {
                var _this = $(this).parents('tr');
                $('#module_id').val(_this.find('.db_module_id').val());
                $('#module_name').val(_this.find('.db_module_name').val());
                $('#module_icon').val(_this.find('.db_module_icon').val());
                $('#module_status').val(_this.find('.db_module_status').val());
                $('#edit_m_g_id').text(_this.find('.m_g_name').val());
                $('#edit_m_g_id').val(_this.find('.m_g_id').val());
                $('#edit_module_type').text(_this.find('.db_module_type_name').val());
                $('#edit_module_type').val(_this.find('.db_module_type').val());


                var status = $('#module_status').val();
                if (status == 1) {
                    $('#module_status').prop('checked', true);
                } else {
                    $('#module_status').prop('checked', false);
                }
            });
            $(document).ready(function() {
                $('.create').click(function(e) {
                    e.preventDefault();
                    var name = $('#create_module_name').val();
                    var icon = $('#create_module_icon').val();
                    var m_g_id = $('#create_m_g_id').val();
                    var status = $('#create_module_status').val();
                    var type = $('#create_type').val();

                    console.log(name);
                    console.log(icon);
                    console.log(m_g_id);
                    console.log(type);
                    console.log(status);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: "post",
                        url: "{{ route('admin.module.create') }}",
                        data: {
                            "name": name,
                            "icon": icon,
                            "m_g_id": m_g_id,
                            "status": status,
                            "type": type,
                        },
                        success: function(response) {
                            location.reload();
                        },
                        error: (error) => {
                            console.log(JSON.stringify(error));
                        }
                    });
                });
                $('.update').click(function(e) {
                    e.preventDefault();
                    var id = $('#module_id').val();
                    var name = $('#module_name').val();
                    var m_g_id = $('#m_g_id').val();
                    var icon = $('#module_icon').val();
                    var status = $('#module_status').val();
                    var type = $('#module_type').val();

                    console.log(id);
                    console.log(name);
                    console.log(icon);
                    console.log(m_g_id);
                    console.log(status);
                    console.log(type);
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
                                    url: "{{ route('admin.module.edit') }}",
                                    data: {
                                        "id": id,
                                        "name": name,
                                        "m_g_id": m_g_id,
                                        "icon": icon,
                                        "status": status,
                                        "type": type,
                                    },
                                    success: function(response) {
                                        swal("Data Successfully Updated.!", {
                                            icon: "success",
                                        }).then((result) => {
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
                                    url: "{{ route('admin.module.delete') }}",
                                    data: {
                                        "id": id,
                                    },
                                    success: function(response) {
                                        swal("Data successfully Deleted.!", {
                                            icon: "success",
                                        }).then((result) => {
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
                $('#create_m_g_id').change(function() {
                    var name = $('#create_module_name').val();
                    var m_g_id = $('#create_m_g_id').val();
                    console.log(name);
                    console.log(m_g_id);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    if (name) {
                        $.ajax({
                            type: "get",
                            url: "{{ route('admin.module.create') }}",
                            data: {
                                "name": name,
                                "m_g_id": m_g_id,
                            },
                            success: function(response) {
                                $('.name_status').html(response);
                                if (response == "OK") {
                                    $('.name_status').html(
                                        '<span class="text-success">Module Name Available</span>'
                                    );
                                    $('#add_module_btn').attr('disabled', false);
                                    $('#name').removeClass('has-error');
                                    setTimeout(function() {
                                        $('.name_status').fadeIn('slow');
                                    }, 1000);
                                    setTimeout(function() {
                                        $('.name_status').fadeOut('slow');
                                    }, 5000);
                                    console.log(response);
                                } else {
                                    $('.name_status').html(
                                        '<span class="text-danger">Module Name Already Exist</span>'
                                    );
                                    $('#add_module_btn').attr('disabled', true);
                                    setTimeout(function() {
                                        $('.name_status').fadeIn('slow');
                                    }, 1000);
                                    setTimeout(function() {
                                        $('.name_status').fadeOut('slow');
                                    }, 5000);
                                    console.log(response);

                                }
                            },
                            error: (error) => {
                                console.log(JSON.stringify(error));
                            }
                        });
                    } else {
                        $('#add_module_btn').attr('disabled', true);
                        $('#edit_module_btn').attr('disabled', true);
                    }
                });
                $('#m_g_id').change(function() {
                    var id = $('#module_id').val();
                    var m_g_id = $('#m_g_id').val();

                    var m_name = $('#module_name').val();
                    console.log(id);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    if (id) {
                        $.ajax({
                            type: "GET",
                            url: "{{ route('admin.module.edit') }}",
                            data: {
                                "name": m_name,
                                "m_g_id": m_g_id,
                            },
                            success: function(response) {
                                $('.edit_name_status').html(response);
                                if (response == "OK") {
                                    $('.edit_name_status').html(
                                        '<span class="text-success">Module Name Available</span>'
                                    );
                                    $('#edit_module_btn').attr('disabled', false);
                                    setTimeout(function() {
                                        $('.edit_name_status').fadeIn('slow');
                                    }, 1000);
                                    setTimeout(function() {
                                        $('.edit_name_status').fadeOut('slow');
                                    }, 5000);
                                } else {
                                    $('.edit_name_status').html(
                                        '<span class="text-danger">Module Name Already Exist</span>'
                                    );
                                    $('#edit_module_btn').attr('disabled', true);
                                    setTimeout(function() {
                                        $('.edit_name_status').fadeIn('slow');
                                    }, 1000);
                                    setTimeout(function() {
                                        $('.edit_name_status').fadeOut('slow');
                                    }, 5000);
                                }
                            },
                            error: (error) => {
                                console.log(JSON.stringify(error));
                            }
                        });
                    } else {
                        $('#add_module_group_btn').attr('disabled', true);
                        $('#edit_module_group_btn').attr('disabled', true);
                    }
                });
            });
        </script>
    @endsection
    {{-- @Include('layouts.links.modals.foot') --}}
</body>

</html>
