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
                        <h3 class="card-title">Banner Edit</h3>
                        <div class="card-toolbar">
                            <div class="example-tools justify-content-center">
                                <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                                <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="form" id="edit_banner_form"  enctype="multipart/form-data" >
                        <input type="hidden" name="id" value="{{ $id }}" />
                        <div class="card-body">
                            <div class="mb-15">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Banner Name:</label>
                                    <div class="col-lg-6">
                                        <input type="text" name="name" class="form-control" placeholder="Enter Banner name" value="{{ $data->name }}"/>
                                        <span class="form-text text-muted">Please enter Banner name</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Image Upload / Thumbnail * (Dekstop)</label>
                                    <div class="col-lg-6">
                                        <div class="image-input image-input-outline" id="kt_image_12">
                                            <div class="image-input-wrapper" style="background-image: url({{ $data->img_url ? $data->img_url : 'assets/media/misc/img-placeholder-landscape.png' }}); width: 700px; height: 250px "></div>
                                            <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                                <i class="fa fa-pen icon-sm text-muted"></i>
                                                <input type="file" name="image_upload" id="imageUpload" accept=".png, .jpg, .jpeg, .svg" />
                                                <input type="hidden" name="profile_avatar_remove" />
                                            </label>
                                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                        <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                    </span>
                                            
                                        @if($data->img_url != '')
                                            <span class="form-text delete_image float-right" onclick="" data-id="{{ $data->id ?? '' }}">hapus</span>
                                        @endif
                                        </div>
    
                                        <span class="form-text text-muted">Allowed file types: png, jpg, jpeg.</span>
                                    </div>
                                </div>
                                {{-- <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Image Upload / Thumbnail * (Mobile)</label>
                                    <div class="col-lg-6">
                                        <input type="file" id="imageUpload" name="image_mobile_upload" class="form-control" accept="image/*" />
                                        <span class="form-text text-muted">Input image you want to upload, max 100 MB</span>
                                        <br>
                                        <img src="{{ $data->img_url_banner }}" id="imagePreview" alt="Image Upload" height="100" width="100">
                                    </div>
                                    <br>
                                </div> --}}
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Video Upload</label>
                                    <div class="col-lg-6">
                                        <input type="file" id="videoUpload" name="video_upload" class="form-control" accept="video/mp4,video/x-m4v,video/*" />
                                        <span class="form-text text-muted">Input video you want to upload, max 100 MB</span>
                                        <br>
                                        @if (isset($data->video_url))
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <iframe class="embed-responsive-item" id="videoPreview" src="{{ isset($data->video_url) ? $data->video_url : '' }}"></iframe>
                                        </div>
                                        @endif
                                    </div>
                                    <br>
                                </div>
{{--                                <div class="form-group row">--}}
{{--                                    <label class="col-lg-3 col-form-label">Type</label>--}}
{{--                                    <div class="col-lg-6">--}}
{{--                                        <div class="radio-inline">--}}
{{--                                            <label class="radio radio-solid">--}}
{{--                                                <input type="radio" name="type" value="image" {{ $data->type == 'image' ? 'checked' : '' }}>--}}
{{--                                                <span></span>Image</label>--}}
{{--                                            <label class="radio radio-solid">--}}
{{--                                                <input type="radio" name="type" value="video" {{ $data->type == 'video' ? 'checked' : '' }}>--}}
{{--                                                <span></span>Video</label>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Deeplink (URL to)</label>
                                    <div class="col-lg-6">
                                        <input type="text" name="url" class="form-control" placeholder="Enter deeplink or URL to" value="{{ $data->url }}" />
                                        <span class="form-text text-muted">Please enter deeplink or URL to</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Sequence</label>
                                    <div class="col-lg-6">
                                        <input type="number" min="1" class="form-control" name="sequence" placeholder="Enter Sequence" value="{{ $data->sequence }}" />
                                        <span class="form-text text-muted">Please enter sequence order for this banner</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-3 col-form-label">Status</label>
                                <div class="col-9 col-form-label">
                                    <div class="radio-inline">
                                        <label class="radio">
                                            <input type="radio" name="status" value="active" {{ $data->status == 'active' ? 'checked' : '' }}/>
                                            <span></span>Active</label>
                                        <label class="radio">
                                            <input type="radio" name="status" value="inactive" {{ $data->status == 'inactive' ? 'checked' : '' }}/>
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
                                            <input type="checkbox" name="newtab" {{ $data->new_tab == true ? 'checked' : '' }}/>
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
    <script src="{{ url('/assets/js/pages/crud/file-upload/image-input.js') }}"></script>
    <script src="{{url('/custom/admin/banner.js')}}" type="application/javascript" ></script>
@endsection
