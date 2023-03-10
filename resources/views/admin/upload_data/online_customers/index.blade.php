@extends('layouts.admin.master')
@section('content')
@section('links_content_head')
    @Include('layouts.links.datatable.head')
@endsection
@section('content_1')
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 mt-2">
        <!-- Button trigger modal -->
        @if ($create == 1)
            <a href="{{ route('admin.upload_data.online_customers.create') }}" class="border px-2 btn"
                style="background-color: #091E3E;color: white">
                Upload Customers Data
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
                    <div class="card-header row">
                        <h3 class="card-title col-lg-6 col-md-6 col-sm-6 col-xs-6">{{ $module_name }} List</h3>
                    </div>
                    <div class="card-header container-fluid">
                        <form action="{{ route('admin.upload_data.transactions.index') }}" method="post">
                            @csrf
                            <div class="row d-flex justify-content-center">
                                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                    <label>From Date</label>
                                    @if (request()->input('date_from'))
                                        <input type="date" name="date_from" id="" class="form-control"
                                            value="{{ request()->input('date_from', old('date_from')) }}"
                                            style="width: 100%">
                                    @else
                                        <input type="date" name="date_from" id="" class="form-control"
                                            value="" style="width: 100%">
                                    @endif
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                    <label>To Date</label>
                                    @if (request()->input('date_to'))
                                        <input type="date" name="date_to" id="" class="form-control"
                                            value="{{ request()->input('date_to', old('date_to')) }}">
                                    @else
                                        <input type="date" name="date_to" id="" class="form-control"
                                            value="">
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

                        <table id="example1"
                            class="table table-bordered @php if(!empty($online_customers)){echo "
                                    table-responsive";}else{} @endphp">
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
@section('links_content_foot')
    @Include('layouts.links.datatable.foot')
@endsection
@endsection
