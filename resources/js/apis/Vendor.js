import Api from '../apis/Api';
import Csrf from '../apis/Csrf'

export default{
    async createProduct(form){
        await Csrf.getCookie();
        return Api.post('/api/seller/product/create' , form);
    },
    async updateProduct(form){
        await Csrf.getCookie();
        return Api.post('/api/seller/product/update' , form);
    },
    async updateStatus(form){
        await Csrf.getCookie();
        return Api.post('/api/product/status' , form);
    },
    async updateProfile(form){
        await Csrf.getCookie();
        return Api.post('/api/seller/update-profile' , form);
    },
    async producList(form){
        await Csrf.getCookie();
        return Api.post('/api/seller/product/list', form);
    },
    async category(){
        await Csrf.getCookie();
        return Api.post('/api/seller/product/category');
    },
    async listOrder(form){
        await Csrf.getCookie();
        return Api.post('/api/seller/order/list', form);
    },
    async refundOrder(form){
        await Csrf.getCookie();
        return Api.post('/api/seller/order/refund', form);
    },
    async getDetailRefundByInvoice(form){
        await Csrf.getCookie();
        return Api.post('/api/seller/order/refund/by_invoice', form);
    },
    async approveRefundByInvoice(form){
        await Csrf.getCookie();
        return Api.post('/api/seller/refund/approve', form);
    },
    async processRefundByInvoice(form){
        await Csrf.getCookie();
        return Api.post('/api/seller/refund/process', form);
    },
    async rejectProcessRefundByInvoice(form){
        await Csrf.getCookie();
        return Api.post('/api/seller/refund/cancel', form);
    },
    async updateStatusInvoice(form){
        await Csrf.getCookie();
        return Api.post('/api/seller/order/update', form);
    },
    async CategoryByParent(form){
        await Csrf.getCookie();
        return Api.post('/api/category/get', form);
    },
    async getDashboardData(form){
        await Csrf.getCookie();
        return Api.get('/api/seller/general', form);
    },
    async getSaleData(form){
        await Csrf.getCookie();
        return Api.post('/api/seller/dashboard/sale-data', form);
    },
    async transactionByInvoiceNumber(form){
        return Api.post('/api/seller/detail/transaction' , form);
    },
    async printLabel(form){
        return Api.post('/api/seller/label' , form);
    },
    async callback(form){
        await Csrf.getCookie();
        return Api.post('/api/payment/callback/hotdeal' , form);
    },
    async updateStatusInvoiceCreatePickup(form){
        await Csrf.getCookie();
        return Api.post('/api/seller/pickup', form);
    },
    async profile() {
        await Csrf.getCookie();
        return Api.get('/api/vendor');
    },
    // async transactionByInvoiceNumber(form){
    //     return Api.post('/api/transaction/detail/transaction' , form);
    // },

    async getProductbyVendor(form){
        await Csrf.getCookie();
        return Api.post('/api/seller/product/edit/byslug' , form);
    },
    async deleteImage(form){
        await Csrf.getCookie()
        return Api.post('/api/seller/delete/galleries' , form)
    },
    async uploadImage(form){
        await Csrf.getCookie()
        return Api.post('/api/seller/upload/galleries' , form)
    },
    async updateVariant(form){
        await Csrf.getCookie()
        return Api.post('/api/seller/product/variant' , form)
    },
    async updateVendorAddress(form){
        await Csrf.getCookie()
        return Api.post('/api/seller/update/address' , form)
    }
}
