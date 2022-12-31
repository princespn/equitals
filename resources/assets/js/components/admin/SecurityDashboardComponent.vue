<template>
    <!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Security Dashboard</h4>
            </div>
        </div>

        <div class="page-content-wrapper ">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-lg-3">
                        <div class="panel">
                            <div class="panel-body p-t-10">
                                <div class="widget-box-one">
                                    <i class="ti-timer widget-box-icon"></i>
                                    <h4 class="panel-title text-muted m-b-15 ">Topups to be checked </h4>
                                    <h2 class="m-t-0 text-primary m-b-15">
                                        <i class="mdi mdi-arrow-down-bold-circle-outline m-r-10"></i>{{dashboard.remaining_topup}}
                                    </h2>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="panel">
                            <div class="panel-body p-t-10">
                                <div class="widget-box-one">
                                    <i class="ti-stats-up widget-box-icon"></i>
                                    <h4 class="panel-title m-b-15 text-muted">Topups Checked</h4>
                                    <h2 class="m-t-0 text-warning m-b-15">
                                        <i class="mdi mdi-arrow-down-bold-circle-outline m-r-10"></i>{{dashboard.checked_topup}}
                                    </h2>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="panel">
                            <div class="panel-body p-t-10">
                                <div class="widget-box-one">
                                    <i class="ti-check-box dash-icon"></i>
                                    <h4 class="panel-title text-muted m-b-15"> Valid Topups</h4>
                                    <h2 class="m-t-0 text-info m-b-15">
                                        <i class="mdi mdi-arrow-up-bold-circle-outline m-r-10"></i>{{dashboard.valid_topup}}
                                    </h2>

                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="panel">
                            <div class="panel-body p-t-10">
                                <div class="widget-box-one">
                                    <i class="ti-alert widget-box-icon"></i>
                                    <h4 class="panel-title m-b-15 text-muted">Invalid Topups</h4>
                                    <h2 class="m-t-0 text-danger m-b-15">
                                        <i class="mdi mdi-arrow-down-bold-circle-outline m-r-10"></i>{{dashboard.invalid_topup}}
                                    </h2>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-lg-3">
                        <div class="panel">
                            <div class="panel-body p-t-10">
                                <div class="widget-box-one">
                                    <i class="ti-timer widget-box-icon"></i>
                                    <h4 class="panel-title text-muted m-b-15 ">Withdrawals to be checked </h4>
                                    <h2 class="m-t-0 text-primary m-b-15">
                                        <i class="mdi mdi-arrow-down-bold-circle-outline m-r-10"></i>{{dashboard.remaining_withdraw}}
                                    </h2>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="panel">
                            <div class="panel-body p-t-10">
                                <div class="widget-box-one">
                                    <i class="ti-stats-up widget-box-icon"></i>
                                    <h4 class="panel-title m-b-15 text-muted">Withdrawals Checked</h4>
                                    <h2 class="m-t-0 text-warning m-b-15">
                                        <i class="mdi mdi-arrow-down-bold-circle-outline m-r-10"></i>{{dashboard.checked_withdraw}}
                                    </h2>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="panel">
                            <div class="panel-body p-t-10">
                                <div class="widget-box-one">
                                    <i class="ti-check-box dash-icon"></i>
                                    <h4 class="panel-title text-muted m-b-15"> Valid Withdrawals</h4>
                                    <h2 class="m-t-0 text-info m-b-15">
                                        <i class="mdi mdi-arrow-up-bold-circle-outline m-r-10"></i>{{dashboard.valid_withdraw}}
                                    </h2>

                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="panel">
                            <div class="panel-body p-t-10">
                                <div class="widget-box-one">
                                    <i class="ti-alert widget-box-icon"></i>
                                    <h4 class="panel-title m-b-15 text-muted">Invalid Withdrawals</h4>
                                    <h2 class="m-t-0 text-danger m-b-15">
                                        <i class="mdi mdi-arrow-down-bold-circle-outline m-r-10"></i>{{dashboard.invalid_withdraw}}
                                    </h2>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="panel">
                            <div class="panel-body p-t-10">
                                <div class="widget-box-one">
                                    <i class="ti-lock widget-box-icon"></i>
                                    <h4 class="panel-title m-b-15 text-muted">Invalid Login Attempt Count</h4>
                                    <h2 class="m-t-0 text-warning m-b-15">
                                        <i class="mdi mdi-account-alert m-r-10"></i>{{dashboard.invalid_login}}
                                    </h2>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row mb-2">
                    <div class="panel">
                        <div class="panel-body p-t-10">                    
                            <div class="col-md-12">
                                <div class="table-dash mb-5">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="row"  colspan = "12" class="text-center">
                                                    <h3>Topup Checked</h3>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="text-align:center;vertical-align:middle">
                                                    <div class="s-arrow">
                                                        <i class="fa fa-lg fa-arrow-left" @click="getSecurityChecks(2)"></i>
                                                    </div>
                                                </td>
                                                <td  v-for="timing in topupCheck"  class="text-center">
                                                    <div><i class="fa fa-lg fa-check-circle text-success"></i></div>
                                                    <div>{{timing.t_time}}</div>
                                                    <div>{{timing.t_date}}</div>                                    
                                                </td>
                                                <td style="text-align:center;vertical-align:middle">
                                                    <div class="s-arrow">
                                                        <i class="fa fa-lg fa-arrow-right" @click="getSecurityChecks(1)"></i>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="panel">
                        <div class="panel-body p-t-10">
                            <div class="col-md-12">
                                <div class="table-dash mb-5">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="row"  colspan = "12" class="text-center">
                                                    <h3>Withdraw Checked</h3>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="text-align:center;vertical-align:middle">
                                                    <div class="s-arrow">
                                                        <i class="fa fa-lg fa-arrow-left" @click="getSecurityChecksW(2)"></i>
                                                    </div>
                                                </td>
                                                <td v-for="timing in withdrawCheck" class="text-center">
                                                    <div><i class="fa fa-lg fa-check-circle text-success"></i></div>
                                                    <div>{{timing.w_time}}</div>
                                                    <div>{{timing.w_date}}</div>
                                                </td>
                                                <td style="text-align:center;vertical-align:middle">
                                                    <div class="s-arrow">
                                                        <i class="fa fa-lg fa-arrow-right" @click="getSecurityChecksW(1)"></i>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                

            </div>
            <!-- container -->
        </div>
        <!-- Page content Wrapper -->
    </div>
    <!-- content -->
