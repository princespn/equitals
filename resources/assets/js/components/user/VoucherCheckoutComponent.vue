<template>
     <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="header-navbar-shadow"></div>
      <div class="content-wrapper">
        <div class="content-header row">
          <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
              <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Voucher Payment</h2>
           
              </div>
            </div>
          </div>
          
        </div>
        <div class="content-body">
          <section id="multiple-column-form">
           <div class="container-fluid">
                <div class="row">
            <!-- Column -->
         <div class="col-md-8">
            <div class="card br-10">
                <div class="card-body">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Your cart</span>
                    <span class="badge badge-secondary badge-pill">{{total_records}}</span>
                    </h4>
         
                    <div class="order-review-table table-responsive">
                    <table class="table table-bordered text-center">
                    <thead>
                    <tr class="row-1">
                        <th class="row-title text-left">Product Name</th>
                        <th class="row-title">Price</th>
                        <th class="row-title">Quantity</th>
                        <th class="row-title">Subtotal</th>
                        <th class="row-title">Total USD</th>
                        <!-- <th class="row-title">Total Coin</th> -->
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="row-2"  v-for="cart in cartdata">
                        <td class="product-name">{{cart.name}}</td>
                        <td class="product-price"><i class=""></i> {{cart.currency_code}} ( {{cart.price}} )</td>
                        <td class="product-quantity">{{cart.quantity}}</td>
                        <td class="product-subtotal"><i class=""></i> {{cart.currency_code}} ( {{cart.total_price}} )</td>
                        
                        <td class="product-subtotal"><i class=""></i> $ {{cart.total_price /cart.usd_to_other_curr}} </td> 
                       <!--  <td class="product-subtotal"><i class=""></i>   {{(cart.total_price /cart.usd_to_other_curr)/cart.coin_rate}} </td> -->
                    </tr>

                    </tbody>
                    <tfoot>
                   <!--  <tr class="row-4">
                        <td class="text-left" colspan="3">Cart Subtotal</td>
                        <td class="pr_subtotal"><i class="fa fa-inr"></i> {{final_total}}</td>
                    </tr> -->
                   <!--  <tr class="row-5">
                        <td class="text-left" colspan="3">Discount amount</td>
                        <td class="pr_subtotal">-<i class="fa fa-inr"></i> {{total_discount}}</td>
                    </tr> -->
                    <!-- <tr class="row-5">
                        <td class="text-left" colspan="3">Coupon Discount</td>
                        <td class="pr_subtotal"><i class="fa fa-inr"></i> {{total_coupon_amount}}</td>
                    </tr> -->
                    <tr class="row-6">
                        <td class="text-left text-white" colspan="4">Order Total</td>
                        <td class="product-subtotal"><i class=""></i>${{final_total_with_coupon/cartdata[0].usd_to_other_curr}}</td>
                        <!-- <td class="product-subtotal text-white"><i class=""></i> {{(final_total_with_coupon/cartdata[0].usd_to_other_curr)/cartdata[0].coin_rate}}</td> -->
                    </tr>
                    </tfoot>
                    </table>
                    </div>

                </div>
            </div>

        </div>
            <div class="col-lg-4">
                <div class="card br10">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <p class="b-yZcAZ4">Login or Create an account to pay with your account balance, receive rewards, and more!</p>
                                <h4>Choose Payment method</h4>

                              <!--   <input type="email" name="email" autocomplete="email" required="" value="" class="form-control"> -->  
                             
                              <!--  <input checked type="radio" value="purchase_wallet" name="payment_mode"> Purchase Wallet -->
                               <br>

                               <input checked type="radio" value="dexd_wallet" name="payment_mode"> DEXD Wallet
                              <br>
                              

                               <!--  <div class="po">
                                      
                               
                                 <div>
                                   <p class="mbb0">  <input type="radio" value="coin_wallet" name="payment_mode">  <b> Coin </b></p> <img src="public/user_files/images/1000.png" width="40px">

                                 <br>
                                 
                                 </div>
                                </div> -->


                          <input v-if="token !=null" value="Place Order" type="button"  v-on:click="proccedToPay" class="btn btn-primary btn-lg btn-block mmt20"> 


                        <input class="btn btn-primary btn-lg btn-block mmt20"  v-if="token ==null" value="Place Oreder" @click="goLogin" type="submit">
                                 
                                 <div class="cart-inner-box box-1 text-center">
                  <!-- <div class="ci-title">
                      <h6>Promotional code</h6>
                    </div> -->
                  <!--   <div class="ci-caption">
                      <p>Enter Your Coupon Code If you have one</p>
                        <form> 
                                    
                          <input v-model="coupon_code"  type="text" placeholder="Coupon Code" v-validate="'required'" @keyup="checkCouponValidFun" class="form-control { error: errors.has('coupon_code') }" >
                            <div class="tooltip2" v-show="errors.has('coupon_code')">
                                        Coupon Code Required
                            </div>

                            <p v-if="couponIsValid==false" style="color: red"> Not Valid Coupon  </p>
                            <p v-if="couponIsValid==true && isCompleteCouponCode" style="color: green"> Valid Coupon  </p>

                            <p v-if="couponAdded==true && isCompleteCouponCode" style="color: green"> Coupon Code Applied  </p>
                            <p v-else style="color: red">  Coupon Code Not Applied </p>
                   
                            <div>
                             <input type="checkbox" :disabled='!isCompleteCouponCode&&couponIsValid==false' id="applycheckbox" @click="applycheckboxFun()" name="applycheckbox" value="">
                            Apply Coupon Code
                          </div>

                       
                        </form>
                    </div> -->
                </div>
            
                <div class="cart-inner-box box-2 text-center">
                    <div class="ci-title">
                       <!--  <h6>Cart Total</h6> -->
                    </div>
                    <div class="ci-caption">

                      <!--   <table class="table table-bordered text-center">
                        <tbody>
                        <tr class="row-4">
                        <td class="text-left" colspan="3">Subtotal</td>
                        <td class="pr_subtotal"><i class="fa fa-inr"></i> {{ final_total.toFixed(2) }}</td>
                        </tr>
                        <tr class="row-4">
                        <td class="text-left" colspan="3">Cart Discount</td>
                        <td class="pr_subtotal"><i class="fa fa-inr"></i>{{ total_discount }}</td>
                        </tr>
                        <tr class="row-4">
                        <td class="text-left" colspan="3">Total Order</td>
                        <td class="pr_subtotal"><i class="fa fa-inr"></i> {{ final_total_with_coupon.toFixed(2) }}</td>
                        </tr>
                        </tbody>
                        </table> -->
                        <!-- <ul>
                            <li>Subtotal <span><i class="fa fa-inr"></i> {{ final_total.toFixed(2) }}</span></li>
                            <li>Shipping <span>Free</span></li>
                            <li>Discount <span>- <i class="fa fa-inr"></i> {{ total_discount }}</span></li>
                            <li>Coupon Discount <span>- <i class="fa fa-inr"></i> {{ total_coupon_amount }}</span></li>
                        </ul> -->
                    </div>
                   <!--  <div class="ci-btn">
                        <ul>
                            <li>Total Order<span><i class="fa fa-inr"></i> {{ final_total_with_coupon.toFixed(2) }}</span></li>
                        </ul>
                    </div> -->
                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
           </div>
        </section>
        </div>
      </div>
    </div>
