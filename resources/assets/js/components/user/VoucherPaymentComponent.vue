<template>
	 <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="header-navbar-shadow"></div>
      <div class="content-wrapper">
        <div class="content-header row">
          <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
              <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Voucher Payment</h2>
           
              </div>
            </div>
          </div>
          
        </div>
        <div class="content-body">
          <section id="multiple-column-form">
           <div class="container-fluid">
                <div class="row">
            <!-- Column -->
         <div class="col-md-4">
            <div class="card br-10">
                <div class="card-body">
                              <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Your cart</span>
            <span class="badge badge-secondary badge-pill">3</span>
          </h4>
          <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Product name</h6>
                <small class="text-muted">Brief description</small>
              </div>
              <span class="text-muted">$12</span>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Second product</h6>
                <small class="text-muted">Brief description</small>
              </div>
              <span class="text-muted">$8</span>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Third item</h6>
                <small class="text-muted">Brief description</small>
              </div>
              <span class="text-muted">$5</span>
            </li>
        
            <li class="list-group-item d-flex justify-content-between">
              <span>Total Estimate</span>
              <strong>$20</strong>
            </li>
          </ul>

                </div>
            </div>
        </div>
            <div class="col-lg-8">
                <div class="card br10">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <p class="b-yZcAZ4">Login or Create an account to pay with your account balance, receive rewards, and more!</p>
                                <h2>Choose Payment method</h2>

                                <input type="email" name="email" autocomplete="email" required="" value="" class="form-control">

                                <div class="po">
                                 <img src="public/user_files/images/1000.png" width="40px"> 
                                 <div>
                                   <p class="mbb0"><b></b></p> 
                                 <span>0.000 </span>
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
                ico_arr:[],
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
            buycoin(index){
                let id=index+1;
                //alert(id)
                 this.$router.push({
                name: 'buy-token',
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
                    this.phase1(this.ico_arr['0']['from_date'],this.ico_arr['0']['to_date'],0,this.ico_arr['0']['status'])

                    this.phase1(this.ico_arr['1']['from_date'],this.ico_arr['1']['to_date'],1,this.ico_arr['1']['status'])

                    this.phase1(this.ico_arr['2']['from_date'],this.ico_arr['2']['to_date'],2,this.ico_arr['2']['status'])

                    this.phase1(this.ico_arr['3']['from_date'],this.ico_arr['3']['to_date'],3,this.ico_arr['3']['status'])

                    this.phase1(this.ico_arr['4']['from_date'],this.ico_arr['4']['to_date'],4,this.ico_arr['4']['status'])
                                    
                                        
                                }else{
                                    this.$toaster.error(response.data.message) 
                                }
                                $('#update-user-password').trigger("reset");
                            })

            },


            phase1(startDate,endDate,no,status){

                let countDownDate="";
               if(status=='Notavailable'){
               //console.log(startDate +no);
               //alert(startDate);
               //$("#saleStart"+no).html(startDate);

                // /document.getElementById("saleStart"+no).innerHTML = startDate;

                 countDownDate = new Date(startDate).getTime();
                
               }else{

                //from_date: "2021-03-20 17:01:03"
                 countDownDate = new Date(endDate).getTime();

                }


                                    // Update the count down every 1 second
                                    let x = setInterval(function() {

                                      // Get today's date and time
                                      let now = new Date().getTime();
                                        
                                      // Find the distance between now and the count down date
                                      let distance = countDownDate - now;
                                        
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