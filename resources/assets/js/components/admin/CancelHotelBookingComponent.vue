<template>
    <!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Cancel Hotel Bookings</h4>
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
                                                    <!-- <input type="text" class="form-control" placeholder="To Date" id="datepicker-autoclose"> -->
                                                    <DatePicker :bootstrap-styling="true" name="to_date" :format="dateFormat" placeholder="To Date" id="to_date"></DatePicker>
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
                                                <input class="form-control" required="" placeholder="Enter user id" type="text" id="user_id">
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="text-center">
                                                    <button type="button" class="btn btn-primary waves-effect waves-light" id="onSearchClick">Search</button>
                                                    <button type="button" class="btn btn-info waves-effect waves-light" @click="exportToExcel">Export To Excel</button>
                                                    <button type="button" class="btn btn-dark waves-effect waves-light mt-4" id="onResetClick">Reset</button>
                                                </div><br>
                                                <div class="text-center"><strong class="text-center">Note: All Below User's are already Refunded Automacially by the system</strong></div>
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
                                <table id="cancelhotel-orders" class="table table-striped table-bordered dt-responsive" style="width: 100% !important;">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Order Id</th>
                                            <th>User Id</th>
                                            <th>Mobile</th>
                                            <!-- <th>Full Name</th> -->
                                            <th>Total Coin</th>
                                            <th>Total USD</th>
                                            
                                            <!-- <th>Reject</th> -->
                                            
                                            <!-- <th>Confirm / Cancel</th> -->
                                            <!-- <th>Payment Status</th> -->
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Payment Mode</th>
                                            <th>Remark</th>
                                            <th>Attachment</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    
                                    <tfoot>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Order Id</th>
                                            <th>User Id</th>
                                            <th>Mobile</th>
                                            <!-- th>Full Name</th> -->
                                            <th>Total Coin</th>
                                            <th>Total USD</th>
                                            
                                            <!-- <th>Reject</th> -->
                                            
                                            <!-- <th>Confirm / Cancel</th> -->
                                            <!-- <th>Payment Status</th> -->
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Payment Mode</th>
                                            <th>Remark</th>
                                            <th>Attachment</th>
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
        <div aria-hidden="true" aria-labelledby="myModalLabel" class="modal fade" id="order-detail-model" role="dialog" tabindex="-1" style="display: none;">
            <div class="modal-dialog">
                <div  class="modal-content">
                    <div  class="modal-header">
                        <button  aria-hidden="true" class="close" data-dismiss="modal" type="button">??</button>
                            <h4  class="modal-title" id="myModalLabel">Hotel Booking Details</h4>
                        </div>
                        <div  class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <h4><p class="text-primary">Hotel Name</p></h4>
                                    <h5><p class="text-muted" >
                                        {{ booking_data.name}}<br><br>
                                        Fax: {{ fax }}<br>
                                        Phone: {{ phone }}

                                    </p></h5>
                                </div>
                                <div class="col-md-3">
                                    <h4><p class="text-primary">Description</p></h4>
                                    <h5>
                                        <p class="text-muted" >
                                            {{ description }}
                                        </p>
                                    </h5>
                                </div>
                                <div class="col-md-3">
                                    <h4><p class="text-primary">Amount</p></h4>
                                    <h5>
                                        <p class="text-muted"> 

                                        ${{orderinfo.total_usd}} </p>
                                    </h5>
                                </div>
                                <div class="col-md-3">
                                    <h4><p class="text-primary">Payment Method</p></h4>
                                    <h5>
                                        <p class="text-muted"> 

                                        {{orderinfo.payment_mode}} </p>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-5">
                                    <h4><span class="text-primary">Check In </span>: <span class="text-muted">{{orderinfo.checkIn}}</span></h4>
                                </div>
                                <div class="col-md-5">
                                    <h4><span class="text-primary">Check Out </span>: <span class="text-muted">{{orderinfo.checkOut}}</span></h4>
                                </div>
                            </div>
                        </div>
                       
                    </div>
                    <div  class="modal-footer hidden">
                        <button class="btn btn-dark waves-effect" data-dismiss="modal" type="button">Cancel</button>
                    </div>
                </div>
            </div>  
        </div>
    </div><!-- content -->
</template>

