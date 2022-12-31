<style type="text/css" scoped>
  .card-footer1 {
    margin-bottom: 20px;
}
.show.preview_file_block {
    background: #0a0c19;
}
    .chat{
      margin-top: auto;
      margin-bottom: auto;
    }
    .card{
      height: 500px;
      border-radius: 15px !important;
      background-color: rgba(0,0,0,0.4) !important;
    }
    .contacts_body{
      padding:  0.75rem 0 !important;
      overflow-y: auto;
      white-space: nowrap;
    }
    .msg_card_body{
      overflow-y: auto;
    }
    .card-header{
      border-radius: 15px 15px 0 0 !important;
      border-bottom: 0 !important;
    }
   .card-footer{
    border-radius: 0 0 15px 15px !important;
      border-top: 0 !important;
  }
    .container{
      align-content: center;
    }
    .search{
      border-radius: 15px 0 0 15px !important;
      background-color: rgba(0,0,0,0.3) !important;
      border:0 !important;
      color:white !important;
    }
    .search:focus{
         box-shadow:none !important;
           outline:0px !important;
    }
    .type_msg{
      background-color: rgba(0,0,0,0.3) !important;
      border:0 !important;
      color:white !important;
      /*height: 40px !important;*/
      overflow: hidden;
    }
      .type_msg:focus{
         box-shadow:none !important;
           outline:0px !important;
    }
    .attach_btn{
  border-radius: 15px 0 0 15px !important;
  background-color: rgba(0,0,0,0.3) !important;
      border:0 !important;
      color: white !important;
      cursor: pointer;
    }
    .send_btn{
  border-radius: 0 15px 15px 0 !important;
  background-color: rgba(0,0,0,0.3) !important;
      border:0 !important;
      color: white !important;
      cursor: pointer;
    }
    .search_btn{
      border-radius: 0 15px 15px 0 !important;
      background-color: rgba(0,0,0,0.3) !important;
      border:0 !important;
      color: white !important;
      cursor: pointer;
    }
    .contacts{
      list-style: none;
      padding: 0;
    }
    .contacts li{
      width: 100% !important;
      padding: 5px 10px;
      margin-bottom: 15px !important;
    }
  .active{
      background-color: rgba(0,0,0,0.3);
  }
    .user_img{
      height: 70px;
      width: 70px;
      border:1.5px solid #f5f6fa;
    
    }
    .user_img_msg{
      height: 40px;
      width: 40px;
      border:1.5px solid #f5f6fa;
    
    }
  .img_cont{
      position: relative;
      height: 70px;
      width: 70px;
  }
  .img_cont_msg{
      display: contents;
      text-align: right;
  }
  .msg_cotainer_outer_left .img_cont_msg{
      display: contents;
      text-align: left;
  }
  .img_cont_msg .message {
      padding-left: 10px;
  }
  .online_icon{
    position: absolute;
    height: 15px;
    width:15px;
    background-color: #4cd137;
    border-radius: 50%;
    bottom: 0.2em;
    right: 0.4em;
    border:1.5px solid white;
  }
  .offline{
    background-color: #c23616 !important;
  }
  .user_info{
    margin-top: auto;
    margin-bottom: auto;
    margin-left: 15px;
  }
  .user_info span{
    font-size: 20px;
    color: white;
  }
  .user_info p{
  font-size: 10px;
  color: rgba(255,255,255,0.6);
  }
  .video_cam{
    margin-left: 50px;
    margin-top: 5px;
  }
  .video_cam span{
    color: white;
    font-size: 20px;
    cursor: pointer;
    margin-right: 20px;
  }
