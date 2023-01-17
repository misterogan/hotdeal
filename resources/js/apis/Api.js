import axios from 'axios';

let Api = axios.create();

Api.defaults.withCredentials = true;
Api.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
Api.defaults.headers.common['Accept'] = 'application/json';
Api.interceptors.response.use(response => {
    return response;
}, error => {
    if (error.response.status === 401) {
        localStorage.removeItem('auth');
        localStorage.removeItem('verify-email');
    }
    return error.response;
});

export default Api;
