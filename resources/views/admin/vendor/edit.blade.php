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
                            <h3 class="card-title">Vendor Form</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">
                                    <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                                    <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                                </div>
                            </div>
                        </div>
                        <!--begin::Form-->
                        <form class="form" id="edit_vendor_form"  enctype="multipart/form-data" >
                            @csrf
                            <input type="hidden" name="vendor_id" value="{{ $vendor->id }}" />
                            <input type="hidden" name="user_id" value="{{ $user->id }}" />
                            <input type="hidden" name="is_edit" id="isEdit" value="true" />
                            <input type="hidden" name="province_id" id="provinceID" value="{{ $vendor->province_id }}" />
                            <input type="hidden" name="city_id" id="cityID" value="{{ $vendor->city_id }}" />
                            <input type="hidden" name="suburb_id" id="suburbID" value="{{ $vendor->suburb_id }}" />
                            <input type="hidden" name="area_id" id="areaID" value="{{ $vendor->area_id }}" />
                            <div class="card-body">
                                <div class="mb-15">

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">User Name</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="username" id="userName" class="form-control" placeholder="Enter User Name" value="{{ $user->name }}" disabled/>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">User Email</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="email" id="email" class="form-control" placeholder="Enter User Email" value="{{ $user->email }}" disabled/>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">User Password</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="password" id="password" class="form-control" placeholder="Classified information" disabled />
                                        </div>
                                    </div>

                                    <hr><br>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Vendor Name</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Vendor Name" value="{{ $vendor->name }}"/>
                                            <span class="form-text text-muted">Please enter vendor name</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Phone</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="phone" id="phone" class="form-control" placeholder="Enter phone number" value="{{ $user->phone }}"  />
                                            <span class="form-text text-muted">Please enter phone number</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">PIC</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="pic" id="pic" class="form-control" placeholder="Please enter PIC name" value="{{ $vendor->pic }}"  />
                                            <span class="form-text text-muted">Please enter PIC name </span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Sameday</label>
                                        <div class="col-9 col-form-label">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" name="sameday" value="true" {{ $vendor->active_sameday == true ? 'checked' : '' }}/>
                                                    <span></span>Active</label>
                                                <label class="radio">
                                                    <input type="radio" name="sameday" value="false" {{ $vendor->active_sameday == true ? '' : 'checked' }} />
                                                    <span></span>Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Vendor Description</label>
                                        <div class="col-lg-6">
                                            <textarea class="form-control" name="description" id="description" style="resize: none" placeholder="Enter Vendor Description">{{ $vendor->description }}</textarea>
                                            <span class="form-text text-muted">Please enter vendor description</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Vendor Image</label>
                                        <div class="col-lg-6">
                                            <div class="image-input image-input-outline" id="kt_image_1">
                                                <div class="image-input-wrapper" style="background-image: url({{ $vendor->image }}"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="image" id="image" accept=".png, .jpg, .jpeg" />
                                                    <input type="hidden" name="profile_avatar_remove" />
                                                </label>
                                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                            </div>
                                            <span class="form-text text-muted">Allowed file types: png, jpg, jpeg.</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Longtitude</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="long" id="long" class="form-control" value="{{ $vendor->lng }}" />
                                            <span class="form-text text-muted">Please enter longtitude</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Latitude</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="lat" id="lat" class="form-control" value="{{ $vendor->lat }}" />
                                            <span class="form-text text-muted">Please enter latitude</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Country</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <select class="form-control" id="kt_select2_101" name="country_select" disabled>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->id }}" {{ $country->code == "ID" ? "selected" : "" }}>{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="country_select" value="102" />

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Province</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <select class="form-control" id="kt_select2_102" name="province_select" onchange="get_city(this.value);">
                                                <option>Select Province</option>
                                                @foreach($provinces as $province)
                                                    <option value="{{ $province->id }}" {{ $vendor->province_id == $province->id ? "selected" : "" }}>{{ $province->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">City</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <select class="form-control" id="kt_select2_103" name="city_select" onchange="get_district(this.value)">
                                                @foreach($cities as $city)
                                                    <option value="{{ $city->id }}" {{ $vendor->city_id == $city->id ? "selected" : "" }}>{{ $city->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Suburb</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <select class="form-control" id="kt_select2_104" name="suburb_select" onchange="get_area(this.value)">
                                                @foreach($suburbs as $suburb)
                                                    <option value="{{ $suburb->id }}" {{ $vendor->suburb_id == $suburb->id ? "selected" : "" }}>{{ $suburb->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Area</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <select class="form-control" id="kt_select2_105" name="area_select">
                                                @foreach($areas as $area)
                                                    <option value="{{ $area->id }}" {{ $vendor->area_id == $area->id ? "selected" : "" }}>{{ $area->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Address</label>
                                        <div class="col-lg-6">
                                            <textarea type="text" name="address" id="address" class="form-control" placeholder="Address">{{ $vendor->address }}</textarea>
                                            <span class="form-text text-muted">Please enter address</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" id="btn_create_flashsale" class="btn btn-success mr-2">Submit</button>
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
    <script src="{{ url('/assets/js/pages/crud/forms/widgets/select2.js') }}"></script>
    <script src="{{ url('/custom/admin/vendor.js') }}" type="application/javascript" ></script>
    <script src="{{ url('/assets/js/pages/crud/file-upload/image-input.js') }}"></script>
@endsection

