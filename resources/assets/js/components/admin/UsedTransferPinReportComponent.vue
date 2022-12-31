<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Used Transfer E-Pin Report</h4>
		    </div>
		</div>

		<div class="page-content-wrapper">
		    <div class="container">
		        <form id="serachForm">
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
		                                        <label class="control-label">Product</label>
		                                        <select class="form-control" id="product_id" v-model="filters.product_id">
		                                        	<option selected value="">Select</option>
		                                            <option :value="product.id" v-for="product in arrProducts">{{product.name}}</option>
		                                        </select>
		                                    </div>
		                                </div>
		                                <div class="col-md-2">
		                                    <div class="form-group">
		                                        <label>User Id</label>
		                                        <input class="form-control" required="" placeholder="Enter Cost" type="text" v-model="filters.cost" id="user_id">
		                                    </div>
		                                </div>
		                                <div class="col-md-2">
		                                    <div class="form-group">
		                                        <label>E-Pin</label>
		                                        <input class="form-control" required="" placeholder="Enter BV" type="text" v-model="filters.b_value" id="pin">
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
		                        <table id="product-report" class="table table-striped table-bordered dt-responsive">
		                            <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>User Id</th>
                                            <th>Full Name</th>
                                            <th>E-Pin</th>
                                            <th>Product Name</th>
                                            <th>Used By</th>
                                            <th>Used Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>User Id</th>
                                            <th>Full Name</th>
                                            <th>E-Pin</th>
                                            <th>Product Name</th>
                                            <th>Used By</th>
                                            <th>Used Date</th>
                                            <th>Status</th>
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
                provide_help_data  : [],
                length : 10,
                start  : 0,
                filters:{
                	product_id:'',
	                cost:'',
	                b_value:'',
                },
                arrProducts:[],
            }
        },
        mounted() {
            this.productReport();
            this.getProducts();
        },
        components: {
            DatePicker
        },
        methods: {
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            productReport(){
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
                        ajax: {
                            url: apiAdminHost+'/used/transfer/pin',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    frm_date: $('#frm_date').val(),
                                    to_date: $('#to_date').val(),
                                    user_id: $('#user_id').val(),
                                    pin: $('#pin').val(),
                                    product_id: $('#product_id').val(),
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
                            { data: 'pin' },
                            { data: 'product_name' },
                            { data: 'used_by' },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.used_date === null || row.used_date === undefined || row.used_date === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.used_date)).format('YYYY-MM-DD hh:mm:ss');
                                    }
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.pin_status === 'Active') {
                                        return `<label class="text-info">${row.pin_status}</label>`;
                                    } else {
                                        return `<label class="text-warning">${row.pin_status}</label>`;
                                    }
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
            },
            getProducts(){
            	axios.get('/getproducts')
                .then(resp => {
                	if(resp.data.code === 200){
                		this.arrProducts = resp.data.data;
                	}
                }).catch(err => {
                	
                })
            }
        }
    }
</script>