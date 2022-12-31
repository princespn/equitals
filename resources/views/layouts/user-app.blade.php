<!DOCTYPE html>

<style>
input[type="number"]::-webkit-outer-spin-button, 
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type="number"] {
    -moz-appearance: textfield;
}
</style>
<html>

<head lang="en">
  <!--start new script -->
  <!-- PAGE TITLE HERE -->
  <title>Equitals</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="keywords" content="" />
  <meta name="author" content="" />
  <meta name="robots" content="" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="" />
  <meta property="og:title" content="" />
  <meta property="og:description" content="" />
  <meta property="og:image" content="social-image.png" />
  <meta name="format-detection" content="telephone=no">
  
<!-- FAVICONS ICON -->
  <link rel="shortcut icon" type="image/png" href="{{ asset('user_files/assets/images/favicon.png') }}" />
  <!-- <link href="{{ asset('user_files/assets/vendor/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet"> -->
  <!-- <link rel="stylesheet" href="{{ asset('user_files/assets/vendor/nouislider/nouislider.min.css') }}"> -->
   <link href="{{ asset('user_files/assets/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
  <!-- <link href="{{ asset('user_files/assets/vendor/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet"> -->
    <link href="{{ asset('user_files/assets/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('user_files/assets/vendor/metismenu/css/metisMenu.min.css') }}">
  <!-- end new script -->
  <script type="text/javascript">

  // Load the Visualization API library and the piechart library.
  google.load('visualization', '1.0', {'packages':['corechart']});
  google.setOnLoadCallback(drawChart);
     // ... draw the chart...
</script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript" src="https://www.google.com/jsapi"></script>

 
  <!-- Bootstrap Css -->
  <!-- <link href="{{ asset('user_files/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" /> -->
  <!-- Icons Css -->
  <!-- <link href="{{ asset('user_files/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" /> -->
  <!-- App Css-->
 <!--  <link href="{{ asset('user_files/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />  -->
 <!-- <link rel="stylesheet" href="{{ asset('user_files/css/dark-layout.min.css') }}">-->
  <!-- <link href="{{ asset('user_files/assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('user_files/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
  <link href="{{ asset('user_files/assets/libs/spectrum-colorpicker2/spectrum.min.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('user_files/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('user_files/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('user_files/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" /> -->
  <!-- Responsive datatable examples -->
<!--   <link href="{{ asset('user_files/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" /> 
  <link rel="stylesheet" src="{{ asset('user_files/assets/libs/css02/all.css') }}" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="{{ asset('user_files/assets/css/style1.css') }}"> -->
  <!-- datatable css1111 -->
  <!-- <script src="{{ asset('user_files/js/fontawesome.min.js') }}"></script> -->
<script src="https://kit.fontawesome.com/2ea3577f09.js" crossorigin="anonymous"></script>
</head>

<body data-sidebar="dark">
  <div id="user-app"></div>
   
<!-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script> -->
<!-- <script src="{{ asset('user_files/assets/libs/jquery/jquery-3.3.1.js') }}"></script> -->
    <script type="text/javascript">
    $(window).load(function(){        
   $('#examplePrimaryModal1').modal('show');
    // display_c();
    });
  </script>
  <script type="text/javascript">
    $(window).load(function(){        
   $('#examplePrimaryModal1').modal('show');
    });
  </script>

<!-- <script type="text/javascript">
 $('#closemodal').click(function() {
    $('#modalwindow').modal('hide');
});
</script> -->

     <script type="text/javascript">
    $(window).load(function(){        
   $('#myModal2').modal('show');
   // display_c();
   // document.getElementById('ct').innerHTML = "Hello";
    });
  </script>
  <script type="text/javascript">
    $(function () {
        $("#btnClosePopup").click(function () {
            // alert('hi');
            $(".mod").css("display", "none");

            $("#myModal2").modal("hide");
        });
    });
    $(document).ready(function(){
   setTimeout(function(){
      display_c();
   },2500); // 5000 to load it after 5 seconds from page load
});
</script>


