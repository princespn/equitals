<template>
  <div v-if="token">
    <div id="container" class="">
      <!-- <topbar></topbar> -->
      <div class="boxed">
       
             <navlink v-if="!isregister"></navlink>
        </div>
        <!--    <transition name="fade"> -->
        <router-view></router-view>
        <!--    </transition> -->
      </div>

       <div v-if="!isregister" class="footer">
    
            <div class="copyright">
                <p>Copyright © Designed &amp; Developed by <a href="www.equitals.com" target="_blank">Equitals</a> 2022</p>
            </div>
        </div>

      <!-- <footer v-if="!isregister" class="footer footer-static footer-dark navbar-shadow">
        <p class="clearfix blue-grey lighten-2 mb-0 text-center">
          <span class="text-center d-block d-md-inline-block mt-25">
            Copyrights &copy;{{objProSettings.copyright_at}}  
            <a
              class="text-bold-800 grey darken-2"
              href="#"
              target="_blank"
            >{{objProSettings.project_name}},</a> All rights Reserved
          </span>
        </p>
      </footer> -->
      <button class="scroll-top btn">
        <i class="pci-chevron chevron-up"></i>
      </button>
    </div>
  </div>
  <div v-else>
    <router-view></router-view>
  </div>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter,
.fade-leave-active {
  opacity: 0;
}
</style>
<script>
import topbar from "./../components/user/HeaderComponent.vue";
import navlink from "./../components/user/NavigationComponent.vue";

export default {
  data() {
    return {
      token: "",
      objProSettings: {},
      isregister:""
    };
  },
  components: {
    topbar,
    navlink
  },
  mounted() {
    this.token = localStorage.getItem("user-token");
    this.getProSettings();
     var routname = this.$route.name;
    if (routname == 'register' || routname == 'thankyou' || routname == 'currencyAddress') {
      this.isregister=true;
    }
  },
  methods: {
    getProSettings() {
      axios
        .get("/getprojectsettings")
        .then(resp => {
          if (resp.data.code === 200) {
            this.objProSettings = resp.data.data;
          }
        })
        .catch(err => {});
    }
  }
};
</script>