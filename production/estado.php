<?php

ob_start();
session_name('SESS_GSAP');
session_start();

if(!isset($_SESSION["nombre"])){
    echo '<script>console.log("");</script>';
    //header("Location:login.php");
}else{
echo '<script>console.log("'.$_SESSION['idSAP'].'");</script>';
require 'header.php';


 ?>
        <!-- Contenido -->
        <div class="right_col" role="main">
          <!-- Datos actulidad en fallas -->
          <div class="row tile_count">
            <div class="col-md-4 col-sm-12 col-xs-12 tile_stats_count">
              <span class="count_top"><i class="fa fa-check"></i> Servicios en el año </span>
              <div class="count"><a href="#" id="num_año"></a></div>            
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12 tile_stats_count">
              <span class="count_top"><i class="fa fa-exclamation-triangle"></i> Servicios en el Mes</span>
              <div class="count"><a href="#" id="num_mes"></a></div>              
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12 tile_stats_count">
              <span class="count_top"><i class="fa fa-exclamation-triangle"></i> Servicios en el dia</span>
              <div class="count"><a href="#" id="num_dia"></a></div>  
            </div>
          </div>

          <br />

		  <!-- Contenido Graficos -->
          <div id="Charts" class="row">         
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Servicios en el año</h2>                   
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <canvas id="mybarChart"></canvas>
                  </div>
                </div>
              </div>           
          </div>
          <!-- /Fin contenido graficos -->
          
        </div>
        <!-- /Fin Contenido -->

<?php 
require 'footer.php';
?>
<script id="myScript"></script>
<script>
    var url = 'scripts/estado.js';
    var extra = '?t=';
    var randomNum = String((Math.floor(Math.random() * 20000000000)));
    document.getElementById('myScript').src = url + extra + randomNum;
</script>

<?php
}
ob_end_flush();
?>
