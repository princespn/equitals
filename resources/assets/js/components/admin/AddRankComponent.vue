<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Add Rank</h4>
		    </div>
		</div>

		<div class="page-content-wrapper">
		    <div class="container">
		    	<div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <div class="">
                                    <form id="addTopUp">
                            			<div class="col-md-5 col-md-offset-3">
                            				<input type="hidden" name="user_id" v-model="topup.user_id">
                            				<div class="form-group">
    											<label>User Id</label>
    											<input type="text" name="username" class="form-control" id="username" placeholder="User Id" v-model="username" v-on:keyup="checkUserExisted">
    											<div class="clearfix"></div>
	                        					<p :class="{'text-success': isAvialable == 'Available','text-danger': isAvialable == 'Not Available'}" v-if="isAvialable!='' && username!=''">{{isAvialable}}</p>
  											</div>

                                             <!-- <div class="form-group">
                                                <label>Payment Type</label>
                                                <select name="payment_type" class="form-control" placeholder="Select Payment Type" v-model="topup.payment_type" id="">
                                                    <option value="BTC" selected="">BTC</option>
                                                   
                                                </select>
                                            </div> -->
                                           
  										    <div class="form-group">
                                                <label>Select Rank</label>
                                                 <select class="form-control" v-model="topup.rank">
                                                    <!-- v-model="topup.country"
                                                  name="package_id"
                                                  class="form-control"
                                                  @change="getFranchiseOnCountry(topup.country)" -->
                                                  <option disabled value="" selected>Select Rank</option>
                                                  <option value="Ace" selected>Ace</option>
                                                  <!-- <option
                                                    v-for="co in ranks"
                                                    v-bind:value="co.rank"
                                                  >{{ co.rank }}</option> -->
                                                </select>
                                            </div>



                                            <!-- <div class="form-group">
                                                <label>Select Franchise</label>
                                                <select name="franchise_user_id" class="form-control" v-model="franchise.user_id" id="franchise_user_id" >
                                                    <option value="" selected>Select Franchise</option>
                                                    <option v-for="franchise_user in franchise" v-bind:value="franchise_user.id">{{ franchise_user.user_id }} </option>
                                                </select>
                                            </div> -->

