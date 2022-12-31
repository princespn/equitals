<template>
<!-- start content -->
<div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">Qaulify Rank Report</h4>
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

                                        <div class="col-md-2">
                                            <div class="row">
                                                <label>Select Type</label>
                                                <select class="form-control" id="rank_name">
                                                    <option :value="null"  selected >Select</option>
                                                    <option v-bind:value="'All'">All</option>
                                                    <option v-for="option in getTypes" v-bind:value="option.rank"> {{ option.rank  }}</option> 
                                                </select>
                                            </div>
                                        </div>
                                        <!--<div class="col-md-2">
                                                <label>Select Rank</label>
                                                 <select class="form-control" id="rank">
                                                  <option disabled value="" selected>Select Rank</option>
                                                  <option
                                                    v-for="co in ranks"
                                                    v-bind:value="co.rank"
                                                  >{{ co.rank }}</option>
                                                </select>
                                            </div> -->
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

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Matching Income Status</label>
                                                <select v-model="mathing_income_status" name="mathing_income_status" id="mathing_income_status" class="form-control">
                                                    <option value="">Select Status</option>
                                                    <option value="Active">Active</option>
                                                    <option value="Inactive">Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><!-- Freedom --> Club Capping Status</label>
                                                <select v-model="club_capping_status" name="club_capping_status" id="club_capping_status" class="form-control">
                                                    <option value="">Select Status</option>
                                                    <option value="1">Achieved</option>
                                                    <option value="0">Not Achieved</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>User Status</label>
                                                <select v-model="user_status" name="user_status" id="user_status" class="form-control">
                                                    <option value="">Select Status</option>
                                                    <option value="Active">Active</option>
                                                    <option value="Inactive">Inactive</option>
                                                </select>
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
                            <table id="total-rank-report" class="table table-striped table-bordered dt-responsive">
                                <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>User Id</th>
                                        <th>Full Name </th> 
                                        <th>Rank</th>
                                        <th>Matching Income Status</th>
                                        <th>Freedom Club Capping Status</th>
                                        <th>User Status</th>
                                        <th>Entry Time</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>User Id</th>
                                        <th>Full Name </th> 
                                        <th>Rank</th>
                                        <th>Matching Income Status</th>
                                        <th>Freedom Club Capping Status</th>
                                        <th>User Status</th>
                                        <th>Entry Time</th>

                                        
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
                ranks:[],
                getTypes:[],
                export_url:''
            }
        },
        mounted() {
            this.productReport();
            /*this.getProducts();
            this.getProjectSetting();
            this.getCountry(); */
            this.getTransactionType();
        },
        components: {
            DatePicker
        },
        methods: {
            productReport2(){
                this.productReport();
            },
            getTransactionType(){
                    axios.get('/get-all-rank', {
                })
                .then(response => {
                    this.getTypes = response.data.data;
                })
                .catch(error => {
                });      
         }, 
             getCountry() {
      axios
        .get("../rank", {})
        .then(response => {
          //this.countries = response.data.data;
          this.ranks = response.data.data;

        })
        .catch(error => {});
    },
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            productReport(){
                //alert("calling");
                let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    const table = $('#total-rank-report').DataTable({
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
                            url: apiAdminHost+'/getqualifyranks',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    //product_id: $('#product_id').val(),
                                    id: $('#user_id').val(),
                                    rank: $('#rank_name').val(),
                                   /*  pin: $('#pin').val(),*/
                                    frm_date: $('#frm_date').val(),
                                    to_date: $('#to_date').val(),
                                    mathing_income_status: $('#mathing_income_status').val(),
                                    club_capping_status: $('#club_capping_status').val(),
                                    user_status: $('#user_status').val(),
                                   // rank: $('#rank').val(),
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
                            {data: 'user_id'},
                            {data: 'fullname'}, 
                            { data: 'rank' },
                            { data: 'maching_income_status' },
                            { data: 'club_capping_status' },
                            { data: 'user_status' },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.entry_time)).format('DD-MM-YYYY');
                                    }
                                }
                            }
                        ]
                    });

                    $('#onSearchClick').click(function () {
                        table.ajax.reload();
                    });
                     $('#rank_name').on('change',function(){
                      //   alert("abhay");
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
                var params = {frm_date: $('#frm_date').val(), to_date: $('#to_date').val(),id: $('#user_id').val(),rank : $('#rank_name').val(),action:'export',responseType: 'blob' };
                axios.post('getqualifyranks', params).then(resp => {
                    if(resp.data.code === 200){
                        //this.export_url = resp.data.data.excel_url;
                        var mystring = resp.data.data.data;
                        var myblob = new Blob([mystring], {
                            type: 'text/plain'
                        });

                        var fileURL = window.URL.createObjectURL(new Blob([myblob]));
                        var fileLink = document.createElement('a');

                        fileLink.href = fileURL;
                        fileLink.setAttribute('download', 'qualifyrank_report.xls');
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