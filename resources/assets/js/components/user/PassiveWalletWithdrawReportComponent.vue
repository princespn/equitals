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
              <h2 class="content-header-title float-left mb-0">Passive Income Withdraw Report</h2></h2>
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
                      
                      <table id="withdraw-passive-income-report" class="table table-striped table-bordered complex-headers">
                        <thead> 
                          <tr>
                            <th>Sr No</th>
                            <th>Amount</th>
                            <th>Deduction</th>
                            <th>Net Amount</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Currency Type</th>
                            <th>Date</th>
                            <th>Remark</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                          <tr>
                            <th>Sr No</th>
                            <th>Amount</th>
                            <th>Deduction</th>
                            <th>Net Amount</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Currency Type</th>
                            <th>Date</th>
                            <th>Remark</th>
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
        const table = $("#withdraw-passive-income-report").DataTable({
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
            url: apiUserHost + "passive-income-withdraw-list",
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
                return `<span>$${row.amount + row.deduction}</span>`;
              }
            },
            {
              render: function(data, type, row, meta) {
                return `<span>$${row.deduction}</span>`;
              }
            },
            {
              render: function(data, type, row, meta) {
                return `<span>$${row.amount}</span>`;
              }
            },
            {
              render: function(data, type, row, meta) {
                return `<a href="https://www.blockchain.com/btc/address/${row.to_address}">${row.to_address}</a>`;
              }
            },

            {
              render: function(data, type, row, meta) {
                if (row.status == 0) {
                  return `<span class="label text-warning">Pending</span>`;
                } else if (row.status == 1) {
                  return `<span class="label text-success">Confirmed</span>`;
                } else if (row.status == 2) {
                  return `<span class="label text-danger">Rejected</span>`;
                } else {
                  return ``;
                }
              }
            },//-- 
             {
              render: function(data, type, row, meta) {
                if (row.network_type == 'BTC') {
                  return `Bitcoin(BTC)`;
                } else if (row.network_type == 'TRX') {
                  return `TRON`;
                } else if (row.network_type === 'ETH') {
                  return `Ethereum(ETH)`;
                } else if (row.network_type === 'BNB.ERC20') {
                  return `Binance`;
                }else {
                  return ``;
                }
              }
            },
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
            },
            { data: 'remark' }
          ]
        });
      }, 0);
    }
  }
};
</script>