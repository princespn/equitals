<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Carry BV Report</h4>
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
                                                    <input type="text" class="form-control" placeholder="From Date" id="datepicker">
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
                                                    <input type="text" class="form-control" placeholder="To Date" id="datepicker-autoclose">
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
		                                        <input class="form-control" required="" placeholder="Enter user id" type="text" id="user_id" f>
		                                    </div>
		                                </div>
		                                 <div class="col-md-2">
		                                    <div class="form-group">
		                                        <label>Payout Number</label>
		                                        <input class="form-control" required="" placeholder="Enter Payout Number" type="text" id="payout_no" f>
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
		                        <table id="carry-bv-report" class="table table-striped table-bordered dt-responsive">
		                            <thead>
		                                <tr>
		                                    <th>Sr.No</th>
		                                    <th>User Id</th>
		                                    <th>Full Name</th>
		                                    <th>Date</th>
		                                    <th>Payout No.</th>
		                                    <th>Match Business</th>
		                                    <th>Carry Left Business</th>
		                                    <th>Before Left Business</th>
		                                    <th>Carry Right Business</th>
		                                    <th>Before Right Business</th>
		                                     
		                                   
		                                    <!-- <th>Action</th> -->

		                                </tr>
		                            </thead>
		                            <!-- <tbody>
		                                <tr>
		                                    <td>1</td>
		                                    <td>System Architect</td>
		                                    <td>1</td>
		                                    <td>5000.00</td>
		                                    <td>10</td>
		                                    <td>
		                                        <label class="text-info"> Active</label>
		                                    </td>

		                                </tr>
		                                <tr>
		                                    <td>2</td>
		                                    <td>Accountant</td>
		                                    <td>1</td>
		                                    <td>5000.00</td>
		                                    <td>10</td>
		                                    <td>
		                                        <label class="text-info"> Active</label>
		                                    </td>
		                                </tr>
		                            </tbody> -->
		                            <tfoot>
		                                <tr>
		                                    <th>Sr.No</th>
		                                    <th>User Id</th>
		                                    <th>Full Name</th>
		                                    <th>Date</th>
		                                    <th>Payout No.</th>
		                                    <th>Match BV</th>
		                                    <th>Carry Left BV</th>
		                                    <th>Before Left BV</th>
		                                    <th>Carry Right BV</th>
		                                    <th>Before Right BV</th>
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
        	
            this.carryBVReport();
        },
        methods: {
            carryBVReport(){
            	let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    const table = $('#carry-bv-report').DataTable({
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
                            url: apiAdminHost+'/show/carrybv',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    frm_date : $('#datepicker').val(),
                                     to_date  : $('#datepicker-autoclose').val(),
                                     user_id  : $('#user_id').val(),
                                      payout_no  : $('#payout_no').val(),

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
							{
                                render: function (data, type, row, meta) {
                                    if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.entry_time)).format('YYYY-MM-DD hh:mm:ss');
                                    }
                                }
                            },
							{ data: 'payout_no' },
							{ data: 'match_bv' },
							{ data: 'carry_l_bv' },
							{ data: 'before_l_bv' },
							{ data: 'carry_r_bv' },
							{ data: 'before_r_bv' },
							
							

                            /*{ data: 'cost' },
                            { data: 'bvalue' },*/
                           /* {
                              render: function (data, type, row, meta) {
                                return '<label class="text-info">'+row.status+'</label>';
                              }
                            }*/
                        ]
                    });
                    $('#onSearchClick').click(function () {
                        table.ajax.reload();
                    });

                    $('#onResetClick').click(function () {
                        $('#searchForm').trigger("reset");
                    });
                },0);
            },
            
        }
    }
</script>