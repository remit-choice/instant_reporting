<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\BuyerPaymentMethod;
use App\Models\Currency;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;

class BuyerPaymentMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            (new UserController)->main();
            return $next($request);
        });
    }
    public function index(Request $request)
    {
        if (FacadesRequest::isMethod('get')) {
            $id = $request->id;
            $buyers_rel = function ($query) use ($id) {
                $query->where('b_id', $id);
                $query->where('status', 1);
            };
            $buyers =  Buyer::whereHas('buyer_payment_methods', $buyers_rel)->with('buyer_payment_methods', $buyers_rel)->where('id', $id)->get();
            // dd($buyers->toArray());
            $payment_methods =  PaymentMethod::get();
            $buyer_name =  Buyer::where('id', $id)->select('name')->first();
            $currencies =  Currency::get();
            return view('admin.buyer.pay_method.index', ['buyers' => $buyers, 'payment_methods' => $payment_methods, 'currencies' => $currencies, 'buyer_name' => $buyer_name, 'id' => $id]);
        } else {
            $buyers =  Buyer::get();
            return view('admin.buyer.index', ['buyers' => $buyers]);
        }
    }
    public function create(Request $request)
    {
        // dd($request->payment_methods[1]);
        $request->validate(
            [
                'payment_methods' => 'required|array|max:255',
                'countries' => 'required|array',
                'countries.*' => 'required|string',
                'currencies' => 'required|array|max:255',
                'rates' => 'required|max:255',
            ],
            [
                'payment_methods.required' => "*Payment Method is required",
                'countries.required' => "*Country is required",
                'currencies.required' => "*Currency is required",
                'rates.required' => "*Rate is required",
            ]
        );
        $buyer_id = $request->buyer_id;
        $payment_methods = $request->payment_methods;
        $countries = $request->countries;
        $currencies = $request->currencies;
        $rates = $request->rates;
        $payment_methods_count = count($payment_methods);
        // dd($payment_methods_count);

        for ($i = 0; $i < $payment_methods_count; $i++) {
            BuyerPaymentMethod::create([
                'b_id' => $buyer_id,
                'p_m_id' => $payment_methods[$i],
                'country' => $countries[$i],
                'c_id' => $currencies[$i],
                'rate' => $rates[$i],
                'status' => 1
            ]);
        }
    }
    public function edit(Request $request)
    {
        return $this->update($request);
    }
    public function update($request)
    {
        $request->validate(
            [
                'countries' => 'required|string|max:255',
                'currencies' => 'required|integer|max:255',
                'rates' => 'required|max:255',
            ],
            [
                'countries.required' => "*Country is required",
                'currencies.required' => "*Currency is required",
                'rates.required' => "*Rate is required",
            ]
        );
        $buyer_id = $request->buyer_id;
        $id = $request->id;
        $payment_methods = $request->payment_methods;
        $countries = $request->countries;
        $currencies = $request->currencies;
        $rates = $request->rates;
        BuyerPaymentMethod::where('id', $id)->update([
            'status' => 0
        ]);
        BuyerPaymentMethod::insert([
            'b_id' => $buyer_id,
            'p_m_id' => $payment_methods,
            'country' => $countries,
            'c_id' => $currencies,
            'rate' => $rates,
            'status' => 1
        ]);
    }
    public function delete(Request $request)
    {
        $id = $request->id;
        BuyerPaymentMethod::where('id', $id)->delete();
    }
}
