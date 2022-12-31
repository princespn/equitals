<template>
    <!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Change Transaction Password</h4>
            </div>
        </div>

        <div class="page-content-wrapper">

                <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-12">
                                <form v-on:submit.prevent="changeTrPass">
                                    <div class="row">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> User Id </label>
                                                <input type="text" id="user_id" name="user_id" placeholder="Enter user_id" v-model="user_id" class="{ error: errors.has('user_id') } form-control" v-validate="'required'" v-on:keyup="checkuserexist">
                                                      <div class="tooltip2" v-show="errors.has('user_id')">
                                                            <div class="tooltip-inner">
                                                                <span v-show="errors.has('user_id')">{{ errors.first('user_id') }}</span>
                                                            </div>
                                                      </div>
                                            </div>
                                            <div class="form-group" style="position: relative;">
                                                <label> New Transaction Password </label>
                                                 <input type="password" id="password" name="password" placeholder="Enter password" v-model="password" class="{ error: errors.has('password') } form-control" v-validate="'required|min:8'">
                                                      <div class="tooltip2" v-show="errors.has('password')">
                                                            <div class="tooltip-inner">
                                                                <span v-show="errors.has('password')">{{ errors.first('password') }}</span>
                                                            </div>
                                                      </div>
                                            </div>
                                            <div class="form-group" style="position: relative;">
                                                <label>Re-Type  Transaction Password</label>
                                                <input type="password" id="re_type_password" name="re_type_password" placeholder="Enter re_type_password" v-model="re_type_password" class="{ error: errors.has('re_type_password') } form-control" v-validate="'required'" v-on:keyup="matchpassword">
                                                      <div class="tooltip2" v-show="errors.has('re_type_password')">
                                                            <div class="tooltip-inner">
                                                                <span v-show="errors.has('re_type_password')">{{ errors.first('re_type_password') }}</span>
                                                            </div>
                                                      </div>

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
            
           // this.pendingKYCReport();
        },
        methods: {
                changeTrPass(){
                /*this.$validator.validateAll().then((result) => {
                    //console.log(result);
                    if (!result) {
                        //console.log("failed")                            
                        return false;
                    }*/

                   
                    this.errmessage  = '';
                axios.post('update/trans/password', {
                    new_password: this.password,
                    retype_password: this.re_type_password,
                    user_id:this.user_id
                }).then(response => {
                    if(response.data.code == 200) {
                        console.log(response);
                     /*   this.flash(response.data.message, 'success', {
                          timeout: 500000,
                        });*/
                    } else {
                       // this.errmessage  = response.data.message;
                        /*this.flash(this.errmessage, 'warning', {
                            timeout: 100000,
                        });*/
                    }
                }). catch(error => {
                        //this.reset();
                       // this.message  = response.data.message;
                        /*this.flash(this.message, 'error', {
                          timeout: 500000,
                        });*/
                    });
               
            },
             checkuserexist() {

                 axios.post('checkuserexist',{
                   user_id:this.user_id,
                 }).then(response => {
                      //console.log(response.data.code);
                     if(response.data.code == 404) {

                       
                        this.custom_msg_class = 'text-danger';
                         this.userExistsMessage = response.data.message;  
                        
                        this.errors.add({
                        field: 'user_id',
                        msg: 'User not available'
                        });
                    
                    }else{
                         this.hiddenuserid = response.data.data.id; 
                        this.custom_msg_class = 'text-success';  
                        this.userExistsMessage = response.data.message;
                        //this.hiddenuserid = response.data.data.id
                    }
                  // this.userexistmsg = response.data.message;
                     
                }).catch(error => {
                    console.log(error);
                    this.message  = '';
                    this.flash(this.message, 'error', {
                      timeout: 500000,
                    });
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
                      console.log('Match');
                 }
            }             
           
        }
    }
</script>