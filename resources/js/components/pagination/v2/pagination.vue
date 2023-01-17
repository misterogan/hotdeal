

<template>
    <div>
        <ul class="pagination mtop-50" v-if="total > 0">
            <li class="prev" @click="$emit('changeAction' , current-1)"></li>
            <li class="" v-for=" n in array_numbers" :key="n" 
            v-bind:class="[n == current ? 'active' : '' ,  hide ? 'hide' : '']" 
            @click="$emit('changeAction' , n)">{{n}}</li>
            <input type="hidden" :value="array_number"/>
            <li class="next" @click="$emit('changeAction' , current+1)"></li>
        </ul>
    </div>
</template>
<script>
    export default {
        name: "PaginationV2.vue",
        props:['total' , 'current'],
        computed: {
            array_number() {
                // if(this.total < 6){
                //     return this.array_numbers = 
                // }
                const pages = [];
                const start = Math.max( 1, this.current - 3);
                const end = Math.min(this.current + 3 , this.total);
                for (let i = start; i <= end; i++) {
                    pages.push(i);
                }
                 this.array_numbers = pages;
                return pages;
            }
        },
        data() {
            return {
                startPaging : 0,
                endPaging : 0,
                array_numbers : 0,
                hide : false
            }
        }
    }
</script>

<style scoped>
    .hide{
        display: none;
    }
</style>