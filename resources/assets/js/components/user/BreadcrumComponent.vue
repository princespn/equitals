<style type="text/css">
  #page-head .breadcrumb li {
    cursor: pointer;
  }
  /*#page-head .breadcrumb li:hover {
    color: #acdc46 !important;
    text-decoration: none;
  }*/
</style>
<template>
  <div id="page-head">
      <!--Page Title-->
      <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
      <div id="page-title">
         <h1 class="page-header text-overflow">{{ title }}</h1>
      </div>
      <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
      <!--End page title-->
      <!--Breadcrumb-->
      <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
      <ol class="breadcrumb">
        <li><i class="demo-pli-home"></i></li>
        <li
          v-for="(breadcrumb, idx) in breadcrumbList"
          :key="idx"
          @click="routeTo(idx)"
          :class=" {'breadcrumb-item': !!breadcrumb.link}">
          {{ breadcrumb.name }}
        </li>
      </ol>
      <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
      <!--End breadcrumb-->
  </div>
</template>

<script>
export default{
   name: 'Breadcrumb',
   data () {
    return {
      breadcrumbList: [],
      title:''
    }
  },
  mounted () { 
    this.updateList() 
  },
  watch: { '$route' () { 
      this.updateList()
      } 
  },
  methods: {
    routeTo (pRouteTo) {
      if (this.breadcrumbList[pRouteTo].link) this.$router.push(this.breadcrumbList[pRouteTo].link)
    },
    updateList () { 
      this.breadcrumbList = this.$route.meta.breadcrumb,
        this.title = this.$route.meta.title

      }
  }
	
}
</script>
<style scoped>
  .breadcrumb {}
  /*ul {
    display: flex;
    justify-content: center;
    list-style-type: none;
    margin: 0;
    padding: 0;
  }
  ul > li {
    display: flex;
    float: left;
    height: 10px;
    width: auto;
    color: $default;
    font-weight: bold;
    font-size: .8em;
    cursor: default;
    align-items: center;
  }
  ul > li:not(:last-child)::after {
    content: '/';
    float: right;
    font-size: .8em;
    margin: 0 .5em;
    color: $light-default;
    cursor: default;
  }
  .linked {
    cursor: pointer;
    font-size: 1em;
    font-weight: normal;
  }*/
</style>
