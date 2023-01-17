@extends('layouts.app')
@section('content')

    <div class="flex-column flex-row-fluid" id="kt_wrapper">
        <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
            <div id="kt_content_container" class="container-xxl">

                <div class="card card-xl-stretch mb-xl-8">
                    <div class="card-header flex-wrap border-0 pt-6 pb-0">
                        <div class="card-title">
                            <h3 class="card-label">Total Seller
                                <span class="d-block text-muted pt-2 font-size-sm">Total seller per days</span></h3>
                        </div>
                    </div>
                    <div class="d-flex flex-column-fluid">
                        <!--begin::Container-->
                        <div class="container">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="event_period">Product Name</label>
                                        <select class="form-control" id="kt_select2_101" name="product_name" >
                                            <option value="all">All Product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="event_period">Period</label>
                                        <input type="date" class="form-control" name="date_from" id="date_from">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="event_period">&nbsp;</label>
                                        <input type="date" class="form-control" name="date_until" id="date_until">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="event_period">&nbsp;</label>
                                        <button class="btn btn-primary btn-block" type="submit" onclick="drawing_chart()">Search</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--end::Container-->
                    </div>
                    <div class="card-body" style="position: relative;">
                        <!--begin::Chart-->
                        <div id="kt_charts_widget_1_chart">
                            <!--begin::Entry-->
                            <div class="d-flex flex-column-fluid">
                                <!--begin::Container-->
                                <div class="container chart-container">
{{--                                    <canvas id="canvas"></canvas>--}}
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

                <div class="card card-xl-stretch mb-xl-8">
                    <div class="card-header flex-wrap border-0 pt-6 pb-0">
                        <div class="card-title">
                            <h3 class="card-label">Best Seller
                                <span class="d-block text-muted pt-2 font-size-sm">Top 10 Best Seller</span></h3>
                        </div>
                    </div>

                    <div class="d-flex flex-column-fluid">
                        <!--begin::Container-->
                        <div class="container">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="event_period">Period</label>
                                        <input type="date" class="form-control" name="date_from_1" id="date_from_1">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="event_period">&nbsp;</label>
                                        <input type="date" class="form-control" name="date_until_1" id="date_until_1">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="event_period">&nbsp;</label>
                                        <button class="btn btn-primary btn-block" type="submit" id="search">Search</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--end::Container-->
                    </div>
                    <div class="card-body" style="position: relative;">
                        <!--begin::Chart-->
                        <div id="kt_charts_widget_1_chart">
                            <!--begin::Entry-->
                            <div class="d-flex flex-column-fluid">
                                <!--begin::Container-->
                                <div class="container">

                                    <table id="dt_product" class="table datatable datatable-bordered datatable-head-custom">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>No.</th>
                                            <th>Product</th>
                                            <th>Total</th>
                                        </tr>
                                        </thead>
                                    </table>

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
    <script src="{{ url('/assets/js/pages/crud/forms/widgets/select2.js') }}"></script>
    <script src="{{url('/custom/admin/analytic_product.js')}}" type="application/javascript" ></script>
@endsection

