<template>
    <!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Coin (Token ) Buy Status Changes</h4>
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
                                            

                                            <div class="form-group"> 
                                                <label for="balance">ICO On Off Status</label>
                                                <select class="form-control" v-model="ico_status">
                                                    <option value="on"> On</option>
                                                    <option value="off"> Off</option>
                                                </select>
                                                
                                             </div>
                                             <div class="form-group"> 
                                                <label for="balance">Admin Error Message</label>
                                                <input type="text" class="form-control" id="balance" name="balance" v-model="ico_admin_error_msg" placeholder="Admin Error Message" >
                                             </div>
                                            
                                            

                                            <div class="col-md-offset-5">
                                                <button type="button" class="btn btn-primary text-center" @click="saveIcoStatusFun">Submit</button>
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
                ico_status:'',
                ico_admin_error_msg:'',
           
            }
        }, 

    created(){
      this.getUserDetails();
    },       
        computed: {
            // isComplete () {
            //     return this.fund.user_id && this.fund.amount;
            // }
          
        },
        mounted() {
            this.getIcoStatusFun();
        },
        methods: {
            
           getIcoStatusFun(){
              axios.get('getIcoStatus')
              .then(response=>{
                    if(response.data.code==200){
                       this.ico_status = response.data.data.ico_status;
                       this.ico_admin_error_msg = response.data.data.ico_admin_error_msg;
                    }else{
                      this.$toaster.error(response.data.message);
                    }
                  }).catch(error=>{

                  })
              },
           
            saveIcoStatusFun(){
                axios.post('/saveIcoStatus',{
                    ico_status: this.ico_status,
                    ico_admin_error_msg: this.ico_admin_error_msg,
                }).then(resp => {
                    if(resp.data.code === 200){
                         this.$toaster.success(resp.data.message);
                        
                    } else {//success

                        this.$toaster.error(resp.data.message);
                       
                    } 
                }).catch(err => {
                    this.$toaster.error(err);
                })
            },
           
        }
    }
</script>