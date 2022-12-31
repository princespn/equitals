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
                <h2 class="content-header-title float-left mb-0">Mode Of Deposit </h2> 
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
          </div> --></div>
        <div class="content-body package">
          <div class="row">
            <div class="col-lg-12">
              <div class="block_white padd mb-30">
                <div class="currency_withdraw">
                  <router-link to="add-fund-one" class="withdraw-ps-select" tag="a">
                  <!-- <a class="withdraw-ps-select" href="/enter?plan=1&amp;ps=cct"> -->
                    <div class="currency_item currency_capital_crypto">
                      <div>
                        <div class="currency_name fs-18 fw-500">Capital Crypto</div>
                        <div class="currency_img">
                          <img src="public/user_files/images/svg/cct.svg" alt="Capital Crypto">
                        </div>
                      </div>
                      <div>
                        <div class="fs-18"><b>$ 0.00</b>
                        </div>
                        <div class="fs-12 fw-500">0.00000000 CCT</div>
                        <div class="currency_btn">
                          <div>Choose</div>
                        </div>
                      </div>
                    </div>
                  </router-link>

                  
                  <a class="withdraw-ps-select" href="/enter?plan=1&amp;ps=btc">
                    <div class="currency_item currency_bitcoin">
                      <div>
                        <div class="currency_name fs-18 fw-500">Bitcoin</div>
                        <div class="currency_img">
                          <img src="public/user_files/images/svg/btc.svg" alt="Bitcoin">
                        </div>
                      </div>
                      <div>
                        <div class="fs-18"><b>$ 0.00</b>
                        </div>
                        <div class="fs-12 fw-500">0.00000000 BTC</div>
                        <div class="currency_btn">
                          <div>Choose</div>
                        </div>
                      </div>
                    </div>
                  </a>
                  <a class="withdraw-ps-select" href="/enter?plan=1&amp;ps=eth">
                    <div class="currency_item currency_ethereum">
                      <div>
                        <div class="currency_name fs-18 fw-500">Ethereum</div>
                        <div class="currency_img">
                          <img src="public/user_files/images/svg/eth.svg" alt="Ethereum">
                        </div>
                      </div>
                      <div>
                        <div class="fs-18"><b>$ 0.00</b>
                        </div>
                        <div class="fs-12 fw-500">0.00000000 ETH</div>
                        <div class="currency_btn">
                          <div>Choose</div>
                        </div>
                      </div>
                    </div>
                  </a>
                  <a class="withdraw-ps-select" href="/enter?plan=1&amp;ps=trx">
                    <div class="currency_item currency_tron">
                      <div>
                        <div class="currency_name fs-18 fw-500">Tron</div>
                        <div class="currency_img">
                          <img src="public/user_files/images/svg/trx.svg" alt="Tron">
                        </div>
                      </div>
                      <div>
                        <div class="fs-18"><b>$ 0.00</b>
                        </div>
                        <div class="fs-12 fw-500">0.00000000 TRX</div>
                        <div class="currency_btn">
                          <div>Choose</div>
                        </div>
                      </div>
                    </div>
                  </a>
                  <a class="withdraw-ps-select" href="/enter?plan=1&amp;ps=bnb">
                    <div class="currency_item currency_binance_coin">
                      <div>
                        <div class="currency_name fs-18 fw-500">Binance Coin (BEP20)</div>
                        <div class="currency_img">
                          <img src="public/user_files/images/svg/bnb.svg" alt="Binance Coin (BEP20)">
                        </div>
                      </div>
                      <div>
                        <div class="fs-18"><b>$ 0.00</b>
                        </div>
                        <div class="fs-12 fw-500">0.00000000 BNB</div>
                        <div class="currency_btn">
                          <div>Choose</div>
                        </div>
                      </div>
                    </div>
                  </a>
                  <a class="withdraw-ps-select" href="/enter?plan=1&amp;ps=usdt">
                    <div class="currency_item currency_tether">
                      <div>
                        <div class="currency_name fs-18 fw-500">Tether</div>
                        <div class="currency_img">
                          <img src="public/user_files/images/svg/usdt.svg" alt="Tether ">
                        </div>
                      </div>
                      <div>
                        <div class="fs-18"><b>$ 0.00</b>
                        </div>
                        <div class="fs-12 fw-500">0.00 USDT</div>
                        <div class="currency_btn">
                          <div>Choose</div>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal loadrr fade" id="loader" tabindex="-1" role="dialog" aria-labelledby="customModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class=" text-center">
          <div class=" ">
            <div class="spinner-grow text-primary" role="status"> <span class="sr-only">Loading...</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Perfect Money Code(Shreemant Mirkute) -->
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
        <!-- <input type="hidden" name="PAYMENT_ID" v-model="user_id" placeholder="ID"> -->
        <!-- as specified in PAYMENT_AMOUNT -->
        <input type="hidden" name="PAYMENT_AMOUNT" id="PAYMENT_AMOUNT" placeholder="Amount">
        <!-- <input type="submit" name="PAYMENT_METHOD" value="Payment method"> -->
      </p>
    </form>
    <!-- End of the code for perfect money -->
    <!-- Start Popup code -->
    <div id="demo-default-modal" role="dialog" tabindex="-1" aria-hidden="true" class="modal fade in">
      <div class="modal-dialog text-center">
        <div class="modal-content text-center">
          <div class="modal-header bg-primary">
            <h3 class="modal-title text-white">Deposit</h3>
          </div>
          <div class="modal-body">
            <div v-if="changemodal==0">
              <h4 class="m-b-10">Amount ${{getpackagedetails.price_in_usd}}</h4>
              <!-- <p class="text-semibold text-main">Please Deposit to complete your topup.</p> -->
              <div class="row m-b-10">
                <div class="col-md-5 text-right text-xs-center white-txt">Amount:</div>
                <div class="col-md-4 text-xs-center">
                  <div class="input-group mar-btm mar-btm-0-xs"><span class="input-group-addon"><i class="fa fa-usd"></i></span>
                    <input type="text" class="form-control" v-model="getpackagedetails.price_in_usd" readonly="">
                  </div>
                </div>
                <div class="col-md-3 text-left text-xs-center"><small>( {{ getpackagedetails.price_in_currency }} BTC )</small>
                </div>
              </div>
              <div class="row">
                <div class="col-md-5 text-right text-xs-center white-txt">Paid Amount:</div>
                <div class="col-md-4">
                  <div class="input-group mar-btm mar-btm-0-xs"><span class="input-group-addon"><i class="fa fa-usd"></i></span> 
                    <input type="text" class="form-control" v-model="getpackagedetails.received_amount">
                  </div>
                </div>
                <div class="col-md-3 text-left text-xs-center white-txt">BTC</div>
              </div>
              <div class="row m-b-10">
                <div class="col-md-5 text-right text-xs-center white-txt">Remaining Amount:</div>
                <div class="col-md-4 text-xs-center">
                  <div class="input-group mar-btm mar-btm-0-xs"><span class="input-group-addon"><i class="fa fa-usd"></i></span> 
                    <input type="text" class="form-control" v-model="getpackagedetails.rem_amount" readonly="">
                  </div>
                </div>
                <div class="col-md-3 text-left text-xs-center white-txt">BTC</div>
              </div>
              <div class="row">
                <div class="col-md-4 offset-md-4 text-center">
                  <div class="qr-bg">
                    <!-- <img src="user_files/images/qr_code.png" width="100%" /> -->
                    <qrcode v-bind:value="this.qrcodevalue"></qrcode>
                  </div>
                </div>
                <div class="col-md-12 text-center">
                  <div class="input-group mar-btm">
                    <input type="text" v-model="getpackagedetails.address" id="btc-add" class="form-control"> <span class="input-group-btn tooltip2">
                              <button class="btn btn-info btn-labeled" type="button" onclick="myFunction1()" onmouseout="outFunc1()"> <i class="fa fa-file" aria-hidden="true"></i>   <span class="tooltiptext" id="refcopy1"></span>
                    Copy</button>
                    </span>
                  </div>
                </div>
                <div class="col-md-12 text-center">
                  <div class="only-msg">
                    <p class="pendingDeposit text-center"></p>
                    <div class="counter text-center"> <span> Confirming... {{secondCount}}  </span>
                    </div>
                    <div class="row ">
                      <p class="qrclass text-center"></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="addfundb2" v-if="changemodal==1">
              <img src="public/user_files/images/check.png" width="200px" />
              <h2 class="pay_confm">Payment Confirmed</h2>
              <div class="confmbtn_o">
                <button type="button" @click="closeModal1" class="btn btn-outline-warning sm">Deposit Again</button>
                <!-- <router-link class="btn btn-outline-primary sm" :to="{ name: 'add-fund-report'}">
                        See History
                </router-link> -->
                <button type="button" @click="showreport1" class="btn btn-outline-primary sm">See History</button>
              </div>
            </div>
            <!-- <div v-if="changemodal==1">
            <h2>Success</h2>
          </div> --></div>
          <div class="modal-footer">
            <button data-dismiss="modal" type="button" @click="closeModal()" class="btn btn-primary">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Username and password popup start -->
    <!--Trigger-->
    <!-- <a class="login-trigger" href="#" data-target="#login" data-toggle="modal">Login</a> -->
    <!-- Shreemant pop up code -->
    <!-- <div id="login" class="modal fade" role="dialog">
  <div class="modal-dialog">
    
    <div class="modal-content">
      <div class="modal-body">
        <button data-dismiss="modal" class="close">&times;</button>
        <h4>Enter Perfect Money Details</h4>
        <form>
          <input type="text" v-model="username" name="username" class="username form-control" placeholder="Username"/>
          <input type="password" v-model="password" name="password" class="password form-control" placeholder="password"/>
          <input hidden="" type="text" v-model="amt" name="amount" class="password form-control" placeholder="amount"/>
          <button data-dismiss="modal" @click="doPayment" type="button" class="btn btn-primary">Login</button>

        </form>
      </div>
    </div>
  </div>  
