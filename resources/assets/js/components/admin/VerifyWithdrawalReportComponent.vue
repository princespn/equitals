<template>
    <!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Verified Withdraw Request Report</h4>
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
                                        <div class="col-md-3"></div>
                                         <div class="col-md-2">
                                             <div class="form-group">
                                            <label>From Date</label>
                                            <div>
                                                <div class="input-group">
                                                     <DatePicker :bootstrap-styling="true" name="frm_date" :format="dateFormat" placeholder="From Date" id="frm_date"></DatePicker>
                                                    <!-- <input type="text" class="form-control" placeholder="From Date" id="datepicker"> -->
                                                    <span class="input-group-addon bg-custom b-0">
                                                        <i class="mdi mdi-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="col-md-2">
                                                <div class="form-group">
                                            <label>To Date</label>
                                            <div>
                                                <div class="input-group">
                                                    <DatePicker :bootstrap-styling="true" name="to_date" :format="dateFormat" placeholder="To Date" id="to_date"></DatePicker>
                                                    <!-- <input type="text" class="form-control" placeholder="To Date" id="datepicker-autoclose"> -->
                                                    <span class="input-group-addon bg-custom b-0">
                                                        <i class="mdi mdi-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                         <div class="col-md-2">
                                            <div class="form-group">
                                                <label>User Id</label>
                                                <input class="form-control"  placeholder="Enter user id" type="text" id="user_id" f>
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
                    <div class="col-md-3 col-sm-6" v-for="summary in withdrawsummary">
                        <div class="panel">
                            <div class="panel-body">
                                <h5 class="panel-title text-muted">
                                   Total {{summary.currency}} Amount <span class="pull-right">{{summary.total_amount}}</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>    
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <table id="withdraw-verifiedrequest-report" class="table table-striped table-bordered dt-responsive" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>
                                                <input type="checkbox" name="allCheck" class="allCheck"/>Select All
                                            </th>
                                            <!-- <th>
                                                <input type="checkbox" id="allCheck"/>Select All
                                            </th> -->
                                            <th>Action</th>
                                            <th>User Id</th>
                                            <th>Amount</th>
                                            <th>Deduction</th>
                                            <th>Net Amount</th>
                                            <th>Network Mode</th>
                                            <th>Wallet</th>
                                            <th>Address</th>
                                            <th>Status</th>
                                            <th>Date</th>

                                           <!--  <th>Bank Account Number</th>

                                            <th>Bank Account Number</th>

                                            <th>Bank Branch Name</th>
                                            <th>A/C Holder Name</th>
                                            <th>Pan Card Number</th>
                                            <th>Bank Name</th>
                                            <th>IFSC Code</th> -->
                                        </tr>
                                    </thead>
                                    <!-- <tfoot>
                                        <tr>
                                             <th>Sr.No</th>
                                            <th>
                                                <input type="checkbox" class="allCheck"/>Select All
                                            </th>
                                            <th>Action</th>
                                            <th>User Id</th>
                                            <th>Amount</th>
                                            <th>Network Mode</th>
                                            <th>Address</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </tfoot> -->
                                </table>
                            </div>
                             <div class="row" style="padding-bottom: 15px;">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-2">
                                            <h4>Remark:</h4>
                                        </div>
                                        <div>
                                            <div class="col-md-4">
                                                <textarea class="form-control rounded-0" id="remark" placeholder="Enter remark here" rows="3" v-model="remark"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-info waves-effect waves-light" @click="onMakePaymentClick">Make Payment</button>
                                            <!-- <button  @click.prevent="sendOTP()" id="otpSend">Send OTP</button> -->
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div><!-- end row -->
            </div><!-- container -->
            <div class="modal fade" id="add-remark-modal">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="fa fa-times"></span></button>
                             <h5 class="modal-title" id="exampleModalLabel">Add Remark</h5>
                        </div>
                        <div class="modal-body">
                            <div class="row form-group">
                                <div class="col-md-2">
                                    <label>Remark</label>
                                </div>
                                <div class="col-md-10">
                                    <textarea class="form-control" v-model="remark" name="remark" id="remark"></textarea>
                                </div>
                            </div>
                             <div class="row form-group">
                                <div class="col-md-2">
                                    <label>OTP</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" v-model="app_otp" name="app_otp" id="app_otp" required placeholder="Enter OTP"  />
                                 </div>
                            </div>
                        </div>
                        <div class="modal-footer text-right">
                            <button type="button" class="btn btn-info" @click="withdrawalReject" :disabled="isdisabledR">Submit</button>
                            <button type="button" class="btn" data-dismiss="modal" aria-label="Close">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--<div class="modal fade" id="add-otp-modal">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="fa fa-times"></span></button>
                             <h5 class="modal-title" id="exampleModalLabel">Enter OTP</h5>
                        </div>
                        <div class="modal-body">
                            <div class="row form-group">
                                <div class="col-md-2">
                                    <label>Enter OTP</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" v-model="admin_otp" name="admin_otp" id="admin_otp" required />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer text-right">
                            <button type="button" class="btn btn-info" @click="withdrawalConfirm" :disabled="isdisabled">Submit</button>
                            <button type="button" class="btn" data-dismiss="modal" aria-label="Close">Cancel</button>
                        </div>
                    </div>
                </div>
            </div> -->


            <!-- Approve E-pin -->
        <div class="modal fade" id="approve-Pin" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <form class="clearForm">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Approve Request</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Request Id</label>
                                        <input class="form-control" required="" placeholder="request Number" type="text" v-model="pinRequestIdForApprovePin" name="pinRequestIdForApprovePin" v-validate="'required'" readonly>
                                        <div class="tooltip2" style="top:137px" v-show="errors.has('pinRequestIdForApprovePin')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('pinRequestIdForApprovePin')">{{ errors.first('pinRequestIdForApprovePin') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Remark</label>
                                        <textarea class="form-control" required="" placeholder="Enter remark" type="text" v-model="remarkForApprovePin" name="remarkForApprovePin" v-validate="'required'"></textarea>
                                        <div class="tooltip2" style="top:274px" v-show="errors.has('remarkForApprovePin')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('remarkForApprovePin')">{{ errors.first('remarkForApprovePin') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>OTP</label>
                                        <input type="text" class="form-control" v-model="app_otp" name="app_otp" id="app_otp" required placeholder="Enter OTP"  />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="onManualApprove" :disabled="errors.any() || !isComplete" class="btn btn-primary waves-effect waves-light" >Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        </div><!-- Page content Wrapper -->
    </div><!-- content -->
</template>

<script>
    import { apiAdminHost } from'./../../admin-config/config';
    import moment from 'moment';
    import DatePicker from 'vuejs-datepicker';
    import Swal from 'sweetalert2';
    var flagotp = 0;
    export default {
        data() {
            return {
                user_data  : [],
                products   : [],
                length : 10,
                start  : 0,
                arrayForSelectedCheckbox:[],
                remark:'',
                sr_no:'',
                app_otp:'',
                isdisabled:false,
                isdisabledR:false,
                pinRequestIdForApprovePin: '',
                remarkForApprovePin: '',
                export_url:'',
                withdrawsummary:[],
                otpSend : 1



            }
        },
        mounted() {
            
            this.withdrawRequestReport();
            this.getWithdrawalSummary();
        },
        computed: {
                isComplete() {
                        return this.remarkForApprovePin && this.pinRequestIdForApprovePin;
                    },
            },
        components: {
            DatePicker
        },
        methods: {
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            withdrawRequestReport(){
                let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                   that.table = $('#withdraw-verifiedrequest-report').DataTable({
                        responsive: true,
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        responsive: true,
                        stateSave: false,
                        ordering: false,
                        dom: 'Bfrtip',
                        buttons: [
                            'pageLength',
                            /*{
                                extend: 'excelHtml5',
                                title: 'Verified Withdraw Request Report',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },*/
                        ],
                        ajax: {
                            url: apiAdminHost+'/getwithdrwalverified',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                     frm_date: $('#frm_date').val(),
                                     to_date: $('#to_date').val(),
                                     user_id: $('#user_id').val()
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
                                    return `<input data-id="${row.sr_no}" type="checkbox" class="myCheck" value="${row.sr_no}">`;
                                }
                            },
                            {
                               render: function (data, type, row, meta) {
                                    return `<a href="javaScript:void(0);" class="text-info waves-effect manualapproverequest" id="appr${row.sr_no}" data-id="${row.sr_no}">M-Approve
                                            </a><br> 
                                            <a href="javaScript:void(0);" class="text-danger waves-effect changestatus" data-id="${row.sr_no}" data-status="${row.status}">Reject
                                            </a>`;
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    return `<span>${row.user_id}</span><span>(${row.fullname})</span>`;
                                }
                            },
                            // { render: function (data, type, row, meta) {
                            //     if(row.withdraw_type == 6)
                            //          {
                            //             return `Principal`;
                            //         } else{
                            //             return `Working`;
                            //         }
                                
                            //     } 
                            // },
                            {
                                render: function (data, type, row, meta) {
                                    return `$${Number(row.amount) + Number(row.deduction)}`;
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    return `$${row.deduction}`;
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    return `$${row.amount}`;
                                }
                            },
                            { data: 'network_type' },
                            { data: 'withdraw_type' },
                            { data: 'to_address' },
                            { data: 'status' },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.entry_time)).format('YYYY-MM-DD');
                                    }
                                }
                            },
                            // { data: 'account_no' },
                            // { data: 'branch_name' },
                            // { data: 'holder_name' },
                            // { data: 'pan_no' },
                            // { data: 'bank_name' },
                            // { data: 'ifsc_code' },
                        ]
                    });
                    
                    $('#onSearchClick').click(function () {
                        that.table.ajax.reload();
                    });

                    $('#onResetClick').click(function () {
                        $('#searchForm').trigger("reset");
                        that.table.ajax.reload();
                    });

                    $('#withdraw-verifiedrequest-report').on('click','.changestatus',function () {
                        that.sendOTPReject($(this).data("id"),$(this).data("status"),4);
                    });

                    $('#withdraw-verifiedrequest-report').on('click','.manualapproverequest',function () {
                        if (that.table.row($(this).parents('tr')).data() !== undefined) {
                                    var data = that.table.row($(this).parents('tr')).data();
                                    that.sendOTP(data, that.table,3);
                                  
                                } else {
                                    var data = that.table.row($(this)).data();
                                    that.sendOTP(data, that.table,3);
                                    
                                }
                    });
                    /*$('#withdraw-verifiedrequest-report').on('click','.manualapproverequest',function () {
                        $('#otpSend').trigger("click");
                       
                    });*/
                    $('#withdraw-verifiedrequest-report').on('click','.approverequest',function () {
                       that.showOTPPopup($(this).data("id")); 
                    });
                    $('#withdraw-verifiedrequest-report').on('click', '.myCheck', function () {
                        $('.allCheck').prop('checked', false);
                        if ($(this).is(':checked')) {
                            that.arrayForSelectedCheckbox.push($(this).data('id'));
                        } else if (!$(this).is(':checked')) {
                            that.arrayForSelectedCheckbox.splice(that.arrayForSelectedCheckbox.indexOf($(this).data('id')), 1);
                        }
                    });

                    $('#withdraw-verifiedrequest-report').on('click','.allCheck',function(){
                        that.arrayForSelectedCheckbox = [];
                        if ($('.allCheck').is(':checked')) {
                            $('input[type="checkbox"].myCheck').prop('checked', true);
                            $('.myCheck').each(function(i, obj) {
                                that.arrayForSelectedCheckbox.push($(this).data('id'));
                            });
                        } else if(!$('.allCheck').is(':checked')) {
                            $('input[type="checkbox"].myCheck').prop('checked', false);
                            $('.myCheck').each(function(i, obj) {
                                that.arrayForSelectedCheckbox.splice(that.arrayForSelectedCheckbox.indexOf($(this).data('id')), 1);
                            });
                        }
                    });
                },0);
            },
           ApprovePayment(data, table) {
            //   this.sendOTP();

                    /*console.log(data); */
                    this.sr_no = data.sr_no;
                    this.pinRequestIdForApprovePin = this.sr_no;
                    this.tbl = table;
                    $('#approve-Pin').modal();
                    // if(this.otpSend == 1){
                    //    this.otpSenalert(this.otpSend);d
                    // }

            },
            sendOTP(data1, tbl,otp_type){
                this.sr_no = data1.sr_no;
                
                this.pinRequestIdForApprovePin = this.sr_no;
                this.tbl = tbl;
                var arr = {
                    otp_type:otp_type
                };
                if (flagotp == 1) {return false;} else {
                flagotp = 1;
                axios
                    .post("sendOtp-add-topup", arr)
                    .then((response) => {
                    if (response.data.code == 200) {
                        //console.log(response);
                        $('#approve-Pin').modal();
                        this.$toaster.success(response.data.message);
                        flagotp = 0;
                        //this.statedata=response.data.data.message;
                        this.app_otp = "";
                    } else {
                        flagotp = 0;
                        this.$toaster.error(response.data.message);
                    }
                    })
                    .catch((error) => {});
                }
            },
        
            onManualApprove() {
                  axios.post('/approveWithdraw',{
                                id: this.pinRequestIdForApprovePin,
                                remark:this.remarkForApprovePin,
                                otp:this.app_otp
                            }).then(response => {
                            if (response.data.code === 200) {
                                this.$toaster.success(response.data.message);
                                this.tbl.ajax.reload();
                                $("#approve-Pin").modal("hide");
                            } else {
                                this.$toaster.error(response.data.message);
                                this.errmessage = response.data.message;
                            }
                        }).catch(error => {
                            this.$toaster.error(error.response.data.message);
                        });
            },
            changeStatus(id, status='2'){
                this.sr_no=id;
                Swal({
                    title: 'Are you sure?',
                    text: `You want to reject this request`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                       $("#add-remark-modal").modal();
                    }
                });
            },

            sendOTPReject(id, status='2',otp_type){

                 this.sr_no=id;
                Swal({
                    title: 'Are you sure?',
                    text: `You want to reject this request`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                    var arr = {
                        otp_type:otp_type
                    };
                    if (flagotp == 1) {return false;} else {
                    flagotp = 1;
                    axios
                        .post("sendOtp-add-topup", arr)
                        .then((response) => {
                        if (response.data.code == 200) {
                            //console.log(response);
                            $('#add-remark-modal').modal();
                            this.$toaster.success(response.data.message);
                            flagotp = 0;
                            //this.statedata=response.data.data.message;
                            this.app_otp = "";
                        } else {
                            flagotp = 0;
                            this.$toaster.error(response.data.message);
                        }
                        })
                        .catch((error) => {});
                        }

                }
                });
            },
                
            withdrawalReject(id, status='2'){
               
                this.isdisabledR=true;
                axios.post('/reject/withdrwalrequest',{
                    sr_no: this.sr_no,
                    remark:this.remark
                }).then(resp => {
                    if(resp.data.code == 200){
                        this.$toaster.success(resp.data.message);
                        $("#add-remark-modal").modal('hide');                        
                        this.table.ajax.reload();
                    } else {
                        this.$toaster.error(resp.data.message);
                    }
                    this.sr_no='';
                    this.remark='';
                        $(".close").trigger('click');
                        $(".close").trigger('click');
                }).catch(err => {

                })
                    
            },               
            // showOTPPopup(sr_no){
            //     this.sr_no=sr_no;
            //     $("#add-otp-modal").modal();
            // },
            withdrawalConfirm(){
                this.isdisabled=true;
                axios.post('/send/withdrwalrequest',{
                    request_id: this.sr_no,
                    admin_otp:this.admin_otp
                }).then(resp => {
                    if(resp.data.code == 200){
                        this.$toaster.success(resp.data.message);                        
                        this.table.ajax.reload();
                    } else {
                        this.$toaster.error(resp.data.message);
                    }
                    this.sr_no='';
                    this.admin_otp='';
                    $(".close").trigger('click');
                    $(".close").trigger('click');
                }).catch(err => {

                })
            },
            onMakePaymentClick() {
                axios.post('/approve/withdrawalrequest',{
                    request_id: this.arrayForSelectedCheckbox,
                    remark: this.remark
                }).then(resp => {
                    if(resp.data.code === 200){
                        
                        //this.$router.push({name:'confirmwithdrawalreport'});
                        this.$toaster.success(resp.data.message);
                        $('#withdraw-verifiedrequest-report').DataTable().ajax.reload();
                    } else {
                        this.$toaster.error(resp.data.message);
                    }
                }).catch(err => {
                    this.$toaster.error(err);
                })
            },

            exportToExcel(){
                var params = {frm_date: $('#frm_date').val(), to_date: $('#to_date').val(),user_id: $('#user_id').val(),action:'export',responseType: 'blob' };
                axios.post('getwithdrwalverified', params).then(resp => {
                    if(resp.data.code === 200){
                        //this.export_url = resp.data.data.excel_url;
                        var mystring = resp.data.data.data;
                        var myblob = new Blob([mystring], {
                            type: 'text/plain'
                        });

                        var fileURL = window.URL.createObjectURL(new Blob([myblob]));
                        var fileLink = document.createElement('a');

                        fileLink.href = fileURL;
                        fileLink.setAttribute('download', 'verifiedwithdrawal.xls');
                        document.body.appendChild(fileLink);

                        fileLink.click();
                    }else{    
                        this.$toaster.error(resp.data.message)
                    }    
                });
            },
            getWithdrawalSummary(){
                axios.post('getWithdrawalSummary',{status: 1,verify_status: 1}).then(resp => {
                    if(resp.data.code === 200){
                        this.withdrawsummary = resp.data.data;
                    } else {
                        this.$toaster.error(resp.data.message);
                    }
                });    
            }
        }
    }
</script>