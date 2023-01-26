<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Currency;
use Illuminate\Http\Request;
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
                'status' => 'required|string|max:255',
            ],
            [
                'name.required' => "*Name is required",
                'status.required' => "*Status is required",
            ]
        );
        $name = $request->name;
        $status = $request->status;

        Buyer::insert([
            'name' => $name,
            'status' => $status
        ]);
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
