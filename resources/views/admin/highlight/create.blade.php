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
                            <h3 class="card-title">Highlight Product Form</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">
                                    <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                                    <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                                </div>
                            </div>
                        </div>
                        <!--begin::Form-->
                            <form class="form" id="create_highlight_form"  enctype="multipart/form-data" >
                                @csrf
                            <div class="card-body">
                                <div class="mb-15">
                                    <div class="form-group row" data-select2-id="189">
                                        <label class="col-3 col-form-label">Product Selection</label>
                                        <div class="col-9 col-form-label">
                                            <select class="form-control select2" id="kt_select2_4" name="product_selection">
                                                @foreach($categories as $category)
                                                    <optgroup label="{{ $category->category }}">
                                                        @foreach($category->products as $product)
                                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row" id="deepLink" style="display:flex">
                                        <label class="col-lg-3 col-form-label">Deep Link</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="deep_link" id="deep_link" class="form-control" />
                                            <span class="form-text text-muted">*optional</span>
                                        </div>
                                    </div>
                                    <div class="form-group row" id="customVideo" style="display:none">
                                        <label class="col-lg-3 col-form-label">Video</label>
                                        <div class="col-lg-6">
                                            <input class="mb-4" type="file" name="video" id="video" accept=".mov, .mp4" />
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <iframe class="embed-responsive-item" src="assets/media/misc/img-placeholder-portrait.png"></iframe>
                                                <span class="form-text text-muted">Product video, accept .mov & .mp4</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group m-0">
                                        <label>Choose Highlight Type:</label>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label class="option">
                                                    <span class="option-control">
                                                        <span class="radio">
                                                            <input type="radio" name="highlight_type" value="1" checked="checked" onclick="highlightOption(1)"/>
                                                            <span></span>
                                                        </span>
                                                    </span>
                                                    <span class="option-label">
                                                        <span class="option-head">
                                                            <span class="option-title">Below First Banner</span>
                                                            <span class="option-focus">Option 1</span>
                                                        </span>
                                                        <span class="option-body"><img alt="option1" src="img/highlight_1.png" style="max-width: 100%; max-height: 100%;"/></span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="option">
                                                    <span class="option-control">
                                                        <span class="radio">
                                                            <input type="radio" name="highlight_type" value="2" onclick="highlightOption(2)"/>
                                                            <span></span>
                                                        </span>
                                                    </span>
                                                    <span class="option-label">
                                                        <span class="option-head">
                                                            <span class="option-title">Below Flash Sale</span>
                                                            <span class="option-focus">Option 2</span>
                                                        </span>
                                                        <span class="option-body"><img alt="option2" src="img/highlight_2.png" style="max-width: 100%; max-height: 100%;"/></span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="option">
                                                    <span class="option-control">
                                                        <span class="radio">
                                                            <input type="radio" name="highlight_type" value="3" onclick="highlightOption(3)"/>
                                                            <span></span>
                                                        </span>
                                                    </span>
                                                    <span class="option-label">
                                                        <span class="option-head">
                                                            <span class="option-title">Below Option 2</span>
                                                            <span class="option-focus">Option 3</span>
                                                        </span>
                                                        <span class="option-body"><img alt="option3" src="img/highlight_3.png" style="max-width: 100%; max-height: 100%;"/></span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="option">
                                                    <span class="option-control">
                                                        <span class="radio">
                                                            <input type="radio" name="highlight_type" value="4" onclick="highlightOption(4)"/>
                                                            <span></span>
                                                        </span>
                                                    </span>
                                                    <span class="option-label">
                                                        <span class="option-head">
                                                            <span class="option-title">Above Footer</span>
                                                            <span class="option-focus">Option 4</span>
                                                        </span>
                                                        <span class="option-body"><img alt="option4" src="img/highlight_4.png" style="max-width: 100%; max-height: 100%;"/></span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="option">
                                                    <span class="option-control">
                                                        <span class="radio">
                                                            <input type="radio" name="highlight_type" value="5" onclick="highlightOption(5)"/>
                                                            <span></span>
                                                        </span>
                                                    </span>
                                                    <span class="option-label">
                                                        <span class="option-head">
                                                            <span class="option-title">Special Event</span>
                                                            <span class="option-focus">Option 5</span>
                                                        </span>
                                                        <span class="option-body"><img alt="option4" src="assets/media/misc/img-placeholder-square.png" style="max-width: 100%; max-height: 100%;"/></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div><br>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Sequence</label>
                                        <div class="col-lg-6">
                                            <input type="number" name="sequence" id="sequence" class="form-control" />
                                            <span class="form-text text-muted">The order in which this product goes into the highlight</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Highlight Image (Square)</label>
                                        <div class="col-lg-6">
                                            <div class="image-input image-input-outline" id="kt_image_11">
                                                <div class="image-input-wrapper" style="background-image: url(assets/media/misc/img-placeholder-square.png)"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="img_square" id="img_square" accept=".png, .jpg, .jpeg, .svg" />
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
                                        <label class="col-lg-3 col-form-label">Highlight Image (Landscape)</label>
                                        <div class="col-lg-6">
                                            <div class="image-input image-input-outline" id="kt_image_12">
                                                <div class="image-input-wrapper" style="background-image: url(assets/media/misc/img-placeholder-landscape.png); width: 250px;"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="img_landscape" id="img_landscape" accept=".png, .jpg, .jpeg, .svg" />
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
                                        <label class="col-lg-3 col-form-label">Highlight Image (Portrait)</label>
                                        <div class="col-lg-6">
                                            <div class="image-input image-input-outline" id="kt_image_13">
                                                <div class="image-input-wrapper" style="background-image: url(assets/media/misc/img-placeholder-portrait.png); height: 250px;"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="img_portrait" id="img_portrait" accept=".png, .jpg, .jpeg, .svg" /> 
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
                                        <label class="col-3 col-form-label">Open NewTab</label>
                                        <div class="col-9 col-form-label">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="checkbox" name="newtab" />
                                                    <span></span>newtab</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Status</label>
                                        <div class="col-9 col-form-label">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" name="status" value="active" />
                                                    <span></span>Active</label>
                                                <label class="radio">
                                                    <input type="radio" name="status" value="inactive" />
                                                    <span></span>Inactive</label>
                                            </div>
                                            <span class="form-text text-muted">Status of this product highlight</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" id="btn_create_highlight" class="btn btn-success mr-2">Submit</button>
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
    <script src="{{ url('/assets/js/pages/crud/file-upload/image-input.js') }}"></script>
    <script src="{{ url('/custom/admin/highlight.js') }}" type="application/javascript" ></script>
    <script>
        document.getElementById("video")
            .onchange = function(event) {
            let file = event.target.files[0];
            let blobURL = URL.createObjectURL(file);
            document.querySelector("iframe").src = blobURL;
        }
        
        function highlightOption(value) {
            if (value === 1) {
                document.getElementById("deepLink").style.display = "flex";
                document.getElementById("customVideo").style.display = "none";
            } else if (value === 2) {
                document.getElementById("deepLink").style.display = "none";
                document.getElementById("customVideo").style.display = "flex";
            }
            else {
                document.getElementById("deepLink").style.display = "none";
                document.getElementById("customVideo").style.display = "none";
                document.getElementById("deep_link").value = "";
            }
        }
    </script>
@endsection

