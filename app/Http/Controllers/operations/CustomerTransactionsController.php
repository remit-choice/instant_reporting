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
}
