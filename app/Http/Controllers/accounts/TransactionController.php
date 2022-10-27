<?php

namespace App\Http\Controllers\accounts;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Models\CurrenciesRate;
use App\Models\Currency;
use App\Models\TransactionsData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;

// use Illuminate\Support\Facades\Request;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            app(UserController::class)->main();
            return $next($request);
        });
    }
    public function sending_index(Request $request)
    {
        if (FacadesRequest::isMethod('get')) {
            return view('accounts.transactions.sending_side_revenue.index');
        } else {
        }
    }
    public function sending_filter(Request $request)
    {
        if (FacadesRequest::isMethod('post')) {
            $date_from = '';
            $date_to = '';
            // dd($request->toArray());
            $tr_no_count = DB::raw('count(tr_no) as count_of_tr_no');
            $vol_in_gbp = DB::raw('format(SUM(IF(payin_ccy="GBP",(payin_amt-admin_charges),(payin_amt/(SELECT currencies_rates.rate FROM currencies INNER JOIN currencies_rates ON currencies.id=currencies_rates.c_id WHERE currencies.currency=payin_ccy AND currencies_rates.status=1)-admin_charges))),2) AS vol_in_gbp');
            $fx_in_gbp = DB::raw('format(SUM(IF(payin_ccy="GBP",(((buyer_dc_rate-agent_rate)*(payin_amt-admin_charges))/ buyer_dc_rate),(((buyer_dc_rate-agent_rate)*(payin_amt-admin_charges))/buyer_dc_rate))/(SELECT currencies_rates.rate FROM currencies INNER JOIN currencies_rates ON currencies.id=currencies_rates.c_id WHERE currencies.currency=payin_ccy AND currencies_rates.status=1)),2) AS fx_in_gbp');
            $charges_in_gbp = DB::raw('format(SUM(IF(payin_ccy="GBP",(admin_charges),(admin_charges/(SELECT currencies_rates.rate FROM currencies INNER JOIN currencies_rates ON currencies.id=currencies_rates.c_id WHERE currencies.currency=payin_ccy AND currencies_rates.status=1)))),2) AS charges_in_gbp');
            $fx_loss = DB::raw('format(SUM(IF(payin_ccy="GBP",(((buyer_dc_rate-agent_rate)*(payin_amt-admin_charges))/ buyer_dc_rate),(((buyer_dc_rate-agent_rate)*(payin_amt-admin_charges))/buyer_dc_rate))/(SELECT currencies_rates.rate FROM currencies INNER JOIN currencies_rates ON currencies.id=currencies_rates.c_id WHERE currencies.currency=payin_ccy AND currencies_rates.status=1))+SUM(IF(payin_ccy="GBP",(admin_charges),(admin_charges/(SELECT currencies_rates.rate FROM currencies INNER JOIN currencies_rates ON currencies.id=currencies_rates.c_id WHERE currencies.currency=payin_ccy AND currencies_rates.status=1)) )),2) AS fx_loss');


            if (!empty($request->search_filter) && empty($request->date_from) && empty($request->date_to)) {
                $transactions = TransactionsData::select('customer_country', $tr_no_count, $vol_in_gbp, $fx_in_gbp, $charges_in_gbp, $fx_loss)->where('customer_country', '!=', '')->groupBy('customer_country')->orderBY('customer_country')->get();
                return view('accounts.transactions.sending_side_revenue.index', ['transactions' => $transactions]);
            } elseif (empty($request->search_filter) && !empty($request->date_from) && empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $transactions = TransactionsData::where([['customer_country', '!=', ''], ['transaction_date', '=', $date_from], ['status', '=', "Paid"]])->orderBY('customer_country')->get();
                return view('accounts.transactions.sending_side_revenue.index', ['transactions' => $transactions]);
            } elseif (!empty($request->search_filter) && !empty($request->date_from) && empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $transactions = TransactionsData::select('customer_country', $tr_no_count, $vol_in_gbp, $fx_in_gbp, $charges_in_gbp, $fx_loss)->where([['customer_country', '!=', ''], ['transaction_date', '=', $date_from], ['status', '=', "Paid"]])->groupBy('customer_country')->orderBY('customer_country')->get();
                return view('accounts.transactions.sending_side_revenue.index', ['transactions' => $transactions]);
            } elseif (empty($request->search_filter) && !empty($request->date_from) && !empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $date_to = date('d/m/Y', strtotime($request->date_to));
                $transactions = TransactionsData::where('customer_country', '!=', '')->whereBetween('transaction_date', [$date_from, $date_to])->where('status', '=', "Paid")->orderBY('customer_country')->get();
                return view('accounts.transactions.sending_side_revenue.index', ['transactions' => $transactions]);
            } elseif (!empty($request->date_from) && !empty($request->date_to) && !empty($request->search_filter)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $date_to = date('d/m/Y', strtotime($request->date_to));
                $transactions = TransactionsData::select('customer_country', $tr_no_count, $vol_in_gbp, $fx_in_gbp, $charges_in_gbp, $fx_loss)->where([['customer_country', '!=', ''], ['status', '=', "Paid"]])->whereBetween('transaction_date', [$date_from, $date_to])->groupBy('customer_country')->orderBY('customer_country')->get();
                return view('accounts.transactions.sending_side_revenue.index', ['transactions' => $transactions]);
            } else {
                if (empty($request->search_filter) && empty($request->date_from) && !empty($request->date_to)) {
                    return redirect()->back()->with('failed', "From Date Mandatory");
                } else {
                    if (!empty($request->search_filter) && empty($request->date_from) && !empty($request->date_to)) {
                        return redirect()->back()->with('failed', "From Date Mandatory");
                    }
                }
                return redirect()->back();
            }
        }
    }
    public function receiving_index(Request $request)
    {
        if (FacadesRequest::isMethod('get')) {
            return view('accounts.transactions.receiving_side_revenue.index');
        } else {
        }
    }
    public function receiving_filter(Request $request)
    {
        if (FacadesRequest::isMethod('post')) {
            $date_from = '';
            $date_to = '';
            // dd($request->toArray());
            $tr_no_count = DB::raw('count(tr_no) as count_of_tr_no');
            $vol_in_gbp = DB::raw('format(SUM(IF(payin_ccy="GBP",(payin_amt-admin_charges),(payin_amt/(SELECT currencies_rates.rate FROM currencies INNER JOIN currencies_rates ON currencies.id=currencies_rates.c_id WHERE currencies.currency=payin_ccy AND currencies_rates.status=1)-admin_charges))),2) AS vol_in_gbp');
            $fx_in_gbp = DB::raw('format(SUM(IF(payin_ccy="GBP",(((buyer_dc_rate-agent_rate)*(payin_amt-admin_charges))/ buyer_dc_rate),(((buyer_dc_rate-agent_rate)*(payin_amt-admin_charges))/buyer_dc_rate))/(SELECT currencies_rates.rate FROM currencies INNER JOIN currencies_rates ON currencies.id=currencies_rates.c_id WHERE currencies.currency=payin_ccy AND currencies_rates.status=1)),2) AS fx_in_gbp');
            $charges_in_gbp = DB::raw('format(SUM(IF(payin_ccy="GBP",(admin_charges),(admin_charges/(SELECT currencies_rates.rate FROM currencies INNER JOIN currencies_rates ON currencies.id=currencies_rates.c_id WHERE currencies.currency=payin_ccy AND currencies_rates.status=1)))),2) AS charges_in_gbp');
            $fx_loss = DB::raw('format(SUM(IF(payin_ccy="GBP",(((buyer_dc_rate-agent_rate)*(payin_amt-admin_charges))/ buyer_dc_rate),(((buyer_dc_rate-agent_rate)*(payin_amt-admin_charges))/buyer_dc_rate))/(SELECT currencies_rates.rate FROM currencies INNER JOIN currencies_rates ON currencies.id=currencies_rates.c_id WHERE currencies.currency=payin_ccy AND currencies_rates.status=1))+SUM(IF(payin_ccy="GBP",(admin_charges),(admin_charges/(SELECT currencies_rates.rate FROM currencies INNER JOIN currencies_rates ON currencies.id=currencies_rates.c_id WHERE currencies.currency=payin_ccy AND currencies_rates.status=1)) )),2) AS fx_loss');


            if (!empty($request->search_filter) && empty($request->date_from) && empty($request->date_to)) {
                $transactions = TransactionsData::select('beneficiary_country', $tr_no_count, $vol_in_gbp, $fx_in_gbp, $charges_in_gbp, $fx_loss)->where('beneficiary_country', '!=', '')->groupBy('beneficiary_country')->orderBY('beneficiary_country')->get();
                return view('accounts.transactions.receiving_side_revenue.index', ['transactions' => $transactions]);
            } elseif (empty($request->search_filter) && !empty($request->date_from) && empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                // dd($date_from);
                $transactions = TransactionsData::where([['beneficiary_country', '!=', ''], ['transaction_date', '=', $date_from], ['status', '=', "Paid"]])->orderBY('beneficiary_country')->get();
                return view('accounts.transactions.receiving_side_revenue.index', ['transactions' => $transactions]);
            } elseif (!empty($request->search_filter) && !empty($request->date_from) && empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $transactions = TransactionsData::select('beneficiary_country', $tr_no_count, $vol_in_gbp, $fx_in_gbp, $charges_in_gbp, $fx_loss)->where([['beneficiary_country', '!=', ''], ['transaction_date', '=', $date_from], ['status', '=', "Paid"]])->groupBy('beneficiary_country')->orderBY('beneficiary_country')->get();
                return view('accounts.transactions.receiving_side_revenue.index', ['transactions' => $transactions]);
            } elseif (empty($request->search_filter) && !empty($request->date_from) && !empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $date_to = date('d/m/Y', strtotime($request->date_to));
                $transactions = TransactionsData::where('beneficiary_country', '!=', '')->whereBetween('transaction_date', [$date_from, $date_to])->where('status', '=', "Paid")->orderBY('beneficiary_country')->get();
                return view('accounts.transactions.receiving_side_revenue.index', ['transactions' => $transactions]);
            } elseif (!empty($request->date_from) && !empty($request->date_to) && !empty($request->search_filter)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $date_to = date('d/m/Y', strtotime($request->date_to));
                $transactions = TransactionsData::select('beneficiary_country', $tr_no_count, $vol_in_gbp, $fx_in_gbp, $charges_in_gbp, $fx_loss)->where([['beneficiary_country', '!=', ''], ['status', '=', "Paid"]])->whereBetween('transaction_date', [$date_from, $date_to])->groupBy('beneficiary_country')->orderBY('beneficiary_country')->get();
                return view('accounts.transactions.receiving_side_revenue.index', ['transactions' => $transactions]);
            } else {
                if (empty($request->search_filter) && empty($request->date_from) && !empty($request->date_to)) {
                    return redirect()->back()->with('failed', "From Date Mandatory");
                } else {
                    if (!empty($request->search_filter) && empty($request->date_from) && !empty($request->date_to)) {
                        return redirect()->back()->with('failed', "From Date Mandatory");
                    }
                }
                return redirect()->back();
            }
        }
    }
}





                // dd($transactions->toArray());
                // dd($date_from);
                // $transactions = TransactionsData::where('transaction_date', '=', $date_from)->select('beneficiary_country', DB::raw('count(tr_no) as count_of_tr_no'),)->groupBy('beneficiary_country')->get();


 // DB::raw('count(tr_no) as count_of_tr_no'),
                    //DB::raw('SUM(IF(beneficiary_country="GBP", (payin_amt-admin_charges),payin_amt)) AS vol_in_gbp')
                    // DB::raw('SUM(IF(="GBP", (payin_amt-admin_charges),(select currency, rate, (payin_amt/rate)-(admin_charges) from currencies_rates WHERE currency=payin_ccy))) AS vol_in_gbp'),
                    // DB::raw('SUM(IF(payin_ccy="GBP", (payin_amt-admin_charges),(payin_amt+admin_charges) )) AS vol_in_gbp')


