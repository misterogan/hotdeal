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
                        <h3 class="card-title">Send Point To Cashtree Member</h3>
                        <div class="card-toolbar">
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="form" id="send_point_member"  enctype="multipart/form-data" >
                        <div class="card-body">

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Target</label>
                                <div class="col-lg-6">
                                   <select class="form-control" id="target" name="target">
                                       <option value="">Select Target</option>
                                       <option value="cashtree_member">Cashtree Member</option>
                                       <option value="all_member">All Member (exclude cashtree)</option>
                                   </select>
                                    <span class="form-text text-muted">Please select the target</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Amount</label>
                                <div class="col-lg-6">
                                    <input type="text" name="amount" id="amount" class="form-control">
                                    <span class="form-text text-muted">Please enter amount of point</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">List Email</label>
                                <div class="col-lg-6">
                                    <textarea class="form-control" name="emails" id="emails" style="resize: none" placeholder="List Of Emails" rows="20"></textarea>
                                    <span class="form-text text-muted">separate email with comma (,)</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Description</label>
                                <div class="col-lg-6">
                                    <textarea class="form-control" name="description" id="description" style="resize: none" rows="20"></textarea>
                                    <span class="form-text text-muted">Please enter description of send point</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">OTP</label>
                                <div class="col-lg-6">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="please insert otp" name="otp" id="otp">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button" onclick="request_otp();" id="btn-request-otp">Request Otp</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                    <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-3"></div>
                                <div class="col-lg-6">
                                    <button type="submit" id="btn_send_point_members" class="btn btn-success mr-2">Submit</button>
                                    <button type="reset" id="btn_cancel" class="btn btn-secondary">Cancel</button>
                                </div>
                            </div>
                        </div>
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
    <script src="{{url('/custom/admin/point.js')}}" type="application/javascript" ></script>
@endsection
