<template>
<!-- start content -->
<div class="content">
	<div class="">
	    <div class="page-header-title">
	        <h4 class="page-title">Reject Fund Request Report</h4>
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

	        <div class="row">
	            <div class="col-md-12">
	                <div class="panel panel-primary">
	                    <div class="panel-body">
	                        <table id="touprequest-report" class="table table-striped table-bordered dt-responsive">
	                            <thead>
	                                <tr>
	                                    <th>Sr.No</th>
	                                    <th>User Id</th>
	                                    <th>Deposite ID </th>
	                                    <th>Amount</th>
	                                    <!-- <th>Package</th> -->
	                                    <th>Fund Req by</th>
	                                    <!-- <th>Topup Type</th>	
                                        <th>Franchise ID</th> -->
                                        <th>Status</th>
                                         <th>Attachment</th>
                                       <!--    <th>Action</th> -->

                                        <!-- <th>Payment Type</th>         -->        
                                        <!-- <th>withdraw</th> -->
	                                    <th>Date</th>
                                        
	                                    <!-- <th>Type</th> -->
	                                </tr>
	                            </thead>
	                            <tfoot>
	                                <tr>
	                                    <th>Sr.No</th>
	                                    <th>User Id</th>
	                                    <th>Deposite ID </th>
	                                    <th>Amount</th>
	                                   <!--  <th>Package</th> -->
	                                    <th>Fund Req by</th>
	                                   <!--  <th>Topup Type</th>	
                                        <th>Franchise ID</th>  -->
                                         <th>Status</th>
                                         <th>Attachment</th> 
                                          <!--  <th></th>   -->           
                                       <!--  <th>Payment Type</th> -->
                                        <!-- <th>withdraw</th> -->
                                        <th>Date</th>
	                                    
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
   	import DatePicker from 'vuejs-datepicker';
     import Swal from 'sweetalert2/dist/sweetalert2.js'

    export default {
        data() {
            return {
                provide_help_data  : [],
                length : 10,
                start  : 0,
                arrProducts:[],
                INR:''
            }
        },
        mounted() {
            this.topupRequestReport();
            this.getProducts();
            this.getProjectSetting();
        },
        components: {
            DatePicker
        },
        methods: {
        	dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            topupRequestReport(){
            	let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    const table = $('#touprequest-report').DataTable({
                        responsive: true,
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        stateSave: false,
                        ordering: false,
                        dom: 'Bfrtip',
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                        buttons: [
                            // 'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5',
                            'pageLength',
                        ],
                        ajax: {
                            url: apiAdminHost+'/gettopup-request',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    product_id: $('#product_id').val(),
                                    id: $('#user_id').val(),
                                    status: 'reject',
                                    pin: $('#pin').val(),
                                    frm_date: $('#frm_date').val(),
                                    to_date: $('#to_date').val()
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
                                    return meta.row + 1;
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    return `<span>${row.user_id}</span><span>(${row.fullname})</span>`;
                                }
                            },
                            { data: 'pin' },
                            /*{ data: 'amount' },*/
                              { render: function (data, type, row, meta,) {
                                   return `<span>$${row.amount}</span>`;
                                
                                } 
                            },
                            /*{ data: 'name' },*/
							{ data: 'top_up_by' },
                           /* { data: 'top_up_type' },
                            { data: 'franchise_user_id' },*/
                            { data: 'admin_status' }, 
                            {
                                render: function (data, type, row, meta) {
                                    if (row.attachment === null) {
                                        return `<img src="public/uploads/files/no_image_available.png" width="70" height="70">`;
                                    } else {
                                        return `<a class="pointer" id="onImageClick" data-img="${row.attachment}"><img src="${row.attachment}" alt="Payment Slip" height="50" width="50"></a>`;
                                    }
                                }
                            },
                            // {
                            //     render: function (data, type, row, meta) {
                            //         //console.log(row);
                            //         return `<ul class="list-unstyled">
                            //                     <li><label class="text-success waves-effect" id="onApproveClick" data-id="${row.srno}" data-user-id="${row.id}" data-amount="${row.amount}" data-franchise_id="${row.franchise_id}" data-prod_id="${row.type}" data-payment_type="${row.payment_type}"> Approve</label>
                            //                     </li>
                            //                     <li>
                            //                         <label class="text-danger waves-effect" data-id="${row.srno}" id="onRejectClick">Reject</label>
                            //                     </li>
                                              
                            //                 </ul>`;
                            //     }
                            // },
                            /*{ data: 'payment_type' },*/
                            /*{ data: 'withdraw' },*/
                            {
                                render: function (data, type, row, meta) {
                                    if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.entry_time)).format('YYYY-MM-DD');
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
                        table.ajax.reload();
                    });

                      /*  Appprove details */
                    $('#touprequest-report tbody').unbind('click').on('click', '#onApproveClick', function (event){
                      
                       // var data = that.table.row($(this).parents('tr')).data();
                        //var remark = $('#remark_' + data.id).val(); var amount = $(this).data('amount');
                        var user_id = $(this).data('user-id')
                        var amount = $(this).data('amount')
                        var franchise_id = $(this).data('franchise_id')
                       
                        var prod_id = $(this).data('prod_id')
                        var payment_type = $(this).data('payment_type')
                        //  alert(1);
                        that.onApproveClick($(this).data('id'),user_id,amount,franchise_id,prod_id,payment_type);
                    });
                    $('#touprequest-report tbody').on('click', '#onRejectClick', function () {  
                       //    var data = that.table.row($(this).parents('tr')).data();
                       // var remark = $('#remark_' + data.id).val();   
                        that.onRejectClick($(this).data('id'));
                    });

                    $('#touprequest-report').on('click', '#onImageClick', function () {
                        that.showImage($(this).data('img'));
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
            onApproveClick(id,user_id,amount,franchise_id,prod_id,payment_type){

               // var remark = $('#remark').val();
                // if(remark == ''){
                //     this.$toaster.error("Enter Remark"); 
                //     return false;
                // }
                      Swal({
                    title: 'Are you sure ?',
                    text: "You won't be able to change this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        axios.post('approve-topup-request', {
                            srno: id,
                            id:user_id,
                            product_id:prod_id,
                            franchise_user_id:franchise_id,
                            hash_unit:amount,
                            payment_type:payment_type
                        }).then(response => {
                            if(response.data.code === 200) {
                                this.$toaster.success(response.data.message);
                                $('#touprequest-report').DataTable().ajax.reload();
                                //this.table.ajax.reload(); 
                                //this.$router.push({ name: 'approved-fund-request'}); 
                            } else {
                                this.$toaster.error(response.data.message);
                                
                               
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
                onRejectClick(id){
                    // var remark = $('.remark').val();
                // if(remark == ''){
                //     this.$toaster.error("Enter Remark"); 
                //     return false;
                // }
                      Swal({
                    title: 'Are you sure ?',
                    text: "You won't be able to change this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        axios.post('reject-topup-request', {                         
                          id:id
                        // remark:remark
                        }).then(response => {
                            if(response.data.code == 200) {
                                this.$toaster.success(response.data.message);
                                 $('#touprequest-report').DataTable().ajax.reload();
                             //   this.$router.push({ name: 'rejected-fund-request'});
                            } else {
                                this.$toaster.error(response.data.message);
                               
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
            getProjectSetting(){
                axios.get('getprojectsettings', {
            })
            .then(response => {
                this.INR = response.data.data['USD-to-INR'];
            })
            .catch(error => {
            }); 
            }, 
        }
    }
</script>