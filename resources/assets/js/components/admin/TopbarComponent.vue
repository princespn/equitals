<template>
	<!-- top bar start -->
    <div class="topbar">
        <!-- LOGO -->
        <div class="topbar-left">
            <!--<a href="index.php" class="logo"><span>Up</span>Bond</a>-->
            <!--<a href="index.php" class="logo-sm"><span>U</span></a>-->
            <a class="logo">
                <img :src="adminAssetsURL+'/images/logo-w.png'" height="50" alt="logo">
            </a>
            <a class="logo-sm">
               <!--  <img :src="adminAssetsURL+'/images/logo_sm.png'" height="30" alt="logo"> -->
               <!--  <img :src="adminAssetsURL+'/images/logo.png'" height="35" alt="logo"> -->
            </a>
            <a class="logo-sm">
                <img :src="adminAssetsURL+'/images/logo.png'" height="56" alt="logo">
            </a>
        </div>
        <!-- Button mobile view to collapse sidebar menu -->
        <div class="navbar navbar-default" role="navigation">
            <div class="container">
                <div class="">
                    <div class="pull-left">
                        <button type="button" class="button-menu-mobile open-left waves-effect waves-light">
                            <i class="ion-navicon"></i>
                        </button>
                        <span class="clearfix"></span>
                    </div>
                    <ul class="nav navbar-nav navbar-right pull-right server hidden-xs">
                        <li class="header-icon dib logoutBtn logout-p-r-50">
                            <span class="btn btn-primary" @click="logout">Logout</span>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right pull-right server">
                        <!-- <li class="fortopip bg-white red-1 ">
                            <h6>Server Time</h6>
                            <p>{{ timeZone.server_time | moment("YYYY-MM-DD hh:mm:ss") }}</p>
                        </li> -->
                         <li class="fortopip bg-white red-1">
                            <h6>Address Balance</h6>
                            <p>{{ timeZone.address_balance}}</p>
                        </li>
                        <li class="fortopip bg-white green ">
                            <h6>Current Time</h6>
                            <p id="ct">{{ timeZone.current_time.date | moment("YYYY-MM-DD hh:mm:ss") }}</p>
                        </li>
                        <li class="fortopip bg-white yellow ">
                            <h6>IP-Address</h6>
                            <p>{{ timeZone.ip_address }}</p>
                        </li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>
    </div>            
            <!-- top bar end -->
</template>

<script>
    import { adminAssets } from'./../../admin-config/config';   
    export default {
        data() {
            return {
                adminAssetsURL:adminAssets,
                timeZone:{
                    server_time:'',
                    current_time:{
                        date:''
                    },
                    ip_address:''
                }
            }
        },
        mounted() {
            this.getAdminLoginDetails();
        },
        methods: {
            
            getAdminLoginDetails(){

                axios.get('/getAdminLoginDetails')
                .then(resp => {
                    this.timeZone = resp.data.data;                    
                }).catch(err => {
                })
            },
            
            logout(){
                axios.post('/logout',{

                }).then(resp => {
                    if(resp.data.code === 200){
                        localStorage.removeItem('access_token');
                        location.reload();
                        this.$router.push({ path:'login'});
                    }
                    localStorage.removeItem('access_token');
                    location.reload();
                    this.$router.push({ path:'login'});
                }).catch(err => {
                })  
            }
        }
    }
</script>