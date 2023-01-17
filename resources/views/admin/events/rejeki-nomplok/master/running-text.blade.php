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
                            <h3 class="card-title">Rejeki Nomplok Running Text</h3>
                        </div>
                        <br>
                        <!--begin::Form-->
                        <form class="form" id="rejeki_nomplok_running_text"  enctype="multipart/form-data" style="margin-left: 50px;">
                            
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Running Text</label>
                                <div class="col-lg-6">
                                    <input type="text" name="text" id="text" class="form-control" placeholder="Enter Running Text" value="{{ $text->text }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-3 col-form-label">Status</label>
                                <div class="col-9 col-form-label">
                                    <div class="radio-inline">
                                        <label class="radio">
                                            <input type="radio" name="status" value="active" {{ $text->status == 'active' ? 'checked' : '' }}/>
                                            <span></span>Active</label>
                                        <label class="radio">
                                            <input type="radio" name="status" value="inactive" {{ $text->status == 'inactive' ? 'checked' : '' }}/>
                                            <span></span>Inactive</label>
                                    </div>
                                    <span class="form-text text-muted">Status of the Running Text</span>
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
    <script src="{{url('/custom/admin/rejeki-nomplok/master.js')}}" type="application/javascript" ></script>
@endsection
