<?php

namespace App\Http\Controllers\operations;

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
                $transactions = TransactionsData::select($hours, $tr_no_count)->where('transaction_date', $date_from)
                    ->groupBy(DB::raw('HOUR(transaction_time)'))
                    ->orderBy('transaction_time', 'ASC')
                    ->orderBy('customer_country', 'ASC')
                    ->get();
                $transactions_data = TransactionsData::select('customer_country', $hours, $tr_no_count)->where('transaction_date', $date_from)
                    ->groupBy([DB::raw('HOUR(transaction_time)'), 'customer_country'])
                    ->orderBy('transaction_time', 'ASC')
                    ->orderBy('customer_country', 'ASC')
                    ->get()->groupBy('hours');
                // dd($transactions->toArray());
                return view('operations.transactions.hourly.index', ['transactions' => $transactions, 'transactions_data' => $transactions_data]);
            } else {
                return redirect()->back()->with('failed', "Date Mandatory");
            }
        }
    }
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