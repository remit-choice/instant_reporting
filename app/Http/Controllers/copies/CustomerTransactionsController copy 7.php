<?php

namespace App\Http\Controllers\copies;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Models\CurrenciesReceivingCountries;
use App\Models\Currency;
use App\Models\OnlineCustomer;
use App\Models\TransactionsData;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
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
            $sending_currencies = Currency::get();
            $receiving_currencies = CurrenciesReceivingCountries::get();
            if (!empty($request->customer_country) && empty($request->beneficiary_country) && !empty($request->date_from) && empty($request->date_to)) {
                $customer_date_from = date('Y-m-d', strtotime($request->date_from));
                $customer_country = $request->customer_country;
                if ($request->customer_country == 'All Customer Countries') {
                    $customers = OnlineCustomer::where('register_date', $customer_date_from)->with('transactions')->orwhereDoesntHave('transactions')
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
                        ])->get()
                        ->groupBy('country')
                        ->map(function ($row) {
                            $array = [];
                            $no_attempt_counts = 0;
                            foreach ($row as $inner_row) {
                                $no_attempt_count = 0;
                                $transacting_count = 0;
                                $non_transacting_count = 0;

                                $transacting_count += $inner_row->transacting_count;
                                $non_transacting_count += $inner_row->non_transacting_count;
                                if (!$inner_row->transactions->isEmpty()) {
                                    $no_attempt_count += 0;
                                    $no_attempt_counts += 0;
                                } else {
                                    $no_attempt_count += 1;
                                    $no_attempt_counts += 1;
                                }
                                foreach ($inner_row->transactions as $beneficiary_country) {
                                    $transacting_counts = 0;
                                    $non_transacting_counts = 0;
                                    if ($beneficiary_country->status == "Paid" || $beneficiary_country->status == "Ok" || $beneficiary_country->status == "Compliance Hold") {
                                        $transacting_counts = 1;
                                    }
                                    if ($beneficiary_country->status == "Cancled") {
                                        $non_transacting_counts = 1;
                                    }
                                    $array[$beneficiary_country->beneficiary_country][] = [
                                        'customer_id' => $beneficiary_country->customer_id,
                                        'beneficiary_country' => $beneficiary_country->beneficiary_country,
                                        'transacting_count' => $transacting_counts,
                                        'non_transacting_count' => $non_transacting_counts,
                                        'no_attempt_count' => $no_attempt_count,
                                    ];
                                }
                            }
                            $transacting_count =  $row->sum('transacting_count');
                            $non_transacting_count = $row->sum('non_transacting_count');
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                } else {
                    $customers_transactions = function ($query) use ($customer_country) {
                        $query->where('customer_country', 'LIKE', '%' . $customer_country . '%');
                    };
                    $get_coustomer_country = Currency::where('name', 'LIKE', '%' . $request->customer_country . '%')->select('iso3')->first();
                    $customer_country = $get_coustomer_country->iso3;
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
                        ])->where('country', $customer_country)->get()->groupBy('country')->map(function ($row) {
                            $array = [];
                            $no_attempt_counts = 0;
                            foreach ($row as $inner_row) {
                                $no_attempt_count = 0;
                                $transacting_count = 0;

                                $non_transacting_count = 0;

                                $transacting_count += $inner_row->transacting_count;
                                $non_transacting_count += $inner_row->non_transacting_count;
                                if (!$inner_row->transactions->isEmpty()) {
                                    $no_attempt_count += 0;
                                    $no_attempt_counts += 0;
                                } else {
                                    $no_attempt_count += 1;
                                    $no_attempt_counts += 1;
                                }
                                foreach ($inner_row->transactions as $beneficiary_country) {
                                    $transacting_counts = 0;
                                    $non_transacting_counts = 0;
                                    if ($beneficiary_country->status == "Paid" || $beneficiary_country->status == "Ok" || $beneficiary_country->status == "Compliance Hold") {
                                        $transacting_counts = 1;
                                    }
                                    if ($beneficiary_country->status == "Cancled") {
                                        $non_transacting_counts = 1;
                                    }
                                    $array[$beneficiary_country->beneficiary_country][] = [
                                        'customer_id' => $beneficiary_country->customer_id,
                                        'beneficiary_country' => $beneficiary_country->beneficiary_country,
                                        'transacting_count' => $transacting_counts,
                                        'non_transacting_count' => $non_transacting_counts,
                                        'no_attempt_count' => $no_attempt_count,
                                    ];
                                }
                            }
                            $transacting_count =  $row->sum('transacting_count');
                            $non_transacting_count = $row->sum('non_transacting_count');
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                }
            } elseif (empty($request->customer_country) && !empty($request->beneficiary_country) && !empty($request->date_from) && empty($request->date_to)) {
                $customer_date_from = date('Y-m-d', strtotime($request->date_from));
                $beneficiary_country = $request->beneficiary_country;
                if ($request->beneficiary_country == 'All Beneficiary Countries') {
                    $customers = OnlineCustomer::where('register_date', $customer_date_from)->orwhereDoesntHave('transactions')->with('transactions')
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
                            $no_attempt_counts = 0;
                            foreach ($row as $inner_row) {
                                $no_attempt_count = 0;
                                $transacting_count = 0;

                                $non_transacting_count = 0;

                                $transacting_count += $inner_row->transacting_count;
                                $non_transacting_count += $inner_row->non_transacting_count;
                                if (empty($inner_row->transactions)) {
                                    $no_attempt_count += 1;
                                } else {
                                    $no_attempt_count += 0;
                                }
                                foreach ($inner_row->transactions as $beneficiary_country) {
                                    $transacting_counts = 0;
                                    $non_transacting_counts = 0;
                                    if ($beneficiary_country->status == "Paid" || $beneficiary_country->status == "Ok" || $beneficiary_country->status == "Compliance Hold") {
                                        $transacting_counts = 1;
                                    }
                                    if ($beneficiary_country->status == "Cancled") {
                                        $non_transacting_counts = 1;
                                    }
                                    $array[$beneficiary_country->beneficiary_country][] = [
                                        'customer_id' => $beneficiary_country->customer_id,
                                        'beneficiary_country' => $beneficiary_country->beneficiary_country,
                                        'transacting_count' => $transacting_counts,
                                        'non_transacting_count' => $non_transacting_counts,
                                        'no_attempt_count' => $no_attempt_count,
                                    ];
                                }
                                if ($inner_row->transactions->isEmpty()) {
                                    $no_attempt_counts += 1;
                                } else {
                                    $no_attempt_counts += 0;
                                }
                            }
                            $transacting_count =  $row->sum('transacting_count');
                            $non_transacting_count = $row->sum('non_transacting_count');
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                } else {
                    $customers_transactions = function ($query) use ($beneficiary_country) {
                        $query->where('beneficiary_country', $beneficiary_country);
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
                            $no_attempt_counts = 0;
                            foreach ($row as $inner_row) {
                                $no_attempt_count = 0;
                                $transacting_count = 0;

                                $non_transacting_count = 0;

                                $transacting_count += $inner_row->transacting_count;
                                $non_transacting_count += $inner_row->non_transacting_count;
                                if (empty($inner_row->transactions)) {
                                    $no_attempt_count += 1;
                                } else {
                                    $no_attempt_count += 0;
                                }
                                foreach ($inner_row->transactions as $beneficiary_country) {
                                    $transacting_counts = 0;
                                    $non_transacting_counts = 0;
                                    if ($beneficiary_country->status == "Paid" || $beneficiary_country->status == "Ok" || $beneficiary_country->status == "Compliance Hold") {
                                        $transacting_counts = 1;
                                    }
                                    if ($beneficiary_country->status == "Cancled") {
                                        $non_transacting_counts = 1;
                                    }
                                    $array[$beneficiary_country->beneficiary_country][] = [
                                        'customer_id' => $beneficiary_country->customer_id,
                                        'beneficiary_country' => $beneficiary_country->beneficiary_country,
                                        'transacting_count' => $transacting_counts,
                                        'non_transacting_count' => $non_transacting_counts,
                                        'no_attempt_count' => $no_attempt_count,
                                    ];
                                }
                                if ($inner_row->transactions->isEmpty()) {
                                    $no_attempt_counts += 1;
                                } else {
                                    $no_attempt_counts += 0;
                                }
                            }
                            $transacting_count =  $row->sum('transacting_count');
                            $non_transacting_count = $row->sum('non_transacting_count');
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                    // dd($customers->toArray());
                }
            } elseif (!empty($request->customer_country) && !empty($request->beneficiary_country) && !empty($request->date_from) && empty($request->date_to)) {
                $customer_date_from = date('Y-m-d', strtotime($request->date_from));
                $beneficiary_country = $request->beneficiary_country;
                if ($request->customer_country == 'All Customer Countries' && $request->beneficiary_country == 'All Beneficiary Countries') {
                    $customer_country = $request->customer_country;
                    $customers = OnlineCustomer::where('register_date', $customer_date_from)->orwhereDoesntHave('transactions')->with('transactions')
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
                            $no_attempt_counts = 0;
                            foreach ($row as $inner_row) {
                                $no_attempt_count = 0;
                                $transacting_count = 0;

                                $non_transacting_count = 0;

                                $transacting_count += $inner_row->transacting_count;
                                $non_transacting_count += $inner_row->non_transacting_count;
                                if (!$inner_row->transactions->isEmpty()) {
                                    $no_attempt_count += 0;
                                    $no_attempt_counts += 0;
                                } else {
                                    $no_attempt_count += 1;
                                    $no_attempt_counts += 1;
                                }
                                foreach ($inner_row->transactions as $beneficiary_country) {
                                    $transacting_counts = 0;
                                    $non_transacting_counts = 0;
                                    if ($beneficiary_country->status == "Paid" || $beneficiary_country->status == "Ok" || $beneficiary_country->status == "Compliance Hold") {
                                        $transacting_counts = 1;
                                    }
                                    if ($beneficiary_country->status == "Cancled") {
                                        $non_transacting_counts = 1;
                                    }
                                    $array[$beneficiary_country->beneficiary_country][] = [
                                        'customer_id' => $beneficiary_country->customer_id,
                                        'beneficiary_country' => $beneficiary_country->beneficiary_country,
                                        'transacting_count' => $transacting_counts,
                                        'non_transacting_count' => $non_transacting_counts,
                                        'no_attempt_count' => $no_attempt_count,
                                    ];
                                }
                            }
                            $transacting_count =  $row->sum('transacting_count');
                            $non_transacting_count = $row->sum('non_transacting_count');
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                } elseif ($request->customer_country == 'All Customer Countries' && $request->beneficiary_country != 'All Beneficiary Countries') {
                    $customer_country = $request->customer_country;
                    $get_beneficiary_country = Currency::where('name', 'LIKE', '%' . $request->beneficiary_country . '%')->select('iso3')->first();
                    $beneficiary_country = $get_beneficiary_country->iso3;
                    $customers_transactions = function ($query) use ($beneficiary_country) {
                        $query->where('beneficiary_country', $beneficiary_country);
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
                            $no_attempt_counts = 0;
                            foreach ($row as $inner_row) {
                                $no_attempt_count = 0;
                                $transacting_count = 0;

                                $non_transacting_count = 0;

                                $transacting_count += $inner_row->transacting_count;
                                $non_transacting_count += $inner_row->non_transacting_count;
                                if (!$inner_row->transactions->isEmpty()) {
                                    $no_attempt_count += 0;
                                    $no_attempt_counts += 0;
                                } else {
                                    $no_attempt_count += 1;
                                    $no_attempt_counts += 1;
                                }
                                foreach ($inner_row->transactions as $beneficiary_country) {
                                    $transacting_counts = 0;
                                    $non_transacting_counts = 0;
                                    if ($beneficiary_country->status == "Paid" || $beneficiary_country->status == "Ok" || $beneficiary_country->status == "Compliance Hold") {
                                        $transacting_counts = 1;
                                    }
                                    if ($beneficiary_country->status == "Cancled") {
                                        $non_transacting_counts = 1;
                                    }
                                    $array[$beneficiary_country->beneficiary_country][] = [
                                        'customer_id' => $beneficiary_country->customer_id,
                                        'beneficiary_country' => $beneficiary_country->beneficiary_country,
                                        'transacting_count' => $transacting_counts,
                                        'non_transacting_count' => $non_transacting_counts,
                                        'no_attempt_count' => $no_attempt_count,
                                    ];
                                }
                            }
                            $transacting_count =  $row->sum('transacting_count');
                            $non_transacting_count = $row->sum('non_transacting_count');
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                } elseif ($request->customer_country != 'All Customer Countries' && $request->beneficiary_country == 'All Beneficiary Countries') {
                    $get_coustomer_country = Currency::where('name', 'LIKE', '%' . $request->customer_country . '%')->select('iso3')->first();
                    $customer_country = $get_coustomer_country->iso3;

                    $customers_transactions = function ($query) use ($request) {
                        $query->where('customer_country', 'LIKE', '%' . $request->customer_country . '%');
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
                        ])->where('country', $customer_country)
                        ->get()->groupBy('country')->map(function ($row) {
                            $array = [];
                            $no_attempt_counts = 0;
                            foreach ($row as $inner_row) {
                                $no_attempt_count = 0;
                                $transacting_count = 0;

                                $non_transacting_count = 0;

                                $transacting_count += $inner_row->transacting_count;
                                $non_transacting_count += $inner_row->non_transacting_count;
                                if (!$inner_row->transactions->isEmpty()) {
                                    $no_attempt_count += 0;
                                    $no_attempt_counts += 0;
                                } else {
                                    $no_attempt_count += 1;
                                    $no_attempt_counts += 1;
                                }
                                foreach ($inner_row->transactions as $beneficiary_country) {
                                    $transacting_counts = 0;
                                    $non_transacting_counts = 0;
                                    if ($beneficiary_country->status == "Paid" || $beneficiary_country->status == "Ok" || $beneficiary_country->status == "Compliance Hold") {
                                        $transacting_counts = 1;
                                    }
                                    if ($beneficiary_country->status == "Cancled") {
                                        $non_transacting_counts = 1;
                                    }
                                    $array[$beneficiary_country->beneficiary_country][] = [
                                        'customer_id' => $beneficiary_country->customer_id,
                                        'beneficiary_country' => $beneficiary_country->beneficiary_country,
                                        'transacting_count' => $transacting_counts,
                                        'non_transacting_count' => $non_transacting_counts,
                                        'no_attempt_count' => $no_attempt_count,
                                    ];
                                }
                            }
                            $transacting_count =  $row->sum('transacting_count');
                            $non_transacting_count = $row->sum('non_transacting_count');
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                } else {
                    $get_coustomer_country = Currency::where(
                        'name',
                        'LIKE',
                        '%' . $request->customer_country . '%'
                    )->select('iso3')->first();
                    $customer_country = $get_coustomer_country->iso3;
                    $customers_transactions = function ($query) use ($request, $beneficiary_country) {
                        $query->where('customer_country', 'LIKE', '%' . $request->customer_country . '%');
                        $query->where('beneficiary_country', $beneficiary_country);
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
                        ])->where('country', $customer_country)
                        ->get()->groupBy('country')->map(function ($row) {
                            $array = [];
                            $no_attempt_counts = 0;
                            foreach ($row as $inner_row) {
                                $no_attempt_count = 0;
                                $transacting_count = 0;

                                $non_transacting_count = 0;

                                $transacting_count += $inner_row->transacting_count;
                                $non_transacting_count += $inner_row->non_transacting_count;
                                if (!$inner_row->transactions->isEmpty()) {
                                    $no_attempt_count += 0;
                                    $no_attempt_counts += 0;
                                } else {
                                    $no_attempt_count += 1;
                                    $no_attempt_counts += 1;
                                }
                                foreach ($inner_row->transactions as $beneficiary_country) {
                                    $transacting_counts = 0;
                                    $non_transacting_counts = 0;
                                    if ($beneficiary_country->status == "Paid" || $beneficiary_country->status == "Ok" || $beneficiary_country->status == "Compliance Hold") {
                                        $transacting_counts = 1;
                                    }
                                    if ($beneficiary_country->status == "Cancled") {
                                        $non_transacting_counts = 1;
                                    }
                                    $array[$beneficiary_country->beneficiary_country][] = [
                                        'customer_id' => $beneficiary_country->customer_id,
                                        'beneficiary_country' => $beneficiary_country->beneficiary_country,
                                        'transacting_count' => $transacting_counts,
                                        'non_transacting_count' => $non_transacting_counts,
                                        'no_attempt_count' => $no_attempt_count,
                                    ];
                                }
                            }
                            $transacting_count =  $row->sum('transacting_count');
                            $non_transacting_count = $row->sum('non_transacting_count');
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                }
            } elseif (!empty($request->customer_country) && empty($request->beneficiary_country) && !empty($request->date_from) && !empty($request->date_to)) {
                $customer_date_from = date('Y-m-d', strtotime($request->date_from));
                $customer_date_to = date('Y-m-d', strtotime($request->date_to));
                if ($request->customer_country == 'All Customer Countries') {
                    $customers = OnlineCustomer::whereBetween('register_date', [$customer_date_from, $customer_date_to])->orwhereDoesntHave('transactions')->with('transactions')
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
                        ])->get()
                        ->groupBy('country')->map(function ($row) {
                            $array = [];
                            $no_attempt_counts = 0;
                            foreach ($row as $inner_row) {
                                $no_attempt_count = 0;
                                $transacting_count = 0;

                                $non_transacting_count = 0;

                                $transacting_count += $inner_row->transacting_count;
                                $non_transacting_count += $inner_row->non_transacting_count;
                                if (!$inner_row->transactions->isEmpty()) {
                                    $no_attempt_count += 0;
                                    $no_attempt_counts += 0;
                                } else {
                                    $no_attempt_count += 1;
                                    $no_attempt_counts += 1;
                                }
                                foreach ($inner_row->transactions as $beneficiary_country) {
                                    $transacting_counts = 0;
                                    $non_transacting_counts = 0;
                                    if ($beneficiary_country->status == "Paid" || $beneficiary_country->status == "Ok" || $beneficiary_country->status == "Compliance Hold") {
                                        $transacting_counts = 1;
                                    }
                                    if ($beneficiary_country->status == "Cancled") {
                                        $non_transacting_counts = 1;
                                    }
                                    $array[$beneficiary_country->beneficiary_country][] = [
                                        'customer_id' => $beneficiary_country->customer_id,
                                        'beneficiary_country' => $beneficiary_country->beneficiary_country,
                                        'transacting_count' => $transacting_counts,
                                        'non_transacting_count' => $non_transacting_counts,
                                        'no_attempt_count' => $no_attempt_count,
                                    ];
                                }
                            }
                            $transacting_count =  $row->sum('transacting_count');
                            $non_transacting_count = $row->sum('non_transacting_count');
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                } else {
                    $customers_transactions = function ($query) use ($request) {
                        $query->where('customer_country', 'LIKE', '%' . $request->customer_country . '%');
                    };
                    $get_coustomer_country = Currency::where('name', 'LIKE', '%' . $request->customer_country . '%')->select('iso3')->first();
                    $customer_country = $get_coustomer_country->iso3;
                    $customers = OnlineCustomer::whereBetween('register_date', [$customer_date_from, $customer_date_to])->orwhereDoesntHave('transactions')->with('transactions', $customers_transactions)
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
                        ])->where('country', $customer_country)
                        ->get()->groupBy('country')->map(function ($row) {
                            $array = [];
                            $no_attempt_counts = 0;
                            foreach ($row as $inner_row) {
                                $no_attempt_count = 0;
                                $transacting_count = 0;

                                $non_transacting_count = 0;

                                $transacting_count += $inner_row->transacting_count;
                                $non_transacting_count += $inner_row->non_transacting_count;
                                if (!$inner_row->transactions->isEmpty()) {
                                    $no_attempt_count += 0;
                                    $no_attempt_counts += 0;
                                } else {
                                    $no_attempt_count += 1;
                                    $no_attempt_counts += 1;
                                }
                                foreach ($inner_row->transactions as $beneficiary_country) {
                                    $transacting_counts = 0;
                                    $non_transacting_counts = 0;
                                    if ($beneficiary_country->status == "Paid" || $beneficiary_country->status == "Ok" || $beneficiary_country->status == "Compliance Hold") {
                                        $transacting_counts = 1;
                                    }
                                    if ($beneficiary_country->status == "Cancled") {
                                        $non_transacting_counts = 1;
                                    }
                                    $array[$beneficiary_country->beneficiary_country][] = [
                                        'customer_id' => $beneficiary_country->customer_id,
                                        'beneficiary_country' => $beneficiary_country->beneficiary_country,
                                        'transacting_count' => $transacting_counts,
                                        'non_transacting_count' => $non_transacting_counts,
                                        'no_attempt_count' => $no_attempt_count,
                                    ];
                                }
                            }
                            $transacting_count =  $row->sum('transacting_count');
                            $non_transacting_count = $row->sum('non_transacting_count');
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                }
            } elseif (empty($request->customer_country) && !empty($request->beneficiary_country) && !empty($request->date_from) && !empty($request->date_to)) {
                $customer_date_from = date('Y-m-d', strtotime($request->date_from));
                $customer_date_to = date('Y-m-d', strtotime($request->date_to));
                $beneficiary_country = $request->beneficiary_country;
                if ($request->beneficiary_country == 'All Beneficiary Countries') {
                    $customers = OnlineCustomer::whereBetween('register_date', [$customer_date_from, $customer_date_to])->orwhereDoesntHave('transactions')->with('transactions')
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
                            $no_attempt_counts = 0;
                            foreach ($row as $inner_row) {
                                $no_attempt_count = 0;
                                $transacting_count = 0;

                                $non_transacting_count = 0;

                                $transacting_count += $inner_row->transacting_count;
                                $non_transacting_count += $inner_row->non_transacting_count;
                                if (!$inner_row->transactions->isEmpty()) {
                                    $no_attempt_count += 0;
                                    $no_attempt_counts += 0;
                                } else {
                                    $no_attempt_count += 1;
                                    $no_attempt_counts += 1;
                                }
                                foreach ($inner_row->transactions as $beneficiary_country) {
                                    $transacting_counts = 0;
                                    $non_transacting_counts = 0;
                                    if ($beneficiary_country->status == "Paid" || $beneficiary_country->status == "Ok" || $beneficiary_country->status == "Compliance Hold") {
                                        $transacting_counts = 1;
                                    }
                                    if ($beneficiary_country->status == "Cancled") {
                                        $non_transacting_counts = 1;
                                    }
                                    $array[$beneficiary_country->beneficiary_country][] = [
                                        'customer_id' => $beneficiary_country->customer_id,
                                        'beneficiary_country' => $beneficiary_country->beneficiary_country,
                                        'transacting_count' => $transacting_counts,
                                        'non_transacting_count' => $non_transacting_counts,
                                        'no_attempt_count' => $no_attempt_count,
                                    ];
                                }
                            }
                            $transacting_count =  $row->sum('transacting_count');
                            $non_transacting_count = $row->sum('non_transacting_count');
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                } else {

                    $customers_transactions = function ($query) use ($beneficiary_country) {
                        $query->where('beneficiary_country', $beneficiary_country);
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
                        ->get()->groupBy('country')->map(function ($row) {
                            $array = [];
                            $no_attempt_counts = 0;
                            foreach ($row as $inner_row) {
                                $no_attempt_count = 0;
                                $transacting_count = 0;

                                $non_transacting_count = 0;

                                $transacting_count += $inner_row->transacting_count;
                                $non_transacting_count += $inner_row->non_transacting_count;
                                if (!$inner_row->transactions->isEmpty()) {
                                    $no_attempt_count += 0;
                                    $no_attempt_counts += 0;
                                } else {
                                    $no_attempt_count += 1;
                                    $no_attempt_counts += 1;
                                }
                                foreach ($inner_row->transactions as $beneficiary_country) {
                                    $transacting_counts = 0;
                                    $non_transacting_counts = 0;
                                    if ($beneficiary_country->status == "Paid" || $beneficiary_country->status == "Ok" || $beneficiary_country->status == "Compliance Hold") {
                                        $transacting_counts = 1;
                                    }
                                    if ($beneficiary_country->status == "Cancled") {
                                        $non_transacting_counts = 1;
                                    }
                                    $array[$beneficiary_country->beneficiary_country][] = [
                                        'customer_id' => $beneficiary_country->customer_id,
                                        'beneficiary_country' => $beneficiary_country->beneficiary_country,
                                        'transacting_count' => $transacting_counts,
                                        'non_transacting_count' => $non_transacting_counts,
                                        'no_attempt_count' => $no_attempt_count,
                                    ];
                                }
                            }
                            $transacting_count =  $row->sum('transacting_count');
                            $non_transacting_count = $row->sum('non_transacting_count');
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                }
            } elseif (!empty($request->customer_country) && !empty($request->beneficiary_country) && !empty($request->date_from) && !empty($request->date_to)) {
                $customer_date_from = date('Y-m-d', strtotime($request->date_from));
                $customer_date_to = date('Y-m-d', strtotime($request->date_to));

                $customer_country = $request->customer_country;
                $beneficiary_country = $request->beneficiary_country;
                $start = Carbon::parse($customer_date_from);
                $end =  Carbon::parse($customer_date_to);
                $days = $end->diffInDays($start);
                if ($request->customer_country == 'All Customer Countries' && $request->beneficiary_country == 'All Beneficiary Countries') {
                    $customers = OnlineCustomer::whereBetween('register_date', [$customer_date_from, $customer_date_to])->orwhereDoesntHave('transactions')->with('transactions')
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
                            $no_attempt_counts = 0;
                            foreach ($row as $inner_row) {
                                $no_attempt_count = 0;
                                $transacting_count = 0;

                                $non_transacting_count = 0;

                                $transacting_count += $inner_row->transacting_count;
                                $non_transacting_count += $inner_row->non_transacting_count;
                                if (!$inner_row->transactions->isEmpty()) {
                                    $no_attempt_count += 0;
                                    $no_attempt_counts += 0;
                                } else {
                                    $no_attempt_count += 1;
                                    $no_attempt_counts += 1;
                                }
                                foreach ($inner_row->transactions as $beneficiary_country) {
                                    $transacting_counts = 0;
                                    $non_transacting_counts = 0;
                                    if ($beneficiary_country->status == "Paid" || $beneficiary_country->status == "Ok" || $beneficiary_country->status == "Compliance Hold") {
                                        $transacting_counts = 1;
                                    }
                                    if ($beneficiary_country->status == "Cancled") {
                                        $non_transacting_counts = 1;
                                    }
                                    $array[$beneficiary_country->beneficiary_country][] = [
                                        'customer_id' => $beneficiary_country->customer_id,
                                        'beneficiary_country' => $beneficiary_country->beneficiary_country,
                                        'transacting_count' => $transacting_counts,
                                        'non_transacting_count' => $non_transacting_counts,
                                        'no_attempt_count' => $no_attempt_count,
                                    ];
                                }
                            }
                            $transacting_count =  $row->sum('transacting_count');
                            $non_transacting_count = $row->sum('non_transacting_count');
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                } elseif ($request->customer_country == 'All Customer Countries' && $request->beneficiary_country != 'All Beneficiary Countries') {
                    $customers_transactions = function ($query) use ($beneficiary_country) {
                        $query->where('beneficiary_country', $beneficiary_country);
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
                        ->get()->groupBy('country')->map(function ($row) {
                            $array = [];
                            $no_attempt_counts = 0;
                            foreach ($row as $inner_row) {
                                $no_attempt_count = 0;
                                $transacting_count = 0;

                                $non_transacting_count = 0;

                                $transacting_count += $inner_row->transacting_count;
                                $non_transacting_count += $inner_row->non_transacting_count;
                                if (!$inner_row->transactions->isEmpty()) {
                                    $no_attempt_count += 0;
                                    $no_attempt_counts += 0;
                                } else {
                                    $no_attempt_count += 1;
                                    $no_attempt_counts += 1;
                                }
                                foreach ($inner_row->transactions as $beneficiary_country) {
                                    $transacting_counts = 0;
                                    $non_transacting_counts = 0;
                                    if ($beneficiary_country->status == "Paid" || $beneficiary_country->status == "Ok" || $beneficiary_country->status == "Compliance Hold") {
                                        $transacting_counts = 1;
                                    }
                                    if ($beneficiary_country->status == "Cancled") {
                                        $non_transacting_counts = 1;
                                    }
                                    $array[$beneficiary_country->beneficiary_country][] = [
                                        'customer_id' => $beneficiary_country->customer_id,
                                        'beneficiary_country' => $beneficiary_country->beneficiary_country,
                                        'transacting_count' => $transacting_counts,
                                        'non_transacting_count' => $non_transacting_counts,
                                        'no_attempt_count' => $no_attempt_count,
                                    ];
                                }
                            }
                            $transacting_count =  $row->sum('transacting_count');
                            $non_transacting_count = $row->sum('non_transacting_count');
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                } elseif ($request->customer_country != 'All Customer Countries' && $request->beneficiary_country == 'All Beneficiary Countries') {
                    $get_coustomer_country = Currency::where('name', 'LIKE', '%' . $request->customer_country . '%')->select('iso3')->first();
                    $customer_country = $get_coustomer_country->iso3;

                    $customers_transactions = function ($query) use ($request) {
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
                        ])->where('country', $customer_country)
                        ->get()->groupBy('country')->map(function ($row) {
                            $array = [];
                            $no_attempt_counts = 0;
                            foreach ($row as $inner_row) {
                                $no_attempt_count = 0;
                                $transacting_count = 0;

                                $non_transacting_count = 0;

                                $transacting_count += $inner_row->transacting_count;
                                $non_transacting_count += $inner_row->non_transacting_count;
                                if (!$inner_row->transactions->isEmpty()) {
                                    $no_attempt_count += 0;
                                    $no_attempt_counts += 0;
                                } else {
                                    $no_attempt_count += 1;
                                    $no_attempt_counts += 1;
                                }
                                foreach ($inner_row->transactions as $beneficiary_country) {
                                    $transacting_counts = 0;
                                    $non_transacting_counts = 0;
                                    if ($beneficiary_country->status == "Paid" || $beneficiary_country->status == "Ok" || $beneficiary_country->status == "Compliance Hold") {
                                        $transacting_counts = 1;
                                    }
                                    if ($beneficiary_country->status == "Cancled") {
                                        $non_transacting_counts = 1;
                                    }
                                    $array[$beneficiary_country->beneficiary_country][] = [
                                        'customer_id' => $beneficiary_country->customer_id,
                                        'beneficiary_country' => $beneficiary_country->beneficiary_country,
                                        'transacting_count' => $transacting_counts,
                                        'non_transacting_count' => $non_transacting_counts,
                                        'no_attempt_count' => $no_attempt_count,
                                    ];
                                }
                            }
                            $transacting_count =  $row->sum('transacting_count');
                            $non_transacting_count = $row->sum('non_transacting_count');
                            return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
                        });
                } else {
                    $customers_transactions = function ($query) use ($customer_country, $beneficiary_country) {
                        $query->where('customer_country', 'LIKE', '%' . $customer_country . '%');
                        $query->where('beneficiary_country', $beneficiary_country);
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
                        ])->where('country', $customer_country)
                        ->get()->groupBy('country')->map(function ($row) {
                            $array = [];
                            $no_attempt_counts = 0;
                            foreach ($row as $inner_row) {
                                $no_attempt_count = 0;
                                $transacting_count = 0;

                                $non_transacting_count = 0;

                                $transacting_count += $inner_row->transacting_count;
                                $non_transacting_count += $inner_row->non_transacting_count;
                                if (!$inner_row->transactions->isEmpty()) {
                                    $no_attempt_count += 0;
                                    $no_attempt_counts += 0;
                                } else {
                                    $no_attempt_count += 1;
                                    $no_attempt_counts += 1;
                                }
                                foreach ($inner_row->transactions as $beneficiary_country) {
                                    $transacting_counts = 0;
                                    $non_transacting_counts = 0;
                                    if ($beneficiary_country->status == "Paid" || $beneficiary_country->status == "Ok" || $beneficiary_country->status == "Compliance Hold") {
                                        $transacting_counts = 1;
                                    }
                                    if ($beneficiary_country->status == "Cancled") {
                                        $non_transacting_counts = 1;
                                    }
                                    $array[$beneficiary_country->beneficiary_country][] = [
                                        'customer_id' => $beneficiary_country->customer_id,
                                        'beneficiary_country' => $beneficiary_country->beneficiary_country,
                                        'transacting_count' => $transacting_counts,
                                        'non_transacting_count' => $non_transacting_counts,
                                        'no_attempt_count' => $no_attempt_count,
                                    ];
                                }
                            }
                            $transacting_count =  $row->sum('transacting_count');
                            $non_transacting_count = $row->sum('non_transacting_count');
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
