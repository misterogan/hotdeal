

<template>
     <div class="pagination">
        <!-- <div class="filter">
            <h5>row per page</h5>
            <div class="select" tabindex="1">
                
            </div>
        </div> -->
        <div class="page-number" v-if="pagination.total > 1">
            <ul>
                <li @click="$emit('updatePage' , {type : 'page' , value : ( pagination.current - 1 )} )">
                    <img src="/img/icon_pagination_left.svg" alt="">
                </li>
                <li :class="item == pagination.current ? 'active' : ' ' "  v-for="item in pagination.total" :key="item" @click="$emit('updatePage' , {type : 'page' , value : item})">{{item}}</li>
                <li @click="$emit('updatePage' , {type : 'page' , value : ( pagination.current + 1 )})">
                    <img src="/img/icon_pagination_right.svg" alt="">
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
    export default {
        name: "Pagination.vue",
        props:['pagination']
    }
</script>

<style scoped>

</style>