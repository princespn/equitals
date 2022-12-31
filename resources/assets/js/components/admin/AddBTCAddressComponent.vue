<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Add BTC Adress </h4>
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
    											<label>BTC Address</label>
    											<input type="text" name="address" class="form-control" id="address" placeholder="Enter Address" v-model="address" >
    											<div class="clearfix"></div>
	                        					<p :class="{'text-success': isAvialable == 'Available','text-danger': isAvialable == 'Not Available'}" v-if="isAvialable!='' && username!=''">{{isAvialable}}</p>
  											</div>
                                           
  											<div class="form-group">
    											<label>Remark</label>
                                               <textarea name="remark" class="form-control" id="remark" placeholder="Enter Remark" v-model="remark" ></textarea>
  											</div>
                                          
                                            
  											<div class="col-md-offset-5">
  												<button type="button" class="btn btn-primary text-center" @click="addAddress">Submit</button>
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
                    hash_unit:''
                },
                isAvialable:'',
                address:'',
                remark:'',
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
        },
        mounted() {
           // this.getProducts();
        },
        methods: {
            
            addAddress(){
                this.isDisabledBtn = false;
                if(this.address == "")
                {
                    this.$toaster.error("Address field is required");
                }
                else
                {
                   Swal({
                    title: 'Are you sure ?',
                    text: "You want to Add Address!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/add-system-btc-address',{
                            address: this.address,
                            remark: this.remark,
                                                     
                        }).then(resp => {
                            if(resp.data.code === 200) {
                                this.$toaster.success(resp.data.message);
                            } else {
                                this.$toaster.error(resp.data.message);
                            }
                            //$('#addTopUp').trigger("reset");
                            this.remark = '';
                            this.address = '';
                            
                        }).catch(err => {
                            this.$toaster.error(err);
                        });
                    }
                });   
                }
                   
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