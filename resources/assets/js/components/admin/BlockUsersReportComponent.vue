<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Block Users Report</h4>
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
                                                <div class="input-group">
    		                                        <DatePicker :bootstrap-styling="true" v-model="frm_date" name="frm_date" :format="dateFormat" placeholder="From Date" id="frm_date"></DatePicker>
                                                    <span class="input-group-addon bg-custom b-0 datepicker_border">
                                                        <i class="mdi mdi-calendar"></i>
                                                    </span>
                                                </div>
		                                    </div>
		                                </div>
		                                <div class="col-md-2">
		                                    <div class="form-group">
		                                        <label>To Date</label>
                                                <div class="input-group">
    		                                        <DatePicker :bootstrap-styling="true" v-model="to_date" name="to_date" :format="dateFormat" placeholder="To Date" id="to_date"></DatePicker>
                                                    <span class="input-group-addon bg-custom b-0 datepicker_border">
                                                        <i class="mdi mdi-calendar"></i>
                                                    </span>
                                                </div>
		                                    </div>
		                                </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>To User Id</label>
                                                <input class="form-control" required="" placeholder="Enter user id" type="text" id="user_id">
                                            </div>
                                        </div>
		                                <div class="row">
		                                    <div class="col-md-12">
		                                        <div class="text-center">
		                                            <button type="button" class="btn btn-primary waves-effect waves-light" id="onSearchClick">Search</button>
		                                            <!-- <button type="button" class="btn btn-info waves-effect waves-light">Export To Excel</button> -->
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
		                        <table id="product-report" class="table table-striped table-bordered dt-responsive">
		                            <thead>
		                                <tr>
		                                    <th>Sr.No</th>
                                            <th>User ID</th>
                                            <th>Full Name</th>
                                            <th>Sponsor ID</th>
                                            <th>Mobile</th>
                                            <th>Country</th>
		                                    <th>Date</th>

		                                </tr>
		                            </thead>
		                            <tfoot>
		                                <tr>
		                                     <th>Sr.No</th>
                                            <th>User ID</th>
                                            <th>Full Name</th>
                                            <th>Sponsor ID</th>
                                            <th>Mobile</th>
                                            <th>Country</th>
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
        	
            this.teamViewReport();
        },
        components: {
            DatePicker
        },
        methods: {
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            teamViewReport(){
            	let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    const table = $('#product-report').DataTable({
                        responsive: true,
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        responsive: true,
                        stateSave: false,
                        ordering: false,
                        dom: 'Brtip',
                         buttons: [
                            // 'copyHtml5',
                            // 'excelHtml5',
                            // 'csvHtml5',
                            // 'pdfHtml5',
                            'pageLength',
                        ],
                        ajax: {
                            url: apiAdminHost+'/show/block/users',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    frm_date : $('#frm_date').val(),
                                    to_date  : $('#to_date').val(),
                                    id: $('#user_id').val(),
                                    status: $('#status').val(),
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
                                    //return meta.row + 1;
                                    return i++;
                                }
                            },
                            { data: 'user_id' },
                            { data: 'fullname' },
                            { data: 'sponsor_id' },
                            { data: 'mobile' },
                            { data: 'country' },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.entry_time)).format('YYYY-MM-DD');
                                    }
                                }
                            },
                        ]
                    });

                    $('#onSearchClick').click(function () {
                        table.ajax.reload();
                    });

                    $('#onResetClick').click(function () {
                       // alert();    
                        $('#searchForm').trigger("reset");
                        table.ajax.reload();
                    });
                },0);
            }
           
        }
    }
</script>