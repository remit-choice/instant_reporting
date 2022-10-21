<?php

namespace App\Http\Controllers;

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
        Currency::where('id', $id)->update([
            'min_rate' => $min_rate,
            'max_rate' => $max_rate
        ]);
        return true;
        // $currencies =  Currency::get();
        // return view('accounts.admin.currencies.index', ['currencies' => $currencies]);
    }
}
