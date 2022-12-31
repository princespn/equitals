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
                <h4 class="page-title">Top Leader Summary</h4>
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
                                        <div class="col-md-12">
                                            <input type="hidden" name="id" v-model="id">
                                            <div class="form-group col-md-3">
                                                <label>User Id</label>
                                                <input type="text" class="form-control" id="" placeholder="User Id" v-model="user_id" v-on:keyup="checkUserExisted" name="user_id">
                                                <div class="clearfix"></div>
                                                <p :class="{'text-success': isAvialable == 'Available','text-danger': isAvialable == 'Not Available'}" v-if="isAvialable!='' && user_id!=''">{{isAvialable}}</p>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label>Full Name</label>
                                                <input name="fullname" class="form-control"  type="text" v-model="fullname" v-validate="'required'" readonly >
                                                <div class="tooltip2" v-show="errors.has('fullname')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('fullname')">{{ errors.first('fullname') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>From Date</label>
                                                <div>
                                                    <div class="input-group">
                                                        <DatePicker :bootstrap-styling="true" name="frm_date" :format="dateFormat" placeholder="From Date" id="frm_date"></DatePicker>
                                                        <span class="input-group-addon bg-custom b-0 datepicker_border">
                                                            <i class="mdi mdi-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>To Date</label>
                                                <div>
                                                    <div class="input-group">
                                                        <DatePicker :bootstrap-styling="true" name="to_date" :format="dateFormat" placeholder="To Date" id="to_date"></DatePicker>
                                                        <span class="input-group-addon bg-custom b-0 datepicker_border">
                                                            <i class="mdi mdi-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>    
                                        <div class="col-md-12 text-center">
                                                <button type="button" class="btn btn-primary text-center" @click="getSummary()">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div><!-- panel-body -->
                        </div><!-- panel -->
                    </div><!-- col -->
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <table id="direct-income-report" class="table table-striped table-bordered dt-responsive">
                                    <thead style="visibility: hidden;">
                                        <tr><th style="width:50%"></th><th style="width:50%"></th></tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><b>Downline topup count</b></td>
                                            <td class="text-center">{{summary.downline_topup_count}}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Total Register Count</b></td>
                                            <td class="text-center">{{summary.total_register}}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Total Confirmed Withdrawal Amount</b></td>
                                            <td class="text-center">{{summary.total_confirm_withdraw}}</td>
                                        </tr>
                                        <tr>
                                            <td><b>DexWallet Downline Total Amount</b></td>
                                            <td class="text-center">{{summary.total_dex_wallet}}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Total Purchase Wallet Balance</b></td>
                                            <td class="text-center">{{summary.total_purchase_wallet}}</td>
                                        </tr>
                                    </tbody>
                                </table>
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

    export default {
        
         data() {
            return {
                summary:{
                    downline_topup_count:0,
                    total_register:0,
                    total_confirm_withdraw:0,
                    total_dex_wallet:0,
                    total_purchase_wallet:0,                     
                },
                id:'',
                user_id: '',               
                fullname: '',               
                isAvialable:'',
                isUserExit : '',
                isDisabledBtn:true,
                err:'',
            }
        },        
        computed: {
            isCompleteUserid(){
                return this.user_id  && this.fullname;
            }
        },
        mounted() {
           
        },
        methods: {
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            checkUserExisted(){
                axios.post('/checkuserexist',{
                     user_id: this.user_id,

                }).then(resp => {
                    if(resp.data.code === 200){
                        this.id = resp.data.data.id;
                        this.fullname = resp.data.data.fullname;
                        this.isAvialable = 'Available';
                        
                    } else {
                        this.id = '';
                        this.fullname = ''
                        this.isAvialable = 'Not Available';
                        this.summary={
                            downline_topup_count:0,
                            total_register:0,
                            total_confirm_withdraw:0,
                            total_dex_wallet:0,
                            total_purchase_wallet:0,                     
                        };
                    }

                }).catch(err => {
                    this.$toaster.error(err);
                })
            },
            getSummary(){
                axios.post('/getsummarybyuserid',{
                     user_id: this.user_id,

                }).then(resp => {
                    if(resp.data.code === 200){
                        this.summary = resp.data.data;                        
                    } else {
                        this.$toaster.error(resp.data.message);
                        this.summary={
                            downline_topup_count:0,
                            total_register:0,
                            total_confirm_withdraw:0,
                            total_dex_wallet:0,
                            total_purchase_wallet:0,
                            frm_date: $('#frm_date').val(),
                            to_date: $('#to_date').val(),
                        };
                    }

                }).catch(err => {
                    this.$toaster.error(err);
                })
            },
            
        }
    }
</script>