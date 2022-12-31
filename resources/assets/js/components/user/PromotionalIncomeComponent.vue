<template>
    <div>
    <div class="page-content">
      <div class="container-fluid">
    

        <section class="card">
          <div class="card-block only-sec">
            

            <h5 class="with-border m-t-lg text-center">Promotional</h5>

            <div class="row">
              <div class="col-lg-7 offset-lg-2">
                <fieldset class="form-group">
                  <label class="form-label" for="exampleInput">Subject</label>
                  <input type="text" class="form-control maxlength-simple" v-model="subject"  name="subject" v-validate="'required'">
                  <div class="tooltip2" v-show="errors.has('subject')">
                  <div class="tooltip-inner">
                     <span v-show="errors.has('subject')">{{ errors.first('subject') }}</span>
                  </div>
               </div>
                  
                </fieldset>
              </div>


              <div class="col-lg-7 offset-lg-2">
                <fieldset class="form-group">
                  <label class="form-label" for="exampleInput">Link</label>
                  <input type="text" class="form-control maxlength-simple" v-model="link" name="link" v-validate="'required'">
                  <div class="tooltip2" v-show="errors.has('link')">
                  <div class="tooltip-inner">
                     <span v-show="errors.has('link')">{{ errors.first('link') }}</span>
                  </div>
               </div>
                  
                </fieldset>
              </div
              </div>
            
            </div>




            <div class="row">
              <div class="col-md-12 col-sm-12 text-center">
                <button :disabled="disablebtn == true || errors.any() || !isCompletePromotions" class="btn btn-rounded btn-primary-outline" @click="sendLink">Submit</button>
              </div>
            </div>
          </div>
        </section>
      </div><!--.container-fluid-->
    </div>
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
             return {
             disablebtn:false,
             subject:'',
             link:'',

         }

        },
        computed: {
            isCompletePromotions() {
                return this.subject && this.link;      
            }
        },
        created(){
           // this.getPackages();
        },
        mounted(){
          //  this.getBalance(); 
          //this.getBTCAddress(); 
        },
        methods:{
            
            sendLink(){
               this.disablebtn = true;
               axios.post('store/promotional',{
                subject:this.subject,
                link:this.link,
                
               })
                .then(response => {
                    if(response.data.code == 200){
                       //
                       this.disablebtn = false;
                       this.$toaster.success(response.data.message);
                       this.$router.push({path:'promotional-report'})
                       
                    } else {
                        this.disablebtn = false;
                        this.$toaster.error(response.data.message);
                       
                    }
              })

            }           
        }
    }
</script>