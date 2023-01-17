import Api from '../apis/Api';
import Csrf from '../apis/Csrf'

export default{
    async register(form){
        await Csrf.getCookie();
        return Api.post('/api/register', form);
    },
    async login(form){
        await Csrf.getCookie();
        return Api.post('/api/login', form);
    },
    async forget(form) {
        await Csrf.getCookie();
        return Api.post('/api/forget', form);
    },
    async forgetVerify(form) {
        await Csrf.getCookie();
        return Api.post('/api/forget/verify', form);
    },
    async forgetSet(form) {
        await Csrf.getCookie();
        return Api.post('/api/forget/set', form);
    },
    async newsletter(form) {
        await Csrf.getCookie();
        return Api.post('/api/newsletter/set', form);
    },
    async Googlelogin(provider){
        //await Csrf.getCookie();
        return Api.post('/api/logins/'+provider);
    },
    async profile(){
        return Api.get('/api/user');
    },
    async logout(){
        return Api.post('/api/logout').then(response => {
            localStorage.removeItem('auth');
            localStorage.removeItem('verify-email');
        });
    },
    async login_google(form) {
        await Csrf.getCookie();
        return Api.post('/api/login/google',form)
    },
    is_login(){
        return localStorage.getItem('auth');
    },
    social_login(provider, res){
        return Api.post('/api/login/'+provider, res);
    },
    checkLoginCookie(name){
        var loginCookie = this.getCookie(name);
        if (loginCookie == null) {
            const d = new Date();
            d.setTime(d.getTime() + (7*24*60*60*1000));
            let expires = "expires="+ d.toUTCString();
            document.cookie = name + "=" + 'ModalCookie' + ";" + expires + ";path=/";
            return false;
        }else {
            return true;
        }
    },
    checkDauCookie(name){
        var dauCookie = this.getCookie(name);
        if (dauCookie == null) {
            const d = new Date();
            d.setTime(d.getTime() + (6*60*60*1000));
            let expires = "expires="+ d.toUTCString();
            document.cookie = name + "=" + 'has_login' + ";" + expires + ";path=/";
            return false;
        }else {
            return true;
        }
    },
    getCookie(name){
        var dc = document.cookie;
        var prefix = name + "=";
        var begin = dc.indexOf("; " + prefix);
        if (begin == -1) {
            begin = dc.indexOf(prefix);
            if (begin != 0) return null;
        }else{
            begin += 2;
            var end = document.cookie.indexOf(";", begin);
            if (end == -1) {
                end = dc.length;
            }
        }
        return decodeURI(dc.substring(begin + prefix.length, end));
    },
    addDau(){
        Csrf.getCookie();
        return Api.get('/api/add-dau')
    },
    async emailRegister(email){
        await Csrf.getCookie();
        return Api.post('/api/register/email',{email: email})
    },
    // async sendOtp(form){
    //     return Api.post('/api/auth/send/otp' , form);
    // },
    async VerifyOtp(form){
        console.log(form)
        await Csrf.getCookie();
        return Api.post('/api/auth/verify/email/otp' , form);
    },
}
