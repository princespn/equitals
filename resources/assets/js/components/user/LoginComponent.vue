<template>
     <div class="">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <div class="text-center mb-3">
                                        <a href="https://www.equitals.com/" target="_blank">
                                            <img src="public/user_files/assets/images/logo.svg" width="250"></a>
                                    </div>
                                    <h4 class="text-center mb-4">Sign in your account</h4>
                                    <!-- <p v-if="!lockout">
                                    <span class="text-danger">{{this.wrong_message}}</span>
                                    </p> -->
                                    <form action="" @submit.prevent="login">
                                        <div class="mb-3">
                                             <input type="text" class="form-control" placeholder="Enter User ID" name="user_id" id="user_id" required="" v-model="user.user_id" autofocus="">
                                        </div>
                                        <div class="mb-3">
                                            <input type="password" class="form-control"  placeholder="Enter password" name="password" id="password" v-model="user.password">
                                        </div>
                                        <div class="row d-flex justify-content-between">
                                            
                                            <div class="">
                                                <router-link to="/forgot-password" class=""> Forgot password?</router-link>
                                               <!--  <a href="forgot-password.php">Forgot Password?</a> -->
                                            </div>
                                        </div>
                                    <div class="row justify-content-center mt-2 mb-2">
                                       <!--  <div class="col-3">
                                            <img src="public/user_files/assets/images/robo.png" class="img-fluid">
                                        </div> -->
                                        <div class="col-9 reCAPTCHA">
                                            <div class="captcha">
                                                <div class="spinner">
                                                    <label>
                                                        <!-- <input type="checkbox" onclick="$(this).attr('disabled','disabled');"> -->
                                                         <input v-model="terms" type="checkbox" id="terms" name="terms" v-validate="'required'" data-vv-as="recaptcha"  tabindex="3" aria-required="true" aria-invalid="false">
                                                        <span class="checkmark"><span>&nbsp;</span></span>
                                                        <div class="tooltip2" v-show="errors.has('form-1.terms')">
                                                        <div class="tooltip-inner"> <span v-show="errors.has('form-1.terms')">{{ errors.first('form-1.terms') }}</span>
                                                        </div>
                                                    </div>
                                                    </label>
                                                </div>
                                                <div class="text">
                                                    I'm not a robot
                                                </div>
                                                <div class="logo">
                                                    <!-- <img src="images/captcha.png" class="img-fluid"> -->
                                            <img src="public/user_files/assets/images/robo.png" class="img-fluid">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="text-center mt-5">
                                            <button :disabled="!loginComplete || !terms" class="btn btn-primary btn-block logbtn" type="submit">Sign Me In</button>
                                        </div>
                                    </form>
                                    <div class="new-account mt-3 text-center">
                                       <!--  <p>Don't have an account? <a class="text-primary" href="register.php">Sign Up Now</a></p> -->
                                         <p>Don't have an account ? <router-link to="/register" class="text-primary"> Signup Now </router-link></p>
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
</template>
<script>
    import { domain } from'./../../user-config/config';
            export default {
            data() {
                return {
                     weburl:'',
                    user: {
                        user_id: '',
                        password: ''
                    },
                   // icon: '../public/user_files/assets/img/icon-mlm.png',
                  ///  logo: '../public/user_files/assets/img/logo.png',
                    hostName: window.location.origin,
                    verify2fa:'',
                    googleotp : null,
                    token : null,
                    verifymailotp : null,
                    otp : null,
                    rand_no:'',
                    captcha:'',
                    captchamatch:false,
                    terms:false,
                    captchamatcherror:'',
                    /*lockout: true*/
                }
            },
            computed:{
               loginComplete(){
                  return this.user.user_id && this.user.password;
               }
            },
            mounted() {
                    this.weburl = domain;
                    var user_id = this.$route.query.user_id;
                    var password = this.$route.query.password;
                    if (user_id!==undefined && password!==undefined && user_id!=='' && password!=='') {
                        this.user.user_id = user_id;
                        this.user.password = password;
                        this.login();
                    }
                    this.getRandomNo();
            },
            methods: {
                login() {
                    axios.post('login', {
                        user_id: this.user.user_id,
                        password: this.user.password,
                    }).then(resp => {
                         let userinfo = resp.data.data; 
                        if(resp.data.code === 200){
                            const token = resp.data.data.access_token;
                            //this.$toaster.success(resp.data.message) 
                             if(userinfo.google2faauth == "TRUE"){
                                //this.verify2Fa();
                                this.token = token;
                                this.verify2fa = true;
                            }else{
                            localStorage.setItem('user-token', token); // store the token in localstorage
                            localStorage.setItem('type', "user"); // store the token in localstorage
                           // this.getPackages();
                                 this.$toaster.success(resp.data.message) 
                                this.$router.push({name: 'dashboard'});
                                location.reload();
                            }
                            
                        }/*else if (resp.data.code === 400) {
                          this.wrong_message = resp.data.message;
                          console.log(resp.data.data.time); 
                          let $time=resp.data.data.time;
                          this.lockout = false;
                          setTimeout(()=>{
                          this.lockout = true;
                          console.log('Button Activated')}, $time)  
                        }*/ else {
                            this.$toaster.error(resp.data.message)
                        }
                    }).catch(err => {
                        //localStorage.removeItem('user-token'); // if the request fails, remove any 
                       // location.reload();
                        this.$router.push({ name: 'login' });
                    })
                },
                   getPackages(){
                    axios.get('get-packages', {
                    })
                    .then(response => {
                        var INR = response.data.data[0]['convert'];
                        var type = response.data.data[0]['type'];
                        localStorage.setItem('INR', INR); 
                        localStorage.setItem('p_type', type); 
                        return true;
                    })
                    .catch(error => {
                    });        
                 },
                reset() {
                    this.user.user_id = '';
                    this.user.password = '';
                },
               matchCaptcha(){
                   if(parseInt(this.captcha) != parseInt(this.rand_no)){
                    /*this.errors.add({
                                    field: 'captcha',
                                    msg: 'Captcha not match'
                                    });*/
                   // alert();                
                    this.captchamatch  = false;
                    this.captchamatcherror="captcha not match";               
                   // return false;
                  }else{
                    // alert();
                    this.captchamatcherror="";  
                    this.captchamatch  = true;  
                     /*this.errors.remove({
                                    field: 'captcha',
                                    
                                    });*/
                   // return true;
                  }
                },
                getRandomNo() {
                  axios
                    .get("generate-random-no", {})
                    .then(response => {
                      this.rand_no = response.data.data;
                    })
                    .catch(error => {});
                },
                 verify2Fa(){
                        axios.post('2fa/validatelogintoken',
                        {
                            googleotp:this.googleotp
                        },
                        {
                            headers: { 'Authorization':  "Bearer "+this.token }
                        }).then(resp => {
                            if(resp.data.code == 200) {
                                if(this.token){
                                    this.sendotp = false;
                                    localStorage.setItem('user-token', this.token);
                                    localStorage.setItem('type', 'user'); 
                                    this.$toaster.success(resp.data.message);
                                     this.$router.push({ name:'dashboard'});
                                     location.reload();
                                    //window.location.href = redirect;
        
                                } else {
                                    this.$toaster.success(resp.data.message);
                                    this.$router.push({ name:'dashboard'});
                                     location.reload();
                                }
                            } else {
                                this.$toaster.error(resp.data.message);
                            }
                        }).catch(err => {
                            this.$toastr.error("Something went wrong");
                        })
                    },
        
            }
        }
</script>