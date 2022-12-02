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
                                        <form action="{{ route('admin.operations.transactions.hourly') }}"
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
                                            class="table table-bordered @php if(!empty($transactions)){echo "table-responsive";}else{} @endphp">
                                            <thead>
                                                <tr>
                                                    <th class="no-sort">#</th>
                                                    <th class="text-capitalize">Hours</th>
                                                    <th class="text-capitalize">Count of Transactions</th>
                                                    <th class="d-none"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($transactions))
                                                        @php
                                                                    $day_start_hours = 0;
                                                                    $end_hour = 0;
                                                                    $day_end_hours = 24;
                                                                    $total_count_of_tr_no = 0;
                                                        @endphp
                                                        @foreach ($transactions as $transaction)
                                                            @php
                                                                $counter = $transaction->hours;
                                                                $counters = $day_start_hours;
                                                                // dd($counter);
                                                            @endphp
                                                                @for ($day_start_hours; $day_start_hours!=$transaction->hours;$day_start_hours++)
                                                                    <tr data-widget="expandable-table" aria-expanded="false" role='button' class="bg-light">
                                                                        <td><i class="expandable-table-caret fas fa-caret-right fa-fw"></i></td>
                                                                        <td>{{  $day_start_hours }} - {{ ++$counters }}</td>
                                                                        <td>{{ 0 }}</td>
                                                                    </tr>
                                                                @endfor    
                                                                <tr data-widget="expandable-table" aria-expanded="false" role='button' class="bg-light sub_table">
                                                                    <td><i class="expandable-table-caret fas fa-caret-right fa-fw"></i></td>
                                                                    <td>{{  $transaction->hours }} - {{ ++$counter }}</td>
                                                                    <td>{{ $transaction->count_of_tr_no }}</td>
                                                                    @php
                                                                        $total_count_of_tr_no += $transaction->count_of_tr_no;
                                                                    @endphp
                                                                </tr>
                                                                    <tr class="expandable-body">
                                                                            <td colspan="6">
                                                                                <table class="table table-hover sub_table_dt">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>#</th>
                                                                                            @if (!empty($transaction->customer_country) && empty($transaction->beneficiary_country))
                                                                                                    <th>Customer Country</th>
                                                                                            @elseif(empty($transaction->customer_country) && !empty($transaction->beneficiary_country))
                                                                                                    <th>Beneficiary Country</th>
                                                                                            @elseif(!empty($transaction->customer_country) && !empty($transaction->beneficiary_country))
                                                                                                    @if (!empty(!is_scalar($transaction->customer_country)) && (!empty($transaction->beneficiary_country) ==1 || !empty($transaction->beneficiary_country) ==2))
                                                                                                        @if ($transaction->beneficiary_country ==2)
                                                                                                                <th>Customer Country</th>
                                                                                                                <th>Beneficiary Country</th> 
                                                                                                        @elseif($transaction->beneficiary_country ==1)
                                                                                                        <th>Customer Country</th>
                                                                                                        @else
                                                                                                            <th>Customer Country</th>
                                                                                                            <th>Beneficiary Country</th> 
                                                                                                        @endif
                                                                                                    @elseif((!empty($transaction->customer_country)==1) && !empty($transaction->beneficiary_country))                                                                                                    
                                                                                                            @if(!empty($transaction->customer)==1)
                                                                                                                    <th>Customer Country</th>
                                                                                                                    <th>Beneficiary Country</th> 
                                                                                                            @else
                                                                                                                <th>Beneficiary Country</th>  
                                                                                                            @endif
                                                                                                    @else
                                                                                                    
                                                                                                        <th>Customer Country</th>
                                                                                                        <th>Beneficiary Country</th>
                                                                                                    @endif
                                                                                            @else
                                                                                            @endif
                                                                                            <th>Count of Transactions</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        @php
                                                                                            $counts = 1;
                                                                                        @endphp
                                                                                        @if (!empty($transaction->customer_country) && empty($transaction->beneficiary_country))
                                                                                            @foreach ($transaction->customer_country as $customer_countries)
                                                                                                @foreach ($customer_countries as  $customer_country)
                                                                                                    <tr data-widget="expandable-table" aria-expanded="false">
                                                                                                        @if (!empty($customer_country->customer_country) && empty($customer_country->beneficiary_country))
                                                                                                        <td role='button'>{{ $counts++ }}</td>
                                                                                                        <td> {{ $customer_country->customer_country }} </td>
                                                                                                        @elseif (empty($customer_country->customer_country) && !empty($customer_country->beneficiary_country))
                                                                                                        <td role='button'>{{ $counts++ }}</td>
                                                                                                        <td> {{ $customer_country->beneficiary_country }} </td>
                                                                                                        @elseif (!empty($customer_country->customer_country) && !empty($customer_country->beneficiary_country))
                                                                                                        <td role='button'><i class="expandable-table-caret fas fa-caret-right fa-fw"></i>{{ $counts++ }}</td>
                                                                                                        <td> {{ $customer_country->customer_country }} </td>
                                                                                                        @else
                                                                                                        @endif
                                                                                                        <td>{{ $customer_country->count_of_tr_no }}</td>
                                                                                                    </tr>
                                                                                                @endforeach     
                                                                                            @endforeach                                                                                    
                                                                                        @elseif(empty($transaction->customer_country) && !empty($transaction->beneficiary_country))
                                                                                            @foreach ($transaction->beneficiary_country as $beneficiary_countries)
                                                                                                @foreach ($beneficiary_countries as  $beneficiary_country)
                                                                                                    <tr data-widget="expandable-table" aria-expanded="false">
                                                                                                        @if (!empty($beneficiary_country->customer_country) && empty($beneficiary_country->customer_country))
                                                                                                        <td role='button'>{{ $counts++ }}</td>
                                                                                                        <td> {{ $beneficiary_country->customer_country }} </td>
                                                                                                        @elseif (empty($beneficiary_country->customer_country) && !empty($beneficiary_country->beneficiary_country))
                                                                                                        <td role='button'>{{ $counts++ }}</td>
                                                                                                        <td> {{ $beneficiary_country->beneficiary_country }} </td>
                                                                                                        @elseif (!empty($beneficiary_country->customer_country) && !empty($beneficiary_country->beneficiary_country))
                                                                                                        <td role='button'><i class="expandable-table-caret fas fa-caret-right fa-fw"></i>{{ $counts++ }}</td>
                                                                                                        <td> {{ $beneficiary_country->customer_country }} </td>
                                                                                                        @else
                                                                                                        @endif
                                                                                                        <td>{{ $beneficiary_country->count_of_tr_no }}</td>
                                                                                                    </tr>
                                                                                                @endforeach     
                                                                                            @endforeach
                                                                                        @elseif(!empty($transaction->customer_country) && !empty($transaction->beneficiary_country))
                                                                                            {{-- All Sending ANd All Receiving --}}
                                                                                            @if (!empty(!is_scalar($transaction->customer_country)) && empty($transaction->customer) && (!empty($transaction->beneficiary_country) ==1 || !empty($transaction->beneficiary_country) ==2))
                                                                                                @if ($transaction->beneficiary_country==2)
                                                                                                    @foreach ($transaction->customer_country as $customer_countrys => $customer_countries)
                                                                                                        <tr data-widget="expandable-table" aria-expanded="false">
                                                                                                        
                                                                                                            @if (!empty($customer_countries[0]) && !empty($customer_countries[1])) 
                                                                                                                <td role='button'><i class="expandable-table-caret fas fa-caret-right fa-fw"></i>{{ $counts++ }}</td>
                                                                                                                <td>                                                                                                       
                                                                                                                    {{ $customer_countrys }}
                                                                                                                </td>
                                                                                                            @else
                                                                                                                <td>{{ $counts++ }}</td>
                                                                                                                <td>
                                                                                                                    {{ $customer_countrys }}
                                                                                                                </td>
                                                                                                                <td>
                                                                                                                    {{ $customer_countries[0]->beneficiary_country }}
                                                                                                                </td>
                                                                                                            @endif 
                                                                                                            <td>
                                                                                                                @if (!empty($customer_countries[0]) && !empty($customer_countries[1]))
                                                                                                                {{ $customer_countries[1] }}
                                                                                                                @else
                                                                                                                    {{ $customer_countries[0]->count_of_tr_no }}
                                                                                                                @endif
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    @endforeach
                                                                                                @elseif(!empty($transaction->beneficiary_country)==1)
                                                                                                    @foreach ($transaction->customer_country as $customer_countrys => $customer_countries)
                                                                                                            @php
                                                                                                                // dd($customer_country[$country_keys+1]->beneficiary_country);
                                                                                                                // dd($customer_countries[0]);
                                                                                                            @endphp
                                                                                                                <tr data-widget="expandable-table" aria-expanded="false">
                                                                                                                
                                                                                                                    @if (!empty($customer_countries[0]) && !empty($customer_countries[1])) 
                                                                                                                        <td role='button'><i class="expandable-table-caret fas fa-caret-right fa-fw"></i>{{ $counts++ }}</td>
                                                                                                                        <td>                                                                                                       
                                                                                                                            {{ $customer_countrys }}
                                                                                                                        </td>
                                                                                                                    @else
                                                                                                                        <td>{{ $counts++ }}</td>
                                                                                                                        <td>
                                                                                                                            {{ $customer_countrys }}
                                                                                                                        </td>
                                                                                                                        <td>
                                                                                                                            {{ $customer_countries[0]->beneficiary_country }}
                                                                                                                        </td>
                                                                                                                    @endif 
                                                                                                                    <td>
                                                                                                                        @if (!empty($customer_countries[0]) && !empty($customer_countries[1]))
                                                                                                                        {{ $customer_countries[1] }}
                                                                                                                        @else
                                                                                                                            {{ $customer_countries[0]->count_of_tr_no }}
                                                                                                                        @endif
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                @if(!empty($customer_countries[0]) && !empty($customer_countries[1]))
                                                                                                                    <tr class="expandable-body">
                                                                                                                        <td colspan="6">
                                                                                                                            <table class="table table-hover">
                                                                                                                                <thead>
                                                                                                                                        <tr>
                                                                                                                                            <th>#</th>
                                                                                                                                            <th>Beneficiary Country</th>
                                                                                                                                            <th>Count of Transactions</th>
                                                                                                                                        </tr>
                                                                                                                                </thead>
                                                                                                                                <tbody>
                                                                                                                                    @php
                                                                                                                                        $counts1 = 1;
                                                                                                                                    @endphp
                                                                                                                                    @if (!empty($customer_countries[0]))
                                                                                                                                        @foreach ($customer_countries[0] as $country_keys => $customer_country)
                                                                                                                                            <tr data-widget="expandable-table" aria-expanded="false" role='button'>
                                                                                                                                                <td>{{ $counts1++ }}</td>
                                                                                                                                                <td> {{ $customer_country->beneficiary_country }} </td>
                                                                                                                                                <td>{{ $customer_country->count_of_tr_no }}</td>
                                                                                                                                            </tr>
                                                                                                                                        @endforeach
                                                                                                                                    @endif
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                @endif
                                                                                                    @endforeach
                                                                                                @else
                                                                                                @endif
                                                                                            {{-- All Sending ANd All Receivings --}}
                                                                                            @elseif((!empty($transaction->customer_country)==1) && !empty($transaction->beneficiary_country))
                                                                                                @if(!empty($transaction->customer)==1)
                                                                                                
                                                                                                    @foreach ($transaction->beneficiary_country as $beneficiary_countrys => $beneficiary_countries)
                                                                                                        <tr data-widget="expandable-table" aria-expanded="false">
                                                                                                            @if (!empty($beneficiary_countries[0]) && !empty($beneficiary_countries[1])) 
                                                                                                                <td role='button'><i class="expandable-table-caret fas fa-caret-right fa-fw"></i>{{ $counts++ }}</td>
                                                                                                                <td>                                                                                                       
                                                                                                                    {{ $beneficiary_countrys }}
                                                                                                                </td>
                                                                                                                <td>                                                                                                       
                                                                                                                    {{ $beneficiary_countries[0]->beneficiary_country }}
                                                                                                                </td>
                                                                                                            @else
                                                                                                                <td>{{ $counts++ }}</td>
                                                                                                                <td>
                                                                                                                    {{ $beneficiary_countries[0]->customer_country  }}
                                                                                                                </td>
                                                                                                                <td>
                                                                                                                    {{ $beneficiary_countries[0]->beneficiary_country }}
                                                                                                                </td>
                                                                                                                <td>
                                                                                                                    {{ $beneficiary_countries[0]->count_of_tr_no }}
                                                                                                                </td>
                                                                                                            @endif 
                                                                                                        </tr>
                                                                                                    @endforeach
                                                                                                @else
                                                                                                @endif
                                                                                            @else
                                                                                            @endif
                                                                                        @else
                                                                                        @endif
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>                                                                                                            
                                                            @php
                                                                $end_hour = $transaction->hours;
                                                                $start_hours = $day_start_hours++;
                                                            @endphp
                                                               
                                                        @endforeach
                                                        @if ($end_hour<$day_end_hours)
                                                            @for (++$end_hour;$end_hour<$day_end_hours;$end_hour++)
                                                                @php
                                                                    $counters= $end_hour;
                                                                @endphp
                                                                <tr data-widget="expandable-table" aria-expanded="false" role='button' class="bg-light">
                                                                    <td><i class="expandable-table-caret fas fa-caret-right fa-fw"></i></td>
                                                                    <td>{{  $end_hour }} - {{ ++$counters }}</td>
                                                                    <td>{{ 0 }}</td>
                                                                </tr>
                                                            @endfor
                                                        @endif
                                                        <tr>
                                                            <td class="no-sort"><Strong>Total</Strong></td>
                                                            <td class="text-capitalize"></td>
                                                            <td class="text-capitalize">{{$total_count_of_tr_no}}</td>
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
            // $(document).on('change', '.countries_filter', function() {
            //     var search_filter = $('.countries_filter').val();
            //     console.log(search_filter);
            //     // $.ajaxSetup({
            //     //     headers: {
            //     //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     //     }
            //     // });
            //     $.ajax({
            //         type: "post",
            //         url: "",
            //         data: {
            //             "search_filter": search_filter,
            //             '_token': '{{csrf_token()}}'
            //         },
            //         success: function(response) {
            //                 console.log(response)
            //                 if(response.beneficiary_country){
            //                     $('#countries').html('<option value="">Select beneficiary_country</option>');
            //                     $.each(response.beneficiary_country, function (key, value) {
            //                         $("#countries").append('<option>' + value.beneficiary_country + '</option>');
            //                     });
            //                 }else{
            //                     $('#countries').html('<option value="">Select customer_country</option>');
            //                     $.each(response.customer_country, function (key, value) {
            //                         $("#countries").append('<option>' + value.customer_country + '</option>');
            //                     });
            //                 }
                            
  
            //         },
            //         error: (error) => {
            //             console.log(JSON.stringify(error));
            //         }
            //     });
            // });
        </script>
    @endsection
</body>

</html>
