<template>
  <div class="content-body">
   <div class="container-fluid">
      <div class="row page-titles">
         <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Members</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">All Members View</a></li>
         </ol>
      </div>
      <!-- row -->
      <div class="row">
         <div class="col-12">
            <div class="card lp">
               <div class="card-header">
                  <h4 class="card-title">All Members</h4>
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
                               <div class="col-12 col-md-2">
                                 <label for="deposit-id">User Id</label> <input placeholder="Enter User Id" id="user-id" type="text" class="form-control">
                              </div>
                               <div class="col-12 col-md-2">
                                  <label for="deposit-id">Select Team</label>
                                    <select id="position" class="form-control">
                                      <option value>All</option>
                                      <option value="1">Left Team</option>
                                      <option value="2">Right Team</option>
                                    </select>
                              </div>
                              <div class="col-12 col-md-2">
                                  <label for="status">Select Status</label>
                                    <select id="status" class="form-control">
                                      <option value>All</option>
                                      <option value="1">Active</option>
                                      <option value="0">Inactive</option>
                                    </select>
                              </div>
                              <div class="col-12 col-md-12 pt-2 justify-content-center">
                                 <div class="button-items button_submit"><button type="button" id="search-box" class="btn btn-success waves-effect waves-light">Search Now</button> <button type="button" id="reset" class="btn btn-warning waves-effect waves-light">Reset Now</button></div>
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
                     <table id="team-view" class="display" style="min-width: 845px">
                        <thead>
                           <tr>
                              <th>Sr No</th>
                              <th>Registration Date</th>
                              <th>User Id</th>
                              <th>Name</th>
                              <th>Sponsor Id</th>
                              <th>Placement Id</th>
                              <!-- class="min-tablet" -->
                              <th>Position</th>
                              <th>Left BV</th>
                              <th>Right BV</th>
                              <th>Status</th>
                              <!-- <th>Left Id</th>
                              <th>Right Id</th>-->
                              
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
import DatePicker from "vuejs-datepicker";
import { apiUserHost } from "../../user-config/config";
import Breadcrum from "./BreadcrumComponent.vue";
import Swal from "sweetalert2";
export default {
  components: {
    Breadcrum,
    DatePicker
  },
  data() {
    return {
      position: "",
      status: "",
      user_id: null,
      from_date: null,
      to_date: null,
      team: {}
    };
  },
  computed: {
    isReset() {
      return this.user_id;
      return this.from_date;
      return this.to_date;
      return this.position;
      return this.status;
    }
  },
  mounted() {
    this.getTeam();
  },
  methods: {
    customFormatter(date) {
      return moment(date).format("DD-MM-Y");
    },
    getTeam() {
      let i = 0;
      let that = this;
      let token = localStorage.getItem("user-token");
      setTimeout(function() {
        const table = $("#team-view").DataTable({
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
          ajax: {
            url: apiUserHost + "team-view",
            type: "POST",
            data: function(d) {
              i = 0;
              i = d.start + 1;

              let params = {
                user_id: $("#user-id").val(),
                frm_date: $("#from-date").val(),
                to_date: $("#to-date").val(),
                position: $("#position").val(),
                status: $("#status").val()
                // type: $("#type").val()
                // level_id : $('#level-id').val(),
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
                that.team = json.data.records;
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
                  row.joining_date === null ||
                  row.joining_date === undefined ||
                  row.joining_date === ""
                ) {
                  return `-`;
                } else {
                  return moment(String(row.joining_date)).format("YYYY/MM/DD");
                }
              }
            },
            { 
              "defaultContent": "",
              data: "user_id"
             },
            {"defaultContent": "",
               data: "fullname" 
            },
            { "defaultContent": "",
              data: "sponser_id" 
            },
            {"defaultContent": "",
               data: "upline_id" 
            },
            {"defaultContent": "",
             data: "position" 
             },
            /*{
                                    render: function (data, type, row, meta) {
                                        if (row.type === 'Red') {
                                          return `<span style="color:Red">${row.type}</span>`;
                                        } else {
                                             return `<span style="color:Green">${row.type}</span>`;
                                        }
                                    }
                                },*/
            {"defaultContent": "",
               data: "left_bv" 
            },
            {"defaultContent": "",
             data: "right_bv" 
             },
             {"defaultContent": "",
              render: function (data, type, row, meta) {
                 if (row.status === "Inactive") {
                    return `<span class="btn btn-danger">${row.status}</span>`;
                  } else {
                       return `<span class="btn btn-success">${row.status}</span>`;
                  }
               } 
             },
            
          ]
        });
        $("#search-box").click(function() {
          table.ajax.reload();
        });

        $("#reset").click(function() {
          $("#user-id").val("");
          $("#from-date").val("");
          $("#to-date").val("");
          $("#position").val("");
          $("#status").val("");
          $("#search-form").trigger("reset");
          i = 0;
          table.ajax.reload();
        });
        // $('#reset').click(function () {
        //     $('#user-id').val('');
        //     $('#from-date').val('');
        //     $('#to-date').val('');
        //     $('#position').val('');
        //     $('#search-form').trigger("reset");
        //     i = 0;
        //      table.ajax.reload();

        // });
      }, 0);
    }
  }
};
</script>