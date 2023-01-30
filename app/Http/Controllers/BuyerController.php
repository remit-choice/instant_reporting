<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Currency;
use App\Models\PaymentMethod;
use App\Models\TransactionsData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;

class BuyerController extends Controller
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
        $buyers =  Buyer::get();
        return view('admin.buyer.index', ['buyers' => $buyers]);
    }
    public function create(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'type' => 'required|integer|max:255',
                'status' => 'required|integer|max:255',
            ],
            [
                'name.required' => "*Name is required",
                'type.required' => "*Type is required",
                'status.required' => "*Status is required",
            ]
        );
        $name = $request->name;
        $type = $request->type;
        $status = $request->status;

        Buyer::insert([
            'name' => $name,
            'type' => $type,
            'status' => $status
        ]);
    }
    public function update(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'type' => 'required|integer|max:255',
                'status' => 'required|integer|max:255',
            ],
            [
                'name.required' => "*Name is required",
                'type.required' => "*Type is required",
                'status.required' => "*Status is required",
            ]
        );
        $id = $request->id;
        $name = $request->name;
        $type = $request->type;
        $status = $request->status;

        Buyer::where('id', $id)->update([
            'name' => $name,
            'type' => $type,
            'status' => $status
        ]);
    }
    public function delete(Request $request)
    {
        $id = $request->id;
        Buyer::where('id', $id)->delete();
    }
    //Report
    public function filter(Request $request)
    {
        $id = $request->id;
        $buyers =  Buyer::where('id', $id)->get();
        $buyer_name =  Buyer::where('id', $id)->select('name')->first();
        if (FacadesRequest::isMethod('get')) {
            $transactions = '';
            return view('admin.buyer.report.index', ['buyer_name' => $buyer_name, 'id' => $id, 'transactions' => $transactions]);
        } elseif (FacadesRequest::isMethod('post')) {
            $date_from = date('d/m/Y', strtotime($request->date_from));
            $date_to = date('d/m/Y', strtotime($request->date_to));
            $vol_in_gbp = DB::raw('SUM(IF(payin_ccy="GBP",(payin_amt-admin_charges),(payin_amt/(SELECT currencies_rates.rate FROM currencies INNER JOIN currencies_rates ON currencies.id=currencies_rates.c_id WHERE currencies.currency=payin_ccy AND currencies_rates.status=1)-admin_charges))) AS vol_in_gbp');
            if (!empty($request->date_from) && empty($request->date_to)) {
                $transactions = TransactionsData::select('paid_date', 'customer_country', $vol_in_gbp, 'bank_name', 'payout_ccy', 'amount', 'payment_method')->where(['paid_date', '=', $date_from, 'bank_name', $buyer_name->name])->get();
            } elseif (!empty($request->date_from) && empty($request->date_to)) {
                $transactions = TransactionsData::select('paid_date', 'customer_country', $vol_in_gbp, 'bank_name', 'payout_ccy', 'amount', 'payment_method')->where(['paid_date', '=', $date_from, 'bank_name', $buyer_name->name])->get();
            } elseif (!empty($request->date_from) && !empty($request->date_to)) {
                $transactions = TransactionsData::select('paid_date', 'customer_country', $vol_in_gbp, 'bank_name', 'payout_ccy', 'amount', 'payment_method')->where('bank_name', $buyer_name->name)->whereBetween('paid_date', [$date_from, $date_to])->get();
            }
            return view('admin.buyer.report.index', ['buyer_name' => $buyer_name, 'id' => $id, 'transactions' => $transactions, 'buyers' => $buyers]);
        } else {
            $buyers =  Buyer::get();
            return view('admin.buyer.index', ['buyers' => $buyers]);
        }
    }
}
