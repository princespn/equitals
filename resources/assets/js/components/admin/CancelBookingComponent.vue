<template>
    <!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Cancel Flight Bookings</h4>
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
                                <table id="payumoney-orders" class="table table-striped table-bordered dt-responsive" style="width: 100% !important;">
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
                        <button  aria-hidden="true" class="close" data-dismiss="modal" type="button">×</button>
                        <h4  class="modal-title" id="myModalLabel">Flight Booking Details</h4>
                    </div>
                    <div  class="modal-body">
                        <div class="row">
                            <img :src="fimg" class="text-center">
                            <div class="col-md-12 text-center">
                                <div class="col-md-4">
                                    <h3><strong class="text-success" v-for="(flight, index) in booking_data.itineraries">
                                        {{flight.segments[0].departure.iataCode}}
                                    </strong></h3>                                    
                                </div>
                                <div class="col-md-4">
                                    <p><i class="fa fa-long-arrow-right" style="font-size:36px"></i></p>
                                </div>
                                <div class="col-md-4">
                                    <h3><strong class="text-info" v-for="(flight, index) in booking_data.itineraries">
                                        {{flight.segments[0].arrival.iataCode}}
                                    </strong></h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <h4><p class="text-primary">{{orderinfo.carriers}}</p></h4>
                                    <h5><p class="text-muted" v-for="(flight, index) in booking_data.itineraries">
                                        {{flight.segments[0].carrierCode}} -
                                        {{flight.segments[0].aircraft.code}}
                                    </p></h5>
                                </div>
                                <div class="col-md-3">
                                    <h4><p class="text-primary">Departure</p></h4>
                                    <h5>
                                        <p class="text-muted" v-for="(flight, index) in booking_data.itineraries">
                                            {{flight.segments[0].departure.at|pRemoveTime}}
                                            <span>{{flight.segments[0].departure.at|pRemoveDate}}</span>
                                        </p>
                                    </h5>
                                </div>
                                <div class="col-md-3">
                                    <h4><p class="text-primary">Duration</p></h4>
                                    <h5>
                                        <p class="text-muted" v-for="(flight, index) in booking_data.itineraries"> {{flight.duration|pDuration}} </p>
                                    </h5>
                                </div>
                                <div class="col-md-3">
                                    <h4><p class="text-primary">Arrival</p></h4>
                                    <h5>
                                        <p class="text-muted d-none d-sm-block" v-for="(flight, index) in booking_data.itineraries">
                                            {{flight.segments[0].arrival.at|pRemoveTime}}
                                            <span> {{flight.segments[0].arrival.at|pRemoveDate}}
                                            </span>
                                        </p> 
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="text-center d-flex">
                                    <h4><span class="text-primary">Class </span>: <span class="text-muted">{{orderinfo.travel_class}}</span></h4>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-striped table-bordered" style="margin-top: 16px;">
                                    <thead>
                                        <tr>
                                            <th class="ForSrNoWidth">Adult</th>
                                            <th>Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(data, i) in adultArr">
                                            <td>{{i+1}}</td>
                                            <td>
                                                {{data.name}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-striped table-bordered" style="margin-top: 16px;">
                                    <thead>
                                        <tr>
                                            <th class="ForSrNoWidth">Child</th>
                                            <th>Name </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(data, i) in childArr">
                                            <td>{{i+1}}</td>
                                            <td>
                                                {{data.name}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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
                fimg : '',
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
                    const table = $('#payumoney-orders').DataTable({
                        responsive: true,
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        stateSave: false,
                        ordering: false,
                        dom: 'Brtip',
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
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
                            url: apiAdminHost+'/get-flight-booking',
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
                    $('#payumoney-orders').on('click', '#view-detail',function(){
                        that.viewOrderDetails($(this).data('id'));
                    });
                    /*  Appprove details */
                    $('#payumoney-orders tbody').unbind('click').on('click', '#onApproveClick', function (event){
                        var data = table.row($(this).parents('tr')).data();
                        var id = $(this).data('id');
                        var remark = '';//$('#remark_' + id).val();
                        that.onApproveClick(id,remark);
                    });
                    $('#payumoney-orders tbody').on('click', '#onRejectClick', function () {
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
                            $('#payumoney-orders').DataTable().ajax.reload();

                          
                            } else {
                                this.$toaster.error(response.data.message);
                                $('#payumoney-orders').DataTable().ajax.reload();
                               
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
                                $('#payumoney-orders').DataTable().ajax.reload();
                            } else {
                                //$('#payumoney-orders').DataTable().ajax.reload();
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

                axios.post(apiAdminHost+'/view-booking-details',{
                    order_id: orderid
                }).then(resp => {
                    this.orderinfo = resp.data.data;
                    this.adultArr = resp.data.data.adultArr;
                    this.booking_data = resp.data.data.booking_data;
                    this.childArr = resp.data.data.childArr;
                    this.fimg = resp.data.data.fimg;
                    // this.total_usd = resp.data.data.records[0]['total_usd'];//sub_total_usd;
                    // console.log(this.total_usd);
                    // this.total_price = resp.data.data.total_price;
                    $('#order-detail-model').modal();
                }).catch(err => {
                    //console.log(err);
                });
            },
            exportToExcel(){
                var params = {status : 'cancel',action:'export',responseType: 'blob' };
                axios.post('get-flight-booking', params).then(resp => {
                    if (resp.data.code == 200) {
                        //this.export_url = resp.data.data.excel_url;
                        var mystring = resp.data.data.data;
                        var myblob = new Blob([mystring], {
                            type: 'text/plain'
                        });

                        var fileURL = window.URL.createObjectURL(new Blob([myblob]));
                        var fileLink = document.createElement('a');

                        fileLink.href = fileURL;
                        fileLink.setAttribute('download', 'Cancel-Flight-Booking.xls');
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