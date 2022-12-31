<template>
	<div class="content">
	    <div class="">
	        <div class="page-header-title">
	            <h4 class="page-title">Assign Rights</h4>
	        </div>
	    </div>
	    <div class="page-content-wrapper">
	        <div  class="container">
	            <div  class="row">
	                <div  class="col-lg-12">
	                    <div  class="panel  panel-primary">
	                        <div  class="panel-body">
	                            <div  class="row">
	                                <div  class="col-md-12">
	                                	<!-- <div class="row">
	                                		<input class="styled-checkbox" id="styled-checkbox-2" type="checkbox" value="value2" checked >
      										<label for="styled-checkbox-2">CSS Only</label>
	                                	</div> -->		
	                                    <div  class="row">
	                                        <div  class="col-md-6 col-md-offset-3 col-xs-12">
	                                            <div  class="form-group">
	                                                <label>Select Sub Admin</label>
	                                                <select class="form-control" name="department" v-model="selctedUser" @change="onSelectAdminClick">
	                                                    <option selected="" value="">Select SubAdmin</option>
	                                                    <option :value="subadmin.id" v-for="subadmin in arrSubadmins">{{subadmin.fullname}}</option>
	                                                </select>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <div class="row" v-if="selctedUser">
	                <div class="col-lg-12">
	                    <div class="panel panel-primary">
	                        <div class="panel-body">
	                            <div class="row">
	                                <div class="col-md-12">
	                                    <div class="row">
	                                        <div class="col-md-offset-3 col-md-6">
	                                            <div class="table-responsive">
	                                                <table  class="table table-striped table-bordered">
	                                                    <tbody>
								                            <tr>
								                              <td>
								                                <strong>Name</strong>
								                              </td>
								                              <td>{{subadminDetails.fullname}}</td>
								                            </tr>
								                            <tr>
								                              <td>
								                                <strong>Mobile No</strong>
								                              </td>
								                              <td>{{subadminDetails.mobile}}</td>
								                            </tr>
								                            <tr>
								                              <td>
								                                <strong>Email Id</strong>
								                              </td>
								                              <td>{{subadminDetails.email}}</td>
								                            </tr>
								                            <tr>
								                              <td>
								                                <strong>Sub Admin ID</strong>
								                              </td>
								                              <td>{{subadminDetails.user_id}}</td>
								                            </tr>
								                          </tbody>
	                                                </table>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>

	                        </div>
	                    </div>
	                </div>
	            </div>
	            <div class="row" v-if="selctedUser">
	                <div  class="col-lg-12">
	                    <div  class="panel panel-primary">
	                        <div  class="panel-body masonry">
	                            <form>
	                            	<div class="row">
						                <div class="panel panel-color panel-primary" v-for="ParentData of arrSubadminsNavigations">
						                    <div class="panel-heading">
						                      	<h3 class="panel-title">
						                      		{{ParentData.parentmenu.parent_menu}}
						                      	</h3>
						                    </div>
						                    <div class="panel-body panel-border"  v-if="ParentData.childmenu.length > 0">
						                      <div class="form-group" v-for="ChildData of ParentData.childmenu">

						                        <div class="checkbox checkbox-primary">

						                          <!-- <input id="Dashboard" type="checkbox" (change)="onChange(ChildData.id, $event.target.checked)" [attr.checked]="(ChildData.is_assign=='Yes')? 'checked' : null">
						                          <label for="Dashboard">
						                            {{ChildData.menu}}
						                          </label> -->

						                          <input name="arrNavigationId[]" type="checkbox" :checked="(ChildData.is_assign == 'Yes' ? 'checked':'')" :value="ChildData.id" checked>
						                          <label>
						                            {{ChildData.menu}}
						                          </label>
						                        </div>

						                      </div>
						                    </div>
						                  </div>
						            </div>
	                            </form>
	                            <div class="text-center">
	                                <button class="btn btn-primary waves-effect waves-light" type="button" @click="updateAssignRights">Update Rights</button>
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
    export default {
        data(){
           	return {
              	arrSubadmins:[],
              	arrSubadminsNavigations:[],
              	arrNavigationId:[],
              	selctedUser:'',
              	subadminDetails:{},
            }
        },
        mounted() {
        	this.getSubadmins();
        },	
        methods: {
            getSubadmins(){
            	axios.get('/getsubadmins')
            	.then(resp => {
                	if(resp.data.code === 200){
                		this.arrSubadmins = resp.data.data;
                	}
                }).catch(err => {
                	this.$toaster.success(err);
                })
            },
            onSelectAdminClick(){
            	for(let i = 0; i < this.arrSubadmins.length; i++) {
			      	if (this.arrSubadmins[i].id == this.selctedUser) {
			        	this.subadminDetails = this.arrSubadmins[i];
			        	break;
			      	}
			    }
			    this.getSubadminNavigation();
            },
            getSubadminNavigation(){
            	axios.post('/getsubadminnavigation',{
            		id:this.selctedUser,
            	}).then(resp => {
                	if(resp.data.code === 200){
                		this.arrSubadminsNavigations = resp.data.data.navigations;

                		/*Array.prototype.forEach.call(this.arrSubadminsNavigations, parent => {
			              Array.prototype.forEach.call(parent.childmenu, child => {
			                if (child.is_assign == 'Yes') {
			                  this.onChange(child.id, true);
			                }
			              });
			            });*/
                	} else {
                		this.$toaster.error(resp.data.message);
                	}
                }).catch(err => {
                	this.$toaster.error(err);
                })
            },
            updateAssignRights(){
            	var arrNavigationId = $("input[name='arrNavigationId[]']:checked").map(function() {
				    return (parseInt($(this).val()));
				}).get().join();
				
            	axios.post('/assignrights',{
            		navigations: { arrData: arrNavigationId }, 
            		id: this.selctedUser
            	}).then(resp => {
                	if(resp.data.code === 200){
                		this.$toaster.success(resp.data.message);
                	} else {
                		this.$toaster.error(resp.data.message);
                	}
                }).catch(err => {
                	this.$toaster.error(err);
                })
            }       
        }
    }
</script>