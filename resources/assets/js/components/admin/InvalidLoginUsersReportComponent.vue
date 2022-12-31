<template>
<!-- start content -->
<div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">Invalid Login Users Report</h4>
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
                            <table id="get-login-count-report" class="table table-striped table-bordered dt-responsive">
                                <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>User Id</th>
                                        <th>IP Address</th>
                                        <th>Remark</th>
                                        <th>Action</th>
                                        <th>Attempted Date</th>
                                       
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                       <th>Sr.No</th>
                                        <th>User Id</th>
                                        <th>IP Address</th>
                                        <th>Remark</th>
                                        <th>Action</th>
                                        <th>Attempted Date</th>
                                        
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
                    const table = $('#get-login-count-report').DataTable({
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
                            url: apiAdminHost+'/getlogincountreport',
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
                                    return `<span>${row.user_id}</span>`;
                                }
                            },
                            { data: 'ip_address' },
                       
                          
                            { data: 'remark' },
                            {
                              render: function(data, type, row, meta) {
                                    if(row.status == 0){
                                         return `<a href="javaScript:void(0);" class="text-info waves-effect changeStatus" data-id="${row.user_id}" data-status="${row.status}">Unblock
                                            </a>`;   
                                    }else{
                                        return `-`;
                                    }  
                              }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.attempted_at === null || row.attempted_at === undefined || row.attempted_at === '') {
                                      return `-`;
                                    } else {
                                       return `<span>${row.attempted_at}</span>`;
                                        //return moment(String(row.attempted_at)).format('YYYY-MM-DD');
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


                    $('#get-login-count-report').on('click','.changeStatus',function () {
                        that.changeStatus($(this).data("id"),$(this).data("status"));
                    });
                    
                },0);
            },
            changeStatus(id,status){
                Swal({
                     title: 'Are you sure?',
                    text: `You want to Unblock this User`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/changeuserblockstatus',{
                            id: id,
                            status: status,
                        }).then(resp => {
                            if(resp.data.code == 200){
                                this.$toaster.success(resp.data.message);
                                $('#get-login-count-report').DataTable().ajax.reload();
                            } else {
                                this.$toaster.error(resp.data.message);
                            }
                        }).catch(err => {

                        })
                    }
                });
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