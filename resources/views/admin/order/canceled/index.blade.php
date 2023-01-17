@extends('layouts.app')
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Invoice
                    <span class="d-block text-muted pt-2 font-size-sm">Invoice Data</span></h3>
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table id="dt_orders_canceled" class="table datatable datatable-bordered datatable-head-custom">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>User</th>
                    <th>Invoice number</th>
                    <th>Description</th>
                    <th>Refund method</th>
                    <th>Reject date</th>
                    <th>Refund Date</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{url('/custom/admin/order.js')}}" type="application/javascript" ></script>
@endsection
