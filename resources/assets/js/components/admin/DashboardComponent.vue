<template>
    <!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Dashboard</h4>
            </div>
        </div>

        <div class="page-content-wrapper ">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <div class="">
                                  <!--   <form id="searchForm"> -->
                                      <!--   <div class="row">                    
                                            <div class="col-md-3"></div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>From Date</label>
                                                    <div>
                                                        <div class="input-group">
                                                            <DatePicker :bootstrap-styling="true" v-model="frm_date" name="frm_date" :format="dateFormat" placeholder="From Date" id="frm_date"></DatePicker>
                                                            <span class="input-group-addon bg-custom b-0 datepicker_border">
                                                                <i class="mdi mdi-calendar"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>To Date</label>
                                                    <div>
                                                        <div class="input-group">
                                                            <DatePicker :bootstrap-styling="true" v-model="to_date" name="to_date" :format="dateFormat" placeholder="To Date" id="to_date"></DatePicker>
                                                            <span class="input-group-addon bg-custom b-0 datepicker_border">
                                                                <i class="mdi mdi-calendar"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>User Id</label>
                                                    <input type="text" id="user_id" name="user_id" placeholder="Enter User Id" v-model="user_id" class="form-control" v-on:keyup="checkuserexist">
                                                    <span id="checkUser" v-bind:class="custom_msg_class">{{userExistsMessage}}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 text-right" style="padding-top: 20px;">
                                                <button type="button" @click="getDashboardData" class="btn btn-primary waves-effect waves-light">Search</button>
                                               
                                                <button class="btn btn-dark waves-effect waves-light mt-4 m-t-4 mtop-4" id="onResetClick" @click="onResetClick" type="button">Reset</button>
                                            </div>
                                        </div> -->
                                         <!-- <button type="button" class="btn btn-info waves-effect waves-light" (click)="exportToExcel($event)">Export To Excel</button>  -->
                                         
                                        <!-- <div class="row">
                                        <div class="text-center">
                                          <button type="submit" class="btn btn-primary waves-effect waves-light" (click)="onSearchClick()">Search</button>
                                          <button type="button" class="btn btn-info waves-effect waves-light" (click)="exportToExcel($event)">Export To Excel</button>
                                          <button type="button" class="btn btn-dark waves-effect waves-light mt-4" (click)="onResetClick()">Reset</button>
                                        </div>
                                      </div> -->
                                   <!--  </form> -->
                                </div>
                            </div>
                            <!-- panel-body -->
                        </div>
                        <!-- panel -->
                    </div>
                    <!-- col -->
                </div>
                <div class="row" v-if="dashboard.isAssignDash==true">
                    <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="ti-user widget-box-icon"></i>
                                        <h4 class="panel-title text-muted m-b-15 "> Users</h4>
                                        <h2 class="m-t-0 text-primary m-b-15">
                                            <i class="mdi mdi-arrow-down-bold-circle-outline m-r-10"></i>{{dashboard.total_users}}
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="ti-user widget-box-icon"></i>
                                        <h4 class="panel-title m-b-15 text-muted"> Unblock Users</h4>
                                        <h2 class="m-t-0 text-warning m-b-15">
                                            <i class="mdi mdi-arrow-down-bold-circle-outline m-r-10"></i>{{dashboard.total_unblock_users}}
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="ti-user widget-box-icon"></i>
                                        <h4 class="panel-title m-b-15 text-muted"> Block Users</h4>
                                        <h2 class="m-t-0 text-danger m-b-15">
                                            <i class="mdi mdi-arrow-down-bold-circle-outline m-r-10"></i>{{dashboard.total_block_users}}
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="fa fa-database dash-icon"></i>
                                        <h4 class="panel-title text-muted m-b-15"> Paid Users</h4>
                                        <h2 class="m-t-0 text-info m-b-15">
                                            <i class="mdi mdi-arrow-up-bold-circle-outline m-r-10"></i>{{dashboard.total_active_user}}
                                        </h2>

                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="row"  v-if="dashboard.isAssignDash==true">

                    <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="fa fa-paperclip dash-icon"></i>
                                        <h4 class="panel-title text-muted m-b-15"> Unpaid Users</h4>
                                        <h2 class="m-t-0 text-success m-b-15">
                                            <i class="mdi mdi-arrow-up-bold-circle-outline m-r-10"></i>{{dashboard.total_inactive_user}}
                                        </h2>

                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                     <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="ti-crown widget-box-icon"></i>
                                        <h4 class="panel-title text-muted m-b-15"> Add Fund  </h4>
                                        <h2 class="m-t-0 text-warning m-b-15">
                                            <i class="mdi mdi-arrow-up-bold-circle-outline m-r-10"></i>{{Number(dashboard.add_fund_btc).toFixed(2)}}
                                        </h2>

                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <router-link :to="{ name: 'withdrawrequestreport' }">
                            <a href="javascript:;">
                                <div class="panel">
                                    <div class="panel-body p-t-10">
                                        <div class="widget-box-one">
                                            <i class="fa fa-exchange dash-icon"></i>
                                            <h4 class="panel-title m-b-15 text-muted">Upcoming Withdrawal</h4>
                                            <h2 class="m-t-0 text-success m-b-15">
                                                <i class="mdi mdi-arrow-down-bold-circle-outline m-r-10"></i>{{Number(dashboard.total_withdraw_pending).toFixed(2)}}
                                            </h2>

                                        </div>
                                    </div>
                                </div>
                            </a>
                        </router-link>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                         <router-link :to="{ name: 'account-wallet' }"> 
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="fa fa-exchange dash-icon"></i>
                                        <h4 class="panel-title m-b-15 text-muted"> Total account wallet balance</h4>
                                        <h2 class="m-t-0 text-danger m-b-15">
                                            <i class="mdi mdi-arrow-down-bold-circle-outline m-r-10"></i>{{Number(dashboard.total_account_wallet_bal).toFixed(2)}}
                                        </h2>

                                    </div>
                                </div>
                            </div>
                        </a>
                    </router-link>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="ti-crown widget-box-icon"></i>
                                        <h4 class="panel-title text-muted m-b-15"> Total purchase wallet balance </h4>
                                        <h2 class="m-t-0 text-warning m-b-15">
                                            <i class="mdi mdi-arrow-up-bold-circle-outline m-r-10"></i>{{Number(dashboard.total_purchase_wallet_bal).toFixed(2)}}
                                        </h2>

                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                     <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="mdi mdi-buffer dash-icon"></i>
                                        <h4 class="panel-title text-muted m-b-15">Total Withdrawal</h4>
                                        <h2 class="m-t-0 text-info m-b-15">
                                            <i class="mdi mdi-arrow-up-bold-circle-outline m-r-10"></i>{{dashboard.total_withdraw_confirmd_deduction}}
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>



                    <!-- <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="ti-crown widget-box-icon"></i>
                                        <h4 class="panel-title text-muted m-b-15"> Total Freedom Income </h4>
                                        <h2 class="m-t-0 text-warning m-b-15">
                                            <i class="mdi mdi-arrow-up-bold-circle-outline m-r-10"></i>{{Number(dashboard.freedom_income).toFixed(2)}}
                                        </h2>

                                    </div>
                                </div>
                            </div>
                        </a>
                    </div> -->



                    <!-- <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="ti-crown widget-box-icon"></i>
                                        <h4 class="panel-title text-muted m-b-15"> Total Super Matching Income </h4>
                                        <h2 class="m-t-0 text-warning m-b-15">
                                            <i class="mdi mdi-arrow-up-bold-circle-outline m-r-10"></i>{{Number(dashboard.supermatching_income).toFixed(2)}}
                                        </h2>

                                    </div>
                                </div>
                            </div>
                        </a>
                    </div> -->

                   

                </div>


                <div class="row"  v-if="dashboard.isAssignDash==true">
                   <!--  <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="ti-stats-up widget-box-icon"></i>
                                        <h4 class="panel-title text-muted m-b-15"> Investment Mode Wallet </h4>
                                        <h2 class="m-t-0 text-primary m-b-15">
                                            <i class="mdi mdi-arrow-up-bold-circle-outline m-r-10"></i>{{dashboard.total_deposite_wallet}}
                                        </h2>

                                    </div>
                                </div>
                            </div>
                        </a>
                    </div> -->
                    <!-- <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="fa fa-money dash-icon"></i>
                                        <h4 class="panel-title text-muted m-b-15"> Matrix Income</h4>
                                        <h2 class="m-t-0 text-success m-b-15">
                                            <i class="mdi mdi-arrow-down-bold-circle-outline m-r-10"></i>{{dashboard.total_sell}}
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div> -->
                   <!--  <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="mdi mdi-buffer dash-icon"></i>
                                        <h4 class="panel-title text-muted m-b-15"> Direct Income</h4>
                                        <h2 class="m-t-0 text-info m-b-15">
                                            <i class="mdi mdi-arrow-up-bold-circle-outline m-r-10"></i>{{dashboard.total_direct_income}}
                                        </h2>

                                    </div>
                                </div>
                            </div>
                        </a>
                    </div> -->
                   <!--   <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="mdi mdi-buffer dash-icon"></i>
                                        <h4 class="panel-title text-muted m-b-15"> Binary Income</h4>
                                        <h2 class="m-t-0 text-info m-b-15">
                                            <i class="mdi mdi-arrow-up-bold-circle-outline m-r-10"></i>{{dashboard.total_binary_income}}
                                        </h2>

                                    </div>
                                </div>
                            </div>
                        </a>
                    </div> -->
                   <!--   <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="fa fa-area-chart dash-icon"></i>
                                        <h4 class="panel-title text-muted m-b-15"> Upline Income </h4>
                                        <h2 class="m-t-0 text-danger m-b-15">
                                            <i class="mdi mdi-arrow-up-bold-circle-outline m-r-10"></i>{{dashboard.total_upline_income}}
                                        </h2>

                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>  -->
                   <!--  <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="ti-cup widget-box-icon"></i>
                                        <h4 class="panel-title text-muted m-b-15"> Level Income </h4>
                                        <h2 class="m-t-0 text-warning m-b-15">
                                            <i class="mdi mdi-arrow-down-bold-circle-outline m-r-10"></i>{{dashboard.total_level_income}}
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div> -->
                     
                    <!-- <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="ti-stats-up widget-box-icon"></i>
                                        <h4 class="panel-title text-muted m-b-15"> ROI Income </h4>
                                        <h2 class="m-t-0 text-primary m-b-15">
                                            <i class="mdi mdi-arrow-up-bold-circle-outline m-r-10"></i>{{dashboard.total_roi_income}}
                                        </h2>

                                    </div>
                                </div>
                            </div>
                        </a>
                    </div> -->
                   <!--  <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="fa fa-area-chart dash-icon"></i>
                                        <h4 class="panel-title text-muted m-b-15">Direct Income </h4>
                                        <h2 class="m-t-0 text-danger m-b-15">
                                            <i class="mdi mdi-arrow-up-bold-circle-outline m-r-10"></i>{{dashboard.total_direct_income}}
                                        </h2>

                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>   -->
                     <!--  <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="fa fa-area-chart dash-icon"></i>
                                        <h4 class="panel-title text-muted m-b-15"> Team ROI Income </h4>
                                        <h2 class="m-t-0 text-danger m-b-15">
                                            <i class="mdi mdi-arrow-up-bold-circle-outline m-r-10"></i>{{dashboard.total_level_roi_income}}
                                        </h2>

                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                   <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="ti-stats-up widget-box-icon"></i>
                                        <h4 class="panel-title text-muted m-b-15">Total Promotional Income </h4>
                                        <h2 class="m-t-0 text-primary m-b-15">
                                            <i class="mdi mdi-arrow-up-bold-circle-outline m-r-10"></i>{{dashboard.total_promotional_income}}
                                        </h2>

                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="ti-stats-up widget-box-icon"></i>
                                        <h4 class="panel-title text-muted m-b-15">Total Award </h4>
                                        <h2 class="m-t-0 text-primary m-b-15">
                                            <i class="mdi mdi-arrow-up-bold-circle-outline m-r-10"></i>{{dashboard.total_award}}
                                        </h2>

                                    </div>
                                </div>
                            </div>
                        </a>
                    </div> -->

                     <!--   <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="mdi mdi-buffer dash-icon"></i>
                                        <h4 class="panel-title text-muted m-b-15"> Total Income</h4>
                                        <h2 class="m-t-0 text-info m-b-15">
                                            <i class="mdi mdi-arrow-up-bold-circle-outline m-r-10"></i>{{dashboard.total_income}}
                                        </h2>

                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
 -->

                  
                   <!--  <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="fa fa-area-chart dash-icon"></i>
                                        <h4 class="panel-title text-muted m-b-15"> Pending Withdrawal</h4>
                                        <h2 class="m-t-0 text-danger m-b-15">
                                            <i class="mdi mdi-arrow-up-bold-circle-outline m-r-10"></i>{{dashboard.total_withdraw_pending}}
                                        </h2>

                                    </div>
                                </div>
                            </div>
                        </a>
                    </div> -->
                   <!--  <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="ti-cup widget-box-icon"></i>
                                        <h4 class="panel-title text-muted m-b-15"> Confirm Withdrawal</h4>
                                        <h2 class="m-t-0 text-warning m-b-15">
                                            <i class="mdi mdi-arrow-down-bold-circle-outline m-r-10"></i>{{dashboard.total_withdraw_confirm}}
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div> -->
                   <!--  <div class="col-sm-6 col-lg-3">
                        <a href="javascript:;">
                            <div class="panel">
                                <div class="panel-body p-t-10">
                                    <div class="widget-box-one">
                                        <i class="ti-stats-up widget-box-icon"></i>
                                        <h4 class="panel-title text-muted m-b-15">Total Profit(Investment - Withdrawal) </h4>
                                        <h2 class="m-t-0 text-primary m-b-15">
                                            <i class="mdi mdi-arrow-up-bold-circle-outline m-r-10"></i>{{dashboard.total_balance}}
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div> -->
                </div>

              
                    
                    
                    
                             
                <!-- end row -->                
            </div>
            <!-- container -->
        </div>
        <!-- Page content Wrapper -->
    </div>
    <!-- content -->
