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
              <h2 class="content-header-title float-left mb-0">Transfer Purchase Wallet Report</h2>
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
                  <!-- <h4 class="card-title">ROI Income Report</h4> -->
                </div>
                <div class="card-content">
                  <div class="card-body card-dashboard">
                    <div class="table-responsive">
                      <table
                        id="topup-transfer-report"
                        class="table table-striped table-bordered complex-headers"
                      >
                        <thead>
                          <tr>
                            <th>Sr No</th>
                            <th>Date</th>
                            <th>N/W Type</th>
                            <th>Credit</th>
                            <th>Debit</th>
                            <th>Previous Balance</th>
                            <th>Final Balance</th>
                            <th>Narration</th>
                            <th>Category</th>
                            <th>Status</th>
                          </tr>
                        </thead>
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
    return {};
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
        const table = $("#topup-transfer-report").DataTable({
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
            url: apiUserHost + "fund-transfer-report",
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
            { data: "network_type" },
            { data: "credit" },
            { data: "debit" },
            { data: "prev_balance" },
            { data: "final_balance" },
            { data: "remarks" },
            { data: "type" },
            {
              render: function(data, type, row, meta) {
                if (row.fund_status === "Credit") {
                  return `<span class="label label-success">Credit</span>`;
                } else {
                  return `<span class="label label-danger">Debit</span>`;
                }
              }
            }
          ]
        });
      }, 0);
    }
  }
};
</script>