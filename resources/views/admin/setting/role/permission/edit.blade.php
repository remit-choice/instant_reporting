@extends('layouts.admin.master')
@section('content')
@section('links_content_head')
    <link rel="stylesheet" href="{{ asset('assets/dist/css/roles_permissions.css') }}" type="text/css">
@endsection
<!-- Main content -->
<section class="content">
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-center border p-1 mb-4">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <ul class="nav nav-pills text-center">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <li class="nav-item">
                            <div class="nav-link" aria-current="page" style="background-color: #091E3E;color: white">
                                @foreach ($roles as $role)
                                    {{ $role->name }}
                                @endforeach
                                {{ $module_name }} <span class="badge ms-2" style="background-color: #091E3E;"></span>
                            </div>
                        </li>
                        <input type="checkbox" id="selectall" class="select-all" />
                        All {{ $module_name }}<br />
                    </div>
                </ul>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <table class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <thead id="head" class="checoboxes">
                    <th class="col-lg-2 col-md-2 col-sm-12 col-xs-12">Modules</th>
                    <th class="col-lg-2 col-md-2 col-sm-12 col-xs-12">All</th>
                    <th class="col-lg-2 col-md-2 col-sm-12 col-xs-12">View</th>
                    <th class="col-lg-2 col-md-2 col-sm-12 col-xs-12">Add</th>
                    <th class="col-lg-2 col-md-2 col-sm-12 col-xs-12">Edit</th>
                    <th class="col-lg-2 col-md-2 col-sm-12 col-xs-12">Delete</th>
                </thead>
                <tbody>
                    <?php
                    $counter = 0;
                    ?>
                    <form action="{{ route('admin.role.permission.edit', [Session::get('roll_id')]) }}" method="POST">
                        @csrf

                        @foreach ($modules_groups as $modules_group)
                            <tr class="checoboxes">
                                <td class="border-none">
                                    {{ $modules_group->name }}
                                </td>
                            </tr>
                            @foreach ($modules_group->modules as $module)
                                @if (!empty($module->permissions['r_id']))
                                    @if (
                                        !empty($module->permissions['view']) ||
                                            !empty($module->permissions['add']) ||
                                            !empty($module->permissions['edit']) ||
                                            !empty($module->permissions['delete']))
                                        <tr class="checoboxes">
                                            <td class="d-flex justify-content-between text-left px-4">
                                                <input type="hidden" name="r_id" id="R_id"
                                                    value="{{ $module->permissions['r_id'] }}">
                                                <input type="hidden" name="m_id[]" class="M_Id"
                                                    value="{{ $module->id }}">
                                                <input type="checkbox" name="m_ids[{{ $counter }}]" class="check"
                                                    value="@if (
                                                        $module->permissions['view'] != null ||
                                                            $module->permissions['add'] != null ||
                                                            $module->permissions['edit'] != null ||
                                                            $module->permissions['delete'] != null) {{ 1 }} @endif"
                                                    @if (
                                                        $module->permissions['view'] != null ||
                                                            $module->permissions['add'] != null ||
                                                            $module->permissions['edit'] != null ||
                                                            $module->permissions['delete'] != null) {{ 'checked' }} @endif>
                                                {{ $module->name }}
                                            </td>
                                            <td>
                                                <input type="checkbox" class="check row_check" value="0">
                                            </td>
                                            <td>
                                                @if ($module->permissions['view'] == null && $module->permissions['view'] != '0')
                                                @else
                                                    <input type="checkbox" name="view[{{ $counter }}]"
                                                        class="check row_child_check"
                                                        value="{{ $module->permissions['view'] }}"
                                                        @if ($module->permissions['view'] == 1) {{ 'checked' }} @endif>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($module->permissions['add'] == null && $module->permissions['add'] != '0')
                                                @else
                                                    <input type="checkbox" name="add[{{ $counter }}]"
                                                        class="check row_child_check"
                                                        value="{{ $module->permissions['add'] }}"
                                                        @if ($module->permissions['add'] == 1) {{ 'checked' }} @endif>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($module->permissions['edit'] == null && $module->permissions['edit'] != '0')
                                                @else
                                                    <input type="checkbox" name="edit[{{ $counter }}]"
                                                        class="check row_child_check"
                                                        value="{{ $module->permissions['edit'] }}"
                                                        @if ($module->permissions['edit'] == 1) {{ 'checked' }} @endif>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($module->permissions['delete'] == null && $module->permissions['delete'] != '0')
                                                @else
                                                    <input type="checkbox" name="delete[{{ $counter }}]"
                                                        class="check row_child_check"
                                                        value="{{ $module->permissions['delete'] }}"
                                                        @if ($module->permissions['delete'] == 1) {{ 'checked' }} @endif>
                                                @endif
                                            </td>
                                        </tr>
                                        @php
                                            $counter = $counter + 1;
                                        @endphp
                                    @endif
                                @endif
                            @endforeach
                        @endforeach
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.role.index') }}" class="btn float-end my-4"
                                style="background-color: #091E3E;color: white">Back</a>
                            <button type="submit" id="Save" class="btn float-end my-4"
                                style="background-color: #091E3E;color: white">
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
@section('links_content_foot')
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
            $('#selectall').change(function() {
                if ($(this).prop('checked')) {
                    // $('.check').prop('checked', true);
                    $('.check').attr('checked', true);
                    $('.check').val(this.checked ? 1 : 0);
                } else {
                    // $('.check').prop('checked', false);
                    $('.check').attr('checked', false);
                    $('.check').val(this.checked ? 1 : 0);
                }
            });
            $('.row_check').change(function() {
                var _this = $(this).parents('tr');
                if ($(this).is(':checked')) {
                    $(this).attr("checked", true)
                    $(this).val(this.checked ? 1 : 0);
                    // $(_this.find('.row_child_check')).prop('checked', true);
                    $(_this.find('.row_child_check')).attr('checked', true);
                    $(_this.find('.row_child_check')).val(this.checked ? 1 : 0);
                } else {
                    $(this).attr('checked', false);
                    $(this).val(this.checked ? 1 : 0);
                    // $(_this.find('.row_child_check')).prop('checked', false);
                    $(_this.find('.row_child_check')).attr('checked', false);
                    $(_this.find('.row_child_check')).val(this.checked ? 1 : 0);
                }
            });
        });
        // $(document).on('change', '#selectall', function() {
        //     if ($(this).prop('checked')) {
        //         $('.check').prop('checked', true)
        //         $('input:checkbox').attr('checked', true);
        //         $('.check').val(this.checked ? 1 : 0);
        //     } else {
        //         $('.check').prop('checked', false);
        //         $('input:checkbox').attr('checked', false);
        //         $('.check').val(this.checked ? 1 : 0);
        //     }
        // });
        // $(document).on('change', '.row_check', function() {
        //     var _this = $(this).parents('tr');
        //      if ($(this).is(':checked')) {
        //         $(this).attr("checked", true)
        //         $(this).val(this.checked ? 1 : 0);
        //         $(_this.find('.row_child_check')).prop('checked', true);
        //         $(_this.find('.row_child_check')).attr('checked', true);
        //         $(_this.find('.row_child_check')).val(this.checked ? 1 : 0);
        //     } else {
        //         $(this).attr('checked', false);
        //         $(this).val(this.checked ? 1 : 0);
        //         $(_this.find('.row_child_check')).prop('checked', false);
        //         $(_this.find('.row_child_check')).attr('checked', false);
        //         $(_this.find('.row_child_check')).val(this.checked ? 1 : 0);
        // }
        // });
    </script>
@endsection
@endsection
