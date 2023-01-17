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
                            <h3 class="card-title">Rejeki Nomplok Banner</h3>
                        </div>
                        <br>
                        <!--begin::Form-->
                        <form class="form" id="rejeki_nomplok_banner"  enctype="multipart/form-data" style="margin-left: 50px;">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Banner Dekstop</label>
                                <div class="col-lg-6">
                                    <div class="image-input image-input-outline" id="kt_image_12">
                                        <div class="image-input-wrapper" style="background-image: url({{ $banner->banner ? $banner->banner : 'assets/media/misc/img-placeholder-landscape.png' }}); width: 700px; height: 250px;"></div>
                                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                            <i class="fa fa-pen icon-sm text-muted"></i>
                                            <input type="file" name="banner" id="banner" accept=".png, .jpg, .jpeg, .svg" />
                                            <input type="hidden" name="profile_avatar_remove" />
                                        </label>
                                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                        
                                    @if($banner->banner != '')
                                        <span class="form-text delete_image float-right" onclick="" data-id="{{ $banner->banner ?? '' }}">hapus</span>
                                    @endif
                                    </div>

                                    <span class="form-text text-muted">Allowed file types: png, jpg, jpeg.</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-3 col-form-label">Status</label>
                                <div class="col-9 col-form-label">
                                    <div class="radio-inline">
                                        <label class="radio">
                                            <input type="radio" name="status" value="active" {{ $banner->status == 'active' ? 'checked' : '' }}/>
                                            <span></span>Active</label>
                                        <label class="radio">
                                            <input type="radio" name="status" value="inactive" {{ $banner->status == 'inactive' ? 'checked' : '' }}/>
                                            <span></span>Inactive</label>
                                    </div>
                                    <span class="form-text text-muted">Status of the Banner</span>
                                </div>
                            </div>

                            {{-- <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Banner Mobile</label>
                                <div class="col-lg-6">
                                    <div class="image-input image-input-outline" id="kt_image_1">
                                        <div class="image-input-wrapper" style="background-image: url({{ $banner->banner_mobile ? $banner->banner_mobile : 'assets/media/misc/img-placeholder-landscape.png' }}); width: 700px;"></div>
                                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                            <i class="fa fa-pen icon-sm text-muted"></i>
                                            <input type="file" name="banner_mobile" id="banner_mobile" accept=".png, .jpg, .jpeg, .svg" />
                                            <input type="hidden" name="profile_avatar_remove" />
                                        </label>
                                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                    </div>
                                    <span class="form-text text-muted">Allowed file types: png, jpg, jpeg.</span>
                                </div>
                            </div> --}}

                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" id="btn_rejeki_nomplok_banner" class="btn btn-success mr-2">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ url('/assets/js/pages/crud/file-upload/image-input.js') }}"></script>
    <script src="{{url('/custom/admin/rejeki-nomplok/master.js')}}" type="application/javascript" ></script>
@endsection
