<template>
    <!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Send Token To User</h4>
            </div>
        </div>

        <div class="page-content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <div class="">
                                    <form id="addFund">
                                        <div class="col-md-5 col-md-offset-3">
                                            <input type="hidden" name="user_id" v-model="fund.user_id">


                                             <div class="form-group"> 
                                                <label for="balance">Admin Balance</label>
                                                <input type="text" class="form-control"  v-model="admin_coin_balance"  readonly="" >
                                             </div>
                                            <div class="form-group">
                                                <label>User Id</label>
                                                <input type="text" name="username" class="form-control" id="username" placeholder="User Id" v-model="username" v-on:keyup="checkUserExisted">
                                                <div class="clearfix"></div>
                                                <p :class="{'text-success': isAvialable == 'Available','text-danger': isAvialable == 'Not Available'}" v-if="isAvialable!='' && username!=''">{{isAvialable}}</p>
                                            </div>

                                           

                                             <div class="form-group"> 
                                                <label for="balance">Token Balance</label>
                                                <input type="text" class="form-control" id="balance" name="balance" v-model="coin_balance" placeholder="Balance" readonly="" >
                                             </div>
                                          <!--    <div class="form-group"> 
                                                <label for="balance"> Phases</label>
                                                <input type="text" class="form-control" id="balance" name="balance" v-model="ico_arr.name" placeholder="Balance" readonly="" >
                                             </div>
 -->
                                            <div class="form-group">
                                                <label class="control-label">Enter Token</label>
                                                <input type="text" class="form-control" id="coins" name="coins" v-model="fund.coins"  placeholder="Enter coins" v-validate="'required|numeric|min_value:1'">
                                                <div v-show='errors.has("coins")' class="tooltip2">
                                                  <span class=" text-danger error-msg-size tooltip-inner"> {{ errors.first("coins") }}</span>
                                                </div>
                                            </div>

                                            

                                            <div class="col-md-offset-5">
                                                <button type="button" class="btn btn-primary text-center" @click="fund_req">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div><!-- panel-body -->
                        </div><!-- panel -->
                    </div><!-- col -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { apiAdminHost } from'./../../admin-config/config';
    import Swal from 'sweetalert2';

    export default {
        
        data() {
            return {
                product_ids: {
                    id: '',
                    name: ''
                },
                topup:{
                    user_id: '',
                    pin: '',
                    product_id: null,
                    hash_unit:'',
                    payment_type:'BTC',
                },

                fund:{
                    user_id:'',
                    fullname:'',
                    coins:'',
                    remark:'',
                },
                ico_arr:{
                    name:''
                },
              
                balance: 0,
                coin_balance: 0,
                isAvialable:'',
                username:'',
                values:'',
                getdata:{},
                arrProduct:[],
                min_hash:'',
                max_hash:'',
                isValid:true,
                usermsg:'',
                admin_coin_balance:'',
               // isDisabledBtn:true,
             
            }
        }, 

    created(){
      this.getUserDetails();
     //this.adminBal();
    },       
        computed: {
            // isComplete () {
            //     return this.fund.user_id && this.fund.amount;
            // }
          
        },
        mounted() {

            this.geticophasesfun();
            this.adminBal();
        },
        methods: {

            adminBal(){
                axios.post('/checkuserexist',{
                    user_id: 'admin',
                }).then(resp => {
                    if(resp.data.code === 200){

                        
                        
                        this.admin_coin_balance = resp.data.data.coin_balance;
                    } else {
                       
                    }
                }).catch(err => {
                    //this.$toaster.error(err);
                })
            },
            
           getUserDetails(){
              axios.get('user-details')
              .then(response=>{
                    if(response.data.code==200){
                      this.user_id = response.data.data.user_id;
                      // this.referral_id = response.data.data.ref_user_id;
                      this.fullname = response.data.data.fullname;
                      // this.ref_fullname = response.data.data.ref_fullname;
                    }else{
                      this.$toaster.error(response.data.message);
                    }
                  }).catch(error=>{

                  })
              },
           
            checkUserExisted(){
                axios.post('/checkuserexist',{
                    user_id: this.username,
                }).then(resp => {
                    if(resp.data.code === 200){

                        this.fund.user_id = resp.data.data.id;
                        this.isAvialable = 'Available';
                        this.balance = resp.data.data.balance;
                        this.coin_balance = resp.data.data.coin_balance;
                    } else {
                        this.fund.user_id = '';
                        this.isAvialable = 'Not Available';
                    }
                }).catch(err => {
                    this.$toaster.error(err);
                })
            },
            geticophasesfun(){
                axios.post('/getIcoPhasesLive',{
                    user_id: this.username,
                }).then(resp => {
                    if(resp.data.code === 200){
                        this.ico_arr = resp.data.data;

                    } else {
                        this.fund.user_id = '';
                        this.isAvialable = 'Not Available';
                    }
                }).catch(err => {
                    this.$toaster.error(err);
                })
            },
            fund_req(){   
            this.$validator.validate().then(valid => {
              if (valid) {
                let formData = new FormData();
              
                formData.append('user_id', this.username);
                formData.append('coin', this.fund.coins);
                formData.append('srno', this.ico_arr.srno);
                axios.post('transferIcoCoin',
                    formData,{
                        headers: {
                                  'Content-Type': 'multipart/form-data'
                          }
                    }
                    
                  ).then(response=>{
                      if(response.data.code==200){
                           this.$toaster.success(response.data.message)
                          this.$router.push({name: 'ico-admin-send-rep'});
                      }else{
                           this.$toaster.error(response.data.message)
                      }
                     // $('#addFund').trigger("reset");
                }).catch(error=>{

                });
              }
            });
          },
        }
    }
</script>