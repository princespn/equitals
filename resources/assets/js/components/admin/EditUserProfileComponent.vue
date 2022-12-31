<template>
  <div class="content">
    <div class>
      <div class="page-header-title">
        <h4 class="page-title">Edit Profile</h4>
      </div>
    </div>

    <div class="page-content-wrapper">
      <div class="container">
        <div class="row">
          <div class="col-md-12 col-sm-3">
            <div class="panel panel-primary">
              <div class="panel-body add-new">
                <a
                  class="btn btn-primary waves-effect waves-light pull-right"
                  href="DQegXMJQD@rM9YZo3Gn@9ePfjEUP@YHU#/user/manage-user-account"
                >
                  <i class="fa fa-mail-reply"></i> &nbsp;Back
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-12">
            <div class="panel panel-primary">
              <div class="panel-body">
                <h4 class="m-t-0 m-b-30">Personal Information</h4>
                <form class="form-horizontal" v-on:submit.prevent="onUpdateUserClick">
                  <input type="hidden" name="id" v-model="editUser.id" />
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">
                        User Idr
                        <!-- <span class="madatoryStar text-danger text-danger">*</span> -->
                      </label>
                      <div class="col-md-7">
                        <input
                          class="form-control"
                          placeholder="User ID"
                          readonly
                          type="text"
                          v-model="editUser.user_id"
                        />
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label" for="example-email">
                        Sponsor Id
                        <!-- <span class="madatoryStar text-danger"> *</span> -->
                      </label>
                      <div class="col-md-7">
                        <input
                          class="form-control"
                          placeholder="User ID"
                          readonly
                          type="text"
                          v-model="editUser.sponsor_id"
                        />
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">
                        Full Name
                        <span class="madatoryStar text-danger">*</span>
                      </label>
                      <div class="col-md-7">
                        <input
                          name="fullname"
                          class="form-control"
                          placeholder="Update Full Name"
                          type="text"
                          v-model="editUser.fullname"
                        />
                        <div class="tooltip2" v-show="errors.has('fullname')">
                          <div class="tooltip-inner">
                            <span v-show="errors.has('fullname')">{{ errors.first('fullname') }}</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                   <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-5 control-label" for="example-mobile"> Mobile
                                <span class="madatoryStar text-danger"> *</span>
                            </label>
                            <div class="col-md-7">
                                <input name="mobile" class="form-control" id="mobile" placeholder="Enter Mobile" required type="text" v-model="editUser.mobile" v-validate="'required'">
                                <div class="tooltip2" v-show="errors.has('mobile')">
                                    <div class="tooltip-inner">
                                        <span v-show="errors.has('mobile')">{{ errors.first('mobile') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label" for="example-email">
                        Email
                        <span class="madatoryStar text-danger">*</span>
                      </label>
                      <div class="col-md-7">
                        <input
                          class="form-control"
                          name="email"
                          placeholder="Update Email"
                          type="text"
                          v-model="editUser.email"
                          v-validate="'required'"
                        />
                        <div class="tooltip2" v-show="errors.has('email')">
                          <div class="tooltip-inner">
                            <span v-show="errors.has('email')">{{ errors.first('email') }}</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">BTC Address</label>
                      <div class="col-md-7">
                        <input
                          class="form-control"
                          placeholder="Update BTC Address"
                          type="text"
                          v-model="editUser.btc_address"
                        />
                      </div>
                    </div>
                  </div>
                    <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">LTC Address</label>
                      <div class="col-md-7">
                        <input
                          class="form-control"
                          placeholder="Update LTC Address"
                          type="text"
                          v-model="editUser.ltc_address"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">TRON Address</label>
                      <div class="col-md-7">
                        <input
                          class="form-control"
                          placeholder="Update TRON Address"
                          type="text"
                          v-model="editUser.trn_address"
                        />
                      </div>
                    </div>
                  </div>
                   <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">USDT Address</label>
                      <div class="col-md-7">
                        <input
                          class="form-control"
                          placeholder="Update USDT Address"
                          type="text"
                          v-model="editUser.usdt_taddress"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Dogecoin Address</label>
                      <div class="col-md-7">
                        <input
                          class="form-control"
                          placeholder="Update Dogecoin Address"
                          type="text"
                          v-model="editUser.doge_address"
                        />
                      </div>
                    </div>
                  </div>
                   <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">SOL Address</label>
                      <div class="col-md-7">
                        <input
                          class="form-control"
                          placeholder="Update SOL Address"
                          type="text"
                          v-model="editUser.solona_address"
                        />
                      </div>
                    </div>
                  </div>  
                     <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">ETH Address</label>
                      <div class="col-md-7">
                        <input
                          class="form-control"
                          placeholder="Update ETH Address"
                          type="text"
                          v-model="editUser.ethereum"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">XRP Address</label>
                      <div class="col-md-7">
                        <input
                          class="form-control"
                          placeholder="Update XRP Address"
                          type="text"
                          v-model="editUser.ripple_address"
                        />
                      </div>
                    </div>
                  </div>      
                  <!-- <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">BNB.BSC Address</label>
                      <div class="col-md-7">
                        <input
                          class="form-control"
                          placeholder="Update BNB.BSC Address"
                          type="text"
                          v-model="editUser.bnb_bsc_address"
                        />
                      </div>
                    </div>
                  </div> -->
                   <!-- <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Litecoin Address</label>
                      <div class="col-md-7">
                        <input
                          class="form-control"
                          placeholder="Update Litecoin Address"
                          type="text"
                          v-model="editUser.ltc_address"
                        />
                      </div>
                    </div>
                  </div> -->
                <!--  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">ETH Address</label>
                      <div class="col-md-7">
                        <input
                          class="form-control"
                          placeholder="Update ETH Address"
                          type="text"
                          v-model="editUser.ethereum"
                        />
                      </div>
                    </div>
                  </div>     -->
                  <!-- <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">TRON Address</label>
                      <div class="col-md-7">
                        <input
                          class="form-control"
                          placeholder="Update TRON Address"
                          type="text"
                          v-model="editUser.trx_address"
                        />
                      </div>
                    </div>
                  </div> -->
                   <!-- <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Dogecoin Address</label>
                      <div class="col-md-7">
                        <input
                          class="form-control"
                          placeholder="Update Dogecoin Address"
                          type="text"
                          v-model="editUser.doge_address"
                        />
                      </div>
                    </div>
                  </div> -->

                  <!-- <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">USDT-TRC20 Address</label>
                      <div class="col-md-7">
                        <input
                          class="form-control"
                          placeholder="Update USDT-TRC20 Address"
                          type="text"
                          v-model="editUser.usdt_trc20_address"
                        />
                      </div>
                    </div>
                  </div> -->
               

            <!--  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Paypal Address</label>
                      <div class="col-md-7">
                        <input
                          class="form-control"
                          placeholder="Update Paypal Address"
                          type="text"
                          v-model="editUser.paypal_address"
                          v-validate="'email'"
                          name="paypal_address"
                        />

                        <div class="tooltip2" v-show="errors.has('paypal_address')">
                          <div class="tooltip-inner">
                            <span v-show="errors.has('paypal_address')">{{ errors.first('paypal_address') }}</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div> -->

               <!--     <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="example-email"> Perfect Money Address
 </label>
                                            <div class="col-md-7">
                                                <input id="address" name="address" class="form-control" placeholder="Update address" type="text" v-model="editUser.perfect_money_address">
                                            </div>
                                        </div>
                                    </div> -->

                  <!--  
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"> Nominee Relation </label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update Nominee Name" type="text" v-model="editUser.relation">
                                            </div>
                                        </div>
                                    </div>                                    

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"> State </label>
                                            <div class="col-md-7">
                                                <select class="form-control" v-model="editUser.state">
                                                    <option selected="" value="">Select State</option>
                                                    <option :value="sta.name" v-for="sta in arrStates">{{sta.name}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="example-email"> City </label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update City" type="text" v-model="editUser.city">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="clearfix"></div>
                                    <hr>

                                    <h4 class="m-t-0 m-b-30"> Bank Information </h4>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"> Account No </label>
                                            <div class="col-md-7">
                                                <input class="form-control" name="account_no" placeholder="Update Account No" type="text" v-model="editUser.account_no">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"> A/C Name </label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update A/C Name" type="text" v-model="editUser.holder_name">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"> Bank Name </label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update Bank Name" type="text" v-model="editUser.bank_name">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="example-email"> Branch Name </label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update Branch Name" type="text" v-model="editUser.branch_name">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"> PAN Number </label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update PAN Number" type="text" v-model="editUser.pan_no">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 m-b-30">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="example-email">IFSC Code</label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update IFSC Code" type="text" v-model="editUser.ifsc_code">
                                            </div>
                                        </div>
                                    </div>

                  -->


                   <div class="col-md-6">
                                        <div class="">
                                            <label class="col-md-5 control-label"> Country </label>
                                            <div class="col-md-7">
                                                <select class="form-control" v-model="editUser.country">
                                                    <option :value="null">Select</option>
                                                    <option :value="cntry.iso_code" v-for="cntry in arrCountry">{{cntry.country}}</option>
                                                </select>
                                            </div>
                                        </div>

                  </div>
                             
                                   <!--  <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="example-email"> Address </label>
                                            <div class="col-md-7">
                                                <input id="address" name="address" class="form-control" placeholder="Update address" type="text" v-model="editUser.address">
                                            </div>
                                        </div>
                                    </div> -->
                             

                                   <!--  <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="example-email"> City </label>
                                            <div class="col-md-7">
                                                <input id="city" name="city" class="form-control" placeholder="Update City" type="text" v-model="editUser.city">
                                            </div>
                                        </div>
                                    </div>  -->

                  <div class="clearfix form-actions">
                    <div class="col-md-offset-5 col-md-6">
                      <div class="col-md-6">
                        <button class="btn btn-info col-md-12" name="submit" @click.prevent="sendOTP()">
                          <i class="ace-icon fa fa-check bigger-110"></i>Submit
                        </button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

          <!-- Popup start -->
        <div id="editBankDetailsmodal" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" class="modal">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Enter OTP</h4>

                       <button type="button" class="close btn" data-dismiss="modal" @click="closePopup('editBankDetailsmodal')">&times;</button>
                    </div>
                    <div class="modal-body">
                      <div class="form-group">
                        <div class="row">
                          <div class="col-md-12">
                            <input type="text" name="otp" placeholder="Enter OTP" v-model="editUser.otp" class="form-control" aria-required="true" aria-invalid="false">
                            <div class="tooltip2" v-show="errors.has('editUser.otp')">
                              <div class="tooltip-inner"> <span v-show="errors.has('otp')">{{ errors.first('editUser.otp') }}</span>
                              </div>
                            </div>
                          </div>
                          <br>
                          <br>
                          <br>
                          <br>
                          <div class="clearfix"></div>
                          <div class="col-md-12">
                            <center>
                              <button type="button" @click="verifyOtp()" class="btn btn-primary">Submit</button>
                            </center>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
               <!-- Popup end -->
      </div>
    </div>
  </div>
</template>

<script>
import { apiAdminHost } from "./../../admin-config/config";
import Swal from "sweetalert2";
import DatePicker from "vuejs-datepicker";
import moment from "moment";

export default {
  data() {
    return {
      arrCountry: [],
      arrStates: [],
      editUser: {
        id: "",
        user_id: "",
        country: null,
        sponsor_id: "",
        fullname: "",
        mobile: "",
        email: "",
        btc_address: "",
        address: "",
        city: "",
        paypal_address:"",
        trn_address:"",
        doge_address:"",
      //  usdt_trc20_address:"",
        usdt_taddress: "",
        ethereum:"",
        bnb_bsc_address:"",
        ltc_address: "",
        solona_address: "",
        ripple_address: "",
        // relation: '',
        // contact_number: '',
        // state: '',
        // city: '',
        // account_no: '',
        // holder_name: '',
        // bank_name: '',
        // branch_name: '',
        // pan_no: '',
        // ifsc_code: '',
        // paytm_no: '',
        // payzapp_no: '',
        // insurance_amt: '',
      }
    };
  },
  components: {
    DatePicker
  },
  computed: {},
  mounted() {
    this.getUserDetails();
    this.getCountry();
  },
  methods: {
    dateFormat(date) {
      return moment(date).format("DD-MM-YYYY");
    },
    getUserDetails() {
      axios
        .post("/getuserprofile", {
          id: this.$route.params.id
        })
        .then(resp => {
          if (resp.data.code === 200) {
            // console.log(resp.data.data.trx_address);
            this.editUser = {
              id: resp.data.data.userProfile.id,
              user_id: resp.data.data.userProfile.user_id,
              sponsor_id: resp.data.data.userProfile.sponser_id,
              country: resp.data.data.userProfile.iso_code,
              fullname: resp.data.data.userProfile.fullname,
              mobile: resp.data.data.userProfile.mobile,
              email: resp.data.data.userProfile.email,
              btc_address: resp.data.data.btc_address,
              ltc_address: resp.data.data.ltc_address,
              ethereum: resp.data.data.eth_address,
              ripple_address: resp.data.data.xrp_address,


              /* address: resp.data.data.userProfile.address,
              city: resp.data.data.userProfile.city,*/
              trn_address: resp.data.data.trx_address,
              doge_address: resp.data.data.doge_address,
              usdt_trc20_address: resp.data.data.usdt_trc20_address,
              // ethereum: resp.data.data.userProfile.eth_address,
              bnb_bsc_address: resp.data.data.bnb_bsc_address,
              usdt_taddress: resp.data.data.usdt_address,
              solona_address: resp.data.data.sol_address,
             
              
           /*   paypal_address: resp.data.data.userProfile.paypal_address,
              perfect_money_address: resp.data.data.userProfile.perfect_money_address*/

              // relation: resp.data.data.userProfile.relation,
              // contact_number: resp.data.data.userProfile.contact_number,
              // state: resp.data.data.userProfile.state,
              // city: resp.data.data.userProfile.city,
              // account_no: resp.data.data.userProfile.account_no,
              // holder_name: resp.data.data.userProfile.holder_name,
              // bank_name: resp.data.data.userProfile.bank_name,
              // branch_name: resp.data.data.userProfile.branch_name,
              // pan_no: resp.data.data.userProfile.pan_no,
              // ifsc_code: resp.data.data.userProfile.ifsc_code,
            };
          } else {
            this.$toaster.error(resp.data.message);
          }
        })
        .catch(err => {
          this.$toaster.error(err);
        });
    },
    onUpdateUserClick() {
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
            .post("/updateuser", this.editUser)
            .then(resp => {
              if (resp.data.code === 200) {
                this.$router.push({ name: "manageuser" });
                this.$toaster.success(resp.data.message);
              } else {
                this.$toaster.error(resp.data.message);
              }
            })
            .catch(err => {
              this.$toaster.error(err.resp.data.message);
            });
        }
      });
    },

    getCountry() {
      axios
        .get("/getcountry")
        .then(resp => {
          if (resp.data.code === 200) {
            this.arrCountry = resp.data.data;
          } else {
            this.$toaster.error(resp.data.message);
          }
        })
        .catch(err => {
          this.$toaster.error(err);
        });
    },
     sendOTP() {
      var arr = {};
      axios
        .post("sendOtp-add-topup", {otp_type:9})
        .then((response) => {
          if (response.data.code == 200) {
            //console.log(response);
            this.$toaster.success(response.data.message);
            //this.statedata=response.data.data.message;
            this.editUser.otp = "";
            $("#editBankDetailsmodal").modal("show");
          } else {
            this.$toaster.error(response.data.message);
          }
        })
        .catch((error) => {});
    },
    closePopup(){
      $("#editBankDetailsmodal").modal("hide");
    },
    verifyOtp() {
      //  alert(this.editUser.otp);
      axios.post('/updateuser',this.editUser)
        .then((response) => {
          if (response.data.code === 200) {
            this.$toaster.success(response.data.message);
            $("#editBankDetailsmodal").modal("hide");
            this.$router.push({ name: "manageuser" });
            
          } else {
            this.$toaster.error(response.data.message);
          }
        }).catch(err => {
        this.$toaster.error(err);
      });
    },
    // onChangeCountry(countryName){
    //     axios.post('/getstatebycountry',{
    //         country:countryName
    //     }).then(resp => {
    //         if(resp.data.code === 200){
    //             this.arrStates = resp.data.data;
    //         } else {
    //             this.$toaster.error(resp.data.message);
    //         }
    //     }).catch(err => {
    //         this.$toaster.error(err);
    //     })
    // }
  }
};
</script>