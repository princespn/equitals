<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Withdraw Confirmed</h4>
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
    		                                        <DatePicker :bootstrap-styling="true" name="frm_date" :format="dateFormat" placeholder="From Date" id="frm_date"></DatePicker>
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
    		                                        <DatePicker :bootstrap-styling="true" name="to_date" :format="dateFormat" placeholder="To Date" id="to_date"></DatePicker>
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
                                                <label>Country</label>
                                               <select
                                                  v-model="country"
                                                  name="country"
                                                  id="country"
                                                  class="form-control"
                                                >
                                                  <option disabled value="" selected>Select Country</option>
                                                  <option
                                                    v-for="co in countries"
                                                    v-bind:value="co.iso_code"
                                                  :key = "co">{{ co.country }}</option>
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
                    <div class="col-md-3 col-sm-6" v-for="(summary,index) in withdrawsummary" v-bind:key="index">
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
		                        <table id="confirm-withdrawal-report" class="table table-striped table-bordered dt-responsive" style="width:100%">
		                            <thead>
		                                <tr>
		                                    <th>Sr.No</th>
                                            <!-- <th>More Details</th> -->
                                            <th>User Id</th>
                                            <th>Total Amount</th>
                                            <th>Deduction</th>
                                            <th>Net Amount</th>
                                            <th>Network Type</th>
                                            <th>Wallet</th>
                                            <th>To Address</th>
                                          <!--   <th>Paypal Address</th> -->
                                             <th>Country</th>
                                           <!--  <th>Perfect Money add</th> -->
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Remark</th>
		                                    
		                                </tr>
		                            </thead>
                                   <!--  <tfoot>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>More Details</th>
                                            <th>User Id</th>
                                            <th>Total Amount</th>
                                            <th>Deduction</th>
                                            <th>Net Amount</th>
                                            <th>To Address</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            
                                        </tr>
                                    </tfoot> -->
		                        </table>
		                    </div>
		                </div>
		            </div>
		        </div><!-- end row -->

                <div class="modal fade" id="confirm-withdrawal-model">
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
                                                <th> Transaction Hash </th>
                                                <th> From Address </th>
                                                <th> To Address </th>
                                                <th> Remark </th>
                                                <th> Network Type </th> 
                                               <!--  <th> Bank Account Number </th> 
                                                <th> Bank Branch Name </th> 
                                                <th> A/C Holder Name </th> 
                                                <th> Pan Card Number </th> 
                                                <th> Bank Name </th> 
                                                <th> IFSC Code </th>   --> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ confirmWithdrawal.transaction_hash }}</td>
                                                <td>{{ confirmWithdrawal.from_address }}</td>
                                                <td>{{ confirmWithdrawal.to_address }}</td>
                                                <td>{{ confirmWithdrawal.remark }}</td>
                                                <td>{{ confirmWithdrawal.network_type }}</td>
                                                <!--<td>{{ confirmWithdrawal.account_no }}</td>
                                                <td>{{ confirmWithdrawal.branch_name }}</td>
                                                <td>{{ confirmWithdrawal.holder_name }}</td>
                                                <td>{{ confirmWithdrawal.pan_no }}</td>
                                                <td>{{ confirmWithdrawal.bank_name }}</td>
                                                <td>{{ confirmWithdrawal.ifsc_code }}</td> -->
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
                confirmWithdrawal : {},
                countries:[],
                country:'',
                export_url:'',
                withdrawsummary:{}
            }
        },
        components: {
            DatePicker
        }, 
        mounted() {
            this.productReport();
            this.getCountry();
            this.getWithdrawalSummary();
        },
        methods: {
            getCountry() {
              axios
                .get("../country", {})
                .then(response => {
                  this.countries = response.data.data;
                })
                .catch(error => {});
            },
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            productReport(){
                let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
              
                setTimeout(function(){
                     that.table = $('#confirm-withdrawal-report').DataTable({
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
                          ajax: {
                            url: apiAdminHost+'/getwithdrwalconfirmed',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;
                                let params = {
                                    frm_date: $('#frm_date').val(),
                                    to_date: $('#to_date').val(),
                                    id: $('#to_user_id').val(),
                                    country: $('#country').val(),
                                };
                                Object.assign(d, params);
                                return d;
                            },
                            headers: {
                              'Authorization': 'Bearer ' + token
                            },
                            dataSrc: function (json) {
                                if (json.code === 200) {

                                    i = parseFloat(json.data.start) + 1;
                                    json['recordsFiltered'] = json.data.recordsFiltered;
                                    json['recordsTotal'] = json.data.recordsTotal;
                                    return json.data.records;
                                } else {
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
                            /*{
                               render: function (data, type, row, meta) {
                                    return '<label class="text-success waves-effect" id="confirmWithdrawal">Details</label>';
                                    
                                }
                            },*/
                            {
                                render: function (data, type, row, meta) {
                                    return `<span>${row.user_id}</span><span>(${row.fullname})</span>`;
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    {
                                        var total_amount = row.amount + row.deduction;
                                        return `<span>$${total_amount}</span>`;
                                    }
                                } 
                            },
                            {
                                render: function (data, type, row, meta) {
                                    return `$${Number(row.deduction)}`;
                                }
                            },
                             {
                                render: function (data, type, row, meta) {
                                    return `$${Number(row.amount)}`;
                                }
                            },
                            { data: 'network_type' },
                            { data: 'withdraw_type' },
                            { data: 'to_address' },
                          /*  { data: 'paypal_address' },*/
                             { data: 'country' },
                          /*  { data: 'perfect_money_address' },*/
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
                            {data:'remark'}
                            
                        ]
                    });
                    $('#confirm-withdrawal-report').on('click','#confirmWithdrawal', function (){
                        //$('#confirm-withdrawal-model').modal();      
                        if (table.row($(this).parents('tr')).data() !== undefined) {
                            var data = table.row($(this).parents('tr')).data();
                            that.OnShowPinxClick(data);
                        } else {
                            var data = table.row($(this)).data();
                            console.log(data);
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
                $('#confirm-withdrawal-model').modal();                 
                this.confirmWithdrawal = data;
            },

            exportToExcel(){
                var params = {frm_date: $('#frm_date').val(), to_date: $('#to_date').val(),id: $('#to_user_id').val(),country:$('#country').val(),action:'export',responseType: 'blob' };
                axios.post('getwithdrwalconfirmed', params).then(resp => {
                    if (resp.data.code === 200) {
                        //this.export_url = resp.data.data.excel_url;
                        var mystring = resp.data.data.data;
                        var myblob = new Blob([mystring], {
                            type: 'text/plain'
                        });

                        var fileURL = window.URL.createObjectURL(new Blob([myblob]));
                        var fileLink = document.createElement('a');

                        fileLink.href = fileURL;
                        fileLink.setAttribute('download', 'confirmed_withdrwal.xls');
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