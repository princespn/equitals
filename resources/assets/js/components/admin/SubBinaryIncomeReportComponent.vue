<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Sub Binary Income Report</h4>
		    </div>
		</div>

		<div class="page-content-wrapper">
		    <div class="container">
		        <form>
		            <div class="row">
		                <div class="col-md-12">
		                    <div class="panel panel-primary">
		                        <div class="panel-body">
		                            <div class="">
		                                <div class="col-md-3"></div>
		                                <div class="col-md-2">
		                                    <div class="form-group">
		                                        <label class="control-label">Product</label>
		                                        <select class="form-control" v-model="filters.product_id">
		                                        	<option selected value="">Select</option>
		                                            <option :value="product.id" v-for="product in arrProducts">{{product.name}}</option>
		                                        </select>
		                                    </div>
		                                </div>
		                                <div class="col-md-2">
		                                    <div class="form-group">
		                                        <label>Cost</label>
		                                        <input class="form-control" required="" placeholder="Enter Cost" type="text" v-model="filters.cost">
		                                    </div>
		                                </div>
		                                <div class="col-md-2">
		                                    <div class="form-group">
		                                        <label>Business</label>
		                                        <input class="form-control" required="" placeholder="Enter business" type="text" v-model="filters.b_value">
		                                    </div>
		                                </div>
		                                <div class="row">
		                                    <div class="col-md-12">
		                                        <div class="text-center">
		                                            <button type="button" class="btn btn-primary waves-effect waves-light" id="onSearchClick" @click="productReport">Search</button>
		                                            <button type="button" class="btn btn-info waves-effect waves-light">Export To Excel</button>
		                                            <button type="button" class="btn btn-dark waves-effect waves-light mt-4" id="onResetClick" @click="reset">Reset</button>
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
                                            <th>Payout No</th>
                                            <th>User Id</th>
                                            <th>Full Name</th>
                                            <th>Left Business</th>
                                            <th>Right Business</th>
                                            <th>Laps Business</th>
                                            <th>Match Pair</th>
                                            <th>Amount</th>
                                            <th>TDS</th>
                                            <th>Admin Charges </th>
                                            <th>Net Amount</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="8">Total:</th>
                                            <th><span id="total_amount"></span></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
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
        methods: {
            productReport(){
            	let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    $('#product-report').DataTable({
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
                            url: apiAdminHost+'/PayoutHistoryReport',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    id: that.$route.params.id,
                                    payout_no: that.$route.params.payout_no
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
                            { data: 'payout_no' },
                            { data: 'user_id' },
                            { data: 'fullname' },
                            { data: 'left_bv' },
                            { data: 'right_bv' },
                            { data: 'laps_bv' },
                            { data: 'pair_no' },
                            { data: 'amount' },
                            { data: 'tax_amount' },
                            { data: 'amt_pin' },
                            { data: 'net_amount' },
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