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
                <h2 class="content-header-title float-left mb-0">Invest Now </h2> 
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
            <div class="col-lg-4 col-md-4 col-12" v-for="(getpackage, index) in getpackages">
              <div class="card">
                <div class="card-body d-flex justify-content-between align-items-start flex-column">
                  <div class="w-100">
                    <h3>{{getpackage.package_type}}</h3>
                    <h1 class="mb-0">
                            <sup class="font-medium-3"></sup>{{getpackage.name}}
                           
                        </h1>
                    <div>
                      <h5 class="mt-1">
                                <span class="text-success">{{ getpackage.roi }}%  Daily Returns </span>
                            </h5>
                    </div>
                  </div>
                  <div>Enter Amount (In USD)
                    <input class="form-control" name="amount" type="text" :min="getpackage.min_hash" :max="getpackage.max_hash" v-model="amount[getpackage.id]" @keyup="minmax(getpackage.id,amount[getpackage.id],getpackage.min_hash,getpackage.max_hash)">
                    <!-- <input type="text" :id="'first-name_'+index" class="form-control" name="fname" placeholder="100" > -->
                    <h5 class="mt-1">
                            <span class="text-success"> ${{ amount[getpackage.id] }}</span>
                        </h5>
                    <h4> Payment Mode </h4>
                    <div class="custom-control custom-radio">
                      <input class="custom-control pointer" required="" name="payment_type" type="radio" v-bind:value="getpackage.id" @click="radioButtonChecked(getpackage.id,'BTC',amount[getpackage.id],getpackage.min_hash,getpackage.max_hash)">BTC</label>&nbsp;
                      <input type="hidden" name="currency_code" v-model="currency_code">
                      <!-- <input type="radio" class="custom-control-input" name="customRadio" id="customRadio1" checked=""> -->
                      <!--  <label class="custom-control-label" for="customRadio1">BTC</label> -->
                    </div>
                  </div>
                </div>
                <hr class="my-50">
                <div class="card-body d-flex justify-content-around flex-column">
                  <div>
                    <button class="btn btn-primary w-100 box-shadow-1 waves-effect waves-light" v-bind:id="'makedeposite'+getpackage.id" v-bind:value="getpackage.id" @click="purchasePackage(getpackage.id,selectedCurrency,amount[getpackage.id])" :disabled="isActiveBtn == false">Make Payment</button>
                    <!--  <button @click="purchasePackage(pack.id,pack.currency_code,pack.cost)" data-toggle="modal" data-target="#default-Modal" class="btn btn-primary w-100 box-shadow-1 waves-effect waves-light">Make Payment<i class="feather icon-chevrons-right"></i></button> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Start Popup code -->
    <div id="demo-default-modal" role="dialog" tabindex="-1" aria-hidden="true" class="modal fade in">
      <div class="modal-dialog text-center">
        <div class="modal-content text-center">
          <div class="modal-header bg-primary">
            <h3 class="modal-title text-white">Deposit</h3>
          </div>
          <div class="modal-body">
            <h4>Package ${{getpackagedetails.price_in_usd}}</h4>
            <p class="text-semibold text-main">Please Deposit to complete your topup.</p>
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
            <div class="row m-b-10">
              <div class="col-md-5 text-right text-xs-center white-txt">Remaining Amount:</div>
              <div class="col-md-4 text-xs-center">
                <div class="input-group mar-btm mar-btm-0-xs"><span class="input-group-addon"><i class="fa fa-usd"></i></span> 
                  <input type="text" class="form-control" v-model="getpackagedetails.price_in_currency" readonly="">
                </div>
              </div>
              <div class="col-md-3 text-left text-xs-center white-txt">BTC</div>
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
            </div>
          </div>
          <div class="modal-footer">
            <button data-dismiss="modal" type="button" @click="closeModal()" class="btn btn-primary">Close</button>
          </div>
        </div>
      </div>
    </div>
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
              status:0,
              btc: '',
              currency_code: {},
              amount: [],
              product_id: '',
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
           // this.$toaster.error("Amount must be maximum " + max);
           // $('#makedeposite'+getpackage).attr('disabled','disabled');
          }else{
            //this.isActiveBtn = true;
            if(this.status == getpackage)
            {
             $('#makedeposite'+getpackage).removeAttr("disabled");
            }
          }
      },
           getPackages(){
              axios.get('get-packages', {
              })
              .then(response => {
                  this.getpackages = response.data.data;
  
                  this.INR = response.data.data[0]['convert'];
                  this.type = response.data.data[0]['type'];
                  this.countryCode = response.data.data[0]['countryCode'];
                  for(let x in this.getpackages){
                      this.amount[this.getpackages[x].id] = this.getpackages[x].min_hash;
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
                  
  
           purchasePackage(product_id, currency_code,hash_unit){
            
            $(".overlay").show();
            $(".loader").show();
            if(currency_code != 'BTC')
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
            {
              
              axios.post('getaddress', {
                 product_id: product_id,
                 currency_code: currency_code,
                 hash_unit: hash_unit,
              })
              .then(resp => {
                if(resp.data.code === 200){
                  this.getpackagedetails = resp.data.data;
                  this.qrcodevalue = this.getpackagedetails.address;
                  $('#demo-default-modal').modal();
                } else {
                  this.$toaster.error(resp.data.message);
                }
              })
              .catch(error => {
              });
            }
                      
           },
           closeModal(){
              $(".overlay").hide();
              $(".loader").hide();
           },
        }
     }
</script>