<template>
    <div>

    <div class="page-content">
        <div class="container-fluid">
           <!--  <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Level Income List</h3>
                            <ol class="breadcrumb breadcrumb-simple">
                                <li><a href="index.php">Home</a>
                                </li>
                                <li><a href="#">My Team</a>
                                </li>
                                <li class="active">Direct User List</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </header> -->
            <section class="card">
                <div class="card-block">
                    <h5 class="with-border text-center">Confirmed ROI Withdrawals</h5>
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="confirm-roi" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>N/W Type</th>
                                        <th>Amount</th>
                                        <th>Deduction</th>               
                                        <th>Net Amount</th>            
                                        <th>BTC Address</th>  
                                        <!-- <th>Date</th> -->
                                        
                                    </tr>
                                </thead>
                                
                            </table>    
                        </div>
                    </div>
                </div>
            </section>
        </div>  
        </div>
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
            }
        },
        mounted(){
            this.getLevelView();
        },
        methods:{
            getLevelView(){
                let i = 0;
                let that = this;
                let token = localStorage.getItem('user-token');
                setTimeout(function(){

                    const table = $('#confirm-roi').DataTable({
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
                            url: apiUserHost+'all-withdraw-confirm-reports',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    // withdraw_type = '3' for roi
                                    withdraw_type: '3',
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
                            { data: 'network_type' },
                            { data: 'amount' },
                            { data: 'deduction' }, 
                            { render: function (data, type, row, meta)
                                {
                                    var net_amount = parseInt(row.amount) - parseInt(row.deduction);
                                    return net_amount;
                                }
                            },
                            { data: 'to_address' }
                        ]
                    });
                },0);
            },         
        }
    }
</script>