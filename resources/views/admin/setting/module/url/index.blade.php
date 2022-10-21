<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@Include('layouts.links.admin.title') | Modules URL</title>
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
                                <h1 class="m-0">Modules URL</h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Modules URL</li>
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
                                        data-target="#module_url_create">
                                        Add Module URL</a>
                                    <!-- Modal -->
                                    <form action="" method="POST" id="module_url_create_form">
                                        @csrf
                                        <div class="modal fade" id="module_url_create">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Module URL</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="" method="post">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="module_name" class="text-capitalize">URL</label>
                                                                <input type="hidden" name="create_module_id"
                                                                    id="create_module_id"
                                                                    value="{{ Session::get('m_id') }}">
                                                                <input type="hidden" name="create_module_url_id"
                                                                    id="create_module_url_id" value="">
                                                                <input type="text" name="create_module_url_name"
                                                                    id="create_module_url_name" class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="create_type"
                                                                    class="text-capitalize">Type</label>
                                                                <select id="create_type" data-live-search="true"
                                                                    title="Select"
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
                                                                    <input type="checkbox"
                                                                        name="create_module_url_status me-2"
                                                                        id="create_module_url_status"
                                                                        value="0">Status</label>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary create"
                                                                id="add_module_btn">Save</button>
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
                                        <h3 class="card-title">Modules List</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body datatable_data">
                                        <table id="example2" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Module Name</th>
                                                    <th>Route Name</th>
                                                    <th>URL</th>
                                                    <th>Type</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="role_data">
                                                @php
                                                    $counter = 1;
                                                @endphp
                                                @foreach ($modules_urls as $modules_url)
                                                    <tr>
                                                        <td>{{ $counter++ }}<input type="hidden" name="db_module_id"
                                                                class="db_module_id"
                                                                value="{{ $modules_url->modules['id'] }}">
                                                            <input type="hidden" name="db_module_url_id"
                                                                class="db_module_url_id" value="{{ $modules_url->id }}">
                                                        </td>
                                                        <td>
                                                            {{ $modules_url->modules['name'] }}
                                                            <input type="hidden" name="db_module_name"
                                                                class="db_module_name"
                                                                value="{{ $modules_url->modules['name'] }}">
                                                        </td>
                                                        <td>
                                                            {{ $modules_url->name }}
                                                            <input type="hidden" name="db_module_url"
                                                                class="db_module_url" value="{{ $modules_url->name }}">

                                                        </td>
                                                        <td>
                                                            {{ $modules_url->url }}
                                                        </td>
                                                        <td>
                                                            @if ($modules_url->type == '1')
                                                                <span class="badge badge-info">Inside Page</span>
                                                                <input type="hidden" name="db_module_type_name"
                                                                    class="db_module_type_name" value="Inside Page">
                                                            @else
                                                                <span class="badge badge-dark">SideBar</span>
                                                                <input type="hidden" name="db_module_type_name"
                                                                    class="db_module_type_name" value="SideBar">
                                                            @endif
                                                            <input type="hidden" name="db_module_type"
                                                                class="db_module_type" value="{{ $modules_url->type }}">
                                                        </td>
                                                        <td>
                                                            @if ($modules_url->status == '1')
                                                                <span class="badge badge-success">Active</span>
                                                            @else
                                                                <span class="badge badge-danger">InActive</span>
                                                            @endif
                                                            <input type="hidden" name="db_module_url_status"
                                                                class="db_module_url_status"
                                                                value="{{ $modules_url->status }}">
                                                        </td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button class="btn btn-secondary dropdown-toggle"
                                                                    type="button" id="dropdownMenuButton"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                    Action
                                                                </button>
                                                                <div class="dropdown-menu edit_module_url"
                                                                    aria-labelledby="dropdownMenuButton">
                                                                    <li>
                                                                        <a class="dropdown-item" type="button"
                                                                            href="#" data-toggle="modal"
                                                                            data-target="#module_url_update">
                                                                            Edit</a>
                                                                    </li>
                                                                    <li>
                                                                        <form
                                                                            action="/admin/setting/module/{{ $modules_url->id }}/url/delete"
                                                                            method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="id"
                                                                                class="id"
                                                                                value="{{ $modules_url->id }}">
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
                                                    <th>Module Name</th>
                                                    <th>Route Name</th>
                                                    <th>URL</th>
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
        <div class="modal fade" id="module_url_update">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Module</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method="post" id="module_url_update_form">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="module_url_id" class="text-capitalize">URL</label>
                                <input type="hidden" name="module_url_id" id="module_url_id" value="">
                                <input type="text" name="module_url" id="module_url" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="module_id" class="text-capitalize">Module</label>
                                <input type="hidden" name="module_id" id="module_id" value="">
                                <input type="text" name="module_name" id="module_name" class="form-control" readonly>
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
                                <label for="module_url_status" class="text-capitalize">
                                    <input type="checkbox" name="module_url_status me-2" id="module_url_status"
                                        value="0">Status</label>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary update" id="">Update</button>
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
                $('#module_url_status').click(function() {
                    if ($(this).is(':checked')) {
                        $(this).attr("checked", true)
                        $(this).val(this.checked ? 1 : 0);
                    } else {
                        $(this).attr('checked', false);
                        $(this).val(this.checked ? 1 : 0);
                    }
                });
                $('#create_module_url_status').click(function() {
                    if ($(this).is(':checked')) {
                        $(this).attr("checked", true)
                        $(this).val(this.checked ? 1 : 0);
                    } else {
                        $(this).attr('checked', false);
                        $(this).val(this.checked ? 1 : 0);
                    }
                });
            });
            $('.edit_module_url').on('click', function() {
                var _this = $(this).parents('tr');
                $('#module_id').val(_this.find('.db_module_id').val());
                $('#module_name').val(_this.find('.db_module_name').val());
                $('#module_url_id').val(_this.find('.db_module_url_id').val());
                $('#module_url').val(_this.find('.db_module_url').val());
                $('#module_url_status').val(_this.find('.db_module_url_status').val());
                $('#edit_module_type').text(_this.find('.db_module_type_name').val());
                $('#edit_module_type').val(_this.find('.db_module_type').val());


                var status = $('#module_url_status').val();
                if (status == 1) {
                    $('#module_url_status').prop('checked', true);
                } else {
                    $('#module_url_status').prop('checked', false);
                }
            });
            $(document).ready(function() {
                $('.create').click(function(e) {
                    e.preventDefault();
                    var url = $('#create_module_url_name').val();
                    var m_id = $('#create_module_id').val();
                    var status = $('#create_module_url_status').val();
                    var type = $('#create_type').val();
                    var urls = "{{ route('admin.module.url.create', 'id') }}";
                    urls = urls.replace('id', m_id);
                    console.log(url);
                    console.log(m_id);
                    console.log(status);
                    console.log(type);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "post",
                        url: urls,
                        data: {
                            "url": url,
                            "m_id": m_id,
                            "type": type,
                            "status": status,
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
                    var m_id = $('#module_id').val();
                    var id = $('#module_url_id').val();
                    var url = $('#module_url').val();
                    var status = $('#module_url_status').val();
                    var type = $('#module_type').val();



                    var urls = "{{ route('admin.module.url.edit', 'id') }}";
                    urls = urls.replace('id', m_id);
                    console.log(id);
                    console.log(m_id);
                    console.log(url);
                    console.log(urls);
                    console.log(type);
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
                                    url: urls,
                                    data: {
                                        "id": id,
                                        "m_id": m_id,
                                        "url": url,
                                        "type": type,
                                        "status": status,
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
                                    url: "/admin/setting/module/" + id +
                                        "/url/delete",
                                    data: {
                                        "id": id,
                                    },
                                    success: function(response) {
                                        swal("Data successfully Deleted.!", {
                                            icon: "success",
                                        }).then((result) => {
                                            $(el).closest('tr').css(
                                                'background',
                                                'tomato');
                                            $(el).closest('tr').fadeOut(
                                                800,
                                                function() {
                                                    $(this)
                                                        .remove();
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
