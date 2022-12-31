<template>
  <div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
      <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h2 class="content-header-title float-left mb-0">Flight Travel Details</h2>
           
              </div>
          </div>
        </div>

        </div>
      <div class="content-body" style="display: none;">
       <!--  <section id="multiple-column-form">
          <div class="container-fluid">
            <div class="row">
              <ul class="nav nav-tabs border-bottom style-3 justify-content-center" id="myTab" role="tablist">
                <li class="nav-item"> <a class="nav-link active" id="flights-tab" data-toggle="tab" href="#flights" role="tab" aria-controls="flights" aria-selected="true"><span><i class="fas fa-plane"></i></span> Flights</a> 
                </li>
                <li class="nav-item"> <a class="nav-link" id="hotels-tab" data-toggle="tab" href="#hotels" role="tab" aria-controls="hotels" aria-selected="false"><span><i class="fas fa-bed"></i></span> Hotels</a> 
                </li>
              </ul>
              <div class="tab-pane fade active show" id="flights" role="tabpanel" aria-labelledby="flights-tab">
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-body">
                      <div class="row justify-content-center">
                        <div class="col-lg-2">
                          <label>From</label>
                          <input type="text" name="" placeholder="From" class="form-control"> <i class="fa fa-map-marker pps"></i>
                        </div>
                        <div class="col-lg-2">
                          <label>To</label>
                          <input type="text" name="" placeholder="To" class="form-control"> <i class="fa fa-map-marker pps"></i>
                        </div>
                        <div class="col-lg-2">
                          <label>Date</label>
                          <input type="text" name="" placeholder="Date" class="form-control"> <i class="fa fa-calendar pps"></i>
                        </div>
                        <div class="col-lg-2">
                          <label>Return Date</label>
                          <input type="text" name="" placeholder="Return Date" class="form-control"> <i class="fa fa-calendar pps"></i>
                        </div>
                        <div class="col-lg-2">
                          <label>Travellers/Class</label>
                          <select name="cat" v-on:change="getProducts" v-model="cat_id" id="catid" class="form-control mb-1">
                            <option value="" selected="selected">Travellers/Class</option>
                            <option :value="cat.id" v-for="cat in categories">{{cat.name}}</option>
                          </select>
                        </div>
                        <div class="col-lg-2">
                          <label>&nbsp;</label>
                          <button class="btn btn-primary btn-block">Search</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              
              <div class="tab-pane fade" id="hotels" role="tabpanel" aria-labelledby="flights-tab">
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-body">
                      <div class="row justify-content-center">
                        <div class="col-lg-3">
                          <label>Enter City</label>
                          <input type="text" name="" placeholder="From" class="form-control"> <i class="fa fa-map-marker pps"></i>
                        </div>
                        <div class="col-lg-3">
                          <label>Check In</label>
                          <input type="text" name="" placeholder="To" class="form-control"> <i class="fa fa-calendar pps"></i>
                        </div>
                        <div class="col-lg-3">
                          <label>Check Out</label>
                          <input type="text" name="" placeholder="Date" class="form-control"> <i class="fa fa-calendar pps"></i>
                        </div>
                       
                        <div class="col-lg-2">
                          <label>Travellers/Class</label>
                          <select name="cat" v-on:change="getProducts" v-model="cat_id" id="catid" class="form-control mb-1">
                            <option value="" selected="selected">calendar</option>
                            <option :value="cat.id" v-for="cat in categories">{{cat.name}}</option>
                          </select>
                        </div>
                        <div class="col-lg-2">
                          <label>&nbsp;</label>
                          <button class="btn btn-primary btn-block">Search</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section> -->
      </div>

      <div id="content">
    <section class="container">
        <div class="row">
          <div class="col-lg-8">
            <div class="bg-white shadow-md rounded p-3 p-sm-4 confirm-details">
              <h2 class="text-6 mb-3 mb-sm-4 text-dark">Confirm Flight Details</h2>


               <hr class="mx-n3 mx-sm-n4 mb-4">

              <!--  {{flightInfo}} -->
              <!-- Departure Flight Detail
              ============================================= -->
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

                    </div>
                    <div class="col col-md-auto text-center ml-auto order-sm-0"><span class="badge badge-danger py-1 px-2 font-weight-normal text-1"><!-- Non Refundable --></span></div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-sm-3 text-center text-md-left d-lg-flex company-info">
                     <span class="align-middle">
                      <img class="img-fluid" alt="" :src="fimg"> </span> <span class="align-middle ml-lg-2"> <span id="flight1" class="d-block text-2 text-dark mt-1 mt-lg-0">{{flightInfo.itineraries[0].segments[0].carrierCode|pFlightName}}</span> <small class="text-muted d-block">{{flightInfo.itineraries[0].segments[0].carrierCode}} -
                            {{flightInfo.itineraries[0].segments[0].aircraft.code}}</small> </span>
                    </div>
                    <div class="col-12 col-sm-3 text-center time-info mt-3 mt-sm-0"> <span class="text-5 text-dark">{{flightInfo.itineraries[0].segments[0].departure.at|pRemoveTime}}

                      <p>{{flightInfo.itineraries[0].segments[0].departure.at|pRemoveDate}}</p>
                    </span> <small class="text-muted d-block">{{flightInfo.itineraries[0].segments[0].departure.iataCode}}</small> </div>
                    <div class="col-12 col-sm-3 text-center time-info mt-3 mt-sm-0"> <span class="text-3 text-dark"> {{flightInfo.itineraries[0].duration|pDuration}}</span> 

                      <small class="text-muted d-block">Duration
                      <p v-if="flightInfo.itineraries[0].segments[0].numberOfStops==0">
                        Non-Stop
                      </p>
                      <p v-else>Stop- {{flightInfo.itineraries[0].segments[0].numberOfStops}} </p>

                       

                    </small> 
                    </div>
                    <div class="col-12 col-sm-3 text-center time-info mt-3 mt-sm-0"> <span class="text-5 text-dark">
                    {{flightInfo.itineraries[0].segments[0].arrival.at|pRemoveTime}}
                    <p>{{flightInfo.itineraries[0].segments[0].arrival.at|pRemoveDate}}</p>

                 </span> <small class="text-muted d-block">{{flightInfo.itineraries[0].segments[0].arrival.iataCode}}</small> </div>
                  </div>
                  <!-- Fare Rules (Modal Dialog)
                  ============================================= -->
                  <div id="fare-rules" class="modal fade" role="dialog" aria-hidden="true">
                   
                  </div><!-- Fare Rules end -->
                  
                </div>
              </div><!-- Departure Flight Detail end -->

             <!--  {{flightInfo.itineraries[0].segments.length}} -->
              
              <!-- Return Flight Detail
              ============================================= -->
              <!-- <div class="card mt-4" v-if="flightInfo.itineraries[0].segments.length>1"> -->
              <div class="card mt-4" v-if="false">
                <div class="card-header">
                  <div class="row align-items-center trip-title">
                    <div class="col-5 col-sm-auto text-center text-sm-left">
                      <h5 class="m-0 trip-place">{{flightInfo.itineraries[0].segments[1].departure.iataCode}}</h5>
                    </div>
                    <div class="col-2 col-sm-auto text-8 text-black-50 text-center trip-arrow">➝</div>
                    <div class="col-5 col-sm-auto text-center text-sm-left">
                      <h5 class="m-0 trip-place">{{flightInfo.itineraries[0].segments[1].arrival.iataCode}}</h5>
                    </div>
                    <div class="col-12 mt-1 d-block d-md-none"></div>
                    <div class="col-6 col-sm col-md-auto text-3 date">{{flightInfo.itineraries[0].segments[1].departure.at|pRemoveTime}}</div>
                    <div class="col-6 col-sm col-md-auto text-right order-sm-1">
                      <!-- <a class="text-1" data-toggle="modal" data-target="#fare-rules-2" href="">Fare Rules</a> -->
                    </div>
                    <div class="col col-md-auto text-center ml-auto order-sm-0"><span class="badge badge-success py-1 px-2 font-weight-normal text-1"> <!-- Non Refundable --></span></div>
                  </div>
                </div>
                <div class="card-body" >
                  <div class="row">
                    <div class="col-12 col-sm-3 text-center text-md-left d-lg-flex company-info"> <span class="align-middle"><img class="img-fluid" alt="" src=""> </span> <span class="align-middle ml-lg-2"> <span class="d-block text-2 text-dark mt-1 mt-lg-0">{{flightInfo.itineraries[0].segments[1].carrierCode|pFlightName}}</span> <small class="text-muted d-block">{{flightInfo.itineraries[0].segments[1].carrierCode}} -


                            {{flightInfo.itineraries[0].segments[1].aircraft.code}}</small> </span> </div>
                    <div class="col-12 col-sm-3 text-center time-info mt-3 mt-sm-0"> <span class="text-5 text-dark">{{flightInfo.itineraries[0].segments[1].departure.at|pRemoveTime}}
                    <p>
                      {{flightInfo.itineraries[0].segments[1].departure.at|pRemoveDate}}
                    </p>
                    </span> 
                    <small class="text-muted d-block">{{flightInfo.itineraries[0].segments[1].departure.iataCode}}</small> 
                    </div>
                    <div class="col-12 col-sm-3 text-center time-info mt-3 mt-sm-0"> <span class="text-3 text-dark">{{flightInfo.itineraries[0].segments[1].duration|pDuration}}</span> <small class="text-muted d-block">Duration</small> 
                    </div>
                    <div class="col-12 col-sm-3 text-center time-info mt-3 mt-sm-0"> <span class="text-5 text-dark">{{flightInfo.itineraries[0].segments[1].arrival.at|pRemoveTime }}
                      <p>
                        {{flightInfo.itineraries[0].segments[1].arrival.at|pRemoveDate }}
                      </p>

                    </span> <small class="text-muted d-block">{{flightInfo.itineraries[0].segments[1].arrival.iataCode}}</small> </div>
                  </div>
                  <!-- Fare Rules (Modal Dialog)
                  ============================================= -->
                  <div id="fare-rules-2" class="modal fade" role="dialog" aria-hidden="true">
                    <!-- <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Fare Rules</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                        </div>
                        <div class="modal-body">
                          <ul class="nav nav-tabs" id="returnTab" role="tablist">
                            <li class="nav-item"> <a class="nav-link active" id="first-tab" data-toggle="tab" href="#first" role="tab" aria-controls="first" aria-selected="false">Baggage Details</a> </li>
                            <li class="nav-item"> <a class="nav-link" id="second-tab" data-toggle="tab" href="#second" role="tab" aria-controls="second" aria-selected="false">Cancellation Fee</a> </li>
                          </ul>
                          <div class="tab-content my-3" id="returnContent">
                            <div class="tab-pane fade show active" id="first" role="tabpanel" aria-labelledby="first-tab">
                              <div class="table-responsive-md">
                                <table class="table table-hover table-bordered">
                                  <thead>
                                    <tr>
                                      <th>&nbsp;</th>
                                      <td class="text-center">Cabin</td>
                                      <td class="text-center">Check-In</td>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>Adult</td>
                                      <td class="text-center">7 Kg</td>
                                      <td class="text-center">15 Kg</td>
                                    </tr>
                                    <tr>
                                      <td>Child</td>
                                      <td class="text-center">7 Kg</td>
                                      <td class="text-center">15 Kg</td>
                                    </tr>
                                    <tr>
                                      <td>Infant</td>
                                      <td class="text-center">0 Kg</td>
                                      <td class="text-center">0 Kg</td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                            <div class="tab-pane fade" id="second" role="tabpanel" aria-labelledby="second-tab">
                              <table class="table table-hover table-bordered">
                                <thead>
                                  <tr>
                                    <th>&nbsp;</th>
                                    <td class="text-center">Per Passenger Fee</td>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>24 hrs - 365 days</td>
                                    <td class="text-center">$250 + $50</td>
                                  </tr>
                                  <tr>
                                    <td>2-24 hours</td>
                                    <td class="text-center">$350 + $50</td>
                                  </tr>
                                  <tr>
                                    <td>0-2 hours</td>
                                    <td class="text-center">$550 + $50</td>
                                  </tr>
                                </tbody>
                              </table>
                              <p class="font-weight-bold">Terms &amp; Conditions</p>
                              <ul>
                                <li>The penalty is subject to 4 hrs before departure. No Changes are allowed after that.</li>
                                <li>The charges are per passenger per sector.</li>
                                <li>Partial cancellation is not allowed on tickets booked under special discounted fares.</li>
                                <li>In case of no-show or ticket not cancelled within the stipulated time, only statutory taxes are refundable subject to Service Fee.</li>
                                <li>No Baggage Allowance for Infants</li>
                                <li>Airline penalty needs to be reconfirmed prior to any amendments or cancellation.</li>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div> -->
                  </div><!-- Fare Rules end -->
                </div>
              </div><!-- Departure Flight Detail end -->
              
              <div class="alert alert-info mt-4"> <span class="badge badge-info">NOTE:</span> This is a special fare given by the airline. Airlines cancellation charges do apply. </div>
              <h2 class="text-6 mb-3 mt-5 text-info">Traveler Details </h2>
              <p class="font-weight-600">Contact Details</p>
              <div class="form-row">
              <!--   <div class="col-sm-6 form-group">
                  <input class="form-control" v-model="email" placeholder="Enter Email" type="text">
                </div>
                <div class="col-sm-6 form-group">
                  <input class="form-control"  v-model="mobile" placeholder="Enter Mobile Number" type="text">
                </div> -->
              </div>
              <p class="text-info">Your booking details will be sent to this email address and mobile number.</p>
              <p class="font-weight-600">Adult 1</p>

              
              <div class="form-row" v-for=" (n,k) in adultArr" :key="k" >
                <div class="col-sm-2 form-group">
                  Adult {{k+1}}
                </div>
               
                <div class="col-sm-8 form-group">
                  <input class="form-control" v-model="n.name"name="adultName"  required placeholder="Enter Adult  Name" type="text">
                </div>
               
              </div>
              
               <div class="form-row" v-for=" (ch,index) in childArr" :key="index" >
                <div class="col-sm-2 form-group">
                  Child {{index+1}}
                </div>
               
                <div class="col-sm-8 form-group">
                  <input class="form-control" 
                   name="childName" v-model="ch.name" placeholder="Enter Child Name" type="text">
                </div>
              </div>
            </div>
          </div>
          
          <!-- Side Panel (Fare Details)
          ============================================= -->
          <aside class="col-lg-4 mt-4 mt-lg-0">
            
            <div class="bg-white shadow-md rounded p-3">
              <h3 class="text-5 mb-3 text-dark">Fare Details</h3>
        <hr class="mx-n3">
              <ul class="list-unstyled">
                <li class="mb-2">Base Fare <span class="float-right text-4 font-weight-500 text-dark">${{flightInfo.price.base}}</span><br>
                  <small class="text-muted">Adult : {{adult}}, Child : {{child}}</small></li>
                <li class="mb-2">Taxes &amp; Fees <span class="float-right text-4 font-weight-500 text-dark">${{(flightInfo.price.total - flightInfo.price.base) |ptow}}</span></li>
                <li class="mb-2">Insurance <span class="float-right text-4 font-weight-500 text-dark">$0</span></li>
              </ul>
              <div class="text-dark bg-light-4 text-4 font-weight-600 p-3"> Total Amount <span class="float-right text-6">${{flightInfo.price.total}}</span> </div>

                     <!--<h3 style="color:#000;">or</h3>
                <div class="text-dark bg-light-4 text-4 font-weight-600 p-3"> Total  Coin ()<span class="float-right text-6">{{flightInfo.price.total/coin_rate}} </span> </div> -->

                 

                            
                      <h4 class="text-black">Choose Payment method</h4>

                              <input checked type="radio" value="purchase_wallet" name="payment_mode"> <b>Purchase Wallet</b>


                             <!--  <input checked type="radio" value="dexd_wallet" name="payment_mode"> DEXD Wallet -->
                              <br>
                                <div class="po">
                                      
                               
                                 <!-- <div>
                                   <p class="mbb0">  <input type="radio" value="coin_wallet" name="payment_mode">  <img src="public/user_files/images/1000.png" width="20px"> 
                                    <b class="text-black"> Coin 
                                    </b></p> 

                                 <br>
                                 
                                 </div> -->
                                </div>
              <button  @click="flightCheckoutFun"class="btn btn-primary btn-block" type="submit">Proceed To Payment</button>
            </div>
          </aside>
        </div>
    </section>

     


  </div>
    </div>
  </div>
