<?php

namespace App\Http\Controllers\accounts\receiving;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Request as FacadesRequest;

class ProfitLossController extends Controller
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
            return view('accounts.transactions.receiving.profit_loss.index');
        } elseif (FacadesRequest::isMethod('post')) {
            return $this->filter($request);
        } else {
        }
    }
}
