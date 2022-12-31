<template>
    <!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Orders</h4>
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
                                                <input class="form-control" required="" placeholder="Enter user id" type="text" id="user_id" f>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="text-center">
                                                    <button type="button" class="btn btn-primary waves-effect waves-light" id="onSearchClick">Search</button>
                                                   <button type="button" class="btn btn-info waves-effect waves-light" @click="exportToExcel">Export To Excel</button>
                                                    <button type="button" class="btn btn-dark waves-effect waves-light mt-4" id="onResetClick">Reset</button>
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
                                <table id="payumoney-orders" class="table table-striped table-bordered dt-responsive" style="width: 100% !important;">
                                    <thead>
                                        <tr>
                                            <th>Sr.No1</th>
                                            <th>Order Id</th>
                                            <th>User Id</th>
                                            <th>Full Name</th>
                                            <th>Mobile</th>
                                            <th>Paid in USD</th>
                                            <!-- <th>Paid in Coin</th> -->
                                            <!-- <th>Discount</th>
                                            <th>Paid Price</th>-->
                                            <th>Payment Mode</th> 
                                            <th>Payment Status</th>
                                            <th>Remark</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    
                                    <tfoot>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Order Id</th>
                                            <th>User Id</th>
                                            <th>Full Name</th>
                                            <th>Mobile</th>
                                            <th>Paid in USD</th>
                                            <!-- <th>Paid in Coin</th> -->
                                            <!-- <th>Discouny</th>
                                            <th>Paid Price</th> -->
                                            <th>Payment Mode</th>
                                             <th>Remark</th>
                                            <th>Payment Status</th>
                                            <th>Status</th>
                                            <th>Date</th>
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
                        <h4  class="modal-title" id="myModalLabel">Order Details</h4>
                    </div>
                    <div  class="modal-body">
                         <table class="table table-striped table-bordered" style="margin-top: 16px;">
                            <thead>
                                <tr>
                                    <th class="ForSrNoWidth">Sr.No</th>
                                    <th>Name </th>
                                    <th>Quantity</th>
                                    <th>mobile</th>
                                    <th>Country</th>
                                    <th>Price</th>
                                    <th>Total USD</th>
                                    <!-- <th>Total Coin</th> -->
                                   <!--  <th>Coupon Code</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data, i) in orderinfo">
                                    <td>{{i+1}}</td>
                                    <td>{{data.name}} </td>
                                    <td>{{data.quantity}}</td>
                                    <td>{{data.mobile}}</td>
                                   <td> {{data.country_name}}</td>
                                        
                                   
                                  
                                    <td>
                                        {{data.currency_code}} ( {{data.total_price}} )
                                    </td>
                                    <td>$ {{data.sub_total_usd}}</td>
                                    <!-- <td> {{data.sub_total_coin}}</td> -->
                                  <!--   <td>
                                        <textarea  rows="4" cols="20" name="coupon_code" class="remark form-control" id=""> </textarea><br>

                                        {{data.coupon_data}}
                                        <button class="btn btn-primary text-success waves-effect"  @clcik="saveCoupon(1)"> Save</button>
                                     </td> -->
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>$ {{total_usd}}</td>
                                    <!-- <td> {{total_coin}}</td> -->
                                   <!--  <td></td> -->
                                </tr>
                            </tbody>
                        </table>
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

    export default {
        
        data() {
            return {
                user_data: [],
                products: [],
                length: 10,
                start: 0,
                orderinfo : {},
                total_price : 0,
                total_usd:0,
                total_coin:0,
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
                            {
                                extend: 'excelHtml5',
                                title: 'User Order Report',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'csvHtml5',
                                title: 'User Order Report',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'pdfHtml5',
                                title: 'User Order Report',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            // {
                            //     extend: 'print',
                            //     title: 'User Order Report',
                            //     exportOptions: {
                            //         columns: ':visible'
                            //     }
                            // }
                        ],
                        ajax: {
                            url: apiAdminHost+'/user-orders',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    frm_date : $('#frm_date').val(),
                                    to_date  : $('#to_date').val(),
                                    user_id  : $('#user_id').val(),
                                    status : 'delivered',
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
                            { data: 'fullname' },
                            { data: 'mobile' },
                              {
                                render: function (data, type, row, meta) {
                                    return `$ ${row.total_usd}`;
                                }
                            },
                            // {
                            //     render: function (data, type, row, meta) {
                            //         return `$ ${row.total_coin}`;
                            //     }
                            // },
                            
                           /* { data: 'discount' },
                            { data: 'total_price' },*/
                            { data: 'payment_mode' },
                            { data: 'remark' },
                            { 
                                render: function (data, type, row, meta) {
                                    if(row.payment_status == 'Success')
                                        return '<label class="text-info">'+row.payment_status+'</label>';
                                    if(row.payment_status == 'Failed')
                                        return '<label class="text-danger">'+row.payment_status+'</label>';
                                    else
                                        return '<label class="text-warning">'+row.payment_status+'</label>';
                                }
                            },

                           /* { data: 'coupon_code' },*/
                            {
                                render: function (data, type, row, meta) {
                                    if(row.status == 'Confirm')
                                        return '<label class="text-info">'+row.status+'</label>';
                                    if(row.status == 'Cancel')
                                        return '<label class="text-danger">'+row.status+'</label>';
                                    else
                                        return '<label class="text-warning">'+row.status+'</label>';
                                }
                            },
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
                                    return `<a id="view-detail" data-id="${row.id}">Order Detail</a>&nbsp;`;
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
                },0);
            },
            viewOrderDetails(orderid){
                axios.post(apiAdminHost+'/view-order-detail',{
                    order_id: orderid
                }).then(resp => {
                    this.orderinfo = resp.data.data.records;
                     //this.total_usd = resp.data.data.records[0]['total_usd'];
                     //this.total_coin = resp.data.data.records[0]['total_coin'];
                      var products=this.orderinfo;
                    for (var i = 0; i < products.length; i++) {
                       this.total_coin=this.total_coin+products[i].sub_total_coin;
                       this.total_usd=this.total_usd+products[i].sub_total_usd;
                    }

                    this.total_price = resp.data.data.total_price;
                    $('#order-detail-model').modal();
                }).catch(err => {
                    //console.log(err);
                });
            },
            exportToExcel(){
                var params = {status : 'delivered',action:'export',responseType: 'blob' };
                axios.post('user-orders', params).then(resp => {
                    if (resp.data.code == 200) {
                        //this.export_url = resp.data.data.excel_url;
                        var mystring = resp.data.data.data;
                        var myblob = new Blob([mystring], {
                            type: 'text/plain'
                        });

                        var fileURL = window.URL.createObjectURL(new Blob([myblob]));
                        var fileLink = document.createElement('a');

                        fileLink.href = fileURL;
                        fileLink.setAttribute('download', 'orders-report.xls');
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