</template>
<script>
  import 'vue-tel-input/dist/vue-tel-input.css';
  import VueTelInput from 'vue-tel-input';
 
    import Swal from 'sweetalert2';
      export default {  
          components: {
            VueTelInput
          }, 
          data(){
              return{
                  updatepassword: {
                      old_password: '',
                      new_password: '',
                      retype_password: '',
                     
                  },

                    adultArr: [],
                    childArr: [],
                    adultName:[],
                    childName:[],
                   flightInfo:'',
                   fimg:'',
                   getcountry:'',
                      countryCode:'IN',
                      categories:'',
                      cat_id:'',
                      countryList:'',
                      coin_rate:2,
                      adult:0,
                      child:0,
          updatepassword:[],
          isDisableBtn:true,
                  ico_arr:[],
                  productsList:''
              }
          },
          computed:{
              isCompletePassword(){
                  return this.updatepassword.old_password && this.updatepassword.new_password && this.updatepassword.retype_password;
              }
          },
          beforeMount(){
            //this.flightInfo =JSON.parse(this.$route.params.data);
           // console.log(this.flightInfo);
           // alert();

            if(this.$route.params.data!=''){

           // this.flightInfo =this.$route.params.data;
            //console.log(this.flightInfo);
            this.order_id =this.$route.params.data;

               axios.post('getTempFlightUdata', {
                            order_id:this.order_id,
                            
                        }).then(response => {
                        if (response.data.code == 200) {
                                
                             this.flightInfo =response.data.data.booking_data;
                            
                             this.adult = response.data.data.adult;
                             this.fimg = response.data.data.fimg;

                              for (var i =0; this.adult > i;  i++) {
                                let t= {name:''};

                                 this.adultArr.push(t);

                               
                             }
                             this.child = response.data.data.child;

                              for (var i =0; this.child > i;  i++) {
                                let t= {name:''};

                                 this.childArr.push(t);

                               
                             }
                           
                             this.coin_rate = response.data.data.coin_rate;
                                   
                                
                            }else{

                           this.$toaster.error(response.data.message);

                            }
                        })
                        .catch(error => {
                        });
                }
          },
          mounted(){
           if(this.$route.params.data!=''){

            }
           
          },
          methods:{

           flightCheckoutFun(){

               let  carriers= $("#flight1").html();

             
            Swal({
                          title: 'Are you sure?',
                          text: `You want to book this flight`,
                          type: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Yes'
                      }).then((result) => {
                         if (result.value) {
          

                let temp= $('input[name="payment_mode"]:checked').val();
                this.payment_mode=temp;
                 axios.post('flightCheckout', {
                              payment_mode: this.payment_mode,
                              flightInfo:this.flightInfo,
                              adult:this.adult,
                              child:this.child,
                              fimg:this.fimg,
                              adultArr:this.adultArr,
                              childArr:this.childArr,
                              order_id:this.order_id,
                              carriers:carriers,
                             // email:this.email,
                              //mobile:this.mobile,

                            
                        }).then(response => {
                                                    this.procedToPay = true;

                            if (response.data.code == 200) {
                                
                                    this.$toaster.success(response.data.message);
                                    this.final_total = response.data.data.total_price;
                                   this.$router.push({name: 'travel-order-history'});
                                   /* setTimeout(function () {
                                       location.reload()
                                    }, 500);*/
                                
                            }else{

                           this.$toaster.error(response.data.message);

                            }
                        })
                        .catch(error => {
                        });
            
                          
                                                 
                          }
                      });
               
              }

            
  
            
   
          }
    }
</script>