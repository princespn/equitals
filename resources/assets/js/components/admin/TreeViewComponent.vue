<style type="text/css">
	.img-a {
	     border-radius: 0; 
	}
</style>
<template>
	<div class="content">
	   	<div class="">
	      	<div class="page-header-title">
	         	<h4 class="page-title">Tree View</h4>
	      	</div>
	   	</div>
	   	<div class="page-content-wrapper">
	      	<div class="container">
	         	<div class="row">
	            	<div class="col-sm-12 m-b-30">
	               		<div class="panel panel-primary m-b-30 fortree" style="height: 615px;">
	                  		<div class="panel-body m-b-30">
	                     		<div class="col-sm-4">
	                        		<form class="example" @submit.prevent="onSearchClick">
	                        			<div class="col-md-10">
	                           				<input type="text" placeholder="Enter User Id" name="search" v-model="searchText" class="form-control" v-on:keyup="checkUserExisted">
	                           			</div>
						                <button type="button" @click.prevent="onSearchClick" class="btn btn-primary btn-search-user-id">
						                    <i class="fa fa-search"></i>
						                </button>
	                        		</form>
	                        		<br>
	                        		<div class="clearfix"></div>
	                        		<p :class="{'text-success': isAvialable == 'Available','text-danger': isAvialable == 'Not Available'}" v-if="isAvialable!=''">{{isAvialable}}</p>
	                     		</div>
	                     		<div class="col-sm-2 col-md-offset-5">
			                        <form class="example">
			                        	<div class="col-sm-3">
			                           		<button class="btn btn-primary m-0" type="button" @click="onBackClick">
			                           			<i aria-hidden="true" class="fa fa-arrow-left"></i>Back
			                           		</button>
			                           	</div>
			                           	<div class="col-sm-3 col-md-offset-5">
			                           		<button class="btn btn-primary m-0" type="button" @click="onForwardClick">
			                           			<i aria-hidden="true" class="fa fa-arrow-right"></i>Forward
			                           		</button>
			                           </div>
			                        </form>
	                     		</div>
	                     <br>
	                     <br>
	                     <br>
	                     <br>
	                     <br>
	                     <div style="margin-top:10%;">
	                        <table  align="center" border="0" class="margin-auto" width="80%">
	                           <tbody >
	                              <tr >
	                                 <td align="center">
	                                    <div class="section-notification">
	                                       <ul class="nav">
	                                          <li class="">
	                                             <img class="img-a" :src="imgURL+'/images/'+userdata.image" width="40">
	                                             <p class="tree-user-name"> {{userdata.user_id}} </p>
	                                             <ul class="notifications" style="left: 42%;">
	                                                <table style="width:100%">
	                                                   	<tbody>
	                                                      	<tr>
	                                                         	<td>
	                                                            	<li>
	                                                               		<strong > User Name</strong>
	                                                            	</li>
	                                                         	</td>
	                                                         	<td>
	                                                            	<li>{{userdata.user_id}} </li>
	                                                         	</td>
	                                                      	</tr>
	                                                      	<tr>
	                                                         <td >
	                                                            <li >
	                                                               <strong> Fullname </strong>
	                                                            </li>
	                                                         </td>
	                                                         <td >
	                                                            <li>{{userdata.fullname}}</li>
	                                                         </td>
	                                                      </tr>
	                                                      <tr >
	                                                         <td >
	                                                            <li >
	                                                               <strong> Sponser User Name</strong>
	                                                            </li>
	                                                         </td>
	                                                         <td >
	                                                            <li>{{userdata.sponsor_id}}</li>
	                                                         </td>
	                                                      </tr>
	                                                      <tr>
	                                                         <td >
	                                                            <li >
	                                                               <strong > Date of joining</strong>
	                                                            </li>
	                                                         </td>
	                                                         <td >
	                                                            <li>{{userdata.entry_time | moment('YYYY-MM-DD')}}</li>
	                                                         </td>
	                                                      </tr>
	                                                     <!--  <tr>
	                                                         <td>
	                                                            <li>
	                                                               <strong>Product Cost</strong>
	                                                            </li>
	                                                         </td>
	                                                         <td >
	                                                            <li>{{userdata.cost}}</li>
	                                                         </td>
	                                                      </tr> -->
	                                                      <tr>
						                                  <td>
						                                    <li>
						                                      <strong> Left ID </strong>
						                                    </li>
						                                  </td>
						                                  <td>
						                                    <li>{{userdata.l_c_count}} </li>
						                                  </td>
						                                </tr>
						                                <tr>
						                                  <td>
						                                    <li>
						                                      <strong> Right ID</strong>
						                                    </li>
						                                  </td>
						                                  <td>
						                                    <li>{{userdata.r_c_count}} </li>
						                                  </td>
						                                </tr>
						                                <tr>
						                                  <td>
						                                    <li>
						                                      <strong> Left Business</strong>
						                                    </li>
						                                  </td>
						                                  <td>
						                                    <li>{{userdata.l_bv}} </li>
						                                  </td>
						                                </tr>
						                                <tr>
						                                  <td>
						                                    <li>
						                                      <strong> Right Business</strong>
						                                    </li>
						                                  </td>
						                                  <td>
						                                    <li>{{userdata.r_bv}} </li>
						                                  </td>
						                                </tr>
						                                <tr>
						                                  <td>
						                                    <li>
						                                      <strong> Left Balance</strong>
						                                    </li>
						                                  </td>
						                                  <td>
						                                    <li>
						                                      <span v-if="userdata.left_bv!=null || userdata.left_bv!=null">{{userdata.left_bv}} </span>
						                                      <span v-if="userdata.left_bv==null || userdata.left_bv==null">N/A </span>
						                                    </li>
						                                  </td>
						                                </tr>
						                                <tr>
						                                  <td>
						                                    <li>
						                                      <strong> Right Balance</strong>
						                                    </li>
						                                  </td>
						                                  <td>
						                                    <li>
						                                      <span v-if="userdata.right_bv!=null || userdata.right_bv!=null">{{userdata.right_bv}} </span>
						                                      <span v-if="userdata.right_bv==null || userdata.right_bv==null">N/A </span>
						                                    </li>
						                                  </td>
						                                </tr>
						                                  <!-- <tr>
						                                  <td>
						                                    <li>
						                                      <strong> User Rank</strong>
						                                    </li>
						                                  </td>
						                                  <td>
						                                    <li>{{userdata.rank}} </li>
						                                  </td>
						                                </tr> -->
						                                <!-- <tr>
						                                  <td>
						                                    <li>
						                                      <strong>Repurchase Left BV</strong>
						                                    </li>
						                                  </td>
						                                  <td>
						                                    <li>
						                                      <span v-if="userdata.l_bv_rep !==''">{{userdata.l_bv_rep}} </span>
						                                      <span v-if="!userdata.l_bv_rep">{{userdata.left_bv_rep}} </span>
						                                    </li>
						                                  </td>
						                                </tr>
						                                <tr>
						                                  <td>
						                                    <li>
						                                      <strong>Repurchase Right BV</strong>
						                                    </li>
						                                  </td>
						                                  <td>
						                                    <li>
						                                      <span v-if="userdata.r_bv_rep !==''">{{userdata.r_bv_rep}}</span>   
						                                      <span v-if="!userdata.r_bv_rep">{{userdata.right_bv_rep}}</span>   
						                                    </li>
						                                  </td>
						                                </tr> -->
	                                                   </tbody>
	                                                </table>
	                                             </ul>
	                                          </li>
	                                       </ul>
	                                    </div>
	                                    <div class="clearfix"></div>
	                                    <img height="40" :src="imgURL+'/images/line.png'" width="10">
	                                    <hr class="frst-hr">
	                                 </td>
	                              </tr>
	                           </tbody>
	                        </table>

	                        <div v-for="(levels, i) in usertreeview">
		                        <table align="center" border="0" class="margin-auto" width="80%">
		                        	<tbody>
		                              	<tr>
		                                 	<td align="center" v-for="(levelData, j) in levels.level" :style="{width:getWidth(levelData)}" :class="{'trd-img': !levelData.level != 1}">
		                                    	<img height="40" :src="imgURL+'/images/line.png'" width="10">
		                                    	<div class="clearfix"></div>
		                                    	<div class="section-notification">
		                                       		<ul class="nav" :id="'tootipId'+i+''+j">
		                                          		<li @mouseover="changeStyle($event)" @mouseleave="changeStyle($event)">
		                                             		<img class="img-a" width="40" :src="imgURL+'/images/'+levelData.image" @click="getMatrixTreeData(levelData.user_id)">
		                                             		<a class="tree-user-name"> {{levelData.user_id}}</a>
		                                             		<ul class="notifications" style="left: 34%;">
		                                                		<table style="width:100%">
		                                                   			<tbody>
		                                                      			<tr>
		                                                         			<td>
		                                                            			<li>
		                                                               				<strong>User Name</strong>
		                                                            			</li>
		                                                         			</td>
		                                                         			<td>
		                                                            			<li>{{levelData.user_id}}</li>
		                                                         			</td>
		                                                      			</tr>
		                                                      			<tr>
		                                                         			<td>
		                                                            			<li>
			                                                               			<strong> Fullname</strong>
		                                                            			</li>
		                                                         			</td>
			                                                         		<td>
			                                                            		<li>{{levelData.fullname}}</li>
			                                                         		</td>
			                                                      		</tr>
			                                                      		<tr>
			                                                         		<td>
			                                                            		<li>
			                                                               			<strong>Sponser User Name</strong>
			                                                            		</li>
			                                                         		</td>
			                                                         		<td>
			                                                            		<li>{{levelData.sponsor_id}}</li>
			                                                         		</td>
			                                                      		</tr>
			                                                      		<tr>
			                                                         		<td>
			                                                            		<li>
			                                                               			<strong>Date of joining</strong>
			                                                            		</li>
			                                                         		</td>
			                                                         		<td>
			                                                            		<li>{{levelData.entry_time | moment('YYYY-MM-DD')}}</li>
			                                                         		</td>
			                                                      		</tr>
			                                                      		<!-- <tr>
					                                                         <td>
					                                                            <li>
					                                                               <strong>Product Cost</strong>
					                                                            </li>
					                                                         </td>
					                                                         <td >
					                                                            <li>{{levelData.cost}}</li>
					                                                         </td>
					                                                      </tr> -->
			                                                      		<tr>
			                                                         		<td>
			                                                            		<li>
			                                                               			<strong>Left ID</strong>
			                                                            		</li>
			                                                         		</td>
			                                                         		<td>
			                                                            		<li>{{levelData.l_c_count}}</li>
			                                                         		</td>
			                                                      		</tr>
			                                                      		<tr>
			                                                         		<td>
			                                                            		<li>
			                                                               			<strong>Right ID</strong>
			                                                            		</li>
			                                                         		</td>
			                                                         		<td>
			                                                            		<li>{{levelData.r_c_count}}</li>
			                                                         		</td>
			                                                      		</tr>
			                                                      		<tr>
			                                                         		<td>
			                                                            		<li>
			                                                               			<strong>Left Business</strong>
			                                                            		</li>
			                                                         		</td>
			                                                         		<td>
			                                                            		<li>{{levelData.l_bv}}</li>
			                                                         		</td>
			                                                      		</tr>
			                                                      		<tr>
			                                                         		<td>
			                                                            		<li>
			                                                               			<strong>Right Business</strong>
			                                                            		</li>
			                                                         		</td>
			                                                         		<td>
			                                                            		<li>{{levelData.r_bv}}</li>
			                                                         		</td>
			                                                      		</tr>
			                                                      		<tr>
			                                                         		<td>
			                                                            		<li>
			                                                               			<strong>Left Balance</strong>
			                                                            		</li>
			                                                         		</td>
			                                                         		<td>
			                                                            		<li>{{levelData.left_bv}}</li>
			                                                         		</td>
			                                                      		</tr>
			                                                      		<tr>
			                                                         		<td>
			                                                            		<li>
			                                                               			<strong>Right Balance</strong>
			                                                            		</li>
			                                                         		</td>
			                                                         		<td>
			                                                            		<li>{{levelData.right_bv}}</li>
			                                                         		</td>
			                                                      		</tr>
			                                                      		  <!-- <tr>
						                                  <td>
						                                    <li>
						                                      <strong> User Rank</strong>
						                                    </li>
						                                  </td>
						                                  <td>
						                                    <li>{{levelData.rank}} </li>
						                                  </td>
						                                </tr> -->
			                                                      		<!-- <tr>
			                                                         		<td>
			                                                            		<li>
			                                                               			<strong>Repurchase Left BV</strong>
			                                                            		</li>
			                                                         		</td>
			                                                         		<td>
			                                                            		<li>{{levelData.left_bv_rep}}</li>
			                                                         		</td>
			                                                      		</tr>
			                                                      		<tr>
			                                                         		<td>
			                                                            		<li>
			                                                               			<strong>Repurchase Right BV</strong>
			                                                            		</li>
			                                                         		</td>
			                                                         		<td>
			                                                            		<li>{{levelData.right_bv_rep}}</li>
			                                                         		</td>
			                                                      		</tr> -->
			                                                   		</tbody>
			                                                	</table>

			                                                	<!-- <table style="width:100%" v-if="levelData.user_id=='Not Available'">
									                                <tr>
									                                  	<td>
									                                    	<li>
									                                      		<strong>Not Available</strong>
									                                    	</li>
									                                  	</td>
									                                </tr>
									                            </table> -->

			                                             	</ul>
			                                          	</li>
			                                       	</ul>
		                                    	</div>
		                                    	<div class="clearfix"></div>

		                                    	<img v-if="levelData.level != lengthOFlastlevelOfTree && levelData.level!= 'Not Available'" :src="imgURL+'/images/line.png'" width="10" height="40"/>
						                      	<hr v-if="levelData.level != lengthOFlastlevelOfTree && levelData.level!= 'Absent'" class="frst-hr"/>

		                                	</td>
		                            	</tr>
		                        	</tbody>
		                    	</table>
	                    	</div>

	                	</div>
	            	</div>
	            	<div class="row" style="background-color: #e7e7e7; padding :30px">
	                     <div class="col-md-3 col-lg-3 col-6 text-center grid-margin stretch-card">
	                        <div class="card">
	                           <a href="javascript:;">
	                              <div  class="card-body">
	                                 <div class="d-flex align-items-center justify-content-md-center">
	                                    <img class="img-fluid tree-img" :src="imgURL+'/images/active.png'" style="width: 50px;">
	                                 </div>
	                                 <div class="indicator">
	                                    <h6 > Active </h6>
	                                 </div>
	                              </div>
	                           </a>
	                        </div>
	                     </div>
	                     <div  class="col-md-3 col-lg-3 col-6 text-center grid-margin stretch-card">
	                        <div  class="card">
	                           <a  href="javascript:;">
	                              <div  class="card-body">
	                                 <div class="d-flex align-items-center justify-content-md-center">
	                                    <img  class="img-fluid tree-img" :src="imgURL+'/images/absent.png'" style="width: 50px;">
	                                 </div>
	                                 <div  class="indicator">
	                                    <h6 > Not Present </h6>
	                                 </div>
	                              </div>
	                           </a>
	                        </div>
	                     </div>
	                     <div  class="col-md-3 col-lg-3 col-6 text-center grid-margin stretch-card">
	                        <div  class="card">
	                           <a href="javascript:;">
	                              <div  class="card-body">
	                                 <div class="d-flex align-items-center justify-content-md-center">
	                                    <img class="img-fluid tree-img" :src="imgURL+'/images/block.png'" style="width: 50px;">
	                                 </div>
	                                 <div  class="indicator">
	                                    <h6> Block </h6>
	                                 </div>
	                              </div>
	                           </a>
	                        </div>
	                     </div>
	                     <div  class="col-md-3 col-lg-3 col-6 text-center grid-margin stretch-card">
	                        <div  class="card">
	                           <a href="javascript:;">
	                              <div  class="card-body">
	                                 <div  class="d-flex align-items-center justify-content-md-center">
	                                    <img  class="img-fluid tree-img" :src="imgURL+'/images/inactive.png'" style="width: 50px;">
	                                 </div>
	                                 <div  class="indicator">
	                                    <h6 > Not Paid </h6>
	                                 </div>
	                              </div>
	                           </a>
	                        </div>
	                     </div>
	            	</div>
	            </div>
	            </div>
	         </div>
	      </div>
	   </div>
	</div>
