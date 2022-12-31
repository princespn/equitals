<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Daily Bussiness Report</h4>
		    </div>
		</div>

		<div class="page-content-wrapper">
		    <div class="container">
		        
		        <div class="row">
		            <div class="col-md-12">
		                <div class="panel panel-primary">
		                    <div class="panel-body">
		                        <table id="direct-income-report" class="table table-striped table-bordered dt-responsive">
		                            <thead>
		                                <tr>
		                                    <th>Sr.No</th>
		                                    <th>total_business</th>
                                            <th>Date</th> 
                                            <!-- <th>TDS</th>
                                            <th>Admin Charges</th>
                                            <th>Net Amount</th>-->
		                                </tr>
		                            </thead>
		                            <!-- <tfoot>
		                                <tr>
		                                    <th>Total</th>
                                            <th></th>
                                            <th></th>
                                            <th>0</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
		                                </tr>
		                            </tfoot> -->
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
    import moment from 'moment';
    import DatePicker from 'vuejs-datepicker';

    export default {
        data() {
            return {
                provide_help_data  : [],
                length : 10,
                start  : 0,
                filters:{
                	product_id:'',
	                cost:'',
	                b_value:'',
                },
                arrProducts:[],
            }
        },
        mounted() {
            this.productReport();
           // this.getProducts();
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
                    const table = $('#direct-income-report').DataTable({
                        responsive: true,
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        stateSave: false,
                        ordering: false,
                        searching: false,
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
                            url: apiAdminHost+'/getdailybussinessdata',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    frm_date: $('#frm_date').val(),
                                    to_date: $('#to_date').val(),
                                    id: $('#to_user_id').val(),
                                    // from_user_id: $('#from_user_id').val(),
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
                                    {
                                        //return meta.row + 1;
                                        //return meta.row + meta.settings._iDisplayStart + 1;
                                        return i++;                                        
                                    }
                                }
                            },
                            /*{
                                render: function (data, type, row, meta) {
                                    return `<span>${row.to_user_id}</span><span>(${row.to_fullname})</span>`;
                                }
                            },*/
                            { data: 'total_business' }, 
                           /* { data: 'status' },*/
                            {
                                render: function (data, type, row, meta) {
                                    if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.entry_time)).format('YYYY-MM-DD');
                                    }
                                }
                            },
                            // { data: 'tax_amount' },
                            // { data: 'amt_pin' },
                            // { data: 'net_amount' },
                            // {
                            //     render: function (data, type, row, meta) {
                            //         if (row.status === 'Paid') {
                            //             return `<label class="text-info">${row.status}</label>`;
                            //         } else {
                            //             return `<label class="text-warning">${row.status}</label>`;
                            //         }
                            //     }
                            // }
                        ]
                    });
                   $('#onSearchClick').click(function () {
                        table.ajax.reload();
                    });

                    $('#onResetClick').click(function () {
                        $('#searchForm').trigger("reset");
                        table.ajax.reload();
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
            reset() {
                this.product_id = this.cost = this.b_value = '';
            }
        }
    }
</script>