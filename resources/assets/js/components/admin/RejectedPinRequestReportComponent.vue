<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Rejected E-Pin Request</h4>
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
                                     <!--    <div class="col-md-2">
                                            <div class="form-group">
                                                <label>User Id</label>
                                                <input class="form-control" required="" placeholder="Enter Cost" type="text" v-model="filters.cost" id="user_id">
                                            </div>
                                        </div> -->
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>E-Pin</label>
                                                <input class="form-control" required="" placeholder="Enter BV" type="text" v-model="filters.b_value" id="pin">
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
                                            <th>E-Pin Request Id</th>
                                            <th>User Id</th>
                                            <th>Full Name</th>
                                            <th>Product Details</th>
                                            <th>Rejected Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>E-Pin Request Id</th>
                                            <th>User Id</th>
                                            <th>Full Name</th>
                                            <th>Product Details</th>
                                            <th>Rejected Date</th>
                                            <th>Status</th>
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
        components: {
            DatePicker
        },
        mounted() {
            this.productReport();
            this.getProducts();
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
                            url: apiAdminHost+'/report/pin/request',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    status: 'Reject',
                                     frm_date : $('#frm_date').val(),
                                     to_date  : $('#to_date').val(),
                                     pin  : $('#pin').val(),
                                     product_id  : $('#product_id').val(),
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
                            { data: 'id' },
                            { data: 'user_id' },
                            { data: 'fullname' },
                            {
                                render: function (data, type, row, meta) {
                                    return `<label class="text-success waves-effect" id="onShowDetailsClick">Show</label>`;
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.rejected_date === null || row.rejected_date === undefined || row.rejected_date === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.rejected_date)).format('YYYY-MM-DD hh:mm:ss');
                                    }
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.rejected_date === null || row.rejected_date === undefined || row.rejected_date === '') {
                                        var reject_date = `-`;
                                    } else {
                                        var reject_date = moment(String(row.rejected_date)).format('YYYY-MM-DD hh:mm:ss');
                                    }
                                    return `<ul class="list-unstyled">
                                                <li>
                                                    <label class="text-danger" title="Rejected Date: ${reject_date}, Rejected Remark: ${row.rejected_remark}">${row.status}
                                                    </label>
                                                </li>
                                            </ul>`;
                                }
                            },
                            {
                                render:function (data, type, row, meta) {
                                    return `<ul class="list-unstyled">
                                                <li class="">
                                                    <label type="button" class="text-success waves-effect" id="showMoreData">Details</label>
                                                </li>
                                                <li>
                                                    <label type="button" class="text-info waves-effect" id="showImage">Receipt</label>
                                                </li>
                                            </ul>`;
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