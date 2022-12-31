<template>
    <div class="content-body">
   <div class="container-fluid">
      <div class="row page-titles">
         <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Manage Funds</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Pending Fund Transactions</a></li>
         </ol>
      </div>
      <!-- row -->
      <div class="row">
         <div class="col-12">
            <div class="card lp">
               <div class="card-header">
                  <h4 class="card-title">Pending Fund Transactions</h4>
               </div>
               <div class="card-body">
                  <form action="#" id="searchForm">
                     <div class="row">
                        <div class="col-12 col-md-10">
                           <div class="row">
                              <div class="col-6 col-md-3">
                                 <label>From Date</label> 
                                 <div>
                                    <div data-date-format="dd M, yyyy" data-date-autoclose="true" data-provide="datepicker" class="input-daterange input-group">
                                    <input type="date" name="start" placeholder="From" id="from-date" class="form-control"></div>
                                 </div>
                              </div>
                              <div class="col-6 col-md-3">                              
                                 <label>To Date</label> 
                                  <div>
                                    <div data-date-format="dd M, yyyy" data-date-autoclose="true" data-provide="datepicker" class="input-daterange input-group">
                                    <input type="date" name="end" placeholder="To" id="to-date" class="form-control"></div>
                                 </div>
                              </div>

                              <div class="col-12 col-md-3">
                                 <label for="deposit-id">Deposit Id</label>
                                  <input placeholder="Enter Deposit Id" id="deposit-id" type="text" class="form-control">
                              </div>

                              <div class="col-12 col-md-3">
                                
                                   <label for="status">Select Status</label>
                                    <select id="status" class="form-control">
                                      <option value>All</option>
                                      <option value="0">Pending</option>
                                      <option value="2">Expired</option>
                                    </select>
                              </div>

                              <div class="col-12 col-md-12 pt-2 justify-content-center">
                                 <div class="button-items button_submit">
                                    <button type="button" id="onSearchClick" class="btn btn-success waves-effect waves-light">Search Now</button> 
                                    <button type="button" id="onResetClick" class="btn btn-warning waves-effect waves-light">Reset Now</button></div>
                              </div>
                           </div>
                        </div>
                        <div class="col-12 col-md-2">
                           <img src="public/user_files/assets/images/search.png" class="img-fluid s-img">
                        </div>
                     </div>
                  </form>
               </div>
               <hr>
               <div class="card-body">
                  <div class="table-responsive">
                     <table id="pending-deposite" class="display" style="min-width: 845px">
                        <thead>
                           <tr>
                             <th>Sr No</th>
                            <th>Date</th>
                            <th>Deposit Id</th>
                            <!--  <th>Package</th> -->
                            
                            <th>Amount</th>
                            <th>Payment Mode</th>
                            <th style="word-break: break-word;">Address</th>
                            <th>Action</th>  
                            <th>Status</th>  
                           </tr>
                        </thead>
                       
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
    import moment from 'moment';
    import { apiUserHost } from'../../user-config/config';
    import Breadcrum from './BreadcrumComponent.vue';
    export default {  
        components: {
            Breadcrum
        }, 
        data(){
            return{
                //total_amount:0,
                INR:'',
                type:'',
            }
        },
        mounted(){
            this.getConfirmedDeposite();
           // this.getProjectSetting();
            //this.getPackages();
            $(function() {
                var start = moment('2015-01-01');
                var end = moment();
                function cb(start, end) {
                    if(start.format('DD/MM/YYYY') == '01/01/2015') {
                        $('.range-text').html('All');
                        $('.from, .to').val('');
                        $('#daterangefocus').focus(); 
                    }else {
                        $('#reportrange span').html(start.format('D-MM-YYYY') + ' - ' + end.format('D-MM-YYYY'));
                        $('.from').val(start.format('D-MM-YYYY'));
                        $('.to').val(end.format('D-MM-YYYY'));
                        $('#daterangefocus').focus(); 
                    }
                }
                $('#reportrange').daterangepicker({
                    opens: 'left',
                    startDate: start,
                    endDate: end,
                    ranges: {
                        'All': [moment('2015-01-01'), moment()],
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    }
                }, cb);
                cb(start, end);
            });
        },
        methods:{
            reloadTable(){
                 // alert();
                 $('#pending-deposite').DataTable().ajax.reload();
                 $('#daterangefocus').blur(); 
                 
             },
             getConfirmedDeposite(){
                let i = 0;
                let that = this;
                let token = localStorage.getItem('user-token');
                setTimeout(function(){

                    that.table = $('#pending-deposite').DataTable({
                        responsive: true,
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        stateSave: false,
                        ordering: false,
                        dom: 'lrtip',
                         // dom: 'Blfrtip',

                        // buttons: [
                        //     // 'copyHtml5',
                        //     'excelHtml5',
                        //    // 'csvHtml5',
                        //    // 'pdfHtml5',
                        //  //   'pageLength',
                        // ],
                        ajax: {
                            url: apiUserHost+'pending-add-deposit',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    deposit_id: $("#deposit-id").val(),
                                    status: $("#status").val(),
                                    frm_date: $("#from-date").val(),
                                    to_date: $("#to-date").val(),
                                };
                                Object.assign(d, params);
                                return d;
                            },
                            headers: {
                              'Authorization': 'Bearer ' + token
                          },
                          dataSrc: function (json) {
                            if (json.code === 200) {
                                let total_amount = 0;
                                if(json.data.records.length != 0){
                                    for (let j = 0; j < json.data.records.length; j++) {
                                        total_amount = total_amount + parseInt(json.data.records[j].price_in_usd);          
                                        $('#total_amount').text("$"+total_amount);
                                    }
                                }else{
                                    $('#total_amount').text("$0");
                                }
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
                        "defaultContent": "", 
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        "defaultContent": "", 
                        render: function (data, type, row, meta) {
                            if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                              return `-`;
                          } else {
                            return moment(String(row.entry_time)).format('YYYY/MM/DD');
                        }
                    }
                },
                { data: 'invoice_id' },
                            //  { render: function (data, type, row, meta) {
                            //        if(that.type == "INR")
                            //          {
                            //             return `<span>${row.rupee_plan_name}</span>`;
                            //         } else{
                            //             return `<span>${row.plan_name}</span>`;
                            //         }

                            //     } 
                            // },
                            /*{ data: 'plan_name' },   */          
                            /*{ data: 'price_in_usd' },*/
                            { 
                                "defaultContent": "", 
                                render: function (data, type, row, meta) {
                                // if(that.type == "INR")
                                //      {
                                //         return `<span>${ parseFloat(row.price_in_usd).toFixed(2) * parseFloat(that.INR).toFixed(2)}</span>`;
                                //     } else{
                                    return `<span>$${row.price_in_usd}</span>`;
                                  //  }

                              } 
                          },
                          /*{ data: 'address' },*/
                          { data: 'payment_mode'},
                          {
                            "defaultContent": "", 
                            render: function (data, type, row, meta) {
                                return "<span style='word-break: break-word;'>"+row.address+"</span>";
                            }
                        },
                        {  "defaultContent": "", 
                                render: function (data, type, row, meta) 
                                {
                                    if(row.product_url == "node_api"){
                                        return "<label id='showInvoice' class='btn btn-link text-primary'>Checkout</label>";
                                    }else{
                                        return "<a href='"+row.status_url+"'>Checkout</a>";
                                    }

                                }
                        },
                        {
                            "defaultContent": "", 
                            render: function (data, type, row, meta) {
                                return "<span style='word-break: break-word;'>"+row.in_status+"</span>";
                            }
                        },
                        ]
                    });

                 $('#pending-deposite').on('click', '#showInvoice', function (){                   
                    if (that.table.row($(this).parents('tr')).data() !== undefined) {
                        var data = that.table.row($(this).parents('tr')).data();
                        that.showFundInvoice(data);
                    } else {
                        var data = that.table.row($(this)).data();
                        that.showFundInvoice(data);
                    }
                });
                $("#onSearchClick").click(function() {
                  that.table.ajax.reload();
                });
                $("#onResetClick").click(function() {
                  $("#searchForm").trigger("reset");
                  that.table.ajax.reload();
                });
                },0);
                },  
                getProjectSetting(){
                    axios.get('getprojectsettings', {
                    })
                    .then(response => {
                        this.INR = response.data.data['USD-to-INR'];
                    })
                    .catch(error => {
                    }); 
                },      
                getPackages(){
                    axios.get('get-packages', {
                    })
                    .then(response => {
                     this.INR = response.data.data[0]['convert'];
                     this.type = response.data.data[0]['type'];

                 })
                    .catch(error => {
                    });        
                }, 
                  showFundInvoice(data){
                    this.$router.push({name:'payment-invoice-status', params: {invoice_id:data.invoice_id,type: 'add-fund'}});
                }   
                }
                }
</script>