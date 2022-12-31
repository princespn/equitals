<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Withdraw Request Report</h4>
		    </div>
		</div>

		<div class="page-content-wrapper">
		    <div class="container">
		        <form id="searchForm">
		            <div class="row">
		                <div class="col-md-12">
		                    <div class="panel panel-primary">
		                        <div class="panel-body">
		                            <div class="">
		                                <div class="col-md-3"></div>
		                                 <div class="col-md-2">
		                                     <div class="form-group">
                                            <label>From Date</label>
                                            <div>
                                                <div class="input-group">
                                                	 <DatePicker :bootstrap-styling="true" name="frm_date" :format="dateFormat" placeholder="From Date" id="frm_date"></DatePicker>
                                                    <!-- <input type="text" class="form-control" placeholder="From Date" id="datepicker"> -->
                                                    <span class="input-group-addon bg-custom b-0">
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
                                                	<DatePicker :bootstrap-styling="true" name="to_date" :format="dateFormat" placeholder="To Date" id="to_date"></DatePicker>
                                                    <!-- <input type="text" class="form-control" placeholder="To Date" id="datepicker-autoclose"> -->
                                                    <span class="input-group-addon bg-custom b-0">
                                                        <i class="mdi mdi-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
		                                </div>
		                                 <div class="col-md-2">
		                                    <div class="form-group">
		                                        <label>User Id</label>
		                                        <input class="form-control"  placeholder="Enter user id" type="text" id="user_id" f>
		                                    </div>
		                                </div>
		                                 
		                                <div class="row">
		                                    <div class="col-md-12">
		                                        <div class="text-center">
		                                            <button type="button" class="btn btn-primary waves-effect waves-light" id="onSearchClick">Search</button>
                                                    <button type="button" class="btn btn-info waves-effect waves-light" @click="exportToExcel">Export To Excel</button>
		                                            <button type="button" class="btn btn-dark waves-effect waves-light mt-4" id="onResetClick">Reset</button>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
		                        </div><!-- panel-body -->
		                    </div><!-- panel -->
		                </div><!-- col -->
		            </div>
		        </form>
                <div class="row">
                    <div class="col-md-3 col-sm-6" v-for="summary in withdrawsummary">
                        <div class="panel">
                            <div class="panel-body">
                                <h5 class="panel-title text-muted">
                                   Total {{summary.currency}} Amount <span class="pull-right">{{summary.total_amount}}</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>    
		        <div class="row">
		            <div class="col-md-12">
		                <div class="panel panel-primary">
		                    <div class="panel-body">
		                        <table id="withdraw-request-report" class="table table-striped table-bordered dt-responsive" width="100%">
		                            <thead>
		                                <tr>
		                                    <th>Sr.No</th>
                                           <!--  <th>
                                                <input type="checkbox" id="allCheck"/>Select All
                                            </th> -->
                                            <th>
                                                <input type="checkbox" id="allCheck">Select All
                                            </th>
                                            <th>User Id</th>
                                            <th>Amount</th>
                                            <th>Service Charges</th>
                                            <th>Net Amount</th>
                                            <!-- <th>Service Charges</th> -->
                                            <!-- <th>Net Amount</th> -->
                                            
                                            <!-- <th>Net Amount</th> -->
                                            <th>Currency Type</th>
                                            <th>Wallet Type</th>
                                            <th>Address</th>
                                            <th>Status</th>
                                             <th>Topup From</th>
                                            <th>IP Address</th>
                                            <th>Date</th>

                                           <!--  <th>Bank Account Number</th>

                                            <th>Bank Account Number</th>

                                            <th>Bank Branch Name</th>
                                            <th>A/C Holder Name</th>
                                            <th>Pan Card Number</th>
                                            <th>Bank Name</th>
                                            <th>IFSC Code</th> -->
		                                </tr>
		                            </thead>
		                            <tfoot>
		                                <tr>
		                                    <th>Sr.No</th>
                                            <th>
                                                <input type="checkbox" id="allCheck"/>Select All
                                            </th>
                                            <!-- <th>Action</th> -->
                                            <th>User Id</th>
                                            <th>Amount</th>
                                            <th>Service Charges</th>
                                            <th>Net Amount</th>
                                            <!-- <th>Service Charges</th> -->
                                            <!-- <th>Net Amount</th> -->
                                            
                                            <!-- <th>Net Amount</th> -->
                                            <th>Currency Type</th>
                                            <th>Wallet Type</th>
                                            <th>Address</th>
                                            <th>Status</th>
                                            <th>Topup From</th>
                                            <th>IP Address</th>
                                            <th>Date</th>
                                            <!-- <th>Bank Account Number</th>
                                            <th>Bank Branch Name</th>
                                            <th>A/C Holder Name</th>
                                            <th>Pan Card Number</th>
                                            <th>Bank Name</th>
                                            <th>IFSC Code</th> -->
		                                </tr>
		                            </tfoot>
		                        </table>
		                    </div>
		                     <div class="row" style="padding-bottom: 15px;">
		                        <!-- <div class="col-md-12">
		                            <div class="form-group">
		                                <div class="col-md-2">
		                                    <h4>Remark:</h4>
		                                </div>
		                                <div>
		                                    <div class="col-md-4">
		                                        <textarea class="form-control rounded-0" id="remark" placeholder="Enter remark here" rows="3" v-model="remark">Pay Now</textarea>
		                                    </div>
		                                </div>
		                            </div>
		                        </div> -->
		                        <div class="col-md-12">
		                            <div class="col-md-2">
		                            </div>
		                            <div class="col-md-4">
		                                <button type="button" class="btn btn-info waves-effect waves-light" @click.prevent="sendOTP()" :disabled="arrayForSelectedCheckbox.length == 0"> Verify Withdrawals </button>
		                            </div>
		                        </div>
		                    </div> 

		                </div>
		            </div>
		        </div><!-- end row -->
		    </div><!-- container -->
            <!--<div class="modal fade" id="add-remark-modal">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="fa fa-times"></span></button>
                             <h5 class="modal-title" id="exampleModalLabel">Enter OTP</h5>
                        </div>
                        <div class="modal-body">
                            <div class="row form-group">
                                <div class="col-md-2">
                                    <label>Enter OTP</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" v-model="admin_otp" name="admin_otp" id="admin_otp" required />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer text-right">
                            <button type="button" class="btn btn-info" @click="withdrawalVerify">Submit</button>
                            <button type="button" class="btn" data-dismiss="modal" aria-label="Close">Cancel</button>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- Popup start -->
            <div id="editBankDetailsmodal" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" class="modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Verify OTP</h4>
                            <button type="button" class="close btn" data-dismiss="modal" @click="closePopup('editBankDetailsmodal')">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="row form-group">
                                <div class="col-md-2">
                                    <label>Enter Temporary OTP</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="otp" v-model="otp" class="form-control" aria-required="true" aria-invalid="false">
                                    <div class="tooltip2" v-show="errors.has('otp')">
                                        <div class="tooltip-inner">
                                            <span v-show="errors.has('otp')">{{ errors.first('otp') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-2">
                                    <label>Enter Permanent OTP</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" v-model="admin_otp" name="admin_otp" id="admin_otp" required />
                                    <div class="tooltip2" v-show="errors.has('admin_otp')">
                                        <div class="tooltip-inner">
                                            <span v-show="errors.has('admin_otp')">{{ errors.first('admin_otp') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <center>
                                        <button @click="verifyOtp()" class="btn btn-primary">Submit</button>
                                    </center>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
               <!-- Popup end -->
		</div><!-- Page content Wrapper -->
	</div><!-- cont-ent -->
</template>

<script>
    import { apiAdminHost } from'./../../admin-config/config';
    import moment from 'moment';
    import DatePicker from 'vuejs-datepicker';

    export default {
        data() {
            return {
                user_data  : [],
                products   : [],
                length : 10,
                start  : 0,
                remark:'Verified by admin',
                arrayForSelectedCheckbox:[],
                admin_otp:'',
                otp:'',
                withdrawsummary : [],
            }
        },
        mounted() {
        	
            this.withdrawRequestReport();
            this.getWithdrawalSummary();
        },
        components: {
            DatePicker
        },
        methods: {
        	dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            withdrawRequestReport(){
            	let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                   that.table = $('#withdraw-request-report').DataTable({
                        responsive: true,
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        responsive: true,
                        stateSave: false,
                        ordering: false,
                        dom: 'Bfrtip',
                        buttons: [
                            'pageLength',
                            /*{
                                extend: 'excelHtml5',
                                title: 'Withdraw Request Report',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },*/
                        ],
                        ajax: {
                            url: apiAdminHost+'/getwithdrwalverify',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                     frm_date: $('#frm_date').val(),
                                     to_date: $('#to_date').val(),
                                     user_id: $('#user_id').val()
                                };
                                Object.assign(d, params);
                                return d;
                            },
                            headers: {
                              'Authorization': 'Bearer ' + token
                            },
                            dataSrc: function (json) {
                                if (json.code === 200) {
                                    that.arrGetHelp = json.data.records;
                                    json['draw'] = json.data.draw;
                                    json['recordsFiltered'] = json.data.recordsFiltered;
                                    json['recordsTotal'] = json.data.recordsTotal;
                                    return json.data.records;
                                } else {
                                    json['draw'] = 0;
                                    json['recordsFiltered'] = 0;
                                    json['recordsTotal'] = 0;
                                    return json;
                                }
                            }
                        },
                        columns: [
                            {
                                render: function (data, type, row, meta) {
                                    return i++;
                                }
                            },
                            {
					          	render: function (data, type, row, meta) {
					            	return `<input data-id="${row.sr_no}" type="checkbox" class="myCheck" value="${row.sr_no}">`;
					          }
					        },
							{
                                render: function (data, type, row, meta) {
                                    return `<span>${row.user_id}</span><span>(${row.fullname})</span>`;
                                }
                            },
                            {
	                            render: function (data, type, row, meta) {
	                                return `$${Number(row.amount)}`;
	                            }
                            },
                             {
                                render: function (data, type, row, meta) {
                                    return `$${Number(row.deduction)}`;
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    return `$${Number(row.amount)-Number(row.deduction)}`;
                                }
                            },
                           
                            // {
	                        //     render: function (data, type, row, meta) {
	                        //         return `$${row.deduction}`;
	                        //     }
                            // },
                            // {
                            // 	render: function (data, type, row, meta) {
	                        //         return `$${row.amount}`;
	                        //     }
                            // },
                            { data: 'network_type' },
                            { data: 'wallet_type' },
                            { data: 'to_address' },
                            { data: 'status' },
                            { data: 'topupfrom' },
                            { data: 'ip_address' },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.entry_time)).format('YYYY-MM-DD');
                                    }
                                }
                            },
                            // { data: 'account_no' },
                            // { data: 'branch_name' },
                            // { data: 'holder_name' },
                            // { data: 'pan_no' },
                            // { data: 'bank_name' },
                            // { data: 'ifsc_code' },
                        ]
                    });

                    $('#onSearchClick').click(function () {
                        that.table.ajax.reload();
                    });

                    $('#onResetClick').click(function () {
                        $('#searchForm').trigger("reset");
                        that.table.ajax.reload();
                    });

                    $('#withdraw-request-report tbody').on('click', '.myCheck', function () {
				      	$('#allCheck').prop('checked', false);
				      	if ($(this).is(':checked')) {
				        	that.arrayForSelectedCheckbox.push($(this).data('id'));
				      	} else if (!$(this).is(':checked')) {
				        	that.arrayForSelectedCheckbox.splice(that.arrayForSelectedCheckbox.indexOf($(this).data('id')), 1);
				      	}
				    });

                    $('#withdraw-request-report thead').on('click','#allCheck',function(){
				      	that.arrayForSelectedCheckbox = [];
				      	if ($('#allCheck').is(':checked')) {
				      		$('input[type="checkbox"].myCheck').prop('checked', true);
				      		$('.myCheck').each(function(i, obj) {
							    that.arrayForSelectedCheckbox.push($(this).data('id'));
							});
				      	} else if(!$('#allCheck').is(':checked')) {
				      		$('input[type="checkbox"].myCheck').prop('checked', false);
				        	$('.myCheck').each(function(i, obj) {
							    that.arrayForSelectedCheckbox.splice(that.arrayForSelectedCheckbox.indexOf($(this).data('id')), 1);
							});
				      	}
				    });
                },0);
            },
            showOTPPopup(){
                $("#add-remark-modal").modal();
            },
            withdrawalVerify() {
                if (this.admin_otp !=='') {
                    axios.post('/verify/withdrwalrequest',{
                        request_id: this.arrayForSelectedCheckbox,
                        admin_otp:this.admin_otp
                    }).then(resp => {
                        if(resp.data.code === 200){
                            this.$toaster.success(resp.data.message);                            
                            this.$router.push({name:'verifiedwithdrawalreport'});
                        } else {
                            this.$toaster.error(resp.data.message);
                            $("#add-remark-modal").modal("hide");
                            this.admin_otp='';
                        }
                    $(".close").trigger('click');
                    $(".close").trigger('click');
                    }).catch(err => {
                        this.$toaster.error(err);
                    })
                }else{
                    this.$toaster.error("OTP is required");
                }
            },
            exportToExcel(){
                var params = {frm_date: $('#frm_date').val(), to_date: $('#to_date').val(),user_id: $('#user_id').val(),action:'export',responseType: 'blob' };
                axios.post('getwithdrwalverify', params).then(resp => {
                    //this.export_url = resp.data.data.excel_url;
                    var mystring = resp.data.data.data;
                    var myblob = new Blob([mystring], {
                        type: 'text/plain'
                    });

                    var fileURL = window.URL.createObjectURL(new Blob([myblob]));
                    var fileLink = document.createElement('a');

                    fileLink.href = fileURL;
                    fileLink.setAttribute('download', 'AllPendingWithdrawals.xls');
                    document.body.appendChild(fileLink);

                    fileLink.click();
                });
            },
            getWithdrawalSummary(){
                axios.post('getWithdrawalSummary',{status: 0,verify_status: 0}).then(resp => {
                    if(resp.data.code === 200){
                        this.withdrawsummary = resp.data.data;
                    } else {
                        this.$toaster.error(resp.data.message);
                    }
                });    
            },
            sendOTP() {
                var arr = {};
                axios
                .post("sendOtp-add-topup", {otp_type:2})
                .then((response) => {
                if (response.data.code == 200) {
                    //console.log(response);
                    this.$toaster.success(response.data.message);
                    //this.statedata=response.data.data.message;
                    this.otp = "";
                    this.admin_otp = "";
                    $("#editBankDetailsmodal").modal("show");
                } else {
                    this.$toaster.error(response.data.message);
                }
                })
                .catch((error) => {});
            },
            closePopup(){
      $("#editBankDetailsmodal").modal("hide");
    },
        verifyOtp() {
            if (this.admin_otp =='') {this.$toaster.error("ADMIN OTP is required"); return false;}
            if (this.otp !=='') {
                axios.post('/verify/withdrwalrequest',{
                    request_id: this.arrayForSelectedCheckbox,
                    otp:this.otp,
                    admin_otp:this.admin_otp
                }).then(resp => {
                    if(resp.data.code === 200){
                        this.$toaster.success(resp.data.message);                            
                        this.$router.push({name:'verifiedwithdrawalreport'});
                        $("#editBankDetailsmodal").modal("hide");
                    } else {
                        this.$toaster.error(resp.data.message);
                        this.otp='';
                    }
                  
                    }).catch(err => {
                        this.$toaster.error(err);
                })
            }else{
                this.$toaster.error("OTP is required");
            }
        },
        }
    }
</script>