<?php

namespace App\Http\Controllers;

use App\Models\TransactionsData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request as FacadesRequest;

class TransactionDataController extends Controller
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
            if ($request->has('filter')) {
                $transactions_data =  '1';
                $date_from = '';
                $date_to = '';
                if (!empty($request->date_from) && empty($request->date_to)) {
                    $date_from = strtotime($request->date_from);
                    $date_from = date('d/m/Y', $date_from);
                    $transactions = TransactionsData::where('transaction_date', $date_from)->get();
                    // dd($transactions->toArray());
                } elseif (empty($request->date_from) && !empty($request->date_to)) {
                    $date_to = strtotime($request->date_to);
                    $date_to = date('n/j/Y', $date_to);
                    $transactions = TransactionsData::where('last_transaction_date',  $date_to)->get();
                    // dd($transactions->toArray());
                } elseif (!empty($request->date_from) && !empty($request->date_to)) {
                    $date_from = strtotime($request->date_from);
                    $date_from = date('d/m/Y', $date_from);
                    $date_to = strtotime($request->date_to);
                    $date_to = date('n/j/Y', $date_to);
                    $transactions = TransactionsData::where([['transaction_date', '>=', $date_from], ['last_transaction_date', '<=', "$date_to%"]])->get();
                    // dd($transactions->toArray());
                } else {
                }
                return
                    view('admin.upload_data.transactions.index', ['date_from' => $date_from, 'date_to' => $date_to, 'transactions' => $transactions, 'transactions_data' => $transactions_data]);
            } else {
                $transactions_data =  '';
                return view('admin.upload_data.transactions.index', ['transactions_data' => $transactions_data]);
            }
        } else {
        }
    }
    public function create(Request $request)
    {
        if (FacadesRequest::isMethod('get')) {
            return view('admin.upload_data.transactions.create');
        } elseif (FacadesRequest::isMethod('post')) {
            if ($request->file('transaction_file')) {
                $file = $request->file('transaction_file');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                // dd($filename);
                $allowed =  array("csv", 'xsls');
                // $maxFileSize = 2097152; // Uploaded file size limit is 2mb
                $fileSize = $file->getSize(); //Get size of uploaded file in bytes
                $location = 'assets/dist/img/transaction_file/';
                $filepath = public_path($location . $filename);

                if (in_array($extension, $allowed)) {
                    File::delete(public_path($location . $filename));
                    $file->move($location, $filename);
                    $i = 0; //so we can skip first row
                    // if ($fileSize <= $maxFileSize) {
                    $files = fopen($filepath, "r");
                    while (($emapData = fgetcsv($files, 10000, ",")) !== FALSE) {
                        $emapData = str_replace(",", " ", $emapData);
                        if ($i > 0) {
                            TransactionsData::insert([
                                'transaction_date' => $emapData[0],
                                'transaction_time' => $emapData[1],
                                'agent_id_collect' => $emapData[2],
                                'agent_name_collect' => $emapData[3],
                                'type' => $emapData[4],
                                'agent_id_main' => $emapData[5],
                                'agent_name_main' => $emapData[6],
                                'office_id' => $emapData[7],
                                'tr_no' => $emapData[8],
                                'pin_no' => $emapData[9],
                                'customer_id' => $emapData[10],
                                'customer_full_name' => $emapData[11],
                                'customer_first_name' => $emapData[12],
                                'customer_last_name' => $emapData[13],
                                'house_no' => $emapData[14],
                                'street' => $emapData[15],
                                'city' => $emapData[16],
                                'post_code' => $emapData[17],
                                'customer_state' => $emapData[18],
                                'customer_country' => $emapData[19],
                                'customer_tel' => $emapData[20],
                                'customer_cell' => $emapData[21],
                                'customer_email' => $emapData[22],
                                'customer_mothername' => $emapData[23],
                                'id_type' => $emapData[24],
                                'id_number' => $emapData[25],
                                'customer_id_issue_place' => $emapData[26],
                                'customer_gender' => $emapData[27],
                                'dob' => $emapData[28],
                                'birth_place' => $emapData[29],
                                'profession' => $emapData[30],
                                'agent_id_pay' => $emapData[31],
                                'agent_name_pay' => $emapData[32],
                                'payment_method' => $emapData[33],
                                'beneficiary_country' => $emapData[34],
                                'customer_rate' => $emapData[35],
                                'agent_rate_' => $emapData[36],
                                'payout_ccy' => $emapData[37],
                                'amount' => $emapData[38],
                                'payin_ccy' => $emapData[39],
                                'payin_amt' => $emapData[40],
                                'admin_charges' => $emapData[41],
                                'agent_charges' => $emapData[42],
                                'beneficiary_full_name' => $emapData[43],
                                'beneficiary_first_name' => $emapData[44],
                                'beneficiary_last_name' => $emapData[45],
                                'receiver_address' => $emapData[46],
                                'receiver_city' => $emapData[47],
                                'receiver_phone' => $emapData[48],
                                'receiver_email' => $emapData[49],
                                'receiver_date_of_birth' => $emapData[50],
                                'receiver_place_of_birth' => $emapData[51],
                                'bank_ac_no' => $emapData[52],
                                'bank_name' => $emapData[53],
                                'branch_name' => $emapData[54],
                                'branch_code' => $emapData[55],
                                'purpose_category' => $emapData[56],
                                'purpose_comments' => $emapData[57],
                                'status' => $emapData[58],
                                'exported' => $emapData[59],
                                'main_hold' => $emapData[60],
                                'subadmin_hold' => $emapData[61],
                                'paid_date' => $emapData[62],
                                'paid_time' => $emapData[63],
                                'buyer_rate' => $emapData[64],
                                'subagent_rate' => $emapData[65],
                                'codice_fiscale' => $emapData[66],
                                'beneficiary_cnic' => $emapData[67],
                                'bene_branch_name' => $emapData[68],
                                'bene_branch_code' => $emapData[69],
                                'bene_bank_name' => $emapData[70],
                                'total_transaction' => $emapData[71],
                                'total_amount' => $emapData[72],
                                'relationship' => $emapData[73],
                                'payment_smethod' => $emapData[74],
                                'payment_type' => $emapData[75],
                                'tmt_no' => $emapData[76],
                                'buyer_dc_rate' => $emapData[77],
                                'customer_register_date' => $emapData[78],
                                'customer_id_1' => $emapData[79],
                                'customer_id_2' => $emapData[80],
                                'log_export_date' => $emapData[81],
                                'last_transaction_date' => $emapData[81],
                            ]);
                        }
                        $i++;
                        // dd($emapData);
                    }
                    // } else {
                    //     return redirect()->back()->with(['failed' => "File Size is big"]);
                    // }

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
    // public function filter(Request $request)
    // {
    //     $date_from = '';
    //     $date_to = '';
    //     if (!empty($request->date_from)) {
    //         $date_from = strtotime($request->date_from);
    //         $date_from = date('d/m/Y', $date_from);
    //         $transactions = TransactionsData::where('transaction_date', $date_from)->get();
    //         // dd($transactions->toArray());
    //     } elseif (!empty($request->date_to)) {
    //         $date_to = strtotime($request->date_to);
    //         $date_to = date('m/d/Y', $date_to);
    //         $transactions = TransactionsData::whereDate('last_transaction_date', '=', '9/26/2022')->get();
    //         dd($transactions->toArray());
    //         // dd($date_to);
    //     } elseif (!empty($request->date_from) && !empty($request->date_to)) {
    //         $date_from =  $request->date_from;
    //         $date_to =  $request->date_to;
    //     } else {
    //     }
    //     return redirect()->route('accounts.admin.transactions')->with(['date_from' => $date_from, 'date_to' => $date_to, 'transactions' => $transactions]);
    // }

    // public function create(Request $request)
    // {
    //     if (FacadesRequest::isMethod('get')) {
    //         return view('accounts.admin.transactions.create');
    //     } elseif (FacadesRequest::isMethod('post')) {
    //         // dd($request->toArray());
    //         if ($request->hasfile('transaction_file')) {
    //             $file = $request->file('transaction_file');

    //             // File Details 
    //             $filename = $file->getClientOriginalName();
    //             $extension = $file->getClientOriginalExtension();
    //             $tempPath = $file->getRealPath();
    //             $image_full_name = $filename . '.' . $extension;
    //             $upload_path = 'assets/dist/img/transaction_file/';
    //             $image_url = $upload_path . $image_full_name;
    //             $response = $file->move($upload_path, $image_full_name);

    //             // Valid File Extensions
    //             $valid_extension = array("csv");

    //             // Check file extension
    //             if (in_array(strtolower($extension), $valid_extension)) {
    //                 $handle = fopen($image_url, 'r');
    //                 $i = 0;
    //                 while (($emapData = fgetcsv($handle, 4096)) !== FALSE) {
    //                     $emapData = str_replace(",", " ", $emapData);
    //                     $unique_transaction =  TransactionsData::where([['pin_no', $emapData[9]], ['status', $emapData[58]]])->count();

    //                     if ($i > 0) {
    //                         if ($unique_transaction == 0) {
    //                             TransactionsData::insert([
    //                                 'transaction_date' => $emapData[0],
    //                                 'transaction_time' => $emapData[1],
    //                                 'agent_id_collect' => $emapData[2],
    //                                 'agent_name_collect' => $emapData[3],
    //                                 'type' => $emapData[4],
    //                                 'agent_id_main' => $emapData[5],
    //                                 'agent_name_main' => $emapData[6],
    //                                 'office_id' => $emapData[7],
    //                                 'tr_no' => $emapData[8],
    //                                 'pin_no' => $emapData[9],
    //                                 'customer_id' => $emapData[10],
    //                                 'customer_full_name' => $emapData[11],
    //                                 'customer_first_name' => $emapData[12],
    //                                 'customer_last_name' => $emapData[13],
    //                                 'house_no' => $emapData[14],
    //                                 'street' => $emapData[15],
    //                                 'city' => $emapData[16],
    //                                 'post_code' => $emapData[17],
    //                                 'customer_state' => $emapData[18],
    //                                 'customer_country' => $emapData[19],
    //                                 'customer_tel' => $emapData[20],
    //                                 'customer_cell' => $emapData[21],
    //                                 'customer_email' => $emapData[22],
    //                                 'customer_mothername' => $emapData[23],
    //                                 'id_type' => $emapData[24],
    //                                 'id_number' => $emapData[25],
    //                                 'customer_id_issue_place' => $emapData[26],
    //                                 'customer_gender' => $emapData[27],
    //                                 'dob' => $emapData[28],
    //                                 'birth_place' => $emapData[29],
    //                                 'profession' => $emapData[30],
    //                                 'agent_id_pay' => $emapData[31],
    //                                 'agent_name_pay' => $emapData[32],
    //                                 'payment_method' => $emapData[33],
    //                                 'beneficiary_country' => $emapData[34],
    //                                 'customer_rate' => $emapData[35],
    //                                 'agent_rate_' => $emapData[36],
    //                                 'payout_ccy' => $emapData[37],
    //                                 'amount' => $emapData[38],
    //                                 'payin_ccy' => $emapData[39],
    //                                 'payin_amt' => $emapData[40],
    //                                 'admin_charges' => $emapData[41],
    //                                 'agent_charges' => $emapData[42],
    //                                 'beneficiary_full_name' => $emapData[43],
    //                                 'beneficiary_first_name' => $emapData[44],
    //                                 'beneficiary_last_name' => $emapData[45],
    //                                 'receiver_address' => $emapData[46],
    //                                 'receiver_city' => $emapData[47],
    //                                 'receiver_phone' => $emapData[48],
    //                                 'receiver_email' => $emapData[49],
    //                                 'receiver_date_of_birth' => $emapData[50],
    //                                 'receiver_place_of_birth' => $emapData[51],
    //                                 'bank_ac_#' => $emapData[52],
    //                                 'bank_name' => $emapData[53],
    //                                 'branch_name' => $emapData[54],
    //                                 'branch_code' => $emapData[55],
    //                                 'purpose_category' => $emapData[56],
    //                                 'purpose_comments' => $emapData[57],
    //                                 'status' => $emapData[58],
    //                                 'exported' => $emapData[59],
    //                                 'main_hold' => $emapData[60],
    //                                 'subadmin_hold' => $emapData[61],
    //                                 'paid_date' => $emapData[62],
    //                                 'paid_time' => $emapData[63],
    //                                 'buyer_rate' => $emapData[64],
    //                                 'subagent_rate' => $emapData[65],
    //                                 'codice_fiscale' => $emapData[66],
    //                                 'beneficiary_cnic' => $emapData[67],
    //                                 'bene_branch_name' => $emapData[68],
    //                                 'bene_branch_code' => $emapData[69],
    //                                 'bene_bank_name' => $emapData[70],
    //                                 'total_transaction' => $emapData[71],
    //                                 'total_amount' => $emapData[72],
    //                                 'relationship' => $emapData[73],
    //                                 'payment_smethod' => $emapData[74],
    //                                 'payment_type' => $emapData[75],
    //                                 'tmt_no' => $emapData[76],
    //                                 'buyer_dc_rate' => $emapData[77],
    //                                 'customer_register_date' => $emapData[78],
    //                                 'customer_id_1' => $emapData[79],
    //                                 'customer_id_2' => $emapData[80],
    //                                 'log_export_date' => $emapData[81],
    //                                 'last_transaction_date' => $emapData[81],
    //                             ]);
    //                         }
    //                     }
    //                     $i++;
    //                 }
    //                 fclose($handle);
    //                 return redirect()->route('accounts.admin.transactions.create')->with('success', 'Your files has been successfully Uploaded');
    //             } else {
    //                 return redirect()->route('accounts.admin.transactions.create')->with('failed', 'Only CSV File Extension Alllowed');
    //             }
    //         }
    //     } else {
    //     }
    // }
}