<!-- data table -->


  <script src="{{ asset('js/app.js')}}"></script>

   <!-- Required vendors -->
    <script src="{{ asset('user_files/assets/vendor/global/global.min.js') }}"></script>
  <script src="{{ asset('user_files/assets/vendor/chart.js/Chart.bundle.min.js') }}"></script>
  <!-- <script src="{{ asset('user_files/assets/vendor/jquery-nice-select/js/jquery.nice-select.min.js') }}"></script>   -->
  <!-- Apex Chart -->
  <script src="{{ asset('user_files/assets/vendor/apexchart/apexchart.js') }}"></script>
  <!-- <script src="{{ asset('user_files/assets/vendor/nouislider/nouislider.min.js') }}"></script> -->
  <!-- <script src="{{ asset('user_files/assets/vendor/wnumb/wNumb.js') }}"></script> -->
       <!-- Datatable -->
    <script src="{{ asset('user_files/assets/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('user_files/assets/js/plugins-init/datatables.init.js') }}"></script>  
  <script src="{{ asset('user_files/assets/js/dashboard/dashboard-1.js') }}"></script>
    <script src="{{ asset('user_files/assets/js/custom.min.js') }}"></script>
  <script src="{{ asset('user_files/assets/js/dlabnav-init.js') }}"></script>
  <script src="{{ asset('user_files/assets/js/demo.js') }}"></script>  

 <!-- JAVASCRIPT -->
       <!--  <script src="{{ asset('user_files/assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('user_files/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('user_files/assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('user_files/assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('user_files/assets/libs/node-waves/waves.min.js') }}"></script>
        <script src="{{ asset('user_files/assets/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
 -->
        <!--Morris Chart-->
       <!--  <script src="{{ asset('user_files/assets/libs/morris/morris.min.js') }}"></script>
        <script src="{{ asset('user_files/assets/libs/raphael/raphael.min.js') }}"></script>

        <script src="{{ asset('user_files/assets/js/pages/dashboard.init.js') }}"></script>

        <script src="{{ asset('user_files/assets/js/pages/morris.init.js') }}"></script>


        <script src="{{ asset('user_files/assets/libs/jquery-knob/jquery.knob.min.js') }}"></script> -->

        <!-- <script src="{{ asset('user_files/assets/js/pages/jquery-knob.init.js') }}"></script> -->

      <!--   <script src="{{ asset('user_files/assets/libs/select2/js/select2.min.js') }}"></script>
        <script src="{{ asset('user_files/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
        <script src="{{ asset('user_files/assets/libs/spectrum-colorpicker2/spectrum.min.js') }}"></script>
        <script src="{{ asset('user_files/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js') }}"></script>
        <script src="{{ asset('user_files/assets/libs/admin-resources/bootstrap-filestyle/bootstrap-filestyle.min.js') }}"></script>
        <script src="{{ asset('user_files/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>

        <script src="{{ asset('user_files/assets/js/pages/form-advanced.init.js') }}"></script> -->


         <!-- Required datatable js -->
       <!--  <script src="{{ asset('user_files/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('user_files/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script> -->
        <!-- Buttons examples -->
        <!-- <script src="{{ asset('user_files/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('user_files/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('user_files/assets/libs/jszip/jszip.min.js') }}"></script>
        <script src="{{ asset('user_files/assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
        <script src="{{ asset('user_files/assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
        <script src="{{ asset('user_files/assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('user_files/assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('user_files/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script> -->
        <!-- Responsive examples -->
      <!--   <script src="{{ asset('user_files/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('user_files/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('user_files/assets/libs/datatables.net/js/dataTables.scroller.min.js') }}"></script> -->

        <!-- Datatable init js -->
        <!-- <script src="{{ asset('user_files/assets/js/pages/datatables.init.js') }}"></script>
        <script src="{{ asset('user_files/assets/js/app.js') }}"></script> -->

<!--  <script type="text/javascript" src="https://pixinvent.com/demo/vuexy-html-bootstrap-admin-template/app-assets/js/scripts/pages/app-chat.min.js"></script> -->

 <script type="text/javascript">
    function googleTranslateElementInit() {
      new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
    }
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<!--  <script type="text/javascript">
    $(document).ready(function(){
$('#action_menu_btn').click(function(){
  $('.action_menu').toggle();
});
  });
 </script> -->

<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?onload=onloadCallback"></script>
<script src="https://www.cryptohopper.com/widgets/js/script"></script>
<script>
// setTimout is not necessary
setTimeout(function() {

  $('.recaptcha').each(function() {
    grecaptcha.render(this.id, {
      'sitekey': '6LdVkwkUAAAAACeeETRX--v9Js0vWyjQOTIZxxeB',
      "theme":"light"
    });
  });

}, 2000);
</script>
 <script>
            window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
                'pusherKey' => config('broadcasting.connections.pusher.key'),
                'pusherCluster' => config('broadcasting.connections.pusher.options.cluster')
            ]) !!};
        </script>


  <!-- <script>
    $(document).ready(function() {
      $('#direct-income-report').DataTable();
      $('#roi-income-report').DataTable();
      $('#binary-report').DataTable();
      $('#franchise-income-report').DataTable();
      $('#withdrawals-income-report').DataTable();
      $('#team-view').DataTable();
      $('#direct-user-list').DataTable();

    } );
  </script> -->

  <!-- datatable scrips -->