</template>
<script>
import { apiAdminHost } from'./../../admin-config/config';
import moment from 'moment';
import DatePicker from 'vuejs-datepicker';
import Swal from 'sweetalert2';

export default {
    data() {
        return {
            dashboard : {
                checked__topup : 0,
                remaining_topup : 0,
                valid_topup : 0,
                invalid_topup : 0,
                checked__withdraw : 0,
                remaining_withdraw : 0,
                valid_withdraw : 0,
                invalid_withdraw : 0,                    
                invalid_login : 0,                    
            },
            hiddenUserId : '',
            user_id : '',
            frm_date : '',
            to_date : '',
            userExistsMessage : '',
            custom_msg_class : '',
            topupCheck:[],
            withdrawCheck:[],
            tPosition:0,
            bcktPosition:0,
            wPosition:0,
            bckwPosition:0,
        }
    },
    components: {
        DatePicker
    },
    mounted() {

        this.getSecurityDashboardData();
        this.getSecurityTopups();
        this.getSecurityWithdrawals();
        this.getSecurityChecks();
        this.getSecurityChecksW();
            //this.fa();
        },
        methods: {
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            getSecurityDashboardData() { 

                axios.post('getSecurityDashboardSummary', {
                    frm_date: (this.frm_date!=='')?moment(this.frm_date).format('DD-MM-YYYY'):'',
                    to_date: (this.to_date!=='')?moment(this.to_date).format('DD-MM-YYYY'):'',
                    id : this.hiddenUserId,
                    user_id: this.user_id,
                }).then(response => {
                    this.dashboard = response.data.data;
                }).catch(error => {
                    this.message  = error.response.data.message;
                    this.flash(this.message, 'error', {
                      timeout: 500000,
                  });
                });
            },
            getSecurityChecks(pageno){
                if(pageno == 1){
                    this.tPosition = this.tPosition + 9;
                    if(this.tPosition > 9){
                      this.bcktPosition = this.bcktPosition + 9;
                  }           
              }else{
                if(this.bcktPosition > 0){
                  this.bcktPosition = this.bcktPosition - 9;              
                  this.tPosition = this.tPosition - 9;
              }
          }
          axios
          .post("/getchecktopuptime",{position:this.tPosition,tCondition:pageno,bckPosition:this.bcktPosition})
          .then(resp => {
              if (resp.data.code === 200) {
                if((resp.data.data.records).length == 0){
                  this.tPosition = this.tPosition - 9;
                  this.bcktPosition = this.bcktPosition - 9;
              }else if((resp.data.data.records).length > 0){
                  this.topupCheck = resp.data.data.records;
              }
                        // console.log("forward position : "+this.tPosition);
                        // console.log("backward position : "+this.bckRoiPosition);
                        // console.log(this.roiincomeTime);
                    }
                })
          .catch(err => {});
      },
      getSecurityChecksW(pageno){
        if(pageno == 1){
            this.wPosition = this.wPosition + 9;
            if(this.wPosition > 9){
              this.bckwPosition = this.bckwPosition + 9;
          }           
      }else{
        if(this.bckwPosition > 0){
          this.bckwPosition = this.bckwPosition - 9;              
          this.wPosition = this.wPosition - 9;
      }
  }
  axios
  .post("/getcheckwithdrawtime",{position:this.wPosition,tCondition:pageno,bckPosition:this.bckwPosition})
  .then(resp => {
      if (resp.data.code === 200) {
        if((resp.data.data.records).length == 0){
          this.wPosition = this.wPosition - 9;
          this.bckwPosition = this.bckwPosition - 9;
      }else if((resp.data.data.records).length > 0){
          this.withdrawCheck = resp.data.data.records;
      }
                        // console.log("forward position : "+this.tPosition);
                        // console.log("backward position : "+this.bckRoiPosition);
                        // console.log(this.roiincomeTime);
                    }
                })
  .catch(err => {});
},
fa() { 

    axios.post('2fa/enable', {
        frm_date: (this.frm_date!=='')?moment(this.frm_date).format('DD-MM-YYYY'):'',
        to_date: (this.to_date!=='')?moment(this.to_date).format('DD-MM-YYYY'):'',
        id : this.hiddenUserId,
        user_id: this.user_id,
    }).then(response => {
        this.dashboard = response.data.data;
    }).catch(error => {
        this.message  = error.response.data.message;
        this.flash(this.message, 'error', {
          timeout: 500000,
      });
    });
},
getSecurityTopups(){
    let i = 0;
    let that = this;
    let token = localStorage.getItem('access_token');
    setTimeout(function(){
        const table = $('#topup-security-report').DataTable({
            responsive: true,
            retrieve: true,
            destroy: true,
            processing: false,
            serverSide: true,
            stateSave: false,
            ordering: false,
            dom: 'Brtip',
            lengthMenu: [[10, 50, 100], [10, 50, 100]],
            buttons: [
                            // 'copyHtml5',
                            /*'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5',*/
                            'pageLength',
                            ],
                            ajax: {
                                url: apiAdminHost+'/getSecurityTopups',
                                type: 'POST',
                                data: function (d) {
                                    i = 0;
                                    i = d.start + 1;

                                    let params = {
                                    /*product_id: $('#product_id').val(),
                                    user_id: $('#user_id').val(),
                                    status: $('#status').val(),
                                    pin: $('#pin').val(),
                                    frm_date: $('#frm_date').val(),
                                    to_date: $('#to_date').val()*/
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
                                    //return meta.row + 1;
                                    return i++;
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    return `<span>${row.user_id}</span><span>(${row.fullname})</span>`;
                                }
                            },
                            { data: 'pin' },                            
                            { 
                                render: function (data, type, row, meta,) {
                                   return `<span>$${row.amount}</span>`;                                
                               } 
                           },
                           { data: 'top_up_by' },                            
                           { data: 'topupfrom' },
                           {
                              render: function(data, type, row, meta) {
                                if(row.cross_verify_status == 1)
                                {
                                    return `<label class="text-info">Valid</label>`;   
                                }else if(row.cross_verify_status == 2)
                                {
                                    return `<label class="text-danger">Invalid</label>`;   
                                }else if(row.cross_verify_status == 0)
                                {
                                    return `<label class="text-warning">Pending</label>`;   
                                }


                            }
                        },
                        {
                            render: function (data, type, row, meta) {
                                if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                  return `-`;
                              } else {
                                return moment(String(row.entry_time)).format('YYYY-MM-DD');
                            }
                        }
                    }
                    ]
                });

$('#onSearchClick').click(function () {
    table.ajax.reload();
});

$('#onResetClick').click(function () {
    $('#searchForm').trigger("reset");
    table.ajax.reload();
});


$('#topup-security-report').on('click','.changeStatus',function () {
    that.changeStatus($(this).data("id"),$(this).data("roi_stop_status"));
});

},0);
},
getSecurityWithdrawals(){
    let i = 0;
    let that = this;
    let token = localStorage.getItem('access_token');
    setTimeout(function(){
        const tablew = $('#withdraw-security-report').DataTable({
            responsive: true,
            retrieve: true,
            destroy: true,
            processing: false,
            serverSide: true,
            stateSave: false,
            ordering: false,
            dom: 'Brtip',
            lengthMenu: [[10, 50, 100], [10, 50, 100]],
            buttons: [
                            // 'copyHtml5',
                            /*'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5',*/
                            'pageLength',
                            ],
                            ajax: {
                                url: apiAdminHost+'/getSecurityWithdrawals',
                                type: 'POST',
                                data: function (d) {
                                    i = 0;
                                    i = d.start + 1;

                                    let params = {
                                    /*product_id: $('#product_id').val(),
                                    user_id: $('#user_id').val(),
                                    status: $('#status').val(),
                                    pin: $('#pin').val(),
                                    frm_date: $('#frm_date').val(),
                                    to_date: $('#to_date').val()*/
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
                    {
                        render: function (data, type, row, meta) {
                            return `<span>${row.user_id}</span><span>(${row.fullname})</span>`;
                        }
                    },
                    {
                        render: function (data, type, row, meta) {
                            return `$${Number(row.amount)}`;
                        }
                    },
                    {
                        render: function (data, type, row, meta) {
                            return `$${Number(row.deduction)}`;
                        }
                    },
                    {
                        render: function (data, type, row, meta) {
                            return `$${Number(row.amount)-Number(row.deduction)}`;
                        }
                    },                        
                    { data: 'network_type' },
                    { data: 'wallet_type' },
                    /*{ data: 'to_address' },*/
                    {
                      render: function(data, type, row, meta) {
                        if(row.cross_verify_status == 1)
                        {
                            return `<label class="text-info">Valid</label>`;   
                        }else if(row.cross_verify_status == 2)
                        {
                            return `<label class="text-danger">Invalid</label>`;   
                        }else if(row.cross_verify_status == 0)
                        {
                            return `<label class="text-warning">Pending</label>`;   
                        }
                        
                        
                    }
                },
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

$('#onSearchClick').click(function () {
    tablew.ajax.reload();
});

$('#onResetClick').click(function () {
    $('#searchForm').trigger("reset");
    tablew.ajax.reload();
});

},0);
},

onResetClick() { 
    $('#searchForm').trigger("reset");
},

checkuserexist() {
    axios.post('checkuserexist',{
       user_id:this.user_id,
   }).then(response => {                     
    if(response.data.code == 404) {
                        // console.log(response.data.message);                     
                        this.userExistsMessage = response.data.message;
                        this.custom_msg_class = 'text-danger';                        
                    }else{
                        //console.log(response.data.data.id);
                        this.hiddenUserId = response.data.data.id;
                        this.userExistsMessage = response.data.message;
                        this.custom_msg_class = 'text-success';
                    }
                }).catch(error => {
                    //console.log(error);
                    this.message  = '';
                    this.flash(this.message, 'error', {
                      timeout: 500000,
                  });
                });
            }
        }
    }
    
    </script>