.msg_cotainer {
    margin-top: auto;
    margin-bottom: auto;
    margin-left: 10px;
    /* border-radius: 25px;
    background-color: #3d4467;
    padding: 10px; */
    position: relative;
   /* width: 40%;*/
}
.msg_cotainer_send {
    margin-top: auto;
    margin-bottom: auto;
    margin-right: 10px;
    border-radius: 25px;
    /*background-color: #6766e4;
    padding: 10px;
    position: relative;*/
   /* width: 40%;*/
}
.msg_cotainer_send p{
    margin-top: auto;
    margin-bottom: auto;
    margin-right: 10px;
    border-radius: 25px;
    background-color: #6766e4;
    padding: 10px;
    position: relative;
   /* width: 40%;*/
}
.msg_cotainer_outer_left .msg_cotainer{
  background-color: unset;
    padding: 0px;
}
.msg_cotainer_outer_left .msg_cotainer p{
    border-radius: 25px;
    background-color: #3d4467;
    padding: 10px;
    position: relative;
   /* width: 40%;*/
}
.msg_cotainer_outer {
    position: relative;
}
  .msg_time{
    position: absolute;
    right: 16px;
    bottom: -15px;
    color: rgba(255,255,255,0.5);
    font-size: 10px;
  }
  .msg_cotainer_outer_left .msg_time{
    position: absolute;
    left: 16px;
    bottom: -15px;
    color: rgba(255,255,255,0.5);
    font-size: 10px;
  }

  .msg_time_send{
    position: absolute;
    right:0;
    bottom: -15px;
    color: rgba(255,255,255,0.5);
    font-size: 10px;
  }
  .msg_head{
    position: relative;
  }
  .msg_left_recvr {
      position: absolute;
      left: 10px;
  }
  #action_menu_btn{
    position: absolute;
    right: 10px;
    top: 10px;
    color: white;
    cursor: pointer;
    font-size: 20px;
  }
  .action_menu{
    z-index: 1;
    position: absolute;
    padding: 15px 0;
    background-color: rgba(0,0,0,0.5);
    color: white;
    border-radius: 15px;
    top: 30px;
    right: 15px;
    display: none;
  }
  .action_menu ul{
    list-style: none;
    padding: 0;
  margin: 0;
  }
  .action_menu ul li{
    width: 100%;
    padding: 10px 15px;
    margin-bottom: 5px;
  }
  .action_menu ul li i{
    padding-right: 10px;
  
  }
  .action_menu ul li:hover{
    cursor: pointer;
    background-color: rgba(0,0,0,0.2);
  }
  .msg_cotainer_send.mh1 p {
      margin-bottom: 0;
      text-align: center;
      margin-right: 0;
  }
  .d-flex.justify-content-end{
    position: relative;
}
  .msg_right_sender {
      position: absolute;
      right: 50px;
  }
  .d-flex.justify-content-start {
      position: relative;
  }
  .justify-content-start .msg_cotainer.mh1 p {
      margin-bottom: 0;
          text-align: center;
  }
  @media(max-width: 767px){
    .contacts_card {
        height: auto;
    }
  }
  @media(max-width: 576px){
  .contacts_card{
    margin-bottom: 15px !important;
  }
  }
  .mt-20{
    margin-top: 20px;
  }
  .mh1{
        left: 0px!important;
    margin: 21px 0px 5px!important;
  }
  .mr-10{
    text-align: right !important;
    margin-right: -40px;
  }
  .w200{
        width: 60px;
    height: 60px;
}
.w2001 {
    width: 140px;
    height: auto;
}
.pointer{
  cursor: pointer;
}
.mt-180{
      margin-top: 180px;
}
</style>
<template>
 <!-- BEGIN: Content-->