<!--   <script>
    $(".nav li").hover(function() {
      $(this)
        .addClass("is-active show")
        .siblings()
        .removeClass("is-active show");
    });
    $(".nav li").mouseleave(function() {
      $(this).removeClass("is-active show");
    });
    $(".dropdown.nav-item .dropdown-menu li a").click(function() {
      $(".nav li")
      .removeClass("is-active show");
      $("body").removeClass("menu-open")
      $("body").addClass("menu-hide")
    })
  </script> -->

<!-- <script>
    $(".dropdown.nav-item").click(function() {
      // alert();
      $(this).addClass('is-active');
    });

  </script> -->




<script>

  $('.dropdown-menu li a').click(function(){
  //alert("The paragraph was clicked.");
  $('.nav-item').removeClass('is-active');
 // $('.nav_toggle_id').removeClass('is-active');

  $('.nav-item').addClass('is-active');

});



</script>

<script>
  /*function myFunction() {
    //alert();
 var copyText = document.getElementById("myInput");
  copyText.select();
  copyText.setSelectionRange(0, 99999)
  document.execCommand("copy");
  alert("Copied the text: " + copyText.value);
}*/
function myFunction(id = "referral_input_left",ref="refcopy") {
    var copyText = document.getElementById( id );
    copyText.select();
    document.execCommand("copy");

    /*var tooltip = document.getElementById(ref);*/
    tooltip.innerHTML = "Copied";
}
function copyRightLink() {
    //alert();
 var copyText = document.getElementById("myRightInput");
  copyText.select();
  copyText.setSelectionRange(0, 99999)
  document.execCommand("copy");
  alert("Copied the text: " + copyText.value);
}


function copyText() {
    //alert();
 var copyText = document.getElementById("showaddressTxt");
  copyText.select();
  copyText.setSelectionRange(0, 99999)
  document.execCommand("copy");
  alert("Copied the text: " + copyText.value);
}

function myFunctionRefLeft() {
  var copyText = document.getElementById("referral-left");
  // alert(copyText);
  copyText.select();
  document.execCommand("copy");

  var tooltip = document.getElementById("refcopy1");
  tooltip.innerHTML = "<span class='btn-icon-start text-secondary'><i class='fa fa-copy color-secondary'></i> </span>Copied !"; // + copyText.value;
}

function outFunc() 
{
  var tooltip = document.getElementById("refcopy1");
  tooltip.innerHTML = "<span class='btn-icon-start text-secondary'><i class='fa fa-copy color-secondary'></i> </span>Copy Now !";
}

function myFunctionRefRight() {
  var copyText = document.getElementById("myRightInput");
  copyText.select();
  document.execCommand("copy");

  var tooltip = document.getElementById("right-refcopy");
  tooltip.innerHTML = "<span class='btn-icon-start text-secondary'><i class='fa fa-copy color-secondary'></i> </span> Copied !"; // + copyText.value;
}

function outrightFunc() 
{
    var tooltip = document.getElementById("right-refcopy");
    tooltip.innerHTML = "<span class='btn-icon-start text-secondary'><i class='fa fa-copy color-secondary'></i> </span> Copy Now !";
}

// function outFuncRL() {
//   var tooltip = document.getElementById("refcopy1");
//   tooltip.innerHTML = "Copy To Clipboard";
// }
</script>
   <!-- <script>
      $('.nav li').hover(function(){
        $(this).addClass('is-active').siblings().removeClass('is-active');
      });
      $('.nav li').mouseleave(function(){
        $(this).removeClass('is-active');
      });
    </script> -->
  <script>
    function myFunction1() {
      var copyText = document.getElementById("btc-add");
      copyText.select();
      document.execCommand("copy");

      var tooltip = document.getElementById("refcopy1");
      tooltip.innerHTML = "Copied"; // + copyText.value;
    }

    function outFunc1() {
      var tooltip = document.getElementById("refcopy1");
      tooltip.innerHTML = "Copy To Clipboard";
    }
  </script>

  <script type="text/javascript">
  $(document).ready(function() {
      $('#direct-user-list').DataTable();
  } );
  </script>
  <!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.debug.js"></script> -->

