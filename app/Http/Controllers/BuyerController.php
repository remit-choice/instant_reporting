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
            (new UserController)->main();
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
        Buyer::create([
            'name' => $name,
            'type' => $type,
            'status' => $status
        ]);
    }
    public function edit(Request $request)
    {
        return $this->update($request);
    }
    public function update($request)
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
        $buyers_rel = function ($query) {
            $query->where('status', 1);
        };
        $buyer_charges =  Buyer::whereHas('buyer_payment_methods', $buyers_rel)->with('buyer_payment_methods', $buyers_rel)->where('id', $id)->get();
        // dd($buyer_name->name);
        // dd($buyer_charges->toArray());
        if (FacadesRequest::isMethod('get')) {
            $transactions = '';
            return view('admin.buyer.report.index', ['buyer_name' => $buyer_name, 'id' => $id, 'transactions' => $transactions]);
        } elseif (FacadesRequest::isMethod('post')) {
            $date_from = date('d/m/Y', strtotime($request->date_from));
            $date_to = date('d/m/Y', strtotime($request->date_to));
            // dd($date_from);
            // dd($date_to);
            if (!empty($request->date_from) && empty($request->date_to)) {
                $transactions = TransactionsData::select('paid_date', 'customer_country', 'payment_method', 'payin_ccy', 'payin_amt', 'buyer_name', 'payout_ccy', 'amount', 'admin_charges', 'payment_method')->where([['paid_date', $date_from], ['buyer_name', $buyer_name->name]])->get();
                // dd($transactions->toArray());
            } elseif (empty($request->date_from) && !empty($request->date_to)) {
                $transactions = TransactionsData::select('paid_date', 'customer_country', 'payment_method', 'payin_ccy', 'payin_amt', 'buyer_name', 'payout_ccy', 'amount', 'admin_charges', 'payment_method')->where([['paid_date', $date_to], ['buyer_name', $buyer_name->name]])->get();
                // dd($transactions->toArray());
            } elseif (!empty($request->date_from) && !empty($request->date_to)) {
                $transactions = TransactionsData::select('paid_date', 'customer_country', 'payment_method', 'payin_ccy', 'payin_amt', 'buyer_name', 'payout_ccy', 'amount', 'admin_charges', 'payment_method')->where('buyer_name', $buyer_name->name)->whereBetween('paid_date', [$date_from, $date_to])->get();
                // dd($transactions->toArray());
                // dd($transactions);
            } else {
                $transactions = '';
            }
            return view('admin.buyer.report.index', ['buyer_name' => $buyer_name, 'id' => $id, 'transactions' => $transactions, 'buyers' => $buyers, 'buyer_charges' => $buyer_charges]);
        } else {
            $buyers =  Buyer::get();
            return view('admin.buyer.index', ['buyers' => $buyers]);
        }
    }
}
