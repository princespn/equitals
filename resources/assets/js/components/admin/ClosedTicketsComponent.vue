<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Closed Tickets</h4>
		    </div>
		</div>

		<div class="page-content-wrapper">
		    <div class="container">		    
		        <div class="row">
		            <div class="col-md-12">
		                <div class="panel panel-primary">
		                    <div class="panel-body">
		                        <table id="team-view-report" class="table table-striped table-bordered dt-responsive">
		                            <thead>
		                                <tr>
		                                    <th>Sr.No</th>
		                                    <th>Ticket No</th>
		                                    <th>User Id</th>
		                                    <th>Full Name</th>
		                                    <th>Title</th>
		                                    <th>Department</th>
		                                    <th>Date</th>
		                                    <th>Action</th>
		                                </tr>
		                            </thead>
		                            
		                            <tfoot>
		                                <tr>
		                                    <th>Sr.No</th>
		                                    <th>Ticket No</th>
		                                    <th>User Id</th>
		                                    <th>Full Name</th>
		                                    <th>Title</th>
		                                    <th>Department</th>
		                                    <th>Date</th>
		                                    <th>Action</th>
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
            this.manageWhatsNew();
        },
        methods: {
            manageWhatsNew(){
            	let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    $('#team-view-report').DataTable({
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
                            url: apiAdminHost+'/tickets',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    status : 'Close'
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
                            { data: 'ticket_no' },
                            { data: 'user_id' },
                            { data: 'fullname' },
                            { data: 'title' },
                            { data: 'dept_name' },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.entry_time)).format('YYYY-MM-DD');
                                    }
                                }
                            },
                            {
                              render: function (data, type, row, meta) {
                                return '<label class="text-primary waves-effect" id="showTicket">Show-Ticket</label>';
                              	}
                            }
							
                            /*{ data: 'cost' },
                            { data: 'bvalue' },*/
                            /*{
                              render: function (data, type, row, meta) {
                                return '<label class="text-info">'+row.status+'</label>';
                              }
                            }*/
                        ]
                    });
                },0);
            }
           
        }
    }
</script>	