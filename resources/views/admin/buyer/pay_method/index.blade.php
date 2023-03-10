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
                                    <th class="text-capitalize">Payment Method</th>
                                    <th class="text-capitalize">Country</th>
                                    <th class="text-capitalize">Currency</th>
                                    <th class="text-capitalize">Charges</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $counter = 1;
                                @endphp
                                @foreach ($buyers as $buyer)
                                    @foreach ($buyer->buyer_payment_methods as $buyer_payment_method)
                                        <tr>
                                            <td>{{ $counter++ }} <input type="hidden" name="db_buyer_id"
                                                    class="db_buyer_id" value="{{ $buyer->id }}">
                                                <input type="hidden" name="db_buyer_payment_method_id"
                                                    class="db_buyer_payment_method_id"
                                                    value="{{ $buyer_payment_method->id }}">
                                            </td>
                                            <td>{{ $buyer_payment_method['payment_methods']->name }}<input
                                                    type="hidden" name="" class="db_payment_method_id"
                                                    value="{{ $buyer_payment_method['payment_methods']->id }}"><input
                                                    type="hidden" name="" class="db_payment_method_name"
                                                    value="{{ $buyer_payment_method['payment_methods']->name }}"></td>
                                            <td>{{ $buyer_payment_method->country }}<input type="hidden" name=""
                                                    class="db_payment_method_country"
                                                    value="{{ $buyer_payment_method->country }}"></td>
                                            <td>{{ $buyer_payment_method['currencies']->iso3 }}<input type="hidden"
                                                    name="" class="db_c_id"
                                                    value="{{ $buyer_payment_method['currencies']->id }}"></td>
                                            <td>{{ $buyer_payment_method->rate }}<input type="hidden" name=""
                                                    class="db_buyer_payment_method_rate"
                                                    value="{{ $buyer_payment_method->rate }}"></td>
                                            <td>
                                                @if ($edit == 1 || $delete == 1)
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                                            id="dropdownMenuButton" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <div class="dropdown-menu edit_buyer_pay_method"
                                                            aria-labelledby="dropdownMenuButton">
                                                            @if ($edit == 1)
                                                                <li>
                                                                    <a class="dropdown-item" type="button"
                                                                        href="#" data-toggle="modal"
                                                                        data-target="#edit_modal_form">
                                                                        Edit</a>
                                                                </li>
                                                            @endif
                                                            @if ($delete == 1)
                                                                <li>
                                                                    <form
                                                                        action="{{ route('admin.buyer.pay_method.delete', ['id' => $id]) }}"
                                                                        method="POST"
                                                                        class="buyer_pay_method_delete_form">
                                                                        @csrf
                                                                        <input type="hidden" name="id"
                                                                            class="id"
                                                                            value="{{ $buyer_payment_method->id }}">
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
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-capitalize">#</th>
                                    <th class="text-capitalize">Payment Method</th>
                                    <th class="text-capitalize">Country</th>
                                    <th class="text-capitalize">Currency</th>
                                    <th class="text-capitalize">Charges</th>
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
{{-- Add Modal Start --}}
<form action="{{ route('admin.buyer.pay_method.create', ['id' => $id]) }}" method="POST" id="create_modal_form">
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
                    <div class="currency_row">
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="buyer_name" class="text-capitalize d-flex justify-content-between">Name<i
                                        class="fas fa-plus rounded-pill bg-primary p-1 append_button"
                                        role="button"></i></label>
                                <input type="hidden" name="buyer_id" value="{{ $id }}">
                                <select class="form-control" name="payment_methods[]" required>
                                    <option selected hidden disabled>SELECT</option>
                                    @foreach ($payment_methods as $payment_method)
                                        <option value="{{ $payment_method->id }}">{{ $payment_method->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback" id="create_buyer_name_error">
                                </span>
                            </div>
                            <div class="form-group col-12">
                                <label for="buyer_name"
                                    class="text-capitalize d-flex justify-content-between">Country</label>
                                <input type="hidden" name="buyer_id" value="{{ $id }}">
                                <select class="form-control" name="countries[]" required>
                                    <option selected hidden disabled>SELECT</option>
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency->name }}">{{ $currency->name }}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback" id="create_buyer_name_error">
                                </span>
                            </div>
                            <div class="form-group col-6">
                                <label for="buyer_name" class="text-capitalize">Currency</label>
                                <select class="form-control" name="currencies[]" required>
                                    <option selected hidden disabled>SELECT</option>
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency->id }}">{{ $currency->name }} |
                                            {{ $currency->iso3 }}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback" id="create_buyer_name_error">
                                </span>
                            </div>
                            <div class="form-group col-6">
                                <label for="buyer_name" class="text-capitalize">Rate</label>
                                <input type="number" name="rates[]" id="" class="form-control"
                                    step="any" required>
                                <span class="invalid-feedback" id="create_buyer_name_error">
                                </span>
                            </div>
                        </div>
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
<form action="{{ route('admin.buyer.pay_method.edit', ['id' => $id]) }}" method="POST" id="edit_modal_form">
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
                    <div class="row">
                        <div class="form-group col-12">
                            <label for="buyer_name" class="text-capitalize">Name</label>
                            <input type="hidden" name="buyer_id" value="{{ $id }}">
                            <input type="hidden" name="id" id="edit_buyer_payment_method_id" value="">
                            {{-- <select class="form-control" name="payment_methods" id="edit_payment_method_id" required>
                                <option selected hidden disabled>SELECT</option>
                                @foreach ($payment_methods as $payment_method)
                                    <option value="{{ $payment_method->id }}">{{ $payment_method->name }}</option>
                                @endforeach
                            </select> --}}
                            <input type="text" id="edit_payment_method_name" class="form-control" readonly>
                            <input type="hidden" name="payment_methods" id="edit_payment_method_id"
                                class="form-control">
                            <span class="invalid-feedback" id="edit_buyer_name_error">
                            </span>
                        </div>
                        <div class="form-group col-12">
                            <label for="buyer_name"
                                class="text-capitalize d-flex justify-content-between">Country</label>
                            <input type="hidden" name="buyer_id" value="{{ $id }}">
                            <select class="form-control" name="countries" id="edit_payment_method_countries"
                                required>
                                <option selected hidden disabled>SELECT</option>
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency->name }}">{{ $currency->name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback" id="create_buyer_name_error">
                            </span>
                        </div>
                        <div class="form-group col-6">
                            <label for="buyer_name" class="text-capitalize">Currency</label>
                            <select class="form-control" name="currencies" id="edit_buyer_payment_method_currency"
                                required>
                                <option selected hidden disabled>SELECT</option>
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->name }} |
                                        {{ $currency->iso3 }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback" id="edit_buyer_name_error">
                            </span>
                        </div>
                        <div class="form-group col-6">
                            <label for="buyer_name" class="text-capitalize">Rate</label>
                            <input type="number" name="rates" id="edit_buyer_payment_method_rate"
                                class="form-control" step="any" required>
                            <span class="invalid-feedback" id="edit_buyer_name_error">
                            </span>
                        </div>
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
    @Include('layouts.links.datatable.foot')
    @Include('layouts.links.sweet_alert.foot')
<script type="text/javascript">
    $('.edit_buyer_pay_method').on('click', function() {
        var _this = $(this).parents('tr');
        $('#edit_buyer_id').val(_this.find('.db_buyer_id').val());
        $('#edit_buyer_payment_method_id').val(_this.find('.db_buyer_payment_method_id').val());
        $('#edit_payment_method_id').val(_this.find('.db_payment_method_id').val());
        $('#edit_payment_method_name').val(_this.find('.db_payment_method_name').val());
        $('#edit_payment_method_countries').val(_this.find('.db_payment_method_country').val());
        $('#edit_buyer_payment_method_currency').val(_this.find('.db_c_id').val());
        $('#edit_buyer_payment_method_rate').val(_this.find('.db_buyer_payment_method_rate').val());
    });
    $('.append_button').on('click', function() {
        var _this = $(this).parents('label').parents('div').parents('div');
        $('.currency_row').append(
            '<div class="row"><div class="form-group col-12"><label for="buyer_name" class="text-capitalize d-flex justify-content-between">Name<i class="fas fa-minus rounded-pill bg-danger p-1 minus_button" role="button"></i></label><div class="input-group"><input type="hidden" name="create_buyer_id" id="create_buyer_id" value=""><select class="form-control" name="payment_methods[]" required><option selected hidden disabled>SELECT</option>@foreach ($payment_methods as $payment_method)<option value="{{ $payment_method->id }}">{{ $payment_method->name }}</option>@endforeach</select></div><span class="invalid-feedback" id="create_buyer_name_error"></span></div><div class="form-group col-12"><label for="buyer_name" class="text-capitalize d-flex justify-content-between">Country</label><input type="hidden" name="buyer_id" value="{{ $id }}"><select class="form-control" name="countries[]" required><option selected hidden disabled>SELECT</option>@foreach ($currencies as $currency)<option value="{{ $currency->name }}">{{ $currency->name }}</option>@endforeach</select><span class="invalid-feedback" id="create_buyer_name_error"></span></div><div class="form-group col-6"><label for="buyer_name" class="text-capitalize">Currency</label><select class="form-control" name="currencies[]" required><option selected hidden disabled>SELECT</option>@foreach ($currencies as $currency)<option value="{{ $currency->id }}">{{ $currency->name }} | {{ $currency->iso3 }}</option>@endforeach</select><span class="invalid-feedback" id="create_buyer_name_error"></span></div><div class="form-group col-6"><label for="buyer_name" class="text-capitalize">Rate</label><input type="number" name="rates[]" step="any" id="" class="form-control" required><span class="invalid-feedback" id="create_buyer_name_error"></span></div></div>'
        )
    });
    $('body').on('click', '.minus_button', function() {
        var _this = $(this).closest('.row').remove();
    });

    $(function() {
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        $('#create_modal_form').submit(function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "post",
                url: $(this).attr('action'),
                data: $(this).serialize(),
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

        $('#edit_modal_form').submit(function(e) {
            e.preventDefault();
            $('#edit_buyer_payment_method_id').val();
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
                        url: $(this).attr('action'),
                        data: $(this).serialize(),
                        success: function(response) {
                            $('#edit_payment_method_id').prop('disabled', true);
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
        });
        $('.buyer_pay_method_delete_form').submit(function(e) {
            e.preventDefault();
            var el = this;
            var id = $(this).closest("tr").find('.id').val();
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
                        url: $(this).attr('action'),
                        data: $(this).serialize(),
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
