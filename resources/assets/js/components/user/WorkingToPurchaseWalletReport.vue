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
                <h2 class="content-header-title float-left mb-0">Dex Wallet To Purchase Wallet Report </h2>
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
                                <div class="col-md-12">
                      <form id="searchForm">
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label class="control-label">From Date</label>
                            <br />
                            <DatePicker
                              :format="customFormatter"
                              id="from-date"
                              :bootstrap-styling="true"
                            ></DatePicker>
                            <!-- <input
                              type="text"
                              name="frm_date"
                              id="from-date"
                              placeholder="From Date"
                              readonly="readonly"
                              autocomplete="off"
                              class="form-control"
                            />-->
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label class="control-label">To Date</label>
                            <br />
                            <!-- <input
                              type="text"
                              name="to-date"
                              id="to_date"
                              placeholder="To Date"
                              readonly="readonly"
                              autocomplete="off"
                              class="form-control"
                            />-->
                            <DatePicker
                              id="to-date"
                              :bootstrap-styling="true"
                              :format="customFormatter"
                            ></DatePicker>
                          </div>
                        </div>

                        <!-- <div class="col-md-3">
                          <div class="form-group">
                            <label class="control-label">Deposit Id</label>
                            <br />
                            <input
                              placeholder="Enter Deposit Id"
                              id="deposit-id"
                              type="text"
                              class="form-control"
                            />
                          </div>
                        </div> -->
                        
                      <!-- </div>

                      <div class="panel-footer bg-gray text-center"> -->
                       <div class="col-md-4">
                          <div class="form-group">  
                         <label class="control-label">&nbsp;</label>
                            <br />
                        <button
                          type="button"
                          name="signup1"
                          value="Sign up"
                          id="onSearchClick"
                          class="btn btn-primary"
                        >Search</button>
                      <!-- </div>
                    </div>
                         <div class="col-md-2">
                          <div class="form-group"> 
                            <label class="control-label">&nbsp;</label>
                            <br /> -->
                        <button
                          type="button"
                          name="signup1"
                          value="Sign up"
                          id="onResetClick"
                          class="btn btn-success"
                        >reset</button>
                      </div>
                    </div>
                      </div>
                    </form>
                    </div>

                    <div class="top-bordr3 m-t-30 m-b-30"></div>
                                  <div class="table-responsive">
                                      <table id="working-purchase-report" class="table table-striped table-bordered complex-headers">
                                          <thead>
                                            <tr>
                                              <th>Sr.No</th>
                                              <th>User Id</th>
                                              <th>Balance</th>
                                              <th>Dex Wallet Amount</th> 
                                              <th>Purchase Wallet Amount</th> 
                                              <!-- <th>Total Income</th> -->
                                              <th>Date</th> 
                                            </tr>
                                          </thead>

                                          <tfoot>
                                              
                                            <tr>
                                              <th>Sr.No</th>
                                              <th>User Id</th>
                                              <th>Balance</th>
                                              <th>Dex Wallet Amount</th> 
                                              <th>Purchase Wallet Amount</th> 
                                              <!-- <th>Total Income</th> -->
                                              <th>Date</th> 
                                            </tr>
                                          </tfoot>
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

                    that.table = $('#working-purchase-report').DataTable({
                        responsive: true,
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        responsive: true,
                        stateSave: false,
                        ordering: true,
                        dom: 'lrtip',
                        ajax: {
                            url: apiUserHost+'working-to-purchase-report',
                            type: 'POST',
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
                              'Authorization': 'Bearer ' + token
                            },
                            dataSrc: function (json) {
                                if (json.code === 200) {
                                    that.arrGetHelp = json.data.records;
                                    json['draw'] = json.data.draw;
                                    json['recordsFiltered'] = json.data.recordsFiltered;
                                    json['recordsTotal'] = json.data.recordsTotal;
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
                            { data: 'user_id' },
                            { data: 'balance' },
                            { data: 'working_wallet_amount' },
                            { data: 'purchase_wallet_amount' },
                            /*{ data: 'total_income_without_roi' },*/
                            {
                                render: function (data, type, row, meta) {
                                    if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.entry_time)).format('YYYY-MM-DD');
                                    }
                                }
                            },
                        ]
                    });
                    $("#onSearchClick").click(function() {
                      that.table.ajax.reload();
                    });
                    $("#onResetClick").click(function() {
                      $("#searchForm").trigger("reset");
                      that.table.ajax.reload();
                    });
                     $('#topup-report tbody').on('click', '#view', function ()
                    {
                     that.onViewClick($(this).data('id'),$(this).data('amount'),$(this).data('currency'),$(this).data('date'));
                    });
                },0);
            },
            onViewClick(id,amount,currency,date1, franchise_id){
             this.$router.push({ name: 'certificate', params: {amount: amount,currency: currency,user_id: id,date1: date1, franchise_id: franchise_id}})
            }
        }
    }
</script>
