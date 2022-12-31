<template>
  <div class="content-body">
    <div class="container-fluid">
      <div class="col-12">
        <div class="card">
          <div class="card-content">
            <div class="card-body card-dashboard text-center">
              <h2 class="content-header-title text-success">Payment Successfull !</h2>
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
  data() {
    return {
      payment_response:{},
    };
  },
  computed: {

  },
  mounted() {
    var pay_resp = this.$route.query;console.log(pay_resp);
    if(pay_resp == null || pay_resp == undefined || pay_resp==''){
      this.$toaster.error("Invalid transaction");
      this.$router.push({name:'add-fund-perfectmoney-report'});            
    }
    this.payment_response.PAYER_ACCOUNT = pay_resp.PAYER_ACCOUNT;
    this.payment_response.PAYMENT_ID = pay_resp.PAYMENT_ID;
    this.payment_response.PAYMENT_BATCH_NUMBER = pay_resp.PAYMENT_BATCH_NUM;
    this.payment_response.in_status = 1;
    this.updatePMRequest(this.payment_response);
  },
  methods: {
    updatePMRequest(resp_data){
     axios.post('update_pm_transaction',resp_data)
        .then(response => {
          if(response.data.code === 200){
            this.$toaster.success(response.data.message);   
            this.$router.push({name:'add-fund-perfectmoney-report'});
          }else{
            this.$toaster.error(response.data.message);
            this.$router.push({name:'add-fund-perfectmoney-report'});            
          }
          //this.currency_code = response.data.data;
        })
        .catch(error => {
        });
    },
  }
};
</script>