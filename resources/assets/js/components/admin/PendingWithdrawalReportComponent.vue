<template>
    <!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Withdrawal Pending</h4>
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
                                                    <DatePicker :bootstrap-styling="true" v-model="frm_date" name="frm_date" :format="dateFormat" placeholder="From Date" id="frm_date"></DatePicker>
                                                    <span class="input-group-addon bg-custom b-0">
                                                        <i class="mdi mdi-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>To Date</label>
                                                <div class="input-group">
                                                    <DatePicker :bootstrap-styling="true" v-model="to_date" name="to_date" :format="dateFormat" placeholder="To Date" id="to_date"></DatePicker>
                                                    <span class="input-group-addon bg-custom b-0">
                                                        <i class="mdi mdi-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>User Id</label>
                                                <input type="text" id="user_id" name="user_id" placeholder="Enter User Id" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Country</label>
                                               <select
                                                  v-model="country"
                                                  name="country"
                                                  id="country"
                                                  class="form-control"
                                                >
                                                  <option disabled value="" selected>Select Country</option>
                                                  <option
                                                    v-for="co in countries"
                                                    v-bind:value="co.iso_code"
                                                  >{{ co.country }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="text-center">
                                                    <button type="button" class="btn btn-primary waves-effect waves-light" id="onSearchClick">Search</button>
                                                   <!--  <button type="button" class="btn btn-info waves-effect waves-light">Export To Excel</button> -->
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
                                <table id="product-report" class="table table-striped table-bordered dt-responsive full-width-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                           <!--  <th>
                                                <input type="checkbox" id="allCheck"/>Select All
                                            </th> -->
                                          <!--   <th>Action</th> -->
                                            <th>User Id</th>
                                            <th>Total Amount</th>
                                            <th>Deduction</th>
                                            <th>Net Amount</th>
                                            <th>To Address</th>
                                           <!--  <th>Paypal Address</th> -->
                                            <th>Country</th>
                                            <!-- <th>Perfect Money add</th> -->
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Payment Type</th>
                                            <!-- <th>Bank Account Number</th>
                                            <th>Bank Branch Name</th>
                                            <th>A/C Holder Name</th>
                                            <th>Pan Card Number</th>
                                            <th>Bank Name</th>
                                            <th>IFSC Code</th> -->

                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <!-- <div class="row" style="padding-bottom: 15px;">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-2">
                                            <h4>Remark:</h4>
                                        </div>
                                        <div>
                                            <div class="col-md-4">
                                                <textarea class="form-control rounded-0" id="remark" placeholder="Enter remark here" rows="3" v-model="remark"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-info waves-effect waves-light" @click="onMakePaymentClick">Make Payment</button>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div><!-- end row -->
            </div><!-- container -->
        </div><!-- Page content Wrapper -->
    </div><!-- content -->
</template>


<script>
    import moment from 'moment';
    import DatePicker from 'vuejs-datepicker';
    import { apiAdminHost } from'./../../admin-config/config';
    import Swal from 'sweetalert2';

    export default {
        data() {
            return {
                frm_date: '',
                to_date: '',
                user_id: '',
                arrayForSelectedCheckbox:[],
                remark:'',
                countries:[],
                country:'',
            }
        },
        components: {
            DatePicker
        }, 
        mounted() {
            this.productReport();
            this.getCountry();
        },
        methods: {
            getCountry() {
              axios
                .get("../country", {})
                .then(response => {
                  this.countries = response.data.data;
                })
                .catch(error => {});
            },
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
                 productReport(){
                let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
              
                setTimeout(function(){
                     that.table = $('#product-report').DataTable({
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
                       /* ajax: {
                            url: apiAdminHost+'/getwithdrwalconfirmed',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;
                                let params = {
                                    frm_date : $('#frm_date').val(),
                                    to_date  : $('#to_date').val(),
                                    id: $('#to_user_id').val(),
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
                        },*/
                          ajax: {
                            url: apiAdminHost+'/getwithdrwalpending',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;
                                let params = {
                                    frm_date: $('#frm_date').val(),
                                    to_date: $('#to_date').val(),
                                    id: $('#user_id').val(),
                                    country: $('#country').val(),
                                };
                                Object.assign(d, params);
                                return d;
                            },
                            headers: {
                              'Authorization': 'Bearer ' + token
                            },
                            dataSrc: function (json) {
                                if (json.code === 200) {

                                    i = parseFloat(json.data.start) + 1;
                                    json['recordsFiltered'] = json.data.recordsFiltered;
                                    json['recordsTotal'] = json.data.recordsTotal;
                                    return json.data.records;
                                } else {
                                    json['recordsFiltered'] = 0;
                                    json['recordsTotal'] = 0;
                                    return json;
                                }
                            }
                        },
                       columns: [
                            {
                                render: function (data, type, row, meta) {
                                   // return i++;
                                   return meta.row + meta.settings._iDisplayStart + 1;
                                }
                            },
                            // {
                            //     render: function (data, type, row, meta) {
                            //         return `<input data-id="${row.id}" type="checkbox" class="myCheck" value="${row.id}">`;
                            //     }
                            // },
                          /*  {
                                render: function (data, type, row, meta) {
                                    return `<textarea class="form-control remark-${row.sr_no}" placeholder="Enter remark here"></textarea>
                                            <div class="clearfix"></div>
                                            <button type="button" class="btn btn-info waves-effect waves-light paid" data-id="${row.sr_no}">Paid Payment</button>`;
                                }
                            },*/
                            {
                                render: function (data, type, row, meta) {
                                    return `<span>${row.user_id}</span><span>(${row.fullname})</span>`;
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    {
                                        var net_amount = row.amount + row.deduction;
                                        return net_amount;
                                    }
                                } 
                            },
                            { data: 'deduction' },
                            { data: 'amount' },
                            { data: 'btc_address' },
                           /* { data: 'paypal_address' },*/
                             { data: 'country' },
                            /*{ data: 'perfect_money_address' },*/
                            { data: 'status' },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.entry_time)).format('YYYY-MM-DD');
                                    }
                                }
                            },
                            { data: 'network_type' },
                          /*  { data: 'account_no' },
                            { data: 'branch_name' },
                            { data: 'holder_name' },
                            { data: 'pan_no' },
                            { data: 'bank_name' },
                            { data: 'ifsc_code' },*/
                        ]
                    });
                    $('#product-report').on('click','#confirmWithdrawal', function (){
                        //$('#confirm-withdrawal-model').modal();      
                        if (table.row($(this).parents('tr')).data() !== undefined) {
                            var data = table.row($(this).parents('tr')).data();
                            that.OnShowPinxClick(data);
                        } else {
                            var data = table.row($(this)).data();
                            console.log(data);
                            that.OnShowPinxClick(data);
                        }

                    });                    
                    $('#onSearchClick').click(function () {
                        that.table.ajax.reload();
                    });
                    $('#onResetClick').click(function () {  
                        $('#searchForm').trigger("reset");
                        that.table.ajax.reload();
                    });
                },0);                
            },
            onMakePaymentClick(sr_no, remark) {
                Swal({
                    title: 'Are you sure ?',
                    text: "You won't be make this payment!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/send/withdrwalrequest',{
                            //srno: this.arrayForSelectedCheckbox,
                            //remark: this.remark
                            srno: sr_no,
                            remark: remark
                        }).then(resp => {
                            if(resp.data.code === 200){
                                this.table.ajax.reload();
                                this.$toaster.success(resp.data.message);
                            } else {
                                this.$toaster.error(resp.data.message);
                            }
                        }).catch(err => {
                            this.$toaster.error(err);
                        })
                    }
                });
            }
        }
    }
</script>