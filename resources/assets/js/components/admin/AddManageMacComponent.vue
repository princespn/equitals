<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Add Mac Address</h4>
		    </div>
		</div>

		<div class="page-content-wrapper">
		    <div class="container">
		    	<div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <div class="">
                                    <form id="addTopUp">
                            			<div class="col-md-5 col-md-offset-3">

                                             <!-- <div class="form-group">
                                                <label>Payment Type</label>
                                                <select name="payment_type" class="form-control" placeholder="Select Payment Type" v-model="topup.payment_type" id="">
                                                    <option value="BTC" selected="">BTC</option>
                                                   
                                                </select>
                                            </div> -->
                                           
  										    <!-- <div class="form-group">
                                                <label>Select Country</label>
                                                 <select
                                                  v-model="topup.country"
                                                  name="package_id"
                                                  class="form-control"
                                                  @change="getFranchiseOnCountry(topup.country)"
                                                >
                                                  <option disabled value="" selected>Select Country</option>
                                                  <option
                                                    v-for="co in countries"
                                                    v-bind:value="co.iso_code"
                                                  >{{ co.country }}</option>
                                                </select>
                                            </div> -->



                                            <!-- <div class="form-group">
                                                <label>Select Franchise</label>
                                                <select name="franchise_user_id" class="form-control" v-model="franchise.user_id" id="franchise_user_id" >
                                                    <option value="" selected>Select Franchise</option>
                                                    <option v-for="franchise_user in franchise" v-bind:value="franchise_user.id">{{ franchise_user.user_id }} </option>
                                                </select>
                                            </div> -->

<!-- 
                                            <div class="form-group">
                                                <label>Select Master  Franchise</label>
                                                <select name="franchise_user_id" class="form-control" v-model="masterfranchise.user_id" id="franchise_user_id" >
                                                    <option value="" selected>Select Master Franchise</option>
                                                    <option v-for="franchise_user in mflist" v-bind:value="franchise_user.id">{{ franchise_user.user_id }} </option>
                                                </select>
                                            </div> -->
                                            <div class="form-group">
                                                <label class="control-label">Add Mac Address</label>
                                                <br />
                                                <input
                                                type="text"
                                                class="form-control"
                                                name="mac_address"
                                                v-model="mac_address"
                                                v-validate="'required'"
                                                />
                                                <div class="clearfix"></div>
                                                <!-- <p class="text-danger">{{usermsg}}</p> -->
                                            </div>

                                           
                                       <!--     
                                            <div class="form-group" v-if="topup.product_id">
                                                <label>Amount</label>

                                                <input type="number" class="form-control" name="hash_unit" 
                      min="1"
                      step="1"
                      onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                      title="Numbers only"  v-model="topup.hash_unit" v-validate="'required|numeric'" v-on:keyup="hashvalidation" >
                                                                   
                                                <p v-if='!isValid' >
                                                    <span class=" text-danger error-msg-size tooltip-inner"> {{ this.usermsg }}</span>
                                                </p>  
                                            </div> -->
                                            
  											<div class="col-md-offset-5">
  												<button type="button" class="btn btn-primary text-center" @click="addTopUp" :disabled="!isComplete">Submit</button>
  											</div>
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
                                <table id="total-sell-report" class="table table-striped table-bordered dt-responsive">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Mac Address</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Mac Address</th>
                                            <th>Status</th>
                                            <th>Date</th>
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
</template>

