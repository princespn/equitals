<style type="text/css">
  .modal-backdrop.fade.show {
    opacity: 0;
}
</style>
<template>
  <div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
      <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h2 class="content-header-title float-left mb-0">Edit Profile</h2>
            </div>
          </div>
        </div>
        <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
          <div class="form-group breadcrum-right">
            <div class="dropdown">
              <button
                class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle"
                type="button"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="feather icon-settings"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#">Chat</a>
                <a class="dropdown-item" href="#">Email</a>
                <a class="dropdown-item" href="#">Calendar</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="content-body">
        <section id="multiple-column-form">
          <div class="row match-height">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <!-- <h4 class="card-title">Edit Profile</h4> -->
                </div>
                <div class="card-content mt-20">
                  <div class="card-body">
                    <form class="form">
                      <div class="form-body edit_prof_o">
                        <div class="row">
                          <div class="col-md-4 col-12">
                            <div class="form-label-group">
                              <label class="control-label">Full Name</label>
                              <input
                                type="text"
                                class="form-control"
                                name="fullname"
                                v-model="profile.fullname"
                                v-validate="'required|alpha_spaces'"
                              />
                              <div class="tooltip2" v-show="errors.has('fullname')">
                                <div class="tooltip-inner">
                                  <span
                                    v-show="errors.has('fullname')"
                                  >{{ errors.first('fullname') }}</span>
                                </div>
                              </div>
                              <label for="full-name">Full Name</label>
                            </div>
                          </div>
                          <div class="col-md-4 col-12" v-show="topupdata">
                            <div class="form-label-group" v-show="topupdata"> 
                              <label class="control-label">Email</label>
                              <input
                                :readonly="profile.email!=null"
                                type="email"
                                name="email"
                                class="form-control"
                                v-model="profile.email"
                                v-validate="'required|email'"
                              />
                              <div class="tooltip2" v-show="errors.has('email')">
                                <div class="tooltip-inner">
                                  <span v-show="errors.has('email')">{{ errors.first('email') }}</span>
                                </div>
                              </div>
                              <label for="email-id">Email</label>
                            </div>
                          </div>

                             <div class="col-md-4 col-12" v-show="!topupdata">
                            <div class="form-label-group"  v-show="!topupdata">
                              <label class="control-label">Email</label>
                              <input
                             
                                type="email"
                                name="email"
                                class="form-control"
                                v-model="profile.email"
                                v-validate="'required|email'"
                              />
                              <div class="tooltip2" v-show="errors.has('email')">
                                <div class="tooltip-inner">
                                  <span v-show="errors.has('email')">{{ errors.first('email') }}</span>
                                </div>
                              </div>
                              <label for="email-id">Email</label>
                            </div>
                          </div>
                       <!--    <div class="col-md-4 col-12">
                            <div class="form-label-group">
                              <label class="control-label">Phone Number</label>
                              <input
                                type="text"
                                name="mobile"
                                class="form-control"
                                v-model="profile.mobile"
                                v-validate="'required|digits:10'"
                              />
                              <div class="tooltip2" v-show="errors.has('mobile')">
                                <div class="tooltip-inner">
                                  <span v-show="errors.has('mobile')">{{ errors.first('mobile') }}</span>
                                </div>
                              </div>
                              <label for="phone-number">Phone Number</label>
                            </div>
                          </div> -->

                      <!--     <div class="col-md-4 col-12">
                            <div class="form-label-group">
                              <label class="control-label">Residential Address</label>
                              <textarea
                                name="address"
                                class="form-control"
                                v-model="profile.address"
                                v-validate="''"
                              ></textarea>
                              <div class="tooltip2" v-show="errors.has('address')">
                                <div class="tooltip-inner">
                                  <span v-show="errors.has('address')">{{ errors.first('address') }}</span>
                                </div>
                              </div>
                              <label for="phone-number">Residential Adddress</label>
                            </div>
                          </div> -->

                       <!--    <div class="col-md-4 col-12">
                            <div class="form-label-group">
                              <label class="control-label">City</label>
                              <input
                                type="text"
                                name="city"
                                class="form-control"
                                v-model="profile.city"
                                v-validate="''"
                              />
                              <div class="tooltip2" v-show="errors.has('city')">
                                <div class="tooltip-inner">
                                  <span v-show="errors.has('city')">{{ errors.first('city') }}</span>
                                </div>
                              </div>
                              <label for="phone-number">City</label>
                            </div>
                          </div> -->
                                 <div class="col-md-4 col-12 ">
                                    <div class="form-label-group">
                                         <p class="thh" for="inputMobile">Mobile</p>

                                          <vue-tel-input
                                          @onInput="onInput"
                                          defaultCountry="VG"
                                          placeholder="Enter Mobile Number"
                                          v-model="profile.mobile"
                                          class="mm"></vue-tel-input>
                                      
                                         <div class="tooltip2" v-show="errors.has('mobile')">
                                            <div class="tooltip-inner">
                                              <span v-show="errors.has('mobile')">{{ errors.first('mobile') }}</span>
                                            </div>
                                         </div>

                                       </div>
                                       
                                     </div>
                          <div class="col-md-4 col-12 mt-mob">
                            <div class="form-label-group">
                              <select name="country" class="form-control" v-model="profile.country" id="country" v-validate="'required'">
                                <option :value="null">Select</option>
                                <option v-for="country in countries" v-bind:value="country.iso_code">
                                  {{ country.country }}
                                </option>
                              </select>
                              <!-- <input type="text" name="country" class="form-control" v-model="profile.country" v-validate="'required'"> -->
                              <div class="tooltip2" v-show="errors.has('country')">
                                <div class="tooltip-inner">
                                  <span v-show="errors.has('country')">{{ errors.first('country') }}</span>
                                </div>
                              </div>
                              <label for="country-floating">Country</label>
                            </div>
                          </div>
                          <div class="col-md-4 col-12">
                            <div class="form-label-group">
                              <input
                                :readonly="btc_preset == 0"
                                type="text"
                                name="btc_address"
                                class="form-control"
                                placeholder="Enter BTC address"
                                v-model="profile.btc_address"
                                v-validate="'required'"
                                v-on:input="checkaddress"
                              />
                               <label for="company-column">BTC Address</label>
                              <div v-if="!btcactive" class="tooltip2">
                                <span
                                  class="text-danger error-msg-size tooltip-inner"
                                >{{ this.btcmsg }}</span>
                              </div>
                             
                            </div>
                          </div>


                        <div class="col-md-4 col-12">
                            <div class="form-label-group">
                              <input
                                :readonly="btc_preset == 0"
                                type="text"
                                name="ethereum"
                                class="form-control"
                                placeholder="Enter ETH address"
                                v-model="profile.ethereum"
                                v-validate="'required'"
                                
                              />
                             
                              <label for="company-column">ETH Address</label>
                            </div>
                          </div>
                      <div class="col-md-4 col-12">
                            <div class="form-label-group">
                              <input
                                :readonly="btc_preset == 0"
                                type="text"
                                name="trn_address"
                                class="form-control"
                                placeholder="Enter TRON address"
                                v-model="profile.trn_address"
                                v-validate="'required'"
                               
                              />
                             
                              <label for="company-column">TRON Address</label>
                            </div>
                          </div>
                          <div class="col-md-4 col-12">
                            <div class="form-label-group">
                              <input
                                :readonly="btc_preset == 0"
                                type="text"
                                name="bnb_address"
                                class="form-control"
                                placeholder="Enter BNB address"
                                v-model="profile.bnb_address"
                                v-validate="'required'"
                              
                              />
                             
                              <label for="company-column">BNB Address</label>
                            </div>
                          </div>

                         <!-- <div class="col-md-4 col-12">
                            <div class="form-label-group">
                              <label class="control-label">Perfect Money Address</label>
                              <input
                              :readonly="btc_preset == 0"
                                type="text"
                                name="perfect_money_address"
                                class="form-control"
                                v-model="profile.perfect_money_address"
                                v-validate="''"
                              />
                              <div class="tooltip2" v-show="errors.has('perfect_money_address')">
                                <div class="tooltip-inner">
                                  <span v-show="errors.has('perfect_money_address')">{{ errors.first('perfect_money_address') }}</span>
                                </div>
                              </div>
                              <label for="phone-number">Perfect Money address</label>
                            </div>
                          </div> -->


                         <!--  <div class="col-md-4 col-12">
                            <div class="form-label-group">
                              <label class="control-label">Paypal Address</label>
                              <input
                              
                                type="text"
                                name="paypal_address"
                                class="form-control"
                                v-model="profile.paypal_address"
                                v-validate="'email'"
                              />
                              <div class="tooltip2" v-show="errors.has('paypal_address')">
                                <div class="tooltip-inner">
                                  <span v-show="errors.has('paypal_address')">{{ errors.first('paypal_address') }}</span>
                                </div>
                              </div>
                              <label for="phone-number">PayPal address</label>
                            </div>
                          </div> -->



                   
                      <div class="col-md-4" style="display: none;">&nbsp;</div>

                           
                         <!--   <div class="col-md-12">
                            <p class="hr1"></p>
                             <h3 class="mh1">Others</h3>
                           </div> -->
                      <!--   <div class="col-md-4 col-12">
                            <div class="form-label-group">
                              <label class="control-label">Facebook Link</label>
                              <input
                                type="text"
                                name="facebook_link"
                                class="form-control"
                                v-model="profile.facebook_link"
                                v-validate="''"
                              />
                              <div class="tooltip2" v-show="errors.has('city')">
                                <div class="tooltip-inner">
                                  <span v-show="errors.has('facebook_link')">{{ errors.first('facebook_link') }}</span>
                                </div>
                              </div>
                              <label for="phone-number">Facebook Link</label>
                            </div>
                          </div> -->
                         <!--  <div class="col-md-4 col-12">
                            <div class="form-label-group">
                              <label class="control-label">Linked In Link</label>
                              <input
                                type="text"
                                name="linkedin_link"
                                class="form-control"
                                v-model="profile.linkedin_link"
                                v-validate="''"
                              />
                              <div class="tooltip2" v-show="errors.has('linkedin_link')">
                                <div class="tooltip-inner">
                                  <span v-show="errors.has('linkedin_link')">{{ errors.first('linkedin_link') }}</span>
                                </div>
                              </div>
                              <label for="phone-number">Linked In Link</label>
                            </div>
                          </div> -->
                         <!--  <div class="col-md-4 col-12">
                            <div class="form-label-group">
                              <label class="control-label">Twitter Link</label>
                              <input
                                type="text"
                                name="twitter_link"
                                class="form-control"
                                v-model="profile.twitter_link"
                                v-validate="''"
                              />
                              <div class="tooltip2" v-show="errors.has('twitter_link')">
                                <div class="tooltip-inner">
                                  <span v-show="errors.has('twitter_link')">{{ errors.first('twitter_link') }}</span>
                                </div>
                              </div>
                              <label for="phone-number">Twitter Link</label>
                            </div>
                          </div> -->
                         <!--   <div class="col-md-4 col-12">
                            <div class="form-label-group">
                              <label class="control-label">Instagram Link</label>
                              <input
                                type="text"
                                name="instagram_link"
                                class="form-control"
                                v-model="profile.instagram_link"
                                v-validate="''"
                              />
                              <div class="tooltip2" v-show="errors.has('instagram_link')">
                                <div class="tooltip-inner">
                                  <span v-show="errors.has('instagram_link')">{{ errors.first('instagram_link') }}</span>
                                </div>
                              </div>
                              <label for="phone-number">Instagram Link</label>
                            </div>
                          </div> -->
                          <br>
                          <div class="form-group col-md-4 col-12" style="display: none;">
                            &nbsp;
                          </div>
                          <div class="col-12 col-md-12 text-center">
                           <!--  <fieldset class="checkbox col-md-4">
                              <div class="vs-checkbox-con vs-checkbox-primary">
                                <input type="checkbox" />
                                <span class="vs-checkbox">
                                  <span class="vs-checkbox--check">
                                    <i class="vs-icon feather icon-check"></i>
                                  </span>
                                </span>
                                <span class>Remember me</span>
                              </div>
                            </fieldset> -->
                         <!--    <button
                              type="submit"
                              class="btn btn-primary mr-1 mb-1 col-md-2"
                              @click.prevent="checkOtp"
                              :disabled="!isCompleteProfile"
                            >Update Changes</button>  -->

                             <button
                              type="submit"
                              class="btn btn-primary mr-1 mb-1 col-md-4 kbb-bbt"
                              @click.prevent="sendOTP"
                              :disabled="!isCompleteProfile"
                            >Update Changes</button>
                            <!-- <button type="reset" class="btn btn-outline-warning mr-1 mb-1">Reset</button> -->
                          </div>
                        </div>
                      </div>

                      <button
                        type="button"
                        id="myotp"
                        style="display:none"
                        class="btn btn-primary"
                        data-toggle="modal"
                        data-target="#editBankDetailsmodal"
                      >Launch demo modal</button>
                      <!-- Popup start -->
                      <div class="modal fade" id="editBankDetailsmodal" role="dialog"  data-backdrop="static">
                        <div class="modal-dialog">
                          <!-- Modal content-->
                          <div class="modal-content">
                            <div v-show="otpstatus==false">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Enter OTP</h4>
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

                                      <div class="tooltip2" v-show="errors.has('otp')">
                                        <div class="tooltip-inner">
                                          <span v-show="errors.has('otp')">{{ errors.first('otp') }}</span>
                                        </div>
                                      </div>
                                    </div>

                                    <br />
                                    <br />
                                    <br />
                                    <br />
                                    <div class="clearfix"></div>
                                    <div class="col-md-12">
                                      <center>
                                        <button
                                          @click="checkOtp()"
                                          type="button"
                                          class="btn btn-primary kbb-bbt"
                                        >Submit</button>
                                      </center>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- Popup End -->
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</template>
<script>
import Breadcrum from "./BreadcrumComponent.vue";
import Swal from "sweetalert2";
export default {
  components: {
    Breadcrum
  },
  data() {
    return {
      topupdata:false, 
      btcactive: true,
      otpstatus: "",
      btcmsg: "",
      otp: "",
      checkMobileValid: "",
      temp: "",
      getMobile: "",

      profile: {
        fullname: "",
        email: "",
        mobile: "",
        btc_address: "",
        country: null,
        address: "",
        perfect_money_address: "",
        facebook_link: "",
        linkedin_link: "",
        twitter_link: "",
        instagram_link: "",
        city: "",
        paypal_address:"",
        bnb_address:"",
        ethereum:"",
        bnb_address:"",

      },
      btc_preset: 0,
      profile: [],
      countries: [],
      type: "",
      profiledata: {
        holder_name: "",
        bank_name: "",
        branch_name: "",
        pan_no: "",
        ifsc_code: "",
       // btc_address: "",
        account_no: "",
        city: "",
        country: null,
        address: "",
        facebook_link: "",
        linkedin_link: "",
        instagram_link: "",
        twitter_link: "",
        paypal_address:"",
        bnb_address:"",
        ethereum:"",
        bnb_address:"",
        //btc_preset: 0
      }
    };
  },
  computed: {
    isCompleteProfile() {
      return (
        this.profile.fullname &&
        this.profile.email &&
        this.profile.mobile &&
        this.btcactive &&
        this.profile.country
      );
    }
  },
  mounted() {
    this.readProfile();
    this.getCountry();
    this.gettopup();
    //$("#editBankDetailsmodal").modal("show");

  },
  methods: {
    onInput({ number, isValid, country }) {
       if(number)
        this.checkMobileValid =isValid;
        this.getcountry=country;
        this.temp = number.split(" ");
        this.temp.shift();
        this.getMobile=this.temp.join('');
    },
      gettopup(){
            /*alert('hello');*/
            axios.get('gettopupdata')
                .then(response => {
                    if(response.data.data.topupdata == 1)
                    {
                        this.topupdata = true;
                        this.cat_id = 1;
                       
                    }else{
                        this.topupdata = false;
                        this.cat_id = 0;
                    }   
                }).catch(error => {
            });
        },
    readProfile() {
      axios
        .post("get-profile-info", {})
        .then(response => {
          this.profile = response.data.data;
          $("#editBankDetailsmodal").modal("hide");
          //alert(this.profile.btc_address);
          //this.profiledata = response.data.data;
          if (this.profile.btc_address == '' || this.profile.btc_address == null && this.profile.perfect_money_address == '' || this.profile.perfect_money_address == null) {
            //alert('hii');
            this.btc_preset = 1;
          }else{
           // alert('dfdsfdsf');
            this.btc_preset = 0;
          }
        })
        .catch(error => {});
    },

    checkaddress() {
      this.btcactive = false;
      this.btcmsg = "Bitcoin address is not valid";
      axios
        .post("check_address", {
          network_type: "BTC",
          address: this.profile.btc_address
        })
        .then(response => {

          if (response.data.code == 200) {
            this.btcactive = true;
          } else {
            this.btcactive = false;
            this.btcmsg = response.data.message;
          }
        })
        .catch(error => {});
    },
    getPackages() {
      axios
        .get("get-packages", {})
        .then(response => {
          this.getpackages = response.data.data;
          this.type = response.data.data[0]["type"];
        })
        .catch(error => {});
    },

    updateUserData() {
      Swal({
        title: "Are you sure?",
        text: `You want to update this user`,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes"
      }).then(result => {
        if (result.value) {
          axios
            .post("user-update", {
              fullname: this.profile.fullname,
              email: this.profile.email,
              mobile: this.profile.mobile,
              address: this.profile.address,
              trn_address: this.profile.trn_address,
              ethereum: this.profile.ethereum,
              bnb_address: this.profile.bnb_address,
             /* city: this.profile.city,*/
              /*perfect_money_address: this.profile.perfect_money_address,*/
              btc: this.profile.btc_address,
              country: this.profile.country,
              address: this.profile.address,
            /*  city: this.profile.city,*/
              account_no: this.profiledata.account_no,
              holder_name: this.profiledata.holder_name,
              pan_no: this.profiledata.pan_no,
              bank_name: this.profiledata.bank_name,
              ifsc_code: this.profiledata.ifsc_code,
              
             /* facebook_link: this.profiledata.facebook_link,
              linkedin_link: this.profiledata.linkedin_link,
              twitter_link: this.profiledata.twitter_link,
              instagram_link: this.profiledata.instagram_link,*/
              branch_name: this.profile.branch_name
            })
            .then(response => {
              $("#editBankDetailsmodal").modal("hide");
              if (response.data.code == 200) {
                this.$toaster.success(response.data.message);
              } else {
                this.$toaster.error(response.data.message);
              }
            })
            .catch(error => {});
        }
      });
    },
    getCountry() {
      axios
        .get("country")
        .then(response => {
          this.countries = response.data.data;
        })
        .catch(error => {});
    },
    checkOtp() {
      axios
        .post("checkotp1", {
          otp: this.otp,
          fullname: this.profile.fullname,
          email: this.profile.email,
          mobile: this.profile.mobile,
          btc: this.profile.btc_address,
          trn_address: this.profile.trn_address,
          ethereum: this.profile.ethereum,
          bnb_address: this.profile.bnb_address,
          country: this.profile.country,
          address: this.profile.address,
          city: this.profile.city,
          perfect_money_address: this.profile.perfect_money_address,
          account_no: this.profiledata.account_no,
          holder_name: this.profiledata.holder_name,
          pan_no: this.profiledata.pan_no,
          bank_name: this.profiledata.bank_name,
          ifsc_code: this.profiledata.ifsc_code,
          facebook_link: this.profiledata.facebook_link,
          linkedin_link: this.profiledata.linkedin_link,
          twitter_link: this.profiledata.twitter_link,
          instagram_link: this.profiledata.instagram_link,
        
         /* branch_name: this.profiledata.branch_name,
          paypal_address: this.profile.paypal_address,*/
        })
        .then(response => {
          if (response.data.code == 200) {
            $("#editBankDetailsmodal").modal("show");
            // this.statedata=response.data.data;
            // this.otp = '';
           // $("#myotp").trigger("click");
            //$("#editBankDetailsmodal").modal("hide");
            this.otpstatus = true;
            /*$('#editBankDetails1').modal('show');*/
            this.$toaster.success(response.data.message);
            this.readProfile();
          } else {
            this.$toaster.error(response.data.message);
          }
        })
        .catch(error => {});
    },

    sendOTP() {

         Swal({
        title: "Are you sure?",
        text: `You want to update this user`,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes"
      }).then(result => {
        if (result.value) {
      axios
        .post("sendOtp-update-user-profile")
        .then(response => {
          if (response.data.code == 200) {
            //console.log(response);
            this.$toaster.success(response.data.message);
            //this.statedata=response.data.data.message;
            $("#editBankDetailsmodal").modal("show");
          } else {
            this.$toaster.error(response.data.message);
          }
        })
        .catch(error => {});

          }
      });
    }
  }
};
</script>