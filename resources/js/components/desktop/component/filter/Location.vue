<template>
    <div class="leftside-menu f-child mbottom-20" v-if="Object.keys(location_data).length > 0">
        <h4>Lokasi</h4>
        <div class="leftside-submenu max-height">
            <div class="leftside-content" v-for="(item, index) in location_data" :key="index" @click="$emit('updateFilter', {type:'location', data:item })">
                <div>{{item.province_name}}</div>
                <div>{{item.total}}</div>
            </div>
        </div>
    </div>
</template>
<script>

    export default {
        name: "Location",
        props :['location_data'],
        mounted(){
        },
        methods : {

        }
    }
</script>
