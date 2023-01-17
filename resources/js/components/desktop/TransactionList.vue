<template>
    <div class="row-hd">
        <div class="col3-flex">
            <div class="flex-basis-40">
                <profile-menu :count="count_transaction"></profile-menu>
                <profile-point-info></profile-point-info>
            </div>
            <router-view @count_transaction="count_transaction = $event"></router-view>
        </div>
    </div>
</template>

<script>
    import ProfileMenu from './component/profile/ProfileMenu.vue'
    import OrderTransactionList from './component/order/OrderTransactionList.vue'
    import HighLightImageOnly from './component/product/HighLightImageOnly.vue'
    import HighLightTwoColumn from './component/product/HighLightTwoColumn.vue'
    import HeaderProfile from '../../components/desktop/header/Profile'
    import User from "../../apis/User";
    import Customer from "../../apis/Customer";
    import RejekiNomplokBanner from './RejekiNomplokBanner.vue'
    import ProfilePointInfo from '../desktop/component/profile/Profile.vue'

    export default {
        name: "TransactionList.vue",
        components: {
                ProfileMenu,
                OrderTransactionList,
                HighLightTwoColumn,
                HighLightImageOnly,
                HeaderProfile,
                RejekiNomplokBanner,
                ProfilePointInfo
        },
        data() {
            return{
                profile : {},
                transaction : {},
                count_transaction : 0
            }
        },
        created() {
            this.get_profile();
            this.get_transaction();
        },
        methods : {
            logout(){

            },
            // check_transaction(event){
            //     this.count
            // },
            get_profile() {
                User.profile().then(response => {
                    this.profile = response.data;
                })
            },
            get_transaction() {
                Customer.getTransactions().then(response => {
                    this.transaction = response.data.data;
                })
            },
        },
    }
</script>
