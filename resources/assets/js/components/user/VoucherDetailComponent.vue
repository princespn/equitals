<template>
	 <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="header-navbar-shadow"></div>
      <div class="content-wrapper">
        <div class="content-header row">
          <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
              <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Vouchers Details</h2>
             <!--    <div class="breadcrumb-wrapper col-12">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Change Password
                    </li>
                  </ol>
                </div> -->
              </div>
            </div>
          </div>
          <!-- <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
            <div class="form-group breadcrum-right">
              <div class="dropdown">
                <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-settings"></i></button>
                <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="#">Chat</a><a class="dropdown-item" href="#">Email</a><a class="dropdown-item" href="#">Calendar</a></div>
              </div>
            </div>
          </div> -->
        </div>
        <div class="content-body">
          <section id="multiple-column-form">
          <div class="container-fluid" style="padding: 11px;">
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="image_selected">
                                       <!--  <img src="public/user_files/images/products/flipkart.webp" class="img-fluid"> -->
                                      <img :src="mainImage" class="img-fluid">

                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="product_description">
                                        <nav>
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item"><a href="#/dashboard">Home</a>
                                                </li>
                                                <li class="breadcrumb-item"><a href="#/voucher">Products</a>
                                                </li>
                                                <li class="breadcrumb-item active"></li>
                                            </ol>
                                        </nav>
                                        <div class="product_name">{{ productdata.name }}  <span class="badge badge-warning"><i class="fa fa-star"></i> 1% Rewards</span> 
                                        </div>
                                        <p v-html="productdata.description" class="text-white1"></p>
                                        <div class="text-white"><span class="b-9mViGV">  </span> This gift code may only work in {{ productdata.country }} </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <h3>Select amount</h3>
                                            </div>
                                            <div class="col-lg-6"> <span>Currency </span>
                                                <select name="cars" id="cars" class="form-control mb-1">
                                                    <option value="volvo">Coin</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="b-1JkzWf">
                                                    <label class="b-1kAMHV" v-for=" price in productdata.variation_data">
                                                        <input class="b-3XVTWj" type="radio" name="value" :value="price.id" @click="priceUp(price.variation_price,productdata.currency_code)">
                                                        <div class="b--tv_7x"></div><span class="b-22CxNm"><s v-if="price.variation_mrp_cost > price.variation_price">
                                                           {{price.currency_code}}
                                                            {{price.variation_mrp_cost}} 
                                                           

                                                        </s> &nbsp;{{productdata.currency_code}} {{price.variation_price}}  <br><div>$ : {{(price.variation_price / productdata.usd_to_other_curr)|curr}} </div></span>
                                               


                                                    </label>
                                                </div>
                                            </div>{{productdata.is_mob_filed}}
                                            <div v-if="productdata.is_mob_filed=='1'" class="col-lg-6 shp">
                                             <span> Enter Mobile Number
                                                </span>
                                                <input  type="text" name="" class="form-control" v-model="mob_filed"placeholder="">
                                            </div>  

                                            <div class="col-lg-6 shp">
                                             <span>Amount to buy
                                                </span>
                                                <input readonly type="text" name="" class="form-control" v-model="selectProPrice"placeholder="">
                                            </div>
                                            <div class="col-lg-12">
                                             <span class="css-19qa4ox-0 eqn5xv88"><!-- <strong>Min:</strong> 25 INR</span>
                                                <span class="css-19qa4ox-0 eqn5xv88"><strong>Max:</strong> 10000 INR</span>
                                                <span class="css-19qa4ox-0 eqn5xv88"><strong>Step:</strong> 0.01 </span>
                                                <span class="css-19qa4ox-0 eqn5xv88"><strong>Price:</strong> 0.00298100  --></span> 
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="button">
                                                    <input type="button" @click="removePro(quantity)"  value="-" class="minus btn btn-primary">
                                        <input class="btn btn-primary quantity-number qty" type="text" v-model="quantity" min="1" max="10">

                                          <input type="button" @click="addPro(quantity)" value="+" class="plus btn btn-primary">

                                         <button type="button" class="btn btn-primary" @click="addToCart(productdata.id)" > Add Cart</button>

                                                </div>
                                            </div>
                                        </div>
                                        <hr class="singleline">
                                        <div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="br-dashed">
                                                        <div class="row">
                                                            <div class="col-md-3 col-xs-3">
                                                                <img src="https://img.icons8.com/color/48/000000/price-tag.png" style="width: 26px;">
                                                            </div>
                                                            <div class="col-md-9 col-xs-9">
                                                                <div class="pr-info"> <span class="break-all">Instant delivery</span> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="br-dashed">
                                                        <div class="row">
                                                            <div class="col-md-3 col-xs-3">
                                                                <img src="https://img.icons8.com/color/48/000000/price-tag.png" style="width: 26px;">
                                                            </div>
                                                            <div class="col-md-9 col-xs-9">
                                                                <div class="pr-info"> <span class="break-all">Fast, Private, Safe</span> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="singleline">
                                    </div>
                                </div>
                            </div>
                            <div class="row row-underline">
                               
                                <div class="col-md-12 pnp">
                                    <div class="card">
                                       
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs customtab" role="tablist">
                                            <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home2" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Description</span></a> 
                                            </li>
                                           <!--  <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#profile2" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">How To Reedem</span></a> 
                                            </li>
                                            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#messages3" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Terms &amp; Conditions</span></a> 
                                            </li> -->
                                        </ul>
                                        <!-- Tab panes -->


                                        <div class="tab-content">
                                            <div class="tab-pane active" id="home2" role="tabpanel">
                                                <div class="p-20">
                                                    <p v-html="productdata.description" class="text-white11"></p>
                                                </div>
                                            </div>
                                           <!--  <div class="tab-pane  p-20" id="profile2" role="tabpanel">
                                                <h2>To Redeem:</h2>
                                                <ol> <b>To Redeem</b>:
                                                    <p>Go to flipkart.com and select the items you want to purchase. When you are ready to checkout, click "Proceed To Pay".</p>
                                                    <p>Select the "Pay By Gift Card" option.</p>
                                                    <p>Enter your 16-digit gift card number and the corresponding 6-digit PIN number when prompted.</p>
                                                </ol>
                                                <p>If the Gift Card value doesn't cover your Order total, you will be prompted to select an additional payment method.</p>
                                                <p>NOTE: Funds will be deducted from your Flipkart Gift Card at the time you place your order. In case of any adjustment or cancellation at a later stage, we will credit the refund back to your Gift Card.</p>
                                            </div>
                                            <div class="tab-pane p-20" id="messages3" role="tabpanel">
                                                <ol>
                                                    <p>EGVs cannot be redeemed for Cash or Credit, but are transferable.</p>
                                                    <p>Flipkart.com and Xoxoday are not responsible if the voucher is lost, stolen or used without permission.</p>
                                                    <p>You can combine a maximum of 15 Gift Cards in a single order at the time of checkout. In case you wish to redeem a number of Gift Cards on a single order, please add your Gift Cards to the Wallet. They can be combined with promotional codes.</p>
                                                    <p>Flipkart.com assumes no responsibility for the products purchased using the EGVs and any liability thereof is expressly disclaimed.</p>
                                                    <p>The validity of EGVs cannot be extended, new EGVs cannot be provided against the expired/unused Vouchers.</p>
                                                    <p>In the event, the beneficiary/Know Your Customer (KYC) details are found to be incorrect/insufficient, Flipkart.com retain the right to cancel the EGV issued.</p>
                                                    <p>There is no fee or other charges associated with EGV purchase.</p>
                                                    <p>EGVs cannot be reloaded or resold.</p>
                                                    <p>The validity of this E-Voucher is 3 months from the date of issue.</p>
                                                </ol>
                                            </div> -->
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
import Vue from "vue";

