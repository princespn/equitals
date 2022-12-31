<template>
  <!-- BEGIN: Content-->
  <div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
          <div class="row breadcrumbs-top">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
              <h2 class="content-header-title float-left mb-0">Manual Fund Request Report</h2>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="daterange-container">
                         <div class="date-range">
                            <div id="reportrange">
                               <!--  <input type="text" style="color:white;border:none;background: #f4f5fb;" id="daterangefocus" @focus="reloadTable"> -->
                                <i class="icon-calendar cal"></i>
                                <i class="icon-chevron-down arrow"></i>
                                <span class="range-text"></span>
                                <input class="from" v-show="false">
                                <input class="to" v-show="false">
                            </div>
                        </div>
                        <!-- <a href="#" data-toggle="tooltip" data-placement="top" title="Download CSV" class="download-reports">
                            <i class="icon-download1"></i>
                        </a> -->
                    </div>
                </div>
          </div>
        </div>
      </div>
            <div class="row ">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="manual-topup" class="table table-striped table-bordered mar-top-23 dataTable dtr-inline collapsed" cellspacing="0" width="100%" role="grid" aria-describedby="demo-dt-basic_info" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Fund Req Id</th>
                                          <!--   <th>Package Name</th> -->
                                            <th>Add Fund</th>
                                            <th>Attachment</th>
                                             <th>Status</th>               
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
    import moment from 'moment';
    import { apiUserHost } from'../../user-config/config';
    import Breadcrum from './BreadcrumComponent.vue';
    export default {  
        components: {
            Breadcrum
        }, 
        data(){
            return{
            }
        },
        mounted(){
            this.manualtopupReport();
        },
        methods:{
            manualtopupReport(){
                let i = 0;
                let that = this;
                let token = localStorage.getItem('user-token');
                setTimeout(function(){

                    const table = $('#manual-topup').DataTable({
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
                            url: apiUserHost+'manual-topup-report',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
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
                                    return i++;
                                }
                            },
                            { data: 'invoice_id' },
                            // { data: 'plan_name' },
                           
                            {
                                render: function (data, type, row, meta) {
                                    return `$${row.top_amount}`;
                                }
                            },
                           {
                                render: function (data, type, row, meta) {
                                    if (row.attachment === null) {
                                        return `<img src="public/uploads/files/no_image_available.png" width="70" height="70">`;
                                    } else {
                                        return `<a class="pointer" id="onImageClick" data-img="${row.attachment}"><img src="${row.attachment}" alt="Payment Slip" height="50" width="50"></a>`;
                                    }
                                }
                            },
                            { data: 'admin_status' }, 
                            {
                                render: function (data, type, row, meta) {
                                    if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.entry_time)).format('YYYY/MM/DD');
                                    }
                                }
                            },
                        ]
                    });
                },0);
            },         
        }
    }
</script>