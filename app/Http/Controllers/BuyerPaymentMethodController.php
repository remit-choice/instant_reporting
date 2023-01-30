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
            app(UserController::class)->main();
            return $next($request);
        });
    }
    public function index(Request $request)
    {
        if (FacadesRequest::isMethod('get')) {
            $id = $request->id;
            $buyers =  Buyer::with('buyer_payment_methods')->where('id', $id)->get();
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
        if (isset($request->status)) {
            $status = 1;
        } else {
            $status = 0;
        }
        $payment_methods_count = count($payment_methods);
        // dd($payment_methods_count);

        for ($i = 0; $i < $payment_methods_count; $i++) {
            BuyerPaymentMethod::insert([
                'b_id' => $buyer_id,
                'p_m_id' => $payment_methods[$i],
                'country' => $countries[$i],
                'c_id' => $currencies[$i],
                'rate' => $rates[$i],
                'status' => $status
            ]);
        }
    }
    public function update(Request $request)
    {
        $request->validate(
            [
                'payment_methods' => 'required|integer|max:255',
                'countries' => 'required|string|max:255',
                'currencies' => 'required|integer|max:255',
                'rates' => 'required|max:255',
            ],
            [
                'payment_methods.required' => "*Payment Method is required",
                'countries.required' => "*Country is required",
                'currencies.required' => "*Currency is required",
                'rates.required' => "*Rate is required",
            ]
        );
        $id = $request->id;
        $buyer_id = $request->buyer_id;
        $payment_methods = $request->payment_methods;
        $countries = $request->countries;
        $currencies = $request->currencies;
        $rates = $request->rates;
        if (isset($request->status)) {
            $status = 1;
        } else {
            $status = 0;
        }
        BuyerPaymentMethod::where('id', $id)->update([
            'b_id' => $buyer_id,
            'p_m_id' => $payment_methods,
            'country' => $countries,
            'c_id' => $currencies,
            'rate' => $rates,
            'status' => $status
        ]);
    }
    public function delete(Request $request)
    {
        $id = $request->id;
        BuyerPaymentMethod::where('id', $id)->delete();
    }
}
