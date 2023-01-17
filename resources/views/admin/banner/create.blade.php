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
                        <h3 class="card-title">Banner Form Layout</h3>
                        <div class="card-toolbar">
                            <div class="example-tools justify-content-center">
                                <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                                <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="form" id="create_banner_form"  enctype="multipart/form-data" >
                        <div class="card-body">
                            <div class="mb-15">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Banner Name:</label>
                                    <div class="col-lg-6">
                                        <input type="text" name="name" class="form-control" placeholder="Enter Banner name" />
                                        <span class="form-text text-muted">Please enter Banner name</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Image Upload / Thumbnail * (Dekstop)</label>
                                    <div class="col-lg-6">
                                        <input type="file" name="image_upload" class="form-control" accept="image/*" />
                                        <span class="form-text text-muted">Input image you want to upload, max 100 MB</span>
                                    </div>
                                    <br>
                                </div>
                                {{-- <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Image Upload / Thumbnail * (Mobile)</label>
                                    <div class="col-lg-6">
                                        <input type="file" name="image_mobile_upload" class="form-control" accept="image/*" />
                                        <span class="form-text text-muted">Input image you want to upload, max 100 MB</span>
                                    </div>
                                    <br>
                                </div> --}}
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Video Upload</label>
                                    <div class="col-lg-6">
                                        <input type="file" name="video_upload" class="form-control" accept="video/mp4,video/x-m4v,video/*" />
                                        <span class="form-text text-muted">Input video you want to upload, max 100 MB</span>
                                    </div>
                                    <br>
                                </div>
{{--                                <div class="form-group row">--}}
{{--                                    <label class="col-lg-3 col-form-label">Type</label>--}}
{{--                                    <div class="col-lg-6">--}}
{{--                                        <div class="radio-inline">--}}
{{--                                            <label class="radio radio-solid">--}}
{{--                                                <input type="radio" name="type" checked="checked" value="image">--}}
{{--                                                <span></span>Image</label>--}}
{{--                                            <label class="radio radio-solid">--}}
{{--                                                <input type="radio" name="type" value="video">--}}
{{--                                                <span></span>Video</label>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Deeplink (URL to)</label>
                                    <div class="col-lg-6">
                                        <input type="text" name="url" class="form-control" placeholder="Enter deeplink or URL to" />
                                        <span class="form-text text-muted">Please enter deeplink or URL to</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Sequence</label>
                                    <div class="col-lg-6">
                                        <input type="number" min="1" class="form-control" name="sequence" placeholder="Enter Sequence" />
                                        <span class="form-text text-muted">Please enter sequence order for this banner</span>
                                    </div>
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
                                    <span class="form-text text-muted">Status of the discount</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-3 col-form-label">Open NewTab</label>
                                <div class="col-9 col-form-label">
                                    <div class="radio-inline">
                                        <label class="radio">
                                            <input type="checkbox" name="newtab" />
                                            <span></span>newtab</label>
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
                </div>
                <!--end::Card-->
            </div>
        </div>
    </div>
</div>
<!--end::Entry-->
@endsection

@section('js')
    <script src="{{url('/custom/admin/banner.js')}}" type="application/javascript" ></script>
@endsection
