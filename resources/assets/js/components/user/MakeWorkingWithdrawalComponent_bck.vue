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
              <h2 class="content-header-title float-left mb-0">Dex Wallet</h2>
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
                    <h4 class="card-title">Working wallet Withdrawal</h4>
                  </div>

                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="reward-income-bg-amt">
                         <span class="card-title">Working Wallet Balance, USD</span>
                          <h2 class="mb-1 font-bold text-white card-title text-primary">
                            ${{ working_balance }}
                          </h2>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <p>Transaction Type</p>
                        <p>
                          <input
                            type="radio"
                            name="mode"
                            id="mode"
                            v-model="mode"
                            :value="'transferToTopup'"
                          />
                          <span>Transfer To Purchase</span>
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <input
                            type="radio"
                            name="mode"
                            id="mode"
                            v-model="mode"
                            :value="'withdraw'"
                            :selected="true"
                          />
                          <span>Withdraw</span>
                        </p>
                      </div>

                      <div class="col-md-12" v-if="mode === 'withdraw'">
                        <p>Currency Type</p>

                        <select v-model="Currency_type" class="form-control">
                          <option
                            v-for="cur in currency_code"
                            :value="cur.currency_code"
                          >
                            {{ cur.currency_name }} ( {{ cur.currency_code }} )
                          </option>
                        </select>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group">
                          <!-- v-if="mode === 'withdraw'" -->
                          <br />
                          <p>Enter The Available Amount (USD)</p>
                          <input
                            type="number"
                            min="20"
                            step="10"
                            id="set-working-wallet"
                            name="Amount"
                            v-model="set_working_wallet"
                            class="
                              form-control
                              blue-back
                              W-a-xs
                              {
                              error:
                              errors.has('set-working-wallet')
                              }
                            "
                            formcontrolname="set-working-wallet"
                            placeholder="Enter Amount"
                            v-validate="'required|numeric'"
                            onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57"
                          />
                          <div class="tooltip2" v-show="errors.has('Amount')">
                            <div class="tooltip-inner">
                              <span v-show="errors.has('Amount')">{{
                                errors.first("Amount")
                              }}</span>
                            </div>
                          </div>
                        </div>
                        <div v-if="mode === 'withdraw'" class="form-group">
                          <p>Deduction</p>
                          <input
                            type="text"
                            name="deduction"
                            v-model="deduction"
                            class="form-control"
                            disabled
                            readonly
                          />
                        </div>

                        <!-- transfer to topup wallet -->
                        <!-- <div v-if="mode === 'transferToTopup'" class="form-group">
                          <br>
                          <p>Enter The Available Amount (USD)</p>
                          <input type="number" min="1" step="1" id="set-topup-wallet" name="set-topup-wallet" v-model="addTopup" class="form-control blue-back W-a-xs { error: errors.has('set-topup-wallet') }" formcontrolname="set-topup-wallet" placeholder="Enter Amount" v-validate="'required|numeric'" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57">
                            <div class="tooltip2" v-show="errors.has('set-topup-wallet')">
                              <div class="tooltip-inner">
                                <span v-show="errors.has('set-topup-wallet')" >{{ errors.first('set-topup-wallet') }}</span>
                              </div>
                            </div>
                          </div> -->
                        <p v-if="!isValid">
                          <span class="error-msg-size tooltip-inner">{{
                            this.usermsg
                          }}</span>
                        </p>
                      </div>
                    </div>

