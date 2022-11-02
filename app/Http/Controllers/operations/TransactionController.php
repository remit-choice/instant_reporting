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
            date_default_timezone_set('Europe/London');

            // select('transaction_time', $hours, $tr_no_count)->where('transaction_date', $date_from->subHour())
            //     ->groupBy(DB::raw('HOUR(transaction_time)'))
            //     ->orderBy('transaction_time', 'ASC')
            //     ->get();

            // dd($end_of_day);
            if (!empty($request->search_filter) && !empty($request->date_from)) {
                $tr_no_count = DB::raw('count(tr_no) as count_of_tr_no');
                $hours = DB::raw('HOUR(transaction_time) as hours');
                $date_from = date('d/m/Y', strtotime($request->date_from));

                $transactions = TransactionsData::select('customer_country',  'transaction_time', $hours, $tr_no_count)->where('transaction_date', $date_from)
                    ->groupBy([DB::raw('HOUR(transaction_time)'), 'customer_country'])
                    // ->orderBy('hours', 'ASC')
                    ->get();
                // $transactions_data = TransactionsData::select('customer_country', $hours, $tr_no_count)->where('transaction_date', $date_from)
                //     ->groupBy('customer_country')
                //     ->get();
                // for Day
                // $transactions_data = TransactionsData::select('transaction_time', $hours, $tr_no_count)->get()->groupBy(function ($date_from) {
                //     return Carbon::parse($date_from)->format('s');
                // });
                // dd($transactions->toArray());
                $data = [];
                $hour = [];
                $customer_country = [];
                $count_of_tr_no = 0;
                foreach ($transactions as $transaction) {
                    // if (in_array($transaction->hours, $hour)) {
                    //     $customer_country[] = [
                    //         'customer_country' => $transaction->customer_country,
                    //     ];
                    // }
                    // } else {
                    //     $customer_country[] = [
                    //         'customer_country' => $transaction->customer_country,
                    //     ];
                    // }
                    if (!in_array($transaction->hours, $hour)) {
                        $hour[] = $transaction->hours;
                        $customer_country[] = [
                            'customer_country' => $transaction->customer_country,
                        ];
                        $data[] = [
                            'hours' => $transaction->hours,
                            'tr_no_count' => $transaction->count_of_tr_no,
                            'customer_country' => $customer_country,
                        ];
                    } else {
                        $customer_country[] = [
                            'customer_country' => $transaction->customer_country,
                        ];
                    }
                }
                dd($data);
                return view('operations.transactions.hourly.index', ['transactions' => $transactions]);
            } else {
                return redirect()->back()->with('failed', "Date Mandatory");
            }
        }
    }
}
