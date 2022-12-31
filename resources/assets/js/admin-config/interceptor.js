import Vue from "vue";
import axios from "axios";

export default function setup() {
  axios.interceptors.request.use(
    function(config) {
      const token = localStorage.getItem("access_token");
     // const adminauth = localStorage.getItem("admin_auth");
      if (token != null) {
        config.headers.Authorization = "Bearer " + token;
      }else{
        //location.reload();
      }
      return config;
    },
    function(err) {
      return Promise.reject(err);
    }
  );
  axios.interceptors.response.use(
    function(config) {
     
       if (config.data.code == 401 ||config.data.code == 403 || config.data.message == 'Please logout and login again session is expired' || config.data.message == 'Please try again') {
            // alert(config.data.code);
            localStorage.removeItem('access_token');
            // localStorage.removeItem('access-token');
            setTimeout(function() { 
                 location.reload();
            }, 1000);
            setTimeout(function() {
                location.reload();
            }, 1000);
        } else {
            return config;
        }
    },
    function(err) {
      
      if (err.response.code == 401 || err.response.code == 403) {
        localStorage.clear();
        localStorage.removeItem('access_token');
        setTimeout(function() { 
            this.$router.push({ name: "login" });
            }, 1000);
            setTimeout(function() { 
                location.reload();
            }, 1000); 
      }
    }
  );
}
