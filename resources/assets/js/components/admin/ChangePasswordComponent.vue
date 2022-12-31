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
                         
                           <div class="form-label-group">
                                   Note:- Password must be more than 6 characters. It should contain uppercase, lowercase, numerical and special characters.              
                                    </div> 
                        <div class="row">
                            <div class="col-md-12">
                                <form v-on:submit.prevent="changePass">
                                    <div class="row">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> User Id </label>
                                                 <input type="text" id="user_id" name="user_id" placeholder="Enter User Id" v-model="user_id" class="{ error: errors.has('user_id') } form-control" v-validate="'required'" v-on:keyup="checkuserexist">
                                                      <div class="tooltip2" v-show="errors.has('user_id')">
                                                            <div class="tooltip-inner">
                                                                <span v-show="errors.has('user_id')">{{ errors.first('user_id') }}</span>
                                                            </div>
                                                      </div>
                                               
                                            </div>
                                            <div class="form-group" style="position: relative;">
                                                <label> New Password </label>
                                                 <input type="password" id="password" name="password" placeholder="Enter New password" v-model="password" class="{ error: errors.has('password') } form-control" v-validate="'required|min:6'">
                                                      <div class="tooltip2" v-show="errors.has('password')">
                                                            <div class="tooltip-inner">
                                                                <span v-show="errors.has('password')">{{ errors.first('password') }}</span>
                                                            </div>
                                                      </div>
                                                <!-- <input class="form-control" type="password" name="new_password" placeholder="Enter New Password"  v-model="password"> -->
                                            </div>
                                            <div class="form-group" style="position: relative;">
                                                <label>Re-Type Password</label>
                                                  <input type="password" id="re_type_password" name="re_type_password" placeholder="Enter Re-Type Password" v-model="re_type_password" class="{ error: errors.has('re_type_password') } form-control" v-validate="'required'" v-on:keyup="matchpassword">
                                                      <div class="tooltip2" v-show="errors.has('re_type_password')">
                                                            <div class="tooltip-inner">
                                                                <span v-show="errors.has('re_type_password')">{{ errors.first('re_type_password') }}</span>
                                                            </div>
                                                      </div>
                                              <!--   <input class="form-control" type="password" name="retype_password" placeholder="Enter Re-Type Password" v-model="re_type_password"> -->

                                            </div>
                                        </div>
                                        <div class="col-md-3"></div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light">Update Password</button>
                                    </div>
                                </form>
                            </div>
                            <!-- col -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
		</div><!-- Page content Wrapper -->
	</div><!-- content -->
</template>

<script>
    import { apiAdminHost } from'./../../admin-config/config';
    export default {
    	
        data() {
            return {
                user_id: '',
                password: '',
                re_type_password: '',               
                custom_msg_class:'',
                userExistsMessage:'',
                hiddenuserid:null,
                id:'',
            }
        },
        mounted() {
        },
        methods: {
            changePass(){
                axios.post('updateuserpassword', {
                    password: this.password,
                    id: this.hiddenuserid
                }).then(resp => {
                    if(resp.data.code === 200) {
                        this.$toaster.success(resp.data.message);
                    } else {
                        this.$toaster.error(resp.data.message);
                    }
                }).catch(error => {
                    this.$toaster.error(error.response.data.message);
                }); 
            },
            checkuserexist() {

                 axios.post('checkuserexist',{
                   user_id:this.user_id,
                 }).then(response => {
                      //console.log(response.data.code);
                     if(response.data.code == 404) {

                       
                        //this.custom_msg_class = 'text-danger';
                         //this.userExistsMessage = response.data.message;  
                        
                        this.errors.add({
                        field: 'user_id',
                        msg: 'User not available'
                        });
                    
                    }else{
                    	 this.hiddenuserid = response.data.data.id; 
                        this.custom_msg_class = 'text-success';  
                        this.userExistsMessage = response.data.message;
                    }
                }).catch(error => {
                    
                });
            }, 
            matchpassword() {
               //  console.log(this.password);
                //  console.log(this.password_confirmation);

                if(this.password !== this.re_type_password){
                      /*this.errors.add('this.password_confirmation', 'not match')*/
                  this.errors.add({
                  field: 're_type_password',
                  msg: 'password does not match'
                  });
                     //console.log(this.errors.errors);
                    // console.log('not match');
                 }else{
                    
                 }
            }
                     	
           
        }
    }
</script>