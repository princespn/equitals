<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
		    <div class="page-header-title">
		        <h4 class="page-title">Manage Gallery</h4>
		    </div>
		</div>

		<div class="page-content-wrapper">
		    <div class="container">
		        <form>
		            <div class="row">
                    <div class="col-md-12 col-sm-3">
                        <div class="panel panel-primary">
                            <div class="panel-body add-new">
                                <router-link class="btn btn-primary waves-effect waves-light" :to="{name: 'addgallery'}">
                                    <i class="fa fa-plus"></i>Add Gallery Item
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
		        </form>
		        <div class="row">
		            <div class="col-md-12">
		                <div class="panel panel-primary">
		                    <div class="panel-body">
		                        <table id="team-view-report" class="table table-striped table-bordered dt-responsive">
		                            <thead>
		                                <tr>
		                                    <th>Sr.No</th>
		                                    <th>Title</th>
		                                    <!-- <th>Description</th> -->
		                                    <th>Date</th>
		                                    <th>Action</th>
		                                    
		                                    
		                                   
		                                   <!--  <th>Action</th> -->

		                                </tr>
		                            </thead>
		                            <!-- <tbody>
		                                <tr>team-view
		                                    <td>1</td>
		                                    <td>System Architect</td>
		                                    <td>1</td>
		                                    <td>5000.00</td>
		                                    <td>10</td>
		                                    <td>
		                                        <label class="text-info"> Active</label>
		                                    </td>

		                                </tr>
		                                <tr>
		                                    <td>2</td>
		                                    <td>Accountant</td>
		                                    <td>1</td>
		                                    <td>5000.00</td>
		                                    <td>10</td>
		                                    <td>
		                                        <label class="text-info"> Active</label>
		                                    </td>
		                                </tr>
		                            </tbody> -->
		                            <tfoot>
		                                <tr>
		                                     <th>Sr.No</th>
		                                    <th>Title</th>
		                                    <!-- <th>Description</th> -->
		                                    <th>Date</th>
		                                    <th>Action</th>
		                                    
		                                </tr>
		                            </tfoot>
		                        </table>
		                    </div>
		                </div>
		            </div>
		        </div><!-- end row -->
		    </div><!-- container -->
		</div><!-- Page content Wrapper -->

		<div aria-hidden="true" aria-labelledby="myModalLabel" class="modal fade" id="add-images-model" role="dialog" tabindex="-1" style="display: none;">
            <div class="modal-dialog">
                <div  class="modal-content">
                    <div  class="modal-header">
                        <button  aria-hidden="true" class="close" data-dismiss="modal" type="button">×</button>
                        <h4  class="modal-title" id="myModalLabel">Add Images In Gallery</h4>
                    </div>
                    <div  class="modal-body">
                        <div  class="row">
                            <div  class="col-md-12">
                                <p>Upload Image</p>
                                <div class="bootstrap-filestyle input-group" style="width: 100%">
                                    <input type="text" name="filename" id="filename" v-model="filename" class="form-control" placeholder="Select Image" disabled="">
                                    <span class="group-span-filestyle input-group-btn" tabindex="0">
                                        <label for="filestyle-0" class="choose_file">
                                            <input type="file" name="file" id="file" ref="file" style="display:none" @change="getImageName">
                                            <img :src="adminAssetsURL+'/images/choose_file.png'" id="upfile1" style="cursor:pointer" @click="selectImage()" />
                                        </label>
                                    </span>
                                </div>
                                <div class="text-center" style="margin-top: 12px;">
                                    <button type="button" @click="onAddImageClick()" class="btn btn-primary waves-effect waves-light">Add Image</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered" style="margin-top: 16px;">
                            <thead>
                                <tr>
                                    <th class="ForSrNoWidth">Sr.No</th>
                                    <th>Image </th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data, i) in imageGallryData">
                                    <td>{{i+1}}</td>
                                    <td>
                                        <img :src="data.attachment" style="height: 104px;" @click="showImage(data.attachment)">
                                    </td>
                                    <td>
                                        <label class="text-danger ">
                                            <a @click="onDeleteImageClick(data)">
                                                <i class="fa fa-trash text-danger font-16"></i>
                                            </a>
                                        </label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div  class="modal-footer hidden">
                        <button class="btn btn-dark waves-effect" data-dismiss="modal" type="button">Cancel</button>
                    </div>
                </div>
            </div>  
        </div>
	</div><!-- content -->
