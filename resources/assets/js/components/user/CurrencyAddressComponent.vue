<template>
  <div>
    <div class="">
      <div class="authincation h-100">
        <div class="container h-100">
          <div class="row justify-content-center h-100 align-items-center">
            <div class="col-md-6">
              <div class="authincation-content">
                <div class="row no-gutters">
                  <div class="col-xl-12">
                    <div class="auth-form">
                      <div class="text-center mb-3">
                        <a href="https://www.equitals.com/">
                          <img
                            src="public/user_files/assets/images/logo.svg"
                            width="250"
                          />
                        </a>
                      </div>

                      <h4 class="text-center mb-4">Currency Address Info</h4>
                      <p class="" v-if="check_flag == 1">
                        Now, your {{curr_type}} Address is reset, Please <a href="#/login" @click="rest_browser">Login</a> and add your {{curr_type}} Address.
                      </p>
                      <p class="" v-else>
                        Your Email Link is expired, Please <a href="#/login" @click="rest_browser">Login</a>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { domain } from "./../../user-config/config";
export default {
  data() {
    return {
      weburl: "",
      useractive: true,
      terms: "",
      user_id: "",
      otp: "",
      otpstatus: "",
      icon: "../public/user_files/assets/img/icon-mlm.png",
      logo: "../public/user_files/assets/img/logo.png",
      hostName: window.location.origin,
      isActiveBtn: true,
      check_flag:0,
      curr_type:'',
    };
  },
  computed: {
    isSubmit() {
      return this.user_id && this.terms;
    },
  },
  mounted() {
    this.weburl = domain;
    if ( this.$route.query.token != undefined && this.$route.query.token != "") {
         // this.register.referral_id  = this.$route.query.ref_id;
        var uniqueid = this.$route.query.token;
        this.checkUserToken(uniqueid);
        //this.checkuserexist();
        this.alreadyref = 1;
    }

    //$('#forgotMe').trigger('click');
    //localStorage.setItem('access_token', "UDGHDFGITIERTMNMNBCVMNBKJC");
  },
  methods: {
    checkUserToken(token) {
        axios.post("check-user-addr-token", {
          token: token,
        })
        .then((response) => {
          if (response.data.code == 200) {
            this.curr_type = response.data.data.currency;
            this.check_flag = 1;
          } else {
            this.curr_type = '';
            this.check_flag = 0;
          }
        })
        .catch((error) => {});
    },
    rest_browser(){
      localStorage.removeItem('user-token');
      localStorage.removeItem('check-in');
      localStorage.removeItem('type');
      setInterval(function(){this.$router.push({name: 'login'});location.reload();},300);
    }

  },
};
</script>
