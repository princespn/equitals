<template>
	<div>
		<div class="page-content">
	
		<div class="container-fluid">
			    <h5 class="with-border text-center">Support</h5>
			<div class="box-typical chat-container">
				<section class="chat-list">
					<div class="chat-list-search">
						<!-- <input type="text" class="form-control form-control-rounded" placeholder="Search"/> -->
					</div><!--.chat-list-search-->
					<div class="chat-list-in scrollable-block">
					
					
					
						<div class="chat-list-item online selected">
							<div class="chat-list-item-photo">
								<img _ngcontent-c5="" alt="" class="online" id="profile-img" src="assets/images/Avatar-1.jpg">
							</div>
							<div class="chat-list-item-header">
								<div class="chat-list-item-name">
									<span class="name">Administration</span>
								</div>
								<!-- <div class="chat-list-item-date">05 Aug</div> -->
							</div>
							<div class="chat-list-item-cont">
								<!-- <div class="chat-list-item-txt">You can run!</div> -->
							</div>
						</div>
						
					</div><!--.chat-list-in-->
				</section><!--.chat-list-->

				<section class="chat-area">
					<div class="chat-area-in">
						<div class="chat-area-header">
							<div class="chat-list-item online">
								<div class="chat-list-item-photo">
									<img _ngcontent-c5="" alt="" class="online" id="profile-img" src="assets/images/Avatar-1.jpg">
								</div>
								<div class="chat-list-item-name">
									<span class="name">Administration</span>
								</div>
								<div class="chat-list-item-txt writing"></div>
							</div>
							<div class="chat-area-header-action">
								<div class="dropdown dropdown-typical">
									<a class="dropdown-toggle dropdown-toggle-txt" id="dd-chat-action" data-target="#" href="http://example.com/" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<!-- <span class="lbl">Actions</span> -->
									</a>
									<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd-chat-action">
										<a class="dropdown-item no-nowrap" href="#">Delete&nbsp;conversation</a>
										<a class="dropdown-item no-nowrap" href="#">Report spam</a>
									</div>
								</div>
							</div>
						</div><!--.chat-area-header-->

						<div class="chat-dialog-area scrollable-block">


						
				<div class="panel-body chat-body media-block" v-for="data in chatmessages">
					    <div v-for="message in data"> 
					        <div v-for="msg in message"> 
					        	
					        		
					            <div v-if="msg.left">
					            	<div class="chat-message">
					                	<div class="chat-message-photo">
											 <img _ngcontent-c5="" alt="" class="online" id="profile-img" src="assets/images/Avatar-1.jpg">
									</div>
										<div class="chat-message-header">
											<div class="tbl-row">
												<div class="tbl-cell tbl-cell-name"> {{msg.left.fromuser_id}}</div>
												<div class="tbl-cell tbl-cell-date">{{msg.left.entry_time}}</div>
											</div>
										</div>

											<div class="chat-message-content">
												<div class="chat-message-txt">{{ msg.left.message }}</div>
											</div>

					                   <!-- <div class="media-body chat-message-content">
					                        <div>
					                            <p>{{ msg.left.message }}<small>{{msg.left.entry_time}}</small></p>
					                        </div>
					                    </div> -->
					            
					            </div>
					                
					            </div>
					            <div v-if="msg.right">
					            	<div class="chat-message">
					            	<div class="chat-message-photo">
											 <img _ngcontent-c5="" alt="" class="online" id="profile-img" src="assets/images/avatar-sign.png">
									</div>
					                   <!--  <div class="media-body chat-message-content"> -->
					                    <div class="chat-message-header">
											<div class="tbl-row">
												<div class="tbl-cell tbl-cell-name"> {{msg.right.fromuser_id}}</div>
												<div class="tbl-cell tbl-cell-date">{{msg.right.entry_time}}</div>
											</div>
										</div>

											<div class="chat-message-content">
												<div class="chat-message-txt">{{ msg.right.message }}</div>
											</div>
										</div>
					              </div>
					                
					            	<!-- </div> -->
					            
					        </div>
					    </div>
					</div>


					<!-- <div class="chat-message">
								<div class="checkbox-bird">
									<input type="checkbox" id="mess-1"/>
									<label for="mess-1"></label>
								</div>
								<div class="chat-message-photo">
									<img src="img/photo-64-1.jpg" alt="">
								</div>
								<div class="chat-message-header">
									<div class="tbl-row">
										<div class="tbl-cell tbl-cell-name">Felix Lane</div>
										<div class="tbl-cell tbl-cell-date">04.07.15</div>
									</div>
								</div>
								<div class="chat-message-content">
									<div class="chat-message-txt">Yes, I will alter it</div>
								</div>
							</div> -->

							
						


						</div><!--.chat-dialog-area-->

						<div class="chat-area-bottom">
							<form class="write-message">
								<div class="avatar">
									<img _ngcontent-c5="" alt="" class="online" id="profile-img" src="assets/images/Avatar-1.jpg">
								</div>
								<div class="form-group">
									<textarea rows="2" class="form-control" placeholder="Type a message" v-model="message"></textarea>
								</div>
								<button type="submit" class="btn btn-rounded float-left" @click="sendMessage()">Send</button>
								<button type="reset" class="btn btn-rounded btn-default float-left">Clear</button>
								<div class="dropdown dropdown-typical dropup attach">
									<!-- <a class="dropdown-toggle dropdown-toggle-txt"
									   id="dd-chat-attach"
									   data-target="#"
									   data-toggle="dropdown"
									   aria-haspopup="true"
									   aria-expanded="false">
										<span class="lbl">Attach</span>
									</a> -->
									<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd-chat-attach">
										<a class="dropdown-item" href="#"><i class="font-icon font-icon-cam-photo"></i>Photo</a>
										<a class="dropdown-item" href="#"><i class="font-icon font-icon-cam-video"></i>Video</a>
										<a class="dropdown-item" href="#"><i class="font-icon font-icon-sound"></i>Audio</a>
										<a class="dropdown-item" href="#"><i class="font-icon font-icon-page"></i>Document</a>
										<a class="dropdown-item" href="#"><i class="font-icon font-icon-earth"></i>Map</a>
									</div>
								</div>
							</form>
						</div><!--.chat-area-bottom-->
					</div><!--.chat-area-in-->
				</section><!--.chat-area-->
			</div><!--.chat-container-->

		</div><!--.container-fluid-->
	</div><!--.page-content-->
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
					chatmessages: {
					id:'',
					from_user_id:'',
					position:'',
					entry_time:'',
					},
					message:'',
					position:'',
					entry_time:'',
			}
		},
        computed: {
        	isCompleteChat() {
        		return this.message;      
        	}
        },
        mounted(){
        	this.getMessages();
        },
        methods:{
            getMessages(){ 
                axios.post('fetch-message', {
                	to_user:1,
                })
                .then(response => {
                    this.chatmessages = response.data.data.messages;
                //    this.chatmessages.showtime = response.data.msgdata;
                })
                .catch(error => {
                }); 
            },
			sendMessage() {
                axios.post('send-message', { 
                    message: this.message,
                    to_user:1,
                })
                .then(response => {
                    if(response.data.code == 200){
                        this.$toaster.success(response.data.message);
                        this.message='';
        				this.getMessages();     
                    } else {
                       this.$toaster.error(response.data.message);
                    }
                })
            }            
        }
    }
</script>