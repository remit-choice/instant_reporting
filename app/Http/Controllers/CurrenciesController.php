<?php

namespace App\Http\Controllers;

use App\Models\CurrenciesRate;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
        $rates = function ($query) {
            $query->where('status', 1);
        };
        $currencies =  Currency::where([['min_rate', '!=', NULL], ['max_rate', '!=', NULL]])->orwhereHas('rates', $rates)->with('rates', $rates)->get();
        // dd(Session::get('dated'));
        $request->session()->pull('dated');
        return view('admin.currencies.rates.index', ['currencies' => $currencies]);
    }
    public function rate_create(Request $request)
    {
        $c_id = $request->c_id;
        $min_rate = $request->min_rate;
        $max_rate = $request->max_rate;
        $rate = $request->rate;
        $iso = $request->iso;
        $iso3 = $request->iso3;
        $currency = $request->currency;

        // dd($rate);
        $dated = date('Y-m-d', strtotime($request->date));
        $request->session()->pull('dated');
        $request->session()->put('dated', $request->date);
        if ($rate >= $min_rate && $min_rate != '' &&   $rate <= $max_rate && $max_rate != '') {
            CurrenciesRate::create([
                'dated' => $dated,
                'c_id' => $c_id,
                'iso' => $iso,
                'iso3' => $iso3,
                'currency' => $currency,
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
        $iso = $request->iso;
        $iso3 = $request->iso3;
        $currency = $request->currency;

        $dated = date('Y-m-d',  strtotime($request->date));
        $request->session()->pull('dated');
        $request->session()->put('dated', $request->date);
        if ($rate >= $min_rate && $rate <= $max_rate) {
            $check_old_date = CurrenciesRate::where([['dated', $dated], ['c_id', $c_id]])->count();
            // dd($check_old_date);
            if ($check_old_date <= 0) {
                CurrenciesRate::where('id', $id)->update([
                    'status' => 0
                ]);
                CurrenciesRate::insert([
                    'dated' => $dated,
                    'c_id' => $c_id,
                    'iso' => $iso,
                    'iso3' => $iso3,
                    'currency' => $currency,
                    'rate' => $rate,
                    'status' => 1
                ]);
            } else {
                CurrenciesRate::where('id', $id)->update([
                    'c_id' => $c_id,
                    'rate' => $rate,
                    'status' => 1
                ]);
            }

            return true;
        } else {
            if ($rate < $min_rate) {
                return 2;
            } else {
                return 3;
            }
        }
    }
    public function rate_filter(Request $request)
    {
        $dated = date('Y-m-d', strtotime($request->date));
        // dd($request->date);
        $rates = function ($query) use ($dated) {
            $query->where('dated', $dated);
        };
        $currencies =  Currency::where([['min_rate', '!=', NULL], ['max_rate', '!=', NULL]])->whereHas('rates', $rates)->with('rates', $rates)->get();
        // dd($currencies->toArray());
        if (Session::get('dated') != $dated) {
            $request->session()->pull('dated');
            $request->session()->put('dated', $dated);
        } else {
            $request->session()->put('dated', $dated);
        }
        return view('admin.currencies.rates.index', ['currencies' => $currencies]);
    }
}
