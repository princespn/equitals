<template>
  <div>
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
                                  <a href="https://www.xentocorp.com/">
                                    <img src="public/user_files/assets/images/logo.png" alt=""></a>
                                </div>

                                    <h4 class="text-center mb-4">Reset password</h4>
                                   <!--  <p class="">
                                        Please enter your user id below and we will send you OTP on your registered email id!.
                                    </p> -->
                                    
                                    <form @submit.prevent="resetPassword">
                                        <div class="mb-3">
                                            <label><strong>New Password</strong></label>
                                            <input type="password" class="form-control { error: errors.has('newpassword') }" name="newpassword" id="newpassword" v-model="user.newpassword" v-validate="'required|min:6|max:15'" autofocus="" placeholder="Enter New Password">
                                            <div class="tooltip1" v-show="errors.has('newpassword')">
                                              <div class="tooltip1-inner"> <span class="text-danger error-msg-size tooltip1-inner" v-show="errors.has('newpassword')">{{ errors.first('newpassword') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label><strong>Confirm Password</strong></label>
                                              <input type="password" name="confirmpassword" class="form-control" id="confirmpassword" required="" v-model="user.confirmpassword" v-validate="'required|min:6|max:15'" placeholder="Enter Confirm Password">
                                              <div class="tooltip1" v-show="errors.has('confirmpassword')">
                                              <div class="tooltip1-inner"> <span class="text-danger error-msg-size tooltip1-inner" v-show="errors.has('confirmpassword')">{{ errors.first('confirmpassword') }}</span>
                                            </div>
                                    </div>
                                        </div>
                                         <small>
                                          Note:- Password must be more than 6 characters. It should contain uppercase, lowercase, numerical and special characters.
                                      </small>

                                        <div class="text-center">
                                          <input :disabled='!isSubmit || errors.any() || !isActiveBtn' type="submit" value="Reset" class="sbmt btn btn-primary btn-inline btn-block">

                                            <!-- <button type="submit" class="btn btn-primary btn-block">Seset</button> -->
                                        </div>
                                    </form>
                                     <div class="new-account mt-3">
                                        <p>Already have an account?  <router-link to="/login" class="text-primary">Back to Login</router-link></p>
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
  </div>
</template>
<script>
  export default {
      data() {
          return {
              resettoken:'',
              user: {
                  newpassword: '',
                  confirmpassword:''
              },
              flag_for_hide_validation_messege:'',
              icon: '../public/user_files/assets/img/icon-mlm.png',
              logo: '../public/user_files/assets/img/logo.png',
              hostName: window.location.origin,
              isActiveBtn:true
          }
      },
      computed:{
          isSubmit(){
             return this.user.newpassword && this.user.confirmpassword;
          }
      },
      mounted(){
          this.getResetToken();
          this.one_letter = 'invalid',
          this.greater_than_6 = 'invalid',
          this.one_capital_letter = 'invalid',
          this.special_char = 'invalid',
          this.one_number = 'invalid',
          this.starting_with_letter = 'invalid',
          this.flag_for_hide_validation_messege = false
      },
  
      methods: {
          onPasswordClick() {
  
            this.one_letter = 'invalid';
            this.greater_than_6 = 'invalid';
            this.one_capital_letter = 'invalid';
            this.special_char = 'invalid';
            this.one_number = 'invalid';
            this.starting_with_letter = 'invalid';
            this.flag_for_hide_validation_messege = true;
  
            if (this.user.newpassword.substring(0, 1) == this.user.newpassword.match(/[A-z]/)) {
                          // alert();
                          this.starting_with_letter = 'valid';
                      }
  
                      if ((this.user.newpassword.length >= 6) && (this.user.newpassword.length <= 15)) {
                         this.greater_than_6 = 'valid';
  
                     }
                     if (this.user.newpassword.match(/[a-z]/)) {
                         this.one_letter = 'valid';
                     }
  
                     if (this.user.newpassword.match(/[A-Z]/)) {
                         this.one_capital_letter = 'valid';
                     }
                     if (this.user.newpassword.match(/\d/)) {
                      this.one_number = 'valid';
                  }
  
                  if (this.user.newpassword.match(/[^a-zA-Z0-9\-\/]/)) {
                     this.special_char = 'valid';
                 }
                 if (this.user.newpassword.match(/\s/g)) {
                     this.special_char = 'invalid';
                 }
  
                 if (this.one_letter === 'valid' && this.greater_than_6 === 'valid' && this.one_capital_letter === 'valid' && this.special_char === 'valid' && this.one_number === 'valid' && this.starting_with_letter === 'valid') {
                     this.flag_for_hide_validation_messege = false;
                 }else{
                    this.errors.add({
                        field: 'password',
                        msg: 'password not valid'
                    });
                }
  
          },
  
          getResetToken(){
              var url = window.location.href;
              var hash = url.split('=');
              if (hash.length == 1) {
                  this.$toaster.error('Something Went Wrong...')
              } else {
                  this.resettoken = hash[1];
              }
          },
  
          resetPassword() {
              if(this.user.newpassword !=this.user.confirmpassword){
                  this.$toaster.error('Password not matched')
              }else{
                  this.isActiveBtn = false;
                  axios.post('reset-password', {
                      resettoken: this.resettoken,
                      password: this.user.newpassword,
                      confirm_password: this.user.confirmpassword,
                  }).then(resp => {
                      if(resp.data.code==200){
                         this.$toaster.success(resp.data.message)
                         this.$router.push({ name: 'login' });
  
                      }else{
                        this.$toaster.error(resp.data.message)
                      }
                      this.isActiveBtn = true;
                  }).catch(err => {
  
                  })
              }
          },
      }
  }
</script>