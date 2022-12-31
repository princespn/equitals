<template>
<div class="content-body">
   <div class="container-fluid">
      <div class="row page-titles">
         <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">My Profits</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Direct Members Profit</a></li>
         </ol>
      </div>
      <!-- row -->
      <div class="row">
         <div class="col-12">
            <div class="card lp">
               <div class="card-header">
                  <h4 class="card-title">Direct Earnings</h4>
               </div>             
               <div class="card-body">
                <form action="#" id="searchForm">
                     <div class="row">
                        <div class="col-12 col-md-10">
                           <div class="row rmb">
                              <div class="col-6 col-md-4">
                                 <label>From Date</label> 
                                 <div>
                                        <input
                                    type="date"
                                    class="form-control"
                                    name="start"
                                    placeholder="From"
                                    id="from-date"
                                  />
                                 </div>
                              </div>
                              <div class="col-6 col-md-4">
                                <label>To Date</label> 
                                 <div>
                                          <input
                                    type="date"
                                    class="form-control"
                                    name="start"
                                    placeholder="From"
                                    id="to-date"
                                  />

                                 </div>
                              </div>
                               <div class="col-12 col-md-4">
                                 <label for="user-id">User Id</label> <input placeholder="Enter User Id" id="user-id" type="text" class="form-control">
                              </div>

                              
                              <div class="col-12 col-md-12 pt-2 justify-content-center">
                                 <div class="button-items button_submit"><button type="button" id="onSearchClick" class="btn btn-success waves-effect waves-light">Search Now</button> <button type="button" id="onResetClick" class="btn btn-warning waves-effect waves-light">Reset Now</button></div>
                              </div>
                           </div>
                        </div>
                        <div class="col-12 col-md-2">
                           <img src="public/user_files/assets/images/search.png" class="img-fluid s-img">
                        </div>
                     </div>
                  </form>
                  </div>
               <hr>
               <div class="card-body">
                  <div class="table-responsive">
                     <table id="direct-income-report" class="display" style="min-width: 845px">
                        <thead>
                           <tr>
                               <th>Sr No</th>
                               <th>Date</th>
                              <th>User Id</th>
                              <th>Amount</th>                         
                              <th>Lapse</th>                         
                              <th>Remark</th>                         
                              <th>Status</th>
                           </tr>
                        </thead>
                        <tbody>
                        
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
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
    //  this.getLevelBalance();
    this.getLevelIncome();
    this.getWalletList();
    // this.getProjectSetting();
   // this.getPackages();
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
        const table = $("#direct-income-report").DataTable({
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
          ordering: false,
          dom: 'lrtip',
          ajax: {
            url: apiUserHost + "direct-income",
            type: "POST",
            data: function(d) {
              i = 0;
              i = d.start + 1;

              let params = {
                user_id: $("#user-id").val(),
                frm_date: $("#from-date").val(),
                to_date: $("#to-date").val(),
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
              "defaultContent": "",
              render: function(data, type, row, meta) {
                return i++;
              }
            },
            {
              "defaultContent": "",
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
            { "defaultContent": "",
              data: "from_user_id" },
            /*{ data: 'from_fullname' },*/
            
            /*{
              render: function(data, type, row, meta) {
                if (that.type == "INR") {
                  return `<span>₹${parseFloat(row.amount).toFixed(2) *
                    parseFloat(that.INR).toFixed(2)}</span>`;
                } else {
                  return `<span>$${row.amount}</span>`;
                }
              }
            },*/
            {
              "defaultContent": "",
              render: function(data, type, row, meta) {
                return `<span>$${row.amount}</span>`;
              }
            },
            {
              "defaultContent": "",
              render: function(data, type, row, meta) {
                return `<span>$${row.laps_amount}</span>`;
              }
            },
            { "defaultContent": "",
              data: "remark" 
            },
            /*{
              "defaultContent": "",
              render: function(data, type, row, meta) {
                return `<span>$${row.purchase_wallet_amount}</span>`;
              }
            },
            {
              "defaultContent": "",
              render: function(data, type, row, meta) {
                return `<span>$${row.working_wallet_amount}</span>`;
              }
            },
            
            /*{ data: 'amount' },*/
            {
              "defaultContent": "",
              render: function(data, type, row, meta) {
                if (row.status === "Paid") {
                  return `<span class="label label-success">Paid</span>`;
                } else {
                  return `<span class="label label-danger">Unpaid</span>`;
                }
              }
            }
          ]
        });
        $("#onSearchClick").click(function () {
          table.ajax.reload();
        });
        $("#onResetClick").click(function () {
          $("#searchForm").trigger("reset");
          table.ajax.reload();
        });
      }, 0);
    }
  }
};
</script>