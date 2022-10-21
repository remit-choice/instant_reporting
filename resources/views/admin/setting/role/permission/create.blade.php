<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@Include('layouts.links.admin.title') | Modules Permissions</title>
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
    <link rel="stylesheet" href="{{ asset('assets/dist/css/roles_permissions.css') }}" type="text/css">

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
                                <h1 class="m-0">Modules Permisions</h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Modules Permisions</li>
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
                                {{-- </ul> --}}
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid px-4">
                        <div class="d-flex justify-content-center border p-1 mb-4">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <ul class="nav nav-pills text-center">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <li class="nav-item">
                                            <div class="nav-link active" aria-current="page">
                                                @foreach ($roles as $role)
                                                    {{ $role->name }}
                                                @endforeach
                                                Permissions <span class="badge ms-2"
                                                    style="background-color: #0d6efd;"></span>
                                            </div>
                                        </li>
                                        <input type="checkbox" id="selectall" class="select-all" />
                                        All Permissions<br />
                                    </div>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <table class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <thead id="head" class="checoboxes">
                                    <th class="col-lg-2 col-md-2 col-sm-12 col-xs-12">Modules</th>
                                    <th class="col-lg-2 col-md-2 col-sm-12 col-xs-12">View</th>
                                    <th class="col-lg-2 col-md-2 col-sm-12 col-xs-12">Add</th>
                                    <th class="col-lg-2 col-md-2 col-sm-12 col-xs-12">Edit</th>
                                    <th class="col-lg-2 col-md-2 col-sm-12 col-xs-12">Delete</th>

                                </thead>
                                <tbody>
                                    <?php
                                    $counter = 0;
                                    ?>
                                    <form
                                        action="{{ route('admin.role.permission.create', ['id' => Session::get('roll_id')]) }}"
                                        method="POST">
                                        @csrf

                                        @foreach ($modules_groups as $modules_group)
                                            @php
                                                $count = 1;
                                            @endphp
                                            @foreach ($modules_group->modules as $module)
                                                @if (!empty($module->permissions))
                                                    @if ($count == 1)
                                                        <tr class="checoboxes">
                                                            <td class="border-none">
                                                                {{ $modules_group->name }}
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $count++;
                                                        @endphp
                                                    @endif

                                                    @if ($module->permissions['view'] != null ||
                                                        $module->permissions['add'] != null ||
                                                        $module->permissions['edit'] != null ||
                                                        $module->permissions['delete'] != null)
                                                        <tr class="checoboxes">
                                                            <td>
                                                                <input type="hidden" name="r_id" id="R_id"
                                                                    value="{{ $module->permissions['r_id'] }}">
                                                                <input type="hidden" class="M_Id"
                                                                    value="{{ $module->id }}">
                                                                {{ $module->name }}
                                                            </td>
                                                            <td>
                                                                @if (empty($module->permissions['view']) && $module->permissions['view'] != 0)
                                                                @else
                                                                    <input type="checkbox" class="check"
                                                                        value="{{ $module->permissions['view'] }}"
                                                                        @if ($module->permissions['view'] == 1) {{ 'checked' }} @endif
                                                                        @if ($module->permissions['view'] == 1) {{ 'disabled' }} @endif>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if (empty($module->permissions['add']) && $module->permissions['add'] != 0)
                                                                @else
                                                                    <input type="checkbox" class="check"
                                                                        value="{{ $module->permissions['add'] }}"
                                                                        @if ($module->permissions['add'] == 1) {{ 'checked' }} @endif
                                                                        @if ($module->permissions['view'] == 1) {{ 'disabled' }} @endif>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if (empty($module->permissions['edit']) && $module->permissions['edit'] != 0)
                                                                @else
                                                                    <input type="checkbox" class="check"
                                                                        value="{{ $module->permissions['edit'] }}"
                                                                        @if ($module->permissions['edit'] == 1) {{ 'checked' }} @endif
                                                                        @if ($module->permissions['view'] == 1) {{ 'disabled' }} @endif>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if (empty($module->permissions['delete']) && $module->permissions['delete'] != 0)
                                                                @else
                                                                    <input type="checkbox" class="check"
                                                                        value="{{ $module->permissions['delete'] }}"
                                                                        @if ($module->permissions['delete'] == 1) {{ 'checked' }} @endif
                                                                        @if ($module->permissions['view'] == 1) {{ 'disabled' }} @endif>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @else
                                                    <tr class="checoboxes">
                                                        <td>
                                                            <input type="hidden" name="r_id" id="R_id"
                                                                value="{{ Session::get('roll_id') }}">
                                                            <input type="hidden" name="m_id[]" class="M_Id"
                                                                value="{{ $module->id }}">
                                                            {{ $module->name }}
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" name="view[{{ $counter }}]"
                                                                class="check" value="0" onclick="Checked()">
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" name="add[{{ $counter }}]"
                                                                class="check" value="0" onclick="Checked()">
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" name="edit[{{ $counter }}]"
                                                                class="check" value="0" onclick="Checked()">
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" name="delete[{{ $counter }}]"
                                                                class="check" value="0" onclick="Checked()">
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $counter = $counter + 1;
                                                    ?>
                                                @endif
                                            @endforeach
                                        @endforeach
                                        <div class="d-flex justify-content-between">
                                            <a href="/admin/setting/role" class="btn btn-primary float-end my-4">Back</a>
                                            <button type="submit" id="Save" class="btn btn-primary float-end my-4">
                                                Save changes
                                            </button>
                                        </div>

                                    </form>

                                </tbody>
                            </table>


                        </div>
                    </div>
                </section>
                <!-- /.content -->
            </div>
        </div>
        <!-- /.modal -->
        @Include('layouts.links.datatable.foot')

        <script type="text/javascript">
            $(function() {
                $('.check').click(function() {
                    if ($(this).is(':checked')) {
                        $(this).attr("checked", true)
                        $(this).val(this.checked ? 1 : 0);
                    } else {
                        $(this).attr('checked', false);
                        $(this).val(this.checked ? 1 : 0);
                    }
                });
            });
            $(document).on('change', '#selectall', function() {
                if ($(this).prop('checked')) {
                    $('.check').prop('checked', true)
                    $('input:checkbox').attr('checked', true);
                    $('.check').val(this.checked ? 1 : 0);
                } else {
                    $('.check').prop('checked', false);
                    $('input:checkbox').attr('checked', false);
                    $('.check').val(this.checked ? 1 : 0);
                }
            });
        </script>
    @endsection
    {{-- @Include('layouts.links.modals.foot') --}}
</body>

</html>
