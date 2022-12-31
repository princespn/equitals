<template>
  <div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
      <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h2 class="content-header-title float-left mb-0"> Hotel </h2>
            </div>
          </div>
        </div>
    </div>
          
      <div class="content-body">
    <section>





    <div class="rows banner_book" id="inner-page-title">
      <div class="container">
        <div class="banner_book_1">
          <ul>
            <li class="dl1">Location :{{hotelInof.hotel.address.cityName}} </li>
            <li class="dl2">Price : {{hotelInof.offers[0].price.total}}</li>
           <!--  <li class="dl3">Duration :{{hotelInof.checkIn}} - {{hotelInof.checkOut}}</li> -->
            <li class="dl4"><a href="#">Book Now</a> </li>
          </ul>
        </div>
      </div>
    </div>
  </section>
  <section>


    <div class="rows inn-page-bg com-colo" >
      <div class="container inn-page-con-bg tb-space">
        <div class="col-md-8">

          <!--====== TOUR TITLE ==========-->
          <div class="tour_head">
            <h2><span class="tour_star"><i class="fa fa-star" aria-hidden="true" v-for=" n in parseInt(hotelInof.hotel.rating)" :key="n"></i>
            </span><span class="tour_rat">{{parseInt(hotelInof.hotel.rating)}}</span></h2> </div>
          <!--====== TOUR DESCRIPTION ==========-->
          <div class="tour_head1 hotel-com-color">
            <h3>{{hotelInof.hotel.name}}</h3>
            <p>{{hotelInof.hotel.description.text.slice(0,500)}} ...</p>
          </div>
          <!--====== ROOMS: HOTEL BOOKING ==========-->
    
          <!--====== HOTEL ROOM TYPES ==========-->
         
          <div class="tour_head1 hot-ameni">


             <div class="col-md-12" >

              <img  v-for="(himg,index) in hotelInof.hotel.more_imgs" v-if="index < 3"class="img-thumbnail" :src="himg.uri">
             
          

        
             </div>
             
            
          </div>
           <!--  <div class="loadingFlight text-center" id="loadingFlight">

              <img  src="public/user_files/loadingFlight.gif"> 
           
           </div> -->
          <div class="tour_head1 hot-ameni">
            <h3>Hotel Amenities</h3>
            <ul>
              <li v-for="data in hotelInof.hotel.amenities"><i class="fa fa-check" aria-hidden="true"></i> {{data}}</li>
              
            </ul>
          </div>
          <!--====== TOUR LOCATION ==========-->
        
        </div>
        <div class="col-md-4 tour_r">


             <aside class="mt-lg-0">
            <div class="bg-white shadow-md rounded p-3">
              <h3 class="text-5 mb-3 text-dark">Fare Details</h3>
        <hr class="mx-n3">
              <ul class="list-unstyled">
                <li class="mb-2">Base Fare <span class="float-right text-4 font-weight-500 text-dark">${{ hotelInof.offers[0].price.total}}</span><br>
                  <small class="text-muted">Adult : {{adult}}, Child : {{child}}</small></li>
                <!-- <li class="mb-2">Taxes &amp; Fees <span class="float-right text-4 font-weight-500 text-dark">${{(hotelInof.offers[0].price.total - hotelInof.offers[0].price.base) |ptow}}</span></li> -->
                <li class="mb-2">Insurance <span class="float-right text-4 font-weight-500 text-dark">$0</span></li>
              </ul>
              <div class="text-dark bg-light-4 text-4 font-weight-600 p-3"> Total Amount <span class="float-right text-6">${{hotelInof.offers[0].price.total}}</span> </div>

                     <!-- <h3 style="color:#000;">Or</h3>
               <div class="text-dark bg-light-4 text-4 font-weight-600 p-3"> Total  Coin (WOZ)<span class="float-right text-6">{{hotelInof.offers[0].price.total/coin_rate}} </span> </div> -->

                 

                            
                      <h4 class="text-black">Choose Payment method</h4>

                              <input checked type="radio" value="purchase_wallet" name="payment_mode"> <b>Purchase Wallet</b>
                              <br>
                                <div class="po">
                                      
                               
                                 <!-- <div>
                                   <p class="mbb0">  <input type="radio" value="coin_wallet" name="payment_mode">  <img src="public/user_files/images/1000.png" width="20px"> <b class="text-black"> Coin 
                                    </b></p> 

                                 <br>
                                 
                                 </div> -->
                                </div>
              <button  @click="flightCheckoutFun"class="btn btn-primary btn-block" type="submit">Proceed To Payment</button>
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
              <li>Check In : {{checkIn}}</li>
              <li>Check Out : {{checkOut}}</li>
              <!-- <li>Free Sightseeing &amp; Hotel</li> -->
            </ul>
          </div>
   
         
        </div>
      </div>
    </div>
  </section>

  
      </div>
    </div>

  
  </div>
