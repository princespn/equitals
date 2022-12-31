<template>
    
    	<!-- Start content -->
    	<div class="content">
    		<div class="">
    		    <div class="page-header-title">
    		        <h4 class="page-title">Pending E-Pin Request</h4>
    		    </div>
    		</div>

    		<div class="page-content-wrapper">
    		    <div class="container">	
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-body">
                                    <div>
                                        <form id="searchForm">
                                            <div class="row">
                                                <div class="col-md-2"></div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>From Date</label>
                                                        <div>
                                                            <div class="input-group">
                                                                <DatePicker :bootstrap-styling="true" name="frm_date" :format="dateFormat" placeholder="From Date" id="frm_date"></DatePicker>

                                                                <!-- <input type="text" class="form-control datepicker" placeholder="From Date" id="frm_date"> -->
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
                                                                <!-- <input type="text" class="form-control datepicker" placeholder="To Date" id="to_date"> -->
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
                                                        <input class="form-control" placeholder="Enter User Id" type="text" id="user_id">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>E-Pin Request Id</label>
                                                        <input class="form-control" placeholder="Enter E-Pin Request Id" type="text" id="pin_req_id">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="text-center">
                                                    <button class="btn btn-primary waves-effect waves-light" id="onSearchClick" type="button">Search</button>
                                                    <button class="btn btn-info waves-effect waves-light" type="button">Export To Excel</button>
                                                    <button class="btn btn-dark waves-effect waves-light mt-4" id="onResetClick" type="button">Reset</button>
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
    		                        <table id="pending-pin-report" class="table table-striped table-bordered dt-responsive">
    		                            <thead>
    		                                <tr>
                                                <th>Sr.No</th>
                                                <th>E-Pin Request Id</th>
                                                <th>User Id</th>
                                                <th>Full Name</th>
                                                <th>Product Details</th>
                                                <th>Amount Deposited Details</th>
                                                <th>Description</th>              
                                                <th>Request Date</th> 
                                                <th>Date</th>                                        
    		                                </tr>
    		                            </thead>
    		                            <tfoot>
    		                                <tr>
    		                                    <th>Sr.No</th>
                                                <th>E-Pin Request Id</th>
                                                <th>User Id</th>
                                                <th>Full Name</th>
                                                <th>Product Details</th>
                                                <th>Amount Deposited Details</th>
                                                <th>Description</th>              
                                                <th>Request Date</th> 
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

            <!-- Product Details Modal -->
            <div class="modal fade" id="product-details" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Product Details</h4>
                        </div>
                        <div class="modal-body">
                            <table class="table table-striped table-bordered dt-responsive">
                                <thead>
                                    <tr>
                                        <th class="ForSrNoWidth">Sr.No</th>
                                        <th>Name </th>
                                        <th>Price </th>
                                        <th>Qty.</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for = "(prodItem,index) in arrayProductDetail">
                                        <td>{{index + 1}}</td>
                                        <td>{{prodItem.name}}</td>
                                        <td>{{prodItem.product_price}}</td>
                                        <td>{{prodItem.request_quantity}}</td>
                                        <td>{{prodItem.total_price}}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3">Total</th>                                        
                                        <th>{{totalQnty}}</th>
                                        <th>{{totalAmoubnt}}</th>
                                    </tr>
                                </tfoot>
                                <!-- <tr style="text-align:center">
                                    <td colspan="13" class="no-data-available text-center">No data available</td>
                                </tr> -->
                            </table>
                        </div>
                        <div class="modal-footer hidden">
                            <button type="button" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

              <!-- Product Details Modal -->
            <div class="modal fade" id="deposit-details" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Product Details</h4>
                        </div>
                        <div class="modal-body">
                            <table class="table table-striped table-bordered dt-responsive">
                                <thead>
                                    <tr>
                                        <th class="ForSrNoWidth">Sr.No</th>
                                        <th>Deposit Type </th>
                                        <th>Date </th>
                                        <th>Amount</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>{{arrayAmountDetail.deposite_type}}</td>
                                        <td>
                                        <span v-if="arrayAmountDetail.deposite_date">{{arrayAmountDetail.deposite_date}}</span>
                                        <span v-if="!arrayAmountDetail.deposite_date">{{arrayAmountDetail.entry_time}}</span>
                                        </td>
                                        <td>{{arrayAmountDetail.amount_deposited}}</td>
                                        
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <!-- <tr>
                                        <th colspan="2">Total</th>
                                        <th>{{totalAmoubnt}}</th>
                                        <th>{{totalQnty}}</th>
                                    </tr> -->
                                </tfoot>
                                <!-- <tr style="text-align:center">
                                    <td colspan="13" class="no-data-available text-center">No data available</td>
                                </tr> -->
                            </table>
                        </div>
                        <div class="modal-footer hidden">
                            <button type="button" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>


              <!-- Verify Paymement Modal -->
              <div class="modal fade" id="verify-payment" role="dialog">
                <div class="modal-dialog">

                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Verify Payment</h4>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        <!-- <div class="col-md-6">
                          <img class="img-responsive" src="assets/images/user.png" alt="Image">
                        </div> -->
                        <div class="col-md-12">
                          <div class="form-group">
                            <label>Verify Date</label>
                            <div>
                              <div class="input-group form-control p-0">
                                <DatePicker :bootstrap-styling="true" v-model="verify_date" name="verify_date" :format="dateFormat" placeholder="From Date" id="verify_date" v-validate="'required'"></DatePicker>
                                  <!-- <input type="text" class="form-control datepicker" placeholder="From Date" id="verify_date" name="verify_date" v-model="verify_date" v-validate="'required'"> -->
                                    <span class="input-group-addon bg-custom b-0">
                                        <i class="mdi mdi-calendar"></i>
                                    </span>
                                    <div class="tooltip2" v-show="errors.has('verify_date')">
                                        <div class="tooltip-inner">
                                            <span v-show="errors.has('verify_date')">{{ errors.first('verify_date') }}</span>
                                        </div>
                                  </div>
                              </div>
                              <!-- input-group -->
                            </div>
                          </div>
                          <div class="form-group">
                            <label>Verify Remark</label>
                            <input class="form-control" required="" name="verifyRemark" placeholder="Verify Remark" type="text" id="verifyRemark" v-model="verifyRemark" v-validate="'required'" >
                             <div class="tooltip2" v-show="errors.has('verifyRemark')">
                                        <div class="tooltip-inner">
                                            <span v-show="errors.has('verifyRemark')">{{ errors.first('verifyRemark') }}</span>
                                        </div>
                                  </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" :disabled="errors.any()" class="btn btn-primary waves-effect waves-light" data-dismiss="modal" @click="verifyPayment">Verify Payment</button>
                        <!-- <button type="button" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Close</button> -->
                    </div>
                  </div>
                </div>
              </div>
              <!-- Reject E-Pin Request Modal-->
              <div class="modal fade" id="reject-pin" role="dialog">
                <div class="modal-dialog">
                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Reject E-Pin Request</h4>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        <!-- <div class="col-md-6">
                          <img class="img-responsive" src="assets/images/user.png" alt="Image">
                        </div> -->
                        <div class="col-md-12">
                          <div class="form-group">
                            <label>E-Pinn Request Id</label>
                            <div>
                              <div class="input-group">
                                  <input type="text" class="form-control" placeholder="Pin Request Id" id="pin_id" name="pin_id" v-model="commonid" v-validate="'required'" readonly>
                                   
                                    <div class="tooltip2" v-show="errors.has('pin_id')">
                                        <div class="tooltip-inner">
                                            <span v-show="errors.has('pin_id')">{{ errors.first('pin_id') }}</span>
                                        </div>
                                  </div>
                              </div>
                              <!-- input-group -->
                            </div>
                          </div>
                          <div class="form-group">
                            <label> Remark</label>
                            <input class="form-control" required="" name="remark" placeholder="Verify Remark" type="text" id="remark" v-model="remark" v-validate="'required'" >
                             <div class="tooltip2" v-show="errors.has('remark')">
                                        <div class="tooltip-inner">
                                            <span v-show="errors.has('remark')">{{ errors.first('remark') }}</span>
                                        </div>
                                  </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-primary waves-effect waves-light"  data-dismiss="modal"  @click = "rejectPayment">Reject Payment</button>
                      <!-- <button type="button" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Close</button> -->
                    </div>
                  </div>

                </div>
              </div>

              
              <!-- For Iamge -->
                <div id="myModal" class="modal2">
                  <span class="close2" @click="closeDialog();">×</span>
                  <img class="modal-content2" id="img01" v-bind:src="dialogImage">
                  <div id="caption"></div>
                </div>

                   <!-- Approve E-pin -->
              <div class="modal fade" id="approve-Pin" role="dialog">
                <div class="modal-dialog">
                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Transfer Pin</h4>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label>E-Pin Request Id</label>
                            <input class="form-control" required="" placeholder="E-Pin Number" type="text" v-model="pinRequestIdForApprovePin" name="pinRequestIdForApprovePin" v-validate="'required'" readonly>
                            <div class="tooltip2" style="top:137px" v-show="errors.has('pinRequestIdForApprovePin')">
                                        <div class="tooltip-inner">
                                            <span v-show="errors.has('pinRequestIdForApprovePin')">{{ errors.first('pinRequestIdForApprovePin') }}</span>
                                        </div>
                                  </div>
                          </div>
                          <div class="form-group">
                            <label>Payment Mode</label>
                            <select class="form-control" v-model="paymentModeForApprovePin" name="paymentModeForApprovePin" v-validate="'required'" >
                              <option value="" selected>Select Mode</option>
                              <option value="Cash">Cash</option>
                              <option value="Credit">Credit</option>
                              <option value="Bank">Bank</option>
                              <option value="Cheque">Cheque</option>
                            </select>
                             <div class="tooltip2" style="top:137px" v-show="errors.has('paymentModeForApprovePin')">
                                        <div class="tooltip-inner">
                                            <span v-show="errors.has('paymentModeForApprovePin')">{{ errors.first('paymentModeForApprovePin') }}</span>
                                        </div>
                                  </div>
                          </div>
                          <div class="form-group">
                            <label>Remark</label>
                            <textarea class="form-control" required="" placeholder="Stock Receiver Proof " type="text" v-model="remarkForApprovePin" name="remarkForApprovePin" v-validate="'required'"></textarea>
                           
                            <div class="tooltip2" style="top:274px" v-show="errors.has('remarkForApprovePin')">
                                        <div class="tooltip-inner">
                                            <span v-show="errors.has('remarkForApprovePin')">{{ errors.first('remarkForApprovePin') }}</span>
                                        </div>
                                  </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" :disabled="errors.any() ||  !isComplete" class="btn btn-primary waves-effect waves-light" @click="onTransferPinClick" data-dismiss="modal">Transfer Pin</button>
                      <!-- <button type="button" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Close</button> -->
                    </div>
                  </div>

                </div>
              </div>

  
              

  
    	</div><!-- content -->
