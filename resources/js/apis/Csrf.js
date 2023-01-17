import Api from '../apis/Api';

export default {
    getCookie() {
        var cookieArr = document.cookie.split(";");
        for (var i = 0; i < cookieArr.length; i++) {
            var cookiePair = cookieArr[i].split("=");
            if (cookiePair[0].trim() === 'XSRF-TOKEN') {
                return new Promise(resolve => {
                    resolve(decodeURIComponent(cookiePair[1]))
                });
            }
        }

        return Api.get('/api/csrf-cookie');
    }
}
