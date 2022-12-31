<template>
<div class="content-body">
   <div class="container-fluid">
      <div class="row page-titles">
         <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Earnings</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Residual Bonus</a></li>
         </ol>
      </div>
      <!-- row -->
      <div class="row">
         <div class="col-12">
            <div class="card lp">
               <div class="card-header">
                  <h4 class="card-title">Residual Bonus</h4>
               </div>
               <div class="card-body">
                  <form action="#" id="searchForm">
                     <div class="row">
                        <div class="col-12 col-md-10">
                           <div class="row">
                              <div class="col-12 col-md-4">
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
                              <div class="col-12 col-md-4">
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
                                 <label for="deposit-id">Deposit Id</label> <input placeholder="Enter Deposit Id" id="deposit-id" type="text" class="form-control">
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
                     <table id="residual-income-report" class="display" style="min-width: 845px">
                        <thead>
                           <tr>
                              <th>Sr No</th>
                              <th>Date</th>
                              <th>Amount</th>
                              <th>ROI Amount</th>
                              <th>Deposit Id</th>
                              <th>Package</th>
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
export default {
  components: {
    Breadcrum,
  },
  data() {
    return {
      INR: "",
      deposit_id: null,
      from_date: null,
      to_date: null,
    };
  },
  computed: {
    isReset() {
      return this.deposit_id;
      return this.from_date;
      return this.to_date;
    },
  },
  mounted() {
    this.getRoi();
    this.getPackages();
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
    getRoi() {
      let i = 0;
      let that = this;
      let token = localStorage.getItem("user-token");
      setTimeout(function () {
        const table = $("#residual-income-report").DataTable({
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
            url: apiUserHost + "residual-income",
            type: "POST",
            data: function (d) {
              i = 0;
              i = d.start + 1;

              let params = {
                deposit_id: $("#deposit-id").val(),
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
                return i++;
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
            {
              "defaultContent": "",
              render: function (data, type, row, meta) {
                return `<span>$${row.on_amount}</span>`;
              },
            },
            {
              "defaultContent": "",
              render: function (data, type, row, meta) {
                return `<span>$${row.amount}</span>`;
              },
            },
            { "defaultContent": "",
              data: "pin" 
              },
            { data: 'package_name' },
            
            
          ],
        });

        $("#onSearchClick").click(function () {
          table.ajax.reload();
        });
        $("#onResetClick").click(function () {
          $("#searchForm").trigger("reset");
          table.ajax.reload();
        });
      }, 0);
    },
  },
};
</script>