// if (FacadesRequest::isMethod('get')) {
//             if ($request->has('filter')) {
//                 $transactions_data =  '1';
//                 $date_from = '';
//                 $date_to = '';
//                 $filter = '';

//                 if (!empty($request->date_from) && empty($request->date_to) && empty($request->filter)) {
//                     $date_from = date('d/m/Y', strtotime($request->date_from));
//                     $transactions = TransactionsData::where('transaction_date', $date_from)->get();
//                     $count_of_tr_no = TransactionsData::select('*', DB::raw('count(tr_no) as count_of_tr_no'),)->groupBy('beneficiary_country')->get();
//                 } elseif (empty($request->date_from) && !empty($request->date_to) && empty($request->filter)) {
//                     $date_to = date('n/j/Y', strtotime($request->date_to));
//                     $transactions = TransactionsData::where('last_transaction_date',  $date_to)->get();
//                     $count_of_tr_no = TransactionsData::select('*', DB::raw('count(tr_no) as count_of_tr_no'),)->groupBy('beneficiary_country')->get();
//                 } elseif (!empty($request->date_from) && !empty($request->date_to) && empty($request->filter)) {
//                     $date_from = date('d/m/Y', strtotime($request->date_from));
//                     $date_to = date('n/j/Y', strtotime($request->date_to));
//                     $transactions = TransactionsData::where([['transaction_date', '>=', $date_from], ['last_transaction_date', '<=', "$date_to%"]])->get();
//                     $count_of_tr_no = TransactionsData::select('*', DB::raw('count(tr_no) as count_of_tr_no'),)->groupBy('beneficiary_country')->get();
//                 } elseif (!empty($request->date_from) || !empty($request->date_to) && !empty($request->filter)) {
//                     dd(1);
//                     $date_from = strtotime($request->date_from);
//                     $date_from = date('d/m/Y', $date_from);
//                     $date_to = strtotime($request->date_to);
//                     $date_to = date('n/j/Y', $date_to);
//                     if (!empty($request->date_from) && empty($request->date_to)) {
//                         $transactions = TransactionsData::where('transaction_date', '>=', $date_from)->select('beneficiary_country', DB::raw('count(tr_no) as count_of_tr_no'),)->groupBy('beneficiary_country')->get();
//                     } elseif (empty($request->date_from) && !empty($request->date_to)) {
//                         $transactions = TransactionsData::where('last_transaction_date', '<=', "$date_to%")->select('beneficiary_country', DB::raw('count(tr_no) as count_of_tr_no'),)->groupBy('beneficiary_country')->get();
//                     } else {
//                     }
//                 } else {
//                 }

