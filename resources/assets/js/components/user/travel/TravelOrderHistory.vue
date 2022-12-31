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
                                    <!--   <th>Coin Total </th> -->
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
                                  <!--   <td>WOZ {{order.total_coin.toFixed(2)}} </td> -->
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
                                        <span v-if="order.status == 'confirm'" class="text-success">
                                        {{order.status}}</span>
                                        <span v-else class="text-warning">
                                        {{order.status}}</span>
                                       
                                    </td>
                                    <td><a href="javascript:void(0)" @click="display_order(order.travel_class,order.booking_data,order.adultArr,order.childArr,order.remark,order.carriers,order.fimg)">View</a></td>
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
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Flight Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Flight Booking Details </p>
            <div class="card">
                <div class="card-header">
                  <div class="row align-items-center trip-title">
                    <div class="col-5 col-sm-auto text-center text-sm-left">
                      <h5 class="m-0 trip-place">{{flightInfo.itineraries[0].segments[0].departure.iataCode}}</h5>
                    </div>
                    <div class="col-2 col-sm-auto text-8 text-black-50 text-center trip-arrow">
                      
                    </div>
                    <div class="col-5 col-sm-auto text-center text-sm-left">
                      <h5 class="m-0 trip-place">{{flightInfo.itineraries[0].segments[0].arrival.iataCode}}</h5>
                    </div>
                    <div class="col-12 mt-1 d-block d-md-none"></div>
                    <div class="col-6 col-sm col-md-auto text-3 date">

                    {{flightInfo.lastTicketingDate}}
                </div>
                    <div class="col-6 col-sm col-md-auto text-right order-sm-1">
                     <!--  <a class="text-1" data-toggle="modal" data-target="#fare-rules" href="">Fare Rules</a> -->

                    </div>
                    <div class="col col-md-auto text-center ml-auto order-sm-0"><span class="badge badge-danger py-1 px-2 font-weight-normal text-1"><!-- Non Refundable --></span></div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-sm-3 text-center text-md-left d-lg-flex company-info">
                     <span class="align-middle"><img class="img-fluid" alt="" :src="fimg"> </span> <span class="align-middle ml-lg-2"> <span class="d-block text-2 text-dark mt-1 mt-lg-0">{{carriers}}</span> <small class="text-muted d-block">{{flightInfo.itineraries[0].segments[0].carrierCode}} -
                            {{flightInfo.itineraries[0].segments[0].aircraft.code}}</small> </span>
                    </div>
                    <div class="col-12 col-sm-3 text-center time-info mt-3 mt-sm-0"> <span class="text-5 text-dark">{{flightInfo.itineraries[0].segments[0].departure.at | pRemoveTime}}
                        <p>
                    {{flightInfo.itineraries[0].segments[0].departure.at| pRemoveDate}}
                      </p></span> 
                    <small class="text-muted d-block">{{flightInfo.itineraries[0].segments[0].departure.iataCode}}</small> </div>
                    <div class="col-12 col-sm-3 text-center time-info mt-3 mt-sm-0"> <span class="text-3 text-dark">{{flightInfo.itineraries[0].duration | pDuration}}</span> <small class="text-muted d-block">Duration</small> 

                           <p v-if="flightInfo.itineraries[0].segments[0].numberOfStops==0">
                        Non-Stop
                      </p>
                      <p v-else>Stop- {{flightInfo.itineraries[0].segments[0].numberOfStops}} </p>
                    </div>
                    <div class="col-12 col-sm-3 text-center time-info mt-3 mt-sm-0"> <span class="text-5 text-dark">{{flightInfo.itineraries[0].segments[0].arrival.at|pRemoveTime}}
                        <p>
                   {{flightInfo.itineraries[0].segments[0].arrival.at|pRemoveDate}}</p>
                    </span> <small class="text-muted d-block">{{flightInfo.itineraries[0].segments[0].arrival.iataCode}}</small> </div>
                  </div>
                
                  
                </div>
              </div><!-- Departure Flight Detail end -->
               <h1 class="text-success text-center"> Travel Class :- {{travelClass}} </h1>
             
             <table class="table table-bordered">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">Adult</th>
                  <th scope="col">Full Name</th>
                  </tr>
              </thead>
              <tbody>
                <tr v-for=" (n,k) in adultArr" :key="k"  >
                  <th scope="row">{{k+1}}</th>
                  <td>{{n.name}}</td>
                 
                </tr>
               
              </tbody>
            </table> 


              <table class="table table-bordered" v-if="childArr.length>0">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">Child</th>
                  <th scope="col">Full Name</th>
                  </tr>
              </thead>
              <tbody>
                <tr v-for=" (n,k) in childArr" :key="k"  >
                  <th scope="row">{{k+1}}</th>
                  <td>{{n.name}}</td>
                 
                </tr>

               
              </tbody>
            </table>
            <div class="text-center">
            <h3> Booking Status </h3>
             <h4 v-if="admin_remark !=null"> {{admin_remark}}</h4>
             <h4 v-else> Pending Form Admin Side</h4>
            </div>
          
            
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
            carriers : '',
            fimg : '',
        }
    },
    mounted() {
       this.getOrderHistory();
        this.scrollToTop();
    },
    methods: {
        getOrderHistory(){
            axios.get('orderFlightHistory', {
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
        display_order(travel_class,booking_data,adultArr,childArr,remark,carriers,fimg){
               //return false;
            this.showOrder=true
           

            this.carriers=carriers;
            this.travelClass=travel_class;
            this.admin_remark=remark;
            this.fimg=fimg;

            
             this.flightInfo=booking_data;
             this.adultArr=adultArr;
             this.childArr=childArr;
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