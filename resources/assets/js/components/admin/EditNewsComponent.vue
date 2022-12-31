<template>
    <div class="content">

        <div class="">
            <div class="page-header-title">
                <h4 class="page-title"> Edit News </h4>
            </div>
        </div>

        <div class="page-content-wrapper ">

            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-3">
                        <div class="panel panel-primary">
                            <div class="panel-body add-new">
                                <a class="btn btn-primary waves-effect waves-light pull-right" href="admin#/manage-theme/news">
                                    <i class="fa fa-mail-reply"></i> &nbsp;Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <h4 class="m-t-0 m-b-30"> Edit News </h4>
                                <form class="form-horizontal" v-on:submit.prevent="onUpdateNewsClick">
                                    <input type="hidden" name="id" v-model="editNews.id">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label"> Subject
                                                <span class="madatoryStar text-danger"> *</span>
                                            </label>
                                            <div class="col-md-10">
                                                <input name="subject" class="form-control" placeholder="Update Subject" type="text" v-model="editNews.subject" v-validate="'required'">
                                                <div class="tooltip2" v-show="errors.has('subject')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('subject')">{{ errors.first('subject') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>    

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="example-mobile"> Description
                                                <span class="madatoryStar text-danger"> *</span>
                                            </label>
                                            <div class="col-md-10">
                                                <input name="text" class="form-control" id="text" placeholder="Enter Description" required type="text" v-model="editNews.description" v-validate="'required'">
                                                <div class="tooltip2" v-show="errors.has('description')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('description')">{{ errors.first('description') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-5 col-md-6">
                                            <div class="col-md-6">
                                                <button class="btn btn-info col-md-12" name="submit" type="submit">
                                                    <i class="ace-icon fa fa-check bigger-110"></i>Submit
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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
    import Swal from 'sweetalert2';
    import DatePicker from 'vuejs-datepicker';
    import moment  from 'moment';

    export default {
    	
        data() {
            return {
                editNews: {
                    id: '',
                    subject: '',
                    description: '',
                }
            }
        }, 
        components: {
            DatePicker
        },       
        computed: {

        },
        mounted() {
            this.getUserDetails();
        },
        methods: {
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            getUserDetails(){
                axios.post('/edit/news',{
                    id: this.$route.params.id,
                }).then(resp => {
                    if(resp.data.code === 200){
                        this.editNews = {
                            id: resp.data.data.id,
                            subject: resp.data.data.sub,
                            description: resp.data.data.text,
                        }
                    } else {
                        this.$toaster.error(resp.data.message);
                    }
                }).catch(err => {
                    this.$toaster.error(err);
                })
            },
            onUpdateNewsClick() {
                Swal({
                    title: 'Are you sure?',
                    text: `You want to edit news`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/update/news',this.editNews)
                        .then(resp => {
                            if(resp.data.code === 200){
                                this.$router.push({name:'managenews'});
                                this.$toaster.success(resp.data.message);
                            } else {
                                this.$toaster.error(resp.data.message);
                            }
                        }).catch(err => {
                            this.$toaster.error(err.resp.data.message);
                        })
                    }
                });
            }
        }
    }
</script>