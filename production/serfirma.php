<?php
ob_start();
session_name('SESS_GSAP');
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location:login.php");
} else {

    require 'header.php';

    if ($_SESSION['Guia'] == 1) {
        ?>

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">

                <div class="clearfix"></div>

                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Servicios por firmar</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div id="listadoservicios" class="x_content">

                                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                        <li role="presentation" class="active"><a href="#tab_content1" role="tab" id="home-tab"  data-toggle="tab" aria-expanded="true">GUIAS</a>
                                        </li>
                                        <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab"  data-toggle="tab" aria-expanded="false">INFORMES</a>
                                        </li>
                                    </ul>

                                    <div id="myTabContent" class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                                            <table id="tblservicios" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>OPCIONES</th>
                                                        <th>SERVICIO</th>
                                                        <th>EQUIPO</th>
                                                        <th>EDIFICIO</th>
                                                        <th>FECHA</th>
                                                        <th>INICIO</th>
                                                        <th>FIN</th> 
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                                            <table id="tblencporfirmar" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>OPCIONES</th>
                                                        <th>Nº INFORME</th>
                                                        <th>Nº SERVICIO</th>
                                                        <th>TIPO SERVICIO</th>
                                                        <th>EQUIPO</th>
                                                        <th>PERIODO</th>
                                                        <th>FECHA VISITA</th>
                                                        <th>CLIENTE</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>    
                                        </div>
                                    </div>
                                </div>

                                <!-- <table id="tblservicios" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>OPCIONES</th>
                                            <th>SERVICIO</th>
                                            <th>EQUIPO</th>
                                            <th>EDIFICIO</th>
                                            <th>FECHA</th>
                                            <th>INICIO</th>
                                            <th>FIN</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table> -->
                            </div>


                            <div id="formularioservicio" class="x_content">
                                <br />
                                <div class="clearfix"></div>
                                <form id="formulario" name="formulario" method="post" class="form-horizontal form-label-left">
                                    <h4><b>INFORMACION DEL EQUIPO</b></h4>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Codigo</label>
                                        <input type="hidden" class="form-control" name="idactividad" id="idactividad">
                                        <input type="text" class="form-control" disabled="disabled" name="codigo" id="codigo">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Tipo de Ascensor</label>
                                        <input type="text" class="form-control" disabled="disabled" name="tascensor" id="tascensor">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Marca</label>
                                        <input type="text" class="form-control" disabled="disabled" name="marca" id="marca">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Modelo</label>
                                        <input type="text" class="form-control" disabled="disabled" name="modelo" id="modelo">
                                    </div>
                                    <h4><b>INFORMACION DEL EDIFICIO</b></h4>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Nombre</label>
                                        <input type="text" class="form-control" disabled="disabled" name="edificio" id="edificio">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Direccion</label>
                                        <input type="text" class="form-control" disabled="disabled" name="direccion" id="direccion">
                                    </div>
                                    <h4><b>INFORMACION DE INICIO DEL SERVICIO</b></h4>          
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Fecha inicio </label>
                                        <input type="text" class="form-control" disabled="disabled" name="fechaini" id="fechaini">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Hora inicio </label>
                                        <input type="text" class="form-control" disabled="disabled" name="horaini" id="horaini">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Tipo de servicio </label>
                                        <input type="text" class="form-control" disabled="disabled" name="tservicio" id="tservicio">
                                    </div>                                   
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Estado del equipo al inicio del servicio</label>
                                        <input type="text" class="form-control" disabled="disabled" name="estadoini" id="estadoini">
                                    </div>    
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label for="observacionini">Observaciones de inicio de servicio</label>
                                        <textarea type="text" id="observacionini" name="observacionini" disabled="disabled" class="resizable_textarea form-control"></textarea>
                                    </div>
                                    <h4><b>INFORMACION DE FINALIZACION DEL SERVICIO</b></h4>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Fecha finalizacion </label>
                                        <input type="text" class="form-control" disabled="disabled" name="fechafin" id="fechafin">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Hora finalizacion </label>
                                        <input type="text" class="form-control" disabled="disabled" name="horafin" id="horafin">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Estado del equipo despues del servicio</label>
                                        <input type="text" class="form-control" disabled="disabled" name="estadofin" id="estadofin">
                                    </div>                                      
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label for="observacionfin">Observaciones de finalizacion del servicio</label>
                                        <textarea type="text" id="observacionfin" name="observacionfin" disabled="disabled" class="resizable_textarea form-control"></textarea>
                                    </div>       
                                    <h4><b>DATOS DE VALIDACION DE SERVICIO</b></h4>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <input type="hidden" id="idserfirma" name="idserfirma" class="form-control">
                                        <label for="nombre">Nombre <span class="required">*</span></label>
                                        <input type="text" id="nombre" name="nombre" required="Campo requerido" class="form-control">
                                    </div>

                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label for="apellido">Apellido <span class="required">*</span></label>
                                        <input type="text" id="apellido" name="apellido" required="Campo requerido" class="form-control">
                                    </div>

                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label for="rut">RUT <span class="required">*</span></label>
                                        <input type="text" id="rut" name="rut" required="Campo requerido" class="form-control">
                                    </div>

                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label for="cargo">Cargo <span class="required">*</span></label>
                                        <input type="text" id="cargo" name="cargo" required="Campo requerido" class="form-control">
                                    </div>

                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label for="firma">Firma <span class="required">*</span></label>
                                        <div class="input-group">
                                            <input type="hidden" name="firma" id="firma">
                                            <input type="text"  class="form-control" name="firmavali" id="firmavali" style="pointer-events: none" required="required" readonly="readonly">
                                            <div class="input-group-btn"> 
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Opciones <span class="caret"></span></button>
                                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                    <li><a onclick="fijarfirma()">Validar</a></li>
                                                    <li class="divider"></li>
                                                    <li><a onclick="borrarfirma()">Borrar firma</a></li></ul>
                                                </ul>
                                            </div> 
                                        </div>    
                                    </div>

                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group" id="firmapad" name="firmapad">
                                        <div class="well">
                                            <canvas id="firmafi" id="firmafi" class="firmafi" style="border: 2px dashed #888; width: 100%;"></canvas>
                                        </div> 
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-sm-6 col-xs-12 left-margin">
                                            <button class="btn btn-primary" type="button" id="btnCancelar"  onclick="cancelarform()">Cancelar</button>
                                            <button class="btn btn-success" type="submit" id="btnGuardar">Validar</button>
                                        </div>
                                    </div>

                                </form>
                            </div>

                            <div id="otradata" style="display: none;" class="x_content">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /page content -->

            <div class="modal fade" id="modalPreview" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content" id="contenido">
                        
                    </div>
                </div>
            </div>

            <!-- /page content -->
            <?php
        } else {
            require 'nopermiso.php';
        }
        require 'footer.php';
        ?>
            <script type="text/javascript" src="../public/build/js/instascan.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
            <script type="text/javascript" src="scripts/serfirma.js"></script>
        <?php
    }
    ob_end_flush();
    ?>