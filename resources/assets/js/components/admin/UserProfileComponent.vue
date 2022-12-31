<template>
	<div class="content">

	  <div class="">
	    <div class="page-header-title">
	      <h4 class="page-title"> View Profile </h4>
	    </div>
	  </div>

	  <div class="page-content-wrapper ">
	    <div class="container">
	      <div class="row">
	        <div class="col-md-12 col-sm-3">
	          <div class="panel panel-primary">
	            <div class="panel-body add-new">
	              <a class="btn btn-primary waves-effect waves-light pull-right" href="p4p4SGFkLUKjRNwrg82rJkzT6rs7XqX#/user/manage-user-account">
	                <i class="fa fa-mail-reply"></i> &nbsp;Back
	              </a>
	            </div>
	          </div>
	        </div>
	      </div>
	      <div class="row">
	        <div class="col-sm-6">
	          <div class="panel panel-color panel-primary">
	            <div class="panel-heading">
	              <h3 class="panel-title">Personal Details {{userProfileData.user_id}}</h3>
	            </div>
	            <div class="panel-body panel-border">
	              <div class="table-responsive">
	                <table class="table table-striped table-bordered">
	                  <tbody>
	                    <tr>
	                      <td>
	                        <strong>Date Of Joining </strong>
	                      </td>
	                      <td>{{userProfileData.entry_time | moment("DD-MM-YYYY")}}</td>
	                    </tr>
	                    <tr>
	                      <td>
	                        <strong>User Id</strong>
	                      </td>
	                      <td>{{userProfileData.user_id}}</td>
	                    </tr>
	                    <tr>
	                      <td>
	                        <strong>Sponsor Id</strong>
	                      </td>
	                      <td>{{userProfileData.sponsor_id}}</td>
	                    </tr>
	                    <tr>
	                      <td>
	                        <strong>Full Name</strong>
	                      </td>
	                      <td>{{userProfileData.fullname}}</td>
	                    </tr>
	                    <tr>
	                      <td>
	                        <strong>Position</strong>
	                      </td>
	                      <td>{{userProfileData.position}}</td>
	                    </tr>
	                    <tr>
	                      <td>
	                        <strong>Mobile</strong>
	                      </td>
	                      <td>{{userProfileData.mobile}}</td>
	                    </tr>
	                   <!--  <tr>
	                      <td>
	                        <strong>Nominee Name</strong>
	                      </td>
	                      <td>{{userProfileData.nominee_name}}</td>
	                    </tr>
	                    <tr>
	                      <td>
	                        <strong> Nominee Relation</strong>
	                      </td>
	                      <td>{{userProfileData.relation}}</td>
	                    </tr> -->
	                    <tr>
	                      <td>
	                        <strong> Email</strong>
	                      </td>
	                      <td>{{userProfileData.email}}</td>
	                    </tr>
	                    <!-- <tr>
	                      <td>
	                        <strong> BTC Address</strong>
	                      </td>
	                      <td>{{userProfileData.btc_address}}</td>
	                    </tr> -->
	                   <!--  <tr>
	                      <td>
	                        <strong> City</strong>
	                      </td>
	                      <td>{{userProfileData.city}}</td>
	                    </tr>
	                    <tr>
	                      <td>
	                        <strong> State</strong>
	                      </td>
	                      <td>{{userProfileData.state}}</td>
	                    </tr>
	                    <tr>
	                      <td>
	                        <strong> Country</strong>
	                      </td>
	                      <td>{{userProfileData.country}}</td>
	                    </tr> -->
	                    <!-- <tr>
	                      <td>
	                        <strong> Insurance Amount</strong>
	                      </td>
	                      <td>{{userProfileData.insurance_amt}}</td>
	                    </tr> -->
	                  </tbody>
	                </table>
	              </div>
	            </div>
	          </div>

	          <!-- panel -->
	        </div>

	        <div class="col-sm-6">


	          <div class="panel panel-color panel-dark">
	            <div class="panel-heading">
	              <h3 class="panel-title">BTC Information</h3>
	            </div>
	            <div class="panel-body panel-border">
	              <div class="table-responsive">
	                <table class="table table-striped table-bordered">
	                  <tbody>
	                  	 <tr>
	                      <td>
	                        <strong> BTC Address</strong>
	                      </td>
	                      <td>{{userProfileData.btc_address}}</td>
	                    </tr>
	                    	 <tr>
	                      <td>
	                        <strong> TRX Address</strong>
	                      </td>
	                      <td>{{userProfileData.trx_address}}</td>
	                    </tr>
	                    	 <!-- <tr>
	                      <td>
	                        <strong> ETH Address</strong>
	                      </td>
	                      <td>{{userProfileData.ethereum}}</td>
	                    </tr> -->
	                    <tr>
	                      <td>
	                        <strong>BNB.BSC Address</strong>
	                      </td>
	                      <td>{{userProfileData.bnb_bsc_address}}</td>
	                    </tr>
	                    <tr>
	                      <td>
	                        <strong> USDT-TRC20 Address</strong>
	                      </td>
	                      <td>{{userProfileData.usdt_trc20_address}}</td>
	                    </tr>
	                    <tr>
	                      <td>
	                        <strong> Litecoin Address</strong>
	                      </td>
	                      <td>{{userProfileData.ltc_address}}</td>
	                    </tr>
	                    <tr>
	                      <td>
	                        <strong> Dogecoin Address</strong>
	                      </td>
	                      <td>{{userProfileData.doge_address}}</td>
	                    </tr>
	                    	 <!-- <tr>
	                      <td>
	                        <strong> BNB Address</strong>
	                      </td>
	                      <td>{{userProfileData.bnb_address}}</td>
	                    </tr> -->
	                   <!--  <tr>
	                      <td>
	                        <strong>Bank Account Number</strong>
	                      </td>
	                      <td>{{userProfileData.account_no}}</td>
	                    </tr>
	                    <tr>
	                      <td>
	                        <strong>A/C Name</strong>
	                      </td>
	                      <td>{{userProfileData.holder_name}}</td>
	                    </tr>
	                    <tr>
	                      <td>
	                        <strong>Bank Name</strong>
	                      </td>
	                      <td>{{userProfileData.bank_name}}</td>
	                    </tr>
	                    <tr>
	                      <td>
	                        <strong>Bank Branch Name</strong>
	                      </td>
	                      <td>{{userProfileData.branch_name}}</td>
	                    </tr>
	                    <tr>
	                      <td>
	                        <strong>Bank IFSC Code</strong>
	                      </td>
	                      <td>{{userProfileData.ifsc_code}}</td>
	                    </tr>
	                    <tr>
	                      <td>
	                        <strong>PAN Number</strong>
	                      </td>
	                      <td>{{userProfileData.pan_no}}</td>
	                    </tr> -->
	                   <!--  <tr>
	                      <td>
	                        <strong>Paytm Number</strong>
	                      </td>
	                      <td>{{userProfileData.paytm_no}}</td>
	                    </tr>
	                    <tr>
	                      <td>
	                        <strong>Tez Number</strong>
	                      </td>
	                      <td>{{userProfileData.payzapp_no}}</td>
	                    </tr> -->
	                    <!-- <tr>
	                      <td>
	                        <strong>Account Type</strong>
	                      </td>
	                      <td>{{userProfileData.account_type}}</td>
	                    </tr> -->
	                  </tbody>
	                </table>
	              </div>
	            </div>
	          </div>

	          <!-- panel -->
	        </div>
	        <!-- col -->
	      </div>
	      <!-- End row -->

	      <!-- Inline Form -->

	    </div>
	    <!-- container -->

	  </div>
	  <!-- Page content Wrapper -->

	</div>
	<!-- content -->
