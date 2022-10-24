<?php

namespace App\Http\Controllers\accounts;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Models\TransactionsData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            app(UserController::class)->main();
            return $next($request);
        });
    }
    public function index(Request $request)
    {
        if (FacadesRequest::isMethod('get')) {
            if ($request->has('filter')) {
                $transactions_data =  '1';
                $date_from = '';
                $date_to = '';
                $filter = '';

                if (!empty($request->date_from) && empty($request->date_to) && empty($request->filter)) {
                    $date_from = strtotime($request->date_from);
                    $date_from = date('d/m/Y', $date_from);
                    $transactions = TransactionsData::where('transaction_date', $date_from)->get();
                    $count_of_tr_no = DB::select("SELECT * , count(tr_no) as count_of_tr_no FROM transactions_data GROUP BY beneficiary_country");
                    // $count_of_tr_no = TransactionsData::select('tr_no', 'beneficiary_country', DB::raw('count(*) as count_of_tr_no'))->groupBy('beneficiary_country')->get();
                    // dd($transactions->toArray());
                } elseif (empty($request->date_from) && !empty($request->date_to) && empty($request->filter)) {
                    $date_to = strtotime($request->date_to);
                    $date_to = date('n/j/Y', $date_to);
                    $transactions = TransactionsData::where('last_transaction_date',  $date_to)->get();
                    $count_of_tr_no = DB::select("SELECT * , count(tr_no) as count_of_tr_no FROM transactions_data GROUP BY beneficiary_country");

                    // $count_of_tr_no = TransactionsData::select('tr_no', 'beneficiary_country', DB::raw('count(*) as count_of_tr_no'))->groupBy('beneficiary_country')->get();
                    // dd($transactions->toArray());
                } elseif (!empty($request->date_from) && !empty($request->date_to) && empty($request->filter)) {
                    $date_from = strtotime($request->date_from);
                    $date_from = date('d/m/Y', $date_from);
                    $date_to = strtotime($request->date_to);
                    $date_to = date('n/j/Y', $date_to);
                    $transactions = TransactionsData::where([['transaction_date', '>=', $date_from], ['last_transaction_date', '<=', "$date_to%"]])->get();
                    $count_of_tr_no = DB::select("SELECT * , count(tr_no) as count_of_tr_no FROM transactions_data GROUP BY beneficiary_country");
                    // $count_of_tr_no = TransactionsData::select('tr_no', 'beneficiary_country', DB::raw('count(*) as count_of_tr_no'))->groupBy('beneficiary_country')->get();
                    // dd($transactions->toArray());
                } elseif (!empty($request->date_from) || !empty($request->date_to) && !empty($request->filter)) {
                    // $currency = $request->currency;
                    $date_from = strtotime($request->date_from);
                    $date_from = date('d/m/Y', $date_from);
                    $date_to = strtotime($request->date_to);
                    $date_to = date('n/j/Y', $date_to);
                    if (!empty($request->date_from) && empty($request->date_to)) {
                        $transactions = TransactionsData::where('transaction_date', '>=', $date_from)->get();
                    } elseif (empty($request->date_from) && !empty($request->date_to)) {
                        $transactions = TransactionsData::where('last_transaction_date', '<=', "$date_to%")->get();
                    } else {
                    }
                    $count_of_tr_no = DB::select("SELECT * , count(tr_no) as count_of_tr_no FROM transactions_data GROUP BY beneficiary_country");

                    // $count_of_tr_no = TransactionsData::select('tr_no', 'beneficiary_country', DB::raw('count(*) as count_of_tr_no'))->groupBy('beneficiary_country')->get();
                } else {
                }
                return
                    view('accounts.transactions.index', ['date_from' => $date_from, 'date_to' => $date_to, 'transactions' => $transactions, 'transactions_data' => $transactions_data, 'count_of_tr_no' => $count_of_tr_no]);
            } else {
                $transactions_data =  '';
                return view('accounts.transactions.index', ['transactions_data' => $transactions_data]);
            }
        } else {
        }
    }
}
