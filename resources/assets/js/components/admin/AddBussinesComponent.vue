<style type="text/css">
   .tooltip2 {
   top: auto;
   }
</style>
<template>
   <!-- Start content -->
   <div class="content">
      <div class="">
         <div class="page-header-title">
            <h4 class="page-title">Add And Remove Business Amount</h4>
         </div>
      </div>
      <div class="page-content-wrapper">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="panel panel-primary">
                     <div class="panel-body">
                        <div class="">
                           <form id="change-user-password" @submit.prevent="">
                              <div class="col-md-5 col-md-offset-3">
                                 <input type="hidden" name="user_id" v-model="user_id">
                                 <div class="form-group">
                                     <label>User Id</label>
                                    <input type="text" class="form-control" id="username" placeholder="User Id" name="username"  v-model="username" v-on:keyup="checkUserExisted" v-validate="'required'" data-vv-as="User Id">
                                    <div class="tooltip2" v-show="isAvialable == 'Not Available'">
                                       <div class="tooltip-inner">
                                          <span>{{ isAvialable }}</span>
                                       </div>
                                    </div>
                                    <div class="tooltip2" v-show="errors.has('username')">
                                       <div class="tooltip-inner">
                                          <span v-show="errors.has('username')">{{ errors.first('username') }}</span>
                                       </div>
                                    </div>
                                 </div>

                                  <div class="form-group"> 
                                                <label for="balance">Right Balance</label>
                                                <input type="text" class="form-control" id="r_bv" name="r_bv" v-model="r_bv" placeholder="Right Balance" readonly="" >
                                   </div>

                                    <div class="form-group"> 
                                                <label for="balance">Left Balance </label>
                                                <input type="text" class="form-control" id="l_bv" name="balance" v-model="l_bv" placeholder="Left Balance" readonly="" >
                                  </div>
                                 <!-- <div class="form-group" v-if="!optall">
                                     <label>Position</label>
                                    <select name="position" v-model="position" class="form-control">
                                    <option :value="optArr.value">{{ optArr.pos}}</option>
                                      <option v-if="pos_avl!='r'" selected value="1">Left</option>
                                      <option v-if="pos_avl!='l'" value="2">Right</option>
                                    </select> 
                                    <div class="tooltip2" v-show="errors.has('position')">
                                       <div class="tooltip-inner">
                                          <span v-show="errors.has('position')">{{ errors.first('position') }}</span>
                                       </div>
                                    </div>
                                 </div> -->

                                 <div class="form-group">
                                     <label>Position</label>
                                    <select name="position" v-model="position" class="form-control">
                                    <option selected value="">Select</option>
                                    <option value="2">Right</option>
                                    <option value="1">Left</option>
                                   <!--  <option v-for = "opt in optArr" value="opt."></option> -->
                                     <!--  <option  value="2">Right</option>
                                      <option  selected value="1">Left</option> -->
                                     
                                    </select> 
                                    <div class="tooltip2" v-show="errors.has('position')">
                                       <div class="tooltip-inner">
                                          <span v-show="errors.has('position')">{{ errors.first('position') }}</span>
                                       </div>
                                    </div>
                                 </div>
                                  <div class="form-group"> 
                                                <label for="balance">Right Business</label>
                                                <input type="text" class="form-control" id="r_business" name="r_business" v-model="r_business" placeholder="Right Business" readonly="" >
                                   </div>

                                    <div class="form-group"> 
                                                <label for="balance">Left Business </label>
                                                <input type="text" class="form-control" id="l_business" name="l_business" v-model="l_business" placeholder="Left Business" readonly="" >
                                  </div>
                            <div class="form-group">
                                     <label>Select Business Type </label>
                                    <select name="position" v-model="type" class="form-control">
                                    <option selected value="">Select</option>
                                    <option value="1">Add Business</option>
                                    <option value="3">Remove Business</option>
                             <!--        <option value="2">Add Power upto Admin</option>
                                     <option value="4">Remove Power upto Admin</option> -->
                                  
                                     
                                    </select> 
                                    <div class="tooltip2" v-show="errors.has('position')">
                                       <div class="tooltip-inner">
                                          <span v-show="errors.has('position')">{{ errors.first('position') }}</span>
                                       </div>
                                    </div>
                                 </div>
                             
                                 <div class="form-group">
                                    <label for="power bv">Enter Business</label>
                                     <input type="text" class="form-control" id="power_bv" name="power_bv" v-model="power_bv" placeholder="Enter Business Amount" v-validate="'required|integer|min_value:1'">
                                    <div class="tooltip2" v-show="errors.has('power_bv')">
                                       <div class="tooltip-inner">
                                          <span v-show="errors.has('power_bv')">{{ errors.first('power_bv') }}</span>
                                       </div>
                                    </div>
                                 </div>
                                  <div class="form-group"> 
                                                <label for="remark">Remark</label>
                                                <input type="text" class="form-control" id="remark" name="remark" v-model="remark" placeholder="Remark" >
                                 </div>

                               
                            
                                 <div class="col-md-offset-5">
                                    <button :disabled="!isCompleteForm || errors.any() || disablebtn == true" type="button" class="btn btn-primary" name="signup1" value="Sign up" @click="addPower">Submit</button>
                                 </div>
                              </div>
                           </form>
                        </div>
                     </div>
                     <!-- panel-body -->
                  </div>
                  <!-- panel -->
               </div>
               <!-- col -->
            </div>
         </div>
      </div>
   </div>
