<template>
  <div class="content-body">
   <div class="container-fluid">
      <div class="row page-titles">
         <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Benefits</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Working Profit Secured</a></li>
         </ol>
      </div>
      <!-- row -->
      <div class="row">
         <div class="col-12">
            <div class="card lp">
               <div class="card-header">
                  <h4 class="card-title">Fund Transfer Report</h4>
               </div>
               
               <div class="card-body">
                   <form action="#" id="searchForm">
                     <div class="row rmb">
                        <div class="col-12 col-md-12">
                           <div class="row">
                              <div class="col-6 col-md-3">
                                 <label>From Date</label> 
                                 <div>
                                 
                                       <input
                                    type="date"
                                    class="form-control input-dark"
                                    name="start"
                                    placeholder="From"
                                    id="from-date"
                                  />
                                 </div>
                              </div>
                              <div class="col-6 col-md-3">
                                 <label>To Date</label> 
                                 <div>
                  
                              <input
                                  type="date"
                                  class="form-control input-dark"
                                  name="start"
                                  placeholder="From"
                                  id="to-date"
                                />

                                 </div>
                              </div>
                              <div class="col-6 col-md-3 sm-pt-10">
                                <label for="user-id">To User Id</label>
                                <input
                                  placeholder="Enter To User Id"
                                  id="touser-id"
                                  type="text"
                                  class="form-control"
                                />
                              </div>
                              
                              <div class="col-12 col-md-12 pt-2 justify-content-center">
                                 <div class="button-items button_submit"><button type="button" id="onSearchClick" class="btn btn-primary">Search Now</button> <button type="button" id="onResetClick" class="btn btn-update custom-btn" style="background:red">Reset Now</button></div>
                              </div>
                           </div>
                        </div>
                     
                     </div>
                  </form>
                  <div class="table-responsive">
                     <table id="withdrawals-income-report" class="display" style="min-width: 845px">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th class="text-success">Credited To</th>
                                <th class="text-danger">Debited From</th>
                                <th>Transferred Amount</th>
                                <!-- <th>Transfer Charges</th>
                                <th>Net Amount</th> -->
                                <th>Remark</th>
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
export default {
  components: {
    Breadcrum,
  },
  data() {
    return {
      INR: "",
    };
  },
  mounted() {
    this.getTopupReport();
    //this.getProjectSetting();
    //  this.getPackages();
  },
  methods: {
    getTopupReport(){
                let i = 0;
                let that = this;
                let token = localStorage.getItem('user-token');
                setTimeout(function(){
                    that.table = $('#withdrawals-income-report').DataTable({
                        responsive: true,
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        responsive: true,
                        stateSave: false,
                        ordering: false,
                        dom:'lrtip',
                        ajax: {
                            url: apiUserHost+'fund-to-fund-transfer-report',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    frmuser_id: $("#frmuser-id").val(),
                                    touser_id: $("#touser-id").val(),
                                    frm_date: $("#from-date").val(),
                                    to_date: $("#to-date").val(),
                                    type:'transfer'
                                };
                                Object.assign(d, params);
                                return d;
                            },
                            headers: {
                              'Authorization': 'Bearer ' + token
                            },
                            dataSrc: function (json) {
                                if (json.code === 200) {
                                    that.arrGetHelp = json.data.records;
                                    json['draw'] = json.data.draw;
                                    json['recordsFiltered'] = json.data.recordsFiltered;
                                    json['recordsTotal'] = json.data.recordsTotal;
                                    // that.total_balance = json.data.totalAmount;
                                    return json.data.records;
                                } else {
                                    json['draw'] = 0;
                                    json['recordsFiltered'] = 0;
                                    json['recordsTotal'] = 0;
                                    return json;
                                }
                            }
                        },
                        columns: [
                             {
                               "defaultContent": "",
                                render: function (data, type, row, meta) {
                                    return meta.row + 1;
                                }
                            },
                            {
                                "defaultContent": "",
                                render: function (data, type, row, meta) {
                                    return `<span>${row.to_user_id}</span>`;
                                }
                            },
                            {
                                "defaultContent": "",
                                render: function (data, type, row, meta) {
                                    return `<span>${row.user_id}</span>`;
                                }
                            },
                            { "defaultContent": "",
                              render: function (data, type, row, meta,) {
                                   return `<span>$${row.amount}</span>  `;

                                }
                            },
                             /*{
                               "defaultContent": "", 
                              render: function (data, type, row, meta,) {
                                   return `<span>$${row.transfer_charge}</span>  `;

                                }
                            }, 
                            { 
                              "defaultContent": "",
                              render: function (data, type, row, meta,) {
                                   return `<span>$${row.net_amount}</span>  `;

                                }
                            },*/
                             {
                              "defaultContent": "",
                              render: function(data, type, row, meta) {
                                if (row.remark === null || row.remark === undefined || row.remark === "") {
                                  return `-`;
                                } else {
                                  return `<span>${row.remark}</span>  `;
                                }
                              }
                            }, 
                           
                            {
                              "defaultContent": "",
                              render: function(data, type, row, meta) {
                                if (row.entry_time === null || row.entry_time === undefined || row.entry_time === "") {
                                  return `-`;
                                } else {
                                  return moment(String(row.entry_time)).format("YYYY/MM/DD");
                                }
                              }
                            }, 
                            /*{ render: function (data, type, row, meta,) {
                                   return `<span>$${row.balance}</span>  `;

                                }
                            },*/
                        ]
                    });
                     $("#onSearchClick").click(function() {
                      // alert("Hello")
                      that.table.ajax.reload();
                    });
                    $("#onResetClick").click(function() {
                      $("#searchForm").trigger("reset");
                      that.table.ajax.reload();
                    });
                     
                },0);
            },
            onViewClick(id,amount,currency,date1, franchise_id){
             this.$router.push({ name: 'certificate', params: {amount: amount,currency: currency,user_id: id,date1: date1, franchise_id: franchise_id}})
            },
  },
};
</script>