<?php

namespace App\Http\Controllers\operations;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Models\CurrenciesRate;
use App\Models\Currency;
use App\Models\TransactionsData;
use Carbon\Carbon;
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
    public function sending_index(Request $request)
    {
        if (FacadesRequest::isMethod('get')) {
            return view('operations.transactions.hourly.index');
        } else {
        }
    }
    public function sending_filter(Request $request)
    {
        if (FacadesRequest::isMethod('post')) {

            if (!empty($request->search_filter) && !empty($request->date_from)) {
                $tr_no_count = DB::raw('count(tr_no) as count_of_tr_no');
                $hours = DB::raw('HOUR(transaction_time) as hours');
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $transactions = TransactionsData::select('transaction_time', $hours, $tr_no_count)->where('transaction_date', $date_from)
                    // ->where('transaction_date', $date_from)
                    ->groupBy(DB::raw('HOUR(transaction_time)'))
                    ->orderBy('transaction_time', 'ASC')
                    ->get();
                $transactions = TransactionsData::select('transaction_time', $hours, $tr_no_count)->where('transaction_date', $date_from)
                    ->groupBy(DB::raw('HOUR(transaction_time)'))
                    ->orderBy('transaction_time', 'ASC')
                    ->get();
                dd($transactions->toArray());
                return view('operations.transactions.hourly.index', ['transactions' => $transactions]);
            } else {
                return redirect()->back()->with('failed', "Date Mandatory");
            }
        }
    }
}
