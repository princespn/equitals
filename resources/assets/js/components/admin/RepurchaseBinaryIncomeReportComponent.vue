<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Binary Income Report</h4>
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
                                                <label>Amount</label>
                                                <input class="form-control" required="" placeholder="Enter user id" type="text" id="amount" f>
                                            </div>
                                        </div>
		                                <div class="row">
		                                    <div class="col-md-12">
		                                        <div class="text-center">
		                                            <button type="button" class="btn btn-primary waves-effect waves-light" id="onSearchClick" >Search</button>
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
		                        <table id="repurchase-binary-report" class="table table-striped table-bordered dt-responsive">
		                            <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Payout No</th>
                                            <th>User Id</th>
                                            <th>Full Name</th>
                                            <th>Left BV</th>
                                            <th>Right BV</th>
                                            <th>Laps BV</th>
                                            <th>Match Pair</th>
                                            <th>Amount</th>
                                            <th>TDS</th>
                                            <th>Admin Charges </th>
                                            <th>Net Amount</th>
                                            <th>Date</th>
                                            <th>Action</th>
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
                    const table = $('#repurchase-binary-report').DataTable({
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
                            url: apiAdminHost+'/RepurchasePayoutNoWiseReport',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    frm_date : $('#datepicker').val(),
                                     to_date  : $('#datepicker-autoclose').val(),
                                     user_id  : $('#user_id').val(),
                                     amount  : $('#amount').val(),
                               
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
                            },
                            {
                                render: function (data, type, row, meta) {
                                    // tslint:disable-next-line:max-line-length
                                    return `<label type="button" class="text-info btn-sm waves-effect pair_detail" id="pair_detail" data-id="${row.id}" data-payoutid="${row.payout_no}">Details</label>`;
                                
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

                $('#repurchase-binary-report').on('click', '.pair_detail',function () {
                    that.$router.push({ 
                        name:'subrepurchasebinaryincomereport',
                        params:{
                            id:$(this).data('id'),
                            payout_no:$(this).data('payoutid')
                        }
                    });
                });
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