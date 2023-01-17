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
                            <h3 class="card-title">User Form</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">
                                    <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                                    <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                                </div>
                            </div>
                        </div>
                        <!--begin::Form-->
                        <form class="form" id="edit_user_form"  enctype="multipart/form-data" >
                            <input type="hidden" name="user_id" value="{{ $user_id }}" />
                            <div class="card-body">
                                <div class="mb-15">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">User Name:</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter User Name" value="{{ $user->name }}" disabled/>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">User Email:</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="email" id="email" class="form-control" placeholder="Enter User Email" value="{{ $user->email }}" disabled/>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Registration Source:</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="registration_source" id="registration_source" class="form-control" placeholder="Registration Source" value="{{ $user->registration_source }}" disabled/>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Email Verified At:</label>
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" name="email_verification_at" id="email_verification_at" placeholder="Email Verified At" value="{{ $user->email_verified_at }}" disabled />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">User Image</label>
                                        <div class="col-lg-6">
                                            <div class="image-input image-input-outline" id="kt_image_1">
                                                <div class="image-input-wrapper" style="background-image: url({{ $user->image }}"></div>
                                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Phone: </label>
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" name="phone" id="phone" placeholder="User Phone" value="{{ $user->phone }}" disabled />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Address: </label>
                                        <div class="col-lg-6">
                                            <textarea type="text" class="form-control" name="address" id="address" style="resize: none" placeholder="User Address" disabled>{{ isset($user->user_addresses[0]) ? $user->user_addresses[0]['address'] : '' }}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Postal Code: </label>
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" name="postal_code" id="postal_code" placeholder="Postal Code" value="{{ isset($user->user_addresses[0]) ? $user->user_addresses[0]['zip_code'] : '' }}" disabled />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">DOB: </label>
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" name="dob" id="dob" placeholder="DOB" value="{{ $user->dob }}" disabled />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Gender: </label>
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" name="gender" id="gender" placeholder="Gender" value="{{ $user->gender }}" disabled />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Is Vendor?: </label>
                                        <div class="col-lg-6">
                                            <input type="checkbox" class="form-control" name="is_vendor" id="is_vendor" {{ $user->is_vendor ? "checked" : "" }} disabled />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Status</label>
                                        <div class="col-lg-6">
                                            <div class="radio-inline">
                                                <label class="radio radio-solid">
                                                    <input type="radio" name="status" {{ $user->status == "active" ? "checked" : "" }} value="active">
                                                    <span></span>Active</label>
                                                <label class="radio radio-solid">
                                                    <input type="radio" name="status" {{ $user->status == "inactive" ? "checked" : "" }} value="inactive">
                                                    <span></span>Inactive</label>
                                                <label class="radio radio-solid">
                                                    <input type="radio" name="status" {{ $user->status == "ban3" ? "checked" : "" }} value="ban3">
                                                    <span></span>Ban 3 days</label>
                                                <label class="radio radio-solid">
                                                    <input type="radio" name="status" {{ $user->status == "ban7" ? "checked" : "" }} value="ban7">
                                                    <span></span>Ban 7 days</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Registered At</label>
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" name="created_at" id="created_at" value="{{ $user->created_at }}" disabled />
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" id="btn_edit_user" class="btn btn-success mr-2">Submit</button>
                                        <button type="reset" id="btn_cancel" class="btn btn-secondary">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Card-->

                    <div class="card card-custom gutter-b">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="card-label">History</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <!--begin::Example-->
                            <div class="example">

                                <div class="example-preview">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home">
                                                <span class="nav-icon">
                                                    <i class="flaticon2-chat-1"></i>
                                                </span>
                                                <span class="nav-text">Transaction</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" aria-controls="profile">
                                                <span class="nav-icon">
                                                    <i class="flaticon2-layers-1"></i>
                                                </span>
                                                <span class="nav-text">Point</span>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" aria-controls="contact">
                                                <span class="nav-icon">
                                                    <i class="flaticon2-rocket-1"></i>
                                                </span>
                                                <span class="nav-text">Rejeki Nomplok</span>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="activity-tab" data-toggle="tab" href="#activity" aria-controls="activity">
                                                <span class="nav-icon">
                                                    <i class="flaticon2-bell-1"></i>
                                                </span>
                                                <span class="nav-text">Activity</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content mt-5" id="myTabContent">
                                        <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                                            <table class="table table-bordered table-checkable" id="kt_datatable">
                                                <thead>
                                                <tr>
                                                    <th>Transaction Number</th>
                                                    <th>Total Payment</th>
                                                    <th>Order Date</th>
                                                    <th>Status</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($orders as $order)
                                                <tr>
                                                    <td>{{$order->transaction_number}}</td>
                                                    <td>{{$order->total_payment}}</td>
                                                    <td>{{$order->created_at}}</td>
                                                    <td>{{$order->status}}</td>
                                                </tr>
                                                @endforeach

                                                </tbody>
                                            </table>

                                        </div>
                                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                            <table class="table table-bordered table-checkable" id="">
                                                <thead>
                                                <tr>
                                                    <th>Type</th>
                                                    <th>Value</th>
                                                    <th>Order Date</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($hotpoints as $hotpoint)
                                                    <tr>
                                                        <td>{{$hotpoint->type}}</td>
                                                        <td>{{$hotpoint->value}}</td>
                                                        <td>{{$hotpoint->created_at}}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                            <table class="table table-bordered table-checkable" id="">
                                                <thead>
                                                <tr>
                                                    <th>Rejeki Nomplok Week</th>
                                                    <th>Product ID</th>
                                                    <th>Coupon Number</th>
                                                    <th>Status</th>
                                                    <th>Created At</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($rejeki_nomplok as $rn)
                                                    <tr>
                                                        <td>{{$rn->week}}</td>
                                                        <td>{{$rn->name}}</td>
                                                        <td>{{$rn->coupon_number}}</td>
                                                        <td>{{$rn->status}}</td>
                                                        <td>{{$rn->created_at}}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>

                                        </div>
                                        <div class="tab-pane fade" id="activity" role="tabpanel" aria-labelledby="activity-tab">
                                            <table class="table table-bordered table-checkable" id="">
                                                <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Activity</th>
                                                    <th>Platform</th>
                                                    <th>Browser</th>
                                                    <th>IP Address</th>
                                                    <th>UTM Id</th>
                                                    <th>UTM Source</th>
                                                    <th>UTM Campaign</th>
                                                    <th>UTM Term</th>
                                                    <th>UTM Medium</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($user_activity as $activity)
                                                    <tr>
                                                        <td>{{ $activity->created_at }}</td>
                                                        <td>{{ $activity->activity }}</td>
                                                        <td>{{ $activity->platform }}</td>
                                                        <td>{{ $activity->browser }}</td>
                                                        <td>{{ $activity->ip_address }}</td>
                                                        <td>{{ $activity->utm_id == 'undefined' ? '' : $activity->utm_id }}</td>
                                                        <td>{{ $activity->utm_source  == 'undefined' ? '' : $activity->utm_source }}</td>
                                                        <td>{{ $activity->utm_campaign  == 'undefined' ? '' : $activity->utm_campaign }}</td>
                                                        <td>{{ $activity->utm_term == 'undefined' ? '' : $activity->utm_term }}</td>
                                                        <td>{{ $activity->utm_medium == 'undefined' ? '' : $activity->utm_medium }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Example-->
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!--end::Entry-->
@endsection
@section('js')
    <script src="{{ url('/assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ url('/assets/plugins/custom/prismjs/prismjs.bundle.js') }}"></script>
    <script src="{{ url('/assets/js/scripts.bundle.js') }}"></script>
    <script src="{{ url('/assets/js/pages/crud/file-upload/image-input.js') }}"></script>
    <script src="{{ url('/assets/js/pages/crud/forms/widgets/select2.js') }}"></script>
    <script src="{{ url('/custom/admin/user.js') }}" type="application/javascript" ></script>
@endsection

