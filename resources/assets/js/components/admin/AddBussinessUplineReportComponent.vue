<template>
    <!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Add Business Upline Report</h4>
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
                                            <div>
                                                <div class="input-group">
                                                    <DatePicker :bootstrap-styling="true" name="frm_date" :format="datePickerFormat" placeholder="From Date" id="frm_date"></DatePicker>
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
                                                    <DatePicker :bootstrap-styling="true" name="to_date" :format="datePickerFormat" placeholder="To Date" id="to_date"></DatePicker>
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
                                                <input class="form-control" required="" placeholder="Enter user id" type="text" id="user_id">
                                            </div>
                                        </div>
                                      
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="text-center">
                                                    <button type="button" class="btn btn-primary waves-effect waves-light" id="onSearchClick" >Search</button>
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
                                    <table id="powerreport" class="table table-striped table-bordered dt-responsive">
                                        <thead>
                                            <tr>
                                                <th>Sr.No</th>                            
                                                <th>User Id</th>
                                                <th>Full Name</th>
                                                <th>Business Amount</th>
                                                <th>Position</th>
                                                <th>Before Left Business</th>
                                                <th>Before Right Business</th>
                                                <th>After Left Business</th>
                                                <th>After Right Business </th>
                                                <th>Before Curr Amt left Business</th>
                                                <th>Before Curr Amt Rght Business</th>
                                                <th>After Curr Amt left Business</th>
                                                <th>After Curr Amt Rght Business</th>   
                                                <th>Remark</th>  
                                                <th>Status</th>           
                                                <th>Date</th>
                                               
                                            </tr>
                                        </thead>
                                     <!--    <tfoot>
                                            <tr>
                                                <th colspan="8">Total:</th>
                                                <th id="totalSum"></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot> -->
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- end row -->
            </div><!-- container -->
        </div><!-- Page content Wrapper -->
    </div><!-- content -->
</template>

<script>
    import { apiAdminHost } from'./../../admin-config/config';
    import moment from 'moment';
    export default {
        data() {
            return {
                provide_help_data  : [],
                length : 10,
                start  : 0,
                filters:{
                    product_id:'',
                    cost:'',
                    b_value:'',
                },
                arrProducts:[],
                export_url:''
            }
        },
        mounted() {
            this.powerReport();
            
        },
        methods: {
            powerReport(){
                let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    const table = $('#powerreport').DataTable({
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        responsive: true,
                        stateSave: false,
                        ordering: false,
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                        dom: 'Brtip',
                        buttons: [
                            'pageLength',
                           // 'colvis',
                            {
                                extend: 'excelHtml5',
                                title: 'Power Report',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'csvHtml5',
                                title: 'Power Report',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'pdfHtml5',
                                title: 'Power Report',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                           /* {
                                extend: 'print',
                                title: 'Power Report',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },*/
                        ],
                        ajax: {
                            url: apiAdminHost+'/bussiness-upline-report',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    frm_date : $('#frm_date').val(),
                                    to_date  : $('#to_date').val(),
                                    user_id  : $('#user_id').val(),
                                    
                                };
                                Object.assign(d, params);
                                return d;
                            },
                            headers: {
                              'Authorization': 'Bearer ' + token
                            },
                            dataSrc: function (json) {
                                if (json.code === 200) {
                                    /*var totalSum = 0;
                                    for(var x in json.data.records){
                                        totalSum += parseFloat(json.data.records[x].amount);
                                    }*/
                                    //$('#totalSum').html(totalSum);
                                    json['recordsFiltered'] = json.data.recordsFiltered;
                                    json['recordsTotal'] = json.data.recordsTotal;
                                    return json.data.records;
                                } else {
                                    $('#totalSum').html(0);
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
                            { data: 'user' },
                            { data: 'fullname' },
                            { data: 'power_bv' },                            
                           {
                                render: function (data, type, row, meta) {
                                    if (row.position == 1) {
                                      return 'Left';
                                    } else {
                                        return 'Right';
                                    }
                                }
                            },
                            { data: 'before_lbv' },
                            { data: 'before_rbv' },
                            { data: 'after_lbv' },
                            { data: 'after_rbv' },
                            { data: 'before_curr_lbv' },
                            { data: 'before_curr_rbv' },
                            { data: 'after_curr_lbv' },
                            { data: 'after_curr_rbv' },
                            { data: 'remark' },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.type == 1) {
                                      return 'Add Business Amount';
                                    }  if (row.type == 3) {
                                      return 'Remove Business Amount';
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
                        table.ajax.reload();
                    });

                    $('#onResetClick').click(function () {
                        $('#searchForm').trigger("reset");
                        table.ajax.reload();
                    });
                },0);

          /*      $('#binary-income-report').on('click', '.pair_detail',function () {
                    that.$router.push({ 
                        name:'subbinaryincomereport',
                        params:{
                            id:$(this).data('id'),
                            payout_no:$(this).data('payoutid')
                        }
                    });
                });*/
            },


            exportToExcel(){
                var params = {frm_date: $('#frm_date').val(), to_date: $('#to_date').val(),id: $('#user_id').val(),action:'export',responseType: 'blob' };
                axios.post('bussiness-report', params).then(resp => {
                    if(resp.data.code === 200){
                        //this.export_url = resp.data.data.excel_url;
                        var mystring = resp.data.data.data;
                        var myblob = new Blob([mystring], {
                            type: 'text/plain'
                        });

                        var fileURL = window.URL.createObjectURL(new Blob([myblob]));
                        var fileLink = document.createElement('a');

                        fileLink.href = fileURL;
                        fileLink.setAttribute('download', 'bussiness-report.xls');
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