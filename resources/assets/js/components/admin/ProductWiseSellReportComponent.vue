<template>
<!-- start content -->
<div class="content">
	<div class="">
	    <div class="page-header-title">
	        <h4 class="page-title">Product Wise Sell Report</h4>
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
                                        <div class="col-md-4"></div>
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
	                                            <label>Amount</label>
	                                            <input class="form-control" placeholder="Enter Amount" type="text" id="amount">
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
	                                    <th>Product Name</th>
	                                    <th>Product Cost</th>
	                                    <th>Amount</th>
	                                    <th>User Count</th>
	                                </tr>
	                            </thead>
	                            <tfoot>
	                                <tr>
	                                    <th>Total</th>
	                                    <th></th>
	                                    <th></th>
	                                    <th>0</th>
	                                    <th>0</th>
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
                filters:{
                	product_id:'',
	                amount:''
                },
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
                            url: apiAdminHost+'/show/productwise/sells',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    product_id: $('#product_id').val(),
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
                            { data: 'product_name' },
                            { data: 'cost' },
                            { data: 'amount' },
                            {
                                render: function (data, type, row, meta) {
                                    return `<a id="onPinCountClick" data-product_id="${row.id}">${row.total_users}</a>`;
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

                    $('#total-sell-report').on('click', '#onPinCountClick',function () {
                        that.$router.push({
                            name:'totalsellreport',
                            params:{
                                product_id: $(this).data('product_id'),
                            }
                        });
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