@extends('layouts.app')
@section('content')
    <div id="kt_content_container" class="container-xxl">
        <div class="card card-xl-stretch mb-xl-8">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Gender
                        <span class="d-block text-muted pt-2 font-size-sm">Analytic for Gender's</span></h3>
                </div>
            </div>
            <div class="card-body" style="position: relative;">
                <!--begin::Chart-->
                <div id="kt_charts_widget_1_chart">
                    <!--begin::Entry-->
                    <div class="d-flex flex-column-fluid">
                        <!--begin::Container-->
                        <div class="container" style='width: 476px;'>
                            <canvas id="canvas"></canvas>
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

        <div class="row g-5 g-xl-4">
            <div class="col-xl-6">
                <div class="card card-xl-stretch mb-xl-8">
                    <div class="card-header flex-wrap border-0 pt-6 pb-0">
                        <div class="card-title">
                            <h3 class="card-label">Customers Age
                                <span class="d-block text-muted pt-2 font-size-sm">Analytic for Date of birth</span></h3>
                        </div>
                    </div>
{{--                            canvas_dob--}}
                    <div class="card-body" style="position: relative;">
                        <div id="kt_charts_widget_1_chart">
                            <!--begin::Entry-->
                            <div class="d-flex flex-column-fluid">
                                <!--begin::Container-->
                                <div class="container" style='width: 476px;'>
                                    <canvas id="canvas_dob"></canvas>
                                </div>
                                <!--end::Container-->
                            </div>
                            <!--end::Entry-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card card-xl-stretch mb-xl-8">
                    <div class="card-header flex-wrap border-0 pt-6 pb-0">
                        <div class="card-title">
                            <h3 class="card-label">Location
                                <span class="d-block text-muted pt-2 font-size-sm">Analytic for Location</span></h3>
                        </div>
                    </div>
                    <div class="card-body" style="position: relative;">
                        <table id="tb_location" class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                            <thead>
                            <th>Location</th>
                            <th>Total</th>
                            </thead>
                            <tbody>
                            @foreach($location as $val)
                                <tr>
                                    <td>{{$val->city_name}}</td>
                                    <td>{{$val->total}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

@endsection
@section('js')
    <script src="{{url('/custom/admin/user_bio.js')}}" type="application/javascript" ></script>
@endsection