<script>
    import { apiAdminHost } from'./../../admin-config/config';
    import moment from 'moment';
    import DatePicker from 'vuejs-datepicker';
    import Swal from 'sweetalert2/dist/sweetalert2.js'

    export default {
        
        data() {
            return {
                user_data: [],
                products: [],
                length: 10,
                start: 0,
                orderinfo : {},
                adultArr : {},
                childArr : {},
                booking_data : {},
                total_price : 0,
                total_usd : 0,
                description: '',
                fax: '',
                phone: '',
            }
        },
        mounted() {
            this.codOrders();
        },
        components: {
            DatePicker
        },
        methods: {
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            codOrders(){
                let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    const table = $('#cancelhotel-orders').DataTable({
                        responsive: true,
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        responsive: true,
                        stateSave: false,
                        ordering: false,
                        dom: 'Brtip',
                        buttons: [
                            'pageLength',
                            // 'colvis',
                            // {
                            //     extend: 'excelHtml5',
                            //     title: 'User Order Report',
                            //     exportOptions: {
                            //         columns: ':visible'
                            //     }
                            // },
                            // {
                            //     extend: 'csvHtml5',
                            //     title: 'User Order Report',
                            //     exportOptions: {
                            //         columns: ':visible'
                            //     }
                            // },
                            // {
                            //     extend: 'pdfHtml5',
                            //     title: 'User Order Report',
                            //     exportOptions: {
                            //         columns: ':visible'
                            //     }
                            // },
                            // {
                            //     extend: 'print',
                            //     title: 'User Order Report',
                            //     exportOptions: {
                            //         columns: ':visible'
                            //     }
                            // }
                        ],
                        ajax: {
                            url: apiAdminHost+'/get-hotel-booking',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    frm_date : $('#frm_date').val(),
                                    to_date  : $('#to_date').val(),
                                    user_id  : $('#user_id').val(),
                                    status : 'cancel',
                                };
                                Object.assign(d, params);
                                return d;
                            },
                            headers: {
                              'Authorization': 'Bearer ' + token
                            },
                            dataSrc: function (json) {
                                if (json.code === 200) {
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
                                    return meta.row + meta.settings._iDisplayStart + 1;
                                }
                            },
                            { data: 'order_id' },
                            { data: 'user_id' },
                            { data: 'mobile' },
                           /* { data: 'fullname' },*/
                           /* {
                              render:function(data, type, row, meta){
                                return `  ( ${row.total_amount} )`;
                                // /${row.coupon_code}
                              }

                            },*/
                            { data: 'total_coin' },
                            {
                              render:function(data, type, row, meta){
                                return `$${row.total_usd}`;
                              }

                            },
                            
                            // {
                            //     render: function (data, type, row, meta) {
                                    
                            //         return `<textarea  rows="2" cols="10" class="remark form-control" id="remarks_${row.id}"> </textarea><br>
                            //             <a href="javascript:void(0);" class="btn btn-danger text-danger waves-effect" data-id="${row.id}" id="onRejectClick">Reject</a>` ;
                                    
                            //     }
                            // },
                            
                            // {
                            //     render: function (data, type, row, meta) {
                            //         //<textarea  rows="4" cols="20" name="coupon_code" class="remark form-control" id="remark_${row.id}"> </textarea><br>
                                    
                            //         return `<textarea  rows="2" cols="10" class="remark form-control" id="remarks_${row.id}"> </textarea><br>
                            //             <a href="javascript:void(0);" class="btn btn-primary text-success waves-effect" id="onApproveClick" data-id="${row.id}" data-user-id="${row.user_id}" data-amount=""> Confirm</a> &nbsp;&nbsp;
                            //             <a href="javascript:void(0);" class="btn btn-danger text-danger waves-effect" data-id="${row.id}" id="onRejectClick">Cancel</a>` ;
                            //     }
                            // },
                            {
                                render: function (data, type, row, meta) {
                                    if(row.status == 'confirm')
                                        return '<label class="text-info">'+row.status+'</label>';
                                    else if(row.status == 'cancel')
                                        return '<label class="text-danger">'+row.status+'</label>';
                                    else
                                        return '<label class="text-warning">'+row.status+'</label>';
                                }
                            },
                            
                            // { 
                            //     render: function (data, type, row, meta) {
                            //         if(row.payment_status == 'Success')
                            //             return '<label class="text-info">'+row.payment_status+'</label>';
                            //         if(row.payment_status == 'Failed')
                            //             return '<label class="text-danger">'+row.payment_status+'</label>';
                            //         else
                            //             return '<label class="text-warning">'+row.payment_status+'</label>';
                            //     }
                            // },
                            
                            {
                                render: function (data, type, row, meta) {
                                    if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.entry_time)).format('YYYY-MM-DD');
                                    }
                                }
                            },
                            { data: 'payment_mode' },
                            { data: 'remark' },
                            {                               
                              render: function (data, type, row, meta) {
                                 return '<img width="60",height="60" src="'+row.image+'">';
                              }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    return `<a id="view-detail" data-id="${row.id}">Order Detail</a>&nbsp;
                                            `;
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
                    $('#cancelhotel-orders').on('click', '#view-detail',function(){
                        that.viewOrderDetails($(this).data('id'));
                    });
                    /*  Appprove details */
                    $('#cancelhotel-orders tbody').unbind('click').on('click', '#onApproveClick', function (event){
                        var data = table.row($(this).parents('tr')).data();
                        var id = $(this).data('id');
                        var remark = '';//$('#remark_' + id).val();
                        that.onApproveClick(id,remark);
                    });
                    $('#cancelhotel-orders tbody').on('click', '#onRejectClick', function () {
                        var data = table.row($(this).parents('tr')).data();
                        var id = $(this).data('id');
                        var remark = $('#remarks_' + id).val();
                        that.onRejectClick(id,remark);
                    });
                },0);
            },
            saveCoupon(cart_id){
                 //alert(cart_id);
            },
            onApproveClick(id,remark){

               // var remark = $('#remark').val();
                if(remark == ''){
                    //this.$toaster.error("Enter Coupon Code"); 
                    //return false;
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
                        axios.post('approveBookingRequest', {
                            id: id,
                            remark:remark,
                        }).then(response => {
                            if(response.data.code === 200) {
                                this.$toaster.success(response.data.message);
                               // setTimeout(function(){ location.reload(); }, 300);   
                            $('#cancelhotel-orders').DataTable().ajax.reload();

                          
                            } else {
                                this.$toaster.error(response.data.message);
                                $('#cancelhotel-orders').DataTable().ajax.reload();
                               
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
                        axios.post('reject-booking-request', {                         
                          id:id,
                         remark:remark
                        }).then(response => {
                            if(response.data.code == 200) {
                                this.$toaster.success(response.data.message);
                                 // this.table.ajax.reload();
                                $('#cancelhotel-orders').DataTable().ajax.reload();
                            } else {
                                //$('#cancelhotel-orders').DataTable().ajax.reload();
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
            viewOrderDetails(orderid){
                // $('#order-detail-model').modal();
                // this.$router.push({
                //     name:'view-order-details',
                //     params:{
                //         id: orderid
                //     }
                // });
                //view-oder-details

                axios.post(apiAdminHost+'/view-hotelbooking-details',{
                    order_id: orderid
                }).then(resp => {
                    this.orderinfo = resp.data.data;
                    this.booking_data = resp.data.data.booking_data.hotel;
                    this.description = this.booking_data.description.text.substr(0,150)+"...";
                    this.fax = this.booking_data.contact.fax;
                    this.phone = this.booking_data.contact.phone;
                    // this.total_usd = resp.data.data.records[0]['total_usd'];//sub_total_usd;
                    // this.total_price = resp.data.data.total_price;
                    $('#order-detail-model').modal();
                }).catch(err => {
                    //console.log(err);
                });
            },
            exportToExcel(){
                var params = {status : 'cancel',action:'export',responseType: 'blob' };
                axios.post('get-hotel-booking', params).then(resp => {
                    if (resp.data.code == 200) {
                        //this.export_url = resp.data.data.excel_url;
                        var mystring = resp.data.data.data;
                        var myblob = new Blob([mystring], {
                            type: 'text/plain'
                        });

                        var fileURL = window.URL.createObjectURL(new Blob([myblob]));
                        var fileLink = document.createElement('a');

                        fileLink.href = fileURL;
                        fileLink.setAttribute('download', 'Cancel-Hotel-Booking.xls');
                        document.body.appendChild(fileLink);

                        fileLink.click();
                    }else{    
                        this.$toaster.error(resp.data.message)
                    }
                });
            },
        }
    }
</script>