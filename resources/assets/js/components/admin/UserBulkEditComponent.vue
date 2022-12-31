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
                <h4 class="page-title">Bulk User Update</h4>
            </div>
        </div>

        <div class="page-content-wrapper">
            <div class="container">

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <div class="">
                                    <form id="update-users">


                                        <div class="col-md-5 col-md-offset-3">
                                            <div class="form-group">
                                                <label>Enter , Seperated User Id</label>
                                                <textarea id="user_ids" name="user_ids" class="form-control" v-model="user_ids" placeholder="Enter , Seperated UserID"></textarea>
                                                <div class="clearfix"></div>
                                            </div>

                                            <div class="form-group">
                                                <label>Full Name</label>
                                                <input name="fullname" class="form-control"  type="text" v-model="fullname">
                                                <div class="tooltip2" v-show="errors.has('fullname')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('fullname')">{{ errors.first('fullname') }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label>Email</label>
                                                <input name="email" class="form-control"  type="text" v-model="email">
                                                <div class="tooltip2" v-show="errors.has('email')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('email')">{{ errors.first('email') }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label>Mobile No.</label>
                                                <input name="mobile" class="form-control"  type="text" v-model="mobile">
                                                <div class="tooltip2" v-show="errors.has('mobile')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('mobile')">{{ errors.first('mobile') }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label>Select Country</label>
                                                <select name="country" class="form-control" v-model="country" id="country" v-validate="'required'">
                                                    <option value="">Select Country</option>
                                                    <option v-for="country in countries" v-bind:value="country.iso_code">
                                                    {{ country.country }}
                                                    </option>
                                                </select>
                                                <div class="tooltip2" v-show="errors.has('country')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('country')">{{ errors.first('country') }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">New Password</label>
                                                <input name="new_password" class="form-control" placeholder="Enter New Password"  type="password" v-model="new_password">
                                                <div class="tooltip2" v-show="errors.has('new_password')">
                                                    <div class="tooltip-inner">
                                                       <span v-show="errors.has('new_password')">{{ errors.first('new_password') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Re-enter Password</label>
                                                <input name="retype_password" class="form-control { error: errors.has('retype_password') }" formcontrolname="retype_password" placeholder="Re-Type New Password" id="retype_password" type="password" v-model="retype_password"  v-validate="'required'">
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
                                                <button type="button" class="btn btn-primary text-center" @click="updateBulkUsers()" :disabled="err">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div><!-- panel-body -->
                        </div><!-- panel -->
                    </div><!-- col -->
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
                fullname:'',
                mobile:'',
                email:'',
                new_password:'',
                retype_password:'',
                user_ids:'',
                isAvialable:'',
                isUserExit : '',
                isDisabledBtn:true,
                countries:[],
                country:'',
            }
        },        
        computed: {
            isCompleteUserid(){
                return this.user_ids  && (this.email  || this.mobile || this.password || this.fullname || this.retype_password);
            }
        },
        mounted() {
            this.getCountry();
        },
        methods: {
            getCountry() {
              axios.get("/getcountry").then(resp => {
                    if (resp.data.code === 200) {
                        this.countries = resp.data.data;
                    } else {
                        this.$toaster.error(resp.data.message);
                    }
                }).catch(err => {
                    this.$toaster.error(err);
                });
            },   
            updateBulkUsers(){
                this.isDisabledBtn = false;
                Swal({
                    title: 'Are you sure ?',
                    text: "You want to update users",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/updatebulkusers',{
                            mobile: this.mobile,
                            fullname: this.fullname,
                            email: this.email,
                            user_ids:this.user_ids,
                            password:this.new_password,
                            country:this.country,

                        }).then(resp => {
                            if(resp.data.code === 200) {
                                this.$toaster.success(resp.data.message);

                                this.$router.push({name: 'bulk-user-update-report'});
                            } else {
                                this.$toaster.error(resp.data.message);
                            }
                            $('#update-users').trigger('reset');
                        })
                    }
                }); 
            },
            
        }
    }
</script>