@extends('layouts.app')
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Hotdeal Invite
                    <span class="d-block text-muted pt-2 font-size-sm">Data Invite User</span></h3>
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table id="dt_invited_user" class="table datatable datatable-bordered datatable-head-custom">
                <thead>
                <tr>
                    <th></th>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Registered At</th>
                </tr>
                </thead>
            </table>
            <!--end: Datatable-->
        </div>
    </div>
@endsection

@section('js')
    <script src="{{url('/custom/admin/invite.js')}}" type="application/javascript" ></script>
@endsection
