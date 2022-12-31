<template>
  <!-- BEGIN: Content-->
  <div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
      <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h2 class="content-header-title float-left mb-0">Pending Fund Report</h2>
              <!-- <div class="breadcrumb-wrapper col-12">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a>
                </li>
                <li class="breadcrumb-item active">Investment Report
                </li>
              </ol>
              </div>-->
            </div>
          </div>
        </div>
        <!-- <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
          <div class="form-group breadcrum-right">
            <div class="dropdown">
              <button
                class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle"
                type="button"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="feather icon-settings"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#">Chat</a>
                <a class="dropdown-item" href="#">Email</a>
                <a class="dropdown-item" href="#">Calendar</a>
              </div>
            </div>
          </div>
        </div> -->
      </div>
      <div class="content-body">
        <!-- Complex headers table -->
        <section id="headers">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <!-- <h4 class="card-title">ROI Income Report</h4> -->
                </div>
                <div class="card-content">
                  <div class="card-body card-dashboard">
                    <div class="table-responsive">
                      <table
                        id="pending-fund-report"
                        class="table table-striped table-bordered complex-headers"
                      >
                        <thead>
                          <tr>
                            <th>Sr No</th>
                            <th>Username</th>
                            <th>USD</th>
                            <th>Request Amount(BTC)</th>
                            <th>Paid Amount</th>
                            <th>Remaining Amount</th>
                            <th>Address</th>
                            <th>Date</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                          <tr>
                            <th>Sr No</th>
                            <th>Username</th>
                             <th>USD</th>
                             <th>Request Amount(BTC)</th>
                             <th>Paid Amount</th>
                            <th>Remaining Amount</th>
                            <th>Address</th>
                            <th>Date</th>
                            <th>Action</th>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!--===================================================-->
            <!--End page content-->
          </div>
        </section>
        <!--===================================================-->
        <!--END CONTENT CONTAINER-->
      </div>
    </div>

     <!-- Start Popup code -->
    <div id="demo-default-modal" role="dialog" tabindex="-1" aria-hidden="true" class="modal fade in">
      <div class="modal-dialog text-center">
        <div class="modal-content text-center">
          <div class="modal-header bg-primary">
            <h3 class="modal-title text-white">Deposit</h3>
          </div>
          <div class="modal-body">
            <div v-if="changemodal==0">
            <h4 class="m-b-10">Amount ${{getpackagedetails.price_in_usd}}</h4>
            <!-- <p class="text-semibold text-main">Please Deposit to complete your topup.</p> -->
            <div class="row m-b-10">
              <div class="col-md-5 text-right text-xs-center white-txt">Amount:</div>
              <div class="col-md-4 text-xs-center">
                <div class="input-group mar-btm mar-btm-0-xs"><span class="input-group-addon"><i class="fa fa-usd"></i></span>

                  <input type="text" class="form-control" v-model="getpackagedetails.price_in_usd" readonly="">
                </div>
              </div>
              <div class="col-md-3 text-left text-xs-center"><small>( {{ getpackagedetails.price_in_currency }} BTC )</small>
              </div>
            </div>
            <div class="row">
              <div class="col-md-5 text-right text-xs-center white-txt">Paid Amount:</div>
              <div class="col-md-4">
                <div class="input-group mar-btm mar-btm-0-xs"><span class="input-group-addon"><i class="fa fa-usd"></i></span> 
                  <input type="text" class="form-control" v-model="getpackagedetails.received_amount">
                </div>
              </div>
              <div class="col-md-3 text-left text-xs-center white-txt">BTC</div>
            </div>
            <div class="row m-b-10">
              <div class="col-md-5 text-right text-xs-center white-txt">Remaining Amount:</div>
              <div class="col-md-4 text-xs-center">
                <div class="input-group mar-btm mar-btm-0-xs"><span class="input-group-addon"><i class="fa fa-usd"></i></span> 
                  <input type="text" class="form-control" v-model="getpackagedetails.rem_amount" readonly="">
                </div>
              </div>
              <div class="col-md-3 text-left text-xs-center white-txt">BTC</div>
            </div>
            
            <div class="row">
              <div class="col-md-4 offset-md-4 text-center">
                <div class="qr-bg">
                  <!-- <img src="user_files/images/qr_code.png" width="100%" /> -->
                  <qrcode v-bind:value="this.qrcodevalue"></qrcode>
                </div>
              </div>
              <div class="col-md-12 text-center">
                <div class="input-group mar-btm">
                  <input type="text" v-model="getpackagedetails.address" id="btc-add" class="form-control"> <span class="input-group-btn tooltip2">
                              <button class="btn btn-info btn-labeled" type="button" onclick="myFunction1()" onmouseout="outFunc1()"> <i class="fa fa-file" aria-hidden="true"></i>   <span class="tooltiptext" id="refcopy1"></span>
                  Copy</button>
                  </span>
                </div>
              </div>

                     <div class="col-md-12 text-center">
              <div class="only-msg">
                <p class="pendingDeposit text-center"></p>
                <div class="counter text-center">

                 <span> Confirming... {{secondCount}}  </span>
                </div>
                <div class="row ">
                  <p class="qrclass text-center"></p>
                </div>
              </div>
            </div>


            </div>
          </div>

          <div class="addfundb2" v-if="changemodal==1">
               <img src="public/user_files/images/check.png" width="200px" />
               <h2 class="pay_confm">Payment Confirmed</h2>
               <div class="confmbtn_o">
                 <button type="button" @click="closeModal1" class="btn btn-outline-warning sm">Deposit Again</button>
                <!-- <router-link class="btn btn-outline-primary sm" :to="{ name: 'add-fund-report'}">
                        See History
                </router-link> -->
                 <button type="button" @click="showreport" class="btn btn-outline-primary sm">See History</button>
               </div>
            </div>
          <!-- <div v-if="changemodal==1">
            <h2>Success</h2>
          </div> -->
          </div>
          <div class="modal-footer">
            <button data-dismiss="modal" type="button" @click="closeModal()" class="btn btn-primary">Close</button>
          </div>
        </div>
      </div>
     
    </div>




  </div>
