<?php

namespace App\Http\Controllers;

use App\Models\OnlineCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request as FacadesRequest;

class OnlineCustomersController extends Controller
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
            $online_customers =  OnlineCustomer::get();
            return view('admin.upload_data.online_customers.index', ['online_customers' => $online_customers]);
        } else {
        }
    }
    public function filter(Request $request)
    {
        if (FacadesRequest::isMethod('post')) {
            $date_from = '';
            $date_to = '';
            if (!empty($request->date_from) && empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $online_customers = OnlineCustomer::where('register_date', $date_from)->get();
                return
                    view('admin.upload_data.online_customers.index', ['online_customers' => $online_customers]);
            } elseif (!empty($request->date_from) && !empty($request->date_to)) {
                $date_from = date('d/m/Y', strtotime($request->date_from));
                $date_to = date('d/m/Y',  strtotime($request->date_to));
<<<<<<< HEAD
                $online_customers = OnlineCustomer::whereBetween('register_date', [$date_from, $date_to])->get();
=======
                $online_customers = OnlineCustomer::whereBetween('register_date', [$date_from, $date_to])->orwhere('register_date', '=', $date_from)->orwhere('register_date', '<=', $date_to)->get();
>>>>>>> 516d6c5567d80e2b5a0647025168fd1c6425198c
                return
                    view('admin.upload_data.online_customers.index', ['online_customers' => $online_customers]);
            } else {
                if (empty($request->date_from) && !empty($request->date_to)) {
                    return redirect()->back()->with('failed', "From Date Mandatory");
                } else {
                    return redirect()->back()->with('failed', "Dates Fields Required");
                }
            }
        }
    }
    public function create(Request $request)
    {
        if (FacadesRequest::isMethod('get')) {
            return view('admin.upload_data.online_customers.create');
        } elseif (FacadesRequest::isMethod('post')) {
            if ($request->file('online_customer_file')) {
                $file = $request->file('online_customer_file');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $allowed =  array("csv", 'xsls');
                $location = 'assets/dist/img/online_customer_file/';
                $filepath = public_path($location . $filename);
                // dd($filename);
                if (in_array($extension, $allowed)) {
                    File::delete(public_path($location . $filename));
                    $file->move($location, $filename);
                    $i = 0;
                    $files = fopen($filepath, "r");
                    while (($emapData = fgetcsv($files, 10000, ",")) !== FALSE) {
                        $emapData = str_replace(",", " ", $emapData);
                        if ($i > 0) {
                            OnlineCustomer::insert([
                                'customer_id' => $emapData[0],
                                'customer_name' => $emapData[1],
                                'full_address_with_postcode' => $emapData[2],
                                'dob' => $emapData[3],
                                'phone' => $emapData[4],
                                'email' => $emapData[5],
                                'country' => $emapData[6],
                                'main_agent' => $emapData[7],
                                'registerd_by' => $emapData[8],
                                'register_date' => $emapData[9],
                                'volume' => $emapData[10],
                                'number_of_transaction' => $emapData[11],
                                'last_transaction_date' => $emapData[12],
                                'sales_code' => $emapData[13],
                                'state' => $emapData[14],
                                'preferred_country' => $emapData[15]
                            ]);
                        }
                        $i++;
                    }
                    fclose($files);
                    return back()->with('success', 'Your files has been successfully Uploaded');
                } else {
                    return back()->with('failed', 'Only CSV File Extension Alllowed');
                }
            } else {
                return back()->with('failed', "No File Selected");
            }
        } else {
        }
    }
}
