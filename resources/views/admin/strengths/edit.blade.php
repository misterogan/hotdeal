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
                            <h3 class="card-title">Edit Hotdeal Strengths</h3>
                        </div>
                        <!--begin::Form-->
                        <form class="form" id="edit_strength_form"  enctype="multipart/form-data" >
                            @csrf
                            <input type="hidden" name="id" value="{{ $strength->id }}" />
                            <div class="card-body">
                                <div class="mb-15">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Image</label>
                                        <div class="col-lg-6">
                                            <div class="image-input image-input-outline" id="kt_image_12">
                                                <div class="image-input-wrapper" style="background-image: url({{ isset($strength->image) ? $strength->image : "assets/media/misc/img-placeholder-square.png" }});"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="image" id="image" accept=".png, .jpg, .jpeg, .svg" />
                                                    <input type="hidden" name="profile_avatar_remove" />
                                                </label>
                                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                            </div>
                                            <span class="form-text text-muted">Allowed file types: png, jpg, jpeg, svg.</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Title</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="title" id="title" class="form-control" placeholder="Enter Title" value="{{ $strength->title }}" />
                                            <span class="form-text text-muted">Please enter title</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Description</label>
                                        <div class="col-lg-6">
                                            <textarea class="form-control" name="description" id="description" placeholder="Enter Description">{{ $strength->description }}</textarea>
                                            <span class="form-text text-muted">Please enter description</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Status</label>
                                        <div class="col-9 col-form-label">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" name="status" value="active" {{ $strength->status == 'active' ? 'checked' : '' }}/>
                                                    <span></span>Active</label>
                                                <label class="radio">
                                                    <input type="radio" name="status" value="inactive" {{ $strength->status == 'inactive' ? 'checked' : '' }}/>
                                                    <span></span>Inactive</label>
                                            </div>
                                            <span class="form-text text-muted">Status of the Strength</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" id="btn_create_strength" class="btn btn-success mr-2">Submit</button>
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
    <script src="{{ url('/custom/admin/strength.js') }}" type="application/javascript" ></script>
@endsection