</div> -->
    <!-- End of the code for popup end -->
    <!-- Priyanka Pop static -->
    <div id="login" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <div>
              <h4 class="m-b-30 text-center"> <img src="public/user_files/assets/img/logo3.png"></h4>
              <!-- <p class="text-semibold text-main">Please Deposit to complete your topup.</p> -->
              <!--     <div class="row m-b-10">
      <div class="col-md-4 text-right text-xs-center white-txt">Payment to:</div>
      <div class="col-md-8 text-left text-xs-center"><small>U22384420 (My company)</small>
      </div>
    </div>
    <div class="row m-b-10">
      <div class="col-md-4 text-right text-xs-center white-txt">Account type:</div>
      <div class="col-md-8 text-left text-xs-center white-txt"><span class="text-green">Verified</span>, 8.3 Trust Score point(s)</div>
    </div> 
    <div class="row m-b-10">
      <div class="col-md-4 text-right text-xs-center white-txt">Credit rating:</div>
      <div class="col-md-8 text-left text-xs-center white-txt">Normal, no overdue loans.</div>
    </div> 
    <div class="row m-b-10">
      <div class="col-md-4 text-right text-xs-center white-txt">Amount: </div>       
      <div class="col-md-8 text-left text-xs-center white-txt">1 USD</div>
    </div> 
    <div class="row m-b-10">
      <div class="col-md-4 text-right text-xs-center white-txt">Memo:</div>
      <div class="col-md-5 text-xs-center">
        <div class="input-group mar-btm mar-btm-0-xs"> 
          <input type="text" class="form-control" readonly="">
        </div>
      </div>
      <div class="col-md-3 text-left text-xs-center white-txt">ref. # test</div>
    </div>
