<template>
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Voucher Checkout</h2>
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
            <span class="text-white">Your cart</span>
            <span class="badge badge-secondary badge-pill">{{total_records}}</span>
          </h4>
                                        <table class="table table-bordered text-center">
                                            <thead>
                                                <tr class="row-1">
                                                    <th class="row-title">
                                                        <p>Item</p>
                                                    </th>
                                                    <th class="row-title">
                                                        <p>Image</p>
                                                    </th>
                                                    <th class="row-title">
                                                        <p>Product Name</p>
                                                    </th>
                                                    <th class="row-title">
                                                        <p>Price</p>
                                                    </th>
                                                    <th class="row-title">
                                                        <p>Quantity</p>
                                                    </th>
                                                    <th class="row-title">
                                                        <p>Subtotal</p>
                                                    </th>
                                                    <th class="row-title">
                                                        <p>Action</p>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="row-2" v-for=" (cart,index)  in this.cartdata">
                                                    <td class="row-close close-1" data-title="product-remove"><i class="ion-close-circled" v-on:click="removeFromCart(cart.variation_id,cart.product_id,cart.cart_id)">{{index+1}}</i>
                                                    </td>
                                                    <td>
                                                        <img style="width: 24% !important;" :src="cart.images[0]" alt="Uren's Product Image">
                                                    </td>
                                                    <td class="product-name" data-title="Product">
                                                        <a class="text-white">{{cart.name}}</a>
                                                    </td>
                                                    <td class="product-price" data-title="Price">
                                                        <p><i class=""></i> {{cart.currency_code}} ( {{cart.cost}} )</p>
                                                    </td>
                                                    <td class="product-quantity" data-title="Quantity">
                                                        <div class="quantity_filter btn">
                                                            <input type="button" @click="removePro(cart,index)" value="-" class="minus btn btn-primary">
                                                            <input readonly class="quantity-number qty btn btn-primary" type="text" v-model="cart.quantity" min="1" max="10">
                                                            <input type="button" @click="addPro(cart,index)" value="+" class="plus btn btn-primary">
                                                        </div>
                                                    </td>
                                                    <td class="product-total" data-title="Subprice">
                                                        <p><i class=""></i> {{cart.currency_code}} ({{cart.total_price}})</p>
                                                    </td>
                                                    <td class="row-close close-2" data-title="product-remove">
                                                        <button v-on:click="removeFromCart(cart.variation_id,cart.product_id,cart.cart_id)" class="bn" title="Click to remove"><i class="fa fa-trash cpp"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="12">
                                                        <ul class="table-btn">
                                                            <li>
                                                                <!--  <a href="shop.html" class="btn btn-secondary"><i class="fa fa-chevron-left"></i>Continue Shopping</a> --> <a v-on:click="continueShopping" class="btn btn-warning text-white"><i class="fa fa-angle-left"></i> Continue Shopping</a>
                                                            </li>
                                                            <!--   <li><a href="#" class="btn btn-primary"><i class="fa fa-refresh"></i>Update cart</a></li> -->
                                                        </ul>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card br10">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <p>Email address for order status updates</p>
                                                <input type="email" v-model="userProfile.email" name="email" autocomplete="email" required="" value="" class="form-control">

                                               

                                               <div class="pdd">
                                                    <div class="checkbox">
                                                      <label  for="same-address">
                                                        <input type="checkbox" value="" id="con1">Add me to the newsletter to receive news about new products and features</label>
                                                    </div>
                                                    <div class="checkbox">
                                                      <label  for="same-address"><input type="checkbox" value="" id="con2">I have read and agree with the Terms &amp; Conditions and the Privacy Policy</label>
                                                    </div>
                                               </div>

                                               <!--  <div class="custom-control custom-checkbox">

                                                    <input type="checkbox" class="custom-control-input" id="con1">
                                                    <label class="custom-control-label" for="same-address">Add me to the newsletter to receive news about new products and features</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="con2">
                                                    <label class="custom-control-label" for="same-address">I have read and agree with the Terms &amp; Conditions and the Privacy Policy</label>
                                                </div> -->
                                                <div class="cart-inner-box box-2 text-center">
                                                    <div class="ci-title">
                                                        <!-- <h6>Cart Total</h6> -->
                                                    </div>
                                                    <div class="ci-caption">
                                                        <!--  <ul>
                            <li><label>Subtotal</label> <span><i class="fa fa-inr"></i> {{final_total.toFixed(2)}}</span></li>
                            <li v-if="couponAdded">Coupon Amount <span><i class="fa fa-inr"></i> {{coupon_total.toFixed(2)}}</span> </li>
                            <li>Total <span><i class="fa fa-inr"></i> {{final_total_with_coupon.toFixed(2)}}</span></li>

                        </ul> -->
                                                        <table class="table table-bordered text-center">
                                                            <tbody>
                                                                <!--  <tr class="row-4">
                        <td class="text-left" colspan="3">Subtotal</td>
                        <td class="pr_subtotal"><i class=""></i> {{ final_total.toFixed(2) }}</td>
                        </tr> -->
                                                                <!--  <tr class="row-4">
                        <td class="text-left" colspan="3">Cart Discount</td>
                        <td class="pr_subtotal"><i class=""></i>{{ coupon_total.toFixed(2) }}</td>
                        </tr> -->
                                                                <!-- <tr class="row-4">
                        <td class="text-left" colspan="3">Total Order</td>
                        <td class="pr_subtotal"><i class=""></i> {{ final_total_with_coupon.toFixed(2) }}</td>
                        </tr> -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="ci-btn">
                                                        <div v-if="cartdata.length ==0">
                                                            <p>No Product in Cart</p> <a v-on:click="continueShopping" class="btn btn-warning text-white"><i class="fa fa-angle-left"></i> Continue Shopping</a>
                                                        </div>
                                                        <div v-if="cartdata.length >0"> <a v-on:click="checkout" class="btn btn-primary btn-lg btn-block"> Proceed to Checkout</a>
                                                        </div>
                                                    </div>
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
                    quantity : null,
                    product_id : null,
                    cartdata : [],
                    total : null,
                    final_total : 0,
                    request_id : null,
                    guest_id : null,
                    token  : null,
                    authstatus : false,
                    coupon_code:'',
                    couponAdded:false,
                    coupon_total:0,
                    final_total_with_coupon:0,
                    userProfile:'',
                    total_records:0,
    
                }
            },
            computed:{
                isCompleteCouponCode(){
                    return this.coupon_code ;
                },
            },
            mounted() {
                this.checkCountryAvoid();
                /*this.initalizeData();
                this.getProductDetail();*/
                this.token = localStorage.getItem('user-token');
               if(this.token != null){
                //alert();
    
                this.cartDataFun();
    
                }
                this.scrollToTop();
                $("#miniCart").removeClass('open');
                this.getUserProfile();
            },
            methods: {
              checkCountryAvoid(){ 
                axios.get('checkUserCountryAvoided', {}).then(response => {
                  if (response.data.code == 200 && response.data.message == '123' ) {
                    this.$router.push({name: 'dashboard'});
                  }
                }).catch(error => {}); 
              },                
                getUserProfile() {
                  axios
                    .get("get-profile-info", {})
                    .then(response => {
                      this.userProfile = response.data.data;
                    })
                    .catch(error => {});
                },

                cartDataFun(){
                    if(this.token != null){
                        axios.get('get-cart-details', {
                        }).then(response => {
                            if(response.data.code  == 200){
                                this.total_records = response.data.data.total_records;
                                this.cartdata = response.data.data.records;
                                this.final_total = response.data.data.total_price;
                                this.final_total_with_coupon = response.data.data.total_price;
                                this.coupon_code_added  = response.data.data.coupon_code;
                                if(this.coupon_code_added){
                                    this.couponAdded = true;
                                    this.coupon_code = response.data.data.coupon_code;
                                    this.coupon_total = response.data.data.coupon_amount;
                                }
                         //  alert(resp.data.data.total_records)
                          // $('.cartCount').html(response.data.data.total_records);
                          // $('.cartPrice').html(response.data.data.total_price);
                        //  this.cartPrice=resp.data.data.total_price;
                         // this.catListArr=resp.data.data.records;
                            } else {
                                this.cartdata = [];
                            }
                        })
                    } else {
                    /*  axios.post('guest-ecommerce-cart-details', {
                            guest_id : this.guest_id,
                            request_id : this.request_id
                        }).then(response => {
                            if(response.data.code  == 200){
                                this.cartdata = response.data.data.records;
                                this.final_total = response.data.data.total_price;
                            } else {
                                this.cartdata = [];
                            }
                        })*/
                    }
                },
    
                addPro(card,index){
                    // alert('hello');
                    let total =this.cartdata[index].quantity+1;
                    this.cartdata[index].quantity=total;
                    this.addQtyToCart(this.cartdata[index]);
    
                    // console.log(total);
    
                },  
                removePro(card,index){
                   
                    let total =this.cartdata[index].quantity-1;
    
                    if(total !=0){
                   this.cartdata[index].quantity=total;
                    //console.log(total);
                   this.addQtyToCart(this.cartdata[index]); 
                    }
                    
                },
                addQtyToCart(cart){
                    let params = {};
                    let url = "";
                    this.params = {
                        'product_id' : cart.product_id,
                        'quantity'   : cart.quantity,
                        'variation_id'   : cart.variation_id,
                        'cart'       : 1,
                    };
                    this.url = 'add-to-cart';
                    cart.total_price = (cart.quantity * cart.cost);
                    axios.post(this.url, this.params).then(response => {
                        if(response.data.code  == 200){
                             setTimeout(function(){ location.reload(); }, 300);
                            this.$toaster.success(response.data.message);
                            this.final_total = response.data.data.total_price;
                        } else {
                            this.$toaster.error(response.data.message);
                        }
                    })
                    .catch(error => {
                    });
                },
                removeFromCart(variation_id,product_id,cartId){
                    Swal({
                        title: 'Are you sure ?',
                        text: "You want to delete this from cart!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, remove it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.value) {
                            let url = "";
                            let params = {};
                            this.url = "remove-from-cart";
                            this.params = {
                                cart_id:cartId,
                                variation_id:variation_id,
                                product_id:product_id,
                            }
                            axios.post(this.url,this.params).then(resp => {
                                if(resp.data.code == 200) {
                                    this.$toaster.success(resp.data.message);
                                    //this.cartDataFun();
                                    setTimeout(function(){ location.reload(); }, 300);
    
                                } else {
                                    this.$toaster.error(resp.data.message);
                                }
                            }).catch(err => {
                                this.$toaster.error(err);
                            });
                        }
                    });  
                },
                continueShopping(){
                    this.$router.push({ name:'voucher'});
                },
                checkout(){
                    axios.post('get-cart-with-single-product').then(response => {
                        if(response.data.code  == 200){
                            this.$router.push({ name:'checkout'});
                        } else {
                            this.$toaster.error(response.data.message);
                        }
                    })
                    .catch(error => {
                    });
                },
                scrollToTop() {
                    window.scrollTo(0,0);
                },
                change_qty(action){
                    var oldValue = this.cart.quantity;
    
                    if (action == 'inc') {
                        var newVal = parseFloat(oldValue) + 1;
                    } else {
                        // Don't allow decrementing below zero
                        if (oldValue > 1) {
                            var newVal = parseFloat(oldValue) - 1;
                        } else {
                            newVal = 1;
                        }
                    }
                    this.cart.quantity=newVal;
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
                                    // response.data.message
                                    if(response.data.data.c_status == 1){
                                        this.$toaster.success("Coupon Already Added!");
                                    }else{
                                        this.$toaster.success("Coupon Added Successfully!");
                                    }
                                   
                                   
                                   this.couponAdded = true;
                                   this.final_total_with_coupon = (this.final_total - response.data.data.amount);
                                   this.coupon_total = response.data.data.amount;
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
    
                // updateCouponStatus(){
                //     axios.post('update_coupon_status', {
                //                 coupon_code: this.coupon_code,
                //                 status: "Inactive",
                //             }).then(response => {
                                
                //             })
                //             .catch(error => {
                //             });
                // },
                // updateCouponStatusInCart(){
                //     axios.post('update_coupon_status_in_cart', {
                //                 coupon_code: this.coupon_code,
                //                 coupon_amount: this.coupon_total,
                //             }).then(response => {
                //             })
                //             .catch(error => {
                //             });
                // }
            }
        }
</script>