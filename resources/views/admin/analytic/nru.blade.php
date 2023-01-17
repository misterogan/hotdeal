@extends('layouts.app')
@section('content')
    
        <div class="card">
            <div class=" card-xl-stretch mb-xl-8">
                <div class="card-body">
                    <div class="m-portlet">
                        <div class="m-portlet__body  m-portlet__body--no-padding">
                            <div class="row m-row--no-padding m-row--col-separator-xl">
                                <div class="col-xl-4">
                                    <!--begin:: Widgets/Daily Sales-->
                                    <div class="m-widget14">
                                        <div class="m-widget14__header m--margin-bottom-30">
                                            <h3 class="m-widget14__title">
                                               Phone 
                                            </h3>
                                            <span class="m-widget14__desc">
                                               User with phone verified 
                                            </span>
                                        </div>
                                        <div class="m-widget14__chart">
                                            <div id="phone" class="m-widget14__chart">
                                            </div>
                                        </div>
                                    </div>
                                    <!--end:: Widgets/Daily Sales-->
                                </div>
                                <div class="col-xl-4">
                                    <!--begin:: Widgets/Daily Sales-->
                                    <div class="m-widget14">
                                        <div class="m-widget14__header m--margin-bottom-30">
                                            <h3 class="m-widget14__title">
                                                Status User
                                            </h3>
                                            <span class="m-widget14__desc">
                                               All User status
                                            </span>
                                        </div>
                                        <div class="m-widget14__chart">
                                            <div id="status" class="m-widget14__chart">
                                            </div>
                                        </div>
                                    </div>
                                    <!--end:: Widgets/Daily Sales-->
                                </div>
                                <div class="col-xl-4">
                                    <!--begin:: Widgets/Profit Share-->
                                    <div class="m-widget14">
                                        <div class="m-widget14__header">
                                            <h3 class="m-widget14__title">
                                               Registration
                                            </h3>
                                            <span class="m-widget14__desc">
                                                User that logins by Facebook, Gmail , Email
                                            </span>
                                        </div>
                                        <div class="row  align-items-center">
                                            <div class="col">
                                                <div id="source" class="m-widget14__chart">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end:: Widgets/Profit Share-->
                                </div>
                            </div>
                        </div>

                        
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class=" card-xl-stretch mb-xl-8">
                <div class="card-body">
                    <div class="m-portlet">
                        <div class="m-portlet__body  m-portlet__body--no-padding">
                            <div class="row m-row--no-padding m-row--col-separator-xl">
                                <div class="row">
                                    <div class="m-widget14__header m--margin-bottom-30">
                                            <h3 class="m-widget14__title">
                                                DAU / NRU 
                                            </h3>
                                            
                                        </div>
                                    
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="event_period">Period</label>
                                            <input type="date" class="form-control" name="date_from" id="date_from">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="event_period">&nbsp;</label>
                                            <input type="date" class="form-control" name="date_until" id="date_until">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="event_period">&nbsp;</label>
                                            <button class="btn btn-primary btn-block" type="submit" onclick="_nru_chart()">Search</button>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="event_period">&nbsp;</label>
                                            <button id="download" class="btn btn-info btn-block">Download</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="m-widget14">
                               <div class="chart-container"></div>
                            </div> --}}
                            <div id="_nru_chart" class="card-rounded-bottom" data-color="primary" style="height: 500px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection
<style>
    .wrapper-header {
        padding-top: 65px !important;
    }
</style>
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{url('/custom/admin/analytic/nru.js')}}" type="application/javascript" ></script>
    <script src="{{url('/custom/admin/dashboard.js')}}" type="application/javascript" ></script>
    <script>
        $('#download').click(function(){
            var date_from = $('#date_from').val();
            var date_until = $('#date_until').val();
            if(!date_from || !date_until){
                alert('Tanggal tidak boleh kosong!');
                return false
            }
            var link = '/admin/analytic/user-bio/download/'+date_from+'-'+date_until;
            window.location.href = link;
        })
    </script>
    <script>
            var data = JSON.parse('<?php echo $datas;?>');
            var options1 = {
                series:Object.values(data.phone.data),
                chart: {
                    type: 'donut',
                },
                colors: data.phone.colour,
                labels: ['unverified','verified'],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                        width: 200
                        },
                        legend: {
                        position: 'bottom'
                        }
                    }
                }]
            };

            var chart1 = new ApexCharts(document.querySelector("#phone"), options1);
            chart1.render();

            var source_option = {
                series:Object.values(data.source.data),
                chart: {
                    type: 'donut',
                },
                colors: data.source.colour,
                labels: Object.keys(data.source.data),
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                        width: 200
                        },
                        legend: {
                        position: 'bottom'
                        }
                    }
                }]
            };
            var chart = new ApexCharts(document.querySelector("#source"), source_option);
            chart.render();


            var source_option = {
                series:Object.values(data.status.data),
                chart: {
                    type: 'donut',
                },
                colors: data.status.colour,
                labels: Object.keys(data.status.data),
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                        width: 200
                        },
                        legend: {
                        position: 'bottom'
                        }
                    }
                }]
            };
            var chart = new ApexCharts(document.querySelector("#status"), source_option);
            chart.render();
    </script>
@endsection