</template>
<script>
    
    import moment from 'moment';
    import DatePicker from 'vuejs-datepicker';

    export default {
        data() {
            return {
                dashboard : {
                    total_users : 0,
                    total_unblock_users : 0,
                    total_block_users : 0,
                    total_active_user : 0,
                    total_inactive_user : 0,
                    total_investment : 0,
                    total_account_wallet_bal : 0,
                    total_purchase_wallet_bal : 0,
                    total_withdraw_confirmd_deduction : 0,
                    total_deposite_admin : 0,
                    total_deposite_btc : 0,
                    total_deposite_wallet : 0,
                    total_income : 0,
                    total_direct_income : 0,
                    total_binary_income : 0,
                    total_level_income : 0,
                    total_roi_income : 0,
                    total_withdraw : 0,
                    total_withdraw_pending : 0,
                    total_withdraw_confirm : 0,
                    total_balance : 0,
                    freedom_income : 0,
                    supermatching_income : 0,
                    total_dex_wallet_bal:0
                },
                hiddenUserId : '',
                user_id : '',
                frm_date : '',
                to_date : '',
                userExistsMessage : '',
                custom_msg_class : ''
            }
        },
        components: {
            DatePicker
        },
        mounted() {

            this.getDashboardData();
            //this.fa();
        },
        methods: {
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            getDashboardData() { 

                axios.post('getDashboardSummary', {
                    frm_date: (this.frm_date!=='')?moment(this.frm_date).format('DD-MM-YYYY'):'',
                    to_date: (this.to_date!=='')?moment(this.to_date).format('DD-MM-YYYY'):'',
                    id : this.hiddenUserId,
                    user_id: this.user_id,
                }).then(response => {
                    this.dashboard = response.data.data;
                }).catch(error => {
                    this.message  = error.response.data.message;
                    this.flash(this.message, 'error', {
                      timeout: 500000,
                    });
                });
            },
            fa() { 

                axios.post('2fa/enable', {
                    frm_date: (this.frm_date!=='')?moment(this.frm_date).format('DD-MM-YYYY'):'',
                    to_date: (this.to_date!=='')?moment(this.to_date).format('DD-MM-YYYY'):'',
                    id : this.hiddenUserId,
                    user_id: this.user_id,
                }).then(response => {
                    this.dashboard = response.data.data;
                }).catch(error => {
                    this.message  = error.response.data.message;
                    this.flash(this.message, 'error', {
                      timeout: 500000,
                    });
                });
            },


            onResetClick() { 
                $('#searchForm').trigger("reset");
            },
            
            checkuserexist() {
                axios.post('checkuserexist',{
                   user_id:this.user_id,
                 }).then(response => {                     
                    if(response.data.code == 404) {
                        // console.log(response.data.message);                     
                        this.userExistsMessage = response.data.message;
                        this.custom_msg_class = 'text-danger';                        
                    }else{
                        //console.log(response.data.data.id);
                        this.hiddenUserId = response.data.data.id;
                        this.userExistsMessage = response.data.message;
                        this.custom_msg_class = 'text-success';
                    }
                }).catch(error => {
                    //console.log(error);
                    this.message  = '';
                    this.flash(this.message, 'error', {
                      timeout: 500000,
                    });
                });
            }
        }
    }
    
</script>