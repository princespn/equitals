<template>
	<div>
		<!-- Start content -->
	    <div class="accountbg"></div>
	    <div class="wrapper-page accountbg">
	        <div class="panel panel-color panel-primary panel-pages">
	            <div class="panel-body">
	                <h3 class="text-center m-t-0 m-b-15">
	                    <a href="#" class="logo logo-admin">
	                        <img :src="adminAssetsURL+'/images/loginLogo.png'" alt="logo">
	                    </a>
	                </h3>

	                <h4 class="text-muted text-center m-t-0">
	                    <b>Verify 2FA Code</b>
	                </h4>

	                <form class="form-horizontal m-t-20" v-on:submit.prevent="sendOtp">
	                    <div class="form-group">
	                        <div class="col-xs-12">
	                        <input type="password" id="googleotp" name="googleotp" placeholder="Enter 2FA Code" v-model="user.googleotp" class="{ error: errors.has('googleotp') } form-control" v-validate="'required'">
	                              <div class="tooltip2" v-show="errors.has('googleotp')">
		                                <div class="tooltip-inner">
		                                    <span v-show="errors.has('googleotp')">{{ errors.first('googleotp') }}</span>
		                                </div>
	                              </div>
	                        </div>
	                    </div>
	                    <div class="form-group text-center m-t-40">
	                        <div class="col-xs-12">
	                            <button :disabled="errors.any() || !isValidate" class="btn btn-primary btn-block btn-lg waves-effect waves-light"  type="submit">Verify</button>
	                        </div>
	                    </div>
	                </form>
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
                    googleotp: ''
                },
                adminAssetsURL:adminAssets,
                token:'',
            } 
        },
        computed:{
        	isValidate() {
        		return this.user.googleotp;

        	}
        },
        mounted() {
        	  this.token = this.$route.params.token 
        },
        methods: {
            sendOtp(){
                axios.post('/2fa/validatelogintoken',this.user).then(resp => {
                	//store the token in local storage
                    if(resp.data.code === 200){
                        localStorage.setItem('access_token', this.token);
                        localStorage.setItem("admin_auth", true);
                        //store the token in localstorage
                        this.$router.push({ name:'dashboard' });
                    	location.reload();
                   		this.$toaster.success(resp.data.message);
                    } else {
                   		this.$toaster.error(resp.data.message);
                    }
                }).catch(err => {
                   	this.$toaster.error(err);
                })
            },
            reset() {
                this.user.otp = '';
            }
        }
    }
</script>