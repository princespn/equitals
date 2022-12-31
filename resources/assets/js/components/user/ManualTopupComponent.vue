<template>
  <!-- BEGIN: Content-->
  <div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
      <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h2 class="content-header-title float-left mb-0">Manual  Fund Request</h2>
            </div>
          </div>
        </div>
        <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
          <div class="form-group breadcrum-right">
            <div class="dropdown">
              <button
                class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle"
                type="button"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="feather icon-settings"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#">Chat</a>
                <a class="dropdown-item" href="#">Email</a>
                <a class="dropdown-item" href="#">Calendar</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="content-body">
        <!-- Complex headers table -->
        <section id="headers">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <!-- <div class="card-header">
                              <h4 class="card-title">Self Topup </h4>
                </div>-->
                <div class="card-content">
                  <div class="card-body card-dashboard">
                    <!--  -->
                    <div class="row">
                      <!-- <div class="text-center col-md-2">
                        <div class="reward-income-bg-amt">
                          <h2 class="mb-1 font-bold">{{(selftopup.top_up_wallet).toFixed(2)}}</h2>
                          <span>Purchase Wallet</span>
                        </div>
                      </div>
                      <div class="text-center col-md-2">
                        <div class="reward-income-bg-amt">
                          <h2 class="mb-1 font-bold">{{selftopup.top_up_wallet_withdraw}}</h2>
                          <span>Purchase Wallet Withdrawal</span>
                        </div>
                      </div> -->
                     <!--  <div class="text-center col-md-4 offset-md-4">
                        <div class="reward-income-bg-amt">
                          <h2
                            class="mb-1 font-bold"
                          >{{(selftopup.top_up_wallet-selftopup.top_up_wallet_withdraw).toFixed(2)}}</h2>
                          <span>Purchase Wallet Balance</span>
                        </div>
                      </div> -->
                      <!-- <div class="text-center col-md-2">
					<div class="reward-income-bg-amt">
						<h2 class="mb-1 font-bold">36.9k</h2>
						<span>Total Income </span>
					</div>
                      </div>-->
                      <!-- <div class="text-center col-md-2">
					<div class="reward-income-bg-amt">
						<h2 class="mb-1 font-bold">36.9k</h2>
						<span> Total Withdrawal </span>
					</div>
                      </div>-->
                      <!-- <div class="text-center col-md-2">
					<div class="reward-income-bg-amt">
						<h2 class="mb-1 font-bold">36.9k</h2>
						<span> Total Balance </span>
					</div>
                      </div>-->
                    </div>
                    <!--  -->

                    <div class="top-bordr3 m-t-30 m-b-30">
                      <select class="form-control" @change="showForm" v-model="paymode">
                        <option  value="">Select Mode</option>
                        <option value="btc">BTC</option>
                        <option value="pm">Perfect Money</option>
                        <option value="pa">Paypal Address</option>
                        <option value="eth">ETH</option>

                      </select>
                      <!--  <div class ="clearfix"></div><br> -->
                      <div>
                      <p style="text-align: center;" id="showaddress"></p>

                 
                       Pay To :- <input id="showaddressTxt" type="text" :value="payAddress" readonly class="form-control col-md-12 col-12 float-left bor-radius-0 hght-30">

                      <button v-if="dropdowselect==1" onClick="copyText()" >Copy Address</button>


                      </div>
                   


                    </div>

                    <div class ="clearfix"></div><br>

                    <div class="col-md-8 offset-md-2 mrgBotm-50" v-if="dropdowselect==1">
                      <div class="row mtBoX">
                        <!-- <div class="col-md-4">
                          <div class="form-group">
                            <input type="hidden" name="user_id" v-model="topup.user_id" />
                            <label class="control-label">Enter User</label>
                            <br />
                            
                            <input
                              type="text"
                              name="username"
                              class="form-control ng-untouched ng-pristine ng-invalid"
                              id="username"
                              placeholder="User Id"
                              v-model="username"
                              v-on:keyup="checkUserExisted"
                            />
                            <div class="clearfix"></div>
                            <p
                              :class="{'text-success': isAvialable == 'Available','text-danger': isAvialable == 'Not Available'}"
                              v-if="isAvialable!='' && username!=''"
                            >{{isAvialable}}</p>
                          </div>
                        </div> -->

                       <!--  <div class="col-md-4">
                          <div class="form-group">
                            <label class="control-label">Select Package</label>
                            <br />
                            <select
                              id
                              v-model="topup.package_id"
                              name="package_id"
                              class="form-control"
                              @change="changeSelect($event)"
                            >
                              <option disabled value selected>Select Package</option>
                              <option
                                v-for="packagelist in packagelists"
                                v-bind:value="packagelist.id"
                              >{{ packagelist.name }}</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="control-label">Select Franchise</label>
                            <br />
                            <select
                              id
                              v-model="franchise.user_id"
                              name="franchise_user_id"
                              class="form-control"
                            >
                              <option disabled value selected>Select Franchise</option>
                              <option
                                v-for="franchise_user in franchise"
                                v-bind:value="franchise_user.id"
                              >{{ franchise_user.user_id }} </option>
                            </select>
                          </div>
                        </div> -->

                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="control-label">Enter Amount</label>
                            <br />
                            <input
                              type="text"
                              class="form-control"
                              name="hash_unit"
                              min="1"
                              step="1"
                              onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                              title="Numbers only"
                              v-model="topup.hash_unit"
                              v-validate="'required|numeric'"
                              
                            />
                            <div class="clearfix"></div>
                            <p class="text-danger">{{usermsg}}</p>

                           
                          </div>
                        </div>

                        
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="control-label">Contact No</label>
                            <br />
                            <input
                              type="text"
                              class="form-control"
                              v-model="topup.contact_no"
                              id="contact_no"
                              name="contact-no"
                            />
                            <div class="clearfix"></div>
                           <!--  <p class="text-danger">{{usermsg}}</p> -->

                           
                          </div>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="control-label">Upload Transaction Slip</label>
                            <br />
                            <input
                              type="file"
                              class="form-control"
                             
                              id="fileprrof"
                              name="fileprrof"
                              ref="file"
                            />
                            <div class="clearfix"></div>
                           <!--  <p class="text-danger">{{usermsg}}</p> -->

                           
                          </div>
                        </div>


                      </div>

                      <div class="panel-footer bg-gray text-center">
                        <button
                          id="selftopup"
                          @click="updateTopup()"
                          type="button"
                          :disabled="!iscomplete||disablebtn==true||usermsg != ''"
                          class="btn btn-primary"
                        >Submit</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <form class="hidden" id="payment" action="https://perfectmoney.is/api/step1.asp" method="POST">
            <p>
               <!-- as specified in PAYEE_ACCOUNT -->
                <input type="hidden" name="PAYEE_ACCOUNT" v-model="perfectmoney.PAYEE_ACCOUNT">
                <!-- merchant’s name as specified in PAYEE_NAME -->
                <input type="hidden" name="PAYEE_NAME" v-model="perfectmoney.PAYEE_NAME">
                <!-- as specified in PAYMENT_UNITS -->
                <input type="hidden" name="PAYMENT_UNITS" v-model="perfectmoney.PAYMENT_UNITS">
                <input type="hidden" name="STATUS_URL" v-model="perfectmoney.STATUS_URL">
                <input type="hidden" name="PAYMENT_URL" v-model="perfectmoney.PAYMENT_URL">
                <input type="hidden" name="NOPAYMENT_URL" v-model="perfectmoney.NOPAYMENT_URL">

                <input type="hidden" name="PAYMENT_ID" v-model="user_id" placeholder="ID">
                <!-- as specified in PAYMENT_AMOUNT -->
                <input type="hidden" name="PAYMENT_AMOUNT" v-model="topup.hash_unit" placeholder="Amount">

                <!-- <input type="submit" name="PAYMENT_METHOD" value="Payment method"> -->
            </p>
          </form>
        </section>
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
      //return this.topup.package_id && this.topup.hash_unit && this.franchise.user_id && this.topup.contact_no;
      return this.topup.hash_unit && this.topup.contact_no;
    }
  },
  data() {
    return {
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
        package_id: "",
        contact_no:'',
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
      franchise:{
        user_id:'',
        id:''
      },
      dropdowselect:0,
      paymode:'',
      disablebtn:false,
      payAddress:'',
    };
  },
  mounted() {
    this.getUserDashboard();
    this.getPackageList();
    this.getFranchiseUserList();
    this.getPerfectMoneyCred();
  },
  methods: {
    // packageid(){
    // 	$('#selftopup').removeAttr("disabled");
    // },


   copyTestFun(){
  //  alert();
 var copyText = document.getElementById("showaddressTxt");

  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /*For mobile devices*/

  /* Copy the text inside the text field */
  document.execCommand("copy");
                    this.$toaster.success('copied address')
  /* Alert the copied text */
  alert("Copied the Al: " + copyText.value)
   },
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
    showForm(){
        this.dropdowselect = 1;
        if(this.paymode == 'btc'){
         // $('#showaddress').text('Pay To:  1KWz7sd7rzmJka9dKJULwMg7PA9NEm6gou');
          //$('#showaddressTxt').val('1KWz7sd7rzmJka9dKJULwMg7PA9NEm6gou');
          this.payAddress="1KWz7sd7rzmJka9dKJULwMg7PA9NEm6gou";
        }else if(this.paymode == 'eth'){
          //$('#showaddress').text('Pay To:  0xee1aaa2efbd41875a477e8cf41f719cedf069dc2');
          //$('#showaddressTxt').val('0xee1aaa2efbd41875a477e8cf41f719cedf069dc2');
           this.payAddress="0xee1aaa2efbd41875a477e8cf41f719cedf069dc2";
        }else if(this.paymode == 'pa'){
          //$('#showaddress').text('Pay To:  wallet@gmail.com');
           $('#showaddressTxt').val('wallet@gmail.com');
           this.payAddress="wallet@gmail.com";
        }else if(this.paymode == 'pm'){
          //$('#showaddress').text('Pay To:  U22384420');
          //$('#showaddressTxt').val('U22384420');

          this.payAddress="U22384420";

        }
    },

    getUserDashboard() {
      axios
        .get("get-user-dashboard", {})
        .then(response => {
          this.selftopup = response.data.data;
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
    getFranchiseUserList(){
      axios.get("get-franchise-users", {})
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

    // updateSelfTopup() {
    //   //alert();
    //   //var package_id = this.topup.package_id;
    //   Swal({
    //     title: "Are you sure?",
    //     text: `Kindly Confirm Investment`,
    //     type: "warning",
    //     showCancelButton: true,
    //     confirmButtonColor: "#3085d6",
    //     cancelButtonColor: "#d33",
    //     confirmButtonText: "Yes"
    //   }).then(result => {
    //     if (result.value) {
    //       //this.doPayment();
    //       axios
    //         .post("self-topup", {
    //           product_id: this.topup.package_id,
    //           user_id: this.username,
    //           hash_unit: this.topup.hash_unit,
    //           franchise_user_id:this.franchise.user_id
    //         })
    //         .then(response => {
    //           if (response.data.code == 200) {
    //             this.$toaster.success(response.data.message);
    //             this.getUserDashboard();
    //             this.getPackageList();
    //             this.topup.package_id = "";
    //             this.topup.hash_unit = "";
    //             this.username = "";
    //             this.franchise.user_id = "";
    //             // $('#package_id').val('');
    //           } else {
    //             this.$toaster.error(response.data.message);
    //           }
    //         });
    //     }
    //   });
    // },

    updateTopup(){
      this.disablebtn = true;
       let formData = new FormData();
       if(this.$refs.file.files[0] != ''){
             formData.append('file', this.$refs.file.files[0]);
            }

          //  product_id: this.topup.package_id,
    //           user_id: this.username,
    //           hash_unit: this.topup.hash_unit,
    //           franchise_user_id:this.franchise.user_id
            //formData.append('product_id', this.topup.package_id); 
            formData.append('hash_unit', this.topup.hash_unit); 
           // formData.append('franchise_user_id', this.franchise.user_id);
          formData.append('contact_no', this.topup.contact_no);
           
            axios.post('manual-topup',
                formData,{
                  headers: {
                            'Content-Type': 'multipart/form-data'
                    }
                }
            ).then(response=>{
               if(response.data.code==200){
                    this.$toaster.success(response.data.message)
                    this.$router.push({name: 'manual-topup-report'});
                     
                 }else{
                    this.$toaster.error(response.data.message)
                    this.disablebtn = false;
                 }
            }).catch(error=>{
                   this.disablebtn = false;
            })
    },

    doPayment() {
     // document.payment.submit();
     $('#payment').submit();
      
    },
    checkUserExisted() {
      axios
        .post("checkuserexist", {
          user_id: this.username
        })
        .then(response => {
          if (response.data.code == 200) {
            axios
              .post("check-downline", {
                user_id: this.username
              })
              .then(res => {
                if (res.data.code == 200) {
                  //alert(1);
                  // this.paid = res.data.data;
                  // this.fullname = response.data.data.fullname;
                  // this.address = response.data.data.address;
                  // this.mobile = response.data.data.mobile;
                  // this.sp_user_id = response.data.data.sponser_id;
                  // this.sp_fullname = response.data.data.sponser_fullname;
                  // this.useractive = true;
                  this.isAvialable = "Available";
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
                  //this.isAvialable = 'Not Available';
                  this.isAvialable = res.data.message;
                }
              });
          } else {
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
        cancelButtonText: "Cancel"
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