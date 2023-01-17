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
                            <h3 class="card-title">Flashsale Edit Form</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">
                                    <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                                    <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                                </div>
                            </div>
                        </div>
                        <!--begin::Form-->
                        <form class="form" id="edit_flashsale_form"  enctype="multipart/form-data" >
                            @csrf
                            <input type="hidden" value="{{ $id }}" name="id" id="id" />
                            <div class="card-body">
                                <div class="mb-15">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Flashsale Name:</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Flashsale Name" value="{{ $data->name }}" />
                                            <span class="form-text text-muted">Please enter flashsale name</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Banner Type</label>
                                        <div class="col-9 col-form-label">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" name="banner_type" value="image" {{ $data->banner_type == "image" ? "checked" : "" }} />
                                                    <span></span>Image</label>
                                                <label class="radio">
                                                    <input type="radio" name="banner_type" value="video" {{ $data->banner_type == "video" ? "checked" : "" }} />
                                                    <span></span>Video</label>
                                            </div>
                                            <span class="form-text text-muted">Banner type of the event</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Banner Dekstop</label>
                                        <div class="col-lg-6">
                                            <div class="image-input image-input-outline" id="kt_image_12">
                                                <div class="image-input-wrapper" style="background-image: url({{ $data->banner ? $data->banner : 'assets/media/misc/img-placeholder-landscape.png' }}); width: 250px;"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="banner" id="banner" accept=".png, .jpg, .jpeg, .svg" />
                                                    <input type="hidden" name="profile_avatar_remove" />
                                                </label>
                                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                                @if($data->banner != '')
                                                    <span class="form-text delete_image float-right" style="display: inline;" onclick="" data-id="{{ $data->id ?? '' }}">hapus</span>
                                                @endif
                                            </div>
                                            <span class="form-text text-muted">Allowed file types: png, jpg, jpeg, svg.</span>
                                        </div>
                                    </div>

                                    {{-- <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Banner Mobile</label>
                                        <div class="col-lg-6">
                                            <div class="image-input image-input-outline" id="kt_image_1">
                                                <div class="image-input-wrapper" style="background-image: url({{ $data->banner_mobile ? $data->banner_mobile : 'assets/media/misc/img-placeholder-landscape.png' }}); width: 250px;"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="banner_mobile" id="banner_mobile" accept=".png, .jpg, .jpeg, .svg" />
                                                    <input type="hidden" name="profile_avatar_remove" />
                                                </label>
                                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                            </div>
                                            <span class="form-text text-muted">Allowed file types: png, jpg, jpeg, svg.</span>
                                        </div>
                                    </div> --}}

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Start Date:</label>
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
                                        <label class="col-3 col-form-label">Status</label>
                                        <div class="col-9 col-form-label">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" name="status" value="active" {{ $data->status == "active" ? "checked" : "" }} />
                                                    <span></span>Active</label>
                                                <label class="radio">
                                                    <input type="radio" name="status" value="inactive" {{ $data->status == "inactive" ? "checked" : "" }} />
                                                    <span></span>Inactive</label>
                                            </div>
                                            <span class="form-text text-muted">Status of the event</span>
                                        </div>
                                    </div>

                                    <div class="form-group row" data-select2-id="189">
                                        <label class="col-3 col-form-label">Product(s) Selection</label>
                                        <div class="col-9 col-form-label">
                                            <select class="form-control select2" id="kt_select2_4" name="product_selection[]" multiple>
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
                                </div>


                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" id="btn_edit_flashsale" class="btn btn-success mr-2">Submit</button>
                                        <button type="reset" id="btn_cancel" class="btn btn-secondary">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Card-->
                </div>
                <div class="card card-custom col-lg-12">
                    <div class="card-header flex-wrap border-0 pt-6 pb-0">
                        <div class="card-title">
                            <h3 class="card-label">Log Flashsale
                                <span class="d-block text-muted pt-2 font-size-sm">Data Log Update Flashsale</span></h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <table class="table datatable datatable-bordered datatable-head-custom">
                            <thead>
                            <tr>
                                <th></th>
                                <th>No.</th>
                                <th>Changes</th>
                                <th>Last Modified</th>
                                <th>Type</th>
                                <th>Modified By</th>
                            </tr>
                            </thead>
                            @foreach ($logs as $item)
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>{{ $loop->iteration }}</th>
                                        <th><a href="{{ url('/admin/flashsale/log/'.$item->id) }}">View Changes</a></th>
                                        <th>{{ $item->updated_at }}</th>
                                        <th>{{ $item->type }}</th>
                                        <th>{{ $item->updated_by }}</th>
                                    </tr>
                                </thead>
                                
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Entry-->
@endsection
@section('js')
    <script src="{{ url('/assets/js/pages/crud/file-upload/image-input.js') }}"></script>
    <script src="{{ url('/assets/js/pages/crud/forms/widgets/select2.js') }}"></script>
    <script src="{{ url('/custom/admin/flashsale.js') }}" type="application/javascript" ></script>
@endsection

