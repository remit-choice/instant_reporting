<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@Include('layouts.links.admin.title') | Currencies Rates</title>
    <style>
        .flex-wrap {
            float: right !important;
        }

        .cur-rate>td {
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
        }

        .dropdown-menu {
            min-width: 0 !important;
            padding: 0.375rem 0.75rem !important;
        }
    </style>
    @Include('layouts.links.admin.head')
    @Include('layouts.links.datatable.head')
    @Include('layouts.links.toastr.head')
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
                            <h1 class="m-0">Currencies</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Currencies</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="modal fade" id="currency_rate_create">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Currency Rate</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('admin.currencies.rates.create') }}" method="POST"
                                        id="currency_rate_create_form" autocomplete="off">
                                        @csrf
                                        <div class="modal-body">
                                            <div id="failes"
                                                class="alert alert-default-danger alert-dismissible fade show"
                                                role="alert" style="display: none">
                                                <span class="text_fails"></span>
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <input type="hidden" name="create_currency_id" id="create_currency_id"
                                                value="">
                                            <div class="form-group row">
                                                <div class="col-6">
                                                    <label for="create_currency_min_rate" class="text-capitalize">min
                                                        rate</label>
                                                    <input type="text" name="create_currency_min_rate"
                                                        id="create_currency_min_rate" class="form-control" readonly>
                                                </div>
                                                <div class="col-6">
                                                    <label for="create_currency_max_rate" class="text-capitalize">max
                                                        rate</label>
                                                    <div class="input-group">
                                                        <input type="text" name="create_currency_max_rate"
                                                            id="create_currency_max_rate" class="form-control" readonly>
                                                        <input type="hidden" name="create_currency_iso"
                                                            id="create_currency_iso" value="">
                                                        <input type="hidden" name="create_currency_iso3"
                                                            id="create_currency_iso3" value="">
                                                        <input type="hidden" name="create_currency" value="">
                                                    </div>
                                                    <span class="invalid-feedback" id="create_currency_max_rate_error">
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="create_currency_rate" class="text-capitalize">rate</label>

                                                <div class="input-group">
                                                    <input type="text" name="create_currency_rate"
                                                        id="create_currency_rate" class="form-control">
                                                </div>
                                                <span class="invalid-feedback" id="create_currency_rate_error">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary create">Create</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                    </div><!-- /.container-fluid -->
                    <div id="success" class="alert alert-default-success alert-dismissible fade show" role="alert"
                        style="display: none">
                        <span class="text_success"></span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <!-- /.content-header -->

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <!-- Small boxes (Stat box) -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card w-100">
                                    <div class="card-header w-100 d-flex justify-content-between">
                                        <div class="col-6">
                                            <h3 class="card-title">Currencies List</h3>
                                        </div>
                                        <div class="col-3">
                                        </div>
                                        <form action="{{ route('admin.currencies.rates.filter') }}" method="post"
                                            class="col-3 float-right d-flex flex-row">
                                            @csrf
                                            <input type="date" name="date" id="filter_date" class="form-control mr-2"
                                                value="@php if (session()->has('dated')){echo session()->get('dated'); }else{echo \Carbon\Carbon::now()->format('Y-m-d');} @endphp">
                                            <button type="submit" name="filter" class="btn mb-2"
                                                style="background-color: #091E3E;color: white">Submit</button>
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
                                                        <input type="hidden" name="db_currency_iso3"
                                                            class="db_currency_iso3" value="{{ $currency->iso3 }}">
                                                    </td>
                                                    <td>{{ $currency->dial }}</td>
                                                    <td>{{ $currency->currency }}
                                                        <input type="hidden" name="db_currency" class="db_currency"
                                                            value="{{ $currency->currency }}">
                                                    </td>
                                                    <td>{{ $currency->currency_name }}</td>
                                                    <td id="min_rate{{ $currency->id }}">
                                                        {{ $currency->min_rate }} <input type="hidden"
                                                            name="db_currency_min_rate" class="db_currency_min_rate"
                                                            value="{{ $currency->min_rate }}"></td>
                                                    <td id="max_rate{{ $currency->id }}">
                                                        {{ $currency->max_rate }} <input type="hidden"
                                                            name="db_currency_max_rate" class="db_currency_max_rate"
                                                            value="{{ $currency->max_rate }}"></td>
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
                                                            <button class="btn btn-secondary dropdown-toggle"
                                                                type="button" id="dropdownMenuButton"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                Action
                                                            </button>
                                                            @if (!$currency->rates->isEmpty())
                                                            <div class="dropdown-menu edit_currency"
                                                                aria-labelledby="dropdownMenuButton">
                                                                <a class="dropdown-item" type="button" href="#"
                                                                    data-toggle="modal"
                                                                    data-target="#currency_rate_update">
                                                                    Edit Rate</a>
                                                            </div>
                                                            @else
                                                            <div class="dropdown-menu create_currency"
                                                                aria-labelledby="dropdownMenuButton">
                                                                <a class="dropdown-item" type="button" href="#"
                                                                    data-toggle="modal"
                                                                    data-target="#currency_rate_create">
                                                                    Add Rate</a>
                                                            </div>
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
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
        </div>
        <div class="modal fade" id="currency_rate_update">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Currency Rate</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.currencies.rates.update') }}" method="post" autocomplete="off">
                        @csrf
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
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        @Include('layouts.links.admin.foot')
        @Include('layouts.links.datatable.foot')
        @Include('layouts.links.sweet_alert.foot')
        <script type="text/javascript">
            $(function() {
	var Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 3000
	});
	$('.create_currency').on('click', function() {
		var _this = $(this).parents('tr');
		$('#create_currency_min_rate').val(_this.find('.db_currency_min_rate').val());
		$('#create_currency_max_rate').val(_this.find('.db_currency_max_rate').val());
		$('#create_currency_rate').val(_this.find('.db_currency_rate').val());
		$('#create_currency_id').val(_this.find('.db_currency_id').val());
		$('#create_currency_iso').val(_this.find('.db_currency_iso').val());
		$('#create_currency_iso3').val(_this.find('.db_currency_iso3').val());
		$('#create_currency').val(_this.find('.db_currency').val());
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
	$('.create').click(function(e) {
		e.preventDefault();
		var c_id = $('#create_currency_id').val();
		var min_rate = $('#create_currency_min_rate').val();
		var max_rate = $('#create_currency_max_rate').val();
		var rate = $('#create_currency_rate').val();
		var date = $('#filter_date').val();
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

		if (rate != '' && min_rate != '' && max_rate != '' && date != '' && rate >= min_rate && rate <= max_rate) {
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
		var date = $('#filter_date').val();
		var iso = $('#currency_iso').val();
		var iso3 = $('#currency_iso3').val();
		var currency = $('#currency').val();


		console.log(id);
		console.log(c_id);
		console.log(min_rate);
		console.log(max_rate);
		console.log(rate);
		console.log(date);
		if (rate != '' && min_rate != '' && max_rate != '' && date != '' && rate >= min_rate && rate <= max_rate) {
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
							url: "{{ route('admin.currencies.rates.update') }}",
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
</body>

</html>