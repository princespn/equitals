<style type="text/css">
  .loader{
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #2a90d2;
    margin: 10px auto;
    transform-style: preserve-3d;
    animation: loading-1 0.8s infinite ease-out;
    animation-fill-mode: forwards;
}
@keyframes loading-1{
    0%{
        box-shadow: 0 0 0 10px #23242c, 0 0 0 12px rgba(42, 144, 210,0.75);
    }
    40%{
        box-shadow: 0 0 0 10px transparent, 0 0 0 20px transparent;
    }
    50%{
        background:  rgb(42, 144, 210);
        transform: scale(1.3) rotateY(20deg);
    }
    100%{ transform: rotateY(180deg); }
}


</style>


<template>
  <!-- BEGIN: Content-->
  <div class="content-body">
   <div class="container-fluid">
      <div class="row page-titles">
         <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Activation</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Activate Account</a>
            </li>
         </ol>
      </div>
      <!-- row -->
      <div class="row">
         <div class="col-12">
            <div class="card lp">
               <div class="card-header">
                  <h4 class="card-title">Activate Member's Account</h4>
               </div>
               <div class="card-body">
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                           <div class="card-content">
                              <div class="card-body card-dashboard">
                                 <div class="row">
                                    <div class="text-center col">
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-3">
                                       <div class="reward-income-bg-amt">
                                          <span class="card-title">EQX Wallet Balance</span> 
                                          <h2 class="mb-1 font-bold card-title text-primary t60">${{topup_wallet.fund_wallet}}</h2>
                                          <div v-if="show_wallet == 1">
                                           <span class="card-title">Development Bonus Balance</span> 
                                          <h2 class="mb-1 font-bold card-title text-primary t60">${{setting_wallet}}</h2>
                                          </div>
                                          <img src="public/user_files/assets/images/fund-w.png" class="img-fluid w160">
                                       </div>
                                        
                                    </div>

                                    <div class="col-md-9">
                                       <div class="row rmb">
                                          <div class="col-md-6">
                                             <div class="form-group">
                                                <label class="control-label">Enter Username</label> <br> <input formcontrolname="touser-id" id="touser-id" name="touser-id" placeholder="Enter User Id" v-model="username" @change="checkUserExisted" data-vv-as="User ID" v-validate="'required'" type="text" class="form-control"> 
                                                <div class="clearfix"></div>
                                               <p :class="{'text-success': isAvialable == 'Available','text-danger': isAvialable == 'Not Available'}" v-if="isAvialable!='' && username!=''">{{isAvialable}}</p>

                                            <div class="tooltip2" v-show="errors.has('username')">
                                              <div class="tooltip-inner">
                                                <span v-show="errors.has('username')">{{ errors.first('username') }}</span>
                                              </div>
                                            </div>
                                             </div>
                                          </div>
                                          <div class="col-md-6">
                                             <div class="form-group">
                                                <div class="form-group">
                                                   <label class="control-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Select Package</font></font></label> <br> 
                                                    <select v-model="topup.package_id" name="package_id" class="form-control" @change="changeSelect($event)" id="package_id" v-validate="'required'">
                                                    <option value="" selected disabled>Select Package</option>
                                                    <option v-for="packagelist in packagelists" v-bind:value="packagelist.id" :key="packagelist"> {{ packagelist.package_name }}</option> 
                                                  </select>
                                                   <div class="tooltip2" style="display: none;">
                                                      <div class="tooltip-inner"><span style="display: none;"></span></div>
                                                   </div>
                                                </div>
                                                <div class="clearfix"></div>
                                             </div>
                                          </div>

                                          <div class="col-md-6">
                                             <div class="form-group">
                                                <label class="control-label">Enter Amount</label> <br>
                                                 <!-- <input id="transfer-amount" name="transfer-amount" formcontrolname="touser-id" placeholder="amount" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" type="number" min="25" step="25" title="Numbers only" aria-required="true" aria-invalid="false" class="form-control">  -->

                                                  <input type="text" class="form-control" @change="changeHashUnit($event)" placeholder="Amount" name="hash_unit" min="1" step="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" title="Numbers only" v-model="topup.hash_unit" v-validate="'required|numeric'" />
                                                  <div class="clearfix"></div>
                                                <p class="text-danger" v-show="!errors.has('hash_unit')">{{usermsg}}</p>


                                                <div class="tooltip2" style="display: none;">
                                                   <div class="tooltip-inner"><span style="display: none;"></span></div>
                                                </div>
                                             </div>
                                          </div>
                                           <div class="col-md-6" v-if="show_wallet == 1">
                                              <div class="mb-2">
                                                <label class="form-label" for="payment-card-number">Transaction Type</label>
                                                <div>
                                                  <input name="transcation_type" type="radio" id="radio_7" class="radio-col-primary"  v-model="transcation_type" value="1" />
                                                  <label for="radio_7">Fund(MIN {{100 - topup_percentage}}%) + Development Bonus(MAX {{topup_percentage}}%)</label>
                                                </div>
                                                <div>
                                                  <input name="transcation_type" type="radio" id="radio_9" class="radio-col-primary"  v-model="transcation_type" value="2" checked/>
                                                  <label for="radio_9">Fund</label>
                                                </div>
                                                
                                              </div>
                                            </div>
                                       </div>
                                       <div class="panel-footer bg-gray text-center">
                                          <div class="col-lg-12">
                                            <br> <button type="button" @click="updateSelfTopup()" :disabled="!iscomplete||usermsg!=''||userNotValid!=''" id="topupsub" class="btn btn-primary">Submit</button>
                                            </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div id="editBankDetailsmodal" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" class="modal">
                     <div class="modal-dialog">
                        <div class="modal-content">
                           <div class="modal-header">
                              <h4 class="modal-title">Enter OTP</h4>
                              <button type="button" data-dismiss="modal" class="close">×</button>
                           </div>
                           <div class="modal-body">
                              <div class="form-group">
                                 <div class="row">
                                    <div class="col-md-12">
                                       <input type="text" name="otp" placeholder="Enter OTP" class="form-control" aria-required="true" aria-invalid="false"> 
                                       <div class="tooltip2" style="display: none;">
                                          <div class="tooltip-inner"><span style="display: none;"></span></div>
                                       </div>
                                    </div>
                                    <br> <br> <br> <br> 
                                    <div class="clearfix"></div>
                                    <div class="col-md-12">
                                       <center><button type="button" class="btn btn-primary">Submit</button></center>
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
      </div>
   </div>