<div class="app-content content">

  <div class="container-fluid h-100 mt-225">
      <div class="row justify-content-center h-100 mt-180">
        <div class="col-md-4 col-xl-3 chat mt-20"><div class="card mb-sm-3 mb-md-0 contacts_card">
          <div class="card-header">
            <div class="input-group">
              <input type="text" placeholder="Search..." name="" class="form-control search">
              <div class="input-group-prepend">
                <span class="input-group-text search_btn"><i class="fas fa-search"></i></span>
              </div>
            </div>
          </div>
          <div class="card-body contacts_body">
            <ul class="contacts">
            <li class="active">
              <div class="d-flex bd-highlight">
                <div class="img_cont">
                  <img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img">
                  <span class="online_icon"></span>
                </div>
                <div class="user_info">
                  <span>Admin</span>
                  <p>Admin is online</p>
                </div>
              </div>
            </li>
           <!--  <li>
              <div class="d-flex bd-highlight">
                <div class="img_cont">
                  <img src="https://2.bp.blogspot.com/-8ytYF7cfPkQ/WkPe1-rtrcI/AAAAAAAAGqU/FGfTDVgkcIwmOTtjLka51vineFBExJuSACLcBGAs/s320/31.jpg" class="rounded-circle user_img">
                  <span class="online_icon offline"></span>
                </div>
                <div class="user_info">
                  <span>Taherah Big</span>
                  <p>Taherah left 7 mins ago</p>
                </div>
              </div>
            </li> -->

            </ul>
          </div>
          <!-- <div class="card-footer"></div> -->
        </div></div>
        <div class="col-md-8 col-xl-6 chat mt-20">
          <div class="card">
            <div class="card-header msg_head">
              <div class="d-flex bd-highlight">
                <div class="img_cont">
                  <img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img">
                  <span class="online_icon"></span>
                </div>
                <div class="user_info">
                  <span>Chat with {{userdata.user_id}}</span>
                  <!-- <p>1767 Messages</p> -->
                </div>
                <div class="video_cam">
                 <!--  <span><i class="fas fa-video"></i></span>
                  <span><i class="fas fa-phone"></i></span> -->
                </div>
              </div>
              <span id="action_menu_btn"><i class="fas fa-ellipsis-v"></i></span>
              <div class="action_menu">
                <ul>
                  <li><i class="fas fa-user-circle"></i> View profile</li>
                  <li><i class="fas fa-users"></i> Add to close friends</li>
                  <li><i class="fas fa-plus"></i> Add to group</li>
                  <li><i class="fas fa-ban"></i> Block</li>
                </ul>
              </div>
            </div>

            <!-- Main Section -->
            <div class="card-body msg_card_body" v-chat-scroll="{always: false}">
                 <div  v-for="message in userchats" class="chat-group">
              <div v-for="msg in message.msgdata" class="chat-group">


              <div  v-if="msg.left" class="d-flex justify-content-start mb-4">
                
               
                  <div class="msg_left_recvr">
                     <h6>{{msg.left.fromuser_id}} </h6> 
                  </div>
                  <div class="msg_cotainer_outer_left">
                    <div class="msg_cotainer mh1">
                       <p  v-if="msg.left.message">{{msg.left.message}}</p>
                    </div>
                    <div class="img_cont_msg">
                       <div v-if="msg.left.attachment">
                              <div class="message" v-for="file in msg.left.files">
                                  <a @click="showImage('public/uploads/support/'+file)"><img :src="'public/uploads/support/'+file" class="w200"></a>
                              </div>
                        </div>
                        <div class="msg_time_o_left">
                           <span class="msg_time">{{msg.left.time}}</span>
                        </div>
                    </div>
                  </div>
                  
                  
                  
              </div>


              <div v-else-if="msg.right" class="d-flex justify-content-end mb-4">
                <div class="msg_right_sender">
                  <h6 class="mr-10">{{msg.right.fromuser_id}}</h6>
                </div>

                <div class="msg_cotainer_outer">
                  <div class="msg_cotainer_send mh1">
                    <p  v-if="msg.right.message">{{msg.right.message}}</p>
                  </div>
                  <div class="img_cont_msg">
                    <div v-if="msg.right.attachment">
                        <div class="message" v-for="file in msg.right.files">
                            <a @click="showImage('public/uploads/support/'+file)">
                              <img :src="'public/uploads/support/'+file" class="w200"></a>
                        </div>
                    </div>
                    <div class="msg_time_o">
                      <span class="msg_time">{{msg.right.time}}</span>
                    </div>
                  </div>     
                  
                  
                  <!-- <span class="msg_time_send">8:55 AM, Today</span> -->   
                           
                  
                </div>
                
              </div>
              </div>
             </div>
          </div>
            <!--End Main Section -->

            <div class="card-footer1">
              <div class="input-group">
                <div class="input-group-append">
                   <!-- <input type="file" class="input-group-text attach_btn" name="file" id="file" ref="file" accept="image/*" multiple @change="onFileChange"/> -->
                       <!--  <button onclick="document.getElementById('file').click();" class="btn btn-icon" type="button"><i class="fas fa-paperclip"></i>
                        </button> -->
                </div>

                <div v-bind:class="isUpload">
                  <span v-for="(file, key) in files">
                      <img :src="file" class="w200"/>
                      <i class="fa fa-close pointer" @click="removeImage(key)"></i>
                  </span>
              </div>

                <textarea name="" class="form-control type_msg" id="chatInputForm" contenteditable="true" v-model="message" @keyup.enter="trigger" placeholder="Type your message..."></textarea>

                <!--  <input class="chat-reply-input form-control" id="chatInputForm" contenteditable="true" v-model="message" placeholder="Write a reply..."  @keyup.enter="trigger" @focus="closeEmoji"> -->

                <div class="input-group-append">
                  <button @click="sendMessage()" class="input-group-text send_btn" title="Send Message" data-original-title="Send" type="button"><i class="fas fa-location-arrow"></i></button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<!-- END: Content-->
</template>

