<template>
	<!-- Start content -->
	<div class="content">
		<div class="">
          <div class="page-header-title">
              <h4 class="page-title">Update Withdraw Setting</h4>
          </div>
      </div>

      <div class="page-content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <div class="">
                                <form id="addFund">
                                    <div class="col-md-5 col-md-offset-3">
                                        <div class="form-group"> 
                                            <label>Withdrawal Status</label>
                                            <p>
                                                <label>                          
                                                    <input type="radio" name="withdraw_status" id="withdraw_status" v-model="withdraw_status" :value="'on'" :selected="withdraw_status=='on'">
                                                    <span>ON</span>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <input type="radio" name="withdraw_status" id="withdraw_status" v-model="withdraw_status" :value="'off'" :selected="withdraw_status=='off'">
                                                    <span>OFF</span>
                                                </label>
                                            </p>
                                        </div>

                                        <div class="form-group"> 
                                            <label>Withdrawal Error Narration</label>
                                            <textarea type="text" class="form-control" name="withdraw_off_msg" placeholder="Enter Narration" v-model="withdraw_off_msg" data-vv-as="withdraw_off_msg">{{withdraw_off_msg}}</textarea>
                                        </div>

                                        <div class="form-group"> 
                                            <label for="username">Withdrawal Day</label>
                                            <select name="withdraw_day" id="withdraw_day" class="form-control" v-model="withdraw_day">
                                                <option value="" selected disabled="">Select day</option>
                                                <option value="Sun">Sunday</option>
                                                <option value="Mon">Monday</option>
                                                <option value="Tue">Tuesday</option>
                                                <option value="Wed">Wednesday</option>
                                                <option value="Thu">Thursday</option>
                                                <option value="Fri">Friday</option>
                                                <option value="Sat">Saturday</option>
                                            </select>
                                        </div>

                                        <div class="form-group"> 
                                            <label for="username">Withdrawal Start Time</label>
                                            <select name="withdraw_start_time" id="withdraw_start_time" v-model="withdraw_start_time" class="form-control">
                                                <option value="" selected disabled>Select Time</option>
                                                <option value="0">00</option>
                                                <option value="1">01</option>
                                                <option value="2">02</option>
                                                <option value="3">03</option>
                                                <option value="4">04</option>
                                                <option value="5">05</option>
                                                <option value="6">06</option>
                                                <option value="7">07</option>
                                                <option value="8">08</option>
                                                <option value="9">09</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                                <option value="18">18</option>
                                                <option value="19">19</option>
                                                <option value="20">20</option>
                                                <option value="21">21</option>
                                                <option value="22">22</option>
                                                <option value="23">23</option>
                                                <option value="24">24</option>
                                            </select>
                                        </div>
                                        <div class="col-md-offset-5">
                                            <button type="button" class="btn btn-primary text-center" @click="update_withdraw_settings">
                                                Update
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div><!-- panel-body -->
                    </div><!-- panel -->
                </div><!-- col -->
            </div>
        </div>
    </div>
</div>
</template>

<script>
import { apiAdminHost } from'../../admin-config/config';
import Swal from 'sweetalert2';

export default {

    data() {
        return {
            withdraw_status:'',
            withdraw_day:'',
            withdraw_start_time:'',
            withdraw_off_msg:''
        }
    }, 
    mounted() {
        this.getProjectSetting();
    },
    methods: {
        getProjectSetting(){
            axios.get('getprojectsettings', {}).then(response => {
                var setting = response.data.data;
                this.withdraw_status = setting.withdraw_status;
                this.withdraw_start_time = setting.withdraw_start_time;
                this.withdraw_day = setting.withdraw_day;
                this.withdraw_off_msg = setting.withdraw_off_msg;
            }).catch(error => {
            }); 
        },
       update_withdraw_settings(){   
            this.$validator.validate().then(valid => {
                if (valid) {
                    axios.post('/update_withdraw_setting',{
                        withdraw_status : this.withdraw_status,
                        withdraw_start_time : this.withdraw_start_time,
                        withdraw_day : this.withdraw_day,
                        withdraw_off_msg : this.withdraw_off_msg
                    }).then(resp => {
                        if(resp.data.code === 200){
                            this.$toaster.success(resp.data.message);
                            this.getProjectSetting();
                        }else {
                            
                            this.$toaster.error(resp.data.message);
                        }
                   }).catch(err => {
                       this.$toaster.error(err);
                   })               
                }
            });
        },
    }
}
</script>