<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@Include('layouts.links.admin.title') | Transactions</title>
    <style>
        .dt-buttons {
            float: right !important;
        }

        .cur-rate>td {
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
        }

        #example1 {
            width: 100% !important;
        }

        .table-responsive {
            display: inline-table !important;
        }

        input[type=date]:focus {
            outline: none;
        }

        .select2-container--default .select2-selection--single {
            height: calc(2.25rem + 2px) !important;
        }

        #search_filter:focus {
            outline: none;
        }
    </style>
    @Include('layouts.favicon')
    @Include('layouts.links.admin.head')
    @Include('layouts.links.datatable.head')
    @Include('layouts.links.toastr.head')
    <script>
        setTimeout(function() {
            $('#failed').slideUp('slow');
        }, 3000);
    </script>
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
                                <h1 class="m-0">Transactions</h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Transactions</li>
                                </ol>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        @if (session('failed'))
                            <div id="failed" class="alert alert-default-danger alert-dismissible fade show"
                                role="alert">
                                <strong>{{ session('failed') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <!-- Small boxes (Stat box) -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title col-lg-6 col-md-6 col-sm-6 col-xs-6">Transactions List</h3>
                                    </div>
                                    <div class="card-header container-fluid">
                                        <form action="{{ route('admin.operations.transactions.customers') }}"
                                            method="post">
                                            @csrf
                                            <div class="row d-flex justify-content-center">
                                                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                    <label>Sending Country</label>
                                                    <select class="form-control countries_filter" name="customer_country" id="customer_country"
                                                        style="width: 100%">
                                                        <option class="py-1" hidden selected
                                                            value="{{ request()->input('customer_country', old('customer_country')) }}">
                                                            @if (request()->input('customer_country'))
                                                                {{ request()->input('customer_country', old('customer_country')) }}
                                                            @else
                                                                SELECT
                                                            @endif
                                                        </option>
                                                        <option value="" id="customer_country_clear" class="text-center">Reset</option>
                                                        <option value="All Customer Countries">All Customer Countries</option>
                                                        @foreach ($sending_currencies as $sending_currency)
                                                            <option value="{{ $sending_currency->name }}" class="py-1">{{ $sending_currency->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                    <label>Receiving Country</label>
                                                    <select class="form-control" name="beneficiary_country" id="beneficiary_country"
                                                        style="width: 100%">
                                                        <option class="py-1" hidden selected
                                                            value="{{ request()->input('beneficiary_country', old('beneficiary_country')) }}">
                                                            @if (request()->input('beneficiary_country'))
                                                                {{ request()->input('beneficiary_country', old('beneficiary_country')) }}
                                                            @else
                                                                SELECT
                                                            @endif
                                                        </option>
                                                        <option value="" id="beneficiary_country_clear" class="text-center">Reset</option>
                                                        <option value="All Beneficiary Countries">All Beneficiary Country</option>
                                                        @foreach ($receiving_currencies as $receiving_currency)
                                                            <option value="{{ $receiving_currency->name }}" class="py-1">{{ $receiving_currency->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                    <label>From Date</label>
                                                    @if (request()->input('date_from'))
                                                        <input type="date" name="date_from" id=""
                                                            class="form-control"
                                                            value="{{ request()->input('date_from', old('date_from')) }}"
                                                            style="width: 100%">
                                                    @else
                                                        <input type="date" name="date_from" id=""
                                                            class="form-control" value="" style="width: 100%">
                                                    @endif
                                                </div>
                                                 <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                    <label>To Date</label>
                                                    @if (request()->input('date_to'))
                                                        <input type="date" name="date_to" id=""
                                                            class="form-control"
                                                            value="{{ request()->input('date_to', old('date_to')) }}"
                                                            style="width: 100%">
                                                    @else
                                                        <input type="date" name="date_to" id=""
                                                            class="form-control" value="" style="width: 100%">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row d-flex justify-content-center">
                                                <button type="submit" name="filter" class="btn mb-1"
                                                    style="background-color: #091E3E;color: white">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <table id="example1"
                                            class="table table-bordered @php if(!empty($customers)){echo "table-responsive";}else{} @endphp">
                                            <thead>
                                                <tr>
                                                    <th class="no-sort">#</th>
                                                    <th class="text-capitalize">Date</th>
                                                    <th class="text-capitalize">Transacting</th>
                                                    <th class="text-capitalize">Non Transacting</th>
                                                    <th class="text-capitalize">No Attempt</th>
                                                    <th class="text-capitalize">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($customers))
                                                        @php
                                                            $count = 1;
                                                            $total_count_of_tr_no_date= 0;
                                                            $total_count_of_tr_no= 0;
                                                        @endphp
                                                        @foreach ($customers as $customer_reg_date => $customer)
                                                            <tr data-widget="expandable-table" aria-expanded="false" role='button' class="bg-light">
                                                                <td><i class="expandable-table-caret fas fa-caret-right fa-fw"></i>{{$count++}}</td>
                                                                <td>{{  $customer_reg_date }}</td>                                                                        
                                                                    <td>{{ $customer->transacting_count }}</td>
                                                                <td>{{  $customer->non_transacting_count }}</td>
                                                                <td>{{  $customer->no_attempt_count }}</td>
                                                                <td>{{ $customer->transacting_count + $customer->non_transacting_count + $customer->no_attempt_count  }}</td>
                                                                @php
                                                                    $total_count_of_tr_no_date += $customer->transacting_count + $customer->non_transacting_count + $customer->no_attempt_count;
                                                                @endphp
                                                            </tr>
                                                            <tr class="expandable-body">
                                                                <td colspan="6">
                                                                    <table class="table table-hover">
                                                                        <thead>
                                                                                <tr>
                                                                                    <th>#</th>
                                                                                    <th>Customer Country</th>
                                                                                        <th class="text-capitalize">Transacting</th>
                                                                                    <th class="text-capitalize">Non Transacting</th>
                                                                                    <th class="text-capitalize">No Attempt</th>
                                                                                    <th class="text-capitalize">Total</th>
                                                                                </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @php
                                                                                $counts1 = 1;
                                                                            @endphp
                                                                            @if (!empty($customer->data))
                                                                                @foreach ($customer->data as $customer_country_key => $inner_country)
                                                                                        <tr data-widget="expandable-table" aria-expanded="false" role='button'>
                                                                                            <td><i class="expandable-table-caret fas fa-caret-right fa-fw"></i>{{ $counts1++ }}</td>
                                                                                            <td>
                                                                                                @php
                                                                                                    $countings = 1;
                                                                                                @endphp
                                                                                                    @if ($countings==1)
                                                                                                        {{ $customer_country_key }}
                                                                                                        @php
                                                                                                            $countings++;
                                                                                                        @endphp
                                                                                                    @endif
                                                                                                    
                                                                                            </td>
                                                                                            <td>{{ $inner_country->transacting_count }}</td>
                                                                                            <td>{{ $inner_country->non_transacting_count }}</td>
                                                                                            <td>{{ $inner_country->no_attempt_count }}</td>
                                                                                            <td>{{ $inner_country->transacting_count + $inner_country->non_transacting_count + $inner_country->no_attempt_count  }}</td>
                                                                                        </tr>
                                                                                        <tr class="expandable-body">
                                                                                            <td colspan="6">
                                                                                                <table class="table table-hover">
                                                                                                    <thead>
                                                                                                        <tr>
                                                                                                            <th>#</th>
                                                                                                            <th>Beneficiary Country</th>
                                                                                                        </tr>
                                                                                                    </thead>
                                                                                                    <tbody>
                                                                                                        @php
                                                                                                            $counts = 1;
                                                                                                            $array = [];
                                                                                                        @endphp
                                                                                                        @if (!empty($inner_country->transactions))
                                                                                                            @foreach ($inner_country->transactions as $transaction)
                                                                                                                @foreach ($transaction as $beneficiary_countries => $beneficiary_country)
                                                                                                                    @if (!in_array($beneficiary_countries,$array))
                                                                                                                        @php
                                                                                                                            $array[] = $beneficiary_countries;
                                                                                                                        @endphp
                                                                                                                        <tr data-widget="expandable-table" aria-expanded="false">
                                                                                                                            <td role='button'>{{ $counts++ }}</td>
                                                                                                                            <td>{{ $beneficiary_countries }}</td>
                                                                                                                        </tr>
                                                                                                                    @endif
                                                                                                                @endforeach       
                                                                                                            @endforeach       

                                                                                                        @endif                                                                               
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                @endforeach
                                                                            @endif
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        <tr>
                                                            <td class="no-sort"><Strong>Total</Strong></td>
                                                            <td class="text-capitalize"></td>
                                                            <td class="text-capitalize"></td>
                                                            <td class="text-capitalize"></td>
                                                            <td class="text-capitalize"></td>
                                                            <td class="text-capitalize">{{$total_count_of_tr_no_date}}</td>
                                                        </tr>
                                                @endif
                                            </tbody>
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
        @Include('layouts.links.admin.foot')
        @Include('layouts.links.datatable.foot')
        @Include('layouts.links.sweet_alert.foot')
        <script type="text/javascript">
            $(document).on('click', '#beneficiary_country_clear', function() {
                $('#beneficiary_country').val(null).trigger("change");
            });
             $(document).on('click', '#customer_country_clear', function() {
                $('#customer_country').val(null).text('SELECT').trigger("change");
            });
        </script>
    @endsection
</body>

</html>
