<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Manage Flight Logo and Name</h4>
		    </div>
		</div>

		<div class="page-content-wrapper">
		    <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-3">
                        <div class="panel panel-primary">
                            <div class="panel-body add-new">
                               <!--  <router-link class="btn btn-primary waves-effect waves-light" :to="{name: 'addecommerceproduct'}">
                                    <i class="fa fa-plus"></i>Add Product
                                </router-link> -->
                               <button @click="addFlightNameFun"
                               class="btn btn-primary waves-effect waves-light">Add New Flight</button>
                                
                            </div>


                        </div>
                    </div>
                </div>

		        <div class="row">
		            <div class="col-md-12">
		                <div class="panel panel-primary">
		                    <div class="panel-body">
		                        <table id="flight-logo-datatable" class="table table-striped table-bordered dt-responsive">
		                            <thead>
		                                <tr>
		                                    <th>Sr.No</th>
		                                    <th>Airline Name</th>
                                            <th>IATA</th>
		                                    <th>3-Digit Code</th>
		                                    <th>ICAO</th>
                                              <th>Logo</th>
                                            <th>Add Logo Img</th>
		                                    <th>Action</th>
		                                </tr>
		                            </thead>
		                            <tfoot>
		                                <tr>
		                                    <th>Sr.No</th>
                                            <th>Airline Name</th>
                                            <th>IATA</th>
                                            <th>3-Digit Code</th>
                                            <th>ICAO</th>
                                            <th>Logo</th>
                                            <th>Add Logo Img</th>
                                            <th>Action</th>
		                                </tr>
		                            </tfoot>
		                        </table>
		                    </div>
		                </div>
		            </div>
		        </div><!-- end row -->

                  <!-- Product Details Modal -->
        <div class="modal fade" id="showPinModel" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel">Add Neww Logo</h4>
                    </div>
                    <form enctype="multipart/form-data" id="sendReplyForm">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" name="ticket_id" id="ticket_id">
                                    <div class="bootstrap-filestyle input-group">
                                        
                                        <div class="bootstrap-filestyle input-group" style="width: 100%">
                                            <input type="text" name="attachments" id="attachments" v-model="fileName" class="form-control" placeholder="" disabled="">
                                            <span class="group-span-filestyle input-group-btn" tabindex="0">
                                                <label for="filestyle-0" class="choose_file">
                                                    <input type="file" name="file" id="file" ref="file" style="display:none" @change="getImageName">
                                                    <img :src="adminAssetsURL+'/images/choose_file.png'" id="upfile1" style="cursor:pointer" @click="selectImage()" />
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>                           
                        </div>
                       
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary waves-effect waves-light" @click="uploadFlightLogo">Upload  Logo</button>

                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
          <!-- Product Details Modal -->
        <div class="modal fade" id="showaddFlightNameModel" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel">Add Neww Flight </h4>
                    </div>
                    <form enctype="multipart/form-data" id="sendReplyForm">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" name="ticket_id" id="ticket_id">
                                    <div class="bootstrap-filestyle input-group">
                                        
                                        <div class="bootstrap-filestyle input-group" style="width: 100%">
                                           <div class="FormData">

                                              <label> Airline Name</label>
                                               
                                               <input type="text" class="form-control" v-model="airline_name" name="">
                                              
                                           </div>

                                           <div class="FormData">
                                             <label> Airline IATA Code</label>
                                                <input type="text" class="form-control" v-model="IATA" name="">
                                           </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>                           
                        </div>
                       
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary waves-effect waves-light" @click="addFlightName">Add Flight</button>

                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

		    </div><!-- container -->
		</div><!-- Page content Wrapper -->
	</div><!-- content -->
</template>

