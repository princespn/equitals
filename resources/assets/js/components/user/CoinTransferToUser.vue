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
              <h2 class="content-header-title float-left mb-0">Token Transfer to user</h2>
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
                          <span>Token Balance</span>
                          <h2 class="mb-1 font-bold text-white">{{ coin_balance }}</h2>

                        </div>
                      </div>
                      <!-- <div class="col-md-12">
                        <p>Transaction Type</p>
                        <p>                          
                          <input type="radio" name="mode" id="mode" v-model="mode" :value="'transferToTopup'">
                          <span>Transfer To Purchase</span>
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="radio" name="mode" id="mode" v-model="mode" :value="'withdraw'" :selected="true">
                          <span>Withdraw</span>
                        </p>
                      </div> -->
                      <!-- <div class="col-md-12" v-if="mode === 'withdraw'">
                        <p>Currency Type</p>

                        <select v-model="Currency_type" class="form-control">
                          <option v-for="cur in currency_code " :value="cur.currency_code">
                            {{cur.currency_name}} ( {{cur.currency_code}} )
                          </option>
                        </select>
                      </div> -->
                      <div class="col-md-12"> 
                        <input type="hidden" name="user_id" v-model="user_id">
                        <div class="form-group">
                            <label>Enter User Id</label>
                            <input type="text" name="username" class="form-control" id="username" placeholder="Enter User Id" v-model="username" v-on:keyup="checkUserExisted">
                            <div class="clearfix"></div>
                            <p :class="{'text-success': isAvialable == 'Available','text-danger': isAvialable == 'Not Available'}" v-if="isAvialable!='' && username!=''">{{isAvialable}}</p>
                        </div>
                        <div class="form-group"><!-- v-if="mode === 'withdraw'" -->
                          <br>
                          <p>Enter The Available Token</p>

                          <input type="number" min="1" step="10" name="Token" formcontrolname="set-working-wallet" placeholder="Enter Token"class="form-control blue-back W-a-xs { error: errors.has('set-working-wallet') }"  v-model="amount" v-validate="'required|numeric'" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57"/>
                          <!-- <input type="number" min="20" step="10" name="Token" class="form-control blue-back W-a-xs { error: errors.has('set-working-wallet') }" formcontrolname="set-working-wallet" placeholder="Enter Token" v-model="amount" v-validate="'required|numeric'" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57"/> -->
                         <!--  <div class="tooltip2" v-show="errors.has('Amount')">
                            <div class="tooltip-inner">
                              <span v-show="errors.has('Amount')">{{ errors.first('Amount') }}</span>
                            </div>
                          </div> -->
                        </div>
                        <!-- <div v-if="mode === 'withdraw'" class="form-group">
                          <p>Deduction</p>
                          <input type="text" name="deduction" v-model="deduction" class="form-control" disabled readonly>
                        </div> -->

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
                          <!-- <p v-if="!isValid">
                            <span class="error-msg-size tooltip-inner">{{ this.usermsg }}</span>
                          </p> -->
                        </div>
                      </div>

                      <div class="row">
                        <!-- <div v-if="day != 4" class="col-md-6 mt-30">
                          <span class="text-danger">
                            Withdrawal will Allowed only on Thursday
                          </span>
                        </div> -->
                        <!-- <div v-if="day = 4" class="col-md-2 mt-30"> -->

                         <button style="width:150px;"  class="btn btn-success wd-but"    @click.prevent="sendOTP()"  type="button" :disabled="!isComplete && isDisabledBtn">Transfer</button>

                         <!-- <button  v-if="mode == 'transferToTopup'" style="width:150px;"  class="btn btn-success wd-but" type="button" @click.prevent="sendOTP()" :disabled="!isCompleteWorkingWithdrawal || !isValid || errors.any() ||withdrawBtn==true">Transfer</button> -->
                       <!-- </div> -->
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
                    <table id="withdraw-income-report" class="table table-striped table-bordered complex-headers">
                      <thead> 
                        <tr>
                          <th>Sr No</th>
                          <th>From User Id</th>
                          <th>To User Id</th>
                          <th>Token</th>
                          <th>Status</th>
                          <th>Date</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                      <tfoot>
                        <tr>
                          <th>Sr No</th>
                          <th>From User Id</th>
                          <th>To User Id</th>
                          <th>Token</th>
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
          <div class="modal fade" id="editBankDetailsmodal" role="dialog" data-backdrop="false">
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
    Breadcrum,
  },
  data() {
    return {
        day: new Date().getDay(),
        isValid: true,
        user_id: "",
        username: "",
        isAvialable:'',
        isUserExit : '',
        isDisabledBtn:true,
        otp:'',
        coin_balance:0,
        amount:0,
    };
  },
  computed: {
    isComplete() {
      return  this.username && this.amount;
    },
  },
    mounted() {
        this.getCoinBal();
        this.getcoinreport();
        this.day;
    },
  methods: {
    checkUserExisted(){
        axios.post('/checkuserexist',{
            user_id: this.username,
        }).then(resp => {
            if(resp.data.code === 200){
                this.id = resp.data.data.id;
                this.user_id = resp.data.data.user_id;
                this.isAvialable = 'Available';
                this.isDisabledBtn = false;
            } else {
                this.user_id = '';
                this.isAvialable = 'Not Available';
                this.isDisabledBtn = true;
            }
        }).catch(err => {
            this.$toaster.error(err);
        })
    },
    hashvalidation() {
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
    getCoinBal(){
      axios.post('get-coin-balance', {
      })
      .then(response => {
       this.coin_balance = response.data.data.avail_bal;
     }).catch(error => {});
    },


      sendOTP(){

        if(this.user_id == ''){
            this.$toaster.error('Please Enter Valid User Id');
            this.isDisabledBtn = true;
            return false;
        }
        if(this.amount == 0){
            this.$toaster.error('Please Enter Token greater than 0');
            this.isDisabledBtn = true;
            return false;
        }

      this.isDisabledBtn = false;
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
         // this.transferFund();
         this.transfercoin();
         $('#editBankDetailsmodal').modal('hide');

        } else {
          this.$toaster.error(response.data.message);
          this.isDisabledBtn = true;
        }
      }).catch(error => {
          this.message = '';
      });
    },   


    transfercoin() {

      axios
            .post("coin-transfer-user", {
                id : this.id,
                user_id : this.user_id,
                coin : this.amount,
            })
            .then((response) => {
              if (response.data.code == 200) {
                this.$toaster.success(response.data.message);
                this.user_id = '';
                this.username = '';
                this.amount = 0;
                this.id = '';
                this.isDisabledBtn = true;
                $('#withdraw-income-report').DataTable().ajax.reload();
               $('#editBankDetailsmodal').modal('hide');
                // $("#editBankDetailsmodal").modal("show");
              } else {
                this.$toaster.error(response.data.message);
              }
            });
        
     /* Swal({
        title: "Are you sure?",
        text: `You want to Transfer Token to this user`,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
      }).then((result) => {
        if (result.value) {
          
        }
      });*/
    },

        getcoinreport() {
            let i = 0;
            let that = this;
            let token = localStorage.getItem("user-token");
            setTimeout(function() {
                const table = $("#withdraw-income-report").DataTable({
                responsive: true,
                lengthMenu: [[10, 50, 100], [10, 50, 100]],
                retrieve: true,
                destroy: true,
                processing: false,
                serverSide: true,
                responsive: true,
                stateSave: false,
                ordering: true,
                ajax: {
                    url: apiUserHost + "coin_transfer_report",
                    type: "POST",
                    data: function(d) {
                    i = 0;
                    i = d.start + 1;

                    let params = {};
                    Object.assign(d, params);
                    return d;
                    },
                    headers: {
                    Authorization: "Bearer " + token,
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
                    },
                },
                columns: [
                    {
                    render: function(data, type, row, meta) {
                        return i++;
                    },
                    },

                    { 'data': 'from_user_id' },
                    { 'data': 'to_user_id' },
                    { 'data': 'coin' },
                    {
                      render: function(data, type, row, meta) {
                        if (row.user_status == 'Sender') {
                          return `<span class="label text-warning">Send</span>`;
                        } else {
                          return `<span class="label text-success">Recieved</span>`;
                        } 
                      },
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
                    },
                    },
                ],
                });
            }, 0);
        },
  },
};
</script>