</template>

<script>
import moment from "moment";
import { apiUserHost } from "../../user-config/config";
import Breadcrum from "./BreadcrumComponent.vue";
export default {
  components: {
    Breadcrum
  },
  data() {
    return {
     getpackagedetails: {
                 price_in_usd: '',
                 received_amount: '',
                 price_in_currency: '',
                 address: '',
                 rem_amount:0,
              },
     changemodal:0,
     qrcodevalue:'',
     secondCount:30,
    status:0,
    btc: '',
    currency_code: {},
    amount: [],
    product_id: '',
  };
  },
  mounted() {
    this.getLevelView();
  },
  methods: {
    getLevelView() {
      let i = 0;
      let that = this;
      let token = localStorage.getItem("user-token");
      setTimeout(function() {
        const table = $("#pending-fund-report").DataTable({
          responsive: true,
          lengthMenu: [
            [10, 50, 100],
            [10, 50, 100]
          ],
          retrieve: true,
          destroy: true,
          processing: false,
          serverSide: true,
          responsive: true,
          stateSave: false,
          ordering: true,
          ajax: {
            url: apiUserHost + "pending-fund-report",
            type: "POST",
            data: function(d) {
              i = 0;
              i = d.start + 1;

              let params = {};
              Object.assign(d, params);
              return d;
            },
            headers: {
              Authorization: "Bearer " + token
            },
            dataSrc: function(json) {
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
          columns: [
            {
              render: function(data, type, row, meta) {
                return i++;
              }
            },
           /* {
              render: function(data, type, row, meta) {
                if (
                  row.entry_time === null ||
                  row.entry_time === undefined ||
                  row.entry_time === ""
                ) {
                  return `-`;
                } else {
                  return moment(String(row.entry_time)).format("YYYY/MM/DD");
                }
              }
            },*/
            { data: "user_id" },
            { data: "price_in_usd" },
            { data: "currency_price" },
            { data: "rec_amt" },
            
            {
              render: function(data, type, row, meta) {
              
                  return row.currency_price - row.rec_amt;
                
              }
            },
            {
              render: function(data, type, row, meta) {
              
                  return `<a href="https://www.blockchain.com/btc/address/${row.address}" target="_blank">${row.address}</a>`
                
              }
            },
            {
              render: function(data, type, row, meta) {
                if (
                  row.entry_time === null ||
                  row.entry_time === undefined ||
                  row.entry_time === ""
                ) {
                  return `-`;
                } else {
                  return moment(String(row.entry_time)).format("YYYY/MM/DD");
                }
              }
            },
            {
              render: function(data, type, row, meta) {
              
                  return `<label class="btn btn-primary sub"  data-inv=${row.invoice_id} data-amt="${row.price_in_usd}">Pay/Check Now<label>`;
                
              }
            },
          ]
        });

          $('#pending-fund-report tbody').on('click', '.sub', function () {

               that.purchasePackage(1,'BTC',$(this).data('amt'));
                // $('#allCheck').prop('checked', false);
                // if ($(this).is(':checked')) {
                //   that.arrayForSelectedCheckbox.push($(this).data('id'));
                // } else if (!$(this).is(':checked')) {
                //   that.arrayForSelectedCheckbox.splice(that.arrayForSelectedCheckbox.indexOf($(this).data('id')), 1);
                // }
            });



      }, 0);
    },
       purchasePackage(product_id, currency_code,hash_unit){
           // alert(hash_unit);
           // return false;
            $(".overlay").show();
            $(".loader").show();
            if(currency_code != 'BTC')
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
            {
              
              axios.post('getaddress', {
                 product_id: product_id,
                 currency_code: currency_code,
                 hash_unit: hash_unit,
              })
              .then(resp => {
                if(resp.data.code === 200){
                  this.getpackagedetails = resp.data.data;
                  this.getpackagedetails.rem_amount = this.getpackagedetails.price_in_currency;
                  //this.qrcodevalue = this.getpackagedetails.address;
                  this.qrcodevalue = "bitcoin:"+this.getpackagedetails.address+"?amount="+this.getpackagedetails.rem_amount;
  
  
                   this.getOneMinInterval();
                   this.changemodal = 0;
                  $('#demo-default-modal').modal();
                } else {
                  this.$toaster.error(resp.data.message);
                }
              })
              .catch(error => {
              });
            }
                      
           },
           getOneMinInterval() {
             $(".pendingDeposit").html('');
             clearInterval(this.OneMinTime);
             this.secondCount = 3;
              this.OneMinTime = setInterval(() => {
               this.secondCount = this.secondCount - 1;
              if (this.secondCount < 10 && this.secondCount >= 0) {
                  this.secondCount = `0${this.secondCount}`;
                } else if (this.secondCount < 0) {
                    this.secondCount = 30;
                  axios.post('fetchAddressBalance', {
                  address: this.getpackagedetails.address,
                   invoice_id: this.getpackagedetails.invoice_id.invoice_id
              })
              .then(response => {
                     if(response.data.code == 200){
                            // alert(response.data.data);
                             var messageData = response.data.message;
                             
                              if(response.data.data.status == 1)
                              {
                                //clearInterval(this.OneMinTime); 
                                 $('.counter').addClass('text-success');
                                $(".counter").show();
                                 $(".qrclass").html('');
                                  $(".pendingDeposit").html('');
                                $(".pendingDeposit").html(messageData);
                                this.confirmDeposit(this.getpackagedetails.invoice_id.invoice_id); 
                              }else if(response.data.data.status == 2){
                                 //  clearInterval(this.OneMinTime); 
                                  $('.counter').addClass('text-danger');
                                  $(".counter").show();
                                   $(".qrclass").html('');
                                    $(".pendingDeposit").html('');
                                $(".pendingDeposit").html(messageData);

                                    
                              }

                               this.getpackagedetails.received_amount = response.data.data.rec;
                               if((this.getpackagedetails.price_in_currency - this.getpackagedetails.received_amount) > 0){
                                    this.getpackagedetails.rem_amount = this.getpackagedetails.price_in_currency - this.getpackagedetails.received_amount;
                               }else{
                                    this.getpackagedetails.rem_amount = 0;
                               }
                                



                              
                          } else {
                             this.$toaster.error(response.data.message);
                             this.disablebtn = false;   
                          }
                  
                  
              })
              
          //this.postServiceCall(params, 'fetchAddressBalance');
            this.secondCount = 30;
          } else {
        }
      } ,1000);
    },
           closeModal(){
              $(".overlay").hide();
              $(".loader").hide();
           },
           closeModal1(){
               $('#demo-default-modal').modal('hide');
           },
           showreport(){
               $('#demo-default-modal').modal('hide');
              this.$router.push({name:'add-fund-report'});
           },
    //         confirmDeposit(invoice_id) {
  
    //          clearInterval(this.OneMinTime);
    //          this.secondCount = 3;
    //           this.OneMinTime = setInterval(() => {
    //            this.secondCount = this.secondCount - 1;
    //           if (this.secondCount < 10 && this.secondCount >= 0) {
    //               this.secondCount = `0${this.secondCount}`;
    //             } else if (this.secondCount < 0) {
    //                 this.secondCount = 30;
    //               axios.post('confirm-deposit', {
    //                invoice_id:invoice_id
    //           })
    //           .then(response => {
    //                  if(response.data.code == 200){
    //                         this.changemodal = 1;
    //                         clearInterval(this.OneMinTime); 
    //                         $('.counter').addClass('text-success');
    //                         $(".counter").hide();
    //                       } else {
    //                          this.changemodal = 0;
    //                       }
                  
                  
    //           })
              
    //       //this.postServiceCall(params, 'fetchAddressBalance');
    //         this.secondCount = 30;
    //       } else {
    //     }
    //   }, 1000);
    // },

           confirmDeposit(invoice_id){
            //alert();
            axios.post('confirm-deposit',{
                invoice_id:invoice_id
            }).then(resp => {
                if(resp.data.data.ret == 1){

                    this.changemodal = 1;
                    clearInterval(this.OneMinTime); 
                    $('.counter').addClass('text-success');
                    $(".counter").hide();
                } else {
                    this.changemodal = 0;
                }
              })
              .catch(error => {
              });
           }
  }
};
</script>

