<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Create Sub Admin</h4>
		    </div>
		</div>

		<div class="page-content-wrapper">
		    <div class="container">
		        <form id="addSubadmin">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>User Name (User Id)</label>
                                <input name="user_id" class="form-control { error: errors.has('user_id') }" placeholder="Enter User Name" type="text" v-model="subadmin.user_id"  v-validate="'required'" v-on:keyup="checkUserExisted" >

                                <p :class="{'text-danger': isAvialable == 'Available'}" v-if="isAvialable=='Available' && isAvialable !='' "> This user id already exist </p>

                                <p :class="{'text-success': isAvialable == 'Not Available'}" v-if="isAvialable=='Not Available' && isAvialable !='' "> You can use this Name</p>

                                <div class="tooltip2" v-show="errors.has('user_id')">
	                                <div class="tooltip-inner">
	                                    <span v-show="errors.has('user_id')">{{ errors.first('user_id') }}</span>
	                                </div>
                              	</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div name="password" class="form-group" style="position: relative;">
                                <label>Password </label>
                                <input name="password" class="form-control { error: errors.has('password') }" placeholder="Enter Password" type="password" v-model="subadmin.password" :disabled="!isButtonActive" v-validate="'required'">

                                <div class="tooltip2" v-show="errors.has('password')">
	                                <div class="tooltip-inner">
	                                    <span v-show="errors.has('password')">{{ errors.first('password') }}</span>
	                                </div>
                              	</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Sub Admin Name</label>
                                <input name="fullname" class="form-control { error: errors.has('fullname') }" placeholder="Enter Sub Admin Name" type="text" v-model="subadmin.fullname" v-validate="'required'">

                                <div class="tooltip2" v-show="errors.has('fullname')">
	                                <div class="tooltip-inner">
	                                    <span v-show="errors.has('fullname')">{{ errors.first('fullname') }}</span>
	                                </div>
                              	</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Email Id</label>
                                <input name="email" class="form-control { error: errors.has('email') }" placeholder="Enter Email Id" type="email" v-model="subadmin.email" v-validate="'required'">

                                <div class="tooltip2" v-show="errors.has('email')">
	                                <div class="tooltip-inner">
	                                    <span v-show="errors.has('email')">{{ errors.first('email') }}</span>
	                                </div>
                              	</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" style="position: relative;">
                                <label>Contact Number</label>
                                <input name="mobile" class="form-control { error: errors.has('mobile') }" id="mobile" placeholder="Enter Contact Number" type="text" v-model="subadmin.mobile" v-validate="'required'">

                                <div class="tooltip2" v-show="errors.has('mobile')">
	                                <div class="tooltip-inner">
	                                    <span v-show="errors.has('mobile')">{{ errors.first('mobile') }}</span>
	                                </div>
                              	</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Department</label>
                                <select name="type" class="form-control { error: errors.has('type') }" v-model="subadmin.type" v-validate="'required'">
                                    <option selected="" value="">Select Department</option>
                                    <!-- <option value="sub-admin">Sub Admin</option> -->
                                    <!-- <option value="sub-admin">Sub Admin</option> -->
                                    <option value="admin"> Admin</option>
                                </select>
                                <div class="tooltip2" v-show="errors.has('type')">
	                                <div class="tooltip-inner">
	                                    <span v-show="errors.has('type')">{{ errors.first('type') }}</span>
	                                </div>
                              	</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="text-center">
                                <button type="button" class="btn btn-primary waves-effect waves-light" @click="addSubadmin" v-html="buttonValue" :disabled="errors.any() || !isValidate"></button>
                            </div>
                        </div>
                    </div>
                </form>
		        <div class="row">
		            <div class="col-md-12">
		                <div class="panel panel-primary">
		                    <div class="panel-body">
                                <div class="table-responsive">

		                        <table id="subadmin-report" class="table table-striped table-bordered dt-responsive">
		                            <thead>
		                                <tr>
		                                    <th>Sr.No</th>
                                            <th>User ID</th>
                                            <th>Full Name</th>
                                            <th>Mobile No</th>
	                                        <th>Email Id</th>
	                                        <th>Department</th>
	                                        <th>Date</th>
		                                </tr>   
		                            </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>User ID</th>
                                            <th>Full Name</th>
                                            <th>Mobile No</th>
                                            <th>Email Id</th>
                                            <th>Department</th>
                                            <th>Date</th>
                                        </tr>   
                                    </tfoot>
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
    import moment from 'moment';
    import DatePicker from 'vuejs-datepicker';
   	import { apiAdminHost } from'./../../admin-config/config';
   	import Swal from 'sweetalert2';
    export default {
        data() {
            return {
                action_status:'add',
                buttonValue:'Add Subadmin',
                isButtonActive:true,
                subadmin: {
                	id: '',
                	fullname: '',
			      	email: '',
			      	user_id: '',
			      	mobile: '',
			      	type: '',
			      	password:''
                },
                isAvialable:'',
            }
        },
        components: {
            DatePicker
        }, 
        mounted() {
            this.subadminReport();
        },
        computed: {
            isValidate () {
                return this.subadmin.fullname && this.subadmin.email && this.subadmin.user_id && this.subadmin.mobile && this.subadmin.type && this.subadmin.password;
            }
        },
        methods: {

            checkUserExisted(){
               // alert();
                axios.post('/checkuserexist',{
                    user_id: this.subadmin.user_id,
                }).then(resp => {
                    if(resp.data.code === 200){
                      
                        this.isAvialable = 'Available';
                    } else {
                       
                        this.isAvialable = 'Not Available';
                    }
                }).catch(err => {
                    this.$toaster.error(err);
                })
            },
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            subadminReport(){
                let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
              
                setTimeout(function(){
                    that.table = $('#subadmin-report').DataTable({
                        responsive: true,
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        stateSave: false,
                        ordering: false,
                       dom: 'lrtip',
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                      /*  buttons: [
                            // 'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5',
                            'pageLength',
                        ],*/
                        ajax: {
                            url: apiAdminHost+'/getsubadminsdetails',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;
                                let params = {
                                    
                                };
                                Object.assign(d, params);
                                return d;
                            },
                            headers: {
                              'Authorization': 'Bearer ' + token
                            },
                            dataSrc: function (json) {
                                //console.log(json)
                               // alert();
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
                            { data: 'user_id' },
                            { data: 'fullname' },
                            { data: 'mobile' },
                            { data: 'email' },
                            { data: 'type' },
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
                },0);                
            },
            addSubadmin(){
				
				Swal({
                    title: 'Are you sure ?',
                    text: "You won't be "+this.action_status+" subadmin.",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No'
                }).then((result) => {
                	if (result.value) {
						if(this.action_status === 'add'){
							axios.post('/create/subadmin',this.subadmin)
							.then(resp => {
			                    if(resp.data.code === 200){
			                   		this.$toaster.success(resp.data.message);
			                   		$('#addSubadmin').trigger('reset');
			                   		this.table.ajax.reload();
			                    } else {
			                   		this.$toaster.error(resp.data.message);
			                   		$('#addSubadmin').trigger('reset');
			                    }
			                }).catch(err => {
			                   	this.$toaster.error(err);
			                   	$('#addSubadmin').trigger('reset');
			                });
						} else if(this.action_status === 'update'){
							axios.post('/update/subadmin',this.subadmin)
							.then(resp => {
			                    if(resp.data.code === 200){
			                   		this.$toaster.success(resp.data.message);
			                   		$('#addSubadmin').trigger('reset');
			                   		this.action_status = 'add';
			    					this.buttonValue = 'Add Subadmin';
			                   		this.table.ajax.reload();
			                    } else {
			                   		this.$toaster.error(resp.data.message);
			                   		$('#addSubadmin').trigger('reset');
			                    }
			                }).catch(err => {
			                   	this.$toaster.error(err);
			                   	$('#addSubadmin').trigger('reset');
			                });
						}
					}
				});
			}
        }
    }
</script>