<script>
    import { apiAdminHost,adminAssets} from'./../../admin-config/config';
    import moment from 'moment';
    import Swal from 'sweetalert2';
    
    export default {
    	
        data() {
            return {
                user_data  : [],
                products   : [],
                length : 10,
                start  : 0,
                airline_name  : '',
                IATA  : '',
                id:'',
                fileName:'',
                  adminAssetsURL:adminAssets,
            }
        },
        mounted() {
        	
            this.manageNews();
        },
        methods: {
             getImageName(e){
                var fileName = e.target.files[0].name;
                this.fileName = fileName;              
            },
             selectImage(){
                $("#file").trigger('click');
            },
             uploadFlightLogo(){

                var id = $("#ticket_id").val();
               // var remark = $("#remark").val();
                var file = $("#file").val();

                if(file == ''){
                    this.$toaster.error("Please select file"); 
                    return false;
                }

                if(file != ''){

                let formData = new FormData();
                if(this.$refs.file.files[0] != ''){
                    formData.append('file', this.$refs.file.files[0]);
                }
                formData.append('id', id);
                //formData.append('remark', remark);
                   
                Swal({
                    title: 'Are you sure ?',
                    text: "You won't Upload Logo!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        axios.post('uploadFlightLogo',
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }).then(response => {
                            if(response.data.code === 200) {
                                this.$toaster.success(response.data.message);
                               // setTimeout(function(){ location.reload(); }, 300); 
                               $('#showPinModel').modal('hide');   
                              $('#flight-logo-datatable').DataTable().ajax.reload();

                          
                            } else {
                                this.$toaster.error(response.data.message);
                                $('#flight-logo-datatable').DataTable().ajax.reload();
                               
                            }
                        }).catch(error => {
                            //this.$toaster.success(response.data.message);
                            this.message  = error.response.data.message;
                            this.flash(this.message, 'error', {
                              timeout: 500000,
                            });
                        });
                    }
                })
            }

            },
            manageNews(){
            	let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    that.table = $('#flight-logo-datatable').DataTable({
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        responsive: true,
                        stateSave: false,
                        ordering: false,
                        dom: 'Bfrtip',
                        lengthMenu: [[10, 50, 100, 500, 1000, 5000, 10000], [10, 50, 100, 500, 1000, 5000, 10000]],
                        buttons: [
                            'pageLength',
                            // 'colvis',
                            {
                                extend: 'excelHtml5',
                                title: 'Ecommerce Product Report',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'csvHtml5',
                                title: 'Ecommerce Product Report',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'pdfHtml5',
                                title: 'Ecommerce Product Report',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            // {
                            //     extend: 'print',
                            //     title: 'Ecommerce Product Report',
                            //     exportOptions: {
                            //         columns: ':visible'
                            //     }
                            // },
                        ],
                        ajax: {
                            url: apiAdminHost+'/getFlightList',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {};

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
                                    return meta.row + meta.settings._iDisplayStart + 1;
                                }
                            },

                                {
                                render: function (data, type, row, meta) {
                                    return row.airline_name;
                                    // return row.name+" "+row.variation_value;
                                }
                            },
                           /* {
                                render: function (data, type, row, meta) {
                                    return `<a href="${row.images[0]}" target="_blank"><img src="${row.images[0]}" class="img-responsive" width="80"></a>`;
                                }
                            },*/
                        
                            { data: 'IATA' },
                            { data: '3_cigit_code' },
                            { data: 'ICAO' },
                           
                            {
                                render: function (data, type, row, meta) {
                                   
                                        return `<img src="${row.logo}" style="width:50px,hight:50px">`;
                                }
                            },
                            {
                            render: function (data, type, row, meta) {
                                   
                                    return `<a id="onMultiImgClick" data-id="${row.id}">
                                                <i class="fa fa-pencil font-16"> Add Logo  </i>
                                            </a>&nbsp;
                                            `;
                                }
                            },
                            {
                              	render: function (data, type, row, meta) {
                                    if(row.status == 'Active')
                                    {
                                        var status = 'danger';
                                        var icon = 'trash';
                                    }
                                    else
                                    {
                                        var status = 'success';
                                        var icon = 'refresh'
                                    }

                                    return `-`;
                                  /*  return `<a id="onUpdateClick" data-id="${row.id}" title="Edit Product">
                                                <i class="fa fa-pencil font-16"></i>
                                            </a>&nbsp;
                                            <a id="onClickList" data-id="${row.id}" title="Variant List">
                                                <i class="fa fa-th-list font-16" aria-hidden="true"></i>
                                            </a>&nbsp;
                                            <a id="onDeleteClick" data-id="${row.id}" delete="Delete Product">
                                                <i class="fa fa-${icon} text-${status} font-16"></i>
                                            </a>`;*/
                              	}
                            }
                        ]
                    });

                    $('#flight-logo-datatable').on('click', '#onUpdateClick',function () {
                        that.$router.push({
                            name:'addecommerceproduct',
                            params:{
                                id: $(this).data('id'),
                            }
                        });
                    }); 
                    $('#flight-logo-datatable').on('click', '#onClickList',function () {
                        that.$router.push({
                            name:'variantlist',
                            params:{
                                id: $(this).data('id'),
                            }
                        });
                    }); 
                    $('#flight-logo-datatable').on('click', '#onMultiImgClick',function () {

                        // var data = table.row($(this).parents('tr')).data();
                        let id = $(this).data('id');
                        that.OnPopupClick(id);
                        /*that.$router.push({
                            name:'product/add-images',
                            params:{
                                id: $(this).data('id'),
                            }
                        });*/
                    });

                    $('#flight-logo-datatable').on('click', '#onDeleteClick',function () {
                       // that.deleteEcommerceProduct($(this).data('id'),that.table);
                    });

                },0);
            },
              OnPopupClick(id) {
                $("#ticket_id").val(id);
               // $("#remark").val('');
                $("#file").val('');
                this.fileName = '';
                $('#showPinModel').modal(); 
            },

            addFlightNameFun(){

             $('#showaddFlightNameModel').modal(); 
             

            },


            addFlightName(){
                Swal({
                    title: 'Are you sure ?',
                    text: "You won't be Add this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/addFlightName',{
                           'airline_name':this.airline_name,
                           'IATA':this.IATA
                        }).then(resp => {
                            if(resp.data.code === 200){
                                this.$toaster.success(resp.data.message);
                                this.table.ajax.reload();
                                $('#showaddFlightNameModel').modal('hide'); 
                            } else {
                                this.$toaster.error(resp.data.message);
                            }
                        }).catch(err => {
                            //console.log(err);
                        });
                    }
                });
            }
        }
    }
</script>	