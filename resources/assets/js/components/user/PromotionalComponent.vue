<template>
	<div>
		<!--CONTENT CONTAINER-->
		<!--===================================================-->
		<div id="content-container">
			<Breadcrum></Breadcrum>
			<!--Page content-->
			<!--===================================================-->
			<div id="page-content">
				<hr class="new-section-sm bord-no">
				<div class="row">
					<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
						<div class="panel panel-body">
							<div class="panel-heading">
								<h3 class="text-center">Add Promotional</h3>
							</div>
							<div class="row">
								<div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12">
									
									<div class="col-sm-12 bg-gray">
										<div class="panel bg-gray">
											<!--Block Styleda Form -->
											<!--===================================================-->
											<form @submit.prevent="addPromotional" id="addPromotional">
												<div class="panel-body">
													<div class="row">
														<div class="col-sm-12">
															<div class="form-group">
																<label class="control-label">Promotional Type</label>
																<select name="promotional_type_id" class="form-control" v-model="promotional.promotional_type_id" id="promotional_type_id" v-validate="'required'">
																	<option :value="null">Select</option>
		                                                       		<option v-for="promotional in arrPromotional" :value="promotional.srno"> {{ promotional.promotional_name }}</option>
		                                                       	</select>
																<div class="tooltip2" v-show="errors.has('promotional_type_id')">
							                                        <div class="tooltip-inner">
							                                           <span v-show="errors.has('promotional_type_id')">{{ errors.first('promotional_type_id') }}</span>
							                                        </div>
							                                    </div>
															</div>
														</div>
														<div class="col-sm-12">
															<div class="form-group">
																<label class="control-label">Link</label>
																<input type="url" class="form-control" name="link" v-model="promotional.link" v-validate="'required|url:require_protocol'" placeholder="Enter link">
																<div class="tooltip2" v-show="errors.has('link')">
							                                        <div class="tooltip-inner">
							                                           <span v-show="errors.has('link')">{{ errors.first('link') }}</span>
							                                        </div>
							                                    </div>
															</div>
														</div>
														<div class="col-sm-12">
															<div class="form-group">
																<label class="control-label">Date</label>
																<DatePicker :bootstrap-styling="true" v-model="promotional.date" name="date" :format="dateFormat" placeholder="Date" id="date" v-validate="'required'" class="date-color-lg"></DatePicker>
																<div class="tooltip2" v-show="errors.has('date')">
							                                        <div class="tooltip-inner">
							                                           <span v-show="errors.has('date')">{{ errors.first('date') }}</span>
							                                        </div>
							                                    </div>
															</div>
														</div>
													</div>
												</div>
												<div class="panel-footer bg-gray text-center">
													<button class="btn btn-primary" type="submit" :disabled='!isCompletepromotional || errors.any() || !isDisabledBtn'>Submit</button>
												</div>
											</form>
											<!--===================================================-->
											<!--End Block Styled Form -->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--===================================================-->
				<!--End page content-->
			</div>
		</div>
		<!--===================================================-->
		<!--END CONTENT CONTAINER-->
	</div>
</template>
<script>
	import Breadcrum from './BreadcrumComponent.vue';
	import Swal from 'sweetalert2';
	import DatePicker from 'vuejs-datepicker';
	import moment from 'moment';

   	export default {  
      	components: {
         	Breadcrum,
         	DatePicker
      	}, 
      	data() {
         	return {
         		promotional: {
	               	promotional_type_id: null,
	               	date: '',
	               	link: '',
	            },
				arrPromotional:[],
				isDisabledBtn:true
         	}
        },
        computed:{
            isCompletepromotional(){
                return this.promotional.link && this.promotional.date && this.promotional.promotional_type_id;
            },
        },
	    mounted() {
	        this.getPromotionalTypes();
	    },
        methods: {
        	dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
        	addPromotional() { 
				this.isDisabledBtn = false;
        		axios.post('store/promotional', this.promotional).then(response => {
                	if(response.data.code === 200){
                        this.$toaster.success(response.data.message);
                        this.$router.push({name:'promotional-report'});
                    } else {
                       	this.$toaster.error(response.data.message);
                    }
                    $('#addPromotional').trigger('reset');
                }).catch(error => {

                });
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