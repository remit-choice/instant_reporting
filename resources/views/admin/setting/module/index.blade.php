@extends('layouts.admin.master')
@section('content')
@section('links_content_head')
    @Include('layouts.links.toastr.head')
@endsection
@section('content_1')
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 mt-2">
        <!-- Button trigger modal -->
        @if ($create == 1)
            <a type="button" href="#" data-toggle="modal" data-target="#create_modal" class="border px-3 btn"
                style="background-color: #091E3E;color: white">
                Add
            </a>
        @endif
    </div>
@endsection
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $module_name }} List</h3>
                    </div>
                    <div class="card-body datatable_data">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th colspan="3">#</th>
                                    <th colspan="3">Group Name</th>
                                </tr>
                            </thead>
                            <tbody class="role_data" id="tablecontents">
                                @foreach ($modules_groups as $modules_group)
                                    <tr data-widget="expandable-table" aria-expanded="false" class="row1"
                                        data-id="{{ $modules_group->id }}">
                                        <td colspan="3">
                                            <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                                        </td>
                                        <td colspan="3">
                                            {{ $modules_group->name }}
                                        </td>
                                    </tr>
                                    <tr class="expandable-body">
                                        <td colspan="6">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Icon</th>
                                                        <th>Type</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tablecontents2">
                                                    @php
                                                        $counter = 1;
                                                    @endphp
                                                    @foreach ($modules_group->modules as $module)
                                                        <tr class="row2" data-id="{{ $module->id }}">
                                                            <td role='button'>{{ $counter++ }}<input type="hidden"
                                                                    name="db_module_id" class="db_module_id"
                                                                    value="{{ $module->id }}">
                                                                <input type="hidden" name="" class="m_g_name"
                                                                    value="{{ $module->modules_group['name'] }}">
                                                                <input type="hidden" name="" class="m_g_id"
                                                                    value="{{ $module->modules_group['id'] }}">
                                                            </td>
                                                            <td>{{ $module->name }}<input type="hidden"
                                                                    name="db_module_name" class="db_module_name"
                                                                    value="{{ $module->name }}"></td>
                                                            <td>{{ $module->icon }}<input type="hidden"
                                                                    name="db_module_icon" class="db_module_icon"
                                                                    value="{{ $module->icon }}">
                                                                <input type="hidden" name="db_module_sort"
                                                                    class="db_module_sort" value="{{ $module->sort }}">
                                                            </td>
                                                            <td>
                                                                @if ($module->type == '1')
                                                                    <span class="badge badge-info">Inside
                                                                        Page</span>
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
                                                                    class="db_module_status"
                                                                    value="{{ $module->status }}">
                                                            </td>
                                                            <td>
                                                                @if ($edit == 1 || $delete == 1 || $permissions_view == 1)
                                                                    <div class="dropdown">
                                                                        <button
                                                                            class="btn btn-secondary dropdown-toggle"
                                                                            type="button" id="dropdownMenuButton"
                                                                            data-toggle="dropdown" aria-haspopup="true"
                                                                            aria-expanded="false">
                                                                            Action
                                                                        </button>
                                                                        <div class="dropdown-menu edit_module"
                                                                            aria-labelledby="dropdownMenuButton">
                                                                            @if ($edit == 1)
                                                                                <li>
                                                                                    <a class="dropdown-item"
                                                                                        type="button" href="#"
                                                                                        data-toggle="modal"
                                                                                        data-target="#edit_modal">
                                                                                        Edit</a>
                                                                                </li>
                                                                            @endif
                                                                            @if ($delete == 1)
                                                                                <li>
                                                                                    <form
                                                                                        action="{{ route('admin.module.delete') }}"
                                                                                        method="POST">
                                                                                        @csrf
                                                                                        <input type="hidden"
                                                                                            name="id"
                                                                                            class="id"
                                                                                            value="{{ $module->id }}">
                                                                                        <button
                                                                                            class="dropdown-item delete"
                                                                                            type="submit">
                                                                                            Delete
                                                                                        </button>
                                                                                    </form>
                                                                                </li>
                                                                            @endif
                                                                            @if ($permissions_view == 1)
                                                                                <li>
                                                                                    <a href="{{ route('admin.module.url.index', [$module->id]) }}"
                                                                                        class="dropdown-item edit_module">
                                                                                        Modules URL
                                                                                    </a>
                                                                                </li>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tbody>
                                <tr>
                                    <th colspan="3">#</th>
                                    <th colspan="3">Group Name</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
{{-- Create Modal Start --}}
<form action="{{ route('admin.module.create') }}" method="POST" id="create_modal_form">
    @csrf
    <div class="modal fade" id="create_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ $module_name }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="failes" class="alert alert-default-danger alert-dismissible fade show" role="alert"
                        style="display: none">
                        <span class="text_fails"></span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="form-group">
                        <label for="module_name" class="text-capitalize">Name</label>
                        <input type="hidden" name="create_module_id" id="create_module_id" value="">
                        <div class="input-group">
                            <input type="text" name="create_module_name" id="create_module_name"
                                class="form-control">
                        </div>
                        <span class="text-danger name_status" id="create_module_name_error">
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="module_icon" class="text-capitalize">Icon</label>
                        <div class="input-group">
                            <input type="text" name="create_module_icon" id="create_module_icon"
                                class="form-control">
                        </div>
                        <span class="text-danger" id="create_module_icon_error">
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="m_g_id" class="text-capitalize">Module
                            Group</label>
                        <div class="input-group">
                            <select id="create_m_g_id" data-live-search="true" title="Select"
                                class="selectpicker show-tick form-control" name="m_g_id">
                                <option value="" selected hidden disabled>Select
                                </option>
                                @foreach ($modules_groups as $modules_group)
                                    <option value="{{ $modules_group->id }}">
                                        {{ $modules_group->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <span class="text-danger" id="create_m_g_id_error">
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="create_type" class="text-capitalize">Type</label>
                        <div class="input-group">
                            <select id="create_type" data-live-search="true" title="Select"
                                class="selectpicker show-tick form-control" name="create_type" required>
                                <option value="" selected hidden disabled>Select
                                </option>
                                <option value="0">SideBar</option>
                                <option value="1">Inside Page</option>
                            </select>
                        </div>
                        <span class="text-danger" id="create_type_error">
                        </span>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input create_module_status" type="checkbox"
                            id="create_module_status" checked="" value="1">
                        <label for="create_module_status" class="custom-control-label">Status</label>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="border px-2 btn create"
                        style="background-color: #091E3E;color: white" id="add_module_btn">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</form>
{{-- Create Modal End --}}
{{-- Edit Modal Start --}}
<form action="" method="post" id="edit_modal_form">
    @csrf
    <div class="modal fade" id="edit_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ $module_name }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="update_failes" class="alert alert-default-danger alert-dismissible fade show"
                        role="alert" style="display: none">
                        <span class="text_fails"></span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="form-group">
                        <label for="module_name" class="text-capitalize">Name</label>
                        <div class="input-group">
                            <input type="hidden" name="module_id" id="module_id" value="">
                            <input type="text" name="module_name" id="module_name" class="form-control">
                        </div>
                        <span class="edit_name_status" style="color: #dc3545"></span>
                        <span class="text-danger" id="module_name_error">
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="module_icon" class="text-capitalize">Icon</label>
                        <div class="input-group">
                            <input type="text" name="module_icon" id="module_icon" class="form-control">
                        </div>
                        <span class="text-danger" id="module_icon_error">
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="m_g_id" class="text-capitalize">Module Group</label>
                        <div class="input-group">
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
                        <span class="text-danger" id="m_g_id_error">
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="module_type" class="text-capitalize">Type</label>
                        <div class="input-group">
                            <select id="module_type" data-live-search="true" title="Select"
                                class="selectpicker show-tick form-control" name="module_type" required>
                                <option id="edit_module_type" selected hidden value="">
                                <option value="0">SideBar</option>
                                <option value="1">Inside Page</option>
                            </select>
                        </div>
                        <span class="text-danger" id="module_type_error">
                        </span>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input module_status" type="checkbox" id="module_status"
                            value="0">
                        <label for="module_status" class="custom-control-label">Status</label>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
{{-- Edit Modal End --}}
@section('links_content_foot')
    @Include('layouts.links.toastr.foot')
    @Include('layouts.links.sweet_alert.foot')
    <script type="text/javascript">
        $('#create_module_status').click(function() {
            if ($(this).is(':checked')) {
                $(this).attr("checked", true)
                $(this).val(this.checked ? 1 : 0);
            } else {
                $(this).attr('checked', false);
                $(this).val(this.checked ? 1 : 0);
            }
        });
        $('#module_status').click(function() {
            if ($(this).is(':checked')) {
                $(this).attr("checked", true)
                $(this).val(this.checked ? 1 : 0);
            } else {
                $(this).attr('checked', false);
                $(this).val(this.checked ? 1 : 0);
            }
        });
        $('.edit_module').on('click', function() {
            var _this = $(this).closest("tr");
            $('#module_id').val(_this.find('.db_module_id').val());
            $('#module_name').val(_this.find('.db_module_name').val());
            console.log(_this.find('.db_module_name').val());
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
        $(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

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
                if (name != '' && icon != '' && m_g_id != '' && type != '') {
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
                    if (name == '') {
                        $('#create_module_name_error').show();
                        $('#create_module_name_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#create_module_name_error').slideUp('slow');
                            $('#create_module_name_error').empty();
                        }, 4000);
                    }
                    if (icon == '') {
                        $('#create_module_icon_error').show();
                        $('#create_module_icon_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#create_module_icon_error').slideUp('slow');
                            $('#create_module_icon_error').empty();
                        }, 4000);
                    }
                    if (m_g_id == null) {
                        $('#create_m_g_id_error').show();
                        $('#create_m_g_id_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#create_m_g_id_error').slideUp('slow');
                            $('#create_m_g_id_error').empty();
                        }, 4000);
                    }
                    if (type == null) {
                        $('#create_type_error').show();
                        $('#create_type_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#create_type_error').slideUp('slow');
                            $('#create_type_error').empty();
                        }, 4000);
                    }
                }
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
                if (name != '' && icon != '' && m_g_id != '' && type != '') {
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
                } else {
                    if (name == '') {
                        $('#module_name_error').show();
                        $('#module_name_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#module_name_error').slideUp('slow');
                            $('#module_name_error').empty();
                        }, 4000);
                    }
                    if (icon == '') {
                        $('#module_icon_error').show();
                        $('#module_icon_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#module_icon_error').slideUp('slow');
                            $('#module_icon_error').empty();
                        }, 4000);
                    }
                    if (m_g_id == null) {
                        $('#m_g_id_error').show();
                        $('#m_g_id_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#m_g_id_error').slideUp('slow');
                            $('#m_g_id_error').empty();
                        }, 4000);
                    }
                    if (type == null) {
                        $('#module_type_error').show();
                        $('#module_type_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#module_type_error').slideUp('slow');
                            $('#module_type_error').empty();
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
                    text: "Once Deleted, you will not be able to recover this record!",
                    icon: 'warning',
                    confirmButtonColor: '#e64942',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: `No`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "post",
                            url: "{{ route('admin.module.delete') }}",
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
@endsection