<script>
    import { apiAdminHost } from'./../../admin-config/config';
    import moment from 'moment';
    import Swal from 'sweetalert2';

    export default {
    	
        data() {
            return {
                product_ids: {
                    id: '',
                    name: ''
                },
                topup:{
                	user_id: '',
                    pin: '',
                	product_id: null,
                    hash_unit:'',
                    payment_type:'BTC',
                },
                isAvialable:'',
                username:'',
                values:'',
                getdata:{},
                arrProduct:[],
                min_hash:'',
                max_hash:'',
                isValid:true,
                usermsg:'',
                isDisabledBtn:true,
                franchise:{
                    user_id:'',
                },
                  masterfranchise:{
                    user_id:'',
                    id:''
                  },
                  masterFranchiseList:[],
                //   countries:[],
                  mflist:{},
                  mac_address:'',
            }
        },        
        computed: {
            //
            isComplete () {
                return this.mac_address;
            },
            
        },
        mounted() {
            this.productReport();
            //this.getFranchiseUserList();
            // this.getMasterFranchiseUserList();
            // this.getCountry()
        },
        methods: {
             
    //    getCountry() {
    //   axios
    //     .get("../country", {})
    //     .then(response => {
    //       this.countries = response.data.data;
    //     })
    //     .catch(error => {});
    // },


            getFranchiseOnCountry(country){

                    axios.post("get-franchise-users", {country:country})
                .then(response => {
                    this.franchise = response.data.data;
                })
                .catch(error => {

                });

            },

             ///--- 
    getMasterFranchiseUserList(){
      axios.get("get-master-franchise-users", {})
      .then(response => {
        this.masterFranchiseList = response.data.data;
        this.mflist = response.data.data;
      })
      .catch(error => {

      });
    },

            getFranchiseUserList(){
                axios.get("get-franchise-users", {})
                .then(response => {
                    this.franchise = response.data.data;
                })
                .catch(error => {

                });
            },
            changeSelect(event){
                let user = _.split(event.target.value, '-', 2); //using lodash here. You can also just use js split func
                let id = user[0]; // your id
                this.min_hash=this.arrProduct[id].min_hash;
                this.max_hash=this.arrProduct[id].max_hash;
                this.activeDiv=true;
                this.hashvalidation();
               // this.usermsg='Amount should be on range ' + this.min_hash + ' to ' + this.max_hash; 
            },
            addTopUp(){
                this.isDisabledBtn = false;
                Swal({
                    title: 'Are you sure ?',
                    text: "You want to add mac address!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/add-mac-address',{
                            mac_address: this.mac_address,                          
                        }).then(resp => {
                            if(resp.data.code === 200) {
                                this.$toaster.success(resp.data.message);
                                this.mac_address = '';
                            } else {
                                this.$toaster.error(resp.data.message);
                            }
                        
                        }).catch(err => {
                            this.$toaster.error(err);
                        });
                    }
                    else{
                        this.isDisabledBtn = true;
                    }
                });     
            },
			checkUserExisted(){
				axios.post('/checkuserexist',{
                    user_id: this.username,
                }).then(resp => {
                    if(resp.data.code === 200){
                    	this.topup.user_id = resp.data.data.id;
                    	this.isAvialable = 'Available';
                    } else {
                    	this.topup.user_id = '';
                    	this.isAvialable = 'Not Available';
                    }
                }).catch(err => {
                	this.$toaster.error(err);
                })
			},
            hashvalidation() {
                if (this.topup.hash_unit < this.min_hash || this.topup.hash_unit > this.max_hash) 
                {
                    this.usermsg = "Amount should be on range " + this.min_hash + " to " + this.max_hash;
                    this.isValid = false;
                } else {
                    this.isValid = true;
                    this.usermsg = "";
                }
            },
            productReport(){
                let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    const table = $('#total-sell-report').DataTable({
                        responsive: true,
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        stateSave: false,
                        ordering: false,
                        dom: 'Brtip',
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                        buttons: [
                            // 'copyHtml5',
                            /*'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5',*/
                            'pageLength',
                        ],
                        ajax: {
                            url: apiAdminHost+'/get-mac-address',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    // product_id: $('#product_id').val(),
                                    // user_id: $('#user_id').val(),
                                    // status: $('#status').val(),
                                    // pin: $('#pin').val(),
                                    // frm_date: $('#frm_date').val(),
                                    // to_date: $('#to_date').val()
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
                                    //return meta.row + 1;
                                    return i++;
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    return `<span>${row.mac_address}</span>`;
                                }
                            },
                             {
                              render: function(data, type, row, meta) {
                                    if(row.status == "Active")
                                    {
                                        return `<a href="javaScript:void(0);" class="text-info waves-effect changeStatus" data-id="${row.id}" data-status="${row.status}">${row.status}
                                            </a>`;   
                                    }else
                                    {
                                        return `<a href="javaScript:void(0);" class="text-danger waves-effect changeStatus" data-id="${row.id}" data-status="${row.status}">${row.status}
                                            </a>`;  
                                    }
                                  
                                
                              }
                            },
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
                        table.ajax.reload();
                    });


                    $('#total-sell-report').on('click','.changeStatus',function () {
                        that.changeStatus($(this).data("id"),$(this).data("status"));
                    });
                    
                },0);
            },
            changeStatus(id,status){
                var msg = (status == "Active")?"Inactive":"Active";
                this.sr_no=id;
                Swal({
                     title: 'Are you sure?',
                    text: `You want to `+msg+" Mac Address?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/change-mac-address-status',{
                            id: id,
                            status: status,
                        }).then(resp => {
                            if(resp.data.code === 200){
                                this.$toaster.success(resp.data.message);
                                $('#total-sell-report').DataTable().ajax.reload();    
                            } else {
                                this.$toaster.error(resp.data.message);
                            }
                        }).catch(err => {
                            this.$toaster.error(err);
                        })
                    }
                });
            },
        }
    }
</script>