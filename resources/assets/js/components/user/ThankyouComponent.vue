
<template>

    <div class="thankyou">
       <div class="container">
            <div class="row">
              <div class="col-md-9 mx-auto">
                <div class="thankyoucard text-center">
                  
                 <div class="row">
                   <div class="col-md-12">
                      <div class="thankB">
                     <a href="https://www.equitals.com/" class="login_logo">
                      <img class="" src="public/user_files/assets/images/logo.png">
                    </a>

                    <!-- <img src="public/user_files/assets/images/thankright.png" style="width: 130px;"> -->
                    <h2 class="title pt-1">Activation Successful</h2>
                    <p class="tp">
                      Congratulations your submission has been successfully received .<br>
                      You are now a registered member of <b>Equitals.</b>
                    </p>
                  </div>
                   <form>
                    <div class="row">
                      <div class="col-sm-12 mt-2">
                        <h4> User Id : {{user_id}}</h4>
                        <h4>Password : {{password}}</h4>
                      </div>
                      <div class="col-lg-12 loginbtn">
                          <router-link :to="{ path: 'login'}" replace class="btn btn-primary">Login</router-link>
                      </div>
                    </div>
                  </form>
                   </div>
                 </div>
                </div>
             </div>
            </div>
          </div>
    </div>
</template>
<script>
  export default {
      data() {
          return {
              icon: "../public/user_files/assets/img/icon-mlm.png",
              logo: "../public/user_files/assets/img/logo.png",
              user_id : '',
              password : '',
              hostName: window.location.origin
          }
      },
      mounted()
          { 
              this.user_id = this.$route.params.user_id,
              this.password = this.$route.params.password
  
      },
  }
</script>