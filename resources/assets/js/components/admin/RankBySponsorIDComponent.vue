<style type="text/css">
    .tooltip2 {
        top: auto;
    
    }

    #direct-income-report_paginate {
        display:none;
    
    }

    #direct-income-report_filter {
        display:none;
    
    }
    #direct-income-report_info {
        display:none;
    
    }

</style>
<template>
    <!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Rank By User ID</h4>
            </div>
        </div>

        <div class="page-content-wrapper">
            <div class="container">

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <div class="">
                                    <form id="change-userid">
                                        <div class="col-md-12">
                                            <input type="hidden" name="id" v-model="id">
                                            <!-- <div class="form-group col-md-2"></div> -->
                                            <div class="form-group col-md-4">
                                                <label>User Id</label>
                                                <input type="text" class="form-control" id="user_id" placeholder="User Id" v-model="user_id" v-on:keyup="checkUserExisted" name="user_id">
                                                <div class="clearfix"></div>
                                                <p :class="{'text-success': isAvialable == 'Available','text-danger': isAvialable == 'Not Available'}" v-if="isAvialable!='' && user_id!=''">{{isAvialable}}</p>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label>Full Name</label>
                                                <input name="fullname" class="form-control"  type="text" v-model="fullname" v-validate="'required'" readonly >
                                                <div class="tooltip2" v-show="errors.has('fullname')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('fullname')">{{ errors.first('fullname') }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label>Rank</label>
                                                  <select class="form-control" id="rank_name">
                                                    <option :value="null"  selected >Select</option>
                                                    <!-- <option v-bind:value="'All'">All</option> -->
                                                    <option v-for="option in getTypes" v-bind:value="option.rank"> {{ option.rank  }}</option> 
                                                </select>
                                            </div>


                                        </div>    
                                        <div class="col-md-12 text-center">
                                                <button type="button" class="btn btn-primary text-center" @click="getSummary()" id="submit_button">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div><!-- panel-body -->
                        </div><!-- panel -->
                    </div><!-- col -->
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                              
                                <table id="direct-income-report" class="table table-striped table-bordered dt-responsive">
                                    <thead>
                                        <tr>
                                            <th>RANK</th>
                                            <th class="text-center">LEFT COUNT</th>
                                            <th class="text-center">RIGHT COUNT</th>
                                        </tr>
                                    </thead>
                                       
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { apiAdminHost } from'./../../admin-config/config';
    import Swal from 'sweetalert2';

    export default {
        
         data() {
            return {
                length : 10,
                start  : 0,
                id:'',
                user_id: '',               
                fullname: '',               
                isAvialable:'',
                isUserExit : '',
                isDisabledBtn:true,
                err:'',
                getTypes:'',
                rank:'',   
                rank_name:'',
            }
        },        
        computed: {
            isCompleteUserid(){
                return this.user_id  && this.fullname  &&  this.getTypes;
            }
        },
        mounted() {
           this.getTransactionType();
        //    this.getCountry();
        },
        methods: {
            checkUserExisted(){
                axios.post('/checkuserexist',{
                     user_id: this.user_id,

                }).then(resp => {
                    if(resp.data.code === 200){
                        this.id = resp.data.data.id;
                        this.fullname = resp.data.data.fullname;
                        this.isAvialable = 'Available';
                        
                    } else {
                        this.id = '';
                        this.fullname = '';
                        this.isAvialable = 'Not Available';
                        
                       
                    }

                }).catch(err => {
                    this.$toaster.error(err);
                })
            },

           getTransactionType(){
                    axios.get('/get-all-rank', {
                })
                .then(response => {
                    this.getTypes = response.data.data;
                })
                .catch(error => {
                });      
            },


            getSummary(){
                  let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    const table = $('#direct-income-report').DataTable({
                        responsive: true,
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        stateSave: false,
                        ordering: false,
                        dom: 'Bfrtip',
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                        buttons: [
                            // 'copyHtml5',
                            // 'excelHtml5',
                            // 'csvHtml5',
                            // 'pdfHtml5',
                            // 'pageLength',
                        ],
                        ajax: {
                            url: apiAdminHost+'/getrankbyuserid',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                     user_id: $('#user_id').val(),
                                    rank: $('#rank_name').val(),
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
                             { data: 'rank' },
                            { data: 'left_count' },
                            { data: 'right_count' },
                              
                        ]
                    });
                   $('#submit_button').click(function () {
                        table.ajax.reload();
                    });

                    $('#onResetClick').click(function () {
                        $('#searchForm').trigger("reset");
                        table.ajax.reload();
                    });
                },0);
            },
            
        }
    }
</script>