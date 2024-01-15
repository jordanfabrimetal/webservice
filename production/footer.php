      
      </div>
    </div>
    
    <!-- Modal HTML -->
    
     <style>
       #modalFirma .modal-body {
         height: 80vh;
       }
     </style>

    <div class="modal fade" id="modalFirma">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">

        <!-- Modal body -->
        <div class="modal-body">
          <iframe src="" width="100%" height="100%" frameborder="0" allowtransparency="true"></iframe>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>

      </div>
    </div>
    </div>

    <!-- jQuery -->
    <script src="../public/build/js/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../public//build/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../public/build/js/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../public/build/js/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="../public/build/js/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="../public/build/js/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="../public/build/js/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="../public/build/js/icheck.min.js"></script>


    <!-- bootstrap-fileinput -->
    <script src="../public/build/js/fileinput.min.js"></script>
    <!-- bootstrap-select -->
    <script src="../public/build/js/bootstrap-select.min.js"></script>

    <!-- Datatables -->
    <script src="../public/build/js/jquery.dataTables.min.js"></script>
    <script src="../public/build/js/dataTables.bootstrap.min.js"></script>
    <script src="../public/build/js/dataTables.buttons.min.js"></script>
    <script src="../public/build/js/buttons.bootstrap.min.js"></script>
    <script src="../public/build/js/buttons.flash.min.js"></script>
    <script src="../public/build/js/buttons.html5.min.js"></script>
    <script src="../public/build/js/buttons.print.min.js"></script>
    <script src="../public/build/js/dataTables.fixedHeader.min.js"></script>
    <script src="../public/build/js/dataTables.keyTable.min.js"></script>
    <script src="../public/build/js/dataTables.responsive.min.js"></script>
    <script src="../public/build/js/responsive.bootstrap.js"></script>
    <script src="../public/build/js/dataTables.scroller.min.js"></script>
    <script src="../public/build/js/jszip.min.js"></script>
    <script src="../public/build/js/pdfmake.min.js"></script>
    <script src="../public/build/js/vfs_fonts.js"></script>

    <!-- Bootbox Alert -->
    <script src="../public/build/js/bootbox.min.js"></script>

    <!-- jquery.inputmask -->
    <script src="../public/build/js/jquery.inputmask.bundle.min.js"></script>

    
    <!-- Switchery -->
    <script src="../public/build/js/switchery.min.js"></script>
    <!-- Select2 -->
    <script src="../public/build/js/select2.full.min.js"></script>
    <!-- Autosize -->
    <script src="../public/build/js/autosize.min.js"></script>
    <!-- jQuery autocomplete -->
    <script src="../public/build/js/jquery.autocomplete.min.js"></script>
    
    <!-- PNotify -->
    <script src="../public/build/js/pnotify.js"></script>
    <script src="../public/build/js/pnotify.buttons.js"></script>
    <script src="../public/build/js/pnotify.nonblock.js"></script>
    
    <!-- morris.js -->
    <script src="../public/build/js/raphael.min.js"></script>
    <script src="../public/build/js/morris.min.js"></script>
    
    <!-- ECharts -->
    <script src="../public/build/js/echarts.min.js"></script>

    <!-- DateJS -->
    <script src="../public/build/js/date.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../public/build/js/custom.js"></script>

    <div id="loadingDiv"><img src="../public/build/images/gif-sap.gif" id="imgLoading"></div>
    <script>
        $(document).ajaxStart(function () {
            $('#loadingDiv').show();
            $.get("../ajax/usuario.php?op=verificasesion",function(data,status){if(data=='login'){$(location).attr('href','login.php');}});
        }).ajaxStop(function () {
          $('#loadingDiv').hide();
        });
    </script>
    
    <script>
      $(document).ready(function() {
        var url;
        $(".showModal").click(function(e) {
          e.preventDefault();
          url = $(this).attr("data-href");
        });

        $('#modalFirma').on('shown.bs.modal', function () {
            const body1 = document.getElementById('modalFirma');
            $("#modalFirma iframe").attr("src", url);
        });
        
        /*var href = document.location.href;
        var lastPathSegment = href.substr(href.lastIndexOf('/') + 1);
        alert(lastPathSegment);*/
<?php
$nameActualPage = basename($_SERVER['PHP_SELF']);
if (isset($_GET['status'])) {
  if ($_GET['status'] == 'sinfirma')
    echo 'window.location.href = "digitalsignature.php";';
}
?>
      });
    </script>
	
  </body>
</html>