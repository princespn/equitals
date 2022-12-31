<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Pending KYC Report</h4>
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
		                                 <div class="col-md-2"></div>
		                                <div class="text-center"> 
			                                 <div class="col-md-2" >
			                                    <div class="form-group">
			                                        <label>User Id</label>
			                                        <input class="form-control" required="" placeholder="Enter user id" type="text" id="user_id" f>
			                                    </div>
			                                </div>	
		                                </div>	                           
		                               <!--  <div class="col-md-2">
		                                    <div class="form-group">
		                                        <label>Cost</label>
		                                        <input class="form-control" required="" placeholder="Enter Cost" type="text" f>
		                                    </div>
		                                </div>
		                                <div class="col-md-2">
		                                    <div class="form-group">
		                                        <label>BV</label>
		                                        <input class="form-control" required="" placeholder="Enter BV" type="text">
		                                    </div>
		                                </div> -->
		                                <div class="row">
		                                    <div class="col-md-12">
		                                        <div class="text-center">
		                                            <button type="button" class="btn btn-primary waves-effect waves-light" id="onSearchClick">Search</button>
		                                            <button type="button" class="btn btn-info waves-effect waves-light">Export To Excel</button>
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
		                        <table id="pending-kyc-report" class="table table-striped table-bordered dt-responsive">
		                            <thead>
		                                <tr>
		                                    <th>Sr.No</th>
		                                    <th>User Id</th>
		                                    <th>Photo</th>
		                                    <th>PAN</th>
		                                    <th>Address</th>
		                                    <th>Remark</th>
		                                    <th>Action</th>
		                                   
		                                   <!--  <th>Action</th> -->

		                                </tr>
		                            </thead>
		                            <!-- <tbody>
		                                <tr>team-view
		                                    <td>1</td>
		                                    <td>System Architect</td>
		                                    <td>1</td>
		                                    <td>5000.00</td>
		                                    <td>10</td>
		                                    <td>
		                                        <label class="text-info"> Active</label>
		                                    </td>

		                                </tr>
		                                <tr>
		                                    <td>2</td>
		                                    <td>Accountant</td>
		                                    <td>1</td>
		                                    <td>5000.00</td>
		                                    <td>10</td>
		                                    <td>
		                                        <label class="text-info"> Active</label>
		                                    </td>
		                                </tr>
		                            </tbody> -->
		                            <tfoot>
		                                <tr>
		                                    <th>Sr.No</th>
		                                    <th>User Id</th>
		                                    <th>Photo</th>
		                                    <th>PAN</th>
		                                    <th>Address</th>
		                                    <th>Remark</th>
		                                    <th>Action</th>
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
    export default {
    	
        data() {
            return {
                user_data  : [],
                products   : [],
                length : 10,
                start  : 0,
            }
        },
        mounted() {
        	
            this.pendingKYCReport();
        },
        methods: {
            pendingKYCReport(){
            	let i = 0;            	
                let that = this;

                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    const table = $('#pending-kyc-report').DataTable({
                        responsive: true,
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        responsive: true,
                        stateSave: false,
                        ordering: false,
                        ajax: {
                            url: apiAdminHost+'/show/kyc',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                     user_id  : $('#user_id').val(),
                                     status  : 'Unverified'
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
                                    return i++;
                                }
                            },
                            { data: 'user_id' },
							{ 								
                              render: function (data, type, row, meta) {
                                 return '<img width="60",height="60" src="'+row.photo+'">';
                              }
                            },
                            { 								
                              render: function (data, type, row, meta) {
                                 return '<img width="60",height="60" src="'+row.pancard+'">';
                              }
                            },
                            { 								
                              render: function (data, type, row, meta) {
                                 return '<img width="60",height="60" src="'+row.address+'">';
                              }
                            },							
							{
                              render: function (data, type, row, meta) {
                                 return '<textarea id="remark" placeholder="Add Remarks" class="text-info remark"></textarea>';
                              }
                            },
                            {
                              render: function (data, type, row, meta) {
                                 return '<a class="text-info" id="approve">Approve</a><br><a class="text-danger">Reject</a>';
                              }
                            },
							
                            /*{ data: 'cost' },
                            { data: 'bvalue' },*/
                            /*{
                              render: function (data, type, row, meta) {
                                return '<label class="text-info">'+row.status+'</label>';
                              }
                            }*/
                        ]
                    });
                    $('#onSearchClick').click(function () {
                        table.ajax.reload();
                    });

                    $('#onResetClick').click(function () {
                        $('#searchForm').trigger("reset");
                    });
                     /*$('#onShowDetailsClick').click(function(){*/
                    $('#pending-kyc-report tbody').on('click', '#approve', function (row,data) 
                    {    
                    	if (table.row($(this).parents('tr')).data() !== undefined) {
                            var data = table.row($(this).parents('tr')).data();
                             var remark = $('#remark_' + data.id).val();
                            that.approveRequest(data,remark);
                        } else {
                            var data = table.row($(this)).data();
                            var remark = $('#remark_' + data.id).val();
                            that.approveRequest(data,remark);
                        }
                       // that.approveRequest();
                       // $('#product-details').modal('showImage');
                        /*if(that.table.row($(this).parents('tr')).data() !== undefined) {
                            var data = that.table.row($(this).parents('tr')).data();
                            that.onShowDetailsClick(data);
                        } else {
                            var data = that.table.row($(this)).data();
                            that.onShowDetailsClick(data);
                        }*/
                    });

                    
                },0);
            },
            approveRequest(data,remark){
            	   
            	  
            	  /* if(remark == ''){
            	   		this.$toaster.error('Remark field is required.');
            	   		return false;
            	   }*/
                    Swal({
                    title: 'Are you sure ?',
                    text: "You won't be able to change this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        axios.post('update/kyc', {
                            remark: remark,
                            status : 'Verified',
                            id : data.id
                        }).then(response => {
                            if(response.data.code == 200) {
                            	this.$toaster.success(response.data.message);
                                this.flash(response.data.message, 'success', {
                                  timeout: 500000,
                                });
                            } else {
                            	this.$toaster.error(response.data.message);
                                this.errmessage  = response.data.message;
                                this.flash(this.errmessage, 'warning', {
                                    timeout: 100000,
                                });
                            }
                        }).catch(error => {
                        	//this.$toaster.success(response.data.message);
                            this.message  = error.response.data.message;
                            this.flash(this.message, 'error', {
                              timeout: 500000,
                            });
                        });
                    }
                })
                this.table.ajax.reload();
            }
           
        }
    }
</script>