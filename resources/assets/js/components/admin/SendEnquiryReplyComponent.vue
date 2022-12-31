<template>
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Send Reply</h4>
            </div>
        </div>
        <div class="page-content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <form class="form-valide m-t-20 col-12" action="#" method="post" novalidate="novalidate" v-on:submit.prevent="sendReply">
                                    <fieldset class="form-group">
                                        <label>To Mail</label>
                                        <input type="email" placeholder="Email" id="to-mail" v-model="to_mail" name="email" class="{ error: errors.has('email') } form-control f-s-25" v-validate="'required|email|max:50'">
                                        <div class="tooltip2" v-show="errors.has('email')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('email')">{{ errors.first('email') }}</span>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset class="form-group">
                                        <label> Subject</label>
                                        <input type="text" placeholder="Subject" id="subject" v-model="subject" name="subject"  class="{ error: errors.has('subject') } form-control f-s-25" v-validate="'required'">
                                        <div class="tooltip2" v-show="errors.has('subject')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('subject')">{{ errors.first('subject') }}</span>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset class="form-group">
                                        <label>Message</label>
                                        <textarea placeholder="Message" v-model="message" rows="3" name="message"  class="{ error: errors.has('message') } form-control f-s-25" v-validate="'required'"></textarea>
                                        <div class="tooltip2" v-show="errors.has('message')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('message')">{{ errors.first('message') }}</span>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <button class="btn btn-primary" type="submit" :disabled="errors.any() || !isComplete ">Submit </button>
                                    <button class="btn btn-secondary" type="reset" @click="resetFrom">Reset </button>
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
   // import { apiAdminHost } from'./../../config'; 
     import { apiAdminHost } from'./../../admin-config/config';
    export default {
        data() {
            return {
                to_mail : null,
                message : null,
                subject : null,
            }
        },
        mounted() {
             let email_id =this.$route.params.id;
             if(email_id != null){
                 this.to_mail=email_id
             }else{
                 this.to_mail=null;
             }
        },
        computed: {
            isComplete () {
                return this.to_mail && this.message && this.subject;
            }
        },
        methods: {
            sendReply(){
                axios.post(apiAdminHost+'/send-enquiry-reply', {
                    to_mail : this.to_mail,
                    message : this.message,
                    subject : this.subject
                }).then(response => {
                    if(response.data.code == 200) {
                        this.$toaster.success(response.data.message);
                        this.$router.replace({ path:'/send-reply-reports'});
                        this.user_message = "";
                        this.resetFrom();
                    } else {
                        this.$toaster.error(response.data.message);
                    }
                }). catch(error => {
                    this.resetFrom();
                    this.$toaster.error(error.data.message);
                });
                this.user_message = "";
            },
            resetFrom(){
                this.to_mail = null;
                this.subject = null;
                this.message = null;
            },
        }
    }
</script>