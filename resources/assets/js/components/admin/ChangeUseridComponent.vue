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
                <h4 class="page-title">Change Userid</h4>
            </div>
        </div>

        <div class="page-content-wrapper">
            <div class="container">

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <div class="">
                                    <form id="change-userid">


                                        <div class="col-md-5 col-md-offset-3">
                                            <input type="hidden" name="id" v-model="changeid.id">
                                            <div class="form-group">
                                                <label>old User Id</label>
                                                <input type="text" class="form-control" id="" placeholder="User Id" v-model="changeid.old_user_id" v-on:keyup="checkUserExisted" name="old_user_id">
                                                <div class="clearfix"></div>
                                                <p :class="{'text-success': isAvialable == 'Available','text-danger': isAvialable == 'Not Available'}" v-if="isAvialable!='' && changeid.old_user_id!=''">{{isAvialable}}</p>
                                            </div>

                                            <div class="form-group">
                                                <label>Full Name</label>
                                                <input name="fullname" class="form-control"  type="text" v-model="changeid.fullname" v-validate="'required'" readonly >
                                                <div class="tooltip2" v-show="errors.has('fullname')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('fullname')">{{ errors.first('fullname') }}</span>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="control-label">New userid</label>
                                                <!-- <input type="hidden" name="id" v-model="changeid.id"> -->
                                                <input name="new_user_id" class="form-control" placeholder="Enter Your New userid"  type="text" v-model="changeid.new_user_id" v-validate="'required'" v-on:keyup="checkUserId">
                                                <!-- <div class="tooltip2" v-show="errors.has('new_user_id')">
                                                    <div class="tooltip-inner">
                                                       <span v-show="errors.has('new_user_id')">{{ errors.first('new_user_id') }}</span>
                                                    </div>
                                                </div> -->
                                                <p :class="{'text-danger': isUserExit == 'userid already existed','text-success': isUserExit == 'new userid generated successfully'}" v-if="isUserExit!=''&& changeid.new_user_id!='' ">{{isUserExit}}</p>
                                                
                                            </div>
                                            <div class="col-md-offset-5">
                                                <button type="button" class="btn btn-primary text-center" @click="changeUserid()" :disabled="err">Change Userid</button>
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
                changeid:{
                    id:'',
                    old_user_id: '',
                    new_user_id: '',
                    fullname:'',
                   
                },
                changeid:{
                    fullname: '',
                },
                isAvialable:'',
                isUserExit : '',
                isDisabledBtn:true,
            }
        },        
        computed: {
            isCompleteUserid(){
                return this.changeid.old_user_id  && this.changeid.new_user_id && this.changeid.id && this.changeid.fullname;
            }
        },
        mounted() {
            this.changeid.new_user_id="DX"+parseInt(Math.floor(Math.random()*90000000) + 10000000);
           
        },
        methods: {
            checkUserExisted(){
                axios.post('/checkuserexist',{
                     user_id: this.changeid.old_user_id,

                }).then(resp => {
                    if(resp.data.code === 200){
                        this.changeid.id = resp.data.data.id;
                        this.changeid.fullname = resp.data.data.fullname;
                        this.isAvialable = 'Available';
                        
                    } else {
                        this.changeid.id = '';
                        this.isAvialable = 'Not Available';
                    }

                }).catch(err => {
                    this.$toaster.error(err);
                })
            },


            checkUserId(){
                axios.post('/checkuserexist',{
                     user_id: this.changeid.new_user_id,
                }).then(resp => {
                    if(resp.data.code === 200){
                        this.isUserExit = 'userid already exist';
                        this.err = true;
                    } else {
                        // this.changeid.id = '';
                        this.isUserExit = 'New userid generated';
                        this.err = false;
                    }

                }).catch(err => {
                    this.$toaster.error(err);
                })
            },

            


            changeUserid(){
                var old_user_id = this.changeid.old_user_id;
                var new_user_id = this.changeid.new_user_id;
                if (old_user_id != new_user_id) {
                    this.isDisabledBtn = false;
                    Swal({
                        title: 'Are you sure ?',
                        text: "You want to change User id!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.value) {
                            axios.post('/updateuserid',{
                                old_user_id: this.changeid.old_user_id,
                                new_user_id: this.changeid.new_user_id,
                                id: this.changeid.id,

                            }).then(resp => {
                                if(resp.data.code === 200) {
                                    this.$toaster.success(resp.data.message);

                                    this.$router.push({name: 'change-userid-report'});
                                } else {
                                    this.$toaster.error(resp.data.message);
                                }
                                $('#change-userid').trigger('reset');
                            }).catch(err => {
                                this.$toaster.error(err);
                            });
                        }
                    });     
                } else {
                    this.$toaster.error('new userid already Available');
                }  
            },
            
        }
    }
</script>