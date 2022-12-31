<template>
   <div class="app-content content">
  <div class="content-overlay"></div>
  <div class="header-navbar-shadow"></div>
  <div class="content-wrapper">
    <div class="content-header row">
      <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
          <div class="col-12">
            <h2 class="content-header-title float-left mb-0">Security</h2>
          
          </div>
        </div>
      </div>
      <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
        <div class="form-group breadcrum-right">
          <div class="dropdown">
            <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-settings"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="#">Chat</a><a class="dropdown-item" href="#">Email</a><a class="dropdown-item" href="#">Calendar</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="content-body">
      <section id="multiple-column-form">
        <div class="row match-height">
          <div class="col-md-6 offset-md-3 text-center">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Two-Factor Authentication (2FA)</h4>
              </div>
              <div class="card-content">
                <div class="card-body">
                  <form v-on:submit.prevent="checkOtp" class="form">
                    <div class="form-body">
                      <div class="row">
                        <div class="col-md-12 col-12">
                         <p>1. Install <a href="https://en.wikipedia.org/wiki/Google_Authenticator" target="_blank" style="font-size:16px; color: ">Google Authenticator</a> on your mobile device.</p>

                         <div>
                           <a href="https://apps.apple.com/us/app/google-authenticator/id388497605" target="_blank"><img src="public/user_files/images/ios.png" width="128px"></a>

                           <a href="https://apps.apple.com/us/app/google-authenticator/id388497605" target="_blank"><img src="public/user_files/images/android.png" width="128px"></a>
                         </div>
                         <br>
                            <!-- <b>AFSKL4EKRDP2AXWE</b> -->
                          <li v-if="fastatus == 'disable'"> Your secret code is : <b>{{profile.secret}}</b>

                            <!-- <div class=" w-qr"> <qrcode v-bind:value="this.qrcodevalue"></qrcode></div>   -->
                            <div v-if="fastatus == 'disable'" class="my-qr"> 
                                <div>
                                    <img v-bind:src="profile.QR_Image">
                                </div>
                            </div>
                        </li>
                         <br>
                          <br>


                         <p>3. Please enter Two Factor Token from Google Authenticator to verify correct setup: </p>


                         <div class="row">
                           <div class="col-md-3 col-6 offset-md-3">
                       
                             <input type="text" name="token" class="text-white qr-input form-control " placeholder="Enter Token" v-model="otp" v-validate="'required'"> 
                            <div class="tooltip2" v-show="errors.has('token')">
                                <div class="tooltip-inner"> <span v-show="errors.has('token')">{{ errors.first('token') }}</span>
                                </div>
                            </div>
                         </div>

                           <!-- <div class="enb">
                                 <button type="submit" class="diamond">{{((this.fastatus)=='disable')?'Enable':'Disable'}}</button>
                            </div> -->

                        
                         <div class="col-md-3  col-6">
                            <button @click="checkOtp()" type="button" class="btn btn-primary mr-1 mb-1">{{((this.fastatus)=='disable')?'Enable':'Disable'}}
                                 <div class="load"><i class="fa fa-spinner fa-spin"></i>Loading</div>
                            </button>
                         </div>
                         </div>
                     
                        </div>
                        <div class="col-12">
                          <!-- <button type="reset" class="btn btn-outline-warning mr-1 mb-1">Reset</button> -->
                        </div>
                      </div>
                    </div>
                  </form>
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
    //import usertitle from './UserTitle.vue'; 
        import Breadcrum from './BreadcrumComponent.vue';
        import Swal from 'sweetalert2';
        
        export default {  
            components: {
                Breadcrum,
               // usertitle
        
            }, 
            data() {
                return {
                    btcactive:true,
                    btcactive:true,
                    disablebtn:false, 
                    btc_address:'',
                    btc_addresses:'',
                    typingTimer:'',
                    btc_error:false,
                    doneTypingInterval:1000,
                    isValidBTCAddress:true,
                    valid:true,
        
        
                    ethactive:true,
                    ethactive:true,
                    eth_address:'',
                    eth_addresses:'',
                    eth_error:false,
                    isValidETHAddress:true,
                    fastatus:'',
                    otpstatus:'',
                    otp:'',
                    profile: {
                        fullname: '',
                        email: '',
                        mobile: '',
                        btc_address: '',
                        secret:'',
                    },
                    profile: [],
                    fastatus:"disable",
        
                }
            },
            computed:{
                isCompleteProfile(){
                    return this.profile.email  /*&& this.profile.btc_address && this.btcactive*/;
                },
            },
            mounted() {
                this.readProfile();
            },
            methods: {
        
        
                readProfile() { 
                    $(".load").hide(); 
                    $(".loadUpdate").hide(); 
                    axios.post('./get-profile-info', {
                    })
                    .then(response => {  
                        this.profile = response.data.data;
                        this.fastatus = this.profile.google2fa_status;
                    })
                    .catch(error => {
                    });
                },
        
                /*checkaddress(){
                    axios.post('check_address',{
                        network_type:'BTC',
                        address:this.profile.btc_address,
                    }).then(response=>{
                        if(response.data.code==200){
                            this.btcactive=true;
                        } else {
                            this.btcactive=false;  
                            this.btcmsg=response.data.message;
                        }
        
                    }).catch(error=>{
        
                    })
                },*/
        
               /*   updateUserData() { 
                        Swal({
                            title: 'Are you sure?',
                            text: `You want to update this user`,
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes'
                        }).then((result) => {
                            if (result.value) {                      
                                axios.post('user-update', {
                                    fullname: this.profile.fullname,
                                    email: this.profile.email,
                                    btc: this.profile.btc_address,
                                })
                                .then(response => {
                                    if(response.data.code == 200){
                                        this.$toaster.success(response.data.message);
                                    } else {
                                       this.$toaster.error(response.data.message);
                                    }
                                })
                                .catch(error => {
                                });
                            }
                        });
                    },*/
        
                /*    updateUserData(){
                        axios.post('user-update',{
                            fullname: this.profile.fullname,
                            email: this.profile.email,
                            btc: this.profile.btc_address,
                            eth: this.profile.eth_address,
                        }).then(response=>{
                            $(".load").hide();
                            $(".sub").show();
                            $('#editBankDetailsmodal').modal('toggle');
                              //$('#editBankDetailsmodal').modal('hide');
                              this.otpstatus = true;
                              if(response.data.code==200){
                                this.$toaster.success(response.data.message);
                              } else {
                                this.$toaster.error(response.data.message);
                              }
        
                            }).catch(error=>{
        
                            })
                        },*/
                        sendOTP(){
                            this.valid = false;
                            $(".loadUpdate").show(); 
                            $(".upd").hide(); 
                            $('#editBankDetailsmodal').modal('toggle');
                        //$('#editBankDetailsmodal').modal('show');
                        $(".loadUpdate").hide(); 
                        $(".upd").show(); 
        
                        this.valid = true; 
                      },
                      checkOtp() {
                        this.$validator.validateAll().then((result) => {   
                            if (!result) {
                                return false;
                            }

                        $(".load").show();
                        $(".sub").hide();
                        var status = '';
                        if(this.fastatus==='disable')
                        {status='enable'}
                        else if(this.fastatus==='enable')
                        {status = 'disable'};

                        axios.post('/2fa/validateloginotp', {
                            googleotp: this.otp,
                            secret: this.profile.secret,
                            status:status,
                        }).then(response => {
                            if (response.data.code == 200) {
                        $(".load").hide();
                        $(".sub").show();
                        this.fastatus = this.profile.google2fa_status;
                        //this.updateUserData();
                            // this.statedata=response.data.data;
                            // this.otp = '';
                        /*  $('#editBankDetailsmodal').modal('hide');
                        this.otpstatus = true;*/
                        /*$('#editBankDetails1').modal('show');*/
                        // $('#editBankDetailsmodal').modal('toggle');
                            //$('#editBankDetailsmodal').modal('hide');
                            this.readProfile();
                            this.$toaster.success(response.data.message);
                            this.otp=''
                        } else {
                        $(".load").hide();
                        $(".sub").show();
                        this.$toaster.error(response.data.message);
                        this.otp='';
                        }
                    }).catch(error => {
                    $(".load").hide();
                    $(".sub").show();
                    });
                }).catch(() => {
                        // Failed
           });
                        
        },
        
        
        
                           }      
                       }
</script>