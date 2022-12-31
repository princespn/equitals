<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Edit User Profile Report</h4>
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
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>To User Id</label>
                                                 <input class="form-control" required="" placeholder="Enter user id" type="text" id="to_user_id">
                                                <!-- <p :class="{'text-success': isAvialable == 'Available','text-danger': isAvialable == 'Not Available'}" v-if="isAvialable!='' && username!=''">{{isAvialable}}</p>
                 			                    <span :class="{'text-success': isAvialable == 'Available','text-danger': isAvialable == 'Not Available'}" v-if="isAvialable == 'Available'"> {{ username }}   ({{ fullname }})</span> -->
                                            </div>
                                        </div>
		                                <div class="row">
		                                    <div class="col-md-12">
		                                        <div class="text-center">
                                                <button type="button" class="btn btn-primary waves-effect waves-light" id="onSearchClick">Search</button>

		                                            <button type="button" class="btn btn-info waves-effect waves-light" @click="exportToExcel">Export To Excel</button>
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
                                <div class="table-responsive">

		                        <table id="edit-user-profile-report" class="table table-striped table-bordered dt-responsive">
		                            <thead>
		                                <tr>
		                                    <th>Sr.No</th>
                                            <th>User Id</th>
                                            <th>Full Name</th>
                                            <th>Mobile</th>
                                            <th>BTC Address</th>
                                            <th>TRX Address</th> 
                                            <th>LTC Address</th>
                                            <th>DOGE Address</th>
                                            <th>USDT Address</th>
                                            <th>ETHEREUM</th>
                                            <th>SOLONA Address</th>
                                            <th>XRP Address</th>
                                           
                                            
                                            <!-- <th>USDT-TRC20 Address</th>  -->
                                            <th>IP Address</th>
                                            <th> Updated By </th>
                                            <!-- <th>More Details</th> -->
		                                </tr>
		                            </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>User Id</th>
                                            <th>Full Name</th>
                                            <th>Mobile</th>
                                            <th>BTC Address</th>
                                             <th>TRX Address</th> 
                                            <th>LTC Address</th>
                                            <th>DOGE Address</th>
                                             <th>USDT Address</th>
                                             <th>ETHEREUM</th>
                                             <th>SOLONA Address</th>
                                             <th>XRP Address</th>


                                           
                                           <!--  <th>USDT-TRC20 Address</th>  -->
                                            <th>IP Address</th>
                                            <th> Updated By </th>
                                            <!-- <th>More Details</th> -->
                                        </tr>
                                    </tfoot>
		                        </table>
                                </div>
		                    </div>
		                </div>
		            </div>
		        </div><!-- end row -->

             <!--   <div class="modal fade" id="edit-user-model">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="fa fa-times"></span></button>
                                 <h5 class="modal-title" id="exampleModalLabel">More Details</h5>
                            </div>
                            <div class="modal-body">
                               <div  class="table-responsive">
                                    <table cellspacing="0" class="table table-bordered table-striped" id="order-listing" width="100%">
                                        <thead>
                                            <tr>
                                                <th> Country </th>
                                                <th> Sponsor Id </th>
                                                <th> IP Address </th>
                                                <th> Updated By </th>
                                                <th> Created At </th>
                                            </tr>
                                        </thead> 
                                        <tbody>
                                            <tr>
                                                <td>{{ edituserreport.country }}</td>
                                                <td>{{ edituserreport.sponser }}</td>
                                                <td>{{ edituserreport.ip }}</td>
                                                <td>{{ edituserreport.updated_by }}</td>
                                                <td>{{ edituserreport.entry_time }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->

		    </div><!-- container -->
		</div><!-- Page content Wrapper -->
	</div><!-- content -->
</template>

<script>
    import moment from 'moment';
    import DatePicker from 'vuejs-datepicker';
   	import { apiAdminHost } from'./../../admin-config/config';
    export default {
        data() {
            return {
                 user_id:'',
                fullname:'',
                username:'',
                isAvialable:'',
                frm_date : null,
                to_date : null,
                edituserreport : {},
                btc_address:"",
                trn_address:"",
                ltc_address:"",
                doge_address:"",
                usdt_taddress:"",
                ethereum:"",
                solona_address:"",
                ripple_address:"",
            }
        },
        components: {
            DatePicker
        }, 
        mounted() {
            this.productReport();
        },
        methods: {
                     checkUserExisted(){
        axios.post('/checkuserexist',{
            user_id: this.username,
        }).then(resp => {
            if(resp.data.code === 200){
                this.user_id = resp.data.data.user_id;
                this.fullname = resp.data.data.fullname;
                this.isAvialable = 'Available';
            } else {
                this.user_id = '';
                this.isAvialable = 'Not Available';
            }
        }).catch(err => {
            // this.$toaster.error(err);
        })
    },
       
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            productReport(){
                let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
              
                setTimeout(function(){
                    that.table = $('#edit-user-profile-report').DataTable({
                        responsive: true,
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        stateSave: false,
                        ordering: false,
                        dom: 'lrtip',
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                        buttons: [
                            // 'copyHtml5',
                            // 'excelHtml5',
                            // 'csvHtml5',
                            // 'pdfHtml5',
                            // 'pageLength',
                        ],
                        ajax: {
                            url: apiAdminHost+'/getuserlogs',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;
                                let params = {
                                    frm_date: $('#frm_date').val(),
                                    to_date: $('#to_date').val(),
                                    id: $('#to_user_id').val(),
                                    // product_id:$('#product_id').val(),
                                    status: $('#status').val(),
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
                            { data: 'user_id' },
                            { data: 'fullname' },
                            { data: 'mobile' },
                            { data: 'btc_address' },
                            { data: 'trn_address' },
                            { data: 'ltc_address' },
                            { data: 'doge_address' },
                             { data: 'usdt_taddress' },
                             { data: 'ethereum' },
                            { data: 'solona_address' },
                            { data: 'ripple_address' },
                            
                            
                            // { data: 'usdt_trc20_address' },
                            /*{ data: 'old_btc_address' },
                             { data: 'new_btc_address' },
                             { data: 'old_bnb_address' },
                             { data: 'new_bnb_address' },
                             { data: 'old_ethereum' },
                            { data: 'new_ethereum' },
                             { data: 'old_trn_address' },
                            { data: 'new_trn_address' },*/
                            { data: 'ip'},
                            { data: 'updated_by' }
                            // {
                            //    render: function (data, type, row, meta) {
                            //         return '<label class="text-success waves-effect" id="edit-user-profile">Details</label>';
                                    
                            //     }
                            // },
                        ]
                    });
                    $('#edit-user-profile-report').on('click','#edit-user-profile', function (){
                        //$('#edit-user-model').modal();      
                        if (that.table.row($(this).parents('tr')).data() !== undefined) {
                            var data = that.table.row($(this).parents('tr')).data();
                            that.OnShowPinxClick(data);
                        } else {
                            var data = that.table.row($(this)).data();
                            that.OnShowPinxClick(data);
                        }
                    });                    
                    $('#onSearchClick').click(function () {
                        that.table.ajax.reload();
                    });
                    $('#onResetClick').click(function () {  
                        $('#searchForm').trigger("reset");
                        that.username = '';
                        that.isAvialable='';
                        that.table.ajax.reload();
                    });
                },0);                
            },

            exportToExcel(){
                var params = {frm_date: $('#frm_date').val(), to_date: $('#to_date').val(),id: $('#to_user_id').val(),status: $('#status').val(),action:'export',responseType: 'blob' };
                axios.post('getuserlogs', params).then(resp => {
                    if(resp.data.code === 200){
                        //this.export_url = resp.data.data.excel_url;
                        var mystring = resp.data.data.data;
                        var myblob = new Blob([mystring], {
                            type: 'text/plain'
                        });

                        var fileURL = window.URL.createObjectURL(new Blob([myblob]));
                        var fileLink = document.createElement('a');

                        fileLink.href = fileURL;
                        fileLink.setAttribute('download', 'EditUserDetails.xls');
                        document.body.appendChild(fileLink);

                        fileLink.click();
                    }else{
                        this.$toaster.error(resp.data.message);
                    }
                });
            },
            OnShowPinxClick(data) {
                $('#edit-user-model').modal();                 
                this.edituserreport = data;
            }
        }
    }
</script>