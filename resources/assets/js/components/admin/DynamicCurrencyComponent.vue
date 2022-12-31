<template>
  <!-- Start content -->
  <div class="content">
    <div class="">
      <div class="page-header-title">
        <h4 class="page-title">Manage User</h4>
      </div>
    </div>

    <div class="page-content-wrapper">
      <div class="container">
        <div class="row">
          <div class="col-md-12 col-sm-3">
            <div class="panel panel-primary">
              <div class="panel-body add-new">
                <router-link
                  class="btn btn-primary waves-effect waves-light"
                  :to="{ name: 'addnewcurrency' }"
                >
                  <i class="fa fa-plus"></i>Add New Currency
                </router-link>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-primary">
              <div class="panel-body">
                <div class="table-responsive">
                  <table
                    id="manage-user-report"
                    class="table table-striped table-bordered dt-responsive"
                  >
                    <thead>
                      <tr>
                        <th>Sr.No</th>
                        <!--<th>Click to login</th>-->
                        <th>Currency Name</th>
                        <th>Currency Code</th>
                        <th>Status</th>
                        <th>Withdrwal Status</th>
                        <th>Action</th>
                        <!-- <th>paypal address</th> -->
                        <!-- <th>Sponser</th>
                                            <th>Position</th>
		                                    <th>Entry Date</th>
                                            <th>Status</th>
		                                    <th>Action</th>-->
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- end row -->
      </div>
      <!-- container -->
    </div>
    <!-- Page content Wrapper -->
  </div>
  <!-- content -->
</template>

<script>
import { apiAdminHost } from "./../../admin-config/config";
import moment from "moment";
import Swal from "sweetalert2";
import DatePicker from "vuejs-datepicker";

export default {
  data() {
    return {
      products: [],
      export_url: "",
    };
  },
  mounted() {
    this.manageUserReport();
  },
  components: {
    DatePicker,
  },
  methods: {
    dateFormat(date) {
      return moment(date).format("DD-MM-YYYY");
    },
    manageUserReport() {
      let i = 0;
      let that = this;
      let token = localStorage.getItem("access_token");
      setTimeout(function () {
        that.table = $("#manage-user-report").DataTable({
          responsive: true,
          retrieve: true,
          destroy: true,
          processing: false,
          serverSide: true,
          stateSave: false,
          ordering: false,
          dom: "Bfrtip",
          lengthMenu: [
            [10, 50, 100],
            [10, 50, 100],
          ],
          buttons: [
            // 'copyHtml5',
            /*'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5',*/
            "pageLength",
          ],
          ajax: {
            url: apiAdminHost + "/currencysettings",
            type: "POST",
            data: function (d) {
              i = 0;
              i = d.start + 1;

              let params = {
                // frm_date: $('#frm_date').val(),
                //to_date: $('#to_date').val(),
                //id: $('#user_id').val(),
                // product_id:$('#product_id').val(),
                // status: $('#status').val(),
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
              render: function (data, type, row, meta) {
                return meta.row + 1;
              },
            },
            { data: "currency_name" },
            {
              render: function (data, type, row, meta) {
                return row.currency_code;
              },
            },
            {
              render: function (data, type, row, meta) {
                // return row.status;
                if (row.status == "1") {
                  var status = "success";
                  var lblstat = "Active";
                } else {
                  var status = "danger";
                  var lblstat = "Inactive";
                }
                return `<label class="text-${status}">${lblstat}</label>`;
              },
            },
            {
              render: function (data, type, row, meta) {
                // return row.status;
                if (row.withdrwal_status == "1") {
                  var status = "success";
                  var lblstat = "Active";
                } else {
                  var status = "danger";
                  var lblstat = "Inactive";
                }
                return `<label class="text-${status}">${lblstat}</label>`;
              },
            },
            {
              render: function (data, type, row, meta) {
                return `
                                            <a class="editmyProfile" data-id="${row.id}" title="Edit">
                                                <i class="fa fa-pencil font-16"></i>
                                            </a>`;
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

        $("#manage-user-report").on("click", "#changeStatus", function () {
          that.changeStatus($(this).data("id"), $(this).data("status"));
        });

        /* $('#manage-user-report').on('click','#login',function () {
                        that.login($(this).data("user_id"));
                    });*/

        $("#manage-user-report").on("click", ".editmyProfile", function () {
          that.$router.push({
            name: "editcurrency",
            params: {
              id: $(this).data("id"),
            },
          });
        });

        $("#manage-user-report").on("click", ".myProfile", function () {
          that.$router.push({
            name: "userprofile",
            params: {
              id: $(this).data("id"),
            },
          });
        });
      }, 0);
    },
    changeStatus(id, status) {
      Swal({
        title: "Are you sure?",
        text: `You want to change status of this user`,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
      }).then((result) => {
        if (result.value) {
          axios
            .post("/blockuser", {
              id: id,
              status: status,
            })
            .then((resp) => {
              if (resp.data.code == 200) {
                this.$toaster.success(resp.data.message);
                this.table.ajax.reload();
              } else {
                this.$toaster.error(resp.data.message);
              }
            })
            .catch((err) => {});
        }
      });
    },
    exportToExcel() {
      var params = {
        frm_date: $("#frm_date").val(),
        to_date: $("#to_date").val(),
        id: $("#user_id").val(),
        status: $("#status").val(),
        action: "export",
        responseType: "blob",
      };
      axios.post("getusers", params).then((resp) => {
        if (resp.data.code === 200) {
          //this.export_url = resp.data.data.excel_url;
          var mystring = resp.data.data.data;
          var myblob = new Blob([mystring], {
            type: "text/plain",
          });

          var fileURL = window.URL.createObjectURL(new Blob([myblob]));
          var fileLink = document.createElement("a");

          fileLink.href = fileURL;
          fileLink.setAttribute("download", "AllUsers.xls");
          document.body.appendChild(fileLink);

          fileLink.click();
        } else {
          this.$toaster.error(resp.data.message);
        }
      });
    },
    /*   login(user_id = null) {
            axios.post('login', {
                user_id: user_id,
                password: '123456',
            }).then(resp => {
                 let userinfo = resp.data.data; 
                if(resp.data.code === 200){
                    const token = resp.data.data.access_token;
                    //this.$toaster.success(resp.data.message) 
                     if(userinfo.google2faauth == "TRUE"){
                        //this.verify2Fa();
                        this.token = token;
                        this.verify2fa = true;
                    }else{
                    localStorage.setItem('user-token', token); // store the token in localstorage
                    localStorage.setItem('type', "user"); // store the token in localstorage
                   // this.getPackages();
                         this.$toaster.success(resp.data.message) 
                        this.$router.push({name: 'dashboard'});
                        location.reload();
                    }
                    
                } else {
                    this.$toaster.error(resp.data.message)
                }
            }).catch(err => {
                //localStorage.removeItem('user-token'); // if the request fails, remove any 
               // location.reload();
                this.$router.push({ name: 'login' });
            })
          } */
  },
};
</script>
