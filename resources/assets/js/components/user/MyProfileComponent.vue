<template>
 <!--**********************************
   Content body start
   ***********************************-->
<div class="content-body">
   <div class="container-fluid">
      <div class="row page-titles">
         <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">App</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Profile</a></li>
         </ol>
      </div>
      <!-- row -->
      <div class="row">
         <div class="col-lg-12">
            <div class="profile card card-body px-3 pt-3 pb-0">
               <div class="profile-head">
                  <div class="photo-content">
                     <div class="cover-photo rounded"></div>
                  </div>
                  <div class="profile-info">
                     <div class="profile-photo">
                        <div class="profile-c">
                           <img src="public/user_files/assets/images/profile/logo-m.svg">
                        </div>
                        <i class="fa-solid fa-camera upload-profile"></i>
                     </div>
                     <div class="profile-details">
                        <div class="profile-name px-3 pt-2">
                           <h4 class="text-primary mb-0">{{profile.fullname}}</h4>
                           <p>{{profile.user_id}}</p>
                        </div>
                        <div class="profile-email px-2 pt-2">
                           <h4 class="text-muted mb-0">{{profile.email}}</h4>
                           <p>Email</p>
                        </div>
                       <!--  <div class="dropdown ms-auto">
                           <a href="#" class="btn btn-primary light sharp" data-bs-toggle="dropdown" aria-expanded="true">
                              <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                 <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <circle fill="#000000" cx="5" cy="12" r="2"></circle>
                                    <circle fill="#000000" cx="12" cy="12" r="2"></circle>
                                    <circle fill="#000000" cx="19" cy="12" r="2"></circle>
                                 </g>
                              </svg>
                           </a>
                           <ul class="dropdown-menu dropdown-menu-end">
                              <li class="dropdown-item"><i class="fa fa-user-circle text-primary me-2"></i> View profile</li>
                              <li class="dropdown-item"><i class="fa fa-users text-primary me-2"></i> Add to btn-close friends</li>
                              <li class="dropdown-item"><i class="fa fa-plus text-primary me-2"></i> Add to group</li>
                              <li class="dropdown-item"><i class="fa fa-ban text-primary me-2"></i> Block</li>
                           </ul>
                        </div> -->
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-xl-4">
            <div class="row">
               <div class="col-xl-12">
                  <div class="card">
                     <div class="card-body">
                        <div class="profile-statistics">
                           <div class="text-center">
                              <div class="row">
                                 <div class="col-lg-12">
                                    <img src="public/user_files/assets/images/resume.png" class="img-fluid w160">
                                 </div>
                                 <div class="col">
                                    <h3 class="m-b-0">${{ working_balance }}</h3>
                                    <span>Available Balance</span>
                                 </div>
                                 <div class="col">
                                    <h3 class="m-b-0">{{profile.totalDirect}}</h3>
                                    <span>Direct Members</span>
                                 </div>
                                
                              </div>
                              <div class="mt-4">
                                 <a @click="moveTop" href="#/add-fund-one" class="btn btn-primary mb-1 me-1">Add Funds</a> 
                                 <a @click="moveTop" href="#/make-working-withdrawal" class="btn btn-primary mb-1" >Withdrawls</a>
                              </div>
                           </div>
        
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-xl-8">
            <div class="card">
               <div class="card-body">
                  <div class="profile-tab">
                     <div class="custom-tab-1">
                        <ul class="nav nav-tabs">
                           <li class="nav-item"><a href="#my-posts" data-bs-toggle="tab" class="nav-link active show">General</a>
                           </li>
                           <li class="nav-item"><a href="#about-me" data-bs-toggle="tab" class="nav-link">Change Password</a>
                           </li>
                           <li class="nav-item"><a href="#profile-settings" data-bs-toggle="tab" class="nav-link">Payment info</a>
                           </li>
                        </ul>
                        <div class="tab-content">
                           <div id="my-posts" class="tab-pane fade active show">
                              <div class="my-post-content pt-3">
                                 <form class="row g-3">
                                    <div class="col-md-6">
                                       <label for="inputUserId" class="form-label">User Id</label>
                                       <input type="text" class="form-control" id="inputUserId" placeholder="Enter User Id" readonly="" v-model="profile.user_id">
                                    </div>
                                    <div class="col-md-6">
                                       <label for="inputName" class="form-label">Name</label>
                                       <input type="text" class="form-control" id="inputName" placeholder="Enter Name" v-model="profile.fullname"  v-on:keypress="isLetter($event)" v-validate="'required|alpha_spaces'">
                                    </div>
                                    <div class="col-md-6">
                                       <label for="inputEmail4" class="form-label">Email</label>
                                       <input type="email" class="form-control" id="inputEmail4" placeholder="Enter Email" v-model="profile.email" v-validate="'required|email'">
                                    </div>
                                    <div class="col-md-6">
                                       <label for="inputPassword4" class="form-label">Mobile No</label>
                                       <!-- <input type="text" class="form-control" id="inputMobileNo" placeholder="Enter Mobile No"> -->
                                       <vue-tel-input type="number" name="mobile"
                                      @onInput="onInput"
                                      defaultCountry="VG"
                                      placeholder="Enter Mobile Number"
                                      v-model="profile.mobile" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57"
                                      class="mm" v-validate="'required|max:22'">
                                    </vue-tel-input>                                      
                                    <div class="tooltip2" v-show="errors.has('mobile')">
                                      <div class="tooltip-inner">
                                        <span v-show="errors.has('mobile')">{{ errors.first('mobile') }}</span>
                                      </div>
                                    </div>
                                    </div>
                                    <div class="col-12">
                                       <!-- <button type="submit" class="btn btn-primary">Save Change</button> -->
                                         <button type="button" class="btn btn-primary" @click.prevent="sendOTP('profile')"
                              :disabled="!isCompleteProfile">Save Changes</button>
                                    </div>
                                 </form>
                              </div>
                           </div>
                           <div id="about-me" class="tab-pane fade">
                              <div class="profile-about-me pt-3">
                                 <form class="row g-3">
                                    <div class="col-md-4">
                                       <label for="inputOldPassword" class="form-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Old Password</font></font></label>
                                       <!-- <input type="text" class="form-control" id="inputUserId" placeholder="Enter Old Password"> -->
                                       <input name="old_password" class="form-control" required placeholder="Enter Your Old Password" id="old_password" type="password" v-model="updatepassword.old_password" v-validate="'required'" data-vv-as="old password" />
                              <div class="tooltip2" v-show="errors.has('old_password')">
                                  <div class="tooltip-inner">
                                     <span v-show="errors.has('old_password')">{{ errors.first('old_password') }}</span>
                                  </div>
                              </div>
                                    </div>
                                    <div class="col-md-4">
                                       <label for="inputNewPassword" class="form-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">New Password</font></font></label>
                                       <!-- <input type="text" class="form-control" id="inputUserId" placeholder="Enter New Password"> -->
                                        <input ref="new_password" name="new_password" class="form-control" placeholder="Enter Your New Password" id="new_password" type="password" v-model="updatepassword.new_password" v-validate="'required|min:6|max:30'" data-vv-as="new password"/>
                            <div class="tooltip2" v-show="errors.has('new_password')">
                              <div class="tooltip-inner">
                                <span v-show="errors.has('new_password')">{{ errors.first('new_password') }}</span>
                              </div>
                            </div>
                                    </div>
                                    <div class="col-md-4">
                                       <label for="inputRetypePassword" class="form-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Retype New Password</font></font></label>
                                       <!-- <input type="text" class="form-control" id="inputUserId" placeholder="Enter Retype New Password"> -->
                                       <input name="retype_password" class="form-control" placeholder="Re-Type New Password" id="retype_password" type="password" v-model="updatepassword.retype_password"  v-validate="'required'" data-vv-as="re-enter password" v-on:keyup="matchpassword"/>
                            <div class="tooltip2" v-show="errors.has('retype_password')">
                              <div class="tooltip-inner">
                                <span v-show="errors.has('retype_password')">{{ errors.first('retype_password') }}</span>
                              </div>
                            </div>
                                    </div>
                                    <div class="col-12">
                                       <button @click.prevent="sendOTP('password')" :disabled='!isCompletePassword || errors.any() || !isDisableBtn' type="button" class="btn btn-primary" style="width:auto">
                                       Update Password </button>
                                       <button type="reset" class="btn btn-danger">
                                         <font style="vertical-align: inherit;">
                                       <font style="vertical-align: inherit;">Reset</font></font></button>

                                       
                                    </div>
                                 </form>
                              </div>
                           </div>
                           <div id="profile-settings" class="tab-pane fade">
                              <div class="pt-3">
                                 <form class="row g-3">
                                    <div class="col-md-6">
                                       <label for="inputBTCAddress" class="form-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">BTC Address</font></font></label>
                                       <input type="text" id="btc_add" class="form-control" required placeholder="BTC Address" v-model="profile.btc_address" name="btc_address" v-on:input="checkaddress"/>
                                       <div v-if="!btcactive" class="tooltip2">
                                          <span class="text-danger error-msg-size tooltip-inner">{{ this.btcmsg }}</span>
                                       </div>
                                    </div>
                                    <div class="col-md-6">
                                       <label for="inputBTCAddress" class="form-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">TRX Address</font></font></label>
                                       <input type="text" id="trx_add" class="form-control" required placeholder="TRX Address" v-model="profile.trx_address" name="trx_address" v-on:input="checkTRXAddress(1)"/>
                                       <div v-if="!trxactive" class="tooltip2">
                                          <span class="text-danger error-msg-size tooltip-inner">{{ this.trxmsg }}</span>
                                       </div>
                                    </div>
                                    <div class="col-md-6">
                                       <label for="inputBTCAddress" class="form-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">BNB.BSC Coin Address</font></font></label>
                                       <input type="text" id="bnb_add" class="form-control" required placeholder="BNB.BSC Coin Address" v-model="profile.bnb_bsc_address" name="bnb_bsc_address" v-on:input="checkBNBAddress"/>
                                       <div v-if="!bnbactive" class="tooltip2">
                                          <span class="text-danger error-msg-size tooltip-inner">{{ this.bnbmsg }}</span>
                                       </div>
                                    </div>
                                    <div class="col-md-6">
                                       <label for="inputBTCAddress" class="form-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">USDT-TRC20 Address</font></font></label>
                                       <input type="text" id="usdt_add" class="form-control" required placeholder="USDT-TRC20 Address" v-model="profile.usdt_trc20_address" name="usdt-trc20_address" v-on:input="checkTRXAddress(2)"/>
                                       <div v-if="!usdttrxactive" class="tooltip2">
                                          <span class="text-danger error-msg-size tooltip-inner">{{ this.usdttrxmsg }}</span>
                                       </div>
                                    </div>
                                    <div class="col-md-6">
                                       <label for="inputBTCAddress" class="form-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Litecoin Address</font></font></label>
                                       <input type="text" id="ltc_address" class="form-control" required placeholder="Litecoin Address" v-model="profile.ltc_address" name="ltc_address" v-on:input="checkLTCAddress"/>
                                       <div v-if="!ltcactive" class="tooltip2">
                                          <span class="text-danger error-msg-size tooltip-inner">{{ this.ltcmsg }}</span>
                                       </div>
                                    </div>
                                    <div class="col-md-6">
                                       <label for="inputBTCAddress" class="form-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Dogecoin Address</font></font></label>
                                       <input type="text" id="doge_address" class="form-control" required placeholder="Dogecoin Address" v-model="profile.doge_address" name="doge_address"/>
                                    </div>
                                    <!-- <div class="col-md-6">
                                       <label for="inputBTCAddress" class="form-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">BCH Address</font></font></label>
                                       <input type="text" id="bch_address" class="form-control" required placeholder="BCH Address" v-model="profile.bch_address" name="bch_address"/>
                                    </div> -->
                                    <!-- <div class="col-md-6">
                                       <label for="inputBTCAddress" class="form-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">SHIBA Address</font></font></label>
                                       <input type="text" id="shib_address" class="form-control" required placeholder="SHIBA Address" v-model="profile.shib_address" name="shib_address"/>
                                    </div>
                                    <div class="col-md-6">
                                       <label for="inputBTCAddress" class="form-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Perfect Money Address</font></font></label>
                                       <input type="text" id="pm_address" class="form-control" required placeholder="Perfect Money Address" v-model="profile.pm_address" name="pm_address"/>
                                    </div> -->
                                    <div class="col-12">
                                       <button type="button" class="btn btn-primary" @click.prevent="sendOTP('address')"
                                :disabled="!isCompleteAddress || !trxactive || !bnbactive || !ltcactive || !usdttrxactive" style="width:auto"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Update Address</font></font></button>
                                    </div>
                                 </form>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- Modal -->                     
                  </div>
               </div>
            </div>
            <div class="modal fade" id="editBankDetailsmodal" role="dialog"  data-backdrop="static">
                    <div class="modal-dialog pops">
                      <!-- Modal content-->
                      <div class="modal-content">
                        <div v-show="otpstatus==false">
                          <div class="modal-header">
                            <h4 class="modal-title">Enter OTP</h4>
                            <button type="button" class="close" @click="closePopup()" data-dismiss="modal">&times;</button>
                          </div>
                          <div class="modal-body">
                            <div class="form-group">
                              <div class="row">
                                <div class="col-md-3 col-3">
                                  <img src="public/user_files/assets/images/otp.png" class="img-fluid">
                                </div>
                                <div class="col-md-9 col-9">
                                  <!--  <label for="btc_address">Otp </label> -->
                                  <input
                                    type="text"
                                    name="otp"
                                    id="otp_number"
                                    class="form-control"
                                    placeholder="Enter OTP"
                                    v-model="otp"
                                    v-validate="'required'"
                                  />

                                  <div class="tooltip2" v-show="errors.has('otp')">
                                    <div class="tooltip-inner">
                                      <span v-show="errors.has('otp')">{{ errors.first('otp') }}</span>
                                    </div>
                                  </div>
                                </div>

                                <br />
                                <br />
                                <br />
                                <br />
                                <div class="clearfix"></div>
                                <div class="col-md-12">
                                  <center>
                                    <button
                                      @click="verifyOtp()"
                                      type="button"
                                      class="btn btn-primary kbb-bbt"
                                    >Submit</button>
                                  </center>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
         </div>
      </div>
      <div class="row">
         <div class="col-xl-3 col-xxl-3 col-sm-6">
                  <div class="card overflow-hidden">
                     <div class="social-graph-wrapper widget-facebook">
                        <span class="s-icon"><i class="fab fa-facebook-f"></i></span>
                     </div>
                     <div class="row">
                        <div class="col-12 border-end">
                           <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                             <!--  <h4 class="m-1"><span class="counter">89</span> k</h4> -->
                            <a href="https://www.facebook.com/Equitals-108488091758849/" target="_blank">  <h3 class="m-0">Facebook</h3></a>
                           </div>
                        </div>
                       <!--  <div class="col-6 border-end">
                           <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                              <h4 class="m-1"><span class="counter">89</span> k</h4>
                              <p class="m-0">Friends</p>
                           </div>
                        </div>
                        <div class="col-6">
                           <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                              <h4 class="m-1"><span class="counter">119</span> k</h4>
                              <p class="m-0">Followers</p>
                           </div>
                        </div> -->
                     </div>
                  </div>
               </div>
               <div class="col-xl-3 col-xxl-3 col-sm-6">
                  <div class="card overflow-hidden">
                     <div class="social-graph-wrapper widget-linkedin">
                        <span class="s-icon"><i class="fab fa-youtube"></i></span>
                     </div>
                     <div class="row">
                        <div class="col-12 border-end">
                           <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                              <!-- <h4 class="m-1"><span class="counter">89</span> k</h4> -->
                            <a href="https://youtube.com/channel/UCza68JoRFBmGnd8Kxd6QFaQ" target="_blank">  <h3 class="m-0">Youtube</h3></a>
                           
                           </div>
                        </div>

                        <!-- <div class="col-6 border-end">
                           <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                              <h4 class="m-1"><span class="counter">89</span> k</h4>
                              <p class="m-0">Friends</p>
                           </div>
                        </div>
                        <div class="col-6">
                           <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                              <h4 class="m-1"><span class="counter">119</span> k</h4>
                              <p class="m-0">Followers</p>
                           </div>
                        </div> -->
                     </div>
                  </div>
               </div>
               <div class="col-xl-3 col-xxl-3 col-sm-6">
                  <div class="card overflow-hidden">
                     <div class="social-graph-wrapper widget-googleplus">
                        <span class="s-icon"><i class="fab fa-telegram"></i></span>
                     </div>
                     <div class="row">
                        <div class="col-12 border-end">
                           <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                              <!-- <h4 class="m-1"><span class="counter">89</span> k</h4> -->
                           <a href="https://t.me/equitals" target="_blank">  <h3 class="m-0">Telegram</h3></a>
                           </div>
                        </div>
                       <!--  <div class="col-6 border-end">
                           <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                              <h4 class="m-1"><span class="counter">89</span> k</h4>
                              <p class="m-0">Friends</p>
                           </div>
                        </div>
                        <div class="col-6">
                           <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                              <h4 class="m-1"><span class="counter">119</span> k</h4>
                              <p class="m-0">Followers</p>
                           </div>
                        </div> -->
                     </div>
                  </div>
               </div>
               <div class="col-xl-3 col-xxl-3 col-sm-6">
                  <div class="card overflow-hidden">
                     <div class="social-graph-wrapper widget-twitter">
                        <span class="s-icon"><i class="fab fa-twitter"></i></span>
                     </div>
                     <div class="row">
                        <div class="col-12 border-end">
                           <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                              <!-- <h4 class="m-1"><span class="counter">89</span> k</h4> -->
                              <a href="https://instagram.com/equitals?utm_medium=copy_link" target="_blank">  <h3 class="m-0">Instagram</h3></a>
                           </div>
                        </div>
                        <!-- <div class="col-6 border-end">
                           <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                              <h4 class="m-1"><span class="counter">89</span> k</h4>
                              <p class="m-0">Friends</p>
                           </div>
                        </div>
                        <div class="col-6">
                           <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                              <h4 class="m-1"><span class="counter">119</span> k</h4>
                              <p class="m-0">Followers</p>
                           </div>
                        </div> -->
                     </div>
                  </div>
               </div>
      </div>
   </div>
