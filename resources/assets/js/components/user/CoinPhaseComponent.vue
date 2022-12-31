<style type="text/css">
  
  .hide-time{

    display:none;
  }
</style>

<template>
	 <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="header-navbar-shadow"></div>
      <div class="content-wrapper">
        
        <div class="content-header">
            <div class="row">
            <div class="col-md-12 saleH">
                <h2> SALE </h2>
               
                <h2 v-if="icoStatusInfo=='off'"> {{adminStatus}} </h2>

            </div>
        </div>
          <!-- <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
              <div class="col-12">
                <h2 class="content-header-title float-left mb-0"></h2>
                <div class="breadcrumb-wrapper col-12">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Change Password
                    </li>
                  </ol>
                </div>
              </div>
            </div>
          </div> -->
          <!-- <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
            <div class="form-group breadcrum-right">
              <div class="dropdown">
                <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-settings"></i></button>
                <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="#">Chat</a><a class="dropdown-item" href="#">Email</a><a class="dropdown-item" href="#">Calendar</a></div>
              </div>
            </div>
          </div> -->
        </div>
        <div class="row">
            <div class="col-md-12">
                <ul class="cpmin">
                    <li class="CPBox" v-for="(ico ,index) in ico_arr">
                        <div class="cpIcon">
                            <img src="public/user_files/images/logo1.png" class="img-responsive">
                        </div>
                        <div class="leftcor"></div>
                        <div class="cpWrap">
                            <div class="cptitlebox">
                                <h4>
                                    {{ico.name}}
                                </h4>
                                    <p> 
                                        <span> Price: </span>
                                        $ {{ico.usd_rate}}
                                    </p>
                                    <p> 
                                        <span> Total Token: </span>
                                        {{ico.total_supply}}
                                    </p>
                                    <p> 
                                        <span> Remaining Tokens: </span>
                                        {{ico.total_supply - ico.sold_supply}}
                                    </p>
                            </div>
                            <div class="bottombox">
                                <span v-if="ico.status=='SoldOut'">
                                    <router-link :to="coin-phase" tag="a">Sold Out</router-link>
                                </span>
                                <span v-if="ico.status=='Available'">
                                    <router-link class="buybtn-cp" :to="'/buy-coin/'+(index + 1)" tag="a">Buy Token</router-link>
                                </span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
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
                    icoStatusInfo: 'Amol',
                    adminStatus: '',
                },
				updatepassword:[],
				isDisableBtn:true,
                ico_arr:[],
                now_time1:'',
                now_time2:'',
                now_time3:'',
                now_time4:'',
                now_time5:'',
            }
        },
        computed:{
            isCompletePassword(){
                return this.updatepassword.old_password && this.updatepassword.new_password && this.updatepassword.retype_password;
            }
        },
        mounted(){
            this.getIcoStatusFun();
            this.geticophasesFun();
        },
        methods:{

            getIcoStatusFun(){
                    axios
          .get("getIcoStatus", {})
          .then(response => {
            this.icoStatusInfo = response.data.data.ico_status;
            this.adminStatus = response.data.data.ico_admin_error_msg;
          
            
          })
          .catch(error => {});
    
            },
            buycoin(index){
                let id=index+1;
                //alert(id)
                 this.$router.push({
                name: 'buy-coin',
                params: {
                    id:id 
                    //variation_id : variationId 
                },
            });

            },
            geticophasesFun(){

                axios.post('geticophases', {})
                            .then(response => {   
                                if(response.data.code === 200) {
                                    this.ico_arr=response.data.data;
                                    //this.$toaster.success(response.data.message);
                                      /* for (var i =0;this.ico_arr.length >i; i++) {
                                            console.log(i);
                                            }*/     
                                        //alert(this.ico_arr['0']['to_date']);
                   //this.phase1(this.ico_arr['0']['from_date'],this.ico_arr['0']['to_date'],0,this.ico_arr['0']['status'],this.ico_arr['0']['now_time'])

                   // this.phase2(this.ico_arr['1']['from_date'],this.ico_arr['1']['to_date'],1,this.ico_arr['1']['status'],this.ico_arr['0']['now_time'])

                   // this.phase3(this.ico_arr['2']['from_date'],this.ico_arr['2']['to_date'],2,this.ico_arr['2']['status'],this.ico_arr['0']['now_time'])

                   // this.phase4(this.ico_arr['3']['from_date'],this.ico_arr['3']['to_date'],3,this.ico_arr['3']['status'],this.ico_arr['0']['now_time'])

                   // this.phase5(this.ico_arr['4']['from_date'],this.ico_arr['4']['to_date'],4,this.ico_arr['4']['status'],this.ico_arr['0']['now_time'])
                                    
                                        
                                }else{
                                    this.$toaster.error(response.data.message) 
                                }
                                $('#update-user-password').trigger("reset");
                            })

            },


            phase1(startDate,endDate,no,status,nt){
                  this.now_time1=nt;
                  let that=this;
                let countDownDate="";
               if(status=='Notavailable'){
                    countDownDate = new Date(startDate).getTime();
                
               }else{

                //from_date: "2021-03-20 17:01:03"
                 countDownDate = new Date(endDate).getTime();
                  return false;

                }


                                    // Update the count down every 1 second
                                    let x = setInterval(function() {

                                      // Get today's date and time
                                       that.now_time1 = new Date(that.now_time1).getTime();
                                       that.now_time1 = new Date(that.now_time1+1000).getTime();
                                    
                               // Find the distance between now and the count down date
                                      let distance = countDownDate - that.now_time1;
                                        
                                      // Time calculations for days, hours, minutes and seconds
                                      let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                      let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                      let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                      let seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                        
                                      // Output the result in an element with id="demo"
                                      document.getElementById("demo"+no).innerHTML = days + "d " + hours + "h "
                                      + minutes + "m " + seconds + "s ";
                                        
                                      // If the count down is over, write some text 
                                      if (distance < 0) {
                                        clearInterval(x);
                                        document.getElementById("demo"+no).innerHTML = "<span style='color:red'>EXPIRED </span>";
                                      }
                                    }, 1000);

                                    
            },
            phase2(startDate,endDate,no,status,nt){
                  this.now_time2=nt;
                    let that=this;
                let countDownDate="";
               if(status=='Notavailable'){
                    countDownDate = new Date(startDate).getTime();
                
               }else{

                //from_date: "2021-03-20 17:01:03"
                 countDownDate = new Date(endDate).getTime();
               return false;

                }


                                    // Update the count down every 1 second
                                    let x = setInterval(function() {

                                      // Get today's date and time
                                       that.now_time2 = new Date(that.now_time2).getTime();
                                       that.now_time2 = new Date(that.now_time2+1000).getTime();
                                    
                               // Find the distance between now and the count down date
                                      let distance = countDownDate - that.now_time2;
                                        
                                      // Time calculations for days, hours, minutes and seconds
                                      let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                      let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                      let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                      let seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                        
                                      // Output the result in an element with id="demo"
                                      document.getElementById("demo"+no).innerHTML = days + "d " + hours + "h "
                                      + minutes + "m " + seconds + "s ";
                                        
                                      // If the count down is over, write some text 
                                      if (distance < 0) {
                                        clearInterval(x);
                                        document.getElementById("demo"+no).innerHTML = "<span style='color:red'>EXPIRED </span>";
                                      }
                                    }, 1000);

                                    
            }, phase3(startDate,endDate,no,status,nt){
                let that=this;
                  this.now_time3=nt;
                let countDownDate="";
               if(status=='Notavailable'){
                    countDownDate = new Date(startDate).getTime();
                
               }else{

                //from_date: "2021-03-20 17:01:03"
                 countDownDate = new Date(endDate).getTime();
                return false;

                }


                                    // Update the count down every 1 second
                                    let x = setInterval(function() {

                                      // Get today's date and time
                                       that.now_time3 = new Date(that.now_time3).getTime();
                                       that.now_time3 = new Date(that.now_time3+1000).getTime();
                                    
                               // Find the distance between now and the count down date
                                      let distance = countDownDate - that.now_time3;
                                        
                                      // Time calculations for days, hours, minutes and seconds
                                      let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                      let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                      let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                      let seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                        
                                      // Output the result in an element with id="demo"
                                      document.getElementById("demo"+no).innerHTML = days + "d " + hours + "h "
                                      + minutes + "m " + seconds + "s ";
                                        
                                      // If the count down is over, write some text 
                                      if (distance < 0) {
                                        clearInterval(x);
                                        document.getElementById("demo"+no).innerHTML = "<span style='color:red'>EXPIRED </span>";
                                      }
                                    }, 1000);

                                    
            },
            phase4(startDate,endDate,no,status,nt){
                let that=this;
                  this.now_time4=nt;
                let countDownDate="";
               if(status=='Notavailable'){
                    countDownDate = new Date(startDate).getTime();
                
               }else{

                //from_date: "2021-03-20 17:01:03"
                 countDownDate = new Date(endDate).getTime();
                 return false;

                }


                                    // Update the count down every 1 second
                                    let x = setInterval(function() {

                                      // Get today's date and time
                                       that.now_time4 = new Date(that.now_time4).getTime();
                                       that.now_time4 = new Date(that.now_time4+1000).getTime();
                                    
                               // Find the distance between now and the count down date
                                      let distance = countDownDate - that.now_time4;
                                        
                                      // Time calculations for days, hours, minutes and seconds
                                      let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                      let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                      let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                      let seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                        
                                      // Output the result in an element with id="demo"
                                      document.getElementById("demo"+no).innerHTML = days + "d " + hours + "h "
                                      + minutes + "m " + seconds + "s ";
                                        
                                      // If the count down is over, write some text 
                                      if (distance < 0) {
                                        clearInterval(x);
                                        document.getElementById("demo"+no).innerHTML = "<span style='color:red'>EXPIRED </span>";
                                      }
                                    }, 1000);

                                    
            },
             phase5(startDate,endDate,no,status,nt){
                let that=this;
                  this.now_time5=nt;
                let countDownDate="";
               if(status=='Notavailable'){
                    countDownDate = new Date(startDate).getTime();
                
               }else{

                //from_date: "2021-03-20 17:01:03"
                 countDownDate = new Date(endDate).getTime();
                 return false;

                }


                                    // Update the count down every 1 second
                                    let x = setInterval(function() {

                                      // Get today's date and time
                                       that.now_time5 = new Date(that.now_time5).getTime();
                                       that.now_time5 = new Date(that.now_time5+1000).getTime();
                                    
                               // Find the distance between now and the count down date
                                      let distance = countDownDate - that. now_time5;
                                        
                                      // Time calculations for days, hours, minutes and seconds
                                      let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                      let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                      let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                      let seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                        
                                      // Output the result in an element with id="demo"
                                      document.getElementById("demo"+no).innerHTML = days + "d " + hours + "h "
                                      + minutes + "m " + seconds + "s ";
                                        
                                      // If the count down is over, write some text 
                                      if (distance < 0) {
                                        clearInterval(x);
                                        document.getElementById("demo"+no).innerHTML = "<span style='color:red'>EXPIRED </span>";
                                      }
                                    }, 1000);

                                    
            },
            
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