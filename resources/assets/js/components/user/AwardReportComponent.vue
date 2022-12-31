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
                                    <h3 class="text-center"> Award Report</h3>
                                </div>
                            </div>
                            <div class="panel-body">
                                <table id="award-report" class="table table-striped table-bordered mar-top-23 dataTable dtr-inline collapsed" cellspacing="0" width="100%" role="grid" aria-describedby="demo-dt-basic_info" style="width: 100%;">
                                    <thead>
                                        <tr>
                                        <th> Sr No </th>
                                        <th> Award Id</th>
                                        <th> Award </th>
                                        <!-- <th> Match </th> -->
                                        <th> Downline needed </th>
                                        <th> Business required </th>
                                        <th> Direct required </th>
                                        <th> Date </th>         
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
    import Swal from 'sweetalert2';
    export default {  
        components: {
            Breadcrum
        }, 
        data(){
            return{
                levelincome: {
                    level_balance:'',
                    level_income_balance: '',
                    wallet_id: 1,
                },
                walletlists: {
                    srno: '',
                    name: ''
                },
            }
        },
        computed: {
            isCompleteLevelIncome () {
                return this.levelincome.level_balance && this.levelincome.level_income_balance && this.levelincome.wallet_id;               
            }
        },
        mounted(){
            this.getLevelBalance();
            this.getLevelIncome();
            this.getWalletList();
        },
        methods:{
            getWalletList(){ 
                axios.get('wallet-list', {
                })
                .then(response => {
                    this.walletlists = response.data.data;
                    //alert(this.walletlists);
                })
                .catch(error => {
                }); 
            },

            getLevelBalance(){ 
                axios.get('get-user-dashboard', {
                })
                .then(response => {
                    this.levelincome.level_balance = response.data.data.level_income_balance;
                })
                .catch(error => {
                }); 
            },

            updateLevelIncome() {
                Swal({
                    title: 'Are you sure?',
                    text: `You want to transfer the wallet`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) { 
                        axios.post('transfer-to-wallet', { 
                            direct_income_balance:0,                   
                            roi_balance:0,                   
                            binary_income_balance:0,                   
                            leadership_income_balance:0,                   
                            level_income_balance: this.levelincome.level_income_balance,
                            wallet_id: this.levelincome.wallet_id,
                        })
                        .then(response => {
                            if(response.data.code == 200){
                                this.$toaster.success(response.data.message);
                                this.levelincome.level_income_balance = '';
                                this.getLevelBalance();
                                this.getWalletList();
                            } else {
                               this.$toaster.error(response.data.message);
                            }
                        })
                    }
                });
            },

            getLevelIncome(){
                let i = 0;
                let that = this;
                let token = localStorage.getItem('user-token');
                setTimeout(function(){

                    const table = $('#award-report').DataTable({
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
                            url: apiUserHost+'userWinnerList',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    // level_id : $('#level-id').val(),
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
                            { data: 'award_id' },
                            // { data: 'award' },
                            { data: 'amount' },
                            // { data: 'designation' },
                            { data: 'downline_needed' },
                            { data: 'business_required' },
                            { data: 'direct' },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.entry_time)).format('YYYY/MM/DD');
                                    }
                                }
                            }
                        ]
                    });
                    $('#search-box').click(function () {
                        table.ajax.reload();
                    });
                    // $('#reset').click(function () {
                    //     $('#user-id').val('');
                    //     $('#from-date').val('');
                    //     $('#to-date').val('');
                    //     $('#position').val('');                    
                    //     $('#search-form').trigger("reset");
                    //     i = 0;
                    //      table.ajax.reload();

                    // });
                },0);
            },
        }
    }
</script>

