@extends('layouts.app')
@section('content')
    <style type="text/css">
        .horizontal-scrollable > .row {
            overflow-x: auto;
            white-space: nowrap;
        }

        .horizontal-scrollable > .row > .col-xs-4 {
            display: inline-block;
            float: none;
        }

        row > .col-xs-3 {
            display:flex;
            flex: 0 0 25%;
            max-width: 25%
        }

        .flex-nowrap {
            -webkit-flex-wrap: nowrap!important;
            -ms-flex-wrap: nowrap!important;
            flex-wrap: nowrap!important;
        }
        .flex-row {
            display:flex;
            -webkit-box-orient: horizontal!important;
            -webkit-box-direction: normal!important;
            -webkit-flex-direction: row!important;
            -ms-flex-direction: row!important;
            flex-direction: row!important;
        }
    </style>
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b example example-compact">
                        <div class="card-header">
                            <h3 class="card-title">Product Create Form</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">
                                    <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                                    <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                                </div>
                            </div>
                        </div>
                        <!--begin::Form-->
                        <form class="form from-prevent-multiple-submits" id="create_product_form"  enctype="multipart/form-data" >
                            <div class="card-body">
                                <div class="mb-15">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Vendor:</label>
                                        <div class="col-6 col-form-label">
                                            <select class="form-control select2" id="kt_select2_11" name="vendor_id">
                                                @foreach($vendors as $vendor)
                                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Product Name:</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Product Name" />
                                            <span class="form-text text-muted">Special characters not allowed (" * ' !)</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Slug:</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="slug" id="slug" class="form-control" max="100"/>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Category:</label>
                                        <div class="col-6 col-form-label">
                                                <select class="form-control select2" id="kt_select2_12" name="category_selection">
                                                @foreach($categories as $parent)
                                                    <optgroup label="{{ $parent->category }}">
                                                        @foreach($parent->children as $child)
                                                            <option value="{{ $child->id }}">{{ $child->category }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Brand:</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="brand" id="brand" class="form-control" placeholder="Enter Brand Name" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Description:</label>
                                        <div class="col-lg-6">
                                            <textarea class="form-control summernote" name="description" id="description" placeholder="Enter Product Description"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Short desc:</label>
                                        <div class="col-lg-6">
                                            <textarea class="form-control summernote" name="short_desc" id="short_desc" style="height: 100px"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Product Images: </label>

                                        <div class="col-lg-6">
                                            <div class="image-input image-input-outline" id="kt_image_11">
                                                <div class="image-input-wrapper" style="background-image: url('assets/media/misc/ic_add.png');"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Image">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="image[]" id="image" accept=".png, .jpg, .jpeg, .svg, .webp" />

                                                    <input type="hidden" name="profile_avatar_remove" />
                                                </label>
                                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                                <span class="form-text text-muted">Primary Image</span>
                                            </div>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <div class="image-input image-input-outline" id="kt_image_12">
                                                <div class="image-input-wrapper" style="background-image: url('assets/media/misc/ic_add.png');"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Image">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="image[]" id="image" accept=".png, .jpg, .jpeg, .svg, .webp" />
                                                    <input type="hidden" name="profile_avatar_remove" />
                                                </label>
                                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                                <span class="form-text text-muted">Image 2</span>
                                            </div>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <div class="image-input image-input-outline" id="kt_image_13">
                                                <div class="image-input-wrapper" style="background-image: url('assets/media/misc/ic_add.png');"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Image">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="image[]" id="image" accept=".png, .jpg, .jpeg, .svg, .webp" />
                                                    <input type="hidden" name="profile_avatar_remove" />
                                                </label>
                                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                                <span class="form-text text-muted">Image 3</span>
                                            </div>

                                            <br><br>

                                            <div class="image-input image-input-outline" id="kt_image_14">
                                                <div class="image-input-wrapper" style="background-image: url('assets/media/misc/ic_add.png');"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Image">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="image[]" id="image" accept=".png, .jpg, .jpeg, .svg,.webp" />
                                                    <input type="hidden" name="profile_avatar_remove" />
                                                </label>
                                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                                <span class="form-text text-muted">Image 4</span>
                                            </div>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <div class="image-input image-input-outline" id="kt_image_15">
                                                <div class="image-input-wrapper" style="background-image: url('assets/media/misc/ic_add.png');"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Image">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>

                                                    <input type="file" name="image[]" id="image" accept=".png, .jpg, .jpeg, .svg, .webp" />
                                                    <input type="hidden" name="profile_avatar_remove" />
                                                </label>
                                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                                <span class="form-text text-muted">Image 5</span>
                                            </div>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <div class="image-input image-input-outline" id="kt_image_16">
                                                <div class="image-input-wrapper" style="background-image: url('assets/media/misc/ic_add.png');"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Image">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>

                                                    <input type="file" name="image[]" id="image" accept=".png, .jpg, .jpeg, .svg, .webp" />
                                                    <input type="hidden" name="profile_avatar_remove" />
                                                </label>
                                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                                <span class="form-text text-muted">Image 6</span>
                                            </div>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Product Video: </label>
                                        <div class="col-lg-6">
                                            <input type="file" name="video" id="video" accept=".mov, .mp4" />
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <iframe class="embed-responsive-item" src="assets/media/misc/img-placeholder-portrait.png"></iframe>
                                                <span class="form-text text-muted">Product video, accept .mov & .mp4</span>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="card-header">
                                        <h5 class="card-subtitle">Price & Stocks</h5>
                                    </div><br>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Single / Variants</label>
                                        <div class="col-lg-6">
                                            <div class="radio-inline">
                                                <label class="radio radio-solid">
                                                    <input type="radio" name="option" value="single" checked>
                                                    <span></span>Single</label>
                                                <label class="radio radio-solid">
                                                    <input type="radio" name="option" value="variant">
                                                    <span></span>Variants</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Admin Fee:</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="admin_fee" id="admin_fee" class="form-control" placeholder="Enter Admin Fee" />
                                        </div>
                                    </div>
                                    <input type="hidden" name="have_variant_2" id="have_variant_2" value="false" />
                                    <input type="hidden" name="is_addVariant" id="is_addVariant" value="false" />
                                    <div class="single" id="single">

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Price:</label>
                                            <div class="col-lg-6">
                                                <input type="text" name="price" id="price" class="form-control" placeholder="Enter Product Price" />
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Selling Price:</label>
                                            <div class="col-lg-6">
                                                <input type="text" name="sell" id="sell" class="form-control" placeholder="Selling Price (generated)" value="" disabled />
                                                <span class="form-text text-muted">Price + Admin Fee</span>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Stock:</label>
                                            <div class="col-lg-6">
                                                <input type="text" name="stock" id="stock" class="form-control" placeholder="Enter Product Stock" />
                                            </div>
                                        </div>

                                    </div>

                                    <div class="variant" id="variant" style="display: none;">
                                        <div class="card-subheader">
                                            <h5 class="card-subtitle">Variant 1</h5>
                                        </div><br>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Nama:</label>
                                            <div class="col-lg-6">
                                                <input type="text" name="variant_key_1" id="variant_key_1" class="form-control variant_key_1" placeholder="Enter Variant Name" />
                                            </div>
                                        </div>
                                        <div class="values-1">
                                            <div class="form-group row">
                                                <label class="col-lg-3 col-form-label">Pilihan:</label>
                                                <div class="col-lg-6">
                                                    <input type="text" name="variant_value_1[]" id="variant_value_1" data-id="1" class="form-control variant_value_1" placeholder="Enter Variant Value" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-6">
                                                <button type="button" id="add_value_1" class="btn btn-outline-info mr-2">Tambah Pilihan</button>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <button type="button" id="add_variation" class="btn btn-outline-info mr-2" style="width: 100%;">Tambah Variasi 2</button>
                                            </div>
                                        </div>

                                        <div class="variant-2" style="display: none;">
                                            <div class="card-subheader">
                                                <h5 class="card-subtitle">Variant 2</h5>
                                            </div><br>
                                            <div class="form-group row">
                                                <label class="col-lg-3 col-form-label">Nama:</label>
                                                <div class="col-lg-6">
                                                    <input type="text" name="variant_key_2" id="variant_key_2" class="form-control variant_key_2" placeholder="Enter Variant Name" />
                                                </div>
                                            </div>
                                            <div class="values-2">
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">Pilihan:</label>
                                                    <div class="col-lg-6">
                                                        <input type="text" name="variant_value_2[]" id="variant_value_2" class="form-control variant_value_2" placeholder="Enter Variant Value" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-lg-6">
                                                    <button type="button" id="add_value_2" class="btn btn-outline-info mr-2">Tambah Pilihan</button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="form-group row">
                                            <div class="col-lg-6">
                                                <button type="button" id="generate_table" class="btn btn-outline-warning mr-2" onclick="generateTable();">Generate Table</button> Click this for every change in the table.
                                            </div>
                                        </div> -->

                                        <div class="variant-table"> </div>

                                        <br>

{{--                                        <div class="container-fluid" style="display: none;">--}}
                                        <div class="container-fluid">
                                            <div class="row flex-row" id="variant_images">
                                                <div class="col-3">
                                                    <div class="image-input image-input-outline" id="kt_image_200">
                                                        <div class="image-input-wrapper" style="background-image: url('assets/media/misc/ic_add.png');"></div>
                                                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Image">
                                                            <i class="fa fa-pen icon-sm text-muted"></i>
                                                            <input type="file" name="variant_image_200[]" id="variant_image_200" accept=".png, .jpg, .jpeg, .svg, .webp" />
                                                            <input type="hidden" name="profile_avatar_remove" />
                                                        </label>
                                                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row" style="display: none;">
                                            <label class="col-lg-1 col-form-label">Variant Images: </label>

                                            <div class="col-lg-4 variant-images horizontal-scrollable">
                                                <div class="image-input image-input-outline" id="kt_image_101">
                                                    <div class="image-input-wrapper" style="background-image: url('assets/media/misc/ic_add.png');"></div>
                                                    <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Image">
                                                        <i class="fa fa-pen icon-sm text-muted"></i>
                                                        <input type="file" name="image_101[]" id="image_101" accept=".png, .jpg, .jpeg, .svg, .webp" />
                                                        <input type="hidden" name="profile_avatar_remove" />
                                                    </label>
                                                    <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                                </div>
                                                <div class="image-input image-input-outline" id="kt_image_101">
                                                    <div class="image-input-wrapper" style="background-image: url('assets/media/misc/ic_add.png');"></div>
                                                    <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Image">
                                                        <i class="fa fa-pen icon-sm text-muted"></i>
                                                        <input type="file" name="image_101[]" id="image_101" accept=".png, .jpg, .jpeg, .svg, .webp" />
                                                        <input type="hidden" name="profile_avatar_remove" />
                                                    </label>
                                                    <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                                </div><div class="image-input image-input-outline" id="kt_image_101">
                                                    <div class="image-input-wrapper" style="background-image: url('assets/media/misc/ic_add.png');"></div>
                                                    <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Image">
                                                        <i class="fa fa-pen icon-sm text-muted"></i>
                                                        <input type="file" name="image_101[]" id="image_101" accept=".png, .jpg, .jpeg, .svg, .webp" />
                                                        <input type="hidden" name="profile_avatar_remove" />
                                                    </label>
                                                    <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <hr>

                                    <div class="card-header">
                                        <h5 class="card-subtitle">More Details</h5>
                                    </div><br>


                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Weight:</label>
                                        <div class="col-lg-3">
                                            <input type="number" name="weight" id="weight" class="form-control" placeholder="Enter Product Weight" />
                                        </div>
                                        <label class="col-lg-3 col-form-label">Height:</label>
                                        <div class="col-lg-3">
                                            <input type="number" name="height" id="height" class="form-control" placeholder="Enter Product Height" />
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">SKU:</label>
                                        <div class="col-lg-3">
                                            <input type="text" name="sku" id="sku" class="form-control" placeholder="Enter Product Length" />
                                        </div>
                                        <label class="col-lg-3 col-form-label">Length:</label>
                                        <div class="col-lg-3">
                                            <input type="number" name="length" id="length" class="form-control" placeholder="Enter Product Length" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Dimensions:</label>
                                        <div class="col-lg-3">
                                            <input type="text" name="dimension" id="dimension" class="form-control" placeholder="Enter Product Dimension" />
                                        </div>

                                        <label class="col-lg-3 col-form-label">Width:</label>
                                        <div class="col-lg-3">
                                            <input type="number" name="width" id="width" class="form-control" placeholder="Enter Product Width" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Status</label>
                                        <div class="col-lg-6">
                                            <div class="radio-inline">
                                                <label class="radio radio-solid">
                                                    <input type="radio" name="status" value="active">
                                                    <span></span>Active</label>
                                                <label class="radio radio-solid">
                                                    <input type="radio" name="status" value="inactive">
                                                    <span></span>Inactive</label>
                                                <label class="radio radio-solid">
                                                    <input type="radio" name="status" value="deleted">
                                                    <span></span>Deleted</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" id="btn_create_voucher" class="btn btn-success mr-2 from-prevent-multiple-submits">Submit</button>
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
    <script src="{{ url('/assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ url('/assets/plugins/custom/prismjs/prismjs.bundle.js') }}"></script>
    <script src="{{ url('/assets/js/scripts.bundle.js') }}"></script>
    <script src="{{ url('/assets/js/pages/crud/file-upload/image-input.js') }}"></script>
    <script src="{{ url('/assets/js/pages/crud/forms/widgets/select2.js') }}"></script>
    <script src="{{ url('/custom/admin/product.js') }}" type="application/javascript" ></script>
    <script>
        var variant_1 =  { label : '' , variant :[]};
        var variant_2  =  { label : '' , variant :[]};
        var p = { details_variant : {}};
        $(document).ready(function() {
            $('.summernote').summernote();
        });

        document.getElementById("video")
            .onchange = function(event) {
            let file = event.target.files[0];
            let blobURL = URL.createObjectURL(file);
            document.querySelector("iframe").src = blobURL;
        }
    </script>
    <script type="text/javascript">
        (function(){
            $('.from-prevent-multiple-submits').on('submit', function(){
                $('.from-prevent-multiple-submits').attr('disabled','true');
            })
        })();
    </script>
    <script>
        $("#name").keyup(function(){
            var str = $(this).val();
            var trims = $.trim(str)
            var slug = trims.replace(/[^a-z0-9]/gi, '-').replace(/-+/g, '-').replace(/^-|\$/g, '')
            var fix_slug = slug.toLowerCase()
            $("#slug").val(fix_slug.substring(0, 100))
            if (fix_slug.length >= 100) {
                alert("slug lebih dari 100 karakter, cek slug!")
            }
        })
    </script>
@endsection