</template>

  
<script>
 import { apiAdminHost } from'./../../admin-config/config';
 import Swal from 'sweetalert2';
export default {
    components: {
       
    },
    data() {
        return {
         
          username:'',
          user_id:'',
          l_bv:'',
          r_bv:'',
          l_business:'',
          r_business:'',
          position:'',
          power_bv:'',
          isAvialable:'',
          user:'',
          id:'',
          disablebtn: false,
          pos_avl:'',
          optArr:{},
          optall: 0,
           type:'',
           remark:''

          
        }
    },
    computed:{
            isCompleteForm(){
                return this.power_bv && this.type;
            },
            
    },
    methods: {
        checkUserExisted(){
               axios.post('/checkuserexist',{
                   user_id: this.username,
               }).then(resp => {
                   if(resp.data.code === 200){
                       this.id = resp.data.data.id;
                       this.user_id = resp.data.data.user_id;
                       this.fullname = resp.data.data.fullname;
                       this.l_bv = resp.data.data.l_bv;
                       this.r_bv = resp.data.data.r_bv;
                       this.l_business = resp.data.data.l_business;
                       this.r_business = resp.data.data.r_business;
                       this.isAvialable = 'Available';

                       if(resp.data.data.power_lbv > 0){

                          this.optArr = {'value':1,'pos':'Left'};
                       }else if(resp.data.data.power_rbv > 0){
                          this.optArr= {'value':2,'pos':'Right'};
                          this.position=2;
                         
                       }else{

                           this.optall = 0;
                       } 
                      // alert(this.optArr.value);
                   } else {
                       this.user_id = '';
                       this.isAvialable = 'Not Available';
                   }
               }).catch(err => {
                   this.$toaster.error(err);
               })
        },
        addPower() {
          if(this.type == '')
            {
                      this.$toaster.error("Please Select Type")
                      this.disablebtn = 0;
                       /*$('#submit').prop('disabled', false);*/
                      return false;
                   }
            this.disablebtn = true;
            this.username = '';
                Swal({
                    title: 'Are you sure ?',
                    text: "You want to Change Bussiness!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
            axios.post('add-bussiness',{
                id: this.id,
                position:this.position,
                power_bv:this.power_bv,
                type:this.type,
                remark:this.remark,

            })
            .then(response=>{
                if(response.data.code == 200) {
                    this.$toaster.success(response.data.message)
                    this.disablebtn = false;
                    this.$router.push({name: 'addbussiness-report'});
                }else{
                    this.$toaster.error(response.data.message)
                    this.pinvalid=false;
                    this.disablebtn = false;
                    this.message=response.data.message;
                }    

            }).catch(error=>{
                this.disablebtn = false;
            });
               });  
        },

       
        
    }
}
</script>



