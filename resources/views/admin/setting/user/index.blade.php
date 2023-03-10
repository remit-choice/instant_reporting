    @extends('layouts.admin.master')
    @section('content')
    @section('links_content_head')
        @Include('layouts.links.datatable.head')
        @Include('layouts.links.toastr.head')
        <link rel="stylesheet" href="{{ asset('assets/dist/css/alt/login.css') }}">
        <script src="{{ asset('assets/dist/js/pages/login.js') }}"></script>
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
                            <h3 class="card-title">User List</h3>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body datatable_data">
                            <table id="example2" class="table table-bordered table-striped">
                                <thead id="t_head">
                                    <tr>
                                        <th>#</th>
                                        <th>Full Name</th>
                                        <th>User Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Designation</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="role_data" id="t_body">
                                    @php
                                        $counter = 1;
                                    @endphp
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $counter++ }}<input type="hidden" name="db_user_id"
                                                    class="db_user_id" value="{{ $user->id }}"> </td>
                                            <td>{{ $user->full_name }}<input type="hidden" name="db_full_name"
                                                    class="db_full_name" value="{{ $user->full_name }}"></td>
                                            <td>{{ $user->user_name }}<input type="hidden" name="db_user_name"
                                                    class="db_user_name" value="{{ $user->user_name }}"></td>
                                            <td>{{ $user->email }}<input type="hidden" name="db_email"
                                                    class="db_email" value="{{ $user->email }}"><input type="hidden"
                                                    name="db_password" class="db_password"
                                                    value="{{ $user->password }}"></td>
                                            <td>{{ $user->roles['name'] }}
                                                <input type="hidden" name="db_role_id" class="db_role_id"
                                                    value="{{ $user->roles['id'] }}">
                                                <input type="hidden" name="db_role_name" class="db_role_name"
                                                    value="{{ $user->roles['name'] }}">
                                            </td>
                                            <td>{{ $user->designation }}<input type="hidden" name="db_designation"
                                                    class="db_designation" value="{{ $user->designation }}"></td>
                                            <td>
                                                @if ($user->status == '1')
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">InActive</span>
                                                @endif
                                                <input type="hidden" name="db_status" class="db_status"
                                                    value="{{ $user->status }}">
                                            </td>
                                            <td>
                                                @if ($edit == 1 || $delete == 1)
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                                            id="dropdownMenuButton" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <div class="dropdown-menu edit_user"
                                                            aria-labelledby="dropdownMenuButton">
                                                            @if ($edit == 1)
                                                                <li>
                                                                    <a class="dropdown-item" type="button"
                                                                        href="#" data-toggle="modal"
                                                                        data-target="#edit_modal">
                                                                        Edit</a>
                                                                </li>
                                                            @endif
                                                            @if ($delete == 1)
                                                                <li>
                                                                    <form action="{{ route('admin.user.delete') }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="id"
                                                                            class="id" value="{{ $user->id }}">
                                                                        <button class="dropdown-item delete"
                                                                            type="submit">
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
                                <tfoot id="t_foot">
                                    <tr>
                                        <th>#</th>
                                        <th>Full Name</th>
                                        <th>User Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Designation</th>
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
    {{-- Add Modal Start --}}
    <form action="{{ route('admin.user.create') }}" method="POST" id="create_modal_form">
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
                            <div class="input-group">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                                <input type="text" name="create_full_name" id="create_full_name"
                                    class="form-control" placeholder="Full Name">
                            </div>
                            <span class="invalid-feedback" id="create_full_name_error">
                            </span>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                                <input type="text" name="create_user_name" id="create_user_name"
                                    class="form-control" placeholder="User Name">
                            </div>
                            <span class="invalid-feedback" id="create_user_name_error">
                            </span>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-level-up-alt"></span>
                                    </div>
                                </div>
                                <input type="text" name="create_designation" id="create_designation"
                                    class="form-control" placeholder="Designation">
                            </div>
                            <span class="invalid-feedback" id="create_designation_error">
                            </span>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                                <input type="email" name="create_email" id="create_email" class="form-control"
                                    placeholder="Email">
                            </div>
                            <span class="invalid-feedback" id="create_email_error">
                            </span>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                <input type="password" name="create_password" class="form-control"
                                    id="create_password" placeholder="Password">
                                <div class="input-group-append" onclick="Create_Password()">
                                    <div class="input-group-text">
                                        <span class="me-2 d-flex">
                                            <i id="create_Show" class="far fa-eye"></i>
                                            <i id="create_Hide" class="fas fa-eye-slash"></i>
                                        </span>
                                    </div>
                                </div>
                                @error('create_password')
                                    <div class="invalid-feedback order-last" id="password_msg">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <span class="invalid-feedback" id="create_password_error">
                            </span>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                <input type="password" name="retyp_password" class="form-control"
                                    id="create_retype_password" placeholder="Password" placeholder="Retype Password">
                                <div class="input-group-append" onclick="Create_Retype_Password()">
                                    <div class="input-group-text">
                                        <span class="me-2 d-flex">
                                            <i id="create_retype_Show" class="far fa-eye"></i>
                                            <i id="create_retype_Hide" class="fas fa-eye-slash"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <span class="invalid-feedback" id="create_retype_password_error">
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="role" class="text-capitalize">Role</label>
                            <div class="input-group">
                                <select name="create_role" id="create_role" class="form-control">
                                    <option value="" selected disabled hidden>SELECT</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="invalid-feedback" id="create_role_error">
                            </span>
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
    <form action="{{ route('admin.user.edit') }}" method="post" id="edit_modal_form">
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
                            <div class="input-group">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                                <input type="text" name="full_name" id="full_name" class="form-control"
                                    placeholder="Full Name">
                                <input type="hidden" name="user_id" id="user_id">
                            </div>
                            <span class="invalid-feedback" id="full_name_error">
                            </span>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                                <input type="text" name="user_name" id="user_name" class="form-control"
                                    placeholder="User Name">
                            </div>
                            <span class="invalid-feedback" id="user_name_error">
                            </span>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-level-up-alt"></span>
                                    </div>
                                </div>
                                <input type="text" name="designation" id="designation" class="form-control"
                                    placeholder="Designation">
                            </div>
                            <span class="invalid-feedback" id="designation_error">
                            </span>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="Email">
                            </div>
                            <span class="invalid-feedback" id="email_error">
                            </span>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                <input type="password" name="password" class="form-control" id="password"
                                    placeholder="Password">
                                <div class="input-group-append" onclick="Password()">
                                    <div class="input-group-text">
                                        <span class="me-2 d-flex">
                                            <i id="Show" class="far fa-eye"></i>
                                            <i id="Hide" class="fas fa-eye-slash"></i>
                                        </span>
                                    </div>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback order-last" id="password_msg">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <span class="invalid-feedback" id="password_error">
                            </span>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                <input type="password" name="password" class="form-control" id="retype_password"
                                    placeholder="Retype Password">
                                <div class="input-group-append" onclick="retype_Password()">
                                    <div class="input-group-text">
                                        <span class="me-2 d-flex">
                                            <i id="retype_Show" class="far fa-eye"></i>
                                            <i id="retype_Hide" class="fas fa-eye-slash"></i>
                                        </span>
                                    </div>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback order-last" id="password_msg">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <span class="invalid-feedback" id="retype_password_error">
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="role" class="text-capitalize">Role</label>
                            <div class="input-group">
                                <select name="role" id="role" class="form-control">
                                    <option value="" id="edit_role" hidden selected></option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="invalid-feedback" id="role_error">
                            </span>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input status" type="checkbox" id="status"
                                value="0">
                            <label for="status" class="custom-control-label">Status</label>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="border btn update"
                                style="background-color: #091E3E;color: white">Update</button>
                        </div>
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
            $('#status').click(function() {
                if ($(this).is(':checked')) {
                    $(this).attr("checked", true)
                    $(this).val(this.checked ? 1 : 0);
                } else {
                    $(this).attr('checked', false);
                    $(this).val(this.checked ? 1 : 0);
                }
            });
            $('#create_user_status').click(function() {
                if ($(this).is(':checked')) {
                    $(this).attr("checked", true)
                    $(this).val(this.checked ? 1 : 0);
                } else {
                    $(this).attr('checked', false);
                    $(this).val(this.checked ? 1 : 0);
                }
            });
            $('.create_user').on('click', function() {
                $('input[type=text]').val('');
                $('input[type=email]').val('');
                $('input[type=password]').val('');
            });
            $('.edit_user').on('click', function() {
                var _this = $(this).parents('tr');
                $('#user_id').val(_this.find('.db_user_id').val());
                $('#full_name').val(_this.find('.db_full_name').val());
                $('#user_name').val(_this.find('.db_user_name').val());
                $('#designation').val(_this.find('.db_designation').val());
                $('#email').val(_this.find('.db_email').val());
                $('#password').val('');
                $('#edit_role').val(_this.find('.db_role_id').val());
                $('#edit_role').text(_this.find('.db_role_name').val());
                $('#status').val(_this.find('.db_status').val());
                var status = $('#status').val();
                if (status == 1) {
                    $('#status').prop('checked', true);
                    $('#status').prop('checked', true);
                } else {
                    $('#status').prop('checked', false);
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
                    var full_name = $('#create_full_name').val();
                    var user_name = $('#create_user_name').val();
                    var designation = $('#create_designation').val();
                    var email = $('#create_email').val();
                    var password = $('#create_password').val();
                    var retype_password = $('#create_retype_password').val();
                    var r_id = $('#create_role').val();

                    console.log(full_name);
                    console.log(user_name);
                    console.log(designation);
                    console.log(email);
                    console.log(password);
                    console.log(retype_password);
                    console.log(r_id);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    if (full_name != '' && user_name != '' && designation != '' && email != '' && password !=
                        '' && password == retype_password && password.length >= 8 && password.length <= 16 &&
                        r_id != '') {
                        $.ajax({
                            type: "post",
                            url: "{{ route('admin.user.create') }}",
                            data: {
                                "full_name": full_name,
                                "user_name": user_name,
                                "designation": designation,
                                "email": email,
                                "password": password,
                                "r_id": r_id,
                            },
                            cache: false,
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
                        if (full_name == '') {
                            $('#create_full_name_error').show();
                            $('#create_full_name_error').html(
                                "Required!");
                            window.setInterval(function() {
                                $('#create_full_name_error').slideUp('slow');
                                $('#create_full_name_error').empty();
                            }, 4000);
                        }
                        if (user_name == '') {
                            $('#create_user_name_error').show();
                            $('#create_user_name_error').html(
                                "Required!");
                            window.setInterval(function() {
                                $('#create_user_name_error').slideUp('slow');
                                $('#create_user_name_error').empty();
                            }, 4000);
                        }
                        if (designation == '') {
                            $('#create_designation_error').show();
                            $('#create_designation_error').html(
                                "Required!");
                            window.setInterval(function() {
                                $('#create_designation_error').slideUp('slow');
                                $('#create_designation_error').empty();
                            }, 4000);
                        }
                        if (email == '') {
                            $('#create_email_error').show();
                            $('#create_email_error').html(
                                "Required!");
                            window.setInterval(function() {
                                $('#create_email_error').slideUp('slow');
                                $('#create_email_error').empty();
                            }, 4000);
                        }
                        if (password == '') {
                            $('#create_password_error').show();
                            $('#create_password_error').html(
                                "Required!");
                            window.setInterval(function() {
                                $('#create_password_error').slideUp('slow');
                                $('#create_password_error').empty();
                            }, 4000);
                        } else {
                            if (password.length < 6) {
                                $('#create_password_error').show();
                                $('#create_password_error').html(
                                    "Min Length is 8!");
                                window.setInterval(function() {
                                    $('#create_password_error').slideUp('slow');
                                    $('#create_password_error').empty();
                                }, 4000);
                            }
                            if (password.length > 16) {
                                $('#create_password_error').show();
                                $('#create_password_error').html(
                                    "Max Length is 16!");
                                window.setInterval(function() {
                                    $('#create_password_error').slideUp('slow');
                                    $('#create_password_error').empty();
                                }, 4000);
                            }
                        }

                        if (retype_password == '') {
                            $('#create_retype_password_error').show();
                            $('#create_retype_password_error').html(
                                "Required!");
                            window.setInterval(function() {
                                $('#create_retype_password_error').slideUp('slow');
                                $('#create_retype_password_error').empty();
                            }, 4000);
                        }
                        if (password != retype_password) {
                            $('#create_password_error').show();
                            $('#create_password_error').html(
                                "Passwords Not Matched!");
                            window.setInterval(function() {
                                $('#create_password_error').slideUp('slow');
                                $('#create_password_error').empty();
                            }, 4000);
                        }
                        if (r_id == null) {
                            $('#create_role_error').show();
                            $('#create_role_error').html(
                                "Required!");
                            window.setInterval(function() {
                                $('#create_role_error').slideUp('slow');
                                $('#create_role_error').empty();
                            }, 4000);
                        }
                    }
                });
                $('.update').click(function(e) {
                    e.preventDefault();
                    var id = $('#user_id').val();
                    var full_name = $('#full_name').val();
                    var user_name = $('#user_name').val();
                    var designation = $('#designation').val();
                    var email = $('#email').val();
                    var password = $('#password').val();
                    var retype_password = $('#retype_password').val();
                    var status = $('#status').val();
                    var r_id = $('#role').val();
                    if (password.length == 0) {
                        password = 0;
                    }
                    console.log(full_name);
                    console.log(user_name);
                    console.log(designation);
                    console.log(email);
                    console.log(password);
                    console.log(retype_password);
                    console.log(r_id);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    if (full_name != '' && user_name != '' && designation != '' && email != '' && r_id != '' &&
                        ((password == 0) || ((password.length >= 8) && (password.length <= 16)))) {
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
                                    url: "{{ route('admin.user.edit') }}",
                                    data: {
                                        "id": id,
                                        "full_name": full_name,
                                        "user_name": user_name,
                                        "designation": designation,
                                        "email": email,
                                        "password": password,
                                        "r_id": r_id,
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
                        if (full_name == '') {
                            $('#full_name_error').show();
                            $('#full_name_error').html(
                                "Required!");
                            window.setInterval(function() {
                                $('#full_name_error').slideUp('slow');
                                $('#full_name_error').empty();
                            }, 4000);
                        }
                        if (user_name == '') {
                            $('#user_name_error').show();
                            $('#user_name_error').html(
                                "Required!");
                            window.setInterval(function() {
                                $('#user_name_error').slideUp('slow');
                                $('#user_name_error').empty();
                            }, 4000);
                        }
                        if (designation == '') {
                            $('#designation_error').show();
                            $('#designation_error').html(
                                "Required!");
                            window.setInterval(function() {
                                $('#designation_error').slideUp('slow');
                                $('#designation_error').empty();
                            }, 4000);
                        }
                        if (email == '') {
                            $('#email_error').show();
                            $('#email_error').html(
                                "Required!");
                            window.setInterval(function() {
                                $('#email_error').slideUp('slow');
                                $('#email_error').empty();
                            }, 4000);
                        }
                        if (password.length < 8) {
                            $('#password_error').show();
                            $('#password_error').html(
                                "Min Length is 8!");
                            window.setInterval(function() {
                                $('#password_error').slideUp('slow');
                                $('#password_error').empty();
                            }, 4000);
                        }
                        if (password.length > 16) {
                            $('#password_error').show();
                            $('#password_error').html(
                                "Max Length is 16!");
                            window.setInterval(function() {
                                $('#password_error').slideUp('slow');
                                $('#password_error').empty();
                            }, 4000);
                        }

                        if (password != retype_password) {
                            $('#password_error').show();
                            $('#password_error').html(
                                "Passwords Not Matched!");
                            window.setInterval(function() {
                                $('#password_error').slideUp('slow');
                                $('#password_error').empty();
                            }, 4000);
                        }
                        if (r_id == null) {
                            $('#role_error').show();
                            $('#role_error').html(
                                "Required!");
                            window.setInterval(function() {
                                $('#role_error').slideUp('slow');
                                $('#role_error').empty();
                            }, 4000);
                        }
                    }
                });
                $('.delete').click(function(e) {
                    e.preventDefault();
                    var el = this;
                    var id = $(this).closest("tr").find('.db_user_id').val();
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
                                url: "{{ route('admin.user.delete') }}",
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
