<template>
    <div class="content">
	    <div class="">
	        <div class="page-header-title">
	            <h4 class="page-title">Enquiry Reports</h4>
	        </div>
	    </div>
	    <div class="page-content-wrapper">
	        <div class="container">
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="panel panel-primary">
	                        <div class="panel-body">
	                            <div class="row">
	                                <div class="col-md-12">
	                                     <form id="searchForm">
                                    <div class="row">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>From Date</label>
                                                <div>
                                                    <div class="input-group">
                                                        <DatePicker :bootstrap-styling="true" name="frm_date" :format="dateFormat" placeholder="From Date" id="frm_date"></DatePicker>
                                                        <!-- <input type="text" class="form-control datepicker" placeholder="From Date" id="frm_date"> -->
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
                                                        <DatePicker :bootstrap-styling="true" name="to_date" :format="dateFormat" placeholder="To Date" id="to_date"></DatePicker>
                                                        <!-- <input type="text" class="form-control datepicker" placeholder="To Date" id="to_date"> -->
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
                                                <input class="form-control" placeholder="Enter User Id" type="text" id="user_id">
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Product</label>
                                                 <select class="form-control" id="product_id">
                                                    <option selected value="">Select</option>
                                                    <option :value="product.id" v-for="product in arrProducts">{{product.name}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>E-Pin</label>
                                                <input class="form-control" placeholder="Enter E-Pin" type="text" id="pin">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Type</label>
                                                <select class="form-control" id="status">
                                                    <option selected value="">Select type</option>
                                                    <option value="registration">Purchase</option>
                                                    <option value="repurchase">Repurchase</option>
                                                </select>
                                            </div>
                                        </div> -->
                                    </div>

                                    <div class="row">
                                        <div class="text-center">
                                            <button type="button" class="btn btn-primary waves-effect waves-light" id="onSearchClick">Search</button>
                                            <!-- <button type="button" class="btn btn-info waves-effect waves-light">Export To Excel</button> -->
                                            <button type="button" class="btn btn-dark waves-effect waves-light mt-4" id="onResetClick">Reset</button>
                                        </div>
                                    </div>
                                </form>
	                                </div>

	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="panel panel-primary">
	                        <div class="panel-body">
	                            <table class="table table-striped table-bordered dt-responsive" id="enquiries-report">
	                                <thead>
	                                    <tr>
	                                        <th>Sr.No</th>
	                                        <th>Fullname</th>
	                                        <th>Email</th>
	                                        <th>Subject</th>
                                            <th>Message</th>
                                            <th>Entry Date</th>
                                            <th>Action</th>
	                                    </tr>
	                                </thead>
	                            </table>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>

	    </div>
   </div>
</template>
<script>
    import moment from 'moment'; 
   // import { formatDates } from'./../../admin-config/helper'; 
   /* import { apiAdminHost } from'./../../config'; */
   import { apiAdminHost } from'./../../admin-config/config';
    import DatePicker from 'vuejs-datepicker';

    export default {
        data() {
            return {
                users : {}
            }
        },
        mounted() {
            this.enquiryReport();
            this.enquiries_report_page_vigit();
        },
        components: {
            DatePicker
        },
        //mixins: [formatDates],
        methods: {
           dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            enquiryReport(){
                let that = this;
                let token = localStorage.getItem('access_token');
                $.fn.dataTable.ext.errMode = 'none';
                setTimeout(function(){
                	let i = 0;
                    const table = $('#enquiries-report').DataTable({
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                        retrieve: true,
                        destroy: true,
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        stateSave: false,
                        ordering: false,
                        ajax: {
                            url: apiAdminHost+'/enquiries',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                	frm_date : $('#frm-date').val(),
                                    to_date  : $('#to-date').val(),
                                };
                                Object.assign(d, params);
                                return d;
                            },
                            headers: {
                              'Authorization': 'Bearer ' + token
                            },
                            dataSrc: function (json) {
                                if (json.code === 200) {
                                    i = 0;
                                    i = parseInt(json.data.start) + 1;
                                    //json['draw'] = json.data.draw;
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
                                    return meta.row + 1;
                                }
                            },
                            { data: 'fullname' },
                            { data: 'email' },
                            { data: 'subject' },
                            { data: 'message' },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.enquiry_datetime === null || row.enquiry_datetime === undefined || row.enquiry_datetime === '') {
                                        return `-`;
                                    } else {
                                        return moment(String(row.enquiry_datetime)).format('YYYY/MM/DD');
                                    }
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    return `<label type="button" class="text-info btn-sm waves-effect enquiry-reply" title=`+row.email+`> Send Reply</label>`
                                }
                            }
                        ]
                    });
                    $('#search-btn').unbind('click').click(function () {
                    	table.ajax.reload();
                    });

                    $('#reset-btn').unbind('click').click(function () {
                    	$('#search-form').trigger("reset");
                        table.ajax.reload();
                    });
                },0);
                $('#enquiries-report').on('click', '.enquiry-reply',function () {
 
                    //console.log(data);
                   // parent
                    that.$router.push({ 
                        name:'send-reply',
                         params:{
                                id:$(this).attr( "title" ),
                            }
                    });
                    
                });
            },
            enquiries_report_page_vigit(){
                axios.get(apiAdminHost+'/send-enquiry-count-zero').then(response => {
                
                }).catch(error => {
               
                });

            }

            
            
        }
    }
</script>