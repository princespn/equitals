<template>
    	<!-- Start content -->
    	<div class="content">
    		<div class="">
    		    <div class="page-header-title">
    		        <h4 class="page-title">Pending INR Topup Request</h4>
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
                                              <!--   <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>E-Pin Request Id</label>
                                                        <input class="form-control" placeholder="Enter E-Pin Request Id" type="text" id="pin_req_id">
                                                    </div>
                                                </div> -->
                                            </div>
                                            <div class="row">
                                                <div class="text-center">
                                                    <button class="btn btn-primary waves-effect waves-light" id="onSearchClick" type="button">Search</button>
                                                   <!--  <button class="btn btn-info waves-effect waves-light" type="button">Export To Excel</button> -->
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
    		                        <table id="pending-franchise-report" class="table table-striped table-bordered dt-responsive table-responsive" width="100%">
    		                            <thead>
    		                                <tr>
                                                <th>Sr.No</th>
                                                <th>User Id</th>              
                                                <th>Fullname</th>
                                                <th>Amount</th>
                                                <th>Attachment</th>
                                                 <th>Remark</th>
                                                <th>Action</th>   
                                                <th>Remark</th> 
                                                <th>Status</th>
                                                <th>Date</th>
                                                        
    		                                </tr>
    		                            </thead>
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
   // import { apiAdminHost } from'./../../admin-config/config';
   
    import Swal from 'sweetalert2/dist/sweetalert2.js'
     import moment from 'moment';
    import DatePicker from 'vuejs-datepicker';
    /*import { formatDates } from'./../../helper'; */
    import { apiAdminHost } from'./../../admin-config/config';

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
                INR:'',
            }
        },
        mounted() { 
            this.getProjectSetting();
            this.getFundRequest();
        },
      
        components: {
            DatePicker
        },
        computed: {
            // isComplete () {
            //     return this.paymentModeForApprovePin && this.remarkForApprovePin && this.pinRequestIdForApprovePin;
            // },
            // isCompleteforverify() {
            //     return /*this.verify_date && */this.verifyRemark;
            // }
        },
        methods: {
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            getFundRequest(){
                
            	
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    let i = 0;
                     that.table = $('#pending-franchise-report').DataTable({
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                        retrieve: true,
                        destroy: true,
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        stateSave: false,
                        ordering: false,
                    
                        ajax: {
                            url: apiAdminHost+'/get-fund-request',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    status : 'Pending',
                                    frm_date: $('#frm_date').val(),
                                    to_date: $('#to_date').val(),
                                    user_id: $('#user_id').val(),         
                                };
                                Object.assign(d, params);
                                return d;
                            },
                            headers: {
                                'Authorization': 'Bearer ' + token
                            },
                            dataSrc: function (json) {
                                   if (json.code === 200) {
                                    i = 0;
                                    i = parseInt(json.data.start) + 1;
                                    //json['draw'] = json.data.draw;
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
                                    return meta.row + meta.settings._iDisplayStart + 1;
                                }
                            },
                            { data: 'user_id' },
                            { data: 'fullname' },
                            /*{ data: 'amount' },*/
                            { render: function (data, type, row, meta,) {
                                   return `<span>$${row.amount}</span><span>(₹${row.amount * that.INR})</span>`;
                                
                                } 
                            },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.attachment === null) {
                                        return `<img src="public/uploads/files/no_image_available.png" width="70" height="70">`;
                                    } else {
                                        return `<a href="${row.attachment}" target="__blank"><img alt="" src="${row.attachment}" width="70" height="70"></a>`;
                                    }
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    
                                        return `<textarea class="remark form-control" id="remark_${row.id}">` ;
                                    
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    return `<ul class="list-unstyled">
                                                <li><label class="text-success waves-effect" id="onApproveClick" data-id="${row.id}" data-user-id="${row.uid}" data-product-id="${row.product_id}" data-amount="${row.amount}"> Approve</label>
                                                </li>
                                                <li>
                                                    <label class="text-danger waves-effect" data-id="${row.id}" id="onRejectClick">Reject</label>
                                                </li>
                                              
                                            </ul>`;
                                }
                            },
                             {
                                render: function (data, type, row, meta) {
                                    if (row.admin_remark === null || row.admin_remark === undefined || row.admin_remark === '') {
                                      return `-`;
                                    } else {
                                        return row.admin_remark;
                                    }
                                }
                            },
                            { data: 'status' },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.entry_time)).format('YYYY-MM-DD');
                                    }
                                }
                            },
                          
                        ]
                    });
                    
                    $('#onSearchClick').click(function () {
                        that.table.ajax.reload();
                    });

                    $('#onResetClick').click(function () {
                        $('#searchForm').trigger("reset");
                        that.table.ajax.reload();
                    });
                    
                    /*  Appprove details */
                    $('#pending-franchise-report tbody').unbind('click').on('click', '#onApproveClick', function (event){
                        var data = that.table.row($(this).parents('tr')).data();
                        var remark = $('#remark_' + data.id).val(); var amount = $(this).data('amount');
                        var product_id = $(this).data('product-id');
                        var user_id = $(this).data('user-id')
                        that.onApproveClick($(this).data('id'),remark,user_id,product_id,amount);
                    });
                    $('#pending-franchise-report tbody').on('click', '#onRejectClick', function () {  
                           var data = that.table.row($(this).parents('tr')).data();
                        var remark = $('#remark_' + data.id).val();   
                        that.onRejectClick($(this).data('id'),remark);
                    });
                 
                },0);
            },
            onApproveClick(id,remark,user_id,product_id,amount){

               // var remark = $('#remark').val();
                if(remark == ''){
                    this.$toaster.error("Enter Remark"); 
                    return false;
                }
                      Swal({
                    title: 'Are you sure ?',
                    text: "You won't be able to change this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        axios.post('approve-fund-request', {
                            id: user_id,
                            remark:remark,
                            hash_unit:amount,
                            product_id:product_id,
                            f_id:id,
                            payment_type:"INR",
                        }).then(response => {
                            if(response.data.code === 200) {
                                this.$toaster.success(response.data.message);
                                this.table.ajax.reload();                             
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
                onRejectClick(id,remark){
                    // var remark = $('.remark').val();
                if(remark == ''){
                    this.$toaster.error("Enter Remark"); 
                    return false;
                }
                      Swal({
                    title: 'Are you sure ?',
                    text: "You won't be able to change this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        axios.post('reject-fund-request', {                         
                          id:id,
                         remark:remark
                        }).then(response => {
                            if(response.data.code == 200) {
                                this.$toaster.success(response.data.message);
                                 this.table.ajax.reload();
                                
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