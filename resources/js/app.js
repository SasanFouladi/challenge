require('./bootstrap');

/**
 * import libraries
 */
import Vue from 'vue';

/**
 * import plugins
 */
import './plugins/VueRouter';
import './plugins/VueBootstrap';
import './plugins/VueMoment';
import './plugins/VueToasted';
import './plugins/VueSweetAlert';
import './plugins/VueAxios';

/**
 * import base Component (App.vue) and routes
 */
import App from './App.vue';
import router from "./routes/router";


new Vue({
  el: '#app',
  router,
  render: h => h(App)
});
