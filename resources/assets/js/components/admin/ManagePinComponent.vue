<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Manage Pin</h4>
		    </div>
		</div>

		<div class="page-content-wrapper">
		    <div class="container">	
                <form id="SearchForm">
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
                                            <input class="form-control" required="" placeholder="Enter E-Pin" type="text" id="pin" f>
                                        </div>
                                    </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="text-center">
                                                    <button class="btn btn-primary waves-effect waves-light" id="onSearchClick" type="button">Search</button>
                                                    <button class="btn btn-info waves-effect waves-light" data-target="#generatE-Pin" data-toggle="modal" type="button">Generate E-Pin</button>
                                                    <button class="btn btn-warning waves-effect waves-light mt-4" data-target="#transfer-pin" data-toggle="modal" type="button">Transfer Pin</button>                                                    
                                                    <button class="btn btn-info waves-effect waves-light mt-4 m-t-4" type="button">Export To Excel</button>
                                                    <button class="btn btn-dark waves-effect waves-light mt-4 m-t-4 mtop-4" id="onResetClick" type="button">Reset</button><!-- 
                                                    <button type="submit" class="btn btn-primary waves-effect waves-light" id="onSearchClick">Search</button>
                                                    <button type="button" class="btn btn-info waves-effect waves-light">Export To Excel</button>
                                                    <button type="button" class="btn btn-dark waves-effect waves-light mt-4" id="onResetClick">Reset</button> -->
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
		                        <table id="manage-pin-report" class="table table-striped table-bordered dt-responsive">
		                            <thead>
		                                <tr>
		                                    <th>Sr.No</th>
		                                    <th>Product Name</th>
		                                    <th>E-Pin Name</th>                 
		                                    <th>Create Date</th>
                                            <th>Status</th>
		                                    <th>Action</th>
		                                </tr>
		                            </thead>
		                            <tfoot>
		                                <tr>
		                                    <th>Sr.No</th>
                                            <th>Product Name</th>
                                            <th>E-Pin Name</th>                 
                                            <th>Create Date</th>
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

       <!--Generate E-Pin Modal Box-->
        <div id="generatE-Pin" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Create Pin</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Select Product</label>
                                    <select class="{ error: errors.has('user_id') } form-control" id="product_id" v-model="product_id" v-validate="'required'" name="product_id">
                                        <option value="">Select Product</option>
                                        <option :value="product.id" v-for="product in arrProducts">{{product.name}}</option>
                                    </select>
                                      <div class="tooltip2" v-show="errors.has('product_id')">
                                        <div class="tooltip-inner">
                                            <span v-show="errors.has('product_id')">{{ errors.first('product_id') }}</span>
                                        </div>
                                  </div>
                                </div>  
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">No of E-Pins (only Number)</label>
                                    <input class="{ error: errors.has('user_id') } form-control" name="no_of_pins" id="no_of_pins" v-validate="'required|numeric|max_value:100'" v-model="no_of_pins" placeholder="Enter Number Of Pins" type="text" maxlength="3">
                                      <div class="tooltip2" v-show="errors.has('no_of_pins')">
                                        <div class="tooltip-inner">
                                            <span v-show="errors.has('no_of_pins')">{{ errors.first('no_of_pins') }}</span>
                                        </div>
                                  </div>
                                </div>  
                            </div>
                        </div>                        
                    </div>
                    <div class="modal-footer">
                         <!-- <button :disabled="errors.any() || !isComplete" @click="register" id="register" type="button" class="btn btn-primary">Sign Up</button> -->

                        <button :disabled="errors.any() || !isComplete" type="button" class="btn btn-primary waves-effect waves-light" @click="onCreatePinClick" data-dismiss="modal">Create Pin</button>

                        <button type="button" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Generate E-Pin Modal Box Ends Here-->

        <!--Transfer Pin Modal Box-->
        <div id="transfer-pin" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Transfer Pin</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Transfer To User ID</label>
                                    <input type="text" id="user_id" name="user_id" placeholder="Enter User ID" v-model="user_id" v-validate="'required'" class="{ error: errors.has('user_id') } form-control" v-on:keyup="checkuserexist">
                                    <span id="checkUser" v-bind:class="custom_msg_class">{{userExistsMessage}}</span>
                                    <div class="tooltip2" v-show="errors.has('user_id')">
                                        <div class="tooltip-inner">
                                            <span v-show="errors.has('user_id')">{{ errors.first('user_id') }}</span>
                                        </div>
                                  </div>
                                </div>  
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Full Name</label>
                                    <input type="text" id="to_fullname" name="to_fullname" placeholder="Enter Full Name" v-model="to_fullname" v-validate="'required'" class="{ error: errors.has('to_fullname') } form-control" readonly="readonly">
                                    
                                    <div class="tooltip2" v-show="errors.has('to_fullname')">
                                        <div class="tooltip-inner">
                                            <span v-show="errors.has('to_fullname')">{{ errors.first('to_fullname') }}</span>
                                        </div>
                                  </div>
                                </div>  
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Select Product</label>
                                    <select class="{ error: errors.has('product_id') } form-control" id="product_id" v-model="product_id" v-validate="'required'" name="product_id">                                        
                                        <option value="" selected>Select Product</option>
                                        <option :value="product.id" v-for="product in arrProducts">{{product.name}} (No. of pin available :  {{product.no_of_pins_available}})</option>
                                    </select>
                                      <div class="tooltip2" v-show="errors.has('product_id')">
                                        <div class="tooltip-inner">
                                            <span v-show="errors.has('product_id')">{{ errors.first('product_id') }}</span>
                                        </div>
                                  </div>
                                </div>  
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">No of E-Pins (only Number)</label>
                                    <input class="{ error: errors.has('no_of_pins') } form-control" name="no_of_pins" id="no_of_pins" v-validate="'required|numeric|max_value:100'" v-model="no_of_pins" placeholder="Enter Number Of Pins" type="text" maxlength="3">
                                      <div class="tooltip2" v-show="errors.has('no_of_pins')">
                                        <div class="tooltip-inner">
                                            <span v-show="errors.has('no_of_pins')">{{ errors.first('no_of_pins') }}</span>
                                        </div>
                                  </div>
                                </div>  
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Payment Mode</label>
                                    <select class="{ error: errors.has('payment_mode') } form-control" formControlName="payment_mode" v-model="payment_mode" id="payment_mode" v-validate="'required'" name="payment_mode">
                                      <option value="" selected>Select Payment Mode</option>
                                      <option value="Cash">Cash</option>
                                      <option value="Credit">Credit</option>
                                      <option value="Bank">Bank</option>
                                      <option value="Cheque">Cheque</option>
                                    </select>                                    
                                    <div class="tooltip2" v-show="errors.has('payment_mode')">
                                        <div class="tooltip-inner">
                                            <span v-show="errors.has('payment_mode')">{{ errors.first('payment_mode') }}</span>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            <div class="col-md-12">
                              <div class="form-group">                                
                                <label class="control-label">Remark</label>
                                <textarea class="form-control" placeholder="Enter Remark" rows="2" id="remark" name="remark" v-model="remark"></textarea>
                              </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Payment Verify Date</label>
                                    <div>
                                        <div class="input-group form-control p-0">
                                            <DatePicker :bootstrap-styling="true" v-model="payment_verify_date" name="payment_verify_date" :format="dateFormat" placeholder="Payment Verify Date" id="payment_verify_date"></DatePicker>
                                            <!-- <input type="text" class="form-control datepicker" placeholder="Payment Verify Date" id="payment_verify_date" v-model="payment_verify_date" autocomplete="off"> -->
                                            <span class="input-group-addon bg-custom b-0">
                                                <i class="mdi mdi-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>   
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Payment Verify Remark</label>
                                    <input type="text" id="payment_verify_remark" name="payment_verify_remark" placeholder="Enter Payment Verify Remark" v-model="payment_verify_remark" v-validate="'required'" class="{ error: errors.has('payment_verify_remark') } form-control">
                                    
                                    <div class="tooltip2" v-show="errors.has('payment_verify_remark')">
                                        <div class="tooltip-inner">
                                            <span v-show="errors.has('payment_verify_remark')">{{ errors.first('payment_verify_remark') }}</span>
                                        </div>
                                  </div>
                                </div>  
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Amount Deposited</label>
                                    <input type="text" id="amount_deposited" name="amount_deposited" placeholder="Enter Amount Deposited" v-model="amount_deposited" v-validate="'required'" class="{ error: errors.has('amount_deposited') } form-control">
                                    
                                    <div class="tooltip2" v-show="errors.has('amount_deposited')">
                                        <div class="tooltip-inner">
                                            <span v-show="errors.has('amount_deposited')">{{ errors.first('amount_deposited') }}</span>
                                        </div>
                                  </div>
                                </div>  
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Description</label>
                                    <input type="text" id="description" name="description" placeholder="Enter Description" v-model="description" v-validate="'required'" class="{ error: errors.has('description') } form-control">
                                    
                                    <div class="tooltip2" v-show="errors.has('description')">
                                        <div class="tooltip-inner">
                                            <span v-show="errors.has('description')">{{ errors.first('description') }}</span>
                                        </div>
                                  </div>
                                </div>  
                            </div>


                        </div> 

                    </div>
                    <div class="modal-footer">
                         <!-- <button :disabled="errors.any() || !isComplete" @click="register" id="register" type="button" class="btn btn-primary">Sign Up</button> -->

                        <button :disabled="errors.any() || !isCompleteTransferPin" type="button" class="btn btn-primary waves-effect waves-light" @click="onTransefrePinClick" data-dismiss="modal">Transfer Pin</button>

                        <button type="button" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Transfer Pin Modal Box Ends Here-->

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
                user_data  : [],
                products   : [],
                length : 10,
                start  : 0,
                user_id : null,
                frm_date : null,
                to_date : null,
                arrProducts:[],
                product_id : null,
                no_of_pins : null,
                userExistsMessage : '',
                custom_msg_class : '',
                to_fullname : '',
                to_user_id : null,
                to_fullname : null,
                payment_mode : null,
                remark: '',
                to_user_id: null,
                payment_verify_date: '',
                payment_verify_remark: '',
                amount_deposited: '',
                description: null,
                action: ''
            }
        },
        components: {
            DatePicker
        },        
        computed: {
            isComplete () {
                return this.product_id && this.no_of_pins;
            },
            isCompleteTransferPin(){
                return this.product_id && this.no_of_pins && this.payment_mode;  
            }
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
                    that.table =  $('#manage-pin-report').DataTable({
                        responsive: true,
                        lengthMenu: [[10, 50, 100, 500, 1000, 5000, 10000], [10, 50, 100, 500, 1000, 5000, 10000]],
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        responsive: true,
                        stateSave: false,
                        ordering: false,
                        ajax: {
                            url: apiAdminHost+'/getpins',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    status : 'Active',
                                    frm_date : $('#frm_date').val(),
                                    to_date  : $('#to_date').val(),
                                    product_id  : $('#product_id').val(),
                                    pin :$('#pin').val(),                            
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
                            { data: 'name' },
                            { data: 'pin' },
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
                                    return '<label class="text-info">'+row.status+'</label>';
                                }
                            },    
                            {
                              render: function (data, type, row, meta) {
                                return '<label class="text-danger" title="Delete"><a id="onDeleteClick"><i class="fa fa-trash text-danger font-16"></i></a></label>';
                              	}
                            },
							
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
                        that.table.ajax.reload();
                    });

                    $('#onResetClick').click(function () {                        
                        /*$('#product_id').val('');
                        $('#datepicker').val(''),
                        $('#datepicker-autoclose').val('');
                        $('#pin').val('');          */
                        $('#searchForm').trigger("reset");
                        that.table.ajax.reload();
                    });

                    /*$('#onShowDetailsClick').click(function(){*/
                    $('#manage-pin-report tbody').on('click', '#onDeleteClick', function () 
                    { 
                        if(table.row($(this).parents('tr')).data() !== undefined) {
                            var data = table.row($(this).parents('tr')).data();
                            that.onDeletePinClick(data, table);
                        } else {
                            var data = table.row($(this)).data();
                            that.onDeletePinClick(data, table);
                        }
                    });
                    // $('#generatE-Pin').click(function () {
                    //     $('#generatE-Pin').modal();                
                    // });

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
            onCreatePinClick(){
                Swal({
                    title: 'Are you sure ?',
                    text: "You want to generate pins!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, generate it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/store/pins',{
                            product_id:this.product_id,
                            no_of_pins:this.no_of_pins,
                        }).then(resp => {
                            if(resp.data.code == 200) {
                                this.$toaster.success(resp.data.message);
                                this.table.ajax.reload();
                            } else {
                                this.$toaster.error(resp.data.message);
                            }
                        }).catch(err => {
                            this.$toaster.error(err);
                        });
                    }
                });     
            },
            onDeletePinClick(data, table){
                this.tbl = table;
                Swal({
                    title: 'Are you sure ?',
                    text: "You want to delete this pin!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.value) {
                        // debugger;
                        axios.post('delete/pin', {
                            id : data.id
                        }).then(response => {
                            if(response.data.code == 200) {
                                this.tbl.ajax.reload();
                                this.$toaster.success(response.data.message);
                                this.flash(response.data.message, 'success', {
                                  timeout: 500000,
                                });
                               
                            } else {
                                this.$toaster.error(response.data.message);
                                this.errmessage  = response.data.message;
                                this.flash(this.errmessage, 'warning', {
                                    timeout: 100000,
                                });
                            }
                        }).catch(error => {
                            //this.$toaster.success(response.data.message);
                            this.message  = error.response.data.message;
                            this.flash(this.message, 'error', {
                              timeout: 500000,
                            });
                        });
                    }
                }); 
            },
            checkuserexist() {
                axios.post('checkuserexist',{
                   user_id:this.user_id,
                 }).then(response => {                     
                    if(response.data.code == 404) {
                        // console.log(response.data.message);
                        this.to_fullname = '';
                        this.userExistsMessage = response.data.message;
                        this.custom_msg_class = 'text-danger';                        
                    }else{                                     
                        this.to_fullname = response.data.data.fullname;
                        this.userExistsMessage = response.data.message;
                        this.custom_msg_class = 'text-success';
                    }
                }).catch(error => {                
                    this.message  = '';
                    this.flash(this.message, 'error', {
                      timeout: 500000,
                    });
                });
            },
            onTransefrePinClick(){
                Swal({
                    title: 'Are you sure ?',
                    text: "You want to transfer pins!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, transfer it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.value) {
                        // debugger;
                        axios.post('/pins/transfer',{
                            product_id:this.product_id,
                            no_of_pins:this.no_of_pins,
                            to_user_id:this.user_id,
                            to_fullname:this.to_fullname,
                            payment_mode:this.payment_mode,
                            remark:this.remark,
                            payment_verify_date:(this.payment_verify_date!=='')?moment(this.payment_verify_date).format('DD-MM-YYYY'):'',
                            payment_verify_remark:this.payment_verify_remark,
                            amount_deposited:this.amount_deposited,
                            description:this.description,
                            action:'transfer_pins',                            
                        }).then(response => {
                            if(response.data.code == 200) {                               
                                this.$toaster.success(response.data.message);
                                this.table.ajax.reload();
                                /*this.flash(response.data.message, 'success', {
                                  timeout: 500000,
                                });*/
                            } else {
                                this.$toaster.error(response.data.message);
                                this.errmessage  = response.data.message;
                                /*this.flash(this.errmessage, 'warning', {
                                    timeout: 100000,
                                });*/
                            }
                        }).catch(error => {
                            this.$toaster.error(error.response.data.message);
                            /*this.message  = error.response.data.message;
                            this.flash(this.message, 'error', {
                              timeout: 500000,
                            });*/
                        });
                    }
                }); 
                
            },


           
        }
    }
</script>	