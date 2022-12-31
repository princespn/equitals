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
                                    <h3 class="text-center">Upline Income Report</h3>
                                </div>
                            </div>
                            <div class="panel-body"> 
                                <!-- <div class="row">
                                    <form action="#" id="search-form">
                                        
                                        <div  class="form-group">
                                            <div class="row">                               
                                                <div class="col-md-3">
                                                    <label for="level-balance">Level Balance</label>
                                                    <input type="text" id="level-balance" name="level-balance" v-model="levelincome.level_balance" class="form-control" placeholder="Level Balance" readonly>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="transfer-level-income">Transfer Level Balance</label>
                                                    <input type="text" id="transfer-level-income" name="transfer-level-income" v-model="levelincome.level_income_balance"  class="form-control { error: errors.has('mobile') }" formcontrolname="transfer-level-income" placeholder="Transfer Level Balance" v-validate="'required|numeric'" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57">
                                                    <div class="tooltip2" v-show="errors.has('transfer-level-income')">
                                                        <div class="tooltip-inner">
                                                           <span v-show="errors.has('transfer-level-income')">{{ errors.first('transfer-level-income') }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="wallet-id">Transfer To</label>
                                                    <select id="wallet-id" name="wallet-id" class="form-control" v-model="levelincome.wallet_id">
                                                        <option v-for="walletlist in walletlists" v-bind:value="walletlist.srno"> {{ walletlist.name }} </option>
                                                   </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <button type="button" class="btn btn-primary m-t-24" name="signup1" value="Sign up" id="search-box" @click="updateLevelIncome()" :disabled='!isCompleteLevelIncome'>Submit</button>
                                                </div> 
                                            </div>
                                        </div>
                                    </form>
                                </div>   -->                             
                                <table id="level-income-report" class="table table-striped table-bordered mar-top-23 dataTable dtr-inline collapsed" cellspacing="0" width="100%" role="grid" aria-describedby="demo-dt-basic_info" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>User Id</th>
                                            <th>Name</th>
                                            <th>Date</th>  
                                            <th class="min-tablet">Amount</th>
                                            <th class="min-tablet">Status</th>
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

                    const table = $('#level-income-report').DataTable({
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
                            url: apiUserHost+'upline-roi',
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
                            { data: 'from_user_id' },   
                            { data: 'from_fullname' },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.entry_time)).format('YYYY/MM/DD');
                                    }
                                }
                            },
                            { data: 'amount' },
                            {
                              render: function (data, type, row, meta) {
                                if (row.status === 'Paid') {
                                  return `<span class="label label-success">Paid</span>`;
                                } else {
                                  return `<span class="label label-danger">Unpaid</span>`;
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