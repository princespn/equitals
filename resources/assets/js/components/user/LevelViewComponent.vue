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
              <h2 class="content-header-title float-left mb-0">Level View</h2>
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
                  <!-- <h4 class="card-title">Level View</h4> -->
                </div>
                <div class="card-content">
                  <div class="card-body card-dashboard">
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label class="control-label">From Date</label>
                            <br />
                            <input
                              type="text"
                              name="frm_date"
                              id="frm_date"
                              placeholder="From Date"
                              readonly="readonly"
                              autocomplete="off"
                              class="form-control"
                            />
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label class="control-label">To Date</label>
                            <br />
                            <input
                              type="text"
                              name="to_date"
                              id="to_date"
                              placeholder="To Date"
                              readonly="readonly"
                              autocomplete="off"
                              class="form-control"
                            />
                          </div>
                        </div>

                        <div class="col-md-3">
                          <div class="form-group">
                            <label class="control-label">User Id</label>
                            <br />
                            <input
                              placeholder="Enter User Id"
                              id="user-id"
                              type="text"
                              class="form-control"
                            />
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label class="control-label">Select Team</label>
                            <br />
                            <select id="position" class="form-control">
                              <option>All</option>
                              <option value="Left">Left Team</option>
                              <option value="Right">Right Team</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="panel-footer bg-gray text-center">
                        <button
                          type="button"
                          name="signup1"
                          value="Sign up"
                          id="search-box"
                          class="btn btn-primary"
                        >Search</button>
                        <button
                          type="button"
                          name="signup1"
                          value="Sign up"
                          id="reset"
                          class="btn btn-success"
                        >reset</button>
                      </div>
                    </div>
                    <div class="top-bordr3 m-t-30 m-b-30"></div>
                    <div class="col-12">
                      <div class="col-md-12">
                        <span id="left_id" class="badge badge-info">Left Id :31</span>
                        <span id="right_id" class="badge badge-info">Right Id :15</span>
                        <span id="left_bv" class="badge badge-success">Left BV :670000</span>
                        <span id="right_bv" class="badge badge-success">Right BV :550000</span>
                      </div>
                    </div>
                    <br />

                    <div class="table-responsive">
                      <table
                        id="level-view"
                        class="table table-striped table-bordered complex-headers"
                      >
                        <thead>
                          <tr>
                            <th>Sr No</th>
                            <th>User Id</th>
                            <th>Name</th>
                            <th>Sponsor Id</th>
                            <th class="min-tablet">Country</th>
                            <th class="min-tablet">Investments</th>
                            <th>Level</th>
                            <th>Registration Date</th>
                            <th>Status</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                          <tr>
                            <th>Sr No</th>
                            <th>User Id</th>
                            <th>Name</th>
                            <th>Sponsor Id</th>
                            <th class="min-tablet">Country</th>
                            <th class="min-tablet">Investments</th>
                            <th>Level</th>
                            <th>Registration Date</th>
                            <th>Status</th>
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
export default {
  components: {
    Breadcrum
  },
  data() {
    return {
      level_id: "",
      getlevels: {
        level_id: "",
        level_name: ""
      },
      selected: 1,
      INR: "",
      type: ""
    };
  },
  mounted() {
    this.getLevelView();
    this.getLevels();
    // this.getProjectSetting();
    //  this.getPackages();
  },
  methods: {
    getLevelView() {
      let i = 0;
      let that = this;
      let token = localStorage.getItem("user-token");
      setTimeout(function() {
        const table = $("#level-view").DataTable({
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
            url: apiUserHost + "level-view",
            type: "POST",
            data: function(d) {
              i = 0;
              i = d.start + 1;

              let params = {
                //level_id : $('#level-id').val(),
                level_id:
                  $("#level_id").val() != null ? $("#level_id").val() : "1"
              };
              Object.assign(d, params);
              return d;
            },
            headers: {
              Authorization: "Bearer " + token
            },
            dataSrc: function(json) {
              if (json.code === 200) {
                json["recordsFiltered"] = json.data.recordsFiltered;
                json["recordsTotal"] = json.data.recordsTotal;
                return json.data.records;
              } else {
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
            { data: "down_user_id" },
            { data: "down_fullname" },
            { data: "sponser_id" },
            { data: "country" },
            // {
            //   render: function(data, type, row, meta) {
            //     if (that.type == "INR") {
            //       return `<span>₹${row.total_investment * that.INR}</span>`;
            //     } else {
            //       return `<span>$${row.total_investment}</span>`;
            //     }
            //   }
            // },
            { data: "total_investment" },
            {
              render: function(data, type, row, meta) {
                return `<span>${row.level}</span>`;
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
                if (row.total_investment > 0) {
                  return `<span class="label label-primary">Paid</span>`;
                } else {
                  return `<span class="label label-danger">Unpaid</span>`;
                }
              }
            }
          ]
        });
        //table.ajax.reload();
        $("#level_id").change(function() {
          table.ajax.reload();
        });
      }, 0);
    },

    getLevels() {
      axios
        .get("get-level", {})
        .then(response => {
          this.getlevels = response.data.data;
          //alert(this.getlevels);
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
    }
  }
};
</script>

