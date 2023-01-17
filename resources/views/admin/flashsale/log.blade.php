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
                                <div class="mb-15">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Flashsale Name</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="name" id="name" class="form-control" value="{{ $before['detail']['name'] }}" disabled/>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Banner Type</label>
                                        <div class="col-9 col-form-label">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" name="banner_type" disabled/>
                                                    <span></span>{{ $before['detail']['banner_type'] }}</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Banner Dekstop</label>
                                        <div class="col-lg-6">
                                            <div class="image-input image-input-outline" id="kt_image_12">
                                                <div class="image-input-wrapper" style="background-image:  url( '{{ $before['detail']['banner'] }}'); width: 250px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Start Date:</label>
                                        <div class="col-lg-6">
                                            <div class="input-group date">
                                                <input type="text" name="name" id="name" class="form-control" value="{{ $before['detail']['start_date'] }}" disabled/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Expiry Date:</label>
                                        <div class="col-lg-6">
                                            <div class="input-group date">
                                                <input type="text" name="name" id="name" class="form-control" value="{{ $before['detail']['end_date'] }}" disabled/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Status</label>
                                        <div class="col-9 col-form-label">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" name="banner_type" disabled/>
                                                    <span></span>{{ $before['detail']['status'] }}</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row" data-select2-id="189">
                                        <label class="col-3 col-form-label">Product(s) Selection</label>
                                        <div class="col-9 col-form-label">
                                                @foreach($beforeProducts as $product)
                                                    <label>{{ $product }}</label>
                                                @endforeach
                                        </div>
                                    </div>
                                </div>
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
                                <div class="mb-15">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Flashsale Name</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="name" id="name" class="form-control" value="{{ $after['detail']['name'] }}" disabled/>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Banner Type</label>
                                        <div class="col-9 col-form-label">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" name="banner_type" disabled/>
                                                    <span></span>{{ $after['detail']['banner_type'] }}</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Banner Dekstop</label>
                                        <div class="col-lg-6">
                                            <div class="image-input image-input-outline" id="kt_image_12">
                                                <div class="image-input-wrapper" style="background-image:  url( '{{ $after['detail']['banner'] }}'); width: 250px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Start Date:</label>
                                        <div class="col-lg-6">
                                            <div class="input-group date">
                                                <input type="text" name="name" id="name" class="form-control" value="{{ $after['detail']['start_date'] }}" disabled/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Expiry Date:</label>
                                        <div class="col-lg-6">
                                            <div class="input-group date">
                                                <input type="text" name="name" id="name" class="form-control" value="{{ $after['detail']['end_date'] }}" disabled/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Status</label>
                                        <div class="col-9 col-form-label">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" name="banner_type" disabled/>
                                                    <span></span>{{ $after['detail']['status'] }}</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row" data-select2-id="189">
                                        <label class="col-3 col-form-label">Product(s) Selection</label>
                                        <div class="col-9 col-form-label">
                                                @foreach($afterProducts as $product)
                                                    <label>{{ $product }}</label>
                                                @endforeach
                                        </div>
                                    </div>
                                </div>
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
                                <div class="mb-15">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Flashsale Name</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Flashsale Name" />
                                            <span class="form-text text-muted">Please enter flashsale name</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Banner Type</label>
                                        <div class="col-9 col-form-label">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" name="banner_type" value="image" />
                                                    <span></span>Image</label>
                                                <label class="radio">
                                                    <input type="radio" name="banner_type" value="video" />
                                                    <span></span>Video</label>
                                            </div>
                                            <span class="form-text text-muted">Banner type of the event</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Banner Dekstop</label>
                                        <div class="col-lg-6">
                                            <div class="image-input image-input-outline" id="kt_image_12">
                                                <div class="image-input-wrapper" style="background-image: url('assets/media/misc/img-placeholder-landscape.png'); width: 250px;"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="banner" id="banner" accept=".png, .jpg, .jpeg, .svg, .webp" />
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
                                        <label class="col-lg-3 col-form-label">Start Date:</label>
                                        <div class="col-lg-6">
                                            <div class="input-group date" id="kt_datetimepicker_1" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" name="start_date" id="start_date" placeholder="Select date &amp; time" data-target="#kt_datetimepicker_1" />
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
                                                <input type="text" class="form-control datetimepicker-input" name="end_date" id="end_date" placeholder="Select date &amp; time" data-target="#kt_datetimepicker_2" />
                                                <div class="input-group-append" data-target="#kt_datetimepicker_2" data-toggle="datetimepicker">
                                                    <span class="input-group-text">
                                                        <i class="ki ki-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="form-group row" data-select2-id="189">
                                        <label class="col-3 col-form-label">Product(s) Selection</label>
                                        <div class="col-9 col-form-label">
                                            <select class="form-control select2" id="kt_select2_4" name="product_selection[]" multiple>
                                                @foreach($categories as $category)
                                                    <optgroup label="{{ $category->category }}">
                                                        @foreach($category->products as $product)
                                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                </div>


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

