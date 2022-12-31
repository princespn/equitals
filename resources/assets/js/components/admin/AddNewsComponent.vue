<template>
    <div class="content">

        <div class="">
            <div class="page-header-title">
                <h4 class="page-title"> Add News </h4>
            </div>
        </div>

        <div class="page-content-wrapper ">

            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-3">
                        <div class="panel panel-primary">
                            <div class="panel-body add-new">
                                <a class="btn btn-primary waves-effect waves-light pull-right" href="EqT2FyvKdAL6FW3gfCKszyU9clNc2hs#/manage-theme/news">
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
                                <h4 class="m-t-0 m-b-30"> Add News </h4>
                                <form v-on:submit.prevent="onAddNewsClick">
                                    <div class="form-group">
                                        <label class="control-label"> Subject
                                            <span class="madatoryStar text-danger"> *</span>
                                        </label>
                                        <input name="subject" class="form-control" placeholder="Add Subject" type="text" v-model="addNews.subject" v-validate="'required'">
                                        <div class="tooltip2" v-show="errors.has('subject')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('subject')">{{ errors.first('subject') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <div>
                                                <div class="form-group">
                                                    <textarea name="text" class="form-control textarea_resize" id="text" placeholder="Enter Description" rows="7" required v-model="addNews.description" v-validate="'required'"></textarea>
                                                    <div class="tooltip2" v-show="errors.has('description')">
                                                        <div class="tooltip-inner">
                                                            <span v-show="errors.has('description')">{{ errors.first('description') }}</span>
                                                        </div>
                                                    </div>
                                                </div><!-- input-group -->
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button class="btn btn-primary" name="submit" type="submit" :disabled='!isCompleteAddNews'>
                                            <i class="ace-icon fa fa-check bigger-110"></i>Submit
                                        </button>
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
                addNews: {
                    subject: '',
                    description: '',
                }
            }
        }, 
        components: {
            DatePicker
        },       
        computed: {
            isCompleteAddNews(){
                return this.addNews.subject && this.addNews.description;
            }
        },
        methods: {
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            onAddNewsClick() {
                Swal({
                    title: 'Are you sure?',
                    text: `You want to add news`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        //axios.post('/update/news',this.addNews)
                        axios.post('/store/news',{
                            subject: this.addNews.subject,
                            description: this.addNews.description,
                        }).then(resp => {
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