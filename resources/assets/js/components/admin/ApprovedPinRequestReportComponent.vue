<template>
    <!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Approved E-Pin Request</h4>
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
                                        <div class="col-md-2"></div>
                                          <div class="col-md-2">
                                            <div class="form-group">
                                            <label>From Date</label>
                                            <div>
                                                <div class="input-group">
                                                    <DatePicker :bootstrap-styling="true" name="frm_date" :format="dateFormat" placeholder="From Date" id="frm_date"></DatePicker>
                                                    <!-- <input type="text" class="form-control" placeholder="From Date" id="datepicker"> -->
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
                                                    <!-- <input type="text" class="form-control" placeholder="To Date" id="datepicker-autoclose"> -->
                                                    <span class="input-group-addon bg-custom b-0">
                                                        <i class="mdi mdi-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                           <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Product</label>
                                                <select class="form-control" id="product_id" v-model="filters.product_id">
                                                    <option selected value="">Select</option>
                                                    <option :value="product.id" v-for="product in arrProducts">{{product.name}}</option>
                                                </select>
                                            </div>
                                        </div>
                                     <!--    <div class="col-md-2">
                                            <div class="form-group">
                                                <label>User Id</label>
                                                <input class="form-control" required="" placeholder="Enter Cost" type="text" v-model="filters.cost" id="user_id">
                                            </div>
                                        </div> -->
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>E-Pin</label>
                                                <input class="form-control" required="" placeholder="Enter BV" type="text" v-model="filters.b_value" id="pin">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="text-center">
                                                    <button type="button" class="btn btn-primary waves-effect waves-light" id="onSearchClick" @click="productReport">Search</button>
                                                    <button type="button" class="btn btn-info waves-effect waves-light">Export To Excel</button>
                                                    <button type="button" class="btn btn-dark waves-effect waves-light mt-4" id="onResetClick" @click="reset">Reset</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- panel-body -->
                            </div><!-- panel -->
                        </div><!-- col -->
                    </div>dialogImage
                </form>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <table id="product-report" class="table table-striped table-bordered dt-responsive">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>E-Pin Request Id</th>
                                            <th>User Id</th>
                                            <th>Full Name</th>
                                            <th>Product Details</th>
                                            <th>Approved Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>E-Pin Request Id</th>
                                            <th>User Id</th>
                                            <th>Full Name</th>
                                            <th>Product Details</th>
                                            <th>Approved Date</th>
                                            <th>Status</th>
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
               <!--  MOdel for image-->
       <div id="myModal" class="modal2">
          <span class="close2" @click="closeDialog();">×</span>
          <img class="modal-content2" id="img01" v-bind:src="dialogImage">
          <div id="caption"></div>
        </div>


        <!-- Product Details Modal -->
