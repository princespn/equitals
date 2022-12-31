<template>
  <!-- BEGIN: Content-->
  <div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
          <div class="row breadcrumbs-top">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
              <h2 class="content-header-title float-left mb-0">Expired Investment</h2>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="daterange-container">
                         <div class="date-range">
                            <div id="reportrange">
                               <!--  <input type="text" style="color:white;border:none;background: #f4f5fb;" id="daterangefocus" @focus="reloadTable"> -->
                                <i class="icon-calendar cal"></i>
                                <i class="icon-chevron-down arrow"></i>
                                <span class="range-text"></span>
                                <input class="from" v-show="false">
                                <input class="to" v-show="false">
                            </div>
                        </div>
                        <!-- <a href="#" data-toggle="tooltip" data-placement="top" title="Download CSV" class="download-reports">
                            <i class="icon-download1"></i>
                        </a> -->
                    </div>
                </div>
          </div>
        </div>
      </div>
            <div class="row ">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card lp">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="confirm-deposite" class="table table-striped table-bordered mar-top-23 dataTable dtr-inline collapsed" cellspacing="0" width="100%" role="grid" aria-describedby="demo-dt-basic_info" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Date</th>
                                            <th>Deposit Id</th>
                                           <!--  <th>Package</th> -->
                                            <th>Amount</th>
                                            <th style="word-break: break-word;">Address</th>
                                            <th>Action</th>       
                                        </tr>
                                    </thead>                                        
                                    <tfoot>
                                        <tr>
                                          <th colspan="3">Total</th>
                                        <!--   <th></th> -->
                                          <th id="total_amount"></th>
                                          <th></th>
                                          <th></th>
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
            //this.getProjectSetting();
           // this.getPackages();
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
                $('#confirm-deposite').DataTable().ajax.reload();
                 $('#daterangefocus').blur(); 
                 
            },
            getConfirmedDeposite(){
                let i = 0;
                let that = this;
                let token = localStorage.getItem('user-token');
                setTimeout(function(){

                    const table = $('#confirm-deposite').DataTable({
                        responsive: true,
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        responsive: true,
                        stateSave: false,
                        ordering: true,
                         // dom: 'Blfrtip',

                        // buttons: [
                        //     // 'copyHtml5',
                        //     'excelHtml5',
                        //    // 'csvHtml5',
                        //    // 'pdfHtml5',
                        //  //   'pageLength',
                        // ],
                        ajax: {
                            url: apiUserHost+'expired-add-deposit',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    frm_date : $('.from').val(),
                                    to_date  : $('.to').val(),
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
                                    for (let j = 0; j < json.data.records.length; j++) {
                                        total_amount = total_amount + parseInt(json.data.records[j].price_in_usd);          
                                        $('#total_amount').text("$"+total_amount);
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
                                render: function (data, type, row, meta) {
                                    return meta.row + meta.settings._iDisplayStart + 1;
                                }
                            },
                            {
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
                              { render: function (data, type, row, meta) {
                                /*if(that.type == "INR")
                                     {
                                        return `<span>${ parseFloat(row.price_in_usd).toFixed(2) * parseFloat(that.INR).toFixed(2)}</span>`;
                                    } else{*/
                                        return `<span>$${row.price_in_usd}</span>`;
                                   // }
                                
                                } 
                            },
                            /*{ data: 'address' },*/
                            {
                                render: function (data, type, row, meta) {
                                    return "<span style='word-break: break-word;'>"+row.address+"</span>";
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    return "<a href='"+row.status_url+"'>Checkout</a>";
                                }
                            },
                        ]
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
        }
    }
</script>