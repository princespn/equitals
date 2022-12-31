<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Withdraw History Report</h4>
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
		                                <div class="col-md-2"></div>
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
		                                  <div class="col-md-2">
		                                    <div class="form-group">
		                                        <label>Amount</label>
		                                        <input class="form-control"  placeholder="Enter amount" type="text" id="amount" f>
		                                    </div>
		                                </div>
		                                 
		                                <div class="row">
		                                    <div class="col-md-12">
		                                        <div class="text-center">
		                                            <button type="button" class="btn btn-primary waves-effect waves-light" id="onSearchClick">Search</button>
		                                            <button type="button" class="btn btn-info waves-effect waves-light">Export To Excel</button>
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
		            <div class="col-md-12">
		                <div class="panel panel-primary">
		                    <div class="panel-body">
		                        <table id="withdraw-history-report" class="table table-striped table-bordered dt-responsive">
		                            <thead>
		                                <tr>
		                                    <th>Sr.No</th>
		                                    <th>User Id</th>
		                                    <th>Full Name</th>
		                                    <th>Amount</th>
		                                    <th>TDS</th>
		                                    <th>Admin Charges</th>
		                                    <th>Net Amount</th>
		                                    <th>Acc No.</th>
		                                    <th>Holder Name</th>
		                                    <th>Bank Name</th>
		                                    <th>Branch Name</th>
		                                    <th>IFSC Code</th>
		                                    <th>Payment Mode</th>
		                                    <th>Date</th>
		                                </tr>
		                            </thead>
		                            <tfoot>
		                                <tr>
		                                    <th>Sr.No</th>
		                                    <th>User Id</th>
		                                    <th>Full Name</th>
		                                    <th>Amount</th>
		                                    <th>TDS</th>
		                                    <th>Admin Charges</th>
		                                    <th>Net Amount</th>
		                                    <th>Acc No.</th>
		                                    <th>Holder Name</th>
		                                    <th>Bank Name</th>
		                                    <th>Branch Name</th>
		                                    <th>IFSC Code</th>
		                                    <th>Payment Mode</th>
		                                    <th>Date</th>
		                                </tr>
		                            </tfoot>
		                        </table>
		                    </div>
		                </div>
		            </div>
		        </div><!-- end row -->
		    </div><!-- container -->
		</div><!-- Page content Wrapper -->
	</div><!-- content -->
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
            }
        },
        mounted() {
        	
            this.withdrawHistoryReport();
        },
        components: {
            DatePicker
        },
        methods: {
        	dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            withdrawHistoryReport(){
            	let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                   const table = $('#withdraw-history-report').DataTable({
                        responsive: true,
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        responsive: true,
                        stateSave: false,
                        ordering: false,
                        ajax: {
                            url: apiAdminHost+'/show/withdrawal/history',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    frm_date: $('#frm_date').val(),
                                    to_date: $('#to_date').val(),
                                    user_id: $('#user_id').val(),
                                    amount: $('#amount').val(),
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
							{ data: 'user_id' },
							{ data: 'fullname' },
							{ data: 'amount' },
							{ data: 'tds' },
							{ data: 'amt_pin' },
							{ data: 'net_amount' },
							{ data: 'account_no' },
							{ data: 'holder_name' },
							{ data: 'bank_name' },
							{ data: 'branch_name' },
							{ data: 'ifsc_code' },
							{ data: 'payment_mode' },
							{
                                render: function (data, type, row, meta) {
                                    return (row.created_at === null || row.created_at === undefined ||  row.created_at === '')? '-' : moment(String(row.created_at)).format('YYYY-MM-DD');
                                    
                                }
                            }
                        ]
                    });

                    $('#onSearchClick').click(function () {
                        table.ajax.reload();
                    });

                    $('#onResetClick').click(function () {
                    	$('#searchForm').trigger("reset");
                    });

                },0);
            }
            
        }
    }
</script>