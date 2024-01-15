<?php
if (strlen(session_id()) < 1) {
    session_name('SESS_GSAP');
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>App Fabrimetal</title>

        <!-- <link rel="icon" href="../public/favicon.ico" type="image/x-icon" /> -->

        <!-- Bootstrap -->
        <link href="../public/build/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="../public/build/css/font-awesome.min.css" rel="stylesheet">
        <!-- NProgress -->
        <link href="../public/build/css/nprogress.css" rel="stylesheet">
        <!-- iCheck -->
        <link href="../public/build/css/green.css" rel="stylesheet">

        <!-- Datatables -->
        <link href="../public/build/css/dataTables.bootstrap.min.css" rel="stylesheet">
        <link href="../public/build/css/buttons.bootstrap.min.css" rel="stylesheet">
        <link href="../public/build/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
        <link href="../public/build/css/responsive.bootstrap.min.css" rel="stylesheet">
        <link href="../public/build/css/scroller.bootstrap.min.css" rel="stylesheet">

        <!-- FORMS -->
        <link href="../public/build/css/prettify.min.css" rel="stylesheet">
        <link href="../public/build/css/select2.min.css" rel="stylesheet">
        <link href="../public/build/css/switchery.min.css" rel="stylesheet">
        <link href="../public/build/css/starrr.css" rel="stylesheet">
        
        <!-- PNotify -->
        <link href="../public/build/css/pnotify.css" rel="stylesheet">
        <link href="../public/build/css/pnotify.buttons.css" rel="stylesheet">
        <link href="../public/build/css/pnotify.nonblock.css" rel="stylesheet">
        
        <!-- bootstrap-file-imput -->
        <link href="../public/build/css/fileinput.min.css" rel="stylesheet">

        <!-- bootstrap-select -->
        <link href="../public/build/css/bootstrap-select.min.css" rel="stylesheet">

        <!-- bootstrap-progressbar -->
        <link href="../public/build/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">

        <!-- bootstrap-daterangepicker -->
        <link href="../public/build/css/daterangepicker.css" rel="stylesheet">

        <!-- Custom Theme Style -->
        <link href="../public/build/css/custom.css" rel="stylesheet">

        <style>
            #loadingDiv{
              display: none;
              position:fixed;
              top:0px;
              right:0px;
              width:100%;
              height:100%;
              background-color:#666;
              z-index:10000000;
              opacity: 0.9;
              filter: alpha(opacity=40); /* For IE8 and earlier */
            }
            #imgLoading {
              position: absolute;
              margin: auto;
              top: 0;
              left: 0;
              right: 0;
              bottom: 0;
            }
        </style>
    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <div class="col-md-3 left_col">
                    <div class="left_col scroll-view">
                        <div class="navbar nav_title" style="border: 0;">
                            <a href="estado.php" class="site_title"><i class="fa fa-building-o"></i> <span>Fabrimetal</span></a>
                        </div>

                        <div class="clearfix"></div>

                        <!-- menu profile quick info -->
                        <div class="profile clearfix">
                            <div class="profile_pic">
                                <img src="../files/usuarios/<?php echo $_SESSION['imagen'] ?>" alt="..." class="img-circle profile_img">
                            </div>
                            <div class="profile_info">
                                <span>Bienvenido,</span>
                                <h2><?php echo $_SESSION['nombre'] . " " . $_SESSION['apellido'] ?></h2>
                            </div>
                        </div>
                        <!-- /menu profile quick info -->

                        <br />

                        <!-- sidebar menu -->
                        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                            <div class="menu_section">
                                <h3>Fabrimetal</h3>
                                <ul class="nav side-menu">
                                    <li><a><i class="fa fa-home"></i>Dashboard<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="estado.php">Estado</a></li>                    
                                        </ul>
                                    </li>
                                    <?php
                                    if ($_SESSION['administrador'] == 1 || $_SESSION['Guia'] == 1) {
                                        ?>
                                        <li><a><i class="fa fa-map-marker"></i>Guia de Servicio<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a href="servicio.php">Guia de Servicio</a></li>
                                                <li><a href="serfirma.php">Guias / Informes por firma</a></li>
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                     if ($_SESSION['administrador'] == 1 || $_SESSION['FC3'] == 1) {
                                        ?>
                                        <!--<li><a><i class="fa fa-map-marker"></i>Emergencias<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a href="llamadaemergencia.php">Llamada de Emergencia</a></li>
                                            </ul>
                                        </li>-->
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <!-- /sidebar menu -->

                        <!-- /menu footer buttons -->
                        <div class="sidebar-footer hidden-small">
                            <a data-toggle="tooltip" data-placement="top" title="Cerrar sesion" href="../ajax/usuario.php?op=salir">
                                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                            </a>
                        </div>
                        <!-- /menu footer buttons -->
                    </div>
                </div>

                <!-- top navigation -->
                <div class="top_nav">
                    <div class="nav_menu">
                        <nav>
                            <div class="nav toggle">
                                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                            </div>

                            <ul class="nav navbar-nav navbar-right">
                                <li class="">
                                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <img src="../files/usuarios/<?php echo $_SESSION['imagen'] ?>" alt=""><?php echo $_SESSION['nombre'] . " " . $_SESSION['apellido'] ?>
                                        <span class=" fa fa-angle-down"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                                        <li><a href="#"> Modificar Perfil</a></li>
                                        <li>
                                            <a href="#" data-href="../production/digitalsignature.php?context=firmatecnico" data-toggle="modal" data-target="#modalFirma" class="showModal">
                                                <span class="badge bg-red pull-right">Actualizar</span>
                                                <span>Firma digital</span>
                                            </a>
                                        </li>
                                        <li><a href="javascript:;">Ayuda</a></li>
                                        <li><a href="../ajax/usuario.php?op=salir"><i class="fa fa-sign-out pull-right"></i> Cerrar sesion</a></li>
                                    </ul>
                                </li>

                                <li role="presentation" class="dropdown">
                                    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-envelope-o"></i>
                                        <span class="badge bg-green">6</span>
                                    </a>
                                    <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                                        <li>
                                            <a>
                                                <span class="image"><img src="../files/usuarios/<?php echo $_SESSION['imagen'] ?>" alt="Profile Image" /></span>
                                                <span>
                                                    <span><?php echo $_SESSION['nombre'] . " " . $_SESSION['apellido'] ?></span>
                                                    <span class="time">3 mins ago</span>
                                                </span>
                                                <span class="message">
                                                    Nuevo cliente
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a>
                                                <span class="image"><img src="../files/usuarios/<?php echo $_SESSION['imagen'] ?>" alt="Profile Image" /></span>
                                                <span>
                                                    <span><?php echo $_SESSION['nombre'] . " " . $_SESSION['apellido'] ?></span>
                                                    <span class="time">3 mins ago</span>
                                                </span>
                                                <span class="message">
                                                    Nuevo cliente
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a>
                                                <span class="image"><img src="../files/usuarios/<?php echo $_SESSION['imagen'] ?>" alt="Profile Image" /></span>
                                                <span>
                                                    <span><?php echo $_SESSION['nombre'] . " " . $_SESSION['apellido'] ?></span>
                                                    <span class="time">3 mins ago</span>
                                                </span>
                                                <span class="message">
                                                    Nuevo cliente
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a>
                                                <span class="image"><img src="../files/usuarios/<?php echo $_SESSION['imagen'] ?>" alt="Profile Image" /></span>
                                                <span>
                                                    <span><?php echo $_SESSION['nombre'] . " " . $_SESSION['apellido'] ?></span>
                                                    <span class="time">3 mins ago</span>
                                                </span>
                                                <span class="message">
                                                    Nuevo cliente
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="text-center">
                                                <a>
                                                    <strong>Listar todas</strong>
                                                    <i class="fa fa-angle-right"></i>
                                                </a>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>