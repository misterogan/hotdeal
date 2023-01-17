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
                            <h3 class="card-title">Partner Form Layout</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">
                                    <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                                    <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                                </div>
                            </div>
                        </div>
                        <!--begin::Form-->
                        <form class="form" id="edit_partner_form"  enctype="multipart/form-data" >
                            <input type="hidden" name="partner_id" id="partner_id" value="{{$partner->id}}" />
                            <div class="card-body">
                                <div class="mb-15">

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Token:</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="partner_token" id="partner_token" class="form-control" placeholder="Enter Partner name" value="{{$partner->token}}" readonly />
                                            <span class="form-text text-muted">Please enter partner name</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Partner Name:</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="partner_name" id="partner_name" class="form-control" placeholder="Enter Partner name" value="{{$partner->partner_name}}" />
                                            <span class="form-text text-muted">Please enter partner name</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Description:</label>
                                        <div class="col-lg-6">
                                            <textarea class="form-control" name="description" id="description" cols="30" rows="10" style="resize:none"> {{$partner->description}}</textarea>
                                            <span class="form-text text-muted">Please enter description</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Code:</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="partner_code" id="partner_code" class="form-control" placeholder="Enter Total Code" value="{{$partner->partner_code}}" />
                                            <span class="form-text text-muted">Please enter total of code</span>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Image:</label>
                                        <div class="col-lg-9 col-xl-6">
                                            <div class="image-input image-input-outline" id="kt_image_1">
                                                <div class="image-input-wrapper" style="background-image: url({{$partner->image}})"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="partner_image" accept=".png, .jpg, .jpeg" />
                                                    <input type="hidden" name="partner_image_remove" />
                                                </label>
                                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                            </div>
                                            <span class="form-text text-muted">Allowed file types: png, jpg, jpeg.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Show in Footer?</label>
                                        <div class="col-9 col-form-label">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" name="footer" value="true" {{ ($partner->show_in_footer==true)? "checked" : "" }} />
                                                    <span></span>Yes</label>
                                                <label class="radio">
                                                    <input type="radio" name="footer" value="false" {{ ($partner->show_in_footer==false)? "checked" : "" }} />
                                                    <span></span>No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Status</label>
                                        <div class="col-9 col-form-label">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" name="status" value="active" {{ ($partner->status=="active")? "checked" : "" }} />
                                                    <span></span>Active</label>
                                                <label class="radio">
                                                    <input type="radio" name="status" value="inactive" {{ ($partner->status=="inactive")? "checked" : "" }} />
                                                    <span></span>Inactive</label>
                                            </div>
                                            <span class="form-text text-muted">Partner Status</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" id="btn_create" class="btn btn-success mr-2">Submit</button>
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
    <script src="{{url('/custom/admin/partner.js')}}" type="application/javascript" ></script>
    <script src="{{ url('/assets/js/pages/crud/file-upload/image-input.js') }}"></script>
@endsection

