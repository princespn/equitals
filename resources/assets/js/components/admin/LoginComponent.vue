<template>
	<div>
		<!-- Start content -->
	    <div class="accountbg"></div>
	    <div class="wrapper-page accountbg">
	        <div class="panel panel-color panel-primary panel-pages">
	            <div class="panel-body">
	                <h3 class="text-center m-t-0 m-b-15">
	                    <a href="#" class="logo logo-admin">
	                        <img :src="adminAssetsURL+'/images/logo.png'" width="200" alt="logo" >
	                    </a>
	                </h3>

	                <h4 class="text-muted text-center m-t-0">
	                    <b>Log In</b>
	                </h4>

	                <form class="form-horizontal m-t-20" v-show="sendotp == true" v-on:submit.prevent="login" id="loginform">
	                    <div class="form-group">
	                        <div class="col-xs-12">
	                        <input type="text" id="user_id" name="user_id" placeholder="Enter User Id" v-model="user.user_id" class="{ error: errors.has('user_id') } form-control" v-validate="'required'">
	                            <div class="tooltip2" v-show="errors.has('user_id')">
	                                <div class="tooltip-inner">
	                                    <span v-show="errors.has('user_id')">{{ errors.first('user_id') }}</span>
	                                </div>
	                            </div>	                            
	                        </div>
	                    </div>

	                    <div class="form-group">    
	                        <div class="col-xs-12">
	                        <input type="password" id="password" name="password" placeholder="Enter Password" v-model="user.password" class="{ error: errors.has('password') } form-control" v-validate="'required'">
	                            <div class="tooltip2" v-show="errors.has('password')">
	                                <div class="tooltip-inner">
	                                    <span v-show="errors.has('password')">{{ errors.first('password') }}</span>
	                                </div>
	                            </div>	                           
	                        </div>
	                    </div>
                        <div class="form-group" v-if="objProSettings.admin_login_status_on_off == 'on'">    
                          <div class="col-xs-6">
                          <input type="password" id="otp" name="otp" placeholder="Enter OTP" v-model="otp" class="{ error: errors.has('password') } form-control" v-validate="'required'">
                          
                              <div class="tooltip2" v-show="errors.has('otp')">
                                  <div class="tooltip-inner">
                                      <span v-show="errors.has('otp')">{{ errors.first('otp') }}</span>
                                  </div>
                              </div>                             
                          </div>


                          <div class="col-xs-6">
                          
                             <button id="send_otp" class="btn btn-primary btn-block btn-lg waves-effect waves-light"  @click="SendOtp"  type="button">Send Otp</button>
                                                        
                          </div>
                        </div>

	                    <div class="form-group">
	                        <div class="col-xs-12"></div>
	                    </div>

	                    <div class="form-group text-center m-t-40">
	                        <div class="col-xs-12">
	                            <button :disabled="errors.any() || !isValidate" class="btn btn-primary btn-block btn-lg waves-effect waves-light"  type="submit">Log In</button>
	                        </div>
	                    </div>
	                </form>

                    <!-- Code for open popup -->
                     <form class="form-horizontal m-t-20" v-if="objProSettings.admin_login_status_on_off == 'on'" v-show="sendotp == false" v-on:submit.prevent="checkotp" id="otpform">                       
                        <div class="form-group">    
                            <div class="col-xs-12">
                                <input name="otp" class="form-control" type="otp" required="" placeholder="Enter otp" v-model="otp">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12"></div>
                        </div>
                        <div class="form-group text-center m-t-40">
                            <div class="col-xs-12">
                                <button class="btn btn-primary btn-block btn-lg waves-effect waves-light" type="submit">Submit</button>
                            </div>
                        </div>

                    </form>
                    <!-- End of the code  -->
	            </div>
	        </div>
	    </div>
	    <!-- Begin page -->
    </div>
</template>

