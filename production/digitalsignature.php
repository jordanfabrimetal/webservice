<?php
ob_start();
session_name('SESS_GSAP');
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location:login.php");
} else {

    require 'header.php';
        ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
              </div>
            </div>
          </div>
        </div>
            <?php
        require 'footer.php';
        ?>
            <script>
                  $(document).ready(function() {
                    $(".showModal").trigger("click");
                  });
                </script>
        <?php
    }
    ob_end_flush();
    ?>