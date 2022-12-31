<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Manage User</h4>
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
                                                    	<DatePicker :bootstrap-styling="true" name="frm_date" :format="dateFormat" placeholder="From Date" id="frm_date"></DatePicker>
                                                        <!-- <input type="text" class="form-control" placeholder="From Date" id="datepicker"> -->
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
                                                        <!-- <input type="text" class="form-control" placeholder="To Date" id="datepicker-autoclose"> -->
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
		                                        <input class="form-control"  placeholder="Enter user id" type="text" id="user_id" f>
		                                    </div>
		                                </div>
		                                <!-- <div class="col-md-3">
		                                    <div class="form-group">
		                                        <label>Sponser Id</label>
		                                        <input class="form-control"  placeholder="Enter Sponser id" type="text" id="sponser_id" f>
		                                    </div>
		                                </div>
		                                <div class="col-md-3">
		                                    <div class="form-group">
		                                        <label class="control-label">Product</label>
		                                        <select class="form-control" id="product_id">
		                                             <option  value="" >Select Product</option>
					                                 <option v-for="(product, index) in products" :value="product.id"
					                                    v-bind:key="index">{{ product.name }}
				                                     </option>
		                                        </select>
		                                    </div>
		                                </div> -->

		                                <div class="col-md-2">
		                                    <div class="form-group">
		                                        <label class="control-label">Status</label>
		                                        <select class="form-control" id="status">
		                                            <option  value="" >Select status</option>
		                                            <option  value="" >All</option>
		                                            <option  value="Active" >Active</option>
                                                    <option  value="Inactive" >Inactive</option>
		                                        </select>
		                                    </div>
		                                </div>
		                                <div class="row">
		                                    <div class="col-md-12">
		                                        <div class="text-center">
		                                            <button type="button" class="btn btn-primary waves-effect waves-light" id="onSearchClick">Search</button>
		                                            <button type="button" class="btn btn-info waves-effect waves-light" @click="exportToExcel">Export To Excel</button>
		                                            <button type="button" class="btn btn-dark waves-effect waves-light mt-4" id="onResetClick">Reset</button>

                                                    <!-- <a :href="export_url" download style="visiblity:hidden" id="downloadexcel"></a> -->
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
		                        <table id="manage-user-report" class="table table-striped table-bordered dt-responsive">
		                            <thead>
		                                <tr>
                                            <th>Sr.No</th>
                                            <th>Click to login</th>
		                                    <th>User Id</th>
		                                    <th>Full Name</th>
                                            <th>Mobile</th>
                                            <th>Country</th>
                                            <th>Email</th>
                                            <!-- <th>paypal address</th> -->
		                                    <th>Sponser</th>
                                            <th>Position</th>
		                                    <th>Entry Date</th>
                                            <th>Withdraw Status</th>
                                            <th>Auto Withdraw Status</th>
                                            <th>Status</th>
		                                    <th>Action</th>
		                                </tr>
		                            </thead>
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
    import Swal from 'sweetalert2';
    import DatePicker from 'vuejs-datepicker';

    export default {
    	
        data() {
            return {
                products: [],
                export_url:''
            }
        },
        mounted() {
            this.manageUserReport();
        },
        components: {
            DatePicker
        },
        methods: {
        	dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            manageUserReport(){
            	let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    that.table = $('#manage-user-report').DataTable({
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
                            url: apiAdminHost+'/getusers',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    frm_date: $('#frm_date').val(),
                                    to_date: $('#to_date').val(),
                                    id: $('#user_id').val(),
                                    // product_id:$('#product_id').val(),
                                    status: $('#status').val(),
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
                            // {
                            //     render: function (data, type, row, meta) {
                            //     return `<a target="_blank" href="${row.login_url}${row.user_id}">Click to login</a>`;
                            //     }
                            // },
                            {
                                render: function (data, type, row, meta) {
                                    return `<label class="text-info waves-effect" id="login" data-id="${row.user_id}">Click to login
                                            </label>`;
                               }
                            
                            },
							{ data: 'user_id' },
                        /*  {
                                render: function (data, type, row, meta) {
                                    if (row.pin === null || row.pin ===
                                        undefined || row.pin === '') {
                                        return `<p>(row.user_id)</p>`;
                                    } else {
                                        return '<p>"{row.user_id}"</p>';
                                    }
                                }
                            },*/
                            { data: 'fullname' },
                            { data: 'mobile' },
                            { data: 'country' },
							{ data: 'email' },
                           // { data: 'paypal_address' },
							{ data: 'sponser_id' },
                            { data: 'position' },

                            {
                                render: function (data, type, row, meta) {
                                    if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.entry_time)).format('YYYY-MM-DD');
                                    }
                                }
                            },
                            { 
                                render:function(data, type, row, meta){
                                   return `<label class="${(row.withdraw_status == 1)?'text-info':'text-warning'} waves-effect" id="changeUserWithdrawStatus" data-id="${row.id}" data-status="${row.auto_withdraw_status}">${(row.withdraw_status == 1)?'ON':'OFF'}</label>`
                                } 
                            },
							{ 
                                render:function(data, type, row, meta){
                                   return `<label class="${(row.auto_withdraw_status == 1)?'text-info':'text-warning'} waves-effect" id="changeWithdrawStatus" data-id="${row.id}" data-status="${row.auto_withdraw_status}">${(row.auto_withdraw_status == 1)?'ON':'OFF'}</label>`
                                } 
                            },
                            { data: 'status' },
                            {
                                render: function (data, type, row, meta) {
                                    return `<a class="myProfile" title="Profile" data-id="${row.id}">
                                                <i class="fa fa-user font-16"></i>
                                            </a>&nbsp;
                                            <a class="editmyProfile" data-id="${row.id}" title="Edit">
                                                <i class="fa fa-pencil font-16"></i>
                                            </a><br>
                                            <label class="${(row.status == 'Active')?'text-info':'text-warning'} waves-effect" id="changeStatus" data-id="${row.id}" data-status="${row.status}">${(row.status == 'Active')?'Block':'Unblock'}
                                            </label>`;
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

                    $('#manage-user-report').on('click','#changeStatus',function () {
                        that.changeStatus($(this).data("id"),$(this).data("status"));
                    });

                    $('#manage-user-report').on('click','#changeWithdrawStatus',function () {
                        that.changeWithdrawStatus($(this).data("id"),$(this).data("status"));
                    });

                    $('#manage-user-report').on('click','#changeUserWithdrawStatus',function () {
                        that.changeUserWithdrawStatus($(this).data("id"),$(this).data("status"));
                    });

                    $('#manage-user-report').on('click','#login',function () {
                        that.loginnn($(this).data("id"));
                    });

                    $('#manage-user-report').on('click','.editmyProfile',function () {
                        that.$router.push({
                            name:'edituserprofile',
                            params:{
                                id: $(this).data('id'),
                            }
                        });
                    });

                    $('#manage-user-report').on('click','.myProfile',function () {
                        that.$router.push({
                            name:'userprofile',
                            params:{
                                id: $(this).data('id'),
                            }
                        });
                    });

                },0);
            },
            changeStatus(id, status){

                Swal({
                    title: 'Are you sure?',
                    text: `You want to change status of this user`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/blockuser',{
                            id: id,
                            status: status,
                        }).then(resp => {
                            if(resp.data.code == 200){
                                this.$toaster.success(resp.data.message);
                                this.table.ajax.reload();
                            } else {
                                this.$toaster.error(resp.data.message);
                            }
                        }).catch(err => {

                        })
                    }
                });
            },
            changeWithdrawStatus(id, status){

                Swal({
                    title: 'Are you sure?',
                    text: `You want to change auto withdraw status of this user`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/changeWithdrawStatus',{
                            id: id,
                            status: status,
                        }).then(resp => {
                            if(resp.data.code == 200){
                                this.$toaster.success(resp.data.message);
                                this.table.ajax.reload();
                            } else {
                                this.$toaster.error(resp.data.message);
                            }
                        }).catch(err => {

                        })
                    }
                });
            },
            changeUserWithdrawStatus(id, status){

                Swal({
                    title: 'Are you sure?',
                    text: `You want to change withdraw status of this user`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/changeUserWithdrawStatus',{
                            id: id,
                            status: status,
                        }).then(resp => {
                            if(resp.data.code == 200){
                                this.$toaster.success(resp.data.message);
                                this.table.ajax.reload();
                            } else {
                                this.$toaster.error(resp.data.message);
                            }
                        }).catch(err => {

                        })
                    }
                });
            },
            exportToExcel(){
                var params = {frm_date: $('#frm_date').val(), to_date: $('#to_date').val(),id: $('#user_id').val(),status: $('#status').val(),action:'export',responseType: 'blob' };
                axios.post('getusers', params).then(resp => {
                    if(resp.data.code === 200){
                        //this.export_url = resp.data.data.excel_url;
                        var mystring = resp.data.data.data;
                        var myblob = new Blob([mystring], {
                            type: 'text/plain'
                        });

                        var fileURL = window.URL.createObjectURL(new Blob([myblob]));
                        var fileLink = document.createElement('a');

                        fileLink.href = fileURL;
                        fileLink.setAttribute('download', 'AllUsers.xls');
                        document.body.appendChild(fileLink);

                        fileLink.click();
                    }else{
                        this.$toaster.error(resp.data.message);
                    }
                });
            },
            loginnn(user_id = null) {           
                axios.post('user_login', {
                    user_id: user_id,
                    password: '123456',
                }).then(resp => {
                    let userinfo = resp.data.data; 
                    if(resp.data.code === 200){
                        const token = resp.data.data.access_token;
                        if(userinfo.google2faauth == "TRUE"){
                            this.token = token;
                            this.verify2fa = true;
                        }else{
                            localStorage.setItem('user-token', token); // store the token in localstorage
                            localStorage.setItem('type', "user"); // store the token in localstorage
                        // this.getPackages();
                          //   window.location.href = ""+resp.data.data.validPath+"user#/dashboard";
                            window.open(""+resp.data.data.Path+"user#/dashboard", '_blank');
                          //   this.$router.push({name: 'dashboard'});
                          //   location.reload();
                        }
                        
                    } else {
                        this.$toaster.error(resp.data.message)
                    }
                }).catch(err => {
                    //localStorage.removeItem('user-token'); // if the request fails, remove any 
                   // location.reload();
                    this.$router.push({ name: 'login' });
                })
            } 
                 
        }
    }
</script>
