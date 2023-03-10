/**
 * Theme: Upbond
 * Form Advanced
 */

! function(e) {
    "use strict";
    var t = function() {};
    t.prototype.init = function() {
     jQuery("#timepicker").timepicker({
      defaultTIme: !1
     }), jQuery("#timepicker2").timepicker({
      showMeridian: !1
     }), jQuery("#timepicker3").timepicker({
      minuteStep: 15
     }), e(".colorpicker-default").colorpicker({
      format: "hex"
     }), e(".colorpicker-rgba").colorpicker(), jQuery("#datepicker").datepicker(), jQuery("#datepicker-autoclose").datepicker({
      autoclose: !0,
      todayHighlight: !0
     }), jQuery("#datepicker-inline").datepicker(), jQuery("#datepicker-multiple-date").datepicker({
      format: "mm/dd/yyyy",
      clearBtn: !0,
      multidate: !0,
      multidateSeparator: ","
     }), jQuery("#date-range").datepicker({
      toggleActive: !0
     }), e("input#defaultconfig").maxlength(), e("input#thresholdconfig").maxlength({
      threshold: 20
     }), e("input#moreoptions").maxlength({
      alwaysShow: !0,
      warningClass: "label label-success",
      limitReachedClass: "label label-danger"
     }), e("input#alloptions").maxlength({
      alwaysShow: !0,
      warningClass: "label label-success",
      limitReachedClass: "label label-danger",
      separator: " out of ",
      preText: "You typed ",
      postText: " chars available.",
      validate: !0
     }), e("textarea#textarea").maxlength({
      alwaysShow: !0
     }), e("input#placement").maxlength({
      alwaysShow: !0,
      placement: "top-left"
     }), e(".vertical-spin").TouchSpin({
      verticalbuttons: !0,
      verticalupclass: "ion-plus-round",
      verticaldownclass: "ion-minus-round"
     }), e("input[name='demo1']").TouchSpin({
      min: 0,
      max: 100,
      step: .1,
      decimals: 2,
      boostat: 5,
      maxboostedstep: 10,
      postfix: "%"
     }), e("input[name='demo2']").TouchSpin({
      min: -1e9,
      max: 1e9,
      stepinterval: 50,
      maxboostedstep: 1e7,
      prefix: "$"
     }), e("input[name='demo3']").TouchSpin(), e("input[name='demo3_21']").TouchSpin({
      initval: 40
     }), e("input[name='demo3_22']").TouchSpin({
      initval: 40
     }), e("input[name='demo5']").TouchSpin({
      prefix: "pre",
      postfix: "post"
     }), e("input[name='demo0']").TouchSpin({})
    }, e.AdvancedForm = new t, e.AdvancedForm.Constructor = t
   }(window.jQuery),
   function(e) {
    "use strict";
    e.AdvancedForm.init()
   }(window.jQuery);