</template>

<script>
    import { apiAdminHost } from'./../../admin-config/config';
    import moment from 'moment';
    import Swal from 'sweetalert2/dist/sweetalert2.js'
    import DatePicker from 'vuejs-datepicker';

    export default {
        data() {
            return {
                length : 10,
                start  : 0,
                arrProducts:[],
                arrayProductDetail:[],
                arrayAmountDetail:{},
                sum1:0,
                sum2:0,
                totalAmoubnt:0,
                totalQnty:0,
                verify_date:'',
                verifyRemark:'',
                pin_id:'',
                remark:'',
                commonid:'',
                pin_id:'',
                tbl:'',
                dialogImage:'',
                paymentModeForApprovePin:'',
                remarkForApprovePin:'',
                pinRequestIdForApprovePin:'',
            }
        },
        mounted() {        	
            this.manageWhatsNew();
            this.getProducts();
        },
        components: {
            DatePicker
        },
        computed: {
            isComplete () {
                return this.paymentModeForApprovePin && this.remarkForApprovePin && this.pinRequestIdForApprovePin;
            },
            isCompleteforverify() {
                return /*this.verify_date && */this.verifyRemark;
            }
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
                    const table = $('#pending-pin-report').DataTable({
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
                                    status : 'Request',
                                    product_id: $('#product_id').val(),
                                    amount: $('#amount').val(),
                                    frm_date: $('#frm_date').val(),
                                    to_date: $('#to_date').val(),
                                    user_id: $('#user_id').val(),
                                    pin_req_id: $('#pin_req_id').val(),            
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
                                    return `<label class="text-success waves-effect" id="onDetailClick">Detail</label>`;
                                }
                            },  

                            { data: 'description' },                            
                            {
                                render: function (data, type, row, meta) {
                                    if (row.request_date === null || row.request_date === undefined || row.request_date === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.request_date)).format('YYYY-MM-DD');
                                    }
                                }
                            },
                           
                            /*{
                                render: function (data, type, row, meta) {
                                    return `<ul class="list-unstyled">
                                                <li><label class="text-success waves-effect" id="verifyPayment"> Verify</label>
                                                </li>
                                                <li>
                                                    <label class="text-danger waves-effect" id="onRejectClick">Reject</label>
                                                </li>
                                                <li><label class="text-info waves-effect" id="showImage">Receipt</label>
                                                </li>
                                            </ul>`;
                                }
                            },    */
                               {
          render: function (data, type, row, meta) {
            if (row.verify_date === null || row.verify_date === undefined || row.verify_date === '') {
              var format_verify = '-';
            } else {
              format_verify = $.datepicker.formatDate('yy-mm-dd', new Date(row.verify_date));
            }

            if (row.payment_status === 'Verified') {
              var Render_one = `Verify Date: ${format_verify},
                               Verify Remark: ${row.verify_remark}`;
            } else {
              Render_one = '';
            }
            if (row.payment_status === 'Verified') {
              var Render_two = `<li>
                                <label class="text-success waves-effect "
                                 id="approve_payment"> Approve</label></li>`;
            } else {
              Render_two = `<li>
                            <label class="text-success waves-effect"
                            id="verifyPayment"> Verify</label></li>`;
            }
            var merge = `<ul class="list-unstyled">
                          ${Render_two}
                          <li class="">
                            <label class="text-danger waves-effect"
                            id="onRejectClick">Reject</label>
                          </li>
                          <li>
                            <label class="text-info  waves-effect"
                            id="showImage1">Receipt</label>
                          </li>
                        </ul>`;
            return merge;
          }
        }
                            /*
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
                    });

                    /*$('#onShowDetailsClick').click(function(){*/
                    $('#pending-pin-report tbody').on('click', '#onShowDetailsClick', function () 
                    {
                     
                       // $('#product-details').modal('show');
                        if(table.row($(this).parents('tr')).data() !== undefined) {
                            var data = table.row($(this).parents('tr')).data();
                            that.onShowDetailsClick(data);
                        } else {
                            var data = table.row($(this)).data();
                            that.onShowDetailsClick(data);
                        }
                    });
                     /*  Deposit details*/
                       $('#pending-pin-report tbody').on('click', '#onDetailClick', function () 
                    {
                     
                       // $('#product-details').modal('show');
                        if(table.row($(this).parents('tr')).data() !== undefined) {
                            var data = table.row($(this).parents('tr')).data();
                            that.onDetailClick(data);
                        } else {
                            var data = table.row($(this)).data();
                            that.onDetailClick(data);
                        }
                    });
                       /*  Appprove details*/
                    $('#pending-pin-report tbody').on('click', '#verifyPayment', function () 
                    {
                          
                       // $('#product-details').modal('show');
                        if(table.row($(this).parents('tr')).data() !== undefined) {
                            var data = table.row($(this).parents('tr')).data();
                            that.onApproveClick(data,table);
                        } else {
                            var data = table.row($(this)).data();
                            that.onApproveClick(data,table);
                        }
                    });
                    $('#pending-pin-report tbody').on('click', '#onRejectClick', function () 
                    {   
                       
                       // $('#product-details').modal('show');
                        if(table.row($(this).parents('tr')).data() !== undefined) {
                            var data = table.row($(this).parents('tr')).data();
                            that.onRejectClick(data,table);
                        } else {
                            var data = table.row($(this)).data();
                            that.onRejectClick(data,table);
                        }
                    });
                  $('#pending-pin-report tbody').on('click', '#showImage1', function () {
                   
                  if (table.row($(this).parents('tr')).data() !== undefined) {
                    var data = table.row($(this).parents('tr')).data();
                    that.showImg(data);
                  } else {
                    var data = table.row($(this)).data();
                    that.showImg(data);
                  }
                });
                  $('#pending-pin-report tbody').on('click', '#approve_payment', function () {
                  if (table.row($(this).parents('tr')).data() !== undefined) {
                    var data = table.row($(this).parents('tr')).data();
                    that.ApprovePayment(data,table);
                  } else {
                    var data = table.row($(this)).data();
                    that.ApprovePayment(data,table);
                  }
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
            onShowDetailsClick(data){

                var i;
                $('#product-details').modal();               
                this.arrayProductDetail = data.user_cart;
                console.log(this.arrayProductDetail);
                 for (let data1 of data.user_cart) {
        // tslint:disable-next-line:radix
        this.totalAmoubnt = this.totalAmoubnt + parseInt(data1.total_price);
        // tslint:disable-next-line:radix
        this.totalQnty = this.totalQnty + parseInt(data1.request_quantity);
      }


                },
                onDetailClick(data){
                    //console.log(data); 
                    $('#deposit-details').modal();               
                    this.arrayAmountDetail = data;
                },
                onApproveClick(data,table){
                     this.totalAmoubnt=0;
                     this.totalQnty=0;
                     this.tbl = table;
                     //console.log(data); 
                     this.commonid = data.id;
                    $('#verify-payment').modal();   

                },
                 ApprovePayment(data,table){

                     //console.log(data); 
                     this.commonid = data.id;
                     this.pinRequestIdForApprovePin = this.commonid;
                     this.tbl = table;
                    $('#approve-Pin').modal();   

                },
                onRejectClick(data,table){

                     //console.log(data); 
                    this.commonid = data.id;
                     this.tbl = table;
                    $('#reject-pin').modal();   

                },
                showImg(data){

                    if (!data.attachment) {

                        var img = 'admin_assets/images/no_image_available.png';
                    } else {
                         img = data.attachment;
                    }
                    var modal = document.getElementById('myModal');
                    modal.style.display = 'block';
                    this.dialogImage = img;  

                },

                verifyPayment(){
                      Swal({
                    title: 'Are you sure ?',
                    text: "You won't be able to change this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        axios.post('verify/payment', {
                            verify_date:(this.verify_date!=='')?moment(this.verify_date).format('DD-MM-YYYY'):'',
                            verify_remark:this.verifyRemark,
                            id: this.commonid
                        }).then(response => {
                            if(response.data.code === 200) {
                                this.$toaster.success(response.data.message);
                                this.tbl.ajax.reload();
                                this.flash(response.data.message, 'success', {
                                  timeout: 500000,
                                });
                            } else {
                                this.$toaster.error(response.data.message);
                                this.errmessage = response.data.message;
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
                })

                },
                rejectPayment(){
                      Swal({
                    title: 'Are you sure ?',
                    text: "You won't be able to change this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        axios.post('reject/pin/request', {
                          remark:this.remark,
                          id:this.commonid,
                        }).then(response => {
                            if(response.data.code == 200) {
                                this.$toaster.success(response.data.message);
                                this.tbl.ajax.reload();
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
                })
                },
                closeDialog() {
                    var modal = document.getElementById('myModal');
                    modal.style.display = 'none';
                },
                onTransferPinClick() {
                      Swal({
                    title: 'Are you sure ?',
                    text: "You won't be able to change this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        axios.post('approve/pin/request', {                           
                         payment_mode:paymentModeForApprovePin,
                         remark:this.remarkForApprovePin,
                         id:pinRequestIdForApprovePin,
                         payment_mode:this.payment_mode
                        }).then(response => {
                            if(response.data.code == 200) {
                                this.$toaster.success(response.data.message);
                                this.tbl.ajax.reload();
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
                })   
                }
            
       
   }
}
</script>	