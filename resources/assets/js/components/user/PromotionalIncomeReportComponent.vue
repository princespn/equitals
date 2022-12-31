<<template>
    <div>

    <div class="page-content">
        <div class="container-fluid">
            <!-- s -->
            <section class="card">
                <div class="card-block">
                    <h5 class="with-border text-center">Video Income Report</h5>
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="promotional-income-report" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Amount</th>

                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>  
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    import { apiUserHost } from'../../user-config/config';
    import Breadcrum from './BreadcrumComponent.vue';
    import DatePicker from 'vuejs-datepicker';

    export default {  
        components: {
            Breadcrum,
            DatePicker
        },
        data(){
            return{
                arrPromotional:[]
            }
        },
        mounted(){
            this.getLevelView();
            this.getPromotionalTypes();
        },
        methods:{
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            getLevelView() {
                let i = 0;
                let that = this;
                let token = localStorage.getItem('user-token');
                setTimeout(function(){
                    that.table = $('#promotional-income-report').DataTable({
                        responsive: true,
                        lengthMenu: [[10, 50, 100], [10, 50, 100]],
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        responsive: true,
                        stateSave: false,
                        ordering: true,
                        ajax: {
                            url: apiUserHost+'show/promotional/income',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                    
                                };
                                Object.assign(d, params);
                                return d;
                            },
                            headers: {
                                'Authorization': 'Bearer ' + token
                            },
                            dataSrc: function (json) {
                                if (json.code === 200) {
                                    json['recordsFiltered'] = json.data.filterRecord;
                                    json['recordsTotal'] = json.data.totalRecord;
                                    return json.data.record;
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
                                    return i++;
                                }
                            },
                            
                            /*{ data: 'amount' },*/
                            {
                                render: function (data, type, row, meta) {
                                   
                                      return '$'+row.amount;
                                   
                                }
                            },

                            /*{
                                render: function (data, type, row, meta) {
                                    if (row.from_date === null || row.from_date === undefined || row.from_date === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.from_date)).format('YYYY/MM/DD');
                                    }
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.to_date === null || row.to_date === undefined || row.to_date === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.to_date)).format('YYYY/MM/DD');
                                    }
                                }
                            },*/
                            {
                                render: function (data, type, row, meta) {
                                    if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.entry_time)).format('YYYY/MM/DD');
                                    }
                                }
                            },
                             { data: 'status' },
                        ]
                    });

                    $('#onSearch').click(function () {
                        that.table.ajax.reload();
                    });

                    $('#onReset').click(function () {
                        $('#search-form').trigger("reset");
                        that.table.ajax.reload();
                    });
                },0);
            },
            getPromotionalTypes() {
                axios.get("show/promotional/type").then(response => {
                    if(response.data.code === 200){
                        this.arrPromotional = response.data.data;   
                    } else {
                        this.$toaster.error(response.data.message);
                    }
                    
                }).catch(error => {

                });
            }
        }
    }
</script>