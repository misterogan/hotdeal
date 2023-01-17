<template>
   <div class="period-list" v-if="Object.keys(week).length > 0 ">
        <div class="item" v-for="(item, index) in week" :key="index">
            <div class="card " >
                <div class="card-period" v-bind:class="index != 0 ? 'active' : ''">
                    <div class="top-card">
                        <h6 class="text-gift">gift hot point</h6>
                        <div class="point">
                            <div></div>
                            <h2>{{item.total_point | NumberFormat}}</h2>
                        </div>
                    </div>
                    <div class="bottom-card">
                        <h6 class="period-count">Periode {{item.week}}</h6>
                        <h6 class="period">{{item.start_date +' - '+ item.end_date}}</h6>
                    </div>
                    <router-link :to="'/winner-list?week='+item.week" >
                        <button class="btn-orange">lihat pemenang</button>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import apiCustomer from '../../apis/Customer'
import BlankPage from './BlankPage.vue'

    export default {
        name: "RejekiNomplokListPeriode.vue",
        data(){
            return{
                week : {},
                pagination : {
                    page : 1,
                    current : 1,
                    total : 1,
                },
            }
        },
        components : {
                BlankPage,
        },
        mounted(){
            this.weekList()
        },
        methods: {
            weekList(){
                apiCustomer.weekList(this.pagination).then( response => {
                    this.week = response.data.data.week
                    this.is_loading = false
                });
            }
        },
    }

</script>