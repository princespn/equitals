<template>
   <div id="layout-wrapper">
      <!--*******************
         Preloader start
         ********************-->
      <div id="preloader">
         <div class="waviy">
            <span style="--i:1">E</span>
            <span style="--i:2">q</span>
            <span style="--i:3">u</span>
            <span style="--i:4">i</span>
            <span style="--i:5">t</span>
            <span style="--i:6">a</span>
            <span style="--i:7">l</span>
            <span style="--i:8">s</span>
            <span style="--i:9">.</span>
            <span style="--i:10">.</span>
            <span style="--i:11">.</span>
            <span style="--i:12">.</span>
         </div>
      </div>
      <!--*******************
         Preloader end
         ********************-->
      <!--**********************************
         Main wrapper start
         ***********************************-->
      <div id="main-wrapper">
         <!--**********************************
            Nav header start
            ***********************************-->
         <div class="nav-header">
            <a class="brand-logo" href="https://www.equitals.com/" target="_blank">             
            <img src="public/user_files/assets/images/logo.svg" class="img-fluid">                
            </a>
            <div class="nav-control">
               <div class="hamburger" id="go">
                  <span class="line"></span><span class="line"></span><span class="line"></span>
               </div>
            </div>
         </div>
         <!--**********************************
            Nav header end
            ***********************************-->
         <!--**********************************
            Header start
            ***********************************-->
         <div class="header">
            <div class="header-content">
               <nav class="navbar navbar-expand">
                  <div class="collapse navbar-collapse justify-content-between">
                     <div class="header-left">
                        <div class="dashboard_bar">
                           Welcome To Equitals Corp Dashboard 
                        </div>
                     </div>
                     <ul class="navbar-nav header-right">
                        <li class="nav-item">
                            <a href="#/presentation" class="btn btn-primary d-sm-inline-block d-none" @click="moveTop"> 
                              <i class="fa fa-file-pdf"></i> Presentations<i class="las la-signal ms-3 scale5"></i>
                            </a>
                           <!-- <a href="#!" class="ins d-sm-inline-block d-none">Your package expires in 7 days</a> -->
                        </li>
                        <li class="mo-block idli">
                            <a href="#/my-profile" class="btn btn-primary" @click="moveTop">{{userdata.user_id}}</a>
                        </li>
                     </ul>
                  </div>
               </nav>
            </div>
         </div>
         <!--**********************************
            Header end ti-comment-alt
            ***********************************-->
         <!--**********************************
            Sidebar start
            ***********************************-->
         <div class="dlabnav">
            <div class="dlabnav-scroll">
               <ul class="metismenu" id="menu">
                  <li class="whitebg">
                     <a class="brand-logo" href="#">             
                        <img src="public/user_files/assets/images/logo-m.svg" width="50">
                        <span class="brand-title">
                        <img src="public/user_files/assets/images/logo-text.svg">        
                        </span>                
                     </a>
                  </li>
                  <li class="dropdown header-profile mo-none">
                      <router-link to="/my-profile" class="nav-link" role="button"  @click="hideNav">
                        <img src="public/user_files/assets/images/profile/profile.png" width="20" alt=""/>
                        <div class="header-info ms-3">
                           <small class="text-end font-w400" style="font-size: 14px;text-transform: uppercase;font-weight: 600;">My Profile</small>
                           <p class="font-w600 midd "><b>{{userdata.user_id}}</b></p>
                           <!-- <small class="text-end font-w400">{{userdata.fullname}}</small> -->
                        </div>
                     </router-link>
                     <div class="dropdown-menu dropdown-menu-end" style="display: none;">
                        <router-link to="/my-profile" class="dropdown-item ai-icon" @click="hideNav">
                           <!-- <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> -->                                
                           <span class="ms-2">Profile </span>
                        </router-link>
                     </div>
                  </li>
                  <li @click="hideNav">
                     <a href="#/dashboard" class="ai-icon" aria-expanded="false" @click="moveTop">
                        <i class="flaticon-025-dashboard"></i>
                        <!-- <span class="badge rounded-pill bg-primary float-end">2</span> -->
                        <span>CONTROL PANEL</span>
                     </a>
                  </li>

                  <li>
                     <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                     <i class="flaticon-068-plus"></i>
                     <span class="nav-text">MANAGE FUNDS</span>
                     </a>
                     <ul aria-expanded="false">
                      
                        <li @click="hideNav">
                           <a @click="moveTop" href="#/add-fund-one">Add New Fund</a>
                        </li>
                        <li @click="hideNav">
                           <a @click="moveTop" href="#/activation">Activation</a>
                        </li>
                        <li @click="hideNav">
                           <a @click="moveTop" href="#/self-topup">Activate Member's Account </a>
                        </li>
                        <li @click="hideNav">
                           <a @click="moveTop" href="#/topup-report">My Activations </a>
                        </li>
                        <li @click="hideNav">
                           <a @click="moveTop" href="#/downline-topup-report">Member's Activation</a>
                        </li>
                        <li @click="hideNav">
                           <a @click="moveTop" href="#/pending-add-deposite">Pending Fund Transactions</a>
                        </li>
                        <li @click="hideNav">
                           <a @click="moveTop" href="#/confirm-deposite">Confirmed Fund Transactions</a>
                        </li>
                     </ul>
                  </li>
                  <!-- <li>
                     <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                     <i class="flaticon-041-graph"></i>
                     <span class="nav-text">Activation</span>
                     </a>
                     <ul aria-expanded="false">                    
                        
                        
                     </ul>
                  </li> -->
                  <li>
                     <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                     <i class="flaticon-086-star"></i>
                     <span class="nav-text">MEMBERS</span>
                     </a>
                     <ul aria-expanded="false">
                        <li @click="hideNav">
                           <a @click="moveTop" href="#/tree-view">Member Structure </a>
                        </li>
                        <li @click="hideNav">
                           <a @click="moveTop" href="#/direct-user-list">Direct Member </a>
                        </li>
                        <li @click="hideNav">
                           <a @click="moveTop" href="#/team-view">All Members</a>
                        </li>
                     </ul>
                  </li>
                  <li>
                     <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                     <i class="flaticon-045-heart"></i>
                     <span class="nav-text">EARNINGS</span>
                     </a>
                     <ul aria-expanded="false">
                      <li @click="hideNav">
                           <a @click="moveTop" href="#/roi-income-report">ROI Earnings </a>
                        </li>

                        <li @click="hideNav">
                           <a @click="moveTop" href="#/direct-income-report">Direct Earnings</a>
                        </li>
                        
                        <li @click="hideNav">
                           <a @click="moveTop" href="#/binary-income-report">Binary Earnings </a>
                        </li>

                         <li @click="hideNav">
                           <a @click="moveTop" href="#/residual-income-report">Residual Bonus</a>
                        </li>
                       
                     </ul>
                  </li>

                  <li>
                     <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                     <i class="flaticon-013-checkmark"></i>
                     <span class="nav-text">WITHDRAWAL</span>
                     </a>
                     <ul aria-expanded="false">
                        <li @click="hideNav">
                           <a @click="moveTop" href="#/make-working-withdrawal">Place Withdrawal</a>
                        </li>
                        <li @click="hideNav">
                           <a @click="moveTop" href="#/withdrawals-income-report">Withdrawal Report </a>
                        </li>                           
                     </ul>
                  </li>
                  <li>
                     <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                     <i class="flaticon-381-repeat-1"></i>
                     <span class="nav-text">TRANSFER</span>
                     </a>
                     <ul aria-expanded="false">
                        <li @click="hideNav">
                           <a @click="moveTop" href="#/fund-to-fund-transfer">Fund Transfer</a>
                        </li>
                        <li @click="hideNav">
                           <a @click="moveTop" href="#/fund-transfer-report">Fund Transfer Report </a>
                        </li>                           
                     </ul>
                  </li>

                  <!-- <li @click="hideNav">
                     <router-link to="/make-working-withdrawal" class="ai-icon" aria-expanded="false">
                        <i class="flaticon-013-checkmark"></i>
                        <span>WITHDRAW</span>
                     </router-link>
                  </li> -->
                  <!-- <li>
                     <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                     <i class="flaticon-013-checkmark"></i>
                     <span class="nav-text">Benefits</span>
                     </a>
                     <ul aria-expanded="false">
                        <li @click="hideNav">
                           <router-link to="/make-working-withdrawal">Secure Working Profit</router-link>
                        </li>
                        <li @click="hideNav">
                           <router-link to="/withdrawals-income-report">Working Profit Secured</router-link>
                        </li>
                        <li commented><router-link to="/working-wallet-to-purchase-report">Working Profit Transferred to Activation Wallet</router-link></li>                            
                     </ul>
                  </li> -->
                  <!-- <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                     <i class="flaticon-072-printer"></i>
                     <span class="nav-text">Wallet Transfer</span>
                     </a>
                     <ul aria-expanded="false">
                     <li><router-link to="/transfer-topup-balance">Transfer Deposit Wallet Balance</router-link></li>
                     <li><router-link to="/purchase-to-purchase-report">Deposit To Deposit Wallet Report</router-link></li>
                     
                     </ul>
                     </li> -->
                  <li class="cp"><a @click.prevent="logout" class="ai-icon" aria-expanded="false">
                     <i class="flaticon-056-minus"></i>
                     <span class="nav-text">EXIT</span>
                     </a>
                  </li>
               </ul>
            </div>
         </div>
         <div class="modal fade" id="examplePrimaryModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
               <div class="modal-content modalBox">
                  <div class="modal-header-modal">
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-md-12">
                           <h4>Left Referral Links</h4>
                           <div class="Referral-l">
                              <input type="text" readonly class="form-control" id=""  :value="referrallink.link+'&position=1'">
                              <button onclick="myFunctionRefLeft()" class="btn btn-outline-primary waves-effect" id="">Copy Now!</button>
                           </div>
                        </div>
                        <div class="col-md-12">
                           <h4>Right Referral Links</h4>
                           <div class="Referral-l">
                              <input type="text" readonly class="form-control" id="" :value="referrallink.link+'&position=2'">
                              <button   id=""  onclick="myFunctionRefRight()" class="btn btn-outline-primary waves-effect" >Copy Now!</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</template>
