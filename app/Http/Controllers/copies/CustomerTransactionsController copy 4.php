<?php

namespace App\Http\Controllers\copies;

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
                $customer_country = $request->customer_country;
                if ($request->customer_country == 'All Customer Countries') {
                    $customers_transactions = function ($query) use ($date_from) {
                        $query->where('customer_register_date', $date_from);
                    };
                    $customers = OnlineCustomer::where('register_date', $customer_date_from)->whereHas('transactions', $customers_transactions)->orwhereDoesntHave('transactions')->with('transactions', $customers_transactions)
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
                        ])->get()->groupBy('country')->map(function ($row) {
                            $array = [];
                            $transacting_count = 0;
                            $non_transacting_count = 0;

                            $inner_transactions_country = [];
                            $no_attempt_counts = 0;
                            foreach ($row as $customer_countries => $inner_row) {
                                $no_attempt_count = 0;
                                $inner_transacting_count = 0;
                                $inner_non_transacting_count = 0;
                                $inner_transacting_count += $inner_row->transacting_count;
                                $inner_non_transacting_count += $inner_row->non_transacting_count;
                                if ($inner_row->transactions->isEmpty()) {
                                    $no_attempt_count += 1;
                                } else {
                                    $no_attempt_count += $inner_row->no_attempt_count;
                                }
                                $inner_transactions_country[]  =  $inner_row->transactions->map(
                                    function ($inner_rows) {

                                        $beneficiary_country = [
                                            'beneficiary_country' => $inner_rows->beneficiary_country,
                                        ];
                                        return json_decode(json_encode($beneficiary_country));
                                    }
                                )->groupBy('beneficiary_country');
                                if ($inner_row->transactions->isEmpty()) {
                                    $no_attempt_counts += 1;
                                } else {
                                    $no_attempt_counts += $inner_row->no_attempt_count;
                                }
                            }
                            // $transacting_count =  $row->sum('transacting_count');
                            // $non_transacting_count = $row->sum('non_transacting_count');
                            $array[$customer_countries] = [
                                'transacting_count' => $transacting_count,
                                'non_transacting_count' => $non_transacting_count,
                                'no_attempt_count' => $no_attempt_count,
                                'transactions' => $inner_transactions_country,
                            ];

                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                    dd($customers->toArray());
                } else {
                    $customers_transactions = function ($query) use ($date_from, $customer_country) {
                        $query->where('customer_register_date', $date_from);
                        $query->where('customer_country', 'LIKE', '%' . $customer_country . '%');
                    };

                    $customers = OnlineCustomer::where('register_date', $customer_date_from)->whereHas('transactions', $customers_transactions)->orwhereDoesntHave('transactions')->with('transactions', $customers_transactions)
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
                        ])->where('country', 'LIKE', '%' . $customer_country . '%')->get()->groupBy('country')->map(function ($row) {
                            $array = [];
                            $transacting_count = 0;
                            $non_transacting_count = 0;

                            $inner_transactions_country = [];
                            $no_attempt_counts = 0;
                            foreach ($row as $customer_countries => $inner_row) {
                                $no_attempt_count = 0;
                                $inner_transacting_count = 0;
                                $inner_non_transacting_count = 0;
                                $inner_transacting_count += $inner_row->transacting_count;
                                $inner_non_transacting_count += $inner_row->non_transacting_count;
                                if ($inner_row->transactions->isEmpty()) {
                                    $no_attempt_count += 1;
                                } else {
                                    $no_attempt_count += $inner_row->no_attempt_count;
                                }
                                $inner_transactions_country[]  =  $inner_row->transactions->map(
                                    function ($inner_rows) {

                                        $beneficiary_country = [
                                            'beneficiary_country' => $inner_rows->beneficiary_country,
                                        ];
                                        return json_decode(json_encode($beneficiary_country));
                                    }
                                )->groupBy('beneficiary_country');
                                if ($inner_row->transactions->isEmpty()) {
                                    $no_attempt_counts += 1;
                                } else {
                                    $no_attempt_counts += $inner_row->no_attempt_count;
                                }
                                $array[$customer_countries] = [
                                    'transacting_count' => $inner_transacting_count,
                                    'non_transacting_count' => $inner_non_transacting_count,
                                    'no_attempt_count' => $no_attempt_count,
                                    'transactions' => $inner_transactions_country,
                                ];
                            }

                            $transacting_count =  $row->sum('transacting_count');
                            $non_transacting_count = $row->sum('non_transacting_count');
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                }
            } elseif (empty($request->customer_country) && !empty($request->beneficiary_country) && !empty($request->date_from) && empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $customer_date_from = date('Y-m-d', strtotime($request->date_from));
                $beneficiary_country = $request->beneficiary_country;
                if ($request->beneficiary_country == 'All Beneficiary Countries') {
                    $customers_transactions = function ($query) use ($date_from) {
                        $query->where('customer_register_date', $date_from);
                    };
                    $customers = OnlineCustomer::where('register_date', $customer_date_from)->whereHas('transactions', $customers_transactions)->orwhereDoesntHave('transactions')->with('transactions', $customers_transactions)
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
                        ])
                        ->get()->groupBy('country')->map(function ($row) {
                            // return $row;
                            $array = [];
                            $transacting_count = 0;
                            $non_transacting_count = 0;

                            $inner_transactions_country = [];
                            $no_attempt_counts = 0;
                            foreach ($row as $customer_countries => $inner_row) {
                                $no_attempt_count = 0;
                                $inner_transacting_count = 0;
                                $inner_non_transacting_count = 0;
                                $inner_transacting_count += $inner_row->transacting_count;
                                $inner_non_transacting_count += $inner_row->non_transacting_count;
                                if ($inner_row->transactions->isEmpty()) {
                                    $no_attempt_count += 1;
                                } else {
                                    $no_attempt_count += $inner_row->no_attempt_count;
                                }
                                $inner_transactions_country[]  =  $inner_row->transactions->map(
                                    function ($inner_rows) {
                                        $beneficiary_country = [
                                            'beneficiary_country' => $inner_rows->beneficiary_country,
                                        ];
                                        return json_decode(json_encode($beneficiary_country));
                                    }
                                )->groupBy('beneficiary_country');
                                if ($inner_row->transactions->isEmpty()) {
                                    $no_attempt_counts += 1;
                                } else {
                                    $no_attempt_counts += $inner_row->no_attempt_count;
                                }
                                $array[$customer_countries] = [
                                    'transacting_count' => $inner_transacting_count,
                                    'non_transacting_count' => $inner_non_transacting_count,
                                    'no_attempt_count' => $no_attempt_count,
                                    'transactions' => $inner_transactions_country,
                                ];
                            }
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                    // dd($customers->toArray());
                } else {
                    $customers_transactions = function ($query) use ($date_from, $beneficiary_country) {
                        $query->where('customer_register_date', $date_from);
                        $query->where('beneficiary_country', 'LIKE', '%' . $beneficiary_country . '%');
                    };
                    $customers = OnlineCustomer::where('register_date', $customer_date_from)->whereHas('transactions', $customers_transactions)->orwhereDoesntHave('transactions')->with('transactions', $customers_transactions)
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
                        ])
                        ->get()->groupBy('country')->map(function ($row) {
                            $array = [];
                            $transacting_count = 0;
                            $non_transacting_count = 0;

                            $inner_transactions_country = [];
                            $no_attempt_counts = 0;
                            foreach ($row as $customer_countries => $inner_row) {
                                $no_attempt_count = 0;
                                $inner_transacting_count = 0;
                                $inner_non_transacting_count = 0;
                                $inner_transacting_count += $inner_row->transacting_count;
                                $inner_non_transacting_count += $inner_row->non_transacting_count;
                                if ($inner_row->transactions->isEmpty()) {
                                    $no_attempt_count += 1;
                                } else {
                                    $no_attempt_count += $inner_row->no_attempt_count;
                                }
                                $inner_transactions_country[]  =  $inner_row->transactions->map(
                                    function ($inner_rows) {

                                        $beneficiary_country = [
                                            'beneficiary_country' => $inner_rows->beneficiary_country,
                                        ];
                                        return json_decode(json_encode($beneficiary_country));
                                    }
                                )->groupBy('beneficiary_country');
                                if ($inner_row->transactions->isEmpty()) {
                                    $no_attempt_counts += 1;
                                } else {
                                    $no_attempt_counts += $inner_row->no_attempt_count;
                                }
                                $array[$customer_countries] = [
                                    'transacting_count' => $inner_transacting_count,
                                    'non_transacting_count' => $inner_non_transacting_count,
                                    'no_attempt_count' => $no_attempt_count,
                                    'transactions' => $inner_transactions_country,
                                ];
                            }
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                }
            } elseif (!empty($request->customer_country) && !empty($request->beneficiary_country) && !empty($request->date_from) && empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $customer_date_from = date('Y-m-d', strtotime($request->date_from));
                $beneficiary_country = $request->beneficiary_country;
                if ($request->customer_country == 'All Customer Countries' && $request->beneficiary_country == 'All Beneficiary Countries') {
                    $customer_country = $request->customer_country;
                    $customers_transactions = function ($query) use ($date_from) {
                        $query->where('customer_register_date', $date_from);
                    };
                    $customers = OnlineCustomer::where('register_date', $customer_date_from)->whereHas('transactions', $customers_transactions)->orwhereDoesntHave('transactions')->with('transactions', $customers_transactions)
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
                        ])
                        ->get()->groupBy('register_date')->map(function ($row) {
                            $array = [];
                            $transacting_count = 0;
                            $non_transacting_count = 0;

                            $inner_transactions_country = [];
                            $no_attempt_counts = 0;
                            foreach ($row->groupBY('country') as $customer_countries => $inner_row) {
                                $no_attempt_count = 0;
                                $inner_transacting_count = 0;
                                $inner_non_transacting_count = 0;
                                foreach ($inner_row as $keys => $inner_sub_rows) {
                                    $inner_transacting_count += $inner_sub_rows->transacting_count;
                                    $inner_non_transacting_count += $inner_sub_rows->non_transacting_count;
                                    if ($inner_sub_rows->transactions->isEmpty()) {
                                        $no_attempt_count += 1;
                                    } else {
                                        $no_attempt_count += $inner_sub_rows->no_attempt_count;
                                    }
                                    $inner_transactions_country[]  =  $inner_sub_rows->transactions->map(
                                        function ($inner_rows) {

                                            $beneficiary_country = [
                                                'beneficiary_country' => $inner_rows->beneficiary_country,
                                            ];
                                            return json_decode(json_encode($beneficiary_country));
                                        }
                                    )->groupBy('beneficiary_country');
                                    if ($inner_sub_rows->transactions->isEmpty()) {
                                        $no_attempt_counts += 1;
                                    } else {
                                        $no_attempt_counts += $inner_sub_rows->no_attempt_count;
                                    }
                                }
                                $array[$customer_countries] = [
                                    'transacting_count' => $inner_transacting_count,
                                    'non_transacting_count' => $inner_non_transacting_count,
                                    'no_attempt_count' => $no_attempt_count,
                                    'transactions' => $inner_transactions_country,
                                ];
                                $transacting_count =  $row->sum('transacting_count');
                                $non_transacting_count = $row->sum('non_transacting_count');
                            }
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                } elseif ($request->customer_country == 'All Customer Countries' && $request->beneficiary_country != 'All Beneficiary Countries') {
                    $customer_country = $request->customer_country;
                    $customers_transactions = function ($query) use ($date_from, $beneficiary_country) {
                        $query->where('customer_register_date', $date_from);
                        $query->where('beneficiary_country', 'LIKE', '%' . $beneficiary_country . '%');
                    };
                    $customers = OnlineCustomer::where('register_date', $customer_date_from)->whereHas('transactions', $customers_transactions)->orwhereDoesntHave('transactions')->with('transactions', $customers_transactions)
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
                        ])
                        ->get()->groupBy('register_date')->map(function ($row) {
                            $array = [];
                            $transacting_count = 0;
                            $non_transacting_count = 0;

                            $inner_transactions_country = [];
                            $no_attempt_counts = 0;
                            foreach ($row->groupBY('country') as $customer_countries => $inner_row) {
                                $no_attempt_count = 0;
                                $inner_transacting_count = 0;
                                $inner_non_transacting_count = 0;
                                foreach ($inner_row as $keys => $inner_sub_rows) {
                                    $inner_transacting_count += $inner_sub_rows->transacting_count;
                                    $inner_non_transacting_count += $inner_sub_rows->non_transacting_count;
                                    if ($inner_sub_rows->transactions->isEmpty()) {
                                        $no_attempt_count += 1;
                                    } else {
                                        $no_attempt_count += $inner_sub_rows->no_attempt_count;
                                    }
                                    $inner_transactions_country[]  =  $inner_sub_rows->transactions->map(
                                        function ($inner_rows) {

                                            $beneficiary_country = [
                                                'beneficiary_country' => $inner_rows->beneficiary_country,
                                            ];
                                            return json_decode(json_encode($beneficiary_country));
                                        }
                                    )->groupBy('beneficiary_country');
                                    if ($inner_sub_rows->transactions->isEmpty()) {
                                        $no_attempt_counts += 1;
                                    } else {
                                        $no_attempt_counts += $inner_sub_rows->no_attempt_count;
                                    }
                                }
                                $array[$customer_countries] = [
                                    'transacting_count' => $inner_transacting_count,
                                    'non_transacting_count' => $inner_non_transacting_count,
                                    'no_attempt_count' => $no_attempt_count,
                                    'transactions' => $inner_transactions_country,
                                ];
                                $transacting_count =  $row->sum('transacting_count');
                                $non_transacting_count = $row->sum('non_transacting_count');
                            }
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                } elseif ($request->customer_country != 'All Customer Countries' && $request->beneficiary_country == 'All Beneficiary Countries') {
                    $get_coustomer_country = Currency::where('name', 'LIKE', '%' . $request->customer_country . '%')->select('iso3')->first();
                    $customer_country = $get_coustomer_country->iso3;

                    $customers_transactions = function ($query) use ($date_from, $request) {
                        $query->where('customer_register_date', $date_from);
                        $query->where('customer_country', 'LIKE', '%' . $request->customer_country . '%');
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
                        ])->where('register_date', $customer_date_from)->where('country', $customer_country)
                        ->get()->groupBy('register_date')->map(function ($row) {
                            $array = [];
                            $transacting_count = 0;
                            $non_transacting_count = 0;

                            $inner_transactions_country = [];
                            $no_attempt_counts = 0;
                            foreach ($row->groupBY('country') as $customer_countries => $inner_row) {
                                $no_attempt_count = 0;
                                $inner_transacting_count = 0;
                                $inner_non_transacting_count = 0;
                                foreach ($inner_row as $keys => $inner_sub_rows) {
                                    $inner_transacting_count += $inner_sub_rows->transacting_count;
                                    $inner_non_transacting_count += $inner_sub_rows->non_transacting_count;
                                    if ($inner_sub_rows->transactions->isEmpty()) {
                                        $no_attempt_count += 1;
                                    } else {
                                        $no_attempt_count += $inner_sub_rows->no_attempt_count;
                                    }
                                    $inner_transactions_country[]  =  $inner_sub_rows->transactions->map(
                                        function ($inner_rows) {

                                            $beneficiary_country = [
                                                'beneficiary_country' => $inner_rows->beneficiary_country,
                                            ];
                                            return json_decode(json_encode($beneficiary_country));
                                        }
                                    )->groupBy('beneficiary_country');
                                    if ($inner_sub_rows->transactions->isEmpty()) {
                                        $no_attempt_counts += 1;
                                    } else {
                                        $no_attempt_counts += $inner_sub_rows->no_attempt_count;
                                    }
                                }
                                $array[$customer_countries] = [
                                    'transacting_count' => $inner_transacting_count,
                                    'non_transacting_count' => $inner_non_transacting_count,
                                    'no_attempt_count' => $no_attempt_count,
                                    'transactions' => $inner_transactions_country,
                                ];
                                $transacting_count =  $row->sum('transacting_count');
                                $non_transacting_count = $row->sum('non_transacting_count');
                            }
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                } else {
                    $get_coustomer_country = Currency::where('name', 'LIKE', '%' . $request->customer_country . '%')->select('iso3')->first();
                    $customer_country = $get_coustomer_country->iso3;
                    // dd($request->customer_country);
                    $customers_transactions = function ($query) use ($date_from, $request, $beneficiary_country) {
                        $query->where('customer_register_date', $date_from);
                        $query->where('customer_country', 'LIKE', '%' . $request->customer_country . '%');
                        $query->where('beneficiary_country', 'LIKE', '%' . $beneficiary_country . '%');
                    };
                    $customers = OnlineCustomer::where('register_date', $customer_date_from)->whereHas('transactions', $customers_transactions)->orwhereDoesntHave('transactions')->with('transactions', $customers_transactions)
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
                        ])->where('country', 'LIKE', '%' . $customer_country . '%')
                        ->get()->groupBy('register_date')->map(function ($row) {
                            $array = [];
                            $transacting_count = 0;
                            $non_transacting_count = 0;

                            $inner_transactions_country = [];
                            $no_attempt_counts = 0;
                            foreach ($row->groupBY('country') as $customer_countries => $inner_row) {
                                $no_attempt_count = 0;
                                $inner_transacting_count = 0;
                                $inner_non_transacting_count = 0;
                                foreach ($inner_row as $keys => $inner_sub_rows) {
                                    $inner_transacting_count += $inner_sub_rows->transacting_count;
                                    $inner_non_transacting_count += $inner_sub_rows->non_transacting_count;
                                    if ($inner_sub_rows->transactions->isEmpty()) {
                                        $no_attempt_count += 1;
                                    } else {
                                        $no_attempt_count += $inner_sub_rows->no_attempt_count;
                                    }
                                    $inner_transactions_country[]  =  $inner_sub_rows->transactions->map(
                                        function ($inner_rows) {

                                            $beneficiary_country = [
                                                'beneficiary_country' => $inner_rows->beneficiary_country,
                                            ];
                                            return json_decode(json_encode($beneficiary_country));
                                        }
                                    )->groupBy('beneficiary_country');
                                    if ($inner_sub_rows->transactions->isEmpty()) {
                                        $no_attempt_counts += 1;
                                    } else {
                                        $no_attempt_counts += $inner_sub_rows->no_attempt_count;
                                    }
                                }
                                $array[$customer_countries] = [
                                    'transacting_count' => $inner_transacting_count,
                                    'non_transacting_count' => $inner_non_transacting_count,
                                    'no_attempt_count' => $no_attempt_count,
                                    'transactions' => $inner_transactions_country,
                                ];
                                $transacting_count =  $row->sum('transacting_count');
                                $non_transacting_count = $row->sum('non_transacting_count');
                            }
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                }
            } elseif (!empty($request->customer_country) && empty($request->beneficiary_country) && !empty($request->date_from) && !empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $date_to = date('d/m/Y', strtotime($request->date_to));
                $customer_date_from = date('Y-m-d', strtotime($request->date_from));
                $customer_date_to = date('Y-m-d', strtotime($request->date_to));
                $customer_country = $request->customer_country;
                if ($request->customer_country == 'All Customer Countries') {
                    $customers_transactions = function ($query) use ($date_from, $date_to) {
                        $query->whereBetween('customer_register_date', [$date_from, $date_to]);
                    };
                    $customers = OnlineCustomer::whereBetween('register_date', [$customer_date_from, $customer_date_to])->whereHas('transactions', $customers_transactions)->orwhereDoesntHave('transactions')->with('transactions', $customers_transactions)
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
                        ])->get()->groupBy('register_date')->map(function ($row) {
                            $array = [];
                            $transacting_count = 0;
                            $non_transacting_count = 0;

                            $inner_transactions_country = [];
                            $no_attempt_counts = 0;
                            foreach ($row->groupBY('country') as $customer_countries => $inner_row) {
                                $no_attempt_count = 0;
                                $inner_transacting_count = 0;
                                $inner_non_transacting_count = 0;
                                foreach ($inner_row as $keys => $inner_sub_rows) {
                                    $inner_transacting_count += $inner_sub_rows->transacting_count;
                                    $inner_non_transacting_count += $inner_sub_rows->non_transacting_count;
                                    if ($inner_sub_rows->transactions->isEmpty()) {
                                        $no_attempt_count += 1;
                                    } else {
                                        $no_attempt_count += $inner_sub_rows->no_attempt_count;
                                    }
                                    $inner_transactions_country[]  =  $inner_sub_rows->transactions->map(
                                        function ($inner_rows) {

                                            $beneficiary_country = [
                                                'beneficiary_country' => $inner_rows->beneficiary_country,
                                            ];
                                            return json_decode(json_encode($beneficiary_country));
                                        }
                                    )->groupBy('beneficiary_country');
                                    if ($inner_sub_rows->transactions->isEmpty()) {
                                        $no_attempt_counts += 1;
                                    } else {
                                        $no_attempt_counts += $inner_sub_rows->no_attempt_count;
                                    }
                                }
                                $array[$customer_countries] = [
                                    'transacting_count' => $inner_transacting_count,
                                    'non_transacting_count' => $inner_non_transacting_count,
                                    'no_attempt_count' => $no_attempt_count,
                                    'transactions' => $inner_transactions_country,
                                ];
                                $transacting_count =  $row->sum('transacting_count');
                                $non_transacting_count = $row->sum('non_transacting_count');
                            }
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                    // dd($customers->toArray());
                } else {
                    $customers_transactions = function ($query) use ($date_from, $date_to, $customer_country) {
                        $query->whereBetween('customer_register_date', [$date_from, $date_to]);
                        $query->where('customer_country', 'LIKE', '%' . $customer_country . '%');
                    };
                    $customers = OnlineCustomer::whereBetween('register_date', [$customer_date_from, $customer_date_to])->whereHas('transactions', $customers_transactions)->orwhereDoesntHave('transactions')->with('transactions', $customers_transactions)
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
                        ])->where('country', 'LIKE', '%' . $customer_country . '%')
                        ->get()->groupBy('register_date')->map(function ($row) {
                            $array = [];
                            $transacting_count = 0;
                            $non_transacting_count = 0;

                            $inner_transactions_country = [];
                            $no_attempt_counts = 0;
                            foreach ($row->groupBY('country') as $customer_countries => $inner_row) {
                                $no_attempt_count = 0;
                                $inner_transacting_count = 0;
                                $inner_non_transacting_count = 0;
                                foreach ($inner_row as $keys => $inner_sub_rows) {
                                    $inner_transacting_count += $inner_sub_rows->transacting_count;
                                    $inner_non_transacting_count += $inner_sub_rows->non_transacting_count;
                                    if ($inner_sub_rows->transactions->isEmpty()) {
                                        $no_attempt_count += 1;
                                    } else {
                                        $no_attempt_count += $inner_sub_rows->no_attempt_count;
                                    }
                                    $inner_transactions_country[]  =  $inner_sub_rows->transactions->map(
                                        function ($inner_rows) {

                                            $beneficiary_country = [
                                                'beneficiary_country' => $inner_rows->beneficiary_country,
                                            ];
                                            return json_decode(json_encode($beneficiary_country));
                                        }
                                    )->groupBy('beneficiary_country');
                                    if ($inner_sub_rows->transactions->isEmpty()) {
                                        $no_attempt_counts += 1;
                                    } else {
                                        $no_attempt_counts += $inner_sub_rows->no_attempt_count;
                                    }
                                }
                                $array[$customer_countries] = [
                                    'transacting_count' => $inner_transacting_count,
                                    'non_transacting_count' => $inner_non_transacting_count,
                                    'no_attempt_count' => $no_attempt_count,
                                    'transactions' => $inner_transactions_country,
                                ];
                                $transacting_count =  $row->sum('transacting_count');
                                $non_transacting_count = $row->sum('non_transacting_count');
                            }
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
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
                $customer_date_from = date('Y-m-d', strtotime($request->date_from));
                $customer_date_to = date('Y-m-d', strtotime($request->date_to));

                $customer_country = $request->customer_country;
                $beneficiary_country = $request->beneficiary_country;
                if ($request->customer_country == 'All Customer Countries' && $request->beneficiary_country == 'All Beneficiary Countries') {
                    $customers_transactions = function ($query) use ($date_from, $date_to) {
                        $query->whereBetween('customer_register_date', [$date_from, $date_to]);
                    };
                    $customers = OnlineCustomer::whereBetween('register_date', [$customer_date_from, $customer_date_to])->whereHas('transactions', $customers_transactions)->orwhereDoesntHave('transactions')->with('transactions', $customers_transactions)
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
                        ])
                        ->get()->groupBy('register_date')->map(function ($row) {
                            $array = [];
                            $transacting_count = 0;
                            $non_transacting_count = 0;

                            $inner_transactions_country = [];
                            $no_attempt_counts = 0;
                            foreach ($row->groupBY('country') as $customer_countries => $inner_row) {
                                $no_attempt_count = 0;
                                $inner_transacting_count = 0;
                                $inner_non_transacting_count = 0;
                                foreach ($inner_row as $keys => $inner_sub_rows) {
                                    $inner_transacting_count += $inner_sub_rows->transacting_count;
                                    $inner_non_transacting_count += $inner_sub_rows->non_transacting_count;
                                    if ($inner_sub_rows->transactions->isEmpty()) {
                                        $no_attempt_count += 1;
                                    } else {
                                        $no_attempt_count += $inner_sub_rows->no_attempt_count;
                                    }
                                    $inner_transactions_country[]  =  $inner_sub_rows->transactions->map(
                                        function ($inner_rows) {

                                            $beneficiary_country = [
                                                'beneficiary_country' => $inner_rows->beneficiary_country,
                                            ];
                                            return json_decode(json_encode($beneficiary_country));
                                        }
                                    )->groupBy('beneficiary_country');
                                    if ($inner_sub_rows->transactions->isEmpty()) {
                                        $no_attempt_counts += 1;
                                    } else {
                                        $no_attempt_counts += $inner_sub_rows->no_attempt_count;
                                    }
                                }
                                $array[$customer_countries] = [
                                    'transacting_count' => $inner_transacting_count,
                                    'non_transacting_count' => $inner_non_transacting_count,
                                    'no_attempt_count' => $no_attempt_count,
                                    'transactions' => $inner_transactions_country,
                                ];
                                $transacting_count =  $row->sum('transacting_count');
                                $non_transacting_count = $row->sum('non_transacting_count');
                            }
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                } elseif ($request->customer_country == 'All Customer Countries' && $request->beneficiary_country != 'All Beneficiary Countries') {
                    $customers_transactions = function ($query) use ($date_from, $date_to, $beneficiary_country) {
                        $query->whereBetween('customer_register_date', [$date_from, $date_to]);
                        $query->where('beneficiary_country', 'LIKE', '%' . $beneficiary_country . '%');
                    };
                    $customers = OnlineCustomer::whereBetween('register_date', [$customer_date_from, $customer_date_to])->whereHas('transactions', $customers_transactions)->orwhereDoesntHave('transactions')->with('transactions', $customers_transactions)
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
                        ])
                        ->get()->groupBy('register_date')->map(function ($row) {
                            $array = [];
                            $transacting_count = 0;
                            $non_transacting_count = 0;

                            $inner_transactions_country = [];
                            $no_attempt_counts = 0;
                            foreach ($row->groupBY('country') as $customer_countries => $inner_row) {
                                $no_attempt_count = 0;
                                $inner_transacting_count = 0;
                                $inner_non_transacting_count = 0;
                                foreach ($inner_row as $keys => $inner_sub_rows) {
                                    $inner_transacting_count += $inner_sub_rows->transacting_count;
                                    $inner_non_transacting_count += $inner_sub_rows->non_transacting_count;
                                    if ($inner_sub_rows->transactions->isEmpty()) {
                                        $no_attempt_count += 1;
                                    } else {
                                        $no_attempt_count += $inner_sub_rows->no_attempt_count;
                                    }
                                    $inner_transactions_country[]  =  $inner_sub_rows->transactions->map(
                                        function ($inner_rows) {

                                            $beneficiary_country = [
                                                'beneficiary_country' => $inner_rows->beneficiary_country,
                                            ];
                                            return json_decode(json_encode($beneficiary_country));
                                        }
                                    )->groupBy('beneficiary_country');
                                    if ($inner_sub_rows->transactions->isEmpty()) {
                                        $no_attempt_counts += 1;
                                    } else {
                                        $no_attempt_counts += $inner_sub_rows->no_attempt_count;
                                    }
                                }
                                $array[$customer_countries] = [
                                    'transacting_count' => $inner_transacting_count,
                                    'non_transacting_count' => $inner_non_transacting_count,
                                    'no_attempt_count' => $no_attempt_count,
                                    'transactions' => $inner_transactions_country,
                                ];
                                $transacting_count =  $row->sum('transacting_count');
                                $non_transacting_count = $row->sum('non_transacting_count');
                            }
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                } elseif ($request->customer_country != 'All Customer Countries' && $request->beneficiary_country == 'All Beneficiary Countries') {
                    $get_coustomer_country = Currency::where('name', 'LIKE', '%' . $request->customer_country . '%')->select('iso3')->first();
                    $customer_country = $get_coustomer_country->iso3;

                    $customers_transactions = function ($query) use ($date_from, $date_to, $request) {
                        $query->whereBetween('customer_register_date', [$date_from, $date_to]);
                        $query->where('customer_country', 'LIKE', '%' . $request->customer_country . '%');
                    };
                    $customers = OnlineCustomer::whereBetween('register_date', [$customer_date_from, $customer_date_to])->whereHas('transactions', $customers_transactions)->orwhereDoesntHave('transactions')->with('transactions', $customers_transactions)
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
                        ])->where('country', 'LIKE', '%' . $customer_country . '%')
                        ->get()->groupBy('register_date')->map(function ($row) {
                            $array = [];
                            $transacting_count = 0;
                            $non_transacting_count = 0;

                            $inner_transactions_country = [];
                            $no_attempt_counts = 0;
                            foreach ($row->groupBY('country') as $customer_countries => $inner_row) {
                                $no_attempt_count = 0;
                                $inner_transacting_count = 0;
                                $inner_non_transacting_count = 0;
                                foreach ($inner_row as $keys => $inner_sub_rows) {
                                    $inner_transacting_count += $inner_sub_rows->transacting_count;
                                    $inner_non_transacting_count += $inner_sub_rows->non_transacting_count;
                                    if ($inner_sub_rows->transactions->isEmpty()) {
                                        $no_attempt_count += 1;
                                    } else {
                                        $no_attempt_count += $inner_sub_rows->no_attempt_count;
                                    }
                                    $inner_transactions_country[]  =  $inner_sub_rows->transactions->map(
                                        function ($inner_rows) {

                                            $beneficiary_country = [
                                                'beneficiary_country' => $inner_rows->beneficiary_country,
                                            ];
                                            return json_decode(json_encode($beneficiary_country));
                                        }
                                    )->groupBy('beneficiary_country');
                                    if ($inner_sub_rows->transactions->isEmpty()) {
                                        $no_attempt_counts += 1;
                                    } else {
                                        $no_attempt_counts += $inner_sub_rows->no_attempt_count;
                                    }
                                }
                                $array[$customer_countries] = [
                                    'transacting_count' => $inner_transacting_count,
                                    'non_transacting_count' => $inner_non_transacting_count,
                                    'no_attempt_count' => $no_attempt_count,
                                    'transactions' => $inner_transactions_country,
                                ];
                                $transacting_count =  $row->sum('transacting_count');
                                $non_transacting_count = $row->sum('non_transacting_count');
                            }
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                } else {
                    $customers_transactions = function ($query) use ($date_from, $date_to, $customer_country) {
                        $query->whereBetween('customer_register_date', [$date_from, $date_to]);
                        $query->where('customer_country', 'LIKE', '%' . $customer_country . '%');
                    };
                    $customers = OnlineCustomer::whereBetween('register_date', [$customer_date_from, $customer_date_to])->whereHas('transactions', $customers_transactions)->orwhereDoesntHave('transactions')->with('transactions', $customers_transactions)
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
                        ])->where('country', 'LIKE', '%' . $customer_country . '%')
                        ->get()->groupBy('register_date')->map(function ($row) {
                            $array = [];
                            $transacting_count = 0;
                            $non_transacting_count = 0;

                            $inner_transactions_country = [];
                            $no_attempt_counts = 0;
                            foreach ($row->groupBY('country') as $customer_countries => $inner_row) {
                                $no_attempt_count = 0;
                                $inner_transacting_count = 0;
                                $inner_non_transacting_count = 0;
                                foreach ($inner_row as $keys => $inner_sub_rows) {
                                    $inner_transacting_count += $inner_sub_rows->transacting_count;
                                    $inner_non_transacting_count += $inner_sub_rows->non_transacting_count;
                                    if ($inner_sub_rows->transactions->isEmpty()) {
                                        $no_attempt_count += 1;
                                    } else {
                                        $no_attempt_count += $inner_sub_rows->no_attempt_count;
                                    }
                                    $inner_transactions_country[]  =  $inner_sub_rows->transactions->map(
                                        function ($inner_rows) {

                                            $beneficiary_country = [
                                                'beneficiary_country' => $inner_rows->beneficiary_country,
                                            ];
                                            return json_decode(json_encode($beneficiary_country));
                                        }
                                    )->groupBy('beneficiary_country');
                                    if ($inner_sub_rows->transactions->isEmpty()) {
                                        $no_attempt_counts += 1;
                                    } else {
                                        $no_attempt_counts += $inner_sub_rows->no_attempt_count;
                                    }
                                }
                                $array[$customer_countries] = [
                                    'transacting_count' => $inner_transacting_count,
                                    'non_transacting_count' => $inner_non_transacting_count,
                                    'no_attempt_count' => $no_attempt_count,
                                    'transactions' => $inner_transactions_country,
                                ];
                                $transacting_count =  $row->sum('transacting_count');
                                $non_transacting_count = $row->sum('non_transacting_count');
                            }
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                }
            } else {
                return redirect()->back()->with('failed', "From Date Mandatory");
            }
            return view('operations.transactions.customers.index', ['customers' => $customers,  'sending_currencies' => $sending_currencies, 'receiving_currencies' => $receiving_currencies]);
        }
    }
}
