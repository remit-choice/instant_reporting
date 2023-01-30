<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@Include('layouts.links.admin.title') | Users</title>
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
    <link rel="stylesheet" href="{{ asset('assets/dist/css/alt/login.css') }}">
    <script src="{{ asset('assets/dist/js/pages/login.js')}}"></script>

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
                            <h1 class="m-0">Users</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Users</li>
                            </ol>
                        </div><!-- /.col -->
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
                                <!-- /.card-header -->
                                <div class="card card-primary card-outline">
                                    <div class="card-body box-profile">
                                        <div class="text-center">
                                            @if (Session::has('image'))
                                                <img class="profile-user-img img-fluid img-circle" src="{{ Session::get('image') }}" alt="User profile picture">
                                            @else        
                                                <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" class="profile-user-img img-fluid img-circle" alt="User profile picture">
                                            @endif
                                        </div>

                                        <h3 class="profile-username text-center">{{ Session::get('full_name') }}</h3>

                                        <p class="text-muted text-center">{{ Session::get('designation') }}</p>

                                        <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <b>Followers</b> <a class="float-right">1,322</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Following</b> <a class="float-right">543</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Friends</b> <a class="float-right">13,287</a>
                                        </li>
                                        </ul>

                                        <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card-body -->
                            <!-- /.card -->
                        </div>
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
    </div>
    <div class="modal fade" id="user_update">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post">
                    @csrf
                    <div class="modal-body">
                         <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                                <input type="text" name="full_name"
                                    id="full_name" class="form-control" placeholder="Full Name">
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
                                <input type="text" name="user_name"
                                    id="user_name" class="form-control" placeholder="User Name">
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
                                <input type="text" name="designation"
                                    id="designation" class="form-control" placeholder="Designation">
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
                                <input type="email" name="email"
                                    id="email" class="form-control" placeholder="Email">
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
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="invalid-feedback" id="role_error">
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="status" class="text-capitalize">
                                <div class="input-group">
                                    <input type="checkbox"
                                        name="status me-2"
                                        id="status" value="0">Status
                                </div>
                            </label>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="border btn update"
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
        $('#user_status').click(function() {
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
                if (full_name != '' && user_name != '' && designation != '' && email != '' && password != '' && password == retype_password && password.length>=8 && password.length <=16 && r_id != '') {
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
                    if(full_name == ''){
                        $('#create_full_name_error').show();
                        $('#create_full_name_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#create_full_name_error').slideUp('slow');
                            $('#create_full_name_error').empty();
                        }, 4000);
                    }
                    if(user_name == ''){
                        $('#create_user_name_error').show();
                        $('#create_user_name_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#create_user_name_error').slideUp('slow');
                            $('#create_user_name_error').empty();
                        }, 4000);
                    }
                    if(designation == ''){
                        $('#create_designation_error').show();
                        $('#create_designation_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#create_designation_error').slideUp('slow');
                            $('#create_designation_error').empty();
                        }, 4000);
                    }
                    if(email == ''){
                        $('#create_email_error').show();
                        $('#create_email_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#create_email_error').slideUp('slow');
                            $('#create_email_error').empty();
                        }, 4000);
                    }
                    if(password == ''){
                        $('#create_password_error').show();
                        $('#create_password_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#create_password_error').slideUp('slow');
                            $('#create_password_error').empty();
                        }, 4000);
                    }else{
                        if(password.length<6){
                            $('#create_password_error').show();
                            $('#create_password_error').html(
                                "Min Length is 8!");
                            window.setInterval(function() {
                                $('#create_password_error').slideUp('slow');
                                $('#create_password_error').empty();
                            }, 4000);
                        }
                        if(password.length>16){
                            $('#create_password_error').show();
                            $('#create_password_error').html(
                                "Max Length is 16!");
                            window.setInterval(function() {
                                $('#create_password_error').slideUp('slow');
                                $('#create_password_error').empty();
                            }, 4000);
                        }
                    }
                    
                    if(retype_password == ''){
                        $('#create_retype_password_error').show();
                        $('#create_retype_password_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#create_retype_password_error').slideUp('slow');
                            $('#create_retype_password_error').empty();
                        }, 4000);
                    }
                    if(password != retype_password){
                        $('#create_password_error').show();
                        $('#create_password_error').html(
                            "Passwords Not Matched!");
                        window.setInterval(function() {
                            $('#create_password_error').slideUp('slow');
                            $('#create_password_error').empty();
                        }, 4000);
                    }
                    if(r_id == null){
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
                if(password.length==0){
                    password=0;
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
                if (full_name != '' && user_name != '' && designation != '' && email != '' && r_id != '' && ((password == 0) || ((password.length>=8) && (password.length<=16)))) {
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
                    if(full_name == ''){
                        $('#full_name_error').show();
                        $('#full_name_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#full_name_error').slideUp('slow');
                            $('#full_name_error').empty();
                        }, 4000);
                    }
                    if(user_name == ''){
                        $('#user_name_error').show();
                        $('#user_name_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#user_name_error').slideUp('slow');
                            $('#user_name_error').empty();
                        }, 4000);
                    }
                    if(designation == ''){
                        $('#designation_error').show();
                        $('#designation_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#designation_error').slideUp('slow');
                            $('#designation_error').empty();
                        }, 4000);
                    }
                    if(email == ''){
                        $('#email_error').show();
                        $('#email_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#email_error').slideUp('slow');
                            $('#email_error').empty();
                        }, 4000);
                    }
                    if(password.length<8){
                        $('#password_error').show();
                        $('#password_error').html(
                            "Min Length is 8!");
                        window.setInterval(function() {
                            $('#password_error').slideUp('slow');
                            $('#password_error').empty();
                        }, 4000);
                    }
                    if(password.length>16){
                        $('#password_error').show();
                        $('#password_error').html(
                            "Max Length is 16!");
                        window.setInterval(function() {
                            $('#password_error').slideUp('slow');
                            $('#password_error').empty();
                        }, 4000);
                    }
                    
                    if(password != retype_password){
                        $('#password_error').show();
                        $('#password_error').html(
                            "Passwords Not Matched!");
                        window.setInterval(function() {
                            $('#password_error').slideUp('slow');
                            $('#password_error').empty();
                        }, 4000);
                    }
                    if(r_id == null){
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
</body>

</html>