<hr> -->
              <h3 class="text-center m-b-10"> Authorize Payment  </h3>
              <div class="row m-b-10">
                <div class="col-md-4 text-right text-xs-center white-txt">Member ID:</div>
                <div class="col-md-8 text-xs-center">
                  <div class="input-group mar-btm mar-btm-0-xs">
                    <input type="text" v-model="username" name="username" class="username form-control" placeholder="Member ID" />
                  </div>
                </div>
              </div>
              <div class="row m-b-10">
                <div class="col-md-4 text-right text-xs-center white-txt">Password:</div>
                <div class="col-md-8 text-xs-center">
                  <div class="input-group mar-btm mar-btm-0-xs">
                    <input type="password" v-model="password" name="password" class="password form-control" placeholder="Password" />
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12 text-center">
                  <button class="btn btn-success btn-labeled" type="button" @click="doPayment"> <i class="fa fa-money" aria-hidden="true"></i>  <span class="tooltiptext" id="refcopy1"></span> Login</button>
                  <!-- <button data-dismiss="modal" @click="doPayment" type="button" class="btn btn-primary">Login</button> -->
                  <button class="btn btn-danger btn-labeled" data-dismiss="modal" type="button" @click="closeModal()"> <i class="fa fa-remove" aria-hidden="true"></i>  <span class="tooltiptext" id="refcopy1"></span> Cancel Payment</button>
                </div>
                <br>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- end of the cdoe -->
    <!-- End Popup Code -->
  </div>
