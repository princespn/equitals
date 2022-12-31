/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

import Vue from "vue";
import VueRouter from "vue-router";
import VueAxios from "vue-axios";
import axios from "axios";
import App from "./user-config/App.vue";
import router from "./user-config/routes";
import {
  apiUserHost
} from "./user-config/config";
import userInterceptor from "./user-config/interceptor";
import adminInterceptor from "./admin-config/interceptor";
import adminRouter from "./admin-config/admin-routes";
import AdminApp from "./admin-config/AdminApp.vue";
import {
  apiAdminHost
} from "./admin-config/config";
import moment from "moment";
import Toaster from "v-toaster";
import "v-toaster/dist/v-toaster.css";
import VeeValidate from "vee-validate";
import swalPlugin from "./VueSweetalert2";
import VueTelInput from 'vue-tel-input';
import {
  VueEditor
} from 'vue2-editor';
import DatePicker from 'vuejs-datepicker';

import Lightbox from 'vue-easy-lightbox';
import VueChatScroll from 'vue-chat-scroll';
import { Picker } from 'emoji-mart-vue';




window.Vue = require("vue");

Vue.use(VueRouter);
Vue.use(VueAxios, axios);
Vue.use(require("vue-moment"));
Vue.use(Toaster, {
  timeout: 5000
});
Vue.use(VeeValidate);
Vue.use(swalPlugin);
Vue.use(VueTelInput);
Vue.use(VueChatScroll);
Vue.use(Picker);
Vue.use(Lightbox);
Vue.component('DatePicker', DatePicker);
Vue.component('Picker', Picker);
Vue.filter('formatDate', function (value) {
  moment.suppressDeprecationWarnings = true;
  if (value) {
    return moment(String(value)).format('D MMM,YYYY hh:mm:ss', true);
  }
});
Vue.mixin({
  data: function () {
    return {
      get datePickerFormat() {
        return 'dd-MM-yyyy';
      }
    }
  }
});

/*Vue.filter("formatDate", function(value) {
  moment.suppressDeprecationWarnings = true;
  if (value) {
    return moment(String(value)).format("D MMM,YYYY hh:mm:ss", true);
  }
}); 3 */


Vue.filter('pDuration', function (value) {

  let temp=value.replace("PT", "");
       temp=temp.replace("H", ":")
        temp=temp.replace("M", "")
    return temp;
});

Vue.filter('pRemoveTime', function (value) {
   let temp=value.slice(0, 10);
      //temp=temp.slice(5, 10);
      
    return temp;

  
});
Vue.filter('pRemoveDate', function (value) {

let temp=value.slice(11, 19);
// temp=temp.slice(5, 10);
    return temp;
});
Vue.filter('ptow', function (value) {

let temp=value.toFixed(2);
// temp=temp.slice(5, 10);
    return temp;
});

Vue.filter('pFlightLogo',function(value){


  
let flights = JSON.parse(localStorage.getItem('flight_logo'));

let result = flights.filter(flight => flight.IATA  == value); 


   if(result.length>0){

    return result[0].logo;
   }
   return `https://equitals-corp.s3.ap-south-1.amazonaws.com/flight-logo/a8YUYJymRQ0VwEtwYxbIUcMRBQ37u74K82rKoXUw.png`;


})

if (window.location.href.indexOf("EqT2FyvKdAL6FW3gfCKszyU9clNc2hs#") > -1) {
  axios.defaults.baseURL = apiAdminHost;

  Vue.use(adminInterceptor);

  const app = new Vue({
    router: adminRouter,
    template: "<AdminApp/>",
    components: {
      AdminApp
    }
  }).$mount("#app");
} else {
  axios.defaults.baseURL = apiUserHost;

  Vue.use(userInterceptor);

  const app = new Vue({
    router,
    template: "<App/>",
    components: {
      App
    }
  }).$mount("#user-app");
}

let idleTime = 0;
$(document).ready(function () {
  //Increment the idle time counter every minute.
  var idleInterval = setInterval(timerIncrement, 2000); // 1 minute

  //Zero the idle timer on mouse movement.
  $(this).mousemove(function (e) {
    idleTime = 0;
  });
  $(this).keypress(function (e) {
    idleTime = 0;
  });
});

function timerIncrement() {

  idleTime = idleTime + 1;
  var type1 = localStorage.getItem('type');
  if (idleTime > 1800) { // 30   minutes
    if (type1 == 'user') {
      localStorage.clear();
      window.location = "http://localhost/XC/public/user#/login";
      location.reload();
    }

  }
}