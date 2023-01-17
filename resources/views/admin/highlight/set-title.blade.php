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
                        <h3 class="card-title">Highlight Form Set Title</h3>
                        <div class="card-toolbar">
                            <div class="example-tools justify-content-center">
                                <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                                <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="form" id="set_highlight_title"  enctype="multipart/form-data" >
                        <div class="card-body">
                            <div class="mb-15">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Below Flash Sale</label>
                                    <div class="col-lg-6">
                                        <span class="option-body">
                                            <img alt="option2" src="img/highlight_2.png" style="max-width: 100%; max-height: 100%;"/>
                                        </span>
                                        <input type="text" name="title_1" class="form-control mt-4" value="{{ !$section_1 ? 'Rekomendasi Untukmu' : $section_1->title }}" />
                                        <span class="form-text text-muted">Highlight title below flash sale</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-15">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Below Section 1</label>
                                    <div class="col-lg-6">
                                        <span class="option-body">
                                            <img alt="option2" src="img/highlight_3.png" style="max-width: 100%; max-height: 100%;"/>
                                        </span>
                                        <input type="text" name="title_2" class="form-control mt-4" value="{{ !$section_2 ? 'Produk Terlaris' : $section_2->title }}" />
                                        <span class="form-text text-muted">Highlight title below section 1</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-15">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Above Footer</label>
                                    <div class="col-lg-6">
                                        <span class="option-body">
                                            <img alt="option2" src="img/highlight_4.png" style="max-width: 100%; max-height: 100%;"/>
                                        </span>
                                        <input type="text" name="title_3" class="form-control mt-4" value="{{ !$section_3 ? 'Spesial Untukmu' : $section_3->title }}" />
                                        <span class="form-text text-muted">Highlight title above footer</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-15">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Special Event</label>
                                    <div class="col-lg-6">
                                        {{-- <span class="option-body">
                                            <img alt="option2" src="img/highlight_4.png" style="max-width: 100%; max-height: 100%;"/>
                                        </span> --}}
                                        <input type="text" name="title_3" class="form-control mt-4" value="{{ !$section_4 ? 'Special Event' : $section_4->title }}" />
                                        <span class="form-text text-muted">Highlight Special Event</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-3"></div>
                                <div class="col-lg-6">
                                    <button type="submit" id="btn_create_discount" class="btn btn-success mr-2">Submit</button>
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
    <script src="{{url('/custom/admin/highlight.js')}}" type="application/javascript" ></script>
@endsection
