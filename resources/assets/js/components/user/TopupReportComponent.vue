<template>
  <div class="content-body">
   <div class="container-fluid">
      <div class="row page-titles">
         <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Activation</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">My Activations</a></li>
         </ol>
      </div>
      <!-- row -->
      <div class="row">
         <div class="col-12">
            <div class="card lp">
               <div class="card-header">
                  <h4 class="card-title">My Activations</h4>
               </div>
               <div class="card-body">
                  <form action="#" id="searchForm">
                     <div class="row rmb">
                        <div class="col-12 col-md-10">
                           <div class="row">
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
                     <table id="topup-report" class="display" style="width:100%;">
                        <thead>
                           <tr>
                              <th>Sr No.</th>
                               <th>Date</th>
                              <th>Deposit Id </th>
                              <th>Amount</th>
                             <!--  <th>Plan</th> -->
                              <th>Topup From </th>                           
                           </tr>
                        </thead>
                        <tbody>
                          <th>Sr No.</th>
                               <th>Date</th>
                              <th>Deposit Id </th>
                              <th>Amount</th>
                             <!--  <th>Plan</th> -->
                              <th>Topup From </th> 
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
        const table = $("#topup-report").DataTable({
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
            url: apiUserHost + "topup-report",
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
              defaultContent: "",
              render: function (data, type, row, meta) {
                return meta.row + 1;
              },
            },
            /*{
                                render: function (data, type, row, meta) {
                                    return `<span>${row.user_id}</span><span>(${row.fullname})</span>`;
                                }
                            },*/


                              {
              defaultContent: "",
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

            {
              defaultContent: "",
              data: "pin",
            },
            /*{ data: 'amount' },*/
            {
              defaultContent: "",
              render: function (data, type, row, meta) {
                return `<span>$${row.amount}</span>  `;
              },
            },
            /*{
              defaultContent: "",
              render: function (data, type, row, meta) {
                return row.product_name  + ' ( ' + row.package_type + ' ) ' ;
              },
            },*/
            // { data: 'franchise_user_id' },
            /*{ data: 'name' },*/
            /* { data: 'top_up_by' },
                            { data: 'top_up_type' },
                            { data: 'payment_type' },*/
            /*{ data: 'withdraw' },*/
            { defaultContent: "", data: "topupfrom" },
            
            /* {
                                render: function (data, type, row, meta) {
                                    if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                      return `-`;
                                    } else {
                                        return `<label class="waves-effect" id="view" data-amount="${row.amount}" data-id="${row.franchise_user_id}" data-date="${row.entry_time}"data-currency="${row.currency_code}" style="color:#7367f0">View
                                            </label>`;
                                    }
                                }
                            }*/
          ],
        });
        $("#onSearchClick").click(function () {
          table.ajax.reload();
        });
        $("#onResetClick").click(function () {
          $("#searchForm").trigger("reset");
          table.ajax.reload();
        });
        $("#topup-report tbody").on("click", "#view", function () {
          that.onViewClick(
            $(this).data("id"),
            $(this).data("amount"),
            $(this).data("currency"),
            $(this).data("date")
          );
        });
      }, 0);
    },
    onViewClick(id, amount, currency, date1, franchise_id) {
      this.$router.push({
        name: "certificate",
        params: {
          amount: amount,
          currency: currency,
          user_id: id,
          date1: date1,
          franchise_id: franchise_id,
        },
      });
    },
  },
};
</script>
