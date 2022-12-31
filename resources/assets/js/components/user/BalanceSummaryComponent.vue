<template>
	<div>
		<!--CONTENT CONTAINER-->
	    <!--===================================================-->
	    <div id="content-container">
	        <Breadcrum></Breadcrum>
	        <!--Page content-->
	        <!--===================================================-->
	        <div id="page-content">

	            <hr class="new-section-sm bord-no">
	            <div class="row">
	                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
	                    <div class="panel panel-body text-center">
	                        <div class="panel-heading">
	                            <h3>Balance Summary</h3>
	                        </div>
	                        <div class="panel-body">
	                           <div class="row">
	                                <div class="col-md-4">
	                                    <div class="panel panel-info panel-colorful media middle pad-all">
	                                        <div class="media-left">
	                                            <div class="pad-hor">
	                                                <i class="demo-psi-coin icon-3x"></i>
	                                            </div>
	                                        </div>
	                                        <div class="media-body">
	                                            <p class="text-3x mar-no text-semibold textflow"> ${{userdetails.total_withdraw}} </p>
	                                            <p class="mar-no">Total Deposit</p>
	                                        </div>
	                                    </div>
	                                </div>


	                                <div class="col-md-4">
	                                    <div class="panel panel-mint panel-colorful media middle pad-all">
	                                        <div class="media-left">
	                                            <div class="pad-hor">
	                                                <i class="demo-pli-basket-coins icon-3x"></i>
	                                            </div>
	                                        </div>
	                                        <div class="media-body">
	                                            <p class="text-3x mar-no text-semibold textflow"> ${{userdetails.total_investment}} </p>
	                                            <p class="mar-no">Total Withdrawal</p>
	                                        </div>
	                                    </div>
	                                </div>


	                                <div class="col-md-4">
	                                    <div class="panel panel-danger panel-colorful media middle pad-all">
	                                        <div class="media-left">
	                                            <div class="pad-hor">
	                                                <i class="demo-pli-file-word icon-3x"></i>
	                                            </div>
	                                        </div>
	                                        <div class="media-body">
	                                            <p class="text-3x mar-no text-semibold textflow" > ${{userdetails.usd}} </p>
	                                            <p class="mar-no">Account Balance</p>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>

	                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
	                    <div class="panel panel-body text-center bg-dark panel-p-equal-p">

	                        <div class="panel-body panel-body-p-equal">

	                        	 <div class="row">
	                                <div class="col-md-4">
	                                    <div class="panel media middle pad-all">
	                                        <div class="media-left">
	                                            <span class="icon-wrap icon-wrap-sm icon-circle bg-mint">
	                                                <i class="ion-arrow-graph-up-left icon-2x"></i>
	                                            </span>
	                                        </div>
	                                        <div class="media-body">
	                                            <p class="text-2x mar-no text-semibold text-main"> ${{userdetails.direct_income}} </p>
	                                            <p class="text-dark mar-no">Direct Income Generated</p>
	                                        </div>
	                                    </div>
	                                </div>


	                                <div class="col-md-4">
	                                    <div class="panel media middle pad-all">
	                                        <div class="media-left">
	                                            <span class="icon-wrap icon-wrap-sm icon-circle bg-primary">
	                                                <i class="ion-arrow-graph-up-right icon-2x"></i>
	                                            </span>
	                                        </div>
	                                        <div class="media-body">
	                                            <p class="text-2x mar-no text-semibold text-main"> ${{userdetails.direct_income_withdraw}} </p>
	                                            <p class="text-dark mar-no">Direct Income Withdraw</p>
	                                        </div>
	                                    </div>
	                                </div>


	                                <div class="col-md-4">
	                                    <div class="panel media middle pad-all">
	                                        <div class="media-left">
	                                            <span class="icon-wrap icon-wrap-sm icon-circle bg-warning">
	                                                <i class="ion-code-download icon-2x"></i>
	                                            </span>
	                                        </div>
	                                        <div class="media-body">
	                                            <p class="text-2x mar-no text-semibold text-main"> ${{userdetails.direct_income_balance}} </p>
	                                            <p class="text-dark mar-no">Direct Income Balance</p>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>


	                            <div class="row">
	                                <div class="col-md-4">
	                                    <div class="panel media middle pad-all">
	                                        <div class="media-left">
	                                            <span class="icon-wrap icon-wrap-sm icon-circle bg-success">
	                                                <i class="ion-arrow-graph-up-left icon-2x"></i>
	                                            </span>
	                                        </div>
	                                        <div class="media-body">
	                                            <p class="text-2x mar-no text-semibold text-main"> ${{userdetails.level_income}} </p>
	                                            <p class="text-dark mar-no">Level Income Generated</p>
	                                        </div>
	                                    </div>
	                                </div>

	                                <div class="col-md-4">
	                                    <div class="panel media middle pad-all">
	                                        <div class="media-left">
	                                            <span class="icon-wrap icon-wrap-sm icon-circle bg-danger">
	                                                <i class="ion-arrow-graph-up-right icon-2x"></i>
	                                            </span>
	                                        </div>
	                                        <div class="media-body">
	                                            <p class="text-2x mar-no text-semibold text-main"> ${{userdetails.level_income_withdraw}} </p>
	                                            <p class="text-dark mar-no">Level Income Withdraw</p>
	                                        </div>
	                                    </div>
	                                </div>

	                                <div class="col-md-4">
	                                    <div class="panel media middle pad-all">
	                                        <div class="media-left">
	                                            <span class="icon-wrap icon-wrap-sm icon-circle bg-info">
	                                                <i class="ion-code-download icon-2x"></i>
	                                            </span>
	                                        </div>
	                                        <div class="media-body">
	                                            <p class="text-2x mar-no text-semibold text-main"> ${{userdetails.level_income_balance}} </p>
	                                            <p class="text-dark mar-no">Level Income Balance</p>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>

	                            <div class="row">
	                                <div class="col-md-4">
	                                    <div class="panel media middle pad-all">
	                                        <div class="media-left">
	                                            <span class="icon-wrap icon-wrap-sm icon-circle bg-mint">
	                                                <i class="ion-arrow-graph-up-left icon-2x"></i>
	                                            </span>
	                                        </div>
	                                        <div class="media-body">
	                                            <p class="text-2x mar-no text-semibold text-main"> ${{userdetails.roi_income}} </p>
	                                            <p class="text-dark mar-no">ROI Income Generated</p>
	                                        </div>
	                                    </div>
	                                </div>


	                                <div class="col-md-4">
	                                    <div class="panel media middle pad-all">
	                                        <div class="media-left">
	                                            <span class="icon-wrap icon-wrap-sm icon-circle bg-primary">
	                                                <i class="ion-arrow-graph-up-right icon-2x"></i>
	                                            </span>
	                                        </div>
	                                        <div class="media-body">
	                                            <p class="text-2x mar-no text-semibold text-main"> ${{userdetails.roi_income_withdraw}} </p>
	                                            <p class="text-dark mar-no">ROI Income Withdraw</p>
	                                        </div>
	                                    </div>
	                                </div>


	                                <div class="col-md-4">
	                                    <div class="panel media middle pad-all">
	                                        <div class="media-left">
	                                            <span class="icon-wrap icon-wrap-sm icon-circle bg-warning">
	                                                <i class="ion-code-download icon-2x"></i>
	                                            </span>
	                                        </div>
	                                        <div class="media-body">
	                                            <p class="text-2x mar-no text-semibold text-main"> ${{userdetails.roi_income_balance}} </p>
	                                            <p class="text-dark mar-no">ROI Income Balance</p>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>

	                            <div class="row">
	                                <div class="col-md-4">
	                                    <div class="panel media middle pad-all">
	                                        <div class="media-left">
	                                            <span class="icon-wrap icon-wrap-sm icon-circle bg-success">
	                                                <i class="ion-arrow-graph-up-left icon-2x"></i>
	                                            </span>
	                                        </div>
	                                        <div class="media-body">
	                                            <p class="text-2x mar-no text-semibold text-main"> ${{userdetails.level_income_roi}} </p>
	                                            <p class="text-dark mar-no">Team ROI Income Generated</p>
	                                        </div>
	                                    </div>
	                                </div>


	                                <div class="col-md-4">
	                                    <div class="panel media middle pad-all">
	                                        <div class="media-left">
	                                            <span class="icon-wrap icon-wrap-sm icon-circle bg-danger">
	                                                <i class="ion-arrow-graph-up-right icon-2x"></i>
	                                            </span>
	                                        </div>
	                                        <div class="media-body">
	                                            <p class="text-2x mar-no text-semibold text-main"> ${{userdetails.leadership_income_withdraw}} </p>
	                                            <p class="text-dark mar-no">Team ROI Income Withdraw</p>
	                                        </div>
	                                    </div>
	                                </div>


	                                <div class="col-md-4">
	                                    <div class="panel media middle pad-all">
	                                        <div class="media-left">
	                                            <span class="icon-wrap icon-wrap-sm icon-circle bg-info">
	                                                <i class="ion-code-download icon-2x"></i>
	                                            </span>
	                                        </div>
	                                        <div class="media-body">
	                                            <p class="text-2x mar-no text-semibold text-main"> ${{userdetails.level_income_roi_balance}} </p>
	                                            <p class="text-dark mar-no">Team ROI Income Balance</p>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>

	                          <!--   <div class="row">
	                                <div class="col-md-4">
	                                    <div class="panel media middle pad-all">
	                                        <div class="media-left">
	                                            <span class="icon-wrap icon-wrap-sm icon-circle bg-danger">
	                                                <i class="ion-arrow-graph-up-left icon-2x"></i>
	                                            </span>
	                                        </div>
	                                        <div class="media-body">
	                                            <p class="text-2x mar-no text-semibold text-main"> ${{userdetails.upline_income}} </p>
	                                            <p class="text-dark mar-no">Upline Income Generated</p>
	                                        </div>
	                                    </div>
	                                </div>


	                                <div class="col-md-4">
	                                    <div class="panel media middle pad-all">
	                                        <div class="media-left">
	                                            <span class="icon-wrap icon-wrap-sm icon-circle bg-info">
	                                                <i class="ion-arrow-graph-up-right icon-2x"></i>
	                                            </span>
	                                        </div>
	                                        <div class="media-body">
	                                            <p class="text-2x mar-no text-semibold text-main"> ${{userdetails.upline_income_withdraw}} </p>
	                                            <p class="text-dark mar-no">Upline Income Withdraw</p>
	                                        </div>
	                                    </div>
	                                </div>


	                                <div class="col-md-4">
	                                    <div class="panel media middle pad-all">
	                                        <div class="media-left">
	                                            <span class="icon-wrap icon-wrap-sm icon-circle bg-success">
	                                                <i class="ion-code-download icon-2x"></i>
	                                            </span>
	                                        </div>
	                                        <div class="media-body">
	                                            <p class="text-2x mar-no text-semibold text-main"> ${{userdetails.upline_balance}} </p>
	                                            <p class="text-dark mar-no">Upline Income Balance</p>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div> -->

	                            <div class="row">
	                                <div class="col-md-4">
	                                    <div class="panel media middle pad-all">
	                                        <div class="media-left">
	                                            <span class="icon-wrap icon-wrap-sm icon-circle bg-success">
	                                                <i class="ion-arrow-graph-up-left icon-2x"></i>
	                                            </span>
	                                        </div>
	                                        <div class="media-body">
	                                            <p class="text-2x mar-no text-semibold text-main"> ${{userdetails.working_wallet}} </p>
	                                            <p class="text-dark mar-no">Working Wallet</p>
	                                        </div>
	                                    </div>
	                                </div>


	                                <div class="col-md-4">
	                                    <div class="panel media middle pad-all">
	                                        <div class="media-left">
	                                            <span class="icon-wrap icon-wrap-sm icon-circle bg-danger">
	                                                <i class="ion-arrow-graph-up-right icon-2x"></i>
	                                            </span>
	                                        </div>
	                                        <div class="media-body">
	                                            <p class="text-2x mar-no text-semibold text-main"> ${{userdetails.working_wallet_withdraw}} </p>
	                                            <p class="text-dark mar-no">Working Wallet Withdraw</p>
	                                        </div>
	                                    </div>
	                                </div>


	                                <div class="col-md-4">
	                                    <div class="panel media middle pad-all">
	                                        <div class="media-left">
	                                            <span class="icon-wrap icon-wrap-sm icon-circle bg-info">
	                                                <i class="ion-code-download icon-2x"></i>
	                                            </span>
	                                        </div>
	                                        <div class="media-body">
	                                            <p class="text-2x mar-no text-semibold text-main"> ${{userdetails.working_Wallet_balance}} </p>
	                                            <p class="text-dark mar-no">Working Wallet Balance</p>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <!--===================================================-->
	        <!--End page content-->

	    </div>
	    <!--===================================================-->
	    <!--END CONTENT CONTAINER-->
	</div>
</template>

<script>
	import moment from 'moment';
	import { apiUserHost } from'../../user-config/config';
	import Breadcrum from './BreadcrumComponent.vue';
    export default {  
        components: {
            Breadcrum
        },
        data(){
        	return{
        		userdetails: {
        			total_withdraw:'',
        			total_investment:'',
        			usd:'',        			
        			level_income:'',
        			level_income_withdraw: '',
        			level_income_balance: '',
        			roi_income:'',
        			roi_income_withdraw:'',
        			roi_income_balance:'',
        			top_up_wallet:'',
        			top_up_wallet_withdraw:'',
        			top_up_Wallet_balance:'',
        			working_wallet:'',
        			working_wallet_withdraw:'',
        			working_Wallet_balance:'',        			
        		},
        	}
        },
        mounted(){
        	this.getUserDetails();
        },
        methods:{

            getUserDetails(){ 
                axios.get('get-user-dashboard', {
                })
                .then(response => {
                    this.userdetails = response.data.data;
                })
                .catch(error => {
                }); 
            },         
        }
    }
</script>