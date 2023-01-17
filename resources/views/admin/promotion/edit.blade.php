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
                            <h3 class="card-title">Edit Promotion Form</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">
                                    <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                                    <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                                </div>
                            </div>
                        </div>
                        <!--begin::Form-->
                        <form class="form" id="edit_promotion_form"  enctype="multipart/form-data" >
                            <input type="hidden" value="{{ $code }}" name="code" id="code" />
                            <div class="card-body">
                                <div class="mb-15">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Voucher Name:</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="voucher_name" id="voucher_name" class="form-control" placeholder="Enter Voucher Name" value="{{ $data->voucher_name }}" />
                                            <span class="form-text text-muted">Please enter voucher name</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Voucher Category</label>
                                        <div class="col-9 col-form-label">
                                            <select class="form-control g_select2" name="voucher_category">
                                                <option value="">Pilih Category</option>
                                                @foreach($voucher_categories as $cat_voucher)
                                                <option value="{{ $cat_voucher->id }}" {{ $cat_voucher->id  == $data->category_promotion_id ? 'selected' : '' }}>{{ $cat_voucher->category }}</option>
                                                @endforeach
                                                <?php print_r($voucher_categories)?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Description</label>
                                        <div class="col-lg-6">
                                            <textarea class="form-control" name="voucher_description" id="voucher_description" style="resize: none" placeholder="Enter Voucher Description">{{ $data->voucher_description }}</textarea>
                                            <span class="form-text text-muted">Please enter description</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Minimum Payment:</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="minimum_payment" id="minimum_payment" class="form-control" placeholder="Enter Minimum Shopping Value" value="{{ $data->minimum_payment }}"/>
                                            <span class="form-text text-muted">Please enter minimum shopping value</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Maximum Promo:</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="maximum_promo" id="maximum_promo" class="form-control" placeholder="Enter Maximum Discount Value" value="{{ $data->maximum_promo }}"/>
                                            <span class="form-text text-muted">Please enter maximum discount value</span>
                                            <span class="form-text text-muted">Please enter 0 if doesn't have maximum promo value</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Total Voucher:</label>
                                        <div class="col-lg-6">
                                            <input type="number" name="total_voucher" id="total_voucher" class="form-control" placeholder="Enter Maximum Discount Value" value="{{ $data->total }}"/>
                                            <span class="form-text text-muted">Please enter total voucher</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Discount Type:</label>
                                        <div class="col-lg-6">
                                            <select name="discount_type" id="discount_type" class="form-control">
                                                <option value=""></option>
                                                <option value="nominal" {{ $data->discount_type == "nominal" ? "selected" : "" }}>Nominal</option>
                                                <option value="percent" {{ $data->discount_type == "percent" ? "selected" : "" }}>Percentage</option>
                                            </select>
                                            <span class="form-text text-muted">Please enter discount type: nominal or percentage</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Voucher Type</label>
                                        <div class="col-lg-6">
                                            <select name="voucher_type" id="voucher_type" class="form-control">
                                                <option value=""></option>
                                                <option value="hotdeal" {{ $data->voucher_type == "hotdeal" ? "selected" : "" }}>Hotdeal</option>
                                                <option value="vendor" {{ $data->voucher_type == "vendor" ? "selected" : "" }}>Vendor</option>
                                            </select>
                                            <span class="form-text text-muted">Please choose voucher type type: hotdeal or vendor</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Discount Value:</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="value_discount" id="value_discount" class="form-control" placeholder="Enter Discount Value" value="{{ $data->value_discount }}"/>
                                            <span class="form-text text-muted">Please enter discount value</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Show Only?</label>
                                        <div class="col-9 col-form-label">
                                            <div class="checkbox-inline">
                                                <label class="checkbox checkbox-primary">
                                                    <input type="checkbox" name="show_only" id="show_only" {{ $data->show_only ? "checked" : "" }} />
                                                    <span></span>Yes</label>
                                            </div>
                                            <span class="form-text text-muted">Check to hide this promotion on checkout</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Start Date Date:</label>
                                        <div class="col-lg-6">
                                            <div class="input-group date" id="kt_datetimepicker_1" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" name="start_date" id="start_date" placeholder="Select date &amp; time" data-target="#kt_datetimepicker_1" value="{{ \App\Helpers\Utils::date_to_picker($data->start_date) }}" />
                                                <div class="input-group-append" data-target="#kt_datetimepicker_1" data-toggle="datetimepicker">
                                                    <span class="input-group-text">
                                                        <i class="ki ki-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Expiry Date:</label>
                                        <div class="col-lg-6">
                                            <div class="input-group date" id="kt_datetimepicker_2" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" name="end_date" id="end_date" placeholder="Select date &amp; time" data-target="#kt_datetimepicker_2" value="{{ \App\Helpers\Utils::date_to_picker($data->end_date) }}" />
                                                <div class="input-group-append" data-target="#kt_datetimepicker_2" data-toggle="datetimepicker">
                                                    <span class="input-group-text">
                                                        <i class="ki ki-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Image</label>
                                        <div class="col-lg-6">
                                            <div class="image-input image-input-outline" id="kt_image_12">
                                                <div class="image-input-wrapper" style="background-image: url({{ $data->image ? $data->image : 'assets/media/misc/img-placeholder-landscape.png' }}); width: 250px;"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="image" id="image" accept=".png, .jpg, .jpeg, .svg" />
                                                    <input type="hidden" name="profile_avatar_remove" />
                                                </label>
                                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                                @if($data->image != '')
                                                    <span class="form-text delete_image float-right" style="display: inline;" onclick="" data-id="{{ $data->id ?? '' }}">hapus</span>
                                                @endif
                                            </div>
                                            <span class="form-text text-muted">Allowed file types: png, jpg, jpeg, svg.</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Apply to all product?</label>
                                        <div class="col-9 col-form-label">
                                            <div class="checkbox-inline">
                                                <label class="checkbox checkbox-primary">
                                                    <input type="checkbox" name="apply_to_all_product" id="apply_to_all_product" {{ $data->apply_to_all_product ? "checked" : "" }}/>
                                                    <span></span>Yes</label>
                                            </div>
                                            <span class="form-text text-muted">Check to make this promotion usable across all of your products</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Voucher code?</label>
                                        <div class="col-9 col-form-label">
                                            <div class="checkbox-inline">
                                                <label class="checkbox checkbox-primary">
                                                    <input type="checkbox" name="is_code" id="is_code" {{ $data->is_code ? "checked" : "" }}/>
                                                    <span></span>Yes</label>
                                            </div>
                                            <span class="form-text text-muted">Check to make this promotion usable search by code</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Multiple Usage?</label>
                                        <div class="col-9 col-form-label">
                                            <div class="checkbox-inline">
                                                <label class="checkbox checkbox-primary">
                                                    <input type="checkbox" name="is_multiple" id="is_multiple" {{ $data->is_multiple ? "checked" : "" }}/>
                                                    <span></span>Yes</label>
                                            </div>
                                            <span class="form-text text-muted">Check to make this promotion multiple times</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Amount Product Only?</label>
                                        <div class="col-9 col-form-label">
                                            <div class="checkbox-inline">
                                                <label class="checkbox checkbox-primary">
                                                    <input type="checkbox" name="amount_product_only" id="amount_product_only" {{ $data->amount_product_only ? "checked" : "" }}/>
                                                    <span></span>Yes</label>
                                            </div>
                                            <span class="form-text text-muted">Check to make this promotion only for product's amount</span>
                                        </div>
                                    </div>

                                    <div class="form-group row" data-select2-id="189">
                                        <label class="col-3 col-form-label">Product(s) Selection</label>
                                        <div class="col-9 col-form-label">
                                            <select class="form-control select2" id="kt_select2_4" name="product_selection[]" multiple {{ $data->apply_to_all_product ? "disabled" : "" }}>
                                                @foreach($categories as $category)
                                                    <optgroup label="{{ $category->category }}">
                                                        @foreach($category->products as $product)
                                                            <option value="{{ $product->id }}" {{ in_array($product->id, $selected_products) ? "selected" : "" }}>{{ $product->name }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row" style="display: none;">
                                        <label class="col-3 col-form-label">Apply to all users?</label>
                                        <div class="col-9 col-form-label">
                                            <div class="checkbox-inline">
                                                <label class="checkbox checkbox-primary">
                                                    <input type="checkbox" name="apply_to_all_user" id="apply_to_all_user" {{ $data->apply_to_all_user ? "checked" : "" }}/>
                                                    <span></span>Yes</label>
                                            </div>
                                            <span class="form-text text-muted">Check to make this promotion usable across all users</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">User Target</label>
                                        <div class="col-9 col-form-label">
                                            <input type="text" name="user_id" id="user_id" class="form-control" value="{{ $data->user_id }}" disabled/>
                                            <span class="form-text text-muted">This is promotion voucher is targeted for User ID {{ $data->user_id }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Status</label>
                                        <div class="col-lg-6">
                                            <div class="radio-inline">
                                                <label class="radio radio-solid">
                                                    <input type="radio" name="status" value="active" {{ $data->status == 'active' ? 'checked' : '' }}>
                                                    <span></span>Active</label>
                                                <label class="radio radio-solid">
                                                    <input type="radio" name="status" value="inactive"  {{ $data->status == 'inactive' ? 'checked' : '' }}>
                                                    <span></span>Inactive</label>
                                                <label class="radio radio-solid">
                                                    <input type="radio" name="status" value="deleted" {{ $data->status == 'deleted' ? 'checked' : '' }}>
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
                                        <button type="submit" id="btn_create_voucher" class="btn btn-success mr-2">Submit</button>
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
    <script src="{{ url('/assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ url('/assets/plugins/custom/prismjs/prismjs.bundle.js') }}"></script>
    <script src="{{ url('/assets/js/scripts.bundle.js') }}"></script>
    <script src="{{ url('/assets/js/pages/crud/file-upload/image-input.js') }}"></script>
    <script src="{{ url('/assets/js/pages/crud/forms/widgets/select2.js') }}"></script>
    <script src="{{ url('/custom/admin/promotion.js') }}" type="application/javascript" ></script>
    <script>
        $('#kt_datetimepicker_1').datetimepicker({
            defaultDate: {{ $data->start_date }}
        });
    </script>
@endsection

