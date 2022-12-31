<template>
<!-- start content -->
<div class="content">
	<div class="">
	    <div class="page-header-title">
	        <h4 class="page-title">All ICO Phases List  </h4>
	    </div>
	</div>
	<div class="page-content-wrapper">
	    <div class="container">
	        <div class="row">
	            <div class="col-md-12">
	                <div class="panel panel-primary">
	                                            <!-- <div class="panel-body">
                            <div class="">
                                <form id="searchForm">
                                    <div class="row">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>From Date</label>
                                                <div>
                                                    <div class="input-group">
                                                        <DatePicker :bootstrap-styling="true" name="frm_date" :format="dateFormat" placeholder="From Date" id="frm_date"></DatePicker>
                                                        
                                                        <span class="input-group-addon bg-custom b-0 datepicker_border">
                                                            <i class="mdi mdi-calendar"></i>
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>To Date</label>
                                                <div>
                                                    <div class="input-group">
                                                        <DatePicker :bootstrap-styling="true" name="to_date" :format="dateFormat" placeholder="To Date" id="to_date"></DatePicker>
                                                        <span class="input-group-addon bg-custom b-0 datepicker_border">
                                                            <i class="mdi mdi-calendar"></i>
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>User Id</label>
                                                <input class="form-control" placeholder="Enter User Id" type="text" id="user_id">
                                            </div>
                                        </div>
                                                        </div>

                                    <div class="row">
                                        <div class="text-center">
                                            <button type="button" class="btn btn-primary waves-effect waves-light" id="onSearchClick">Search</button>
                                            
                                            
                                            <button type="button" class="btn btn-dark waves-effect waves-light mt-4" id="onResetClick">Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div> -->
	                </div>
	            </div>
	        </div>

	        <div class="row">
	            <div class="col-md-12">
	                <div class="panel panel-primary">
	                    <div class="panel-body">
	                        <table id="ico-phases-list" class="table table-striped table-bordered dt-responsive">
	                            <thead>
	                                <tr>
	                                   <th>Sr No</th>
                                       <th>Phase Name</th>
                                       <!--  <th>Start Date</th>
                                        <th>End Date</th> -->
                                        <th>Total Coin</th>
                                        <th>Sell Coin</th>
                                        <th>Available Coin</th>
                                        <th>Buy Min Coin</th>
                                        <th>Coin Rate</th>
                                        <th>Status</th>
                                       <!--  <th>Action</th>  --> 
	                                </tr>
	                            </thead>
	                            <tfoot>
	                                <tr><th>Sr No</th>
                                       <th>Phase Name</th>
                                      <!--   <th>Start Date</th>
                                        <th>End Date</th> -->
                                        <th>Total Coin</th>
                                        <th>Sell Coin</th>
                                        <th>Available Coin</th>
                                        <th>Buy Min Coin</th>
                                        <th>Coin Rate</th>
                                        <th>Status</th>  
                                       <!--  <th>Action</th>   -->
	                                    
	                                </tr>
	                            </tfoot>
	                        </table>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</div>
<!-- content -->
</template>

<script>
   	import { apiAdminHost } from'./../../admin-config/config';
   	import moment from 'moment';
   	import DatePicker from 'vuejs-datepicker';
        import Swal from 'sweetalert2';


    export default {
        data() {
            return {
                provide_help_data  : [],
                length : 10,
                start  : 0,
                arrProducts:[],
                INR:'',
            }
        },
        mounted() {
            this.productReport();
            this.getProducts();
            this.getProjectSetting();
        },
        components: {
            DatePicker
        },
        methods: {
        	dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            productReport(){
            	let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    const table = $('#ico-phases-list').DataTable({
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
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5',
                            'pageLength',
                        ],
                        ajax: {
                            url: apiAdminHost+'/geticophases',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    product_id: $('#product_id').val(),
                                    id: $('#user_id').val(),
                                    status: $('#status').val(),
                                    pin: $('#pin').val(),
                                    frm_date: $('#frm_date').val(),
                                    to_date: $('#to_date').val(),
                                    frompage:'admin'
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
                                    return meta.row + 1;
                                }
                            },
                             { data: 'name' },
                            /* { data: 'from_date' },
                             { data: 'to_date' },*/
                             { data: 'total_supply' },
                             { data: 'sold_supply' },
                             
                            /*{ data: 'payment_type' },*/
                            /*{ data: 'withdraw' },*/
                            {
                                render: function (data, type, row, meta) {
                                    
                                        return row.total_supply - row.sold_supply;
                                    
                                }
                            },
                            { data: 'min_coin' },
                            { data: 'usd_rate' },
                            // { data: 'status' },
                            {
                               render:function(data,type,row,meta){

                                if(row.status=='Available'){
                                    return `<span class="text-info" >Active</sapn>`;
                                }else{

                                    return `<span class="text-danger" >Inactive</sapn>`;
                                    
                                }
                               }

                            },
                            /*{
                                render: function (data, type, row, meta) {
                                    if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.entry_time)).format('YYYY-MM-DD');
                                    }
                                }
                            }*/
                            /* {
                               render: function (data, type, row, meta) {


                                   if(row.status=='Available'){

                                    return `<a href="javaScript:void(0);" class="text-danger waves-effect manualapproverequest" data-id="${row.srno}" data-status="${row.status}">
                                      Make as Inactive
                                     </a>
                                    `;

                                   }else{
                                    return `<a href="javaScript:void(0);" class="text-info waves-effect manualapproverequest" data-id="${row.srno}" data-status="${row.status}"> 
                                         Make as Active
                                       </a>`;


                                   }
                                    
                                }
                            },*/
                        ]
                    });

                    $('#onSearchClick').click(function () {
                        table.ajax.reload();
                    });

                    $('#onResetClick').click(function () {
                        $('#searchForm').trigger("reset");
                        table.ajax.reload();
                    });
                    $('#ico-phases-list').on('click','.manualapproverequest',function () {
                       // alert($(this).data('id'));
                        
                          that.changesStatus($(this).data('id'),$(this).data('status'));
                         });


                    
                },0);
            },
            getProducts(){
            	axios.get('/getproducts',{
                }).then(resp => {
                	if(resp.data.code === 200){
                		this.arrProducts = resp.data.data;
                	}
                }).catch(err => {
                	
                })
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
            changesStatus(id,status){


                Swal({
                    title: 'Are you sure?',
                    text: `You want to changes status`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {

                    axios.post('updateStatusPhasesStatus', {srno:id,status:status
                    })
                    .then(response => {
                        this.$toaster.success(response.data.message);

                        $("#ico-phases-list").DataTable().ajax.reload();
                        //this.$toaster.success(response.data.message);
                    })
                    .catch(error => {
                    }); 
                    
                });


                   
            }
        }
    }
</script>