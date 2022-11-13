<?php

namespace App\Http\Controllers\operations;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Models\CurrenciesReceivingCountries;
use App\Models\Currency;
use App\Models\TransactionsData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;

class TransactionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            app(UserController::class)->main();
            return $next($request);
        });
    }
    public function index()
    {
        if (FacadesRequest::isMethod('get')) {
            $sending_currencies = Currency::get();
            $receiving_currencies = CurrenciesReceivingCountries::get();
            return view('operations.transactions.hourly.index', ['sending_currencies' => $sending_currencies, 'receiving_currencies' => $receiving_currencies]);
        } else {
        }
    }
    public function filter(Request $request)
    {
        if (FacadesRequest::isMethod('post')) {
            $date_from = '';
            $date_to = '';
            $sending_currencies = Currency::get();
            $receiving_currencies = CurrenciesReceivingCountries::get();
            $tr_no_count = DB::raw('count(tr_no) as count_of_tr_no');
            $hours = DB::raw('HOUR(transaction_time) as hours');
            if (!empty($request->customer_country) && empty($request->beneficiary_country) && !empty($request->date_from) && empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $customer_country = $request->customer_country;
                if ($request->customer_country == 'All Customer Countries') {
                    $transactions = TransactionsData::select($hours, $tr_no_count)->where('transaction_date', $date_from)
                        ->groupBy(DB::raw('HOUR(transaction_time)'))
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->get();
                    $transactions_data = TransactionsData::select('customer_country', $hours, $tr_no_count)->where('transaction_date', $date_from)
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'customer_country'])
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->get()->groupBy('hours');
                    // dd($transactions_data->toArray());
                } else {
                    $transactions = TransactionsData::select($hours, $tr_no_count)->where('transaction_date', $date_from)->where('customer_country', 'LIKE', '%' . $customer_country . '%')
                        ->groupBy(DB::raw('HOUR(transaction_time)'))
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->get();

                    $transactions_data = TransactionsData::select('customer_country', $hours, $tr_no_count)->where('transaction_date', $date_from)->where('customer_country', 'LIKE', '%' . $customer_country . '%')
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'customer_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->get()->groupBy('hours');
                    // dd($transactions_data->toArray());
                }
                return view('operations.transactions.hourly.index', ['transactions' => $transactions, 'transactions_data' => $transactions_data, 'sending_currencies' => $sending_currencies, 'receiving_currencies' => $receiving_currencies]);
            } elseif (empty($request->customer_country) && !empty($request->beneficiary_country) && !empty($request->date_from) && empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $beneficiary_country = $request->beneficiary_country;
                if ($request->beneficiary_country == 'All Beneficiary Countries') {
                    $transactions = TransactionsData::select($hours, $tr_no_count)->where('transaction_date', $date_from)
                        ->groupBy(DB::raw('HOUR(transaction_time)'))
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get();
                    $transactions_data = TransactionsData::select('beneficiary_country', $hours, $tr_no_count)->where('transaction_date', $date_from)
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'beneficiary_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get()->groupBy('hours');
                } else {
                    $transactions = TransactionsData::select($hours, $tr_no_count)->where('transaction_date', $date_from)->where('beneficiary_country', 'LIKE', '%' . $beneficiary_country . '%')
                        ->groupBy(DB::raw('HOUR(transaction_time)'))
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get();
                    $transactions_data = TransactionsData::select('beneficiary_country', $hours, $tr_no_count)->where('transaction_date', $date_from)->where('beneficiary_country', 'LIKE', '%' . $beneficiary_country . '%')
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'beneficiary_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get()->groupBy('hours');
                }
                return view('operations.transactions.hourly.index', ['transactions' => $transactions, 'transactions_data' => $transactions_data, 'sending_currencies' => $sending_currencies, 'receiving_currencies' => $receiving_currencies]);
            } elseif (!empty($request->customer_country) && !empty($request->beneficiary_country) && !empty($request->date_from) && empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $customer_country = $request->customer_country;
                $beneficiary_country = $request->beneficiary_country;
                if ($request->customer_country == 'All Customer Countries' && $request->beneficiary_country == 'All Beneficiary Countries') {
                    $transactions = TransactionsData::select($hours, $tr_no_count)->where('transaction_date', $date_from)
                        ->groupBy(DB::raw('HOUR(transaction_time)'))
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get();
                    // $transactions_data = TransactionsData::select('customer_country', $hours, $tr_no_count)->where('transaction_date', $date_from)
                    //     ->groupBy([DB::raw('HOUR(transaction_time)'), 'customer_country'])
                    //     ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                    //     ->orderBy('transaction_time', 'ASC')
                    //     ->orderBy('customer_country', 'ASC')
                    //     ->orderBy('beneficiary_country', 'ASC')
                    //     ->get()->groupBy('hours');
                    // dd($transactions_data->toArray());
                    $transactions_data = TransactionsData::select('customer_country', 'beneficiary_country', $hours, $tr_no_count)->where('transaction_date', $date_from)
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'customer_country', 'beneficiary_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get()->groupBy('hours');
                    // $collect = $transactions_data->map(function ($item) {
                    //     return collect($item)->keys();
                    // });
                    // return $collect->keys()->all();
                    // dd($transactions_data->toArray());
                    $transactions_array = [];
                    $transactions_receiving_array = [];
                    $hours = [];
                    // $tr_no_count = 0;
                    $i = 0;
                    // foreach ($transactions as $transaction) {
                    foreach ($transactions_data as $tr_hour => $transaction_data) {
                        // if ($transaction->hours == $tr_hour)
                        $count_of_tr_no = 0;
                        foreach ($transaction_data as $key => $array) {
                            // foreach ($array as $key1 => $sub_array) {
                            $count_of_tr_no += $array->count_of_tr_no;
                            
                            $transactions_receiving_array[] = [
                                $array->customer_country => [
                                    'beneficiary_country' => $array->beneficiary_country,
                                    'count_of_tr_no' => $array->count_of_tr_no
                                ]
                            ];

                            // }
                        }
                        $transactions_array[] = [
                            'customer_country' => $transactions_receiving_array,
                            'count_of_tr_no' => $count_of_tr_no
                        ];
                    }
                    // }
                    // $colleciton = new Collection($transactions_array);
                    dd($transactions_array);
                    return view('operations.transactions.hourly.index', ['transactions' => $transactions, 'transactions_data' => $transactions_data, 'sending_currencies' => $sending_currencies, 'receiving_currencies' => $receiving_currencies]);
                    // return view('operations.transactions.hourly.index', ['transactions' => $transactions, 'transactions_data' => $transactions_data, 'transactions_data_sub_menus' => $transactions_data_sub_menus, 'sending_currencies' => $sending_currencies, 'receiving_currencies' => $receiving_currencies]);
                } elseif ($request->customer_country == 'All Customer Countries' && $request->beneficiary_country != 'All Beneficiary Countries') {
                    $transactions = TransactionsData::select($hours, $tr_no_count)->where('transaction_date', $date_from)->where('beneficiary_country', 'LIKE', '%' . $beneficiary_country . '%')
                        ->groupBy(DB::raw('HOUR(transaction_time)'))
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get();
                    $transactions_data = TransactionsData::select('customer_country', 'beneficiary_country', $hours, $tr_no_count)->where('transaction_date', $date_from)->where('beneficiary_country', 'LIKE', '%' . $beneficiary_country . '%')
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'customer_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get()->groupBy('hours');
                } elseif ($request->customer_country != 'All Customer Countries' && $request->beneficiary_country == 'All Beneficiary Countries') {
                    $transactions = TransactionsData::select($hours, $tr_no_count)->where('transaction_date', $date_from)->where('customer_country', 'LIKE', '%' . $customer_country . '%')
                        ->groupBy(DB::raw('HOUR(transaction_time)'))
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get();
                    $transactions_data = TransactionsData::select('customer_country', 'beneficiary_country', $hours, $tr_no_count)->where('transaction_date', $date_from)->where('customer_country', 'LIKE', '%' . $customer_country . '%')
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'beneficiary_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get()->groupBy('hours');
                } else {
                    $transactions = TransactionsData::select($hours, $tr_no_count)->where('transaction_date', $date_from)->where('customer_country', 'LIKE', '%' . $customer_country . '%')->where('beneficiary_country', 'LIKE', '%' . $beneficiary_country . '%')
                        ->groupBy([DB::raw('HOUR(transaction_time)')])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->get();
                    $transactions_data = TransactionsData::select('customer_country', 'beneficiary_country', $hours, $tr_no_count)->where('transaction_date', $date_from)->where('customer_country', 'LIKE', '%' . $customer_country . '%')->where('beneficiary_country', 'LIKE', '%' . $beneficiary_country . '%')
                        ->groupBy([DB::raw('HOUR(transaction_time)')])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get()->groupBy('hours', 'customer_country');
                }
                return view('operations.transactions.hourly.index', ['transactions' => $transactions, 'transactions_data' => $transactions_data, 'sending_currencies' => $sending_currencies, 'receiving_currencies' => $receiving_currencies]);
            } elseif (!empty($request->customer_country) && empty($request->beneficiary_country) && !empty($request->date_from) && !empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $date_to = date('d/m/Y', strtotime($request->date_to));
                $customer_country = $request->customer_country;
                if ($request->customer_country == 'All Customer Countries') {
                    $transactions = TransactionsData::select($hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])
                        ->groupBy(DB::raw('HOUR(transaction_time)'))
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->get();
                    $transactions_data = TransactionsData::select('customer_country', $hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'customer_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->get()->groupBy('hours');
                } else {
                    $transactions = TransactionsData::select($hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])->where('customer_country', 'LIKE', '%' . $customer_country . '%')
                        ->groupBy(DB::raw('HOUR(transaction_time)'))
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->get();
                    $transactions_data = TransactionsData::select('customer_country', $hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])->where('customer_country', 'LIKE', '%' . $customer_country . '%')
                        ->groupBy([DB::raw('HOUR(transaction_time)')])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->get()->groupBy('hours', 'customer_country');
                }
                return view('operations.transactions.hourly.index', ['transactions' => $transactions, 'transactions_data' => $transactions_data, 'sending_currencies' => $sending_currencies, 'receiving_currencies' => $receiving_currencies]);
            } elseif (empty($request->customer_country) && !empty($request->beneficiary_country) && !empty($request->date_from) && !empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $date_to = date('d/m/Y', strtotime($request->date_to));
                $beneficiary_country = $request->beneficiary_country;
                if ($request->beneficiary_country == 'All Beneficiary Countries') {
                    $transactions = TransactionsData::select($hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])
                        ->groupBy(DB::raw('HOUR(transaction_time)'))
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get();
                    $transactions_data = TransactionsData::select('beneficiary_country', $hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'beneficiary_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get()->groupBy('hours');
                } else {
                    $transactions = TransactionsData::select($hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])->where('beneficiary_country', 'LIKE', '%' . $beneficiary_country . '%')
                        ->groupBy(DB::raw('HOUR(transaction_time)'))
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get();
                    $transactions_data = TransactionsData::select('beneficiary_country', $hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])->where('beneficiary_country', 'LIKE', '%' . $beneficiary_country . '%')
                        ->groupBy([DB::raw('HOUR(transaction_time)')])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get()->groupBy('hours', 'beneficiary_country');
                }
                return view('operations.transactions.hourly.index', ['transactions' => $transactions, 'transactions_data' => $transactions_data, 'sending_currencies' => $sending_currencies, 'receiving_currencies' => $receiving_currencies]);
            } elseif (!empty($request->customer_country) && !empty($request->beneficiary_country) && !empty($request->date_from) && !empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $date_to = date('d/m/Y', strtotime($request->date_to));
                $customer_country = $request->customer_country;
                $beneficiary_country = $request->beneficiary_country;
                if ($request->customer_country == 'All Customer Countries' && $request->beneficiary_country == 'All Beneficiary Countries') {
                    $transactions = TransactionsData::select($hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])
                        ->groupBy(DB::raw('HOUR(transaction_time)'))
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get();
                    $transactions_data = TransactionsData::select('customer_country', 'beneficiary_country', $hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'customer_country', 'beneficiary_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get()->groupBy('hours');
                } elseif ($request->customer_country == 'All Customer Countries' && $request->beneficiary_country != 'All Beneficiary Countries') {
                    $transactions = TransactionsData::select($hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])->where('beneficiary_country', 'LIKE', '%' . $beneficiary_country . '%')
                        ->groupBy(DB::raw('HOUR(transaction_time)'))
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get();
                    $transactions_data = TransactionsData::select('customer_country', 'beneficiary_country', $hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])->where('beneficiary_country', 'LIKE', '%' . $beneficiary_country . '%')
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'customer_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get()->groupBy('hours');
                } elseif ($request->customer_country != 'All Customer Countries' && $request->beneficiary_country == 'All Beneficiary Countries') {
                    $transactions = TransactionsData::select($hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])->where('customer_country', 'LIKE', '%' . $customer_country . '%')
                        ->groupBy(DB::raw('HOUR(transaction_time)'))
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get();
                    $transactions_data = TransactionsData::select('customer_country', 'beneficiary_country', $hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])->where('customer_country', 'LIKE', '%' . $customer_country . '%')
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'beneficiary_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get()->groupBy('hours');
                } else {
                    $transactions = TransactionsData::select('customer_country', 'beneficiary_country', $hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])->where('customer_country', 'LIKE', '%' . $customer_country . '%')->where('beneficiary_country', 'LIKE', '%' . $beneficiary_country . '%')
                        ->groupBy([DB::raw('HOUR(transaction_time)')])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->get();
                    // dd(
                    //     $transactions->toArray()
                    // );
                    $transactions_data = TransactionsData::select('customer_country', 'beneficiary_country', $hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])->where('customer_country', 'LIKE', '%' . $customer_country . '%')->where('beneficiary_country', 'LIKE', '%' . $beneficiary_country . '%')
                        ->groupBy([DB::raw('HOUR(transaction_time)')])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get()->groupBy('hours');
                    // dd(
                    //     $transactions_data->toArray()
                    // );
                }
                return view('operations.transactions.hourly.index', ['transactions' => $transactions, 'transactions_data' => $transactions_data, 'sending_currencies' => $sending_currencies, 'receiving_currencies' => $receiving_currencies]);
            } else {
                return redirect()->back()->with('failed', "From Date Mandatory");
            }
        }
    }
}