//                 return
//                     view('accounts.transactions.index', ['date_from' => $date_from, 'date_to' => $date_to, 'transactions' => $transactions, 'transactions_data' => $transactions_data]);
//             } else {
//                 $transactions_data =  '';
//                 return view('accounts.transactions.index', ['transactions_data' => $transactions_data]);
//             }
//         } else {
//         }




                // $GBP_convert_show = 0;
                // $fx_in_fc_round_show = 0;
                // $charges_in_GBP_show = 0;
                // $fx_in_GBP_show = 0;
                // $ssrl_show = 0;
                // $fx_loss_show = 0;
                // $country = [];
                // $transaction_data = [];

                // foreach ($transactions as $transaction) {
                //     $rates = function ($query) {
                //         $query->where('status', 1);
                //     };
                //     $currency = $transaction->payout_ccy;

                //     $currency_rates = Currency::where([['currency', $currency], ['name', $transaction->beneficiary_country]])
                //         ->whereHas('rates', $rates)
                //         ->with('rates', $rates)
                //         ->get();

                //     // dd($currency_rates->toArray());
                //     foreach ($currency_rates as $currency_rate) {
                //         if (!empty($currency_rate->rates)) {
                //             foreach ($currency_rate->rates as $rate) {
                //                 $rate = $rate->rate;
                //             }
                //         } else {
                //             $rate = '';
                //         }
                //     }
                //     if ($transaction->payout_ccy == 'GBP' && !in_array($transaction->beneficiary_country, $country)) {
                //         $country[] = $transaction->beneficiary_country;
                //         $GBP_convert = $transaction->payout_amt - $transaction->admin_charges;
                //         $GBP_convert_show += $GBP_convert;
                //         $transaction_data[] = [
                //             'beneficiary_country' => $transaction->beneficiary_country,
                //             'vol_in_gbp' => $GBP_convert_show
                //         ];
                //     } elseif ($transaction->payout_ccy != 'GBP' && !in_array($transaction->beneficiary_country, $country)) {
                //         if (!empty($rate)) {
                //             $country[] = $transaction->beneficiary_country;
                //             $GBP_convert = number_format($transaction->payout_amt / $rate, 2) - $transaction->admin_charges;
                //             $GBP_convert_show += $GBP_convert;
                //             $transaction_data[] = [
                //                 'beneficiary_country' => $transaction->beneficiary_country,
                //                 'vol_in_gbp' => $GBP_convert_show
                //             ];
                //         } else {
                //         }
                //     } else {
                //         if ($transaction->payout_ccy == 'GBP') {
                //             $country[] = $transaction->beneficiary_country;
                //             $GBP_convert = $transaction->payout_amt - $transaction->admin_charges;
                //             $GBP_convert_show += $GBP_convert;
                //         } elseif ($transaction->payout_ccy != 'GBP') {
                //             if (!empty($rate)) {
                //                 $country[] = $transaction->beneficiary_country;
                //                 $GBP_convert = number_format($transaction->payout_amt / $rate, 2) - $transaction->admin_charges;
                //                 $GBP_convert_show += $GBP_convert;
                //             } else {
                //             }
                //         }


                //         // $vol_in_gbp += $GBP_convert_show;
                //     }
                //     // $transaction_data[] = [
                //     //     'vol_in_gbp' => $GBP_convert_show
                //     // ];
                // }

                // $country[] = array_unique($country);
                // dd($transactions->toArray());