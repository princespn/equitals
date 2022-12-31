<template>
    <!-- begin wrapper -->
    <div id="wrapper" v-if="token">
        <topbar></topbar>
        <navlink></navlink>
        <div class="content-page">
            
            <!-- <transition name="fade"> -->
            <router-view></router-view>
            <!-- </transition> -->

            <!-- start footer -->
            <footer class="footer">©{{objProSettings.copyright_at}} {{objProSettings.project_name}} Admin Panel</footer>
            <!-- end footer -->
        </div>
    </div>
    <!-- end wrapper -->
    <div v-else>
        <!-- <transition name="fade"> -->
        <router-view></router-view>
        <!-- </transition> -->
    </div>
</template>

<script>
    import topbar from './../components/admin/TopbarComponent.vue';
    import navlink from './../components/admin/NavigationComponent.vue';
    
    export default {
        data(){
            return {
                token:'',
                objProSettings:{}
            }
        },
        components:{
            topbar,
            navlink,
        },
        mounted() { 
            this.token = localStorage.getItem('access_token');
            this.getProSettings();
        },
        methods: {
            getProSettings(){
                axios.get('/getprojectsettings')
                .then(resp => {
                    if(resp.data.code === 200){
                        this.objProSettings = resp.data.data;
                    }
                }).catch(err => {
                })
            }
        }
    }
</script>