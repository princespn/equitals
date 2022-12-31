<template>
	<!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 v-if="!product.id" class="page-title">Add Product</h4>
                <h4 v-if="product.id" class="page-title">Update Product</h4>
            </div>
        </div>

        <div class="page-content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form class="ecommerce-product-form" v-on:submit.prevent="saveUpdateProduct">
                                            <input type="hidden"  id="pid" v-model="product.id">
                                            
                                            
                                         
                                            <div class="form-group">
                                                <label>Upload Images</label>
                                                <input class="filestyle" data-buttonname="btn-default" id="filestyle-0" name="attachment" type="file" ref="file" accept="image/*" @change="uploadImage($event)" >
                                            </div>
                                            <!-- multiple -->
                                         
                                            <!-- <label>Description</label> -->
                                            <!-- <vue-editor v-model="product.description"></vue-editor> -->
                                            <!-- <div class="summernote"></div> -->
                                            <div class="text-center">
                                                <button type="submit" name="submit" class="btn btn-primary waves-effect waves-light" :disabled="errors.any() || !isCompleted || disableBtn" id="product-btn">Add Images</button>
                                             
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End row -->
            </div>
            <!-- container -->
        </div>
        <!-- Page content Wrapper -->


                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <table id="showImgstable" class="table table-striped table-bordered dt-responsive">
                                    <thead>
                                        <tr>
                                             <th>Sr.No</th>
                                            <th>Product Name</th>
                                            <th>Image</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Sr.No</th>
                                             <th>Product Name</th>
                                            <th>Image</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div><!-- end row -->
    </div>
    <!-- content -->
</template>

<script>
    import { apiAdminHost } from'./../../admin-config/config';
    import moment from 'moment';

    export default {
        data() {
            return {
                arrProducts:[],
                product:{
                    id:'',
                    tag:'',
                    name:'',
                    cost:0,
                    image : '',
                    description : '',
                    category_id:'',
                    brand_name:'',
                    manufacturer:'',
                    mpn:'',
                    variation:'Size',
                    variation_values:[{'id':'','value':'','price':''}]
                },
                categories:[],
                files:[],
                disableBtn:false,
                temp:'',
            }
        },
        mounted() {
            if(this.$route.params.id){
                this.getProductById(this.$route.params.id);
                this.product.id=this.$route.params.id;
            }
            this.getCategories();
            this.showImgs();
        },
        computed: {
            isCompleted(){
                return this.product.id;
            }
        },
        methods: {
            addInput()
            {
                this.product.variation_values.push({'id':'','value':'','price':''});
            },
            removeInput(index)
            {
                this.product.variation_values.splice(index,1);
            },
         uploadImage(e){
         
             this.temp=event.target.files[0]
           //  console.log(this.temp);
         },

            saveUpdateProduct(event){
                this.disableBtn = true;
                 if(this.product.id != ''){
                    let formData = new FormData();
                    formData.append('id', this.product.id);
                    //if(typeof this.$refs.file.files[0]!== 'undefined'){
                        formData.append('file', this.temp);
                  
                    axios.post('add/multiple-img',formData)
                    .then(resp => {
                        this.disableBtn = false;
                        if(resp.data.code === 200){
                            this.$toaster.success(resp.data.message);
                            //this.$router.push({ name:'ecommerceproduct'});

                            $("#filestyle-0").val(null);

                            event.target.reset();
                            $('#showImgstable').DataTable().ajax.reload();
                        } else {
                            this.$toaster.error(resp.data.message);
                        }
                    }).catch(err => {
                        //console.log(err);
                    })
              }
                
            },
             showImgs(){
                let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    that.table = $('#showImgstable').DataTable({
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        responsive: true,
                        stateSave: false,
                        ordering: false,
                        dom: 'Bfrtip',
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
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
                            url: apiAdminHost+'/getEcommerceProductsImg',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;
                                     // alert($('#pid').val())
                                let params = {
                                   pid:$('#pid').val(),
                                  // aa:123,

                                };

                                Object.assign(d, params);
                                return d;
                            },
                            headers: {
                              'Authorization': 'Bearer ' + token
                            },
                            dataSrc: function (json) {
                                if (json.code === 200) {
                                    that.arrGetHelp = json.data.record;
                                    json['draw'] = json.data.draw;
                                    json['recordsFiltered'] = json.data.filterRecord;
                                    json['recordsTotal'] = json.data.totalRecord;
                                    return json.data.record;
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
                                    return `${row.name}`;
                                }
                            },  
                            {
                                render: function (data, type, row, meta) {
                                    return `<a href="${row.img_url}" target="_blank"><img src="${row.img_url}" class="img-responsive" width="80"></a>`;
                                }
                            },
                            
                           
                            {
                                render: function (data, type, row, meta) {
                                    if(row.status == 'Active')
                                    {
                                        var status = 'success';
                                    }
                                    else
                                    {
                                        var status = 'danger';
                                    }
                                    return `<label class="text-${status}">${row.status}</label>`;
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
                            },
                            {
                                render: function (data, type, row, meta) {
                                    if(row.status == 'Active')
                                    {
                                        var status = 'danger';
                                        var icon = 'trash';
                                        return `<a id="inactiveBtn" data-id="${row.id}">
                                                    <i class="fa fa-${icon} text-${status} font-16"> </i>
                                                </a>`;
                                    }
                                    else
                                    {
                                        var status = 'success';
                                        var icon = 'refresh'
                                        return `<a id="activeBtn" data-id="${row.id}">
                                                <i class="fa fa-${icon} text-${status} font-16"> </i>
                                            </a>`;
                                    }
                                    
                                }
                            }
                        ]
                    });

                    $('#showImgstable').on('click', '#inactiveBtn',function () {
                    /*    that.$router.push({
                            name:'addecommerceproduct',
                            params:{
                                pid: $(this).data('id'),
                            }
                        });*/
                        that.deleteEcommerceProduct($(this).data('id'),'Inactive');
                          that.table.ajax.reload();
                    }); 
                    $('#showImgstable').on('click', '#activeBtn',function () {
                      /*  that.$router.push({
                            name:'product/add-images',
                            params:{
                                pid: $(this).data('pid'),
                            }
                        });*/
                         that.deleteEcommerceProduct($(this).data('id'),'Active');
                         //that.table.ajax.reload();
                    });

                    $('#showImgstable').on('click', '#onDeleteClick',function () {
                        that.deleteEcommerceProduct($(this).data('id'),'Inactive');
                         that.table.ajax.reload();
                    });

                },0);
            },
               getProductById(id){
                axios.post('/edit/ecommerce-product',{
                    id: id
                }).then(resp => {
                    if(resp.data.code === 200){
                        this.product = resp.data.data;
                    }
                }).catch(err => {
                    //console.log(err);
                })
            },
             getCategories(){
                axios.post('/get/product-category',{
                    status : 'Active'
                })
                .then(resp => {
                    if(resp.data.code === 200){
                        this.categories = resp.data.data;
                    }
                }).catch(err => {
                    //console.log(err);
                })
            }, 

            deleteEcommerceProduct(id,status){
                // alert(status);
                axios.post('/productImgStatus',{
                    status : status,
                    id : id
                })
                .then(resp => {
                    if(resp.data.code === 200){
                       // this.categories = resp.data.data;
                        $('#showImgstable').DataTable().ajax.reload();

                    }
                }).catch(err => {
                    //console.log(err);
                })
            },
            
        }
    }
</script>	