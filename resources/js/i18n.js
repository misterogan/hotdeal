import Vue from 'vue';
import VueI18n from 'vue-i18n'

Vue.use(VueI18n);

const lang = function () {
    if (window.navigator.languages) {
        return window.navigator.languages[0];
    } else {
        return window.navigator.userLanguage || window.navigator.language;
    }
};

const i18n = new VueI18n({
    locale: lang(),
    fallbackLocale: 'en',
    messages:{
        'en': require('../js/locales/en.json'),
        'id': require('../js/locales/id.json')
    }
});

export default i18n;