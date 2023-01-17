@extends('layouts.app')
@section('content')
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b example example-compact">
                        <div class="card-header">
                            <h3 class="card-title">Coupon Form Layout</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">
                                    <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                                    <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                                </div>
                            </div>
                        </div>
                        <!--begin::Form-->
                        <form class="form" id="create_coupon_form"  enctype="multipart/form-data" >
                            <div class="card-body">
                                <div class="mb-15">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Coupon Name:</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="name" class="form-control" placeholder="Enter Coupon name" />
                                            <span class="form-text text-muted">Please enter Coupon name</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Hotpoin:</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="hotpoint_value" class="form-control" placeholder="Enter Hotpoin value" />
                                            <span class="form-text text-muted">Please enter Hotpoin value</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Partner Name:</label>
                                        <div class="col-lg-6">
                                            <select class="form-control" name="partner" id="partner" onchange="get_code(this.value);">
                                                <option>-</option>
                                                @foreach($partners as $partner)
                                                    <option value="{{$partner->id}}">{{$partner->partner_name}}</option>
                                                @endforeach
                                            </select>
                                            <span class="form-text text-muted">Please enter partner name</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Serial Code:</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="serial_code" id="serial_code" class="form-control" placeholder="Enter Serial Code" />
                                            <span class="form-text text-muted">Please enter Serial Code</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Length Code:</label>
                                        <div class="col-lg-6">
                                            <input type="number" name="length_code" class="form-control" placeholder="Enter length of code" />
                                            <span class="form-text text-muted">Please enter length of code</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Start:</label>
                                        <div class="col-lg-6">
                                            <input class="form-control"  name="start_date" type="datetime-local"  id="example-datetime-local-input" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Expired:</label>
                                        <div class="col-lg-6">
                                            <input class="form-control" name="expired_date" type="datetime-local" id="example-datetime-local-input" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Total Coupon:</label>
                                        <div class="col-lg-6">
                                            <input type="number" name="total_coupon" class="form-control" placeholder="Enter Total Code" />
                                            <span class="form-text text-muted">Please enter total of code</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Status</label>
                                        <div class="col-9 col-form-label">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" name="status" value="active" />
                                                    <span></span>Active</label>
                                                <label class="radio">
                                                    <input type="radio" name="status" value="inactive" />
                                                    <span></span>Inactive</label>
                                            </div>
                                            <span class="form-text text-muted">Coupon Status</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" id="btn_create_discount" class="btn btn-success mr-2">Generate</button>
                                        <button type="reset" id="btn_cancel" class="btn btn-secondary">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Card-->
                </div>
            </div>
        </div>
    </div>
    <!--end::Entry-->
@endsection

@section('js')
    <script src="{{url('/custom/admin/coupon.js')}}" type="application/javascript" ></script>
@endsection
