<template>
	 <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="header-navbar-shadow"></div>
      <div class="content-wrapper">
        <div class="content-header row">
          <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
              <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Change Password</h2>
             <!--    <div class="breadcrumb-wrapper col-12">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Change Password
                    </li>
                  </ol>
                </div> -->
              </div>
            </div>
          </div>
          <!-- <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
            <div class="form-group breadcrum-right">
              <div class="dropdown">
                <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-settings"></i></button>
                <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="#">Chat</a><a class="dropdown-item" href="#">Email</a><a class="dropdown-item" href="#">Calendar</a></div>
              </div>
            </div>
          </div> -->
        </div>
        <div class="content-body">
          <section id="multiple-column-form">
            <div class="row match-height">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                          <!--   <h4 class="card-title">Change Password</h4> -->
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <form id="update-user-password" class="form">
                                    <div class="form-body">
                                       <div class="form-label-group">
                                   Note:- Password must be more than 6 characters. It should contain uppercase, lowercase, numerical and special characters.              
                                    </div> 
                                        <div class="row">

                                                                              
                                            <div class="col-md-4 col-12">
                                                <div class="form-label-group">
			                                             		<input name="old_password" class="form-control" placeholder="Enter Your Old Password" id="old_password" type="password" v-model="updatepassword.old_password" v-validate="'required'" data-vv-as="old password">
	                                                            <div class="tooltip2" v-show="errors.has('old_password')">
	                                                                <div class="tooltip-inner">
	                                                                   <span v-show="errors.has('old_password')">{{ errors.first('old_password') }}</span>
	                                                                </div>
	                                                            </div>
                                                    <label for="old-password">Old Password</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-label-group">
                                                    	<input ref="new_password" name="new_password" class="form-control" placeholder="Enter Your New Password" id="new_password" type="password" v-model="updatepassword.new_password" v-validate="'required|min:6|max:30'" data-vv-as="new password">
                    														<div class="tooltip2" v-show="errors.has('new_password')">
                    															<div class="tooltip-inner">
                    																<span v-show="errors.has('new_password')">{{ errors.first('new_password') }}</span>
                    															</div>
                    														</div>
                                                    <label for="new-password">New Password</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-label-group">
                                                   <input name="retype_password" class="form-control" placeholder="Re-Type New Password" id="retype_password" type="password" v-model="updatepassword.retype_password"  v-validate="'required'" data-vv-as="re-enter password" v-on:keyup="matchpassword">
														<div class="tooltip2" v-show="errors.has('retype_password')">
															<div class="tooltip-inner">
																<span v-show="errors.has('retype_password')">{{ errors.first('retype_password') }}</span>
															</div>
														</div>
                                                    <label for="re-enter-password">Re-enter Password</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <button type="button" class="btn btn-primary mr-1 mb-1" @click="updateUserPassword" :disabled='!isCompletePassword || errors.any() || !isDisableBtn'>Change Password</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        </div>
      </div>
    </div>
</template>
<script>
	import Breadcrum from './BreadcrumComponent.vue';
	import Swal from 'sweetalert2';
   	export default {  
      	components: {
         	Breadcrum
      	}, 
        data(){
            return{
                updatepassword: {
                    old_password: '',
                    new_password: '',
                    retype_password: '',
                },
				updatepassword:[],
				isDisableBtn:true
            }
        },
        computed:{
            isCompletePassword(){
                return this.updatepassword.old_password && this.updatepassword.new_password && this.updatepassword.retype_password;
            }
        },
        methods:{
            updateUserPassword() {            	
                var new_pwd = this.updatepassword.new_password;
                var conf_pwd = this.updatepassword.retype_password;
                if (new_pwd == conf_pwd) {
					this.isDisableBtn = false;
                	Swal({
	                    title: 'Are you sure?',
	                    text: `You want to change password`,
	                    type: 'warning',
	                    showCancelButton: true,
	                    confirmButtonColor: '#3085d6',
	                    cancelButtonColor: '#d33',
	                    confirmButtonText: 'Yes'
	                }).then((result) => {
	                    if (result.value) { 
		                    axios.post('change-password', {                    
		                        current_pwd: this.updatepassword.old_password,
		                        new_pwd: this.updatepassword.new_password,
		                        conf_pwd: this.updatepassword.retype_password,           
		                    })
		                    .then(response => {   
		                    	if(response.data.code === 200) {
				                    this.$toaster.success(response.data.message);
				                }else{
				                    this.$toaster.error(response.data.message) 
								}
								$('#update-user-password').trigger("reset");
		                    })
		                }
                	});
                } else {
                    this.$toaster.error('New Password and Reset Password Not Matched...');
                }                
            },
            matchpassword() {
	            if(this.updatepassword.new_password != this.updatepassword.retype_password){
	                /*this.errors.add('this.password_confirmation', 'not match')*/
	              	this.errors.add({
	              		field: 'retype_password',
	              		msg: 'password does not match'
	              	});
	            } else {
					
	            }
            }
        }
	}
</script>