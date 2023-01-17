import Api from '../apis/Api';
import Csrf from '../apis/Csrf'

export default{
    async Categories(form){
        await Csrf.getCookie();
        return Api.post('/api/category/get' , form);
    },
    async province(){
        await Csrf.getCookie();
        return Api.get('/api/location/province/get'); 
    },
    async city(form){
        await Csrf.getCookie();
        return Api.post('/api/location/cities/get' , form); 
    },
    async subrubs(form){
        await Csrf.getCookie();
        return Api.post('/api/location/suburbs/get' , form); 
    },
    async area(form){
        await Csrf.getCookie();
        return Api.post('/api/location/area/get' , form); 
    },
    async masterStatusOrder(){
        await Csrf.getCookie();
        return Api.get('/api/master/status/order'); 
    },
}