</div>
</template>
<script>
  import Breadcrum from './BreadcrumComponent.vue';
     export default {  
        components: {
           Breadcrum
        }, 
        data() {
           return {
              working_balance: 0,
              type:'',
              btcactive: true,
              otpstatus:'',
              otpverifyre:'',
              btcmsg: '',
              trxmsg: '',
              btc_preset: 0,
              otp:'',
              profile: {
                 code:'',
                 user_id: '',
                 sponser: '',
                 sponser_fullname: '',
                 sponser_country: '',
                 fullname: '',
                 city: '',
                 email: '',
                 btc_address: '',
                 perfect_money_address: '',
                 country: '',
                 mobile: '',
                 address: '',
                 ref_user_id: '',
                  holder_name: '',
                      bank_name: '',
                      branch_name: '',
                      pan_no: '',
                      ifsc_code: '',    
                      btc_address:'',
                      account_no:'',
                       bnb_address:"",
                       ethereum:"",
                       bnb_address:"",
              },
              isValidBTCAddress: true,
              updatepassword: {
                      old_password: '',
                      new_password: '',
                      retype_password: '',
              }, 
              profile: [],
              updatepassword:[],
              btcactive:true,
              disablebtn:false,  
              btc_address:'',
              btc_addresses:'',
              typingTimer:'',
              btc_error:false,
              doneTypingInterval:500,
              photo:'',
              isDisableBtn:true,
              trxactive:true,
              bnbactive:true,
              ltcactive:true,
              usdttrxactive:true,
             // photoSelected:'',
              proimg:'user_files/img/avatar-1-256.png',
  
           }
        },
        computed: {

       
          isCompleteProfile() {
            return (
              this.profile.fullname &&
              this.profile.email &&
              this.profile.mobile
            );
          },
          isCompleteAddress() {
            return (
              this.profile.btc_address || this.profile.trx_address  || this.profile.bnb_bsc_address || this.profile.usdt_trc20_address || this.profile.ltc_address || this.profile.doge_address
            );
          },
          isCompletePassword(){
            return this.updatepassword.old_password && this.updatepassword.new_password && this.updatepassword.retype_password;
          }
        },
        mounted() {
           this.readProfile();
           this.getWorkingWithdrawal();
           //this.getPackages();
          // this.getProfileImg();
        },
        methods: {
         moveTop(){
           document.body.scrollTop = 0;
          document.documentElement.scrollTop = 0;

        },
               isLetter(e) {
  let char = String.fromCharCode(e.keyCode); // Get the character
  if(/^[A-Za-z ]+$/.test(char)) return true; // Match with regex 
  else e.preventDefault(); // If not match, don't add to input text
},
           getWorkingWithdrawal() {
      axios
        .get("get-working-balance", {})
        .then((response) => {
          // console.log(response)
          if (response.data.code == 200) {
            this.working_balance =
              typeof response.data.data == Object ? 0 : response.data.data;
          }
        })
        .catch((error) => {});
    },
            onInput({ number, isValid, country }) {
         // console.log(number)
         if (number) {
           this.checkMobileValid = isValid;
           this.getcountry = country;
           // this.temp = number.split(" ");
           //   this.temp.shift();
           this.getMobile = number.input;
           this.profiledata.mobile = this.getMobile;
           this.profiledata.country = country.iso2;
           this.getCountryCode = country.iso2;
         }
       },
            readProfile() {      
              axios.post('get-profile-info', {
              })
              .then(response => {  
                 this.profile = response.data.data;
                 this.btc_addresses = response.data.data.btc_address;
              })
              .catch(error => {
              });
            },
            checkTRXAddress(addFor){
               var trx_address = "";
               if(addFor == "1"){
                  trx_address = ""+this.profile.trx_address+"";
               }else{
                  trx_address = ""+this.profile.usdt_trc20_address+"";
               }
               if(trx_address.charAt(0) == 't' || trx_address.charAt(0) == 'T' || trx_address == ''){
                  this.trxactive = true;
                  this.usdttrxactive = true;
                  this.trxmsg = "";
                  this.usdttrxmsg = "";
               }else{
                  if(addFor == 1){
                     this.trxactive = false;
                     this.trxmsg = "TRX Address should be start with 'T or t'";
                  }else{
                     this.usdttrxactive = false;
                     this.usdttrxmsg = "USDT-TRC20 Address should be start with 'T or t'";
                  }
               }
            },
            checkLTCAddress(){
               let ltc_address = ""+this.profile.ltc_address+"";
               // console.log(ltc_address.substring(1,4));
               if(ltc_address.charAt(0) == '3' || ltc_address.charAt(0) == 'L' || ltc_address.charAt(0) == 'M' || ltc_address == ''){
                  this.ltcactive = true;
                  this.ltcmsg = "";
               }else{
                  this.ltcactive = false;
                  this.ltcmsg = "Litecoin Address should be start with '3 or L or M'";
               }
            },

            checkBNBAddress(){
               let bnb_bsc_address = ""+this.profile.bnb_bsc_address+"";
               if(bnb_bsc_address.charAt(0) == '0' || bnb_bsc_address.charAt(0) == '1' || bnb_bsc_address == ''){
                  this.bnbactive = true;
                  this.bnbmsg = "";
               }else{
                  this.bnbactive = false;
                  this.bnbmsg = "BNB-BSC Address should be start with '0 or 1'";
               }
            },
            getPackages() {
              axios.get('get-packages', {})
                  .then(response => {
                      this.getpackages = response.data.data;
                      this.type = response.data.data[0]['type'];
                  })
                  .catch(error => {});
            },
            updateUserData() {
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
                                mobile: this.profile.mobile,
                                btc: this.profile.btc_address,
                                country: this.profile.country,
                                account_no: this.profile.account_no,
                                holder_name: this.profile.holder_name,
                                pan_no: this.profile.pan_no,
                                bank_name: this.profile.bank_name,
                                ifsc_code: this.profile.ifsc_code,
                                branch_name: this.profile.branch_name,

                            })
                            .then(response => {
                                if (response.data.code == 200) {
                                    this.$toaster.success(response.data.message);
                                } else {
                                    this.$toaster.error(response.data.message);
                                }
                            })
                            .catch(error => {});
                    }
                });
            },
            getCountry() {
                axios.get("country").then(response => {
                    this.countries = response.data.data;
                }).catch(error => {

                });
            },
            verifyOtp() {
               
                    if (this.otpverifyre == "profile") {
                      this.updateUserDetails();
                    } else if (this.otpverifyre == "password") {
                      this.updateUserPassword();
                    } else if (this.otpverifyre == "address") {
                      this.changeAddress();
                    }
                  
            },
            // verifyOtp() {
            //   axios
            //     .post("verify-user-otp", {
            //       otp: this.otp,
            //     })
            //     .then((response) => {
            //       if (response.data.code == 200) {
            //         // this.otpVerified = true;
            //         this.$toaster.success(response.data.message);
            //         this.otpSent = true;
            //         this.optVerified = true;
            //         if (this.otpverifyre == "profile") {
            //           this.updateUserDetails();
            //         } else if (this.otpverifyre == "password") {
            //           this.updateUserPassword();
            //         } else if (this.otpverifyre == "address") {
            //           this.changeAddress();
            //         }
            //         // this.otp = '';
            //         $("#editBankDetailsmodal").modal("hide");
            //       } else {
            //         this.$toaster.error(response.data.message);
            //       }
            //     })
            //     .catch((error) => {
            //       this.message = "";
            //     });
            // },
            updateUserDetails(){
              axios.post('update-user-deatils',{
                  otp: this.otp,
                  fullname: this.profile.fullname,
                  email: this.profile.email,
                  mobile: this.profile.mobile,
                  btc: this.profile.btc_address,
                  country: this.profile.country,
                  account_no: this.profile.account_no,
                  holder_name: this.profile.holder_name,
                  pan_no: this.profile.pan_no,
                  bank_name: this.profile.bank_name,
                  ifsc_code: this.profile.ifsc_code,
                  branch_name: this.profile.branch_name,
              }).then(response=>{
                  if(response.data.code == 200){
                     // this.statedata=response.data.data;
                     // this.otp = '';
                      $('#editBankDetailsmodal').modal('hide');
                     this.otpstatus = false; 
                     /*$('#editBankDetails1').modal('show');*/
                     // this.$router.push({ path: "my-profile" });
                     this.$toaster.success(response.data.message);
                     // location.reload();
                  }else{    
                       this.$toaster.error(response.data.message);
                     }
              }).catch(error=>{
              }) 
            },
            checkOtp(){
              axios.post('checkotp1',{
                  otp:this.otp,
                  fullname: this.profile.fullname,
                  email: this.profile.email,
                  mobile: this.profile.mobile,
                  btc: this.profile.btc_address,
                  country: this.profile.country,
                  account_no: this.profile.account_no,
                  holder_name: this.profile.holder_name,
                  pan_no: this.profile.pan_no,
                  bank_name: this.profile.bank_name,
                  ifsc_code: this.profile.ifsc_code,
                  branch_name: this.profile.branch_name,
              }).then(response=>{
                  if(response.data.code == 200){
                     // this.statedata=response.data.data;
                     // this.otp = '';
                      $('#editBankDetailsmodal').modal('hide');
                     this.otpstatus = false; 
                     /*$('#editBankDetails1').modal('show');*/
                     this.$toaster.success(response.data.message);

                  }else{    
                       this.$toaster.error(response.data.message);
                     }
              }).catch(error=>{
              }) 
            },
            closePopup(){
              $("#editBankDetailsmodal").modal("hide");
            },
            checkValidBTCAddr() {
              let that = this;
              that.isValidBTCAddress = false;
              that.btc_error = false;
              $(".loaderD").html('Checking BTC Address..<i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="font-size:16px"></i>');
              clearTimeout(this.typingTimer); 

              if (/\s/.test(that.profile.btc_address)) {
                 that.errors.remove('btc_address'); 
                 that.errors.add({field: 'btc_address', msg: 'Spaces not allowed in address'});
                 $(".loaderD").html("");
                 return false;
              }else{
                 that.errors.remove('btc_address'); 
              }
             
              that.typingTimer = setTimeout(function(){
              
              if(that.profile.btc_address == "")
              {
                  $(".loaderD").html("");
                  that.isValidBTCAddress=true;
                  that.btc_error = false;
                  that.errors.remove('btc_address');
              }
              else
              {  
                that.errors.add({
                    field: 'btc_address',
                    msg: 'checking for valid btc address'
                });
                that.isValidBTCAddress = false;
                  axios.post('check_address', {
                      address: that.profile.btc_address,
                      network_type: 'BTC'
                  }).then(response => {
                    if(response.data.code === 200){
                      $(".loaderD").html("");
                      that.isValidBTCAddress = true;
                      that.btc_error = false;
                           that.errors.remove('btc_address'); 
                      } else {
                        $(".loaderD").html("");
                        that.btc_error = true;
                        that.isValidBTCAddress = false;
                         that.errors.remove('btc_address'); 
                          that.errors.add({field: 'btc_address', msg: 'Enter valid BTC address.'});
                      }
                  }).catch(error => {
  
                  });
                }
              }, this.doneTypingInterval);
            },  
            sendOTP(e){
              this.otpverifyre = e;
              document.getElementById('otp_number').value = ''; 
              axios.post('sendOtp-update-user-profile').then(response=>{

                    if(response.data.code == 200){
                        //console.log(response);
                        this.$toaster.success(response.data.message);
                        //this.statedata=response.data.data.message;
                   
                    $('#editBankDetailsmodal').modal('show');

                    }else{                 
                           this.$toaster.error(response.data.message);
                       }
                }).catch(error=>{
                })  
            },
            updateUserPassword() {
                this.disablebtn = true;
                var new_password = this.updatepassword.new_password;
                var retype_password = this.updatepassword.retype_password;
                if (new_password == retype_password) {
                    axios.post('change-password', { 
                        otp: this.otp,                   
                        current_pwd: this.updatepassword.old_password,
                        new_pwd: this.updatepassword.new_password,
                        conf_pwd: this.updatepassword.retype_password,           
                    })
                    .then(response => {     
                        if(response.data.code === 200) {  
                        this.$toaster.success(response.data.message);
                        $('#update-user-password').trigger("reset"); 
                        $('#editBankDetailsmodal').modal('hide');
                        this.$router.push({ path: "my-profile" });
                        this.otpstatus = false; 
                        this.clearPassword()  
                         this.disablebtn = false;
                         // location.reload();
                        }else{
                            this.$toaster.error(response.data.message);
                             this.disablebtn = false;
                        }                     
                    })
                } else {
                    this.$toaster.error('New Password and Reset Password Not Matched...');
                     this.disablebtn = false;
                }                
            },
            clearPassword() {
                this.updatepassword.old_password = '';
                this.updatepassword.new_password = '';
                this.updatepassword.retype_password = '';
            },
            uploadImameModal(){
              $('#upload-modal').modal('show');
            },
            photoSelected(evt) {
               // alert();
                $('#uploadPhoto').attr('disabled',false);
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
              /*if(upload_type == 'pan'){
                  if(this.$refs.pan.files[0] != ''){
                      formData.append('photo', this.$refs.pan.files[0]);
                  }             
              }
              if(upload_type == 'address'){
                  if(this.$refs.address.files[0] != ''){
                      formData.append('photo', this.$refs.address.files[0]);
                  }
              }*/
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
                  this.$toaster.success(response.data.message);
                  $('#update-user-documents').trigger("reset");
                  this.getProfileImg();
                 //this.readUserDocuments();
              })
            },
            getProfileImg(){
               axios.post('get-profile-img').then(response=>{

                    if(response.data.code == 200){
                        this.proimg = response.data.data.attachment;
                    }else{                 
                           //this.$toaster.error(response.data.message);
                       }
                }).catch(error=>{
                })  
            },
            changeAddress(){
              axios.post('change-address', {   
                  otp: this.otp,                   
                  btc: this.profile.btc_address,
                  trn: this.profile.trx_address,        
                  bnb_bsc: this.profile.bnb_bsc_address ,         
                  usdt_trc20: this.profile.usdt_trc20_address, 
                  ltc:this.profile.ltc_address,
                  doge: this.profile.doge_address,
                  bch: this.profile.bch_address,
                  shib: this.profile.shib_address,
                  pm : this.profile.pm_address,    
              })
              .then(response => {     
                  if(response.data.code === 200) {  
                    this.$toaster.success(response.data.message);
                    $('#editBankDetailsmodal').modal('hide');
                     this.otpstatus = false; 
                     this.$router.push({ path: "my-profile" });
                    this.disablebtn = false;
                    // location.reload();
                  }else{
                    this.$toaster.error(response.data.message);
                    this.disablebtn = false;
                  }                     
              });
            },
            checkaddress() {
              this.btcactive = false;
              this.btcmsg = "Bitcoin address is not valid";
              let btc_address = ""+this.profile.btc_address+"";
               // console.log(ltc_address.substring(1,4));
               if(btc_address.charAt(0) == '3' || btc_address.charAt(0) == '1' || btc_address.charAt(0) == 'b' || btc_address == ''){
                  axios
                   .post("check_address", {
                     network_type: "BTC",
                     address: this.profile.btc_address
                   })
                   .then(response => {

                     if (response.data.code == 200) {
                       this.btcactive = true;
                        this.btcmsg = "";
                     } else {
                       this.btcactive = false;
                       this.btcmsg = response.data.message;
                     }
                   })
                   .catch(error => {});
                  
               }else{
                  this.btcactive = false;
                  this.btcmsg = "Bitcoin Address should be start with '3 or 1 or b'";
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