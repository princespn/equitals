<template>
  <div class="main-content">
    <div class="page-content">
      <div class="col-lg-10 col-md-10 col-12" style="margin: auto;"> 
        <div class="card card-payment">
          <div class="card-header">
            <h4 class="card-title">Transfer Deposit Wallet Balance</h4>
            <!-- <h4 class="card-title text-primary">$455.60</h4> -->
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-content">
                    <div class="card-body card-dashboard">
                      <div class="row">
                       <!--  <div class="text-center col">
                          <div class="reward-income-bg-amt">
                            <h2 class="mb-1 font-bold">${{ (fundtransfer.top_up_wallet).toFixed(2) }}</h2>
                            <span>Purchase Income</span>
                          </div>
                        </div> -->
                        <!-- <div class="text-center col">
                          <div class="reward-income-bg-amt">
                            <h2 class="mb-1 font-bold">${{ fundtransfer.working_Wallet_balance }}</h2>
                            <span>Account Wallet Balance</span>
                          </div>
                        </div> -->
                        <div class="text-center col">
                          <div class="reward-income-bg-amt">
                            <span class="card-title">Deposit Wallet Balance</span>
                            <h2 class="mb-1 font-bold card-title text-primary">${{ topup_bal.toFixed(2) }}</h2>
                          </div>
                        </div>
                      </div>
                      <div class="top-bordr3 m-t-30 m-b-30"></div>
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="control-label">User ID</label>
                              <br />
                              <input
                                class="form-control" formcontrolname="touser-id" id="touser-id" name="touser-id" placeholder="Enter User Id" type="text" v-model="fundtransfer.touser_id" v-on:input="checkUserExisted"/>
                              <div class="clearfix"></div>
                              <p :class="{'text-success': isAvialable == 'Available','text-danger': isAvialable == 'Not Available'}" v-if="isAvialable!=''">{{isAvialable}}</p>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="control-label">Full Name</label>
                              <br />
                              <input class="form-control" formcontrolname="fullname" id="fullname" name="fullname" placeholder="Full Name" type="text" v-model="fullname" disabled readonly />
                              <div class="clearfix"></div>
                              
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="control-label">Enter Amount</label>
                              <br />
                              <input id="transfer-amount" name="transfer-amount" v-model="fundtransfer.transfer_amount" class="form-control" formcontrolname="touser-id" placeholder="amount" v-validate="'required|numeric'" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" type="number" min="25" step="25" title="Numbers only" aria-required="true" aria-invalid="false"/>
                              <div class="tooltip2" v-show="errors.has('transfer-amount')">
                                <div class="tooltip-inner">
                                  <span v-show="errors.has('transfer-amount')">{{ errors.first('transfer-amount') }}</span>
                                </div>
                              </div>
                            </div>     
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="control-label">Remark</label>
                              <br />
                              <input class="form-control" formcontrolname="remark" id="remark" name="remark" placeholder="Remark" type="text" v-model="fundtransfer.remark"  />
                              <div class="clearfix"></div>
                            </div>
                          </div>
                        </div>
                        <div class="panel-footer bg-gray text-center">
                          <div class="col-lg-12"><br>
                            <button class="btn btn-primary" type="button" @click.prevent="sendOTP()" :disabled="!isCompleteTransferFund || errors.any() || !btndisabled || !useractive" id="topupsub">Submit</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Popup start -->
        <div class="modal" id="editBankDetailsmodal" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">

            <div class="modal-content">

              <div class="modal-header">
                <h4 class="modal-title">Enter OTP</h4>
                <button type="button" class="close" @click="closePopup()" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-12">

                      <input type="text" name="otp" class="form-control" placeholder="Enter OTP" v-model="otp" v-validate="'required'"/>

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
                        <button  @click="verifyOtp()" type="button" class="btn btn-primary">Submit</button>
                      </center>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Popup End -->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment";
