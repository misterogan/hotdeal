@extends('layouts.app')
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Ticket Raffles
                    <span class="d-block text-muted pt-2 font-size-sm">Data Ticket Raffles</span></h3>
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table id="dt_ticket_raffle" class="table datatable datatable-bordered datatable-head-custom">
                <thead>
                <tr>
                    <th></th>
                    <th>No.</th>
                    <th>Event Name</th>
                    <th>Ticket Number</th>
                    <th>Peserta</th>
                    <th>Pemenang</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Created At</th>
                </tr>
                </thead>
            </table>
            <!--end: Datatable-->
        </div>
    </div>
@endsection

@section('js')
    <script src="{{url('/custom/admin/ticket_raffle.js')}}" type="application/javascript" ></script>
@endsection
