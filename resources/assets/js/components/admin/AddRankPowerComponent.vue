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
            <h4 class="page-title">Add Rank Powar</h4>
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
                                    <label for="power bv">Full name</label>
                                     <input type="text" class="form-control" id="fullname" name="fullname" v-model="fullname" readonly v-validate="'required'">
                                     <div class="tooltip2" v-show="errors.has('fullname')">
                                       <div class="tooltip-inner">
                                          <span v-show="errors.has('fullname')">{{ errors.first('fullname') }}</span>
                                       </div>
                                    </div>
                                 </div>

                                    <div class="form-group">
                                     <label>Select Rank</label>
                                        <select class="form-control" id="rank_name" v-model="rank" name="rank">
                                            <option :value="null"  selected >Select</option>
                                            <option v-for="option in getTypes" v-bind:value="option.rank" :key="option"> {{ option.rank  }}</option> 
                                        </select>
                                    <div class="tooltip2" v-show="errors.has('rank')">
                                       <div class="tooltip-inner">
                                          <span v-show="errors.has('rank')">{{ errors.first('rank') }}</span>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group">
                                     <label>Position</label>
                                    <select name="position" v-model="position" class="form-control" @change="rankCount">
                                    <option selected value="">Select</option>
                                    <option value="r">Right</option>
                                    <option value="l">Left</option>
                                    </select> 
                                    <div class="tooltip2" v-show="errors.has('position')">
                                       <div class="tooltip-inner">
                                          <span v-show="errors.has('position')">{{ errors.first('position') }}</span>
                                       </div>
                                    </div>
                                 </div>
                               
                                <div class="form-group">
                                    <label>Rank Count</label>
                                    <input name="rankname" class="form-control"  type="text"  v-model="rankname" v-validate="'required'" readonly >
                                    <div class="tooltip2" v-show="errors.has('rankname')">
                                        <div class="tooltip-inner">
                                            <span v-show="errors.has('rankname')">{{ errors.first('rankname') }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                 <!-- <div class="form-group">
                                     <label>Select Power Type </label>
                                    <select name="type" v-model="type" class="form-control">
                                    <option selected value="">Select</option>
                                    <option value="add">Add Rank Power</option>
                                    <option value="remove">Remove Rank Power</option>
                                    </select> 
                                    <div class="tooltip2" v-show="errors.has('type')">
                                       <div class="tooltip-inner">
                                          <span v-show="errors.has('type')">{{ errors.first('type') }}</span>
                                       </div>
                                    </div>
                                 </div> -->

                                 <div class="form-group">
                                    <label for="power bv">Add Power</label>
                                     <input type="text" class="form-control" id="power" name="power" v-model="power" placeholder="Enter Power" v-validate="'required|integer|min_value:1'">
                                    <div class="tooltip2" v-show="errors.has('power')">
                                       <div class="tooltip-inner">
                                          <span v-show="errors.has('power')">{{ errors.first('power') }}</span>
                                       </div>
                                    </div>
                                 </div>

                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-primary text-center" @click="addPower()">Submit</button>
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
         fullname:'',
          username:'',
          user_id:'',
          position:'',
          isAvialable:'',
          user:'',
          id:'',
          disablebtn: false,
         //  pos_avl:'',
            // type:'',
            ranks:[],
            getTypes:[],
            // optArr:{},
         //  optall: 0,
          power:0,
          rank:'',   
         //  rankcount:'' ,
          rankname:'',                                                                                                                 

          
        }
    },
    mounted() {
           
            this.getTransactionType();
        },
    computed:{
            // isCompleteForm(){this.power},
            
    },
    methods: { 
       rankCount()
       {
            var position_rank = this.position;
            var rank_name_last = this.rank.toLowerCase().trim();
            var rank_last_name = position_rank+"_"+rank_name_last;
            // alert(rank_last_name);
            axios.post('/getRankCount',{
                   rank_name: rank_last_name,
                    user_id: this.username,
               }).then(resp => {
                   if(resp.data.code === 200){
                      this.rankname=resp.data.data.rank_name; 
                  //  console.log(this.rankname);
                   }
               }).catch(err => {
                   this.$toaster.error(err);
               })
       },
       
        checkUserExisted(){
               axios.post('/checkuserexist',{
                   user_id: this.username,
               }).then(resp => {
                   if(resp.data.code === 200){
                       this.id = resp.data.data.id;
                       this.user_id = resp.data.data.user_id;
                       this.fullname = resp.data.data.fullname;
                       this.isAvialable = 'Available';
                   
                   } else {
                       this.user_id = '';
                       this.isAvialable = 'Not Available';
                   }
               }).catch(err => {
                   this.$toaster.error(err);
               })
        },

        


        getTransactionType(){
                    axios.get('/get-all-rank', {
                })
                .then(response => {
                    this.getTypes = response.data.data;
                })
                .catch(error => {
                });      
         },


         
         
          addPower() {
         //  if(this.type == '')
         //    {
         //              this.$toaster.error("Please Select Type")
         //              this.disablebtn = 0;
         //               /*$('#submit').prop('disabled', false);*/
         //              return false;
         //           }
         //    this.disablebtn = true;
            this.username = '';
                Swal({
                    title: 'Are you sure ?',
                    text: "You want to Add Power!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
            axios.post('add_rankpower',{
                id: this.id,
                position:this.position,
                power:this.power,
               //  type:this.type,
                rank: this.rank,
               
            })
            .then(response=>{
                if(response.data.code == 200) {
                    this.$toaster.success(response.data.message)
                    this.disablebtn = false;
                     this.$router.push({name: 'AddRankPowerReport'});
                   
                }else{
                    this.$toaster.error(response.data.message)
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



