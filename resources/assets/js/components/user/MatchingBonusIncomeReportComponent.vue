<template>
<div class="content-body">
   <div class="container-fluid">
      <div class="row page-titles">
         <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">My Earnings</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Equitals Bonus</a></li>
         </ol>
      </div>
      <!-- row -->
      <div class="row">
         <div class="col-12">
            <div class="card lp">
               <div class="card-header">
                  <h4 class="card-title">Equitals Bonus</h4>
               </div>
                <div class="card-body">
                  <form action="#" id="searchForm">
                     <div class="row">
                        <div class="col-12 col-md-10">
                           <div class="row rmb">
                              <div class="col-12 col-md-6">
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
                              <div class="col-12 col-md-6">
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
                              <!--  <div class="col-6 col-md-4">
                                 <label for="deposit-id">User Id</label> <input placeholder="Enter User Id" id="deposit-id" type="text" class="form-control">
                              </div>
 -->
                              
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
                     <table id="matching-bonus-report" class="display" style="min-width: 845px">
                        <thead>
                           <tr>
                                 <th>Sr No</th>
                                 <th>Date</th>
                                <!--  <th>Rank</th> -->
                                 <th>Equitals Bonus</th>
                                 <th>Rank</th>
                                <!--  <th>Remark</th> -->
                                <!-- <th>User Id</th>
                                <th>Full Name</th>
                                <th>Amount</th> -->
                                <!--  <th>On Amount</th>  -->
                                <!-- <th>Status</th> -->
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
    Breadcrum,
  },
  data() {
    return {
      from_date: null,
      to_date: null,
    };
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
        const table = $("#matching-bonus-report").DataTable({
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
            url: apiUserHost + "matching-bonus-income",
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
            /*{
                  render: function (data, type, row, meta) {
                      return `<span>${row.user_id}</span><span>(${row.fullname})</span>`;
                  }
              },*/

            {
              render: function (data, type, row, meta) {
                return `<span>$${row.amount}</span>`;
              },
            },
            {
              render: function(data, type, row, meta) {
                return `<span>${row.bonus_name}</span>`;
              }
            },
            /* {
              "defaultContent": "",
              render: function (data, type, row, meta) {
                if (
                  row.remark === null ||
                  row.remark === undefined ||
                  row.remark === ""
                ) {
                  return `-`;
                } else {
                  return `<span>${row.remark}</span>`;
                }
              },
            },*/
            /*{
              render: function(data, type, row, meta) {
                return `<span>${row.remark}</span>`;
              }
            },*/

            /*{
              render: function(data, type, row, meta) {
                return `<span>$${row.net_amount}</span>`;
              }
            },*/
           
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