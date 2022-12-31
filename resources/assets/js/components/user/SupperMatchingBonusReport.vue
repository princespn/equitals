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
                ROI Income Report
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
                    <h4 class="card-title">Super Matching Bonus</h4>
                  </div>

                  <div class="row">
                    <div class="col-12">
                      <div class="card">
                        <div class="card-body">
                          <table
                            id="roi-income-report"
                            class="
                              table table-striped table-bordered
                              complex-headers
                            "
                          >
                            <thead>
                              <tr>
                                <th>Sr No</th>
                                <th>User ID</th>
                                <th>Full Name</th>
                                <th>Pin</th>
                                <th>Rank</th>
                                <th>Amount</th>
                                <th>Date</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th>Sr No</th>
                                <th>User ID</th>
                                <th>Full Name</th>
                                <th>Pin</th>
                                <th>Rank</th>
                                <th>Amount</th>
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
export default {
  components: {
    Breadcrum,
  },
  data() {
    return {
      INR: "",
      supermatchingbonus: {
        rank: "",
        entry_time: "",
      },
    };
  },
  mounted() {
    this.getRoi();
    //this.getPackages();
    this.getsupermatchingbonusincome();
  },
  methods: {
    getsupermatchingbonusincome() {
      axios
        .get("get-supermatchingbonusdata", {})
        .then((response) => {
          this.supermatchingbonus.rank = response.data.data.rank;
          this.supermatchingbonus.entry_time = response.data.data.entry_time;
        })
        .catch((error) => {});
    },
    getPackages() {
      axios
        .get("get-packages", {})
        .then((response) => {
          this.INR = response.data.data[0]["convert"];
          this.type = response.data.data[0]["type"];
        })
        .catch((error) => {});
    },
    getRoi() {
      let i = 0;
      let that = this;
      let token = localStorage.getItem("user-token");
      setTimeout(function () {
        const table = $("#roi-income-report").DataTable({
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
            url: apiUserHost + "supper-macthing-bonus",
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
              console.log(json);
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
            { data: "user_id" },

            {
              render: function (data, type, row, meta) {
                return `<span>${row.fullname}</span>`;
              },
            },
            { data: "pin" },
            { data: "rank" },
            {
              render: function (data, type, row, meta) {
                return `<span>$${row.amount}</span>`;
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
            /*{
              render: function(data, type, row, meta) {
                if (row.status === "Paid") {
                  return `<span class="label label-success">Paid</span>`;
                } else {
                  return `<span class="label label-danger">Unpaid</span>`;
                }
              }
            }*/
          ],
        });
      }, 0);
    },
  },
};
</script>