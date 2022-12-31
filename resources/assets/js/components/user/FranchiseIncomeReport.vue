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
              <h2 class="content-header-title float-left mb-0">Franchise Income Report</h2>
            </div>
          </div>
        </div>
        <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
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
        </div>
      </div>
      <div class="content-body">
        <!-- Complex headers table -->
        <section id="headers">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <!-- <h4 class="card-title">Level Referral Income Report</h4> -->
                </div>
                <div class="card-content">
                  <div class="card-body card-dashboard">
                    <div class="table-responsive">
                      <table
                        id="franchise-income-report"
                        class="table table-striped table-bordered complex-headers"
                      >
                        <thead>
                          <tr>
                            <th>Sr No</th>
                            <th>User Id</th>
                            <th>Amount</th>
                            <th>Percentage</th>
                            <th>On Amount</th>
                             <th>User Type</th>
                            <th>Pin</th>
                            <th>Date</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                          <tr>
                            <th>Sr No</th>
                            <th>User Id</th>
                            <th>Amount</th>
                            <th>Percentage</th>
                            <th>On Amount</th>
                            <th>User Type</th>
                            <th>Pin</th>
                            <th>Date</th>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
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
    Breadcrum
  },
  data() {
    return {
      INR: "",
      type: "",
      levelincome: {
        level_balance: "",
        level_income_balance: "",
        wallet_id: 1
      },
      walletlists: {
        srno: "",
        name: ""
      }
    };
  },
  computed: {
    isCompleteLevelIncome() {
      return (
        this.levelincome.level_balance &&
        this.levelincome.level_income_balance &&
        this.levelincome.wallet_id
      );
    }
  },
  mounted() {
    this.getLevelBalance();
    this.getLevelIncome();
    this.getWalletList();
    // this.getProjectSetting();
    this.getPackages();
  },
  methods: {
    getWalletList() {
      axios
        .get("wallet-list", {})
        .then(response => {
          this.walletlists = response.data.data;
          //alert(this.walletlists);
        })
        .catch(error => {});
    },
    getPackages() {
      axios
        .get("get-packages", {})
        .then(response => {
          this.INR = response.data.data[0]["convert"];
          this.type = response.data.data[0]["type"];
        })
        .catch(error => {});
    },

    getLevelBalance() {
      axios
        .get("get-user-dashboard", {})
        .then(response => {
          this.levelincome.level_balance =
            response.data.data.level_income_balance;
        })
        .catch(error => {});
    },

    updateLevelIncome() {
      Swal({
        title: "Are you sure?",
        text: `You want to transfer the wallet`,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes"
      }).then(result => {
        if (result.value) {
          axios
            .post("transfer-to-wallet", {
              direct_income_balance: 0,
              roi_balance: 0,
              binary_income_balance: 0,
              leadership_income_balance: 0,
              level_income_balance: this.levelincome.level_income_balance,
              wallet_id: this.levelincome.wallet_id
            })
            .then(response => {
              if (response.data.code == 200) {
                this.$toaster.success(response.data.message);
                this.levelincome.level_income_balance = "";
                this.getLevelBalance();
                this.getWalletList();
              } else {
                this.$toaster.error(response.data.message);
              }
            });
        }
      });
    },

    getLevelIncome() {
      let i = 0;
      let that = this;
      let token = localStorage.getItem("user-token");
      setTimeout(function() {
        const table = $("#franchise-income-report").DataTable({
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
            url: apiUserHost + "franchise-income",
            type: "POST",
            data: function(d) {
              i = 0;
              i = d.start + 1;

              let params = {
                // level_id : $('#level-id').val(),
              };
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
            { data: "from_user_id" },
            /*{ data: 'from_fullname' },*/
            
            {
              render: function(data, type, row, meta) {
                if (that.type == "INR") {
                  return `<span>₹${parseFloat(row.amount).toFixed(2) *
                    parseFloat(that.INR).toFixed(2)}</span>`;
                } else {
                  return `<span>$${row.amount}</span>`;
                }
              }
            },
            { data: "percentage" },
            {
              render: function(data, type, row, meta) {
                that.type == "USD";
                if (that.type == "INR") {
                  return `<span>₹${parseFloat(row.on_amount).toFixed(2) *
                    parseFloat(that.INR).toFixed(2)}</span>`;
                } else {
                  return `<span>$${row.on_amount}</span>`;
                }
              }
            },

              {
                                render: function (data, type, row, meta) {
                                    if (row.percentage==3) {
                                      return `Franchise`;
                                    } else {
                                        return 'Master Franchise';
                                    }
                                }
                            },
            { data: 'pin' },
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
          ]
        });
        $("#search-box").click(function() {
          table.ajax.reload();
        });
        // $('#reset').click(function () {
        //     $('#user-id').val('');
        //     $('#from-date').val('');
        //     $('#to-date').val('');
        //     $('#position').val('');
        //     $('#search-form').trigger("reset");
        //     i = 0;
        //      table.ajax.reload();

        // });
      }, 0);
    }
  }
};
</script>