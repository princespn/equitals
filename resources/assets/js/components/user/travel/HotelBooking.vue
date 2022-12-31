<style type="text/css">
  
  .text-bold1{

        color: #000000;
  }
</style>

<template>
  <div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">


          
      <div class="content-body">
        <section id="multiple-column-form">
          <div class="container-fluid">
            <div class="row">
              <div class="col-xl-12 col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Book Hotel</h4>
                  </div>
                  <div class="card-body">
                    <ul class="nav nav-tabs justify-content-center" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link" id="service-tab-center"  href="#/travel" role="tab" aria-selected="true"> <span>
                 <i class="fas fa-plane"></i></span>Flight Booking</a>
                      </li>
                      <li class="nav-item"> <a class="nav-link active" id="home-tab-center" href="#/hotel" aria-controls="home-center" role="tab" aria-selected="false"><span>
                  <i class="fas fa-hotel"></i></span>Hotel Booking </a>
                      </li>
                    </ul>
                    <div class="tab-content">

                     
                     
                        <div class="row justify-content-center pp">
                          <div class="col-lg-2">
                            <label>Enter City</label>
                           <input autocomplete="off" type="text" name="fromLoc" placeholder="From" class="form-control" v-model="fromLoc" @keyup="doSearch($event)" > <i class="fa fa-map-marker pps"></i>
                             <span v-show="errors.has('fromLoc')">{{ errors.first('fromLoc') }}</span>

                             <div v-if="fromLocData.length > 0">
                               
                              <ul class="ul-bg">
                              <li v-for="city in fromLocData" @click="setFromLoc(city.address.cityName,city.address.cityCode)">
                               {{city.address.cityName}}</li>
                              
                              </ul>
                            </div>
                          </div>
                          <div class="col-lg-2">
                            <label>Check In</label>
                            <input type="date" name="checkIn" id="checkIn" v-model="checkIn"placeholder="To" class="form-control" @click="clickDate"> 
                          </div>
                          <div class="col-lg-2">
                            <label>Check Out</label>
                            <input type="date" name="checkOut"  id="checkOut" v-model="checkOut" placeholder="Date" class="form-control">
                          </div>
                          <div class="col-lg-2">
                            <label>Rooms(Max 8)</label>
                            <select name="rooms" v-model="rooms" id="rooms" class="form-control mb-1">
                              <option selected>Select Rooms</option>
                              
                              <option :value="n" v-for="n in 9">{{n}}</option>
                            </select>
                          </div>

                          <div class="col-lg-1">
                            <label>Adult</label>
                            <select v-model="adult"class="form-control">
                              
                               <option selected value="1">1</option>
                               <option value="2">2</option>
                               <option value="3">3</option>
                               <option value="4">4</option>
                               <option value="5">5</option>
                               <option value="6">6</option>
                               <option value="7">7</option>
                               <option value="8">8</option>
                               <option value="9">9</option>
                            </select>
                          </div>
                          <div class="col-lg-1">
                            <label>Child</label>
                            <select v-model="child"class="form-control">
                              
                               <option selected value="0">0</option>
                               <option value="1">1</option>
                               <option value="2">2</option>
                               <option value="3">3</option>
                               <option value="4">4</option>
                               <option value="5">5</option>
                              
                            </select>
                          </div>
                          <div class="col-lg-2">
                            <label>&nbsp;</label>
                            <button @click="searchHotel"class="btn btn-primary btn-block p11">Search</button>
                             <!-- {{totalHotels}}weqkjwh -->
                          </div>
                        </div>
                    
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
               

            <div class="col-md-12 hot-page2-alp-con-right" v-if="totalHotels==0">
            <div class="hot-page2-alp-con-right-1">
              
            
                 <h1 class="text-center text-dark">Hotel Not Found</h1>
             
            </div>

          </div>
           <div class="col-md-12 hot-page2-alp-con-right" v-if="totalHotels==null">
            <div class="hot-page2-alp-con-right-1">
                             <h1 class="text-center text-dark">Please Search Hotel with City Name </h1>
            
            </div>

          </div>

           <div class="loadingFlight text-center" id="loadingFlight">


        <img  src="public/user_files/loadingFlight.gif"> 
       <!--  <h2 class="text-dark">We Are Searching</h2> -->
        

      </div>

        <div class="container-fluid">
     
      <div class="row">
        <div class="hot-page2-alp-con row">


            
          <!--LEFT LISTINGS-->
          <div class="col-md-2 hot-page2-alp-con-left">
            <!--PART 1 : LEFT LISTINGS-->
          
            <!--PART 2 : LEFT LISTINGS-->
           
            <!--PART 7 : LEFT LISTINGS-->
          

       
          </div>
          <!--END LEFT LISTINGS-->
          <!--RIGHT LISTINGS-->

