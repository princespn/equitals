
</style>
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
                                      <img src="public/user_files/assets/images/logo.svg" width="250"></a>
                                  </div>

                                    <h4 class="text-center mb-4">Reset password</h4>
                                    <p class="">
                                        Please enter your user id below and we will send you OTP on your registered email id!.
                                    </p>
                                    <form action="#!">
                                        <div class="mb-3">
                                            <label><strong>User Id</strong></label>
                                            <input type="text" class="form-control" autofocus="" v-model="user_id" v-on:input="checkuserexist" placeholder="User Id">
                                            <div v-if="!useractive" class="tooltip2">
                                              <span class="text-danger error-msg-size tooltip-inner">
                                                {{ this.usermsg }}
                                              </span>
                                            </div>
                                        </div>
                                    </form>
                                      <div
                                    class="modal fade"
                                    id="editBankDetailsmodal"
                                    role="dialog"
                                    data-backdrop="static">
                                <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                <div v-show="otpstatus == false">
                                <div class="modal-header">
                                  <button
                                    type="button"
                                    class="close"
                                    data-dismiss="modal"
                                  >
                                    &times;
                                  </button>
                               
                                  <h4 class="modal-title">Enter Otp</h4>
                                </div>
                                <div class="modal-body">
                                  <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-12">
                                        <!--  <label for="btc_address">Otp </label> -->
                                        <input
                                          type="text"
                                          name="otp"
                                          class="form-control"
                                          placeholder="Enter OTP"
                                          v-model="otp"
                                          v-validate="'required'"
                                        />
                                        <div
                                          class="tooltip2"
                                          v-show="errors.has('otp')"
                                        >
                                          <div class="tooltip-inner">
                                            <span v-show="errors.has('otp')">{{
                                              errors.first("otp")
                                            }}</span>
                                          </div>
                                        
                                      </div>
                                      <div class="clearfix"></div>
                                      <br />
                                      <br />
                                      <div class="col-md-12">
                                        <center>
                                          <button
                                            @click="checkOtp()"
                                            type="button"
                                            class="btn btn-primary"
                                          >
                                            Submit
                                          </button>
                                        </center>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        </div>  
                         
                                          <div class="row justify-content-center mt-2 mb-2">
                                       <!--  <div class="col-3">
                                            <img src="images/robo.png" class="img-fluid">
                                        </div> -->
                                        <div class="col-9 reCAPTCHA">
                                            <div class="captcha">
                                                <div class="spinner">
                                                    <label>
                                                        <input type="checkbox" v-model="terms"  id="terms" name="terms" v-validate="'required'" data-vv-as="recaptcha"  aria-required="true" aria-invalid="false">
                                                        <span class="checkmark"><span>&nbsp;</span></span>
                                                           <div class="tooltip2" v-show="errors.has('form-1.terms')">
                                                            <div class="tooltip-inner">
                                                              <span v-show="errors.has('form-1.terms')">{{
                                                                errors.first("form-1.terms")
                                                              }}</span>
                                                            </div>
                                                          </div>
                                                    </label>
                                                </div>
                                                <div class="text">
                                                    I'm not a robot
                                                </div>
                                                <div class="logo">
                                                    <!-- <img src="images/captcha.png" class="img-fluid"> -->
                                                  <img src="public/user_files/assets/images/robo.png" class="img-fluid">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br />
                                    <button
                                      style="display: none"
                                      data-target="#editBankDetailsmodal"
                                      data-toggle="modal"
                                      id="forgotMe"
                                    ></button>
                                    

                                        <div class="text-center">
                                          <input
                                          type="button"
                                          value="Reset"
                                          class="
                                            btn btn-primary btn-inline
                                            fs16
                                            btn-block
                                            mtb-10
                                            btn-forgot
                                            logbtn
                                          "
                                          @click="sendOTP"
                                          :disabled="!isSubmit || !isActiveBtn"
                                        />
                                            <!-- <button type="submit" class="btn btn-primary btn-block">Reset</button> -->
                                        </div>
                                  
                                     <div class="new-account mt-3 text-center">
                                        <p>Already have an account? <router-link to="/login" class="text-primary">Sign In Now</router-link></p>
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
    };
  },
  computed: {
    isSubmit() {
      return this.user_id && this.terms;
    },
  },
  mounted() {
    this.weburl = domain;

    //$('#forgotMe').trigger('click');
    //localStorage.setItem('access_token', "UDGHDFGITIERTMNMNBCVMNBKJC");
  },
  methods: {
    checkuserexist() {
      axios
        .post("checkuserexist", {
          user_id: this.user_id,
        })
        .then((response) => {
          if (response.data.code == 200) {
            this.useractive = true;
          } else {
            this.useractive = false;
            this.usermsg = response.data.message;
          }
        })
        .catch((error) => {});
    },

    forgotPassword() {
      this.isActiveBtn = false;
      axios
        .post("reset-passwordlink", {
          user_id: this.user_id,
        })
        .then((resp) => {
          if (resp.data.code == 200) {
            this.$toaster.success(resp.data.message);
            this.$router.push({ name: "login" });
          } else {
            this.$toaster.error(resp.data.message);
          }
          this.isActiveBtn = true;
        })
        .catch((err) => {});
    },

    sendOTP() {
      axios
        .post("sendOtp-update-user-profile1", {
          user_id: this.user_id,
        })
        .then((response) => {
          if (response.data.code == 200) {
            //console.log(response);
            this.$toaster.success(response.data.message);
           
            //this.statedata=response.data.data.message;

            $("#forgotMe").trigger("click");
            
          } else {
            this.$toaster.error(response.data.message);
          }
        })
        .catch((error) => {});
    },

    checkOtp() {
      axios
        .post("checkotp2", {
          otp: this.otp,
          user_id: this.user_id,
        })
        .then((response) => {
          if (response.data.code == 200) {
            // this.statedata=response.data.data;
            // this.otp = '';
            $("#forgotMe").trigger("click");
            //  $('#editBankDetailsmodal').modal('hide');
            //this.otpstatus = true;
            window.location.href = response.data.data;
            /*$('#editBankDetails1').modal('show');*/
            // this.$toaster.success(response.data.message);
          } else {
            this.$toaster.error(response.data.message);
          }
        })
        .catch((error) => {});
    },
  },
      
};
</script>