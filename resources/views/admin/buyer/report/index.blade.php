<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@Include('layouts.links.admin.title') | Buyer Report</title>
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
                            <h1 class="m-0">{{ $buyer_name->name }} Buyer Report</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">{{ $buyer_name->name }} Buyer Report</li>
                            </ol>
                        </div><!-- /.col -->
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
                                <div class="card-header">
                                    <h3 class="card-title">Buyer Report</h3>
                                </div>
                                <div class="card-header container-fluid">
                                        <form action="{{ route('admin.buyer.report.index',['id'=>$id]) }}"
                                            method="post">
                                            @csrf
                                            <div class="row d-flex justify-content-center">
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
                                                            value="{{ request()->input('date_to', old('date_to')) }}">
                                                    @else
                                                        <input type="date" name="date_to" id=""
                                                            class="form-control" value="">
                                                    @endif
                                                </div>
                                                <div class="form-group col-lg-1 col-md-1 col-sm-3 col-xs-3 mt-4">
                                                    <label></label>
                                                    <button type="submit" name="filter" class="btn mt-2"
                                                        style="background-color: #091E3E;color: white">Submit</button>
                                                </div>
                                            </div>
                                            {{-- <div class="row d-flex justify-content-center">
                                                
                                            </div> --}}
                                        </form>
                                    </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-capitalize">#</th>
                                                <th class="text-capitalize">Paid Date</th>
                                                <th class="text-capitalize">Customer Country</th>
                                                <th class="text-capitalize">Volumn in GBP</th>
                                                <th class="text-capitalize">Bank Name</th>
                                                <th class="text-capitalize">Payout CCY</th>
                                                <th class="text-capitalize">Payout Amount</th>
                                                <th class="text-capitalize">Payment Method</th>
                                                <th class="text-capitalize">Transact Count</th>
                                                <th class="text-capitalize">Deal Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $counter = 1;
                                            @endphp
                                            @if (!empty($transactions))
                                                @php
                                                    $count_of_tr_no = 0;
                                                    $vol_in_gbp_show = 0;
                                                    $amount = 0;
                                                @endphp
                                                @foreach ($transactions as $transaction)
                                                    @php
                                                        $vol_in_gbp =0;
                                                        $rate = 0;
                                                        $rates = function ($query) {
                                                            $query->where('status', 1);
                                                        };
                                                        $currencies_rates = App\Models\BuyerPaymentMethod::where('currency', $transaction->payin_ccy)
                                                            ->ordoesntHave('rates')
                                                            ->whereHas('rates', $rates)
                                                            ->with('rates', $rates)
                                                            ->get();
                                                            //volume in gbp
                                                        if ($transaction->payin_ccy == 'GBP') {
                                                            $vol_in_gbp = $transaction->payin_amt - $transaction->admin_charges;
                                                        } elseif ($transaction->payin_ccy != 'GBP' && $rate != 0) {
                                                            $vol_in_gbp = $transaction->payin_amt / $rate - $transaction->admin_charges;
                                                        } else {
                                                        }
                                                    @endphp
                                                <tr>
                                                    <td>{{ $counter++ }}</td>
                                                    <td>{{ $transaction->paid_date }}</td>
                                                    <td>{{ $transaction->customer_country }}</td>
                                                    <td>
                                                        @if ($rate != 0)
                                                        {{ number_format(floor($vol_in_gbp*100)/100, 2) }}
                                                        @php
                                                            $vol_in_gbp_show += $vol_in_gbp;
                                                        @endphp
                                                    @endif</td>
                                                    <td>{{ $transaction->bank_name }}</td>
                                                    <td>{{ $transaction->payout_ccy }}</td>
                                                    <td>{{ $transaction->amount }}</td>
                                                    <td>{{ $transaction->payment_method }}</td>
                                                    <td>1</td>
                                                    <td>
                                                        @foreach ($buyers as $buyer)
                                                            @if ($buyer->type==1)
                                                                <span class="bg-secondary badge"><i class="fa fa-percent" aria-hidden="true"></i> Percentage</span>
                                                            @elseif ($buyer->type==2)
                                                                <span class="bg-secondary badge"><i class="fas fa-sort-amount-down" aria-hidden="true"></i> Fixed Amount</span>
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                @php
                                                    $vol_in_gbp_show += $vol_in_gbp;
                                                    $count_of_tr_no += 1;
                                                    $amount += $transaction->amount;
                                                @endphp
                                                @endforeach
                                                <tr>
                                                    <td><strong>Total</strong></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{{ number_format(floor($vol_in_gbp_show*100)/100, 2) }}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{{ $amount }}</td>
                                                    <td></td>
                                                    <td>{{ $count_of_tr_no }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class="text-capitalize">#</th>
                                                <th class="text-capitalize">Paid Date</th>
                                                <th class="text-capitalize">Customer Country</th>
                                                <th class="text-capitalize">Volumn in GBP</th>
                                                <th class="text-capitalize">Bank Name</th>
                                                <th class="text-capitalize">Payout CCY</th>
                                                <th class="text-capitalize">Payout Amount</th>
                                                <th class="text-capitalize">Payment Method</th>
                                                <th class="text-capitalize">Transact Count</th>
                                                <th class="text-capitalize">Deal Type</th>
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
    <!-- /.modal -->
    @Include('layouts.links.admin.foot')
    @Include('layouts.links.datatable.foot')
    @Include('layouts.links.sweet_alert.foot')
    @endsection
</body>

</html>