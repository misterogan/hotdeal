@extends('layouts.app')
@section('content')
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-custom">
                        <div class="card-header">
                            <h3 class="card-title">
                                Changes
                            </h3>
                            <span class="card-title">{{ $log->created_at }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <br>

            @if ($log->type == 'updated')
            <div class="row">
                <div class="col-lg-6">
                    <!--begin::Card-->
                    <div class="card card-custom">
                        <div class="card-header">
                            <h3 class="card-title">
                                Before
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group row transaction-content-center">
                                <label class="col-lg-4 col-form-label">Product Name:</label>
                                <div class="col-lg-8">
                                    <span class="text-muted"><h6> <span class="text-secondary">{{ $oldProduct['name'] }}</span> </h6> </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Vendor:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="vendor" id="vendor" class="form-control" placeholder="" value="{{ $oldVendor }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Category:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="category" id="category" class="form-control" placeholder="" value="{{ $oldCategory }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Brand:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="brand" id="brand" class="form-control" placeholder="" value="{{ $oldProduct['brand'] }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Admin Fee:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="admin_fee" id="admin_fee" class="form-control" placeholder="" value="{{ $oldProduct['admin_fee'] }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">SKU:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="sku" id="sku" class="form-control" placeholder="" value="{{ $oldProduct['sku'] }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Status:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="status" id="status" class="form-control" placeholder="" value="{{ $oldProduct['status'] }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Description:</label>
                                <div class="col-lg-8">
                                    <textarea name="" id="" cols="45" rows="10" disabled>{{ $oldProduct['description'] }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Weight:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="weight" id="weight" class="form-control" placeholder="" value="{{ $oldProduct['weight'] }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Height:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="height" id="height" class="form-control" placeholder="" value="{{ $oldProduct['height'] }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Length:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="length" id="length" class="form-control" placeholder="" value="{{ $oldProduct['length'] }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Width:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="width" id="width" class="form-control" placeholder="" value="{{ $oldProduct['width'] }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Product Images: </label>
                                <div class="col-lg-12" style="display: flex; flex-wrap: wrap">
                                @foreach ($oldGalleries as $item)
                                    @if ($item['product_variant_image'] == null && $item['type'] == '1')
                                        <div class="image-input image-input-outline ml-2 mb-2" id="kt_image_11">
                                            <div class="image-input-wrapper" style="background-image: url({{ $item['link'] }});"></div>
                                        </div>
                                    @endif
                                @endforeach
                                </div>
                            </div>
                            @if ($oldVideo != null)
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Video: </label>
                                    <div class="col-lg-12" style="display: flex; flex-wrap: wrap">
                                        <video src="{{ $oldVideo['link'] }}" width="350px" autoplay></video>
                                    </div>
                                </div>
                            @endif
                            <hr>
                            @if ($old_have_variant == true)
                                <h4>Variants</h4>
                                @foreach ($oldDetailProduct as $item)
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">{{ $item['variant_key_1'] }}:</label>
                                        <div class="col-lg-8">
                                            <span class="text-muted"><h6> <span class="text-secondary">{{ $item['variant_value_1'] }}</span> </h6> </span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">Price:</label>
                                        <div class="col-lg-8">
                                            <input type="text" name="price" id="price" class="form-control" placeholder="" value="{{ $item['price'] + $oldProduct['admin_fee'] }}" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">Stock:</label>
                                        <div class="col-lg-8">
                                            <input type="text" name="stock" id="stock" class="form-control" placeholder="" value="{{ $item['stock'] }}" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">Variation Code:</label>
                                        <div class="col-lg-8">
                                            <input type="text" name="child_sku" id="child_sku" class="form-control" placeholder="" value="{{ $item['child_sku'] }}" disabled/>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Variant Images: </label>
                                    <div class="col-lg-12" style="display: flex; flex-wrap: wrap">
                                    @foreach ($oldGalleries as $item)
                                        @if ($item['product_variant_image'] != null)
                                            <div class="image-input image-input-outline ml-2 mb-2" id="kt_image_11">
                                                <div class="image-input-wrapper" style="background-image: url({{ $item['link'] }});"></div>
                                                <span class="form-text text-muted">{{ $item['product_variant_image'] }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                    </div>
                                </div>
                            @else
                                <h4>Single</h4>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Price:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="price" id="price" class="form-control" placeholder="" value="{{ $oldDetailProduct[0]['price'] }}" disabled/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Stock:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="stock" id="stock" class="form-control" placeholder="" value="{{ $oldDetailProduct[0]['stock'] }}" disabled/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Selling Price:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="admin_fee" id="admin_fee" class="form-control" placeholder="" value="{{ $oldDetailProduct[0]['price'] + $oldProduct['admin_fee'] }}" disabled/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Dimensions:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="dimensions" id="dimensions" class="form-control" placeholder="" value="{{ $oldProduct['dimension'] }}" disabled/>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <!--begin::Card-->
                    <div class="card card-custom">
                        <div class="card-header">
                            <h3 class="card-title">
                                After
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group row transaction-content-center">
                                <label class="col-lg-4 col-form-label">Product Name:</label>
                                <div class="col-lg-8">
                                    <span class="text-muted"><h6> <span class="text-secondary">{{ $afterProduct['name'] }}</span> </h6> </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Vendor:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="vendor" id="vendor" class="form-control" placeholder="" value="{{ $afterVendor }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Category:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="category" id="category" class="form-control" placeholder="" value="{{ $afterCategory }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Brand:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="brand" id="brand" class="form-control" placeholder="" value="{{ $afterProduct['brand'] }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Admin Fee:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="admin_fee" id="admin_fee" class="form-control" placeholder="" value="{{ $afterProduct['admin_fee'] }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">SKU:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="sku" id="sku" class="form-control" placeholder="" value="{{ $afterProduct['sku'] }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Status:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="status" id="status" class="form-control" placeholder="" value="{{ $afterProduct['status'] }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Description:</label>
                                <div class="col-lg-8">
                                    <textarea name="" id="" cols="45" rows="10" disabled>{{ $afterProduct['description'] }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Weight:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="weight" id="weight" class="form-control" placeholder="" value="{{ $afterProduct['weight'] }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Height:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="height" id="height" class="form-control" placeholder="" value="{{ $afterProduct['height'] }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Length:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="length" id="length" class="form-control" placeholder="" value="{{ $afterProduct['length'] }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Width:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="width" id="width" class="form-control" placeholder="" value="{{ $afterProduct['width'] }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Product Images: </label>
                                <div class="col-lg-12" style="display: flex; flex-wrap: wrap">
                                @foreach ($afterGalleries as $item)
                                    @if ($item['product_variant_image'] == null && $item['type'] == '1')
                                        <div class="image-input image-input-outline ml-2 mb-2" id="kt_image_11">
                                            <div class="image-input-wrapper" style="background-image: url({{ $item['link'] }});"></div>
                                        </div>
                                    @endif
                                @endforeach
                                </div>
                            </div>
                            @if ($afterVideo != null)
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Video: </label>
                                    <div class="col-lg-12" style="display: flex; flex-wrap: wrap">
                                        <video src="{{ $afterVideo['link'] }}" width="350px" autoplay></video>
                                    </div>
                                </div>
                            @endif
                            <hr>
                            @if ($after_have_variant == true)
                                <h4>Variants</h4>
                                @foreach ($afterDetailProduct as $item)
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">{{ $item['variant_key_1'] }}:</label>
                                        <div class="col-lg-8">
                                            <span class="text-muted"><h6> <span class="text-secondary">{{ $item['variant_value_1'] }}</span> </h6> </span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">Price:</label>
                                        <div class="col-lg-8">
                                            <input type="text" name="price" id="price" class="form-control" placeholder="" value="{{ $item['price'] + $afterProduct['admin_fee'] }}" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">Stock:</label>
                                        <div class="col-lg-8">
                                            <input type="text" name="stock" id="stock" class="form-control" placeholder="" value="{{ $item['stock'] }}" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">Variation Code:</label>
                                        <div class="col-lg-8">
                                            <input type="text" name="child_sku" id="child_sku" class="form-control" placeholder="" value="{{ $item['child_sku'] }}" disabled/>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Variant Images: </label>
                                    <div class="col-lg-12" style="display: flex; flex-wrap: wrap">
                                    @foreach ($afterGalleries as $item)
                                        @if ($item['product_variant_image'] != null)
                                            <div class="image-input image-input-outline ml-2 mb-2" id="kt_image_11">
                                                <div class="image-input-wrapper" style="background-image: url({{ $item['link'] }});"></div>
                                                <span class="form-text text-muted">{{ $item['product_variant_image'] }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                    </div>
                                </div>
                            @else
                                <h4>Single</h4>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Price:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="price" id="price" class="form-control" placeholder="" value="{{ $afterDetailProduct[0]['price'] }}" disabled/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Stock:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="stock" id="stock" class="form-control" placeholder="" value="{{ $afterDetailProduct[0]['stock'] }}" disabled/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Selling Price:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="admin_fee" id="admin_fee" class="form-control" placeholder="" value="{{ $afterDetailProduct[0]['price'] + $afterProduct['admin_fee'] }}" disabled/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Dimensions:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="dimensions" id="dimensions" class="form-control" placeholder="" value="{{ $afterProduct['dimension'] }}" disabled/>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col-lg-12">
                    <!--begin::Card-->
                    <div class="card card-custom">
                        <div class="card-header">
                            <h3 class="card-title">
                                Created
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group row transaction-content-center">
                                <label class="col-lg-4 col-form-label">Product Name:</label>
                                <div class="col-lg-8">
                                    <span class="text-muted"><h6> <span class="text-secondary">{{ $oldProduct['name'] }}</span> </h6> </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Vendor:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="vendor" id="vendor" class="form-control" placeholder="" value="{{ $oldVendor }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Category:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="category" id="category" class="form-control" placeholder="" value="{{ $oldCategory }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Brand:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="brand" id="brand" class="form-control" placeholder="" value="{{ $oldProduct['brand'] }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Admin Fee:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="admin_fee" id="admin_fee" class="form-control" placeholder="" value="{{ $oldProduct['admin_fee'] }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">SKU:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="sku" id="sku" class="form-control" placeholder="" value="{{ $oldProduct['sku'] }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Status:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="status" id="status" class="form-control" placeholder="" value="{{ $oldProduct['status'] }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Description:</label>
                                <div class="col-lg-8">
                                    <textarea name="" id="" cols="45" rows="10" disabled>{{ $oldProduct['description'] }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Weight:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="weight" id="weight" class="form-control" placeholder="" value="{{ $oldProduct['weight'] }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Height:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="height" id="height" class="form-control" placeholder="" value="{{ $oldProduct['height'] }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Length:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="length" id="length" class="form-control" placeholder="" value="{{ $oldProduct['length'] }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Width:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="width" id="width" class="form-control" placeholder="" value="{{ $oldProduct['width'] }}" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Product Images: </label>
                                <div class="col-lg-12" style="display: flex; flex-wrap: wrap">
                                @foreach ($oldGalleries as $item)
                                    @if ($item['product_variant_image'] == null && $item['type'] == '1')
                                        <div class="image-input image-input-outline ml-2 mb-2" id="kt_image_11">
                                            <div class="image-input-wrapper" style="background-image: url({{ $item['link'] }});"></div>
                                        </div>
                                    @endif
                                @endforeach
                                </div>
                            </div>
                            @if ($oldVideo != null)
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Video: </label>
                                    <div class="col-lg-12" style="display: flex; flex-wrap: wrap">
                                        <video src="{{ $oldVideo['link'] }}" width="350px" autoplay></video>
                                    </div>
                                </div>
                            @endif
                            <hr>
                            @if ($old_have_variant == true)
                                <h4>Variants</h4>
                                @foreach ($oldDetailProduct as $item)
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">{{ $item['variant_key_1'] }}:</label>
                                        <div class="col-lg-8">
                                            <span class="text-muted"><h6> <span class="text-secondary">{{ $item['variant_value_1'] }}</span> </h6> </span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">Price:</label>
                                        <div class="col-lg-8">
                                            <input type="text" name="price" id="price" class="form-control" placeholder="" value="{{ $item['price'] + $oldProduct['admin_fee'] }}" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">Stock:</label>
                                        <div class="col-lg-8">
                                            <input type="text" name="stock" id="stock" class="form-control" placeholder="" value="{{ $item['stock'] }}" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">Variation Code:</label>
                                        <div class="col-lg-8">
                                            <input type="text" name="child_sku" id="child_sku" class="form-control" placeholder="" value="{{ $item['child_sku'] }}" disabled/>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Variant Images: </label>
                                    <div class="col-lg-12" style="display: flex; flex-wrap: wrap">
                                    @foreach ($oldGalleries as $item)
                                        @if ($item['product_variant_image'] != null)
                                            <div class="image-input image-input-outline ml-2 mb-2" id="kt_image_11">
                                                <div class="image-input-wrapper" style="background-image: url({{ $item['link'] }});"></div>
                                                <span class="form-text text-muted">{{ $item['product_variant_image'] }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                    </div>
                                </div>
                            @else
                                <h4>Single</h4>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Price:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="price" id="price" class="form-control" placeholder="" value="{{ $oldDetailProduct[0]['price'] }}" disabled/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Stock:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="stock" id="stock" class="form-control" placeholder="" value="{{ $oldDetailProduct[0]['stock'] }}" disabled/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Selling Price:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="admin_fee" id="admin_fee" class="form-control" placeholder="" value="{{ $oldDetailProduct[0]['price'] + $oldProduct['admin_fee'] }}" disabled/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Dimensions:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="dimensions" id="dimensions" class="form-control" placeholder="" value="{{ $oldProduct['dimension'] }}" disabled/>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
                
            @endif



            <br>

        </div>
    </div>
    <!--end::Entry-->
@endsection
@section('js')
    <script src="{{ url('/assets/js/pages/crud/forms/widgets/select2.js') }}"></script>
    <script src="{{ url('/custom/admin/transaction.js') }}" type="application/javascript" ></script>
@endsection

