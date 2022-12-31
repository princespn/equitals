<template>
     <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="header-navbar-shadow"></div>
      <div class="content-wrapper">
        <div class="content-header row">
          <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
              <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Order History </h2>
          
              </div>
            </div>
          </div>
        
        </div>
        <div class="content-body">
              <div class="table-responsive">
                        <table class="table table-bordered" v-if="orderhistory.length > 0">
                            <thead>
                                <tr>
                                    <th>Order Id</th>
                                    <!-- <th>Product Name</th> -->
                                  <!--   <th>Email</th> -->
                                    <th>Date</th>
                                   <!--  <th>Total </th> -->
                                    <!-- <th>Final Total </th> -->
                                      <th>USD Total </th>
                                      <!-- <th>Coin Total </th> -->
                                    <th>Payment Mode</th>
                                    <th>Delivery Status</th>
                                    <th>Action</th>
                                    <!-- <th>
                                        Invoice
                                    </th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="order in orderhistory">
                                    <td>{{order.order_id}}</td>
                                    <!-- <th>{{order.product_name}}</th> -->
                                    <!-- <td>{{order.email}}</td> -->
                                    <td>{{order.entry_time | moment("DD/MM/YYYY")}}</td>
                                    <!-- <td>{{order.total_price.toFixed(2)}} </td> -->
                                    <td>$ {{order.total_usd|ptow}} </td>
                                    <!-- <td> {{order.total_coin.toFixed(2)}} </td> -->
                                    <td>{{order.payment_mode}}</td>
                                   <!--  <td>{{(order.total_price - order.coupon_amount).toFixed(2)}} </td> -->
                             <!--        <td>
                                        <span v-if="order.payment_status == 'Success'" class="text-success">
                                        {{order.payment_status}}</span>
                                        <span v-if="order.payment_status == 'Pending'" class="text-warning">{{order.payment_status}}</span>
                                        <span v-if="order.payment_status == 'Refund'" class="text-warning">
                                        {{order.payment_status}}</span>
                                        <span v-if="order.payment_status == 'Failed'" class="text-danger">
                                        {{order.payment_status}}</span>
                                    </td> -->
                                    <td>
                                        <span v-if="order.status == 'Delivered'" class="text-success">
                                        {{order.status}}</span>
                                        <span v-else class="text-warning">
                                        {{order.status}}</span>
                                       
                                    </td>
                                    <td><a href="javascript:void(0)" @click="display_order(order.id)">View</a></td>
                                    <!-- <td>
                                        <router-link v-if="order.status == 'Confirm'" tag="a" :to="{ name:'invoice',params: { invoice_id: order.id }}" > Invoice
                                        </router-link>
                                        <span v-if="order.status != 'Confirm'">
                                            -
                                        </span>
                                    </td> -->
                                </tr>
                            </tbody>
                        </table>
                        <p v-if="this.orderhistory.length == 0">
                            <span>
                                You have not ordered anything
                            </span>
                        </p>
                    </div>
      </div>
    </div>
    </div>
</template>



<script>
export default {
    data() {
        return {
            orderhistory : {},
        }
    },
    mounted() {
       this.getOrderHistory();
        this.scrollToTop();
    },
    methods: {
        getOrderHistory(){
            axios.get('get-orders', {
            }).then(response => {
                if(response.data.code  == 200){
                    this.orderhistory = response.data.data.records;
                }
                else {
                    this.orderhistory = [];
                }
            })
            .catch(error => {
            });   
        },
        display_order(order_id){
            this.$router.push({
                name: 'order-details',
                params: {
                    id: order_id,
                }
            });
        },
        scrollToTop() {
                window.scrollTo(0,0);
           }
    }
}
</script>