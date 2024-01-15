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

        <link rel="icon" href="../public/favicon.ico" type="image/x-icon" />

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

        <!-- PNotify -->
        <link href="../public/build/css/pnotify.css" rel="stylesheet">
        <link href="../public/build/css/pnotify.buttons.css" rel="stylesheet">
        <link href="../public/build/css/pnotify.nonblock.css" rel="stylesheet">

        <!-- FORMS -->
        <link href="../public/build/css/prettify.min.css" rel="stylesheet">
        <link href="../public/build/css/select2.min.css" rel="stylesheet">
        <link href="../public/build/css/switchery.min.css" rel="stylesheet">
        <link href="../public/build/css/starrr.css" rel="stylesheet">

        <!-- bootstrap-file-imput -->
        <link href="../public/build/css/fileinput.min.css" rel="stylesheet">

        <!-- bootstrap-select -->
        <link href="../public/build/css/bootstrap-select.min.css" rel="stylesheet">

        <!-- bootstrap-daterangepicker -->
        <link href="../public/build/css/daterangepicker.css" rel="stylesheet">
        <!-- bootstrap-datetimepicker -->
        <link href="../public/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
        <!-- Ion.RangeSlider -->
        <link href="../public/build/css/normalize.css" rel="stylesheet">
        <link href="../public/build/css/ion.rangeSlider.css" rel="stylesheet">
        <link href="../public/build/css/ion.rangeSlider.skinFlat.css" rel="stylesheet">
        <!-- Bootstrap Colorpicker -->
        <link href="../public/build/css/bootstrap-colorpicker.min.css" rel="stylesheet">

        <!-- bootstrap-progressbar -->
        <link href="../public/build/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
        <!-- JQVMap -->
        <link href="../public/build/css/jqvmap.min.css" rel="stylesheet"/>
        <!-- bootstrap-daterangepicker -->
        <link href="../public/build/css/daterangepicker.css" rel="stylesheet">

        <!-- FullCalendar -->
        <link href="../public/build/css/fullcalendar.min.css" rel="stylesheet">
        <link href="../public/build/css/fullcalendar.print.css" rel="stylesheet" media="print">

        <link href="../public/build/css/dropzone.min.css" rel="stylesheet">

        <!-- Custom Theme Style -->
        <link href="../public/build/css/custom.css" rel="stylesheet">
    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <div class="col-md-3 left_col">
                    <div class="left_col scroll-view">
                        <div class="navbar nav_title" style="border: 0;">
                            <a href="inicio.php" class="site_title"><img src="../public/build/images/logo.png" height="40" width="210"> <span></span></a>
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
                                    <li><a href="inicio.php"><i class="fa fa-home"></i>Inicio<span class="fa fa-chevron-down"></span></a>
                                    </li>
                                    <?php
                                    if ($_SESSION['administrador'] == 1 || $_SESSION['Gerencia'] == 1) {
                                        ?>
                                        <li><a><i class="fa fa-map-marker"></i>Gerencia<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                
                                                 <?php
                                                    if ($_SESSION['administrador'] == 1 || $_SESSION['DGerencia'] == 1) {
                                                 ?>
                                                <li><a>Dashboard <span class="fa fa-chevron-down"></span></a>
                                                    <ul class="nav child_menu">
                                                        <li><a href="dashventa.php">Comercial</a></li>
                                                        <li><a href="dashinstalaciones.php">Instalaciones</a></li>
                                                        <li><a href="dashboardinstalaciones.php">Instalaciones por PM</a></li>
                                                        <li><a href="dashguiasservicio.php">GSE</a></li>
                                                        <li><a href="dashcontabilidad2.php">Facturación GSE</a></li>
                                                        <li><a href="dashcontratos2.php">Contratos</a></li> 
                                                        <li><a href="llamadas.php">FC3</a></li>
                                                        <li><a href="informecallcenter.php">FC3 Llamadas</a></li>
                                                    </ul>
                                                </li>
                                                <?php
                                                }
                                                if ($_SESSION['administrador'] == 1 || $_SESSION['LGerencia'] == 1) {
                                                ?>
                                                <li><a>Listados <span class="fa fa-chevron-down"></span></a>
                                                    <ul class="nav child_menu">
                                                        <li><a href="reportevisitas.php">Listado Instalaciones</a></li>
                                                        <li><a href="listadollamadas.php">Listado Llamadas Emergencia</a></li>
                                                    </ul>
                                                </li>
                                                <?php
                                                }
                                                if ($_SESSION['administrador'] == 1 || $_SESSION['TVGerencia'] == 1) {
                                                ?>                                               
                                                <li><a>Pantallas <span class="fa fa-chevron-down"></span></a>
                                                    <ul class="nav child_menu">
                                                        <li><a href="pantallacontabilidad.php">Facturación GSE</a></li>
                                                        <li><a href="pantallagse.php">GSE</a></li>
                                                        <li><a href="pantallainstalaciones.php">Instalaciones</a></li>
                                                        <li><a href="pantallainstalacion.php">Proyectos Instalaciones</a></li>
                                                        <li><a href="pantallacontratos.php">Contratos</a></li>
                                                        <li><a href="slidecontabilidad.php">Slider Gerencia</a></li>
                                                    </ul>
                                                </li>
                                                <?php
                                                }
                                                ?> 
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    if ($_SESSION['administrador'] == 1 || $_SESSION['Contratos'] == 1) {
                                        ?>
                                        <li><a><i class="fa fa-map-marker"></i>Contratos<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a href="dashcontratos2.php">Dashboard Contratos</a></li> 
                                                <li><a href="contrato2.php">Contratos</a></li>
                                                <li><a href="contratoinstalacion.php">Contratos NEB</a></li>
                                                <li><a href="ascensor.php">Ascensores</a></li>
                                                <li><a href="edificio.php">Edificios</a></li>
                                                <li><a href="cliente.php">Clientes</a></li>           
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    if ($_SESSION['administrador'] == 1) {
                                        ?>

                                        <li><a><i class="fa fa-map-marker"></i>Informatica<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a>Pantallas<span class="fa fa-chevron-down"></span></a>
                                                    <ul class="nav child_menu">
                                                        <li><a href="arduino2.php">Lista Arduinos</a></li>
                                                        <li><a href="slidecontabilidad.php">Slider Gerencia</a></li>
                                                    </ul>
                                                </li>
                                                <li><a>Monitoreo<span class="fa fa-chevron-down"></span></a>
                                                    <ul class="nav child_menu">     
                                                        <li><a href="monitoreo.php">Monitoreo</a></li>
                                                        <li><a href="monitoreogse.php">Monitoreo GSE</a></li>
                                                        <li><a href="arduino.php">Arduinos</a></li>
                                                        <li><a href="arduino2.php">Lista Arduinos</a></li>
                                                        <li><a href="dirk.php">Lista Dirk</a></li>
                                                    </ul>
                                                </li>
                                                <li><a href="idnumbers.php">Etiquetado </a></li>
                                                <li><a>Sol. información web <span class="fa fa-chevron-down"></span></a>
                                                    <ul class="nav child_menu">
                                                        <li class="sub_menu"><a href="solinformacion.php">Solicitud de información</a></li>
                                                        <li><a href="contactoinfo.php">Contactos info</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    if ($_SESSION['administrador'] == 1 || $_SESSION['Instalaciones'] == 1) {
                                        ?>

                                        <li><a><i class="fa fa-map-marker"></i>Instalaciones<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a href="visita.php">Proyectos</a></li>
                                                <?php if ($_SESSION['AsigProyectos'] == 1 || $_SESSION['administrador'] == 1) { ?>
                                                    <li><a href="proyecto.php">Asig. P. Manager / Supervisor</a></li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    if ($_SESSION['administrador'] == 1 || $_SESSION['Modernizaciones'] == 1) {
                                        ?>

                                        <li><a><i class="fa fa-map-marker"></i>Modernización<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a href="modernizacion.php">Proyectos</a></li>
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    if ($_SESSION['administrador'] == 1 || $_SESSION['Comercial'] == 1) {
                                        ?>

                                        <li><a><i class="fa fa-map-marker"></i>Comercial <span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <?php
                                                if ($_SESSION['administrador'] == 1 || $_SESSION['Preventa'] == 1) {
                                                    ?> 
                                                    <li><a href="preventa.php">Memo de venta</a></li>
                                                    <?php
                                                }
                                                if ($_SESSION['administrador'] == 1 || $_SESSION['Aprobacion'] == 1) {
                                                    ?> 
                                                    <li><a href="aproventas.php">Aprobacion</a></li>
                                                    <?php
                                                }
                                                if ($_SESSION['administrador'] == 1 || $_SESSION['VentaFacturacion'] == 1) {
                                                    ?>     
                                                    <li><a href="ventafact.php">Ventas</a></li>
                                                    <?php
                                                }
                                                ?> 
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    if ($_SESSION['administrador'] == 1 || $_SESSION['Adquisiciones'] == 1) {
                                        ?>

                                        <li><a><i class="fa fa-map-marker"></i>Adquisiciones <span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a href="soladquisiciones.php">Solicitud de Aquisición</a></li>
                                                <li><a href="aprosoladquisicion.php">Aprobación de Aquisición</a></li>
                                                <li><a><i class="fa fa-cog"></i>Configuración<span class="fa fa-chevron-down"></span></a>
                                                    <ul class="nav child_menu">
                                                        <li><a href="proveedor.php">Proveedores</a></li>
                                                        <li><a href="producto.php">Productos</a></li>
                                                        <li><a href="categoria_producto.php">Categoria de productos</a></li>
                                                        <li><a href="bodega.php">Bodega</a></li>
                                                        <li><a href="faseaprobacion.php">Fases de Aprobación</a></li>
                                                    </ul>
                                                </li>
                                                
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    if ($_SESSION['administrador'] == 1 || $_SESSION['Servicio'] == 1) {
                                        ?>

                                        <li><a><i class="fa fa-map-marker"></i>Servicios<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <?php
                                                if ($_SESSION['administrador'] == 1 || $_SESSION['Dllamadas'] == 1) {
                                                    ?>
                                                    <li><a href="llamadas.php">LLamadas</a></li>
                                                    <li><a href="gestionllamadas.php">Gesti&oacute;n de LLamadas</a></li>
                                                    <?php
                                                }
                                                if ($_SESSION['administrador'] == 1 || $_SESSION['DGuias'] == 1) {
                                                    ?>
                                                    <li><a href="dashguias.php">Servicios</a></li>
                                                    <li><a href="servicio2.php">GSE Historico</a></li>
                                                    <?php
                                                }
                                                if ($_SESSION['administrador'] == 1 || $_SESSION['GGuias'] == 1) {
                                                    ?>
                                                    <li><a href="servicio2.php">GSE Historico</a></li>
                                                    <li><a href="servicio.php">GSE</a></li>
                                                    <li><a href="serviciomes.php">GSE mes</a></li>
                                                    <li><a href="serpfirma.php">GSE por firmar</a></li>
                                                    <?php
                                                }
                                                if ($_SESSION['administrador'] == 1 || $_SESSION['GGuiasE'] == 1) {
                                                    ?>
                                                    <li><a href="seremergencia.php">GSE Emergencia</a></li>
                                                    <li><a href="listaemergencia.php">GSE Emergencia del dia</a></li>
                                                    <li><a href="tickets.php">Ticket Emergencia</a></li>
                                                    <li><a href="solatencion.php">Solicitudes Atencion</a></li>

                                                    <?php
                                                }
                                                if ($_SESSION['administrador'] == 1 || $_SESSION['APresupuesto'] == 1 || $_SESSION['GPresupuesto'] == 1 || $_SESSION['GEServicios'] == 1) {
                                                    ?>                                            
                                                    <li><a href="presupuesto.php">Presupuestos</a></li>
                                                    <li><a href="servicio2.php">GSE Historico</a></li>
                                                    <?php
                                                }
                                                if ($_SESSION['administrador'] == 1 || $_SESSION['RGuias'] == 1 || $_SESSION['RPresupuesto'] == 1) {
                                                    ?>
                                                    <li><a href="serpsup.php">GSE por firma Sup </a></li>
                                                    <li><a href="gsesup.php">GSE Mes Sup </a></li>
                                                    <li><a href="presupuestosup.php">Presupuestos Sup </a></li>
                                                    <li><a href="gsesupervisor.php">GSE Supervisor</a></li>
                                                    <?php
                                                }
                                                if ($_SESSION['administrador'] == 1 || $_SESSION['GEServicios'] == 1) {
                                                    ?>
                                                    <li><a href="dashservicios.php">Dashboard de Servicios</a></li>
                                                    <li><a href="dashpresupuestos.php">Lista Presupuestos</a></li>
                                                    <?php
                                                }
                                                if ($_SESSION['administrador'] == 1 || $_SESSION['GEServicios'] == 1 || $_SESSION['AReparacion'] == 1 || $_SESSION['GReparacion'] == 1) {
                                                    ?>
                                                    <li><a href="reparacion.php">Reparacion</a></li>
                                                    <?php
                                                } 
                                                if ($_SESSION['administrador'] == 1 || $_SESSION['GReclamos'] == 1 || $_SESSION['AReparacion'] == 1 || $_SESSION['GReparacion'] == 1) {
                                                    ?>
                                                    <li><a href="solreclamo.php">Solicitudes Reclamo</a></li>
                                                    <?php
                                                }
                                                ?>
                                            </ul>
                                        </li>

                                        <?php
                                    }
                                    if ($_SESSION['administrador'] == 1 || $_SESSION['Contabilidad'] == 1) {
                                        ?>
                                        <li><a><i class="fa fa-map-marker"></i>Contabilidad<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a href="centrocosto.php">Centros de Costo</a></li>
                                                <li><a href="#">Listar Clientes</a></li>
                                                <li><a href="equipo.php">Listar Equipos</a></li>
                                                <li><a href="servicio.php">GSE</a></li>
                                                <li><a href="facturaGSE.php">Factura - GSE</a></li>
                                                <li><a href="facturaservicio.php">Facturas</a></li>
                                                <li><a href="dashcontabilidad.php">Dashboard Contabilidad</a></li>
                                            </ul>
                                        </li>
                                        <?php
                                    }

                                    if ($_SESSION['administrador'] == 1 || $_SESSION['Comex'] == 1) {
                                        ?>
                                        <li><a><i class="fa fa-map-marker"></i>Comercio Exterior<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a href="importacion.php">Importación</a></li>
                                            </ul>
                                        </li>
                                        <?php
                                    }

                                    if ($_SESSION['administrador'] == 1 || $_SESSION['RRHH'] == 1) {
                                        ?>
                                        <li><a><i class="fa fa-map-marker"></i>RRHH<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a href="trabnosotros.php">Curriculum Pagina Web</a></li>
                                            </ul>
                                        </li>

                                        <?php
                                    }

                                    if ($_SESSION['administrador'] == 1) {
                                        ?>

                                        <li><a><i class="fa fa-map-marker"></i>Bodega<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a href="equiposizaje.php">Equipos de izaje</a></li>
                                                <li><a><i class="fa fa-map-marker"></i>Configuración<span class="fa fa-chevron-down"></span></a>
                                                    <ul class="nav child_menu">
                                                        <li><a href="marcaizaje.php">Marca</a></li>
                                                        <li><a href="modeloizaje.php">Modelo</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <?php
                                    }

                                    if ($_SESSION['administrador'] == 1) {
                                        ?>

                                        <li><a><i class="fa fa-map-marker"></i>Electricidad<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a href="equiposizaje.php">Equipos de izaje</a></li>
                                                <li><a><i class="fa fa-map-marker"></i>Configuración<span class="fa fa-chevron-down"></span></a>
                                                    <ul class="nav child_menu">
                                                        <li><a href="marcaizaje.php">Marca</a></li>
                                                        <li><a href="modeloizaje.php">Modelo</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <?php
                                    }

                                    if ($_SESSION['Pantallas'] == 1) {
                                        ?>
                                        <li><a><i class="fa fa-map-marker"></i>Pantallas<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a href="dashservicios.php">Dashboard Servicios</a></li>
                                                <li><a href="dashcontratos.php">Dashboard Contratos</a></li>
                                                <li><a href="dashguias.php">Dashboard GSE</a></li>
                                                <li><a href="dashpresupuestos.php">Lista Presupuestos</a></li>
                                                <li><a href="listaemergencia.php">Lista GSE Emergencia</a></li>
                                                <li><a href="listaatencion.php">Lista Atencion</a></li>
                                                <li><a href="llamadas.php">Dashboard Call Center</a></li>
                                                <li><a href="monitoreo.php">Monitoreo</a></li>
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    if ($_SESSION['SPantallas'] == 1) {
                                        ?>
                                        <li><a><i class="fa fa-map-marker"></i>Pantallas Servicios<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a href="dashpresupuestos.php">Lista Presupuestos</a></li>
                                                <li><a href="dashguias.php">Servicios</a></li>
                                                <li><a href="pantallagse.php">Dashboard GSE</a></li>
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>


                            <?php
                            if ($_SESSION['administrador'] == 1) {
                                ?>
                                <div class="menu_section">
                                    <h3>Mantenimiento</h3>
                                    <ul class="nav side-menu">

                                        <?php
                                        if ($_SESSION['administrador'] == 1) {
                                            ?>
                                            <li><a><i class="fa fa-bug"></i> Sistema <span class="fa fa-chevron-down"></span></a>
                                                <ul class="nav child_menu">
                                                    <li><a href="usuario.php">Usuarios</a></li>
                                                    <li><a href="role.php">Perfiles</a></li>
                                                    <li><a href="permiso.php">Permisos</a></li>
                                                </ul>
                                            </li>
                                            <?php
                                        }
                                        ?>

                                        <?php
                                        if ($_SESSION['administrador'] == 1) {
                                            ?>
                                            <li><a><i class="fa fa-bug"></i> Modulos <span class="fa fa-chevron-down"></span></a>
                                                <ul class="nav child_menu">
                                                    <li><a href="centrocosto.php">Centros de Costo</a></li>
                                                </ul>
                                            </li>
                                            <?php
                                        }
                                        ?>

                                    </ul>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <!-- /sidebar menu -->

                        <!-- /menu footer buttons -->
                        <div class="sidebar-footer hidden-small">
                            <a data-toggle="tooltip" data-placement="top" title="Pantalla completa" onclick="fullsecreen()">
                                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                            </a>
                            <a><span class="glyphicon" aria-hidden="true"></span></a>
                            <a><span class="glyphicon" aria-hidden="true"></span></a>
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
                                        <li><a href="javascript:;">Ayuda</a></li>
                                        <li><a href="../ajax/usuario.php?op=salir"><i class="fa fa-sign-out pull-right"></i> Cerrar sesion</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>