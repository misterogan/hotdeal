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
                            <h3 class="card-title">Edit Discount Form</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">
                                    <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                                    <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                                </div>
                            </div>
                        </div>
                        <!--begin::Form-->
                            <form class="form" id="edit_discount_form"  enctype="multipart/form-data" >
                            <input type="hidden" value="{{ $id }}" name="id" id="id" />
                            <input type="hidden" value="{{ $data->vendor_id }}" name="vendor_id" id="vendor_id" />
                            <div class="card-body">
                                <div class="mb-15">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Vendor</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <select class="form-control" id="kt_select2_101" name="vendor" disabled>
                                                @foreach($vendors as $vendor)
                                                    <option value="{{ $vendor->id }}" {{ $vendor->id == $data->vendor_id ? "selected" : "" }}>{{ $vendor->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Start Date:</label>
                                        <div class="col-lg-6">
                                            <div class="input-group date" id="kt_datetimepicker_1" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" name="start_date" id="start_date" placeholder="Select date &amp; time" data-target="#kt_datetimepicker_1" value="{{ \App\Helpers\Utils::date_to_picker($data->start_date) }}" />
                                                <div class="input-group-append" data-target="#kt_datetimepicker_1" data-toggle="datetimepicker">
                                                    <span class="input-group-text">
                                                        <i class="ki ki-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Expiry Date:</label>
                                        <div class="col-lg-6">
                                            <div class="input-group date" id="kt_datetimepicker_2" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" name="end_date" id="end_date" placeholder="Select date &amp; time" data-target="#kt_datetimepicker_2" value="{{ \App\Helpers\Utils::date_to_picker($data->end_date) }}" />
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
                                                    <input type="radio" name="status" value="active" {{ $data->status == "active" ? "checked" : "" }} />
                                                    <span></span>Active</label>
                                                <label class="radio">
                                                    <input type="radio" name="status" value="inactive" {{ $data->status == "inactive" ? "checked" : "" }} />
                                                    <span></span>Inactive</label>

                                                <label class="radio">
                                                    <input type="radio" name="status" value="deleted" {{ $data->status == "deleted" ? "checked" : "" }} />
                                                    <span></span>Delete</label>
                                            </div>
                                            <span class="form-text text-muted">Status of the discount</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Discount Type</label>
                                        <div class="col-9 col-form-label">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="hidden" name="vendor_id" value={{ $data->vendor_id }}>
                                                    <input type="radio" name="type" value="percentage" {{ $data->type == "percentage" ? "checked" : "" }} />
                                                    <span></span>Percentage</label>
                                                <label class="radio">
                                                    <input type="radio" name="type" value="nominal" {{ $data->type == "nominal" ? "checked" : "" }} />
                                                    <span></span>Amount</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" id="btn_create_discount" class="btn btn-success mr-2">Submit</button>
                                        <button type="reset" id="btn_cancel" class="btn btn-secondary">Cancel</button>
                                    </div>
                                </div>
                            </div>


                        </form>
                        <!--end::Form-->

                        <table id="dt_discount_products" class="table datatable datatable-bordered datatable-head-custom">
                            <thead>
                            <tr>
                                <th></th>
                                <th>No.</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Nominal Discount</th>
                                <th>Discounted Price</th>
                            </tr>
                            </thead>
                        </table>

                    </div>
                    <!--end::Card-->

                </div>
            </div>
        </div>
    </div>
    <!--end::Entry-->
@endsection
@section('js')
    <script src="{{ url('/assets/js/pages/crud/forms/widgets/select2.js') }}"></script>
    <script src="{{ url('/custom/admin/discount.js') }}" type="application/javascript" ></script>
@endsection

