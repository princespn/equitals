<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Promotional Token Report</h4>
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
                                                <div class="input-group">
                                                    <DatePicker :bootstrap-styling="true" :format="dateFormat" placeholder="From Date" id="frm_date"></DatePicker>
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
                                                    <DatePicker :bootstrap-styling="true" :format="dateFormat" placeholder="To Date" id="to_date"></DatePicker>
                                                    <span class="input-group-addon bg-custom b-0 datepicker_border">
                                                        <i class="mdi mdi-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
		                                <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Promotional Type</label>
                                                <select name="promotional_type_id" class="form-control" id="promotional_type_id">
                                                    <option value="" selected="">Select</option>
                                                    <option v-for="promotional in arrPromotional" :value="promotional.srno"> {{ promotional.promotional_name }}</option>
                                                </select>
                                            </div>
                                        </div>
		                                 <div class="col-md-2" >
		                                    <div class="form-group">
		                                        <label>User Id</label>
		                                        <input class="form-control" required="" placeholder="Enter user id" type="text" id="user_id" f>
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
		                        <table id="pending-kyc-report" class="table table-striped table-bordered dt-responsive">
		                            <thead>
		                                <tr>
		                                    <th>Sr.No</th>
		                                    <th>User Id</th>
                                            <th>Fullname</th>
                                            <!-- <th>Promotional Type</th> -->
                                            <th>Token</th>
                                            <!-- <th>From Date</th>
                                            <th>To Date</th> -->
                                            <th>Date</th>
		                                </tr>
		                            </thead>
		                            <tfoot>
		                                <tr>
		                                    <th>Sr.No</th>
                                            <th>User Id</th>
                                            <th>Fullname</th>
                                           <!--  <th>Promotional Type</th> -->
                                            <th>Token</th>
                                            <!-- <th>From Date</th>
                                            <th>To Date</th> -->
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
    import Swal from 'sweetalert2';
    import DatePicker from 'vuejs-datepicker';

    export default {
    	data() {
            return {
                arrPromotional:[],
                length : 10,
                start  : 0,
            }
        },
        mounted() {
            this.pendingKYCReport();
            this.getPromotionalTypes();
        },
        components: {
            DatePicker
        },
        methods: {
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            pendingKYCReport(){
            	let i = 0;            	
                let that = this;

                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    that.table = $('#pending-kyc-report').DataTable({
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
                            // 'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5',
                            'pageLength',
                        ],
                        ajax: {
                            url: apiAdminHost+'/show/promotional/income',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    user_id  : $('#user_id').val(),
                                    frm_date  : $('#frm_date').val(),
                                    to_date  : $('#to_date').val(),
                                    promotional_type_id  : $('#promotional_type_id').val(),
                                };
                                Object.assign(d, params);
                                return d;
                            },
                            headers: {
                                'Authorization': 'Bearer ' + token
                            },
                            dataSrc: function (json) {
                                if (json.code === 200) {
                                    json['recordsFiltered'] = json.data.filterRecord;
                                    json['recordsTotal'] = json.data.totalRecord;
                                    return json.data.record;
                                } else {
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
                           /* { data: 'promotional_name' },*/
                            /*{ data: 'amount' },*/
                            {
                                render: function (data, type, row, meta) {
                                    
                                        return '$'+row.amount;
                                    
                                }
                            },
                          /*  {
                                render: function (data, type, row, meta) {
                                    if (row.to_date === null || row.to_date === undefined || row.to_date === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.to_date)).format('YYYY-MM-DD');
                                    }
                                }
                            },*/
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
                        that.table.ajax.reload();
                    });

                    $('#onResetClick').click(function () {
                        $('#searchForm').trigger("reset");
                        that.table.ajax.reload();
                    });
                },0);
            },
            getPromotionalTypes() {
                axios.get("show/promotional/type").then(response => {
                    if(response.data.code === 200){
                        this.arrPromotional = response.data.data;   
                    } else {
                        this.$toaster.error(response.data.message);
                    }
                }).catch(error => {

                });
            }
        }
    }
</script>