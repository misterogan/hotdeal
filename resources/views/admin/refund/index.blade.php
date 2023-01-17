@extends('layouts.app')
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Refunds
                    <span class="d-block text-muted pt-2 font-size-sm">User Refunds</span></h3>
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table id="dt_refund" class="table datatable datatable-bordered datatable-head-custom">
                <thead>
                <tr>
                    <th></th>
                    <th>No.</th>
                    <th>User</th>
                    <th>Status</th>
                    <th>Type</th>
                    <th>Order Details</th>
                    <th>Description</th>
                    <th>Image 1</th>
                    <th>Image 2</th>
                    <th>Image 3</th>
                    <th>Video</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
                </thead>
            </table>
            <!--end: Datatable-->
        </div>
    </div>
@endsection

@section('js')
    <script src="{{url('/custom/admin/refund.js')}}" type="application/javascript" ></script>
@endsection
