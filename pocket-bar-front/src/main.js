import Vue from 'vue';
import App from './App.vue';
import vuetify from './plugins/vuetify';
import router from './router';
import Axios from 'axios';
import shortkey from "vue-shortkey";
import VueAxios from "vue-axios";
import Vuelidate from 'vuelidate';
import Vuex from 'vuex';
import Echo from 'laravel-echo';
import store from '@/store';
import './registerServiceWorker'
Vue.use(Vuelidate)
Vue.use(VueAxios, Axios)
Vue.use(Vuex)
Vue.use(store)
Vue.use(shortkey)
Vue.config.productionTip = false
window.Axios = require('axios')
window.Pusher = require('pusher-js');
Axios.defaults.headers.common['Authorization'] = `Bearer ${store.state.token}`;



window.Echo = new Echo({
  broadcaster: 'pusher',
  key: 'local',
  wsHost: window.location.hostname,
  wsPort: 6001,
  cluster: 'mt1',
  forceTLS: false,
  disableStats: true,
  //encrypted: true,
});


new Vue({
  vuetify,
  router,
  Vuex, shortkey,
  VueAxios, Axios, Vuelidate, store,
  render: h => h(App),
}).$mount('#app')
