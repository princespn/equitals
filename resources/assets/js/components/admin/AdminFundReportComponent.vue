<template>
<!-- start content -->
<div class="content">
	<div class="">
	    <div class="page-header-title">
	        <h4 class="page-title">Admin Fund Report</h4>
	    </div>
	</div>
	<div class="page-content-wrapper">
	    <div class="container">
	        <div class="row">
	            <div class="col-md-12">
	                <div class="panel panel-primary">
	                    <div class="panel-body">
	                        <div class="">
	                            <form id="searchForm">
	                                <div class="row">
	                                	<div class="col-md-3"></div>
	                                    <div class="col-md-2">
	                                        <div class="form-group">
	                                            <label>From Date</label>
	                                            <div>
	                                                <div class="input-group">
	                                                	<DatePicker :bootstrap-styling="true" name="frm_date" :format="dateFormat" placeholder="From Date" id="frm_date"></DatePicker>
	                                                    <!-- <input type="text" class="form-control datepicker" placeholder="From Date" id="frm_date"> -->
	                                                    <span class="input-group-addon bg-custom b-0 datepicker_border">
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
	                                                    <span class="input-group-addon bg-custom b-0 datepicker_border">
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
	                                    <!-- <div class="col-md-2">
	                                        <div class="form-group">
	                                            <label class="control-label">Product</label>
	                                             <select class="form-control" id="product_id">
		                                        	<option selected value="">Select</option>
		                                            <option :value="product.id" v-for="product in arrProducts">{{product.name}}</option>
		                                        </select>
	                                        </div>
	                                    </div>
	                                    <div class="col-md-2">
	                                        <div class="form-group">
	                                            <label>E-Pin</label>
	                                            <input class="form-control" placeholder="Enter E-Pin" type="text" id="pin">
	                                        </div>
	                                    </div>
	                                    <div class="col-md-2">
	                                        <div class="form-group">
	                                            <label>Type</label>
	                                            <select class="form-control" id="status">
	                                                <option selected value="">Select type</option>
	                                                <option value="registration">Purchase</option>
	                                                <option value="repurchase">Repurchase</option>
	                                            </select>
	                                        </div>
	                                    </div> -->
	                                </div>

	                                <div class="row">
	                                    <div class="text-center">
                                            <button type="button" class="btn btn-primary waves-effect waves-light" id="onSearchClick">Search</button>
                                            <button type="button" class="btn btn-info waves-effect waves-light" @click="exportToExcel">Export To Excel</button>
                                            <button type="button" class="btn btn-dark waves-effect waves-light mt-4" id="onResetClick">Reset</button>
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
	                        <table id="admin-fund-report" class="table table-striped table-bordered dt-responsive">
	                            <thead>
	                                <tr>
	                                   <th>Sr No</th>
                                       <th>User Id</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Remark</th>
                                        <th>Date</th>  
	                                </tr>
	                            </thead>
	                            <tfoot>
	                                <tr>
                                      <th>Sr No</th>
                                       <th>User Id</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                          <th>Remark</th>
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
</div>
<!-- content -->
</template>

<script>
   	import { apiAdminHost } from'./../../admin-config/config';
   	import moment from 'moment';
   	import DatePicker from 'vuejs-datepicker';

    export default {
        data() {
            return {
                provide_help_data  : [],
                length : 10,
                start  : 0,
                arrProducts:[],
                INR:'',
                 export_url:''
            }
        },
        mounted() {
            this.productReport();
            /*this.getProducts();
            this.getProjectSetting();*/
        },
        components: {
            DatePicker
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
                    const table = $('#admin-fund-report').DataTable({
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
                            url: apiAdminHost+'/fund_report',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    product_id: $('#product_id').val(),
                                    id: $('#user_id').val(),
                                    status: $('#status').val(),
                                    pin: $('#pin').val(),
                                    frm_date: $('#frm_date').val(),
                                    to_date: $('#to_date').val()
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
                                    return `<span>${row.user_id}</span><span>(${row.fullname})</span>`;
                                }
                            },
                            /*{ data: 'amount' },*/
                              { render: function (data, type, row, meta,) {
                                   return `<span>$${row.amount}</span>`;
                                
                                } 
                            },
                            { data: 'status' }, 
							// { data: 'top_up_by' },
       //                      { data: 'top_up_type' },
       //                      { data: 'franchise_user_id' },
                            /*{ data: 'payment_type' },*/
                            /*{ data: 'withdraw' },*/
                            {
                                render: function (data, type, row, meta) {
                                    if (row.admin_remark === null) {
                                      return `-`;
                                    } else {
                                        return `<span>${row.admin_remark}</sapn>`;
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
                    
                },0);
            },
            getProducts(){
            	axios.get('/getproducts',{
                }).then(resp => {
                	if(resp.data.code === 200){
                		this.arrProducts = resp.data.data;
                	}
                }).catch(err => {
                	
                })
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

            exportToExcel(){
                var params = {frm_date: $('#frm_date').val(), to_date: $('#to_date').val(),id: $('#user_id').val(),action:'export',responseType: 'blob' };
                axios.post('fund_report', params).then(resp => {
                    if(resp.data.code === 200){
                        //this.export_url = resp.data.data.excel_url;
                        var mystring = resp.data.data.data;
                        var myblob = new Blob([mystring], {
                            type: 'text/plain'
                        });

                        var fileURL = window.URL.createObjectURL(new Blob([myblob]));
                        var fileLink = document.createElement('a');

                        fileLink.href = fileURL;
                        fileLink.setAttribute('download', 'fundreport.xls');
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