<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@Include('layouts.links.admin.title') | Online Customers</title>
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
    </style>
    @Include('layouts.favicon')
    @Include('layouts.links.admin.head')
    @Include('layouts.links.datatable.head')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>


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
                                <h1 class="m-0">Customers</h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Customers</li>
                                </ol>
                            </div><!-- /.col -->
                            <div class="col-sm-6 mt-3">
                                <a href="{{ route('admin.upload_data.online_customers.create') }}" class="border px-2 btn"
                                    style="background-color: #091E3E;color: white">
                                    Upload Online Customers Data
                                </a>
                            </div>
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
                                <div class="card">
                                    <div class="card-header row">
                                        <h3 class="card-title col-lg-6 col-md-6 col-sm-6 col-xs-6">Customers List</h3>
                                        <div class="dates col-lg-6 col-md-6 col-sm-6 col-xs-6 d-flex justify-content-end">
                                            <form action="{{ route('admin.upload_data.online_customers') }}" method="get">
                                                {{-- {!! csrf_field() !!} --}}
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                @if (Request::get('date_from'))
                                                    <input type="date" name="date_from" id="" class="p-1"
                                                        value="{{ Request::get('date_from') }}">
                                                @else
                                                    <input type="date" name="date_from" id="" class="p-1"
                                                        value="">
                                                @endif
                                                @if (!empty(Request::get('date_to')))
                                                    <input type="date" name="date_to" id="" class="p-1"
                                                        value="{{ Request::get('date_to') }}">
                                                @else
                                                    <input type="date" name="date_to" id="" class="p-1"
                                                        value="">
                                                @endif
                                                <button type="submit" name="filter" class="btn mb-2"
                                                    style="background-color: #091E3E;color: white">Submit</button>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">

                                        <table id="example1"
                                            class="table table-bordered @php if(!empty($online_customers)){echo "table-responsive";}else{} @endphp">
                                            <thead>
                                                <tr>
                                                    <th class="text-capitalize">#</th>
                                                    <th class="text-capitalize">customer_id</th>
                                                    <th class="text-capitalize">customer_name</th>
                                                    <th class="text-capitalize">full_address_with_postcode</th>
                                                    <th class="text-capitalize">dob</th>
                                                    <th class="text-capitalize">phone</th>
                                                    <th class="text-capitalize">country</th>
                                                    <th class="text-capitalize">main_agent</th>
                                                    <th class="text-capitalize">registerd_by</th>
                                                    <th class="text-capitalize">register_date</th>
                                                    <th class="text-capitalize">volume</th>
                                                    <th class="text-capitalize">number_of_transaction</th>
                                                    <th class="text-capitalize">last_transaction_date</th>
                                                    <th class="text-capitalize">sales_code</th>
                                                    <th class="text-capitalize">state</th>
                                                    <th class="text-capitalize">preferred_country</th>
                                                    <th class="text-capitalize">city</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($online_customers))
                                                    @php $counter = 1; @endphp
                                                    @foreach ($online_customers as $online_customer)
                                                        <tr>
                                                            <td>{{ $counter++ }}</td>
                                                            <td>{{ $online_customer->customer_id }}</td>
                                                            <td>{{ $online_customer->customer_name }}</td>
                                                            <td>{{ $online_customer->full_address_with_postcode }}</td>
                                                            <td>{{ $online_customer->dob }}</td>
                                                            <td>{{ $online_customer->phone }}</td>
                                                            <td>{{ $online_customer->country }}</td>
                                                            <td>{{ $online_customer->main_agent }}</td>
                                                            <td>{{ $online_customer->registerd_by }}</td>
                                                            <td>{{ $online_customer->register_date }}</td>
                                                            <td>{{ $online_customer->volume }}</td>
                                                            <td>{{ $online_customer->number_of_transaction }}</td>
                                                            <td>{{ $online_customer->last_transaction_date }}</td>
                                                            <td>{{ $online_customer->sales_code }}</td>
                                                            <td>{{ $online_customer->state }}</td>
                                                            <td>{{ $online_customer->preferred_country }}</td>
                                                            <td>{{ $online_customer->city }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                @endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="text-capitalize">#</th>
                                                    <th class="text-capitalize">customer_id</th>
                                                    <th class="text-capitalize">customer_name</th>
                                                    <th class="text-capitalize">full_address_with_postcode</th>
                                                    <th class="text-capitalize">dob</th>
                                                    <th class="text-capitalize">phone</th>
                                                    <th class="text-capitalize">country</th>
                                                    <th class="text-capitalize">main_agent</th>
                                                    <th class="text-capitalize">registerd_by</th>
                                                    <th class="text-capitalize">register_date</th>
                                                    <th class="text-capitalize">volume</th>
                                                    <th class="text-capitalize">number_of_transaction</th>
                                                    <th class="text-capitalize">last_transaction_date</th>
                                                    <th class="text-capitalize">sales_code</th>
                                                    <th class="text-capitalize">state</th>
                                                    <th class="text-capitalize">preferred_country</th>
                                                    <th class="text-capitalize">city</th>
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
        @Include('layouts.links.datatable.foot')
    @endsection
</body>

</html>
