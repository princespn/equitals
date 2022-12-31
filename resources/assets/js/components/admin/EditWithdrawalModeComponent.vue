<template>
    <div class="content">

        <div class="">
            <div class="page-header-title">
                <h4 class="page-title"> Edit Payment Mode </h4>
            </div>
        </div>

        <div class="page-content-wrapper ">

            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-3">
                        <div class="panel panel-primary">
                            <div class="panel-body add-new">
                                <a class="btn btn-primary waves-effect waves-light pull-right" href="admin#/withdrawal-payment">
                                    <i class="fa fa-mail-reply"></i> &nbsp;Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <h4 class="m-t-0 m-b-30"> Payment Information </h4>
                                <form class="form-horizontal" v-on:submit.prevent="onUpdateUserClick">
                                    <input type="hidden" name="id" v-model="user_id">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">
                                                User Id
                                                <!-- <span class="madatoryStar text-danger text-danger">*</span> -->
                                            </label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="User ID" readonly type="text" v-model="user_id">
                                            </div>
                                        </div>
                                    </div>

                                 
                                    <div class="col-md-6">
                                        <div class="">
                                            <label class="col-md-5 control-label"> Modes </label>
                                            <div class="col-md-7">
                                                <select class="form-control" v-model="mode">
                                                    <option :value="mode" v-for="mode in modes">{{mode}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"> Full Name
                                                <span class="madatoryStar text-danger"> *</span>
                                            </label>
                                            <div class="col-md-7">
                                                <input name="fullname" class="form-control" placeholder="Update Full Name" type="text" v-model="fullname" v-validate="'required'" readonly="">
                                                <div class="tooltip2" v-show="errors.has('fullname')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('fullname')">{{ errors.first('fullname') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>    

                                   

                                    <!--  


                                    -->



                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-5 col-md-6">
                                            <div class="col-md-6">
                                                <button class="btn btn-info col-md-12" name="submit" type="submit">
                                                    <i class="ace-icon fa fa-check bigger-110"></i>Submit
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { apiAdminHost } from'./../../admin-config/config';
    import Swal from 'sweetalert2';
    import DatePicker from 'vuejs-datepicker';
    import moment  from 'moment';

    export default {
    	
        data() {
            return {
                modes:[
                'BTC',
                'INR'
                ],
                mode:'',
                user_id:'',
                fullname:'',
                postData:{
                    id:'',
                    mode:'',
                },
               
            }
        }, 
        components: {
            DatePicker
        },       
        computed: {

        },
        mounted() {
            this.getUserDetails();
          //  this.getCountry();
        },
        methods: {
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            getUserDetails(){
                axios.post('/getwithdrawaltype',{
                    id: this.$route.params.id,
                }).then(resp => {
                     
                    if(resp.data.code === 200){
                        
                            this.mode = resp.data.data.mode;
                            this.user_id = resp.data.data.userId;
                            this.fullname = resp.data.data.fullname;
                            this.postData.id = this.$route.params.id;
                            this.postData.mode = this.mode;
                     
                    } else {
                        this.$toaster.error(resp.data.message);
                    }
                }).catch(err => {
                    this.$toaster.error(err);
                })
            },
            onUpdateUserClick() {
                Swal({
                    title: 'Are you sure?',
                    text: `You want to update payment mode`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        this.postData['mode'] = this.mode;
                        axios.post('/updatepaymentmode',this.postData)
                        .then(resp => {
                            if(resp.data.code === 200){
                                this.$router.push({name:'withdrawalpayment'});
                                this.$toaster.success(resp.data.message);
                            } else {
                                this.$toaster.error(resp.data.message);
                            }
                        }).catch(err => {
                            this.$toaster.error(err.resp.data.message);
                        })
                    }
                });
            },

           
            // onChangeCountry(countryName){
            //     axios.post('/getstatebycountry',{
            //         country:countryName
            //     }).then(resp => {
            //         if(resp.data.code === 200){
            //             this.arrStates = resp.data.data;
            //         } else {
            //             this.$toaster.error(resp.data.message);
            //         }
            //     }).catch(err => {
            //         this.$toaster.error(err);
            //     })
            // }
        }
    }
</script>