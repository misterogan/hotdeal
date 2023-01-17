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
                            <h3 class="card-title">Promotion Banner</h3>
                        </div>
                        <br>
                        <!--begin::Form-->
                        <form class="form" id="promotion_banner"  enctype="multipart/form-data" style="margin-left: 50px;">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Banner</label>
                                <div class="col-lg-6">
                                    <div class="image-input image-input-outline" id="kt_image_12">
                                        <div class="image-input-wrapper" style="background-image: url({{ $banner->banner ? $banner->banner : 'assets/media/misc/img-placeholder-landscape.png' }}); width: 700px; height: 250px;"></div>
                                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                            <i class="fa fa-pen icon-sm text-muted"></i>
                                            <input type="file" name="banner" id="banner" accept=".png, .jpg, .jpeg, .svg" />
                                            <input type="hidden" name="profile_avatar_remove" />
                                        </label>
                                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                                        </span>
                                        @if($banner->banner != '')
                                            <span class="form-text delete_banner float-right" style="display: inline;" onclick="" data-id="{{ $banner->id ?? '' }}">hapus</span>
                                        @endif
                                    </div>
                                    <span class="form-text text-muted">Allowed file types: png, jpg, jpeg.</span>
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" id="btn_rejeki_nomplok_banner" class="btn btn-success mr-2">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ url('/assets/js/pages/crud/file-upload/image-input.js') }}"></script>
    <script src="{{url('/custom/admin/promotion.js')}}" type="application/javascript" ></script>
    <script>
        $(".delete_banner").on("click", function (event) {
            var span = $(this)
            var id = span.data('id')
            var token = $('meta[name="csrf-token"]').attr('content');
            // let id = $(this).val();
            $.ajax(
                {
                    headers: { 'X-CSRF-TOKEN': token },
                    method: "POST",
                    url: '/admin/promotion/delete-banner',
                    data: {
                        data_id : id,
                        _token : token
                    }
                }
            ).then(function() {
                location.reload();
                $('')
            });
        });
    </script>
@endsection
