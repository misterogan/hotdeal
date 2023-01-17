@extends('layouts.app')
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">User
                    <span class="d-block text-muted pt-2 font-size-sm">User Data</span></h3>
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table id="dt_users" class="table datatable datatable-bordered datatable-head-custom">
                <thead>
                <tr>
                    <th></th>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Source</th>
                    <th>Email Verified</th>
                    <th>Image</th>
                    <th>is Vendor</th>
                    <th>Status</th>
                    <th>Register At</th>
                    <th>Invited Users</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{url('/custom/admin/user.js')}}" type="application/javascript" ></script>
@endsection
