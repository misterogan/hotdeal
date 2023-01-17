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
                            <h3 class="card-title">Privacy Policy</h3>
                        </div>
                        <!--begin::Form-->
                            <form class="form" id="create_privacy_form"  enctype="multipart/form-data" >
                                @csrf
                            <div class="card-body">
                                <div class="mb-15">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Title</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="title" id="title" class="form-control" placeholder="Enter Title" />
                                            <span class="form-text text-muted">Please enter title</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Description</label>
                                        <div class="col-lg-9">
                                            {{-- <textarea id="description" name="description" class="form-control kt_docs_tinymce_hidden"></textarea> --}}
                                            <textarea id="description" name="description" class="form-control summernote"></textarea>
                                            <span class="form-text text-muted">Please enter description</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Status</label>
                                        <div class="col-9 col-form-label">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" name="status" value="active" checked/>
                                                    <span></span>Active</label>
                                                <label class="radio">
                                                    <input type="radio" name="status" value="inactive" />
                                                    <span></span>Inactive</label>
                                            </div>
                                            <span class="form-text text-muted">Status of the Privacy Policy</span>
                                        </div>
                                    </div>
                            </div>

                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" id="btn_create_faq" class="btn btn-success mr-2">Submit</button>
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
    <script>
    //     tinymce.init({
    //     selector: ".kt_docs_tinymce_hidden",
    //     menubar: false,
    //     toolbar: ["styleselect fontselect fontsizeselect",
    //         "undo redo | cut copy paste | bold italic | link image | alignleft aligncenter alignright alignjustify",
    //         "bullist numlist | outdent indent | blockquote subscript superscript | advlist | autolink | lists charmap | print preview |  code"],
    //     plugins : "advlist autolink link image lists charmap print preview code"
    // });
    $(document).ready(function() {
        $('.summernote').summernote();
    });
    </script>
    <script src="{{ url('/assets/js/pages/crud/file-upload/image-input.js') }}"></script>
    <script src="{{ url('/custom/admin/privacy.js') }}" type="application/javascript" ></script>
@endsection

