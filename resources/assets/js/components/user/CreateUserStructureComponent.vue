
<template>
  <div>
    <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="header-navbar-shadow"></div>
      <div class="content-wrapper">
        <div class="content-header row">
          <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
              <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Create User Structure</h2> 
              </div>
            </div>
          </div>

        </div>
        <div class="row text-center">
          <div class="col-lg-6">
            <h2 class="smm-resp">PURCHASE WALLET BALANCE: <br>${{selftopup.top_up_Wallet_balance}}</b></h2>
          </div>
          <div class="col-lg-6">
            <h2 class="smm-resp">NEW FUND WALLET BALANCE: <br>${{selftopup.fund_Wallet_balance}}</b></h2>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="row justify-content-center clearfix">
          <div class="col-md-6 cuss">
             

            <div class="card-content">
              <div class="card-body pt-0">

                <form class="slctbox register clearfix" @submit.prevent="registerUser">
                  <div class="form-group">
                    <label>Transcation Type</label>
                    <select id="transcation_type" v-model="register.transcation_type" class="form-control">
                      <option value="">Select Transcation Type</option>
                      <option value="1">Purchase wallet (max 50%) + New Fund Wallet </option>
                      <option value="2">New Fund Wallet</option>
                    </select> 
                  </div> 
                  <div class="form-label-group">
                    <input type="text" name="referral_id" class="form-control" placeholder="Your Sponsor's Username" v-model="register.referral_id" v-validate="'required'" v-on:input="checkuserexist(2)" id="referral_id" data-vv-as="referral id">
                    <div v-if='!useractive' class="tooltip2">
                      <span class=" text-danger error-msg-size tooltip-inner"> {{ this.usermsg }}</span>
                    </div>
                    <label for="inputEmail">Sponsor's ID</label>
                  </div>


                  <div class="form-label-group">
                    <input type="text" name="no_stucture" class="form-control" placeholder="Enter Number of Structures" v-model="register.no_stucture"  v-on:input="calculateamount()" v-validate="'required'">
                    <div class="loaderD"></div>
                    <div class="tooltip2" v-show="errors.has('no_stucture')">
                      <div class="tooltip-inner">
                        <span v-show="errors.has('no_stucture')">{{ errors.first('no_stucture') }}</span>
                      </div>
                    </div>
                    <label for="inputEmail">Number of Structures </label>
                  </div>

                  <div class="form-label-group">
                    <input type="text" class="form-control" name="fullname" placeholder="Enter Full Name" v-model="register.fullname" v-validate="'required|min:5|max:1100'" data-vv-as="fullname" v-on:input="checkuserexist(1)">
                    <div class="tooltip2" v-show="errors.has('fullname')">
                      <div class="tooltip-inner">
                        <span v-show="errors.has('fullname')">{{ errors.first('fullname') }}</span>
                      </div>
                    </div>
                    <label for="inputName">Fullname</label>
                  </div>
                  <div class="form-label-group">
                    <input type="Email" name="email" class="form-control" placeholder="Enter Email Id" v-model="register.email" v-validate="'required|email|max:70'" data-vv-as="email">
                    <div class="tooltip2" v-show="errors.has('email')">
                      <div class="tooltip-inner">
                        <span v-show="errors.has('email')">{{ errors.first('email') }}</span>
                      </div>
                    </div>
                    <label for="inputEmail">Email</label>
                  </div>
                  <div class="form-label-group">
                    <vue-tel-input v-validate="'required'" @onInput="onInput" defaultCountry="VG" placeholder="Enter Mobile Number" v-model="register.mobile" ></vue-tel-input>

                    <div class="tooltip2" v-show="errors.has('mobile')">
                      <div class="tooltip-inner">
                        <span v-show="errors.has('mobile')">{{ errors.first('mobile') }}</span>
                      </div>
                    </div>
                    <label for="inputMobile">Mobile</label>
                  </div>

                  <div class="form-label-group mt-30">
                    <input type="text" style='color: #000!important;' name="topup_amount" class="form-control" placeholder="Amount" v-model="register.topup_amount" readonly="">
                    <div class="loaderD"></div>
                    <div class="tooltip2" v-show="errors.has('topup_amount')">
                      <div class="tooltip-inner">
                        <span v-show="errors.has('topup_amount')">{{ errors.first('topup_amount') }}</span>
                      </div>
                    </div>
                    <label for="inputEmail">Amount</label>
                  </div>



                  <div class="form-label-group">
                    <input type="text" name="amount" class="form-control" placeholder="Amount" v-model="register.amount" readonly="">
                    <div class="loaderD"></div>
                    <div class="tooltip2" v-show="errors.has('amount')">
                      <div class="tooltip-inner">
                        <span v-show="errors.has('amount')">{{ errors.first('amount') }}</span>
                      </div>
                    </div>
                    <label for="inputEmail">Total Amount</label>
                  </div>

                  <div class="form-label-group reg_ico2_o">
                    <input ref="password" id="password" data-toggle="password" type="Password" name="password" class="form-control" placeholder="Enter Password" v-model="register.password" v-validate="'required|min:6|max:30'" v-on:keyup="onPasswordClick" data-vv-as="password">

                    <span class="reg_ico2" style="top: 7px;"><i @click="showPass" aria-hidden="true" :class="eye"></i></span>
                    <div class="tooltip2" v-show="errors.has('password')">
                      <div class="tooltip-inner">
                        <span v-show="errors.has('password')">{{ errors.first('password') }}</span>
                      </div>
                    </div>
                    <label for="inputPassword">Password</label>
                  </div>
                  <small class="form-label-group text-danger" v-show="password_validation_msg">
                    Note:- Password must be more than 6 characters. It should contain uppercase, lowercase, numerical and special characters.              
                  </small> 



                  <button type="submit" :disabled="errors.any() || !isCompleteRegister || isActiveBtn==false" value="Register" class="sbmt btn btn-primary float-right btn-inline mb-50">Create Structure</button>

                </form>
              </div>
            </div> 
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
import 'vue-tel-input/dist/vue-tel-input.css';
import VueTelInput from 'vue-tel-input';
import { domain } from'./../../user-config/config';
export default {
  components: {
    VueTelInput,
  },
  data() {
    return {
      package_id: 0,
      usermsg1:'',
      weburl:'',
      useractive: true,
      useractive1: false,
      register: {
        user_id: "",
        fullname: "",
        email: "",
        referral_id: "",
        mobile: "",
        //btc_address: "",
        password: "",
        confirm_password: "",
        position: "",
        country: "",
        transcation_type:"",
        amount:0,
        //secret_question:'',
       // secret_ans:'',
     },
     selftopup: {
      top_up_wallet: 0,
      top_up_wallet_withdraw: 0,
      top_up_Wallet_balance: 0,
      package_id: 0,
      fund_wallet: 0,
      fund_wallet_withdraw: 0,
      fund_Wallet_balance: 0
    },
    topup: {
      user_id: "",
      pin: "",
      product_id: null,
      hash_unit: "",
      payment_type: "BTC",
      package_id: ""
        //username:'',
      },
      packagelists: {
        id: "",
        name: ""
      },
      countries: {
        iso_code: "",
        country: ""
      },
      flag_for_hide_validation_messege: "",
      icon: "../public/user_files/assets/img/icon-mlm.png",
      logo: "../public/user_files/assets/img/logo.png",
      hostName: window.location.origin,
      checkMobileValid:true,
      getcountry:'',
      getMobile:'',
      temp:'',
      selected:true,
      isActiveBtn:true,
      queArr:'',
      terms:'',
      agree:'',
      rand_no:'',
      captcha:'',
      captchamatch:false,
      captchamatcherror:'',
      btc_error:'',
      typingTimer:'',
      doneTypingInterval:500,
      showpassword:0,
      alreadyref:0,
      is_referral:'',
      eye:'fa fa-eye-slash',
      password_validation_msg:true
    };
  },
  computed: {
    isCompleteRegister() {
      return (
        //this.register.user_id 
       // && !this.btc_error 
       this.register.user_id &&
       this.register.amount &&
       this.register.topup_amount &&
       this.register.no_stucture &&
       this.register.password &&
       this.register.mobile &&
       this.register.email &&
       this.register.transcation_type
            // this.register.confirm_password 
            /*this.register.mobile*/ /*&& this.register.position*/ 
       // this.selected &&
       //this.register.position &&
       /*this.register.password &&
        this.register.confirm_password *///&&
         //this.register.btc_address &&
        // this.checkMobileValid&&
       // this.register.mobile
        // this.register.eth_address
        );
    }
  },
  mounted() {
    this.register.topup_amount = 50;
    this.currentgetUserId();
    var Topup = this.$route.params.topupdata;
    this.register.balanceamount = this.$route.params.balanceamount;
    this.getUserDashboard();
    //alert(balanceamount);
    this.register.user_id="DX"+parseInt(Math.floor(Math.random()*900000) + 100000);
    this.weburl = domain;
   // alert(this.weburl);
   /*this.getCountry();*/
   /*this.getQuestions();*/
   /*this.getRandomNo();*/
   /*this.getPackageList();*/
   (this.one_letter = "invalid"),
   (this.greater_than_6 = "invalid"),
   (this.one_capital_letter = "invalid"),
   (this.special_char = "invalid"),
   (this.one_number = "invalid"),
   (this.starting_with_letter = "invalid"),
   (this.flag_for_hide_validation_messege = false);
   if((this.$route.query.ref_id != undefined) && (this.$route.query.ref_id != '')){
       // this.register.referral_id  = this.$route.query.ref_id;
       var uniqueid = this.$route.query.ref_id;
       this.register.position = this.$route.query.position;
       this.getUserId(uniqueid)
        //this.checkuserexist();
        this.alreadyref = 1;

      }

    },
    methods: {
      changeSelect(event) {
      let user = _.split(event.target.value, "-", 2); //using lodash here. You can also just use js split func
      let id = user[0]; // your id
      //console.log(this.arrProduct);
      this.min_hash = this.arrProduct[id].min_hash;
      this.max_hash = this.arrProduct[id].max_hash;
      this.hashvalidation();
      // this.activeDiv = true;
      // this.usermsg='Amount should be on range ' + this.min_hash + ' to ' + this.max_hash;
    },
    calculateamount(){
      /*alert(this.register.no_stucture);*/
      if(this.register.no_stucture <= 1100 )
      {
        this.register.amount=this.register.no_stucture *50;  
      }else{
        this.$toaster.error('Number of structure should be upto 1000 only');
        this.register.no_stucture = null;
        this.register.amount = null;
      }
      
    },
    currentgetUserId() {
      axios
      .get("gecurrentuser", {})
      .then(response => {
        console.log(response);
        this.register.referral_id = response.data.data;

      })
      .catch(error => {});
    },
    getPackageList() {
      axios
      .get("get-packages", {})
      .then(response => {
        console.log(response);
        this.packagelists = response.data.data;
        for (var i in this.packagelists) {
          this.arrProduct[this.packagelists[i].id] = this.packagelists[i];
        }

      })
      .catch(error => {});
    },
    onInput({ number, isValid, country }) {
     if(number)
      this.checkMobileValid =isValid;
    this.getcountry=country;
    this.temp = number.split(" ");
    this.temp.shift();
    this.getMobile=this.temp.join('');
  },
  onPasswordClick() {
    this.one_letter = "invalid";
    this.greater_than_6 = "invalid";
    this.one_capital_letter = "invalid";
    this.special_char = "invalid";
    this.one_number = "invalid";
    this.starting_with_letter = "invalid";
    this.flag_for_hide_validation_messege = true;

    if (
      this.register.password.substring(0, 1) ==
      this.register.password.match(/[A-z]/)
      ) {
      this.starting_with_letter = "valid";
  }

  if (
    this.register.password.length >= 6 &&
    this.register.password.length <= 15
    ) {
    this.greater_than_6 = "valid";
}
if (this.register.password.match(/[a-z]/)) {
  this.one_letter = "valid";
}

if (this.register.password.match(/[A-Z]/)) {
  this.one_capital_letter = "valid";
}
if (this.register.password.match(/\d/)) {
  this.one_number = "valid";
}

if (this.register.password.match(/[^a-zA-Z0-9\-\/]/)) {
  this.special_char = "valid";
}
if (this.register.password.match(/\s/g)) {
  this.special_char = "invalid";
}
if (
  this.one_letter === "valid" &&
  this.greater_than_6 === "valid" &&
  this.one_capital_letter === "valid" &&
  this.special_char === "valid" &&
  this.one_number === "valid" &&
  this.starting_with_letter === "valid"
  ) {
  this.flag_for_hide_validation_messege = false;
} else {
  this.errors.add({
    field: "password",
    msg: "password not valid"
  });
}
},
getCountry() {
  axios
  .get("country", {})
  .then(response => {
    this.countries = response.data.data;
  })
  .catch(error => {});
},
getQuestions() {
  axios
  .get("get-questions", {})
  .then(response => {
    this.queArr = response.data.data;
  })
  .catch(error => {});
},
getRandomNo() {
  axios
  .get("generate-random-no", {})
  .then(response => {
    this.rand_no = response.data.data;
  })
  .catch(error => {});
},


check($event){
 if($("#agree").prop('checked') == true){
   this.agree = 1;
 }else{
   this.agree = 0;
 } 
},

checkuserexist(para) {
     // alert(para);
     if(para == 1){
      var user = this.register.user_id;
    }else{
      var user = this.register.referral_id;
    }
    axios
    .post("checkuserexist", {
      user_id: user
    })
    .then(response => {
      if (response.data.code == 200) {
        if(para == 1){
          this.useractive1 = true;
          this.usermsg1 = "user Already exist with this id";
          this.isActiveBtn = false;
        }else{
          this.useractive = true;
          if(this.useractive1 == false){
            this.isActiveBtn = true;  
          }

        }

      } else {
        if(para == 1){
          this.useractive1 = false;
          if(this.useractive == true){
            this.isActiveBtn = true;  
          }
             //this.isActiveBtn = true;
             this.usermsg1 = response.data.message;
           }else{
            this.useractive = false;
            this.isActiveBtn = false;
            this.usermsg = response.data.message;
          }

        }
      })
    .catch(error => {});
  },

  getUserId() {
    axios
    .post("get-user-id", {
      uid: this.$route.query.ref_id
    })
    .then(response => {
      if (response.data.code == 200) {
       this.register.referral_id = response.data.data;
       $('#referral_id').prop('readonly',true);
       $('#transcation_type').attr('disabled','disabled');
       this.useractive = true;
     } else {
      this.useractive = false;
      this.usermsg = response.data.message;
    }
  })
    .catch(error => {});
  },
  matchCaptcha(){
   if(parseInt(this.captcha) != parseInt(this.rand_no)){
        /*this.errors.add({
                        field: 'captcha',
                        msg: 'Captcha not match'
                      });*/
       // alert();                
       this.captchamatch  = false;
       this.captchamatcherror="captcha not match";               
       // return false;
     }else{
        // alert();
        this.captchamatcherror="";  
        this.captchamatch  = true;  
         /*this.errors.remove({
                        field: 'captcha',
                        
                      });*/
       // return true;
     }
   },
   registerUser() {

    this.isActiveBtn = false;
      // var password = this.register.password;
      // var confirm_password = this.register.confirm_password;
      // if (password == confirm_password) {
        /*alert(this.topup.package_id);*/
      /* alert(this.topup.package_id);
      alert(this.topup.package_id);*/
      // alert(this.register.balanceamount);
      axios
      .post("createstructure", {

        user_id:this.register.user_id,
        amount:this.register.amount,
        topup_amount:this.register.topup_amount,
        no_stucture:this.register.no_stucture,
        password: this.register.password,
        password_confirmation: this.register.password,
        balanceamount :this.register.balanceamount,

        email: this.register.email,
        mobile: this.register.mobile,
        fullname: this.register.fullname,
        transcation_type: this.register.transcation_type,
            /*ref_user_id: this.register.referral_id,
            password: this.register.password,
            password_confirmation: this.register.confirm_password,
            position: this.register.position,
            address: "",
            product_id: this.topup.package_id,*/
            //btc_address: this.register.btc_address,
            //secret_question: this.register.secret_question,
           // secret_ans: this.register.secret_ans,

         })
      .then(resp => {
        if (resp.data.code == 200) {
         $('#registerForm').trigger("reset");
         this.$toaster.success(resp.data.message)
         this.disablebtn = false;  
         this.$router.push({ name: 'StructureReportComponent'})

       } else {
        this.$toaster.error(resp.data.message);
      }
      this.isActiveBtn = true;
    })
      .catch(err => {});

    },
    getPackages(){
      axios.get('get-packages', {
      })
      .then(response => {
        var INR = response.data.data[0]['convert'];
        var type = response.data.data[0]['type'];
        localStorage.setItem('INR', INR); 
        localStorage.setItem('p_type', type); 
        return true;
      })
      .catch(error => {
      });        
    },
    reset() {
      this.user.user_id = "";
      this.user.password = "";
    },
    getReferralId() {
      $("#referral_id").val("Williamson");
    },
    matchpassword() {
      if(this.register.password != this.register.confirm_password){
        /*this.errors.add('this.password_confirmation', 'not match')*/
        this.errors.add({
          field: 'confirm_password',
          msg: 'password does not match'
        });
      } else {

      }
    },
   /* check($event){
      if($("#agree").prop('checked') == true){
        this.terms = 1;
      }else{
        this.terms = 0;
      } 
    },*/
    showPass(){


     if(this.showpassword == 0){
       document.getElementById('password').type = 'text';
       this.eye = 'fa fa-eye'
       this.showpassword = 1;
     }else if(this.showpassword == 1){
       document.getElementById('password').type = 'password';
       this.showpassword = 0;
       this.eye = 'fa fa-eye-slash'
     }

   },

   checkValidBTCAddr() {
    let that = this;
    that.isValidBTCAddress = false;
    that.btc_error = false;
    $(".loaderD").html('Checking BTC Address..<i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="font-size:16px"></i>');
    clearTimeout(this.typingTimer); 

    if (/\s/.test(that.register.btc_address)) {
     that.errors.remove('btc_address'); 
     that.errors.add({field: 'btc_address', msg: 'Spaces not allowed in address'});
     $(".loaderD").html("");
     return false;
   }else{
     that.errors.remove('btc_address'); 
   }
         /*that.errors.remove('btc_address'); 
         that.errors.add({field: 'btc_address', msg: 'Enter valid BTC address.'});*/

         that.typingTimer = setTimeout(function(){

           if(that.register.btc_address == ""){
            $(".loaderD").html("");
            that.isValidBTCAddress=true;
            that.btc_error = false;
            that.errors.remove('btc_address');
          }
          else{
           that.errors.add({
            field: 'btc_address',
            msg: 'checking for valid btc address'
          });
           that.isValidBTCAddress = false;
           axios.post('check_address1', {
            address: that.register.btc_address,
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
       checkAddress(){
        $(".loaderD").html('Checking BTC Address..<i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="font-size:16px"></i>');
        clearTimeout(this.typingTimer); 
        let that = this;
        that.typingTimer = setTimeout(function(){
          that.btc_error = false;
          if(that.register.btc_address == "")
          {
            that.btc_error=false;
            $(".loaderD").html("");
          }
          else
          {

           axios.post('check_address1',{
            address:that.register.btc_address, 
            network_type :'BTC'      
          }).then(response=>{
            if(response.data.code==200){
              $(".loaderD").html("");
              that.btc_error=false;
            } else {
              that.btc_error=true;  
              $(".loaderD").html("");
            }
          }).catch(error=>{

          }) 

        }

      }, this.doneTypingInterval);

      },
      check1(){
        if(this.is_referral == true){
          this.register.referral_id = "Williamson"; 
          this.checkuserexist();   
        }else{
          this.register.referral_id = ""; 
          this.sponsor_name="";
        }

      },
      //getUserDashboard() {
      //  axios
       // .get("get-user-dashboard", {})
       // .then(response => {
        //  this.selftopup = response.data.data;
        //  var hidedata = this.selftopup.top_up_wallet - this.selftopup.top_up_wallet_withdraw
          /*console.log(hidedata);*/

        //})
        //.catch(error => {});
      //},
      getUserDashboard() 
      {
        axios.post("get-wallet-balance", {'wallet_type':'top_up_wallet'})
        .then(response => {
          if(response.data.code==200){
            this.selftopup.top_up_Wallet_balance = response.data.data.toFixed(2);
          }
        })
        .catch(error => {});

        axios.post("get-wallet-balance", {'wallet_type':'fund_wallet'})
        .then(response => {
          if(response.data.code==200){
            this.selftopup.fund_Wallet_balance = response.data.data.toFixed(2);
          }
        })
        .catch(error => {});
      },


    }
  };
  </script>