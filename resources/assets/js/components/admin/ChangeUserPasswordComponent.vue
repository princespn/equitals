<style type="text/css">
    .tooltip2 {
        top: auto;
    }
</style>
<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Change Password</h4>
		    </div>
		</div>

		<div class="page-content-wrapper">
		    <div class="container">

		    	<div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <div class="">
                                    <form id="change-user-password">


                            			<div class="col-md-5 col-md-offset-3">
                            				<input type="hidden" name="user_id" v-model="changepwd.user_id">
                            				<div class="form-group">
    											<label>User Id</label>
    											<input type="text" class="form-control" id="username" placeholder="User Id" v-model="changepwd.username" v-on:keyup="checkUserExisted">
    											<div class="clearfix"></div>
	                        					<p :class="{'text-success': isAvialable == 'Available','text-danger': isAvialable == 'Not Available'}" v-if="isAvialable!=''">{{isAvialable}}</p>
  											</div>
  											<!-- <div class="form-group">
    											<label>Full Name</label>
                                                <input name="fullname" class="form-control" placeholder="Update Full Name" type="text" v-model="changepwd.fullname" v-validate="'required'" readonly >
                                                <div class="tooltip2" v-show="errors.has('fullname')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('fullname')">{{ errors.first('fullname') }}</span>
                                                    </div>
                                                </div>
  											</div> -->
                                            <div class="form-group">
                                                <label class="control-label">New Password</label>
                                                <input name="new_password" class="form-control" placeholder="Enter Your New Password"  type="password" v-model="changepwd.new_password" v-validate="'required'">
                                                <div class="tooltip2" v-show="errors.has('new_password')">
                                                    <div class="tooltip-inner">
                                                       <span v-show="errors.has('new_password')">{{ errors.first('new_password') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Re-enter Password</label>
                                                <input name="retype_password" class="form-control { error: errors.has('retype_password') }" formcontrolname="retype_password" placeholder="Re-Type New Password" id="retype_password" type="password" v-model="changepwd.retype_password"  v-validate="'required'">
                                                <div class="tooltip2" v-show="errors.has('retype_password')">
                                                    <div class="tooltip-inner">
                                                       <span v-show="errors.has('retype_password')">{{ errors.first('retype_password') }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="form-label-group">
                                                    Note:- Password must be more than 6 characters. It should contain uppercase, lowercase, numerical and special characters.              
                                                </div>
                                           </div> 
  											<div class="col-md-offset-5">
  												<button type="button" class="btn btn-primary text-center"  @click.prevent="sendOTP()" :disabled='!isCompletePassword || !isDisabledBtn'>Submit</button>
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
                              <button type="button" @click="changeUserPassword()" class="btn btn-primary">Submit</button>
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
                changepwd:{
                    username:'',
                    fullname: '',
                    user_id: '',
                    new_password: '',
                    retype_password: '',
                },
                changepwd:{
                    fullname: '',
                },
                isAvialable:'',
                isDisabledBtn:true,
                otp:""
            }
        },        
        computed: {
            isCompletePassword(){
                return this.changepwd.username && this.changepwd.user_id && this.changepwd.fullname && this.changepwd.new_password && this.changepwd.retype_password;
            }
        },
        mounted() {
            
        },
        methods: {
            checkUserExisted(){
                axios.post('/checkuserexist',{
                    user_id: this.changepwd.username,
                }).then(resp => {
                    if(resp.data.code === 200){
                        this.changepwd.user_id = resp.data.data.id;
                        this.changepwd.fullname = resp.data.data.fullname;
                        this.isAvialable = 'Available';
                    } else {
                        this.changepwd.user_id = '';
                        this.isAvialable = 'Not Available';
                    }
                }).catch(err => {
                    this.$toaster.error(err);
                })
            },
             sendOTP() {
                var arr = {};
                axios
                    .post("sendOtp-add-topup",  {otp_type:10})
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
            changeUserPassword(){
                var new_pwd = this.changepwd.new_password;
                var conf_pwd = this.changepwd.retype_password;
                if (new_pwd == conf_pwd) {
                    this.isDisabledBtn = false;
                            axios.post('/updateuserpassword',{
                                id: this.changepwd.user_id,
                                password: this.changepwd.new_password,
                                confirm_password: this.changepwd.retype_password,
                                otp:this.otp
                            }).then(resp => {
                                if(resp.data.code === 200) {
                                    this.$toaster.success(resp.data.message);
                                    $("#editBankDetailsmodal").modal("hide");
                                    this.changepwd.user_id='';
                                    this.changepwd.username='';
                                    this.changepwd.fullname='';
                                    this.changepwd.new_password='';
                                    this.t.retype_password='';
                                    this.isAvialable='';
                                    this.otp="";
                                    this.closePopup();
                                } else {
                                    this.$toaster.error(resp.data.message);
                                }
                                // $('#change-user-password').trigger('reset');
                            });
                } else {
                    this.$toaster.error('New Password and Reset Password Not Matched...');
                }  
            },
			
        }
    }
</script>