<!-- 
                                            <div class="form-group">
                                                <label>Select Master  Franchise</label>
                                                <select name="franchise_user_id" class="form-control" v-model="masterfranchise.user_id" id="franchise_user_id" >
                                                    <option value="" selected>Select Master Franchise</option>
                                                    <option v-for="franchise_user in mflist" v-bind:value="franchise_user.id">{{ franchise_user.user_id }} </option>
                                                </select>
                                            </div> -->
                                              
                                            
                                            <!-- <div class="form-group">
                                                <label>Select Plan</label>
                                                <select name="product_id" class="form-control" v-model="topup.product_id" id="product_id" @change="changeSelect($event)" >
                                                    <option :value="null">Select Package</option>
                                                    <option v-for="product_id in product_ids" v-bind:value="product_id.id"> {{ product_id.name }}</option> 
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Enter Amount</label>
                                                <br />
                                                <input
                                                type="text"
                                                class="form-control"
                                                name="hash_unit"
                                                min="1"
                                                step="1"
                                                onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                                title="Numbers only"
                                                v-model="topup.hash_unit"
                                                v-validate="'required|numeric'"
                                                v-on:keyup="hashvalidation"
                                                />
                                                <div class="clearfix"></div>
                                                <p class="text-danger">{{usermsg}}</p>
                                            </div> -->

                                           
                                       <!--     
                                            <div class="form-group" v-if="topup.product_id">
                                                <label>Amount</label>

                                                <input type="number" class="form-control" name="hash_unit" 
                      min="1"
                      step="1"
                      onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                      title="Numbers only"  v-model="topup.hash_unit" v-validate="'required|numeric'" v-on:keyup="hashvalidation" >
                                                                   
                                                <p v-if='!isValid' >
                                                    <span class=" text-danger error-msg-size tooltip-inner"> {{ this.usermsg }}</span>
                                                </p>  
                                            </div> -->
                                            
  											<div class="col-md-offset-5">
  												<button type="button" class="btn btn-primary text-center" @click="addTopUp" :disabled="!isComplete || !isDisabledBtn">Submit</button>
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
                    rank: 'Ace',
                	product_id: null,
                    hash_unit:'',
                    payment_type:'BTC',
                },
                isAvialable:'',
                username:'',
                values:'',
                getdata:{},
                arrProduct:[],
                min_hash:'',
                max_hash:'',
                isValid:true,
                usermsg:'',
                isDisabledBtn:true,
                franchise:{
                    user_id:'',
                },
                  masterfranchise:{
                    user_id:'',
                    id:''
                  },
                  masterFranchiseList:[],
                  ranks:[],
                  mflist:{},
            }
        },        
        computed: {
            //
            isComplete () {
                return this.topup.user_id /*&& this.topup.product_id && this.topup.hash_unit && this.usermsg==''*/;
            },
            isCompleteTransferPin(){
              return this.product_id && this.no_of_pins && this.payment_mode;  
            }
        },
        mounted() {
            this.getProducts();
            //this.getFranchiseUserList();
            this.getMasterFranchiseUserList();
            this.getCountry()
        },
        methods: {
             
       getCountry() {
      axios
        .get("../rank", {})
        .then(response => {
          //this.countries = response.data.data;
          this.ranks = response.data.data;

        })
        .catch(error => {});
    },


            getFranchiseOnCountry(country){

                    axios.post("get-franchise-users", {country:country})
                .then(response => {
                    this.franchise = response.data.data;
                })
                .catch(error => {

                });

            },

             ///--- 
    getMasterFranchiseUserList(){
      axios.get("get-master-franchise-users", {})
      .then(response => {
        this.masterFranchiseList = response.data.data;
        this.mflist = response.data.data;
      })
      .catch(error => {

      });
    },

            getFranchiseUserList(){
                axios.get("get-franchise-users", {})
                .then(response => {
                    this.franchise = response.data.data;
                })
                .catch(error => {

                });
            },
            changeSelect(event){
                let user = _.split(event.target.value, '-', 2); //using lodash here. You can also just use js split func
                let id = user[0]; // your id
                this.min_hash=this.arrProduct[id].min_hash;
                this.max_hash=this.arrProduct[id].max_hash;
                this.activeDiv=true;
                this.hashvalidation();
               // this.usermsg='Amount should be on range ' + this.min_hash + ' to ' + this.max_hash; 
            },
            addTopUp(){
                this.isDisabledBtn = false;
                Swal({
                    title: 'Are you sure ?',
                    text: "You want to Add Rank!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.value) {
                        /*/store/topup*/
                        axios.post('/store/AddRank',{
                            id: this.topup.user_id,
                            // pin: this.topup.pin,
                            rank: this.topup.rank, 
                              
                            /*hash_unit: this.topup.hash_unit,      
                            payment_type: this.topup.payment_type, 
                            franchise_user_id:this.franchise.user_id,
                            masterfranchise_user_id:this.masterfranchise.user_id,
                            device:'web',
                            topupfrom:'admin panel'*/                       
                        }).then(resp => {
                            if(resp.data.code === 200) {
                                this.$toaster.success(resp.data.message);
                                 this.$router.push({name: 'AddRankReport'});
                            } else {
                                this.$toaster.error(resp.data.message);
                            }
                            //$('#addTopUp').trigger("reset");
                            this.username = '';
                            this.topup = {
                                user_id: '',
                                rank: '',
                                /*pin: '',
                                product_id: null,
                                hash_unit:''*/
                            };
                            this.isDisabledBtn = true;
                        }).catch(err => {
                            this.$toaster.error(err);
                        });
                    }
                    else{
                        this.isDisabledBtn = true;
                    }
                });     
            },
			checkUserExisted(){
				axios.post('/checkuserexist',{
                    user_id: this.username,
                }).then(resp => {
                    if(resp.data.code === 200){
                    	this.topup.user_id = resp.data.data.id;
                    	this.isAvialable = 'Available';
                    } else {
                    	this.topup.user_id = '';
                    	this.isAvialable = 'Not Available';
                    }
                }).catch(err => {
                	this.$toaster.error(err);
                })
			},
            hashvalidation() {
                if (this.topup.hash_unit < this.min_hash || this.topup.hash_unit > this.max_hash) 
                {
                    this.usermsg = "Amount should be on range " + this.min_hash + " to " + this.max_hash;
                    this.isValid = false;
                } else {
                    this.isValid = true;
                    this.usermsg = "";
                }
            },
            getProducts(){

                axios.post('/show/products',{
                    // length : 10,
                    // start  : 0,
                    status: 'Active',
                }).then(resp => {
                    if(resp.data.code === 200){
                        this.product_ids = resp.data.data;
                        for(var i in this.product_ids){
                            this.arrProduct[this.product_ids[i].id] = this.product_ids[i];
                        }
                    } else {
                        this.$toaster.error(resp.data.message);
                    }
                }).catch(err => {
                    this.$toaster.error(err);
                })
            }
        }
    }
</script>