</template>

<script>
    import { apiAdminHost } from'./../../admin-config/config';
    import { adminAssets } from'./../../admin-config/config';  
    import moment from 'moment';
    import Swal from 'sweetalert2';
    export default {
    	
        data() {
            return {
                user_data  : [],
                products   : [],
                length : 10,
                start  : 0,
                adminAssetsURL:adminAssets,
                imageGallryData:[],
                gid:'',
                filename:'',
            }
        },
        mounted() {
        	
            this.manageGallery();
        },
        methods: {
        	selectImage(){
                $("#file").trigger('click');
            },

            getImageName(e){
                var filename = e.target.files[0].name;
                $('#filename').val(filename);              
            },
            manageGallery(){
            	let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    that.table = $('#team-view-report').DataTable({
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
                            url: apiAdminHost+'/show/gallery',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    provide : 1
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
                          
                            { data: 'name' },
                            { data: 'created_at' },                            
                            {
                              render: function (data, type, row, meta) {
                               return `<a id="onAddImageButtonClick" data-id="${row.id}">
                                                <i class="fa fa-plus font-16"></i>
                                            </a>&nbsp;
                                            <a id="onUpdateClick" data-id="${row.id}">
                                                <i class="fa fa-pencil font-16"></i>
                                            </a>&nbsp;
                                            <a id="onDeleteClick" data-id="${row.id}">
                                                <i class="fa fa-trash text-danger font-16"></i>
                                            </a>`;
                              }
                            }

							
                            /*{ data: 'cost' },
                            { data: 'bvalue' },*/
                            /*{
                              render: function (data, type, row, meta) {
                                return '<label class="text-info">'+row.status+'</label>';
                              }
                            }*/
                        ]
                    });
                      $('#team-view-report').on('click', '#onUpdateClick',function () {
                        that.$router.push({
                            name:'addgallery',
                            params:{
                                id: $(this).data('id'),
                            }
                        });
                    });

                    $('#team-view-report').on('click', '#onDeleteClick',function () {
                        that.deleteGallery($(this).data('id'));
                    });

                    $('#team-view-report').on('click', '#onAddImageButtonClick',function(){
                        that.onAddImageButtonClick($(this).data('id'));
                    });
                },0);
            },
             deleteGallery(id){
                Swal({
                    title: 'Are you sure ?',
                    text: "You won't be delete this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/delete/gallery',{
                            id: id
                        }).then(resp => {
                            if(resp.data.code === 200){
                                this.$toaster.success(resp.data.message);
                                this.manageGallery();
                                this.table.ajax.reload();
                            } else {
                                this.$toaster.error(resp.data.message);
                            }
                        }).catch(err => {
                            console.log(err);
                        });
                    }
                });
            },
            onAddImageButtonClick(gid){
                axios.post('/show/gallery/image',{
                    gid: gid
                }).then(resp => {
                    this.imageGallryData = resp.data.data;
                    this.gid = gid;
                    $('#add-images-model').modal();
                }).catch(err => {
                    console.log(err);
                });
            },
            onDeleteImageClick(data){
                Swal({
                    title: 'Are you sure ?',
                    text: "You won't be delete image!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/delete/gallery/image',{
                            id: data.id
                        }).then(resp => {
                            if(resp.data.code === 200) {
                                this.$toaster.success(resp.data.message);
                                this.onAddImageButtonClick(data.gid);
                                //this.manageGallery();

                            } else {
                                this.$toaster.error(resp.data.message);
                            }
                        }).catch(err => {
                            console.log(err);
                        });
                    }
                });
            },
            onAddImageClick(){
                let formData = new FormData();
                if(typeof this.$refs.file.files[0]!== 'undefined'){
                    formData.append('attachment', this.$refs.file.files[0]);
                    formData.append('gid', this.gid);
                
                    axios.post('/store/gallery/image',formData)
                    .then(resp => {
                        if(resp.data.code === 200) {
                            this.$toaster.success(resp.data.message);
                            this.onAddImageButtonClick(this.gid);
                            $('#addImagesFrom').trigger('reset');
                        } else {
                            this.$toaster.error(resp.data.message);
                        }
                    }).catch(err => {
                        console.log(err);
                    });
                } else {
                    this.$toaster.error('Please select image');
                }
            }
           
        }
    }
</script>	