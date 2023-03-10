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
                                    <th>Name</th>
                                    <th>Icon</th>
                                    <th>Sort</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="role_data" id="tablecontents">
                                @php
                                    $counter = 1;
                                @endphp
                                @foreach ($modules_groups as $module_group)
                                    <tr data-widget="expandable-table" aria-expanded="false" class="row1"
                                        data-id="{{ $module_group->id }}">
                                        <td>{{ $counter++ }}<input type="hidden" name="db_module_group_id"
                                                class="db_module_group_id" value="{{ $module_group->id }}">
                                        </td>
                                        <td>{{ $module_group->name }}<input type="hidden" name="db_module_group_name"
                                                class="db_module_group_name" value="{{ $module_group->name }}"></td>
                                        <td>{{ $module_group->icon }}<input type="hidden" name="db_module_group_icon"
                                                class="db_module_group_icon" value="{{ $module_group->icon }}"></td>
                                        <td>{{ $module_group->sort }} <input type="hidden" name="db_module_group_sort"
                                                class="db_module_group_sort" value="{{ $module_group->sort }}"></td>
                                        <td>
                                            @if ($module_group->status == '1')
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">InActive</span>
                                            @endif
                                            <input type="hidden" name="db_module_group_status"
                                                class="db_module_group_status" value="{{ $module_group->status }}">
                                        </td>
                                        <td>
                                            @if ($edit == 1 || $delete == 1)
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu edit_module_group"
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
                                                                <form action="{{ route('admin.module.group.delete') }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="id" class="id"
                                                                        value="{{ $module_group->id }}">
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
                                    <th>Name</th>
                                    <th>Icon</th>
                                    <th>Sort</th>
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
{{-- Create Modal Start --}}
<form action="{{ route('admin.module.group.create') }}" method="POST" id="create_modal_form">
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
                        <label for="module_group_name" class="text-capitalize">Name</label>
                        <input type="hidden" name="create_module_group_id" id="create_module_group_id" value="">
                        <input type="text" name="create_module_group_name" id="create_module_group_name"
                            class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="module_group_icon" class="text-capitalize">Icon</label>
                        <input type="text" name="create_module_group_icon" id="create_module_group_icon"
                            class="form-control">
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input create_module_group_status" type="checkbox"
                            id="create_module_group_status" checked value="1">
                        <label for="create_module_group_status" class="custom-control-label">Status</label>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                        <label for="module_group_name" class="text-capitalize">Name</label>
                        <input type="hidden" name="module_group_id" id="module_group_id" value="">
                        <input type="text" name="module_group_name" id="module_group_name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="module_group_icon" class="text-capitalize">Icon</label>
                        <input type="text" name="module_group_icon" id="module_group_icon" class="form-control">
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input module_group_status" type="checkbox"
                            id="module_group_status" value="0">
                        <label for="module_group_status" class="custom-control-label">Status</label>
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
        $('.edit_module_group').on('click', function() {
            var _this = $(this).parents('tr');
            $('#module_group_id').val(_this.find('.db_module_group_id').val());
            $('#module_group_name').val(_this.find('.db_module_group_name').val());
            $('#module_group_icon').val(_this.find('.db_module_group_icon').val());
            $('#module_group_status').val(_this.find('.db_module_group_status').val());
            $('#module_group_sort').val(_this.find('.db_module_group_sort').val());
            var status = $('#module_group_status').val();
            if (status == 1) {
                $('#module_group_status').prop('checked', true);
            } else {
                $('#module_group_status').prop('checked', false);
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
                var name = $('#create_module_group_name').val();
                var icon = $('#create_module_group_icon').val();
                var status = $('#create_module_group_status').val();
                var sort = $('#create_module_group_sort').val();
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
                    url: "{{ route('admin.module.group.create') }}",
                    data: {
                        "name": name,
                        "icon": icon,
                        "status": status,
                        "sort": sort
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
            });
            $('.update').click(function(e) {
                e.preventDefault();
                var id = $('#module_group_id').val();
                var name = $('#module_group_name').val();
                var icon = $('#module_group_icon').val();
                var sort = $('#module_group_sort').val();
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
                            url: "{{ route('admin.module.group.edit') }}",
                            data: {
                                "id": id,
                                "name": name,
                                "icon": icon,
                                "status": status,
                                "sort": sort
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
                            url: "{{ route('admin.module.group.delete') }}",
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
@endsection
