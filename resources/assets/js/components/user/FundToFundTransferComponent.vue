<template>
  <div class="content-body">
    <div class="container-fluid">
      <div class="row page-titles">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="javascript:void(0)">Benifits</a>
          </li>
          <li class="breadcrumb-item"><a href="javascript:void(0)">Fund Transfer</a>
          </li>
        </ol>
      </div>
      <!-- row -->
      <div class="row">
        <!-- WORKING WALLET START -->
        <div class="col-12 col-lg-12">
          <div class="card lp">
            <div class="card-header">
              <h4 class="card-title">Fund Transfer</h4>
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
                            <div class="reward-income-bg-amt"> <span class="card-title">Fund Wallet Balance</span> 
                              <h2 class="mb-1 font-bold card-title text-primary t60">${{ topup_bal }}</h2>
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
                                      <div class="col-sm-12" >
                                        <div class="form-group">
                                            <label class="control-label">User ID</label>
                                            <input class="form-control input-dark" formcontrolname="touser-id" id="touser-id" name="touser-id" placeholder="Enter User Id" type="text" v-model="fundtransfer.touser_id" v-on:input="checkUserExisted"/>
                                            <div class="clearfix"></div>
                                            <p :class="{'text-success': isAvialable == 'Available','text-danger': isAvialable == 'Not Available'}" v-if="isAvialable!=''">{{msg}}</p>
                                        </div>
                                      </div>
                                    </div>
                                  </fieldset>
                                  <div class="clearfix"></div>
                                  <!---->
                                </div>
                              </div>
                              <div class="col-md-12">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input class="form-control input-dark" formcontrolname="fullname" id="fullname" name="fullname" placeholder="Full Name" type="text" v-model="fullname" disabled readonly />
                                    <div class="clearfix"></div>
                                </div>
                              </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Enter Amount</label>
                                    <input id="transfer-amount" maxlength="8" name="transfer-amount" v-model="fundtransfer.transfer_amount" class="form-control input-dark" formcontrolname="touser-id" placeholder="amount" v-validate="'required|numeric|min_value:1'" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" type="number" min="1" step="20" title="Numbers only" aria-required="true" aria-invalid="false"/>
                                    <div class="tooltip2" v-show="errors.has('transfer-amount')">
                                       <div class="tooltip-inner">
                                          <span v-show="errors.has('transfer-amount')">{{ errors.first('transfer-amount') }}</span>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Remark</label>
                                    <input class="form-control input-dark" formcontrolname="remark" id="remark" name="remark" placeholder="Remark" type="text" v-model="fundtransfer.remark"  />
                                    
                                </div>
                            </div>
                            <div class="panel-footer bg-gray">
                                <div class="panel-footer bg-gray">
                                    <div class="col-lg-12">
                                        <br><br>
                                        <button class="btn btn-primary" type="button" @click.prevent="sendOTP()" :disabled="!isCompleteTransferFund || errors.any() || !btndisabled || !useractive" id="topupsub">Submit</button>
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
                              <button type="button" @click="transferFund()" class="btn btn-primary">Submit</button>
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
           top_up_Wallet_balance: 0,
           touser_id:"",
           transfer_amount:"",
           remark:"",

         },
         working_balance:0,
         otpVerified:false,
         otpSent:false,
         otp:'',
         fullname:'',
         topup_bal: 0,
         remark : '',
         username:'',
         btndisabled:true,
         msg: "",
       };
     },
     computed: {
       isCompleteTransferFund() {
         return this.fundtransfer.touser_id && this.fundtransfer.transfer_amount ;
       }
     },
     mounted() {
       this.getTopupDetails();
        this.getTopupReport();
     },
     methods: {
       getTopupReport(){
                let i = 0;
                let that = this;
                let token = localStorage.getItem('user-token');
                setTimeout(function(){
                    that.table = $('#working-to-working-transfer-report').DataTable({
                        responsive: true,
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        responsive: true,
                        stateSave: false,
                        ordering: false,
                        dom:'lrtip',
                        ajax: {
                            url: apiUserHost+'working-to-working-transfer-balance-report',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    frmuser_id: $("#frmuser-id").val(),
                                    touser_id: $("#touser-id").val(),
                                    frm_date: $("#from-date").val(),
                                    to_date: $("#to-date").val(),
                                    type:'transfer'
                                };
                                Object.assign(d, params);
                                return d;
                            },
                            headers: {
                              'Authorization': 'Bearer ' + token
                            },
                            dataSrc: function (json) {
                                if (json.code === 200) {
                                    that.arrGetHelp = json.data.records;
                                    json['draw'] = json.data.draw;
                                    json['recordsFiltered'] = json.data.recordsFiltered;
                                    json['recordsTotal'] = json.data.recordsTotal;
                                    // that.total_balance = json.data.totalAmount;
                                    return json.data.records;
                                } else {
                                    json['draw'] = 0;
                                    json['recordsFiltered'] = 0;
                                    json['recordsTotal'] = 0;
                                    return json;
                                }
                            }
                        },
                        columns: [
                             {
                               "defaultContent": "",
                                render: function (data, type, row, meta) {
                                    return meta.row + 1;
                                }
                            },
                            {
                                "defaultContent": "",
                                render: function (data, type, row, meta) {
                                    return `<span>${row.to_user_id}</span>`;
                                }
                            },
                            {
                                "defaultContent": "",
                                render: function (data, type, row, meta) {
                                    return `<span>${row.user_id}</span>`;
                                }
                            },
                            { "defaultContent": "",
                              render: function (data, type, row, meta,) {
                                   return `<span>$${row.amount}</span>  `;

                                }
                            },
                             /*{
                               "defaultContent": "", 
                              render: function (data, type, row, meta,) {
                                   return `<span>$${row.transfer_charge}</span>  `;

                                }
                            }, 
                            { 
                              "defaultContent": "",
                              render: function (data, type, row, meta,) {
                                   return `<span>$${row.net_amount}</span>  `;

                                }
                            },*/
                             {
                              "defaultContent": "",
                              render: function(data, type, row, meta) {
                                if (row.remark === null || row.remark === undefined || row.remark === "") {
                                  return `-`;
                                } else {
                                  return `<span>${row.remark}</span>  `;
                                }
                              }
                            }, 
                           
                            {
                              "defaultContent": "",
                              render: function(data, type, row, meta) {
                                if (row.entry_time === null || row.entry_time === undefined || row.entry_time === "") {
                                  return `-`;
                                } else {
                                  return moment(String(row.entry_time)).format("YYYY/MM/DD");
                                }
                              }
                            }, 
                            /*{ render: function (data, type, row, meta,) {
                                   return `<span>$${row.balance}</span>  `;

                                }
                            },*/
                        ]
                    });
                     $("#onSearchClick").click(function() {
                      // alert("Hello")
                      that.table.ajax.reload();
                    });
                    $("#onResetClick").click(function() {
                      $("#searchForm").trigger("reset");
                      that.table.ajax.reload();
                    });
                     
                },0);
            },
       getTopupDetails() {
         axios.post("get-wallet-balance", {'wallet_type':'fund_wallet'})
           .then(response => {
             if(response.data.code == 200){
                this.topup_bal = response.data.data;
             }
            
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
               axios
                 .post("check-downline", {
                   user_id: this.fundtransfer.touser_id
                 })
                 .then(res => {
                   if (res.data.code == 200) {
                     //alert(1);
                     // this.paid = res.data.data;
                     // this.fullname = response.data.data.fullname;
                     // this.address = response.data.data.address;
                     // this.mobile = response.data.data.mobile;
                     // this.sp_user_id = response.data.data.sponser_id;
                     // this.sp_fullname = response.data.data.sponser_fullname;
                     this.useractive = true;
                     this.isAvialable = "Available";
                     this.msg = "Available";
                     this.fullname = response.data.data.fullname;
                   } else {
                     // alert(res.data.message)
                     // this.paid = '';
                     // this.isdownline = 0;
                     //  this.fullname = '';
                     // this.address = '';
                     // this.mobile = '';
                     // this.sp_user_id = '';
                     // this.sp_fullname = '';
                     this.useractive = false;
                     /*this.topup.user_id = "";*/
                     this.isAvialable = 'Not Available';
                     this.msg = 'Not a Downline user';
                     this.fullname = "";
                   }
                 });
             } else {
               this.useractive = false;
               this.isAvialable = 'Not Available';
               this.msg = 'Invalid User';
               this.fullname = "";
             }
           })
           .catch(error => {
             console.log(error);
           });
       },
       transferFund() {
         axios.post("fund-to-fund-transfer", {
           otp:this.otp,
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
             this.$router.push("/working-to-working-transfer-report");
           } else {
             this.$toaster.error(response.data.message);
             this.otp = "";
             this.btndisabled = true;
           }
         });
       },
   
       sendOTP(){

         var arr = {};
         if(this.fundtransfer.touser_id == "")
         {
            this.$toaster.error("User id field required");
         }else if(this.fundtransfer.transfer_amount == "")
         {
            this.$toaster.error("Amount  field required");
         }else if(this.fundtransfer.remark == "")
         {
            this.$toaster.error("Remark field required");
         }

         else{



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

         }

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