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
                            <h3 class="card-title">Special Event Form</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">
                                    <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                                    <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                                </div>
                            </div>
                        </div>
                        <!--begin::Form-->
                        <form class="form" id="edit_rule_form" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="mb-15">
                                    <input type="hidden" name="id" id="id" value="{{ $rule->id }}">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Start Date:</label>
                                        <div class="col-lg-6">
                                            <div class="input-group date" id="kt_datetimepicker_1" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" name="start_date" id="start_date" placeholder="Select date &amp; time" data-target="#kt_datetimepicker_1" value="{{ \App\Helpers\Utils::date_to_picker($rule->start_date) }}" />
                                                <div class="input-group-append" data-target="#kt_datetimepicker_1" data-toggle="datetimepicker">
                                                    <span class="input-group-text">
                                                        <i class="ki ki-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">End Date:</label>
                                        <div class="col-lg-6">
                                            <div class="input-group date" id="kt_datetimepicker_2" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" name="end_date" id="end_date" placeholder="Select date &amp; time" data-target="#kt_datetimepicker_2" value="{{ \App\Helpers\Utils::date_to_picker($rule->end_date) }}" />
                                                <div class="input-group-append" data-target="#kt_datetimepicker_2" data-toggle="datetimepicker">
                                                    <span class="input-group-text">
                                                        <i class="ki ki-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Count From: </label>
                                        <div class="col-lg-6">
                                            <input type="text" name="count_from" id="count_from" class="form-control" value="{{ $rule->count_from }}" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Count After: </label>
                                        <div class="col-lg-6">
                                            <input type="text" name="count_after" id="count_after" class="form-control" value="{{ $rule->count_after }}" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Percentage: </label>
                                        <div class="col-lg-6">
                                            <input type="text" name="percentage" id="percentage" class="form-control" value="{{ $rule->percentage }}" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Status</label>
                                        <div class="col-lg-6">
                                            <div class="radio-inline">
                                                <label class="radio radio-solid">
                                                    <input type="radio" name="status" value="active" {{ $rule->status == 'active' ? 'checked' : '' }}>
                                                    <span></span>Active</label>
                                                <label class="radio radio-solid">
                                                    <input type="radio" name="status" value="inactive" {{ $rule->status == 'inactive' ? 'checked' : '' }}>
                                                    <span></span>Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" class="btn btn-success mr-2">Submit</button>
                                        <button type="button" id="btn_cancel" class="btn btn-secondary">Cancel</button>
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
    <script src="{{url('/custom/admin/invite.js')}}" type="application/javascript" ></script>
    <script src="{{ url('/assets/js/pages/crud/file-upload/image-input.js') }}"></script>
@endsection
