<template>
	<div>
  	<div class="page-content">
      <div class="container-fluid">
    

        <section class="card">
          <div class="card-block">
            

            <h5 class="with-border m-t-lg text-center">Team Wallet Withdraw</h5>

            <div class="row">
              <div class="col-lg-7 offset-lg-2">
                <fieldset class="form-group">
                  <label class="form-label" for="exampleInput">Balance</label>
                  <input type="text" class="form-control maxlength-simple" v-model="balance" readonly>
                  
                </fieldset>
              </div>
            <div class="col-lg-7 offset-lg-2">
                <fieldset class="form-group">
                  <label class="form-label" for="exampleInput">Amount</label>
                  <input type="text" class="form-control maxlength-simple" v-model="amount"  maxlength="15"  name="amount" 
                      min="1"
                      step="1"
                      onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                      title="Numbers only" v-validate="'required|numeric'" v-on:keyup="hashvalidation" >
               <div class="tooltip2" v-show="errors.has('amount')">
                  <div class="tooltip-inner">
                     <span v-show="errors.has('amount')">{{ errors.first('amount') }}</span>
                  </div>
               </div>
               <span v-show="usermsg != ''">{{ usermsg }}</span>
                </fieldset>
              </div>

              <div class="col-lg-7 offset-lg-2">
                <fieldset class="form-group">
                  <label class="form-label" for="exampleInput">Address</label>
                  <input type="text" class="form-control maxlength-simple" v-model="btc" readonly>
                  
                </fieldset>
              </div>
            
            </div>




            <div class="row">
              <div class="col-md-12 col-sm-12 text-center">
                <button :disabled="disablebtn == true || errors.any()" class="btn btn-rounded btn-primary-outline" @click="withdrawAmount">Withdraw</button>
              </div>
            </div>
          </div>
        </section>
      </div><!--.container-fluid-->
    </div>
	</div>
</template>

<script>
	import moment from 'moment';
	import { apiUserHost } from'../../user-config/config';
	import Breadcrum from './BreadcrumComponent.vue';
    export default {  
        components: {
            Breadcrum
        },
        data(){
        	 return {
             disablebtn:false,
             amount:0,
             balance:'',
             min_hash:25,
             isValid:true,
             usermsg:'',
             btc:'',

         }

        },
        computed: {
        	/*isCompleteChat() {
        		return this.message;      
        	}*/
        },
        created(){
           // this.getPackages();
        },
        mounted(){
        	this.getBalance(); 
          this.getBTCAddress(); 
        },
        methods:{
            trigger () {
    			this.sendMessage();
    		},
            getPackages(){ 
                axios.post('get-packages')
                .then(response => {
                	//alert();
                    this.packageArr = response.data.data;
                    //console.log(this.packageArr);
                    // debugger;
                   
                })
                .catch(error => {
                }); 
            },
            getBTCAddress(){ 
                axios.post('get-profile-info')
                .then(response => {

                  //alert();
                    this.btc = response.data.data.btc_address;
                    //console.log(this.packageArr);
                    // debugger;
                   
                })
                .catch(error => {
                }); 
            },
			sendMessage() {
                axios.post('send-message', { 
                    to_user:1,
                    message: this.message,
                })
                .then(response => {
                    if(response.data.code == 200){
                        this.$toaster.success(response.data.message);
                        this.message='';
        				this.getMessages();     
                    } else {
                       this.$toaster.error(response.data.message);
                    }
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
                    
            closeModal(){
                $(".overlay").hide();
                $(".loader").hide();
            },
            hashvalidation(){
                // if(this.topup.product_id==1){
                //     this.min_hash=this.max_hash;
                // }

                if(this.amount< this.min_hash){
                     this.usermsg='Amount should be greater ' + this.min_hash;
                     this.isValid = false;
                }else{
                    this.isValid = false;
                    this.usermsg='';
                }
          
            },
            getBalance(){
              
              axios.post('get-working-balance')
                .then(response => {
                    if(response.data.code == 200){

                        this.balance = response.data.data;
                      //
                    } else {
                       //this.$toaster.error(response.data.message);
                       
                    }
                })
            }, 
            withdrawAmount(){
               if(this.balance < this.amount){
                  this.$toaster.error('Insufficient balance');
                  return false;
               }
               axios.post('working-wallet-withdraw',{
                amount:this.amount,
                address:this.btc,
                mode:'BTC'
               })
                .then(response => {
                    if(response.data.code == 200){
                       //
                       this.$toaster.success(response.data.message);
                       this.amount = 0;
                       this.getBalance();
                    } else {
                        this.$toaster.error(response.data.message);
                       
                    }
              })

            }           
        }
    }
</script>