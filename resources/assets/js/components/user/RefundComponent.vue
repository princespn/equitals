<template>
     <div class="">
        <div class="authincation h-100">
            <div class="container h-100">
                <div class="row justify-content-center h-100 align-items-center">
                    <div class="col-md-6">
                        <div class="authincation-content">
                            <div class="row no-gutters">
                                <div class="col-xl-12">
                                    <div class="auth-form">
                                        <div class="text-center mb-3">
                                            <a href="https://www.equitals.com/" target="_blank">
                                                <img src="public/user_files/assets/images/logo.svg" width="250"></a>
                                        </div>
                                        <h4 class="text-center mb-4">CLAIM REFUND</h4>
                                          <div class="row col-md-12" v-if="invoice.status == 'PENDING'">
                                            <div class="row ">
                                              <div class="col-md-12 ">
                                                <form class="auth-login-form mt-2" role="form" v-on:submit.prevent="claim_refund">
                                                  <div class="text">
                                                    <div class="col-md-12 form-group mb-1">
                                                       <label class="form-label mb-1">Payment ID</label>
                                                       <h4>{{invoice.paymentId}}</h4>
                                                    </div>
                                                    <div class="col-md-12 form-group mb-1">
                                                      <label class="form-label mb-1">Amount</label>
                                                       <h4>{{invoice.amount}} {{invoice.currency}}</h4>                           
                                                    </div>
                                                    <div class="col-md-12 form-group mb-1">
                                                        <label class="form-label mb-1">Enter {{invoice.currency}} Address to claim</label>
                                                        <input type="text" v-model="address" class="form-control pink-input mb-1" name="address" id="address">
                                                    </div>                          
                                                    <div class="col-md-12 form-group mb-1">
                                                      <button type="submit" class="btn btn-primary w-100">
                                                        Submit
                                                      </button>
                                                    </div>
                                                  </div>
                                                </form>
                                              </div>
                                            </div>                    
                                          </div>
                                          <div class="row col-md-12" v-else-if="invoice.status == 'EXPIRED'">
                                            <div class="row  pb-5">
                                              <div class="col-md-12 form-group mb-1">
                                               <h3 class="text-danger">EXPIRED</h3>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="row col-md-12" v-else-if="invoice.status == 'PAID'">
                                            <div class="text-center">
                                              <h3 class="text-success">ALREADY CLAIMED</h3>
                                            </div>
                                            <div class="row  pb-5 text-dark">
                                              <div class="col-md-12 form-group mb-1">
                                                <label class="form-label mb-1">Payment ID</label>
                                                <h4>{{invoice.paymentId}}</h4>
                                              </div>
                                              <div class="col-md-12 form-group mb-1">
                                                <label class="form-label mb-1">Amount</label>
                                                <h4>{{invoice.amount}} {{invoice.currency}}</h4>                           
                                              </div>
                                              <div class="col-md-12 form-group mb-1">
                                                <label class="form-label mb-1">View Transaction Details</label>
                                                <p><u><a :href="invoice.transactionHashRedirectURL" target="_blank">Click Here</a></u></p>
                                              </div>
                                            </div>
                                          </div>

                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div id="invest_load" class="loader-bg-clr hide-loader">
                      <div class="loader"></div>
                    </div>
                </div>
            </div>
      </div>
    </template>


<script>
import Vue from 'vue';
export default {
  data() {
    return {
      invoice:{
        currency:'',
        transactionHash:0,
        transactionHashRedirectURL:'',
        amount:0,
        address:'',
        paymentId:''
      },
      address:'',
      transaction_id:'',
    }
  },
  computed:{

  },
  mounted(){
    this.transaction_id = this.$route.params.transaction_id;    
    this.getRefundDetails();
    let that = this;
  },
  methods: {
    getRefundDetails(){
      $("#invest_load").removeClass("hide-loader");
      $("#invest_load").addClass("show-loader");
      axios.post('get-refund-data', {
           transaction_id: this.transaction_id
      }).then(resp => {
        if(resp.data.code == 200){
          this.invoice = resp.data.data;
    
          $("#invest_load").removeClass("show-loader");
          $("#invest_load").addClass("hide-loader");
        }else{
          $("#invest_load").removeClass("show-loader");
          $("#invest_load").addClass("hide-loader");
          this.$toaster.error(resp.data.message);
          this.$router.push({name:'fund-deposite-report'});
        }
      });
    },
    claim_refund(){
      $("#invest_load").removeClass("hide-loader");
      $("#invest_load").addClass("show-loader");
      axios.post('set-refund-data', {
           address: this.address,
           currency: this.invoice.currency,
           transaction_id:this.transaction_id
      }).then(resp => {
        if(resp.data.code == 200){
          this.invoice = resp.data.data;
    
          $("#invest_load").removeClass("show-loader");
          $("#invest_load").addClass("hide-loader");
        }else{
          $("#invest_load").removeClass("show-loader");
          $("#invest_load").addClass("hide-loader");
          this.$toaster.error(resp.data.message);
          //this.$router.push({name:'fund-deposite-report'});
        }
      });
    },
  }
}
</script>


