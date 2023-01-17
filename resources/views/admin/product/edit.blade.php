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
    </style>
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b example example-compact">
                        <div class="card-header">
                            <h3 class="card-title">Product Edit Form</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">
                                    <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                                    <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                                </div>
                            </div>
                        </div>
                        <!--begin::Form-->
                        <form class="form from-prevent-multiple-submits" id="edit_product_form"  enctype="multipart/form-data" >
                            <input type="hidden" name="product_id" value="{{ $product_id }}" />
                            <div class="card-body">
                                <div class="mb-15">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Vendor:</label>
                                        <div class="col-6 col-form-label">
                                            <select class="form-control select2" id="kt_select2_11" name="vendor_id" readonly>
                                                @foreach($vendors as $vendor)
                                                    @if ($vendor->id == $product->vendor_id)
                                                        <option value="{{ $vendor->id }}" selected>{{ $vendor->name }}</option>
                                                    @else
                                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Product Name:</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Product Name" value="{{ $product->name }}"/>
                                            <span class="form-text text-muted">Special characters not allowed (" * ' !)</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Category:</label>
                                        <div class="col-6 col-form-label">
                                            <select class="form-control select2" id="kt_select2_12" name="category_selection">
                                                @foreach($categories as $parent)
                                                    <optgroup label="{{ $parent->category }}">
                                                        @foreach($parent->children as $child)
                                                            @if ($child->id == $product->category_id)
                                                            <option value="{{ $child->id }}" selected>{{ $child->category }}</option>
                                                            @else
                                                            <option value="{{ $child->id }}">{{ $child->category }}</option>
                                                            @endif
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Brand:</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="brand" id="brand" class="form-control" placeholder="Enter Brand Name" value="{{ $product->brand }}" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Description:</label>
                                        <div class="col-lg-6">
                                            <textarea class="form-control summernote" name="description" id="description" style="height: 200px" placeholder="Enter Product Description">{!! $description !!}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Short desc:</label>
                                        <div class="col-lg-6">
                                            <textarea class="form-control summernote" name="short_desc" id="short_desc" style="height: 100px">{!! $product->short_desc !!}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Product Images: </label>
                                        <div class="col-lg-6">
                                            <div class="image-input image-input-outline" id="kt_image_11">
                                                <input type="hidden" name="image_1_id" value="{{ isset($product->images[0]->id) ? $product->images[0]->id : ''}}">
                                                <div class="image-input-wrapper" style="background-image: url({{ isset($product->images[0]->link) ? $product->images[0]->link : 'assets/media/misc/ic_add.png' }});"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Image">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="image_1" id="image" accept=".png, .jpg, .jpeg, .svg, .webp" />

                                                    <input type="hidden" name="profile_avatar_remove" />
                                                </label>
                                                <span data-image="{{ isset($product->images[0]->link) ? $product->images[0]->link : '' }}" class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow remove-image-primary" data-toggle="tooltip" title="Remove Image">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                                <span class="form-text text-muted">Primary Image</span>
                                            </div>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <div class="image-input image-input-outline" id="kt_image_12">
                                                <input type="hidden" name="image_2_id" value="{{ isset($product->images[1]->id) ? $product->images[1]->id : ''}}">
                                                <div class="image-input-wrapper" style="background-image: url({{ isset($product->images[1]->link) ? $product->images[1]->link : 'assets/media/misc/ic_add.png' }});"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Image">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="image_2" id="image" accept=".png, .jpg, .jpeg, .svg, .webp" />
                                                    <input type="hidden" name="profile_avatar_remove" />
                                                </label>
                                                <span data-image="{{ isset($product->images[1]->link) ? $product->images[1]->link : '' }}" class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow remove-image-primary" data-toggle="tooltip" title="Remove Image">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                                <span class="form-text text-muted">Image 2</span>
                                            </div>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <div class="image-input image-input-outline" id="kt_image_13">
                                                <input type="hidden" name="image_3_id" value="{{ isset($product->images[2]->id) ? $product->images[2]->id : ''}}">
                                                <div class="image-input-wrapper" style="background-image: url({{ isset($product->images[2]->link) ? $product->images[2]->link : 'assets/media/misc/ic_add.png' }});"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Image">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="image_3" id="image" accept=".png, .jpg, .jpeg, .svg, .webp" />
                                                    <input type="hidden" name="profile_avatar_remove" />
                                                </label>
                                                <span data-image="{{ isset($product->images[2]->link) ? $product->images[2]->link : '' }}" class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow remove-image-primary" data-toggle="tooltip" title="Remove Image">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                                <span class="form-text text-muted">Image 3</span>
                                            </div>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <div class="image-input image-input-outline" id="kt_image_14">
                                                <input type="hidden" name="image_4_id" value="{{ isset($product->images[3]->id) ? $product->images[3]->id : ''}}">
                                                <div class="image-input-wrapper" style="background-image: url({{ isset($product->images[3]->link) ? $product->images[3]->link : 'assets/media/misc/ic_add.png' }});"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Image">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="image_4" id="image" accept=".png, .jpg, .jpeg, .svg,.webp" />
                                                    <input type="hidden" name="profile_avatar_remove" />
                                                </label>
                                                <span data-image="{{ isset($product->images[3]->link) ? $product->images[3]->link : '' }}" class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow remove-image-primary" data-toggle="tooltip" title="Remove Image">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                                <span class="form-text text-muted">Image 4</span>
                                            </div>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <div class="image-input image-input-outline" id="kt_image_15">
                                                <input type="hidden" name="image_5_id" value="{{ isset($product->images[4]->id) ? $product->images[4]->id : ''}}">
                                                <div class="image-input-wrapper" style="background-image: url({{ isset($product->images[4]->link) ? $product->images[4]->link : 'assets/media/misc/ic_add.png' }});"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Image">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>

                                                    <input type="file" name="image_5" id="image" accept=".png, .jpg, .jpeg, .svg, .webp" />
                                                    <input type="hidden" name="profile_avatar_remove" />
                                                </label>
                                                <span data-image="{{ isset($product->images[4]->link) ? $product->images[4]->link : '' }}" class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow remove-image-primary" data-toggle="tooltip" title="Remove Image">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                                <span class="form-text text-muted">Image 5</span>
                                            </div>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <div class="image-input image-input-outline" id="kt_image_16">
                                                <input type="hidden" name="image_6_id" value="{{ isset($product->images[5]->id) ? $product->images[5]->id : ''}}">
                                                <div class="image-input-wrapper" style="background-image: url({{ isset($product->images[5]->link) ? $product->images[5]->link : 'assets/media/misc/ic_add.png' }});"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Image">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>

                                                    <input type="file" name="image_6" id="image" accept=".png, .jpg, .jpeg, .svg, .webp" />
                                                    <input type="hidden" name="profile_avatar_remove" />
                                                </label>
                                                <span data-image="{{ isset($product->images[5]->link) ? $product->images[5]->link : '' }}" class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow remove-image-primary" data-toggle="tooltip" title="Remove Image">
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
                                            <input type="file" name="video" id="video" accept=".mov, .mp4" /> <br><br>
                                            <input type="hidden" name="video_id" value="{{ isset($video->link) ? $video->link : ''}}">
                                            <input type="hidden" id="delete_video" name="delete_video" value="false">
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <iframe class="embed-responsive-item" src="{{ isset($product->video->link) ? $product->video->link : 'assets/media/misc/img-placeholder-portrait.png' }}"></iframe>
                                                <span class="form-text text-muted">Product video, accept .mov & .mp4</span>
                                            </div>
                                            @if (isset($product->video->link))
                                                <span style="cursor: pointer" id="deleteVideo">Delete video</span>
                                            @endif

                                        </div>
                                    </div>

                                    <hr>

                                    <div class="card-header">
                                        <h5 class="card-subtitle">Price & Stocks</h5>
                                    </div><br>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Single / Variants</label>
                                        <div class="col-9 col-form-label">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" name="option" value="single" {{ count($product->details) <= 1 ? 'checked' : '' }}/>
                                                    <span></span>Single</label>
                                                <label class="radio">
                                                    <input type="radio" name="option" value="variant" {{ count($product->details) > 1 ? 'checked' : '' }}/>
                                                    <span></span>Variants</label>
                                            </div>
                                            <span class="form-text text-muted">Product Options</span>
                                        </div>
                                    </div>

                                    <input type="hidden" name="have_variant_2" id="have_variant_2" value="{{ $is_multiple_variants ? 'true' : 'false' }}" />
                                    <input type="hidden" name="is_addVariant" id="is_addVariant" value="{{ $is_multiple_variants ? 'true' : 'false' }}" />
                                    <div class="single" id="single" style="display: {{ count($product->details) <= 1 ? 'block' : 'none' }};">
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Price:</label>
                                            <div class="col-lg-6">
                                                <input type="text" name="price" id="price" class="form-control" placeholder="Enter Product Price" value="{{ count($product->details) == 1 ? $product->details[0]->price : '' }}" />
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Selling Price:</label>
                                            <div class="col-lg-6">
                                                <input type="text" name="sell" id="sell" class="form-control" placeholder="Selling Price (generated)" value="{{ count($product->details) == 1 ? \App\Helpers\Utils::currency_convert($product->details[0]->price + $product->admin_fee) : '' }}" disabled />
                                                <span class="form-text text-muted">Price + Admin Fee</span>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Stock:</label>
                                            <div class="col-lg-6">
                                                <input type="text" name="stock" id="stock" class="form-control" placeholder="Enter Product Stock" value="{{ count($product->details) == 1 ? $product->details[0]->stock : '' }}"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="variant" id="variant" style="display: {{ count($product->details) > 1 ? 'block' : 'none' }};">
                                        <div class="card-subheader">
                                            <h5 class="card-subtitle">Variant 1</h5>
                                        </div><br>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Nama:</label>
                                            <div class="col-lg-6">
                                                <input type="text" name="variant_key_1" id="variant_key_1" class="form-control variant_key_1" placeholder="Enter Variant Name" value="{{ isset($product->details[0]->variant_key_1) ? $product->details[0]->variant_key_1 : '' }}"/>
                                            </div>
                                        </div>
                                        <?php
                                            $looper = 0;
                                        ?>
                                        <div class="values-1">
                                            @foreach($product->details as $detail)
                                                @if($loop->index == 0 || $detail->variant_value_1 != $cache)
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">Pilihan:</label>
                                                    <div class="col-lg-6">
                                                        <input type="text" name="variant_value_1[]" id="variant_value_1" data-id="{{ $loop->iteration }}" class="form-control variant_value_1" placeholder="Enter Variant Value" value="{{ $detail->variant_value_1 }}"/>
                                                    </div>
                                                </div>
                                                @php($looper++)
                                                @endif
                                                @php($cache = $detail->variant_value_1)
                                            @endforeach
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

                                        <div class="variant-2" style="display: {{ $is_multiple_variants ? 'block' : 'none' }};">
                                            <div class="card-subheader">
                                                <h5 class="card-subtitle">Variant 2</h5>
                                            </div><br>
                                            <div class="form-group row">
                                                <label class="col-lg-3 col-form-label">Nama:</label>
                                                <div class="col-lg-6">
                                                    <input type="text" name="variant_key_2" id="variant_key_2" class="form-control variant_key_2" placeholder="Enter Variant Name" value="{{ isset($product->details[0]->variant_key_2) ? $product->details[0]->variant_key_2 : '' }}"/>
                                                </div>
                                            </div>
                                            <div class="values-2">
                                                @if(count($product->details) > 0)
                                                    @php($looper = count($product->details) / $looper)
                                                    @for ($i = 0; $i < $looper; $i++)
                                                        <div class="form-group row">
                                                            <label class="col-lg-3 col-form-label">Pilihan:</label>
                                                            <div class="col-lg-6">
                                                                <input type="text" name="variant_value_2[]" id="variant_value_2" class="form-control variant_value_2" placeholder="Enter Variant Value" value="{{ $product->details[$i]->variant_value_2 }}" />
                                                            </div>
                                                        </div>
                                                    @endfor
                                                @endif
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-lg-6">
                                                    <button type="button" id="add_value_2" class="btn btn-outline-info mr-2">Tambah Pilihan</button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="form-group row">
                                            <div class="col-lg-6">
                                                <button type="button" id="generate_table" class="btn btn-outline-warning mr-2" onclick="generate_table_2();">Generate Table</button> Click this for every change in the table.
                                            </div>
                                        </div> -->
                                        <div class="variant-table">
                                            @if ($is_multiple_variants == false)
                                            <table class="table table-bordered" id="createVariantTable">
                                                <tr>
                                                    <th class="tableVariantName1">{{ isset($product->details[0]->variant_key_1) ? $product->details[0]->variant_key_1:'' }}</th>
                                                    <th>Price</th>
                                                    <th>Stock</th>
                                                    <th>Variation Code</th>
                                                </tr>
                                                @foreach($product->details as $detail)
                                                <tr>
                                                    <td>{{ $detail->variant_value_1 }}</td>
                                                    <td>Rp. <input type="text" placeholder="Price" name="price_{{ $loop->index }}" value="{{ $detail->price }}"/> </td>
                                                    <td> <input type="number" placeholder="Stock" name="stock_{{ $loop->index }}" value="{{ $detail->stock }}"/> </td>
                                                    <td> <input type="text" placeholder="Variation Code" name="code_{{ $loop->index }}" value="{{ $detail->child_sku }}"/> </td>
                                                </tr>
                                                @endforeach
                                            </table>
                                            @else
                                            <table class="table table-bordered" id="createVariantTable">
                                                <tr>
                                                    <th class="tableVariantName1">{{ $product->details[0]->variant_key_1 ? $product->details[0]->variant_key_1: '' }}</th>
                                                    <th class="tableVariantName2">{{ $product->details[0]->variant_key_2 ? $product->details[0]->variant_key_2 : '' }}</th>
                                                    <th>Price</th>
                                                    <th>Stock</th>
                                                    <th>Variation Code</th>
                                                </tr>
                                                @foreach($product->details as $detail)
                                                    <tr>
                                                        <td>{{ $detail->variant_value_1 }}</td>
                                                        <td>{{ $detail->variant_value_2 }}</td>
                                                        <td>Rp. <input type="text" placeholder="Price" name="price_{{ $loop->index }}" value="{{ $detail->price }}"/> </td>
                                                        <td> <input type="number" placeholder="Stock" name="stock_{{ $loop->index }}" value="{{ $detail->stock }}"/> </td>
                                                        <td> <input type="text" placeholder="Variation Code" name="code_{{ $loop->index }}" value="{{ $detail->variation_code }}"/> </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                            @endif
                                        </div>

                                        <br>
                                        
                                        <div class="form-group row" style="">
                                            <label class="col-lg-3 col-form-label">Variant Images:</label>
                                            <div class="container-fluid">
                                                <div class="row flex-row" id="variant_images">
                                                    @foreach($variant_images as $vi)
                                                        <div class="col-3">
                                                            <div class="image-input image-input-outline" id="kt_image_200">
                                                            <div class="image-input-wrapper" style="background-image: url({{ isset($vi->link) ? $vi->link : 'assets/media/misc/ic_add.png' }});"></div>
                                                                    <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Image">
                                                                        <i class="fa fa-pen icon-sm text-muted"></i>
                                                                        <input type="file" name="variant_image_200[]" id="variant_image_200" accept=".png, .jpg, .jpeg, .svg" />
                                                                        <input type="hidden" name="profile_avatar_remove" />
                                                                    </label>
                                                                    <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                                        <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>



                                </div>

                                <div class="card-header">
                                        <h5 class="card-subtitle">More Details</h5>
                                    </div><br>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Weight:</label>
                                        <div class="col-lg-3">
                                            <input type="number" name="weight" id="weight" class="form-control" placeholder="Enter Product Weight" value="{{ $product->weight }}" />
                                        </div>
                                        <label class="col-lg-3 col-form-label">Height:</label>
                                        <div class="col-lg-3">
                                            <input type="number" name="height" id="height" class="form-control" placeholder="Enter Product Height" value="{{ $product->height }}" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">SKU:</label>
                                        <div class="col-lg-3">
                                            <input type="text" name="sku" id="sku" class="form-control" placeholder="Enter Product Length" value="{{ $product->sku }}" />
                                        </div>
                                        <label class="col-lg-3 col-form-label">Length:</label>
                                        <div class="col-lg-3">
                                            <input type="number" name="length" id="length" class="form-control" placeholder="Enter Product Length" value="{{ $product->length }}" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Admin Fee:</label>
                                        <div class="input-group col-lg-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input type="number" name="admin_fee" id="admin_fee" class="form-control" placeholder="Enter Admin Fee" value="{{ $product->admin_fee }}" />
                                        </div>
                                        <label class="col-lg-3 col-form-label">Width:</label>
                                        <div class="col-lg-3">
                                            <input type="number" name="width" id="width" class="form-control" placeholder="Enter Product Width" value="{{ $product->width }}" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Dimensions:</label>
                                        <div class="col-lg-3">
                                            <input type="text" name="dimension" id="dimension" class="form-control" placeholder="Enter Product Dimension" value="{{ $product->dimension }}" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Status</label>
                                        <div class="col-lg-6">
                                            <div class="radio-inline">
                                                @if($product->status != 'deleted')
                                                <label class="radio radio-solid">
                                                    <input type="radio" name="status" value="active" {{ $product->status == 'active' ? 'checked' : '' }}>
                                                    <span></span>Active</label>
                                                <label class="radio radio-solid">
                                                    <input type="radio" name="status" value="inactive"  {{ $product->status == 'inactive' ? 'checked' : '' }}>
                                                    <span></span>Inactive</label>
                                                <label class="radio radio-solid">
                                                    <input type="radio" name="status" value="deleted" {{ $product->status == 'deleted' ? 'checked' : '' }}>
                                                    <span></span>Deleted</label>
                                                @else
                                                    <label class="radio radio-solid">
                                                    <input type="radio" name="status" value="deleted" {{ $product->status == 'deleted' ? 'checked' : '' }}>
                                                    <span></span>Deleted</label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" id="btn_edit_product" class="btn btn-success mr-2 from-prevent-multiple-submits">Submit</button>
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
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Log Product
                            <span class="d-block text-muted pt-2 font-size-sm">Data Log Update Products</span></h3>
                    </div>
                </div>
                <div class="card-body">
                    <!--begin: Datatable-->
                    <table id="dt_log_product" class="table datatable datatable-bordered datatable-head-custom">
                        <thead>
                        <tr>
                            <th></th>
                            <th>No.</th>
                            <th>Product Name</th>
                            <th>Changes</th>
                            <th>Last Modified</th>
                            <th>Type</th>
                            <th>Modified By</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--end::Entry-->
@endsection
@section('js')
    {{-- <script src="{{ url('/assets/plugins/global/plugins.bundle.js') }}"></script> --}}
    <script src="{{ url('/assets/plugins/custom/prismjs/prismjs.bundle.js') }}"></script>
    <script src="{{ url('/assets/js/scripts.bundle.js') }}"></script>
    <script src="{{ url('/assets/js/pages/crud/file-upload/image-input.js') }}"></script>
    <script src="{{ url('/assets/js/pages/crud/forms/widgets/select2.js') }}"></script>
    <script src="{{ url('/custom/admin/product.js') }}" type="application/javascript" ></script>
    <script>
        <?php
        $p = $product;

        unset($p['description']);
        unset($p['short_desc']);
        
        ?>
        var p = JSON.parse(`<?php echo $p;?>`);
        console.log(p);
        var variant_1 =  { label : '' , variant :[]};
        var variant_2  =  { label : '' , variant :[]};
        //var removed_image = [];
        if(Object.keys(p.variant.variant_1.variant).length > 0){
                variant_1.label = p.variant.variant_1.label;
                variant_1.variant = p.variant.variant_1.variant;
        }
        if(Object.keys(p.variant.variant_2.variant).length > 0){
                variant_2.label = p.variant.variant_2.label;
                variant_2.variant = p.variant.variant_2.variant;
        }
        $(document).ready(function() {
            $('.summernote').summernote({
                toolbar: [
                    // ['cleaner',['cleaner']], // The Button
                    ['font',['bold','italic','underline','clear']],
                    ['para',['ul','ol','paragraph']],
                    ['insert',['media','link']],
                ],
            });
            detailVariant()
            generate_variant_1()
            generate_variant_2()

        });

        document.getElementById("video")
            .onchange = function(event) {
            let file = event.target.files[0];
            let blobURL = URL.createObjectURL(file);
            document.querySelector("iframe").src = blobURL;
        }

        document.getElementById("deleteVideo")
            .onclick = function(event) {
            // document.getElementById("video").val = 'delete_video';
            $("#delete_video").val("true");
            document.querySelector("iframe").src = 'assets/media/misc/img-placeholder-portrait.png';
        }
    </script>
@endsection

