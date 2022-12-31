<template>
  <!-- Start content -->
  <div class="content">
    <div class>
      <div class="page-header-title">
        <h4 class="page-title">Account Wallet Report</h4>
      </div>
    </div>

    <div class="page-content-wrapper">
      <div class="container">
        <form id="searchForm">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-primary">
                <div class="panel-body">
                  <div class>
                    <div class="col-md-3"></div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label>From Date</label>
                        <div class="input-group">
                          <DatePicker
                            :bootstrap-styling="true"
                            
                            name="frm_date"
                            :format="dateFormat"
                            placeholder="From Date"
                            id="frm_date"
                          ></DatePicker>
                          <span class="input-group-addon bg-custom b-0 datepicker_border">
                            <i class="mdi mdi-calendar"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label>To Date</label>
                        <div class="input-group">
                          <DatePicker
                            :bootstrap-styling="true"
                            
                            name="to_date"
                            :format="dateFormat"
                            placeholder="To Date"
                            id="to_date"
                          ></DatePicker>
                          <span class="input-group-addon bg-custom b-0 datepicker_border">
                            <i class="mdi mdi-calendar"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label>To User Id</label>
                        <input
                          class="form-control"
                          required
                          placeholder="Enter user id"
                          type="text"
                          id="to_user_id"
                        />
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="text-center">
                          <button
                            type="button"
                            class="btn btn-primary waves-effect waves-light"
                            id="onSearchClick"
                          >Search</button>
                          <button type="button" class="btn btn-info waves-effect waves-light" @click="exportToExcel">Export To Excel</button>
                          <button
                            type="button"
                            class="btn btn-dark waves-effect waves-light mt-4"
                            id="onResetClick"
                          >Reset</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- panel-body -->
              </div>
              <!-- panel -->
            </div>
            <!-- col -->
          </div>
        </form>
        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-primary">
              <div class="panel-body">
                <table
                  id="account-wallet-report"
                  class="table table-striped table-bordered dt-responsive"
                >
                  <thead>
                    <tr>
                      <th>Sr.No</th>
                      <th>User Id</th>
                     <!--  <th>Direct Income</th>
                      <th>Binary Income</th>
                      <th>ROI Income</th> -->
                      <th>Purchase Wallet Balance</th>
                     <!--  <th>Franchise Income</th> -->
                      <th>Total Income</th>
                      <th>Account Wallet Balance</th>
                      <th>Entry Date</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th></th>
                      <!-- <th></th>
                      <th></th>
                      <th></th> -->
                     <!--  <th></th> -->
                      <th>Total Balance</th>
                      <th id="topup" style="color:red"></th>
                      <th id="withdraw" style="color:red"></user_idth>
                      <th id="inv" style="color:red"></th>
                      <th></th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- end row -->

        <!-- <div class="modal fade" id="deposit-address-model">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" class="fa fa-times"></span>
                </button>
                <h5 class="modal-title" id="exampleModalLabel">More Details</h5>
              </div>
              <div class="modal-body">
                <div class="table-responsive">
                  <table
                    cellspacing="0"
                    class="table table-bordered table-striped"
                    id="order-listing"
                    width="100%"
                  >
                    <thead>
                      <tr>
                        <th>Confirmation Remark</th>
                        <th>Confirm Date</th>
                        <th>IP Address</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>{{ depositaddress.confirm_remark }}</td>
                        <td>{{ depositaddress.confirm_date }}</td>
                        <td>{{ depositaddress.ip_address }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>NaN
              </div>
            </div>
          </div>
        </div>-->
      </div>
      <!-- container -->
    </div>
    <!-- Page content Wrapper -->
  </div>
  <!-- content -->
</template>

<script>
import moment from "moment";
import { apiAdminHost } from "./../../admin-config/config";
import DatePicker from "vuejs-datepicker";
export default {
  data() {
    return {
      
      dashboard: {
        total_users: 0,
        total_unblock_users: 0,
        total_block_users: 0,
        total_active_user: 0,
        total_inactive_user: 0,
        total_investment: 0,
        total_deposite_admin: 0,
        total_deposite_btc: 0,
        total_deposite_wallet: 0,
        total_income: 0,
        total_direct_income: 0,
        total_binary_income: 0,
        total_level_income: 0,
        total_roi_income: 0,
        total_withdraw: 0,
        total_withdraw_pending: 0,
        total_withdraw_confirm: 0,
        total_balance: 0
      },
      user_id: "",
      frm_date: "",
      to_date: "",
      export_url:''
    };
  },
  components: {
    DatePicker
  },
  mounted() {
    this.getDashboardData();
  },
  methods: {
    dateFormat(date) {
      return moment(date).format("DD-MM-YYYY");
    },
    // productReport() {
    //   axios
    //     .post("getDashboardSummary", {
    //       frm_date:
    //         this.frm_date !== ""
    //           ? moment(this.frm_date).format("DD-MM-YYYY")
    //           : "",
    //       to_date:
    //         this.to_date !== ""
    //           ? moment(this.to_date).format("DD-MM-YYYY")
    //           : "",
    //       id: this.hiddenUserId,
    //       user_id: this.user_id
    //     })
    //     .then(response => {
    //       this.dashboard = response.data.data;
    //     })
    //     .catch(error => {
    //       this.message = error.response.data.message;
    //       this.flash(this.message, "error", {
    //         timeout: 500000
    //       });
    //     });
    // },
    getDashboardData() {
      let i = 0;
      let that = this;
      let token = localStorage.getItem("access_token");

      setTimeout(function() {
        that.table = $("#account-wallet-report").DataTable({
          responsive: true,
          retrieve: true,
          destroy: true,
          processing: false,
          serverSide: true,
          stateSave: false,
          ordering: false,
          dom: "Brtip",
          lengthMenu: [
            [10, 50, 100],
            [10, 50, 100]
          ],
          buttons: [
            // 'copyHtml5',
            /*"excelHtml5",
            "csvHtml5",
            "pdfHtml5",*/
            "pageLength"
          ],

          ajax: {
            url: apiAdminHost + "/getaccountwallet",
            type: "POST",
            data: function(d) {
              i = 0;
              i = d.start + 1;

              let params = {
                frm_date: $("#frm_date").val(),
                to_date: $("#to_date").val(),
                user_id: $("#to_user_id").val()
              };
              Object.assign(d, params);
              return d;
            },
            headers: {
              Authorization: "Bearer " + token
            },
            dataSrc: function(json) {
              if (json.code === 200) {
                $('#inv').html(json.data.total);
                $('#withdraw').html(json.data.balance);
                $('#topup').html(json.data.topup_bal);
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
                //return meta.row + 1;
                return i++;
              }
            },
            {
              render: function(data, type, row, meta) {
                return `<span>${row.user_id}</span><span>(${row.fullname})</span>`;
              }
            },
            /*{
              render: function(data, type, row, meta) {
                return `<span>${row.direct_income}</span>`;
              }
            },
            {
              render: function(data, type, row, meta) {
                return `<span>${row.binary_income}</span>`;
              }
            },
            {
              render: function(data, type, row, meta) {
                return `<span>${row.roi_income}</span>`;
              }
            },*/ 
            {
              render: function(data, type, row, meta) {
                return `<span>$${(row.fund_wallet - row.fund_wallet_withdraw).toFixed(2)}</span>`;
              }
            },
           /* {
              render: function(data, type, row, meta) {
                return `<span>${row.franchise_income}</span>`;
              }
            },*/
            {
              render: function(data, type, row, meta) {
                return `<span>$${(row.working_wallet).toFixed(2)}</span>`;
              }
            },
            {
              render: function(data, type, row, meta) {
                return `<span>$${(row.working_wallet -
                  row.working_wallet_withdraw).toFixed(2)}</span>`;
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
                  return moment(String(row.entry_time)).format("YYYY-MM-DD");
                }
              }
            }
          ]
        });
        $("#account-wallet-report").on(
          "click",
          "#deposite-address",
          function() {
            //$('#deposit-address-model').modal();
            if (that.table.row($(this).parents("tr")).data() !== undefined) {
              var data = that.table.row($(this).parents("tr")).data();
              that.OnShowPinxClick(data);
            } else {
              var data = that.table.row($(this)).data();
              that.OnShowPinxClick(data);
            }
          }
        );
        $("#onSearchClick").click(function() {
          that.table.ajax.reload();
        });
        $("#onResetClick").click(function() {
          $("#searchForm").trigger("reset");
          that.table.ajax.reload();
        });
      }, 0);
    },
    OnShowPinxClick(data) {
      $("#deposit-address-model").modal();
      this.depositaddress = data;
    },

    exportToExcel(){
      var params = {frm_date: $('#frm_date').val(), to_date: $('#to_date').val(),user_id: $('#to_user_id').val(),action:'export',responseType: 'blob' };
      axios.post('getaccountwallet', params).then(resp => {
      if(resp.data.code === 200){
        //this.export_url = resp.data.data.excel_url;
        var mystring = resp.data.data.data;
        var myblob = new Blob([mystring], {
          type: 'text/plain'
        });

        var fileURL = window.URL.createObjectURL(new Blob([myblob]));
        var fileLink = document.createElement('a');

        fileLink.href = fileURL;
        fileLink.setAttribute('download', 'account_wallet.xls');
        document.body.appendChild(fileLink);

        fileLink.click();
      }else{    
        this.$toaster.error(resp.data.message)
      }    
    });
  }
  }
};
</script>