<style type="text/css">
  .show-loader{
    display: block;
  }
  .hide-loader{
    display: none;
  }
</style>
<template>
  <div>
    <div class="app-content content ">
      <div class="content-overlay"></div>
      <div class="header-navbar-shadow"></div>
      <div class="content-wrapper container-xxl p-0">
        <div class="content-body"><!-- account setting page -->
          <section id="page-account-settings">
            <div class="row justify-content-center">
              <div class="col-lg-6 col-md-6 col-12">
                <div class="card card-payment">
                  <div class=" text-center  mt-3">
                    <!-- <span>
                      <router-link tag="a" :to="{ name:'fund-deposite-report'}"><i class="fa fa-arrow-left"></i>
                        <span>Back</span>
                      </router-link>
                    </span> -->
                    <h4 class="text-warning text-center"><b>INVOICE PAYMENT STATUS</b></h4>
                  </div>
                  <div class="card-body" v-if="invoice.paymentStatus == 'PENDING'">
                    <div class="row  pb-5">
                      <div class="col-md-4">
                          <qrcode v-bind:value="invoice.qrcodevalue" v-bind:options="{size:300}" class="img-thumbnail"></qrcode>
                      </div>
                      <div class="col-md-8 text-center">
                        <div class="row">
                          <div class="col-md-6 card-body">
                             <h4>{{invoice.totalRemainingAmount}} {{invoice.currency}}</h4>
                             <small><b>Amount Remaining</b></small>
                          </div>
                          <div class="col-md-6 card-body">
                             <h4>{{invoice.totalReceivedAmount}} {{invoice.currency}}</h4>
                             <small><b>Total Received Amount</b></small>                           
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6 card-body">
                             <h4>{{invoice.totalAmount}} {{invoice.currency}}</h4>
                             <small><b>Total Amount</b></small>                           
                          </div>
                          <div class="col-md-6 card-body">
                             <h4 id="demo">00:00:00</h4>
                             <small><b>Time Left</b></small>                           
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text">Address</span>
                        </div>
                        <input type="text" :value="invoice.address" id="copyaddress" class="form-control pink-input" readonly="" style="pointer:none !important;">
                        <div class="input-group-append">
                          <button onclick="myFunction('copyaddress','copiedaddress')" onmouseout="outFunc()" class="btn btn-primary  copy-btn" id='copiedaddress1'>
                            Copy text
                          </button>
                        </div>
                      </div>
                    </div> 
                    <div class="row">
                      <p>
                        <small>
                          <b>Make sure send enough to cover any coin transaction fees ! only use regular sends not via any type of contract</b>
                        </small>
                      </p>
                      <p><small>Payment ID : {{invoice.paymentId}}</small></p>
                      <p><small><b>If you have made payment please <a href="#"  onclick="location.reload()">Click Here</a></b></small></p>
                    </div>
                  </div>
                  <div class="card-body text-center" v-else-if="invoice.paymentStatus == 'EXPIRED'">
                    <h2 class="text-danger">EXPIRED</h2>
                  </div>
                  <div class="card-body text-center" v-else-if="invoice.paymentStatus == 'PAID'">
                    <h2 class="text-success">PAID</h2>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
    <div id="invest_load" class="loader-bg-clr hide-loader">
      <div class="loader"></div>
    </div>
  </div>
</template>

<script>
var intervalTimer;
import Vue from 'vue'; 
import moment from 'moment';
import { apiUserHost, userAssets } from'../../user-config/config';
import Breadcrum from './BreadcrumComponent.vue';
import QrcodeVue from 'qrcode.vue';
import VueQrcode from '@xkeshi/vue-qrcode';
Vue.component(VueQrcode.name, VueQrcode);

export default {  
  components: {
    Breadcrum,
    QrcodeVue
  }, 
  data() {
    return {
      invoice:{
        qrcodevalue:'',
        currency:'',
        totalRemainingAmount:0,
        totalReceivedAmount:0,
        totalAmount:0,
        remainingTime:'00:00:00',
        address:'',
        paymentId:''
      },
      invoice_id:'',
      trans_type :'',
      size:300
    }
  },
  computed:{

  },
  mounted(){
    this.invoice_id = this.$route.params.invoice_id;
    this.trans_type = this.$route.params.type;
    this.getInvoiceDetails();
    let that = this;
    setTimeout(() => {
      that.getInvoiceDetails();
    },100000);
  },
  methods: {
    getInvoiceDetails(){
      $("#invest_load").removeClass("hide-loader");
      $("#invest_load").addClass("show-loader");
      axios.post('get-fund-invoice', {
           invoice_id: this.invoice_id
      }).then(resp => {
        if(resp.data.code == 200){
          this.invoice = resp.data.data;
          this.invoice.qrcodevalue = this.invoice.address;
          if (this.invoice.paymentStatus == "PENDING") {
            clearInterval(intervalTimer);
            this.countDownTimerStart(this.invoice.remainingTime);
          }
    
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
    countDownTimerStart(remainingTime){
      let that =this;
      let [hh, mm, ss] = remainingTime.split(':').map(s=>parseInt(s, 10));
      var date = new Date();
      var addHours = hh;
      var addMinutes = mm;
      var addSeconds = ss;

      date.setTime(date.getTime() + (addHours * 60 * 60 * 1000)); 
      date.setTime(date.getTime() + (addMinutes * 60 * 1000));
      date.setTime(date.getTime() + (addSeconds *  1000));

      var countDownDate = new Date(date).getTime();

      intervalTimer = setInterval(function() {
          var now = new Date().getTime();          

          var distance = countDownDate - now;
          
          // Time calculations for days, hours, minutes and seconds
          var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
          var seconds = Math.floor((distance % (1000 * 60)) / 1000);
          
          // Output the result in an element with id="demo"
          var element =  document.getElementById('demo');
          if (typeof(element) != 'undefined' && element != null){
            element.innerHTML = ((hours<10)?'0'+hours:hours)+":"+((minutes<10)?'0'+minutes:minutes)+":"+((seconds<10)?'0'+seconds:seconds) ;            
          }          
          // If the count down is over, write some text 
          if (distance < 0) {
              clearInterval(intervalTimer);
              var element =  document.getElementById('demo');
              if (typeof(element) != 'undefined' && element != null){
                element.innerHTML = "EXPIRED";
              }
          }
      }, 1000);
    }
  }
}
</script>


