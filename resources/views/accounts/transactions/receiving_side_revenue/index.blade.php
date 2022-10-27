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
    </style>
    @Include('layouts.favicon')
    @Include('layouts.links.admin.head')
    @Include('layouts.links.datatable.head')
    @Include('layouts.links.selectpciker.head')
    {{-- @Include('layouts.links.modals.head') --}}
    {{-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> --}}
    <script>
        setTimeout(function() {
            $('#failed').slideUp('slow');
        }, 3000);
    </script>
    <style>
        #search_filter:focus {
            outline: none;
        }
    </style>
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
                                        <form action="{{ route('admin.accounts.transactions.receiving_side_revenue') }}"
                                            method="post">
                                            @csrf
                                            <div class="row d-flex justify-content-center">

                                                <select name="search_filter" id="search_filter"
                                                    class="col-lg-3 col-md-3 col-sm-6 col-xs-6 my-2 mx-3"
                                                    style="padding: 7px">
                                                    <option
                                                        value="{{ request()->input('search_filter', old('search_filter')) }}"
                                                        hidden selected>
                                                        @if (request()->input('search_filter'))
                                                            {{ request()->input('search_filter', old('search_filter')) }}
                                                        @else
                                                            SELECT
                                                        @endif
                                                    </option>
                                                    <option value="Benificiary Country">Benificiary Country</option>
                                                </select>
                                                {{-- <div class="form-group">
                                                    <label>Minimal</label>
                                                    <select class="select2 col-lg-3 col-md-3 col-sm-6 col-xs-6 my-2 mx-3"
                                                        name="search_filter" id="search_filter" title="SELECT">
                                                        <option value="" hidden selected>SELECT</option>
                                                        <option
                                                            value="{{ request()->input('search_filter', old('search_filter')) }}"
                                                            hidden selected>
                                                            @if (request()->input('search_filter'))
                                                                {{ request()->input('search_filter', old('search_filter')) }}
                                                            @else
                                                            @endif
                                                        </option>
                                                        <option value="Benificiary Country">Benificiary Country</option>
                                                    </select>
                                                </div> --}}

                                                {{-- <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div> --}}
                                                @if (request()->input('date_from'))
                                                    <input type="date" name="date_from" id=""
                                                        class="p-1 col-lg-3 col-md-3 col-sm-3 col-xs-3 my-2 mx-3"
                                                        value="{{ request()->input('date_from', old('date_from')) }}">
                                                @else
                                                    <input type="date" name="date_from" id=""
                                                        class="p-1 col-lg-3 col-md-3 col-sm-3 col-xs-3 my-2 mx-3"
                                                        value="">
                                                @endif
                                                {{-- <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div> --}}
                                                @if (request()->input('date_to'))
                                                    <input type="date" name="date_to" id=""
                                                        class="p-1 col-lg-3 col-md-3 col-sm-3 col-xs-3 my-2 mx-3"
                                                        value="{{ request()->input('date_to', old('date_to')) }}">
                                                @else
                                                    <input type="date" name="date_to" id=""
                                                        class="p-1 col-lg-3 col-md-3 col-sm-3 col-xs-3 my-2 mx-3"
                                                        value="">
                                                @endif
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
                                                    <th class="text-capitalize">#</th>
                                                    <th class="text-capitalize">beneficiary_country</th>
                                                    <th class="text-capitalize">Count of Tr_No</th>
                                                    <th class="text-capitalize">Sum of Volume in GBP</th>
                                                    <th class="text-capitalize">Sum Fx in GBP</th>
                                                    <th class="text-capitalize">Sum Charges in GBP</th>
                                                    <th class="text-capitalize">Sum of SSRL Charges</th>
                                                    <th class="text-capitalize">Sum FX Loss</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $counter = 1;
                                                    
                                                @endphp
                                                @if (!empty($transactions))
                                                    @if (request()->input('search_filter'))
                                                        @foreach ($transactions as $transaction)
                                                            <tr>
                                                                <td>{{ $counter++ }}</td>
                                                                <td>{{ $transaction->beneficiary_country }}</td>
                                                                <td>{{ $transaction->count_of_tr_no }}</td>
                                                                <td>{{ $transaction->vol_in_gbp }}</td>
                                                                <td>{{ $transaction->fx_in_gbp }}</td>
                                                                <td>{{ $transaction->charges_in_gbp }}</td>
                                                                <td>{{ $transaction->charges_in_gbp }}</td>
                                                                <td>
                                                                    @if ($transaction->fx_loss > 0)
                                                                        {{ 0 }}
                                                                    @else
                                                                        {{ $transaction->fx_loss }}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        @php
                                                            $vol_in_gbp_show = 0;
                                                            $fx_in_GBP_show = 0;
                                                            $charges_in_GBP_show = 0;
                                                            $ssrl_charges_in_GBP_show = 0;
                                                            $fx_loss_show = 0;
                                                        @endphp
                                                        @foreach ($transactions as $transaction)
                                                            @php
                                                                $rate = 0;
                                                                $rates = function ($query) {
                                                                    $query->where('status', 1);
                                                                };
                                                                $currencies_rates = App\Models\Currency::where('currency', $transaction->payin_ccy)
                                                                    ->ordoesntHave('rates')
                                                                    ->whereHas('rates', $rates)
                                                                    ->has('rates')
                                                                    ->get();
                                                                
                                                                foreach ($currencies_rates as $currency_rate) {
                                                                    if (!empty($currency_rate->rates)) {
                                                                        foreach ($currency_rate->rates as $rate) {
                                                                            $rate = $rate->rate;
                                                                        }
                                                                    } else {
                                                                        $rate = 0;
                                                                    }
                                                                }
                                                                
                                                                //volume in gbp
                                                                if ($transaction->payin_ccy == 'GBP') {
                                                                    $vol_in_gbp = number_format($transaction->payin_amt - $transaction->admin_charges, 2);
                                                                    $vol_in_gbp_show += $vol_in_gbp;
                                                                } elseif ($transaction->payin_ccy != 'GBP' && $rate != 0) {
                                                                    $vol_in_gbp = number_format($transaction->payin_amt / $rate - $transaction->admin_charges, 2);
                                                                    $vol_in_gbp_show += $vol_in_gbp;
                                                                } else {
                                                                }
                                                                
                                                                //fx in gbp
                                                                
                                                                // $fx_in_fc = 0;
                                                                // $fx_in_fc = number_format((($transaction->buyer_dc_rate - $transaction->agent_rate) * ($transaction->payin_amt - $transaction->admin_charges)) / $transaction->buyer_dc_rate, 2);
                                                                // if ($transaction->payin_ccy == 'GBP' && $fx_in_fc != 0) {
                                                                //     $fx_in_GBP = $fx_in_fc;
                                                                //     $fx_in_GBP_show += $fx_in_GBP;
                                                                // } elseif ($transaction->payin_ccy != 'GBP' && $rate != 0 && $fx_in_fc != 0) {
                                                                //     $fx_in_GBP = number_format($fx_in_fc / $rate, 2);
                                                                //     $fx_in_GBP_show += $fx_in_GBP;
                                                                // } else {
                                                                // }
                                                                
                                                                //charges in gbp
                                                                
                                                                // if ($transaction->payin_ccy == 'GBP') {
                                                                //     $charges_in_GBP = $transaction->admin_charges;
                                                                //     $charges_in_GBP_show += $charges_in_GBP;
                                                                // } elseif ($transaction->payin_ccy != 'GBP' && $rate != 0) {
                                                                //     $charges_in_GBP = number_format($transaction->admin_charges / $rate, 2);
                                                                //     $charges_in_GBP_show += $charges_in_GBP;
                                                                // } else {
                                                                // }
                                                                
                                                                // //SSRL charges in gbp
                                                                
                                                                // if ($transaction->payin_ccy == 'GBP') {
                                                                //     $ssrl_charges_in_GBP = $transaction->admin_charges;
                                                                //     $ssrl_charges_in_GBP_show += $ssrl_charges_in_GBP;
                                                                // } elseif ($transaction->payin_ccy != 'GBP' && $rate != 0) {
                                                                //     $ssrl_charges_in_GBP = number_format($transaction->admin_charges / $rate, 2);
                                                                //     $ssrl_charges_in_GBP_show += $ssrl_charges_in_GBP;
                                                                // } else {
                                                                // }
                                                                
                                                                // //fx loss
                                                                
                                                                // if ($rate != 0) {
                                                                //     $fx_in_fc = number_format((($transaction->buyer_dc_rate - $transaction->agent_rate) * ($transaction->payin_amt - $transaction->admin_charges)) / $transaction->buyer_dc_rate, 2);
                                                                
                                                                //     $fx_in_GBP = number_format($fx_in_fc / $rate, 2);
                                                                //     $charges_in_GBP = number_format($transaction->admin_charges / $rate, 2);
                                                                //     $fx_loss = $fx_in_GBP + $charges_in_GBP;
                                                                //     if ($fx_loss > 0) {
                                                                //         $fx_loss = 0;
                                                                //     } elseif ($fx_loss < 0) {
                                                                //         $fx_loss = $fx_in_GBP + $charges_in_GBP;
                                                                //         $fx_loss_show += $fx_loss;
                                                                //     } else {
                                                                //     }
                                                                // }
                                                                
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $counter++ }}</td>
                                                                <td>{{ $transaction->beneficiary_country }}</td>
                                                                <td>{{ $count = 1 }}</td>
                                                                <td>
                                                                    @if ($rate != 0)
                                                                        {{ $vol_in_gbp }}
                                                                    @else
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    {{-- @if ($rate != 0)
                                                                        {{ $fx_in_GBP }}
                                                                    @else
                                                                    @endif --}}
                                                                </td>
                                                                <td>
                                                                    {{-- @if ($rate != 0)
                                                                        {{ $charges_in_GBP }}
                                                                    @else
                                                                    @endif --}}
                                                                </td>
                                                                <td>
                                                                    {{-- @if ($rate != 0)
                                                                        {{ $ssrl_charges_in_GBP }}
                                                                    @else
                                                                    @endif --}}
                                                                </td>
                                                                <td>
                                                                    {{-- @if ($rate != 0)
                                                                        @if ($fx_loss > 0)
                                                                            {{ 0 }}
                                                                        @else
                                                                            {{ $fx_loss }}
                                                                        @endif
                                                                    @else
                                                                        <a href="{{ route('admin.currencies') }}"
                                                                            class="btn btn-danger">Update Rate</a>
                                                                    @endif --}}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        <tr>
                                                            <td>Total</td>
                                                            <td></td>
                                                            <td>{{ $counter-- }}</td>
                                                            <td>{{ $vol_in_gbp_show }}</td>
                                                            <td>{{ $fx_in_GBP_show }}</td>
                                                            <td>{{ $charges_in_GBP_show }}</td>
                                                            <td>{{ $ssrl_charges_in_GBP_show }}</td>
                                                            <td>{{ $fx_loss_show }}</td>
                                                        </tr>
                                                    @endif
                                                @else
                                                @endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="text-capitalize">#</th>
                                                    <th class="text-capitalize">beneficiary_country</th>
                                                    <th class="text-capitalize">Count of Tr_No</th>
                                                    <th class="text-capitalize">Sum of Volume in GBP</th>
                                                    <th class="text-capitalize">Sum Fx in GBP</th>
                                                    <th class="text-capitalize">Sum Charges in GBP</th>
                                                    <th class="text-capitalize">Sum of SSRL Charges</th>
                                                    <th class="text-capitalize">Sum FX Loss</th>
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
        @Include('layouts.links.selectpciker.foot')
    @endsection

    {{-- @Include('layouts.links.modals.foot') --}}
</body>

</html>
