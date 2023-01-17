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
                            <h3 class="card-title">Rejeki Nomplok</h3>
                        </div>
                        <!--begin::Form-->
                            <form class="form" id="create_master_form" enctype="multipart/form-data">
                                @csrf
                            <div class="card-body">
                                <div class="mb-15">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Week</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="week" id="week" class="form-control" placeholder="Enter Week" />
                                            <span class="form-text text-muted">Please enter week</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Start Date</label>
                                        <div class="col-lg-6">
                                            <div class="input-group date" id="kt_datetimepicker_1" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" name="start_date" id="start_date" placeholder="Select date &amp; time" data-target="#kt_datetimepicker_1" />
                                                <div class="input-group-append" data-target="#kt_datetimepicker_1" data-toggle="datetimepicker">
                                                    <span class="input-group-text">
                                                        <i class="ki ki-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">End Date</label>
                                        <div class="col-lg-6">
                                            <div class="input-group date" id="kt_datetimepicker_2" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" name="end_date" id="end_date" placeholder="Select date &amp; time" data-target="#kt_datetimepicker_2" />
                                                <div class="input-group-append" data-target="#kt_datetimepicker_2" data-toggle="datetimepicker">
                                                    <span class="input-group-text">
                                                        <i class="ki ki-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Status</label>
                                        <div class="col-9 col-form-label">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" name="status" value="active" checked/>
                                                    <span></span>Active</label>
                                                <label class="radio">
                                                    <input type="radio" name="status" value="inactive" />
                                                    <span></span>Inactive</label>
                                            </div>
                                            <span class="form-text text-muted">Status of the FAQ</span>
                                        </div>
                                    </div>

                                    <hr>
                                    <br>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">IHSG</label>
                                        <div class="col-lg-6">
                                            <input type="number" name="ihsg" id="ihsg" class="form-control" placeholder="Enter IHSG" />
                                            <span class="form-text text-muted">Please enter IHSG</span>
                                        </div>
                                    </div>
                            </div>

                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" id="btn_create_master" class="btn btn-success mr-2">Submit</button>
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
    <script src="{{ url('/assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ url('/assets/plugins/custom/prismjs/prismjs.bundle.js') }}"></script>
    <script src="{{ url('/assets/js/pages/crud/forms/widgets/select2.js') }}"></script>
    <script src="{{url('/custom/admin/rejeki-nomplok/master.js')}}" type="application/javascript" ></script>
@endsection

