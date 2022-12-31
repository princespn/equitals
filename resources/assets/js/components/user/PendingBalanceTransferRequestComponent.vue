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
                Pending Balance Receive Report
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
                    <h4 class="card-title">Pending Balance Receive Report</h4>
                  </div>

                  <div class="card-body">
                    <form id="searchForm">
                      <div class="row">
                        <div class="col-6">
                          <label>Date Range</label>
                          <div>
                            <div
                              class="input-daterange input-group"
                              data-date-format="dd M, yyyy"
                              data-date-autoclose="true"
                              data-provide="datepicker"
                            >
                              <input
                                type="text"
                                class="form-control"
                                name="start"
                                id="from-date"
                                placeholder="From"
                              />
                              <input
                                type="text"
                                class="form-control"
                                name="end"
                                id="to-date"
                                placeholder="To"
                              />
                            </div>
                          </div>
                        </div>

                        <div class="col-6 col-md-3">
                          <label for="user-id">User Id</label>
                          <input
                            placeholder="Enter User Id"
                            id="user-id"
                            type="text"
                            class="form-control"
                          />
                        </div>
                        <div class="col-md-3 col-6 pt-2">
                          <div class="button-items button_submit">
                        <button
                          type="button"
                          name="signup1"
                          value="Sign up"
                          id="onSearchClick"
                          class="btn btn-primary waves-effect waves-light"
                        >
                          Search
                        </button>
                        <button
                          type="button"
                          name="signup1"
                          value="Sign up"
                          id="onResetClick"
                          class="btn btn-danger waves-effect waves-light"
                        >
                          Reset
                        </button>
                      </div>
                        </div>
                      </div>
                      
                    </form>
                  </div>

                  <div class="row">
                    <div class="col-12">
                      <div class="card">
                        <div class="card-body">
                          <table
                            id="pending-transfer-balance-report"
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
                                <th>Email</th>
                                <th>Amount</th>
                                <th>Date</th>
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
      transfer_btn: false,
    };
  },
  computed: {},
  mounted() {
    this.getTopupReport();
  },
  methods: {
    getTopupReport() {
      let i = 0;
      let that = this;
      let token = localStorage.getItem("user-token");
      setTimeout(function () {
        that.table = $("#pending-transfer-balance-report").DataTable({
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
          ordering: false,
          dom: "lrtip",
          ajax: {
            url: apiUserHost + "pending-transfer-balance-request",
            type: "POST",
            data: function (d) {
              i = 0;
              i = d.start + 1;

              let params = {
                user_id: $("#user-id").val(),
                frm_date: $("#from-date").val(),
                to_date: $("#to-date").val(),
                type: "transfer",
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
              "defaultContent": "",
              render: function (data, type, row, meta) {
                return meta.row + 1;
              },
            },
            {
              "defaultContent": "",
              render: function (data, type, row, meta) {
                return `<span>${row.user_id}</span>`;
              },
            },
            {
              "defaultContent": "",
              render: function (data, type, row, meta) {
                return row.name /* + ' ( ' + row.package_type + ' ) ' */;
              },
            },
            {
              "defaultContent": "",
              render: function (data, type, row, meta) {
                return row.email /* + ' ( ' + row.package_type + ' ) ' */;
              },
            },
            {
              "defaultContent": "",
              render: function (data, type, row, meta) {
                return `<span>$${row.amount}</span>  `;
              },
            },
            {
              "defaultContent": "",
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
        $("#onSearchClick").click(function () {
          that.table.ajax.reload();
        });
        $("#onResetClick").click(function () {
          $("#searchForm").trigger("reset");
          that.table.ajax.reload();
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
  },
};
</script>