</template>
<script>
  import Vue from 'vue'; 
          import moment from 'moment';
          import { apiUserHost } from'../../user-config/config';
          import Breadcrum from './BreadcrumComponent.vue';
          import QrcodeVue from 'qrcode.vue';
          import VueQrcode from '@xkeshi/vue-qrcode';
          Vue.component(VueQrcode.name, VueQrcode);
      
          export default {  
            components: {
              Breadcrum,
              QrcodeVue
            }, 
            data(){
               return {
                 secondCount:30,
                 username:'',
                 password:'',
                 amt:'',
                  status:0,
                  typeChange:'BTC',
                  btc: '',
                  currency_code: {},
                  amount: [],
                  product_id: '',
                  perfectmoney: {
                  },
                  getpackages: {
                        id: '',
                        roi: '',
                        duration: '',
                        name: '',
                        cost: '',
                  },
                  getpackagedetails: {
                     price_in_usd: '',
                     received_amount: '',
                     price_in_currency: '',
                     address: '',
                     rem_amount:0,
                  },
                  size:'',
                  qrcodevalue:'',
                  INR:'',
                  selectedCurrency:'',
                  countryCode:'',
                  user_id:'',
                          fullname:'',
                          
                          disablebtn:false,
                          isActiveBtn: false,
                          balance:{},
                          payment_mode:'Bank',
                          trn_ref_no:'',
                          holder_name:'',
                          bank_name:'',
                          deposit_date:'',
                          fundamount:'',
                          fundAmountDisplay:'',
                          type:'',
                          changemodal:0,
               }
            },
            computed: {
               isCompleteMakeDeposite () {
                   return this.btc;      
               },
                 isComplete () {
                          return this.amount, this.payment_mode, this.trn_ref_no;
                      }
            },
      
            mounted(){
               this.getPackages();
               this.getAllCurrency();
               this.getPerfectMoneyCred();
                //$('#demo-default-modal').modal('show');
            },
            methods:{
           minmax(getpackage,value, min, max)     {
         /*   if(this.type == "INR")
            {
              min = min * this.INR;
              max = max * this.INR;
            }
      */
              if(parseInt(value) < min)
              {
                this.isActiveBtn = false;
               // this.amount[getpackage] = min;
                this.$toaster.error("Amount must be minimum " + min);
                $('#makedeposite'+getpackage).attr('disabled','disabled');
              }else if(parseInt(value) > max){
                this.isActiveBtn = false;
                //this.amount[getpackage] = max;
                this.$toaster.error("Amount must be maximum " + max);
                $('#makedeposite'+getpackage).attr('disabled','disabled');
              }else{
                //this.isActiveBtn = true;
                if(this.status == getpackage)
                {
                 $('#makedeposite'+getpackage).removeAttr("disabled");
                }
              }
          },
               getPackages(){
                  axios.get('get-packages1', {
                  })
                  .then(response => {
                      this.getpackages = response.data.data;
      
                      this.INR = response.data.data[0]['convert'];
                      this.type = response.data.data[0]['type'];
                      this.countryCode = response.data.data[0]['countryCode'];
                      for(let x in this.getpackages){
                          this.amount[this.getpackages[x].id] = '';
                      }
                  })
                  .catch(error => {
                  });        
               },
    
                getPerfectMoneyCred() {
                  axios
                    .get("get-perfect-cred", {})
                    .then(response => {
                      this.perfectmoney = response.data.data;
                    })
                    .catch(error => {});
                },
      
               getAllCurrency(){ 
                  axios.get('getall-currency', {
                  })
                  .then(response => {
                     this.currency_code = response.data.data;
                  })
                  .catch(error => {
                  });        
               },
      
               radioButtonChecked(id,name,value,min,max){
                this.selectedCurrency = name;
                this.status = id;
                
                   $('.pay').attr('disabled','disabled');
             if(parseInt(value) < min)
              {
                $('#makedeposite'+id).attr('disabled','disabled');
              }else if(parseInt(value) > max){
                $('#makedeposite'+id).attr('disabled','disabled');
              }else{
                 $('#makedeposite'+id).removeAttr("disabled");
              }
      
               },
      
               fundRequest() {
                        
                         this.disablebtn = true;
                         let formData = new FormData();
                         if(this.$refs.file.files[0] != ''){
                           formData.append('file', this.$refs.file.files[0]);
                         }
                         formData.append('amount', this.fundamount);
                          formData.append('INR', this.INR);
                         formData.append('payment_mode', this.payment_mode);
                         formData.append('trn_ref_no', this.trn_ref_no);
                         formData.append('holder_name', this.holder_name);
                         formData.append('bank_name', this.bank_name);
                         formData.append('product_id', this.product_id);
                         formData.append('deposit_date', this.deposit_date);
                         
                         axios.post('fund-request',
                          formData,{
                            headers: {
                              'Content-Type': 'multipart/form-data'
                            }
                          }
                          ).then(response=>{
                           if(response.data.code==200){
                             $('#INR-payment').modal('toggle');
                            this.$toaster.success(response.data.message)
                            
                            this.disablebtn = false;
                            this.$router.push({name:'fund-request-report'});
                            
                          }else{
                            this.$toaster.error(response.data.message)
                            this.disablebtn = false;
                          }
                        }).catch(error=>{
                         this.disablebtn = false;
                       })
                      },
                      
      
               purchasePackage1(product_id, currency_code,hash_unit){
                alert(hash_unit);
                $(".overlay").show();
                $(".loader").show();
                if(currency_code != 'BTC')
                {
                  $('#login').modal('show');
    
                   //this.doPayment(hash_unit);
                   this.amt = hash_unit;
                  /* axios.post('sendWAMessage', {
                     INR: this.INR,
                     amount: hash_unit,
                  })
                  this.fundamount = hash_unit;
                  this.fundAmountDisplay = hash_unit * this.INR;
                  this.product_id = product_id;
                  $("#INR-payment").modal("show");*/
                }
                else
                {
                  
                  axios.post('getaddress', {
                     product_id: product_id,
                     currency_code: this.typeChange,
                     hash_unit: hash_unit,
                  })
                  .then(resp => {
                    if(resp.data.code === 200){
                      this.getpackagedetails = resp.data.data;
                      this.getpackagedetails.rem_amount = this.getpackagedetails.price_in_currency;
                      //this.qrcodevalue = this.getpackagedetails.address;
                      this.qrcodevalue = "bitcoin:"+this.getpackagedetails.address+"?amount="+this.getpackagedetails.rem_amount;
      
      
                       this.getOneMinInterval();
                       this.changemodal = 0;
                      $('#demo-default-modal').modal();
                    } else {
                      this.$toaster.error(resp.data.message);
                    }
                  })
                  .catch(error => {
                  });
                }
                          
               },
    
            purchasePackagecoin(product_id, currency_code,hash_unit){
              
              $(".loadrr").modal();
              //$(".loader").show();
              /*if(currency_code != 'BTC')
              {
                 axios.post('sendWAMessage', {
                   INR: this.INR,
                   amount: hash_unit,
                })
                this.fundamount = hash_unit;
                this.fundAmountDisplay = hash_unit * this.INR;
                this.product_id = product_id;
                $("#INR-payment").modal("show");
              }
              else
              {*/
               // alert(currency_code);
                 $(".overlay").show();
                $(".loader").show();
                if(currency_code == 'PM')
                {
                  $('#login').modal();
    
                   //this.doPayment(hash_unit);
                   this.amt = hash_unit;
                  /* axios.post('sendWAMessage', {
                     INR: this.INR,
                     amount: hash_unit,
                  })
                  this.fundamount = hash_unit;
                  this.fundAmountDisplay = hash_unit * this.INR;
                  this.product_id = product_id;
                  $("#INR-payment").modal("show");*/
                }
                else
                {
                
                axios.post('purchase-package', {
                   product_id: product_id,
                   currency_code: currency_code,
                   hash_unit: hash_unit,
                })
                .then(resp => {
                  if(resp.data.code === 200){
                    this.getpackagedetails = resp.data.data;
                    this.qrcodevalue = this.getpackagedetails.address;
                    var txn = resp.data.data.txn_details;
                    $('#loader').modal().hide();
                    $('#loader').modal('hide');
                    location.replace(this.getpackagedetails.status_url);
                    /*if (this.getpackagedetails.exists == 0) {
                      location.replace(this.getpackagedetails.status_url);
                    }else{
                      this.$toaster.success("Already requested for same amount");              
                    }*/
                    //$('#demo-default-modal').modal();
                  } else {
                    this.$toaster.error(resp.data.message);
                  }
                })
                .catch(error => {
                });
              }
                        
             },
    
              closeModal(){
                $(".loadrr").hide();
                $(".loader").hide();
                $("#loader").css('display','none')
             },
    
              doPayment(amount) {
              //// $('#PAYMENT_AMOUNT').val(amount);
              //// $('#payment').submit();
    
               axios.post('getperfect-money', {
                 amount: this.amt,
                 username: this.username,
                 password: this.password
                  })
                  .then(response => {
                  if(response.data.code === 200){
                    this.changemodal = 1;
                    this.$toaster.success(response.data.message);
                       $('#login').modal('hide');
                      $('#demo-default-modal').modal();
                      // this.$router.push({name:'add-fund-perfectmoney-report'});
                    // $('#login').modal();
                  }else{
    
                    this.$toaster.error(response.data.message);
                  }
                     //this.currency_code = response.data.data;
                  })
                  .catch(error => {
                  });
                
              },
               getOneMinInterval() {
      
                 clearInterval(this.OneMinTime);
                 this.secondCount = 3;
                  this.OneMinTime = setInterval(() => {
                   this.secondCount = this.secondCount - 1;
                  if (this.secondCount < 10 && this.secondCount >= 0) {
                      this.secondCount = `0${this.secondCount}`;
                    } else if (this.secondCount < 0) {
                        this.secondCount = 30;
                      axios.post('fetchAddressBalance', {
                      address: this.getpackagedetails.address,
                       invoice_id: this.getpackagedetails.invoice_id.invoice_id
                  })
                  .then(response => {
                         if(response.data.code == 200){
                                // alert(response.data.data);
                                 var messageData = response.data.message;
                                 
                                  if(response.data.data.status == 1)
                                  {
                                    //clearInterval(this.OneMinTime); 
                                     $('.counter').addClass('text-success');
                                    $(".counter").show();
                                     $(".qrclass").html('');
                                      $(".pendingDeposit").html('');
                                    $(".pendingDeposit").html(messageData);
                                    this.confirmDeposit(this.getpackagedetails.invoice_id.invoice_id); 
                                  }else if(response.data.data.status == 2){
                                     //  clearInterval(this.OneMinTime); 
                                      $('.counter').addClass('text-danger');
                                      $(".counter").show();
                                       $(".qrclass").html('');
                                        $(".pendingDeposit").html('');
                                    $(".pendingDeposit").html(messageData);
    
                                        
                                  }
    
                                   this.getpackagedetails.received_amount = response.data.data.rec;
                                   if((this.getpackagedetails.price_in_currency - this.getpackagedetails.received_amount) > 0){
                                        this.getpackagedetails.rem_amount = this.getpackagedetails.price_in_currency - this.getpackagedetails.received_amount;
                                   }else{
                                        this.getpackagedetails.rem_amount = 0;
                                   }
                                    
    
    
    
                                  
                              } else {
                                 this.$toaster.error(response.data.message);
                                 this.disablebtn = false;   
                              }
                      
                      
                  })
                  
              //this.postServiceCall(params, 'fetchAddressBalance');
                this.secondCount = 30;
              } else {
            }
          } ,1000);
        },
               closeModal(){
                  $(".overlay").hide();
                  $(".loader").hide();
               },
               closeModal1(){
                   $('#demo-default-modal').modal('hide');
               },
               showreport(){
                   $('#demo-default-modal').modal('hide');
                  this.$router.push({name:'add-fund-report'});
               },
               showreport1(){
                   $('#demo-default-modal').modal('hide');
                  this.$router.push({name:'add-fund-perfectmoney-report'});
    
               },
        //         confirmDeposit(invoice_id) {
      
        //          clearInterval(this.OneMinTime);
        //          this.secondCount = 3;
        //           this.OneMinTime = setInterval(() => {
        //            this.secondCount = this.secondCount - 1;
        //           if (this.secondCount < 10 && this.secondCount >= 0) {
        //               this.secondCount = `0${this.secondCount}`;
        //             } else if (this.secondCount < 0) {
        //                 this.secondCount = 30;
        //               axios.post('confirm-deposit', {
        //                invoice_id:invoice_id
        //           })
        //           .then(response => {
        //                  if(response.data.code == 200){
        //                         this.changemodal = 1;
        //                         clearInterval(this.OneMinTime); 
        //                         $('.counter').addClass('text-success');
        //                         $(".counter").hide();
        //                       } else {
        //                          this.changemodal = 0;
        //                       }
                      
                      
        //           })
                  
        //       //this.postServiceCall(params, 'fetchAddressBalance');
        //         this.secondCount = 30;
        //       } else {
        //     }
        //   }, 1000);
        // },
    
               confirmDeposit(invoice_id){
                //alert();
                axios.post('confirm-deposit',{
                    invoice_id:invoice_id
                }).then(resp => {
                    if(resp.data.data.ret == 1){
    
                        this.changemodal = 1;
                        clearInterval(this.OneMinTime); 
                        $('.counter').addClass('text-success');
                        $(".counter").hide();
                    } else {
                        this.changemodal = 0;
                    }
                  })
                  .catch(error => {
                  });
               }
            }
         }
</script>