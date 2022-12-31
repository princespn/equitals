<template>
     <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="header-navbar-shadow"></div>
      <div class="content-wrapper">
        <div class="content-header row">
          <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
              <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Order Details </h2>
          
              </div>
            </div>
          </div>
        
        </div>
        <div class="content-body">
              <div class="table-responsive">
                        <table class="table table-bordered" >
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Product Name</th>
                                    <!-- <th>country</th> -->
                                    <th>Image</th>
                                    <th>Mobile</th>
                                    <!-- <th>Coupon Code</th> -->
                                   <!--  <th>remark</th> -->
                                   <!--  <th>Total </th> -->
                                    <!-- <th>Final Total </th> -->
                                      <th>Price Total </th>
                                      <th>Total USD </th>
                                     <!--  <th> Total Coin </th> -->
                                  <!--   <th>Payment Status</th> -->
                                    <!-- <th>Delivery Status</th> -->
                                    <!-- <th>Date</th>
                                    <th>Action</th> -->
                                    <!-- <th>
                                        Invoice
                                    </th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(order,index) in orderdata.products">
                                    <td>{{index+1}}</td>
                                    <td>{{order.name}}</td>
                                    <!--  <td>
                                        {{order.country_name}}
                                       </td> -->
                                    <td> <img style="max-width: 10%;" :src="order.images[0]"></td>
                                    <td>{{order.mobile}}</td>
                                  

                                
                                   <!--  <td>{{order.remark}}</td> -->
                                      
                                  {{order.currency_code}} ( {{order.total_price|ptow}} ) </td>
                                    <td> $ {{order.sub_total_usd}} </td>
                                    <!-- <td> {{order.sub_total_coin}} </td> -->
                             
                                   <!--  <td>{{order.entry_time | moment("DD/MM/YYYY")}}</td> -->
                                    <!-- <td>
                                        <span v-if="order.status == 'Delivered'" class="text-success">
                                        {{order.status}}</span>
                                        <span v-else class="text-warning">
                                        {{order.status}}</span>
                                       
                                    </td> -->
                                    
                                  
                                </tr>
                            </tbody>
                        </table>
                        <!-- <p v-if="this.orderdata.length == 0">
                            <span>
                                You have not ordered anything
                            </span>
                        </p> -->
                    </div>
      </div>

        <div class="content-body">
           <h2>Order details</h2>
                                <div class="panel-body odd-det">
                                    <p>Order ID : {{orderdata.order_id}}</p>
                                    <!-- <p>Total Amount : <i class="fa fa-inr"></i> {{orderdata.total_price}} </p> -->
                                    <!-- <p>Coupon Amount : <i class="fa fa-inr"></i> {{orderdata.coupon_amount}} </p> -->
                                    <p>Final USD : <i class=""></i> $ {{total_usd}} </p>
                                   <!--  <p>Final Coin : <i class=""></i> {{total_coin}} </p> -->
                                    <p>Ordered date : {{orderdata.order_date}}</p>
                                    <p>Delivery status : {{orderdata.delivery_status}}</p> 
                                    <p>Payment Mode : {{orderdata.payment_mode}}</p> 
                                    <p>Remark : {{orderdata.remark}}</p>
                                  <!--   <p>Coupon Code : {{orderdata.coupon_list}}</p> -->
                                    <!-- <p>Payment mode : {{orderdata.payment_mode}}</p>
 -->                                </div>
        </div>
      </div>
    </div>
</template>




<script>
    export default {
    data() {
        return {
            orderdata : {},
            token : null,
            total_usd:0,
            total_coin:0,
        }
    },
    mounted() {
        this.token = localStorage.getItem('user-token');
        this.getOrderDetails();
        this.scrollToTop();
    },
    methods: {
        getOrderDetails(){
            axios.post('get-order-details', {
                'order_id' : this.$route.params.id
            }).then(response => {
                if(response.data.code  == 200){
                    this.orderdata = response.data.data;
                    var products=this.orderdata.products;
                    for (var i = 0; i < products.length; i++) {
                       this.total_coin=this.total_coin+products[i].sub_total_coin;
                       this.total_usd=this.total_usd+products[i].sub_total_usd;
                    }
                } else {
                    this.$toaster.error(response.data.message);
                    this.$router.push({ 
                        name:'order-history',
                    });
                }
            })
            .catch(error => {
            });   
        },
        
        scrollToTop() {
            window.scrollTo(0,0);
        }
    }
}
</script>
