<?php

namespace App\Http\Controllers\operations;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Models\CurrenciesReceivingCountries;
use App\Models\Currency;
use App\Models\OnlineCustomer;
use App\Models\TransactionsData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;
use SebastianBergmann\Type\NullType;

class CustomerTransactionsController extends Controller
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
            return view('operations.transactions.customers.index', ['sending_currencies' => $sending_currencies, 'receiving_currencies' => $receiving_currencies]);
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
                $customer_date_from = date('Y-m-d', strtotime($request->date_from));
                // dd($date_from);
                // dd($customer_date_from);
                $customer_country = $request->customer_country;
                if ($request->customer_country == 'All Customer Countries') {
                    $customers_transactions = function ($query) use ($date_from) {
                        $query->where('customer_register_date', $date_from);
                    };
                    $customers = OnlineCustomer::whereHas('transactions', $customers_transactions)->orwhereDoesntHave('transactions')->with('transactions', $customers_transactions)
                        ->withCount([
                            'transactions as transacting_count' => function ($query) {
                                if ($query->where('status', 'Paid') || $query->where('status', 'Ok') || $query->where('status', 'Compliance Hold')) {
                                    return 1;
                                }
                            },
                            'transactions as non_transacting_count' => function ($query) {
                                if ($query->where('status', 'Cancled')) {
                                    return 1;
                                }
                            },
                            // 'transactions as no_attempt_count' => function ($query) {
                            //     if (empty($query)) {
                            //         return 1;
                            //     }
                            // },
                        ])->where('register_date', $customer_date_from)
                        ->get()->groupBy('register_date')->map(function ($row) {
                            // return $row->groupBY('country');
                            $array = [];
                            $transacting_count = 0;
                            $non_transacting_count = 0;
                            foreach ($row->groupBY('country') as $inner_row) {
                                $no_attempt_count = 0;

                                foreach ($inner_row as $inner_sub_row) {

                                    if ($inner_sub_row['transactions']->isEmpty()) {
                                        $no_attempt_count += 1;
                                    } else {
                                        $no_attempt_count += $inner_sub_row['no_attempt_count'];
                                    }


                                    $transacting_count += $inner_sub_row['transacting_count'];
                                    $non_transacting_count += $inner_sub_row['non_transacting_count'];
                                    $no_attempt_count += $inner_sub_row['no_attempt_count'];

                                    $array[] = [
                                        'customer_id' => $inner_sub_row['customer_id'],
                                        'transacting_count' => $inner_sub_row['transacting_count'],
                                        'non_transacting_count' => $inner_sub_row['non_transacting_count'],
                                        'no_attempt_count' => $no_attempt_count,
                                        'transactions' => $inner_sub_row['transactions']->map(function ($transactions_row) use ($no_attempt_count, $inner_sub_row) {
                                            $transactions_rows = [
                                                'customer_country' => $transactions_row->customer_country,
                                                'beneficiary_country' => $transactions_row->beneficiary_country,
                                            ];
                                            return json_decode(json_encode($transactions_rows));
                                        }),
                                        // json_decode(
                                        //     json_encode(
                                        //         [
                                        //             'customer_country' => $inner_sub_row['transactions']->map(function ($transactions_row) {
                                        //                 $beneficiary_country = [
                                        //                     'customer_country' => $transactions_row->customer_country,
                                        //                     'beneficiary_country' => $transactions_row->beneficiary_country
                                        //                 ];
                                        //                 return json_decode(json_encode($beneficiary_country));
                                        //             })->groupBY('customer_country'),
                                        //             'transacting_count' => $inner_sub_row['transactions']->groupBY('customer_country')->map(function ($transactions_row) {
                                        //                 // return $transactions_row;
                                        //                 if ($transactions_row->where('status', 'Paid') || $transactions_row->where('status', 'Ok') || $transactions_row->where('status', 'Compliance Hold')) {

                                        //                     return $transactions_row->where('status', 'Paid')->count() + $transactions_row->where('status', 'Ok')->count() + $transactions_row->where('status', 'Compliance Hold')->count();
                                        //                 }
                                        //             }),
                                        //             'non_transacting_count' => $inner_sub_row['transactions']->groupBY('customer_country')->map(function ($transactions_row) {
                                        //                 if ($transactions_row->where('status', 'Cancled')) {
                                        //                     return $transactions_row->where('status', 'Cancled')->count();
                                        //                 }
                                        //             }),
                                        //             'no_attempt_count' => $no_attempt_count,
                                        //         ]
                                        //     )
                                        // ),

                                    ];
                                    $no_attempt_count = 0;
                                    // $transacting_count += $inner_sub_row['transacting_count'];
                                    // $non_transacting_count += $inner_sub_row['non_transacting_count'];
                                    if ($inner_sub_row['transactions']->isEmpty()) {
                                        $no_attempt_count += 1;
                                    } else {
                                        $no_attempt_count += $inner_sub_row['no_attempt_count'];
                                    }
                                }
                            }
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_count]));
                        });

                    // ->groupBy('register_date')->withCount('register_date');
                    dd($customers->toArray());

                    // $transactions = TransactionsData::select('customer_country', 'customer_register_date', $tr_no_count)->where('transaction_date', $date_from)
                    //     ->whereHas('customers', $customers)->orwhereDoesntHave('customers', $customers)->with('customers')
                    //     // ->groupBy(['customer_register_date'])
                    //     ->orderBy('customer_country', 'ASC')
                    //     ->get();

                    // ->groupBy(['hours'])->map(function ($row, $hours) {
                    //     return json_decode(json_encode(['hours' => $hours, 'customer_country' => $row->groupBY(['customer_country']), 'count_of_tr_no' => $row->sum('count_of_tr_no')]));
                    // });
                } else {
                    $transactions = TransactionsData::select('customer_country', $tr_no_count)->where('transaction_date', $date_from)->where('customer_country', 'LIKE', '%' . $customer_country . '%')
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
                $customer_date_from = date('Y-m-d', strtotime($request->date_from));
                $customer_date_to = date('Y-m-d', strtotime($request->date_to));
                if ($request->customer_country == 'All Customer Countries') {
                    $customers_transactions = function ($query) use ($date_from, $date_to) {
                        $query->whereBetween('customer_register_date', [$date_from, $date_to]);
                    };
                    $customers = OnlineCustomer::whereHas('transactions', $customers_transactions)->orwhereDoesntHave('transactions', $customers_transactions)->with('transactions', $customers_transactions)
                        ->withCount([
                            'transactions as transacting_count' => function ($query) {
                                if ($query->where('status', 'Paid') || $query->where('status', 'Ok') || $query->where('status', 'Compliance Hold')) {
                                    return 1;
                                }
                            },
                            'transactions as non_transacting_count' => function ($query) {
                                if ($query->where('status', 'Cancled')) {
                                    return 1;
                                }
                            },
                            'transactions as no_attempt_count' => function ($query) {
                                if ('transacting_count' == 0 && 'non_transacting_count' == 0) {
                                    // return '';
                                }
                            },
                            'transactions as total_transactions' => function ($query) {
                                return 1;
                            },
                        ])->whereBetween('register_date', [$customer_date_from, $customer_date_to])
                        ->get()->groupBy('register_date');
                    dd($customers->toArray());
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
            return view('operations.transactions.customers.index', ['customers' => $customers,  'sending_currencies' => $sending_currencies, 'receiving_currencies' => $receiving_currencies]);
        }
    }
}
