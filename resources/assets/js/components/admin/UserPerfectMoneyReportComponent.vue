<template>
    <!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">PerfectMoney Report</h4>
            </div>
        </div>

        <div class="page-content-wrapper">
            <div class="container">
                <form id="searchForm">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-body">
                                    <div class="">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>From Date</label>
                                                <div class="input-group">
                                                    <DatePicker :bootstrap-styling="true" v-model="frm_date" name="frm_date" :format="dateFormat" placeholder="From Date" id="frm_date"></DatePicker>
                                                    <span class="input-group-addon bg-custom b-0 datepicker_border">
                                                        <i class="mdi mdi-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>To Date</label>
                                                <div class="input-group">
                                                    <DatePicker :bootstrap-styling="true" v-model="to_date" name="to_date" :format="dateFormat" placeholder="To Date" id="to_date"></DatePicker>
                                                    <span class="input-group-addon bg-custom b-0 datepicker_border">
                                                        <i class="mdi mdi-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>To User Id</label>
                                                <input class="form-control" required="" placeholder="Enter user id" type="text" id="to_user_id">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Select Status</label>
                                                <select id="in_status" name="tran_status"  class="form-control">
                                                    <option value="">Select Status</option>

                                                    <option value="0"> Pending</option>
                                                    <option value="1"> Confirm</option>
                                                    <option value="2"> Rejected</option>
                                                  
                                                </select>
                                            </div> 
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="text-center">
                                                    <button type="button" class="btn btn-primary waves-effect waves-light" id="onSearchClick">Search</button>
                                                    <button type="button" class="btn btn-info waves-effect waves-light" @click="exportToExcel">Export To Excel</button>
                                                    <button type="button" class="btn btn-dark waves-effect waves-light mt-4" id="onResetClick">Reset</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- panel-body -->
                            </div><!-- panel -->
                        </div><!-- col -->
                    </div>
                </form>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <div class="table-responsive">
                              <table id="user-perfectmoney-report" class="table table-striped table-bordered dt-responsive">
                                   
                                    <thead>
                                          <tr>
                                            <th>Sr No</th>
                                            <th>Username</th>
                                            <th>USD</th>
                                            <th>Payee Account Name</th>
                                             <th>Payee Account</th>
                                             <th>Payer Account</th>
                                             <th>Payment Id</th>
                                             <th>Status</th>
                                            <th>Date</th>
                                            <th>Remark</th>
                                          </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                        <th>Sr No</th>
                                        <th>Username</th>
                                         <th>USD</th>
                                        <th>Payee Account Name</th>
                                         <th>Payee Account</th>
                                         <th>Payer Account</th>
                                         <th>Payment Id</th>
                                         <th>Status</th>
                                        <th>Date</th>
                                            <th>Remark</th>
                                      </tr>
                                    </tfoot>
                                </table>      
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- end row -->
<!-- 
                <div class="modal fade" id="deposit-address-model">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="fa fa-times"></span></button>
                                 <h5 class="modal-title" id="exampleModalLabel">More Details</h5>
                            </div>
                            <div class="modal-body">
                               <div  class="table-responsive">
                                    <table cellspacing="0" class="table table-bordered table-striped" id="order-listing" width="100%">
                                        <thead>
                                            <tr>
                                                <th> Confirmation Remark </th>
                                                <th> Confirm Date </th>
                                                <th> IP Address </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ depositaddress.confirm_remark }}</td>
                                                <td>{{ depositaddress.confirm_date }}</td>
                                                <td>{{ depositaddress.ip_address }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

            </div><!-- container -->
        </div><!-- Page content Wrapper -->
    </div><!-- content -->
</template>

<script>
    import moment from 'moment';
    import DatePicker from 'vuejs-datepicker';
    import { apiAdminHost } from'./../../admin-config/config';
    export default {
        data() {
            return {
                frm_date : null,
                to_date : null,
                depositaddress : {},
            }
        },
        components: {
            DatePicker
        }, 
        mounted() {
            this.productReport();
        },
        methods: {
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            productReport(){
                let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
              
                setTimeout(function(){
                    that.table = $('#user-perfectmoney-report').DataTable({
                        responsive: true,
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        stateSave: false,
                        ordering: false,
                        dom: 'Bfrtip',
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                        buttons: [
                            // 'copyHtml5',
                            /*'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5',*/
                            'pageLength',
                        ],
                        // ajax: {
                        //     url: apiAdminHost+'/getdepositaddrtrans',
                        //     type: 'POST',
                        //     data: function (d) {
                        //         i = 0;
                        //         i = d.start + 1;
                        //         let params = {
                        //             frm_date : $('#frm_date').val(),
                        //             to_date  : $('#to_date').val(),
                        //             id: $('#to_user_id').val(),
                        //         };
                        //         Object.assign(d, params);
                        //         return d;
                        //     },
                        //     headers: {
                        //       'Authorization': 'Bearer ' + token
                        //     },
                        //     dataSrc: function (json) {
                        //         if (json.code === 200) {
                        //             that.arrGetHelp = json.data.records;
                        //             json['draw'] = json.data.draw;
                        //             json['recordsFiltered'] = json.data.filterRecord;
                        //             json['recordsTotal'] = json.data.totalRecord;
                        //             return json.data.record;
                        //         } else {
                        //             json['draw'] = 0;
                        //             json['recordsFiltered'] = 0;
                        //             json['recordsTotal'] = 0;
                        //             return json;
                        //         }
                        //     }
                        // },
                         ajax: {
                            url: apiAdminHost+'/getperfectmoneyreport',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    frm_date : $('#frm_date').val(),
                                    to_date  : $('#to_date').val(),
                                    to_date: $('#to_date').val(),
                                    id: $('#to_user_id').val(),
                                    in_status: $('#in_status').val(),
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
                            { data: "user_id" },
                            { data: "price_in_usd" },
                            // { data: "currency_price" },

                            { data: "payee_account_name" },
                            { data: "payee_account" },
                            { data: "payer_account" },
                            { data: "payment_id" },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.in_status == "Pending") {
                                      return `<label class="text-warning">`+row.in_status+`</label>`;
                                    } else if (row.in_status == "Confirm") {
                                      return `<label class="text-info">`+row.in_status+`</label>`;
                                    }else if (row.in_status == "Rejected") {
                                      return `<label class="text-danger">`+row.in_status+`</label>`;
                                    }else{
                                      return `-`;
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
                            { data: "remark" },
                        ]
                    });
                    $('#user-perfectmoney-report').on('click','#deposite-address', function (){
                        //$('#deposit-address-model').modal();      
                        if (that.table.row($(this).parents('tr')).data() !== undefined) {
                            var data = that.table.row($(this).parents('tr')).data();
                            that.OnShowPinxClick(data);
                        } else {
                            var data = that.table.row($(this)).data();
                            that.OnShowPinxClick(data);
                        }
                    });                    
                    $('#onSearchClick').click(function () {
                        that.table.ajax.reload();
                    });
                    $('#onResetClick').click(function () {  
                        $('#searchForm').trigger("reset");
                        that.table.ajax.reload();
                    });
                },0);                
            },
            OnShowPinxClick(data) {
                $('#deposit-address-model').modal();                 
                this.depositaddress = data;
            },
            exportToExcel(){
                var params = { frm_date: $('#frm_date').val(),to_date: $('#to_date').val(),id: $('#to_user_id').val(),in_status: $('#in_status').val(),action:'export',responseType: 'blob' };
                axios.post('getperfectmoneyreport', params).then(resp => {
                    if (resp.data.code == 200) {
                        //this.export_url = resp.data.data.excel_url;
                        var mystring = resp.data.data.data;
                        var myblob = new Blob([mystring], {
                            type: 'text/plain'
                        });

                        var fileURL = window.URL.createObjectURL(new Blob([myblob]));
                        var fileLink = document.createElement('a');

                        fileLink.href = fileURL;
                        fileLink.setAttribute('download', 'UserPerfectMoneyReport.xls');
                        document.body.appendChild(fileLink);

                        fileLink.click();
                    }else{    
                        this.$toaster.error(resp.data.message)
                    }
                });
            }
        }
    }
</script>