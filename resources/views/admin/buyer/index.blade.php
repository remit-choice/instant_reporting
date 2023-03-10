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
                                            @if ($buyer->type == 1)
                                                <span class="bg-secondary badge"><i class="fa fa-percent"
                                                        aria-hidden="true"></i> Percentage</span>
                                            @elseif ($buyer->type == 2)
                                                <span class="bg-secondary badge"><i class="fas fa-sort-amount-down"
                                                        aria-hidden="true"></i> Fixed Amount</span>
                                            @endif
                                            <input type="hidden" name="db_buyer_type" class="db_buyer_type"
                                                value="{{ $buyer->type }}">
                                        </td>
                                        <td>
                                            @if ($buyer->status == 1)
                                                <span class="bg-success badge">Active</span>
                                            @else
                                                <span class="bg-danger badge">InActive</span>
                                            @endif
                                            <input type="hidden" name="db_buyer_status" class="db_buyer_status"
                                                value="{{ $buyer->status }}">
                                        </td>
                                        <td>
                                            @if ($edit == 1 || $permissions_view == 1 || $permissions_buyer_report_view == 1 || $delete == 1)
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu edit_buyer"
                                                        aria-labelledby="dropdownMenuButton">
                                                        @if ($edit == 1)
                                                            <li>
                                                                <a class="dropdown-item" type="button" href="#"
                                                                    data-toggle="modal" data-target="#edit_modal">
                                                                    Edit</a>
                                                            </li>
                                                        @endif
                                                        @if ($permissions_view == 1)
                                                            <li>
                                                                <a class="dropdown-item" type="button"
                                                                    href="{{ route('admin.buyer.pay_method.index', ['id' => $buyer->id]) }}">
                                                                    Add Payment Method</a>
                                                            </li>
                                                        @endif
                                                        @if ($permissions_buyer_report_view == 1)
                                                            <li>
                                                                <a class="dropdown-item" type="button"
                                                                    href="{{ route('admin.buyer.report.index', ['id' => $buyer->id]) }}">
                                                                    View Report</a>
                                                            </li>
                                                        @endif
                                                        @if ($delete == 1)
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
                                    <th class="text-capitalize">#</th>
                                    <th class="text-capitalize">Name</th>
                                    <th class="text-capitalize">Dealing Type</th>
                                    <th class="text-capitalize">Status</th>
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
<form action="{{ route('admin.buyer.create') }}" method="POST">
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
                        <label for="buyer_name" class="text-capitalize">Name</label>
                        <div class="input-group">
                            <input type="hidden" name="create_buyer_id" id="create_buyer_id" value="">
                            <input type="text" name="create_buyer_name" id="create_buyer_name"
                                class="form-control">
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
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input create_buyer_status" type="checkbox"
                            id="create_buyer_status" checked value="1">
                        <label for="create_buyer_status" class="custom-control-label">Status</label>
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
{{-- Add Modal End --}}
{{-- Edit Modal Start --}}
<form action="{{ route('admin.buyer.edit') }}" method="post">
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
                        <label for="buyer_name" class="text-capitalize">Name</label>
                        <div class="input-group">
                            <input type="hidden" name="edit_buyer_id" id="edit_buyer_id" value="">
                            <input type="text" name="edit_buyer_name" id="edit_buyer_name" class="form-control">
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
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input edit_buyer_status" type="checkbox" id="edit_buyer_status"
                            value="0">
                        <label for="edit_buyer_status" class="custom-control-label">Status</label>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary update">Update</button>
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
    <script type="text/javascript">
        $('.edit_buyer').on('click', function() {
            var _this = $(this).parents('tr');
            $('#edit_buyer_id').val(_this.find('.db_buyer_id').val());
            $('#edit_buyer_name').val(_this.find('.db_buyer_name').val());
            console.log(_this.find('.db_buyer_name').val());
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
                    if (name = '') {
                        $('#create_buyer_name_error').show();
                        $('#create_buyer_name_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#create_buyer_name_error').slideUp('slow');
                            $('#create_buyer_name_error').empty();
                        }, 4000);
                    } else if (type = '') {
                        $('#create_buyer_type_error').show();
                        $('#create_buyer_type_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#create_buyer_type_error').slideUp('slow');
                            $('#create_buyer_type_error').empty();
                        }, 4000);
                    } else {}
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
                } else {
                    if (name != '') {
                        $('#edit_buyer_name_error').show();
                        $('#edit_buyer_name_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#edit_buyer_name_error').slideUp('slow');
                            $('#edit_buyer_name_error').empty();
                        }, 4000);
                    } else if (type != '') {} else {
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
@endsection
