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
                                    <th class="ForSrNoWidth">Sr.No</th>
                                    <th>Name </th>
                                    <th>Quantity</th>
                                    <th>Country</th>
                                    <th>Mobile</th>
                                    <th>Price</th>
                                    <th>Total USD</th>
                                    <!-- <th>Total Coin</th> -->
                                    <th>Submit Coupon code</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data, i) in orderinfo">
                                    <td>{{i+1}}</td>
                                    <td>
                                   
                                        {{data.name}}
                                    </td>
                                    <td>
                                        {{data.quantity}}
                                    </td>
                                    <td>
                                        {{data.country_name}}
                                    </td>
                                    <td>
                                        {{data.mobile}}
                                    </td>
                                    <td>
                                        {{data.currency_code}} ( {{data.total_price}} )
                                    </td>
                                    <td>$ {{data.sub_total_usd}}</td>
                                    <!-- <td> {{data.sub_total_coin}}</td> -->
                                    <td>
                                       
                                        <textarea  rows="4" cols="20" name="coupon_code" class="remark form-control" :id="'coupon'+i" placeholder=" Use comma if the voucher quantity is more than 1 .
Ex. 2 Amazon vouchers- coupon code - 12345 , 54321">{{data.coupon_data}}</textarea><br>
                                        <button class="btn btn-primary" type="button" @click="saveCoupon(data.cart_id,i)"> Save</button>
                                     </td>
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
                                    <td></td>
                                </tr>
                            </tbody>
                                   
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
    import Swal from 'sweetalert2/dist/sweetalert2.js'

    export default {
        
        data() {
            return {
                user_data: [],
                products: [],
                length: 10,
                start: 0,
                orderinfo : {},
                total_price : 0,
                total_usd : 0,
                total_coin : 0,
            }
        },
        mounted() {
            if(this.$route.params.id){
                this.getviewOrderDetails(this.$route.params.id);
                //alert();
            }
        },
        components: {
            DatePicker
        },
        methods: {
   
            saveCoupon(cart_id,cid){
                
                 let coupon = $.trim($("#coupon"+cid).val());

              
                  Swal({
                    title: 'Are you sure ?',
                    text: "You won't be able to change this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        axios.post('saveCoupon', {
                            cart_id: cart_id,
                            coupon_code: coupon,
                        }).then(response => {
                            if(response.data.code === 200) {
                                this.$toaster.success(response.data.message);
                                                             
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
            onApproveClick(id,remark){

               // var remark = $('#remark').val();
                if(remark == ''){
                    this.$toaster.error("Enter Coupon Code"); 
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
                        axios.post('approveOrderRequest', {
                            id: id,
                            remark:remark,
                        }).then(response => {
                            if(response.data.code === 200) {
                                this.$toaster.success(response.data.message);
                                setTimeout(function(){ location.reload(); }, 300);                             
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
                        axios.post('reject-order-request', {                         
                          id:id,
                         remark:remark
                        }).then(response => {
                            if(response.data.code == 200) {
                                this.$toaster.success(response.data.message);
                                 // this.table.ajax.reload();
                                setTimeout(function(){ location.reload(); }, 300);
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
            getviewOrderDetails(orderid){
                
                axios.post(apiAdminHost+'/view-order-detail',{
                    order_id: orderid
                }).then(resp => {
                    this.orderinfo = resp.data.data.records;
                   // this.total_usd = resp.data.data.records[0]['total_usd'];//sub_total_usd;
                    //this.total_coin = resp.data.data.records[0]['total_coin'];
                     var products=this.orderinfo;
                    for (var i = 0; i < products.length; i++) {
                       this.total_coin=products[i].sub_total_coin;
                       this.total_usd=products[i].sub_total_usd;
                    }
                    //sub_total_usd;
                   // / console.log(this.total_usd);
                    this.total_price = resp.data.data.total_price;
                   // $('#order-detail-model').modal();
                }).catch(err => {
                    //console.log(err);
                });
            },
        }
    }
</script>