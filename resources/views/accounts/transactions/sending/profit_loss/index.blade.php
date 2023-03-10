@extends('layouts.admin.master')
@section('content')
@section('links_content_head')
    @Include('layouts.links.datatable.head')
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
                        <form action="{{ route('admin.accounts.transactions.sending.revenue.index') }}" method="post">
                            @csrf
                            <div class="row d-flex justify-content-center">
                                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-6">
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
                                    <label class="invisible">Submit</label>
                                    <button type="submit" name="filter" class="btn form-control"
                                        style="background-color: #091E3E;color: white">Submit</button>
                                </div>
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
                                    <th class="text-capitalize">Customer Country</th>
                                    <th class="text-capitalize">Count of Tr_No</th>
                                    <th class="text-capitalize">Sum of Volume in GBP</th>
                                    <th class="text-capitalize">Sum Fx in GBP</th>
                                    <th class="text-capitalize">Sum Charges in GBP</th>
                                    <th class="text-capitalize">Sum of SSRL Charges</th>
                                    <th class="text-capitalize">Sum of Net Admin Charges</th>
                                    <th class="text-capitalize">Sum FX Loss</th>
                                    <th class="text-capitalize">Total Revenue</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if (!empty($transactions))
                                    @php
                                        $counter = 1;
                                    @endphp
                                    @if (request()->input('search_filter'))
                                        @php
                                            $count_of_tr_no = 0;
                                            $vol_in_gbp_show = 0;
                                            $fx_in_GBP_show = 0;
                                            $charges_in_GBP_show = 0;
                                            $ssrl_charges_in_GBP_show = 0;
                                            $net_admin_charges_in_gbp_show = 0;
                                            $fx_loss_show = 0;
                                            $total_revenue_in_gbp_show = 0;
                                        @endphp
                                        @foreach ($transactions as $transaction)
                                            <tr>
                                                <td>{{ $counter++ }}</td>
                                                <td>{{ $transaction->customer_country }}</td>
                                                <td>{{ $transaction->count_of_tr_no }}</td>
                                                <td>{{ number_format(floor($transaction->vol_in_gbp * 100) / 100, 2) }}
                                                </td>
                                                <td>{{ number_format(floor($transaction->fx_in_gbp * 100) / 100, 2) }}
                                                </td>
                                                <td>{{ number_format(floor($transaction->charges_in_gbp * 100) / 100, 2) }}
                                                </td>
                                                <td>{{ number_format(floor($transaction->charges_in_gbp * 100) / 100, 2) }}
                                                </td>
                                                </td>
                                                <td>{{ number_format(floor($transaction->net_admin_charges_in_gbp * 100) / 100, 2) }}
                                                </td>
                                                <td>
                                                    @if ($transaction->fx_loss > 0)
                                                        {{ 0 }}
                                                    @else
                                                        {{ number_format(floor($transaction->fx_loss * 100) / 100, 2) }}
                                                    @endif
                                                </td>
                                                <td>{{ number_format(floor($transaction->total_revenue_in_gbp * 100) / 100, 2) }}
                                                </td>

                                            </tr>
                                            @php
                                                $count_of_tr_no += $transaction->count_of_tr_no;
                                                $vol_in_gbp_show += $transaction->vol_in_gbp;
                                                $fx_in_GBP_show += $transaction->fx_in_gbp;
                                                $charges_in_GBP_show += $transaction->charges_in_gbp;
                                                $ssrl_charges_in_GBP_show += $transaction->charges_in_gbp;
                                                $net_admin_charges_in_gbp_show += $transaction->net_admin_charges_in_gbp;
                                                $total_revenue_in_gbp_show += $transaction->total_revenue_in_gbp;
                                                if ($transaction->fx_loss > 0) {
                                                    $fx_loss_show += 0;
                                                } else {
                                                    $fx_loss_show += $transaction->fx_loss;
                                                }
                                            @endphp
                                        @endforeach
                                        <tr>
                                            <td><strong>Total</strong></td>
                                            <td></td>
                                            <td>{{ $count_of_tr_no }}</td>
                                            <td>{{ number_format(floor($vol_in_gbp_show * 100) / 100, 2) }}</td>
                                            <td>{{ number_format(floor($fx_in_GBP_show * 100) / 100, 2) }}</td>
                                            <td>{{ number_format(floor($charges_in_GBP_show * 100) / 100, 2) }}</td>
                                            <td>{{ number_format(floor($ssrl_charges_in_GBP_show * 100) / 100, 2) }}
                                            </td>
                                            <td>{{ number_format(floor($net_admin_charges_in_gbp_show * 100) / 100, 2) }}
                                            </td>
                                            <td>{{ number_format(floor($fx_loss_show * 100) / 100, 2) }}</td>
                                            <td>{{ number_format(floor($total_revenue_in_gbp_show * 100) / 100, 2) }}
                                            </td>
                                        </tr>
                                    @else
                                        @php
                                            $vol_in_gbp_show = 0;
                                            $fx_in_GBP_show = 0;
                                            $charges_in_GBP_show = 0;
                                            $ssrl_charges_in_GBP_show = 0;
                                            $net_admin_charges_in_gbp_show = 0;
                                            $fx_loss_show = 0;
                                            $total_revenue_in_gbp_show = 0;
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
                                                    ->with('rates', $rates)
                                                    ->get();
                                                // dd($currencies_rates->toArray());
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
                                                    $vol_in_gbp = $transaction->payin_amt - $transaction->admin_charges;
                                                } elseif ($transaction->payin_ccy != 'GBP' && $rate != 0) {
                                                    $vol_in_gbp = $transaction->payin_amt / $rate - $transaction->admin_charges;
                                                } else {
                                                }
                                                
                                                //fx in gbp
                                                
                                                $fx_in_fc = 0;
                                                $fx_in_fc = (($transaction->buyer_dc_rate - $transaction->agent_rate) * ($transaction->payin_amt - $transaction->admin_charges)) / $transaction->buyer_dc_rate;
                                                if ($transaction->payin_ccy == 'GBP' && $fx_in_fc != 0) {
                                                    $fx_in_GBP = $fx_in_fc;
                                                } elseif ($transaction->payin_ccy != 'GBP' && $rate != 0 && $fx_in_fc != 0) {
                                                    $fx_in_GBP = $fx_in_fc / $rate;
                                                } else {
                                                }
                                                
                                                //charges in gbp
                                                
                                                if ($transaction->payin_ccy == 'GBP') {
                                                    $charges_in_GBP = $transaction->admin_charges;
                                                } elseif ($transaction->payin_ccy != 'GBP' && $rate != 0) {
                                                    $charges_in_GBP = $transaction->admin_charges / $rate;
                                                } else {
                                                }
                                                
                                                //SSRL charges in gbp
                                                if ($transaction->payin_ccy == 'GBP') {
                                                    $ssrl_charges_in_GBP = $transaction->admin_charges;
                                                } elseif ($transaction->payin_ccy != 'GBP' && $rate != 0) {
                                                    $ssrl_charges_in_GBP = $transaction->admin_charges / $rate;
                                                } else {
                                                }
                                                
                                                //net admin charges in gbp
                                                if ($transaction->agent_name_main == 'SSRL') {
                                                    $net_admin_charges_in_gbp = $transaction->admin_charges / $rate - $transaction->admin_charges / $rate;
                                                } elseif ($transaction->agent_name_main != 'SSRL' && $rate != 0) {
                                                    $net_admin_charges_in_gbp = $transaction->admin_charges / $rate;
                                                } else {
                                                }
                                                
                                                // //fx loss
                                                if ($rate != 0) {
                                                    $fx_in_fc = (($transaction->buyer_dc_rate - $transaction->agent_rate) * ($transaction->payin_amt - $transaction->admin_charges)) / $transaction->buyer_dc_rate;
                                                
                                                    $fx_in_GBP = $fx_in_fc / $rate;
                                                    $charges_in_GBP = $transaction->admin_charges / $rate;
                                                    $fx_loss = $fx_in_GBP + $charges_in_GBP;
                                                    if ($fx_loss > 0) {
                                                        $fx_loss = 0;
                                                    } elseif ($fx_loss < 0) {
                                                    } else {
                                                    }
                                                }
                                                
                                                //Total revenue in gbp
                                                $total_revenue_in_gbp = $fx_in_GBP + $net_admin_charges_in_gbp;
                                            @endphp
                                            @if ($rate != 0)
                                                <tr>
                                                    <td>{{ $counter++ }}</td>
                                                    <td>{{ $transaction->customer_country }}</td>
                                                    <td>{{ $count = 1 }}</td>
                                                    <td>
                                                        @if ($rate != 0)
                                                            {{ number_format(floor($vol_in_gbp * 100) / 100, 2) }}
                                                            @php
                                                                $vol_in_gbp_show += $vol_in_gbp;
                                                            @endphp
                                                        @else
                                                        @endif

                                                    </td>
                                                    <td>
                                                        @if ($rate != 0)
                                                            {{ number_format(floor($fx_in_GBP * 100) / 100, 2) }}
                                                            @php
                                                                $fx_in_GBP_show += $fx_in_GBP;
                                                            @endphp
                                                        @else
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($rate != 0)
                                                            {{ number_format(floor($charges_in_GBP * 100) / 100, 2) }}
                                                            @php
                                                                $charges_in_GBP_show += $charges_in_GBP;
                                                            @endphp
                                                        @else
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($rate != 0)
                                                            {{ number_format(floor($ssrl_charges_in_GBP * 100) / 100, 2) }}
                                                            @php
                                                                $ssrl_charges_in_GBP_show += $ssrl_charges_in_GBP;
                                                            @endphp
                                                        @else
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($rate != 0)
                                                            {{ number_format(floor($net_admin_charges_in_gbp * 100) / 100, 2) }}
                                                            @php
                                                                $net_admin_charges_in_gbp_show += $net_admin_charges_in_gbp;
                                                            @endphp
                                                        @else
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($rate != 0)
                                                            @if ($fx_loss > 0)
                                                                {{ 0 }}
                                                                @php
                                                                    $fx_loss_show += 0;
                                                                @endphp
                                                            @else
                                                                {{ number_format(floor($fx_loss * 100) / 100, 2) }}
                                                                @php
                                                                    $fx_loss_show += $fx_loss;
                                                                @endphp
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($rate != 0)
                                                            {{ number_format(floor($total_revenue_in_gbp * 100) / 100, 2) }}
                                                            @php
                                                                $total_revenue_in_gbp_show += $total_revenue_in_gbp;
                                                            @endphp
                                                        @else
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        <tr>
                                            <td><strong>Total</strong></td>
                                            <td></td>
                                            <td>{{ --$counter }}</td>
                                            <td>{{ number_format(floor($vol_in_gbp_show * 100) / 100, 2) }}</td>
                                            <td>{{ number_format(floor($fx_in_GBP_show * 100) / 100, 2) }}</td>
                                            <td>{{ number_format(floor($charges_in_GBP_show * 100) / 100, 2) }}</td>
                                            <td>{{ number_format(floor($ssrl_charges_in_GBP_show * 100) / 100, 2) }}
                                            </td>
                                            <td>{{ number_format(floor($net_admin_charges_in_gbp_show * 100) / 100, 2) }}
                                            </td>
                                            <td>{{ number_format(floor($fx_loss_show * 100) / 100, 2) }}</td>
                                            <td>{{ number_format(floor($total_revenue_in_gbp_show * 100) / 100, 2) }}
                                            </td>
                                        </tr>
                                    @endif
                                @else
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-capitalize">#</th>
                                    <th class="text-capitalize">Customer Country</th>
                                    <th class="text-capitalize">Count of Tr_No</th>
                                    <th class="text-capitalize">Sum of Volume in GBP</th>
                                    <th class="text-capitalize">Sum Fx in GBP</th>
                                    <th class="text-capitalize">Sum Charges in GBP</th>
                                    <th class="text-capitalize">Sum of SSRL Charges</th>
                                    <th class="text-capitalize">Sum of Net Admin Charges</th>
                                    <th class="text-capitalize">Sum FX Loss</th>
                                    <th class="text-capitalize">Total Revenue</th>
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
<!-- /.content -->
@section('links_content_foot')
    @Include('layouts.links.datatable.foot')
@endsection
@endsection
