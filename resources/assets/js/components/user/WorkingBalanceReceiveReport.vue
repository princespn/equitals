<template>
	 <!-- BEGIN: Content-->
    <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="header-navbar-shadow"></div>
      <div class="content-wrapper">
        <div class="content-header row">
          <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
              <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Received Balance Report </h2>
                <!-- <div class="breadcrumb-wrapper col-12">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Investment Report
                    </li>
                  </ol>
                </div> -->
              </div>
            </div>
          </div>
          <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
            <div class="form-group breadcrum-right">
              <div class="dropdown">
                <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-settings"></i></button>
                <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="#">Chat</a><a class="dropdown-item" href="#">Email</a><a class="dropdown-item" href="#">Calendar</a></div>
              </div>
            </div>
          </div>
        </div>
        <div class="content-body">
          <!-- Complex headers table -->
          <section id="headers">
              <div class="row">
                  <div class="col-12">
                      <div class="card">
                          <div class="card-header">
                              <!-- <h4 class="card-title">ROI Income Report</h4> -->

                            
                          </div>
                          <div class="card-content">                           
                            <div class="card-body card-dashboard">
                              <div class="table-responsive">
                                <table id="receive-balance-report" class="table table-striped table-bordered complex-headers">
                                  <thead>
                                    <tr>
                                      <th>Sr.No</th>
                                      <th>From User Id</th>
                                      <th>FullName</th>
                                      <th>Amount</th>
                                      <th>Date</th>
                                    </tr>
                                  </thead>

                                  <!-- <tfoot>
                                    <tr>
                                      <th colspan="3">Total Amount</th>
                                      <th><span">0</span></th>
                                    </tr>
                                  </tfoot> -->
                                </table>
                              </div>
                            </div>
                          </div>
                      </div>
                  </div>
              </div>
          </section>
          <!--/ Complex headers table -->

        </div>
      </div>
    </div>
    <!-- END: Content-->
</template>

<script>
    import moment from 'moment';
    import { apiUserHost } from'../../user-config/config';
    import Breadcrum from './BreadcrumComponent.vue';
    import Swal from 'sweetalert2';
    export default {
        components: {
            Breadcrum
        },
        data(){
            return{
                total_balance:0,
                transfer_btn:false
            }
        },
        computed: {

        },
        mounted(){
            this.getTopupReport();
        },
        methods:{
            getTopupReport(){
                let i = 0;
                let that = this;
                let token = localStorage.getItem('user-token');
                setTimeout(function(){

                    const table = $('#receive-balance-report').DataTable({
                        responsive: true,
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        responsive: true,
                        stateSave: false,
                        ordering: true,
                        ajax: {
                            url: apiUserHost+'working-receive-balance-report',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    // level_id : $('#level-id').val(),
                                    type:'receive'
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
                                    that.total_balance = json.data.totalAmount;
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
                                render: function (data, type, row, meta) {
                                    return meta.row + 1;
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    return `<span>${row.user_id}</span>`;
                                }
                            },
                            { render: function (data, type, row, meta,) {
                                   return row.name/* + ' ( ' + row.package_type + ' ) ' */ ;

                                }
                            },                           
                            { render: function (data, type, row, meta,) {
                                   return `<span>$${row.amount}</span>  `;

                                }
                            },

                            {
                              render: function(data, type, row, meta) {
                                if (row.entry_time === null || row.entry_time === undefined || row.entry_time === "") {
                                  return `-`;
                                } else {
                                  return moment(String(row.entry_time)).format("YYYY/MM/DD");
                                }
                              }
                            }
                        ]
                    });
                    $('#search-box').click(function () {
                        table.ajax.reload();
                    });
                     
                },0);
            },
            onViewClick(id,amount,currency,date1, franchise_id){
             this.$router.push({ name: 'certificate', params: {amount: amount,currency: currency,user_id: id,date1: date1, franchise_id: franchise_id}})
            },
        }
    }
</script>
