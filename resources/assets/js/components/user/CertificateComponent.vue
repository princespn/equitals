
<style type="text/css">
  table tr td{
    vertical-align: top;
  }
  p{
    margin-bottom: 0px !important;
  }
  .inv_btm_c h6{
    margin: 4px 0px 0px 10px;
      font-size: 11px;
  }
  .inv_btm_c ol li {
      line-height: 12px;
      font-size: 9px;
}
.inv_head2 p, .inv_head3 p {
    margin-bottom: 1px;
    font-size: 12px;
    color: #000;
    line-height: 18px;
}
</style>

<template>
  <div>
    <div id="printableArea" v-if="topupArr" class="">
      <br>
      <br>
      <br>
      <br>
      <br>
      <br>
      <br>
      <br>
      <br>
      <div id="pdfcontent" class="certificate_o" style="border:1px solid #000; margin: 15px auto 100px; padding: 0; background:#fff; max-width: 450px;">


        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 2px 0px; border: 1px solid #000; border-left: 0; border-right: 0; border-top: 0; border-bottom: 0px;">
          <tr>
            <td style="">
            <div class="invoice_header">
              <div style="text-align: center; padding: 15px 0;">
                <img src="public/user_files/images/logo.png">
              </div>
              <!-- <h1 style="font-size: 30px; text-align: center; text-transform:uppercase; margin: 0px; padding: 0px; color: #209c3d; font-weight: 600; padding: 5px;">Private Limited</h1> -->
            </div>
            </td>
          </tr>
          <tr>
            <td>
            <br>
            <h5 style="font-size: 15px; text-align: center; margin: 0px; padding: 0px; color: #209c3d; font-weight: 600; padding: 5px;">www.equitals.com</h5>
          </td>
          </tr>

          <tr>
            <td style="border: 1px solid #000; border-right: 0; border-left: 0; padding: 5px 0px;">
              <h5 style="font-size: 18px; text-transform: uppercase; text-align: center; margin: 0px; color: #000;"> Investment Certificate</h5>
            </td>
          </tr>
          <tr>
            <td style="padding: 0 15px;">
              <table style="width: 100%; padding: 2px 0px; border: 1px solid #000; border-left: 0; border-right: 0; border-top: 0; border-bottom: 0px;" cellpadding="0" cellspacing="0" border="0">
                <tr >
                  <td style="padding: 5px;"><strong>User Id</strong></td>
                  <td style="padding: 5px;">: {{user_id}}</td>
                </tr>

                <tr >
                  <td style="padding: 5px;"><strong>Deposit Id</strong></td>
                  <td style="padding: 5px;">: {{user_id}}</td>
                </tr>
                <tr >
                  <td style="padding: 5px;"><strong>Currency </strong></td>
                  <td style="padding: 5px;">: {{currency}}</td>
                </tr>
                <tr>
                  <td style="padding: 5px;"><strong>Date </strong></td>
                  <td style="padding: 5px;">: {{date1}}</td>
                </tr>
               <tr>
                  <td style="padding: 5px;"><strong>Amount </strong></td>
                  <td style="padding: 5px;">: {{amount}}</td>
                </tr>

              </table>
            </td>

          </tr>
          <br>
          <br>
          <br>

         <tr>

          <td style="border:none !important; text-align: center;padding: 15px; border-top: 1px solid #000;" width="100%">
            <button style="font-size:14px;" @click="printInvoice"> Print me</button>
            <button id="exportForm" @click="exportPdf">Export to  PDF</button>
          </td>

        </tr>
        </table>





</div>
</div>
<div v-if="!topupArr">
  <h2>No Invoice Generated</h2>
</div>
</div>
</template>

<script>

  export default {
    data() {

      return {
        topupArr:[],
        topupArr1:[],
        topup_id:'',
        userdata: [],


        date1: '',
        amount: '',
        user_id: '',
        currency: '',
        // deposit_id:'',

      }
    },
    mounted() {

      var topup_id = this.$route.params.topup_id;
         if((topup_id == undefined) || (topup_id=='')) {
            // this.$router.push({name: 'topup-report'});
            // return ;
          }
          else
          {
            this.topup_id=topup_id;
          }
          this.amount = this.$route.params.amount,
          this.currency = this.$route.params.currency,
          this.user_id = this.$route.params.user_id,
          this.date1 = this.$route.params.date1

    },
    methods: {
      printInvoice(){

        var DocumentContainer = document.getElementById('printableArea');
        var WindowObject = window.open('', "PrintWindow", "width=750,height=650,top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes");
        WindowObject.document.writeln(DocumentContainer.innerHTML);
        WindowObject.document.close();
        WindowObject.focus();
        WindowObject.print();
        WindowObject.close();
        newWin.document.write(divToPrint.innerHTML);
        newWin.print();
        newWin.close();
      },
      exportPdf(){
        html2canvas($('#pdfcontent'), {
        onrendered: function(canvas) {
          var pdf = new jsPDF('a', 'mm', 'a4');

          var imgData = canvas.toDataURL('image/jpeg', 1.0);
          pdf.addImage(imgData, 'JPEG', 5, 5, 200, 0);

          pdf.save("export.pdf");
        }
      });
      }
    }
  }
</script>
