
<template>
  <div
    class="
      horizontal-layout horizontal-menu
      dark-layout
      1-column
      navbar-floating
      footer-static
      bg-full-screen-image
      blank-page blank-page
      only
    "
    data-open="hover"
    data-menu="horizontal-menu"
    data-col="1-column"
    data-layout="dark-layout"
  >
    <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="header-navbar-shadow"></div>
      <div class="content-wrapper ool">
        <div class="content-body">
          <div class="container">
            <div class="row">
              <div class="col-lg-8 col-12 p-0 mlp">
                <div class="text-center">
                  <a href="https://www.equitals.com/" class="login_logo">
                    <img
                      class="regr_logo"
                      src="public/user_files/images/logo1.png"
                    />
                  </a>
                  <h2 class="mb-0">Register</h2>
                  <p class="px-2">
                    Fill the below form to create a new account.
                  </p>
                  <div class="card-content">
                    <div class="card-body pt-0 logpp">
                      <form
                        class="slctbox register clearfix"
                        @submit.prevent="registerUser"
                      >
                        <div class="row">
                          <div class="form-label-group col-lg-6">
                            <p class="lt">User Id</p>
                            <input
                              type="text"
                              class="form-control"
                              name="user_id"
                              placeholder="Enter Username"
                              v-model="register.user_id"
                              v-validate="'required|alpha_num|min:5|max:30'"
                              data-vv-as="user_id"
                              readonly=""
                            />
                            <!-- v-on:input="checkuserexist(1)" -->
                            <div v-if="useractive1" class="tooltip2">
                              <span
                                class="text-danger error-msg-size tooltip-inner"
                              >
                                {{ this.usermsg1 }}</span
                              >
                            </div>
                            <div
                              class="tooltip2"
                              v-show="errors.has('user_id')"
                            >
                              <div class="tooltip-inner">
                                <span v-show="errors.has('user_id')">{{
                                  errors.first("user_id")
                                }}</span>
                              </div>
                            </div>
                            <!-- <label for="inputName">Use ID</label> -->
                          </div>

                          <div class="form-label-group col-lg-6">
                            <p class="lt">Full Name</p>

                            <input
                              type="text"
                              class="form-control"
                              name="fullname"
                              placeholder="Enter Full Name"
                              v-model="register.fullname"
                              v-validate="'required|min:5|max:30'"
                              data-vv-as="fullname"
                              v-on:input="checkuserexist(1)"
                            />
                            <!-- <div v-if='useractive1' class="tooltip2">
                                            <span class=" text-danger error-msg-size tooltip-inner"> {{ this.usermsg1 }}</span>
                                        </div> -->
                            <div
                              class="tooltip2"
                              v-show="errors.has('fullname')"
                            >
                              <div class="tooltip-inner">
                                <span v-show="errors.has('fullname')">{{
                                  errors.first("fullname")
                                }}</span>
                              </div>
                            </div>
                            <!-- <label for="inputName">Fullname</label> -->
                          </div>
                          <!-- defaultCountry="IN" -->
                        </div>

                        <div class="row">
                          <div class="form-label-group col-lg-6">
                            <p class="lt">Mobile</p>

                            <vue-tel-input
                              @onInput="onInput"
                              defaultCountry="VG"
                              placeholder="Enter Mobile Number"
                              v-model="register.mobile"
                            ></vue-tel-input>

                            <div class="tooltip2" v-show="errors.has('mobile')">
                              <div class="tooltip-inner">
                                <span v-show="errors.has('mobile')">{{
                                  errors.first("mobile")
                                }}</span>
                              </div>
                            </div>
                            <!-- <label for="inputMobile">Mobile</label> -->
                          </div>

                          <div class="form-label-group col-lg-6 mobpos">
                            <p class="lt">Position</p>

                            <select
                              id="s_id"
                              v-model="register.position"
                              v-validate="'required|'"
                              class="form-control"
                              style="color: #000 !important"
                            >
                              <option value="">Select Position</option>
                              <option value="1">Left</option>
                              <option value="2">Right</option>
                            </select>
                            <!-- <label for="position">Position</label> -->
                          </div>
                        </div>

                        <div class="row">
                          <div class="form-label-group col-lg-6">
                            <p class="lt">Email</p>

                            <input
                              type="Email"
                              name="email"
                              class="form-control"
                              placeholder="Enter Email Id"
                              v-model="register.email"
                              v-validate="'required|email|max:70'"
                              data-vv-as="email"
                            />
                            <div class="tooltip2" v-show="errors.has('email')">
                              <div class="tooltip-inner">
                                <span v-show="errors.has('email')">{{
                                  errors.first("email")
                                }}</span>
                              </div>
                            </div>
                            <!-- <label for="inputEmail">Email</label> -->
                          </div>

                          <!--  <div class="form-label-group">
                                        <input type="text" name="btc_address" class="form-control" placeholder="Enter Bitcoin Address" v-model="register.btc_address"  v-on:keyup="checkValidBTCAddr" v-validate="'required'">
                                              <div class="loaderD"></div>
                                              <div class="tooltip2" v-show="errors.has('btc_address')">
                                                  <div class="tooltip-inner">
                                                      <span v-show="errors.has('btc_address')">{{ errors.first('btc_address') }}</span>
                                                  </div>
                                              </div>
                                        <label for="inputEmail">BTC Address</label>
                                    </div> -->

                          <div class="form-label-group col-lg-6">
                            <p class="lt">Sponsor's ID</p>

                            <input
                              type="text"
                              name="referral_id"
                              class="form-control"
                              placeholder="Your Sponsor's Id"
                              v-model="register.referral_id"
                              v-validate="'required'"
                              v-on:input="checkuserexist(2)"
                              id="referral_id"
                              data-vv-as="referral id"
                            />
                            <!-- <div class="reg_check_o" v-if="alreadyref == 0">
                                            <input style="cursor:pointer; margin-left:30px;" type="checkbox" 
                                                 value="yes" 
                                                 id="refer" 
                                                 v-model="is_referral" 
                                                 @change="check1()" class="regist-new-checkBox"> Dont have Sponsor?
                                             </div> -->
                            <div v-if="!useractive" class="tooltip2">
                              <span
                                class="text-danger error-msg-size tooltip-inner"
                              >
                                {{ this.usermsg }}</span
                              >
                            </div>
                            <!-- <label for="inputEmail">Sponsor's Username</label> -->
                          </div>
                        </div>

                        <div class="row">
                          <div class="form-label-group reg_ico2_o col-lg-6">
                            <p class="lt">Password</p>
                            <input
                              ref="password"
                              id="password"
                              data-toggle="password"
                              type="Password"
                              name="password"
                              class="form-control"
                              placeholder="Enter Password"
                              v-model="register.password"
                              v-validate="'required|min:6|max:30'"
                              data-vv-as="password"
                            />

                            <span class="reg_ico2"
                              ><i
                                @click="showPass"
                                aria-hidden="true"
                                :class="eye"
                              ></i
                            ></span>
                            <div
                              class="tooltip2"
                              v-show="errors.has('password')"
                            >
                              <div class="tooltip-inner">
                                <span v-show="errors.has('password')">{{
                                  errors.first("password")
                                }}</span>
                              </div>
                            </div>
                            <!-- <label for="inputPassword">Password</label> -->
                          </div>

                          <div class="form-label-group col-lg-6">
                            <p class="lt">Confirm Password</p>

                            <input
                              type="password"
                              name="confirm_password"
                              class="form-control"
                              placeholder="Confirm Password"
                              v-model="register.confirm_password"
                              v-validate="'required'"
                              data-vv-as="confirm password"
                              v-on:keyup="onPasswordClick"
                            />
                            <div
                              class="tooltip2"
                              v-show="errors.has('confirm_password')"
                            >
                              <div class="tooltip-inner">
                                <span v-show="errors.has('confirm_password')">{{
                                  errors.first("confirm_password")
                                }}</span>
                              </div>
                            </div>
                            <!-- <label for="inputConfPassword">Confirm Password</label> -->
                            <small
                              class="form-label-group text-danger"
                              v-show="password_validation_msg"
                            >
                              Note:- Password must be more than 6 characters. It
                              should contain uppercase, lowercase, numerical and
                              special characters.
                            </small>
                          </div>
                        </div>
                        <!--
                                   <div class="form-group row mt-10 acc">
                                        <div class="col-12 ">-->
                        <!-- <fieldset class="checkbox">
                                              <div class="vs-checkbox-con vs-checkbox-primary">
                                                <input type="checkbox" checked>
                                                <span class="vs-checkbox">
                                                  <span class="vs-checkbox--check">
                                                    <i class="vs-icon feather icon-check"></i>
                                                  </span>
                                                </span>
                                                <span class=""> I accept the terms & conditions.</span>
                                                <input v-model="terms" type="checkbox" @change="check($event)" id="agree" name="agree"> <span> I agree with</span>  <a href="#" data-toggle="modal" data-target="#myModal" >Terms and conditions</a>
                                                
                                              </div>
                                            </fieldset> -->
                        <!--  <input v-model="agree" type="checkbox" v-validate="'required'" @change="check($event)" id="agree" name="agree" class="form-check-input { error: errors.has('dob') }" value="" checked>
                                            I accept the terms & conditions.

                                           

                                      <div class="tooltip2" v-show="errors.has('agree')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('agree')">{{ errors.first('agree') }}</span>
                                            </div>
                                        </div>
                                        </div>
                                    </div>-->


                        <!--  <div class="row justify-content-center">
                                      <div class="col-md-6 otp-main">
                                        <div class="col-md-12 col-12  p-0 text-center1">
                                          <button class="btn btn-primary send-otp-btn col-md-12" style="center;" type="button" :disabled="errors.any() || !isCompleteRegister || isActiveBtn==false" @click="sendOtp">Send Otp</button>
                                          <br>

                                            <a type="button" :disabled="errors.any() || !isCompleteRegister || isActiveBtn==false" @click="sendOtp" class="mtb-10">Resend OTP</a>

                                            <br>

                                        </div>

                                      
                                         <button class="btn btn-success send-otp-btn col-md-4" style="center;" type="button" :disabled="errors.any() || !isCompleteRegister || isActiveBtn==false" @click="sendOtp">Resend Otp</button>

                                        <div class="row ">
                                         <div class="col-md-10 m-t-20 text-center1 offset-md-1" v-if="otpSent">
                                           <input type="password" id="otp" name="otp" placeholder="Enter otp" v-model="otp" class="{ error: errors.has('otp') } form-control" v-validate="'required|digits:6'">
                                           <div class="tooltip2" v-show="errors.has('otp')">
                                            <div class="tooltip-inner">
                                              <span v-show="errors.has('otp')">{{ errors.first('otp') }}</span>
                                            </div>
                                          </div>
                                        </div>


                                      </div>
                                    </div>
                                  </div>   -->

                        <div class="captcha mb-2">
                          <div class="recaptcha-spinner">
                            <label>
                              <input
                                v-model="terms"
                                type="checkbox"
                                id="terms"
                                name="terms"
                                v-validate="'required'"
                                data-vv-as="recaptcha"
                                tabindex="3"
                                aria-required="true"
                                aria-invalid="false"
                              />
                              <span class="checkmark"><span>&nbsp;</span></span>
                              <div
                                class="tooltip2"
                                v-show="errors.has('form-1.terms')"
                              >
                                <div class="tooltip-inner">
                                  <span v-show="errors.has('form-1.terms')">{{
                                    errors.first("form-1.terms")
                                  }}</span>
                                </div>
                              </div>
                            </label>
                          </div>
                          <div class="recaptcha-text">I'm not a robot</div>
                          <div class="recaptcha-logo">
                            <img src="public/user_files/images/recaptcha.png" />
                            <p>reCAPTCHA</p>
                          </div>
                        </div><br><br>

                        <div class="row justify-content-center">
                          <!-- <button type="submit" class="btn btn-primary float-right btn-inline mb-50">Register</a></button> -->
                          <div class="col-md-6 offset-lg-0">
                            <button
                              type="submit"
                              :disabled="
                                errors.any() ||
                                !isCompleteRegister ||
                                isActiveBtn == false
                              "
                              value="Register"
                              class="
                                sbmt
                                btn btn-primary
                                float-right
                                btn-inline
                              "
                            >
                              Register
                            </button>

                            <!-- @click="verifyOtp" -->
                          </div>

                          <div class="col-md-12 mtt10">
                            <p>
                              Already have an account?
                              <router-link to="/login" class=""
                                >Login</router-link
                              >
                            </p>
                          </div>
                        </div>
                        <!-- <a href="login.php" class="btn btn-outline-primary float-left btn-inline mb-50">Login</a> -->
                      </form>
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
import "vue-tel-input/dist/vue-tel-input.css";
import VueTelInput from "vue-tel-input";
import { domain } from "./../../user-config/config";
export default {
  components: {
    VueTelInput,
  },
  data() {
    return {
      password_validation_msg: true,
      usermsg1: "",
      weburl: "",
      useractive: true,
      useractive1: false,
      otpSent: false,
      otp: "",
      otpVerified: false,
      isVerifyOtp: "",
      whatsapp_no: "",
      register: {
        user_id: "",
        fullname: "",
        email: "",
        referral_id: "",
        mobile: "",
        //btc_address: "",
        password: "",
        confirm_password: "",
        position: "",
        country: "",

        //secret_question:'',
        // secret_ans:'',
      },
      countries: {
        iso_code: "",
        country: "",
      },
      flag_for_hide_validation_messege: "",
      icon: "../public/user_files/assets/img/icon-mlm.png",
      logo: "../public/user_files/assets/img/logo.png",
      hostName: window.location.origin,
      checkMobileValid: true,
      getcountry: "",
      getMobile: "",
      temp: "",
      selected: true,
      isActiveBtn: true,
      queArr: "",
      terms: "",
      agree: "",
      rand_no: "",
      captcha: "",
      captchamatch: false,
      captchamatcherror: "",
      btc_error: "",
      typingTimer: "",
      doneTypingInterval: 500,
      showpassword: 0,
      alreadyref: 0,
      is_referral: "",
      eye: "fa fa-eye-slash",
    };
  },
  computed: {
    isCompleteRegister() {
      return (
        this.register.user_id &&
        // && !this.btc_error
        this.register.email &&
        this.register.referral_id &&
        this.terms &&
        this.agree &&
        /*  this.register.position &&*/
        this.register.fullname &&
        // this.register.mobile /*&& this.register.position*/ &&
        // this.selected &&
        //this.register.position &&
        this.register.password &&
        this.register.confirm_password && //&&
        //this.register.btc_address &&
        // this.checkMobileValid&&
        this.register.mobile
        // this.register.eth_address
      );
    },
  },
  mounted() {
    this.register.user_id =
      "DX" + parseInt(Math.floor(Math.random() * 90000000) + 10000000);
    this.weburl = domain;
    // alert(this.weburl);
    this.getCountry();
    /*this.getQuestions();*/
    /*this.getRandomNo();*/
    (this.one_letter = "invalid"),
      (this.greater_than_6 = "invalid"),
      (this.one_capital_letter = "invalid"),
      (this.special_char = "invalid"),
      (this.one_number = "invalid"),
      (this.starting_with_letter = "invalid"),
      (this.flag_for_hide_validation_messege = false);
    if (
      this.$route.query.ref_id != undefined &&
      this.$route.query.ref_id != ""
    ) {
      // this.register.referral_id  = this.$route.query.ref_id;
      var uniqueid = this.$route.query.ref_id;
      this.register.position = this.$route.query.position;
      this.getUserId(uniqueid);
      //this.checkuserexist();
      this.alreadyref = 1;
    }
  },
  methods: {
    onInput({ number, isValid, country }) {
      if (number) this.checkMobileValid = isValid;
      this.getcountry = country;
      this.temp = number.split(" ");
      this.temp.shift();
      this.getMobile = this.temp.join("");
    },
    onPasswordClick() {
      this.one_letter = "invalid";
      this.greater_than_6 = "invalid";
      this.one_capital_letter = "invalid";
      this.special_char = "invalid";
      this.one_number = "invalid";
      this.starting_with_letter = "invalid";
      this.password_validation_msg = true;

      if (
        this.register.password.substring(0, 1) ==
        this.register.password.match(/[A-z]/)
      ) {
        this.starting_with_letter = "valid";
      }

      if (
        this.register.password.length >= 6 &&
        this.register.password.length <= 15
      ) {
        this.greater_than_6 = "valid";
      }
      if (this.register.password.match(/[a-z]/)) {
        this.one_letter = "valid";
      }

      if (this.register.password.match(/[A-Z]/)) {
        this.one_capital_letter = "valid";
      }
      if (this.register.password.match(/[0-9]/)) {
        this.one_number = "valid";
      }

      if (this.register.password.match(/[^a-zA-Z0-9\-\/]/)) {
        this.special_char = "valid";
      }
      if (this.register.password.match(/\s/g)) {
        this.special_char = "valid";
      }
      if (
        this.one_letter === "valid" &&
        this.greater_than_6 === "valid" &&
        this.one_capital_letter === "valid" &&
        this.special_char === "valid" &&
        this.one_number === "valid" &&
        this.starting_with_letter === "valid"
      ) {
        //this.flag_for_hide_validation_messege = false;
        this.password_validation_msg = false;
      } else {
        this.errors.add({
          field: "password",
          msg: "password not valid",
        });
        this.password_validation_msg = true;
      }
      if (this.register.password != this.register.confirm_password) {
        /*this.errors.add('this.password_confirmation', 'not match')*/
        this.errors.add({
          field: "confirm_password",
          msg: "password does not match",
        });
      }
    },
    getCountry() {
      axios
        .get("country", {})
        .then((response) => {
          this.countries = response.data.data;
        })
        .catch((error) => {});
    },
    getQuestions() {
      axios
        .get("get-questions", {})
        .then((response) => {
          this.queArr = response.data.data;
        })
        .catch((error) => {});
    },
    sendOtp() {
      if (this.register.position == "") {
        this.$toaster.error("Please Select Position");
        this.disablebtn = 0;
        /*$('#submit').prop('disabled', false);*/
        return false;
      }
      axios
        .post("send-registration-otp", {
          /*  alert(whatsapp_no);*/
          /*mobile_number: this.register.mobile,*/
          /* alert(whatsapp_no);*/
          email: this.register.email,
          user_id: this.register.user_id,
        })
        .then((response) => {
          if (response.data.code == 200) {
            this.otpVerified = false;
            this.$toaster.success(response.data.message);
            this.otpSent = true;
          } else {
            this.$toaster.error(response.data.message);
          }
        })
        .catch((error) => {
          this.message = "";
        });
    },
    verifyOtp() {
      axios
        .post("verify-registration-otp", {
          mobile: this.register.mobile,
          user_id: this.register.user_id,
          otp: this.otp,
        })
        .then((response) => {
          if (response.data.code == 200) {
            this.otpVerified = true;
            this.$toaster.success(response.data.message);
            this.otpSent = true;
            this.optVerified = true;
            this.registerUser();
            //  this.mobileNotEditable = true;
            //  $("#whatsapp_no").prop("readonly",true);
          } else {
            this.$toaster.error(response.data.message);
          }
        })
        .catch((error) => {
          this.message = "";
        });
    },
    getRandomNo() {
      axios
        .get("generate-random-no", {})
        .then((response) => {
          this.rand_no = response.data.data;
        })
        .catch((error) => {});
    },

    check($event) {
      if ($("#agree").prop("checked") == true) {
        this.agree = 1;
      } else {
        this.agree = 0;
      }
    },

    checkuserexist(para) {
      // alert(para);
      if (para == 1) {
        var user = this.register.user_id;
      } else {
        var user = this.register.referral_id;
      }
      axios
        .post("checkuserexist", {
          user_id: user,
        })
        .then((response) => {
          if (response.data.code == 200) {
            if (para == 1) {
              this.useractive1 = true;
              this.usermsg1 = "user Already exist with this id";
              this.isActiveBtn = false;
            } else {
              this.useractive = true;
              if (this.useractive1 == false) {
                this.isActiveBtn = true;
              }
            }
          } else {
            if (para == 1) {
              this.useractive1 = false;
              if (this.useractive == true) {
                this.isActiveBtn = true;
              }
              //this.isActiveBtn = true;
              this.usermsg1 = response.data.message;
            } else {
              this.useractive = false;
              this.isActiveBtn = false;
              this.usermsg = response.data.message;
            }
          }
        })
        .catch((error) => {});
    },

    getUserId() {
      axios
        .post("get-user-id", {
          uid: this.$route.query.ref_id,
        })
        .then((response) => {
          if (response.data.code == 200) {
            this.register.referral_id = response.data.data;
            $("#referral_id").prop("readonly", true);
            $("#s_id").attr("disabled", "disabled");
            this.useractive = true;
          } else {
            this.useractive = false;
            this.usermsg = response.data.message;
          }
        })
        .catch((error) => {});
    },
    matchCaptcha() {
      if (parseInt(this.captcha) != parseInt(this.rand_no)) {
        /*this.errors.add({
                        field: 'captcha',
                        msg: 'Captcha not match'
                        });*/
        // alert();
        this.captchamatch = false;
        this.captchamatcherror = "captcha not match";
        // return false;
      } else {
        // alert();
        this.captchamatcherror = "";
        this.captchamatch = true;
        /*this.errors.remove({
                        field: 'captcha',
                        
                        });*/
        // return true;
      }
    },
    registerUser() {
      this.isActiveBtn = false;
      // var password = this.register.password;
      // var confirm_password = this.register.confirm_password;
      // if (password == confirm_password) {
      axios
        .post("register", {
          user_id: this.register.user_id,
          email: this.register.email,
          mobile: this.register.mobile,
          fullname: this.register.fullname,
          country: this.getcountry.iso2,
          ref_user_id: this.register.referral_id,
          password: this.register.password,
          password_confirmation: this.register.confirm_password,
          position: this.register.position,
          address: "",
          //btc_address: this.register.btc_address,
          //secret_question: this.register.secret_question,
          // secret_ans: this.register.secret_ans,
        })
        .then((resp) => {
          if (resp.data.code == 200) {
            $("#registerForm").trigger("reset");
            this.$toaster.success(resp.data.message);
            this.disablebtn = false;
            this.$router.push({
              name: "thankyou",
              params: {
                user_id: resp.data.data.userid,
                password: resp.data.data.password,
              },
            });
          } else {
            this.$toaster.error(resp.data.message);
          }
          this.isActiveBtn = true;
        })
        .catch((err) => {});
    },
    getPackages() {
      axios
        .get("get-packages", {})
        .then((response) => {
          var INR = response.data.data[0]["convert"];
          var type = response.data.data[0]["type"];
          localStorage.setItem("INR", INR);
          localStorage.setItem("p_type", type);
          return true;
        })
        .catch((error) => {});
    },
    reset() {
      this.user.user_id = "";
      this.user.password = "";
    },
    getReferralId() {
      $("#referral_id").val("Williamson");
    },
    matchpassword() {
      if (this.register.password != this.register.confirm_password) {
        /*this.errors.add('this.password_confirmation', 'not match')*/
        this.errors.add({
          field: "confirm_password",
          msg: "password does not match",
        });
        // this.password_validation_msg = true;
      } else {
        // this.password_validation_msg = false;
      }
    },
    /* check($event){
      if($("#agree").prop('checked') == true){
        this.terms = 1;
      }else{
        this.terms = 0;
      } 
    },*/
    showPass() {
      if (this.showpassword == 0) {
        document.getElementById("password").type = "text";
        this.eye = "fa fa-eye";
        this.showpassword = 1;
      } else if (this.showpassword == 1) {
        document.getElementById("password").type = "password";
        this.showpassword = 0;
        this.eye = "fa fa-eye-slash";
      }
    },

    checkValidBTCAddr() {
      let that = this;
      that.isValidBTCAddress = false;
      that.btc_error = false;
      $(".loaderD").html(
        'Checking BTC Address..<i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="font-size:16px"></i>'
      );
      clearTimeout(this.typingTimer);

      if (/\s/.test(that.register.btc_address)) {
        that.errors.remove("btc_address");
        that.errors.add({
          field: "btc_address",
          msg: "Spaces not allowed in address",
        });
        $(".loaderD").html("");
        return false;
      } else {
        that.errors.remove("btc_address");
      }
      /*that.errors.remove('btc_address'); 
         that.errors.add({field: 'btc_address', msg: 'Enter valid BTC address.'});*/

      that.typingTimer = setTimeout(function () {
        if (that.register.btc_address == "") {
          $(".loaderD").html("");
          that.isValidBTCAddress = true;
          that.btc_error = false;
          that.errors.remove("btc_address");
        } else {
          that.errors.add({
            field: "btc_address",
            msg: "checking for valid btc address",
          });
          that.isValidBTCAddress = false;
          axios
            .post("check_address1", {
              address: that.register.btc_address,
              network_type: "BTC",
            })
            .then((response) => {
              if (response.data.code === 200) {
                $(".loaderD").html("");
                that.isValidBTCAddress = true;
                that.btc_error = false;
                that.errors.remove("btc_address");
              } else {
                $(".loaderD").html("");
                that.btc_error = true;
                that.isValidBTCAddress = false;
                that.errors.remove("btc_address");
                that.errors.add({
                  field: "btc_address",
                  msg: "Enter valid BTC address.",
                });
              }
            })
            .catch((error) => {});
        }
      }, this.doneTypingInterval);
    },
    checkAddress() {
      $(".loaderD").html(
        'Checking BTC Address..<i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="font-size:16px"></i>'
      );
      clearTimeout(this.typingTimer);
      let that = this;
      that.typingTimer = setTimeout(function () {
        that.btc_error = false;
        if (that.register.btc_address == "") {
          that.btc_error = false;
          $(".loaderD").html("");
        } else {
          axios
            .post("check_address1", {
              address: that.register.btc_address,
              network_type: "BTC",
            })
            .then((response) => {
              if (response.data.code == 200) {
                $(".loaderD").html("");
                that.btc_error = false;
              } else {
                that.btc_error = true;
                $(".loaderD").html("");
              }
            })
            .catch((error) => {});
        }
      }, this.doneTypingInterval);
    },
    check1() {
      if (this.is_referral == true) {
        this.register.referral_id = "Williamson";
        this.checkuserexist();
      } else {
        this.register.referral_id = "";
        this.sponsor_name = "";
      }
    },
  },
};
</script>