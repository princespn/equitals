<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Old Topup Report</h4>
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
		                                <div class="col-md-4"></div>
		                                <div class="col-md-2">
                                            <input type="hidden" name="user_id" v-model="topup.user_id">
                                            <div class="form-group">
                                                <label>User Id</label>
                                                <input type="text" name="username" class="form-control" id="username" placeholder="User Id" v-model="username" v-on:keyup="checkUserExisted">
                                                <div class="clearfix"></div>
                                                <p :class="{'text-success': isAvialable == 'Available','text-danger': isAvialable == 'Not Available'}" v-if="isAvialable!='' && username!=''">{{isAvialable}}</p>
                                            </div>
		                                </div>
		                               
		                                <div class="row">
		                                    <div class="col-md-12">
		                                        <div class="text-center">
		                                            <button type="button" class="btn btn-primary waves-effect waves-light" id="onSearchClick">Search</button>
		                                            <button type="button" class="btn btn-dark waves-effect waves-light mt-4" id="onResetClick">Reset</button>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </form>
		        <div class="row">
		            <div class="col-md-12">
		                <div class="panel panel-primary">
		                    <div class="panel-body">
		                        <table id="product-report" class="table table-striped table-bordered dt-responsive">
		                            <thead>
		                                <tr>
		                                    <th>Sr.No</th>
                                            <th>id</th>
                                            <th>Fullname</th>
                                            <th>Mobile</th>
                                            <th>Email</th>
                                            <th>amount</th>
		                                    <th>withdraw</th>
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
    import moment from 'moment';
    import DatePicker from 'vuejs-datepicker';
   	import { apiAdminHost } from'./../../admin-config/config';
    export default {
        data() {
            return {
                frm_date : null,
                to_date : null,
                topup:{
                    user_id: '',
                    pin: '',
                    product_id: null,
                    hash_unit:''
                },
                isAvialable:'',
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
            checkUserExisted(){
                axios.post('/checkuserexist',{
                    user_id: this.username,
                }).then(resp => {
                    if(resp.data.code === 200){
                        this.topup.user_id = resp.data.data.id;
                        this.isAvialable = 'Available';
                    } else {
                        this.topup.user_id = '';
                        this.isAvialable = 'Not Available';
                    }
                }).catch(err => {
                    this.$toaster.error(err);
                })
            },
            productReport(){
                let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
              
                setTimeout(function(){
                    const table = $('#product-report').DataTable({                        
                        responsive: true,
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        responsive: true,
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
                            url: apiAdminHost+'/oldgettopup',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;
                                let params = {
                                    status: 'Active',
                                    frm_date : $('#frm_date').val(),
                                    to_date  : $('#to_date').val(),
                                    id  : $('#username').val(),
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
                                    json['recordsFiltered'] = json.data.filterRecord;
                                    json['recordsTotal'] = json.data.totalRecord;
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
                                    //return i++;
                                }
                            },
                            { data: 'user_id' },
                            { data: 'fullname' },
                            { data: 'mobile' },
                            { data: 'email' },
                            { data: 'amount' },
                            { data: 'withdraw' },
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
            }
        }
    }
</script>