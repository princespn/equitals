<template>
     <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="header-navbar-shadow"></div>
      <div class="content-wrapper">
        <div class="content-header row">
          <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
              <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Hotel Order History </h2>
          
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
                                    <!--   <th>Coin Total </th> -->
                                    <th>Download</th>
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
                                    <td>$ {{order.total_usd.toFixed(2)}} </td>
                                    <!-- <td>WOZ {{order.total_coin.toFixed(2)}} </td> -->
                                    <td><p v-if="order.img!=null">
                                        <button @click="showimg(order.img)">Show</button>
                                      </p>
                                         <p v-else> -- </p>
                                    
                                    </td>
                                    <td>{{order.payment_mode}}

                                      


                                    </td>
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
                                        <span v-if="order.status == 'confirm'" class="text-success">
                                        {{order.status}}</span>
                                        <span v-else class="text-warning">
                                        {{order.status}}</span>
                                       
                                    </td>
                                    <td><a href="javascript:void(0)" @click="display_order(order.booking_data,order.remark,order.total_coin,order.payment_mode,order.adult,order.child,order.rating,order.total_usd,order.checkIn,order.checkOut)">View</a></td>
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



<div v-if="showOrder" class="modal fade bd-example-modal-lg" id="orderdetails" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Hotel Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

         <section>
    <div class="rows inn-page-bg com-colo" >
      <div class="container inn-page-con-bg tb-space">
        <div class="col-md-7">
          <!--====== TOUR TITLE ==========-->
          <div class="tour_head">
            <h2><span class="tour_star">
              <i class="fa fa-star" aria-hidden="true" v-for="n in parseInt(hotelInof.hotel.rating)" :key="n"></i>
            </span>
            <span class="tour_rat">{{parseInt(hotelInof.hotel.rating)}}</span></h2> </div>
          <!--====== TOUR DESCRIPTION ==========-->
          <div class="tour_head1 hotel-com-color">
            <h3>{{hotelInof.hotel.name}}</h3>
            <p>{{hotelInof.hotel.description.text.slice(0, 200)}} ...</p>
          </div>
          <!--====== ROOMS: HOTEL BOOKING ==========-->
    
          <!--====== HOTEL ROOM TYPES ==========-->
         
          <div class="tour_head1 hot-ameni">
             <div class="col-md-12" >

               <img  v-for="(himg,index) in hotelInof.hotel.more_imgs" v-if="index < 3"class="img-thumbnail" :src="himg.uri">

             
            

        
             </div>
          </div><div class="tour_head1 hot-ameni">
            <h3>Hotel Amenities</h3>
            <ul>
              <li v-for="data in hotelInof.hotel.amenities"><i class="fa fa-check" aria-hidden="true"></i> {{data}}</li>
              
            </ul>
          </div>
          <!--====== TOUR LOCATION ==========-->
        
        </div>
        <div class="col-md-5 tour_r">


             <aside class="mt-lg-0">
            <div class="bg-white shadow-md rounded p-3">
              <h3 class="text-5 mb-3 text-dark">Fare Details</h3>
        <hr class="mx-n3">
              <ul class="list-unstyled">
                <li class="mb-2">Base Fare <span class="float-right text-4 font-weight-500 text-dark">${{ hotelInof.offers[0].price.total}}</span><br>
                  <small class="text-muted">Adult : {{adult}}, Child : {{child}}</small></li>
               <!--  <li class="mb-2">Taxes &amp; Fees <span class="float-right text-4 font-weight-500 text-dark">${{(hotelInof.offers[0].price.total - hotelInof.offers[0].price.base) |ptow}}</span></li> -->
                <li class="mb-2">Insurance <span class="float-right text-4 font-weight-500 text-dark">$0</span></li>
              </ul>
              <div class="text-dark bg-light-4 text-4 font-weight-600 p-3"> Total Amount <span class="float-right text-6">${{hotelInof.offers[0].price.total}}</span> </div>

                  <!--    <h3 style="color:#000;">Or</h3>
               <div class="text-dark bg-light-4 text-4 font-weight-600 p-3"> Total  Coin<span class="float-right text-6">{{total_coin}} </span> </div> -->

                 

                            
                      <h4 class="text-black">Payment method</h4>
                              <div v-if="payment_mode=='purchase_wallet'">
                              <input checked type="radio" value="purchase_wallet" name="payment_mode"> <b>Purchase Wallet</b>
                              <br>
                            </div>
                                <!-- <div v-else class="po">
                                      
                               
                                 <div>
                                   <p class="mbb0">  <input checked type="radio" value="coin_wallet" name="payment_mode">  <img src="public/user_files/images/1000.png" width="20px"> <b class="text-black"> Coin 
                                    </b></p> 

                                 <br>
                                 
                                 </div>
                                </div> -->
             
            </div>
          </aside>
          <!--====== SPECIAL OFFERS ==========-->
          <!-- <div class="tour_right tour_offer">
            <div class="band1"><img src="images/offer.png" alt=""> </div>
            <p>Special Offer</p>
            <h4>${{hotelInof.offers[0].price.total}}<span class="n-td">
                <span class="n-td-1">${{hotelInof.offers[0].price.}}<</span>
                </span>
              </h4> <a href="booking.html" class="link-btn">Book Now</a>

            </div> -->
          <!--====== TRIP INFORMATION ==========-->
          <div class="tour_right tour_incl tour-ri-com">
            <h3>Trip Information</h3>
            <ul>
              <li>Location : {{hotelInof.hotel.address.cityName}}</li>
              <li>Check In Date: {{checkIn}}</li>
              <li>Check Out Date: {{checkOut}}</li>
              <!-- <li>Free Sightseeing &amp; Hotel</li> -->
            </ul>
          </div>

            <div class="text-center ">
            <h3 class="text-danger"> Booking Status </h3>
             <h4 class="text-success" v-if="admin_remark !=null"> {{admin_remark}}</h4>
             <h4 class="text-success" v-else> Pending Form Admin Side</h4>
            </div>
   
         
        </div>
      </div>
    </div>
  </section>
        
          
          
            
      </div>
      <div class="modal-footer">
       
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
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
            flightInfo : {},
            showOrder : false,
            childArr : [],
            adultArr : [],
            travelClass : '',
            admin_remark : '',
            total_coin : '',
            payment_mode : '',
            adult : '',
            child : '',
            rating : '',
            checkIn : '',
            checkOut : '',
            ticket:'',
        }
    },
    mounted() {
       this.getOrderHistory();
        this.scrollToTop();
    },
    methods: {

      showimg(img){

          //setTimeout(function(){  $("#orderdetails").modal('show');}, 300);


      },
        getOrderHistory(){
            axios.get('orderHotelHistory', {
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
        display_order(booking_data,remark,total_coin,payment_mode,adult,child,rating,total_usd,checkIn,checkOut){
               //,return false;
            this.showOrder=true
            ///localhost.setItem(JSON.stringify(carriers));

             this.hotelInof=booking_data;
             this.total_coin=total_coin;
             this.total_usd=total_usd;
             this.payment_mode=payment_mode;
             this.admin_remark=remark;
             this.adult=adult;
             this.child=child;
             this.rating=rating;
             this.checkIn=checkIn;
             this.checkOut=checkOut;
           
             setTimeout(function(){  $("#orderdetails").modal('show');}, 300);


           


            /*this.$router.push({
                name: 'order-details',
                params: {
                    id: order_id,
                }
            });*/
        },
        scrollToTop() {
                window.scrollTo(0,0);
           }
    }
}
</script>