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
                                    <h3 class="text-center">INR Payment Report</h3>
                                </div>
                            </div>
                            <div class="panel-body">
                                <table id="confirm-deposite" class="table table-striped table-bordered mar-top-23 dataTable dtr-inline collapsed" cellspacing="0" width="100%" role="grid" aria-describedby="demo-dt-basic_info" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Deposit Id</th>
                                             <th>Amount</th>
                                             <th>Package Name</th>
                                            <th>Status </th>
                                           <th>Date</th>
                                            <th>Attachment</th>      
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
                //total_amount:0,
                INR:'',
                INRVALUE:'',
                type:'',
                totalAmount:'',
            }
        },
        mounted(){
            this.getConfirmedDeposite();
            this.getProjectSetting();
            this.getPackages();
        },
        methods:{
            getConfirmedDeposite(){
                let i = 0;
                let that = this;
                let token = localStorage.getItem('user-token');
                setTimeout(function(){

                    const table = $('#confirm-deposite').DataTable({
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
                            url: apiUserHost+'fund-request-report',
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
                                    if (json.code === 200) {
                                    that.arrGetHelp = json.data.record;
                                    json['draw'] = json.data.draw;
                                    json['recordsFiltered'] = json.data.filterRecord;
                                    json['recordsTotal'] = json.data.totalRecord;
                                    return json.data.record;
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
                        { data: 'invoice_id' },
                       // { data: 'amount' },
                       {
                        render: function (data, type, row, meta) {
                                   return `<span>₹${row.amount * that.INR}</span>`;
                                
                                }
                       },
                       
                       { data: 'product_name' },
                        /*{ data: 'status' },*/
                          {
                              render: function (data, type, row, meta) {
                                if (row.status === 'Approve') {
                                  return `<span class="label label-primary">Approved</span>`;
                                } else if (row.status === 'Reject')  {
                                  return `<span class="label label-danger">Rejected</span>`;
                                }else{
                                    return `<span class="label label-warning">Pending</span>`;
                                }
                              }
                            },
                        { data: 'entry_time' },
                        {
                                render: function (data, type, row, meta) {
                                    // tslint:disable-next-line:max-line-length
                                    return `<img width="50" height="50" src=${row.attachment}>`;
                                
                                }
                        },
                        
                    ]
                    });
                },0);
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
            getPackages(){
            axios.get('get-packages', {
            })
            .then(response => {
                this.INRVALUE = response.data.data[0]['convert'];
                this.type = response.data.data[0]['type'];
                
            })
            .catch(error => {
            });        
         },  
        }
    }
</script>