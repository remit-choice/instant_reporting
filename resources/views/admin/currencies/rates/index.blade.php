@extends('layouts.admin.master')
@section('content')
@section('links_content_head')
    @Include('layouts.links.datatable.head')
    @Include('layouts.links.toastr.head')
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
                    <div class="card-header container-fluid">
                        <form action="{{ route('admin.currencies.rates.index') }}" method="post">
                            @csrf
                            <div class="row d-flex justify-content-center">
                                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                    <label>Date</label>
                                    @if (request()->input('date'))
                                        <input type="date" name="date" id="" class="form-control"
                                            value="{{ request()->input('date', old('date')) }}" style="width: 100%">
                                    @else
                                        <input type="date" name="date" id="" class="form-control"
                                            value="@php echo \Carbon\Carbon::now()->format('Y-m-d'); @endphp"
                                            style="width: 100%">
                                    @endif
                                </div>
                                <div class="form-group col-lg-1 col-md-1 col-sm-3 col-xs-3">
                                    <label class="invisible">Filter</label>
                                    <button type="submit" name="filter" class="btn form-control"
                                        style="background-color: #091E3E;color: white">Submit</button>
                                </div>
                            </div>

                        </form>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-capitalize">#</th>
                                    <th class="text-capitalize">name</th>
                                    <th class="text-capitalize">iso</th>
                                    <th class="text-capitalize">iso3</th>
                                    <th class="text-capitalize">dial</th>
                                    <th class="text-capitalize">currency</th>
                                    <th class="text-capitalize">currency_name</th>
                                    <th class="text-capitalize">min_rate</th>
                                    <th class="text-capitalize">max_rate</th>
                                    <th class="text-capitalize">rate</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $counter = 1;
                                @endphp
                                @foreach ($currencies as $currency)
                                    <tr>
                                        <td>{{ $counter++ }} <input type="hidden" name="db_currency_id"
                                                class="db_currency_id" value="{{ $currency->id }}">
                                        </td>
                                        <td>{{ $currency->name }}</td>
                                        <td>{{ $currency->iso }}<input type="hidden" name="db_currency_iso"
                                                class="db_currency_iso" value="{{ $currency->iso }}">
                                        </td>
                                        <td>{{ $currency->iso3 }}
                                            <input type="hidden" name="db_currency_iso3" class="db_currency_iso3"
                                                value="{{ $currency->iso3 }}">
                                        </td>
                                        <td>{{ $currency->dial }}</td>
                                        <td>{{ $currency->currency }}
                                            <input type="hidden" name="db_currency" class="db_currency"
                                                value="{{ $currency->currency }}">
                                        </td>
                                        <td>{{ $currency->currency_name }}</td>
                                        <td id="min_rate{{ $currency->id }}">
                                            {{ $currency->min_rate }} <input type="hidden" name="db_currency_min_rate"
                                                class="db_currency_min_rate" value="{{ $currency->min_rate }}"></td>
                                        <td id="max_rate{{ $currency->id }}">
                                            {{ $currency->max_rate }} <input type="hidden" name="db_currency_max_rate"
                                                class="db_currency_max_rate" value="{{ $currency->max_rate }}"></td>
                                        @if (!$currency->rates->isEmpty())
                                            @foreach ($currency->rates as $rate)
                                                @if ($rate->status == 1)
                                                    <td id="rate{{ $rate->id }}">
                                                        {{ $rate->rate }}
                                                        <input type="hidden" name="db_currency_rate_id"
                                                            class="db_currency_rate_id" value="{{ $rate->id }}">
                                                        <input type="hidden" name="db_currency_rate"
                                                            class="db_currency_rate" value="{{ $rate->rate }}">
                                                    </td>
                                                @endif
                                            @endforeach
                                        @else
                                            <td></td>
                                        @endif
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    Action
                                                </button>
                                                @if ($edit == 1 && !$currency->rates->isEmpty())
                                                    <div class="dropdown-menu edit_currency"
                                                        aria-labelledby="dropdownMenuButton">
                                                        <li>
                                                            <a class="dropdown-item" type="button" href="#"
                                                                data-toggle="modal" data-target="#edit_modal">
                                                                Edit Rate</a>
                                                        </li>
                                                    </div>
                                                @else
                                                    @if ($create == 1)
                                                        <div class="dropdown-menu edit_currency"
                                                            aria-labelledby="dropdownMenuButton">
                                                            <li>
                                                                <a class="dropdown-item" type="button" href="#"
                                                                    data-toggle="modal" data-target="#create_modal">
                                                                    Add Rate</a>
                                                            </li>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-capitalize">#</th>
                                    <th class="text-capitalize">name</th>
                                    <th class="text-capitalize">iso</th>
                                    <th class="text-capitalize">iso3</th>
                                    <th class="text-capitalize">dial</th>
                                    <th class="text-capitalize">currency</th>
                                    <th class="text-capitalize">currency_name</th>
                                    <th class="text-capitalize">min_rate</th>
                                    <th class="text-capitalize">max_rate</th>
                                    <th class="text-capitalize">rate</th>
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
<form action="{{ route('admin.currencies.rates.create') }}" method="POST" id="create_modal_form"
    autocomplete="off">
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
                    <input type="hidden" name="create_currency_id" id="create_currency_id" value="">
                    <input type="hidden" name="create_currency" id="create_currency" value="">

                    <div class="form-group row">
                        <div class="col-6">
                            <label for="create_currency_min_rate" class="text-capitalize">min
                                rate</label>
                            <input type="text" name="create_currency_min_rate" id="create_currency_min_rate"
                                class="form-control" readonly>
                        </div>
                        <div class="col-6">
                            <label for="create_currency_max_rate" class="text-capitalize">max
                                rate</label>
                            <div class="input-group">
                                <input type="text" name="create_currency_max_rate" id="create_currency_max_rate"
                                    class="form-control" readonly>
                                <input type="hidden" name="create_currency_iso" id="create_currency_iso"
                                    value="">
                                <input type="hidden" name="create_currency_iso3" id="create_currency_iso3"
                                    value="">
                                <input type="hidden" name="create_currency" value="">
                            </div>
                            <span class="invalid-feedback" id="create_currency_max_rate_error">
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="create_currency_rate" class="text-capitalize">rate</label>

                        <div class="input-group">
                            <input type="text" name="create_currency_rate" id="create_currency_rate"
                                class="form-control">
                        </div>
                        <span class="invalid-feedback" id="create_currency_rate_error">
                        </span>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary create">Create</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form>
{{-- Add Modal End --}}
{{-- Edit Modal Start --}}
<form action="{{ route('admin.currencies.rates.edit') }}" method="post" autocomplete="off" id="edit_modal_form">
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
                    <input type="hidden" name="currency_id" id="currency_id" value="">
                    <input type="hidden" name="currency_rate_id" id="currency_rate_id" value="">
                    <div class="form-group row">
                        <div class="col-6">
                            <label for="currency_min_rate" class="text-capitalize">min rate</label>
                            <input type="text" name="currency_rate" id="currency_min_rate" class="form-control"
                                readonly>
                        </div>
                        <div class="col-6">
                            <label for="currency_max_rate" class="text-capitalize">max rate</label>
                            <input type="text" name="currency_rate" id="currency_max_rate" class="form-control"
                                readonly>
                        </div>
                        <input type="hidden" name="currency_iso" id="currency_iso" value="">
                        <input type="hidden" name="currency_iso3" id="currency_iso3" value="">
                        <input type="hidden" name="currency" id="currency" value="">
                    </div>
                    <div class="form-group">
                        <label for="currency_rate" class="text-capitalize">rate</label>
                        <div class="input-group">
                            <input type="text" name="currency_rate" id="currency_rate" class="form-control">
                        </div>
                        <span class="invalid-feedback" id="currency_max_rate_error">
                        </span>
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
        $('.create_currency').on('click', function() {
            var _this = $(this).parents('tr');
            $('#create_currency_min_rate').val(_this.find('.db_currency_min_rate').val());
            $('#create_currency_max_rate').val(_this.find('.db_currency_max_rate').val());
            $('#create_currency_rate').val(_this.find('.db_currency_rate').val());
            $('#create_currency_id').val(_this.find('.db_currency_id').val());
            $('#create_currency_iso').val(_this.find('.db_currency_iso').val());
            $('#create_currency_iso3').val(_this.find('.db_currency_iso3').val());
            $('#create_currency').val(_this.find('.db_currency').val());
            console.log($('#create_currency').val());
        });
        $('.edit_currency').on('click', function() {
            var _this = $(this).parents('tr');
            $('#currency_min_rate').val(_this.find('.db_currency_min_rate').val());
            $('#currency_max_rate').val(_this.find('.db_currency_max_rate').val());
            $('#currency_rate').val(_this.find('.db_currency_rate').val());
            $('#currency_id').val(_this.find('.db_currency_id').val());
            $('#currency_rate_id').val(_this.find('.db_currency_rate_id').val());
            $('#currency_iso').val(_this.find('.db_currency_iso').val());
            $('#currency_iso3').val(_this.find('.db_currency_iso3').val());
            $('#currency').val(_this.find('.db_currency').val());
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
                var c_id = $('#create_currency_id').val();
                var min_rate = $('#create_currency_min_rate').val();
                var max_rate = $('#create_currency_max_rate').val();
                var rate = $('#create_currency_rate').val();
                var date = $('#date').val();
                var iso = $('#create_currency_iso').val();
                var iso3 = $('#create_currency_iso3').val();
                var currency = $('#create_currency').val();

                console.log(c_id);
                console.log(min_rate);
                console.log(max_rate);
                console.log(rate);
                console.log(date);
                console.log(iso);
                console.log(iso3);
                console.log(currency);

                if (rate != '' && min_rate != '' && max_rate != '' && date != '' && rate >= min_rate &&
                    rate <= max_rate) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "post",
                        url: "{{ route('admin.currencies.rates.create') }}",
                        data: {
                            "c_id": c_id,
                            "min_rate": min_rate,
                            "max_rate": max_rate,
                            "rate": rate,
                            "date": date,
                            "iso": iso,
                            "iso3": iso3,
                            "currency": currency,
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
                    if (min_rate == '' && max_rate == '') {
                        $('#failes').show();
                        $('#failes .text_fails').html(
                            "Rate limit Not Declared");
                        window.setInterval(function() {
                            $('#failes').slideUp('slow');
                        }, 5000);
                    } else {
                        $('#failes').show();
                        $('#failes .text_fails').html(
                            "Date Not Selected");
                        window.setInterval(function() {
                            $('#failes').slideUp('slow');
                        }, 5000);
                    }
                    if (rate == '') {
                        $('#create_currency_max_rate_error').show();
                        $('#create_currency_max_rate_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#create_currency_max_rate_error').slideUp('slow');
                            $('#create_currency_max_rate_error').empty();
                        }, 5000);
                    }
                    if (rate < min_rate) {
                        $('#failes').show();
                        $('#failes .text_fails').html(
                            "Rate is below from minimum rate!");
                        window.setInterval(function() {
                            $('#failes').slideUp('slow');
                            $('#failes .text_fails').empty();
                        }, 5000);
                    } else if (rate > min_rate) {
                        $('#failes').show();
                        $('#failes .text_fails').html(
                            "Rate is above from maximum rate!");
                        window.setInterval(function() {
                            $('#failes').slideUp('slow');
                            $('#failes .text_fails').empty();
                        }, 5000);
                    } else {}

                }

            });
            $('.update').click(function(e) {
                e.preventDefault();
                var id = $('#currency_rate_id').val();
                var c_id = $('#currency_id').val();
                var min_rate = $('#currency_min_rate').val();
                var max_rate = $('#currency_max_rate').val();
                var rate = $('#currency_rate').val();
                var date = $('#date').val();
                var iso = $('#currency_iso').val();
                var iso3 = $('#currency_iso3').val();
                var currency = $('#currency').val();


                console.log(id);
                console.log(c_id);
                console.log(min_rate);
                console.log(max_rate);
                console.log(rate);
                console.log(date);
                if (rate != '' && min_rate != '' && max_rate != '' && date != '' && rate >= min_rate &&
                    rate <= max_rate) {
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
                                url: "{{ route('admin.currencies.rates.edit') }}",
                                data: {
                                    "id": id,
                                    "c_id": c_id,
                                    "min_rate": min_rate,
                                    "max_rate": max_rate,
                                    "rate": rate,
                                    "date": date,
                                    "iso": iso,
                                    "iso3": iso3,
                                    "currency": currency,
                                },
                                success: function(response) {
                                    if (response == '1') {
                                        Swal.fire(
                                            'Updated!',
                                            'Data Successfully Updated.!',
                                            'success'
                                        ).then((result) => {
                                            location.reload();

                                        });

                                    }
                                },
                                error: (error) => {
                                    console.log(JSON.stringify(error));
                                }
                            });

                        }
                    });
                } else {
                    if (min_rate == '' && max_rate == '') {
                        $('#update_failes').show();
                        $('#update_failes .text_fails').html(
                            "Rate limit Not Declared");
                        window.setInterval(function() {
                            $('#update_failes').slideUp('slow');
                        }, 5000);
                    } else {
                        $('#update_failes').show();
                        $('#update_failes .text_fails').html(
                            "Date Not Selected");
                        window.setInterval(function() {
                            $('#update_failes').slideUp('slow');
                        }, 5000);
                    }
                    if (rate == '') {
                        $('#currency_max_rate_error').show();
                        $('#currency_max_rate_error').html(
                            "Required!");
                        window.setInterval(function() {
                            $('#currency_max_rate_error').slideUp('slow');
                            $('#currency_max_rate_error').empty();
                        }, 5000);
                    }
                    if (rate < min_rate) {
                        $('#update_failes').show();
                        $('#update_failes .text_fails').html(
                            "Rate is below from minimum rate!");
                        window.setInterval(function() {
                            $('#update_failes').slideUp('slow');
                            $('#update_failes .text_fails').empty();
                        }, 5000);
                    } else if (rate > min_rate) {
                        $('#update_failes').show();
                        $('#update_failes .text_fails').html(
                            "Rate is above from maximum rate!");
                        window.setInterval(function() {
                            $('#update_failes').slideUp('slow');
                            $('#update_failes .text_fails').empty();
                        }, 5000);
                    } else {}

                }

            });
        });
    </script>
@endsection
@endsection
