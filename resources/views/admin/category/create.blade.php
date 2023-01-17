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
                            <h3 class="card-title">Category Form Layout</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">
                                    <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                                    <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                                </div>
                            </div>
                        </div>
                        <!--begin::Form-->
                        <form class="form" id="create_category_form" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="mb-15">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Category / Subcategory Name: </label>
                                        <div class="col-lg-6">
                                            <input type="text" name="category" id="category" class="form-control" placeholder="Enter Category/Subcategory name" />
                                            <span class="form-text text-muted">Please enter Category/Subcategory name</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Is Parent Category?</label>
                                        <div class="col-9 col-form-label">
                                            <div class="checkbox-inline">
                                                <label class="checkbox checkbox-primary">
                                                    <input type="checkbox" name="is_parent" id="is_parent" />
                                                    <span></span>Yes</label>
                                            </div>
                                            <span class="form-text text-muted">Check to make this category a parent category</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Belongs To</label>
                                        <div class="col-9 col-form-label">
                                            <select class="form-control" name="parent_id" id="belong_to">
                                                <option value="0">--</option>
                                                @foreach($parents as $parent)
                                                    <option value="{{ $parent->id }}">{{ $parent->category }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Image: </label>
                                        <div class="col-lg-6">
                                            <div class="image-input image-input-outline" id="kt_image_11">
                                                <div class="image-input-wrapper" style="background-image: url('assets/media/misc/ic_add.png');"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Image">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="image" id="image" accept=".png, .jpg, .jpeg, .svg, .webp" />
                                                    <input type="hidden" name="profile_avatar_remove" />
                                                </label>
                                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                                <span class="form-text text-muted">Image for category</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Icon: </label>
                                        <div class="col-lg-6">
                                            <div class="image-input image-input-outline" id="kt_image_12">
                                                <div class="image-input-wrapper" style="background-image: url('assets/media/misc/ic_add.png');"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Image">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="icon" id="icon" accept=".png, .svg" />
                                                    <input type="hidden" name="profile_avatar_remove" />
                                                </label>
                                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                                <span class="form-text text-muted">Icon for category</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Status</label>
                                        <div class="col-lg-6">
                                            <div class="radio-inline">
                                                <label class="radio radio-solid">
                                                    <input type="radio" name="status" checked="checked" value="active">
                                                    <span></span>Active</label>
                                                <label class="radio radio-solid">
                                                    <input type="radio" name="status" value="inactive">
                                                    <span></span>Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Show In Menu?</label>
                                        <div class="col-9 col-form-label">
                                            <div class="checkbox-inline">
                                                <label class="checkbox checkbox-primary">
                                                    <input type="checkbox" name="show_in_menu" id="show_in_menu" />
                                                    <span></span>Yes</label>
                                            </div>
                                            <span class="form-text text-muted">Check to make this category show in menu</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Role</label>
                                        <div class="col-9 col-form-label">
                                            <select class="form-control" name="role" id="role">
                                                <option value="">--</option>
                                                <option value="promotion">Promotion</option>
                                                <option value="product_bundling">Product Bundling</option>
                                            </select>
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
    <script src="{{url('/custom/admin/category.js')}}" type="application/javascript" ></script>
    <script src="{{ url('/assets/js/pages/crud/file-upload/image-input.js') }}"></script>

@endsection