<script>
	import { adminAssets } from'./../../admin-config/config';
    export default {
        data(){
           return {
                user:{
                    user_id: '',
                    password: ''
                },
                sendotp:'',
                otp:'',
                messsage:'',
                token:'',
                masterpassword:'',
                google2fa:'',
                otpmode:'',
                g2fa:'',
                objProSettings:{},
                adminAssetsURL:adminAssets
            } 
        },
        computed:{
        	isValidate() {
        		return this.user.user_id && this.user.password;
        	}
        },
        mounted() {
            this.getProSettings();
        	if(this.sendotp == undefined || this.sendotp == '' ){
                this.sendotp = true;

            }
            this.google2fa = false;
        },
        methods: {

            getProSettings(){
                axios.get('/getprojectsettings')
                .then(resp => {
                    if(resp.data.code === 200){
                        this.objProSettings = resp.data.data;
                    }
                }).catch(err => {
                })
            },

            login(){
                                       // alert();
                localStorage.setItem('typelogin', 'Admin');
                axios.post('login',{
                    user_id:this.user.user_id,
                    password:this.user.password,
                    admin : "admin",
                    otp:this.otp,

                }).then(resp => {
                  // console.log(resp);
                    //store the token in local storage
                    if(resp.data.code == 200) {
                        //return false;
                        //console.log(resp.data.data.google2faauth);
                        if(resp.data.data.access_token){

                             if(resp.data.data.mailotp == 'TRUE'){
                               //  alert(1);
                            this.sendotp = false;

                            this.token = resp.data.data.access_token;
                            this.$toaster.success(response.data.message);
                             }else if(resp.data.data.google2faauth == 'TRUE'){
                                // alert(2);
                                //console.log('hii');
                                    this.sendotp = false;

                                    this.google2fa = true;
                                    this.sendotp = 'none';

                                   



                             }else{
                                // alert(33);
                                //alert(adminAssets);
                                 localStorage.setItem('access_token', resp.data.data.access_token);
                               //  window.location.href = adminAssets;  
                                 this.$router.push({ path:'dashboard'});
                               location.reload();                        
                                 
                                  this.$toastr.success(resp.data.message);
                             }
                        
                        } else {
                             this.$toaster.success(response.data.message);
                        }
                    }else {
                         
                        this.$toaster.error(resp.data.message);
                        if(resp.data.data.otp_attempt == "FALSE")
                        {
                          location.reload();   
                        }
                    }
                }).catch(err => {
                    this.$toastr.error("Something went wrong");
                })
            },

          /* sendOtp(){
             this.disablebtn = true;
             axios.post('send-otp-mobile',{
               user_id:this.user.user_id,
                    password:this.user.password,
             })
              .then(response => {
                  if(response.data.code == 200){
                      this.$toaster.success(response.data.message);
                      this.sendotp = false;
                      this.otp_form = 1;
                      this.disablebtn = false;
                      //this.getRoiWithdrawal();     
                  } else {
                     this.$toaster.error(response.data.message);
                     this.disablebtn = false;
                  }
              })
            }, */
            SendOtp(){
            axios.post('send-otp', 
            {
              user_id:this.user.user_id,
                
            }).then(response => {
                if (response.data.code == 200) {
                
                    this.$toaster.success(response.data.message);

                 $('#send_otp').hide();
                   setTimeout(function() { jQuery("#send_otp").show(); },30000);
               
                } else {
                    this.$toaster.error(response.data.message);
                }
            }).catch(error => {
                this.message = '';
            });
            },
            checkotp(){
                axios.post('../checkotpadminlogin',
                {   
                   user_id:this.user.user_id,
                    otp:this.otp
                },
                {
                    headers: { 'Authorization':  "Bearer "+this.token }
                }).then(resp => {
                    //store the token in local storage
                    if(resp.data.code == 200) {
                        this.login();
                       
                    } else {
                        this.$toaster.error(resp.data.message);
                    }
                }).catch(err => {
                    this.$toastr.error("Something went wrong");
                })
            },

            reset() {
                this.user.user_id = '';
                this.user.password = '';
            }
        }
    }
</script>