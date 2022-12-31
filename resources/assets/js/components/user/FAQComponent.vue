<style>
  .show {
    display: none !important;
  }
  .in {
    display: block !important;
  }
</style>
<template>
  <div>
    <!--CONTENT CONTAINER-->
    <!--===================================================-->
    <div id="content-container">
      <Breadcrum></Breadcrum>
      <!--Page content-->
      <!--===================================================-->
      <div id="page-content">
        <hr class="new-section-sm bord-no">
        <div class="row">
          <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="panel panel-body">
              <div class="panel-heading">
                <!-- <h3 class="text-center">FAQ</h3> -->
              </div>

              <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="col-sm-12">
                    <div class="panel">
                      <!--Block Styleda Form -->
                      <!--===================================================-->
                      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                          <div class="panel-heading" role="tab" id="headingOne">
                            <h4 class="panel-title panel-title_faq"> <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> 1. How to view My Profile?  </a> </h4>
                          </div>
                          <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body panel-body_faq">-After Login, click on 'Profile' and then click on 'My Profile'.</div>
                          </div>
                        </div>
                        <div class="panel panel-default">
                          <div class="panel-heading" role="tab" id="headingTwo">
                            <h4 class="panel-title panel-title_faq"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"> 2. How to update/edit my profile? </a> </h4>
                          </div>
                          <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                            <div class="panel-body panel-body_faq">-After Login, click on 'Profile',then click on 'Edit Profile'. All the BITCOIN(BTC) users are requested only to enter your BITCOIN ADDRESS. All the users making payments through INDIAN RUPEES(INR) are requested to fill up their BANK ACCOUNT NUMBER, BANK BRANCH NAME, A/C HOLDER NAME, PAN CARD NAME, BANK NAME and IFSC CODE.
                              <br />Once you are done with filling up necessary information then click on 'Update Changes' button. Our system will send an OTP on your registered E-mail id and registered Mobile number. Enter that OTP and click on 'Submit' button and your Profile will be updated.</div>
                          </div>
                        </div>
                        <div class="panel panel-default">
                          <div class="panel-heading" role="tab" id="headingThree">
                            <h4 class="panel-title panel-title_faq"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree"> 3. How to change the password of my User Account?  </a> </h4>
                          </div>
                          <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                            <div class="panel-body panel-body_faq">-After Login, click on 'Profile',then click on 'Change Password'. Enter your 'Current Password' then enter a 'New Password' and again 'Re-enter Password' and click on 'Submit' button. Your password will be changed.</div>
                          </div>
                        </div>
                        <div class="panel panel-default">
                          <div class="panel-heading" role="tab" id="headingFour">
                            <h4 class="panel-title panel-title_faq"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">4. How to Deposit funds in my account? </a> </h4>
                          </div>
                          <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                            <div class="panel-body panel-body_faq">-After Login, click on 'Deposit', then click on 'Make New Payments'. Choose an investment package. If you wish to deposit BITCOIN(BTC) enter the amount in Dollar value(USD) then select BTC option and click on 'Make Payment' button. System will generate an unique BITCOIN(BTC) address with a QR code, send the deposit amount on that BITCOIN(BTC) address from you BITCOIN wallet. Your payment will be automatically confirmed by our system after 6 confirmations from Blockchain. If you wish to deposit INDIAN RUPEES enter the amount in Dollar value(USD) then select INR option and click on 'Make Payment' button. System will send you company's bank account details on your registered E-mail id and Mobile no. You must send the funds to the same bank account through various ways such as NEFT, IMPS, RTGS. Once you send the payment upload the transfer proof such as payment slip or screenshot of successfull transfer. System admin will verify and confirm your payments within 2 to 3 hours.</div>
                          </div>
                        </div>
                        <div class="panel panel-default">
                          <div class="panel-heading" role="tab" id="headingFive">
                            <h4 class="panel-title panel-title_faq"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive"> 5. Where to see pending deposits in my User Account? </a> </h4>
                          </div>
                          <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                            <div class="panel-body panel-body_faq">-After Login, click on 'Deposit', then click on 'Ongoing Payments'.</div>
                          </div>
                        </div>
                        <div class="panel panel-default">
                          <div class="panel-heading" role="tab" id="headingSix">
                            <h4 class="panel-title panel-title_faq"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix"> 6. Where to see confirmed deposits in my User Account? </a> </h4>
                          </div>
                          <div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
                            <div class="panel-body panel-body_faq">-After Login, click on 'Deposit', then click on 'Confirmed Payments'.</div>
                          </div>
                        </div>
                        <div class="panel panel-default">
                          <div class="panel-heading" role="tab" id="headingSeven">
                            <h4 class="panel-title panel-title_faq"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven"> 7. Where to see INDIAN RUPEES(INR) deposit details in my User Account? </a> </h4>
                          </div>
                          <div id="collapseSeven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven">
                            <div class="panel-body panel-body_faq">-After Login, click on 'Deposit', then click on 'INR Payment Report'.</div>
                          </div>
                        </div>
                        <div class="panel panel-default">
                          <div class="panel-heading" role="tab" id="headingEight">
                            <h4 class="panel-title panel-title_faq"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseEight" aria-expanded="false" aria-controls="collapseEight"> 8. Where to see list of my direct referrals? </a> </h4>
                          </div>
                          <div id="collapseEight" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEight">
                            <div class="panel-body panel-body_faq">-After Login, click on 'My Team', then click on 'Direct User List'.</div>
                          </div>
                        </div>
                        <div class="panel panel-default">
                          <div class="panel-heading" role="tab" id="headingNine">
                            <h4 class="panel-title panel-title_faq"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseNine" aria-expanded="false" aria-controls="collapseNine"> 9. Where to see the list of users referred in my 5 levels?  </a> </h4>
                          </div>
                          <div id="collapseNine" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingNine">
                            <div class="panel-body panel-body_faq">-After Login, click on 'My Team', then click on 'Level View'.</div>
                          </div>
                        </div>
                        <div class="panel panel-default">
                          <div class="panel-heading" role="tab" id="headingTen">
                            <h4 class="panel-title panel-title_faq"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTen" aria-expanded="false" aria-controls="collapseTen"> 10. Where to see the earning details from my direct referrals? </a> </h4>
                          </div>
                          <div id="collapseTen" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTen">
                            <div class="panel-body panel-body_faq">-After Login, click on 'Income Report', then click on 'Referral Income Report'.</div>
                          </div>
                        </div>
                        <div class="panel panel-default">
                          <div class="panel-heading" role="tab" id="headingEleven">
                            <h4 class="panel-title panel-title_faq"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven"> 11.Where to see the earning details from the users referred in my 5 levels? </a> </h4>
                          </div>
                          <div id="collapseEleven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEleven">
                            <div class="panel-body panel-body_faq">-After Login, click on 'Income Report', then click on 'Level Income Report'.</div>
                          </div>
                        </div>
                        <div class="panel panel-default">
                          <div class="panel-heading" role="tab" id="headingTwelve">
                            <h4 class="panel-title panel-title_faq"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve"> 12. Where to see the earning details from daily ROI? </a> </h4>
                          </div>
                          <div id="collapseTwelve" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwelve">
                            <div class="panel-body panel-body_faq">-After Login, click on 'Income Report', then click on 'ROI Income Report'.</div>
                          </div>
                        </div>
                        <div class="panel panel-default">
                          <div class="panel-heading" role="tab" id="headingThirty">
                            <h4 class="panel-title panel-title_faq"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThirty" aria-expanded="false" aria-controls="collapseThirty"> 13. Where to see the earning details from my team's ROI?  </a> </h4>
                          </div>
                          <div id="collapseThirty" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThirty">
                            <div class="panel-body panel-body_faq">-After Login, click on 'Income Report', then click on 'Team ROI Income Report'.</div>
                          </div>
                        </div>
                        <div class="panel panel-default">
                          <div class="panel-heading" role="tab" id="headingForty">
                            <h4 class="panel-title panel-title_faq"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseForty" aria-expanded="false" aria-controls="collapseForty"> 14. Where to see my pending withdrawal details? </a> </h4>
                          </div>
                          <div id="collapseForty" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingForty">
                            <div class="panel-body panel-body_faq">- After Login, click on 'Withdrawal', then click on 'Ongoing Withdrawal'.</div>
                          </div>
                        </div>


                            <div class="panel panel-default">
                          <div class="panel-heading" role="tab" id="headingFifteen">
                            <h4 class="panel-title panel-title_faq"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFifteen" aria-expanded="false" aria-controls="collapseFifteen"> 15. Where to see my confirmed withdrawal details? </a> </h4>
                          </div>
                          <div id="collapseFifteen" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFifteen">
                            <div class="panel-body panel-body_faq">- After Login, click on 'Withdrawal', then click on 'Confirmed Withdrawal'.</div>
                          </div>
                        </div>


                      
           
            </div>
                      <!--===================================================-->
                      <!--End Block Styled Form -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--===================================================-->
        <!--End page content-->
      </div>
    </div>
    <!--===================================================-->
    <!--END CONTENT CONTAINER-->
  </div>
</template>
<script>
  import Breadcrum from './BreadcrumComponent.vue';
      import Swal from 'sweetalert2';
      export default {
          components: {
              Breadcrum
          },
          data() {
              return {
                  btcactive: true,
                  otpstatus:'',
                  btcmsg: '',
                  otp:'',
                  profile: {
                      fullname: '',
                      email: '',
                      mobile: '',
                      btc_address: '',
                      country: null
                  },
                  profile: [],
                  countries: [],
                  type: '',
                  profiledata: {         
                      holder_name: '',
                      bank_name: '',
                      branch_name: '',
                      pan_no: '',
                      ifsc_code: '',    
                      btc_address:'',
                      account_no:'',
                  },
              }
          },
          computed: {
              isCompleteProfile() {
                  return this.profile.fullname && this.profile.email && this.profile.mobile && this.btcactive && this.profile.country;
              },
          },
          mounted() {
  
          },
          methods: {
          }
      }
</script>