</template>

<script>
    import { apiAdminHost } from'./../../admin-config/config';
    import moment  from 'moment';

    export default {
    	
        data() {
            return {
                userProfileData: {
                    id: '',
                    user_id: '',
                    sponsor_id: '',
                    fullname: '',
                    dob: '',
                    nominee_name: '',
                    relation: '',
                    email: '',
                    country: '',
                    contact_number: '',
                    state: '',
                    city: '',
                    account_no: '',
                    holder_name: '',
                    bank_name: '',
                    branch_name: '',
                    pan_no: '',
                    ifsc_code: '',
                    paytm_no: '',
                    payzapp_no: '',
                    insurance_amt: '',
                    mobile:'',
                    entry_time:'',
                    btc_address:'',
                    ethereum:'',
                    bnb_address:'',
                    trn_address:'',
					bnb_bsc_address: '',
					trx_address: '',
					usdt_trc20_address: '',
					ltc_address: '',
					doge_address: '',
                }
            }
        },
        mounted() {
            this.getUserDetails();
        },
        methods: {
            getUserDetails(){
                axios.post('/viewprofile',{
                    id: this.$route.params.id,
                }).then(resp => {
                    if(resp.data.code === 200){
                        this.userProfileData = {
                            id: resp.data.data.id,
                            user_id: resp.data.data.user_id,
                            sponsor_id: resp.data.data.ref_user_id,
                            fullname: resp.data.data.fullname,
                            dob: resp.data.data.dob,
                            mobile: resp.data.data.mobile,
                            nominee_name: resp.data.data.nominee_name,
                            relation: resp.data.data.relation,
                            email: resp.data.data.email,
                            country: resp.data.data.country,
                            contact_number: resp.data.data.contact_number,
                            state: resp.data.data.state,
                            city: resp.data.data.city,
                            account_no: resp.data.data.account_no,
                            holder_name: resp.data.data.holder_name,
                            bank_name: resp.data.data.bank_name,
                            branch_name: resp.data.data.branch_name,
                            pan_no: resp.data.data.pan_no,
                            ifsc_code: resp.data.data.ifsc_code,
                            entry_time: resp.data.data.entry_time,
                            position: resp.data.data.position,
                            ethereum: resp.data.data.ethereum,
                            btc_address: resp.data.data.btc_address,
                            bnb_address: resp.data.data.bnb_address,
                            bnb_bsc_address: resp.data.data.bnb_bsc_address,
                            trx_address: resp.data.data.trx_address,
                            usdt_trc20_address: resp.data.data.usdt_trc20_address,
                            ltc_address: resp.data.data.ltc_address,
                            doge_address: resp.data.data.doge_address,
                        }
                    } else {
                        this.$toaster.error(resp.data.message);
                    }
                }).catch(err => {
                    this.$toaster.error(err);
                })
            }
        }
    }
</script>