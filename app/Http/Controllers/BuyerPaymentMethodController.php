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
                'currencies' => 'required|array|max:255',
                'rates' => 'required|array|max:255',
            ],
            [
                'payment_methods.required' => "*Payment Method is required",
                'currencies.required' => "*Currency is required",
                'rates.required' => "*Rate is required",
            ]
        );
        $buyer_id = $request->buyer_id;
        $payment_methods = $request->payment_methods;
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
                'name' => 'required|string|max:255',
                'status' => 'required|string|max:255',
            ],
            [
                'name.required' => "*Name is required",
                'status.required' => "*Status is required",
            ]
        );
        $id = $request->id;
        $name = $request->name;
        $status = $request->status;

        Buyer::where('id', $id)->update([
            'name' => $name,
            'status' => $status
        ]);
    }
    public function delete(Request $request)
    {
        $id = $request->id;
        Buyer::where('id', $id)->delete();
    }
}
