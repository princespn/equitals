<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>{{Config::get('constants.settings.projectname')}} Admin Panel</title>
        <meta content="Admin Dashboard" name="description" />
        <meta content="ThemeDesign" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="{{ asset('admin_assets/images/favicon.png') }}">
        <link href="{{ asset('admin_assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('admin_assets/plugins/datatables/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin_assets/plugins/datatables/responsive.bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin_assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
        <!-- {{-- sanket --}} -->
        <!-- Export to CSV -->
        <link href="{{ asset('admin_assets/plugins/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
        <link href="{{ asset('admin_assets/plugins/datatables/buttons.dataTables.min.css') }}" rel="stylesheet">
        <!-- End Export to CSV -->
        <!--Morris Chart CSS -->
        <link rel="stylesheet" href="{{ asset('admin_assets/plugins/morris/morris.css') }}">
        <link rel="stylesheet" href="{{ asset('admin_assets/plugins/chartist/css/chartist.min.css') }}">
        <!-- Summernote css -->
        <link href="{{ asset('admin_assets/plugins/summernote/summernote.css') }}" rel="stylesheet" />
        <!--bootstrap-wysihtml5-->
        <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}">
        <link href="{{ asset('admin_assets/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('admin_assets/css/icons.css') }}" rel="stylesheet" type="text/css">
        <!-- {{-- <link rel="stylesheet" href="https://unpkg.com/vue-toastr-2/dist/vue-toastr-2.min.css"> --}} -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700|Source+Sans+Pro:400,600,700">
        <link href="{{ asset('admin_assets/css/style.css') }}" rel="stylesheet" type="text/css">
    </head>
    <body>
        
        <!-- content view -->
        <div id="app"></div>
        <!-- content view end-->
        <!-- jQuery  -->
        <script src="{{ asset('js/app.js')}}"></script>
        <script src="{{ asset('admin_assets/js/jquery.min.js') }}"></script>
        <!-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script> -->
        <script src="{{ asset('admin_assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('admin_assets/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('admin_assets/js/jquery-ui.min.js') }}"></script>
        <!--Date Picker-->
        <script src="{{ asset('admin_assets/plugins/timepicker/bootstrap-timepicker.js') }}"></script>
        <script src="{{ asset('admin_assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
        <script src="{{ asset('admin_assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
        <script src="{{ asset('admin_assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('admin_assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('admin_assets/pages/form-advanced.js') }}"></script>
        <script src="{{ asset('admin_assets/js/modernizr.min.js') }}"></script>
        <script src="{{ asset('admin_assets/js/detect.js') }}"></script>
        <script src="{{ asset('admin_assets/js/fastclick.js') }}"></script>
        <script src="{{ asset('admin_assets/js/jquery.slimscroll.js') }}"></script>
        <script src="{{ asset('admin_assets/js/jquery.blockUI.js') }}"></script>
        <script src="{{ asset('admin_assets/js/waves.js') }}"></script>
        <script src="{{ asset('admin_assets/js/jquery.nicescroll.js') }}"></script>
        <script src="{{ asset('admin_assets/js/jquery.scrollTo.min.js') }}"></script>
        <script src="{{ asset('admin_assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js') }}"></script>
        <script src="{{ asset('admin_assets/plugins/summernote/summernote.min.js') }}"></script>
        <script src="{{ asset('admin_assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js') }}"></script>
        <script src="{{ asset('admin_assets/js/app.js') }}"></script>
        <script src="{{ asset('admin_assets/plugins/morris/morris.min.js') }}"></script>
        <script src="{{ asset('admin_assets/plugins/raphael/raphael-min.js') }}"></script>
        <script src="{{ asset('admin_assets/pages/dashborad.js') }}"></script>
        <!--RSPV data tables-->
        <script src="{{ asset('admin_assets/plugins/datatables/dataTables.bootstrap.js') }}"></script>
        <script src="{{ asset('admin_assets/plugins/datatables/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('admin_assets/plugins/datatables/responsive.bootstrap.min.js') }}"></script>
        <!-- Export to CSV -->
        <script src="{{ asset('admin_assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="{{ asset('admin_assets/plugins/datatables/pdfmake.min.js') }}"></script>
        <script src="{{ asset('admin_assets/plugins/datatables/vfs_fonts.js') }}"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
        <!-- End Export to CSV -->
        <!-- Datatable init js -->
        <script src="{{ asset('admin_assets/pages/datatables.init.js') }}"></script>
        <!-- vue toastr -->
        <!-- {{-- <script src="https://unpkg.com/vue/dist/vue.js"></script>
        <script src="https://unpkg.com/vue-toastr-2/dist/vue-toastr-2.js"></script> --}} -->
        <!--RSPV data tables Ends-->
        <script>
            // $(window).resize(function () {
            //     $('.slimscrollleft').slimscroll({
            //         height: 'auto',
            //         position: 'right',
            //         size: '5px',
            //         color: '#9ea5ab'
            //     });
            // });
            $(document).ready(function(){
                setTimeout(function(){
                  display_c();
               },2500);
                $('.summernote').summernote({
                    height: 200,                 // set editor height
                    minHeight: null,             // set minimum height of editor
                    maxHeight: null,             // set maximum height of editor
                });

            });
        </script>
        <script type="text/javascript">
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
      function myFunction(id = "referral_input_left",ref="refcopy") {
            var copyText = document.getElementById( id );
            copyText.select();
            document.execCommand("copy");

            /*var tooltip = document.getElementById(ref);*/
            tooltip.innerHTML = "Copied";
        }
        </script>
    </body>
</html>