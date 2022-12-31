<template>
    <!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Add Member</h4>
            </div>
        </div>

        <div class="page-content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <div class="">
                                    <form id="addDeduction">
                                        <input type="hidden" name="memberID" id="memberID" v-model="memberID" />
                                        <div class="col-md-5 col-md-offset-3">
                                            <input type="hidden" name="user_id" v-model="fund.user_id">
                                            <div class="form-group">
                                                <label>Member Id</label>
                                                <input type="text" name="member" class="form-control" id="member" placeholder="Member Id" v-model="member"  >
                                                <div class="clearfix"></div>
                                            </div>

                                            <div class="form-group"> 
                                                <label for="balance">Receiver Id</label>
                                                <input type="text" class="form-control" id="receiver" name="receiver" v-model="receiver" placeholder="Receiver Id"  >
                                             </div>

                                            <div class="form-group">
                                                <label class="control-label">Pass Key</label>
                                                <input type="password" class="form-control" id="passkey" name="passkey" v-model="passkey"  placeholder="Enter passkey" >
                                                <div v-show='errors.has("amount")' class="tooltip2">
                                                  <span class=" text-danger error-msg-size tooltip-inner"> {{ errors.first("amount") }}</span>
                                                </div>
                                            </div>

                                            <div class="col-md-offset-5">

                                                <button type="button" id="btnAdd" class="btn btn-primary text-center" :disabled = "!isComplete || errors.any()"  @click="add_mem">Add Member</button>
                                                <button type="button" id="btnUpdate" class="btn btn-primary text-center" :disabled = "!isComplete || errors.any()"  @click="update_mem">Update Member</button>
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
                    amount:'',
                    remark:'',
                    wallet:''
                },
              
                receiver: '',
                username:'',
                values:'',
                getdata:{},
                arrProduct:[],
                min_hash:'',
                max_hash:'',
                isValid:true,
                usermsg:'',
                member:'',
                memberID:'',
                passkey:'',
                working_balance:''
               // isDisabledBtn:true,
             
            }
        }, 

    created(){
      this.getUserDetails();
    },       
        computed: {
            isComplete () {
                return this.member && this.receiver && this.passkey;
            },
          
        },
        mounted() {
        },
        methods: {
            
           getUserDetails(){
              axios.get('member-details')
              .then(response=>{
                    if(response.data.code==200){
                      this.member = response.data.data.member;
                      this.receiver = response.data.data.receiver;
                      this.passkey = response.data.data.passkey;
                      this.memberID = response.data.data.memberID;
                      // this.ref_fullname = response.data.data.ref_fullname;
                      document.getElementById('btnAdd').style.display = "none";
                      document.getElementById('btnUpdate').style.display = "show";
                    }else{
                      //this.$toaster.error(response.data.message);
                      document.getElementById('btnUpdate').style.display = "none";
                      document.getElementById('btnAdd').style.display = "show";
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
                        this.working_balance = resp.data.data.balance;
                        this.acc_balance = resp.data.data.acc_wallet;

                    } else {
                        this.fund.user_id = '';
                        this.isAvialable = 'Not Available';
                    }
                }).catch(err => {
                    this.$toaster.error(err);
                })
            },
          

            add_mem(){   
                this.$validator.validate().then(valid => {
                if (valid) {
                    let formData = new FormData();
                
                    formData.append('member', this.member);
                    formData.append('receiver', this.receiver);
                    formData.append('passkey', this.passkey);
                    axios.post('add_member',formData)
                    .then(response=>{
                        if(response.data.code==200){
                            this.$toaster.success(response.data.message)
                            setTimeout(function(){ location.reload(); },1000);
                            // /this.$router.push({name: 'admin-add-deduction-report'});
                        }else{
                            this.$toaster.error(response.data.message)
                        }
                    }).catch(error=>{

                    });
                }
                });
            },
            update_mem(){
                this.$validator.validate().then(valid => {
                if (valid) {
                    let formData = new FormData();
                    formData.append('memberID', this.memberID);
                    formData.append('member', this.member);
                    formData.append('receiver', this.receiver);
                    formData.append('passkey', this.passkey);
                    axios.post('update_member',formData)
                    .then(response=>{
                        if(response.data.code==200){
                            this.$toaster.success(response.data.message)
                            setTimeout(function(){ location.reload(); },1000);
                            // /this.$router.push({name: 'admin-add-deduction-report'});
                        }else{
                            this.$toaster.error(response.data.message)
                        }
                    }).catch(error=>{

                    });
                }
                });
            },
        }
    }
</script>