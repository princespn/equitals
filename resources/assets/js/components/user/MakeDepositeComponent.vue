<style>
.pointer {cursor: pointer;}
.overlay {
  position: fixed;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  background-color: #1111118f;
  z-index: 1111;
}
.loader{
   position: fixed;
  z-index: 999;
  height: 2em;
  width: 2em;
  overflow: visible;
  margin: auto;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}
.cursor {cursor: pointer;}
</style>

<template>
   <div>
      <!--CONTENT CONTAINER-->
      <!--===================================================-->
      <div id="content-container">
        <Breadcrum></Breadcrum>
        <!--Page content-->
        <!--===================================================-->
        <div class="overlay" style="display:none;"></div>
         <div id="page-content">
            <hr class="new-section-sm bord-no">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="panel panel-body text-center bg-trans">
                      
                        <div class="row pricing">
                           <!--Personal Plan-->
                           <!--===================================================-->
                           <div class="loader" style="display:none">
                          <i class="fa fa-spinner fa-spin fa-5x"></i>
                        </div>
                           <div class="col-sm-4" v-for="(getpackage, index) in getpackages">
                              <div class="panel">
                                 <div class="panel-body">
                                    <p class="pricing-title"> Package </p>
                                    <div class="pricing-price">
                                       <p class="text-info">
                                          <span class="text-normal">
                                            <span v-if="type=='BOTH' || type=='BTC'">
                                            {{getpackage.name}}
                                            <br />
                                            {{getpackage.package_name}}
                                          </span>
                                            <span v-if="type=='INR'">
                                            {{getpackage.name_rupee}}
                                             <br />
                                            {{getpackage.package_name}}
                                          </span>

                                          </span>
                                       </p>
                                    </div>
                                    <ul class="list-group">
                                       <li v-if="getpackage.id==1" class="list-group-item"><strong>{{getpackage.roi}}%</strong> &nbsp; Daily Returns</li>
                                       <li v-if="getpackage.id!=1" class="list-group-item"><strong>{{getpackage.roi}}%</strong> &nbsp; Daily Returns</li>
                                       <!-- <li class="list-group-item"><strong>{{getpackage.duration}}%</strong> Total Profit</li> --> 
                                       <!-- <li class="list-group-item"><strong>{{getpackage.duration}}</strong> Days</li> -->
                                       <li class="list-group-item">
                                        <center>Enter Amount (In USD)</center>
                                          <input class="form-control" name="amount" type="text" :min="getpackage.min_hash" :max="getpackage.max_hash" v-model="amount[getpackage.id]" @keyup="minmax(getpackage.id,amount[getpackage.id],getpackage.min_hash,getpackage.max_hash)">
                                           <!--   <div class="amount"> -->


                                       </li>
                                     <!--  

                                     

                                       <li class="list-group-item">
                                          <input class="form-control" name="amount" type="number" placeholder="Enter Amount" v-model="amount[getpackage.id]" @focusout="minmax(getpackage.id,amount[getpackage.id],getpackage.min_hash,getpackage.max_hash)" >
                                           <!--   <div class="amount"> 


                                       </li> -->
                                       <li class="list-group-item pb-35" >
                                                   <span class="amount">
                                                    <span v-if="type=='BOTH' || type=='BTC'">
                                                    ${{ amount[getpackage.id] }}
                                                  </span>
                                            </span>

                                            <!--  </div> -->
                                            <span class="INR">
                                              <span v-if="type=='INR'">
                                               ₹{{ amount[getpackage.id] * INR}}
                                             </span>
                                          </span> 
                                       </li>

                                       <li class="list-group-item"><strong></strong>Payment Mode</li>
                                       <li class="list-group-item" >

                                        <span v-if="type=='BOTH'">
                                        
                                        <span v-for="currency in currency_code">
                                          <label class="radio-inline">
                                          <input class="pointer" required="" name="payment_type" type="radio" v-bind:value="getpackage.id" @click="radioButtonChecked(getpackage.id,currency.currency_code,amount[getpackage.id],getpackage.min_hash,getpackage.max_hash)"> {{currency.currency_code}} </label> &nbsp;

                                      
                                       <input type="hidden" name="currency_code" v-model="currency_code">
                                       </span>
                                     </span>

                                     <span v-if="type=='BTC'">

                                      
                                          <label class="radio-inline">
                                          <input class="pointer" required="" name="payment_type" type="radio" v-bind:value="getpackage.id" @click="radioButtonChecked(getpackage.id,'BTC',amount[getpackage.id],getpackage.min_hash,getpackage.max_hash)"> BTC </label> &nbsp;

                                      
                                       <input type="hidden" name="currency_code" v-model="currency_code">
                                       


                                     </span>

                                     <span v-if="type=='INR'">

                                          <label class="radio-inline">
                                          <input class="pointer" required="" name="payment_type" type="radio" v-bind:value="getpackage.id" @click="radioButtonChecked(getpackage.id,'INR',amount[getpackage.id],getpackage.min_hash,getpackage.max_hash)"> INR </label> &nbsp;

                                      
                                       <input type="hidden" name="currency_code" v-model="currency_code">
                                       

                                     </span>

                                     </li>
                                     
                                    </ul>                                   

                                    <button class="btn btn-block btn-info pay" v-bind:id="'makedeposite'+getpackage.id" v-bind:value="getpackage.id" @click="purchasePackage(getpackage.id,selectedCurrency,amount[getpackage.id])" :disabled="isActiveBtn == false">Make Payment</button>
                                 </div>
                              </div>
                           </div>                            
                        </div>
                    </div>
                </div>
            </div>
         </div>
        <!--===================================================-->
        <!--End page content-->

         <div class="modal fade" id="demo-default-modal" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
            <div class="modal-dialog text-center">
                <div class="modal-content text-center">

                    <!--Modal header-->
                    <div class="modal-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal" @click="closeModal()"><i class="pci-cross pci-circle"></i></button>
                        <h3 class="modal-title text-white">Deposit</h3>
                    </div>

                    <!--Modal body-->
                    <div class="modal-body">
                        <h4>Package ${{getpackagedetails.price_in_usd}}</h4>
                        <p class="text-semibold text-main">Please Deposit to complete your topup.</p>
                        <div class="row mar-btm">
                            <div class="col-md-4 text-right text-xs-center">Amount:</div>
                            <div class="col-md-4 text-xs-center"><div class="input-group mar-btm mar-btm-0-xs">
                                <span class="input-group-addon bg-primary"><i class="fa fa-usd"></i></span>
                                <input type="text" class="form-control" v-model="getpackagedetails.price_in_usd" readonly="">
                            </div></div>
                            <div class="col-md-4 text-left text-xs-center"><small>( {{ getpackagedetails.price_in_currency }} BTC )</small></div>
                        </div>

                        <div class="row mar-btm">
                            <div class="col-md-4 text-right text-xs-center">Remaining Amount:</div>
                            <div class="col-md-4 text-xs-center"><div class="input-group mar-btm mar-btm-0-xs">
                                <span class="input-group-addon bg-primary"><i class="fa fa-btc"></i></span>
                                <input type="text" class="form-control" v-model="getpackagedetails.price_in_currency" readonly="">
                            </div></div>
                            <div class="col-md-4 text-left text-xs-center"> BTC</div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 text-right text-xs-center">Paid Amount:</div>
                            <div class="col-md-4"><div class="input-group mar-btm mar-btm-0-xs">
                                <span class="input-group-addon bg-primary"><i class="fa fa-btc"></i></span>
                                <input type="text" class="form-control" v-model="getpackagedetails.received_amount">
                            </div></div>
                            <div class="col-md-4 text-left text-xs-center">BTC</div>
                        </div>

                        

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <div>
                                 <!-- <img src="user_files/assets/img/qr.png"> -->
                                   <!--  <qrcode-vue :value="getpackagedetails.address" :size="size" level="H"></qrcode-vue> -->
                                    <qrcode v-bind:value="this.qrcodevalue"></qrcode>

                                  </div>
                                </div>

                                <div class="col-md-6 col-md-offset-3 text-center">
                                    <div class="input-group mar-btm">
                                       <input type="text" v-model="getpackagedetails.address" id="referral-link" class="form-control">
                                       <span class="input-group-btn">
                                        <div class="tooltip ">
                                            <button onclick="myFunction()" onmouseout="outFunc()" class="btn btn-primary">
                                              <span class="tooltiptext" id="refcopy">Copy to clipboard</span>
                                              Copy text
                                          </button>
                                      </div>
                                  </span>
                              </div>
                          </div>
                      </div>
                    </div>

                    <!--Modal footer-->
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-primary" type="button" @click="closeModal()">Close</button>
                    </div>
                </div>
            </div>
         </div>

          <!-- Created By NIkunj Shah -->
          <div class="modal fade" id="INR-payment" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
            <div class="modal-dialog text-center">
                <div class="modal-content text-center">

                    <!--Modal header-->
                    <div class="modal-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal" @click="closeModal()"><i class="pci-cross pci-circle"></i></button>
                        <h3 class="modal-title text-white">Deposit</h3>
                    </div>

                    <!--Modal body-->
                    <div class="modal-body">
                                  
                        <form class="form" enctype="multipart/form-data">
                  <div class="form-group justify-content-center">
                    <h4 class="text-success"> Bank account details has been sent on your 
                     <span v-if="countryCode==91"> 
                      registered mobile number and
                     </span>
                     email. </h4>
                    <div class="form-group">
                      
                      <div class="row p-15">
                        <div class="form-group mb-1 col-sm-12 col-md-4">
                          <label for="password">Amount</label>
                        </div>
                        <div class="form-group mb-1 col-sm-12 col-md-6">
                          <input type="text" readonly="" class="form-control" id="amount" name="amount" v-model="fundAmountDisplay" v-validate="'min_value:1'" placeholder="Enter Amount" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57">
                          <div class="tooltip2" v-show="errors.has('fundamount')">
                            <div class="tooltip-inner"> <span v-show="errors.has('fundamount')">{{ errors.first('fundamount') }}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row p-15">
                        <div class="form-group mb-1 col-sm-12 col-md-4">
                          <label for="password"> Mode of Payment</label>
                        </div>
                          <div class="form-group mb-1 col-sm-12 col-md-6">
                                <div class="form-group">
                                  <select class="form-control" name="payment_mode" v-model="payment_mode" id="payment_mode">
                                    <!-- <option value="phone_pay"> UPI  Phone Pay</option>
                                    <option value="google_pay"> UPI Google Pay</option>
                                    <option value="paytm_no"> UPI PayTm</option>
                                    <option value="mobile_banking">Mobile Banking (Mobile Bank App) </option>
                                    <option value="rtgs">RTGS</option>
                                    <option value="neft">NEFT</option> -->
                                    <option value="Bank" selected="selected">Bank</option>
                                  </select>
                                </div>
                          </div>
                       </div>

                       <div class="row p-15">
                        <div class="form-group mb-1 col-sm-12 col-md-4">
                          <label for="trn_ref_no">Transaction Reference number</label>
                        </div>
                          <div class="form-group mb-1 col-sm-12 col-md-6">
                            <div class="form-group">
                              <input type="text" class="form-control" id="trn_ref_no" name="trn_ref_no" v-model="trn_ref_no" v-validate="'min_value:1'" placeholder=" Transaction Reference number" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57">
                            </div>
                          </div>
                       </div> 

                       <div class="row p-15">
                        <div class="form-group mb-1 col-sm-12 col-md-4">
                          <label for="holder_name">Sender AC Holder Name</label>
                        </div>
                          <div class="form-group mb-1 col-sm-12 col-md-6">
                            <div class="form-group">
                              <input type="text" class="form-control" id="holder_name" name="holder_name" v-model="holder_name" placeholder="AC Holder Name" >
                            </div>
                          </div>
                       </div>

                       <div class="row p-15">
                        <div class="form-group mb-1 col-sm-12 col-md-4">
                          <label for="bank_name">Bank Name</label>
                        </div>
                          <div class="form-group mb-1 col-sm-12 col-md-6">
                            <div class="form-group">
                              <input type="text" class="form-control" id="bank_name" name="bank_name" v-model="bank_name" placeholder="Bank name" >
                            </div>
                          </div>
                       </div>

                        <div class="row p-15">
                        <div class="form-group mb-1 col-sm-12 col-md-4">
                          <label for="deposit_date">Deposit Date</label>
                        </div>
                          <div class="form-group mb-1 col-sm-12 col-md-6">
                            <div class="form-group">
                                <!-- <DatePicker :bootstrap-styling="true" name="deposit_date" placeholder="Deposit date" id="deposit_date"></DatePicker>
                                <span class="input-group-addon bg-custom b-0">
                                    <i class="mdi mdi-calendar"></i>
                                </span> -->
                              <input type="date" class="form-control" id="deposit_date" name="deposit_date" v-model="deposit_date" placeholder="Deposit date" >
                            </div>
                          </div>
                       </div>  
                       <!-- Transaction Reference number 

                      #. Sender AC Holder Name:-

                      #. Sender bank name:-

                      #. Deposit date -->
                      <div class="row p-15">
                        <div class="form-group mb-1 col-sm-12 col-md-4">
                           <span id="fileselector">
                              <label  class=" btn-default upload-btn-fund" for="upload-file-selector">Upload Payment Slip</label>
                             </span>
                        </div>
                        <div class="form-group mb-1 col-sm-12 col-md-6"> 
                          <span id="fileselector">
                            <input  accept="image/*"  id="upload-file-selector" multiple="" name="file" type="file" class="ng-untouched ng-pristine ng-valid"ref="file">
                          </span>
                        </div>
                      </div>                    
                      <hr>
                    </div>
                  </div>
                  <div class="form-group row text-center">
                    <div class="col-lg-12">
                      <button type="button" class="btn btn-primary" name="signup1" value="Sign up" @click="fundRequest" :disabled='errors.any() || disablebtn == true'>Submit</button>
                      <!-- <button @click="saveHelp" id="savehelp" type="button" class="btn btn-primary col-md-4 offset-md-4 col-4 offset-4" disabled >Submit</button> -->
                    </div>
                  </div>
                </form>

                       
                        

                        
                    </div>

                    <!--Modal footer-->
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-primary" type="button" @click="closeModal()">Close</button>
                    </div>
                </div>
            </div>
         </div>




      </div>
      <!--===================================================-->
      <!--END CONTENT CONTAINER-->
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