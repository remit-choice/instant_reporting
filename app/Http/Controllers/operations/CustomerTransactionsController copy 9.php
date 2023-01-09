<?php

namespace App\Http\Controllers\operations;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Models\CurrenciesReceivingCountries;
use App\Models\Currency;
use App\Models\OnlineCustomer;
use Illuminate\Http\Request;
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
            $sending_currencies = Currency::get();
            $receiving_currencies = CurrenciesReceivingCountries::get();
            $transacting_query = [
                'transactions as transacting_count' => function ($query) {
                    if ($query->where('status', 'Paid') || $query->where('status', 'Ok') || $query->where('status', 'Compliance Hold')) {
                        return 1;
                    }
                },
                'transactions as non_transacting_count' => function ($query) {
                    if ($query->where('status', 'Canceled')) {
                        return 1;
                    }
                },
            ];
            if (!empty($request->customer_country) && empty($request->beneficiary_country) && !empty($request->date_from) && empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $date_from_trx = date('j/n/Y', strtotime($request->date_from));
                // dd($date_from_trx);
                $customer_country = $request->customer_country;
                if ($request->customer_country == 'All Customer Countries') {
                    $customers_transactions = function ($query) use ($date_from_trx) {
                        $query->where('transaction_date', $date_from_trx);
                    };
                    $customers = OnlineCustomer::where('register_date', $date_from)->with('transactions', $customers_transactions)
                        ->withCount($transacting_query)->get()->groupBy('country')
                        ->map(function ($row) {
                            return $this->filter_funciton($row);
                        });
                    // $customers = OnlineCustomer::where('register_date', $date_from)->with('transactions')
                    //     ->withCount($transacting_query)->groupBy('country')->toSql();
                    // dd($customers);
                    dd($customers->toArray());
                } else {
                    $customers_transactions = function ($query) use ($customer_country) {
                        $query->where('customer_country', 'LIKE', '%' . $customer_country . '%');
                    };
                    $get_coustomer_country = Currency::where('name', 'LIKE', '%' . $request->customer_country . '%')->select('iso3')->first();
                    $customer_country = $get_coustomer_country->iso3;
                    $customers = OnlineCustomer::where('register_date', $date_from)->with('transactions', $customers_transactions)
                        ->withCount($transacting_query)->where('country', $customer_country)->get()->groupBy('country')->map(function ($row) {
                            return $this->filter_funciton($row);
                        });
                    // dd($customers->toArray());
                }
            } elseif (empty($request->customer_country) && !empty($request->beneficiary_country) && !empty($request->date_from) && empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $beneficiary_country = $request->beneficiary_country;
                if ($request->beneficiary_country == 'All Beneficiary Countries') {
                    $customers = OnlineCustomer::where('register_date', $date_from)->with('transactions')
                        ->withCount($transacting_query)
                        ->get()->groupBy('country')->map(function ($row) {
                            return $this->filter_funciton($row);
                        });
                } else {
                    $customers_transactions = function ($query) use ($beneficiary_country) {
                        $query->where('beneficiary_country', $beneficiary_country);
                    };
                    $customers_transactions_data = function ($query) use ($customers_transactions) {
                        $query->whereHas('transactions', $customers_transactions)->orWhereDoesntHave('transactions');
                    };
                    $customers = OnlineCustomer::where('register_date', $date_from)->where($customers_transactions_data)->with('transactions')
                        ->withCount($transacting_query)->get()->groupBy('country')
                        ->map(function ($row) {
                            return $this->filter_funciton($row);
                        });
                    // dd($customers->toArray());
                }
            } elseif (!empty($request->customer_country) && !empty($request->beneficiary_country) && !empty($request->date_from) && empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $beneficiary_country = $request->beneficiary_country;
                if ($request->customer_country == 'All Customer Countries' && $request->beneficiary_country == 'All Beneficiary Countries') {
                    $customer_country = $request->customer_country;
                    $customers = OnlineCustomer::where('register_date', $date_from)->with('transactions')
                        ->withCount($transacting_query)
                        ->get()->groupBy('country')->map(function ($row) {
                            return $this->filter_funciton($row);
                        });
                } elseif ($request->customer_country == 'All Customer Countries' && $request->beneficiary_country != 'All Beneficiary Countries') {
                    $customer_country = $request->customer_country;
                    $customers_transactions = function ($query) use ($beneficiary_country) {
                        $query->where('beneficiary_country', $beneficiary_country);
                    };
                    $customers_transactions_data = function ($query) use ($customers_transactions) {
                        $query->whereHas('transactions', $customers_transactions)->orWhereDoesntHave('transactions');
                    };
                    $customers = OnlineCustomer::where('register_date', $date_from)->where($customers_transactions_data)->with('transactions')
                        ->withCount($transacting_query)
                        ->get()->groupBy('country')->map(function ($row) {
                            return $this->filter_funciton($row);
                        });
                } elseif ($request->customer_country != 'All Customer Countries' && $request->beneficiary_country == 'All Beneficiary Countries') {
                    $get_coustomer_country = Currency::where('name', 'LIKE', '%' . $request->customer_country . '%')->select('iso3')->first();
                    $customer_country = $get_coustomer_country->iso3;
                    $customers = OnlineCustomer::where('register_date', $date_from)->with('transactions')
                        ->withCount($transacting_query)->where('country', $customer_country)
                        ->get()->groupBy('country')->map(function ($row) {
                            return $this->filter_funciton($row);
                        });
                } else {
                    $get_coustomer_country = Currency::where(
                        'name',
                        'LIKE',
                        '%' . $request->customer_country . '%'
                    )->select('iso3')->first();
                    $customer_country = $get_coustomer_country->iso3;
                    $customers_transactions = function ($query) use ($beneficiary_country) {
                        $query->where('beneficiary_country', $beneficiary_country);
                    };
                    $customers_transactions_data = function ($query) use ($customers_transactions) {
                        $query->whereHas('transactions', $customers_transactions)->orWhereDoesntHave('transactions');
                    };
                    $customers = OnlineCustomer::where('register_date', $date_from)->where($customers_transactions_data)->with('transactions')
                        ->withCount($transacting_query)->where('country', $customer_country)
                        ->get()->groupBy('country')->map(function ($row) {
                            return $this->filter_funciton($row);
                        });
                }
            } elseif (!empty($request->customer_country) && empty($request->beneficiary_country) && !empty($request->date_from) && !empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $date_to = date('d/m/Y', strtotime($request->date_to));
                if ($request->customer_country == 'All Customer Countries') {
                    $customers = OnlineCustomer::whereBetween('register_date', [$date_from, $date_to])->with('transactions')
                        ->withCount($transacting_query)->get()
                        ->groupBy('country')->map(function ($row) {
                            return $this->filter_funciton($row);
                        });
                } else {
                    $customers_transactions = function ($query) use ($request) {
                        $query->where('customer_country', 'LIKE', '%' . $request->customer_country . '%');
                    };
                    $get_coustomer_country = Currency::where('name', 'LIKE', '%' . $request->customer_country . '%')->select('iso3')->first();
                    $customer_country = $get_coustomer_country->iso3;
                    $customers = OnlineCustomer::whereBetween('register_date', [$date_from, $date_to])->with('transactions', $customers_transactions)
                        ->withCount($transacting_query)->where('country', $customer_country)
                        ->get()->groupBy('country')->map(function ($row) {
                            return $this->filter_funciton($row);
                        });
                }
            } elseif (empty($request->customer_country) && !empty($request->beneficiary_country) && !empty($request->date_from) && !empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $date_to = date('d/m/Y', strtotime($request->date_to));
                $beneficiary_country = $request->beneficiary_country;
                if ($request->beneficiary_country == 'All Beneficiary Countries') {
                    $customers = OnlineCustomer::whereBetween('register_date', [$date_from, $date_to])->with('transactions')
                        ->withCount($transacting_query)
                        ->get()->groupBy('country')->map(function ($row) {
                            return $this->filter_funciton($row);
                        });
                } else {

                    $customers_transactions = function ($query) use ($beneficiary_country) {
                        $query->where('beneficiary_country', $beneficiary_country);
                    };
                    $customers_transactions_data = function ($query) use ($customers_transactions) {
                        $query->whereHas('transactions', $customers_transactions)->orWhereDoesntHave('transactions');
                    };
                    $customers = OnlineCustomer::whereBetween('register_date', [$date_from, $date_to])->where($customers_transactions_data)->with('transactions')
                        ->withCount($transacting_query)
                        ->get()->groupBy('country')->map(function ($row) {
                            return $this->filter_funciton($row);
                        });
                }
            } elseif (!empty($request->customer_country) && !empty($request->beneficiary_country) && !empty($request->date_from) && !empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $date_to = date('d/m/Y', strtotime($request->date_to));
                $customer_country = $request->customer_country;
                $beneficiary_country = $request->beneficiary_country;
                if ($request->customer_country == 'All Customer Countries' && $request->beneficiary_country == 'All Beneficiary Countries') {
                    $customers = OnlineCustomer::whereBetween('register_date', [$date_from, $date_to])->with('transactions')
                        ->withCount($transacting_query)
                        ->get()->groupBy('country')->map(function ($row) {
                            return $this->filter_funciton($row);
                        });
                } elseif ($request->customer_country == 'All Customer Countries' && $request->beneficiary_country != 'All Beneficiary Countries') {
                    $customers_transactions = function ($query) use ($beneficiary_country) {
                        $query->where('beneficiary_country', $beneficiary_country);
                    };
                    $customers_transactions_data = function ($query) use ($customers_transactions) {
                        $query->whereHas('transactions', $customers_transactions)->orWhereDoesntHave('transactions');
                    };
                    $customers = OnlineCustomer::whereBetween('register_date', [$date_from, $date_to])->where($customers_transactions_data)->with('transactions')
                        ->withCount($transacting_query)
                        ->get()->groupBy('country')->map(function ($row) {
                            return $this->filter_funciton($row);
                        });
                    // dd($customers->toArray());
                } elseif ($request->customer_country != 'All Customer Countries' && $request->beneficiary_country == 'All Beneficiary Countries') {
                    $get_coustomer_country = Currency::where('name', 'LIKE', '%' . $request->customer_country . '%')->select('iso3')->first();
                    $customer_country = $get_coustomer_country->iso3;
                    $customers = OnlineCustomer::whereBetween('register_date', [$date_from, $date_to])->with('transactions')
                        ->withCount($transacting_query)->where('country', $customer_country)
                        ->get()->groupBy('country')->map(function ($row) {
                            return $this->filter_funciton($row);
                        });
                } else {
                    $get_coustomer_country = Currency::where('name', 'LIKE', '%' . $request->customer_country . '%')->select('iso3')->first();
                    $customer_country = $get_coustomer_country->iso3;
                    $customers_transactions = function ($query) use ($beneficiary_country) {
                        $query->where('beneficiary_country', $beneficiary_country);
                    };
                    $customers_transactions_data = function ($query) use ($customers_transactions) {
                        $query->whereHas('transactions', $customers_transactions)->orWhereDoesntHave('transactions');
                    };
                    $customers = OnlineCustomer::whereBetween('register_date', [$date_from, $date_to])->where($customers_transactions_data)->with('transactions')
                        ->withCount($transacting_query)->where('country', $customer_country)
                        ->get()->groupBy('country')->map(function ($row) {
                            return $this->filter_funciton($row);
                        });
                }
            } else {
                return redirect()->back()->with('failed', "From Date Mandatory");
            }
            return view('operations.transactions.customers.index', ['customers' => $customers,  'sending_currencies' => $sending_currencies, 'receiving_currencies' => $receiving_currencies]);
        }
    }
    public function filter_funciton($row)
    {
        $array = [];
        $no_attempt_counts = 0;
        foreach ($row as $inner_row) {
            $no_attempt_count = 0;
            $transacting_count = 0;
            $non_transacting_count = 0;

            $transacting_count += $inner_row->transacting_count;
            $non_transacting_count += $inner_row->non_transacting_count;
            if ($inner_row->transactions->isEmpty()) {
                $no_attempt_count += 1;
                $no_attempt_counts += 1;
            }
            $transacting_counts = 0;
            $non_transacting_counts = 0;

            foreach ($inner_row->transactions as $beneficiary_country) {
                if ($beneficiary_country->status == "Paid" || $beneficiary_country->status == "Ok" || $beneficiary_country->status == "Compliance Hold") {
                    $transacting_counts = 1;
                }
                if ($beneficiary_country->status == "Canceled") {
                    $non_transacting_counts = 1;
                }
                $array[$beneficiary_country->beneficiary_country][] = [
                    // 'customer_id' => $beneficiary_country->customer_id,
                    // 'beneficiary_country' => $beneficiary_country->beneficiary_country,
                    'transacting_count' => $transacting_counts,
                    'non_transacting_count' => $non_transacting_counts,
                ];
            }
            $transacting_count =  $row->sum('transacting_count');
            $non_transacting_count = $row->sum('non_transacting_count');
        }

        return json_decode(json_encode(['data' => $array, 'transacting_count' => $transacting_count, 'non_transacting_count' => $non_transacting_count, 'no_attempt_count' => $no_attempt_counts]));
    }
}