<div class="modal fade" id="details" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Details</h4>
      </div>
      <div class="modal-body">
        <table id="" class="table table-striped table-bordered dt-responsive">
          <thead>
            <tr>
              <th>Amount Deposited Details</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <ul>
                  <li>Deposit Type: {{moreDetailData.deposite_type}}</li>
                  <li>
                   <!-- <span>Date: {{moreDetailData.deposite_date}}</span> -->
                    <span v-if="moreDetailData.deposite_date">Date: {{moreDetailData.deposite_date}}</span>
                    <span v-if="!moreDetailData.deposite_date">Date: {{moreDetailData.entry_time}}</span>
                  </li>
                  <li>Amount: {{moreDetailData.amount_deposited}}</li>
                </ul>
              </td>
              <td>{{moreDetailData.description}}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer hidden">
        <button type="button" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

    <!-- for show -->
  <!-- Product Details Modal -->
            <div class="modal fade" id="product-details" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Product Details</h4>
                        </div>
                        <div class="modal-body">
                            <table class="table table-striped table-bordered dt-responsive">
                                <thead>
                                    <tr>
                                        <th class="ForSrNoWidth">Sr.No</th>
                                        <th>Name </th>
                                        <th>Price </th>
                                        <th>Qty.</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for = "(prodItem,index) in arrayProductDetail">
                                        <td>{{index + 1}}</td>
                                        <td>{{prodItem.name}}</td>
                                        <td>{{prodItem.product_price}}</td>
                                        <td>{{prodItem.request_quantity}}</td>
                                        <td>{{prodItem.total_price}}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3">Total</th>                                        
                                        <th>{{totalQnty}}</th>
                                        <th>{{totalAmoubnt}}</th>
                                    </tr>
                                </tfoot>
                                <!-- <tr style="text-align:center">
                                    <td colspan="13" class="no-data-available text-center">No data available</td>
                                </tr> -->
                            </table>
                        </div>
                        <div class="modal-footer hidden">
                            <button type="button" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

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
                moreDetailData:[],
                dialogImage:'',
                arrayProductDetail:[],
                 totalAmoubnt:0,
                totalQnty:0,
            }
        },
        mounted() {
            this.productReport();
            this.getProducts();
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
                    const table = $('#product-report').DataTable({
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
                            url: apiAdminHost+'/report/pin/request',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    status: 'Approve',
                                    frm_date: $('#frm_date').val(),
                                    to_date: $('#to_date').val(),
                                    //user_id  : $('#user_id').val(),
                                    pin: $('#pin').val(),
                                    product_id: $('#product_id').val(),
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
                            { data: 'id' },
                            { data: 'user_id' },
                            { data: 'fullname' },
                            {
                                render: function (data, type, row, meta) {
                                    return `<label class="text-success waves-effect" id="onShowDetailsClick">Show</label>`;
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.approve_date === null || row.approve_date === undefined || row.approve_date === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.approve_date)).format('YYYY-MM-DD hh:mm:ss');
                                    }
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.verify_date === null || row.verify_date === undefined || row.verify_date === '') {
                                        var verify_date = `-`;
                                    } else {
                                        var verify_date = moment(String(row.verify_date)).format('YYYY-MM-DD hh:mm:ss');
                                    }
                                    return `<ul class="list-unstyled">
                                                <li>
                                                    <label class="text-info" title="Verify Date:${verify_date}, Verify Remark: ${row.verify_remark}">${row.status}
                                                    </label>
                                                </li>
                                            </ul>`;
                                }
                            },
                            {
                                render:function (data, type, row, meta) {
                                    return `<ul class="list-unstyled">
                                                <li class="">
                                                    <label type="button" class="text-success waves-effect" id="showMoreData">Details</label>
                                                </li>
                                                <li>
                                                    <label type="button" class="text-info waves-effect" id="showImage">Receipt</label>
                                                </li>
                                            </ul>`;
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
                    $('#product-report tbody').on('click', '#showImage', function () {
                    
                  if (table.row($(this).parents('tr')).data() !== undefined) {
                    var data = table.row($(this).parents('tr')).data();
                    that.showImg(data);
                  } else {
                    var data = table.row($(this)).data();
                    that.showImg(data);
                  }
                });
                     $('#product-report tbody').on('click', '#showMoreData', function () {
                    
                  if (table.row($(this).parents('tr')).data() !== undefined) {
                    var data = table.row($(this).parents('tr')).data();
                    that.showMoreData(data);
                  } else {
                    var data = table.row($(this)).data();
                    that.showMoreData(data);
                  }
                });


                    /*$('#onShowDetailsClick').click(function(){*/
                    $('#product-report tbody').on('click', '#onShowDetailsClick', function () 
                    {   
                     
                       // $('#product-details').modal('show');
                        if(table.row($(this).parents('tr')).data() !== undefined) {
                            var data = table.row($(this).parents('tr')).data();
                            that.onShowDetailsClick(data);
                        } else {
                            var data = table.row($(this)).data();
                            that.onShowDetailsClick(data);
                        }
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
            },
            showImg(data){
                 if (!data.attachment) {

                        var img = 'admin_assets/images/no_image_available.png';
                    } else {
                         img = data.attachment;
                    }
                    var modal = document.getElementById('myModal');
                    modal.style.display = 'block';
                    this.dialogImage = img;  

            },
            closeDialog() {
                    var modal = document.getElementById('myModal');
                    modal.style.display = 'none';
                },
            showMoreData(data) {
                 $('#details').modal();               
                 this.moreDetailData = data;
            },
             onShowDetailsClick(data){

                var i;
                $('#product-details').modal();               
                this.arrayProductDetail = data.user_cart;
                //console.log(this.arrayProductDetail);
                console.log('hjghjgh');
                 for (let data1 of data.user_cart) {
                    // tslint:disable-next-line:radix
                    console.log(data1);
                    this.totalAmoubnt = this.totalAmoubnt + parseInt(data1.total_price);
                    // tslint:disable-next-line:radix
                    this.totalQnty = this.totalQnty + parseInt(data1.request_quantity);
                  }


                },
        }
    }
</script>