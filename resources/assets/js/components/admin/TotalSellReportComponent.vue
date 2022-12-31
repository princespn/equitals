<template>
<!-- start content -->
<div class="content">
	<div class="">
	    <div class="page-header-title">
	        <h4 class="page-title">Sell Report</h4>
	    </div>
	</div>
	<div class="page-content-wrapper">
	    <div class="container">
	        <div class="row">
	            <div class="col-md-12">
	                <div class="panel panel-primary">
	                    <div class="panel-body">
	                        <div class="">
	                            <form id="searchForm">
	                                <div class="row">
	                                    <div class="col-md-2">
	                                        <div class="form-group">
	                                            <label>From Date</label>
	                                            <div>
	                                                <div class="input-group">
	                                                    <input type="text" class="form-control datepicker" placeholder="From Date" id="frm_date">
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
	                                                    <input type="text" class="form-control datepicker" placeholder="To Date" id="to_date">
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
	                                             <select class="form-control" id="product_id">
		                                        	<option selected value="">Select</option>
		                                            <option :value="product.id" v-for="product in arrProducts">{{product.name}}</option>
		                                        </select>
	                                        </div>
	                                    </div>
	                                    <div class="col-md-2">
	                                        <div class="form-group">
	                                            <label>User Id</label>
	                                            <input class="form-control" placeholder="Enter User Id" type="text" id="user_id">
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
	                                    </div>
	                                </div>

	                                <div class="row">
	                                    <div class="text-center">
                                            <button type="button" class="btn btn-primary waves-effect waves-light" id="onSearchClick">Search</button>
                                            <button type="button" class="btn btn-info waves-effect waves-light">Export To Excel</button>
                                            <button type="button" class="btn btn-dark waves-effect waves-light mt-4" id="onResetClick">Reset</button>
                                        </div>
	                                </div>
	                            </form>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>

	        <div class="row">
	            <div class="col-md-12">
	                <div class="panel panel-primary">
	                    <div class="panel-body">
	                        <table id="total-sell-report" class="table table-striped table-bordered dt-responsive">
	                            <thead>
	                                <tr>
	                                    <th>Sr.No</th>
	                                    <th>User Id</th>
	                                    <th>Full Name</th>
	                                    <th>Product Name</th>
	                                    <th>Cost</th>
	                                    <th>E-Pin</th>
	                                    <th>Type</th>
	                                    <th>Date</th>
	                                </tr>
	                            </thead>
	                            <tfoot>
	                                <tr>
	                                    <th>Total</th>
	                                    <th></th>
	                                    <th></th>
	                                    <th></th>
	                                    <th>0</th>
	                                    <th></th>
	                                    <th></th>
	                                    <th></th>
	                                </tr>
	                            </tfoot>
	                        </table>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</div>
<!-- content -->
</template>

<script>
   	import { apiAdminHost } from'./../../admin-config/config';
   	import moment from 'moment';
    export default {
        data() {
            return {
                provide_help_data  : [],
                length : 10,
                start  : 0,
                arrProducts:[],
            }
        },
        mounted() {
            this.productReport();
            this.getProducts();
        },
        methods: {
            productReport(){
            	let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    const table = $('#total-sell-report').DataTable({
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
                            url: apiAdminHost+'/gettopup',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    product_id: (($('#product_id').val()!='')?$('#product_id').val():that.$route.params.product_id),
                                    user_id: $('#user_id').val(),
                                    status: $('#status').val(),
                                    pin: $('#pin').val(),
                                    frm_date: $('#frm_date').val(),
                                    to_date: $('#to_date').val(),
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
                            { data: 'product_name' },
                            { data: 'amount' },
							{ data: 'pin' },
                            { data: 'status' },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.entry_time)).format('YYYY-MM-DD hh:mm:ss');
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
            	axios.get('/getproducts',{
                }).then(resp => {
                	if(resp.data.code === 200){
                		this.arrProducts = resp.data.data;
                	}
                }).catch(err => {
                	
                })
            },
            reset() {
                this.product_id = this.cost = this.b_value = '';
            }
        }
    }
</script>