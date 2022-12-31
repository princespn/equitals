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
              <h2 class="content-header-title float-left mb-0">
                All ID Purchase Balance Receive
              </h2>
            </div>
          </div>
        </div>
        <div
          class="
            content-header-right
            text-md-right
            col-md-3 col-12
            d-md-block d-none
          "
        >
          <div class="form-group breadcrum-right">
            <div class="dropdown">
              <button
                class="
                  btn-icon btn btn-primary btn-round btn-sm
                  dropdown-toggle
                "
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
        </div>
      </div>

      <div class="content-body">
        <!-- Complex headers table -->
        <section id="headers">
          <div class="main-content">
            <div class="page-content">
              <div class="container-fluid">
                <div class="card card-payment">
                  <!-- <h4 class="card-title">Activation Report</h4> -->

                  <div class="card-header">
                    <h4 class="card-title">All ID Purchase Balance Receive</h4>
                  </div>

                  <div class="card-body">
                    <form on:submit="return false;">
                      <div class="row">
                        <div class="form-group breadcrum-right col-md-3">
                          <label>Total Balance</label>
                          <input
                            type="text"
                            class="form-control"
                            name="total_balance"
                            id="total_balance"
                            v-model="total_balance"
                            disabled
                            readonly
                          />
                        </div>
                        <!-- <div class="form-group breadcrum-right col-md-3">
                                    <label>Withdrawable Balance</label>
                                    <input type="text" class="form-control" name="withdraw_balance" id="withdraw_balance" v-model="withdraw_balance" disabled readonly>
                                  </div> -->
                        <div class="form-group breadcrum-right col-md-2">
                          <br />
                          <button
                            type="button"
                            class="btn btn-primary"
                            @click="sendOTP"
                            id="transfer_btn"
                            :disabled="transfer_btn"
                          >
                            <b>Receive Balance</b>
                          </button>
                        </div>
                        <div class="form-group breadcrum-right col-md-2">
                          <label>User ID</label>
                          <input
                            type="text"
                            class="form-control"
                            name="user_id"
                            id="user_id"
                            v-model="user_id"
                          />
                        </div>
                        <div class="form-group breadcrum-right col-md-2">
                          <br />
                          <button
                            type="button"
                            class="btn btn-primary"
                            name="search-box"
                            id="search-box"
                          >
                            <b>Search</b>
                          </button>
                        </div>
                      </div>
                    </form>
                  </div>

                  <div class="row">
                    <div class="col-12">
                      <div class="card">
                        <div class="card-body">
                          <table
                            id="purchase-balance-report"
                            class="
                              table table-striped table-bordered
                              complex-headers
                            "
                          >
                            <thead>
                              <tr>
                                <th>Sr.No</th>
                                <th>User Id</th>
                                <th>FullName</th>
                                <th>Amount</th>
                                <th>Withdrawable</th>
                              </tr>
                            </thead>

                            <!-- <tfoot>
                                    <tr>
                                      <th colspan="3">Total Amount</th>
                                      <th><span">0</span></th>
                                    </tr>
                                  </tfoot> -->
                          </table>
                        </div>
                      </div>
                    </div>
                    <!-- end col -->
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
import moment from "moment";
import { apiUserHost } from "../../user-config/config";
import Breadcrum from "./BreadcrumComponent.vue";
import Swal from "sweetalert2";
export default {
  components: {
    Breadcrum,
  },
  data() {
    return {
      total_balance: 0,
      withdraw_balance: 0,
      transfer_btn: false,
      otp: "",
      user_id: "",
    };
  },
  computed: {},
  mounted() {
    this.checkRequestExists();
    this.getTopupReport();
  },
  methods: {
    getTopupReport() {
      let i = 0;
      let that = this;
      let token = localStorage.getItem("user-token");
      setTimeout(function () {
        const table = $("#purchase-balance-report").DataTable({
          responsive: true,
          lengthMenu: [
            [10, 50, 100],
            [10, 50, 100],
          ],
          retrieve: true,
          destroy: true,
          processing: false,
          serverSide: true,
          responsive: true,
          stateSave: false,
          ordering: true,
          searching: false,
          bFilter: false,
          ajax: {
            url: apiUserHost + "all-id-purchase-balance-report",
            type: "POST",
            data: function (d) {
              i = 0;
              i = d.start + 1;

              let params = {
                // level_id : $('#level-id').val(),
                user_id: $("#user_id").val(),
              };
              Object.assign(d, params);
              return d;
            },
            headers: {
              Authorization: "Bearer " + token,
            },
            dataSrc: function (json) {
              if (json.code === 200) {
                that.arrGetHelp = json.data.records;
                json["draw"] = json.data.draw;
                json["recordsFiltered"] = json.data.recordsFiltered;
                json["recordsTotal"] = json.data.recordsTotal;
                that.total_balance = json.data.totalAmount;
                that.withdraw_balance = json.data.withdrawAmount;
                return json.data.records;
              } else {
                json["draw"] = 0;
                json["recordsFiltered"] = 0;
                json["recordsTotal"] = 0;
                return json;
              }
            },
          },
          columns: [
            {
              render: function (data, type, row, meta) {
                return meta.row + 1;
              },
            },
            {
              render: function (data, type, row, meta) {
                return `<span>${row.user_id}</span>`;
              },
            },
            {
              render: function (data, type, row, meta) {
                return row.name /* + ' ( ' + row.package_type + ' ) ' */;
              },
            },
            {
              render: function (data, type, row, meta) {
                return `<span>$${row.amount}</span>  `;
              },
            },
            {
              render: function (data, type, row, meta) {
                if (row.amount >= 1) {
                  return '<span class="text-success"><i class="fa fa-check"></i></span>';
                } else {
                  return '<span class="text-danger"><i class="fa fa-times"></i></span>';
                }
              },
            },
          ],
        });
        $("#search-box").click(function () {
          table.ajax.reload();
        });
      }, 0);
    },
    onViewClick(id, amount, currency, date1, franchise_id) {
      this.$router.push({
        name: "certificate",
        params: {
          amount: amount,
          currency: currency,
          user_id: id,
          date1: date1,
          franchise_id: franchise_id,
        },
      });
    },
    transfer_balance() {
      /*this.transfer_btn = true;*/
      axios
        .post("add-purchase-transfer-request", {
          amount: this.withdraw_balance,
        })
        .then((resp) => {
          if (resp.data.code == 200) {
            this.$toaster.success(resp.data.message);
            $("#editBankDetailsmodal").modal("hide");
            this.$router.push({ path: "pending-purchase-transfer-requests" });
          } else {
            $("#balance-report").DataTable().ajax.reload();
            this.transfer_btn = false;
            this.$toaster.error(resp.data.message);
          }
        })
        .catch((error) => {});
    },

    sendOTP() {
      axios
        .post("sendOtp-update-user-profile" /*{type:'balance_transfer'}*/)
        .then((response) => {
          if (response.data.code == 200) {
            //console.log(response);
            this.$toaster.success(response.data.message);
            //this.statedata=response.data.data.message;

            $("#editBankDetailsmodal").modal("show");
          } else {
            this.$toaster.error(response.data.message);
          }
        })
        .catch((error) => {});
    },
    verifyOtp() {
      axios
        .post("verify-user-otp", {
          otp: this.otp,
        })
        .then((response) => {
          if (response.data.code == 200) {
            this.otpVerified = true;
            this.$toaster.success(response.data.message);
            this.otpSent = true;
            this.optVerified = true;
            this.transfer_balance();
            //  this.mobileNotEditable = true;
            //  $("#whatsapp_no").prop("readonly",true);
          } else {
            this.$toaster.error(response.data.message);
          }
        })
        .catch((error) => {
          this.message = "";
        });
    },
    checkRequestExists() {
      axios
        .post("check-purchase-transfer-request-exists")
        .then((resp) => {
          if (resp.data.code == 200) {
            this.$toaster.error(resp.data.message);
            this.$router.push({ path: "pending-purchase-transfer-requests" });
          }
        })
        .catch((error) => {});
    },
  },
};
</script>
