        <template>
    <!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Team Investment</h4>
            </div>
        </div>

        <div class="page-content-wrapper">
            <div class="container"> 
                <form id="SearchForm">
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
                                                        <DatePicker :bootstrap-styling="true" v-model="frm_date" name="frm_date" :format="dateFormat" placeholder="From Date" id="frm_date"></DatePicker>
                                                        <!-- <input type="text" class="form-control" placeholder="From Date" id="datepicker" v-model="frm_date" autocomplete="off"> -->
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
                                                        <DatePicker :bootstrap-styling="true" v-model="to_date" name="to_date" :format="dateFormat" placeholder="To Date" id="to_date"></DatePicker>
                                                        <!-- <input type="text" class="form-control" placeholder="To Date" id="datepicker-autoclose" v-model="to_date" autocomplete="off"> -->
                                                        <span class="input-group-addon bg-custom b-0 datepicker_border">
                                                            <i class="mdi mdi-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                     
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>User ID</label>
                                                <input class="form-control" required="" placeholder="Enter User ID" type="text" id="user_id" v-model="userid" f>
                                            </div>
                                        </div>

                                           <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Level</label>
                                                <select class="form-control" id="level_id">
                                                <option value="">All</option>
                                                    <!-- <option :value="product.level_id" v-for="product in userlevels" v-if="product.level_name == 1">Direct</option> -->
                                                    <option :value="product.level_id" v-for="product in userlevels" >Level {{ parseInt(product.level_name) }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="text-center">
                                                    <button class="btn btn-primary waves-effect waves-light" id="onSearchClick" type="button">Search</button>                             
                                                    <!-- <button class="btn btn-info waves-effect waves-light mt-4 m-t-4" type="button">Export To Excel</button> -->
                                                    <button class="btn btn-dark waves-effect waves-light mt-4 m-t-4 mtop-4" id="onResetClick" type="button">Reset</button><!-- 
                                                    <button type="submit" class="btn btn-primary waves-effect waves-light" id="onSearchClick">Search</button>
                                                    <button type="button" class="btn btn-info waves-effect waves-light">Export To Excel</button>
                                                    <button type="button" class="btn btn-dark waves-effect waves-light mt-4" id="onResetClick">Reset</button> -->
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
                                <table id="manage-pin-report" class="table table-striped table-bordered dt-responsive">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>User Id</th>
                                            <th>Down User Id</th> 
                                            <th>Sponser Id</th>
                                            <th>Sponser Name</th>
                                            <th>Designation</th>           
                                             <th>Level</th>
                                             <th>Package Amount</th>
                                            <!-- <th>Country</th> -->
                                            <th>Activation Date</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Total</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>            
                                            <th></th>
                                            <th id="inv"></th>
                                            <!-- <th>Country</th> -->
                                            <th></th>
                                        </tr>
                                    </tfoot>
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
    import { apiAdminHost } from'./../../admin-config/config';
    import moment from 'moment';
    import Swal from 'sweetalert2';
    import DatePicker from 'vuejs-datepicker';

    export default {
        
        data() {
            return {
                user_data  : [],
                products   : [],
                userlevels:[],
                level_id : 1,
                userExistsMessage : '',
                custom_msg_class : '' ,
                userid:''               
            }
        },
        components: {
            DatePicker
        }, 
        mounted() {
            this.getLevelViews();
            this.getProducts();
        },
        methods: {
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            getLevelViews(){
               
                let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    const table =  $('#manage-pin-report').DataTable({
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
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5',
                            'pageLength',
                        ],
                        ajax: {
                            url: apiAdminHost+'/getlevelviews',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    id : 1,
                                    frm_date : $('#frm_date').val(),
                                    to_date  : $('#to_date').val(),
                                    level_id  : $('#level_id').val(),
                                    user_id :$('#user_id').val(),                            
                                };
                                Object.assign(d, params);
                                return d;
                            },
                            headers: {
                              'Authorization': 'Bearer ' + token
                            },
                            dataSrc: function (json) {
                                if (json.code === 200) {

                                    let totalSum = 0;
                                    for(var x in json.data.records){
                                    totalSum += json.data.records[x].investment;
                                    }
                                    $('#inv').html(totalSum);
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
                                    return `<span>${row.user_id}</span><span>(${row.user_id_fullname})</span>`;
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    return `<span>${row.down_user_id}</span><span>(${row.down_user_id_fullname})</span>`;
                                }
                            },
                            { data: 'sponser_userid' },
                            { data: 'sponser_fullname' },
                            { data: 'desg' }, 
                            {
                                render: function (data, type, row, meta) {
                                   /*if(row.level == 1){
                                      return `Direct`;
                                   }else{
                                      return parseInt(row.level) - 1;
                                   }*/
                                   return row.level;
                                }
                            },
                            { data: 'investment' },
                            /*{ data: 'country' },*/
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
                         that.getProducts();
                        table.ajax.reload();
                    });

                    $('#onResetClick').click(function () { 
                        $('#frm_date').val('');
                        $('#to_date').val('');
                        $('#level_id').val(1);
                        $('#user_id').val('');  
                        table.ajax.reload();

                        /*$('#product_id').val('');
                        $('#datepicker').val(''),
                        $('#datepicker-autoclose').val('');
                        $('#pin').val('');          */
                        //$('#searchForm').trigger("reset");
                        //that.table.ajax.reload();
                    });

                },0);
            },
            getProducts(){
                //alert();
                axios.post('/getuserlevels',{
                    id: 1,
                    user_id: $('#user_id').val(), 
                }).then(resp => {
                    if(resp.data.code === 200){
                        this.userlevels = resp.data.data;
                    }
                }).catch(err => {
                    
                })
            },
            checkuserexist() {
                axios.post('checkuserexist',{
                   user_id:this.user_id,
                 }).then(response => {                     
                    if(response.data.code == 404) {
                        // console.log(response.data.message);
                        this.to_fullname = '';
                        this.userExistsMessage = response.data.message;
                        this.custom_msg_class = 'text-danger';                        
                    }else{                                     
                        this.to_fullname = response.data.data.fullname;
                        this.userExistsMessage = response.data.message;
                        this.custom_msg_class = 'text-success';
                    }
                }).catch(error => {                
                    this.message  = '';
                    this.flash(this.message, 'error', {
                      timeout: 500000,
                    });
                });
            },           
        }
    }
</script>   