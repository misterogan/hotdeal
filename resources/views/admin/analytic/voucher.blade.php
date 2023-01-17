@extends('layouts.app')
@section('content')
    <div class="flex-column flex-row-fluid" id="kt_wrapper">
        <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
            <div id="kt_content_container" class="container-xxl">
                <div class="card card-xl-stretch mb-xl-8">
                    <div class="card-header flex-wrap border-0 pt-6 pb-0">
                        <div class="card-title">
                            <h3 class="card-label">Voucher
                                <span class="d-block text-muted pt-2 font-size-sm">Analytic for Voucher</span></h3>
                        </div>
                    </div>
                    <div class="card-body" style="position: relative;">
                        <!--begin::Chart-->
                        <div id="kt_charts_widget_1_chart">
                            <!--begin::Entry-->
                            <div class="d-flex flex-column-fluid">
                                <!--begin::Container-->
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="event_period">Period</label>
                                                <input type="date" class="form-control" name="date_from" id="date_from">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="event_period">&nbsp;</label>
                                                <input type="date" class="form-control" name="date_until" id="date_until">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="event_period">&nbsp;</label>
                                                <button class="btn btn-primary btn-block" type="submit" onclick="voucher()">Search</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="chart-hot-point">
                                        </div>
                                    </div>
                                </div>
                                <!--end::Container-->
                            </div>
                            <!--end::Entry-->
                        </div>
                        <!--end::Chart-->
                        <div class="resize-triggers"></div>
                        <!--end::Body-->
                    </div>
                </div>
                <div class="card card-custom">
                    <div class="card-header flex-wrap border-0 pt-6 pb-0">
                        <div class="card-title">
                            <h3 class="card-label">Report Voucher
                                <span class="d-block text-muted pt-2 font-size-sm">Total Data Voucher</span></h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <table id="" class="table datatable datatable-bordered datatable-head-custom">
                            <thead>
                            <tr>
                                <th></th>
                                <th>No.</th>
                                <th>Nama Voucher</th>
                                <th>Tipe Voucher</th>
                                <th>Status</th>
                                <th>Total Penggunaan</th>
                            </tr>
                            <input type="hidden" id="voucher_value" value="">
                            @foreach ($voucher as $item)
                                <tr>
                                    <td></td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td> <a class="item-active filter-voucher">{{ $item->voucher_name }}</a></td>
                                    <td>{{ $item->voucher_type }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>{{ $item->total_voucher }}</td>
                                </tr>
                            @endforeach
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>

@endsection
@section('js')
    <script src="{{url('/custom/admin/analytic/voucher.js')}}" type="application/javascript" ></script>
@endsection

