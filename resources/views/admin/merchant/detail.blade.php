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
                            <h3 class="card-title">Merchant Form</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">
                                    <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                                    <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                                </div>
                            </div>
                        </div>
                        <!--begin::Form-->
                        <form class="form" id="edit_highlight"  enctype="multipart/form-data" >
                            @csrf
                            <div class="card-body">
                                <div class="mb-15">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Vendor Name</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="name" id="name" class="form-control" value="{{ $vendor->name }}" disabled/>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Address</label>
                                        <div class="col-lg-6">
                                            <textarea name="address" id="address" cols="30" rows="5" class="form-control" disabled>
                                                {{ $vendor->address }}
                                            </textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row" data-select2-id="189">
                                        <label class="col-3 col-form-label">Highlight Product(s) Selection</label>
                                        <div class="col-6 col-form-label">
                                            <select class="form-control select2" id="kt_select2_4" name="product_selection[]" multiple>
                                                <optgroup label="product">
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}" {{ in_array($product->id, $selected_products) ? "selected" : "" }}>{{ $product->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" id="edit_highlight" class="btn btn-success mr-2">Submit</button>
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

            <div class="row">
                <div class="col-lg-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b example example-compact">
                        <div class="card-header">
                            <h3 class="card-title">Banner Merchant Form</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">
                                    <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                                    <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                                </div>
                            </div>
                        </div>
                        <!--begin::Form-->
                        <form class="form" id="add_merchant_banner"  enctype="multipart/form-data" >
                            @csrf
                            <div class="card-body">
                                <div class="mb-15">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Banner</label>
                                        <div class="col-lg-6">
                                            <div class="image-input image-input-outline" id="kt_image_12">
                                                <div class="image-input-wrapper" style="background-image: url(assets/media/misc/img-placeholder-landscape.png); width: 300px; height: 150px;"></div>
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
                                    <input type="hidden"  name="vendor_id" id="vendor_id" value="{{ $vendor->id }}">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Url</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="url" id="url" class="form-control"/>
                                            <span class="form-text text-muted">*kosongkan jika tidak perlu</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" id="edit_highlight" class="btn btn-success mr-2">Submit</button>
                                        <button type="reset" id="btn_cancel" class="btn btn-secondary">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!--end::Form-->
                        <table id="dt_banner_merchant" class="table datatable datatable-bordered datatable-head-custom">
                            <thead>
                            <tr>
                                <th></th>
                                <th>No.</th>
                                <th>Image</th>
                                <th>Url</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Created By</th>
                                <th>Action</th>
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
    <script src="{{ url('/custom/admin/vendor.js') }}" type="application/javascript" ></script>
    <script src="{{ url('/assets/js/pages/crud/file-upload/image-input.js') }}"></script>
@endsection

