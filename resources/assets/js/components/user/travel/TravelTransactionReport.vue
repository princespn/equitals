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
              <h2 class="content-header-title float-left mb-0">Travel Transaction Report</h2>
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
                  <!-- <h4 class="card-title">Direct Referral Income Report</h4> -->
                </div>
                <div class="card-content">
                  <div class="card-body card-dashboard">
                    <div class="table-responsive">
                      
                      <table
                        id="voucherTransaction1"
                        class="table table-striped table-bordered complex-headers"
                      >
                        <thead> 
                          <tr>
                            <th>Sr No</th>
                            <th>USD Dr</th>
<!--                             <th>Coin Dr </th>
 -->                            <th>USD Cr</th>
                            <!-- <th>Coin Cr</th> -->
                            <th>Status</th>
                            <th>Transaction Type</th>
                            <th>Date</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                          <tr>
                            <th>Sr No</th>
                          <th>USD Dr</th>
                            <!-- <th>Coin Dr </th> -->
                            <th>USD Cr</th>
                            <!-- <th>Coin Cr</th> -->
                             <th>Status</th>
                            <th>Transaction Type</th>
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
import { apiUserHost } from "../../../user-config/config";
export default {
  components: {
    
  },
  data() {
    return {
      INR: ""
    };
  },
  mounted() {
    this.getRoi();
    //this.getProjectSetting();
    //  this.getPackages();
  },
  methods: {
    getPackages() {
      axios
        .get("get-packages", {})
        .then(response => {
          this.INR = response.data.data[0]["convert"];
          this.type = response.data.data[0]["type"];
        })
        .catch(error => {});
    },
    getRoi() {
      let i = 0;
      let that = this;
      let token = localStorage.getItem("user-token");
      //var lang = "Chinese";
      setTimeout(function() {
        const table = $("#voucherTransaction1").DataTable({
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
          /* "language": {
                            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/"+ lang +".json"
                        },*/
          ajax: {
            url: apiUserHost + "travelTransactionInof",
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
                return `<span>$${row.debit}</span>`;
              }
            },
           /* {
              render: function(data, type, row, meta) {
                return `<span> WOZ ${row.debit_coin}</span>`;
              }
            },*/
             {
              render: function(data, type, row, meta) {
                return `<span>$${row.credit}</span>`;
              }
            },
           /* {
              render: function(data, type, row, meta) {
                return `<span> WOZ ${row.credit_coin}</span>`;
              }
            },*/
           

            {
              render: function(data, type, row, meta) {
                if (row.status == 0) {
                  return `<span class="label label-danger">Pending</span>`;
                } else if (row.status == 1) {
                  return `<span class="label label-success">Confirmed</span>`;
                } else {
                  return `<span class="label label-success">Refund</span>`;
                }
              }
            },//-- 
            {
              render: function(data, type, row, meta) {
                
                  return row.network_type;
               
              }
            },
            /*{ data: 'amount' },*/
            /*{ data: 'on_amount' },*/
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
            }
          ]
        });
      }, 0);
    }
  }
};
</script>