<template>
   <!--**********************************
      Content body start
      ***********************************-->
   <div class="content-body">
      <!-- row -->
      <div class="container-fluid">
         <div class="row invoice-card-row row-cols-1 row-cols-sm-2 row-cols-md-5 dashboard-card">
            <div class="col">
               <div class="card bg-secondary invoice-card">
                  <div class="card-body text-center">
                     <div class="icon">
                        <img src="public/user_files/assets/images/dash-icon/icon4.png" class="img-fluid w30">
                     </div>
                     <div>
                        <span class="text-white fs-18">Active Deposit</span>
                        <h2 class="text-white invoice-num">${{(userdata.total_deposit)}}</h2>
                        
                     </div>
                  </div>
               </div>
            </div>
            <div class="col">
               <a href="#/roi-income-report" @click="moveTop">
                  <div class="card bg-warning invoice-card">
                     <div class="card-body text-center">
                        <div class="icon">
                           <img src="public/user_files/assets/images/dash-icon/icon1.png" class="img-fluid w30">
                        </div>
                        <div>
                           <span class="text-white fs-18">ROI Earnings</span>
                           <h2 class="text-white invoice-num">${{userdata.roi_income}}</h2>
                           
                        </div>
                     </div>
                  </div>
               </a>
            </div>
            <div class="col">
               <a href="#/direct-income-report" @click="moveTop">
                  <div class="card bg-blue invoice-card">
                     <div class="card-body text-center">
                        <div class="icon">
                           <img src="public/user_files/assets/images/dash-icon/icon2.png" class="img-fluid w30">
                        </div>
                        <div>
                           <span class="text-white fs-18">Direct Earnings</span>
                           <h2 class="text-white invoice-num">${{userdata.direct_income }}</h2>
                           
                        </div>
                     </div>
                  </div>
               </a>
            </div>
            <div class="col">
               <a href="#/binary-income-report" @click="moveTop">
                  <div class="card bg-info invoice-card">
                     <div class="card-body text-center">
                        <div class="icon">
                           <img src="public/user_files/assets/images/dash-icon/bineryicon.png" class="img-fluid w30">
                        </div>
                        <div>
                           <span class="text-white fs-18">Binary Earnings</span>
                           <h2 class="text-white invoice-num">${{userdata.binary_income }}</h2>
                           
                        </div>
                     </div>
                  </div>
               </a>
            </div>
            <div class="col">
               <div class="card bg-secondary invoice-card">
                  <div class="card-body text-center">
                     <div class="icon">
                        <img src="public/user_files/assets/images/dash-icon/icon4.png" class="img-fluid w30">
                     </div>
                     <div>
                        
                        <span class="text-white fs-18">Total Earnings</span>
                        <h2 class="text-white invoice-num">${{(userdata.total_earnings)}}</h2>
                     </div>
                  </div>
               </div>
            </div>
            
         </div>


         <!--New Addtion Row Start Here-->
         <div class="row justify-content-center">
            <div class="col-xl-4 col-lg-12 col-sm-12">
               <div class="card overflow-hidden">
                  <div class="text-center p-2 overlay-box"
                     style="background-image: url(public/user_files/assets/images/img5.jpg);">
                     <img src="public/user_files/assets/images/card/rewards.png" width="70"
                        class="img-fluid rounded-circle" alt="">
                     <h3 class="mt-1 mb-0 text-white">Total Equitals Bonus</h3>
                  </div>
                  <div class="card-body">
                     <div class="row text-center">
                        <div class="col-12">
                           <div class="bgl-primary rounded p-3">
                              <h2 class="mb-0 text-white">${{userdata.matching_bonus_income}}</h2>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-xl-4 col-xxl-4 col-sm-6">
               <div class="card overflow-hidden">
                  <div class="social-graph-wrapper widget-facebook overlay-box p-2"
                     style="background-image: url(public/user_files/assets/images/img5.jpg);">
                     <img src="public/user_files/assets/images/card/medal.png" width="70"
                        class="img-fluid rounded-circle" alt="">
                     <h3 class="mt-1 mb-0 text-white">Current Equitals Bonus</h3>
                  </div>
                  <div class="row">
                     <div class="col-12">
                        <div class="pt-1 pb-1 ps-0 pe-0 text-center">
                           <h2 class="m-1"><span class="counter">${{userdata.current_reward}}</span> Weekly </h2>
                        </div>
                     </div>

                     <div class="col-6 border-end">
                        <div class="pt-1 pb-1 ps-0 pe-0 text-center">
                           <h4 class="m-1"><span class="counter">${{userdata.l_bv}}</span></h4>
                           <p class="m-0">Left Business</p>
                        </div>
                     </div>
                     <div class="col-6">
                        <div class="pt-1 pb-1 ps-0 pe-0 text-center">
                           <h4 class="m-1"><span class="counter">${{userdata.r_bv}}</span></h4>
                           <p class="m-0">Right Business</p>
                        </div>
                     </div>

                  </div>
               </div>
            </div>
            <div class="col-xl-4 col-xxl-4 col-sm-6">
               <div class="card overflow-hidden">
                  <div class="social-graph-wrapper widget-facebook overlay-box p-2"
                     style="background-image: url(public/user_files/assets/images/img5.jpg);">
                     <img src="public/user_files/assets/images/card/ribbon-badge.png" width="70"
                        class="img-fluid rounded-circle" alt="">
                     <h3 class="mt-1 mb-0 text-white">Next Equitals Bonus</h3>
                  </div>
                  <div class="row">
                     <div class="col-12">
                        <div class="pt-1 pb-1 ps-0 pe-0 text-center">
                           <h2 class="m-1"><span class="counter">${{userdata.req_amt}}</span> Weekly </h2>
                        </div>
                     </div>
                     <div class="col-6 border-end">
                        <div class="pt-1 pb-1 ps-0 pe-0 text-center">
                           <h4 class="m-1"><span class="counter">${{userdata.req_lbv}}</span></h4>
                           <p class="m-0">Required Left Business</p>
                        </div>
                     </div>
                     <div class="col-6">
                        <div class="pt-1 pb-1 ps-0 pe-0 text-center">
                           <h4 class="m-1"><span class="counter">${{userdata.req_rbv}}</span> </h4>
                           <p class="m-0">Required Right Business</p>
                        </div>
                     </div>

                  </div>
               </div>
            </div>

            <!--     <div class="col-md-4">
               <div class="card">
                   <h2> Rank Earnings </h2>
                   <p class="text-center">01</p>
               </div>
               </div>
               <div class="col-md-4">
                <div class="card">
                   <h2>Current Reward </h2>
                   <p class="text-center">  Left & Right Businness</p> 
               </div>          
               </div>
               <div class="col-md-4">
                <div class="card">
                   <h2>Remaining Reward</h2>
                   <p>Left Business</p> <p>Right Business</p>
               </div>              
               </div> -->
         </div>
         <!--New Addtion Row Ends Here-->
         <div class="row">
            <div class="col-xl-12 col-xxl-12">
               <div class="card">
                  <div class="card-body">
                     <div class="row align-items-center">
                        <!--  <div class="col-xl-12">
                           <div class="user-bx">
                              <img src="images/profile/small/pic1.jpg" alt="">
                              <div>
                                 <h6 class="user-name">Samuel</h6>
                                 <span class="meta">@sam224</span>
                              </div>
                              <i class="las la-check-circle check-icon"></i>
                           </div>
                           </div> -->
                        <div class="col-xl-3">
                           <div class="card-bx bg-green">
                              <img class="pattern-img" src="public/user_files/assets/images/pattern/pattern9.png"
                                 alt="">
                              <div class="card-info text-white">
                                 <img src="public/user_files/assets/images/pattern/circle.png" class="mb-4" alt="">
                                 <h2 class="text-white card-balance">${{ (userdata.fund_Wallet_balance)}}</h2>
                                 <p class="fs-16 text-white">EQX Wallet Balance</p>
                                 <!-- <span>+0,8% than last week</span> -->
                              </div>
                              <a class="change-btn" href="#/add-fund-one" @click="moveTop">
                                 <i class="fa fa-caret-up up-ico"></i>Add Fund<span class="reload-icon"><i
                                       class="fas fa-sync-alt reload active"></i></span>
                              </a>
                           </div>
                        </div>
                        <div class="col-xl-6">
                           <div class="row  mt-xl-0 mt-4">
                              <div class="col-md-6">
                                 <h4 class="card-title">Revenue Overview</h4>
                                 <!-- <span>Showing Below All Income</span>  -->
                                 <ul class="card-list mt-4">
                                    <li><span class="bg-warning circle"></span>ROI
                                       Earnings<span>${{ userdata.roi_income }}</span></li>
                                    <li><span class="bg-success circle"></span>Direct
                                       Earnings<span>${{ userdata.direct_income }}</span></li>
                                    <li><span class="bg-blue circle"></span>Binary
                                       Earnings<span>${{ userdata.binary_income }}</span></li>
                                    <li><span class="bg-light circle"></span>Equitals Bonus Earnings<span>${{ (userdata.matching_bonus_income)}}</span></li>
                                    <li><span class="bg-danger circle"></span>Total Earnings<span>${{ (userdata.total_earnings)}}</span></li>
                                 </ul>
                              </div>
                              <div class="col-md-6">
                                 <!-- <canvas id="polarChart1"></canvas> -->
                                 <div class="d-flex justify-content-center align-items-center">
                                    <div id="chart">
                                      <apexchart type="pie" width="320" :options="chartOptions" :series="series"></apexchart>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-xl-3">
                           <div class="card-bx bg-green">
                              <img class="pattern-img" src="public/user_files/assets/images/pattern/pattern9.png"
                                 alt="">
                              <div class="card-info text-white">
                                 <img src="public/user_files/assets/images/pattern/circle.png" class="mb-4" alt="">
                                 <h2 class="text-white card-balance">${{(userdata.working_Wallet_balancenew)}}</h2>
                                 <p class="fs-16 text-white">Performance Wallet Balance</p>
                                 <!-- <span>+0,8% than last week</span> -->
                              </div>
                              <a class="change-btn" href="#/make-working-withdrawal" @click="moveTop">
                                 <i class="fa fa-caret-up up-ico"></i>Withdraw<span class="reload-icon"><i
                                       class="fas fa-sync-alt reload active"></i></span>
                              </a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-xl-4 col-xxl-4 dn">
               <div class="card">
                  <div class="card-body pb-0 pt-3">
                     <div class="row">
                        <div class="col-xl-5">
                           <div class="card-bx bg-blue">
                              <img class="pattern-img" src="public/user_files/assets/images/pattern/pattern6.png"
                                 alt="">
                              <div class="card-info text-white">
                                 <img src="public/user_files/assets/images/pattern/circle.png" class="mb-4" alt="">
                                 <h2 class="text-white card-balance">${{ (userdata.fund_Wallet_balance)}}</h2>
                                 <p class="fs-16 text-white">EQX Balance</p>
                                 <!-- <span>+0,8% than last week</span> -->
                              </div>
                              <a class="change-btn" href="#/add-fund-one" @click="moveTop">
                                 <i class="fa fa-caret-up up-ico"></i>Add Fund<span class="reload-icon"><i
                                       class="fas fa-sync-alt reload active"></i></span>
                              </a>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="card-body pb-0 pt-3 dn">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="form-group">
                              <!-- <label class="control-label">Transaction Type</label>  -->
                              <fieldset class="mb-3">
                                 <div class="row">
                                    <div class="col-sm-12" v-if="mode === 'withdraw'">
                                       <p>Currency Type</p>
                                       <select v-model="Currency_type" class="form-control">
                                          <option value="">Select Currancy</option>
                                          <option v-for="cur in currency_code " :value="cur.currency_code">
                                             {{cur.currency_name}} ( {{cur.currency_code}} )
                                          </option>
                                       </select>
                                    </div>
                                 </div>
                              </fieldset>
                              <div class="clearfix"></div>
                           </div>
                        </div>
                        <div class="col-md-12">
                           <div class="form-group">
                              <label class="control-label">Enter The Available Amount (USD)</label> <br>
                              <input type="number" min="10" step="10" id="set-working-wallet" name="Amount"
                                 v-model="set_working_wallet"
                                 class="form-control blue-back W-a-xs { error: errors.has('set-working-wallet') }"
                                 formcontrolname="set-working-wallet" placeholder="Enter Amount"
                                 v-validate="'required|numeric'"
                                 onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" />
                              <div class="tooltip2" v-show="errors.has('Amount')">
                                 <div class="tooltip-inner">
                                    <span v-show="errors.has('Amount')">{{ errors.first('Amount') }}</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-12">
                           <div v-if="mode === 'withdraw'" class="form-group">
                              <br>
                              <p>Deduction</p>
                              <input type="text" name="deduction" v-model="deduction" class="form-control" disabled
                                 readonly>
                           </div>
                           <p v-if="!isValid">
                              <span class="error-msg-size tooltip-inner">{{ this.usermsg }}</span>
                           </p>
                        </div>
                        <br><br>
                        <!-- <div class="col-md-8">
                           <div class="form-group">
                              <label class="control-label">Remark</label> <br>
                              <input formcontrolname="remark" id="remark" name="remark" placeholder="Remark" type="text" class="form-control" v-model="remark"> 
                              <div class="clearfix"></div>
                           </div>
                           </div> -->
                     </div>
                     <div class="panel-footer bg-gray text-center">
                        <button v-if="mode == 'withdraw'" class="btn btn-primary" style="width: 100%;" type="button"
                           @click.prevent="sendOTP()"
                           :disabled="!isCompleteWorkingWithdrawal || !isValid || errors.any() ||withdrawBtn==true">Withdraw</button>
                        <button v-if="mode == 'transferToTopup'" class="btn btn-primary" type="button"
                           @click.prevent="sendOTP()"
                           :disabled="!isCompleteWorkingWithdrawal || !isValid || errors.any() ||withdrawBtn==true">Transfer</button>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-xl-6 col-xxl-6">
               <div class="card">
                  <div class="card-header border-0 pb-0">
                     <div>
                        <h4 class="card-title mb-2">News Section</h4>
                        <!-- <span class="fs-12">Lorem ipsum dolor sit amet, consectetur</span> -->
                     </div>
                     <div class="dropdown">
                        <a href="javascript:void(0);" class="btn-link" data-bs-toggle="dropdown" aria-expanded="false">
                        </a>
                     </div>
                  </div>
                  <div class="card-body">
                     <div class="col-xl-12 mh270 h600">
                        <!-- News Panel Widget BEGIN -->
                        <!-- News Panel Widget BEGIN -->
                        <div class="cryptohopper-web-widget demoScroll sc6" data-id="5" data-news_count="5"
                           data-news_length="4" data-atid="1"></div>
                        <!-- <div class="widget-container">
                           <iframe src="https://widgetscdn.cryptomood.com/sentiment-news?theme=light&direction=row&size=large"></iframe>
                           </div> -->
                        <!-- News Panel Widget END -->
                     </div>
                     <!--  <ul >
                        <li v-for="news in newsdata"><a href=""><marquee onmouseover="this.stop();" onmouseout="this.start();">{{ news.text }}</marquee></a></li>
                        </ul> -->
                     <!-- <marquee v-if="newsdata != ''" v-for="news in newsdata">{{ news.text }}</marquee>
                        <marquee v-if="newsdata == ''">Welcome to Lupaz. We are coming soon!</marquee> -->
                  </div>
                  <!-- <div class="card-footer border-0 pt-0">
                     <a href="javascript:void(0);" class="btn btn-outline-primary d-block btn-lg">View More</a>
                     </div> -->
               </div>
            </div>
            <div class="col-xl-6 col-xxl-6">
               <div class="row">
                  <div class="col-xl-12">
                     <div class="card coin-card">
                        <div class="card-body d-sm-flex d-block align-items-center">
                           <span class="coin-icon">
                              <div class="icon me-3">
                                 <img src="public/user_files/assets/images/dash-icon/icon4.png" class="img-fluid w30">
                              </div>
                           </span>
                           <div>
                              <h3 class="text-white">Referral Links</h3>
                              <p> Click Below For Referral Links</p>
                              <a class="btn btn-primary text-white ref" href="javascript:void(0);"
                                 data-bs-toggle="modal" data-bs-target="#exampleModalCenter">Click Here >></a>
                              <!-- <button type="button" class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg">Large modal</button> -->
                           </div>
                           <!-- Modal -->
                           <div class="modal fade" id="exampleModalCenter">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                 <div class="modal-content">
                                    <div class="modal-header">
                                       <h5 class="modal-title">Equitals Corp Referral Links</h5>
                                       <button type="button" class="btn-close" data-bs-dismiss="modal">
                                       </button>
                                    </div>
                                    <div class="modal-body">
                                       <div class="row">
                                          <div class="col-md-12">
                                             <div class="card-bx bg-blue">
                                                <img class="pattern-img"
                                                   src="public/user_files/assets/images/pattern/pattern6.png" alt="">
                                                <div class="card-info text-white pt0">
                                                   <img src="public/user_files/assets/images/pattern/circle.png"
                                                      class="mb-1" alt="">
                                                   <h2 class="text-white card-balance">Left Referral Link</h2>
                                                   <div class="row">
                                                      <div class="col-xl-6 col-md-6 col-sm-6 col-12 pe-sm-0">
                                                         <div class="mb-1">
                                                            <input type="text" class="form-control" readonly
                                                               id="referral-left"
                                                               :value="referrallink.link+'&position=1'">
                                                         </div>
                                                      </div>
                                                      <div class="col-xl-6 col-md-6 col-sm-6 col-12">
                                                         <button type="button" class="btn btn-rounded btn-secondary"
                                                            id="refcopy1" onclick="myFunctionRefLeft()"
                                                            onmouseout="outFunc()"><span
                                                               class="btn-icon-start text-secondary"><i
                                                                  class="fa fa-copy color-secondary"></i> </span>Copy
                                                            Now!</button>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="col-md-12 mt-10">
                                             <div class="card-bx bg-purpel mb-0">
                                                <img class="pattern-img"
                                                   src="public/user_files/assets/images/pattern/pattern10.png" alt="">
                                                <div class="card-info text-white pt0">
                                                   <img src="public/user_files/assets/images/pattern/circle.png"
                                                      class="mb-1 fr" alt=""><br><br>
                                                   <h2 class="text-white card-balance">Right Referral Link</h2>
                                                   <div class="row">
                                                      <div class="col-xl-6 col-md-6 col-sm-6 col-12 pe-sm-0">
                                                         <div class="mb-1">
                                                            <input type="text" readonly class="form-control"
                                                               id="myRightInput"
                                                               :value="referrallink.link+'&position=2'">
                                                         </div>
                                                      </div>
                                                      <div class="col-xl-6 col-md-6 col-sm-6 col-12">
                                                         <button type="button" class="btn btn-rounded btn-secondary"
                                                            id="right-refcopy" onclick="myFunctionRefRight()"
                                                            onmouseout="outrightFunc()"><span
                                                               class="btn-icon-start text-secondary"><i
                                                                  class="fa fa-copy color-secondary"></i> </span>Copy
                                                            Now!</button>
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
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="card progress-card">
                        <a href="#/team-view" @click="moveTop">
                           <div class="card-body d-flex">
                              <div class="me-auto">
                                 <h4 class="card-title">Total Member</h4>
                                 <div class="d-flex align-items-center">
                                    <h2 class="fs-38 mb-0">{{ userdata.totalActive }}</h2>
                                    <div class="text-success transaction-caret">
                                       <i class="fas fa-sort-up"></i>
                                       <!-- <p class="mb-0">+0.5%</p> -->
                                    </div>
                                 </div>
                              </div>
                              <div class="progress progress-vertical-bottom" style="min-height:110px;min-width:10px;">
                                 <div class="progress-bar bg-primary" style="width:10px; height:40%;"
                                    role="progressbar">
                                    <span class="sr-only">40% Complete</span>
                                 </div>
                              </div>
                              <div class="progress progress-vertical-bottom" style="min-height:110px;min-width:10px;">
                                 <div class="progress-bar bg-primary" style="width:10px; height:55%;"
                                    role="progressbar">
                                    <span class="sr-only">55% Complete</span>
                                 </div>
                              </div>
                              <div class="progress progress-vertical-bottom" style="min-height:110px;min-width:10px;">
                                 <div class="progress-bar bg-primary" style="width:10px; height:80%;"
                                    role="progressbar">
                                    <span class="sr-only">80% Complete</span>
                                 </div>
                              </div>
                              <div class="progress progress-vertical-bottom" style="min-height:110px;min-width:10px;">
                                 <div class="progress-bar bg-primary" style="width:10px; height:50%;"
                                    role="progressbar">
                                    <span class="sr-only">50% Complete</span>
                                 </div>
                              </div>
                           </div>
                        </a>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="card progress-card">
                        <div class="card-body d-flex">
                           <div class="me-auto">
                              <h4 class="card-title">Active Member</h4>
                              <div class="d-flex align-items-center">
                                 <h2 class="fs-38 mb-0">{{ userdata.totalInactive }}</h2>
                                 <div class="text-success transaction-caret">
                                    <i class="fas fa-sort-up"></i>
                                    <!-- <p class="mb-0">+0.5%</p> -->
                                 </div>
                              </div>
                           </div>
                           <div class="progress progress-vertical-bottom" style="min-height:110px;min-width:10px;">
                              <div class="progress-bar bg-primary" style="width:10px; height:40%;" role="progressbar">
                                 <span class="sr-only">40% Complete</span>
                              </div>
                           </div>
                           <div class="progress progress-vertical-bottom" style="min-height:110px;min-width:10px;">
                              <div class="progress-bar bg-primary" style="width:10px; height:55%;" role="progressbar">
                                 <span class="sr-only">55% Complete</span>
                              </div>
                           </div>
                           <div class="progress progress-vertical-bottom" style="min-height:110px;min-width:10px;">
                              <div class="progress-bar bg-primary" style="width:10px; height:80%;" role="progressbar">
                                 <span class="sr-only">80% Complete</span>
                              </div>
                           </div>
                           <div class="progress progress-vertical-bottom" style="min-height:110px;min-width:10px;">
                              <div class="progress-bar bg-primary" style="width:10px; height:50%;" role="progressbar">
                                 <span class="sr-only">50% Complete</span>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div id="editBankDetailsmodal" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
            class="modal">
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                     <h4 class="modal-title">Enter OTP</h4>
                     <button type="button" data-dismiss="modal" @click="closePopup()" class="close">×</button>
                  </div>
                  <div class="modal-body">
                     <div class="form-group">
                        <div class="row">
                           <div class="col-md-12">
                              <input type="text" name="otp" placeholder="Enter OTP" v-model="otp" class="form-control"
                                 aria-required="true" aria-invalid="false">
                              <div class="tooltip2" v-show="errors.has('otp')">
                                 <div class="tooltip-inner">
                                    <span v-show="errors.has('otp')">{{ errors.first('otp') }}</span>
                                 </div>
                              </div>
                           </div>
                           <br> <br> <br> <br>
                           <div class="clearfix"></div>
                           <div class="col-md-12">
                              <center><button type="button" @click="verifyOtp()" class="btn btn-primary">Submit</button>
                              </center>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- Reminder modal popup start -->
        <!--  <div id="show_popup" class="modal fade bd-example-modal-lg bg-b" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
               <div class="modal-content no-bgg">
                   <div class="modal-header">
                     <h5 class="modal-title">Reminder Message</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" @click="closePopup('show_popup')">
                     </button>
                     </div>
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-xl-12 mtb50">
                           <p class="ins1">Your package expires in {{userdata.retopup_days}} day</p>
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer modal-footer justify-content-center bnn">
                     <button type="button" @click="closePopup('show_popup')" class="btn btn-danger light"
                        data-bs-dismiss="modal">Close</button>
                     <a @click.prevent="closePopup1" type="button" class="btn btn-success light">Accept</a>
                  </div>
               </div>
            </div>
         </div> -->
         <!-- Reminder modal popup Ends -->
      </div>
   </div>
   <!--**********************************
      Content body end
      ***********************************-->
