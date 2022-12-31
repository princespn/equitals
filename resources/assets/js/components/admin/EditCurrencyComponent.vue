<template>
  <div class="content">
    <div class>
      <div class="page-header-title">
        <h4 class="page-title">Edit Currency</h4>
      </div>
    </div>

    <div class="page-content-wrapper">
      <div class="container">
        <div class="row">
          <div class="col-md-12 col-sm-3">
            <div class="panel panel-primary">
              <div class="panel-body add-new">
                <a
                  class="btn btn-primary waves-effect waves-light pull-right"
                  href="p4p4SGFkLUKjRNwrg82rJkzT6rs7XqX#/settings/dynamic-currency-setting"
                >
                  <i class="fa fa-mail-reply"></i> &nbsp;Back
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-12">
            <div class="panel panel-primary">
              <div class="panel-body">
                <h4 class="m-t-0 m-b-30">Currency Information</h4>
                <form
                  class="form-horizontal"
                  v-on:submit.prevent="onUpdateUserClick"
                >
                  <input type="hidden" name="id" v-model="editUser.id" />
                 

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">
                        Currency Name
                        <span class="madatoryStar text-danger">*</span>
                      </label>
                      <div class="col-md-7">
                        <input
                          name="currency_name"
                          class="form-control"
                          placeholder="Update Currency Name"
                          type="text"
                          v-model="editUser.currency_name"
                        />
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label
                        class="col-md-5 control-label"
                        for="example-mobile"
                      >
                        Currency Code
                        <span class="madatoryStar text-danger"> *</span>
                      </label>
                      <div class="col-md-7">
                        <input
                          name="currency_code"
                          class="form-control"
                          id="currency_code"
                          placeholder="Enter Currency Code"
                          required
                          type="text"
                          v-model="editUser.currency_code"
                        />
                      </div>
                    </div>
                  </div>

                 <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label" for="example-email">
                        Currency Status
                        <span class="madatoryStar text-danger">*</span>
                      </label>
                      <div class="col-md-7">
                      <select
                        name="currency_status"
                        class="form-control"
                        id="currency_status"
                        v-model="editUser.currency_status"
                      >
                        <option :value="0">InActive</option>
                        <option :value="1">Active</option>
                        <!--<option v-for="product_id in product_ids" v-bind:value="product_id.id" :key="product_id"> {{ product_id.name }}</option> -->
                      </select>
                    </div>
                     </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label" for="example-email">
                        Withdrwal Status
                        <span class="madatoryStar text-danger">*</span>
                      </label>
                      <div class="col-md-7">
                      <select
                        name="withdrwal_status"
                        class="form-control"
                        id="withdrawl_status"
                        v-model="editUser.withdrwal_status"
                      >
                        <option :value="0">InActive</option>
                        <option :value="1">Active</option>
                        <!--<option v-for="product_id in product_ids" v-bind:value="product_id.id" :key="product_id"> {{ product_id.name }}</option> -->
                      </select>
                    </div>
                    </div>
                  </div>

               <!--    <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label" for="example-email">
                        Coinpayment Status
                        <span class="madatoryStar text-danger">*</span>
                      </label>
                      <div class="col-md-7">
                      <select
                        name="coinpayment_status"
                        class="form-control"
                        id="coinpayment_status"
                        v-model="editUser.coinpayment_status"
                      >
                        <option :value="0">InActive</option>
                        <option :value="1">Active</option>
                        <option v-for="product_id in product_ids" v-bind:value="product_id.id" :key="product_id"> {{ product_id.name }}</option> -->
                     <!-- </select>
                    </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label" for="example-email">
                        Node API Status
                        <span class="madatoryStar text-danger">*</span>
                      </label>
                      <div class="col-md-7">
                      <select
                        name="node_Api_status"
                        class="form-control"
                        id="node_api_status"
                        v-model="editUser.node_api_status"
                      >
                        <option :value="0">InActive</option>
                        <option :value="1">Active</option>
                        <option v-for="product_id in product_ids" v-bind:value="product_id.id" :key="product_id"> {{ product_id.name }}</option> 
                      </select>
                    </div>
                    </div>
                  </div>-->

                 
                  <!--  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-5 control-label">Paypal Address</label>
                      <div class="col-md-7">
                        <input
                          class="form-control"
                          placeholder="Update Paypal Address"
                          type="text"
                          v-model="editUser.paypal_address"
                          v-validate="'email'"
                          name="paypal_address"
                        />

                        <div class="tooltip2" v-show="errors.has('paypal_address')">
                          <div class="tooltip-inner">
                            <span v-show="errors.has('paypal_address')">{{ errors.first('paypal_address') }}</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div> -->

                  <!--     <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="example-email"> Perfect Money Address
 </label>
                                            <div class="col-md-7">
                                                <input id="address" name="address" class="form-control" placeholder="Update address" type="text" v-model="editUser.perfect_money_address">
                                            </div>
                                        </div>
                                    </div> -->

                  <!--  
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"> Nominee Relation </label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update Nominee Name" type="text" v-model="editUser.relation">
                                            </div>
                                        </div>
                                    </div>                                    

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"> State </label>
                                            <div class="col-md-7">
                                                <select class="form-control" v-model="editUser.state">
                                                    <option selected="" value="">Select State</option>
                                                    <option :value="sta.name" v-for="sta in arrStates">{{sta.name}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="example-email"> City </label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update City" type="text" v-model="editUser.city">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="clearfix"></div>
                                    <hr>

                                    <h4 class="m-t-0 m-b-30"> Bank Information </h4>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"> Account No </label>
                                            <div class="col-md-7">
                                                <input class="form-control" name="account_no" placeholder="Update Account No" type="text" v-model="editUser.account_no">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"> A/C Name </label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update A/C Name" type="text" v-model="editUser.holder_name">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"> Bank Name </label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update Bank Name" type="text" v-model="editUser.bank_name">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="example-email"> Branch Name </label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update Branch Name" type="text" v-model="editUser.branch_name">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"> PAN Number </label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update PAN Number" type="text" v-model="editUser.pan_no">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 m-b-30">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="example-email">IFSC Code</label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update IFSC Code" type="text" v-model="editUser.ifsc_code">
                                            </div>
                                        </div>
                                    </div>

                  -->

                  <!--<div class="col-md-6">
                    <div class="">
                      <label class="col-md-5 control-label"> Country </label>
                      <div class="col-md-7">
                        <select class="form-control" v-model="editUser.country">
                          <option :value="null">Select</option>
                          <option
                            :value="cntry.iso_code"
                            v-for="cntry in arrCountry"
                          >
                            {{ cntry.country }}
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>-->

                  <!--  <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="example-email"> Address </label>
                                            <div class="col-md-7">
                                                <input id="address" name="address" class="form-control" placeholder="Update address" type="text" v-model="editUser.address">
                                            </div>
                                        </div>
                                    </div> -->

                  <!--  <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="example-email"> City </label>
                                            <div class="col-md-7">
                                                <input id="city" name="city" class="form-control" placeholder="Update City" type="text" v-model="editUser.city">
                                            </div>
                                        </div>
                                    </div>  -->

                  <div class="clearfix form-actions">
                    <div class="col-md-offset-5 col-md-6">
                      <div class="col-md-6">
                        <button
                          class="btn btn-info col-md-12"
                          name="submit"
                          type="submit"
                        >
                          <i class="ace-icon fa fa-check bigger-110"></i>Submit
                        </button>
                      </div>
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
import { apiAdminHost } from "./../../admin-config/config";
import Swal from "sweetalert2";
import DatePicker from "vuejs-datepicker";
import moment from "moment";

export default {
  data() {
    return {
      arrCountry: [],
      arrStates: [],
      editUser: {
        id: this.$route.params.id,
        currency_name: "",
        currency_code: "",
        currency_status: "",
        withdrwal_status: "",
        //coinpayment_status: "",
        //node_api_status: "",
        user_id: "",
        country: null,
        sponsor_id: "",
        fullname: "",
        mobile: "",
        email: "",
        btc_address: "",
        address: "",
        city: "",
        paypal_address: "",
        trn_address: "",
        ethereum: "",
        bnb_address: "",
        // relation: '',
        // contact_number: '',
        // state: '',
        // city: '',
        // account_no: '',
        // holder_name: '',
        // bank_name: '',
        // branch_name: '',
        // pan_no: '',
        // ifsc_code: '',
        // paytm_no: '',
        // payzapp_no: '',
        // insurance_amt: '',
      },
    };
  },
  components: {
    DatePicker,
  },
  computed: {},
  mounted() {
    this.getUserDetails();
    this.getCountry();
  },
  methods: {
    dateFormat(date) {
      return moment(date).format("DD-MM-YYYY");
    },
    getUserDetails() {
      axios
        .post("/getcurrencydetails", {
          id: this.$route.params.id,
        })
        .then((resp) => {
          if (resp.data.code === 200) {
            this.editUser = {
              id: resp.data.data.userProfile.id,
              currency_name: resp.data.data.userProfile.currency_name,
              currency_code: resp.data.data.userProfile.currency_code,
              currency_status: resp.data.data.userProfile.status,
              withdrwal_status: resp.data.data.userProfile.withdrwal_status,
          //    coinpayment_status: resp.data.data.userProfile.coinpayment_status,
            //  node_api_status: resp.data.data.userProfile.node_api_status,

              /*   paypal_address: resp.data.data.userProfile.paypal_address,
              perfect_money_address: resp.data.data.userProfile.perfect_money_address*/

              // relation: resp.data.data.userProfile.relation,
              // contact_number: resp.data.data.userProfile.contact_number,
              // state: resp.data.data.userProfile.state,
              // city: resp.data.data.userProfile.city,
              // account_no: resp.data.data.userProfile.account_no,
              // holder_name: resp.data.data.userProfile.holder_name,
              // bank_name: resp.data.data.userProfile.bank_name,
              // branch_name: resp.data.data.userProfile.branch_name,
              // pan_no: resp.data.data.userProfile.pan_no,
              // ifsc_code: resp.data.data.userProfile.ifsc_code,
            };
          } else {
            this.$toaster.error(resp.data.message);
          }
        })
        .catch((err) => {
          this.$toaster.error(err);
        });
    },
    onUpdateUserClick() {
      Swal({
        title: "Are you sure?",
        text: `You want to update this Currency`,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
      }).then((result) => {
        if (result.value) {
          axios
            .post("/updatecurrency", this.editUser)
            .then((resp) => {
              if (resp.data.code === 200) {
                this.$router.push({ name: "dynamic-currency-setting" });
                this.$toaster.success(resp.data.message);
              } else {
                this.$toaster.error(resp.data.message);
              }
            })
            .catch((err) => {
              this.$toaster.error(err.resp.data.message);
            });
        }
      });
    },

    getCountry() {
      axios
        .get("/getcountry")
        .then((resp) => {
          if (resp.data.code === 200) {
            this.arrCountry = resp.data.data;
          } else {
            this.$toaster.error(resp.data.message);
          }
        })
        .catch((err) => {
          this.$toaster.error(err);
        });
    },
    // onChangeCountry(countryName){
    //     axios.post('/getstatebycountry',{
    //         country:countryName
    //     }).then(resp => {
    //         if(resp.data.code === 200){
    //             this.arrStates = resp.data.data;
    //         } else {
    //             this.$toaster.error(resp.data.message);
    //         }
    //     }).catch(err => {
    //         this.$toaster.error(err);
    //     })
    // }
  },
};
</script>