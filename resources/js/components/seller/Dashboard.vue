<template>
    <div>
        <div class="main-content">
            <div class="center-menu pr-15" >
                <div class="main-menu">
                    <div class="row-100 report">
                        <div class="col-23">
                            <div class="box-report">
                                <h5>belum bayar</h5>
                                <router-link to="/vendor/order/list?filter=awaiting_payment"><h2>{{ dashboard_data.order_unpaid }}</h2></router-link>
                            </div>
                        </div>
                        <div class="col-23">
                            <div class="box-report">
                                <h5>Pesanan perlu diproses</h5>
                                <router-link to="/vendor/order/list?filter=pending"><h2>{{ dashboard_data.order_need_processing }}</h2></router-link>
                            </div>
                        </div>
                        <div class="col-23">
                            <div class="box-report">
                                <h5>Menunggu jadwal penjemputan</h5>
                                <router-link to="/vendor/order/list?filter=processed"><h2>{{ dashboard_data.order_need_pickup }}</h2></router-link>
                            </div>
                        </div>
                        <div class="col-23">
                            <div class="box-report">
                                <h5>Pesanan dalam pengiriman</h5>
                                <router-link to="/vendor/order/list?filter=delivery"><h2>{{ dashboard_data.delivered }}</h2></router-link>
                            </div>
                        </div>
                    </div>
                    <div class="row-100 report">
                        <div class="col-23">
                            <div class="box-report">
                                <h5>Pesanan sampai</h5>
                                <router-link to="/vendor/order/list?filter=arrived"><h2>{{ dashboard_data.arrived }}</h2></router-link>
                            </div>
                        </div>
                        <div class="col-23">
                            <div class="box-report">
                                <h5>Pesanan selesai</h5>
                                <router-link to="/vendor/order/list?filter=completed"><h2>{{ dashboard_data.complete }}</h2></router-link>
                            </div>
                        </div>
                        
                        <div class="col-23">
                            <div class="box-report">
                                <h5>Pesanan Refund</h5>
                                <router-link to="/vendor/order/list?filter=refund_requested"><h2>{{ dashboard_data.refund }}</h2></router-link>
                            </div>
                        </div>

                        <div class="col-23">
                            <div class="box-report">
                                <h5>produk habis</h5>
                                <h2>{{ dashboard_data.product_out_of_stock }}</h2>
                            </div>
                        </div>
                        
                        
                    </div>
                    <div class="row-100">
                        <div class="box-chart">
                            <h5 class="mbottoom-5">penjualan saya hari ini</h5>
                            <h5 class="grey">terakhir diperbaharui hari ini <strong>{{current_date}}</strong> pukul <strong>{{current_time}} WIB</strong></h5>
                            <div class="input-data">
                                <form action="">
                                    <input type="date" name="date_from" id="date_from" placeholder="Masukkan nama produk">
                                    <input class="mleft-15" type="date" name="date_to" id="date_to" placeholder="Masukkan nama produk">
                                    <a class="btn-secondary mleft-15" @click="filterSaleData()">Submit</a>
                                </form>
                            </div>
                            <template>
                                <LineChartGenerator
                                    :chart-options="chartOptions"
                                    :chart-data="chartData"
                                    :chart-id="chartId"
                                    :dataset-id-key="datasetIdKey"
                                    :plugins="plugins"
                                    :css-classes="cssClasses"
                                    :styles="styles"
                                    :width="width"
                                    :height="height"
                                />
                            </template>
                            <!-- <canvas id="rice" width="600" height="400"></canvas> -->
                            <div class="row chart-desc">
                                <div class="desc-chart">
                                    <h5>total pengunjung</h5>
                                    <div class="number">
                                        <h2 class="mright-10">{{ dashboard_data.total_visits }}</h2>
                                        <div class="data">
                                            <img src="/img/ic_inc.svg" alt="">
                                            <h4>0</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="desc-chart">
                                    <h5>produk dilihat</h5>
                                    <div class="number">
                                        <h2 class="mright-10">{{ dashboard_data.product_out_of_stock }}</h2>
                                        <div class="data">
                                            <img src="/img/ic_dec.svg" alt="">
                                            <h4>0</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="desc-chart">
                                    <h5>tingkat konversi</h5>
                                    <div class="number">
                                        <h2 class="mright-10">{{ dashboard_data.conversion_time }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="row chart-desc">
                                <div class="desc-chart">
                                    <h5>pesanan</h5>
                                    <div class="number">
                                        <h2 class="mright-10">{{ dashboard_data.order_count }}</h2>
                                    </div>
                                </div>
                                <div class="desc-chart">
                                    <h5>pendapatan bersih</h5>
                                    <div class="number">
                                        <h2 class="mright-10">{{ dashboard_data.net_gain }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right-menu">
                <div class="box-pink">
                    <div class="content">
                        <h5 class="mbottom-5">informasi terbaru</h5>
                        <h5 class="fw-400">hari ini {{current_date}}</h5>
                        <h5 class="mbottom-5">Jasa Kirim & Waktu Lebaran<sup>Baru</sup></h5>
                        <h5 class="fw-400">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Pellentesque a sagittis elit. Proin pharetra dolor a libero viverra tempor.
                            Phasellus euismod nisl at metus lacinia sagittis.
                        </h5>
                        <hr>
                        <h5 class="mbottom-5">Jasa Kirim & Waktu Lebaran</h5>
                        <h5 class="fw-400">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque a sagittis elit.</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import axios from 'axios'
    import VueAxios from "vue-axios"
    import Vendor from "../../apis/Vendor"
    import { Line as LineChartGenerator } from 'vue-chartjs/legacy'

    import {
        Chart as ChartJS,
        Title,
        Tooltip,
        Legend,
        LineElement,
        LinearScale,
        CategoryScale,
        PointElement
    } from 'chart.js'

    ChartJS.register(
        Title,
        Tooltip,
        Legend,
        LineElement,
        LinearScale,
        CategoryScale,
        PointElement
    )

    Vue.use(VueAxios, axios)

    export default {
        name: "Dashboard",
        props: {
            chartId: {
                type: String,
                    default: 'line-chart'
                },
                datasetIdKey: {
                    type: String,
                    default: 'label'
                },
                width: {
                    type: Number,
                    default: 400
                },
                height: {
                    type: Number,
                    default: 400
                },
                cssClasses: {
                    default: '',
                    type: String
                },
                styles: {
                    type: Object,
                    default: () => {}
                },
                plugins: {
                    type: Array,
                    default: () => []
                }
            },
        data() {
            return{
                dashboard_data : { },
                current_date: '',
                current_time: '',
                chartData: {
                    labels: [],
                    datasets: [
                    {
                        label: 'Penjualan',
                        backgroundColor: '#f87979',
                        data: []
                    },
                    ]
                },
                chartOptions: {
                    responsive: true,
                    maintainAspectRatio: false
                },
                date_from : '',
                date_to : ''
            }
        },
        methods: {
            getDashboard() {
                Vendor.getDashboardData().then(response => {
                    if (response.data.code == 200) {
                        this.dashboard_data = response.data.data
                    } else {
                        this.error = response.data.errors;
                    }
                }).catch((errors) => {
                    this.error = errors.data.errors;
                });
            },
            date_function: function () {
   
                var currentDate = new Date();
    
                var date = currentDate.getDate();
                var month = currentDate.toLocaleString('default', { month: 'long' });
                var year = currentDate.getFullYear();
                var hour = currentDate.getHours();
                var minute = currentDate.getMinutes()<10 ? '0' + currentDate.getMinutes() : currentDate.getMinutes();
                
                this.current_date = date + ' ' + month + ' ' + year;
                this.current_time = hour + ':' + minute
            },
            getSaleData() {
                this.date_from = $('#date_from').val()
                this.date_to = $('#date_to').val()
                
                const fd = new FormData();
                fd.append('date_from' , this.date_from)
                fd.append('date_to' , this.date_to)
                Vendor.getSaleData(fd).then(response => {
                    if(response.data.code == 200) {
                        this.label = response.data.data.timestamp

                        this.label.forEach((value, index) => {
                            this.chartData.labels.push(value)
                        });

                        this.data = response.data.data.sold
                        this.data.forEach((value, index) => {
                            this.chartData.datasets[0].data.push(value)
                        });
                    }
                })
            },
            filterSaleData() {
                this.chartData.labels = [];
                this.getSaleData();
            }

        },
        mounted() {
            this.getDashboard()
            this.getSaleData()
            this.date_function()
        },
        components: {
            LineChartGenerator
        }
    }
</script>
