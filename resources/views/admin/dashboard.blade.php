@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-xl-8">
        <!--begin::Mixed Widget 13-->
        <div class="card card-custom gutter-b card-stretch">
            <!--begin::Beader-->
            <div class="card-header border-0 py-5">
                <h3 class="card-title font-weight-bolder">Sales</h3>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body p-0 d-flex flex-column">
                <!--begin::Stats-->
                <div class="card-spacer pt-5 bg-white flex-grow-1">
                    <!--begin::Row-->
                    <div class="row row-paddingless">
                        <div class="col mr-8">
                            <div class="font-size-sm text-muted font-weight-bold">Today Sales</div>
                            <div class="font-size-h4 font-weight-bolder">Rp. {{number_format($transaction_today)}}</div>
                        </div>
                        <div class="col">
                            <div class="font-size-sm text-muted font-weight-bold">Sales this month</div>
                            <div class="font-size-h4 font-weight-bolder">Rp. {{ number_format( array_key_exists(date('M') , $sales_yoy) ?  $sales_yoy[date('M')] : 0 )}}</div>
                        </div>
                    </div>
                    <!--end::Row-->
                    <!--begin::Row-->
                    <!-- <div class="row row-paddingless mt-8">
                        <div class="col mr-8">
                            <div class="font-size-sm text-muted font-weight-bold">Annual Taxes 2019</div>
                            <div class="font-size-h4 font-weight-bolder">$29,004</div>
                        </div>
                        <div class="col">
                            <div class="font-size-sm text-muted font-weight-bold">Annual Income</div>
                            <div class="font-size-h4 font-weight-bolder">$1,480,00</div>
                        </div>
                    </div> -->
                    <!--end::Row-->
                </div>
                <!--end::Stats-->
                <!--begin::Chart-->
                <div id="sales_yoy_graph" class="card-rounded-bottom" style="height: 200px"></div>
                <!--end::Chart-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Mixed Widget 13-->
    </div>
    <div class="col-xl-4">
        <div class="card card-custom card-stretch card-stretch-half gutter-b">
            <!--begin::Body-->
            <div class="card-body p-0">
                <div class="d-flex align-items-center justify-content-between card-spacer flex-grow-1">
                    <span class="symbol symbol-50 symbol-light-primary mr-2">
                        <span class="symbol-label">
                            <span class="svg-icon svg-icon-xl svg-icon-primary">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Group.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24" />
                                        <path d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        <path d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                        </span>
                    </span>
                    <div class="d-flex flex-column text-right">
                        <span class="text-dark-75 font-weight-bolder font-size-h3">+ {{$new_registered}}</span>
                        <span class="text-muted font-weight-bold mt-2">New Users / weekly</span>
                    </div>
                </div>
                <div id="_monthly_user" class="card-rounded-bottom" data-color="primary" style="height: 150px"></div>
            </div>
            <!--end::Body-->
        </div>
        <div class="card card-custom card-stretch card-stretch-half gutter-b">
            <!--begin::Body-->
            <div class="card-body p-0">
                <div class="d-flex align-items-center justify-content-between card-spacer flex-grow-1">
                    <span class="symbol symbol-50 symbol-light-primary mr-2">
                        <span class="symbol-label">
                            <span class="svg-icon svg-icon-xl svg-icon-primary">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Group.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24" />
                                        <path d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        <path d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                        </span>
                    </span>
                    <div class="d-flex flex-column text-right">
                        <span class="text-dark-75 font-weight-bolder font-size-h3">{{$total_users}}</span>
                        <span class="text-muted font-weight-bold mt-2">All User</span>
                    </div>
                </div>
                <div id="_all_user" class="card-rounded-bottom" data-color="primary" style="height: 300px"></div>
            </div>
            <!--end::Body-->
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <!--begin::Charts Widget 6-->
        <div class="card card-custom card-stretch gutter-b">
            <!--begin::Header-->
            <div class="card-header h-auto border-0">
                <div class="card-title py-5">
                    <h3 class="card-label">
                        <span class="d-block text-dark font-weight-bolder">Recent Orders (Dummi Data)</span>
                        <span class="d-block text-muted mt-2 font-size-sm">More than 500+ new orders</span>
                    </h3>
                </div>
                <div class="card-toolbar">
                    <span class="mr-5 d-flex align-items-center font-weight-bold">
                    <i class="label label-dot label-xl label-primary mr-2"></i>Sales</span>
                    <span class="d-flex align-items-center font-weight-bold">
                    <i class="label label-dot label-xl label-info mr-2"></i>Authors</span>
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body">
                <div class="row">
                    <div class="col-4 d-flex flex-column">
                        <!--begin::Block-->
                        <div class="bg-light-warning p-8 rounded-xl flex-grow-1">
                            <!--begin::Item-->
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-circle symbol-white symbol-30 flex-shrink-0 mr-3">
                                    <div class="symbol-label">
                                        <span class="svg-icon svg-icon-md svg-icon-danger">
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Shopping/Cart3.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <path d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                    <path d="M3.5,9 L20.5,9 C21.0522847,9 21.5,9.44771525 21.5,10 C21.5,10.132026 21.4738562,10.2627452 21.4230769,10.3846154 L17.7692308,19.1538462 C17.3034221,20.271787 16.2111026,21 15,21 L9,21 C7.78889745,21 6.6965779,20.271787 6.23076923,19.1538462 L2.57692308,10.3846154 C2.36450587,9.87481408 2.60558331,9.28934029 3.11538462,9.07692308 C3.23725479,9.02614384 3.36797398,9 3.5,9 Z M12,17 C13.1045695,17 14,16.1045695 14,15 C14,13.8954305 13.1045695,13 12,13 C10.8954305,13 10,13.8954305 10,15 C10,16.1045695 10.8954305,17 12,17 Z" fill="#000000" />
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-size-sm font-weight-bold">$2800</div>
                                    <div class="font-size-sm text-muted">Weekly CoreAd Sales</div>
                                </div>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-circle symbol-white symbol-30 flex-shrink-0 mr-3">
                                    <div class="symbol-label">
                                        <span class="svg-icon svg-icon-md svg-icon-info">
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
                                                    <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519)" x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-size-sm font-weight-bold">490</div>
                                    <div class="font-size-sm text-muted">Manuals Added</div>
                                </div>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-circle symbol-white symbol-30 flex-shrink-0 mr-3">
                                    <div class="symbol-label">
                                        <span class="svg-icon svg-icon-md svg-icon-warning">
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flip-vertical.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <path d="M9.07117914,12.5710461 L13.8326627,12.5710461 C14.108805,12.5710461 14.3326627,12.3471885 14.3326627,12.0710461 L14.3326627,0.16733734 C14.3326627,-0.108805035 14.108805,-0.33266266 13.8326627,-0.33266266 C13.6282104,-0.33266266 13.444356,-0.208187188 13.3684243,-0.0183579985 L8.6069408,11.8853508 C8.50438409,12.1417426 8.62909204,12.4327278 8.8854838,12.5352845 C8.94454394,12.5589085 9.00756943,12.5710461 9.07117914,12.5710461 Z" fill="#000000" opacity="0.3" transform="translate(11.451854, 6.119192) rotate(-270.000000) translate(-11.451854, -6.119192)" />
                                                    <path d="M9.23851648,24.5 L14,24.5 C14.2761424,24.5 14.5,24.2761424 14.5,24 L14.5,12.0962912 C14.5,11.8201488 14.2761424,11.5962912 14,11.5962912 C13.7955477,11.5962912 13.6116933,11.7207667 13.5357617,11.9105959 L8.77427814,23.8143047 C8.67172143,24.0706964 8.79642938,24.3616816 9.05282114,24.4642383 C9.11188128,24.4878624 9.17490677,24.5 9.23851648,24.5 Z" fill="#000000" transform="translate(11.500000, 18.000000) scale(1, -1) rotate(-270.000000) translate(-11.500000, -18.000000)" />
                                                    <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000)" x="11" y="2" width="2" height="20" rx="1" />
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-size-sm font-weight-bold">34,300</div>
                                    <div class="font-size-sm text-muted">Emails Received</div>
                                </div>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-circle symbol-white symbol-30 flex-shrink-0 mr-3">
                                    <div class="symbol-label">
                                        <span class="svg-icon svg-icon-md svg-icon-success">
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Cupboard.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <path d="M3.5,3 L9.5,3 C10.3284271,3 11,3.67157288 11,4.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L3.5,20 C2.67157288,20 2,19.3284271 2,18.5 L2,4.5 C2,3.67157288 2.67157288,3 3.5,3 Z M9,9 C8.44771525,9 8,9.44771525 8,10 L8,12 C8,12.5522847 8.44771525,13 9,13 C9.55228475,13 10,12.5522847 10,12 L10,10 C10,9.44771525 9.55228475,9 9,9 Z" fill="#000000" opacity="0.3" />
                                                    <path d="M14.5,3 L20.5,3 C21.3284271,3 22,3.67157288 22,4.5 L22,18.5 C22,19.3284271 21.3284271,20 20.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,4.5 C13,3.67157288 13.6715729,3 14.5,3 Z M20,9 C19.4477153,9 19,9.44771525 19,10 L19,12 C19,12.5522847 19.4477153,13 20,13 C20.5522847,13 21,12.5522847 21,12 L21,10 C21,9.44771525 20.5522847,9 20,9 Z" fill="#000000" transform="translate(17.500000, 11.500000) scale(-1, 1) translate(-17.500000, -11.500000)" />
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-size-sm font-weight-bold">550</div>
                                    <div class="font-size-sm text-muted">Meetups Completed</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-circle symbol-white symbol-30 flex-shrink-0 mr-3">
                                    <div class="symbol-label">
                                        <span class="svg-icon svg-icon-md svg-icon-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <rect fill="#000000" opacity="0.3" x="13" y="4" width="3" height="16" rx="1.5" />
                                                    <rect fill="#000000" x="8" y="9" width="3" height="11" rx="1.5" />
                                                    <rect fill="#000000" x="18" y="11" width="3" height="9" rx="1.5" />
                                                    <rect fill="#000000" x="3" y="13" width="3" height="7" rx="1.5" />
                                                </g>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-size-sm font-weight-bold">$346,000</div>
                                    <div class="font-size-sm text-muted">Total Author Sales</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-circle symbol-white symbol-30 flex-shrink-0 mr-3">
                                    <div class="symbol-label">
                                        <span class="svg-icon svg-icon-md svg-icon-danger">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <path d="M7.825,10.225 C7.2,9.475 6.85,8.4 6.85,7.375 C6.85,4.55 9.15,2.05 12.35,2.05 C15.45,2.05 17.8,4.45 17.875,7.425 L15.075,7.425 C15.075,5.85 13.975,4.6 12.35,4.6 C10.75,4.6 9.6,5.775 9.6,7.375 C9.6,8.26626781 10.0162926,9.06146809 10.6676674,9.58392078 C10.7130614,9.62033024 10.7238389,12.2340233 10.7,17.425 L17.5444449,17.425 C17.8205873,17.425 18.0444449,17.6488576 18.0444449,17.925 C18.0444449,17.9869142 18.0329457,18.0482899 18.0105321,18.1060047 L17.3988817,19.6810047 C17.3242018,19.8733052 17.1390868,20 16.9327944,20 L6.3,20 C6.02385763,20 5.8,19.7761424 5.8,19.5 L5.8,17.925 C5.8,17.6488576 6.02385763,17.425 6.3,17.425 L7.925,17.425 L7.925,12.475 L7.825,10.225 Z" fill="#000000" />
                                                    <path d="M4.3618034,11.2763932 L4.8618034,10.2763932 C4.94649941,10.1070012 5.11963097,10 5.30901699,10 L15.190983,10 C15.4671254,10 15.690983,10.2238576 15.690983,10.5 C15.690983,10.5776225 15.6729105,10.6541791 15.6381966,10.7236068 L15.1381966,11.7236068 C15.0535006,11.8929988 14.880369,12 14.690983,12 L4.80901699,12 C4.53287462,12 4.30901699,11.7761424 4.30901699,11.5 C4.30901699,11.4223775 4.32708954,11.3458209 4.3618034,11.2763932 Z" fill="#000000" opacity="0.3" />
                                                </g>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-size-sm font-weight-bold">560</div>
                                    <div class="font-size-sm text-muted">Total Transactions</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div id="kt_charts_widget_6_chart" class="chart-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
    <script src="{{url('/assets/js/pages/widgets.js')}}"></script>
    <script>
        var sales_yoy = JSON.parse('<?php echo json_encode($sales_yoy);?>');
        var nru_dau = JSON.parse('<?php echo json_encode($sales_yoy);?>');
    </script>
@endsection

