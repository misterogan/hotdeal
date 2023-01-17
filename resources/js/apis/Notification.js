import Api from '../apis/Api';

export default{
    async get(){
        return Api.get('/api/notification/get');
    },
    async allnotification(form){
        return Api.post('/api/notification/allnotif' , form);
    },
    async markAsRead(form){
        return Api.post('/api/notification/mark/read' , form);
    },
    
}