Vue.filter('curr', function (input) {
  // here `input` === `this.userInput`
  let temp=parseFloat(input)
  return  temp.toFixed(2);
})
    export default {
    data() {
        return {
            productdata : {},
            quantity : 1,
            token : null,
            product1:{},
            relatedProducts:{},
                 productImg1:'',
                productImg2:'',
                productImg3:'',
                mainImage:'',
                selectProPrice:'',
                quantity:1,
                mob_filed:'',
        }
    },
    mounted() {
        this.token = localStorage.getItem('user-token');
        this.getProductDetail();
        //this.getRelatedProducts();
        this.scrollToTop();
    },
    methods: {

        priceUp(rs,currency_code){

            this.selectProPrice=currency_code +" "+rs;
        },

        showImg(img){
         this.mainImage=img;
        },

               getRelatedProducts() {
                  let product_id = this.$route.params.id;
            axios.post('getRelatedProducts', {
            sort_by_order: "DESC",
            length:10,
            start:0,
            product_id:product_id,

            }).then(response => {
                if (response.data.code == 200)
                    this.relatedProducts = response.data.data.records;
            })
            .catch(error => {

            });
        },   
            addPro(index){
             
                let total =index+1;

                 if(total==0){

                  this.quantity=1;
                 }else{
                    this.quantity=total;
                 }
              // this.addQtyToCart(this.productdata);

               // console.log(total);

            },  
            removePro(index){
                 if(index==1){

                  this.quantity=1;
                 }else{
                     let total =index -1;
                    this.quantity=total;
                 }

                //console.log(total);

             //this.addQtyToCart(this.productdata);
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
        getProductDetail(){
            var product_id = this.$route.params.id;
            // var variation_id = this.$route.params.variation_id;
            
            axios.post('get-product-details', {
                'product_id' : product_id,
                // 'variation_id' : variation_id
            }).then(response => {
                if(response.data.code  == 200){
                    this.productdata = response.data.data;

                    this.mainImage=this.productdata.images[0];
                } else {
                    this.$toaster.error("Product not found");
                    this.$router.push({ 
                        name:'products-list',
                    });
                }
            })
            .catch(error => {
            });   
        },
        viewProduct(product) {
            //alert();
            this.productImg1='';

                this.productImg2='';

                this.productImg3=''; 
                             this.product1=product;

                if(this.product1.images.length >=1){
                   // alert(this.product1.images[0]);
                    this.productImg1=this.product1.images[0];
                  //  alert(this.productImg1);
                }
                if(this.product1.images.length >=2){
                    this.productImg2=this.product1.images[1];
                }if(this.product1.images.length >=3){
                    this.productImg3=this.product1.images[2];
                }
            //console.log(this.product1.images[1])
                $("#exampleModalNew").modal('show');
        },  
        addToCart(product_id){
            //alert();
            var variation_id = $("input:radio.b-3XVTWj:checked").val();
            if(variation_id == null){
                this.$toaster.error("Please select package !!");
            }else{
                let carturl = 'add-to-cart';
                let guest = (this.token != null && this.token != undefined) ? false : true;
                let params = {};
                if((this.token != null && this.token != undefined)){
                    this.params = {
                        'product_id' : product_id,
                        'quantity'   : this.quantity,
                        'variation_id' : variation_id,
                        'mobile' : this.mob_filed
                    };
                } else {
                     $("#exampleModalNew").modal('hide');
                    this.$router.push({ 
                        name:'login',
                    });
                }
                this.params = {
                        'product_id' : product_id,
                        'quantity'   : this.quantity,
                        'variation_id' : variation_id,
                         'mobile' : this.mob_filed
                    };
                axios.post(carturl, this.params).then(response => {
                    if(response.data.code  == 200){
                        this.$toaster.success(response.data.message);
                        this.productdata = response.data.data;
                          
                        this.$router.push({ 
                            name:'cart',
                        });
                        setTimeout(function(){ location.reload(); }, 300);

                    } else {
                        this.$toaster.error(response.data.message);
                    }
                })
                .catch(error => {
                });   
                
            }
        },
        addToWishlist(){
            let carturl = 'add-to-wishlist';
            let guest = (this.token != null && this.token != undefined) ? false : true;
            let params = {};
            if((this.token != null && this.token != undefined)){
                this.params = {
                    'product_id' : this.$route.params.id,
                };
            } else {
                //alert();
                // login route of user
            }
            axios.post(carturl, this.params).then(response => {
                if(response.data.code  == 200){
                    this.$toaster.success(response.data.message);
                    this.productdata = response.data.data;
                    this.$router.push({ 
                        name:'wishlist',
                    });
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
            var oldValue = this.quantity;

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
            this.quantity=newVal;
        },
           viewDetails(productId,variationId) {
           let that=this;

  setTimeout(function(){

 that.$router.push({name: 'loding-page'});

   }, 1000);

     setTimeout(function(){

 that.$router.push({
                name: 'products-detail',
                params: {
                    id: productId,
                    variation_id : variationId 
                }
            });

   }, 1500);
        },

    }
}
</script>