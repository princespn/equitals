<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Unused E- Pin Report</h4>
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
                                                        <DatePicker :bootstrap-styling="true" v-model="frm_date" name="frm_date" :format="dateFormat" placeholder="From Date" id="frm_date"></DatePicker>
                                                        <!-- <input type="text" class="form-control" placeholder="From Date" id="datepicker" v-model="frm_date" autocomplete="off"> -->
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
                                                        <DatePicker :bootstrap-styling="true" v-model="to_date" name="to_date" :format="dateFormat" placeholder="To Date" id="to_date"></DatePicker>
                                                        <!-- <input type="text" class="form-control" placeholder="To Date" id="datepicker-autoclose" v-model="to_date" autocomplete="off"> -->
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
                                                <label>E-Pin</label>
                                                <input type="text" id="pin" name="pin" placeholder="Enter E-Pin" v-model="pin" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="text-center">
                                                    <button class="btn btn-primary waves-effect waves-light" id="onSearchClick" type="button">Search</button>
                                                    <button class="btn btn-info waves-effect waves-light mt-4 m-t-4" type="button">Export To Excel</button>

                                                    <!-- <button class="btn btn-info waves-effect waves-light" data-target="#generatE-Pin" data-toggle="modal" type="button">Generate E-Pin</button>
                                                    <button class="btn btn-warning waves-effect waves-light mt-4" data-target="#transfer-pin" data-toggle="modal" type="button">Transfer Pin</button> -->
                                                    
                                                    <button class="btn btn-dark waves-effect waves-light mt-4 m-t-4 mtop-4" id="onResetClick" type="button">Reset</button>
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
		                        <table id="unused-pin-report" class="table table-striped table-bordered dt-responsive">
		                            <thead>
		                                <tr>
                                            <th>Sr.No</th>
		                                    <th>E-Pin</th>
		                                    <th>Product Name</th>
                                            <th>Date</th>                            
                                            <th>Status</th>              
		                                </tr>
		                            </thead>
		                            <tfoot>
		                                <tr>
		                                    <th>Sr.No</th>
                                            <th>E-Pin</th>
                                            <th>Product Name</th>
                                            <th>Date</th>                            
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
                user_data  : [],
                products   : [],
                length : 10,
                start  : 0,
                user_id : null,
                frm_date : null,
                to_date : null,
                pin : '',
                arrProducts : [],
            }
        },
        components: {
            DatePicker
        },
        mounted() {        	
            this.manageWhatsNew();
            this.getProducts();
        },
        methods: {
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            manageWhatsNew(){
            	let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    const table = $('#unused-pin-report').DataTable({
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
                            url: apiAdminHost+'/report/pin/use_unused',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    frm_date : $('#frm_date').val(),
                                    to_date  : $('#to_date').val(),
                                    product_id  : $('#product_id').val(),
                                    pin : $('#pin').val(),       
                                    status: 'Active',                            
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
                            { data: 'pin' },
                            { data: 'product_name' },                           
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
                                    return '<label type="submit" class="text-info">'+row.pin_status+'</label>';
                                }
                            },  
                            /*{
                              render: function (data, type, row, meta) {
                                    return '<label class="text-info">'+row.status+'</label>';
                                }
                            },    
                            {
                              render: function (data, type, row, meta) {
                                return '<label class="text-danger" title="Delete"><a id="onDeleteClick"><i class="fa fa-trash text-danger font-16"></i></a></label>';
                              	}
                            },*/
							
                            /*{ data: 'cost' },
                            { data: 'bvalue' },*/
                            /*{
                              render: function (data, type, row, meta) {
                                return '<label class="text-info">'+row.status+'</label>';
                              }
                            }*/
                        ]
                    });

                    $('#onSearchClick').click(function () {
                        table.ajax.reload();
                    });

                    $('#onResetClick').click(function () {                        
                        $('#searchForm').trigger("reset");
                        table.ajax.reload();
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
           
        }
    }
</script>	