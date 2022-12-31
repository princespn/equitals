<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Make Franchisee</h4>
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
                            				<input type="hidden" name="user_id" v-model="user_id">
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
  												<button type="button" class="btn btn-primary text-center" @click="Addfranchisee" :disabled="!isComplete || !isDisabledBtn">Submit</button>
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
                   	user_id: '',
                    pin: '',
                	product_id: null,
                    hash_unit:'',
                    payment_type:'BTC',
                
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
                }
            }
        },        
        computed: {
            isComplete () {
                return this.user_id && this.usermsg=='';
            },
            isCompleteTransferPin(){
              return this.product_id && this.no_of_pins && this.payment_mode;  
            }
        },
        mounted() {
            //this.getProducts();
            //this.getFranchiseUserList();
        },
        methods: {
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
            Addfranchisee(){
                this.isDisabledBtn = false;
                Swal({
                    title: 'Are you sure ?',
                    text: "You want to make franchise!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/makeAsFranchise',{
                            user_id: this.username,
                                     
                        }).then(resp => {
                            this.isDisabledBtn = true;
                            if(resp.data.code === 200) {
                                this.$toaster.success(resp.data.message);
                                 this.$router.push({name: 'user/franchise-user-report'});
                            } else {
                                this.$toaster.error(resp.data.message);
                            }
                            //$('#addTopUp').trigger("reset");
                            
                           
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
                    	this.user_id = resp.data.data.id;
                    	this.isAvialable = 'Available';
                    } else {
                    	this.user_id = '';
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