<!-- 
          <div class="hot-page2-alp-r-list" v-for="data in searchHotelData">

              <span v-for="img in data.hotel.media">

             {{img.uri}}  


              </span>
                <span v-for="h in data.hotel">
               {{h.description}}
                </span>
                <span class="hot-list-p3-2">${{data.offers[0].price.total}}</span>
        
         </div> -->
         
          <div class="col-md-9 hot-page2-alp-con-right" v-if="searchHotelData.length>0">
          <h1 class="text-center" style="color: #242a46 !important"> Hotel Found  {{totalHotels}}</h1>
            <div class="hot-page2-alp-con-right-1">
              <div class="row">
                <div class="hot-page2-alp-r-list" v-for="data in searchHotelData">
                  <div class="col-md-3 hot-page2-alp-r-list-re-sp">
                    <a href="javascript:void(0);">
                      <div class="hotel-list-score">{{data.hotel.rating}}</div>
                     
                     
                      <div class="hot-page2-hli-1">
                        <span v-for="img in data.hotel.media">

                      <img :src="img.uri" alt=""> 
                      </span>

                      </div>
                      <div class="hom-hot-av-tic hom-hot-av-tic-list"> Available Rooms </div>
                    </a>
                  </div>
                  <div class="col-md-6">
                    <div class="hot-page2-alp-ri-p2"> <a href="#"><h3>{{data.hotel.name}}</h3></a>
                      <ul>
                        <li v-for="(des,index) in data.hotel.description">

                          

                          <h5 v-if="index!='lang'">

                             <a href="#"> {{des.slice(0,200)}} ...</a>
                                                       </h5>

                        </li>
                        <li> Phone :- {{data.hotel.contact.phone}}</li>
                        <li> Fax :- {{data.hotel.contact.phone}}</li>
                        <li> location :- {{data.hotel.address.cityName}}</li>
                      <!--   <li> Fax :- {{data.hotel.contact.phone}}</li> -->
                      </ul>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="hot-page2-alp-ri-p3">
                      <div class="hot-page2-alp-r-hot-page-rat">25%Off</div> 

                      <span class="hot-list-p3-1">
                       <h5> checkIn {{data.offers[0].checkInDate}}</h5>
                       </span> 
                      <span class="hot-list-p3-1">
                       <h5>checkOut {{data.offers[0].checkOutDate}} </h5></span>
                      <span class="hot-list-p3-2"> <h5 class="prht">${{data.offers[0].price.total}}  </h5></span>
                      <span class="hot-list-p3-1 ">
                        <button class="hot-page2-alp-quot-btn" @click="saveHotelTempInfo(data)">Book Now</button>
                      </span> 
                    </div>
                  </div>
                </div>
                
               
               
              
              </div>
            </div>
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
                        checkOut:'',
                        checkIn:'',
                        rooms:1,
                        adult:1,
                        child:0,
                        searchHotelData:[],
                        totalHotels:null,
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

            // this.searchHotelData = JSON.parse(localStorage.getItem('myStorage'));

              // this.totalHotels= this.searchHotelData.length;
            },
            mounted(){


              setTimeout(function(){
                  let today1 = new Date().toISOString().split('T')[0];

                  document.getElementsByName("checkIn")[0].setAttribute('min', today1);

                 }, 500);

              
              $("#loadingFlight").hide()
            },
            methods:{

              clickDate(){

                let checkIn=this.checkIn;

                  setTimeout(function(){

                  document.getElementsByName("checkOut")[0].setAttribute('min', checkIn);

                  },1);
              },
              searchHotel(){

                   $("#loadingFlight").show()
                   axios.post('searchHotelOffers',
                        {rooms:this.rooms,checkIn:this.checkIn,checkOut:this.checkOut,city:this.formCityCode,child:this.child,adult:this.adult}).then(response => {
                                $("#loadingFlight").hide()
                       
                              if(response.data.code==200){

            
                               this.searchHotelData=response.data.data.data;

                             //  myStorage = localStorage.setItem('myStorage',JSON.stringify(this.searchHotelData));

                             
                               //console.log(this.fromLcoData); 
                                this.totalHotels= this.searchHotelData.length;

                              }else{

                                this.$toaster.error(response.data.message)
                              }
                               
                            })
                            .catch(error => {

                            });
                              
                        //}
                              

                            
              },

              setFromLoc(name,code){
                  this.formCityCode=code;
                   this.fromLoc=name;
                   this.fromLocData=[];

              },
              
              onChange(event) {
                      this.tripWay = event.target.value;
                      console.log(this.tripWay);
                  },

               doSearch(evt){
                var searchText = evt.target.value; // this is the search text
                if(this.timeout) clearTimeout(this.timeout);
                this.timeout = setTimeout(() => {
                   this.getLocation()
                }, 300);
              },


              getLocation(){
                              axios.post('getHotelLoc',{fromLoc:this.fromLoc})
                            .then(response => {

                              if(response.data.code==200){


                               //console.log('success');
                               //console.log(response);
                              this.fromLocData=response.data.data;
                               console.log(this.fromLocData);

                              }
                               
                            })
                            .catch(error => {});
                              

                //console.log(this.fromLco)
              },

              saveHotelTempInfo(hotel){

                $("#loadingFlight").show()
                 axios.post('saveHotelTempInfo', {
                            hotelInfo:hotel,
                            adult:this.adult,
                            child:this.child,
                            checkIn:this.checkIn,
                            checkOut:this.checkOut,
                            rooms:this.rooms,
                            //tClass:this.tClass,
                        }).then(response => {
                                          $("#loadingFlight").hide();

                            if (response.data.code == 200) {

                               let id=response.data.data;
                                
                                  this.$router.push({ name: 'hotel-details', params: {data:id }}) ;

                                
                            }else{

                           this.$toaster.error(response.data.message);

                            }
                        })
                        .catch(error => {
                        });

            

                

                    
              },

              
     
            }
      }
</script>