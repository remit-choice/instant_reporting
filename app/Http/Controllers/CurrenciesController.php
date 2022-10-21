<?php

namespace App\Http\Controllers;

use App\Models\CurrenciesRate;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrenciesController extends Controller
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
        $currencies =  Currency::get();
        return view('admin.currencies.index', ['currencies' => $currencies]);
    }
    public function update(Request $request)
    {
        $id = $request->id;
        $min_rate = $request->min_rate;
        $max_rate = $request->max_rate;
        if ($min_rate < $max_rate) {
            Currency::where('id', $id)->update([
                'min_rate' => $min_rate,
                'max_rate' => $max_rate
            ]);
            return true;
        } else {
            if ($min_rate > $max_rate) {
                return 2;
            } else {
                return 3;
            }
        }
    }
    public function rate_index(Request $request)
    {
        $currencies =  Currency::with('rates')->get();
        // dd($currencies->toArray());
        return view('admin.currencies.rates.index', ['currencies' => $currencies]);
    }
    public function rate_create(Request $request)
    {
        $c_id = $request->c_id;
        $min_rate = $request->min_rate;
        $max_rate = $request->max_rate;
        $rate = $request->rate;
        $dated = date('Y-m-d');
        if ($rate >= $min_rate &&  $rate <= $max_rate) {
            CurrenciesRate::insert([
                'dated' => $dated,
                'c_id' => $c_id,
                'rate' => $rate,
                'status' => 1
            ]);
            return true;
        } else {
            if ($rate < $min_rate) {
                return 2;
            } else {
                return 3;
            }
        }
    }
    public function rate_update(Request $request)
    {
        $id = $request->id;
        $c_id = $request->c_id;
        $min_rate = $request->min_rate;
        $max_rate = $request->max_rate;
        $rate = $request->rate;
        if ($rate >= $min_rate && $rate <= $max_rate) {
            CurrenciesRate::where('id', $id)->update([
                'rate' => $rate,
            ]);
            return true;
        } else {
            if ($rate < $min_rate) {
                return 2;
            } else {
                return 3;
            }
        }
    }
}
