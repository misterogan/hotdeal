@extends('layouts.app')
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Transactions
                    <span class="d-block text-muted pt-2 font-size-sm">Transaction Data</span></h3>
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table id="dt_transactions" class="table datatable datatable-bordered datatable-head-custom">
                <thead>
                <tr>
                    <th></th>
                    <th>No.</th>
                    <th>Transaction Number</th>
                    <th>User</th>
                    <th>Total Payment</th>
                    <th>Total Discount</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{url('/custom/admin/transaction.js')}}" type="application/javascript" ></script>
@endsection
