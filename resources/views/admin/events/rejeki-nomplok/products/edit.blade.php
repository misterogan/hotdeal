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
                            <h3 class="card-title">Rejeki Nomplok
                            <span class="d-block text-muted pt-2 font-size-sm">Edit</span></h3>
                        </div>
                        <!--begin::Form-->
                            <form class="form" id="edit_product_form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $rejeki_id }}">
                            <div class="card-body">
                                <div class="mb-15">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Product</label>
                                        <div class="col-lg-6">
                                            <select class="form-control select2" id="kt_select2_11" name="product_id">
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" {{ $product->id == $rejeki->product_id ? 'selected' : '' }}>{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="form-text text-muted">Please select product</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Status</label>
                                        <div class="col-9 col-form-label">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" name="status" value="active" {{ $rejeki->status == 'active' ? 'checked' : '' }}/>
                                                    <span></span>Active</label>
                                                <label class="radio">
                                                    <input type="radio" name="status" value="inactive" {{ $rejeki->status == 'inactive' ? 'checked' : '' }}/>
                                                    <span></span>Inactive</label>
                                            </div>
                                            <span class="form-text text-muted">Status of the FAQ</span>
                                        </div>
                                    </div>
                            </div>

                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" id="btn_edit_product" class="btn btn-success mr-2">Submit</button>
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
    <script src="{{ url('/assets/js/pages/crud/forms/widgets/select2.js') }}"></script>
    <script src="{{url('/custom/admin/rejeki-nomplok/products.js')}}" type="application/javascript" ></script>
@endsection

