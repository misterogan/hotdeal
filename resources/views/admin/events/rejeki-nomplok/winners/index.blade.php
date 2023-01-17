@extends('layouts.app')
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Rejeki Nomplok
                    <span class="d-block text-muted pt-2 font-size-sm">Coupons </span></h3>
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table id="dt_winners" class="table datatable datatable-bordered datatable-head-custom">
                <thead>
                <tr>
                    <th></th>
                    <th>No.</th>
                    <th>Week</th>
                    <th>User</th>
                    <th>Coupon Number</th>
                    <th>Value Hotpoint</th>
                    <th>Product</th>
                    <th>Created At</th>
                </tr>
                </thead>
            </table>
            <!--end: Datatable-->
        </div>
    </div>
@endsection

@section('js')
    <script>
        var filter = false;
    </script>
    <script src="{{url('/custom/admin/rejeki-nomplok/winners.js')}}" type="application/javascript" ></script>
@endsection
