<template>
	<div>	
		<!--CONTENT CONTAINER-->
        <!--===================================================-->
        <div id="content-container">
            <Breadcrum></Breadcrum>            
            <!--Page content-->
            <!--===================================================-->
            <div id="page-content">
                
                <hr class="new-section-sm bord-no">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="card">
                            <div class="panel panel-body">
                               <div class="panel-heading">
                                    <h3 class="text-center">Direct User List</h3>
                                </div>
                            </div>
                            <div class="panel-body">
                                <table id="direct-user-list" class="table table-striped table-bordered mar-top-23 dataTable dtr-inline collapsed" cellspacing="0" width="100%" role="grid" aria-describedby="demo-dt-basic_info" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>User Id</th>
                                            <th class="min-tablet">Country</th>
                                            <th class="min-tablet">Investments</th>
                                            <th>Registration Date</th> 
                                            <th>Status</th>           
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>               
                
            </div>
            <!--===================================================-->
            <!--End page content-->

        </div>
        <!--===================================================-->
        <!--END CONTENT CONTAINER-->
	</div>
</template>

<script>
    import moment from 'moment';
    import { apiUserHost } from'../../user-config/config';
    import Breadcrum from './BreadcrumComponent.vue';
    export default {  
        components: {
            Breadcrum
        }, 
        data(){
            return{
                getlevels: {
                    level_id: '',
                    level_name: ''
                },
            }
        },
        mounted(){
            this.getLevelView();
            this.getLevels();
        },
        methods:{
            getLevelView(){
                let i = 0;
                let that = this;
                let token = localStorage.getItem('user-token');
                setTimeout(function(){

                    const table = $('#direct-user-list').DataTable({
                        responsive: true,
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        responsive: true,
                        stateSave: false,
                        ordering: true,
                        ajax: {
                            url: apiUserHost+'level-view',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    level_id : '1',
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
                            { data: 'country' },
                            { data: 'total_investment' },             
                            {
                                render: function (data, type, row, meta) {
                                    if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.entry_time)).format('YYYY/MM/DD');
                                    }
                                }
                            },
                            {
                              render: function (data, type, row, meta) {
                                if (row.status === 'Active') {
                                  return `<span class="label label-primary">Active</span>`;
                                } else {
                                  return `<span class="label label-danger">Inactive</span>`;
                                }
                              }
                            }
                        ]
                    });
                },0);
            },

            getLevels(){ 
                axios.get('get-level', {
            })
            .then(response => {
                this.getlevels = response.data.data;
                //alert(this.getlevels);
            })
            .catch(error => {
            }); 
        }
         
        }
    }
</script>