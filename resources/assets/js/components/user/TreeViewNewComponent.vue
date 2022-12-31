<template>
  <div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
      <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h2 class="content-header-title float-left mb-0">Tree View New</h2>
            </div>
          </div>
        </div>
        <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
          <div class="form-group breadcrum-right">
            <div class="dropdown">
              <button
                class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle"
                type="button"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="feather icon-settings"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#">Chat</a>
                <a class="dropdown-item" href="#">Email</a>
                <a class="dropdown-item" href="#">Calendar</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="content-body">
        <section id="multiple-column-form">
          <div class="row match-height">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <!-- <h4 class="card-title">Edit Profile</h4> -->
                </div>
                <div class="card-content">
                  <div class="card-body">
                      <div class="right_bar">
    <div class="container-fluid">
        <div class="white_box mt-3 mb-3">
             
            </div>
             </div>
        </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</template>



<script>
import Breadcrum from "./BreadcrumComponent.vue";
import { apiUserHost, userAssets } from "./../../user-config/config";
import moment from "moment";
export default {
  components: {
    Breadcrum
  },
  data() {
    return {
      imgURL: userAssets,
      user_id: "",
      userdata: {},
      usertreeview: [],
      lengthOFlastlevelOfTree: 0,
      isAvialable: "",
      searchText: "",
      nameForBack: "",
      BackListArray: [],
      ForwordListArray: [],
      back_status: false,
      forward_status: false,
      usersleft:'',
      usersright:'',
      todayleft:'',
      todayright:'',
      usersrightcounts:'',
      usersleftcounts:'',
      userdata_for_rank:{}
      
    };
  },
  mounted() {
    this.getUserDetails();
    this.getAmount();
    this.get_todays_rank_count();
    this.getTreeViewManual();
    $("li").hover(
      function() {
        $(this).addClass("is-active");
      },
      function() {
        $(this).removeClass("is-active");
      }
    );
    //this.getPools();
  },
  methods: {
     getUserDetails() {
      axios
        .get("get-user-dashboard", {})
        .then(response => {
          this.userdata_for_rank = response.data.data;
          //this.referrallink = response.data.data;
         // $("#rank").html(this.userdata.rank);
          

        })
        .catch(error => {});
    },
    getAmount(){
      axios
        .get("/getrank")
        .then(resp => {
          if (resp.data.code === 200) {
            console.log(resp);
            this.all_rank_data = resp.data.data[0];
            // this.usersleft = resp.data.data.usersleft;
            // this.usersright = resp.data.data.usersright;
            // this.usersrightcounts = resp.data.data.usersrightcounts;
            // this.usersleftcounts = resp.data.data.usersleftcounts;
            // this.todayleft = resp.data.data.todayleft;
            // this.todayright = resp.data.data.todayright;
          }
        })
        .catch({});
    },
     get_todays_rank_count(){
      axios
        .get("/getAmount")
        .then(resp => {
          if (resp.data.code === 200) {
            console.log(resp);
            
           // this.all_rank_data = resp.data.data[0];
            this.usersleft = resp.data.data.usersleft;
            this.usersright = resp.data.data.usersright;
            this.usersrightcounts = resp.data.data.usersrightcounts;
            this.usersleftcounts = resp.data.data.usersleftcounts;
            this.todayleft = resp.data.data.todayleft;
            this.todayright = resp.data.data.todayright;
          }
        })
        .catch({});
    },
    getTreeViewManual() {
      axios
        .post("/getlevelviewtree/productbase", {
          id: this.user_id,
          reqFrom:'web'
        })
        .then(resp => {
          if (resp.data.code === 200) {
            this.userdata = resp.data.data.user;
            this.usertreeview = resp.data.data.tree_data;
            this.lengthOFlastlevelOfTree = this.usertreeview[
              this.usertreeview.length - 1
            ].level[
              this.usertreeview[this.usertreeview.length - 1].level.length - 1
            ].level;

            if (!this.back_status) {
              this.nameForBack = this.userdata.user_id;
              if (
                this.BackListArray[this.BackListArray.length - 1] !==
                this.nameForBack
              ) {
                this.BackListArray.push(this.nameForBack);
              }
              this.back_status = false;
            }
          }
        })
        .catch(err => {
          this.$toaster.error(err);
        });
    },
    getWidth(data) {
      switch (data.level) {
        case 1:
          return "50%";
        case 2:
          return "25%";
      }
    },
    changeStyle($event) {
      $(".nav li").hover(function() {
        $(this)
          .addClass("is-active")
          .siblings()
          .removeClass("is-active");
      });

      $(".nav li").mouseleave(function() {
        $(this).removeClass("is-active");
      });
    },
    getMatrixTreeData(id) {
      this.user_id = id;
      this.getTreeViewManual();
    },
    onSearchClick() {
      this.isAvialable = "";
      if (this.searchText === "") {
        this.isAvialable = "";
      } else {
        this.user_id = this.searchText;
        this.getTreeViewManual();
      }
    },
    onBackClick() {
      if (this.BackListArray.length === 1) {
        this.back_status = false;
        this.$toaster.error("You are on root, you can not back");
      } else {
        this.back_status = true;
        const lengthOfArray = this.BackListArray.length;
        const userIdForBack = this.BackListArray[lengthOfArray - 2];
        const userIdForForword = this.BackListArray[lengthOfArray - 1];
        this.getMatrixTreeData(userIdForBack);
        this.BackListArray.splice(lengthOfArray - 1, 1);
        this.ForwordListArray.push(userIdForForword);
      }
      this.searchText = "";
      this.isAvialable = "";
    },
    onForwardClick() {
      if (this.ForwordListArray.length === 0) {
        this.forward_status = false;
        this.$toaster.error("You are on root, you can not back");
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
      this.searchText = "";
      this.isAvialable = "";
    },
    checkUserExisted() {
      axios
        .post("/checkuserexist/crossleg", {
          user_id: this.searchText
        })
        .then(resp => {
          if (resp.data.code === 200) {
            this.isAvialable = "Available";
          } else {
            this.isAvialable = "NA";
          }
        })
        .catch(err => {
          this.$toaster.error(err);
        });
    },
    registerUser(position, current_level, current_placement) {
      //console.log("Position : " + position);
      // console.log("Virtual : " + virtual_id);
      // console.log("Sponser : " + sponsor_id);
      // console.log("Current Placement Position : " + current_placement);
      //console.log(this.usertreeview);
      // fetch placement id
      let placement_level = current_level - 2;
      if (placement_level < 0) {
        this.$root.placement_user_id = this.userdata.user_id;
      } else {
        //console.log("Placement Level : " + placement_level);
        let fetchposition = current_placement < 2 ? 0 : 1;
        //console.log("Fetch Position : " + fetchposition);
        let sponseruserinfo = this.usertreeview[placement_level].level[
          fetchposition
        ];
        //console.log(this.virtual_parent_id );
        //let virtual_id = sponseruserinfo.user_id
        this.$root.placement_user_id = sponseruserinfo.user_id;
        /*this.$root.position  = position;
					this.$router.push({name: 'register-user'});*/
        //console.log(JSON.stringify(sponseruserinfo));
        //console.log("Virtual : " + virtual_id);
      }
      if (
        this.$root.placement_user_id != undefined &&
        this.$root.placement_user_id != "Not Available"
      ) {
        this.$root.position = position;
        this.$router.push({
          name: "register-user"
        });
      }
      //console.log("Register");
    }
  }
};
</script>