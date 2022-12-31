<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Manage Popup</h4>
		    </div>
		</div>

		<div class="page-content-wrapper">
		    <div class="container">
		        <form>
		            <div class="row">
		                <div class="col-md-12">
		                    <div class="panel panel-primary">
		                        <div class="panel-body">
		                            <div class="">
		                               <!--  <div class="col-md-3"></div> -->
		                                <div class="text-center"> 
			                                 <div class="col-md-2" >
			                                    <div class="form-group">
								                                      <a _ngcontent-c13="" class="btn btn-primary waves-effect waves-light" routerlink="/panel-side/manage-theme/edit-news/0" ng-reflect-router-link="/panel-side/manage-theme/edit-" href="/productbase/admin/panel-side/manage-theme/edit-news/0">
					                          <i _ngcontent-c13="" class="fa fa-plus"></i>Add Pop Up</a>	            	         </div>
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
		                               <!--  <div class="row">
		                                    <div class="col-md-12">
		                                        <div class="text-center">
		                                            <button type="submit" class="btn btn-primary waves-effect waves-light" id="onSearchClick">Search</button>
		                                            <button type="button" class="btn btn-info waves-effect waves-light">Export To Excel</button>
		                                            <button type="button" class="btn btn-dark waves-effect waves-light mt-4" id="onResetClick">Reset</button>
		                                        </div>
		                                    </div>
		                                </div> -->
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
		                        <table id="team-view-report" class="table table-striped table-bordered dt-responsive">
		                            <thead>
		                                <tr>
		                                    <th>Sr.No</th>
		                                    <th>Subject</th>
		                                    <th>Description</th>
		                                    <th>Photo</th>
		                                    <th>Date</th>
		                                    <th>Edit</th>
		                                    <th>Delete</th>
		                                    
		                                   
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
		                                    <th>Subject</th>
		                                    <th>Description</th>
		                                    <th>Photo</th>
		                                    <th>Date</th>
		                                    <th>Edit</th>
		                                    <th>Delete</th>
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
        	
            this.manageWhatsNew();
        },
        methods: {
            manageWhatsNew(){
            	let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    $('#team-view-report').DataTable({
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
                            url: apiAdminHost+'/getpopup',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    provide : 1
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
                          
                            { data: 'sub' },
                            { data: 'text' },
                            {
                              render: function (data, type, row, meta) {
                                return '<img src="'+row.attachment+'" width="100" height="120" >';
                              }
                            },
                            { data: 'entry_time' },
                            {
                              render: function (data, type, row, meta) {
                                return '<a id="onUpdateClick"><i class="fa fa-pencil font-16"></i></a>';
                              }
                            },
                            {
                              render: function (data, type, row, meta) {
                                return '<a id="onDeleteClick"><i class="fa fa-trash text-danger font-16"></i></a>';
                              }
                            }

							
                            /*{ data: 'cost' },
                            { data: 'bvalue' },*/
                            /*{
                              render: function (data, type, row, meta) {
                                return '<label class="text-info">'+row.status+'</label>';
                              }
                            }*/
                        ]
                    });
                },0);
            }
           
        }
    }
</script>	