<script>
    import Breadcrum from './BreadcrumComponent.vue'; 
    import VueEasyLightbox from 'vue-easy-lightbox';
    import {Picker} from 'emoji-mart-vue';
    export default {
        components:{
            Breadcrum,
            VueEasyLightbox,
            Picker,
        }, 
        data(){
            return{
                message:'',
                userchats:[],
                to_user:1,
                files:[],
                upload_files:[],
                gallery_files:[],
                scrolled:false,
                imgs: '', 
                visible: false,
                index: 0 , 
                isEmojiPicker: false,
                type:'text',
                typing:false,
                user:'',
                trans_word:'',
                toastermsg:'',
                toastercolor:'',   //1 Red , 0 Green
                chatcompo:1,
                userdata:
                {
                  user_id:''
                }            }
        },
        computed:{
            isUpload()
            {
                if(this.files.length!=0){
                    return 'show preview_file_block';
                }
                else{
                    return 'hide';
                }
            },
            isCompleteChat() {
                return this.message;      
            },
        },
        created(){
          this.fetch_message_read();
            Echo.channel('chat')
            .listen('MessageSent', (e) => {
                // console.log(e);

                this.fetch_message();
            });
        },
        mounted(){
          $('.chat-icon').css('display','none');
          this.getUserDetails();
            
            this.fetch_message_read();
            this.fetch_message();
        },
        methods:{
          getUserDetails(){ 
                axios.get('get-user-dashboard', {
                })
                .then(response => {
                     this.userdata = response.data.data;
                     this.total_withdraw = (this.userdata.confirmed_roi_withdraw).toFixed(4);
                     //alert(this.userdata.show_popup);
                     if(this.userdata.show_popup == 1){
                       this.lasttopup_pin = this.userdata.lasttopup_pin;
                       this.user_id = this.userdata.user_id;
                       this.lasttopup_amount = this.userdata.lasttopup_amount;
                       this.lasttopup_per = this.userdata.lasttopup_per;
                          this.openmodal = 1;
                          //$('#myModal').modal('show');
                        
                        
                     }else{
                           this.openmodal = 0;
                         //$('#myModal').modal('hide');
                     }
                })
                .catch(error => {
                }); 
             },
            trigger () {
                this.sendMessage();
            },
            sendMessage() {
                var to_user = this.to_user;
                let formData = new FormData();
               /* let uploadedFiles = this.$refs.file.files;
                for(let i=0;i<this.files.length;i++)
                {
                    this.upload_files.push(uploadedFiles[i]);
                    formData.append('files[]', this.upload_files[i]);
                    this.type='file';
                }*/
                formData.append('to_user', to_user);
                formData.append('message', this.message);
                formData.append('translated_message', this.trans_word);
                formData.append('type', this.type);
                axios.post('send-message', formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(response => {
                    if(response.data.code == 200){
                        this.$toaster.success(response.data.message);
                        this.message='';
                        this.trans_word='';
                        this.files=[];
                        this.upload_files=[];  
                        this.fetch_message_read(); 
                        this.fetch_message();
                    } else {
                      
                       this.$toaster.error(response.data.message);
                    }
                })
            },
            fetch_message() {
                axios.post('fetch_message',{
                    to_user : this.to_user,
                }).then(response => {
                    this.userchats = response.data.data;
                    this.imgs=response.data.data.files; 
                    $('#fetch').html(response.data.data.total_unread);
                   // alert()
                   // location.reload();
                    //alert(this.userchats);
                }).catch(error => {

                });              
            },

            fetch_message_read() {
                axios.post('fetch_message_read',{
                    to_user : this.to_user,
                }).then(response => {
                    this.userchats = response.data.data;
                    this.imgs=response.data.data.files; 
                    $('#fetch').html(0);
                   // alert()
                   // location.reload();
                    //alert(this.userchats);
                }).catch(error => {

                });              
            },
            onFileChange(e) {
                var files = e.target.files || e.dataTransfer.files;
                var i=0;
                if (!files.length)
                    return;
                for(i=0;i<files.length;i++)
                {
                    this.createImage(files[i]);
                }       
            },
            createImage(create_file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.files.push(e.target.result);
                    // vm.file = e.target.result;
                };
                reader.readAsDataURL(create_file);
            },
            removeImage(key) {
                this.files.splice(key, 1);
            },
            showImage(image) {
                var i=this.imgs.indexOf(image);
                this.index = i ;
                this.show();
            },
            show() {
              this.visible = true;
            },
            handleHide() {
              this.visible = false;
            },
            showEmojiPicker(){
                this.isEmojiPicker=!(this.isEmojiPicker);
            },
            addEmoji (emoji) {
                this.message=this.message+emoji.native;
            },
            closeEmoji(){
                this.isEmojiPicker=false;
            }   
        }
    }
</script>
