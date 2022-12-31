
<style>
   @media screen and (max-width: 500px){
 .content-body {
    overflow: auto;
    background: white;
    box-shadow: 0px 5px 5px 0px rgb(82 63 105 / 5%);
}
.card {
    background: transparent!important;
    box-shadow: inherit;
}
   }
</style>

<template>
  <div class="content-body">
   <div class="container-fluid">
      <div class="row page-titles">
         <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Members</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Tree View</a></li>
         </ol>
      </div>
      <!-- row -->
      <div class="row">
         <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                  <h4 class="card-title">Member Structure</h4>
               </div>
               <div class="card-body">
                   <div class="container">
                       
                        <div class="row introscreen justify-content-center">
                            <div class="col-md-4">
                                <div class="d-flex">
                                    <div>
                                        <form class="input-group flex-nowrap"  @submit.prevent="onSearchClick">
                                        <input type="text" class="form-control-search" v-model="searchText"      v-on:keyup="checkUserExisted" name="search" placeholder="Search..." autocomplete="off">
                                        <span class="input-group-text" id="addon-wrapping">
                                            <a href="javascript:void(0)" @click="onSearchClick">
                                          <i class="fa fa-search"></i></a>
                                        </span>
                                    </form>
                                    <span
                                        :class="{'text-success': isAvialable == 'Available','text-danger': isAvialable == 'Not Available'}" v-if="isAvialable!=''">{{isAvialable}}
                                    </span>  
                                     
                                    </div>
                                    <button class="btn btntree" @click="onBackClick" style="padding: 3px;cursor: pointer;">
                                        <i class="fa fa-arrow-left"></i>
                                    </button>
                                    <button class="btn btntree" @click="onForwardClick">
                                        <i class="fa fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4">
                               <div class="introlist">
                                <ul>
                                    <li>
                                        <img src="public/user_files/assets/images/tree-view/active-i.png">
                                        <span>Active</span>
                                    </li>
                                    <li>
                                        <img src="public/user_files/assets/images/tree-view/inactive-i.png">
                                        <span>Inactive</span>
                                    </li>
                                    <li>
                                        <img src="public/user_files/assets/images/tree-view/absent-i.png">
                                        <span>Absent</span>
                                    </li>
                                </ul>
                            </div>
                            </div>
                        </div>
                        <div class="XentoTree">
                            <div class="row midline">
                                <div class="col-12">
                                    <div class="tree-level1">
                                        <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" id="myTooltip1" class="mytooltip">
                                            <img :src="imgURL+'/assets/images/tree-view/'+userdata.image">

                                            <div :class="'XentoTree-tooip-text '+userdata.cardclass">

                                                <div class="row">
                                                    <div class="col-3">
                                                        <img :src="imgURL+'/assets/images/tree-view/'+userdata.image">
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="tph">
                                                            <h1>{{userdata.user_id}}</h1>
                                                            <ul>
                                                                <li>
                                                                    <i class="fa fa-user ps-0"></i> {{userdata.fullname}}
                                                                </li>
                                                                <li>
                                                                    <i class="fa fa-calendar-alt"></i> Join: {{userdata.entry_time | moment('YYYY-MM-DD')}}
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-3 pe-0">
                                                        <div class="whiteBox">
                                                            <h2>
                                                                Package 
                                                                <span>{{ userdata.product_name }}</span>
                                                            </h2>
                                                        </div>
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="whiteBox">
                                                            <div>
                                                                <p>Sponsor Id : {{ userdata.sponsor_id }} </p>
                                                                <p>Placement Id : {{ userdata.virtual_parent_id }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="SelfT">
                                                    <p>Self Topup : {{ userdata.usertopup }}</p>
                                                </div>
                                                <table class="table whitetable">
                                                  <thead>
                                                    <tr>
                                                      <th scope="col">Factors</th>
                                                      <th scope="col">Left Side</th>
                                                      <th scope="col">Right Side</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                    <tr>
                                                      <th scope="row">Team</th>
                                                      <td>{{ userdata.l_c_count }}</td>
                                                      <td>{{ userdata.r_c_count }}</td>
                                                    </tr>
                                                    <tr>
                                                      <th scope="row">Business</th>
                                                      <td>{{ userdata.l_bv }}</td>
                                                      <td>{{ userdata.r_bv }}</td>
                                                    </tr>
                                                    <tr>
                                                      <th scope="row">Carry</th>
                                                      <td>{{ userdata.left_bv }}</td>
                                                      <td>{{ userdata.right_bv }}</td>
                                                    </tr>
                                                 
                                                  </tbody>
                                                </table>
                                            </div>
                                        </a>
                                        <h6>{{userdata.user_id}}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row midline VHline">
                                <div class="col-6 tree-2-1">
                                    <a href="javascript:void(0)" @click.prevent="getMatrixTreeData(usertreeview[0].level[0].user_id)" data-toggle="tooltip" data-placement="bottom" id="myTooltip1" class="mytooltip">
                                         <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[0].level[0].image">
                                        
                                       <!--  <div class="XentoTree-tooip-text absent-card"> -->
                                            <div :class="'XentoTree-tooip-text '+usertreeview[0].level[0].cardclass">
                                                <div class="row">
                                                    <div class="col-3">
                                                         <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[0].level[0].image">
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="tph">
                                                            <h1>{{usertreeview[0].level[0].user_id}}</h1>
                                                            <ul>
                                                                <li>
                                                                    <i class="fa fa-user ps-0"></i> {{usertreeview[0].level[0].fullname}}
                                                                </li>
                                                                <li>
                                                                    <i class="fa fa-calendar-alt"></i> Join: {{usertreeview[0].level[0].entry_time | moment('YYYY-MM-DD')}}
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-3 pe-0">
                                                        <div class="whiteBox">
                                                            <h2>
                                                                Package 
                                                                <span>{{usertreeview[0].level[0].product_name}}</span>
                                                            </h2>
                                                        </div>
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="whiteBox">
                                                            <div>
                                                                <p>Sponsor Id : {{usertreeview[0].level[0].sponsor_id}} </p>
                                                                <p>Placement Id : {{usertreeview[0].level[0].virtual_parent_id}}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="SelfT">
                                                    <p>Self Topup : {{usertreeview[0].level[0].selftopup}}</p>
                                                </div>
                                                <table class="table whitetable">
                                                  <thead>
                                                    <tr>
                                                      <th scope="col">Factors</th>
                                                      <th scope="col">Left Side</th>
                                                      <th scope="col">Right Side</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                    <tr>
                                                      <th scope="row">Team</th>
                                                      <td>{{ usertreeview[0].level[0].l_c_count }}</td>
                                                      <td>{{ usertreeview[0].level[0].r_c_count }}</td>
                                                    </tr>
                                                    <tr>
                                                      <th scope="row">Business</th>
                                                     <td>{{ usertreeview[0].level[0].l_bv}}</td>
                                                        <td>{{ usertreeview[0].level[0].r_bv}}</td>
                                                    </tr>
                                                    <tr>
                                                      <th scope="row">Carry</th>
                                                       <td>{{ usertreeview[0].level[0].left_bv}}</td>
                                                        <td>{{ usertreeview[0].level[0].right_bv}}</td>
                                                    </tr>
                                                  
                                                  </tbody>
                                                </table>
                                            </div>

                                    </a>
                                    <h6>{{usertreeview[0].level[0].user_id}}</h6>
                                </div>
                                <div class="col-6 tree-2-2">
                                    <a href="javascript:void(0)" @click.prevent="getMatrixTreeData(usertreeview[0].level[1].user_id)" data-toggle="tooltip" data-placement="bottom" id="myTooltip1" class="mytooltip">
                                        <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[0].level[1].image">
                                        
                                      <!--   <div class="XentoTree-tooip-text absent-card"> -->
                                            <div :class="'XentoTree-tooip-text '+usertreeview[0].level[1].cardclass">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[0].level[1].image">
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="tph">
                                                            <h1>{{ usertreeview[0].level[1].user_id }}</h1>
                                                            <ul>
                                                                <li>
                                                                    <i class="fa fa-user ps-0"></i> {{ usertreeview[0].level[1].fullname }}
                                                                </li>
                                                                <li>
                                                                    <i class="fa fa-calendar-alt"></i> Join: {{usertreeview[0].level[1].entry_time | moment('YYYY-MM-DD')}}
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-3 pe-0">
                                                        <div class="whiteBox">
                                                            <h2>
                                                                Package 
                                                                <span>{{ usertreeview[0].level[1].product_name }}</span>
                                                            </h2>
                                                        </div>
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="whiteBox">
                                                            <div>
                                                                <p>Sponsor Id : {{ usertreeview[0].level[1].sponsor_id }} </p>
                                                                  <p>Placement Id : {{usertreeview[0].level[1].virtual_parent_id}}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="SelfT">
                                                    <p>Self Topup : {{usertreeview[0].level[1].selftopup}}</p>
                                                </div>
                                                <table class="table whitetable">
                                                  <thead>
                                                    <tr>
                                                      <th scope="col">Factors</th>
                                                      <th scope="col">Left Side</th>
                                                      <th scope="col">Right Side</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                    <tr>
                                                      <th scope="row">Team</th>
                                                      <td>{{ usertreeview[0].level[1].l_c_count }}</td>
                                                        <td>{{ usertreeview[0].level[1].r_c_count }}</td>
                                                    </tr>
                                                    <tr>
                                                      <th scope="row">Business</th>
                                                     <td>{{ usertreeview[0].level[1].l_bv }}</td>
                                                        <td>{{ usertreeview[0].level[1].r_bv }}</td>
                                                    </tr>
                                                    <tr>
                                                      <th scope="row">Carry</th>
                                                      <td>{{ usertreeview[0].level[1].left_bv }}</td>
                                                        <td>{{ usertreeview[0].level[1].right_bv }}</td>
                                                    </tr>
                                                   
                                                  </tbody>
                                                </table>
                                            </div>

                                    </a>
                                    <h6>{{ usertreeview[0].level[1].user_id }}</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="row midline VHline">
                                        <div class="col-6 tree-3-1">
                                            <a href="javascript:void(0)" @click.prevent="getMatrixTreeData(usertreeview[1].level[0].user_id)" data-toggle="tooltip" data-placement="bottom" id="myTooltip1" class="mytooltip">
                                                <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[1].level[0].image">
                                               
                                               <!-- <div class="XentoTree-tooip-text active-card"> -->
                                                <div :class="'XentoTree-tooip-text '+usertreeview[1].level[0].cardclass">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[1].level[0].image">
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="tph">
                                                            <h1>{{ usertreeview[1].level[0].user_id }}</h1>
                                                            <ul>
                                                                <li>
                                                                    <i class="fa fa-user ps-0"></i> {{ usertreeview[1].level[0].fullname }}
                                                                </li>
                                                                <li>
                                                                    <i class="fa fa-calendar-alt"></i> Join: {{usertreeview[1].level[0].entry_time | moment('YYYY-MM-DD')}}
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-3 pe-0">
                                                        <div class="whiteBox">
                                                            <h2>
                                                                Package 
                                                                <span>{{usertreeview[1].level[0].product_name}}</span>
                                                            </h2>
                                                        </div>
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="whiteBox">
                                                            <div>
                                                                <p>Sponsor Id : {{usertreeview[1].level[0].sponsor_id}} </p>
                                                                  <p>Placement Id : {{usertreeview[1].level[0].virtual_parent_id}}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="SelfT">
                                                    <p>Self Topup : {{usertreeview[1].level[0].selftopup}}</p>
                                                </div>
                                                <table class="table whitetable">
                                                  <thead>
                                                    <tr>
                                                      <th scope="col">Factors</th>
                                                      <th scope="col">Left Side</th>
                                                      <th scope="col">Right Side</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                    <tr>
                                                      <th scope="row">Team</th>
                                                      <td>{{ usertreeview[1].level[0].l_c_count }}</td>
                                                        <td>{{ usertreeview[1].level[0].r_c_count }}</td>
                                                    </tr>
                                                    <tr>
                                                      <th scope="row">Business</th>
                                                      <td>{{ usertreeview[1].level[0].l_bv }}</td>
                                                        <td>{{ usertreeview[1].level[0].r_bv }}</td>
                                                    </tr>
                                                    <tr>
                                                      <th scope="row">Carry</th>
                                                     <td>{{ usertreeview[1].level[0].left_bv }}</td>
                                                        <td>{{ usertreeview[1].level[0].right_bv }}</td>
                                                    </tr>
                                                    
                                                  </tbody>
                                                </table>
                                            </div>
                                                
                                            </a>
                                            <h6>{{ usertreeview[1].level[0].user_id }}</h6>
                                        </div>
                                        <div class="col-6 tree-3-2">
                                            <a href="javascript:void(0)" @click.prevent="getMatrixTreeData(usertreeview[1].level[1].user_id)" data-toggle="tooltip" data-placement="bottom" id="myTooltip1" class="mytooltip">
                                                <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[1].level[1].image"> 
                                                
                                              <!--  <div class="XentoTree-tooip-text active-card"> -->
                                                <div :class="'XentoTree-tooip-text '+usertreeview[1].level[1].cardclass">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[1].level[1].image"> 
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="tph">
                                                             <h1>{{ usertreeview[1].level[1].user_id }}</h1>
                                                              <ul>
                                                                  <li>
                                                                      <i class="fa fa-user ps-0"></i> {{ usertreeview[1].level[1].fullname }}
                                                                  </li>
                                                                  <li>
                                                                      <i class="fa fa-calendar-alt"></i> join: {{usertreeview[1].level[1].entry_time | moment('YYYY-MM-DD')}}
                                                                  </li>
                                                              </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-3 pe-0">
                                                        <div class="whiteBox">
                                                            <h2>
                                                                Package 
                                                                <span>{{usertreeview[1].level[1].product_name}}</span>
                                                            </h2>
                                                        </div>
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="whiteBox">
                                                            <div>
                                                                <p>Sponsor Id : {{ usertreeview[1].level[1].sponsor_id }} </p>
                                                                  <p>Placement Id : {{ usertreeview[1].level[1].virtual_parent_id }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="SelfT">
                                                   <p>Self Topup : {{usertreeview[1].level[1].selftopup}}</p>
                                                </div>
                                                <table class="table whitetable">
                                                  <thead>
                                                    <tr>
                                                      <th scope="col">Factors</th>
                                                      <th scope="col">Left Side</th>
                                                      <th scope="col">Right Side</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                     <tr>
                                                        <th scope="row">Team</th>
                                                        <td>{{ usertreeview[1].level[1].l_c_count }}</td>
                                                        <td>{{ usertreeview[1].level[1].r_c_count }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th scope="row">Business</th>
                                                        <td>{{ usertreeview[1].level[1].l_bv }}</td>
                                                        <td>{{ usertreeview[1].level[1].r_bv }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th scope="row">Carry</th>
                                                        <td>{{ usertreeview[1].level[1].left_bv }}</td>
                                                        <td>{{ usertreeview[1].level[1].right_bv }}</td>
                                                      </tr>
                                                  </tbody>
                                                </table>
                                            </div>
                                            </a>
                                            <h6>{{ usertreeview[1].level[1].user_id }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row midline VHline">
                                        <div class="col-6 tree-3-3">
                                            <a href="javascript:void(0)" @click.prevent="getMatrixTreeData(usertreeview[1].level[2].user_id)" data-toggle="tooltip" data-placement="bottom" id="myTooltip1" class="mytooltip">
                                                <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[1].level[2].image">
                                                
                                              <!--   <div class="XentoTree-tooip-text active-card"> -->
                                                <div :class="'XentoTree-tooip-text '+usertreeview[1].level[2].cardclass">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[1].level[2].image">
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="tph">
                                                            <h1>{{ usertreeview[1].level[2].user_id }}</h1>
                                                            <ul>
                                                                <li>
                                                                    <i class="fa fa-user ps-0"></i> {{ usertreeview[1].level[2].fullname }}
                                                                </li>
                                                                <li>
                                                                    <i class="fa fa-calendar-alt"></i> Join: {{ usertreeview[1].level[2].entry_time || moment('YYYY-MM-DD')}}
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-3 pe-0">
                                                        <div class="whiteBox">
                                                            <h2>
                                                                Package 
                                                                <span>{{ usertreeview[1].level[2].product_name }}</span>
                                                            </h2>
                                                        </div>
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="whiteBox">
                                                            <div>
                                                                <p>Sponsor Id : {{ usertreeview[1].level[2].sponsor_id }} </p>
                                                                  <p>Placement Id : {{ usertreeview[1].level[2].virtual_parent_id }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="SelfT">
                                                    <p>Self Topup : {{ usertreeview[1].level[2].selftopup }}</p>
                                                </div>
                                                <table class="table whitetable">
                                                  <thead>
                                                    <tr>
                                                      <th scope="col">Factors</th>
                                                      <th scope="col">Left Side</th>
                                                      <th scope="col">Right Side</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                     <tr>
                                                        <th scope="row">Team</th>
                                                        <td>{{ usertreeview[1].level[2].l_c_count }}</td>
                                                        <td>{{ usertreeview[1].level[2].r_c_count }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th scope="row">Business</th>
                                                        <td>{{ usertreeview[1].level[2].l_bv }}</td>
                                                        <td>{{ usertreeview[1].level[2].r_bv }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th scope="row">Carry</th>
                                                        <td>{{ usertreeview[1].level[2].left_bv }}</td>
                                                        <td>{{ usertreeview[1].level[2].right_bv }}</td>
                                                      </tr>
                                                  </tbody>
                                                </table>
                                            </div>
                                                
                                            </a>
                                            <h6>{{ usertreeview[1].level[2].user_id }}</h6>
                                        </div>
                                        <div class="col-6 tree-3-4">
                                            <a href="javascript:void(0)" @click.prevent="getMatrixTreeData(usertreeview[1].level[3].user_id)" data-toggle="tooltip" data-placement="bottom" id="myTooltip1" class="mytooltip">
                                                <img  :src="imgURL+'/assets/images/tree-view/'+usertreeview[1].level[3].image">
                                                
                                               <!--  <div class="XentoTree-tooip-text active-card"> -->
                                            <div :class="'XentoTree-tooip-text '+usertreeview[1].level[3].cardclass">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <img  :src="imgURL+'/assets/images/tree-view/'+usertreeview[1].level[3].image">
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="tph">
                                                            <h1>{{ usertreeview[1].level[3].user_id }}</h1>
                                                            <ul>
                                                                <li>
                                                                    <i class="fa fa-user ps-0"></i> {{ usertreeview[1].level[3].fullname }}
                                                                </li>
                                                                <li>
                                                                    <i class="fa fa-calendar-alt"></i> Join: {{ usertreeview[1].level[3].entry_time | moment("YYYY-MM-DD") }}
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-3 pe-0">
                                                        <div class="whiteBox">
                                                            <h2>
                                                                Package 
                                                                <span>{{ usertreeview[1].level[3].product_name }}</span>
                                                            </h2>
                                                        </div>
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="whiteBox">
                                                            <div>
                                                                 <p>Sponsor Id : {{ usertreeview[1].level[3].sponsor_id }} </p>
                                                                  <p>Placement Id : {{ usertreeview[1].level[3].virtual_parent_id }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="SelfT">
                                                    <p>Self Topup : {{ usertreeview[1].level[3].selftopup }}</p>
                                                </div>
                                                <table class="table whitetable">
                                                  <thead>
                                                    <tr>
                                                      <th scope="col">Factors</th>
                                                      <th scope="col">Left Side</th>
                                                      <th scope="col">Right Side</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                     <tr>
                                                        <th scope="row">Team</th>
                                                        <td>{{ usertreeview[1].level[3].l_c_count }}</td>
                                                        <td>{{ usertreeview[1].level[3].r_c_count }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th scope="row">Business</th>
                                                        <td>{{ usertreeview[1].level[3].l_bv }}</td>
                                                        <td>{{ usertreeview[1].level[3].r_bv }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th scope="row">Carry</th>
                                                        <td>{{ usertreeview[1].level[3].left_bv }}</td>
                                                        <td>{{ usertreeview[1].level[3].right_bv }}</td>
                                                      </tr>
                                                  </tbody>
                                                </table>
                                            </div>
                                                
                                            </a>
                                            <h6>{{ usertreeview[1].level[3].user_id }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row rowline-4">
                                <div class="col-3">
                                    <div class="row midline VHline">
                                        <div class="col-6 tree-4-1">
                                            <a href="javascript:void(0)" @click.prevent="getMatrixTreeData(usertreeview[2].level[0].user_id)" data-toggle="tooltip" data-placement="bottom" id="myTooltip1" class="mytooltip">
                                                 <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[2].level[0].image">
                                                
                                              
                                            <div :class="'XentoTree-tooip-text '+usertreeview[2].level[0].cardclass">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[2].level[0].image">
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="tph">
                                                            <h1>{{ usertreeview[2].level[0].user_id }}</h1>
                                                              <ul>
                                                                  <li>
                                                                      <i class="fa fa-user ps-0"></i> {{ usertreeview[2].level[0].fullname }}
                                                                  </li>
                                                                  <li>
                                                                      <i class="fa fa-calendar-alt"></i> join: {{ usertreeview[2].level[0].entry_time | moment('YYYY-MM-DD')}}
                                                                  </li>
                                                              </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-3 pe-0">
                                                        <div class="whiteBox">
                                                            <h2>
                                                                Package 
                                                                <span>{{ usertreeview[2].level[0].product_name }}</span>
                                                            </h2>
                                                        </div>
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="whiteBox">
                                                            <div>
                                                                <p>Sponsor Id : {{ usertreeview[2].level[0].sponsor_id}} </p>
                                                                  <p>Placement Id : {{ usertreeview[2].level[0].virtual_parent_id}}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="SelfT">
                                                    <p>Self Topup : {{ usertreeview[2].level[0].selftopup }}</p>
                                                </div>
                                                <table class="table whitetable">
                                                  <thead>
                                                    <tr>
                                                      <th scope="col">Factors</th>
                                                      <th scope="col">Left Side</th>
                                                      <th scope="col">Right Side</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                   <tr>
                                                        <th scope="row">Team</th>
                                                        <td>{{ usertreeview[2].level[0].l_c_count }}</td>
                                                        <td>{{ usertreeview[2].level[0].r_c_count }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th scope="row">Business</th>
                                                        <td>{{ usertreeview[2].level[0].l_bv }}</td>
                                                        <td>{{ usertreeview[2].level[0].r_bv }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th scope="row">Carry</th>
                                                        <td>{{ usertreeview[2].level[0].left_bv }}</td>
                                                        <td>{{ usertreeview[2].level[0].right_bv }}</td>
                                                      </tr>
                                                  </tbody>
                                                </table>
                                            </div>    
                                            </a>
                                            <h6>{{ usertreeview[2].level[0].user_id }}</h6>
                                        </div>
                                        <div class="col-6 tree-4-2">
                                            <a href="javascript:void(0)"  @click.prevent="getMatrixTreeData(usertreeview[2].level[1].user_id)" data-toggle="tooltip" data-placement="bottom" id="myTooltip1" class="mytooltip">
                                                 <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[2].level[1].image">
                                                
                                              <!--   <div class="XentoTree-tooip-text active-card"> -->
                                            <div :class="'XentoTree-tooip-text '+usertreeview[2].level[1].cardclass">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[2].level[1].image">
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="tph">
                                                            <h1>{{ usertreeview[2].level[1].user_id }}</h1>
                                                            <ul>
                                                                <li>
                                                                    <i class="fa fa-user ps-0"></i> {{ usertreeview[2].level[1].fullname }}
                                                                </li>
                                                                <li>
                                                                    <i class="fa fa-calendar-alt"></i> Join: {{ usertreeview[2].level[1].entry_time | moment('YYYY-MM-DD')}}
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-3 pe-0">
                                                        <div class="whiteBox">
                                                            <h2>
                                                                Package 
                                                                <span>{{ usertreeview[2].level[1].product_name }}</span>
                                                            </h2>
                                                        </div>
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="whiteBox">
                                                            <div>
                                                                 <p>Sponsor Id : {{ usertreeview[2].level[1].sponsor_id}} </p>
                                                                  <p>Placement Id : {{ usertreeview[2].level[1].virtual_parent_id}}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="SelfT">
                                                    <p>Self Topup : {{ usertreeview[2].level[1].selftopup }}</p>
                                                </div>
                                                <table class="table whitetable">
                                                  <thead>
                                                    <tr>
                                                      <th scope="col">Factors</th>
                                                      <th scope="col">Left Side</th>
                                                      <th scope="col">Right Side</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                    <tr>
                                                        <th scope="row">Team</th>
                                                        <td>{{ usertreeview[2].level[1].l_c_count }}</td>
                                                        <td>{{ usertreeview[2].level[1].r_c_count }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th scope="row">Business</th>
                                                        <td>{{ usertreeview[2].level[1].l_bv }}</td>
                                                        <td>{{ usertreeview[2].level[1].r_bv }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th scope="row">Carry</th>
                                                        <td>{{ usertreeview[2].level[1].left_bv }}</td>
                                                        <td>{{ usertreeview[2].level[1].right_bv }}</td>
                                                      </tr>
                                                  </tbody>
                                                </table>
                                            </div>
                                                
                                            </a>
                                            <h6>{{ usertreeview[2].level[1].user_id }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="row midline VHline">
                                        <div class="col-6 tree-4-3">
                                            <a href="javascript:void(0)" @click.prevent="getMatrixTreeData(usertreeview[2].level[2].user_id)" data-toggle="tooltip" data-placement="bottom" id="myTooltip1" class="mytooltip">
                                                 <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[2].level[2].image">
                                                
                                               <!--  <div class="XentoTree-tooip-text inactive-card"> -->
                                            <div :class="'XentoTree-tooip-text '+usertreeview[2].level[2].cardclass">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[2].level[2].image">
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="tph">
                                                            <h1>{{ usertreeview[2].level[2].user_id }}</h1>
                                                              <ul>
                                                                  <li>
                                                                      <i class="fa fa-user ps-0"></i> {{ usertreeview[2].level[2].fullname }}
                                                                  </li>
                                                                  <li>
                                                                      <i class="fa fa-calendar-alt"></i> join: {{ usertreeview[2].level[2].entry_time | moment('YYYY-MM-DD')}}
                                                                  </li>
                                                              </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-3 pe-0">
                                                        <div class="whiteBox">
                                                            <h2>
                                                                Package 
                                                                  <span>{{ usertreeview[2].level[2].product_name }}</span>
                                                            </h2>
                                                        </div>
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="whiteBox">
                                                            <div>
                                                                 <p>Sponsor Id : {{ usertreeview[2].level[2].sponsor_id }} </p>
                                                                  <p>Placement Id : {{ usertreeview[2].level[2].virtual_parent_id }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="SelfT">
                                                    <p>Self Topup : {{ usertreeview[2].level[2].selftopup }}</p>
                                                </div>
                                                <table class="table whitetable">
                                                  <thead>
                                                    <tr>
                                                      <th scope="col">Factors</th>
                                                      <th scope="col">Left Side</th>
                                                      <th scope="col">Right Side</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                   <tr>
                                                        <th scope="row">Team</th>
                                                        <td>{{ usertreeview[2].level[2].l_c_count }}</td>
                                                        <td>{{ usertreeview[2].level[2].r_c_count }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th scope="row">Business</th>
                                                        <td>{{ usertreeview[2].level[2].l_bv }}</td>
                                                        <td>{{ usertreeview[2].level[2].r_bv }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th scope="row">Carry</th>
                                                        <td>{{ usertreeview[2].level[2].left_bv }}</td>
                                                        <td>{{ usertreeview[2].level[2].right_bv }}</td>
                                                      </tr>
                                                  </tbody>
                                                </table>
                                            </div>
                                                
                                            </a>
                                            <h6>{{ usertreeview[2].level[2].user_id }}</h6>
                                        </div>
                                        <div class="col-6 tree-4-4">
                                            <a href="javascript:void(0)" @click.prevent="getMatrixTreeData(usertreeview[2].level[3].user_id)" data-toggle="tooltip" data-placement="bottom" id="myTooltip1" class="mytooltip">
                                                <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[2].level[3].image">
                                                
                                              <!--   <div class="XentoTree-tooip-text absent-card"> -->
                                                    <div :class="'XentoTree-tooip-text '+usertreeview[2].level[3].cardclass">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[2].level[3].image">
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="tph">
                                                            <h1>{{ usertreeview[2].level[3].user_id }}</h1>
                                                            <ul>
                                                               <li>
                                                                      <i class="fa fa-user ps-0"></i> {{ usertreeview[2].level[3].fullname }}
                                                                  </li>
                                                                  <li>
                                                                      <i class="fa fa-calendar-alt"></i> join: {{ usertreeview[2].level[3].entry_time | moment('YYYY-MM-DD')}}
                                                                  </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-3 pe-0">
                                                        <div class="whiteBox">
                                                            <h2>
                                                                 Package 
                                                                  <span>{{ usertreeview[2].level[3].product_name }}</span>
                                                            </h2>
                                                        </div>
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="whiteBox">
                                                            <div>
                                                                  <p>Sponsor Id : {{ usertreeview[2].level[3].sponsor_id }} </p>
                                                                  <p>Placement Id : {{ usertreeview[2].level[3].virtual_parent_id }}</p>
                                                              </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="SelfT">
                                                    <p>Self Topup : {{ usertreeview[2].level[3].selftopup }}</p>
                                                </div>
                                                <table class="table whitetable">
                                                  <thead>
                                                    <tr>
                                                      <th scope="col">Factors</th>
                                                      <th scope="col">Left Side</th>
                                                      <th scope="col">Right Side</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                   <tr>
                                                        <th scope="row">Team</th>
                                                        <td>{{ usertreeview[2].level[3].l_c_count }}</td>
                                                        <td>{{ usertreeview[2].level[3].r_c_count }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th scope="row">Business</th>
                                                        <td>{{ usertreeview[2].level[3].l_bv }}</td>
                                                        <td>{{ usertreeview[2].level[3].r_bv }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th scope="row">Carry</th>
                                                        <td>{{ usertreeview[2].level[3].left_bv }}</td>
                                                        <td>{{ usertreeview[2].level[3].right_bv }}</td>
                                                      </tr>
                                                  </tbody>
                                                </table>
                                            </div>
                                                
                                            </a>
                                           <h6>{{ usertreeview[2].level[3].user_id }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="row midline VHline">
                                        <div class="col-6 tree-4-5">
                                            <a href="javascript:void(0)" @click.prevent="getMatrixTreeData(usertreeview[2].level[4].user_id)" data-toggle="tooltip" data-placement="bottom" id="myTooltip1" class="mytooltip">
                                                 <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[2].level[4].image">
                                                <h6>{{ usertreeview[2].level[4].user_id }}</h6>
                                                
                                              <!--   <div class="XentoTree-tooip-text inactive-card"> -->
                                            <div :class="'XentoTree-tooip-text '+usertreeview[2].level[4].cardclass">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[2].level[4].image">
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="tph">
                                                            <h1>{{ usertreeview[2].level[4].user_id }}</h1>
                                                              <ul>
                                                                  <li>
                                                                      <i class="fa fa-user ps-0"></i> {{ usertreeview[2].level[4].fullname }}
                                                                  </li>
                                                                  <li>
                                                                      <i class="fa fa-calendar-alt"></i> join: {{ usertreeview[2].level[4].entry_time | moment('YYYY-MM-DD')}}
                                                                  </li>
                                                              </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-3 pe-0">
                                                        <div class="whiteBox">
                                                            <h2>
                                                                Package 
                                                                  <span>{{ usertreeview[2].level[4].product_name }}</span>
                                                            </h2>
                                                        </div>
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="whiteBox">
                                                             <div>
                                                                  <p>Sponsor Id : {{ usertreeview[2].level[4].sponsor_id }} </p>
                                                                  <p>Placement Id : {{ usertreeview[2].level[4].virtual_parent_id }}</p>
                                                              </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="SelfT">
                                                   <p>Self Topup : {{ usertreeview[2].level[4].selftopup }}</p>
                                                </div>
                                                <table class="table whitetable">
                                                  <thead>
                                                    <tr>
                                                      <th scope="col">Factors</th>
                                                      <th scope="col">Left Side</th>
                                                      <th scope="col">Right Side</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                      <tr>
                                                        <th scope="row">Team</th>
                                                        <td>{{ usertreeview[2].level[4].l_c_count }}</td>
                                                        <td>{{ usertreeview[2].level[4].r_c_count }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th scope="row">Business</th>
                                                        <td>{{ usertreeview[2].level[4].l_bv }}</td>
                                                        <td>{{ usertreeview[2].level[4].r_bv }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th scope="row">Carry</th>
                                                        <td>{{ usertreeview[2].level[4].left_bv }}</td>
                                                        <td>{{ usertreeview[2].level[4].right_bv }}</td>
                                                      </tr>
                                                  </tbody>
                                                </table>
                                            </div>

                                            </a>
                                        </div>
                                        <div class="col-6 tree-4-6">
                                            <a href="javascript:void(0)" @click.prevent="getMatrixTreeData(usertreeview[2].level[5].user_id)" data-toggle="tooltip" data-placement="bottom" id="myTooltip1" class="mytooltip">
                                                 <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[2].level[5].image">
                                                <h6>{{ usertreeview[2].level[5].user_id }}</h6>
                                                
                                              <!--   <div class="XentoTree-tooip-text active-card"> -->
                                                    <div :class="'XentoTree-tooip-text '+usertreeview[2].level[5].cardclass">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[2].level[5].image">
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="tph">
                                                            <h1>{{ usertreeview[2].level[5].user_id }}</h1>
                                                            <ul>
                                                                <li>
                                                                    <i class="fa fa-user ps-0"></i> {{ usertreeview[2].level[5].fullname }}
                                                                  </li>
                                                                  <li>
                                                                      <i class="fa fa-calendar-alt"></i> join: {{ usertreeview[2].level[5].entry_time | moment('YYYY-MM-DD')}}
                                                                  </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-3 pe-0">
                                                        <div class="whiteBox">
                                                            <h2>
                                                                Package 
                                                                <span>{{ usertreeview[2].level[5].product_name }}</span>
                                                            </h2>
                                                        </div>
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="whiteBox">
                                                            <div>
                                                               <p>Sponsor Id : {{ usertreeview[2].level[5].sponsor_id }} </p>
                                                                  <p>Placement Id : {{ usertreeview[2].level[5].virtual_parent_id }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="SelfT">
                                                    <p>Self Topup : {{ usertreeview[2].level[5].selftopup }}</p>
                                                </div>
                                                <table class="table whitetable">
                                                  <thead>
                                                    <tr>
                                                      <th scope="col">Factors</th>
                                                      <th scope="col">Left Side</th>
                                                      <th scope="col">Right Side</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                     <tr>
                                                        <th scope="row">Team</th>
                                                        <td>{{ usertreeview[2].level[5].l_c_count }}</td>
                                                        <td>{{ usertreeview[2].level[5].r_c_count }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th scope="row">Business</th>
                                                        <td>{{ usertreeview[2].level[5].l_bv }}</td>
                                                        <td>{{ usertreeview[2].level[5].r_bv }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th scope="row">Carry</th>
                                                        <td>{{ usertreeview[2].level[5].left_bv }}</td>
                                                        <td>{{ usertreeview[2].level[5].right_bv }}</td>
                                                      </tr>
                                                  </tbody>
                                                </table>
                                            </div>

                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="row midline VHline">
                                        <div class="col-6 tree-4-7">
                                            <a href="javascript:void(0)" @click.prevent="getMatrixTreeData(usertreeview[2].level[6].user_id)" data-toggle="tooltip" data-placement="bottom" id="myTooltip1" class="mytooltip">
                                                 <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[2].level[6].image">
                                                
                                               <!--  <div class="XentoTree-tooip-text inactive-card"> -->
                                                    <div :class="'XentoTree-tooip-text '+usertreeview[2].level[6].cardclass">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[2].level[6].image">
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="tph">
                                                            <h1>{{ usertreeview[2].level[6].user_id }}</h1>
                                                            <ul>
                                                                <li>
                                                                      <i class="fa fa-user ps-0"></i> {{ usertreeview[2].level[6].fullname }}
                                                                  </li>
                                                                  <li>
                                                                      <i class="fa fa-calendar-alt"></i> join: {{ usertreeview[2].level[6].entry_time | moment('YYYY-MM-DD')}}
                                                                  </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-3 pe-0">
                                                        <div class="whiteBox">
                                                            <h2>
                                                                Package 
                                                                 <span>{{ usertreeview[2].level[6].product_name }}</span>
                                                            </h2>
                                                        </div>
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="whiteBox">
                                                            <div>
                                                               <p>Sponsor Id : {{ usertreeview[2].level[6].sponsor_id }} </p>
                                                                  <p>Placement Id : {{ usertreeview[2].level[6].virtual_parent_id }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="SelfT">
                                                    <p>Self Topup : {{ usertreeview[2].level[6].selftopup }}</p>
                                                </div>
                                                <table class="table whitetable">
                                                  <thead>
                                                    <tr>
                                                      <th scope="col">Factors</th>
                                                      <th scope="col">Left Side</th>
                                                      <th scope="col">Right Side</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                     <tr>
                                                        <th scope="row">Team</th>
                                                        <td>{{ usertreeview[2].level[6].l_c_count }}</td>
                                                        <td>{{ usertreeview[2].level[6].r_c_count }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th scope="row">Business</th>
                                                        <td>{{ usertreeview[2].level[6].l_bv }}</td>
                                                        <td>{{ usertreeview[2].level[6].r_bv }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th scope="row">Carry</th>
                                                        <td>{{ usertreeview[2].level[6].left_bv }}</td>
                                                        <td>{{ usertreeview[2].level[6].right_bv }}</td>
                                                      </tr>
                                                  </tbody>
                                                </table>
                                            </div>
                                                
                                            </a>
                                            <h6>{{ usertreeview[2].level[6].user_id }}</h6>
                                        </div>
                                        <div class="col-6 tree-4-8">
                                            <a href="javascript:void(0)" @click.prevent="getMatrixTreeData(usertreeview[2].level[7].user_id)" data-toggle="tooltip" data-placement="bottom" id="myTooltip1" class="mytooltip">
                                                 <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[2].level[7].image">
                                                
                                                <!-- <div class="XentoTree-tooip-text active-card"> -->
                                            <div :class="'XentoTree-tooip-text '+usertreeview[2].level[7].cardclass">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <img :src="imgURL+'/assets/images/tree-view/'+usertreeview[2].level[7].image">
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="tph">
                                                            <h1>{{ usertreeview[2].level[7].user_id }}</h1>
                                                            <ul>
                                                                <li>
                                                                      <i class="fa fa-user ps-0"></i> {{ usertreeview[2].level[7].fullname }}
                                                                  </li>
                                                                  <li>
                                                                      <i class="fa fa-calendar-alt"></i> join: {{ usertreeview[2].level[7].entry_time | moment('YYYY-MM-DD')}}
                                                                  </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-3 pe-0">
                                                        <div class="whiteBox">
                                                            <h2>
                                                                Package 
                                                                <span>{{ usertreeview[2].level[7].product_name }}</span>
                                                            </h2>
                                                        </div>
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="whiteBox">
                                                            <div>
                                                                <p>Sponsor Id : {{ usertreeview[2].level[7].sponsor_id }} </p>
                                                                  <p>Placement Id : {{ usertreeview[2].level[7].virtual_parent_id }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="SelfT">
                                                    <p>Self Topup : {{ usertreeview[2].level[7].selftopup }}</p>
                                                </div>
                                                <table class="table whitetable">
                                                  <thead>
                                                    <tr>
                                                      <th scope="col">Factors</th>
                                                      <th scope="col">Left Side</th>
                                                      <th scope="col">Right Side</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                      <tr>
                                                        <th scope="row">Team</th>
                                                        <td>{{ usertreeview[2].level[7].l_c_count }}</td>
                                                        <td>{{ usertreeview[2].level[7].r_c_count }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th scope="row">Business</th>
                                                        <td>{{ usertreeview[2].level[7].l_bv }}</td>
                                                        <td>{{ usertreeview[2].level[7].r_bv }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th scope="row">Carry</th>
                                                        <td>{{ usertreeview[2].level[7].left_bv }}</td>
                                                        <td>{{ usertreeview[2].level[7].right_bv }}</td>
                                                      </tr>
                                                  </tbody>
                                                </table>
                                            </div>
                                                
                                            </a>
                                            <h6>{{ usertreeview[2].level[7].user_id }}</h6>
                                        </div>
                                    </div>
                                </div>
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
    /*this.getUserDetails();*/
    //this.getAmount();
    //this.get_todays_rank_count();
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
            //console.log(resp);
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
            this.userdata_for_rank = resp.data.data.user_rankdata;
            
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
        this.$root.placement_user_id != "Absent"
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