<br>
                    <div class="row">
                      <div v-if="day != 4" class="col-md-6 mt-30">
                        <span class="text-danger">
                          Withdrawal will Allowed only on Thursday
                        </span>
                      </div>
                      <div v-if="(day = 4)" class="col-md-2 mt-30">
                        <button
                          v-if="mode == 'withdraw'"
                          style="width: 150px"
                          class="btn btn-primary waves-effect waves-light"
                          type="button"
                          @click.prevent="sendOTP()"
                          :disabled="
                            !isCompleteWorkingWithdrawal ||
                            !isValid ||
                            errors.any() ||
                            withdrawBtn == true
                          "
                        >
                          Withdraw
                        </button>

                        <button
                          v-if="mode == 'transferToTopup'"
                          style="width: 150px"
                          class="btn btn-primary waves-effect waves-light"
                          type="button"
                          @click.prevent="sendOTP()"
                          :disabled="
                            !isCompleteWorkingWithdrawal ||
                            !isValid ||
                            errors.any() ||
                            withdrawBtn == true
                          "
                        >
                          Transfer
                        </button>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-12">
                      <div class="card">
                        <div class="card-body">
                          <table
                            id="withdraw-income-report"
                            class="
                              table table-striped table-bordered
                              complex-headers
                            "
                          >
                            <thead>
                              <tr>
                                <th>Sr No</th>
                                <th>Amount</th>
                                <th>Deduction</th>
                                <th>Net Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th>Sr No</th>
                                <th>Amount</th>
                                <th>Deduction</th>
                                <th>Net Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                              </tr>
                            </tfoot>
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
      day: new Date().getDay(),
      workingwithdrawal: {
        topup_wallet: 0,
        working_wallet: 0,
        working_wallet_withdraw: 0,
        working_Wallet_balance: 0,
        working_Wallet_balancenew: 0,
        btc_address: "",
      },
      working_balance: 0,
      Currency_type: "TRX",

      set_working_wallet: 0,
      addTopup: 0,
      mode: "transferToTopup",
      isValid: true,
      usermsg: "",
      otp: "",
      withdrawBtn: false,
      currency_code: [],
      otpSent: false,
      optVerified: false,
      deduction: "20%",
    };
  },
  computed: {
    isCompleteWorkingWithdrawal() {
      return this.set_working_wallet || this.addTopup;
    },
  },
  mounted() {
    this.getAllCurrency();
    this.getWorkingWithdrawal();
    this.day;
    this.getRoi();
  },
  methods: {
    getWorkingWithdrawal() {
      axios
        .get("get-working-balance", {})
        .then((response) => {
          console.log(response)
          if (response.data.code == 200) {
            this.working_balance =
              typeof response.data.data == Object ? 0 : response.data.data;
          }
        })
        .catch((error) => {});
    },
    hashvalidation() {
      this.isValid = false;

      if (this.set_working_wallet % 10 != 0) {
        this.usermsg = "Amount must be multiples of 10";
        this.isValid = false;
      } else {
        this.isValid = true;
        this.usermsg = "";
      }
      this.addTopup = 0;
    },

    hashValidationTopup() {
      this.isValid = false;

      if (this.addTopup == 0) {
        this.usermsg = "Amount must be multiples of 1";
        this.isValid = false;
      } else {
        this.isValid = true;
        this.usermsg = "";
        this.$validator.reset();
      }
      this.set_working_wallet = 0;
    },
    getAllCurrency() {
      axios
        .post("getall-currency", {
          withdrwal_status: 1,
        })
        .then((response) => {
          this.currency_code = response.data.data;
          this.Currency_type = "TRX";
        })
        .catch((error) => {});
    },
    updateWorkingWithdrawal() {
      Swal({
        title: "Are you sure?",
        text: `You want to update this user`,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
      }).then((result) => {
        if (result.value) {
          axios
            .post("withdraw-income-otp", {
              level_income_balance: 0,
              direct_income_balance: 0,
              roi_balance: 0,
              binary_income_balance: 0,
              topup_wallet: 0,
              addTopup: this.addTopup,
              transfer_wallet: 0,
              working_wallet: this.set_working_wallet,
              mode: this.mode,
            })
            .then((response) => {
              if (response.data.code == 200) {
                this.$toaster.success(response.data.message);
                $("#editBankDetailsmodal").modal("show");
              } else {
                this.$toaster.error(response.data.message);
              }
            });
        }
      });
    },
    withdrawsucess() {
      this.withdrawBtn = true;
      axios
        .post("withdraw-income", {
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
        })
        .then((response) => {
          this.withdrawBtn = false;
          if (response.data.code == 200) {
            this.$toaster.success(response.data.message);

            $("#editBankDetailsmodal").modal("hide");

            this.$router.push({ path: "withdrawals-income-report" });
          } else {
            this.$toaster.error(response.data.message);
            this.otp = "";
          }
        });
    },

    TransferToPurchase() {
      this.withdrawBtn = true;
      axios
        .post("working-to-purchase-self-transfer", {
          amount: Number(this.set_working_wallet),
        })
        .then((response) => {
          this.withdrawBtn = false;
          if (response.data.code == 200) {
            this.$toaster.success(response.data.message);

            $("#editBankDetailsmodal").modal("hide");

            this.$router.push({ path: "dex-to-purchase-report" });
          } else {
            this.$toaster.error(response.data.message);
          }
        });
    },

    sendOTP() {
      var arr = {};
      if (this.mode == "withdraw") {
        arr = { type: "Withdrawal" };
      } else {
        arr = { type: "transfer" };
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
            if (this.mode == "withdraw") {
              this.withdrawsucess();
            } else if (this.mode == "transferToTopup") {
              this.TransferToPurchase();
            }
          } else {
            this.$toaster.error(response.data.message);
          }
        })
        .catch((error) => {
          this.message = "";
        });
    },

    getRoi() {
      let i = 0;
      let that = this;
      let token = localStorage.getItem("user-token");
      setTimeout(function () {
        const table = $("#withdraw-income-report").DataTable({
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
          ajax: {
            url: apiUserHost + "withdrwal-income",
            type: "POST",
            data: function (d) {
              i = 0;
              i = d.start + 1;

              let params = {};
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
                return i++;
              },
            },

            {
              render: function (data, type, row, meta) {
                return `<span>$${row.amount + row.deduction}</span>`;
              },
            },
            {
              render: function (data, type, row, meta) {
                return `<span>$${row.deduction}</span>`;
              },
            },
            {
              render: function (data, type, row, meta) {
                return `<span>$${row.amount}</span>`;
              },
            },

            {
              render: function (data, type, row, meta) {
                if (row.status == 0) {
                  return `<span class="label text-warning">Pending</span>`;
                } else if (row.status == 1) {
                  return `<span class="label text-success">Confirmed</span>`;
                } else if (row.status == 2) {
                  return `<span class="label text-danger">Rejected</span>`;
                } else {
                  return ``;
                }
              },
            },
            {
              render: function (data, type, row, meta) {
                if (
                  row.entry_time === null ||
                  row.entry_time === undefined ||
                  row.entry_time === ""
                ) {
                  return `-`;
                } else {
                  return moment(String(row.entry_time)).format("YYYY/MM/DD");
                }
              },
            },
          ],
        });
      }, 0);
    },
  },
};
</script>