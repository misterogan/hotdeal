@extends('layouts.app')
@section('content')
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!--begin::Card-->
                    <div class="card card-custom">
                        <div class="card-header">
                            <h3 class="card-title">
                                Invoice Info
                            </h3>
                        </div>

                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Invoice Number:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="invoice_number" id="invoice_number" class="form-control" placeholder="" value="{{ $order->invoice_number }}" disabled/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Invoice Total Payment:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="total_payment" id="invoice_total_payment" class="form-control" placeholder="" value="{{ \App\Helpers\Utils::currency_convert($order->invoice_total_payment) }}" disabled/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Invoice Total Discount:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="total_discount" id="invoice_total_discount" class="form-control" placeholder="" value="{{ \App\Helpers\Utils::currency_convert($order->invoice_total_discount) }}" disabled/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Status:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="status" id="status" class="form-control" placeholder="" value="{{ ucfirst($order->master_status->status_code) }}" disabled/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Created At:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="created_at" id="created_at" class="form-control" placeholder="" value="{{ $order->created_at }}" disabled/>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col-lg-6">
                    <!--begin::Card-->
                    <div class="card card-custom">
                        <div class="card-header">
                            <h3 class="card-title">
                                Buyer Info
                            </h3>
                        </div>
                        <!--begin::Form-->
                        <form>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Buyer Name:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="buyer_name" id="buyer_name" class="form-control" placeholder="" value="{{ $order->order->user->name }}" disabled/>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Buyer Phone No.: </label>
                                    <div class="col-lg-8">
                                        <input type="text" name="buyer_phone_no" id="buyer_phone_no" class="form-control" placeholder="" value="{{ $order->order->user->phone }}" disabled/>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Buyer Address:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="buyer_address" id="buyer_address" class="form-control" placeholder="" value="{{ $order->order->user->user_addresses[0]['address'] ?? '' }}" disabled/>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Courier Shipping:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="logistic_name" id="logistic_name" class="form-control" placeholder="" value="{{ $order->shipping->logistic_name }}" disabled/>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Payment Method:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="created_at" id="created_at" class="form-control" placeholder="" value="{{ $order->payment->bank_code }}" disabled/>
                                    </div>
                                </div>

                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                </div>

                <div class="col-lg-6">
                    <!--begin::Card-->
                    <div class="card card-custom">
                        <div class="card-header">
                            <h3 class="card-title">
                                Vendor Info
                            </h3>
                        </div>
                        <!--begin::Form-->
                        <form>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Vendor Name:</label>
                                    <div class="col-lg-8">
                                        <a href="/admin/vendor/edit/{{ $order->vendor->id }}"><input type="text" name="name" id="name" class="form-control" style="color: blue;" placeholder="" value="{{ $order->vendor->name }}" disabled/></a>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Vendor Description:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="description" id="description" class="form-control" placeholder="" value="{{ $order->vendor->description }}" disabled/>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Vendor Address:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="address" id="address" class="form-control" placeholder="" value="{{ $order->vendor->address }}" disabled/>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Status:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="status" id="status" class="form-control" placeholder="" value="{{ ucfirst($order->vendor->status) }}" disabled/>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Vendor Province:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="province" id="province" class="form-control" placeholder="" value="{{ $order->vendor->province->name }}" disabled/>
                                    </div>
                                </div>


                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-lg-6">
                    <!--begin::Card-->
                    <div class="card card-custom">
                        <div class="card-header">
                            <h3 class="card-title">
                                Account Information
                            </h3>
                        </div>
                        <!--begin::Form-->
                        <form>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Refund Status</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="buyer_name" id="buyer_name" class="form-control" placeholder="" value="{{ $refund->refund_type }}" disabled/>
                                    </div>
                                </div>
                                @if($refund->bank_account)
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">Account Name : </label>
                                        <div class="col-lg-8">
                                            <input type="text" name="buyer_phone_no" id="buyer_phone_no" class="form-control" placeholder="" value="{{ $refund->bank_account->account_name }}" disabled/>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">Account Number:</label>
                                        <div class="col-lg-8">
                                            <input type="text" name="buyer_address" id="buyer_address" class="form-control" placeholder="" value="{{ $refund->bank_account->account_number }}" disabled/>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">Bank Name:</label>
                                        <div class="col-lg-8">
                                            <input type="text" name="logistic_name" id="logistic_name" class="form-control" placeholder="" value="{{ $refund->bank_account->bank_name }}" disabled/>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">Foto KTP:</label>
                                        <div class="col-lg-8">
                                            <div class="image-input image-input-outline" id="kt_image_11">
                                                <div class="image-input-wrapper" style="background-image: url({{ isset($refund->bank_account->identity_image) ? $refund->bank_account->identity_image : 'assets/media/misc/ic_add.png' }});"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                

                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card card-custom card-stretch gutter-b">
                        <!--begin::Header-->
                        <div class="card-header align-items-center border-0 mt-4">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="font-weight-bolder text-dark">Refund Logs</span>
                            </h3>
                        </div>
                        <div class="card-body pt-4">
                            <div class="timeline timeline-6 mt-3">
                                @foreach ($refund->logs as $item)
                                    <div class="timeline-item align-items-start">
                                        <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg"></div>
                                        <div class="timeline-badge">
                                            <i class="fa fa-genderless text-warning icon-xl"></i>
                                        </div>
                                        <div class="font-weight-mormal font-size-lg timeline-content text-muted pl-3">
                                           <span> {{$item->created_at}}</span>
                                           <br>
                                           <span> {{$item->description}}</span>
                                        </div>
                                    </div>
                                @endforeach
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-custom">
                        <div class="card-header">
                            <h3 class="card-title">
                                Invoice Details
                            </h3>
                        </div>

                        <table class="table table-bordered" id="invoiceDetailTable" style="margin: 20px;">
                            <tr>
                                <th>No. </th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                            </tr>
                            @foreach($order->order_products as $op)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $op->product_detail->product->name }}</td>
                                    <td>{{ \App\Helpers\Utils::currency_convert($op->fix_price) }}</td>
                                    <td>{{ $op->quantity }}</td>
                                </tr>
                            @endforeach
                        </table>

                    </div>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-custom">
                        <div class="card-header">
                            <h3 class="card-title">
                                Other Details
                            </h3>
                        </div>

                        <form class="form" id="edit_refund_form">
                            <input type="hidden" id="refund_id" name="refund_id" value="{{ $refund->id }}" >
                            <input type="hidden" id="refund_type" name="refund_type" value="{{ $refund->refund_type }}" >

                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Refund Status: </label>
                                    <div class="col-6 col-form-label">
                                        <select class="form-control select2 refundStatus" id="kt_select2_11" name="refund_status">
                                            @foreach($refund_status as $status)
                                                <option value="{{ $status->id }}" {{ $status->id == $refund->refund_status_id ? "selected" : "" }}>{{ $status->status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row" id="hotpoint_view" style="display: none;">
                                    <label class="col-lg-4 col-form-label">Hotpoint:</label>
                                    <div class="col-lg-6 col-form-label">
                                        <input type="number" name="hotpoint" id="hotpoint" class="form-control" placeholder="Enter Hotpoint Refund" />
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" id="btn_edit_refund" class="btn btn-success mr-2">Submit</button>
                                        <button type="reset" id="btn_cancel" class="btn btn-secondary">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--end::Entry-->
@endsection
@section('js')
    <script src="{{ url('/assets/js/pages/crud/forms/widgets/select2.js') }}"></script>
    <script src="{{ url('/custom/admin/refund.js') }}" type="application/javascript" ></script>
@endsection