import { apiUserHost } from "../../user-config/config";
import Breadcrum from "./BreadcrumComponent.vue";
import Swal from "sweetalert2";
export default {
  components: {
    Breadcrum
  },
  data() {
    return {
      useractive: true,
      isAvialable: "",
      fundtransfer: {
        top_up_wallet: 0,
        top_up_wallet_withdraw: 0,
        top_up_Wallet_balance: 0
      },
      otpVerified:false,
      otpSent:false,
      otp:'',
      fullname:'',
      topup_bal:0,
      remark : '',
      username:'',
      btndisabled:true
    };
  },
  computed: {
    isCompleteTransferFund() {
      return this.fundtransfer.touser_id && this.fundtransfer.transfer_amount ;
    }
  },
  mounted() {
    this.getTopupDetails();
  },
  methods: {
    getTopupDetails() {
      axios.post("get-wallet-balance", {'wallet_type':'fund_wallet'})
        .then(response => {
          this.topup_bal = response.data.data;
        })
        .catch(error => {});
    },

    checkuserexist() {
      axios
        .post("checkuserexist", {
          user_id: this.fundtransfer.touser_id
        })
        .then(response => {
          if (response.data.code == 200) {
            this.useractive = true;
          } else {
            this.useractive = false;
            this.usermsg = response.data.message;
          }
        })
        .catch(error => {});
    },

    checkUserExisted() {
      axios
        .post("checkuserexist", {
          user_id: this.fundtransfer.touser_id
        })
        .then(response => {
          if (response.data.code == 200) {
              //alert(1);
              // this.paid = res.data.data;
               this.fullname = response.data.data.fullname;
              // this.address = response.data.data.address;
              // this.mobile = response.data.data.mobile;
              // this.sp_user_id = response.data.data.sponser_id;
              // this.sp_fullname = response.data.data.sponser_fullname;
              this.useractive = true;
              this.isAvialable = "Available";
            } else {
              // alert(res.data.message)
              // this.paid = '';
              // this.isdownline = 0;
              this.fullname = '';
              // this.address = '';
              // this.mobile = '';
              // this.sp_user_id = '';
              // this.sp_fullname = '';
              this.useractive = false;
              /*this.topup.user_id = "";*/
              this.isAvialable = 'Not Available';
              this.isAvialable = res.data.message;
            
          }
        })
        .catch(error => {
          console.log(error);
        });
    },

    transferFund() {
      axios.post("purchase-to-purchase-transfer", {
        to_user_id: this.fundtransfer.touser_id,
        amount: this.fundtransfer.transfer_amount,
        remark : this.fundtransfer.remark,
        topup_wallet_bal: this.fundtransfer.top_up_Wallet_balance
      })
      .then(response => {
        if (response.data.code == 200) {
          this.otp = '';
          $('#editBankDetailsmodal').modal('hide');          
          this.$toaster.success(response.data.message); 
          this.btndisabled = true;         
          this.$router.push("/purchase-to-purchase-report");
        } else {
          this.$toaster.error(response.data.message);
          this.btndisabled = true;
        }
      });
    },

    sendOTP(){
      if(Number(this.topup_bal) < this.fundtransfer.transfer_amount){
        this.$toaster.error('Insufficient balance');
        return false;
      }
      this.btndisabled = false;
      axios.post('sendOtp-update-user-profile'/*,{type:'Withdrawal'}*/).then(response=>{

            if(response.data.code == 200){
                //console.log(response);
                this.$toaster.success(response.data.message);
                //this.statedata=response.data.data.message;
            $('#editBankDetailsmodal').modal('show');

            }else{                 
              this.$toaster.error(response.data.message);
            }
        }).catch(error=>{
        })  
    },

    verifyOtp(){
      axios.post('verify-user-otp', {
          otp : this.otp
      }).then(response => {
        if (response.data.code == 200) {
          this.otpVerified = true;
          this.$toaster.success(response.data.message);
          this.otpSent = true;
          this.optVerified = true;
          this.transferFund();
        } else {
          this.$toaster.error(response.data.message);
          this.btndisabled = true;
        }
      }).catch(error => {
          this.message = '';
      });
    }, 
    closePopup(){
      $("#editBankDetailsmodal").modal("hide");
    },   
  }
};
</script>