</template>

<script>
    import { apiAdminHost, adminAssets } from'./../../admin-config/config';
    import moment from 'moment';
    export default {
        data() {

            return {
				imgURL: adminAssets,
				user_id:'',
				userdata:{},
				usertreeview:[],
				lengthOFlastlevelOfTree:0,
				isAvialable:'',
				searchText:'',
				nameForBack:'',
				BackListArray:[],
				ForwordListArray:[],
				back_status:false,
				forward_status:false,
            }
        },
        mounted() {
        	this.getTreeViewManual();
        	  $("li").hover(
			  function () {
			    $(this).addClass("is-active");
			  },
			  function () {
			    $(this).removeClass("is-active");
			  }
			);
        	//this.getPools();//

        },
        methods: {
            getTreeViewManual(){
                axios.post('/getlevelviewtree/productbase',{
                    id:this.user_id,
                    
                }).then(resp => {
					if(resp.data.code === 200) {
						this.userdata = resp.data.data.user;
						this.usertreeview = resp.data.data.tree_data;
						this.lengthOFlastlevelOfTree = this.usertreeview[this.usertreeview.length - 1].level[this.usertreeview[this.usertreeview.length - 1].level.length - 1].level;

						if(!this.back_status){
							this.nameForBack = this.userdata.user_id;
			                if (this.BackListArray[this.BackListArray.length - 1] !== this.nameForBack) 
			                {
			                  this.BackListArray.push(this.nameForBack);
			                }
			                this.back_status = false;
						}
					}
                }).catch(err => {
                	this.$toaster.error(err);
                })
            },
            getWidth(data) {
			    switch (data.level) {
			      	case 1: return '50%';
			      	case 2: return '25%';
			    }
			},
			changeStyle($event) {
			    $('.nav li').hover(function () {
			      	$(this).addClass('is-active').siblings().removeClass('is-active');
			    });

			    $('.nav li').mouseleave(function () {
			      	$(this).removeClass('is-active');
			    });
			},
			getMatrixTreeData(id) {				
				this.user_id = id;
			    this.getTreeViewManual();
			},
			onSearchClick() {
			    this.isAvialable = '';
			    if (this.searchText === '') {
			      	this.isAvialable = '';
			    } else {
			      	this.user_id = this.searchText;
			    	this.getTreeViewManual();
			    }
			},
			onBackClick() {
				
			    if (this.BackListArray.length === 1) {
			    	this.back_status = false;
			    	this.$toaster.error('You are on root, you can not back');
			    } else {
			    	this.back_status = true;
			      	const lengthOfArray = this.BackListArray.length;
			      	const userIdForBack = this.BackListArray[lengthOfArray - 2];
			      	const userIdForForword = this.BackListArray[lengthOfArray - 1];
			      	this.getMatrixTreeData(userIdForBack);
			      	this.BackListArray.splice(lengthOfArray - 1, 1);
			      	this.ForwordListArray.push(userIdForForword);
			    }
			    this.searchText = '';
			    this.isAvialable = '';
			},
			onForwardClick() {
				
			    if (this.ForwordListArray.length === 0) {
			    	this.forward_status = false;
			    	this.$toaster.error('You are on root, you can not back');
			    } else {
			    	this.forward_status = true;
			      	const lengthOfArray = this.ForwordListArray.length;

			      	/*if(lengthOfArray > 1){
			      		const userIdForForword = this.ForwordListArray[lengthOfArray - 2];
			      		this.getMatrixTreeData(this.ForwordListArray[lengthOfArray - 1]);
			      	} else {
			      		const userIdForForword = this.ForwordListArray[lengthOfArray - 1];
			      		this.getMatrixTreeData(this.ForwordListArray[lengthOfArray - 1]);
			      	}*/
			      	const userIdForBack = this.ForwordListArray[lengthOfArray - 1];
			      	//this.getMatrixTreeData(userIdForForword);
			      	this.getMatrixTreeData(this.ForwordListArray[lengthOfArray - 1]);
			      	this.ForwordListArray.splice(lengthOfArray - 1, 1);
			      	this.BackListArray.push(userIdForBack);
			    }
			    this.searchText = '';
			    this.isAvialable = '';
			},
			checkUserExisted(){
				axios.post('/checkuserexist',{
                    user_id: this.searchText,
                }).then(resp => {
                    if(resp.data.code === 200){
                    	this.isAvialable = 'Available';
                    } else {
                    	this.isAvialable = 'Not Available';
                    }
                }).catch(err => {
                	this.$toaster.error(err);
                })
			}
        }
    }
</script>	