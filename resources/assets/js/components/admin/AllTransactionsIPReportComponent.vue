<template>
<!-- start content -->
<div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">All Transactions IP Report</h4>
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
                            <table id="total-ip-report" class="table table-striped table-bordered dt-responsive">
                                <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>User Id</th>
                                        <th>IP address</th>
                                        <th>Type </th>
                                        <th>Amount</th>
                                        <th>Pin</th>
                                        <th>Deduction</th>
                                        <th>Payment Mode</th>
                                        <th>Payment By</th>
                                        <th>Remark</th> 
                                        <th>Date</th>
                                        
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>User Id</th>
                                        <th>IP address</th>
                                        <th>Type </th>
                                        <th>Amount</th>
                                        <th>Pin</th>
                                        <th>Deduction</th>
                                        <th>Payment Mode</th>
                                        <th>Payment By</th>
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
    import Swal from 'sweetalert2';

    export default {
        data() {
            return {
                provide_help_data  : [],
                length : 10,
                start  : 0,
                arrProducts:[],
                INR:'',
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
                    const table = $('#total-ip-report').DataTable({
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
                            url: apiAdminHost+'/getusersipaddress',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    product_id: $('#product_id').val(),
                                    user_id: $('#user_id').val(),
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
                                    //return meta.row + 1;
                                    return i++;
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                     if (row.user_id === null || row.user_id === undefined || row.user_id === '') {
                                      return `-`;
                                    } else {
                                        return `<span>${row.user_id}</span><span> (${row.fullname})</span>`;
                                    }
                                    
                                }
                            },  
                            { data: 'ip_address' },
                            /*{
                                render: function (data, type, row, meta) {
                                     if (row.from_user_id === null || row.from_user_id === undefined || row.from_user_id === '') {
                                      return `-`;
                                    } else {
                                        return `<span>${row.from_user_id}</span>`;
                                    }
                                    
                                }
                            }, */
                            
                            { data: 'type' },
                            
                            
                          /*  { data: 'product_name' },*/
                            /*{ data: 'amount' },*/
                            { render: function (data, type, row, meta,) {
                                if(row.amount > 0)
                                {

                                   return `<span>$${row.amount}</span>`;
                                }else{
                                     return `<span>-</span>`;
                                }
                                
                                } 
                            },
                            {
                                render: function (data, type, row, meta) {
                                     if (row.invoice_id === null || row.invoice_id === undefined || row.invoice_id === '' || row.invoice_id === "0") {
                                      return `-`;
                                    } else {
                                        return `<span>${row.invoice_id}</span>`;
                                    }
                                    
                                }
                            },  
                            { render: function (data, type, row, meta,) {
                                if(row.deduction > 0)
                                {

                                   return `<span>$${row.deduction}</span>`;
                                }else{
                                     return `<span>-</span>`;
                                }
                                
                                } 
                            },
                             {
                                render: function (data, type, row, meta) {
                                     if (row.payment_mode === null || row.payment_mode === undefined || row.payment_mode === '') {
                                      return `-`;
                                    } else {
                                        return `<span>${row.payment_mode}</span>`;
                                    }
                                    
                                }
                            },  
                             {
                                render: function (data, type, row, meta) {
                                     if (row.product_url === null || row.product_url === undefined || row.product_url === '') {
                                      return `-`;
                                    } else {
                                        return `<span>${row.product_url}</span>`;
                                    }
                                    
                                }
                            },  
                            /*{ data: 'name' },*/
                            
                            { data: 'remark' },
                            
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


                    $('#total-sell-report').on('click','.changeStatus',function () {
                        that.changeStatus($(this).data("id"),$(this).data("roi_stop_status"));
                    });
                    
                },0);
            },
            changeStatus(id,status){
                var msg = (status == 1)?"STOP":"START";
                this.sr_no=id;
                Swal({
                     title: 'Are you sure?',
                    text: `You want to `+msg+" ROI for this topup",
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
           roiStop(){
                this.isdisabledR=true;
                axios.post('/topuproistop',{
                    sr_no: this.sr_no,
                     remark:this.remark,
                     pin:this.pin
                }).then(resp => {
                    if(resp.data.code == 200){
                        this.$toaster.success(resp.data.message);
                        $("#add-remark-modal").modal('hide');                        
                        $('#total-sell-report').DataTable().ajax.reload();                        
                    } else {
                        this.$toaster.error(resp.data.message);
                    }
                    this.sr_no='';
                    this.remark='';
                    this.pin='';
                        $(".close").trigger('click');
                        $(".close").trigger('click');
                }).catch(err => {

                })
                    
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
                axios.get('getprojectsettings', {}).then(response => {
                    this.INR = response.data.data['USD-to-INR'];
                }).catch(error => {
                }); 
            },
            exportToExcel(){
                var params = {product_id: $('#product_id').val(),user_id: $('#user_id').val(),status: $('#status').val(),pin: $('#pin').val(),frm_date: $('#frm_date').val(),to_date: $('#to_date').val(),action:"export",responseType: 'blob'};
                axios.post('gettopup', params).then(resp => {
                    //this.export_url = resp.data.data.excel_url;
                    var mystring = resp.data.data.data;
                    var myblob = new Blob([mystring], {
                        type: 'text/plain'
                    });

                    var fileURL = window.URL.createObjectURL(new Blob([myblob]));
                    var fileLink = document.createElement('a');

                    fileLink.href = fileURL;
                    fileLink.setAttribute('download', 'AllTopups.xls');
                    document.body.appendChild(fileLink);

                    fileLink.click();
                });
            }
        }
    }
</script>