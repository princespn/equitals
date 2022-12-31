<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">News Report</h4>
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
		                                <div class="col-md-2">
		                                    <div class="form-group">
		                                        <label>From Date</label>
                                                <div class="input-group">
    		                                        <DatePicker :bootstrap-styling="true" name="frm_date" :format="dateFormat" placeholder="From Date" id="frm_date"></DatePicker>
                                                    <span class="input-group-addon bg-custom b-0 datepicker_border">
                                                        <i class="mdi mdi-calendar"></i>
                                                    </span>
                                                </div>
    		                                </div>
		                                </div>
		                                <div class="col-md-2">
		                                    <div class="form-group">
		                                        <label>To Date</label>
                                                <div class="input-group">
    		                                        <DatePicker :bootstrap-styling="true" name="to_date" :format="dateFormat" placeholder="To Date" id="to_date"></DatePicker>
                                                    <span class="input-group-addon bg-custom b-0 datepicker_border">
                                                        <i class="mdi mdi-calendar"></i>
                                                    </span>
                                                </div>
		                                    </div>
		                                </div>
		                                <div class="row">
		                                    <div class="col-md-12">
		                                        <div class="text-center">
		                                            <button type="button" class="btn btn-primary waves-effect waves-light" id="onSearchClick">Search</button>
		                                            <!-- <button type="button" class="btn btn-info waves-effect waves-light">Export To Excel</button> -->
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
	                            <div class="">
	                               <!--  <div class="col-md-3"></div> -->
	                                <div class="text-center"> 
		                                 <div class="col-md-2" >
		                                    <div class="form-group">
		                                    	<router-link class="btn btn-primary waves-effect waves-light" :to="{ path: '/manage-theme/add-news'}" replace>Add News</router-link>
							                </div>
		                                </div>	
	                                </div>
	                            </div>
	                        </div><!-- panel-body -->
	                    </div><!-- panel -->
	                </div><!-- col -->
	            </div>
		        <div class="row">
		            <div class="col-md-12">
		                <div class="panel panel-primary">
		                    <div class="panel-body">
		                        <table id="manage-news" class="table table-striped table-bordered dt-responsive">
		                            <thead>
		                                <tr>
		                                    <th>Sr.No</th>
		                                    <th>Subject</th>
		                                    <th>Description</th>
		                                    <th>Date</th>
		                                    <th>Edit</th>
		                                    <th>Delete</th>
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
    import Swal from 'sweetalert2';
   	import { apiAdminHost } from'./../../admin-config/config';
    export default {
        data() {
            return {
                frm_date : null,
                to_date : null,
            }
        },
        components: {
            DatePicker
        }, 
        mounted() {
            this.manageNews();
        },
        methods: {
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },

            manageNews(){
                let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
              
                setTimeout(function(){
                    that.table = $('#manage-news').DataTable({
                        responsive: true,
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        stateSave: false,
                        ordering: false,
                        dom: 'lrtip',
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                        ajax: {
                            url: apiAdminHost+'/getnews',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;
                                
                                let params = {
                                    status: 'Active',
                                    frm_date : $('#frm_date').val(),
                                    to_date  : $('#to_date').val(),
                                };
                                Object.assign(d, params);
                                return d;
                            },
                            headers: {
                              'Authorization': 'Bearer ' + token
                            },
                            dataSrc: function (json) {
                                if (json.code === 200) {
                                    console.log(json);
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
                           /*  {
                                render: function (data, type, row, meta) {
                                    return row.sub;
                                }
                            },
                             {
                                render: function (data, type, row, meta) {
                                    return row.text;
                                }
                            },*/
                            { data: 'sub' },
                            { data: 'text' },

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
                              	render: function (data, type, row, meta) {
                              		return `<a class="editmyProfile" data-id="${row.id}" title="Edit">
                                                <i class="fa fa-pencil font-16"></i>
                                            </a>`;
                              	}
                            },
                            {
                              	render: function (data, type, row, meta) {
                                	return `<span id="onDeleteClick" data-id="${row.id}"><i class="fa fa-trash text-danger font-16"></i></span>`;
                              	}
                            }
,
                        ]
                    });

                   $('#onSearchClick').click(function () {
                        that.table.ajax.reload();
                    });

                    $('#onResetClick').click(function () {  
                        $('#searchForm').trigger("reset");
                        that.table.ajax.reload();
                    });

                    $('#manage-news').on('click','.editmyProfile',function () {
                        that.$router.push({
                            name:'editnews',
                            params:{
                                id: $(this).data('id'),
                            }
                        });
                    });

                    $('#manage-news').on('click','#onDeleteClick', function (){
                    	that.deleteNews($(this).data("id"));
                    });

                },0);                
            },

            deleteNews(id){
                Swal({
                    title: 'Are you sure?',
                    text: `You want to delete this news`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/delete/news',{
                            id: id,
                        }).then(resp => {
                            if(resp.data.code == 200){
                                this.$toaster.success(resp.data.message);
                            	//this.$router.push({ name:'manage-theme/news'});
                                //that.table.ajax.reload();
                                this.table.ajax.reload();
                                //this.manageNews();
                                //location.reload();
                            } else {
                                this.$toaster.error(resp.data.message);
                            }
                        }).catch(err => {

                        })
                    }
                });
            } 

        }
    }
</script>