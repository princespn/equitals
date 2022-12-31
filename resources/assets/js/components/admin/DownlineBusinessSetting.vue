<template>
	<!-- start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Downline Business Setting</h4>
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
		                               <!--  <div class="col-md-3"></div> -->
		                                 <div class="col-md-2">
		                                     <div class="form-group">
                                            <label>From Date</label>
                                            <div>
                                                <div class="input-group">
                                                    <DatePicker :bootstrap-styling="true" name="frm_date" :format="dateFormat" placeholder="From Date" id="frm_date"></DatePicker>
                                                 
                                                    <span class="input-group-addon bg-custom b-0">
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
                                                   
                                                    <span class="input-group-addon bg-custom b-0">
                                                        <i class="mdi mdi-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
		                                </div>
		                                 <div class="col-md-2">
		                                    <div class="form-group">
		                                        <label>User Id</label>
		                                        <input class="form-control" required="" placeholder="Enter user id" type="text" id="user_id">
                                               
		                              </div>
		                                </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label></label>
                                                 <button type="button" class="btn btn-primary waves-effect waves-light" id="onSearchClick">Search</button>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label></label>
                                                <button type="button" class="btn btn-dark waves-effect waves-light mt-4" id="onResetClick">Reset</button>
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
                                    <div class="">


                                     <div> 
                                     <h2> Total Business(<span class="total_business"></span>) - Total Setting (<span id="business_setting"></span>)= <span id="bms"></span> 
                                    </h2>
                                     </div>
                                        <div class="col-md-3">
                                               <div class="form-group">
                                            <label>Setting Amount</label>
                                            <div>
                                                <div class="input-group">
                                                   <input class="form-control" required=""  type="number" name="" placeholder="Enter Amount" v-model="settingAmt">
                                                </div>
                                            </div>
                                        </div>

                                        </div>

                                        <div class="col-md-3">
                                               <div class="form-group">
                                            <label>Enter Remark</label>
                                            <div>
                                                <div class="input-group">
                                                   <textarea class="form-control" required=""  type="number" name="" placeholder="Enter Remark" v-model="remark">
                                                   </textarea>
                                                </div>
                                            </div>
                                        </div>

                                        </div>
                                       
                                         <div class="col-md-2">
                                            <div class="form-group">
                                              <label></label>
                                                 <button type="button" class="btn btn-primary waves-effect waves-light" @click="saveBusinessSettingFun">Save</button>
                                            </div>
                                        </div>


                                                                              
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="text-center">
                                                   
                                                    <!-- <button type="button" class="btn btn-info waves-effect waves-light">Export To Excel</button> -->
                                                    <!-- <button type="button" class="btn btn-dark waves-effect waves-light mt-4" id="onResetClick">Reset</button> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- panel-body -->
                            </div><!-- panel -->
                        </div><!-- col -->
                    </div>
		        <div class="row">
		            <div class="col-md-12">
		                <div class="panel panel-primary">
		                    <div class="panel-body">
		                        <table id="downline-user-report" class="table table-striped table-bordered dt-responsive">
		                            <thead>
		                                <tr>
		                                  <th>Sr.No</th>
                                            <th>User Id</th>
                                            <th>Full Name</th>
                                            <th>Amount</th>
                                            <th>Status URL</th>
                                            <th>Invoice Id</th>
                                            <th>Payment Mode</th>
                                            <th>Address</th>
                                            <th>Date</th>
		                                    <!-- <th>Action</th> -->

		                                </tr>
		                            </thead>
		                            
		                            <tfoot>
		                                <tr>
		                                    <th></th>
                                            <th></th>
                                            <th>Total</th>
                                            <th class="total_business"></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
		                                </tr>
		                            </tfoot>
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
                user_data  : [],
                products   : [],
                length : 10,
                start  : 0,
                settingAmt  : '',
                remark  : '',
            }
        },
        mounted() {
           // alert(111);
        	
            this.downlineUsersReport();
        },
        components: {
            DatePicker
        },
        methods: {
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            saveBusinessSettingFun(){

               let user_id= $('#user_id').val();
 
                axios
                  .post("saveBusinessSetting", {user_id:user_id,amount:this.settingAmt,remark:this.remark})
                  .then(response => {
                    if(response.data.code==200){

                   // this.usddata = response.data.data.usddata;

                 this.$toaster.success(response.data.message);
                    //this.downlineUsersReport();
                }else{
                 this.$toaster.error(response.data.message);
                }

                    /*console.log(this.usddata);*/
                    
                  })
                  .catch(error => {});
    
            },

            downlineUsersReport(){
            	let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                   const table = $('#downline-user-report').DataTable({
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
                            url: apiAdminHost+'/findDownlineUsersBusiness',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    frm_date: $('#frm_date').val(),
                                    to_date: $('#to_date').val(),
                                    user_id: $('#user_id').val()
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
                                    $(".total_business").html(json.data.total_business);
                                    $("#business_setting").html(json.data.business_setting);

                                    let bms=(json.data.total_business-json.data.business_setting)
                                    $("#bms").html(bms);
                                    return json.data.records;
                                } else {
                                    //alert('');

                                   // that.$toaster.error(json.data.message);
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
							{ data: 'fullname' },
                            { data: 'hash_unit' },//payment_mode,address,hash_unit
                               {
                                render: function (data, type, row, meta) {
                                    return "<a href='"+row.status_url+"'>Checkout</a>";
                                }
                            },
                            { data: 'invoice_id' },
                            { data: 'payment_mode' },
                            { data: 'address' },
                          
							
							{
                                render: function (data, type, row, meta) {
                                    if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.entry_time)).format('YYYY-MM-DD');
                                    }
                                }
                            }
                        ]
                    });
                       $('#onSearchClick').click(function () {
                        table.ajax.reload();
                    });

                    $('#onResetClick').click(function () {
                        $('#searchForm').trigger("reset");
                    });
                },0);
            },
            
        }
    }
</script>