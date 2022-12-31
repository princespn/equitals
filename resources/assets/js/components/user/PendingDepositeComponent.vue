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
                                    <h3 class="text-center">Ongoing Payments</h3>
                                </div>
                            </div>
                            <div class="panel-body">
                                <table id="confirm-deposite" class="table table-striped table-bordered mar-top-23 dataTable dtr-inline collapsed" cellspacing="0" width="100%" role="grid" aria-describedby="demo-dt-basic_info" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Date</th>
                                            <th>Deposit Id</th>
                                            <th>Package</th>
                                            <th>Amount</th>
                                            <th>Address</th>       
                                        </tr>
                                    </thead>                                        
                                    <tfoot>
                                        <tr>
                                          <th colspan="3">Total</th>
                                          <th></th>
                                          <th id="total_amount"></th>
                                          <th></th>
                                        </tr>
                                      </tfoot>
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
                type:'',
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
                        ordering: true,
                        ajax: {
                            url: apiUserHost+'pending-deposit',
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

                                    let total_amount = 0;
                                    for (let j = 0; j < json.data.records.length; j++) {
                                        total_amount = total_amount + parseInt(json.data.records[j].price_in_usd);          
                                        $('#total_amount').text(total_amount);
                                    }

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
                            {
                                render: function (data, type, row, meta) {
                                    if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.entry_time)).format('YYYY/MM/DD');
                                    }
                                }
                            },
                            { data: 'invoice_id' },
                             { render: function (data, type, row, meta) {
                                   if(that.type == "INR")
                                     {
                                        return `<span>₹${row.rupee_plan_name}</span>`;
                                    } else{
                                        return `<span>${row.plan_name}</span>`;
                                    }
                                
                                } 
                            },
                            /*{ data: 'plan_name' },   */          
                            /*{ data: 'price_in_usd' },*/
                              { render: function (data, type, row, meta) {
                                if(that.type == "INR")
                                     {
                                        return `<span>₹${ parseFloat(row.price_in_usd).toFixed(2) * parseFloat(that.INR).toFixed(2)}</span>`;
                                    } else{
                                        return `<span>$${row.price_in_usd}</span>`;
                                    }
                                
                                } 
                            },
                            { data: 'address' }
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
               this.INR = response.data.data[0]['convert'];
                this.type = response.data.data[0]['type'];
                
            })
            .catch(error => {
            });        
         },   
        }
    }
</script>