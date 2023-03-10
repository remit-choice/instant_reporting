@extends('layouts.admin.master')
@section('content')
@section('links_content_head')
    @Include('layouts.links.datatable.head')
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
                        <table id="example2" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Module Name</th>
                                    <th>Mode Name</th>
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
                                                class="db_module_id" value="{{ $modules_url->modules['id'] }}">
                                            <input type="hidden" name="db_module_url_id" class="db_module_url_id"
                                                value="{{ $modules_url->id }}">
                                        </td>
                                        <td>
                                            {{ $modules_url->modules['name'] }}
                                            <input type="hidden" name="db_module_name" class="db_module_name"
                                                value="{{ $modules_url->modules['name'] }}">
                                        </td>
                                        <td>
                                            @if ($modules_url->mode == 1)
                                                <span class="badge badge-secondary p-2"><i class="fa fa-eye"
                                                        aria-hidden="true"></i>&nbsp; View</span>
                                            @elseif ($modules_url->mode == 2)
                                                <span class="badge badge-secondary p-2"><i class="fa fa-plus"
                                                        aria-hidden="true"></i>&nbsp; Add</span>
                                            @elseif ($modules_url->mode == 3)
                                                <span class="badge badge-secondary p-2"><i class="fa fa-edit"
                                                        aria-hidden="true"></i>&nbsp; Edit</span>
                                            @elseif ($modules_url->mode == 4)
                                                <span class="badge badge-secondary p-2"><i class="fa fa-trash"
                                                        aria-hidden="true"></i>&nbsp; Delete</span>
                                            @elseif ($modules_url->mode == 5)
                                                <span class="badge badge-secondary p-2"><i class="fa fa-search"
                                                        aria-hidden="true"></i>&nbsp; Filter</span>
                                            @endif
                                            <input type="hidden" name="db_mode" class="db_mode"
                                                value="{{ $modules_url->mode }}">
                                        </td>
                                        <td>
                                            {{ $modules_url->name }}
                                            <input type="hidden" name="db_module_url" class="db_module_url"
                                                value="{{ $modules_url->name }}">

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
                                            <input type="hidden" name="db_module_type" class="db_module_type"
                                                value="{{ $modules_url->type }}">
                                        </td>
                                        <td>
                                            @if ($modules_url->status == '1')
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">InActive</span>
                                            @endif
                                            <input type="hidden" name="db_module_url_status"
                                                class="db_module_url_status" value="{{ $modules_url->status }}">
                                        </td>
                                        <td>
                                            @if ($edit == 1 || $delete == 1)
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu edit_module_url"
                                                        aria-labelledby="dropdownMenuButton">
                                                        @if ($edit == 1)
                                                            <li>
                                                                <a class="dropdown-item" type="button" href="#"
                                                                    data-toggle="modal" data-target="#edit_modal">
                                                                    Edit</a>
                                                            </li>
                                                        @endif
                                                        @if ($delete == 1)
                                                            <li>
                                                                <form
                                                                    action="/admin/setting/module/{{ $modules_url->id }}/url/delete"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="id" class="id"
                                                                        value="{{ $modules_url->id }}">
                                                                    <button class="dropdown-item delete" type="submit">
                                                                        Delete
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Module Name</th>
                                    <th>Mode Name</th>
                                    <th>Route Name</th>
                                    <th>URL</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
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
{{-- Add Modal Start --}}
<form action="" method="POST" id="create_modal_form">
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
                    <div class="form-group">
                        <label for="create_mode" class="text-capitalize">Mode</label>
                        <div class="input-group">
                            <select id="create_mode" data-live-search="true" title="Select"
                                class="selectpicker show-tick form-control" name="create_mode">
                                <option value="" selected hidden disabled>Select
                                </option>
                                @php
                                    $array = [];
                                @endphp
                                @foreach ($modules_urls as $modules_url)
                                    @php
                                        $array[] = $modules_url->mode;
                                    @endphp
                                @endforeach
                                @if (!in_array('1', $array))
                                    <option value="1">View</option>
                                @endif
                                @if (!in_array('2', $array))
                                    <option value="2">Create</option>
                                @endif
                                @if (!in_array('3', $array))
                                    <option value="3">Edit</option>
                                @endif
                                @if (!in_array('4', $array))
                                    <option value="4">Delete</option>
                                @endif
                            </select>
                        </div>
                        <span class="invalid-feedback" id="create_mode_error">
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="module_name" class="text-capitalize">URL</label>
                        <div class="input-group">
                            <input type="hidden" name="create_module_id" id="create_module_id"
                                value="{{ Session::get('m_id') }}">
                            <input type="hidden" name="create_module_url_id" id="create_module_url_id"
                                value="">
                            <input type="text" name="create_module_url_name" id="create_module_url_name"
                                class="form-control">
                        </div>
                        <span class="invalid-feedback" id="create_module_url_name_error">
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="create_type" class="text-capitalize">Type</label>
                        <div class="input-group">
                            <select id="create_type" data-live-search="true" title="Select"
                                class="selectpicker show-tick form-control" name="create_type">
                                <option value="" selected hidden disabled>Select
                                </option>
                                <option value="0">SideBar</option>
                                <option value="1">Inside Page</option>
                            </select>
                        </div>
                        <span class="invalid-feedback" id="create_type_error">
                        </span>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input create_module_url_status" type="checkbox"
                            id="create_module_url_status" checked value="1">
                        <label for="create_module_url_status" class="custom-control-label">Status</label>
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
{{-- Add Modal End --}}
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
                    <div class="form-group">
                        <label for="module_mode" class="text-capitalize">Mode</label>
                        <div class="input-group">
                            <select id="module_mode" data-live-search="true" title="Select"
                                class="selectpicker show-tick form-control" name="module_mode">
                                <option value="1">View</option>
                                <option value="2">Create</option>
                                <option value="3">Edit</option>
                                <option value="4">Delete</option>
                            </select>
                        </div>
                        <span class="invalid-feedback" id="module_mode_error">
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="module_url_id" class="text-capitalize">URL</label>
                        <div class="input-group">
                            <input type="hidden" name="module_url_id" id="module_url_id" value="">
                            <input type="text" name="module_url" id="module_url" class="form-control">
                        </div>
                        <span class="invalid-feedback" id="module_url_error">
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="module_id" class="text-capitalize">Module</label>
                        <input type="hidden" name="module_id" id="module_id" value="">
                        <input type="text" name="module_name" id="module_name" class="form-control" readonly>
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
                        <span class="invalid-feedback" id="module_type_error">
                        </span>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input module_url_status" type="checkbox" id="module_url_status"
                            value="0">
                        <label for="module_url_status" class="custom-control-label">Status</label>
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
</form>
{{-- Edit Modal End --}}
@section('links_content_foot')
    @Include('layouts.links.datatable.foot')
    @Include('layouts.links.sweet_alert.foot')
    @Include('layouts.links.toastr.foot')
    <script type="text/javascript">
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
        $('.edit_module_url').on('click', function() {
            var _this = $(this).parents('tr');
            $('#module_id').val(_this.find('.db_module_id').val());
            $('#module_name').val(_this.find('.db_module_name').val());
            $('#module_url_id').val(_this.find('.db_module_url_id').val());
            $('#module_url').val(_this.find('.db_module_url').val());
            $('#module_url_status').val(_this.find('.db_module_url_status').val());
            $('#edit_module_type').text(_this.find('.db_module_type_name').val());
            $('#edit_module_type').val(_this.find('.db_module_type').val());
            $('#module_mode').val(_this.find('.db_mode').val());

            var status = $('#module_url_status').val();
            if (status == 1) {
                $('#module_url_status').prop('checked', true);
            } else {
                $('#module_url_status').prop('checked', false);
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
                var url = $('#create_module_url_name').val();
                var m_id = $('#create_module_id').val();
                var status = $('#create_module_url_status').val();
                var type = $('#create_type').val();
                var mode = $('#create_mode').val();
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
                if (url != '' && type != '') {
                    $.ajax({
                        type: "post",
                        url: urls,
                        data: {
                            "url": url,
                            "m_id": m_id,
                            "type": type,
                            "mode": mode,
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
                    if (url == '') {
                        $('#create_module_url_name_error').show();
                        $('#create_module_url_name_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#create_module_url_name_error').slideUp('slow');
                            $('#create_module_url_name_error').empty();
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
                var m_id = $('#module_id').val();
                var id = $('#module_url_id').val();
                var url = $('#module_url').val();
                var status = $('#module_url_status').val();
                var type = $('#module_type').val();
                var mode = $('#module_mode').val();

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
                if (url != '' && type != '') {
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
                                url: urls,
                                data: {
                                    "id": id,
                                    "m_id": m_id,
                                    "url": url,
                                    "type": type,
                                    "mode": mode,
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
                } else {
                    if (url == '') {
                        $('#module_url_error').show();
                        $('#module_url_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#module_url_error').slideUp('slow');
                            $('#module_url_error').empty();
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
                            url: "/admin/setting/module/" + id +
                                "/url/delete",
                            data: {
                                "id": id,
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Data Successfully Deleted.!',
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
            $('#module_mode').change(function() {
                var m_id = $('#module_id').val();
                var module_mode = $(this).val();
                var urls = "{{ route('admin.module.url.edit', 'id') }}";
                urls = urls.replace('id', m_id);
                console.log(m_id);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                if (m_id) {
                    $.ajax({
                        type: "get",
                        url: urls,
                        data: {
                            "m_id": m_id,
                            "module_mode": module_mode,
                        },
                        success: function(response) {
                            if (response == "OK") {
                                $('#module_mode_error').html(
                                    '<span class="text-success">Mode is Available</span>'
                                );
                                $('.update').attr('disabled', false);
                                setTimeout(function() {
                                    $('#module_mode_error').fadeIn('slow');
                                }, 1000);
                                setTimeout(function() {
                                    $('#module_mode_error').fadeOut('slow');
                                }, 5000);
                            } else {
                                $('#module_mode_error').html(
                                    '<span class="text-danger">Mode Already Exist</span>'
                                );
                                $('.update').attr('disabled', true);
                                setTimeout(function() {
                                    $('#module_mode_error').fadeIn('slow');
                                }, 1000);
                                setTimeout(function() {
                                    $('#module_mode_error').fadeOut('slow');
                                }, 5000);
                            }
                        },

                        error: (error) => {
                            console.log(JSON.stringify(error));
                        }
                    });
                } else {
                    $('.update').attr('disabled', true);
                }
            });
        });
    </script>
@endsection
@endsection
