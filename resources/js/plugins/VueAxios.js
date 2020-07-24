import Vue from 'vue';
import axios from 'axios';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


let axiosPlugin = {};

axiosPlugin.install = function (Vue, options) {
  Vue.prototype.$axios = axios;
};

Vue.use(axiosPlugin);