</template>
<script>
//import { required, email, minLength } from "vuelidate/lib/validators";

  import 'vue-tel-input/dist/vue-tel-input.css';
 // import { required, email, minLength } from "vuelidate/lib/validators";

    import VueTelInput from 'vue-tel-input';
      import Swal from 'sweetalert2';
        export default {  
            components: {
              VueTelInput,
            }, 
            data(){
                return{
                   form: {
                    formlocation: "",
                    },
                        updatepassword: {
                        old_password: '',
                        new_password: '',
                        retype_password: '',
                       
                    },
                     getcountry:'',
                        countryCode:'IN',
                        categories:'',
                        cat_id:'',
                        countryList:'',
                        fromLoc:'',
                        toLoc:'',
                        fromLocData:[],
                        toLocData:[],
                        tripWay:'one',
                        fdata:'',
                        tdata:'',
                        tClass:'',
                        formCityCode:'',
                        toCityCode:'',
                        hotelInof:[],
                        payment_mode:'',
                        coin_rate:'',
                        adult:1,
                        child:0,
                        travelClass:[
                           {name:'Economy',id:'economy'},
                           {name:'Premium Economy',id:'premium_economy'},
                           {name:'Business',id:'business'},
                           {name:'First',id:'first'},
                           
                        ],
                          
                         
                        /* name:'Premium Economy',
                         name:'Business',
                         name:'First',*/

                        
                        
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
            //this.hotelInof =JSON.parse(this.$route.params.data);
           // console.log(this.hotelInof);
           // alert();

               //  $("#loadingFlight").hide()

            if(this.$route.params.data!=''){

           // this.hotelInof =this.$route.params.data;
            //console.log(this.hotelInof);
            this.order_id =this.$route.params.data;

               axios.post('getTempHoteldata', {
                            order_id:this.order_id,
                            
                        }).then(response => {
                        //  $("#loadingFlight").hide()
                        if (response.data.code == 200) {

                                
                             this.hotelInof =response.data.data.booking_data;
                             this.checkIn =response.data.data.checkIn;
                             this.checkOut =response.data.data.checkOut;
                             this.adult =response.data.data.adult;
                             this.child =response.data.data.child;
                            
                             
                           
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

            // $("#loadingFlight").hide()
           
          },
          methods:{

           flightCheckoutFun(){


            Swal({
                          title: 'Are you sure?',
                          text: `You want to book this Hotel`,
                          type: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Yes'
                      }).then((result) => {
                         if (result.value) {


                
               let temp= $('input[name="payment_mode"]:checked').val();
                this.payment_mode=temp;
                 axios.post('hotelCheckout', {
                              payment_mode: this.payment_mode,
                              hotelInof:this.hotelInof,
                              adult:this.adult,
                              child:this.child,
                              checkIn:this.checkIn,
                              checkOut:this.checkOut,
                              order_id:this.order_id,
                             // email:this.email,
                              //mobile:this.mobile,

                            
                        }).then(response => {
                                                    

                           $("#loadingFlight").hide()
                            this.procedToPay = true;

                            if (response.data.code == 200) {
                                
                                    this.$toaster.success(response.data.message);
                                    this.final_total = response.data.data.total_price;
                                   this.$router.push({name: 'hotel-order-history'});
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