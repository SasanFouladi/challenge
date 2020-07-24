import Vue from 'vue';
import VueMoment from 'vue-moment';
import moment from "moment";

moment.locale(document.documentElement.lang);

Vue.use(VueMoment, {
  moment
});