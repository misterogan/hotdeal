@extends('layouts.app')
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Rejeki Nomplok
                    <span class="d-block text-muted pt-2 font-size-sm">Master / Week Strengths</span></h3>
            </div>
            <div class="card-toolbar">
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table id="dt_master" class="table datatable datatable-bordered datatable-head-custom">
                <thead>
                <tr>
                    <th></th>
                    <th>No.</th>
                    <th>Week</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>IHSG</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Last Modified</th>
                    <th>Modified By</th>
                </tr>
                </thead>
            </table>
            <!--end: Datatable-->
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ url('/assets/js/pages/crud/file-upload/image-input.js') }}"></script>
    <script src="{{url('/custom/admin/rejeki-nomplok/master.js')}}" type="application/javascript" ></script>
@endsection