<script>
   import moment from "moment";
   import { apiUserHost } from "../../user-config/config";
   export default {
     data() {
       return {
         rankdata:"",
         userdata: {
           fullname: "",
           email: "",
           is_franchise: "0"
         },
         is_franchise: "0",
         photo:'',
         usddata:'',
         referrallink: "",
       };
     },
     mounted() {
       this.getUserDetails();
       $(function(){
               $("#upload_link").on('click', function(e){
                   e.preventDefault();
                   $("#photo:hidden").trigger('click');
               });
           });
       
      // this.getUserProfile();
       //this.getProfileImg();
       /*this.getusddata();*/
      /* this.getrankdata();*/
     },
     methods: {
      moveTop(){
           document.body.scrollTop = 0;
          document.documentElement.scrollTop = 0;

        },
       clickSubMenu(){
     // alert('hieq2234ii');
   
       },
       hideNav(){
   
             var windowWidth = window.screen.width;      
             // alert(windowWidth);           
             if (windowWidth< 768) {
                // alert('hi');
   
                 // $('.pace-done').removeClass('nav-toggle');
                 $('#go').trigger('click');
                   // alert('hi');
   
             }
              
         },
       getusddata(){
         axios
           .get("getusddata", {})
           .then(response => {
             this.usddata = response.data.data.usddata;
             /*console.log(this.usddata);*/
             
           })
           .catch(error => {});
       },
       // hideNav() {
       //    alert('hiii');
        
       // },
   
     /*  getUserProfile() {
         axios
           .get("get-profile-info", {})
           .then(response => {
             this.userdata = response.data.data;
             this.is_franchise = response.data.data.is_franchise;
           })
           .catch(error => {});
       },*/
    
        getProfileImg(){ 
           axios.get('get-profile-img', {
           })
           .then(response => {
                 if (response.data.code == 200) {
   
                     this.photo = response.data.data.attachment;
                  }else{
                     this.photo = 'public/user_files/images/face7.jpg';
                  }
               
               
               
           })
           .catch(error => {
           }); 
       },
    getUserDetails() {
       axios
         .get("get-user-navigation", {})
         .then(response => {
           this.userdata = response.data.data;
           this.referrallink = response.data.data;
            if(this.userdata.is_franchise==1){
                $("#is_franchise").html(this.userdata.user_id + '( franchise )');
              }else{
                $("#is_franchise").html(this.userdata.user_id);
              }
   
         })
         .catch(error => {});
     },
   
       onUploadDocuments(upload_type) {  
                   /* Create FormData object */
                   let formData = new FormData();
                   /* 
                       Here we check file uploading type that is photo, pan card or address proof.
                       Append file upload to photo.
                   */
                   if(upload_type == 'photo'){
                        
                       if(this.$refs.photo.files[0] != ''){
                           formData.append('photo', this.$refs.photo.files[0]);
                       }               
                   }
                   if(upload_type == 'pan'){
                       if(this.$refs.pan.files[0] != ''){
                           formData.append('photo', this.$refs.pan.files[0]);
                       }             
                   }
                   if(upload_type == 'address'){
                       if(this.$refs.address.files[0] != ''){
                           formData.append('photo', this.$refs.address.files[0]);
                       }
                   }
                   /* Here we append file type that is photo, pan card or address proof. */
                   formData.append('name', upload_type);                
                   axios.post('upload-photos',
                       formData,
                       {
                           headers: {
                               'Content-Type': 'multipart/form-data'
                           }
                       }
                   )
                   .then(response => { 
                       if (response.data.code == 200) {
                         this.$toaster.success(response.data.message);
                         $('#update-user-documents').trigger("reset");
                         location.reload();
                         this.readUserProfile();
                         this.readUserDocuments();
                         location.reload();
                       }else{
                         this.$toaster.error(response.data.message);
                       }
                   })
               },
   
       logout() {
         localStorage.removeItem("user-token");
         //localStorage.removeItem('INR');
         // localStorage.removeItem('p_type');
         localStorage.removeItem("type");
         // if the request fails, remove any
         this.$router.push("/login");
         location.reload();
       }
     }
   };
</script>