<!-- <script src='https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js'></script> -->
<!-- <script src="{{ asset('user_files/assets/libs/jspdf/jspdf.min.js') }}"></script>
<script src="{{ asset('user_files/assets/libs/jspdf/html2canvas.min.js') }}"></script> -->
  <script type="text/javascript">
    $(function(){

  var $translateBar = $('#translate-a'),
      $translateToggle = $('.translate-toggle'),
      $picker = $translateBar.find('select'),
      visibleClass = "visible",
      hideOnChange = true; // hide bar after choice

      $translateToggle.on('click', function(e){
        e.preventDefault();
        $translateBar.toggleClass(visibleClass);
      });

      if(hideOnChange){
        $translateBar.on('change', 'select', function(){
          if($translateBar.hasClass(visibleClass)){
            $translateBar.removeClass(visibleClass);
          }
        });
      }

});

function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'en',
    autoDisplay: false,
    includedLanguages: 'af,ak,sq,am,ar,hy,az,eu,be,bem,xx-bork,bs,br,bg,km,ca,chr,ny,zh-CN,zh-TW,co,hr,cs,da,nl,xx-el,er,en,eo,et,ee,fo,tl,fi,fr,fy,gaa,gl,ka,de,el,gn,xx-hacker,ht,ha,haw,iw,hu,is,ig,id,ia,ga,it,ja,jw,kn,kk,rw,rn,xx-klingon,kg,ko,kri,ku,ckb,ky,lo,la,lv,ln,lt,loz,lg,ach,mk,mg,ms,mt,mi,mfe,mo,mn,sr-ME,ne,pcm,nso,no,nn,oc,or,om,ps,fa,xx-pirate,pl,pt-BR,pt-PT,qu,ro,rm,nyn,ru,gd,sr,sh.st,tn,crs,sn,si,sk,sl,so,es,es-419,su,sw,sv,tg,tt,th,ti,to,lua,tum,tr,tk,tw,ug,uk,ur,uz,vi,cy,wo,xh,yi,yo,zu',
    layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL
  }, 'google_translate_element');
}

      function display_c(){
          var refresh=1000; // Refresh rate in milli seconds
          // console.log(refresh);
          mytime=setTimeout('display_ct()',refresh)
      }
      function display_ct() {
        var objDate = new Date(new Date());
        // console.log(objDate);
            var tz_time = 3;
            var numberOfMlSeconds = objDate.getTime();
            var addMlSeconds = (210)*  60 * 1000;
            // console.log(addMlSeconds);
            var d = new Date(numberOfMlSeconds + addMlSeconds);

         var newd = d.getFullYear()+"-"+((d.getMonth() < 10) ? ("0" + d.getMonth()) : d.getMonth())+"-"+((d.getDate() < 10) ? ("0" + d.getDate()) : d.getDate())+" "+((d.getHours() < 10) ? ("0" + d.getHours()) : d.getHours())+":"+((d.getMinutes() < 10) ? ("0" + d.getMinutes()) : d.getMinutes())+":"+((d.getSeconds() < 10) ? ("0" + d.getSeconds()) : d.getSeconds());

        document.getElementById('ct').innerHTML = newd;
        display_c();
      }
  </script>

 <!-- <script type="text/javascript">
    $('#exportForm').click(function(){
      html2canvas($('#pdfcontent'), {
        onrendered: function(canvas) {
          var pdf = new jsPDF('a', 'mm', 'a4');

          var imgData = canvas.toDataURL('image/jpeg', 1.0);
          pdf.addImage(imgData, 'JPEG', 5, 5, 200, 0);

          pdf.save("export.pdf");
        }
      });
    });
  </script> -->

<!-- <script type="text/javascript">
  var doc = new jsPDF();
var specialElementHandlers = {
    '#editor': function (element, renderer) {
        return true;
    }
};
$('#submitpdf').click(function () {
    doc.fromHTML($('#pdfcontent').html(), 15, 15, {
        'width': 190,
            'elementHandlers': specialElementHandlers
    });
    doc.save('sample-page.pdf');
});
</script> -->


<!-- <script type="text/javascript">
  var doc = new jsPDF();

  var specialElementHandlers = {
      '#editor': function(element, renderer){
          return true;
      }
  };

  doc.fromHTML($('#render_me').get(0), 15, 15, {
      'width': 170,
      'elementHandlers': specialElementHandlers
  });

  $('a').click(function(){
    doc.save('TestHTMLDoc.pdf');
  });
</script> -->

</body>