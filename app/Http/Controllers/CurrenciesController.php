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
            (new UserController)->main();
            return $next($request);
        });
    }
    public function index(Request $request)
    {
        $currencies =  Currency::get();
        return view('admin.currencies.index', ['currencies' => $currencies]);
    }
    public function edit(Request $request)
    {
        return $this->update($request);
    }
    public function update($request)
    {
        $request->validate(
            [
                'min_rate' => 'required|integer',
                'max_rate' => 'required|integer',
            ],
            [
                'min_rate.required' => '*required',
                'max_rate.required' => '*required',
            ]
        );
        $id = $request->id;
        $min_rate = $request->min_rate;
        $max_rate = $request->max_rate;
        if ($min_rate < $max_rate) {
            Currency::where('id', $id)->update([
                'min_rate' => $min_rate,
                'max_rate' => $max_rate
            ]);
            return true;
        }
    }
}
