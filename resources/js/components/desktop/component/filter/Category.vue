<template>
    <div class="leftside-menu mbottom-20" v-if="Object.keys(category_data).length > 0">
        <h4>Kategori</h4>
        <div class="leftside-submenu max-height">
            <div class="leftside-content" v-for="(item, index) in category_data" :key="index">
                <div class="label relative pointer">
                    <span class="pointer">{{ item.category_name }}</span>
                    <input class="category-menu pointer" type="checkbox" :id="'check' +  item.id" name="category" v-on:change="$emit('updateFilter', {type:'category', data:item })" />
                    <label class="pointer" :for="'check' + item.id"></label>
                </div>
<!--                <button class="radio" @click="$emit('updateFilter', {type:'category', data:item })">-->
<!--                    <input type="radio" :id="'check' +  index" name="category" />-->
<!--                    <label :for="'check' + index">{{ item.category_name }}</label>-->
<!--                </button>-->
            </div>
        </div>
    </div>
</template>


<script>
export default {
    name: "Category",
    props :['category_data'],
    mounted(){
    },
    methods : {

    }
}
</script>
