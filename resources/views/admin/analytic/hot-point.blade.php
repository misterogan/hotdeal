@extends('layouts.app')
@section('content')
    <div class="flex-column flex-row-fluid" id="kt_wrapper">
        <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
            <div id="kt_content_container" class="container-xxl">
                <div class="card card-xl-stretch mb-xl-8">
                    <div class="card-header flex-wrap border-0 pt-6 pb-0">
                        <div class="card-title">
                            <h3 class="card-label">Hotpoint
                                <span class="d-block text-muted pt-2 font-size-sm">Analytic for Hotpoint</span></h3>
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
                                                <button class="btn btn-primary btn-block" type="submit" onclick="_hotpoint_chart()">Search</button>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="event_period">&nbsp;</label>
                                                <button id="download" class="btn btn-info btn-block">Download</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="_hotpoint_chart" class="card-rounded-bottom" data-color="primary" style="height: 500px"></div>

                                </div>
                                <!--end::Container-->
                            </div>
                            <!--end::Entry-->
                        </div>
                        <!--end::Chart-->
                        <div class="card-title pt-4 mb-4">
                            <h3 class="card-label">Total Use & Earn Point Daily</h3>
                        </div>
                        <table id="dt_hotpoint" class="table datatable datatable-bordered datatable-head-custom mb-3">
                            <thead>
                            <tr>
                                <th></th>
                                <th>No.</th>
                                <th>Date</th>
                                <th>Total Use</th>
                                <th>Total Earn</th>
                            </tr>
                            </thead>
                        </table>
                        <div class="resize-triggers"></div>
                        <!--end::Body-->
                    </div>
                </div>

                <div class="card card-xl-stretch mb-xl-8">
                    <div class="card-header flex-wrap border-0 pt-6 pb-0">
                        <div class="card-title">
                            <h3 class="card-label">Hotpoint
                                <span class="d-block text-muted pt-2 font-size-sm">Analytic for Hotpoint per Month</span></h3>
                        </div>
                    </div>
                    <div class="card-body" style="position: relative;">
                        <!--begin::Chart-->
                        <div id="kt_charts_widget_1_chart">
                            <!--begin::Entry-->
                            <div class="d-flex flex-column-fluid">
                                <!--begin::Container-->
                                <div class="container">
                                    <div id="_monthly_hotpoint_chart" class="card-rounded-bottom" data-color="primary" style="height: 500px"></div>
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
            </div>
        </div>

    </div>

@endsection
@section('js')
    <script src="{{url('/custom/admin/analytic/hotpoint.js')}}" type="application/javascript" ></script>
@endsection

