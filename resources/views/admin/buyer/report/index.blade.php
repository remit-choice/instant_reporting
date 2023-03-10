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
                        <form action="{{ route('admin.buyer.report.index', ['id' => $id]) }}" method="post">
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
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-capitalize">#</th>
                                    <th class="text-capitalize">Paid Date</th>
                                    <th class="text-capitalize">Customer Country</th>
                                    <th class="text-capitalize">Volumn in GBP</th>
                                    <th class="text-capitalize">Buyer Name</th>
                                    <th class="text-capitalize">Payout CCY</th>
                                    <th class="text-capitalize">Payout Amount</th>
                                    <th class="text-capitalize">Payment Method</th>
                                    <th class="text-capitalize">Payment Currency</th>
                                    <th class="text-capitalize">Charges</th>
                                    <th class="text-capitalize">Charges In GBP</th>
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
                                        $charges_show = 0;
                                        $charges_in_gbp_show = 0;
                                        $amount = 0;
                                    @endphp
                                    @foreach ($transactions as $transaction)
                                        @php
                                            $rate = 0;
                                            // $vol_in_gbp = 0;
                                            $rates = function ($query) {
                                                $query->where('status', 1);
                                            };
                                            $currencies_rates = App\Models\Currency::where('currency', 'GBP')
                                                ->ordoesntHave('rates')
                                                ->whereHas('rates', $rates)
                                                ->with('rates', $rates)
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
                                            $vol_in_gbp = $transaction->payin_amt - $transaction->admin_charges;
                                        @endphp
                                        <tr>
                                            <td>{{ $counter++ }}</td>
                                            <td>{{ $transaction->paid_date }}</td>
                                            <td>{{ $transaction->customer_country }}</td>
                                            <td>
                                                @if ($rate != 0)
                                                    {{ number_format(floor($vol_in_gbp * 100) / 100, 2) }}
                                                    @php
                                                        $vol_in_gbp_show += $vol_in_gbp;
                                                    @endphp
                                                @endif
                                            </td>
                                            <td>{{ $transaction->buyer_name }}</td>
                                            <td>{{ $transaction->payout_ccy }}</td>
                                            <td>{{ $transaction->amount }}</td>
                                            <td>{{ $transaction->payment_method }}</td>
                                            @php
                                                $array = [];
                                                $counting = 1;
                                            @endphp
                                            @foreach ($buyer_charges as $buyer_charge)
                                                @foreach ($buyer_charge->buyer_payment_methods as $buyer_payment_method)
                                                    @php
                                                        $payment_methods_name = $buyer_payment_method['payment_methods']->name;
                                                        $currency = json_decode($buyer_payment_method->currencies);
                                                    @endphp
                                                    @if ($transaction->payment_method == $payment_methods_name)
                                                        @php
                                                            $array[] = $payment_methods_name;
                                                        @endphp
                                                        <td>{{ $currency->currency }}</td>
                                                        @php
                                                            $charges_in_gbp = 0;
                                                            $buyer_payment_method_rate = 0;
                                                        @endphp
                                                        @if ($buyer_charge->type == 1)
                                                            @php
                                                                $rate = 0;
                                                                $rates = function ($query) {
                                                                    $query->where('status', 1);
                                                                };
                                                                // For GBP Start
                                                                $currencies_rates = App\Models\Currency::where('currency', $transaction->payout_ccy)
                                                                    ->ordoesntHave('rates')
                                                                    ->whereHas('rates', $rates)
                                                                    ->with('rates', $rates)
                                                                    ->get();
                                                                foreach ($currencies_rates as $currency_rate) {
                                                                    if (!empty($currency_rate->rates)) {
                                                                        foreach ($currency_rate->rates as $rate) {
                                                                            $rate = $rate->rate;
                                                                            // dd($rate);
                                                                        }
                                                                    } else {
                                                                        $rate = 0;
                                                                    }
                                                                }
                                                                // dd($buyer_payment_method->rate);
                                                                // For GBP End *****************/
                                                                $charges_in_gbp = ($transaction->amount * $buyer_payment_method->rate) / 100;
                                                                if ($rate >= 1) {
                                                                    $charges_in_gbp = $charges_in_gbp / $rate;
                                                                } else {
                                                                    $charges_in_gbp = $charges_in_gbp * $rate;
                                                                }
                                                                
                                                                $charges_in_gbp_show += $charges_in_gbp;
                                                                // For Dealing Currency Start
                                                                if (!empty($currency->rates)) {
                                                                    foreach ($currency->rates as $buyer_rate) {
                                                                        if ($buyer_rate->rate >= 1) {
                                                                            $buyer_payment_method_rate = $charges_in_gbp / $buyer_rate->rate;
                                                                        } else {
                                                                            $buyer_payment_method_rate = $charges_in_gbp * $buyer_rate->rate;
                                                                        }
                                                                        $charges_show += $buyer_payment_method_rate;
                                                                    }
                                                                } else {
                                                                    $buyer_payment_method_rate = 0;
                                                                }
                                                                // For Dealing Currency End *****************/
                                                            @endphp
                                                        @elseif ($buyer_charge->type == 2)
                                                            @php
                                                                $charges_show += $buyer_payment_method->rate;
                                                                $buyer_payment_method_rate = $buyer_payment_method->rate;
                                                            @endphp
                                                        @endif
                                                        <td>{{ number_format(floor($buyer_payment_method_rate * 100) / 100, 2) }}
                                                        </td>
                                                        <td>{{ number_format(floor($charges_in_gbp * 100) / 100, 2) }}
                                                        </td>
                                                    @endif
                                                    @if (!in_array($transaction->payment_method, $array) && $counting == 1)
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        @php
                                                            $counting++;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                            @endforeach
                                            <td>1</td>
                                            <td>
                                                @foreach ($buyers as $buyer)
                                                    @if ($buyer->type == 1)
                                                        <span class="bg-secondary badge"><i class="fa fa-percent"
                                                                aria-hidden="true"></i> Percentage</span>
                                                    @elseif ($buyer->type == 2)
                                                        <span class="bg-secondary badge"><i
                                                                class="fas fa-sort-amount-down" aria-hidden="true"></i>
                                                            Fixed Amount</span>
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
                                    @php
                                        // dd($array);
                                    @endphp
                                    <tr>
                                        <td><strong>Total</strong></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ number_format(floor($vol_in_gbp_show * 100) / 100, 2) }}</td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ number_format(floor($amount * 100) / 100, 2) }}</td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ number_format(floor($charges_show * 100) / 100, 2) }}</td>
                                        <td>{{ number_format(floor($charges_in_gbp_show * 100) / 100, 2) }}</td>
                                        <td>{{ $count_of_tr_no }}</td>
                                        <td></td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-capitalize">#</th>
                                    <th class="text-capitalize">Paid Date</th>
                                    <th class="text-capitalize">Customer Country</th>
                                    <th class="text-capitalize">Volumn in GBP</th>
                                    <th class="text-capitalize">Buyer Name</th>
                                    <th class="text-capitalize">Payout CCY</th>
                                    <th class="text-capitalize">Payout Amount</th>
                                    <th class="text-capitalize">Payment Method</th>
                                    <th class="text-capitalize">Payment Currency</th>
                                    <th class="text-capitalize">Charges</th>
                                    <th class="text-capitalize">Charges In GBP</th>
                                    <th class="text-capitalize">Transact Count</th>
                                    <th class="text-capitalize">Deal Type</th>
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
@section('links_content_foot')
    @Include('layouts.links.datatable.foot')
@endsection
@endsection
