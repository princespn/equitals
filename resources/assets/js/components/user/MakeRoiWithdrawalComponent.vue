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
	                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-1261">
	                    <div class="panel panel-body ">
	                        <div class="panel-heading">
	                            <h3 class="text-center">Make ROI Withdrawal</h3>
	                        </div>
	                        <div class="panel-body bg-gray">
	                            <form>
	                                <div class="panel-body">
	                                    <div class="row">
	                                        <div class="col-sm-6">
	                                            <div class="form-group">
	                                                <label class="control-label">ROI Income</label>
	                                                <br>
	                                                <b class="text-dark"> ${{roiwithdrawal.roi_income}} </b>
	                                            </div>
	                                        </div>
	                                        <div class="col-sm-6">
	                                            <div class="form-group">
	                                                <label class="control-label">ROI Withdrawal</label>
	                                                <br>
	                                                <b class="text-dark"> ${{roiwithdrawal.roi_income_withdraw}} </b>
	                                            </div>
	                                        </div>
	                                        <hr/>
	                                        <div class="col-sm-6">
	                                            <div class="form-group">
	                                                <label class="control-label">ROI Balance</label>
	                                                <br>
	                                                <b class="text-dark"> ${{roiwithdrawal.roi_income_balance}} </b>
	                                            </div>
	                                        </div>
	                                        <div class="col-sm-6">
	                                            <div class="form-group">
	                                                <label class="control-label">Withdraw Mode</label>
	                                                <br>
	                                                <b class="text-dark">BITCOIN</b>
	                                            </div>
	                                        </div>
	                                        <hr/>

	                                        <div class="col-sm-6">
	                                            <div class="form-group">
	                                                <label class="control-label">Withdraw BITCOIN Address</label>
	                                                <br>
	                                                <b class="text-dark"> {{roiwithdrawal.btc_address}} </b>
	                                            </div>
	                                        </div>

	                                        <div class="col-sm-6">
	                                            <div class="form-group">
	                                                <label class="control-label">Enter Amount</label>
	                                                <input type="number" min="1" step="10" id="roi-balance" name="roi-balance" v-model="roiwithdrawal.roi_balance"  class="form-control W-a-xs { error: errors.has('roi-balance') }" formcontrolname="roi-balance" placeholder="Enter Amount" v-validate="'required|numeric'" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57">
                                                    <div class="tooltip2" v-show="errors.has('roi-balance')">
                                                        <div class="tooltip-inner">
                                                           <span v-show="errors.has('roi-balance')">{{ errors.first('roi-balance') }}</span>
                                                        </div>
                                                    </div>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                                <div class="panel-footer bg-gray text-center">
	                                    <button class="btn btn-primary" type="button" @click="updateRoiWithdrawal()" :disabled='!isCompleteRoiWithdrawal'>ROI Withdrawal</button>
	                                </div>
	                            </form>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <!--===================================================-->
	            <!--End page content-->
	        </div>
	    </div>
	    <!--===================================================-->
	    <!--END CONTENT CONTAINER-->
	</div>
</template>

<script>
	import moment from 'moment';
	import { apiUserHost } from'../../user-config/config';
	import Breadcrum from './BreadcrumComponent.vue';
	import Swal from 'sweetalert2';
    export default {  
        components: {
            Breadcrum
        },
        data(){
        	return{
        		roiwithdrawal: {
        			roi_income:'',
        			roi_income_withdraw: '',
        			roi_income_balance: '',
        			btc_address: '',
        		},
        	}
        },
        computed: {
        	isCompleteRoiWithdrawal () {
        		return this.roiwithdrawal.roi_balance;
        	}
        },
        mounted(){
        	this.getRoiWithdrawal();
        },
        methods:{
            getRoiWithdrawal(){ 
                axios.get('get-user-dashboard', {
                })
                .then(response => {
                    this.roiwithdrawal = response.data.data;
                })
                .catch(error => {
                }); 
            },

            updateRoiWithdrawal() {
            	Swal({
                    title: 'Are you sure?',
                    text: `You want to update this user`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {    
		                axios.post('withdraw-income', { 
		                	level_income_balance:0,
		                	direct_income_balance:0,
		                	binary_income_balance:0,
		                	topup_wallet:0,
		                	transfer_wallet:0,
		                	working_wallet:0,
		                    roi_balance: this.roiwithdrawal.roi_balance,
		                })
		                .then(response => {
		                    if(response.data.code == 200){
		                        this.$toaster.success(response.data.message);
		                        this.getRoiWithdrawal();     
		                    } else {
		                       this.$toaster.error(response.data.message);
		                    }
		                })
		            }
                });
            }            
        }
    }
</script>