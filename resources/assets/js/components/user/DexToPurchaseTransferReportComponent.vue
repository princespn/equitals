<template>
 <div class="content-body">
   <div class="container-fluid">
      <div class="row page-titles">
         <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Benefits</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Working Profit Transferred to Activation Wallet</a></li>
         </ol>
      </div>
      <!-- row -->
      <div class="row">
         <div class="col-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Working Profit Transferred to Activation Wallet</h4>
               </div>
               <hr>
               <div class="card-body">
                  <div class="table-responsive">
                     <table id="dex-purchase-report" class="display" style="min-width: 845px">
                        <thead>
                           <tr>
                               <th>Sr.No</th>
                                <th>User Id</th>
                                <th>Amount</th>
                                <th>Transfer Charge</th>
                                <th>Net Amount</th>
                                <th>Date</th>
                           </tr>
                        </thead>
                         
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
    Breadcrum,
  },
  data() {
    return {};
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
        that.table = $("#dex-purchase-report").DataTable({
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
            url: apiUserHost + "dex-to-purchase-transfer-report",
            type: "POST",
            data: function (d) {
              i = 0;
              i = d.start + 1;

              let params = {
                frm_date: $("#from-date").val(),
                to_date: $("#to-date").val(),
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
            { "defaultContent": "",
              data: "user_id" 
            },
            { "defaultContent": "",
              data: "amount" 
            },
            {  "defaultContent": "",
               data: "transfer_charge"
            },
            { "defaultContent": "",
               data: "net_amount" 
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
                  return moment(String(row.entry_time)).format("YYYY-MM-DD");
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
  },
};
</script>
