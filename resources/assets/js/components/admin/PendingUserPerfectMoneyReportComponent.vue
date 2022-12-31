<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Pending PerfectMoney Report</h4>
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
		                                <div class="row">
		                                    <div class="col-md-12">
		                                        <div class="text-center">
		                                            <button type="button" class="btn btn-primary waves-effect waves-light" id="onSearchClick">Search</button>
		                                            <!-- <button type="button" class="btn btn-info waves-effect waves-light">Export To Excel</button> -->
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
                              <table id="pending-perfectmoney-report" class="table table-striped table-bordered dt-responsive">
                                   
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Action</th>
                                            <th>Username</th>
                                            <th>USD</th>
                                            <th>Payee Name</th>
                                            <th>Payee Account</th>
                                            <th>Payer Account</th>
                                            <th>Payment Id</th>
                                            <th>Batch No.</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Action</th>
                                            <th>Username</th>
                                            <th>USD</th>
                                            <th>Payee Name</th>
                                            <th>Payee Account</th>
                                            <th>Payer Account</th>
                                            <th>Payment Id</th>
                                            <th>Batch No.</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                      </tr>
                                    </tfoot>
                                </table>      
                                </div>
		                    </div>
		                </div>
		            </div>
		        </div><!-- end row -->
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
                                        <!-- 
                                            <div class="form-group hidden">
                                                <label>Request Id</label>
                                                <input class="form-control" required="" placeholder="request Number" type="text" v-model="pinRequestIdForApprovePin" name="pinRequestIdForApprovePin" v-validate="'required'" disabled readonly>
                                                <div class="tooltip2" style="top:137px" v-show="errors.has('pinRequestIdForApprovePin')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('pinRequestIdForApprovePin')">{{ errors.first('pinRequestIdForApprovePin') }}</span>
                                                    </div>
                                                </div>
                                            </div> -->
                                            
                                            <div class="form-group">
                                                <label>Remark</label>
                                                <textarea class="form-control" required="" placeholder="Enter remark" type="text" v-model="remarkForApprove" name="remarkForApprove" v-validate="'required'"></textarea>
                                                <div class="tooltip2" style="top:274px" v-show="errors.has('remarkForApprove')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('remarkForApprove')">{{ errors.first('remarkForApprove') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" @click="onManualApprove" :disabled="errors.any() || !isComplete || isdisabledA" class="btn btn-primary waves-effect waves-light" data-dismiss="modal">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
                            </div>
                            <div class="modal-footer text-right">
                                <button type="button" class="btn btn-info" @click="rejectPMRequest" :disabled="isdisabledR">Submit</button>
                                <button type="button" class="btn" data-dismiss="modal" aria-label="Close">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
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
    import Swal from 'sweetalert2';
    export default {
        data() {
            return {
                frm_date : null,
                to_date : null,
                depositaddress : {},
                pinRequestIdForApprovePin:'',
                remarkForApprove:'',
                isComplete:true,
                isdisabledR:false,
                isdisabledA:false,
                remark:'',
                errmessage:'',
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
                    that.table = $('#pending-perfectmoney-report').DataTable({
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
                            // 'excelHtml5',
                            // 'csvHtml5',
                            // 'pdfHtml5',
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
                                    in_status: 0,
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
                            {
                               render: function (data, type, row, meta) {
                                    return `<a href="javaScript:void(0);" class="text-info waves-effect manualapproverequest" data-id="${row.srno}">M-Approve
                                            </a><br>
                                            <a href="javaScript:void(0);" class="text-danger waves-effect changestatus" data-id="${row.srno}">Reject
                                            </a>`;
                                }
                            },
                            { data: "user_id" },
                            { data: "price_in_usd" },
                            // { data: "currency_price" },

                            { data: "payee_account_name" },
                            { data: "payee_account" },
                            { data: "payer_account" },
                            { data: "payment_id" },
                            { data: "trans_hash" },

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
                        ]
                    });
                    $('#pending-perfectmoney-report').on('click','#deposite-address', function (){
                        //$('#deposit-address-model').modal();      
                        if (that.table.row($(this).parents('tr')).data() !== undefined) {
                            var data = that.table.row($(this).parents('tr')).data();
                            that.OnShowPinxClick(data);
                        } else {
                            var data = that.table.row($(this)).data();
                            that.OnShowPinxClick(data);
                        }
                    });
                    $('#pending-perfectmoney-report').on('click','.changestatus',function () {
                        that.changeStatus(that.table,$(this).data("id"));
                    });

                    $('#pending-perfectmoney-report').on('click','.manualapproverequest',function () {
                        if (that.table.row($(this).parents('tr')).data() !== undefined) {
                            var id = $(this).data("id");
                            var data = that.table.row($(this).parents('tr')).data();
                            that.ApprovePayment(id,data, that.table);
                        } else {
                            var id = $(this).data("id");
                            var data = that.table.row($(this)).data();
                            that.ApprovePayment(id,data, that.table);
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

           ApprovePayment(id,data, table) {
                Swal({
                    title: 'Are you sure ?',
                    text: "You are going to approve Payment!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    this.sr_no = id;/*data.srno;*/
                    this.pinRequestIdForApprovePin = this.sr_no;
                    this.tbl = table;
                    this.isdisabledA=false;
                    this.remarkForApprove = "";
                    $('#approve-Pin').modal();
                });
            },
           onManualApprove() {
                this.isdisabledA=true;
                axios.post('/approvePMRequest',{id: this.sr_no,remark:this.remarkForApprove}).then(response => {
                    if (response.data.code === 200) {
                        this.$toaster.success(response.data.message);
                        this.tbl.ajax.reload();
                    } else {
                        this.$toaster.error(response.data.message);
                        this.errmessage = response.data.message;
                        this.isdisabledA=false;
                    }
                }).catch(error => {
                    this.$toaster.error(error.response.data.message);
                });
            },
            changeStatus(table,id, status='2'){
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
                        this.tbl = table;
                        this.remark = "";
                        this.isdisabledR=false;
                        $("#add-remark-modal").modal();
                    }
                });
            },
            rejectPMRequest(){
                this.isdisabledR=true;
                axios.post('rejectPMRequest',{
                    id: this.sr_no,
                    remark:this.remark
                }).then(resp => {
                    if(resp.data.code == 200){
                        this.$toaster.success(resp.data.message);
                        this.remark = "";
                        $("#add-remark-modal").modal('hide');                        
                        this.tbl.ajax.reload();
                       /*$('#pending-perfectmoney-report').DataTable.ajax.reload();*/
                    } else {
                        this.$toaster.error(resp.data.message);
                        this.isdisabledR=false;
                    }
                    this.sr_no='';
                    this.remark='';
                        $(".close").trigger('click');
                        $(".close").trigger('click');
                }).catch(err => {

                })
        }
    }
    }
</script>