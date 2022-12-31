<template>
 <div class="content-body">
   <div class="container-fluid">
      <div class="row page-titles">
         <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Manage Funds</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Add Fund</a>
            </li>
         </ol>
      </div>
      <!-- row -->
      <div class="row">
         <div class="col-12">
            <div class="card lp">
               <div class="card-header">
                  <h4 class="card-title">Add New Fund</h4>
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
                                          <span class="card-title">Available Balance</span> 
                                          <h2 class="mb-1 font-bold card-title text-primary t60">${{fund_wallet_balance.fund_wallet}}</h2>
                                          <img src="public/user_files/assets/images/wallet.png" class="img-fluid w160">
                                       </div>
                                    </div>
                                    <div class="col-md-9">
                                       <form  @submit.prevent="purchasePackagecoin(product_id, currency_code,amount)" data-vv-scope="addfundform">
                                          <div class="row">
                                             <div class="col-md-6">
                                                <div class="form-group rmb10">
                                                   <label class="control-label">Enter Amount (In USD)</label> <br> 
                                                   <input type="text" class="form-control" placeholder="Enter Amount" name="amount" min="1" step="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" title="Numbers only" v-model="amount" v-validate="'required|numeric|min_value:50'" />
                                                   <div class="tooltip2" v-show="errors.has('addfundform.amount')">
                                                      <div class="tooltip-inner">
                                                         <span v-show="errors.has('addfundform.amount')">{{ errors.first('addfundform.amount') }}</span>
                                                      </div>
                                                   </div>
                                                   <div class="clearfix"></div>
                                                </div>
                                             </div>
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                   <label class="control-label">Preview Amount</label> <br> <input v-model="amount" formcontrolname="touser-id" id="touser-id" name="touser-id" placeholder="Enter Amount (In USD)" type="text" class="form-control" disabled> 
                                                   <div class="clearfix"></div>
                                                </div>
                                             </div>
                                             <div class="col-md-12 mt-3">
                                                <div class="table-responsive">
                                                   <div class="d-flex align-items-end flex-wrap">
                                                      <div class="filtaring-area me-3">
                                                         <div class="size-filter">
                                                            <label class="m-b-15">Select Payment Mode</label> <br>                                             
                                                            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                                               <!-- <ul> -->
                                                              <template v-for="cur in currency_code ">
                                                                  <input type="radio" class="btn-check"   :name="'btnradio'+cur.id" :id="'btnradio'+cur.id"  :value="cur.currency_code" v-model="typeChange">
                                                                  <label class="btn btn-outline-primary" :for="'btnradio'+cur.id">{{cur.currency_code}}</label>

                                                              </template>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="panel-footer bg-gray text-center">
                                             <div class="col-lg-12"><br> 
                                                <button class="btn btn-primary" type="submit">Make Payment </button>
                                             </div>
                                          </div>
                                       </form>
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

                   <!-- Perfect Money Code(Shreemant Mirkute) -->
                   <form class="hidden" id="pmpayment" action="https://perfectmoney.com/api/step1.asp" method="POST">
                       <input type="hidden" name="PAYEE_ACCOUNT" id="payee_account">
                       <input type="hidden" name="PAYEE_NAME" id="payee_name">
                       <input type="hidden" name="PAYMENT_ID" id="payment_id">
                       <input type="hidden" name="PAYMENT_AMOUNT" id="payment_amount">
                       <input type="hidden" name="PAYMENT_UNITS" id="payment_units">
                       <!-- <input type="hidden" name="STATUS_URL" v-model="perfectmoney.STATUS_URL"> -->
                       <input type="hidden" name="PAYMENT_URL" id="payment_url">
                       <input type="hidden" name="NOPAYMENT_URL" id="nopayment_url">
                       <input type="hidden" name="PAYMENT_URL_METHOD" id="payment_url_method">
                       <input type="hidden" name="NOPAYMENT_URL_METHOD" id="nopayment_url_method">
                   </form>
               </div>
            </div>
         </div>
      </div>
   </div>
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
              amount: '',
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
            fund_wallet_balance:{
            fund_wallet:0,
            purchase_wallet:0,
            usermsg: "",
            isValid: true

            }
           }
        },
        computed: {
           isCompleteMakeDeposite () {
               return this.btc;      
           },
            isComplete () {
              return this.amount;
            }
        },
  
        mounted(){
           this.getPackages();
           this.getAllCurrency();
           this.getUserDashboard();
          /* this.getPerfectMoneyCred();*/
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
      
      getUserDashboard() {
      axios.get("get-topup-balance", {})
         .then(response => {
          this.fund_wallet_balance = response.data.data;          
         })
        .catch(error => {});
      },
             createPMTransaction(){ 
              axios.post('create_pm_transaction', {
                amount: this.amount,
              }) 
              .then(response => {
                if(response.data.code === 200){
                  this.closeModal();
                  this.$toaster.success(response.data.message);
                  this.perfectmoney = response.data.data;
                  var permoney = this.perfectmoney;
                  document.getElementById("payee_account").value=permoney.PAYEE_ACCOUNT;
                  document.getElementById("payee_name").value=permoney.PAYEE_NAME;
                  document.getElementById("payment_id").value=permoney.PAYMENT_ID;
                  document.getElementById("payment_amount").value=permoney.PAYMENT_AMOUNT;
                  document.getElementById("payment_units").value=permoney.PAYMENT_UNITS;
                  document.getElementById("payment_url").value=permoney.PAYMENT_URL;
                  document.getElementById("nopayment_url").value=permoney.NOPAYMENT_URL;
                  document.getElementById("payment_url_method").value=permoney.PAYMENT_URL_METHOD;
                  document.getElementById("nopayment_url_method").value=permoney.NOPAYMENT_URL_METHOD;
                  console.log(this.perfectmoney);
                  $("#pmpayment").submit();
                }else{
                  this.$toaster.error(response.data.message);
                  this.closeModal();
                }
                 //this.currency_code = response.data.data;
              })
              .catch(error => {
              });
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


           purchasePackagecoin(product_id='', currency_code='',amount=0){
            this.$validator.validateAll('addfundform').then((result) => {
               if (result) {
          //alert(this.currency_code);return false;
          currency_code = this.typeChange;
          
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
               this.amount = amount;
               this.createPMTransaction(amount);

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
                  currency_code: this.typeChange,
                  hash_unit: this.amount,
               })
               .then(resp => {
                  if(resp.data.code === 200){
                     this.getpackagedetails = resp.data.data;
                     this.qrcodevalue = this.getpackagedetails.address;
                     // var txn = resp.data.data.txn_details;
                     $('#loader').modal().hide();
                     $('#loader').modal('hide');
                     if(this.getpackagedetails.payment_by == "node_api")
                     {
                        this.$router.push({name:'payment-invoice-status', params: {invoice_id:this.getpackagedetails.invoice_id,type: 'add-fund'}});
                     }else if(this.getpackagedetails.payment_by == "coinpayment"){
                        location.replace(this.getpackagedetails.status_url);
                     }

                     /*location.replace(this.getpackagedetails.status_url);
                        if (this.getpackagedetails.exists == 0) {
                        location.replace(this.getpackagedetails.status_url);
                        }else{
                        this.$toaster.success("Already requested for same amount");              
                     }*/
                        //$('#demo-default-modal').modal();
                     } else {
                        this.$toaster.error(resp.data.message);
                     }
                  }).catch(error => {});
               }
            }});      
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