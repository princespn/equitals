<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Add Development Bonus Wallet Fund</h4>
		    </div>
		</div>

		<div class="page-content-wrapper">
		    <div class="container">
		    	<div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <div class="">
                                    <form id="addSettingFund">
                            			<div class="col-md-5 col-md-offset-3">
                            				<input type="hidden" name="user_id" v-model="fund.user_id">
                            				<div class="form-group">
    											<label>User Id</label>
    											<input type="text" name="username" class="form-control" id="username" placeholder="User Id" v-model="username" v-on:keyup="checkUserExisted">
    											<div class="clearfix"></div>
	                        					<p :class="{'text-success': isAvialable == 'Available','text-danger': isAvialable == 'Not Available'}" v-if="isAvialable!='' && username!=''">{{isAvialable}}</p>
  											</div>

                                            <div class="form-group"> 
                                                <label for="balance">Balance</label>
                                                <input type="text" class="form-control" id="balance" name="balance" v-model="balance" placeholder="Balance" readonly="" >
                                            </div>

                                            <div class="form-group"> 
                                                <label for="balance">Topup Percentage</label>
                                                <input type="text" class="form-control" id="topup_percentage" name="topup_percentage" v-model="topup_percentage" placeholder="Balance" v-validate="'required|numeric|min_value:1|max_value:100'">
                                                <div v-show='errors.has("amount")' class="tooltip1">
                                                  <span class=" text-danger error-msg-size tooltip-inner"> {{ errors.first("amount") }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group"> 
                                                <label for="username">Name</label>
                                                <input type="text" class="form-control" id="username" name="username" v-model="fullname" placeholder="Name" readonly="" >
                                             </div>

                                                <div class="form-group"> 
                                                <label for="email">Mail Id</label>
                                                <input type="text" class="form-control" id="email" name="email" v-model="email" placeholder="Mail Id" readonly="" >
                                             </div>

                                             <div class="form-group"> 
                                                <label for="remark">Remark</label>
                                                <input type="text" class="form-control" id="remark" name="remark" v-model="fund.remark" placeholder="Remark" >
                                             </div>
                                            <div class="form-group">
                                                <label class="control-label">Enter Amount</label>
                                                <input type="text" class="form-control" id="amount" name="amount" v-model="fund.amount"  placeholder="Enter amount" v-validate="'required|numeric|min_value:10'">
                                                <div v-show='errors.has("amount")' class="tooltip1">
                                                  <span class=" text-danger error-msg-size tooltip-inner"> {{ errors.first("amount") }}</span>
                                                </div>
                                            </div>

  											<div class="col-md-offset-5">
  												<button type="button" class="btn btn-primary text-center" @click.prevent="sendOTP()" :disabled="!isComplete || !isDisabledBtn">Submit</button>
  											</div>
										</div>
                                	</form>
                            	</div>
                        	</div><!-- panel-body -->
                    	</div><!-- panel -->
                	</div><!-- col -->
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
                                        <button type="button"  @click="fund_req" class="btn btn-primary">Submit</button>
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
	</div>
</template>

<script>
    import { apiAdminHost } from'./../../admin-config/config';
    import Swal from 'sweetalert2';

    export default {
    	
        data() {
            return {
                product_ids: {
                    id: '',
                    name: ''
                },
                topup:{
                	user_id: '',
                    pin: '',
                	product_id: null,
                    hash_unit:'',
                    payment_type:'BTC',
                },

                fund:{
                    user_id:'',
                    fullname:'',
                    amount:'',
                    remark:'',
                },
              
                balance: 0,
                topup_percentage: 0,
                isAvialable:'',
                fullname:'',
                username:'',
                values:'',
                email:'',
                getdata:{},
                arrProduct:[],
                min_hash:'',
                max_hash:'',
                isValid:true,
                usermsg:'',
               isDisabledBtn:true,
               otp:""
             
            }
        }, 

    created(){
      //this.getUserDetails();
    },       
        computed: {
            isComplete () {
                return this.fund.user_id && this.fund.amount;
            }
          
        },
        mounted() {
        },
        methods: {
            
           getUserDetails(){
              axios.get('user-details')
              .then(response=>{
                    if(response.data.code==200){
                      this.user_id = response.data.data.user_id;
                      // this.referral_id = response.data.data.ref_user_id;
                     /* this.fullname = response.data.data.fullname;*/
                      // this.ref_fullname = response.data.data.ref_fullname;
                    }else{
                      this.$toaster.error(response.data.message);
                    }
                  }).catch(error=>{

                  })
              },
           
			checkUserExisted(){
				axios.post('/checkuserexist',{
                    user_id: this.username,
                }).then(resp => {
                    if(resp.data.code === 200){
                    	this.fund.user_id = resp.data.data.id;
                    	this.isAvialable = 'Available';
                        this.balance = resp.data.data.setting_fund_wallet_balance;
                        this.topup_percentage = resp.data.data.topup_percentage;
                        this.email = resp.data.data.email;
                        this.fullname = resp.data.data.fullname;
                    } else {
                    	this.fund.user_id = '';
                    	this.isAvialable = 'Not Available';
                    }
                }).catch(err => {
                	this.$toaster.error(err);
                })
			},
             sendOTP() {
                var arr = {};
                axios.post("sendOtp-add-topup", {otp_type:8})
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
            closePopup(){
                $("#editBankDetailsmodal").modal("hide");
            },
            fund_req(){  
                this.isDisabledBtn = false;
                this.$validator.validate().then(valid => {
                    if (valid) {
                        axios.post('add_setting_fund',{
                                user_id:this.fund.user_id,
                                amount:Number(this.fund.amount),
                                topup_percentage:Number(this.topup_percentage),
                                remark:this.fund.remark,
                                otp:this.otp
                            }).then(response=>{
                              if(response.data.code==200){
                                   this.$toaster.success(response.data.message);
                                   $("#editBankDetailsmodal").modal("hide");
                                   this.$router.push({name: 'adminsettingfundreport'});
                                   
                              }else{
                                   this.$toaster.error(response.data.message)
                              }
                              
                               this.fund = {
                                      user_id: '',
                                      amount:''
                                    };
                              $('#fund_req').trigger("reset");
                              this.isDisabledBtn = true;
                        }).catch(error=>{

                        });
                    }
                });
            },
        }
    }
</script>