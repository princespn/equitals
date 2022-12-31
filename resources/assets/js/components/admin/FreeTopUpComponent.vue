<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Free Top Up</h4>
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

                                            
                                             <div class="form-group">
                                                <label>Payment Type</label>
                                                <select name="payment_type" class="form-control" placeholder="Select Payment Type" v-model="topup.payment_type" id="">
                                                    <option value="BTC">BTC</option>
                                                    <option value="INR">INR</option>
                                                </select>
                                            </div>
                                           
  											<div class="form-group">
    											<label>Select Plan</label>
                                                <select name="product_id" class="form-control" v-model="topup.product_id" id="product_id" @change="changeSelect($event)" >
                                                    <option :value="null">Select Package</option>
                                                    <option v-for="product_id in product_ids" v-bind:value="product_id.id"> {{ product_id.name }}</option> 
                                                </select>
  											</div>
                                           

                                            <div class="form-group" v-if="topup.product_id">
                                                <label>Amount</label>

                                                <input type="number" class="form-control" name="hash_unit" 
                      min="1"
                      step="1"
                      onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                      title="Numbers only"  v-model="topup.hash_unit" v-validate="'required|numeric'" v-on:keyup="hashvalidation" >
                                                                   <!--  <div class="tooltip-inner">
                                                                       <span v-show="errors.has('hash_unit')">{{ errors.first('hash_unit') }}</span>
                                                                    </div> -->
                                                <p v-if='!isValid' >
                                                    <span class=" text-danger error-msg-size tooltip-inner"> {{ this.usermsg }}</span>
                                                </p>  
                                            </div>
                                            
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
            }
        },        
        computed: {
            isComplete () {
                return this.topup.user_id && this.topup.payment_type && this.topup.product_id && this.topup.hash_unit && this.usermsg=='';
            },
            isCompleteTransferPin(){
              return this.product_id && this.no_of_pins && this.payment_mode;  
            }
        },
        mounted() {
            this.getProducts();
        },
        methods: {
            changeSelect(event){
                let user = _.split(event.target.value, '-', 2); //using lodash here. You can also just use js split func
                let id = user[0]; // your id
                this.min_hash=this.arrProduct[id].min_hash;
                this.max_hash=this.arrProduct[id].max_hash;
                this.activeDiv=true;
               // this.usermsg='Amount should be on range ' + this.min_hash + ' to ' + this.max_hash; 
            },
            addTopUp(){
                this.isDisabledBtn = false;
                Swal({
                    title: 'Are you sure ?',
                    text: "You want to topup!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/store/freetopup',{
                            id: this.topup.user_id,
                            // pin: this.topup.pin,
                            product_id: this.topup.product_id,   
                            hash_unit: this.topup.hash_unit,
                            payment_type: this.topup.payment_type,                         
                        }).then(resp => {
                            if(resp.data.code === 200) {
                                this.$toaster.success(resp.data.message);
                            } else {
                                this.$toaster.error(resp.data.message);
                            }
                            //$('#addTopUp').trigger("reset");
                            this.username = '';
                            this.topup = {
                                user_id: '',
                                pin: '',
                                product_id: null,
                                hash_unit:''
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
            hashvalidation(){
                // if(this.topup.product_id==1){
                //     this.min_hash=this.max_hash;
                // }
                if(this.topup.hash_unit< this.min_hash || this.topup.hash_unit >this.max_hash){
                     this.usermsg='Amount should be on range ' + this.min_hash + ' to ' + this.max_hash;
                     this.isValid = false;
                }else{
                    this.isValid = false;
                    this.usermsg='';
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