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
                            <h3 class="card-title">Rejeki Nomplok (edit)</h3>
                        </div>
                        <!--begin::Form-->
                            <form class="form" id="edit_master_form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $master_id }}">
                            <input type="hidden" name="week" value="{{ $master->week  }}">
                            <input type="hidden" id="coupon_winner"  value="{{ $coupon_winner  }}">
                            <div class="card-body">
                                <div class="mb-15">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Week</label>
                                        <div class="col-lg-6">
                                            <input type="text"  id="week" class="form-control" disabled placeholder="Enter Week" value="{{ $master->week }}"/>
                                            <span class="form-text text-muted">Please enter week</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Start Date</label>
                                        <div class="col-lg-6">
                                            <div class="input-group date" id="kt_datetimepicker_1" data-target-input="nearest">
                                                <input type="text" disabled class="form-control datetimepicker-input" name="start_date" id="start_date" placeholder="Select date &amp; time" data-target="#kt_datetimepicker_1" value="{{ \App\Helpers\Utils::date_to_picker($master->start_date) }}" {{ $master->ihsg != null ? 'disabled' : '' }}/>
                                                <div class="input-group-append" data-target="#kt_datetimepicker_1" data-toggle="datetimepicker">
                                                    <span class="input-group-text">
                                                        <i class="ki ki-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">End Date</label>
                                        <div class="col-lg-6">
                                            <div class="input-group date" id="kt_datetimepicker_2" data-target-input="nearest">
                                                <input type="text" disabled class="form-control datetimepicker-input" name="end_date" id="end_date" placeholder="Select date &amp; time" data-target="#kt_datetimepicker_2" value="{{ \App\Helpers\Utils::date_to_picker($master->end_date) }}" {{ $master->ihsg != null ? 'disabled' : '' }}/>
                                                <div class="input-group-append" data-target="#kt_datetimepicker_2" data-toggle="datetimepicker">
                                                    <span class="input-group-text">
                                                        <i class="ki ki-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Status</label>
                                        <div class="col-9 col-form-label">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input disabled type="radio" name="status" value="active" {{ $master->status == 'active' ? 'checked' : '' }} {{ $master->ihsg != null ? 'disabled' : '' }}/>
                                                    <span></span>Active</label>
                                                <label class="radio">
                                                    <input disabled type="radio" name="status" value="inactive" {{ $master->status == 'inactive' ? 'checked' : '' }} {{ $master->ihsg != null ? 'disabled' : '' }}/>
                                                    <span></span>Inactive</label>
                                            </div>
                                            <span class="form-text text-muted">Status of the FAQ</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">IHSG</label>
                                        <div class="col-lg-6">
                                            {{-- <input type="number" name="ihsg" id="ihsg" class="form-control" placeholder="Enter IHSG" value="{{ $master->ihsg }}" {{ $master->ihsg != null ? 'disabled' : '' }}/> --}}
                                            <input type="number" name="ihsg" id="ihsg" class="form-control" placeholder="Enter IHSG" value="{{ $master->ihsg }}"/>
                                            <span class="form-text text-muted">Please enter IHSG</span>
                                            <br/>
                                            <input  type="hidden" id="ihsg_copy" />
                                            <button type="button" onclick="check_rejeki_nomplok_winner(false)" class="btn btn-success mr-2 button-check button-check-coupons">Cek Pemenang</button>
                                            @if ($master->ihsg != null)
                                                <button type="button" onclick="check_rejeki_nomplok_winner(true)" class="btn btn-secondary mr-2 button-check-coupons">All Coupons</button>    
                                            @endif
                                        </div>
                                    </div>
                                    @if ($master->ihsg != null )
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Total Pemenang</label>
                                        <div class="col-lg-6">
                                            <h5>{{ $info->total_winner}}</h5>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Total Point</label>
                                        <div class="col-lg-6">
                                            <h5>{{$info->total_payback}}</h5>
                                        </div>
                                    </div>
                                    @endif

                                    @if ($master->ihsg != null)
                                        @if ($info->total_winner > 0)
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Total Point</label>
                                            <div class="col-lg-6">
                                                <button type="button" class="btn btn-primary btn-sm">
                                                    Kirim Semua
                                                </button>
                                            </div>
                                        </div>
                                        @endif
                                    @endif
                            </div>
                            <div class="card-title">
                                <h3 class="card-label">Rejeki Nomplok
                                    <span class="d-block text-muted pt-2 font-size-sm">Coupons </span></h3>
                            </div>
                            <table id="dt_coupons" class="table datatable datatable-bordered datatable-head-custom">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>No.</th>
                                    <th>User</th>
                                    <th>Week</th>
                                    <th>Order Detail</th>
                                    <th>Product</th>
                                    <th>Coupon Number</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Price</th>
                                    <th>action</th>
                                </tr>
                                </thead>
                            </table>
                            <!--end: Datatable-->


                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        {{-- <button type="submit" id="btn_create_master" class="btn btn-success mr-2" {{ $master->ihsg != null ? 'disabled' : '' }}>Proses</button> --}}
                                        <button type="submit" id="btn_create_master" class="btn btn-success mr-2" >Proses</button>
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
    var filter = true;
</script>
    <script src="{{url('/custom/admin/rejeki-nomplok/coupons.js')}}" type="application/javascript" ></script>
@endsection

