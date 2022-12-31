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
                Airdrops Report
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
                    <h4 class="card-title">Airdrops Report</h4>
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
                                <!-- <th>Full Name</th>> -->
                                <th>Amount</th>
                                <th>Total usd</th>
                                <th>Entry Time</th>
                                <th>Current Time</th>
                                <th>Minutes</th>
                                <th>Dexd</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th>Sr No</th>
                                <th>User ID</th>
                                <!-- <th>Full Name</th>> -->
                                <th>Amount</th>
                                <th>Total usd</th>
                                <th>Entry Time</th>
                                <th>Current Time</th>
                                <th>Minutes</th>
                                <th>Dexd</th>
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
    };
  },
  mounted() {
    this.getPackages();
    this.getearnreport();
  },
  methods: {
    getPackages() {
      axios
        .get("get-packages", {})
        .then((response) => {
          this.INR = response.data.data[0]["convert"];
          this.type = response.data.data[0]["type"];
        })
        .catch((error) => {});
    },

    getearnreport() {
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
            url: apiUserHost + "getearnreport",
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

            /*{
              render: function(data, type, row, meta) {
                return `<span>${row.fullname}</span>`;
              }
            },*/

            {
              render: function (data, type, row, meta) {
                return `<span>$${row.amount}</span>`;
              },
            },

            // { data: "total_usd" },

            {
              render: function (data, type, row, meta) {
                var tot = row.total_usd;
                if (row.topup_time_in_minut >= "2021-06-11 00:00:00") {
                  return tot / 2;
                } else {
                  return parseFloat(tot).toFixed(3);
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
                  return moment(String(row.entry_time)).format(
                    "DD/MM/YYYY - HH:mm"
                  );
                }
              },
            },

            {
              render: function (data, type, row, meta) {
                var server_time = new Date(row.server_time_in_minute);
                return moment(String(server_time)).format("DD/MM/YYYY - HH:mm");
              },
            },

            {
              render: function (data, type, row, meta) {
                var server_time_in_minute = new Date(row.server_time_in_minute);
                var topup_time_in_minut = new Date(row.topup_time_in_minut);
                var diff = server_time_in_minute - topup_time_in_minut;
                var totalSeconds = diff / 1000;
                return parseFloat(totalSeconds / 60).toFixed(0);
              },
            },

            // {
            //   render: function(data, type, row, meta) {
            //     var zero = 0;
            //     // alert(zero);
            //     var server_time_in_minute = new Date(row.server_time_in_minute);
            //     var topup_time_in_minut = new Date(row.topup_time_in_minut);
            //     var diff =(server_time_in_minute)-(topup_time_in_minut);
            //     var totalSeconds = diff/1000;
            //     if(row.topup_time_in_minut >= "2021-06-11 00:00:00")
            //     {
            //       return zero;
            //     }
            //     else{
            //      return parseFloat(totalSeconds/60).toFixed(0);
            //     }

            //   }
            // },

            //  {
            //     render: function(data, type, row, meta) {
            //       var server_time_in_minute = new Date(row.server_time_in_minute);
            //       var topup_time_in_minut = new Date(row.topup_time_in_minut);
            //       var diff =(server_time_in_minute)-(topup_time_in_minut);
            //       var totalSeconds = diff/1000;
            //       var minutes = totalSeconds/60;
            //       return parseFloat(minutes*0.001).toFixed(3);
            //     }
            //   },

            {
              render: function (data, type, row, meta) {
                var server_time_in_minute = new Date(row.server_time_in_minute);
                var topup_time_in_minut = new Date(row.topup_time_in_minut);
                var diff = server_time_in_minute - topup_time_in_minut;
                var totalSeconds = diff / 1000;
                var minutes = parseFloat(totalSeconds / 60).toFixed(0);
                if (row.topup_time_in_minut >= "2021-08-01 00:00:00") {
                  return parseFloat(minutes * 0.00025).toFixed(4);
                } else if (row.topup_time_in_minut >= "2021-06-11 00:00:00") {
                  return parseFloat(minutes * 0.0005).toFixed(4);
                } else {
                  return parseFloat(minutes * 0.001).toFixed(4);
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
