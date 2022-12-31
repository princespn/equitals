<template>
	 <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="header-navbar-shadow"></div>
      <div class="content-wrapper">
        <div class="content-header row">
          <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
              <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Buy Coin</h2>
             <!--    <div class="breadcrumb-wrapper col-12">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Change Password
                    </li>
                  </ol>
                </div> -->
              </div>
            </div>
          </div>
          <!-- <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
            <div class="form-group breadcrum-right">
              <div class="dropdown">
                <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-settings"></i></button>
                <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="#">Chat</a><a class="dropdown-item" href="#">Email</a><a class="dropdown-item" href="#">Calendar</a></div>
              </div>
            </div>
          </div> -->
        </div>
        <div class="content-body">
          <section id="multiple-column-form">
          <div class="container">
    <div class="row">
        <div class="main-content col-lg-8">
           <!--  <div class="d-lg-none"><a href="#" data-toggle="modal" data-target="#add-wallet" class="btn btn-danger btn-xl btn-between w-100 mgb-1-5x">Add your wallet address before buy <em class="ti ti-arrow-right"></em></a>
                <div class="gaps-1x mgb-0-5x d-lg-none d-none d-sm-block"></div>
            </div> -->
            <div class="content-area card">
                <div class="card-innr">
                 <div class="obb" >
                        <div class="card-head">
                        <span class="card-sub-title text-primary font-mid">Step 1</span>
                        <h4 class="card-title">{{ico_arr.name}}</h4>
                    </div>
                    <div class="card-text row">
                        <div class="col-lg-6">
                            <p>Date {{ico_arr.from_date}}</p>
                        <p>Price : $ {{ico_arr.usd_rate}}  </p>
                        <p>Remaining Coins: {{ ico_arr.total_supply - ico_arr.sold_supply}}</p>
                        </div>
                      <div class="col-lg-6">
                            
                             <p v-if="ico_arr.status=='Notavailable'">Phase Start ON </p>
                              <!--  <p v-else>Phase End In</p> -->
                          <p v-if="ico_arr.status=='Notavailable'" id="demo11"></p>
                      </div>
                    </div>
                    <div class="col-lg-8 justify-content-center mao">
                        <h3>Purchase Wallet Balance</h3>
                        <p class="pay-option-label pwb">{{ico_arr.purchase_wallet.toFixed(2)}}</p>
                    </div>

                 </div>

                  <div class="obb">
                    <div class="card-head"><span class="card-sub-title text-primary font-mid">Step 2</span>
                        <h4 class="card-title">Amount of contribute</h4>
                    </div>
                    <div class="card-text">
                        <p>Enter your amount, you would like to contribute and calculate the amount of token you will received. The calculator helps to convert required currency to Coin.</p>
                    </div>
                   
                        <div class="row mao jsc">
                                <div class="col-lg-6">
                                    <input id="bcoin"class="form-control" v-model="coin" type="text" @keyup="calCoin()" placeholder="Enter Coin ">
                                </div>
                               <div class="col-lg-1">
                                   <p class="ptt">=</p>
                               </div>

                                <div class="col-lg-5">
                                       <p class="ptt">$ {{coinFinalAmt.toFixed(2)}}</p>
                                </div>
                    
                            
                        </div>
                        <div class="token-calc-note note note-plane"><em class="fas fa-times-circle text-danger"></em><span class="note-text text-light">100 Coin minimum contribution require.</span>
                        </div>
                   
                   
                    <div class="token-overview-wrap">
                        
                        <div class="note note-plane note-danger note-sm pdt-1x pl-0">
                            <p>Your Contribution will be calculated based on exchange rate at the moment your transaction is confirm.</p>
                        </div>
                    </div>

                </div>

                <div class="obb">
                      <div class="card-head"><span class="card-sub-title text-primary font-mid">Step 3</span>
                        <h4 class="card-title">Buy Coin </h4>
                    </div>
                    <div class="card-text">
                        <p></p>
                    </div>

                    <div class="pay-buttons">

                       
                      
                        <div v-if="ico_arr.total_supply != ico_arr.sold_supply && ico_arr.status!='SoldOut'"  class="pay-button btn-block text-center"><a href="#" class="btn btn-primary btn-between w190" @click="buyCoins">Buy Coin <em class="ti ti-arrow-right"></em></a> 
                        </div> 
                         <div v-if="ico_arr.total_supply == ico_arr.sold_supply ||ico_arr.status=='SoldOut' " class="pay-button btn-block text-center"> 
                            <h2>Sold Out</h2>
                        </div> 

                       <!--  v-if="ico_arr.status=='Available'"   <div v-else class="pay-button btn-block text-center"><a href="#" class="btn btn-danger btn-between w190" >EXPIRED <em class="ti ti-arrow-right"></em></a>
                        </div> -->
                    </div>
                </div>
              
                   
                </div>
                <!-- .card-innr -->
            </div>
            <!-- .content-area -->
        </div>
        <!-- .col -->
        <div class="aside sidebar-right col-lg-4">
            
            <div class="token-statistics card card-token height-auto">
                <div class="card-innr">
                    <div class="token-balance">
                        <div class="token-balance-text">
                            <h6 class="card-sub-title">Total Coins</h6><span class="lead">{{ico_arr.total_supply}}<span></span></span>
                        </div>
                    </div>
                    <div class="token-balance token-balance-s2">
                        <h6 class="card-sub-title">Your Contribution</h6>
                        <ul class="token-balance-list">
                            <li class="token-balance-sub"><span class="lead">{{ico_arr.coin}}</span><span class="sub"> Coin</span>
                            </li>

                          
                        </ul>
                    </div>
                </div>
            </div>
           
        </div>
        <!-- .col -->
    </div>
    <!-- .container -->

