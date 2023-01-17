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
                            <h3 class="card-title">Administrators</h3>
                        </div>
                        <!--begin::Form-->
                            <form class="form" id="create_administrator_form"  enctype="multipart/form-data" >
                                @csrf
                            <div class="card-body">
                                <div class="mb-15">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Name</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name" />
                                            <span class="form-text text-muted">Please enter name</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Email</label>
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" name="email" id="email" placeholder="Enter Email" />
                                            <span class="form-text text-muted">Please enter email</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Password</label>
                                        <div class="col-lg-6">
                                            <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" />
                                            <span class="form-text text-muted">Please enter password</span>
                                        </div>
                                    </div>

                            </div>

                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" id="btn_create_administrator" class="btn btn-success mr-2">Submit</button>
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
    <script src="{{ url('/custom/admin/administrator.js') }}" type="application/javascript" ></script>
@endsection

