import './bootstrap'

import Vue from 'vue';
import App from './components/App'
import router from './router/index'
import i18n from './i18n'
import './utils/header'
import './utils/quantity'
import './utils/share'

// import OtpInput from "@bachdgvn/vue-otp-input";

// Vue.component("v-otp-input", OtpInput);

new Vue({
    el: '#app',
    components: { App },
    router,
    i18n
});