</div>
        </section>
        </div>
      </div>
    </div>
</template>
<script>
	import Breadcrum from './BreadcrumComponent.vue';
	import Swal from 'sweetalert2';
   	export default {  
      	components: {
         	Breadcrum
      	}, 
        data(){
            return{
                updatepassword: {
                    old_password: '',
                    new_password: '',
                    retype_password: '',
                },
				updatepassword:[],
				isDisableBtn:true,
                coin:'',
                coinFinalAmt:0,
                //ico_arr:''
                ico_arr:{
                    name:'',
                bonus_percentage: 0,
                coin_name: "",
                days: 5,
                entry_time: "",
                from_date: "",
                min_coin: 0,
                name: "",
                percentage: 0,
                purchase_wallet:0,
                sold_percentage: 0,
                sold_supply: 0,
                status: "0",
                to_date: "",
                total_supply: '',
                usd_rate: 0,
                now_time:0

                }
            }
        },
        computed:{
            isCompletePassword(){
                return this.updatepassword.old_password && this.updatepassword.new_password && this.updatepassword.retype_password;
            }
        },
        mounted(){
            this.geticophasesFun();

        },

        
        methods:{
            buyCoins(){

                if(this.coin==''){
                   $( "#bcoin" ).focus();
                   this.$toaster.error('Please Enter Coins'); 
                    return false;
                }
               
               this.$route.params.id
            
                    this.isDisableBtn = false;
                    Swal({
                        title: 'Are you sure?',
                        text: `You want to purchase coin`,
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes'
                    }).then((result) => {
                        if (result.value) { 
                            axios.post('purchaseCoin', {                    
                                coin: this.coin,
                                srno: this.$route.params.id,
                            })
                            .then(response => {   
                                if(response.data.code === 200) {
                                    this.$toaster.success(response.data.message);

                                    this.$router.push({name:'purchase-coin-rep'});
                                    //this.$router.push({name: 'buy-token'});

                                }else{
                                    this.$toaster.error(response.data.message) 
                                }
                               // $('#update-user-password').trigger("reset");
                            })
                        }
                    });

            },


            phase1(startDate,endDate,no,status){
             let that=this;
                let countDownDate="";
           //alert(status);
                if(status=='Notavailable'){
               //console.log(startDate +no);
               //alert(startDate);
               //$("#saleStart"+no).html(startDate);

                // /document.getElementById("saleStart"+no).innerHTML = startDate;
                 countDownDate = new Date(startDate).getTime();

               }else{

                //from_date: "2021-03-20 17:01:03"
                 countDownDate = new Date(endDate).getTime();
                 return false;
                  }



                                    // Update the count down every 1 second
                                    let x = setInterval(function() {
                              
                                     //console.log(that.now_time)
                                      // Get today's date and time
                                       that.now_time = new Date(that.now_time).getTime();
                                       that.now_time = new Date(that.now_time+1000).getTime();

                                       //console.log(that.now_time)

                                       // alert(that.now_time)
/*alert(now.getMinutes() + ':' + now.getSeconds()); //11:55
d.setSeconds(d.getSeconds() + 10);
alert(d.getMinutes() + ':0' + d.getSeconds()); //12:05*/
                                        
                                      // Find the distance between now and the count down date
                                      let distance = countDownDate -  that.now_time;
                                        
                                      // Time calculations for days, hours, minutes and seconds
                                      let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                      let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                      let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                      let seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                        
                                      // Output the result in an element with id="demo"
                                      document.getElementById("demo11").innerHTML = days + "d " + hours + "h "
                                      + minutes + "m " + seconds + "s ";
                                        
                                      // If the count down is over, write some text 
                                      if (distance < 0) {
                                        clearInterval(x);
                                         document.getElementById("demo11").innerHTML = "<h2 style='color:red'>EXPIRED </h2>";
                                      }
                                    }, 1000);

                                  
            },
            
            calCoin(){
               this.coinFinalAmt =this.ico_arr.usd_rate * this.coin;

            },
            
            geticophasesFun(){
                  let product_id = this.$route.params.id;
                axios.post('geticophases', {id:product_id})
                            .then(response => {   
                                if(response.data.code === 200) {

                                    this.ico_arr=response.data.data[0];

                                     this.phase1(this.ico_arr['from_date'],this.ico_arr['to_date'],0,this.ico_arr['status']);

                                     this.now_time=this.ico_arr['now_time'];
                                     console.log(this.now_time)
                                    // alert(this.now_time)
                                    //this.$toaster.success(response.data.message);
                                }else{
                                    this.$toaster.error(response.data.message) 
                                }
                                $('#update-user-password').trigger("reset");
                            })

            },

            //let product_id = this.$route.params.id;
            updateUserPassword() {            	
                var new_pwd = this.updatepassword.new_password;
                var conf_pwd = this.updatepassword.retype_password;
                if (new_pwd == conf_pwd) {
					this.isDisableBtn = false;
                	Swal({
	                    title: 'Are you sure?',
	                    text: `You want to change password`,
	                    type: 'warning',
	                    showCancelButton: true,
	                    confirmButtonColor: '#3085d6',
	                    cancelButtonColor: '#d33',
	                    confirmButtonText: 'Yes'
	                }).then((result) => {
	                    if (result.value) { 
		                    axios.post('change-password', {                    
		                        current_pwd: this.updatepassword.old_password,
		                        new_pwd: this.updatepassword.new_password,
		                        conf_pwd: this.updatepassword.retype_password,           
		                    })
		                    .then(response => {   
		                    	if(response.data.code === 200) {
				                    this.$toaster.success(response.data.message);
				                }else{
				                    this.$toaster.error(response.data.message) 
								}
								$('#update-user-password').trigger("reset");
		                    })
		                }
                	});
                } else {
                    this.$toaster.error('New Password and Reset Password Not Matched...');
                }                
            },
            matchpassword() {
	            if(this.updatepassword.new_password != this.updatepassword.retype_password){
	                /*this.errors.add('this.password_confirmation', 'not match')*/
	              	this.errors.add({
	              		field: 'retype_password',
	              		msg: 'password does not match'
	              	});
	            } else {
					
	            }
            }
        }
	}
</script>