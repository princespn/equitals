<template>
    	<!-- Start content -->
    	<div class="content">
    		<div class="">
    		    <div class="page-header-title">
    		        <h4 class="page-title">Rejected INR Topup Request</h4>
    		    </div>
    		</div>

    		<div class="page-content-wrapper">
    		    <div class="container">	
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-body">
                                    <div>
                                        <form id="searchForm">
                                            <div class="row">
                                                <div class="col-md-2"></div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>From Date</label>
                                                        <div>
                                                            <div class="input-group">
                                                                <DatePicker :bootstrap-styling="true" name="frm_date" :format="dateFormat" placeholder="From Date" id="frm_date"></DatePicker>

                                                                <!-- <input type="text" class="form-control datepicker" placeholder="From Date" id="frm_date"> -->
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
                                                                <!-- <input type="text" class="form-control datepicker" placeholder="To Date" id="to_date"> -->
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
                                                        <input class="form-control" placeholder="Enter User Id" type="text" id="user_id">
                                                    </div>
                                                </div>
                                              <!--   <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>E-Pin Request Id</label>
                                                        <input class="form-control" placeholder="Enter E-Pin Request Id" type="text" id="pin_req_id">
                                                    </div>
                                                </div> -->
                                            </div>
                                            <div class="row">
                                                <div class="text-center">
                                                    <button class="btn btn-primary waves-effect waves-light" id="onSearchClick" type="button">Search</button>
                                                    <!-- <button class="btn btn-info waves-effect waves-light" type="button">Export To Excel</button> -->
                                                    <button class="btn btn-dark waves-effect waves-light mt-4" id="onResetClick" type="button">Reset</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>


                                </div>

                            </div>
                        </div>
                    </div>
    		        <div class="row">
    		            <div class="col-md-12">
    		                <div class="panel panel-primary">
    		                    <div class="panel-body">
    		                        <table id="pending-franchise-report" class="table table-striped table-bordered dt-responsive">
    		                            <thead>
    		                                <tr>
                                                <th>Sr.No</th>
                                                <th>User Id</th>              
                                                <th>Fullname</th>
                                                <th>Amount</th>
                                                <th>Attachment</th> 
                                                <th>Remark</th> 
                                                <th>Status</th>
                                                <th>Date</th>
    		                                </tr>
    		                            </thead>
    		                        </table>
    		                    </div>
    		                </div>
    		            </div>
    		        </div><!-- end row -->
    		    </div><!-- container -->
    		</div><!-- Page content Wrapper -->


  
    	</div><!-- content -->
</template>

<script>
   // import { apiAdminHost } from'./../../admin-config/config';
    import Swal from 'sweetalert2/dist/sweetalert2.js'
     import moment from 'moment';
    import DatePicker from 'vuejs-datepicker';
    /*import { formatDates } from'./../../helper'; */
    import { apiAdminHost } from'./../../admin-config/config';

    export default {
        data() {
            return {
                length : 10,
                start  : 0,
                arrProducts:[],
                arrayProductDetail:[],
                arrayAmountDetail:{},
                sum1:0,
                sum2:0,
                totalAmoubnt:0,
                totalQnty:0,
                verify_date:'',
                verifyRemark:'',
                pin_id:'',
                remark:'',
                commonid:'',
                pin_id:'',
                tbl:'',
                dialogImage:'',
                paymentModeForApprovePin:'',
                remarkForApprovePin:'',
                pinRequestIdForApprovePin:'',
                INR:'',
            }
        },
        mounted() { 
            this.getProjectSetting();
            this.getFundRequest();
           // this.getProducts();
        },
        components: {
            DatePicker
        },
        computed: {
            isComplete () {
                return this.paymentModeForApprovePin && this.remarkForApprovePin && this.pinRequestIdForApprovePin;
            },
            isCompleteforverify() {
                return /*this.verify_date && */this.verifyRemark;
            }
        },
        methods: {
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            getFundRequest(){
                
            	let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    that.table = $('#pending-franchise-report').DataTable({
                        responsive: true,
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        responsive: true,
                        stateSave: false,
                        ordering: false,
                        ajax: {
                            url: apiAdminHost+'/get-fund-request',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                status : 'Reject',
                                    frm_date: $('#frm_date').val(),
                                    to_date: $('#to_date').val(),
                                    user_id: $('#user_id').val(),
                                               
                                };
                                Object.assign(d, params);
                                return d;
                            },
                            headers: {
                              'Authorization': 'Bearer ' + token
                            },
                            dataSrc: function (json) {
                                if (json.code === 200) {
                                    i = 0;
                                    i = parseInt(json.data.start) + 1;
                                    //json['draw'] = json.data.draw;
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
                                    return meta.row + meta.settings._iDisplayStart + 1;
                                }
                            },
                            { data: 'user_id' },
                            { data: 'fullname' },
                             { render: function (data, type, row, meta,) {
                                   return `<span>$${row.amount}</span><span>(₹${row.amount * that.INR})</span>`;
                                
                                } 
                            },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.attachment === null) {
                                        return `<img src="public/admin_assets/images/no_image_available.png" width="70" height="70">`;
                                    } else {
                                        return `<a href="${row.attachment}" target="__blank"><img alt="" src="${row.attachment}" width="70" height="70"></a>`;
                                    }
                                }
                            },
                            { data: 'admin_remark' },
                            { data: 'status' },
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
                        that.table.ajax.reload();
                    });

                    $('#onResetClick').click(function () {
                        $('#searchForm').trigger("reset");
                        that.table.ajax.reload();
                    });
                    
                       /*  Appprove details*/
                    $('#pending-franchise-report tbody').on('click', '#onApproveClick', function () 
                    {
                        that.onApproveClick($(this).data('id'));
                    });
                    $('#pending-franchise-report tbody').on('click', '#onRejectClick', function () 
                    {   
                       that.onRejectClick($(this).data('id'));
                    });
                 

                },0);
            },
            getProjectSetting(){
                axios.get('getprojectsettings', {
            })
            .then(response => {
                this.INR = response.data.data['USD-to-INR'];
            })
            .catch(error => {
                }); 
            }, 
   }
}
</script>	