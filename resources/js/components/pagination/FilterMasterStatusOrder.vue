<template>
     <div class="col select filter">
        <h5>filter</h5>
        <select tabindex="1" class="select-filter" v-model="selected"  v-if="Object.keys(list).length > 0" @change="$emit('updatePage' , {type : 'filter' , value :selected})" >
            <option disabled value="">Please select one</option>
            <option v-for="(item, index) in list " :key="index" :value="item.code">{{item.description}}</option>
        </select>
    </div>
</template>
<script>
    import apiMaster from '../../apis/Master'
    export default {
        name: "FilterMasterStatusOrder.vue",
        data(){
            return {
                list : {},
                selected : null
            }
        },
        mounted(){
             apiMaster.masterStatusOrder().then( response =>{
                this.list = response.data.data
            });
        },
    }
</script>

<style scoped>

</style>