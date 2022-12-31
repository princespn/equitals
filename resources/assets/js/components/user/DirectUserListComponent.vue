<template>
  <div class="content-body">
   <div class="container-fluid">
      <div class="row page-titles">
         <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Members</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">My Direct Members</a></li>
         </ol>
      </div>
      <!-- row -->
      <div class="row">
         <div class="col-12">
            <div class="card lp">
               <div class="card-header">
                  <h4 class="card-title">Direct Member</h4>
               </div>
               <div class="card-body">
                  <form action="#" id="searchForm">
                     <div class="row">
                        <div class="col-12 col-md-10">
                           <div class="row rmb">
                              <div class="col-6 col-md-3">
                                 <label>From Date</label> 
                                 <div>
                                    <div data-date-format="dd M, yyyy" data-date-autoclose="true" data-provide="datepicker" class="input-daterange input-group"><input type="date" name="start" placeholder="From" id="from-date" class="form-control"></div>
                                 </div>
                              </div>
                              <div class="col-6 col-md-3">
                                 <label>To Date</label>
                                 <div>
                                    <div data-date-format="dd M, yyyy" data-date-autoclose="true" data-provide="datepicker" class="input-daterange input-group"><input type="date" name="end" placeholder="To" id="to-date" class="form-control"></div>
                                 </div>
                              </div>
                               <div class="col-12 col-md-3">
                                 <label for="user-id">User Id</label> <input placeholder="Enter User Id" id="user-id" type="text" class="form-control">
                              </div>

                             <div class="col-12 col-md-3">
                                  <label for="status">Select Status</label>
                                    <select id="status" class="form-control">
                                      <option value>All</option>
                                      <option value="1">Active</option>
                                      <option value="2">Inactive</option>
                                    </select>
                              </div>

                              
                              <div class="col-12 col-md-12 pt-2 justify-content-center">
                                 <div class="button-items button_submit">
                                  <button type="button" id="onSearchClick" class="btn btn-success waves-effect waves-light">Search Now</button> 
                                  <button type="button" id="onResetClick" class="btn btn-warning waves-effect waves-light">Reset Now</button></div>
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
                     <table id="direct-user-list" class="display" style="min-width: 845px">
                        <thead>
                           <tr>
                              <th>Sr No</th>
                               <th>Date</th>
                              <th>User Id</th>
                              <th>Name</th>
                              <th>Phone</th>
                              <th>Email Id</th>
                            <!--  <th class="min-tablet">Fullname</th> -->
                            <!--   <th class="min-tablet">Position</th> -->
                              <th>Investment</th>
                              <th>Position</th>
                             
                            <!-- <th>Status</th>  -->
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
    Breadcrum
  },
  data() {
    return {
      getlevels: {
        level_id: "",
        level_name: ""
      },
      INR: "",
      type: ""
    };
  },
  mounted() {
    this.getLevelView();
    /*this.getLevels();
    this.getPackages();*/
  },
  methods: {
    getLevelView() {
      let i = 0;
      let that = this;
      let token = localStorage.getItem("user-token");
      setTimeout(function() {
        that.table = $("#direct-user-list").DataTable({
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
          dom:'lrtip',
          ajax: {
            url: apiUserHost + "direct_list",
            type: "POST",
            data: function(d) {
              i = 0;
              i = d.start + 1;

              let params = {
                              user_id: $("#user-id").val(),
                              frm_date: $("#from-date").val(),
                              to_date: $("#to-date").val(),
                              status: $("#status").val()
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
            {data :"user_id",
            "defaultContent": "" 
            },
            { data: 'fullname' },
            { data: "mobile",
             "defaultContent": ""
            },
            { data: "email",
             "defaultContent": ""
              },
            
            /*{
              render: function(data, type, row, meta) {
                that.type == "USD";
                if (that.type == "INR") {
                  return `<span>${row.total_investment * that.INR}</span>`;
                } else {
                  return `<span>$${row.total_investment}</span>`;
                }
              }
            },*/
             { "defaultContent": "",
              render: function(data, type, row, meta) {
                return `<span>$${row.topup_amount}</span>`;
              }
            },
            { data: "position" ,
             "defaultContent": ""
             },
          
            /* {
                              render: function (data, type, row, meta) {
                                if (row.total_investment > 0) {
                                  return `<span class="label label-primary">Paid</span>`;
                                } else {
                                  return `<span class="label label-danger">Unpaid</span>`;
                                }
                              }
                            }*/
          ]
        });

         $("#onSearchClick").click(function() {
            var startDate = $("#frm-date").val();
            var endDate = $("#to-date").val();
            if(endDate < startDate)
            {
                that.$toaster.error("To date is less than from date");
                return false;
                // alert("To date should not less than from date ");
            }
            that.table.ajax.reload();
          });
        $("#onResetClick").click(function() {
          $("#searchForm").trigger("reset");
          that.table.ajax.reload();
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
        })
        .catch(error => {});
    }
  }
};
</script>