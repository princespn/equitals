<template>
  <!-- BEGIN: Content-->
  <div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
      <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h2 class="content-header-title float-left mb-0">Passive Income Wallet</h2>
            </div>
          </div>
        </div>
      </div>
    <div class="content-body">
      <!-- Complex headers table -->
      <section id="headers">
        <div class="row">
          <div class="col-md-5 col-12">
            <div class="card">
              <div class="card-content">
                <div class="card-body card-dashboard">
                  <div class="row"></div>
                  <div class="top-bordr3 m-t-30 m-b-30"></div>
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="reward-income-bg-amt">
                          <div>Passive Income Wallet Balance, USD</div>
                          <div>
                            <table>
                            <tr>
                              <td width="50%"><h4><span class="pull-left">Total</span></h4></td>
                              <td width="50%"><h4><span class="pull-right">Withdrawable</span></h4></td>
                            </tr>    
                            <tr>
                              <td><h5><span class="pull-left">${{ passive_balance }}</span></h5></td>
                              <td><h5><span class="pull-right">${{ passive_withdraw_balance }}</span></h5></td>

                            </tr>
                            </table>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12" v-if="mode === 'withdraw'">
                        <p>Currency Type</p>

                        <select v-model="Currency_type" class="form-control">
                          <option v-for="cur in currency_code " :value="cur.currency_code">
                            {{cur.currency_name}} ( {{cur.currency_code}} )
                          </option>
                        </select>
                      </div>
                      <div class="col-md-12"> 
                        <div class="form-group"><!-- v-if="mode === 'withdraw'" -->
                          <br>
                          <p>Enter The Available Amount (USD)</p>
                          <input type="number" min="20" step="10" id="withdraw_amount" name="withdraw_amount" v-model="withdraw_amount" class="form-control blue-back W-a-xs { error: errors.has('withdraw_amount') }" formcontrolname="withdraw_amount" placeholder="Enter Amount" v-validate="'required|numeric'" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57"/>
                          <div class="tooltip2" v-show="errors.has('withdraw_amount')">
                            <div class="tooltip-inner">
                              <span v-show="errors.has('withdraw_amount')">{{ errors.first('withdraw_amount') }}</span>
                            </div>
                          </div>
                        </div>
                        <div v-if="mode === 'withdraw'" class="form-group">
                          <p>Deduction</p>
                          <input type="text" name="deduction" v-model="deduction" class="form-control" disabled readonly>
                        </div>

                        <!-- transfer to topup wallet -->
                        <!-- <div v-if="mode === 'transferToTopup'" class="form-group">
                          <br>
                          <p>Enter The Available Amount (USD)</p>
                          <input type="number" min="1" step="1" id="set-topup-wallet" name="set-topup-wallet" v-model="addTopup" class="form-control blue-back W-a-xs { error: errors.has('set-topup-wallet') }" formcontrolname="set-topup-wallet" placeholder="Enter Amount" v-validate="'required|numeric'" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57">
                            <div class="tooltip2" v-show="errors.has('set-topup-wallet')">
                              <div class="tooltip-inner">
                                <span v-show="errors.has('set-topup-wallet')" >{{ errors.first('set-topup-wallet') }}</span>
                              </div>
                            </div>
                          </div> -->
                          <p v-if="!isValid">
                            <span class="error-msg-size tooltip-inner">{{ this.usermsg }}</span>
                          </p>
                        </div>
                      </div>

                      <div class="row">
                        <div v-if="day != 7" class="col-md-6 mt-30">
                          <span class="text-danger">
                            Withdrawal will Allowed only on Sunday
                          </span>
                        </div>
                        <div v-if="day = 7" class="col-md-2 mt-30">

                         <button  v-if="mode == 'withdraw'" style="width:150px;"  class="btn btn-success wd-but" type="button" @click.prevent="sendOTP()" :disabled="!isCompleteWorkingWithdrawal || !isValid || errors.any() ||withdrawBtn==true">Withdraw</button>                         
                       </div>
                     </div>
                   </div>
                 </div>
               </div>
             </div>
           </div>
           <div class="col-md-7 col-12">
            <div class="card">
              <div class="card-header">
                <!-- <h4 class="card-title">Direct Referral Income Report</h4> -->
              </div>
              <div class="card-content">
                <div class="card-body card-dashboard">
                  <div class="table-responsive">                      
                    <table id="withdraw-passive-report" class="table table-striped table-bordered complex-headers">
                      <thead> 
                        <tr>
                          <th>Sr No</th>
                          <th>Amount</th>
                          <th>Deduction</th>
                          <th>Net Amount</th>
                          <th>Status</th>
                          <th>Date</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                      <tfoot>
                        <tr>
                          <th>Sr No</th>
                          <th>Amount</th>
                          <th>Deduction</th>
                          <th>Net Amount</th>
                          <th>Status</th>
                          <th>Date</th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
            </div>              
          </div>
            <!-- <div class="col-6">
              <img src="public/user_files/images/wallett.png" class="img-fluid">
            </div> -->
          </div>
          <!-- Popup start -->
          <div class="modal" id="editBankDetailsmodal" role="dialog">
            <div class="modal-dialog">

              <div class="modal-content">

                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Enter OTP</h4>
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
        </section>
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
      day : new Date().getDay(),
      workingwithdrawal: {
        topup_wallet: 0,
        working_wallet: 0,
        working_wallet_withdraw: 0,
        working_Wallet_balance: 0,
        working_Wallet_balancenew:0,
        btc_address: ""
      },
      passive_balance:0,    
      passive_withdraw_balance:0,    
      Currency_type: "TRX",

      withdraw_amount: 0,
      addTopup: 0,
      mode: "withdraw",
      isValid: true,
      usermsg: "",
      otp: "",
      withdrawBtn:false,
      currency_code:[],
      otpSent : false,
      optVerified : false,
      deduction:'20%'
    };
  },
  computed: {
    isCompleteWorkingWithdrawal() {
      return this.withdraw_amount || this.addTopup;
    }
  },
  mounted() {
    this.getAllCurrency();
    this.getWorkingWithdrawal();
    this.getPassiveBalance();
    this.day;
    this.getRoi();

  },
  methods: {
    getWorkingWithdrawal() {
      axios.post("get-wallet-balance", {wallet_type:'passive_income'}).then(response => {
        if (response.data.code == 200) {
          this.passive_balance = response.data.data;
        }
      }).catch(error => {});
    },
    getPassiveBalance() {
      axios.post("get-wallet-balance", {wallet_type:'passive_income_withdraw'}).then(response => {
        if (response.data.code == 200) {
          this.passive_withdraw_balance = response.data.data;
        }
      }).catch(error => {});
    },
    hashvalidation() {
      this.isValid = false;

      if (this.withdraw_amount % 10 != 0) {
        this.usermsg = "Amount must be multiples of 10";
        this.isValid = false;
      } else {
        this.isValid = true;
        this.usermsg = "";
      }
      this.addTopup = 0;
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
      this.withdraw_amount = 0;
    },
    getAllCurrency(){ 
      axios.post('getall-currency', {
        withdrwal_status:1,
      })
      .then(response => {
       this.currency_code = response.data.data;
       this.Currency_type="TRX";
     }).catch(error => {});        
    },
    updateWorkingWithdrawal() {
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
          .post("withdraw-income-otp", {
            level_income_balance: 0,
            direct_income_balance: 0,
            roi_balance: 0,
            binary_income_balance: 0,
            topup_wallet: 0,
            addTopup: this.addTopup,
            transfer_wallet: 0,
            working_wallet: this.withdraw_amount,
            mode: this.mode

          })
          .then(response => {
            if (response.data.code == 200) {
              this.$toaster.success(response.data.message);
              $('#editBankDetailsmodal').modal('show');
            } else {
              this.$toaster.error(response.data.message);
            }
          });
        }
      });
    },
    withdrawsucess() {
      this.withdrawBtn=true;
      axios.post("withdraw-passive-income", {
        level_income_balance: 0,
        direct_income_balance: 0,
        roi_balance: 0,
        binary_income_balance: 0,
        topup_wallet: 0,
        addTopup: this.addTopup,
        transfer_wallet: 0,
        working_wallet: Number(this.withdraw_amount),
        mode: this.mode,
        Currency_type:this.Currency_type,


      }).then(response => {
        this.withdrawBtn=false;
        if (response.data.code == 200) {
          this.$toaster.success(response.data.message);
          
          $('#editBankDetailsmodal').modal('hide');

          this.$router.push({ path:'withdrawals-income-report'});
        } else {
          this.$toaster.error(response.data.message);
          this.otp = '';
        }
      });


    },

    TransferToPurchase() {
      this.withdrawBtn=true;
      axios.post("working-to-purchase-self-transfer", {
        amount:Number(this.withdraw_amount)
      }).then(response => {
        this.withdrawBtn=false;
        if (response.data.code == 200) {
          this.$toaster.success(response.data.message);
          
          $('#editBankDetailsmodal').modal('hide');

          this.$router.push({ path:'dex-to-purchase-report'});
        } else {
          this.$toaster.error(response.data.message);
        }
      });


    },


    sendOTP(){
      if (this.withdraw_amount > this.passive_withdraw_balance) {
        this.$toaster.error("Amount must be less or equal of withdrawable balance");   
        return false;     
      }
      var arr = {};
      if (this.mode == "withdraw") {
        arr = {type:'Withdrawal'};
      }else{
        arr = {type:'transfer'};
      }
      axios.post('sendOtp-update-user-profile',arr).then(response=>{

            if(response.data.code == 200){
                //console.log(response);
              this.$toaster.success(response.data.message);
                //this.statedata=response.data.data.message;
              this.otp='';
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
          if (this.mode == "withdraw") {
            this.withdrawsucess();
          }else if (this.mode == 'transferToTopup') {
            this.TransferToPurchase();
          }
        } else {
          this.$toaster.error(response.data.message);
        }
      }).catch(error => {
          this.message = '';
      });
    },

    getRoi() {
      let i = 0;
      let that = this;
      let token = localStorage.getItem("user-token");
      setTimeout(function() {
        const table = $("#withdraw-passive-report").DataTable({
          responsive: true,
          lengthMenu: [
          [10, 50, 100],
          [10, 50, 100]
          ],
          retrieve: true,
          destroy: true,
          processing: false,
          serverSide: true,
          responsive: true,
          stateSave: false,
          ordering: true,
          ajax: {
            url: apiUserHost + "passive-income-withdraw-list",
            type: "POST",
            data: function(d) {
              i = 0;
              i = d.start + 1;

              let params = {};
              Object.assign(d, params);
              return d;
            },
            headers: {
              Authorization: "Bearer " + token
            },
            dataSrc: function(json) {
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
            }
          },
          columns: [
          {
            render: function(data, type, row, meta) {
              return i++;
            }
          },

          {
            render: function(data, type, row, meta) {
              return `<span>$${row.amount + row.deduction}</span>`;
            }
          },
          {
            render: function(data, type, row, meta) {
              return `<span>$${row.deduction}</span>`;
            }
          },
          {
            render: function(data, type, row, meta) {
              return `<span>$${row.amount}</span>`;
            }
          },

          {
            render: function(data, type, row, meta) {
              if (row.status == 0) {
                return `<span class="label text-warning">Pending</span>`;
              } else if (row.status == 1) {
                return `<span class="label text-success">Confirmed</span>`;
              } else if (row.status == 2) {
                return `<span class="label text-danger">Rejected</span>`;
              } else {
                return ``;
              }
            }
          },
          {
            render: function(data, type, row, meta) {
              if (
                row.entry_time === null ||
                row.entry_time === undefined ||
                row.entry_time === ""
                ) {
                return `-`;
            } else {
              return moment(String(row.entry_time)).format("YYYY/MM/DD");
            }
          }
        }
        ]
      });
      }, 0);
    }

  }
};
</script>