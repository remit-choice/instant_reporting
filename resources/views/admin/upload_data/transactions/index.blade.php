@extends('layouts.admin.master')
@section('content')
@section('links_content_head')
    @Include('layouts.links.datatable.head')
@endsection
@section('content_1')
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 mt-2">
        <!-- Button trigger modal -->
        @if ($create == 1)
            <a href="{{ route('admin.upload_data.transactions.create') }}" class="border px-2 btn"
                style="background-color: #091E3E;color: white">
                Upload Transaction Data
            </a>
        @endif
    </div>
@endsection
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        @if (session('failed'))
            <div id="failed" class="alert alert-default-danger alert-dismissible fade show" role="alert">
                <strong>{{ session('failed') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header row">
                        <h3 class="card-title col-lg-6 col-md-6 col-sm-6 col-xs-6">Transactions List</h3>
                    </div>
                    <div class="card-header container-fluid">
                        <form action="{{ route('admin.upload_data.transactions.index') }}" method="post">
                            @csrf
                            <div class="row d-flex justify-content-center">
                                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                    <label>From Date</label>
                                    @if (request()->input('date_from'))
                                        <input type="date" name="date_from" id="" class="form-control"
                                            value="{{ request()->input('date_from', old('date_from')) }}"
                                            style="width: 100%">
                                    @else
                                        <input type="date" name="date_from" id="" class="form-control"
                                            value="" style="width: 100%">
                                    @endif
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                    <label>To Date</label>
                                    @if (request()->input('date_to'))
                                        <input type="date" name="date_to" id="" class="form-control"
                                            value="{{ request()->input('date_to', old('date_to')) }}">
                                    @else
                                        <input type="date" name="date_to" id="" class="form-control"
                                            value="">
                                    @endif
                                </div>
                                <div class="form-group col-lg-1 col-md-1 col-sm-3 col-xs-3">
                                    <label class="invisible">Filter</label>
                                    <button type="submit" name="filter" class="btn form-control"
                                        style="background-color: #091E3E;color: white">Submit</button>
                                </div>
                            </div>

                        </form>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1"
                            class="table table-bordered @php if(request()->input('date_from') || request()->input('date_to')){echo "
                                    table-responsive";}else{} @endphp">
                            <thead>
                                <tr>
                                    <th class="text-capitalize">#</th>
                                    <th class="text-capitalize">transaction_date</th>
                                    <th class="text-capitalize">transaction_time</th>
                                    <th class="text-capitalize">agent_id_collect</th>
                                    <th class="text-capitalize">agent_name_collect</th>
                                    <th class="text-capitalize">type</th>
                                    <th class="text-capitalize">agent_id_main</th>
                                    <th class="text-capitalize">agent_name_main</th>
                                    <th class="text-capitalize">office_id</th>
                                    <th class="text-capitalize">tr_no</th>
                                    <th class="text-capitalize">pin_no</th>
                                    <th class="text-capitalize">customer_id</th>
                                    <th class="text-capitalize">customer_full_name</th>
                                    <th class="text-capitalize">customer_first_name</th>
                                    <th class="text-capitalize">customer_last_name</th>
                                    <th class="text-capitalize">house_no</th>
                                    <th class="text-capitalize">street</th>
                                    <th class="text-capitalize">city</th>
                                    <th class="text-capitalize">post_code</th>
                                    <th class="text-capitalize">customer_state</th>
                                    <th class="text-capitalize">customer_country</th>
                                    <th class="text-capitalize">customer_tel</th>
                                    <th class="text-capitalize">customer_cell</th>
                                    <th class="text-capitalize">customer_email</th>
                                    <th class="text-capitalize">customer_mothername</th>
                                    <th class="text-capitalize">id_type</th>
                                    <th class="text-capitalize">id_number</th>
                                    <th class="text-capitalize">customer_id_issue_place</th>
                                    <th class="text-capitalize">customer_gender</th>
                                    <th class="text-capitalize">dob</th>
                                    <th class="text-capitalize">birth_place</th>
                                    <th class="text-capitalize">profession</th>
                                    <th class="text-capitalize">agent_id_pay</th>
                                    <th class="text-capitalize">agent_name_pay</th>
                                    <th class="text-capitalize">payment_method</th>
                                    <th class="text-capitalize">beneficiary_country</th>
                                    <th class="text-capitalize">customer_rate</th>
                                    <th class="text-capitalize">agent_rate</th>
                                    <th class="text-capitalize">payout_ccy</th>
                                    <th class="text-capitalize">amount</th>
                                    <th class="text-capitalize">payin_ccy</th>
                                    <th class="text-capitalize">payin_amt</th>
                                    <th class="text-capitalize">admin_charges</th>
                                    <th class="text-capitalize">agent_charges</th>
                                    <th class="text-capitalize">beneficiary_full_name</th>
                                    <th class="text-capitalize">beneficiary_first_name</th>
                                    <th class="text-capitalize">beneficiary_last_name</th>
                                    <th class="text-capitalize">receiver_address</th>
                                    <th class="text-capitalize">receiver_city</th>
                                    <th class="text-capitalize">receiver_phone</th>
                                    <th class="text-capitalize">receiver_email</th>
                                    <th class="text-capitalize">receiver_date_of_birth</th>
                                    <th class="text-capitalize">receiver_place_of_birth</th>
                                    <th class="text-capitalize">bank_ac_#</th>
                                    <th class="text-capitalize">bank_name</th>
                                    <th class="text-capitalize">branch_name</th>
                                    <th class="text-capitalize">branch_code</th>
                                    <th class="text-capitalize">purpose_category</th>
                                    <th class="text-capitalize">purpose_comments</th>
                                    <th class="text-capitalize">status</th>
                                    <th class="text-capitalize">exported</th>
                                    <th class="text-capitalize">main_hold</th>
                                    <th class="text-capitalize">subadmin_hold</th>
                                    <th class="text-capitalize">paid_date</th>
                                    <th class="text-capitalize">paid_time</th>
                                    <th class="text-capitalize">buyer_rate</th>
                                    <th class="text-capitalize">subagent_rate</th>
                                    <th class="text-capitalize">codice_fiscale</th>
                                    <th class="text-capitalize">beneficiary_cnic</th>
                                    <th class="text-capitalize">bene_branch_name</th>
                                    <th class="text-capitalize">bene_branch_code</th>
                                    <th class="text-capitalize">bene_bank_name</th>
                                    <th class="text-capitalize">total_transaction</th>
                                    <th class="text-capitalize">total_amount</th>
                                    <th class="text-capitalize">relationship</th>
                                    <th class="text-capitalize">payment_smethod</th>
                                    <th class="text-capitalize">payment_type</th>
                                    <th class="text-capitalize">tmt_no</th>
                                    <th class="text-capitalize">buyer_dc_rate</th>
                                    <th class="text-capitalize">customer_register_date</th>
                                    <th class="text-capitalize">customer_id_1</th>
                                    <th class="text-capitalize">customer_id_2</th>
                                    <th class="text-capitalize">log_export_date</th>
                                    <th class="text-capitalize">last_transaction_date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ((!empty($transactions) && request()->input('date_from')) || request()->input('date_to'))
                                    @php $counter = 1; @endphp
                                    @foreach ($transactions as $transaction)
                                        <tr>
                                            <td>{{ $counter++ }}</td>
                                            <td>{{ $transaction->transaction_date }}</td>
                                            <td>{{ $transaction->transaction_time }}</td>
                                            <td>{{ $transaction->agent_id_collect }}</td>
                                            <td>{{ $transaction->agent_name_collect }}</td>
                                            <td>{{ $transaction->type }}</td>
                                            <td>{{ $transaction->agent_id_main }}</td>
                                            <td>{{ $transaction->agent_name_main }}</td>
                                            <td>{{ $transaction->office_id }}</td>
                                            <td>{{ $transaction->tr_no }}</td>
                                            <td>{{ $transaction->pin_no }}</td>
                                            <td>{{ $transaction->customer_id }}</td>
                                            <td>{{ $transaction->customer_full_name }}</td>
                                            <td>{{ $transaction->customer_first_name }}</td>
                                            <td>{{ $transaction->customer_last_name }}</td>
                                            <td>{{ $transaction->house_no }}</td>
                                            <td>{{ $transaction->street }}</td>
                                            <td>{{ $transaction->city }}</td>
                                            <td>{{ $transaction->post_code }}</td>
                                            <td>{{ $transaction->customer_state }}</td>
                                            <td>{{ $transaction->customer_country }}</td>
                                            <td>{{ $transaction->customer_tel }}</td>
                                            <td>{{ $transaction->customer_cell }}</td>
                                            <td>{{ $transaction->customer_email }}</td>
                                            <td>{{ $transaction->customer_motdername }}</td>
                                            <td>{{ $transaction->id_type }}</td>
                                            <td>{{ $transaction->id_number }}</td>
                                            <td>{{ $transaction->customer_id_issue_place }}</td>
                                            <td>{{ $transaction->customer_gender }}</td>
                                            <td>{{ $transaction->dob }}</td>
                                            <td>{{ $transaction->birtd_place }}</td>
                                            <td>{{ $transaction->profession }}</td>
                                            <td>{{ $transaction->agent_id_pay }}</td>
                                            <td>{{ $transaction->agent_name_pay }}</td>
                                            <td>{{ $transaction->payment_metdod }}</td>
                                            <td>{{ $transaction->beneficiary_country }}</td>
                                            <td>{{ $transaction->customer_rate }}</td>
                                            <td>{{ $transaction->agent_rate }}</td>
                                            <td>{{ $transaction->payout_ccy }}</td>
                                            <td>{{ $transaction->amount }}</td>
                                            <td>{{ $transaction->payin_ccy }}</td>
                                            <td>{{ $transaction->payin_amt }}</td>
                                            <td>{{ $transaction->admin_charges }}</td>
                                            <td>{{ $transaction->agent_charges }}</td>
                                            <td>{{ $transaction->beneficiary_full_name }}</td>
                                            <td>{{ $transaction->beneficiary_first_name }}</td>
                                            <td>{{ $transaction->beneficiary_last_name }}</td>
                                            <td>{{ $transaction->receiver_address }}</td>
                                            <td>{{ $transaction->receiver_city }}</td>
                                            <td>{{ $transaction->receiver_phone }}</td>
                                            <td>{{ $transaction->receiver_email }}</td>
                                            <td>{{ $transaction->receiver_date_of_birtd }}</td>
                                            <td>{{ $transaction->receiver_place_of_birtd }}</td>
                                            <td>{{ $transaction->bank_ac_no }}</td>
                                            <td>{{ $transaction->bank_name }}</td>
                                            <td>{{ $transaction->branch_name }}</td>
                                            <td>{{ $transaction->branch_code }}</td>
                                            <td>{{ $transaction->purpose_category }}</td>
                                            <td>{{ $transaction->purpose_comments }}</td>
                                            <td>{{ $transaction->status }}</td>
                                            <td>{{ $transaction->exported }}</td>
                                            <td>{{ $transaction->main_hold }}</td>
                                            <td>{{ $transaction->subadmin_hold }}</td>
                                            <td>{{ $transaction->paid_date }}</td>
                                            <td>{{ $transaction->paid_time }}</td>
                                            <td>{{ $transaction->buyer_rate }}</td>
                                            <td>{{ $transaction->subagent_rate }}</td>
                                            <td>{{ $transaction->codice_fiscale }}</td>
                                            <td>{{ $transaction->beneficiary_cnic }}</td>
                                            <td>{{ $transaction->bene_branch_name }}</td>
                                            <td>{{ $transaction->bene_branch_code }}</td>
                                            <td>{{ $transaction->bene_bank_name }}</td>
                                            <td>{{ $transaction->total_transaction }}</td>
                                            <td>{{ $transaction->total_amount }}</td>
                                            <td>{{ $transaction->relationship }}</td>
                                            <td>{{ $transaction->payment_smetdod }}</td>
                                            <td>{{ $transaction->payment_type }}</td>
                                            <td>{{ $transaction->tmt_no }}</td>
                                            <td>{{ $transaction->buyer_dc_rate }}</td>
                                            <td>{{ $transaction->customer_register_date }}</td>
                                            <td>{{ $transaction->customer_id_1 }}</td>
                                            <td>{{ $transaction->customer_id_2 }}</td>
                                            <td>{{ $transaction->log_export_date }}</td>
                                            <td>{{ $transaction->last_transaction_date }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-capitalize">#</th>
                                    <th class="text-capitalize">transaction_date</th>
                                    <th class="text-capitalize">transaction_time</th>
                                    <th class="text-capitalize">agent_id_collect</th>
                                    <th class="text-capitalize">agent_name_collect</th>
                                    <th class="text-capitalize">type</th>
                                    <th class="text-capitalize">agent_id_main</th>
                                    <th class="text-capitalize">agent_name_main</th>
                                    <th class="text-capitalize">office_id</th>
                                    <th class="text-capitalize">tr_no</th>
                                    <th class="text-capitalize">pin_no</th>
                                    <th class="text-capitalize">customer_id</th>
                                    <th class="text-capitalize">customer_full_name</th>
                                    <th class="text-capitalize">customer_first_name</th>
                                    <th class="text-capitalize">customer_last_name</th>
                                    <th class="text-capitalize">house_no</th>
                                    <th class="text-capitalize">street</th>
                                    <th class="text-capitalize">city</th>
                                    <th class="text-capitalize">post_code</th>
                                    <th class="text-capitalize">customer_state</th>
                                    <th class="text-capitalize">customer_country</th>
                                    <th class="text-capitalize">customer_tel</th>
                                    <th class="text-capitalize">customer_cell</th>
                                    <th class="text-capitalize">customer_email</th>
                                    <th class="text-capitalize">customer_mothername</th>
                                    <th class="text-capitalize">id_type</th>
                                    <th class="text-capitalize">id_number</th>
                                    <th class="text-capitalize">customer_id_issue_place</th>
                                    <th class="text-capitalize">customer_gender</th>
                                    <th class="text-capitalize">dob</th>
                                    <th class="text-capitalize">birth_place</th>
                                    <th class="text-capitalize">profession</th>
                                    <th class="text-capitalize">agent_id_pay</th>
                                    <th class="text-capitalize">agent_name_pay</th>
                                    <th class="text-capitalize">payment_method</th>
                                    <th class="text-capitalize">beneficiary_country</th>
                                    <th class="text-capitalize">customer_rate</th>
                                    <th class="text-capitalize">agent_rate</th>
                                    <th class="text-capitalize">payout_ccy</th>
                                    <th class="text-capitalize">amount</th>
                                    <th class="text-capitalize">payin_ccy</th>
                                    <th class="text-capitalize">payin_amt</th>
                                    <th class="text-capitalize">admin_charges</th>
                                    <th class="text-capitalize">agent_charges</th>
                                    <th class="text-capitalize">beneficiary_full_name</th>
                                    <th class="text-capitalize">beneficiary_first_name</th>
                                    <th class="text-capitalize">beneficiary_last_name</th>
                                    <th class="text-capitalize">receiver_address</th>
                                    <th class="text-capitalize">receiver_city</th>
                                    <th class="text-capitalize">receiver_phone</th>
                                    <th class="text-capitalize">receiver_email</th>
                                    <th class="text-capitalize">receiver_date_of_birth</th>
                                    <th class="text-capitalize">receiver_place_of_birth</th>
                                    <th class="text-capitalize">bank_ac_#</th>
                                    <th class="text-capitalize">bank_name</th>
                                    <th class="text-capitalize">branch_name</th>
                                    <th class="text-capitalize">branch_code</th>
                                    <th class="text-capitalize">purpose_category</th>
                                    <th class="text-capitalize">purpose_comments</th>
                                    <th class="text-capitalize">status</th>
                                    <th class="text-capitalize">exported</th>
                                    <th class="text-capitalize">main_hold</th>
                                    <th class="text-capitalize">subadmin_hold</th>
                                    <th class="text-capitalize">paid_date</th>
                                    <th class="text-capitalize">paid_time</th>
                                    <th class="text-capitalize">buyer_rate</th>
                                    <th class="text-capitalize">subagent_rate</th>
                                    <th class="text-capitalize">codice_fiscale</th>
                                    <th class="text-capitalize">beneficiary_cnic</th>
                                    <th class="text-capitalize">bene_branch_name</th>
                                    <th class="text-capitalize">bene_branch_code</th>
                                    <th class="text-capitalize">bene_bank_name</th>
                                    <th class="text-capitalize">total_transaction</th>
                                    <th class="text-capitalize">total_amount</th>
                                    <th class="text-capitalize">relationship</th>
                                    <th class="text-capitalize">payment_smethod</th>
                                    <th class="text-capitalize">payment_type</th>
                                    <th class="text-capitalize">tmt_no</th>
                                    <th class="text-capitalize">buyer_dc_rate</th>
                                    <th class="text-capitalize">customer_register_date</th>
                                    <th class="text-capitalize">customer_id_1</th>
                                    <th class="text-capitalize">customer_id_2</th>
                                    <th class="text-capitalize">log_export_date</th>
                                    <th class="text-capitalize">last_transaction_date</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@section('links_content_foot')
    @Include('layouts.links.datatable.foot')
@endsection
@endsection
