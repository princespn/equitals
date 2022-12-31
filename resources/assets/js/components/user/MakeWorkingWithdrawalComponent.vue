<template>
  <div class="content-body">
    <div class="container-fluid">
      <div class="row page-titles">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="javascript:void(0)">Benifits</a>
          </li>
          <li class="breadcrumb-item"><a href="javascript:void(0)">Place Withdrawal</a>
          </li>
        </ol>
      </div>
      <!-- row -->
      <div class="row">
        <!-- WORKING WALLET START -->
        <div class="col-12 col-lg-6">
          <div class="card lp">
            <div class="card-header">
              <h4 class="card-title">Place Withdrawal</h4>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="card-content">
                      <div class="card-body card-dashboard">
                        <div class="row">
                          <div class="text-center col"></div>
                        </div>
                        <div class="row">
                          <div class="col-md-5">
                            <div class="reward-income-bg-amt"> <span class="card-title">Performance Wallet Balance, USD</span> 
                              <h2 class="mb-1 font-bold card-title text-primary t60">${{ working_balance }}</h2>
                              <img src="public/user_files/assets/images/w-balance.png" class="img-fluid w190">
                            </div>
                          </div>
                          <div class="col-md-7">
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <!-- <label class="control-label">Transaction Type</label>  -->
                                  <fieldset class="mb-3">
                                    <div class="row">
                                      <!-- <label class="col-form-label col-sm-4 pt-0">Transaction Type</label> -->
                                      <!-- <div class="col-sm-8">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="gridRadios" v-model="mode" :value="'transferToTopup'">
                                                        <label class="form-check-label">
                                                             Transfer To Deposit
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="gridRadios" v-model="mode" :value="'withdraw'" :selected="true">
                                                        <label class="form-check-label">
                                                            Withdraw
                                                        </label>
                                                    </div>
                                                   
                                                </div> -->
                                      <div class="col-sm-12" v-if="mode === 'withdraw'">
                                        <label>Currency Type</label>
                                        <!-- <select v-model="Currency_type" class="form-control">
                                          <option value="">Select Currency</option>
                                          <option v-for="cur in currency_code " :value="cur.currency_code">{{cur.currency_name}} ( {{cur.currency_code}} )</option>
                                        </select> -->
                                         <input v-model="Currency_type" name="currency_type" placeholder ="Enter Currency"
                                          class="form-control" v-validate="'required'" data-vv-as="Currency_type" disabled readonly
                                        >
                                      </div>
                                    </div>
                                  </fieldset>
                                  <div class="clearfix"></div>
                                  <!---->
                                </div>
                              </div>
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label class="control-label">Enter The Available Amount (USD)</label>
                                  
                                  <input type="number"  min="20" step="20" id="set-working-wallet" name="working_withdrawal_amount" v-model="set_working_wallet" class="form-control blue-back W-a-xs { error: errors.has('set-working-wallet') }" formcontrolname="set-working-wallet" placeholder="Enter Amount" data-vv-as="Amount" v-on:input="hashvalidation" v-validate="'required|numeric'" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" />
                                  
                                  <!-- <input id="transfer-amount" name="transfer-amount" formcontrolname="touser-id" placeholder="amount" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" type="number" min="25" step="25" title="Numbers only" aria-required="true" aria-invalid="false" class="form-control">  -->
                                   <div v-if='isValid' class="tooltip2" v-show="errors.has('working_withdrawal_amount')">
                                    <div class="tooltip-inner"> <span v-show="errors.has('working_withdrawal_amount')">{{ errors.first('working_withdrawal_amount') }}</span>
                                    </div>
                                  </div>
                                   <p v-if="!isValid"> <span class="error-msg-size tooltip-inner">{{ this.usermsg }}</span>
                                </p>
                                </div>
                              </div>
                              <div class="col-md-12">
                                <div v-if="mode === 'withdraw'" class="form-group">
                                 
                                  <label>Deduction</label>
                                  <input type="text" name="deduction" v-model="deduction" class="form-control" disabled readonly>
                                </div>
                                <!-- <p v-if="!isValid"> <span class="error-msg-size tooltip-inner">{{ this.usermsg }}</span> -->
                                </p>
                              </div>
                              <br>
                              <br>
                              <!-- <div class="col-md-12">
                                <div class="form-group">
                                  <label class="control-label">Remark</label>
                                  
                                  <input formcontrolname="remark" id="remark" name="remark" placeholder="Remark" type="text" class="form-control" v-model="remark">
                                  <div class="clearfix"></div>
                                </div>
                              </div> -->
                            </div>
                            <div class="panel-footer bg-gray">
                              <div class="col-lg-12">
                                <br>
                                <!-- <button type="button" disabled="disabled" id="topupsub" class="btn btn-primary">Submit</button> -->
                                <button v-if="mode == 'withdraw'" class="btn btn-primary" type="button" @click.prevent="sendOTP()" :disabled="!isCompleteWorkingWithdrawal || !isValid || errors.any() ||withdrawBtn==true">Withdraw</button>
                                <button v-if="mode == 'transferToTopup'" class="btn btn-primary" type="button" @click.prevent="sendOTP()" :disabled="!isCompleteWorkingWithdrawal || !isValid || errors.any() ||withdrawBtn==true">Transfer</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
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
                      <!-- <button type="button" @click="closePopup1()" data-dismiss="modal" class="close">×</button> -->
                       <button type="button" class="close btn" data-dismiss="modal" @click="closePopup('editBankDetailsmodal')">&times;</button>
                    </div>
                    <div class="modal-body">
                      <div class="form-group">
                        <div class="row">
                          <div class="col-md-12">
                            <input type="text" name="otp" placeholder="Enter OTP" v-model="otp" class="form-control" aria-required="true" aria-invalid="false">
                            <div class="tooltip2" v-show="errors.has('otp')">
                              <div class="tooltip-inner"> <span v-show="errors.has('otp')">{{ errors.first('otp') }}</span>
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
        <!-- WORKING WALLET END -->

        <!-- ROI WALLET START -->
        <div class="col-12 col-lg-6">
          <div class="card lp">
            <div class="card-header">
              <h4 class="card-title">Requite Withdrawal</h4>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="card-content">
                      <div class="card-body card-dashboard">
                        <div class="row">
                          <div class="text-center col"></div>
                        </div>
                        <div class="row">
                          <div class="col-md-5">
                            <div class="reward-income-bg-amt"> <span class="card-title">Requite Wallet Balance, USD</span> 
                              <h2 class="mb-1 font-bold card-title text-primary t60">${{ requite_income }}</h2>
                              <img src="public/user_files/assets/images/w-balance.png" class="img-fluid w190">
                            </div>
                          </div>
                          <div class="col-md-7">
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <!-- <label class="control-label">Transaction Type</label>  -->
                                  <fieldset class="mb-3">
                                    <div class="row">
                                      <!-- <label class="col-form-label col-sm-4 pt-0">Transaction Type</label> -->
                                      <!-- <div class="col-sm-8">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="gridRadios" v-model="mode" :value="'transferToTopup'">
                                                        <label class="form-check-label">
                                                             Transfer To Deposit
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="gridRadios" v-model="mode" :value="'withdraw'" :selected="true">
                                                        <label class="form-check-label">
                                                            Withdraw
                                                        </label>
                                                    </div>
                                                   
                                                </div> -->
                                      <div class="col-sm-12" v-if="mode === 'withdraw'">
                                        <label>Currency Type</label>
                                        <!-- <select v-model="Currency_type1" class="form-control">
                                          <option value="">Select Currency</option>
                                          <option v-for="cur in currency_code " :value="cur.currency_code">{{cur.currency_name}} ( {{cur.currency_code}} )</option>
                                        </select> -->
                                         <input v-model="Currency_type1"  placeholder ="Enter Currency"
                                          class="form-control" v-validate="'required'" data-vv-as="Currency_type" disabled readonly
                                        >
                                      </div>
                                    </div>
                                  </fieldset>
                                  <div class="clearfix"></div>
                                  <!---->
                                </div>
                              </div>
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label class="control-label">Enter The Available Amount (USD)</label>
                                  
                                  <input type="number" min="20" step="20" id="set-working-wallet1" name="roi_withdrawal_amount" v-model="set_working_wallet1" class="form-control blue-back W-a-xs { error: errors.has('set-working-wallet1') }" formcontrolname="set-working-wallet1" data-vv-as="Amount" placeholder="Enter Amount" v-on:input="hashvalidation1" v-validate="'required|numeric'" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" />
                                  <!-- <input id="transfer-amount" name="transfer-amount" formcontrolname="touser-id" placeholder="amount" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" type="number" min="25" step="25" title="Numbers only" aria-required="true" aria-invalid="false" class="form-control">  -->
                                  <!-- <div class="tooltip2" v-show="errors.has('roi_withdrawal_amount')">
                                    <div class="tooltip-inner"> <span v-show="errors.has('roi_withdrawal_amount')">{{ errors.first('roi_withdrawal_amount') }}</span>
                                    </div>
                                  </div> -->
                                  <div v-if='isValid1' class="tooltip2" v-show="errors.has('roi_withdrawal_amount')">
                                    <div class="tooltip-inner"> <span v-show="errors.has('roi_withdrawal_amount')">{{ errors.first('roi_withdrawal_amount') }}</span>
                                    </div>
                                  </div>
                                   <p v-if="!isValid1"> <span class="error-msg-size tooltip-inner">{{ this.usermsg }}</span>
                                </p>

                                </div>
                              </div>
                              <div class="col-md-12">
                                <div v-if="mode === 'withdraw'" class="form-group">
                                 
                                  <label>Deduction</label>
                                  <input type="text" name="roi_deduction" v-model="deduction" class="form-control" disabled readonly>
                                </div>
                                <!-- <p v-if="!isValid"> <span class="error-msg-size tooltip-inner">{{ this.usermsg }}</span>
                                </p> -->
                              </div>
                              <br>
                              <br>
                            <!--   <div class="col-md-12">
                                <div class="form-group">
                                  <label class="control-label">Remark</label>
                                  
                                  <input formcontrolname="remark1" id="remark1" name="remark1" placeholder="Remark" type="text" class="form-control" data-vv-as="Remark" v-model="remark1">
                                  <div class="clearfix"></div>
                                </div>
                              </div> -->
                            </div>
                            <div class="panel-footer bg-gray">
                              <div class="col-lg-12">
                                <br>
                                <!-- <button type="button" disabled="disabled" id="topupsub" class="btn btn-primary">Submit</button> -->
                                <button v-if="mode == 'withdraw'" class="btn btn-primary" type="button" @click.prevent="sendOTP1()" :disabled="!isCompleteWorkingWithdrawal1 || !isValid || !isValid1 || errors.any() ||withdrawBtn==true">Withdraw</button>
                                <button v-if="mode == 'transferToTopup'" class="btn btn-primary" type="button" @click.prevent="sendOTP1()" :disabled="!isCompleteWorkingWithdrawal1 || !isValid || errors.any() ||withdrawBtn==true">Transfer</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Popup start -->
             <div class="modal fade" id="editBankDetailsmodal1" role="dialog">
            <div class="modal-dialog">

              <div class="modal-content">

                <div class="modal-header">
                  <button type="button" class="close btn" data-dismiss="modal" @click="closePopup1('editBankDetailsmodal1')">&times;</button>
                  <h4 class="modal-title">Enter OTP</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-12">

                        <input type="text" name="otp_roi" data-vv-as="OTP" class="form-control" placeholder="Enter OTP" v-model="otp1" v-validate="'required'"/>

                        <div class="tooltip2" v-show="errors.has('otp_roi')">
                          <div class="tooltip-inner">
                            <span v-show="errors.has('otp_roi')">{{ errors.first('otp_roi') }}</span>
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
                          <button  @click="withdrawsucessroi()" :disabled="otpbtn2" type="button" class="btn btn-primary">Submit</button>
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
        <!-- ROI WALLET END -->
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
    Breadcrum,
  },
  data() {
    return {
      day: new Date().getDay(),
      workingwithdrawal: {
        topup_wallet: 0,
        working_wallet: 0,
        working_wallet_withdraw: 0,
        working_Wallet_balance: 0,
        working_Wallet_balancenew: 0,
        btc_address: "",
      },
      working_balance: 0,
      roi_income: 0,
      requite_income: 0,
      Currency_type: 'BTC',
      Currency_type1: 'BTC',

      set_working_wallet: '',
      set_working_wallet1: '',
      addTopup: 0,
      mode: "withdraw",
      isValid: true,
      isValid1: true,
      usermsg: "",
      usermsg1: "",
      otp: "",
      withdrawBtn: false,
      currency_code: [],
      otpSent: false,
      optVerified: false,
      deduction: "",
      //remark:'',
      //remark1:'',
    };
  },
  computed: {
    isCompleteWorkingWithdrawal() {
      return this.set_working_wallet || this.addTopup;
    },
    isCompleteWorkingWithdrawal1() {
      return this.set_working_wallet1 || this.addTopup;
    }
  },
  mounted() {
    this.getAllCurrency();
    this.getWorkingWithdrawal();
    this.getRoiWithdrawal();
    this.getRequiteWithdrawal();
    this.getWorkingWithdrawalDeduction();
    this.day;
    this.getRoi();
  },
  methods: {
    getWorkingWithdrawal() {
      axios
        .get("get-working-balance", {})
        .then((response) => {
          // console.log(response)
          if (response.data.code == 200) {
            this.working_balance =
              typeof response.data.data == Object ? 0 : response.data.data;
          }
        })
        .catch((error) => {});
    },
    getRoiWithdrawal() {
      axios.get("get-roi-balance", {})
      .then(response => {
        if (response.data.code == 200) {
          this.roi_income = 
            typeof response.data.data == Object ? 0 : response.data.data;
         /*this.roi_income = response.data.data;*/
        }
      }).catch(error => {});
    },
    getRequiteWithdrawal() {
      axios.get("get-requite-balance", {})
      .then(response => {
        if (response.data.code == 200) {
          this.requite_income = 
            typeof response.data.data == Object ? 0 : response.data.data;
         /*this.roi_income = response.data.data;*/
        }
      }).catch(error => {});
    },
    getWorkingWithdrawalDeduction() {
      axios
        .get("get-working-withdrawal-deduction", {})
        .then((response) => {
          // console.log(response)
          if (response.data.code == 200) {
            this.deduction =
              typeof response.data.data == Object ? 0 : response.data.data+"%";
          }
        })
        .catch((error) => {});
    },
    hashvalidation_old() {
      this.isValid = false;

      if (this.set_working_wallet % 10 != 0) {
        this.usermsg = "Amount must be multiples of 10";
        this.isValid = false;
      } else {
        this.isValid = true;
        this.usermsg = "";
      }
      this.addTopup = 0;
    },
      hashvalidation() {
      this.isValid = false;
      /*if (this.set_working_wallet < 60 && this.Currency_type == 'BTC' ) {
        this.usermsg = "Amount must be greater than or equal to 60 ";
        this.isValid = false;
      } else*/ if (this.set_working_wallet < 20) {
         this.usermsg = "Amount must be greater than or equal to 20 ";
         this.isValid = false;
      } else {
        this.isValid = true;
        this.usermsg = "";
      }
      
    },
    hashvalidation1() {
      this.isValid1 = false;
      /*if (this.set_working_wallet1 < 60 && this.Currency_type1 == 'BTC' ) {
        this.usermsg = "Amount must be greater than or equal to 60 ";
        this.isValid1 = false;
      } else */if (this.set_working_wallet1 < 20) {
         this.usermsg = "Amount must be greater than or equal to 20 ";
         this.isValid1 = false;
      } else {
        this.isValid1 = true;
        this.usermsg = "";
      }
      
    },

    hashValidationTopup() {
      this.isValid = false;

      if (this.addTopup == 0) {
        this.usermsg = "Amount must be multiples of 1";
        this.isValid = false;
      } else {
        this.isValid = true;
        this.usermsg = "";
        this.$validator.reset();
      }
      this.set_working_wallet = 0;
    },
    getAllCurrency() {
      axios
        .post("getall-currency", {
          withdrwal_status: 1,
        })
        .then((response) => {
          this.currency_code = response.data.data;
          // this.Currency_type = "TRX";
        })
        .catch((error) => {});
    },
    updateWorkingWithdrawal() {
      Swal({
        title: "Are you sure?",
        text: `You want to update this user`,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
      }).then((result) => {
        if (result.value) {
          axios
            .post("withdraw-income-otp", {
              level_income_balance: 0,
              direct_income_balance: 0,
              roi_balance: 0,
              binary_income_balance: 0,
              topup_wallet: 0,
              addTopup: this.addTopup,
              transfer_wallet: 0,
              working_wallet: this.set_working_wallet,
              mode: this.mode,
            })
            .then((response) => {
              if (response.data.code == 200) {
                this.$toaster.success(response.data.message);
                $("#editBankDetailsmodal").modal("show");
              } else {
                this.$toaster.error(response.data.message);
              }
            });
        }
      });
    },
    withdrawsucess() {
       
      this.withdrawBtn = true;
      axios
        .post("withdraw-income", {
          level_income_balance: 0,
          direct_income_balance: 0,
          roi_balance: 0,
          binary_income_balance: 0,
          topup_wallet: 0,
          addTopup: this.addTopup,
          transfer_wallet: 0,
          working_wallet: Number(this.set_working_wallet),
          mode: this.mode,
          Currency_type: this.Currency_type,
          //remark:this.remark
        })
        .then((response) => {
          this.withdrawBtn = false;
          if (response.data.code == 200) {
            this.$toaster.success(response.data.message);

            $("#editBankDetailsmodal").modal("hide");

            this.$router.push({ path: "withdrawals-income-report" });
          } else {
            this.$toaster.error(response.data.message);
            this.otp = "";
          }
        });
    },

    withdrawsucessroi() {
      this.withdrawBtn=true;
      axios.post("withdraw-roi-wallet", {
        level_income_balance: 0,
        direct_income_balance: 0,
        roi_balance: 0,
        binary_income_balance: 0,
        topup_wallet: 0,
        addTopup: 0,
        transfer_wallet: 0,
        working_wallet: Number(this.set_working_wallet1),
        mode: "withdraw",
        Currency_type:this.Currency_type1,
       // remark:this.remark1,
        wallet_type:"ROI Wallet",
        otp:this.otp1


      }).then(response => {
        this.withdrawBtn=false;
        if (response.data.code == 200) {
          this.$toaster.success(response.data.message);
          
          $('#editBankDetailsmodal1').modal('hide');

          this.$router.push({ path:'withdrawals-income-report'});
          this.otpbtn2=false;          
        } else {
          this.$toaster.error(response.data.message);
          this.otp1 = '';
          this.otpbtn2=false;
        }
      });
    },

    // TransferToPurchase() {
    //   this.withdrawBtn = true;
    //   axios
    //     .post("working-to-purchase-self-transfer", {
    //       amount: Number(this.set_working_wallet),
    //     })
    //     .then((response) => {
    //       this.withdrawBtn = false;
    //       if (response.data.code == 200) {
    //         this.$toaster.success(response.data.message);

    //         $("#editBankDetailsmodal").modal("hide");

    //         this.$router.push({ path: "working-wallet-to-purchase-report" });
    //       } else {
    //         this.$toaster.error(response.data.message);
    //       }
    //     });
    // },

    sendOTP() {
      var arr = {};
      if (this.mode == "withdraw") {
        if(this.Currency_type == "")
        {
           this.withdrawBtn = false;
        return this.$toaster.error("Please select Currency");
       
        }
        arr = { type: "Withdrawal" };
      } else {
        arr = { type: "transfer" };
      }
      axios
        .post("sendOtp-update-user-profile", arr)
        .then((response) => {
          if (response.data.code == 200) {
            //console.log(response);
            this.$toaster.success(response.data.message);
            //this.statedata=response.data.data.message;
            this.otp = "";
            $("#editBankDetailsmodal").modal("show");
          } else {
            this.$toaster.error(response.data.message);
          }
        })
        .catch((error) => {});
    },

    sendOTP1(){
     var arr = {};
      //if (this.mode == "withdraw") {
        arr = {type:'Withdrawal',wallet_type:'ROI Wallet'};

     // }
      //else{
      //  arr = {type:'transfer'};
      //}
      axios.post('sendOtp-update-user-profile',arr).then(response=>{

            if(response.data.code == 200){
                //console.log(response);
              this.$toaster.success(response.data.message);
                //this.statedata=response.data.data.message;
              this.otp1='';
              $('#editBankDetailsmodal1').modal('show');

            }else{                 
              this.$toaster.error(response.data.message);
            }
        }).catch(error=>{
        })  
    },

    closePopup(){
      $("#editBankDetailsmodal").modal("hide");
    },
    closePopup1(){
      $("#editBankDetailsmodal1").modal("hide");
    },

    verifyOtp() {
       this.withdrawBtn = true;
      axios
        .post("withdraw-income", {
          otp:this.otp,
          level_income_balance: 0,
          direct_income_balance: 0,
          roi_balance: 0,
          binary_income_balance: 0,
          topup_wallet: 0,
          addTopup: this.addTopup,
          transfer_wallet: 0,
          working_wallet: Number(this.set_working_wallet),
          mode: this.mode,
          Currency_type: this.Currency_type,
          //remark:this.remark
        })
        .then((response) => {
          this.withdrawBtn = false;
          if (response.data.code == 200) {
            this.$toaster.success(response.data.message);

            $("#editBankDetailsmodal").modal("hide");

            this.$router.push({ path: "withdrawals-income-report" });
          } else {
            this.$toaster.error(response.data.message);
            this.otp = "";
          }
        });
    },
     verifyOtp1(){
      this.otpbtn2=true;
      axios.post('verify-user-otp', {
          otp : this.otp1,
          level_income_balance: 0,
          direct_income_balance: 0,
          roi_balance: 0,
          binary_income_balance: 0,
          topup_wallet: 0,
          addTopup: this.addTopup,
          transfer_wallet: 0,
          working_wallet: Number(this.set_working_wallet1),
          mode: this.mode,
          Currency_type: this.Currency_type1,
         // remark:this.remark1

      }).then(response => {
        if (response.data.code == 200) {
          this.$toaster.success(response.data.message);
          this.otpSent1 = true;
          this.optVerified1 = true;
          this.withdrawsucessroi();          
        } else {
          this.$toaster.error(response.data.message);
          this.otpbtn2=false;
        }
      }).catch(error => {
          this.message = '';
      });
    },

    getRoi() {
      let i = 0;
      let that = this;
      let token = localStorage.getItem("user-token");
      setTimeout(function () {
        const table = $("#withdraw-income-report").DataTable({
          responsive: true,
          lengthMenu: [
            [10, 50, 100],
            [10, 50, 100],
          ],
          retrieve: true,
          destroy: true,
          processing: false,
          serverSide: true,
          responsive: true,
          stateSave: false,
          ordering: true,
          ajax: {
            url: apiUserHost + "withdrwal-income",
            type: "POST",
            data: function (d) {
              i = 0;
              i = d.start + 1;

              let params = {};
              Object.assign(d, params);
              return d;
            },
            headers: {
              Authorization: "Bearer " + token,
            },
            dataSrc: function (json) {
              if (json.code === 200) {
                that.arrGetHelp = json.data.records;
                json["draw"] = json.data.draw;
                json["recordsFiltered"] = json.data.recordsFiltered;
                json["recordsTotal"] = json.data.recordsTotal;
                return json.data.records;
              } else {
                json["draw"] = 0;
                json["recordsFiltered"] = 0;
                json["recordsTotal"] = 0;
                return json;
              }
            },
          },
          columns: [
            {
              render: function (data, type, row, meta) {
                return i++;
              },
            },

            {
              render: function (data, type, row, meta) {
                return `<span>$${row.amount + row.deduction}</span>`;
              },
            },
            {
              render: function (data, type, row, meta) {
                return `<span>$${row.deduction}</span>`;
              },
            },
            {
              render: function (data, type, row, meta) {
                return `<span>$${row.amount}</span>`;
              },
            },

            {
              render: function (data, type, row, meta) {
                if (row.status == 0) {
                  return `<span class="label text-warning">Pending</span>`;
                } else if (row.status == 1) {
                  return `<span class="label text-success">Confirmed</span>`;
                } else if (row.status == 2) {
                  return `<span class="label text-danger">Rejected</span>`;
                } else {
                  return ``;
                }
              },
            },
            {
              render: function (data, type, row, meta) {
                if (
                  row.entry_time === null ||
                  row.entry_time === undefined ||
                  row.entry_time === ""
                ) {
                  return `-`;
                } else {
                  return moment(String(row.entry_time)).format("YYYY/MM/DD");
                }
              },
            },
          ],
        });
      }, 0);
    },
  },
};
</script>