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
                                        <form action="{{ route('admin.operations.transactions.hourly') }}"
                                            method="post">
                                            @csrf
                                            <div class="row d-flex justify-content-center">
                                                <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                                    <label>Select Country</label>
                                                    <select class="form-control" name="search_filter" id="search_filter"
                                                        style="width: 100%">
                                                        <option class="py-1" hidden selected
                                                            value="{{ request()->input('search_filter', old('search_filter')) }}">
                                                            @if (request()->input('search_filter'))
                                                                {{ request()->input('search_filter', old('search_filter')) }}
                                                            @else
                                                                SELECT
                                                            @endif
                                                        </option>
                                                        <option value="Customer Country" class="py-1">Customer
                                                            Country</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                                    <label>Date</label>
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
                                            class="table table-bordered @php if(!empty($transactions)){echo "table-responsive";}else{} @endphp">
                                            <thead>
                                                <tr>
                                                    <th class="no-sort">#</th>
                                                    <th class="text-capitalize">Hours</th>
                                                    <th class="text-capitalize">Count of Tr_No</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($transactions))
                                                    @php
                                                        $counter = 0;
                                                        $counters = 0;
                                                    @endphp
                                                    @if (request()->input('search_filter'))
                                                        @foreach ($transactions as $transaction)
                                                            <tr data-widget="expandable-table" aria-expanded="false" role='button'>
                                                                <td><i class="expandable-table-caret fas fa-caret-right fa-fw"></i></td>
                                                                <td>{{  $transaction->hours }} - {{ ++$transaction->hours }}</td>
                                                                <td>{{ $transaction->count_of_tr_no }}  {{$transaction->transaction_time}}</td>
                                                            </tr>
                                                           <tr class="expandable-body">
                                                                <td colspan="6">
                                                                    <table class="table table-hover" id="example2">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>#</th>
                                                                                <th>Customer Country</th>
                                                                                <th>Count of Tr_No</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @php
                                                                                $counts = 1;
                                                                                $tr_no_count = DB::raw('count(tr_no) as count_of_tr_no');
                                                                                $transactions_data = App\Models\TransactionsData::select('customer_country',$tr_no_count)->where('transaction_time','LIKE', $transaction->transaction_time)->groupBy('customer_country')->get();
                                                                                // dd($transactions_data->toArray());
                                                                            @endphp
                                                                            @foreach ($transactions_data as $transaction_data)
                                                                                    <tr>
                                                                                        <td>{{ $counts++ }}</td>
                                                                                        <td>{{ $transaction_data->customer_country }}</td>
                                                                                        <td>{{ $transaction_data->count_of_tr_no }}</td>
                                                                                    </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                         
                                                     @endif
                                                @else
                                                @endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="no-sort">#</th>
                                                    <th class="text-capitalize">Hours</th>
                                                    <th class="text-capitalize">Count of Tr_No</th>
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
        @Include('layouts.links.admin.foot')
        @Include('layouts.links.datatable.foot')
    @endsection
</body>

</html>