</template>

  
<script>
   import moment from "moment";
   import VueApexCharts from 'vue-apexcharts';
   import {
      apiUserHost
   } from "../../user-config/config";
   export default {
       components: {
          apexchart :VueApexCharts
       },

      data() {


         return {
             series: [],
          chartOptions: {
             colors:['#ffa755', '#6fe365', '#4a6ecc','#c8c8c8'],
             legend: {
               show: false,
             },
            chart: {
              width: 380,
              type: 'pie',
            },
            labels: ['ROI Earning', 'Direct Earning', 'Binary Earning','XentoBonus Earning'],
            responsive: [{
              breakpoint: 480,
              options: {
                chart: {
                  width: 200
                },
                legend: {
                  position: 'bottom'
                }
              }
            }]
          },
            itemname: "",
            INR: "",
            type: "",

            usddata: 0,
            series_data: [],
            currency_code: {},
            withdrawal_currency_code: {},
            typeChange: 'BTC',
            amount: 0,
            userdata: {
               total_earnings: 0,
               roi_income: 0,
               leveincome: 0,
               direct_income: 0,
               totawithdraw: 0,
               total_withdraw_amount: 0,
               top_up_Wallet_balance: 0,
               direct_list: 0,
               fund_Wallet_balance: 0,
               retopup_days: 0
            },
            mode: "withdraw",
            Currency_type: 'BTC',
            set_working_wallet: '',
            set_working_wallet: 0,
            userProfile: {},
            todolists: {
               id: "",
               itemname: ""
            },
            getpackages: {
               id: ""
            },
            myteams: {
               fullname: ""
            },
            useractivities: {
               fullname: "",
               message: "",
               entry_time: ""
            },
            working_balance: 0,
            objProSettings: {},
            referrallink: "",
            disablebtn: false,
            subject: "",
            isValid: true,
            usermsg: "",
            otp: "",
            deduction: "",
            newsdata: '',
            link: "",
            link1: "",
            link2: "",
            displayLink: true,
            displaymodal: false,
            usersleft: '',
            usersright: '',
            todayleft: '',
            todayright: '',
            usersrightcounts: '',
            usersleftcounts: '',
            icoStatusInfo: '',
            adminStatus: '',
            contest_data: {},
            table: {},
            mode: "withdraw",
            withdrawBtn: false,
            currency_code: [],
            deduction: "",
            remark: ''
         };
      },

      mounted() {
         // this.getInvestmentReports();
         //this.getIcoStatusFun();
         //this.disableCpy();
         this.getUserDetails();
         //this.check90Days();
         //this.getusddata();
         //this.get_todays_rank_count();
         this.close();
         this.getUserContestData();
         this.getAllCurrency();
         this.getWithdrawalCurrency();
         this.getWorkingWithdrawalDeduction();
         this.getWorkingWithdrawal();
         this.getLevelView();
         //this.getnews();
         //  alert(this.INR);
         //  this.getToDoList();
         // this.getPackages();
         // this.getMyTeam();
         // this.getAllActivity();
         // this.getUserProfile();
         //this.getProSettings();
         // this.getReferralLink();
         // this.checkPromo();
         setTimeout(() => {
            this.get_graph_report();
         }, 2000)
         // this.getGraphDetails();
      },
      computed: {
         isCompletePromotions() {
            return this.link1;
         },
         isCompletePromotions2() {
            return this.link2;
         },
         isCompleteWorkingWithdrawal() {
            return this.set_working_wallet || this.addTopup;
         },
      },
      methods: {
         moveTop(){
           document.body.scrollTop = 0;
          document.documentElement.scrollTop = 0;

        },
         getGraphDetails()
         {
           new google.charts.load("current", {packages:["corechart"]});
        new google.charts.setOnLoadCallback(this.drawChart());
         },
         drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Income', 'In $'],
          ['Roi',  5.85],
          ['Direct',  1.66],
          ['Binary', 0.316],
          ['XentoBonus', 0.0791]
        ]);

      var options = {  
        legend: 'none',
        pieSliceText: 'label',
        title: '',
        pieStartAngle: 100,
      };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
      },
         getnews() {
            axios.get('get-news')
               .then(response => {
                  if (response.data.code === 200) {
                     this.newsdata = response.data.data;
                  }
               }).catch(error => {

               });
         },
         getWorkingWithdrawal() {
            axios
               .get("get-working-balance", {})
               .then((response) => {
                  // console.log(response)
                  if (response.data.code == 200) {
                     this.working_balance =
                        typeof response.data.data == Object ? 0 : response.data.data;
                  }
               })
               .catch((error) => {});
         },
         getWorkingWithdrawalDeduction() {
            axios
               .get("get-working-withdrawal-deduction", {})
               .then((response) => {
                  // console.log(response)
                  if (response.data.code == 200) {
                     this.deduction =
                        typeof response.data.data == Object ? 0 : response.data.data + "%";
                  }
               })
               .catch((error) => {});
         },
         getAllCurrency() {
            axios.get('getall-currency', {
                  withdrwal_status: 1,
               })
               .then(response => {
                  this.currency_code = response.data.data;
               })
               .catch(error => {});
         },
         getWithdrawalCurrency() {
            axios
               .post("getall-currency", {
                  withdrwal_status: 1,
               })
               .then((response) => {
                  this.currency_code = response.data.data;
                  // this.Currency_type = "TRX";
               })
               .catch((error) => {});
         },
         get_graph_report() {
            var that = this;
            var ctx = document.getElementById("polarChart1").getContext('2d');
            Chart.defaults.global.legend.display = false;
            var myChart = new Chart(ctx, {
               type: 'polarArea',
               data: {
                  labels: ["Binary Income", "Direct Income", "ROI Income", "Available Balance"],
                  datasets: [{
                     backgroundColor: [
                        "#496ecc",
                        "#49820d",
                        "#ffa755",
                        "#c8c8c8"
                     ],
                     data: [this.userdata.binary_income, this.userdata.direct_income, this.userdata
                        .roi_income, this.userdata.matching_bonus_income
                     ]
                  }]
               },
               options: {
                  maintainAspectRatio: true,
                  scale: {
                     scaleShowLine: false,
                     display: false,
                     pointLabels: {
                        fontSize: 0
                     },
                  },
                  tooltips: {
                     enabled: false,
                  }
               }
            });
         },
         getIcoStatusFun() {
            axios
               .get("getIcoStatus", {})
               .then(response => {
                  this.icoStatusInfo = response.data.data.ico_status;
                  this.adminStatus = response.data.data.ico_admin_error_msg;


               })

         },
         purchasePackagecoin(product_id = '', currency_code = '', hash_unit = 0) {

            // $(".loadrr").modal();
            //$(".loader").show();
            /*if(currency_code != 'BTC')
            {
               axios.post('sendWAMessage', {
                 INR: this.INR,
                 amount: hash_unit,
              })
              this.fundamount = hash_unit;
              this.fundAmountDisplay = hash_unit * this.INR;
              this.product_id = product_id;
              $("#INR-payment").modal("show");
            }
            else
            {*/
            // alert(currency_code);
            // $(".overlay").show();
            // $(".loader").show();
            if (currency_code == 'PM') {
               // $('#login').modal();

               //this.doPayment(hash_unit);
               this.amt = hash_unit;
               /* axios.post('sendWAMessage', {
                  INR: this.INR,
                  amount: hash_unit,
               })
               this.fundamount = hash_unit;
               this.fundAmountDisplay = hash_unit * this.INR;
               this.product_id = product_id;
               $("#INR-payment").modal("show");*/
            } else {

               axios.post('purchase-package', {
                     currency_code: this.typeChange,
                     hash_unit: this.amount,
                  })
                  .then(resp => {
                     if (resp.data.code === 200) {
                        this.getpackagedetails = resp.data.data;
                        this.qrcodevalue = this.getpackagedetails.address;
                        var txn = resp.data.data.txn_details;
                        $('#loader').modal().hide();
                        $('#loader').modal('hide');
                        location.replace(this.getpackagedetails.status_url);
                        /*if (this.getpackagedetails.exists == 0) {
                          location.replace(this.getpackagedetails.status_url);
                        }else{
                          this.$toaster.success("Already requested for same amount");              
                        }*/
                        //$('#demo-default-modal').modal();
                     } else {
                        this.$toaster.error(resp.data.message);
                     }
                  })
                  .catch(error => {});
            }

         },
         get_todays_rank_count() {
            axios
               .get("/getAmount")
               .then(resp => {
                  if (resp.data.code === 200) {
                     //console.log(resp);

                     // this.alrank_data = resp.data.data[0];
                     this.usersleft = resp.data.data.usersleft;
                     this.usersright = resp.data.data.usersright;
                     this.usersrightcounts = resp.data.data.usersrightcounts;
                     this.usersleftcounts = resp.data.data.usersleftcounts;
                     this.todayleft = resp.data.data.todayleft;
                     this.todayright = resp.data.data.todayright;
                  }
               })
               .catch({});
         },
         close() {
            $("#sheet").click(function () {
               $("#myModal").css("display", "none");
            });
         },
         closePopup(id = 'editBankDetailsmodal') {
            $("#" + id).modal("hide");
         },
         closePopup1() {
            this.$router.push("/add-fund-one");
            location.reload();

         },
         disableCpy() {
            $(document).ready(function () {
               $("#referral-link1").bind("cut copy paste", function (e) {
                  e.preventDefault();
               });
            });
         },
         getProSettings() {
            axios
               .get("/getprojectsettings")
               .then(resp => {
                  if (resp.data.code === 200) {
                     this.objProSettings = resp.data.data;
                     // console.log(this.objProSettings);
                  }
               })
               .catch(err => {});
         },
         getusddata() {
            axios
               .get("getusddata", {})
               .then(response => {
                  var usd_data = response.data.data.usddata;
                  this.usddata = parseFloat(usd_data).toFixed(4)
                  /*console.log(this.usddata);*/

               })
               .catch(error => {});
         },
         // myFunctionb() {
         //       var copyText = document.getElementById("referral-link");
         //       copyText.select();
         //       copyText.setSelectionRange(0, 99999)
         //       document.execCommand("copy");

         //       var tooltip = document.getElementById("myTooltip");
         //       tooltip.innerHTML = "Copied: "; //+ copyText.value;
         //   },
         //    outFunct() {
         //       var tooltip = document.getElementById("myTooltip");
         //       tooltip.innerHTML = "Copy to clipboard";
         //   },
         getReferralLink() {
            axios
               .get("/get-reference-id")
               .then(resp => {
                  if (resp.data.code === 200) {
                     this.referrallink = resp.data.data;

                     // this.referrallink = "https://www.arbitude.com/arbitude/public/user#/register?ref_id=7509997056";
                  }
               })
               .catch({});
         },
         rankdisplay() {
            axios
               .get("/getrank", {})
               .then(resp => {
                  if (resp.data.code === 200) {


                     //this.alrank_data = resp.data.data[0];
                     // console.log(resp.data.data); 
                     // this.referrallink = "https://www.arbitude.com/arbitude/public/user#/register?ref_id=7509997056";
                  }
               })
               .catch({});
         },
         getUserDetails() {
            axios
               .get("get-user-dashboard")
               .then(response => {
                  this.userdata = response.data.data;
                  
                  this.series.push(this.userdata.roi_income);
                  this.series.push(this.userdata.direct_income);
                  this.series.push(this.userdata.binary_income);
                  this.series.push(this.userdata.matching_bonus_income);
                  this.referrallink = response.data.data;
                  /*$("#rank").html(this.userdata.rank);*/
                 /* if (this.userdata.show_popup == 1) {
                     $('#show_popup').modal('show');
                  } else {
                     $('#show_popup').modal('hide');
                  }*/
               })
               .catch(error => {});
         },
         getToDoList() {
            axios
               .post("todo-list", {})
               .then(response => {
                  this.todolists = response.data.data;
               })
               .catch(error => {});
         },

         deleteTodo(id) {
            axios
               .post("todo-delete", {
                  id: id
               })
               .then(response => {
                  if (response.data.code == 200) {
                     this.$toaster.success(response.data.message);
                     this.getToDoList();
                  } else {
                     this.$toaster.error(response.data.message);
                  }
               })
               .catch(error => {});
         },

         addTodo() {
            axios
               .post("todo-add", {
                  itemname: this.itemname
               })
               .then(response => {
                  if (response.data.code == 200) {
                     this.$toaster.success(response.data.message);
                     this.getToDoList();
                     this.itemname = "";
                  } else {
                     this.$toaster.error(response.data.message);
                  }
               })
               .catch(error => {});
         },

         getPackages() {
            axios
               .get("get-packages", {})
               .then(response => {
                  this.INR = response.data.data[0]["convert"];
                  this.type = response.data.data[0]["type"];
                  this.getpackages = response.data.data;
               })
               .catch(error => {});
         },

         getMyTeam() {
            axios
               .post("myTeam", {
                  limit: 5
               })
               .then(response => {
                  this.myteams = response.data.data;
               })
               .catch(error => {});
         },
         checkPromo() {
            axios
               .post("check/promotonalexist")
               .then(response => {
                  if (response.data.code == 200) {
                     this.displayLink = false;
                  } else {
                     this.displayLink = true;
                  }
               })
               .catch(error => {});
         },

         check90Days() {
            axios.post("check_90_days").then(response => {
                  if (response.data.code == 200) {
                     this.displaymodal = true;
                  } else {
                     this.displaymodal = false;
                  }
               })
               .catch(error => {});
         },

         getAllActivity() {
            axios
               .get("all-activity", {})
               .then(response => {
                  this.useractivities = response.data.data;
               })
               .catch(error => {});
         },
         getUserProfile() {
            axios.get("get-profile-info", {})
               .then(response => {
                  this.userProfile = response.data.data;
               })
               .catch(error => {});
         },
         getUserContestData() {
            axios.post("get-user-contest-data", {})
               .then(response => {
                  this.contest_data = response.data.data;
               })
               .catch(error => {});
         },
         getUserContestData1() {
            let i = 0;
            let that = this;
            let token = localStorage.getItem("user-token");
            setTimeout(function () {
               that.table = $("#contest-data").DataTable({
                  responsive: true,
                  lengthMenu: [
                     [10, 50, 100],
                     [10, 50, 100]
                  ],
                  bLengthChange: false,
                  searching: false,
                  retrieve: true,
                  destroy: true,
                  processing: false,
                  serverSide: true,
                  responsive: true,
                  stateSave: false,
                  ordering: false,
                  bPaginate: false,
                  bInfo: false,
                  ajax: {
                     url: apiUserHost + "get-user-contest-data",
                     type: "POST",
                     data: function (d) {
                        i = 0;
                        i = d.start + 1;

                        let params = {};
                        Object.assign(d, params);
                        return d;
                     },
                     headers: {
                        Authorization: "Bearer " + token
                     },
                     dataSrc: function (json) {
                        if (json.code === 200) {
                           that.arrGetHelp = json.data;
                           json["recordsFiltered"] = json.data;
                           json["recordsTotal"] = 4;
                           return json.data;
                        } else {
                           json["recordsFiltered"] = 0;
                           json["recordsTotal"] = 0;
                           return json;
                        }
                     }
                  },
                  columns: [{
                        render: function (data, type, row, meta) {
                           return i++;
                        }
                     },

                     {
                        render: function (data, type, row, meta) {
                           return that.userdata.l_commander;
                        }
                     },
                     {
                        render: function (data, type, row, meta) {
                           return that.userdata.r_commander;
                        }
                     },
                     {
                        render: function (data, type, row, meta) {
                           return row.required_left_commanders;
                        }
                     },
                     {
                        render: function (data, type, row, meta) {
                           return row.required_right_commanders;
                        }
                     },
                     {
                        render: function (data, type, row, meta) {
                           return row.contest_prize;
                        }
                     },
                     {
                        render: function (data, type, row, meta) {
                           if (row.claim_status == 0) {
                              return `<button class="btn green-claim claimprize">Claim</button>`;
                           } else if (row.claim_status == 1) {
                              return `<button class=" btn red-claim" data-toggle="tooltip" data-placement="top" title="you have already claimed this reward" disaled>Claimed</button>`;
                           } else if (row.claim_status == 2) {
                              return `<span class="label text-danger">Rejected</span>`;
                           } else if (row.claim_status == 3) {
                              return `<span class="label text-danger">You have already claimed another reward</span>`;
                           } else {
                              return `<span class="label text-danger">Not Achieved`;
                           }
                        }
                     },

                  ]
               });
               $('#contest-data').on('click', '.claimprize', function () {
                  if (that.table.row($(this).parents('tr')).data() !== undefined) {
                     var data = that.table.row($(this).parents('tr')).data();
                     that.claim_prize(data, that.table);
                  } else {
                     var data = table.row($(this)).data();
                     that.claim_prize(data, that.table);
                  }
               });
            }, 0);
         },
         sendOTP() {
            var arr = {};
            if (this.mode == "withdraw") {
               if (this.Currency_type == "") {
                  this.withdrawBtn = false;
                  return this.$toaster.error("Please select Currency");

               }
               arr = {
                  type: "Withdrawal"
               };
            } else {
               arr = {
                  type: "transfer"
               };
            }
            axios
               .post("sendOtp-update-user-profile", arr)
               .then((response) => {
                  if (response.data.code == 200) {
                     //console.log(response);
                     this.$toaster.success(response.data.message);
                     //this.statedata=response.data.data.message;
                     this.otp = "";
                     $("#editBankDetailsmodal").modal("show");
                  } else {
                     this.$toaster.error(response.data.message);
                  }
               })
               .catch((error) => {});
         },
         verifyOtp() {
            this.withdrawBtn = true;
            axios
               .post("withdraw-income", {
                  otp: this.otp,
                  level_income_balance: 0,
                  direct_income_balance: 0,
                  roi_balance: 0,
                  binary_income_balance: 0,
                  topup_wallet: 0,
                  addTopup: this.addTopup,
                  transfer_wallet: 0,
                  working_wallet: Number(this.set_working_wallet),
                  mode: this.mode,
                  Currency_type: this.Currency_type,
                  remark: this.remark
               })
               .then((response) => {
                  this.withdrawBtn = false;
                  if (response.data.code == 200) {
                     this.$toaster.success(response.data.message);

                     $("#editBankDetailsmodal").modal("hide");

                     this.$router.push({
                        path: "withdrawals-income-report"
                     });
                  } else {
                     this.$toaster.error(response.data.message);
                     this.otp = "";
                  }
               });
         },
         claim_prize(id) {
            axios.post("claim_prize", {
                  id: id
               }).then(response => {
                  if (response.data.code == 200) {
                     this.$toaster.success(response.data.message);
                     this.getUserContestData();
                  } else {
                     this.$toaster.error(response.data.message);
                  }
               })
               .catch(error => {});
         },
         getLevelView() {
            let i = 0;
            let that = this;
            let token = localStorage.getItem("user-token");
            setTimeout(function () {
               that.table = $("#direct-user-list").DataTable({
                  responsive: true,
                  lengthMenu: [
                     [5, 10, 50],
                     [5, 10, 50]
                  ],
                  retrieve: true,
                  destroy: true,
                  processing: false,
                  serverSide: true,
                  responsive: true,
                  stateSave: false,
                  ordering: false,
                  scrollY: 250,
                  deferRender: true,
                  scroller: true,
                  dom: 'rt',
                  ajax: {
                     url: apiUserHost + "team-view",
                     type: "POST",
                     data: function (d) {
                        i = 0;
                        i = d.start + 1;

                        let params = {
                           user_id: $("#user-id").val(),
                           frm_date: $("#from-date").val(),
                           to_date: $("#to-date").val(),
                        };
                        Object.assign(d, params);
                        return d;
                     },

                     headers: {
                        Authorization: "Bearer " + token
                     },
                     dataSrc: function (json) {
                        if (json.code === 200) {
                           that.arrGetHelp = json.data.records;
                           json["draw"] = json.data.draw;
                           json["recordsFiltered"] = json.data.recordsFiltered;
                           json["recordsTotal"] = json.data.recordsTotal;
                           return json.data.records;
                        } else {
                           json["draw"] = 0;
                           json["recordsFiltered"] = 0;
                           json["recordsTotal"] = 0;
                           return json;
                        }
                     }
                  },
                  columns: [{
                        "defaultContent": "",
                        render: function (data, type, row, meta) {
                           return i++;
                        }
                     },
                     {
                        data: "user_id",
                        "defaultContent": ""
                     },
                     // { data: 'fullname' },            
                     {
                        data: "position",
                        "defaultContent": ""
                     },
                     {
                        "defaultContent": "",
                        render: function (data, type, row, meta) {
                           if (
                              row.joining_date === null ||
                              row.joining_date === undefined ||
                              row.joining_date === ""
                           ) {
                              return `-`;
                           } else {
                              return moment(String(row.joining_date)).format("YYYY/MM/DD");
                           }
                        }
                     },
                     {
                        "defaultContent": "",
                        render: function (data, type, row, meta) {
                           // that.type == "USD";
                           if (row.amount != 0) {
                              return `<span style="color:green;">Active</span>`;
                           } else {
                              return `<span style="color:red;">Inactive</span>`;
                           }
                        }
                     }
                  ]
               });

               $("#onSearchClick").click(function () {
                  that.table.ajax.reload();
               });
               $("#onResetClick").click(function () {
                  $("#searchForm").trigger("reset");
                  that.table.ajax.reload();
               });

            }, 0);
         },
         sendLink(no) {
            if (no == 1) {
               this.link = this.link1;
            } else {
               this.link = this.link2;
            }
            this.disablebtn = true;
            axios
               .post("store/promotional", {
                  subject: this.subject,
                  link: this.link
               })
               .then(response => {
                  if (response.data.code == 200) {
                     //
                     this.disablebtn = false;
                     this.$toaster.success(response.data.message);
                     this.$router.push({
                        path: "promotional-report"
                     });
                  } else {
                     this.disablebtn = false;
                     this.$toaster.error(response.data.message);
                  }
               });
         }
      }
   };
</script>