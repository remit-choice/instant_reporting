<?php

namespace App\Http\Controllers\operations;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Models\CurrenciesReceivingCountries;
use App\Models\Currency;
use App\Models\TransactionsData;
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
        // elseif (FacadesRequest::isMethod('get') && !empty($request->search_filter)) {
        //     dd(1);
        //     if ($request->search_filter == 'customer_country') {
        //         $customer_countries['customer_country'] = TransactionsData::groupBy('customer_country')->orderBy('customer_country', 'ASC')->get();
        //         return response()->json($customer_countries);
        //     } elseif ($request->search_filter == 'beneficiary_country') {
        //         $beneficiary_countries['beneficiary_country'] = TransactionsData::select('beneficiary_country')->groupBy('beneficiary_country')->orderBy('beneficiary_country', 'ASC')->get();
        //         return response()->json($beneficiary_countries);
        //     } else {
        //     }
        // }
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
                    $transactions = TransactionsData::select('customer_country', $hours, $tr_no_count)->where('transaction_date', $date_from)
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'customer_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->get()->groupBy(['hours'])->map(function ($row, $hours) {
                            return json_decode(json_encode(['hours' => $hours, 'customer_country' => $row->groupBY(['customer_country']), 'count_of_tr_no' => $row->sum('count_of_tr_no')]));
                        });
                } else {
                    $transactions = TransactionsData::select('customer_country', $hours, $tr_no_count)->where('transaction_date', $date_from)->where('customer_country', 'LIKE', '%' . $customer_country . '%')
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'customer_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->get()->groupBy(['hours'])->map(function ($row, $hours) {
                            return json_decode(json_encode(['hours' => $hours, 'customer_country' => $row->groupBY(['customer_country']), 'count_of_tr_no' => $row->sum('count_of_tr_no')]));
                        });
                }
            } elseif (empty($request->customer_country) && !empty($request->beneficiary_country) && !empty($request->date_from) && empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $beneficiary_country = $request->beneficiary_country;
                if ($request->beneficiary_country == 'All Beneficiary Countries') {
                    $transactions = TransactionsData::select('beneficiary_country', $hours, $tr_no_count)->where('transaction_date', $date_from)
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'beneficiary_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get()->groupBy(['hours'])->map(function ($row, $hours) {
                            return json_decode(json_encode(['hours' => $hours, 'beneficiary_country' => $row->groupBY(['beneficiary_country']), 'count_of_tr_no' => $row->sum('count_of_tr_no')]));
                        });
                } else {
                    $transactions = TransactionsData::select('beneficiary_country', $hours, $tr_no_count)->where('transaction_date', $date_from)->where('beneficiary_country', 'LIKE', '%' . $beneficiary_country . '%')
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'beneficiary_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get()->groupBy(['hours'])->map(function ($row, $hours) {
                            return json_decode(json_encode(['hours' => $hours, 'beneficiary_country' => $row->groupBY(['beneficiary_country']), 'count_of_tr_no' => $row->sum('count_of_tr_no')]));
                        });
                }
            } elseif (!empty($request->customer_country) && !empty($request->beneficiary_country) && !empty($request->date_from) && empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $customer_country = $request->customer_country;
                $beneficiary_country = $request->beneficiary_country;
                if ($request->customer_country == 'All Customer Countries' && $request->beneficiary_country == 'All Beneficiary Countries') {
                    $transactions = TransactionsData::select('customer_country', 'beneficiary_country', $hours, $tr_no_count)->where('transaction_date', $date_from)
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'customer_country', 'beneficiary_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get()->groupBy(['hours'])->map(function ($row, $hours) {
                            return json_decode(json_encode(['customer_country' => $row->groupBY(['customer_country'])->map(function ($inner, $key) {
                                $map1 = $inner->map(function ($nested_inner) {
                                    return json_decode(json_encode(['beneficiary_country' => $nested_inner['beneficiary_country'], 'count_of_tr_no' => $nested_inner['count_of_tr_no']]));
                                });
                                $map2 = $inner->map(function ($nested_inner) {
                                    return json_decode(json_encode(['count_of_tr_no' => $nested_inner['count_of_tr_no']]));
                                })->sum('count_of_tr_no');
                                return [$map1, $map2];
                            }), 'beneficiary_country' => 1, 'count_of_tr_no' => $row->sum('count_of_tr_no'), 'hours' => $hours]));
                        });
                    // dd($transactions->toArray());
                } elseif ($request->customer_country == 'All Customer Countries' && $request->beneficiary_country != 'All Beneficiary Countries') {
                    $transactions = TransactionsData::select('customer_country', 'beneficiary_country', $hours, $tr_no_count)->where('transaction_date', $date_from)->where('beneficiary_country', 'LIKE', '%' . $beneficiary_country . '%')
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'customer_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get()->groupBy(['hours'])->map(function ($row, $hours) {
                            return json_decode(json_encode(['customer_country' => $row->groupBY('customer_country'), 'beneficiary_country' => 2, 'count_of_tr_no' => $row->sum('count_of_tr_no'), 'hours' => $hours]));
                        });
                    // dd($transactions->toArray());
                } elseif ($request->customer_country != 'All Customer Countries' && $request->beneficiary_country == 'All Beneficiary Countries') {
                    $transactions = TransactionsData::select('customer_country', 'beneficiary_country', $hours, $tr_no_count)->where('transaction_date', $date_from)->where('customer_country', 'LIKE', '%' . $customer_country . '%')
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'beneficiary_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get()->groupBy(['hours'])->map(function ($row, $hours) {
                            return json_decode(json_encode(['customer' => 1, 'customer_country' => 1, 'beneficiary_country' => $row->groupBY('beneficiary_country'), 'count_of_tr_no' => $row->sum('count_of_tr_no'), 'hours' => $hours]));
                        });
                } else {
                    $transactions = TransactionsData::select('customer_country', 'beneficiary_country', $hours, $tr_no_count)->where('transaction_date', $date_from)->where('customer_country', 'LIKE', '%' . $customer_country . '%')->where('beneficiary_country', 'LIKE', '%' . $beneficiary_country . '%')
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'beneficiary_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get()->groupBy(['hours'])->map(function ($row, $hours) {
                            return json_decode(json_encode(['customer' => 1, 'customer_country' => 1, 'beneficiary_country' => $row->groupBY('beneficiary_country'), 'count_of_tr_no' => $row->sum('count_of_tr_no'), 'hours' => $hours]));
                        });
                }
            } elseif (!empty($request->customer_country) && empty($request->beneficiary_country) && !empty($request->date_from) && !empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $date_to = date('d/m/Y', strtotime($request->date_to));
                $customer_country = $request->customer_country;
                if ($request->customer_country == 'All Customer Countries') {
                    $transactions = TransactionsData::select('customer_country', $hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'customer_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->get()->groupBy(['hours'])->map(function ($row, $hours) {
                            // return $row;
                            return json_decode(json_encode(['customer_country' => $row->groupBY('customer_country'), 'count_of_tr_no' => $row->sum('count_of_tr_no'), 'hours' => $hours]));
                        });
                } else {
                    $transactions = TransactionsData::select('customer_country', $hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])->where('customer_country', 'LIKE', '%' . $customer_country . '%')
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'customer_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->get()->groupBy(['hours'])->map(function ($row, $hours) {
                            // return $row;
                            return json_decode(json_encode(['customer_country' => $row->groupBY('customer_country'), 'count_of_tr_no' => $row->sum('count_of_tr_no'), 'hours' => $hours]));
                        });
                }
            } elseif (empty($request->customer_country) && !empty($request->beneficiary_country) && !empty($request->date_from) && !empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $date_to = date('d/m/Y', strtotime($request->date_to));
                $beneficiary_country = $request->beneficiary_country;
                if ($request->beneficiary_country == 'All Beneficiary Countries') {
                    $transactions = TransactionsData::select('beneficiary_country', $hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'beneficiary_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get()->groupBy(['hours'])->map(function ($row, $hours) {
                            return json_decode(json_encode(['beneficiary_country' => $row->groupBY('beneficiary_country'), 'count_of_tr_no' => $row->sum('count_of_tr_no'), 'hours' => $hours]));
                        });
                } else {
                    $transactions = TransactionsData::select('beneficiary_country', $hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])->where('beneficiary_country', 'LIKE', '%' . $beneficiary_country . '%')
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'beneficiary_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get()->groupBy(['hours'])->map(function ($row, $hours) {
                            return json_decode(json_encode(['beneficiary_country' => $row->groupBY('beneficiary_country'), 'count_of_tr_no' => $row->sum('count_of_tr_no'), 'hours' => $hours]));
                        });
                }
            } elseif (!empty($request->customer_country) && !empty($request->beneficiary_country) && !empty($request->date_from) && !empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $date_to = date('d/m/Y', strtotime($request->date_to));
                $customer_country = $request->customer_country;
                $beneficiary_country = $request->beneficiary_country;
                if ($request->customer_country == 'All Customer Countries' && $request->beneficiary_country == 'All Beneficiary Countries') {
                    $transactions = TransactionsData::select('customer_country', 'beneficiary_country', $hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'customer_country', 'beneficiary_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get()->groupBy(['hours'])->map(function ($row, $hours) {
                            return json_decode(json_encode(['customer_country' => $row->groupBY(['customer_country'])->map(function ($inner, $key) {
                                $map1 = $inner->map(function ($nested_inner) {
                                    return json_decode(json_encode(['beneficiary_country' => $nested_inner['beneficiary_country'], 'count_of_tr_no' => $nested_inner['count_of_tr_no']]));
                                });
                                $map2 = $inner->map(function ($nested_inner) {
                                    return json_decode(json_encode(['count_of_tr_no' => $nested_inner['count_of_tr_no']]));
                                })->sum('count_of_tr_no');
                                return [$map1, $map2];
                            }), 'beneficiary_country' => 1, 'count_of_tr_no' => $row->sum('count_of_tr_no'), 'hours' => $hours]));
                        });
                    // dd($transactions->toArray());
                } elseif ($request->customer_country == 'All Customer Countries' && $request->beneficiary_country != 'All Beneficiary Countries') {
                    $transactions = TransactionsData::select('customer_country', 'beneficiary_country', $hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])->where('beneficiary_country', 'LIKE', '%' . $beneficiary_country . '%')
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'customer_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get()->groupBy(['hours'])->map(function ($row, $hours) {
                            return json_decode(json_encode(['customer_country' => $row->groupBY('customer_country'), 'beneficiary_country' => 2, 'count_of_tr_no' => $row->sum('count_of_tr_no'), 'hours' => $hours]));
                        });
                    // dd($transactions->toArray());
                } elseif ($request->customer_country != 'All Customer Countries' && $request->beneficiary_country == 'All Beneficiary Countries') {
                    $transactions = TransactionsData::select('customer_country', 'beneficiary_country', $hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])->where('customer_country', 'LIKE', '%' . $customer_country . '%')
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'beneficiary_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get()->groupBy(['hours'])->map(function ($row, $hours) {
                            return json_decode(json_encode(['customer' => 1, 'customer_country' => 1, 'beneficiary_country' => $row->groupBY('beneficiary_country'), 'count_of_tr_no' => $row->sum('count_of_tr_no'), 'hours' => $hours]));
                        });
                } else {
                    $transactions = TransactionsData::select('customer_country', 'beneficiary_country', $hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])->where('customer_country', 'LIKE', '%' . $customer_country . '%')->where('beneficiary_country', 'LIKE', '%' . $beneficiary_country . '%')
                        ->groupBy([DB::raw('HOUR(transaction_time)'), 'beneficiary_country'])
                        ->orderBy(DB::raw('HOUR(transaction_time)'), 'ASC')
                        ->orderBy('transaction_time', 'ASC')
                        ->orderBy('customer_country', 'ASC')
                        ->orderBy('beneficiary_country', 'ASC')
                        ->get()->groupBy(['hours'])->map(function ($row, $hours) {
                            return json_decode(json_encode(['customer' => 1, 'customer_country' => 1, 'beneficiary_country' => $row->groupBY('beneficiary_country'), 'count_of_tr_no' => $row->sum('count_of_tr_no'), 'hours' => $hours]));
                        });
                }
            } else {
                return redirect()->back()->with('failed', "From Date Mandatory");
            }
            return view('operations.transactions.hourly.index', ['transactions' => $transactions,  'sending_currencies' => $sending_currencies, 'receiving_currencies' => $receiving_currencies]);
        }
    }
    // dd($date_from);
    // if (!empty($request->search_filter) && !empty($request->date_from) && empty($request->date_to)) {
    //     if ($request->search_filter == 'customer_country') {
    //         $transactions = TransactionsData::select($hours, $tr_no_count)->where('transaction_date', $date_from)
    //             ->groupBy(DB::raw('HOUR(transaction_time)'))
    //             ->orderBy('transaction_time', 'ASC')
    //             ->orderBy('customer_country', 'ASC')
    //             ->get();
    //         $transactions_data = TransactionsData::select('customer_country', $hours, $tr_no_count)->where('transaction_date', $date_from)
    //             ->groupBy([DB::raw('HOUR(transaction_time)'), 'customer_country'])
    //             ->orderBy('transaction_time', 'ASC')
    //             ->orderBy('customer_country', 'ASC')
    //             ->get()->groupBy('hours');
    //     } else {
    //         $transactions = TransactionsData::select($hours, $tr_no_count)->where('transaction_date', $date_from)
    //             ->groupBy(DB::raw('HOUR(transaction_time)'))
    //             ->orderBy('transaction_time', 'ASC')
    //             ->orderBy('beneficiary_country', 'ASC')
    //             ->get();
    //         $transactions_data = TransactionsData::select('beneficiary_country', $hours, $tr_no_count)->where('transaction_date', $date_from)
    //             ->groupBy([DB::raw('HOUR(transaction_time)'), 'beneficiary_country'])
    //             ->orderBy('transaction_time', 'ASC')
    //             ->orderBy('beneficiary_country', 'ASC')
    //             ->get()->groupBy('hours');
    //     }
    //     return view('operations.transactions.hourly.index', ['transactions' => $transactions, 'transactions_data' => $transactions_data]);
    // } elseif (!empty($request->search_filter) && !empty($request->date_from) && !empty($request->date_to)) {
    //     $date_to = date('d/m/Y', strtotime($request->date_to));
    //     if ($request->search_filter == 'customer_country') {
    //         $transactions = TransactionsData::select($hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])->orwhere('transaction_date', '=', $date_from)->orwhere('transaction_date', '<=', $date_to)
    //             ->groupBy(DB::raw('HOUR(transaction_time)'))
    //             ->orderBy('transaction_time', 'ASC')
    //             ->orderBy('customer_country', 'ASC')
    //             ->get();
    //         $transactions_data = TransactionsData::select('customer_country', $hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])->orwhere('transaction_date', '=', $date_from)->orwhere('transaction_date', '<=', $date_to)
    //             ->groupBy([DB::raw('HOUR(transaction_time)'), 'customer_country'])
    //             ->orderBy('transaction_time', 'ASC')
    //             ->orderBy('customer_country', 'ASC')
    //             ->get()->groupBy('hours');
    //     } else {
    //         $transactions = TransactionsData::select($hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])->orwhere('transaction_date', '=', $date_from)->orwhere('transaction_date', '<=', $date_to)
    //             ->groupBy(DB::raw('HOUR(transaction_time)'))
    //             ->orderBy('transaction_time', 'ASC')
    //             ->orderBy('beneficiary_country', 'ASC')
    //             ->get();
    //         $transactions_data = TransactionsData::select('beneficiary_country', $hours, $tr_no_count)->whereBetween('transaction_date', [$date_from, $date_to])->orwhere('transaction_date', '=', $date_from)->orwhere('transaction_date', '<=', $date_to)
    //             ->groupBy([DB::raw('HOUR(transaction_time)'), 'beneficiary_country'])
    //             ->orderBy('transaction_time', 'ASC')
    //             ->orderBy('beneficiary_country', 'ASC')
    //             ->get()->groupBy('hours');
    //     }
    //     return view('operations.transactions.hourly.index', ['transactions' => $transactions, 'transactions_data' => $transactions_data]);
    // } else {
    //     return redirect()->back()->with('failed', "From Date Mandatory");
    // }
}









 // $transactions_data  = [];
                // foreach ($transactions as $transaction) {
                //     $transactions_data[] = TransactionsData::select('transaction_time', $hours, $tr_no_count)->whereBetween('transaction_time', [$transaction->hours, $transaction->hours + 1])
                //         ->groupBy('customer_country')
                //         ->orderBy('customer_country', 'ASC')
                //         ->get()->toArray();
                // }

                // dd($transactions_data->toArray());

                // $hour = [];
                // $transaction_data = [];
                // $customer_country = [];

                // foreach ($transactions as $transaction) {
                //     $count_of_tr_no = 0;
                //     // array_values($arr);
                //     if (!in_array($transaction->customer_country, array_column($customer_country, 'customer_country'))) {
                //         $customer_country[] = [
                //             'count_of_tr_no' => ++$count_of_tr_no,
                //         ];
                //     } else {
                //         array_push(array_column($customer_country, 'customer_country'), $transaction->count_of_tr_no);
                //         // $customer_country[] = [
                //         //     'customer_country' => $transaction->customer_country,
                //         //     'count_of_tr_no' => ++$count_of_tr_no,
                //         // ];
                //     }
                //     // $customer_country[] = [];
                //     // $customer =  array_key_count(array_column($customer_country, 'customer_country'));
                //     if (!in_array($transaction->hours, $hour)) {
                //         $hour[] = $transaction->hours;
                //         $transaction_data[] = [
                //             'hour' => $transaction->hours,
                //             'count_of_tr_no' => $transaction->count_of_tr_no,
                //             'customer_country' => $customer_country,
                //         ];
                //     } else {
                //         // $transaction_data[] = [
                //         //     'customer_country' => $customer_country,
                //         // ];
                //     }
                // }
                // dd($transaction_data);