</template>




    
<script>
    import Swal from 'sweetalert2';
    export default {
        data() {
            return {
                sameAddress:false,
                quantity: null,
                product_id: null,
                cartdata: {},
                total: null,
                final_total: 0,
                profiledata: {},
                order_note: null,
                payment_mode: 'purchase_wallet',
                hash: null,
                endurl: null,
                parameters: {
                    key: null,
                    hash: null,
                    txnid: null,
                    amount: null,
                    firstname: null,
                    email: null,
                    phone: null,
                    productinfo: null,
                    surl: null,
                    furl: null,
                    service_provider: null,
                },
                procedToPay: true,
                total_discount: 0,
                fullname: '',
                email: '',
                mobile: '',
                address: '',
                pincode: '',
                city: '',
                state:'',
                country: '',
                order_note: '',
                offerImage: 'public/ecommerce_assets/img/offer.png',
                countryArr: '',
                isocode : '',
                companyName:'',
                user_address:'',
                token:'',
                coupon_code:'',
                couponAdded:false,
                coupon_total:0,
                billingData:[],
                shoppingData:[],
                billingData:{
                    fname: '',
                    lname: '',
                    email: '',
                    phone: '',
                    address: '',
                    pincode: '',
                    city: '',
                    state:'',
                    country: '',
                    company_name: '',
                },
                shippingData:{
                    fname: '',
                    lname: '',
                    email: '',
                    phone: '',
                    address: '',
                    pincode: '',
                    city: '',
                    state:'',
                    country: '',
                    company_name: '',
                    special_request:'',
                },
                total_coupon_amount:0,
                final_total_with_coupon:0,
                couponIsValid:true,
                total_records:''
            }
        },
        
        computed: {
           isCompleteCouponCode(){
                //return this.coupon_code ;
            },
            isCompleteInfo() {
                //return this.fullname && this.email && this.mobile && this.user_address && this.city && this.country
            },
            isCompleteBillingData(){
                //return this.billingData.fname  && this.billingData.email && this.billingData.phone && this.billingData.city && this.billingData.state && this.billingData.address && this.billingData.zipcode && this.billingData.country;
            },
            isCompleteShippingData(){
                //return this.shippingData.fname  && this.shippingData.email && this.shippingData.phone && this.shippingData.city && this.shippingData.state && this.shippingData.address && this.shippingData.zipcode && this.shippingData.country;
            },
        },
        mounted() {
                this.token = localStorage.getItem('user-token');
                if(this.token !=null){
                   
                    this.cartDataFun();
                    //this.getProfileData();   
                    //this.billingAddressDetails();
                    //this.shippingAddressDetails();
                }else{
                     this.$toaster.error('please login');
                       this.$router.push({
                name: 'login'
            });
                     return false;

                }

             $("#miniCart").removeClass('open');
         
            //this.getCountry();
            this.scrollToTop();
            this.checkCountryAvoid();
        },
        methods: {
            checkCountryAvoid(){ 
                axios.get('checkUserCountryAvoided', {}).then(response => {
                  if (response.data.code == 200 && response.data.message == '123' ) {
                    this.$router.push({name: 'dashboard'});
                  }
                }).catch(error => {}); 
            },
            checkSelected(){
                // alert("ok");
                axios.post('set-same-address-billing', {                    
                    isSameAddress: this.sameAddress,                               
                })
                .then(response => {
                    if(response.data.code == 200){
                        this.$toaster.success(response.data.message);
                        this.shippingAddressDetails();
                    } else {
                       // this.$toaster.error(response.data.message);
                    }
                })
            },

        applycheckboxFun(){
           if($("#applycheckbox").is(':checked')){

                this.checkCouponValidFun();
              }else{
                 this.checkCouponValidFun();
             }       


            },
 
            updateBillingDataDetails() {
                axios.post('update-billing-address-data', {                    
                    fname: this.billingData.fname,                 
                    lname: this.billingData.lname,
                    phone: this.billingData.phone,
                    email: this.billingData.email,
                    country:this.billingData.country,
                    address:this.billingData.address,
                    city:this.billingData.city,
                    state:this.billingData.state,
                    zipcode:this.billingData.zipcode,
                    company_name:this.billingData.company_name, 
                    isSameAddress:this.sameAddress,                   
                })
                .then(response => {
                    if(response.data.code == 200){
                        this.$toaster.success(response.data.message);
                        
                    } else {
                       this.$toaster.error(response.data.message);
                    }
                })
            },

            updateShippingDataDetails() {
                axios.post('update-shipping-address-data', {                    
                    fname: this.shippingData.fname,                 
                    lname: this.shippingData.lname,
                    phone: this.shippingData.phone,
                    email: this.shippingData.email,
                    country:this.shippingData.country,
                    address:this.shippingData.address,
                    city:this.shippingData.city,
                    state:this.shippingData.state,
                    zipcode:this.shippingData.zipcode,
                    company_name:this.shippingData.company_name,
                    special_request:this.shippingData.special_request,                   
                })
                .then(response => {
                    if(response.data.code == 200){
                        this.$toaster.success(response.data.message);
                        
                    } else {
                       this.$toaster.error(response.data.message);
                    }
                })
            },
            billingAddressDetails(){
                axios.get('billing-info-data', {
                })
                .then(response => {
                    this.billingData = response.data.data;
                })
                .catch(error => {
                });           
            },
            shippingAddressDetails(){
                axios.get('shipping-address-data', {
                })
                .then(response => {
                    this.shippingData = response.data.data;
                })
                .catch(error => {
                });           
            },
            cartDataFun() {
               
                axios.get('get-cart-details', {
                }).then(response => {
                    if (response.data.code == 200) {
                        this.total_records = response.data.data.total_records;
                        this.cartdata = response.data.data.records;
                        this.final_total = response.data.data.total_price;
                        this.total_discount = response.data.data.total_discount;
                        this.total_coupon_amount = response.data.data.coupon_amount;
                        this.final_total_with_coupon = (this.final_total -(this.total_discount + this.total_coupon_amount));
                        if(response.data.data.is_same_address == '0'){
                            this.sameAddress = false;
                        }else{
                            this.sameAddress = true;
                        }
                    } else {
                        this.$toaster.error("You have no product in your cart.");
                        this.$router.push({name: 'product'});
                    }
                })
            },
            getProfileData() {
                axios.get('profile', {
                })
                .then(response => {
                    if (response.data.code == 200) {
                        this.profiledata = response.data.data;
                        this.fullname =this.profiledata.fullname ;
                        this.email =this.profiledata.email ;
                        this.mobile =this.profiledata.mobile ;
                        this.address =this.profiledata.user_address ;
                        this.pincode =this.profiledata.pincode ;
                        this.state=this.profiledata.state;
                        this.city =this.profiledata.city ;
                        this.country =this.profiledata.country ;
                        this.isocode =this.profiledata.country ;
                        this.user_address =this.profiledata.user_address;
                        //console.log(this.country);
                    }
                })
                .catch(error => {
                });
            },
            getCountry() {
                axios.get('../getCountry', {
                })
                        .then(response => {
                            this.countryArr = response.data.data;
                        })
                        .catch(error => {
                        });
            },
            proccedToPay(){
             // alert(token)

             let temp= $('input[name="payment_mode"]:checked').val();
                this.payment_mode=temp;

               //return false;
                  if(this.token ==null){

                    this.$toaster.error('please login');
                     return false;
                  }
                Swal({
                    title: 'Are you sure ?',
                    text: "You want to proceed!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.value) {
                        this.procedToPay = false;
                    axios.post('get-cart-with-single-product').then(response => {
                        if(response.data.code  == 200){
                            this.finalProcide();
                        } else {
                            this.$toaster.error(response.data.message);
                        }
                    })
                    .catch(error => {
                    });


                   /*if($("#applycheckbox").is(':checked')){


                        axios.post('add_coupon', {
                            coupon_code: this.coupon_code,
                        }).then(response => {
                            if (response.data.code == 200) {
                               this.finalProcide();
                               this.updateCouponStatusInCart();
                               this.updateCouponStatus();
                            }else{
                               // this.addCoupon = false;
                               this.$toaster.error(response.data.message);

                            }
                        })
                        .catch(error => {
                        });


                   }else{

                       this.finalProcide();

                   }*/

                   

                     
                    }
                });
                //console.log(this.payment_mode);
            },


            finalProcide(){

                     this.procedToPay = false;
                 axios.post('checkout', {
                            payment_mode: this.payment_mode,
                            fullname:this.shippingData.fname+" "+this.shippingData.lname,
                            email:this.shippingData.email,
                            mobile: this.shippingData.phone,
                            address: this.shippingData.address,
                            state: this.shippingData.state,
                            pincode: this.shippingData.zipcode,
                            city: this.shippingData.city,
                            country:this.shippingData.country,
                            order_note: this.order_note,
                            coupon_code: this.coupon_code,
                            coupon_added: this.couponAdded,

                        }).then(response => {
                                                    this.procedToPay = true;

                            if (response.data.code == 200) {
                                
                                    this.$toaster.success(response.data.message);
                                    this.final_total = response.data.data.total_price;
                                   this.$router.push({name: 'order-history'});
                                   /* setTimeout(function () {
                                       location.reload()
                                    }, 500);*/
                                
                            }else{

                           this.$toaster.error(response.data.message);

                            }
                        })
                        .catch(error => {
                        });

            },
              addCoupon(){
                
                if(this.token ==null){

                    this.$toaster.error('please login');
                     return false;
                }
                if(this.final_total < 500){
                    var message = 
                    this.$toaster.error("Total amount shoudl be greater than 500");
                }
                Swal({
                    title: 'Are you sure ?',
                    text: "You want to proceed!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.value) {
                        // this.addCoupon = false;
                        axios.post('add_coupon', {
                            coupon_code: this.coupon_code,
                        }).then(response => {
                            if (response.data.code == 200) {
                               this.updateCouponStatusInCart();
                               this.updateCouponStatus();
                            }else{
                               // this.addCoupon = false;
                               this.$toaster.error(response.data.message);

                            }
                        })
                        .catch(error => {
                        });
                    }
                });
                //console.log(this.payment_mode);
            
            },
             checkCouponValidFun(){
              if(this.coupon_code==''){
                this.couponIsValid=true;
                return false;
              }
                axios.post('checkCouponValid', {
                            coupon_code: this.coupon_code,
                            //status: "Inactive",
                        }).then(response => {

                           if (response.data.code == 200) {
                             this.couponIsValid=true;
                            
                                 if($("#applycheckbox").is(':checked')){

                                       this.couponAdded = true;
                                     this.$toaster.success('Successfully Applied');
                                         this.total_coupon_amount=response.data.data.amount;

                                        // alert(this.total_discount + this.total_coupon_amount)
                                           this.final_total_with_coupon = (this.final_total -(this.total_discount + this.total_coupon_amount));
                                        // alert(this.final_total_with_coupon)
                                    }else{
                                   this.couponAdded = false;
                                                                     
                                   this.total_coupon_amount=0;
                                    this.final_total_with_coupon = (this.final_total -(this.total_discount + this.total_coupon_amount));
                                      // this.$toaster.success('Successfully Removed');
                                   }   

                          
                            
                              
                          }else{
                            this.couponIsValid=false;
                                    if($("#applycheckbox").is(':checked')){


                                    }else{

                                                                     
                                   this.total_coupon_amount=0;
                                    this.final_total_with_coupon = (this.final_total -(this.total_discount + this.total_coupon_amount));
                                      // this.$toaster.success('Successfully Removed');
                                   }   

                             
                          }
                            
                        })
                        .catch(error => {
                        });
              
            },    updateCouponStatus(){
                axios.post('update_coupon_status', {
                            coupon_code: this.coupon_code,
                            status: "Inactive",
                        }).then(response => {

                         
                            
                        })
                        .catch(error => {
                        });
                          
            },
            updateCouponStatusInCart(){
                axios.post('update_coupon_status_in_cart', {
                            coupon_code: this.coupon_code,
                            coupon_amount: this.coupon_total,
                        }).then(response => {
                        })
                        .catch(error => {
                        });
            },
 
            goLogin() {
                  this.$router.push({name: 'login'});
            },
            scrollToTop() {
                window.scrollTo(0, 0);
            },

            
        }
    }
</script>
