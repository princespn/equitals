
<style type="text/css">
  
</style>
<template>
  <div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
    <!--   <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h2 class="content-header-title float-left mb-0"> Travel </h2>
              </div>
          </div>
        </div>
      </div> -->

          
      <div class="content-body">
        <section id="multiple-column-form">
          <div class="container-fluid">
            <div class="row">
              <div class="col-xl-12 col-lg-12">
                <div class="card">
                  <div class="card-header ticket-title">
                    <h4 class="card-title">Book Tickets</h4>
                  </div>
                  <div class="card-body">
                    <ul class="nav nav-tabs justify-content-center" role="tablist">
                      <li class="nav-item"> <a class="nav-link active btn-blu" id="home-tab-center" data-toggle="tab" href="#/traval" aria-controls="home-center" role="tab" aria-selected="true"><span>
                  <i class="fas fa-plane"></i></span>Flight Booking</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link btn-dark-blu" id="service-tab-center"  href="#/hotel"  role="tab" aria-selected="false"> <span>
                 <i class="fas fa-hotel"></i></span>Hotel Booking</a>
                      </li>
                    </ul>
                    <div class="tab-content">

                      <div class="tab-pane active outer-ui" id="home-center" aria-labelledby="home-tab-center" role="tabpanel">
                          
                        <div class="row justify-content-center knn">
                          <div class="col-lg-12 mid">
                            <div class="demo-inline-spacing">
                              <div class="custom-control custom-radio">
                                <input type="radio" id="customRadio1" name="customRadio" @change="onChange($event)"  value="one" class="custom-control-input" checked="">
                                <label class="custom-control-label" for="customRadio1">One Way</label>
                              </div>
                              <!-- <div class="custom-control custom-radio">
                                <input type="radio" id="customRadio2" name="customRadio" @change="onChange($event)" value="two" class="custom-control-input">
                                <label class="custom-control-label" for="customRadio2">Round Trip</label>
                              </div> -->
                              <!-- <div class="custom-control custom-radio">
                                <input type="radio" id="customRadio3" name="customRadio" class="custom-control-input">
                                <label class="custom-control-label" for="customRadio3">Multi City</label>
                              </div> -->
                            </div>
                          </div>

                            
                            
                            <div class="col-lg-2" >
                            <label>From</label>
                 
           
                            <input type="text" autocomplete="off" name="fromLoc" placeholder="From" class="form-control" v-model="fromLoc" @keyup="doSearch($event)" > <i class="fa fa-map-marker pps"></i>
                             <span v-show="errors.has('fromLoc')">{{ errors.first('fromLoc') }}</span>

                             <div v-if="fromLocData.length > 0">
                              
                              <ul class="ul-bg">
                              <li v-for="city in fromLocData" @click="setFromLoc(city.cityName,city.cityCode)">
                               {{city.cityName}} - {{city.cityCode}}</li>
                              
                              </ul>
                            </div>
                          </div>
                          <div class="col-lg-2">
                            <label>To</label>
                            <input type="text" autocomplete="off" v-model="toLoc" placeholder="To" class="form-control" @keyup="getToLoc"> <i class="fa fa-map-marker pps"></i>


                            <div v-if="toLocData.length > 0">
                              
                              <ul class="ul-bg">
                              <li v-for="city in toLocData" @click="setToLoc(city.cityName,city.cityCode)">
                               {{city.cityName}} - {{city.cityCode}}</li>
                              
                              </ul>
                            </div>
                          </div>
                          <div class="col-lg-2">
                            <label>Date</label>
                            <input type="date" name="todate" id="todate"  v-model="fdata" placeholder="Date" class="form-control"> 
                          </div>

                          <div class="col-lg-1">
                            <label>Adult</label>
                            <select v-model="adult"class="form-control">
                              
                               <option value="1">1</option>
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
                              
                               <option value="0">0</option>
                               <option value="1">1</option>
                               <option value="2">2</option>
                               <option value="3">3</option>
                               <option value="4">4</option>
                               <option value="5">5</option>
                              
                            </select>
                          </div>
                          <!-- <div class="col-lg-2">
                            <label>Return Date</label>
                            <input  :disabled="tripWay=='one'"  type="date" name="" v-model="tdata" placeholder="Return Date" class="form-control"> <i class="fa fa-calendar pps"></i>
                          </div> -->
                          <div class="col-lg-2">
                            <label>Travellers/Class</label>
                            <select name="tClass" v-model="tClass" id="tClass" class="form-control mb-1">
                              <option value="" selected="selected">Select Travellers/Class</option>
                              <option :value="cat.id" v-for="cat in travelClass">{{cat.name}}</option>
                            </select>
                          </div>
                          <div class="col-lg-2">
                            <label>&nbsp;</label>

                            <button @click="searchFlight" class="btn btn-primary btn-block p11">Search</button>

                            <!-- {{testFm| pFlightName}} -->
                           
                          </div>

                      
                           <!--  <button class="btn btn-sm btn-primary"><i class="fas fa-shopping-cart d-block d-lg-none"></i> <span class="d-none d-lg-block" @click="bookFlight('2','fimg'+1)">Book</span></button>


                             <img id="fimg1"class="img-fluid" alt="" src="https://equitals.s3.ap-south-1.amazonaws.com/flight-logo/a8YUYJymRQ0VwEtwYxbIUcMRBQ37u74K82rKoXUw.png">  -->
                        </div>

                         
                      
                      </div>
                      
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>


        <div class="loadingFlight text-center" id="loadingFlight">


        <img  src="public/user_files/loadingFlight.gif"> 
       <!--  <h2 class="text-dark">We Are Searching</h2> -->
        

      </div>
        



        
            <div class="col-md-12">
            <div class="modal-box15">
            
           <div class="row">
                            <!-- <aside class="col-md-3">
                                <div class="bg-white shadow-md rounded p-3">
                                 
                                   
                                    <div class="accordion accordion-alternate style-2 mt-n3" id="toggleAlternate">
                                        <div class="card">
                                            <div class="card-header" id="stops">
                                                <h5 class="mb-0"> <a href="#" data-toggle="collapse" data-target="#togglestops" aria-expanded="true" aria-controls="togglestops">No. of Stops</a> </h5>
                                            </div>
                                            <div id="togglestops" class="collapse show" aria-labelledby="stops">
                                                <div class="card-body">
                                                    <div class="custom-control">
                                                          <input @click="searchFlight" checked type="radio" value="true" name="stop">
                                                        <label for="nonstop">Non Stop</label>
                                                    </div>
                                                    <div class="custom-control">
                                                      <input  type="radio" @click="searchFlight" value="false" name="stop">
                                                        <label for="1stop">1+ Stop</label>
                                                    </div>
                                                   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header" id="departureTime">
                                                <h5 class="mb-0"> <a href="#" class="collapse" data-toggle="collapse" data-target="#toggleDepartureTime" aria-expanded="true" aria-controls="toggleDepartureTime">Departure Time</a> </h5>
                                            </div>
                                            <div id="toggleDepartureTime" class="collapse show" aria-labelledby="departureTime">
                                                <div class="card-body">
                                                    <div class="custom-control custom-checkbox clearfix">
                                                        <input type="checkbox" id="earlyMorning" name="departureTime" class="custom-control-input">
                                                        <label class="custom-control-label" for="earlyMorning">Early Morning</label> <small class="text-muted float-right">12am - 8am</small> 
                                                    </div>
                                                    <div class="custom-control custom-checkbox clearfix">
                                                        <input type="checkbox" id="morning" name="departureTime" class="custom-control-input">
                                                        <label class="custom-control-label" for="morning">Morning</label> <small class="text-muted float-right">8am - 12pm</small> 
                                                    </div>
                                                    <div class="custom-control custom-checkbox clearfix">
                                                        <input type="checkbox" id="midDay" name="departureTime" class="custom-control-input">
                                                        <label class="custom-control-label" for="midDay">Mid-Day</label> <small class="text-muted float-right">12pm - 4pm</small> 
                                                    </div>
                                                    <div class="custom-control custom-checkbox clearfix">
                                                        <input type="checkbox" id="evening" name="departureTime" class="custom-control-input">
                                                        <label class="custom-control-label" for="evening">Evening</label> <small class="text-muted float-right">4pm - 8pm</small> 
                                                    </div>
                                                    <div class="custom-control custom-checkbox clearfix">
                                                        <input type="checkbox" id="night" name="departureTime" class="custom-control-input">
                                                        <label class="custom-control-label" for="night">Night</label> <small class="text-muted float-right">8pm - 12am</small> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header" id="price">
                                                <h5 class="mb-0"> <a href="#" class="collapse" data-toggle="collapse" data-target="#togglePrice" aria-expanded="true" aria-controls="togglePrice">Price</a> </h5>
                                            </div>
                                            <div id="togglePrice" class="collapse show" aria-labelledby="price">
                                                <div class="card-body">
                                                    <p>
                                                        <input id="amount" type="text" readonly="" class="form-control border-0 bg-transparent p-0">
                                                    </p>
                                                    <div id="slider-range" class="ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content">
                                                        <div class="ui-slider-range ui-corner-all ui-widget-header" style="left: 10.4167%; width: 71.25%;"></div><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 10.4167%;"></span><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 81.6667%;"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header" id="airlines">
                                                <h5 class="mb-0"> <a href="#" class="collapse" data-toggle="collapse" data-target="#toggleAirlines" aria-expanded="true" aria-controls="toggleAirlines">Airlines</a> </h5>
                                            </div>
                                            <div id="toggleAirlines" class="collapse show" aria-labelledby="airlines">
                                                <div class="card-body">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" id="asianaAir" name="airlines" class="custom-control-input">
                                                        <label class="custom-control-label" for="asianaAir">Asiana Airlines</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" id="americanAir" name="airlines" class="custom-control-input">
                                                        <label class="custom-control-label" for="americanAir">American Airlines</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" id="airCanada" name="airlines" class="custom-control-input">
                                                        <label class="custom-control-label" for="airCanada">Air Canada</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" id="airIndia" name="airlines" class="custom-control-input">
                                                        <label class="custom-control-label" for="airIndia">Air India</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" id="jetAirways" name="airlines" class="custom-control-input">
                                                        <label class="custom-control-label" for="jetAirways">Jet Airways</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" id="spicejet" name="airlines" class="custom-control-input">
                                                        <label class="custom-control-label" for="spicejet">Spicejet</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" id="indiGo" name="airlines" class="custom-control-input">
                                                        <label class="custom-control-label" for="indiGo">IndiGo</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" id="multiple" name="airlines" class="custom-control-input">
                                                        <label class="custom-control-label" for="multiple">Multiple Airlines</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </aside>
 -->
                             <div class="col-md-12 mt-4 mt-md-0" v-if="totalFligths == 0">

                                <h1 class="text-center">Flight Not Found</h1>

                           </div>  
                           <div v-else class="col-md-12 mt-4 mt-md-0" >

                                <h1 class="text-center">Flight Search Flight with City name And Code1</h1>


                           </div>


                            <div class="col-md-12 mt-4 mt-md-0"  v-if="searchFlightData.length>0">
                                <div class="bg-white shadow-md rounded py-4">
                                    <div class="mx-3 mb-3 text-center">
                                       
                                        <h2 class="text-6 mb-4 text-dark">{{fromLoc}} <small class="mx-2 text-dark">to</small>{{toLoc}} Total flight Found {{totalFligths}}</h2>
                                    </div>
                                    <div class="text-1 bg-light-3 border border-right-0 border-left-0 py-2 px-3">
                                        <div class="row">
                                          <div class="col col-sm-2 text-center">
                                            <span class="d-none d-sm-block text-dark">Airline</span></div>
                                          <div class="col col-sm-2 text-center">Departure</div>
                                          <div class="col-sm-2 text-center d-none d-sm-block">Duration</div>
                                          <div class="col col-sm-2 text-center">Arrival</div>
                                          <div class="col col-sm-2 text-right">Price</div>
                                        </div>
                                      </div>

                                  
                                    <div class="flight-list" v-for="(flight,index) in searchFlightData">
                                        <div class="flight-item">
                                            <div class="row align-items-center flex-row pt-4 pb-2 px-3">
                                                <div class="col col-sm-2 text-center d-lg-flex company-info"> <span class="align-middle">
                                                  <img :id="'fimg'+index"class="img-fluid" alt="" :src="flight.itineraries[0].segments[0].carrierCode|pFlightLogo"> </span>  <span class="align-middle ml-lg-2"> <span class="d-block text-1 text-dark">{{flight.itineraries[0].segments[0].carrierCode|pFlightName}}</span>  <small class="text-muted d-block">
                                                  {{flight.itineraries[0].segments[0].carrierCode}} -
                                                   {{flight.itineraries[0].segments[0].aircraft.code}}
                                                </small> 
                                                    </span>
                                                </div>
                                                <div class="col col-sm-2 text-center time-info"> <span class="text-4">

                                                {{flight.itineraries[0].segments[0].departure.iataCode}}</span>  <small class="text-muted d-none d-sm-block">{{flight.itineraries[0].segments[0].departure.at|pRemoveTime}}

                                                <p>{{flight.itineraries[0].segments[0].departure.at|pRemoveDate}}</p>
                                                </small> 
                                                </div>
                                                <div class="col-sm-2 text-center d-none d-sm-block time-info"> <span class="text-3">{{flight.itineraries[0].segments[0].duration|pDuration}}</span> 
                                                 <small  class="text-muted d-none d-sm-block">

                                                   <span class="text-3" v-if="flight.itineraries[0].segments[0].numberOfStops==0">
                                                      Non-Stop
                                                    </span>
                                                    <span v-else>Stop- {{flight.itineraries[0].segments[0].numberOfStops}} </span>
                                                 

                                                </small> 
                                                 

                                                </div>
                                                <div class="col col-sm-2 text-center time-info"> <span class="text-4">{{flight.itineraries[0].segments[0].arrival.iataCode}}</span>  <small class="text-muted d-none d-sm-block">{{flight.itineraries[0].segments[0].arrival.at|pRemoveTime}}
                                                <p>
                                                  {{flight.itineraries[0].segments[0].arrival.at|pRemoveDate}}
                                                </p>
                                                </small> 
                                                </div>
                                                <div class="col col-sm-2 text-right text-dark text-6 price">${{flight.price.total}}</div>
                                                <div class="col-12 col-sm-2 text-center ml-auto btn-book"> <button class="btn btn-sm btn-primary"><i class="fas fa-shopping-cart d-block d-lg-none"></i> <span class="d-none d-lg-block" @click="bookFlight(flight,'fimg'+index)">Book</span></button>
                                                </div>
                                               <!--  <div class="col col-sm-auto col-lg-2 ml-auto mt-1 text-1 text-center"><a data-toggle="modal" data-target="#flight-1" href="">Flight Details</a>
                                                </div> -->
                                            </div>
                                      
                                        </div>
                                    </div>


                                  <!-- Flight Details Modal Dialog
                  ============================================= -->
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>

              <div class="col-md-12">
            <div class="modal-box15">
                <!-- Button trigger modal -->
               <!--  <button type="button" class="btn btn-primary btn-lg show-modal" data-toggle="modal" data-target="#myModal">
                  view modal
                </button> -->
 
                <!-- Modal -->
           



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

 //import airports  from "../../user-config/airports.json";

    import VueTelInput from 'vue-tel-input';
      
      //import TravelWayComponent from './TravelWayComponent.vue';
      import Swal from 'sweetalert2';
        export default {  
            components: {
              VueTelInput
            }, 
            data(){
                return{

                  temp_logo:'M3',
                   form: {
                    formlocation: "",
                    },
                        updatepassword: {
                        old_password: '',
                        new_password: '',
                        retype_password: '',
                       
                    },
                    //airports:airports,
                    testFm:'AI',
                    child:0,
                    adult:1,
                    adultName:[],
                    childName:[],
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
                        totalFligths:null,
                        selectedFlight:'',
                        searchFlightData:[],


                        travelClass:[
                           {name:'Economy',id:'ECONOMY'},
                           {name:'Premium Economy',id:'PREMIUM_ECONOMY'},
                           {name:'Business',id:'BUSINESS'},
                           {name:'First',id:'FIRST'},
                           
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
            mounted(){
             $("#loadingFlight").hide();

              var today = new Date().toISOString().split('T')[0];

              document.getElementsByName("todate")[0].setAttribute('min', today);
               // this.getProducts('IN');
                //voucher-detail
                setTimeout(function(){
                  let today1 = new Date().toISOString().split('T')[0];

              document.getElementsByName("todate")[0].setAttribute('min', today1);

                 }, 500);

               //this.getFlightCodeName();
             //this.getCategories();
             this.getFlightLogo();



            },
            methods:{


              bookFlight(flight,fimgId){

                   // this.selectedFlight=flight; 

                  let fimg=$('#'+fimgId).attr('src');

                  $("#loadingFlight").show();

                 $("#loadingFlight").show();
                 axios.post('saveFlightTempInfo', {
                            flightInfo:flight,
                            adult:this.adult,
                            child:this.child,
                            tClass:this.tClass,
                            fimg:fimg,
                        }).then(response => {

                           $("#loadingFlight").hide();
                                                    this.procedToPay = true;

                            if (response.data.code == 200) {

                               let id=response.data.data;
                                
                                  this.$router.push({ name: 'travel-details', params: {data:id }}) ;

                                
                            }else{

                           this.$toaster.error(response.data.message);

                            }
                        })
                        .catch(error => {
                        });

            


                    
              },
              searchFlight(){

                this.searchFlightData=[];

               let stop= $('input[name="stop"]:checked').val();
              if(stop==undefined){
                stop=false;

              }
             

                if(this.formCityCode==''||this.toCityCode == ''||this.fdata ==''||this.tClass ==''){

                  this.$toaster.error(' Enter required fields ')
                }else{
                    this.flightInfo=[];
                    let time =new Date().getTime();
                     $("#loadingFlight").show();
                        
                  
                      //axios.post('searchFlight',
                      axios.post('searchFlightOffers',
                        {fromLoc:this.formCityCode,
                             toLoc:this.toCityCode,
                             fdate:this.fdata,
                             tClass:this.tClass,
                             child:this.child,
                             stop:stop,
                             adult:this.adult}).then(response => {
                                  $("#loadingFlight").hide();
                              if(response.data.code==200){

                              // console.log('success');
                               this.totalFligths=response.data.data.meta.count;

                          
                               this.searchFlightData=response.data.data.data;

                              
                               

                             let carriers= JSON.stringify(response.data.data.dictionaries.carriers);

                              localStorage.setItem('carriers',carriers);


                       
                               console.log(this.totalFligths);

                              }else{

                                this.toaster.error(response.data.message)
                              }
                               
                            })
                            .catch(error => {

                            });
                              
                        }
                              

                            
              },

              setFromLoc(name,code){
                  this.formCityCode=code;
                   this.fromLoc=name;
                   this.fromLocData=[];

              },
              setToLoc(name,code){
                this.toCityCode=code;
                   this.toLoc=name;
                   this.toLocData=[];

              },
              onChange(event) {
                      this.tripWay = event.target.value;
                      console.log(this.tripWay);
                  },


               filterItems(arr, query) {

                console.log(arr);
                console.log(query);

                return false;
                    return arr.filter(function(el) {
                        return el.city.toLowerCase().indexOf(query.toLowerCase()) !== -1
                    })
                  },

               doSearch(evt){

                

                var searchText = evt.target.value; // this is the search text
                if(this.timeout) clearTimeout(this.timeout);
                this.timeout = setTimeout(() => {
                   this.getLocation()
                }, 2000);
              },


              getLocation(){
                              axios.post('getFlightLoc',{fromLoc:this.fromLoc})
                            .then(response => {

                              if(response.data.code==200){

                                this.fromLocData=response.data.data;

                               // console.log('success');
                               // console.log(response);
                               /*if(response.data.data.data){
                                this.fromLocData=response.data.data;
                               }else{
                               }*/
                               //console.log(this.fromLcoData);

                              }
                               
                            })
                            .catch(error => {});
                              

                //console.log(this.fromLco)
              },

               doSearch2(evt){
                var searchText = evt.target.value; // this is the search text
                if(this.timeout) clearTimeout(this.timeout);
                this.timeout = setTimeout(() => {
                   this.getToLoc()
                }, 2000);
              },


              getToLoc(){


                axios.post('getFlightLoc',{fromLoc:this.toLoc})
                            .then(response => {

                              if(response.data.code==200){

                                this.toLocData=response.data.data;

                               // console.log('success');
                               // console.log(response);
                            /*  if(response.data.data.data){
                                this.toLocData=response.data.data.data;
                               }else{
                               }*/

                              }
                               
                            })
                            .catch(error => {});
                              

                //console.log(this.fromLco)
              },

              getFlightCodeName(){


                axios.post('getFlightCodeName',{fromLoc:this.toLoc})
                            .then(response => {

                              if(response.data.code==200){


                               // console.log('success');

                               let flight_code=JSON.stringify(response.data.data)

                                localStorage.setItem("flight_code",flight_code);
                               // console.log(response);
                                                             //console.log(this.fromLcoData);

                              }
                               
                            })
                            .catch(error => {});
                              

                //console.log(this.fromLco)
              },getFlightLogo(){


                axios.post('getFlightLogo',{fromLoc:this.toLoc})
                            .then(response => {

                              if(response.data.code==200){


                               // console.log('success');

                               let flight_logo=JSON.stringify(response.data.data)

                                localStorage.setItem("flight_logo",flight_logo);
                               // console.log(response);
                                                             //console.log(this.fromLcoData);

                              }
                               
                            })
                            .catch(error => {});
                              

                //console.log(this.fromLco)
              },
    
              onInput({ number, isValid, country }) {
                //alert(country);
                console.log(country)
                   
            this.getcountry=country.name;
            this.countryCode=country.iso2;
             this.getProducts(country.iso2);
        },
        changeCat(){
      //alert();
          this.getProducts(this.getcountry);
        },
    
        
          }
      }
</script>