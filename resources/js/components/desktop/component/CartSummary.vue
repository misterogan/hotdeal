<template>
    <div>
        <div class="col-25p">
            <div class="box-pink">
                <div class="content">
                    <h5 class="pink">Ringkasan Belanja</h5>
                    <div class="list-checkout" v-for="(item , index) in itemsummary" :key="index">
                        <p class="n-prod" style="padding-right:25px;">{{item.product_detail.product.name}} - {{item.product_detail.variant_v_1}} | {{item.product_detail.variant_v_2}}  </p>
                        <p>{{(item.product_detail.price * item.quantity) | amount}}</p>
                    </div>
                    <hr>
                    <div class="row-total">
                        <h5 class="pink">Total Belanja</h5>
                        <h3 class="total">{{TotalPayment | amount}}</h3>
                    </div>
                    <button>
                        <img src="/img/ic_checkout.svg" alt="">
                        <router-link to="/checkout">Check Out</router-link>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CustomerAPi from '../../../apis/Customer'
    export default {
        name: "CartSummary",
        props :['itemsummary'],
        data(){
            return {
               // itemsummary : []
            }
        },
        computed : {
            TotalPayment : function(){
               return this.itemsummary.reduce((Tprice, n) => {
                    return Tprice += parseInt(n.product_detail.price) * parseInt(n.quantity);
               }, 0)
            }
        },
        filters :{
            amount: function(value){
                let val = (value/1).toFixed(0).replace('.', ',')
                return 'Rp '+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            }
        }
    }
    
</script>