</div>
</template>

<script>
import moment from "moment";
import { apiUserHost } from "../../user-config/config";
import Breadcrum from "./BreadcrumComponent.vue";
import Swal from "sweetalert2";
export default {
  components: {
    Breadcrum
  },
  computed: {
    iscomplete() {
      return this.username && this.topup.package_id && this.topup.hash_unit;
    }
  },
  data() {
    return {
      /*objProSettings:{},*/
       hidedata : 0,
       disablebtn: true,
       countries: [],
      selftopup: {
        top_up_wallet: 0,
        top_up_wallet_withdraw: 0,
        top_up_Wallet_balance: 0,
        package_id: 0
      },
      topup: {
        user_id: "",
        pin: "",
        product_id: null,
        hash_unit: "",
        payment_type: "BTC",
        package_id: "1",
       // transcation_type:""
        //username:'',
      },
      perfectmoney: {
      },
      packagelists: {
        id: "",
        name: ""
      },
      user_id : "",
      isAvialable: "",
      username: "",
      min_hash: "",
      max_hash: "",
      usermsg: "",
      arrProduct: [],
      isValid: true,
      userNotValid:true,
      franchise:{
        user_id:'',
        id:''
      },
      setting_wallet:0,
      show_wallet:0,
      topup_percentage:0,
      transcation_type:2, 
      masterfranchise:{
        user_id:'',
        id:''
      },
      masterFranchiseList:[],
      clicked: true,
      topup_wallet:{
        fund_wallet:0,
        purchase_wallet:0

      }
    };
  },
  mounted() {
    this.getUserDashboard();
    this.getPackageList();
    this.getSettingFund();
    //this.getProSettings();
    /*this.getMasterFranchiseUserList();*/
   /* this.getPerfectMoneyCred();*/
    /*this.getCountry();*/

  },
  methods: {
    // packageid(){
    // 	$('#selftopup').removeAttr("disabled");
    // },
   /* getProSettings(){
                axios.get('/getprojectsettings')
                .then(resp => {
                    if(resp.data.code === 200){
                        this.objProSettings = resp.data.data;
                    }
                }).catch(err => {
                })
    }, */
    getPackageList() {
      axios
        .get("get-packages", {})
        .then(response => {
          this.packagelists = response.data.data;
          for (var i in this.packagelists) {
            this.arrProduct[this.packagelists[i].id] = this.packagelists[i];
          }
        })
        .catch(error => {});
    },

    getCountry() {
      axios
        .get("country", {})
        .then(response => {
          this.countries = response.data.data;
        })
        .catch(error => {});
    },
    changeHashUnit(){
      let user = _.split(event.target.value, "-", 2); //using lodash here. You can also just use js split func
      let id = user[0]; // your id
      this.min_hash = this.arrProduct[this.topup.package_id].min_hash;
      this.max_hash = this.arrProduct[this.topup.package_id].max_hash;
      // console.log(this.topup.hash_unit);
      if (this.topup.hash_unit < this.min_hash || this.topup.hash_unit > this.max_hash
      ) {
        this.usermsg =
          "Amount should be on range " + this.min_hash + " to " + this.max_hash;
        this.isValid = false;
      } else {
        this.isValid = true;
        this.usermsg = "";
      }
      console.log(this.usermsg);
    },
     getSettingFund() {
          axios
            .get("get-setting-balance", {})
            .then((response) => {
              if (response.data.code === 200) {
                this.setting_wallet = response.data.data.setting_wallet;
                this.show_wallet = response.data.data.show_wallet;
                if (this.show_wallet == 1) {
                  this.transcation_type=1;
                }
                this.topup_percentage = response.data.data.topup_percentage;

              }else{
                this.setting_wallet = 0;
                this.show_wallet = 0;
                this.topup_percentage = 0;

              }
            })
            .catch((error) => {});
        },
    getUserDashboard() {
      axios
        .get("get-topup-balance", {})
        .then(response => {
          this.topup_wallet = response.data.data;          
        })
        .catch(error => {});
    },
    getPerfectMoneyCred() {
      axios
        .get("get-perfect-cred", {})
        .then(response => {
          this.perfectmoney = response.data.data;
        })
        .catch(error => {});
    },

    ///--- 
    getMasterFranchiseUserList(){
      axios.get("get-master-franchise-users", {})
      .then(response => {
        this.masterFranchiseList = response.data.data;
      })
      .catch(error => {

      });
    },


    getFranchiseOnCountry(country){

      //alert(country);

      axios.post("get-franchise-users", {country:country})
      .then(response => {
        this.franchise = response.data.data;
      })
      .catch(error => {

      });

    },

    hashvalidation() {
      // if(this.topup.product_id==1){
      //     this.min_hash=this.max_hash;
      // }
      if (
        this.topup.hash_unit < this.min_hash ||
        this.topup.hash_unit > this.max_hash
      ) {
        this.usermsg =
          "Amount should be on range " + this.min_hash + " to " + this.max_hash;
        this.isValid = false;
      } else {
        this.isValid = true;
        this.usermsg = "";
      }
    },

    checkUserExisted1() {
      console.log(this.username);
      axios
        .post("/checkuserexist", {
          user_id: this.username
        })
        .then(resp => {
          if (resp.data.code === 200) {
            this.topup.user_id = resp.data.data.id;
            //this.btc_address = resp.data.data.btc_address;
            //this.eth_address = resp.data.data.ethereum;

            /*  if(this.btc_address == null && this.eth_address != null ){
                            
                             this.topup.payment_type = "ETH";
                        }else if(this.btc_address != null && this.eth_address == null ){
                           
                             this.topup.payment_type = "BTC";
                        }else{
                            this.topup.payment_type = "BTC";
                        } */

            this.isAvialable = "Available";
          } else {
            this.topup.user_id = "";
            this.isAvialable = "Not Available";
          }
        })
        .catch(err => {
          arrProduct;
          this.$toaster.error(err);
          arrProduct;
        });
    },
    redirectregistration(){
      /*this.$router.push({ name: 'registerafterlogin' });*/
      for (var i in this.packagelists) {
            this.arrProduct[this.packagelists[i].id] = this.packagelists[i];
          }
          this.topupdata = (this.arrProduct[this.packagelists[i].id].name);

           var balncedata = this.topup_wallet.fund_wallet;

          if(balncedata >0)
          {
            this.$router.push({
                            name:'user-create-structure',
                            params:{
                                topupdata: this.topupdata,
                                balanceamount: balncedata,
                            }
                        });
          }else{
            this.$toaster.error("Insufficient Fund Wallet Balance");
          }
      
    },
    updateSelfTopup() {
      // this.$validator.validateAll().then((result) => {   
      //   if (!result) {
      //       return false;
      //   }
        //alert();
        //var package_id = this.topup.package_id;
        Swal({
          title: "Submit your Confirmation?",
          text: `Kindly Confirm Investment`,
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          cancelButtonText: "No",
          confirmButtonText: "Yes"
        }).then(result => {
          if (result.value) {
            //this.doPayment();
            this.disablebtn=true;

             // if (this.clicked==true) {
             //  this.clicked=false;
              axios
              .post("self-topup", {
                product_id: this.topup.package_id,
                user_id: this.username,
                hash_unit: this.topup.hash_unit,
                transcation_type:this.transcation_type,
                device:'web',
                topupfrom:'Purchase Wallet'
              })
              .then(response => {
                if (response.data.code == 200) {
                  this.$toaster.success(response.data.message);
                  this.getUserDashboard();
                  this.getPackageList();
                  this.topup.package_id = "";
                  this.topup.hash_unit = "";
                  this.username = "";
                /*  this.franchise.user_id = "";
                 this.masterfranchise.user_id = "";*/
                   this.disablebtn=false;
                  // $('#package_id').val('');
                  this.$router.push({ name: 'downline-topup-report' });
                } else {
                  this.$toaster.error(response.data.message);
                }
              });
             // }
           
          }
        });
      // });
    },

    doPayment() {
     // document.payment.submit();
     $('#payment').submit();
      
    },
    checkUserExisted() {
      console.log(this.username);
      axios
        .post("checkuserexist", {
          user_id: this.username
        })
        .then(response => {
          if (response.data.code == 200) {
            axios.post("check-downline", {
                user_id: this.username
              }).then(res => {
                if (res.data.code == 200) {
                  //alert(1);
                  // this.paid = res.data.data;
                  // this.fullname = response.data.data.fullname;
                  // this.address = response.data.data.address;
                  // this.mobile = response.data.data.mobile;
                  // this.sp_user_id = response.data.data.sponser_id;
                  // this.sp_fullname = response.data.data.sponser_fullname;
                  // this.useractive = true;
                  this.topup.user_id = res.data.data;
                  this.isAvialable = "Available";
                  /*this.topup.package_id = 1;
                  this.topup.hash_unit = 50;*/
                  this.userNotValid = false;
                } else {
                  // alert(res.data.message)
                  // this.paid = '';
                  // this.isdownline = 0;
                  //  this.fullname = '';
                  // this.address = '';
                  // this.mobile = '';
                  // this.sp_user_id = '';
                  // this.sp_fullname = '';
                  // this.useractive = false;
                  this.topup.user_id = "";
                  /*this.topup.package_id = "";
                  this.topup.hash_unit = "";*/
                  //this.isAvialable = 'Not Available';
                  this.isAvialable = res.data.message;
                  this.userNotValid = true;
                }
              });
          } else {
            this.userNotValid = true;
            this.isAvialable = response.data.message;
          }
        })
        .catch(error => {
          console.log(error);
        });
    },
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
    addTopUp() {
      this.isDisabledBtn = false;
      Swal({
        title: "Are you sure ?",
        text: "You want to topup!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes!",
        cancelButtonText: "No" 
      }).then(result => {
        if (result.value) {
          axios
            .post("/store/topup", {
              id: this.topup.user_id,
              // pin: this.topup.pin,
              product_id: this.topup.product_id,
              hash_unit: this.topup.hash_unit,
              payment_type: this.topup.payment_type
            })
            .then(resp => {
              if (resp.data.code === 200) {
                this.$toaster.success(resp.data.message);
              } else {
                this.$toaster.error(resp.data.message);
              }
              //$('#addTopUp').trigger("reset");
              this.username = "";
              this.topup = {
                user_id: "",
                pin: "",
                product_id: null,
                hash_unit: ""
              };
              this.isDisabledBtn = true;
            })
            .catch(err => {
              this.$toaster.error(err);
            });
        } else {
          this.isDisabledBtn = true;
        }
      });
    }
  }
};
</script>