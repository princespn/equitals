<template>
    <!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Manage Product Categories</h4>
            </div>
        </div>

        <div class="page-content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-3">
                        <div class="panel panel-primary">
                            <div class="panel-body add-new">
                                <router-link class="btn btn-primary waves-effect waves-light" :to="{name: 'add-category'}">
                                    <i class="fa fa-plus"></i>Add Category
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <table id="category-report" class="table table-striped table-bordered dt-responsive">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Name</th>
                                           <!--  <th>Image</th> -->
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Name</th>
                                           <!--  <th>Image</th> -->
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
            </div><!-- container -->
        </div><!-- Page content Wrapper -->
    </div><!-- content -->
</template>

<script>
    import { apiAdminHost } from'./../../admin-config/config';
    import moment from 'moment';
    import Swal from 'sweetalert2';
    
    export default {
        
        data() {
            return {
                user_data  : [],
                products   : [],
                length : 10,
                start  : 0,
                id:'',
            }
        },
        mounted() {
            
            this.manageNews();
        },
        methods: {
            manageNews(){
                let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    that.table = $('#category-report').DataTable({
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
                            url: apiAdminHost+'/get/product-category',
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
                            { data: 'name' },
                          /*  {
                                render: function (data, type, row, meta) {
                                    return `<a href="${row.image}" target="_blank"><img src="${row.image}" class="img-responsive" width="80"></a>`;
                                }
                            },*/
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
                                    }
                                    else
                                    {
                                        var status = 'success';
                                        var icon = 'refresh'
                                    }
                                    return `<a id="onUpdateClick" data-id="${row.id}">
                                                <i class="fa fa-pencil font-16"></i>
                                            </a>&nbsp;
                                            <a id="onDeleteClick" data-id="${row.id}">
                                                <i class="fa fa-${icon} text-${status} font-16"></i>
                                            </a>`;
                                }
                            }
                        ]
                    });

                    $('#category-report').on('click', '#onUpdateClick',function () {
                        that.$router.push({
                            name:'add-category',
                            params:{
                                id: $(this).data('id'),
                            }
                        });
                    });

                    $('#category-report').on('click', '#onDeleteClick',function () {
                        that.deleteEcommerceProduct($(this).data('id'),that.table);
                    });

                },0);
            },
            
            deleteEcommerceProduct(id,table){
                Swal({
                    title: 'Are you sure ?',
                    text: "You won't be delete this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/delete/product-category',{
                            id: id
                        }).then(resp => {
                            if(resp.data.code === 200){
                                this.$toaster.success(resp.data.message);
                                this.table.ajax.reload();
                            } else {
                                this.$toaster.error(resp.data.message);
                            }
                        }).catch(err => {
                            //console.log(err);
                        });
                    }
                });
            },
            

        }
    }
</script>   