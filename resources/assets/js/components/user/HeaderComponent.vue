<template>
  <div
    class="horizontal-layout horizontal-menu dark-layout 2-columns navbar-floating footer-static"
    data-open="hover"
    data-menu="horizontal-menu"
    data-col="2-columns"
    data-layout="dark-layout"
  >
    <!-- BEGIN: Header-->
    <nav
      class="header-navbar navbar-expand-lg navbar navbar-with-menu navbar-fixed navbar-brand-center"
    >
      <div class="navbar-header d-xl-block d-none">
        <ul class="nav navbar-nav flex-row">
          <li class="nav-item">
            <a class="navbar-brand" href="index.php">
              <div class="brand-logo"></div>
            </a>
          </li>
        </ul>
      </div>
      <div class="navbar-wrapper">
        <div class="navbar-container content">
          <div class="navbar-collapse" id="navbar-mobile">
            <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
              <ul class="nav navbar-nav">
                <li class="nav-item mobile-menu d-xl-none mr-auto">
                  <a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#">
                    <i class="ficon feather icon-menu"></i>
                  </a>
                </li>
              </ul>
              <ul class="nav navbar-nav bookmark-icons">
                <li class="nav-item d-none d-lg-block">
                  <a class="nav-link" href="#" data-toggle="tooltip" data-placement="top" title="Chat" >
                    <i class="ficon feather icon-message-square"></i>
                  </a>
                </li>
                <li class="nav-item d-none d-lg-block">
                  <a class="nav-link" href="#"  data-toggle="tooltip"  data-placement="top" title="Messages" >
                    <i class="ficon feather icon-mail"></i>
                  </a>
                </li>
              </ul>
            </div>
            <ul class="nav navbar-nav float-right">
              <!--       <li class="dropdown dropdown-language nav-item">
                <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="flag-icon flag-icon-us"></i>
                  <span class="selected-language">English</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdown-flag">
                  <a class="dropdown-item" href="#" data-language="en">
                    <i class="flag-icon flag-icon-us"></i> English
                  </a>
                  <a class="dropdown-item" href="#" data-language="fr">
                    <i class="flag-icon flag-icon-fr"></i> French
                  </a>
                  <a class="dropdown-item" href="#" data-language="de">
                    <i class="flag-icon flag-icon-de"></i> German
                  </a>
                  <a class="dropdown-item" href="#" data-language="pt"><i class="flag-icon flag-icon-pt"></i> Portuguese
                  </a>
                </div>
              </li>-->
              <li class="nav-item d-none d-lg-block">
                <a class="nav-link nav-link-expand">
                  <i class="ficon feather icon-maximize"></i>
                </a>
              </li>
              <li class="nav-item nav-search">
                <a class="nav-link nav-link-search">
                  <i class="ficon feather icon-search"></i>
                </a>
                <div class="search-input">
                  <div class="search-input-icon">
                    <i class="feather icon-search primary"></i>
                  </div>
                  <input
                    class="input"
                    type="text"
                    placeholder="Explore..."
                    tabindex="-1"
                    data-search="template-list"
                  />
                  <div class="search-input-close">
                    <i class="feather icon-x"></i>
                  </div>
                  <ul class="search-list search-list-main"></ul>
                </div>
              </li>
              <li class="dropdown dropdown-user nav-item">
                <a
                  class="dropdown-toggle nav-link dropdown-user-link"
                  href="#"
                  data-toggle="dropdown"
                >
                  <div class="user-nav d-sm-flex d-none">
                    <span class="user-name text-bold-600">John Doe</span>
                    <span class="user-status">Available</span>
                  </div>
                  <span>
                    <img
                      class="round"
                      src="assets/images/avatar-s-11.jpg"
                      alt="avatar"
                      height="40"
                      width="40"
                    />
                  </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                  <!-- <a class="dropdown-item" href="my-profile.php">
                  <i class="feather icon-user"></i>Profile </a>
                  <a class="dropdown-item" href="edit-profile.php">
                  <i class="feather icon-mail"></i> Edit Profile</a>
                  <a class="dropdown-item" href="change-password.php">
                  <i class="feather icon-check-square"></i> Change Password</a>-->
                  <!-- <a class="dropdown-item" href="app-chat.html">
                  <i class="feather icon-message-square"></i> Chats</a>-->
                  <!--    <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="login.php">
                  <i class="feather icon-power"></i> Logout</a>-->
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
    
  </div>
</template>

<script>
export default {
  data() {
    return {
      userdetails: {
        user_id: "",
        ip_address: "",
        current_time: ""
      },
      server_time: "",
      icon: "../public/user_files/assets/img/small-icon.png",
      logo: "../public/user_files/assets/img/loginLogo.png",
      referrallink: "",
      ip_addr: ""
    };
  },
  mounted() {
    //this.getUserLoginDetails();
    //this.getReferralLink();
    //this.getIpAddress();
  },
  methods: {
    getUserLoginDetails() {
      axios
        .get("get-user-dashboard", {})
        .then(response => {
          //console.log(response);
          this.userdetails = response.data.data;
          // alert(this.userdetails.fullname);
          this.server_time = response.data.data.server_time.date;
        })
        .catch(error => {});
    },
    logout() {
      localStorage.removeItem("user-token");
      localStorage.removeItem("INR");
      localStorage.removeItem("p_type");
      localStorage.removeItem("type");
      // if the request fails, remove any
      //location.reload();
      this.$router.push({ name: "login" });
    },
    getReferralLink() {
      axios
        .get("/get-reference-id")
        .then(resp => {
          if (resp.data.code === 200) {
            this.referrallink = resp.data.data;
          }
        })
        .catch({});
    },
    getIpAddress() {
      axios
        .post("/get-ip-addr")
        .then(resp => {
          if (resp.data.code === 200) {
            this.ip_addr = resp.data.data;
          } else {
            this.ip_addr = "-";
          }
        })
        .catch({});
    }
  }
};
</script>