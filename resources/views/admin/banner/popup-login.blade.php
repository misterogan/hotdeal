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
                            <h3 class="card-title">Login Popup Banner</h3>
                        </div>
                        <!--begin::Form-->
                            <form class="form" id="login_popup_form"  enctype="multipart/form-data" >
                                @csrf
                            <div class="card-body">
                                <div class="mb-15">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Banner</label>
                                        <div class="col-lg-6">
                                            <div class="image-input image-input-outline" id="kt_image_12">
                                                <div class="image-input-wrapper" style="background-image: url({{ isset($banner->image) ? $banner->image : "assets/media/misc/img-placeholder-landscape.png" }}); width: 300px; height: 350px;"></div>
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
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" id="btn_about" class="btn btn-success mr-2">Submit</button>
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
    <script src="{{ url('/assets/js/pages/crud/file-upload/image-input.js') }}"></script>
    <script type="text/javascript">
        $("#login_popup_form").on("submit", function (event) {
            event.preventDefault();
            var token = $('meta[name="csrf-token"]').attr('content');
            var formData = new FormData(this);
            $.ajax({
                headers: { 'X-CSRF-TOKEN': token },
                type : 'POST',
                data: formData,
                url  : '/admin/banner/login/popup/update',
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
               
                success: function(data){
                    console.log(data);
                    if(data.status === true) {
                        swal.fire({
                            text: data.message,
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn font-weight-bold btn-light-primary"
                            }
                        }).then(function() {
                            location.href = "/admin/banner/login/popup";
                        });
                    }else {
                        var values = '';
                        jQuery.each(data.message, function (key, value) {
                            values += value+"<br>";
                        });

                        swal.fire({
                            html: values,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Error!",
                            customClass: {
                                confirmButton: "btn font-weight-bold btn-light-primary"
                            }
                        }).then(function() { });
                    }
                }
            });
        });
    </script>

    
@endsection

