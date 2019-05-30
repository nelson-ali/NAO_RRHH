<?php

/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  29-12-2015
*/


class GestiontiposdocumentosController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * Función para la carga de la página de gestión de relaciones laborales.
     * Se cargan los combos necesarios.
     */
    public function indexAction()
    {
        $auth = $this->session->get('auth');
        if (isset($auth['version'])) {
            $version = $auth['version'];
        } else $version = "0.0.0";

        $this->assets->addCss('/assets/css/bootstrap-switch.css?v=' . $version);
        $this->assets->addJs('/js/switch/bootstrap-switch.js?v=' . $version);
       // $this->assets->addCss('/assets/css/oasis.principal.css?v=' . $version);
        $this->assets->addCss('/assets/css/jquery-ui.css?v=' . $version);
        //$this->assets->addCss('/css/oasis.grillas.css?v=' . $version);
        $this->assets->addJs('/js/numeric/jquery.numeric.js?v=' . $version);
        $this->assets->addJs('/js/sortable/jquery-sortable.js?v=' . $version);

        $this->assets->addJs('/js/colorpicker-master/js/evol.colorpicker.min.js?v=' . $version);
        $this->assets->addCss('/js/colorpicker-master/css/evol.colorpicker.css?v=' . $version);

        $this->assets->addJs('/js/gestiontiposdocumentos/oasis.gestiontiposdocumentos.tab.js?v=' . $version);
        $this->assets->addJs('/js/gestiontiposdocumentos/oasis.gestiontiposdocumentos.index.js?v=' . $version);
        $this->assets->addJs('/js/gestiontiposdocumentos/oasis.gestiontiposdocumentos.new.edit.js?v=' . $version);
        $this->assets->addJs('/js/gestiontiposdocumentos/oasis.gestiontiposdocumentos.down.js?v=' . $version);
        /*$this->assets->addJs('/js/gestiontiposdocumentos/oasis.gestiontiposdocumentos.approve.js?v=' . $version);
        $this->assets->addJs('/js/gestiontiposdocumentos/oasis.gestiontiposdocumentos.export.js?v=' . $version);
        */
    }

    /**
     * Función para obtener el listado de Tipos de Documentos.
     */
    public function listAction()
    {
        $this->view->disable();
        $obj = new Ftiposdocumentos();
        $resul = $obj->getAll();
        $tipodocumento = Array();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $tipodocumento[] = array(
                    'nro_row' => 0,
                    'id' => $v->id_tipodocumento,
                    'tipo_documento' => $v->tipo_documento,
                    'codigo' => $v->codigo,
                    'indispensable' => $v->indispensable,
                    'indispensable_descripcion' => $v->indispensable_descripcion,
                    'id_tipopresdoc' => $v->id_tipopresdoc,
                    'tipo_pres_doc' => $v->tipo_pres_doc,
                    'id_periodopresdoc' => $v->id_periodopresdoc,
                    'periodo_pres_doc' => $v->periodo_pres_doc,
                    'id_tipoperssoldoc' => $v->id_tipoperssoldoc,
                    'tipo_pers_sol_doc' => $v->tipo_pers_sol_doc,
                    'id_tipoemisordoc' => $v->id_tipoemisordoc,
                    'tipo_emisor_doc' => $v->tipo_emisor_doc,
                    'id_tipofechaemidoc' => $v->id_tipofechaemidoc,
                    'tipo_fecha_emi_doc' => $v->tipo_fecha_emi_doc,
                    'hora' => $v->hora,
                    'hora_descripcion' => $v->hora_descripcion,
                    'dia' => $v->dia,
                    'dia_descripcion' => $v->dia_descripcion,
                    'mes' => $v->mes,
                    'mes_descripcion' => $v->mes_descripcion,
                    'trimestre' => $v->trimestre,
                    'trimestre_descripcion' => $v->trimestre_descripcion,
                    'semestre' => $v->semestre,
                    'semestre_descripcion' => $v->semestre_descripcion,
                    'gestion' => $v->gestion,
                    'gestion_descripcion' => $v->gestion_descripcion,
                    'tipofechaemidoc_descripcion' => $v->tipofechaemidoc_descripcion,
                    'id_genero' => $v->id_genero,
                    'genero' => $v->genero,
                    'id_normativamod' => $v->id_normativamod,
                    'normativamod' => $v->normativamod,
                    'nivelsalarial_nivel' => $v->nivelsalarial_nivel,
                    'nivelsalarial_nivel_denominacion' => $v->nivelsalarial_nivel_denominacion,
                    'permanente' => $v->permanente,
                    'permanente_descripcion' => $v->permanente_descripcion,
                    'eventual' => $v->eventual,
                    'eventual_descripcion' => $v->eventual_descripcion,
                    'consultor_linea' => $v->consultor_linea,
                    'consultor_linea_descripcion' => $v->consultor_linea_descripcion,
                    'consultor_producto' => $v->consultor_producto,
                    'consultor_producto_descripcion' => $v->consultor_producto_descripcion,
                    'id_grupoarchivo' => $v->id_grupoarchivo,
                    'grupo_archivo' => $v->grupo_archivo,
                    'ruta_carpeta' => $v->ruta_carpeta,
                    'nombre_carpeta' => $v->nombre_carpeta,
                    'formato_archivo_digital' => $v->formato_archivo_digital,
                    'resolucion_archivo_digital' => $v->resolucion_archivo_digital,
                    'altura_archivo_digital' => $v->altura_archivo_digital,
                    'anchura_archivo_digital' => $v->anchura_archivo_digital,
                    'columnas_aux' => $v->columnas_aux,
                    'columnas_aux_min' => $v->columnas_aux_min . ($v->columnas_aux_min != null && $v->columnas_aux_min != '' ? '.' : ''),
                    'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                    'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                    'observacion' => $v->observacion,
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'baja_logica' => $v->baja_logica,
                    'agrupador' => $v->agrupador,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg != "" ? date("d-m-Y", strtotime($v->fecha_reg)) : "",
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod
                );
            }
        }
        echo json_encode($tipodocumento);
    }

    /**
     * Función para la obtención del listado de tipos de documentos para un registro de relación laboral considerando la agrupación determinada.
     */
    public function listbygroupAction()
    {

        $idGrupo = 0;
        if (isset($_GET["id_grupo"]) && $_GET["id_grupo"] != null) {
            $idGrupo = $_GET["id_grupo"];
        }
        $idRelaboral = 0;
        if (isset($_GET["id_relaboral"]) && $_GET["id_grupo"] != null) {
            $idRelaboral = $_GET["id_relaboral"];
        }
        $this->view->disable();
        $obj = new Ftiposdocumentos();
        $resul = $obj->getAllByGroups($idGrupo, $idRelaboral);
        $tipodocumento = Array();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $tipodocumento[] = array(
                    'nro_row' => 0,
                    'id_tipodocumento' => $v->id_tipodocumento,
                    'tipo_documento' => $v->tipo_documento,
                    'codigo' => $v->codigo,
                    'indispensable' => $v->indispensable,
                    'indispensable_descripcion' => $v->indispensable_descripcion,
                    'id_tipopresdoc' => $v->id_tipopresdoc,
                    'tipo_pres_doc' => $v->tipo_pres_doc,
                    'id_periodopresdoc' => $v->id_periodopresdoc,
                    'periodo_pres_doc' => $v->periodo_pres_doc,
                    'id_tipoperssoldoc' => $v->id_tipoperssoldoc,
                    'tipo_pers_sol_doc' => $v->tipo_pers_sol_doc,
                    'id_tipoemisordoc' => $v->id_tipoemisordoc,
                    'tipo_emisor_doc' => $v->tipo_emisor_doc,
                    'id_tipofechaemidoc' => $v->id_tipofechaemidoc,
                    'tipo_fecha_emi_doc' => $v->tipo_fecha_emi_doc,
                    'hora' => $v->hora,
                    'hora_descripcion' => $v->hora_descripcion,
                    'dia' => $v->dia,
                    'dia_descripcion' => $v->dia_descripcion,
                    'mes' => $v->mes,
                    'mes_descripcion' => $v->mes_descripcion,
                    'trimestre' => $v->trimestre,
                    'trimestre_descripcion' => $v->trimestre_descripcion,
                    'gestion' => $v->gestion,
                    'gestion_descripcion' => $v->gestion_descripcion,
                    'tipofechaemidoc_descripcion' => $v->tipofechaemidoc_descripcion,
                    'id_genero' => $v->id_genero,
                    'genero' => $v->genero,
                    'id_normativamod' => $v->id_normativamod,
                    'normativa_mod' => $v->normativa_mod,
                    'nivelsalarial_nivel' => $v->nivelsalarial_nivel,
                    'nivelsalarial_nivel_denominacion' => $v->nivelsalarial_nivel_denominacion,
                    'permanente' => $v->permanente,
                    'permanente_descripcion' => $v->permanente_descripcion,
                    'eventual' => $v->eventual,
                    'eventual_descripcion' => $v->eventual_descripcion,
                    'consultor_linea' => $v->consultor_linea,
                    'consultor_linea_descripcion' => $v->consultor_linea_descripcion,
                    'consultor_producto' => $v->consultor_producto,
                    'consultor_producto_descripcion' => $v->consultor_producto_descripcion,
                    'id_grupoarchivo' => $v->id_grupoarchivo,
                    'grupo_archivo' => $v->grupo_archivo,
                    'ruta_carpeta' => $v->ruta_carpeta,
                    'nombre_carpeta' => $v->nombre_carpeta,
                    'formato_archivo_digital' => $v->formato_archivo_digital,
                    'resolucion_archivo_digital' => $v->resolucion_archivo_digital,
                    'altura_archivo_digital' => $v->altura_archivo_digital,
                    'anchura_archivo_digital' => $v->anchura_archivo_digital,
                    'columnas_aux' => $v->columnas_aux,
                    'columnas_aux_min' => $v->columnas_aux_min . ($v->columnas_aux_min != null && $v->columnas_aux_min != '' ? '.' : ''),
                    'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                    'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                    'observacion' => $v->observacion,
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'baja_logica' => $v->baja_logica,
                    'agrupador' => $v->agrupador,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod
                );
            }
        }
        echo json_encode($tipodocumento);
    }

    /**
     * Función para el regsitro de Tipos de Documentos.
     */
    public function saveAction()
    {
        $auth = $this->session->get('auth');
        $user_reg_id = $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            /**
             * Modificación de registro de Tipo de Documento.
             */
            $idTipoDocumento = $_POST['id'];
            $tipoDocumento = $_POST['tipo_documento'];
            $codigo = strtoupper($_POST['codigo']);
            $indispensable = $_POST['indispensable'];
            $idGrupoArchivo = $_POST['grupoarchivo_id'];
            $idTipoPresDoc = $_POST['tipopresdoc_id'];
            $idTipoEmisorDoc = $_POST['tipoemisordoc_id'];
            $idNormativaMod = null;
            if ($_POST['normativamod_id'] > 0) {
                $idNormativaMod = $_POST['normativamod_id'];
            }
            $nivelNivelSalarial = null;
            if($_POST['nivelsalarial_nivel']!=null&&$_POST['nivelsalarial_nivel']!=""){
                $nivelNivelSalarial = $_POST['nivelsalarial_nivel'];
            }
            $idPeriodoPresDoc = $_POST['periodopresdoc_id'];
            $idGenero = $_POST['genero_id'];
            $idTipoPersSolDoc = $_POST['tipoperssoldoc_id'];
            $idTipoFechaEmiDoc = $_POST['tipofechaemidoc_id'];
            $permanente = $_POST['permanente'];
            $eventual = $_POST['eventual'];
            $consultorLinea = $_POST['consultor_linea'];
            $consultorProducto = $_POST['consultor_producto'];
            $rutaCarpeta = $_POST['ruta_carpeta'];
            $nombreCarpeta = $_POST['nombre_carpeta'];
            $formatoArchivoDigital = $_POST['formato_archivo_digital'];
            $resolucionArchivoDigital = null;
            if ($_POST['resolucion_archivo_digital'] > 0) {
                $resolucionArchivoDigital = $_POST['resolucion_archivo_digital'];
            }
            $alturaArchivoDigital = null;
            if ($_POST['altura_archivo_digital'] > 0) {
                $alturaArchivoDigital = $_POST['altura_archivo_digital'];
            }
            $anchuraArchivoDigital = null;
            if ($_POST['anchura_archivo_digital'] > 0) {
                $anchuraArchivoDigital = $_POST['anchura_archivo_digital'];
            }
            $columnasAux = null;
            if ($_POST['columnas_aux'] != '') {
                $columnasAux = $_POST['columnas_aux'];
            }
            $fechaIni = null;
            if ($_POST['fecha_ini'] != '') {
                $fechaIni = $_POST['fecha_ini'];
            }
            $fechaFin = null;
            if ($_POST['fecha_fin'] != '') {
                $fechaFin = $_POST['fecha_fin'];
            }
            $observacion = $_POST['observacion'];
            if ($idTipoDocumento > 0) {
                $objTipoDocumentoAux = Tiposdocumentos::findFirst(array("id!=" . $_POST["id"] . " AND (UPPER(tipo_documento) LIKE UPPER('" . $tipoDocumento . "') OR UPPER(codigo) LIKE UPPER('" . $codigo . "')) AND baja_logica = 1"));
                if (!is_object($objTipoDocumentoAux)) {
                    $objTipoDocumento = Tiposdocumentos::findFirstById($_POST["id"]);
                    if (is_object($objTipoDocumento)) {
                        $objTipoDocumento->tipo_documento = $tipoDocumento;
                        $objTipoDocumento->codigo = $codigo;
                        $objTipoDocumento->indispensable = $indispensable;
                        $objTipoDocumento->tipopresdoc_id = $idTipoPresDoc;
                        $objTipoDocumento->periodopresdoc_id = $idPeriodoPresDoc;
                        $objTipoDocumento->tipoperssoldoc_id = $idTipoPersSolDoc;
                        $objTipoDocumento->tipoemisordoc_id = $idTipoEmisorDoc;
                        $objTipoDocumento->tipofechaemidoc_id = $idTipoFechaEmiDoc;
                        $objTipoDocumento->genero_id = $idGenero;
                        $objTipoDocumento->normativamod_id = $idNormativaMod;
                        $objTipoDocumento->nivelsalarial_nivel = $nivelNivelSalarial;
                        $objTipoDocumento->permanente = $permanente;
                        $objTipoDocumento->eventual = $eventual;
                        $objTipoDocumento->consultor_linea = $consultorLinea;
                        $objTipoDocumento->consultor_producto = $consultorProducto;
                        $objTipoDocumento->grupoarchivo_id = $idGrupoArchivo;
                        $objTipoDocumento->ruta_carpeta = $rutaCarpeta;
                        $objTipoDocumento->nombre_carpeta = $nombreCarpeta;
                        $objTipoDocumento->formato_archivo_digital = $formatoArchivoDigital;
                        $objTipoDocumento->resolucion_archivo_digital = $resolucionArchivoDigital;
                        $objTipoDocumento->altura_archivo_digital = $alturaArchivoDigital;
                        $objTipoDocumento->anchura_archivo_digital = $anchuraArchivoDigital;
                        $objTipoDocumento->columnas_aux = $columnasAux;
                        $objTipoDocumento->fecha_ini = $fechaIni;
                        $objTipoDocumento->fecha_fin = $fechaFin;
                        $objTipoDocumento->observacion = $observacion;
                        $objTipoDocumento->user_mod_id = $user_mod_id;
                        $objTipoDocumento->fecha_mod = $hoy;
                        try {
                            if ($objTipoDocumento->save()) {
                                $ftd = new Ftiposdocumentos();
                                $upd = $ftd->updAllAuxColumnsInPresentacionDoc($objTipoDocumento->id);
                                if ($upd == 1) {
                                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se modific&oacute; el registro del tipo de documento de modo satisfactorio.');
                                } else {
                                    $msj = array('result' => 1, 'msj' => 'Se modific&oacute; el registro del tipo de documento de modo satisfactorio, sin embargo, no se actualizaron las columnas en el registro de presentacioacute;n de documentos.');
                                }
                            } else {
                                $msj = array('result' => 0, 'msj' => 'Error: No se modific&oacute; el registro del tipo de documento.');
                            }
                        } catch (\Exception $e) {
                            echo get_class($e), ": ", $e->getMessage(), "\n";
                            echo " File=", $e->getFile(), "\n";
                            echo " Line=", $e->getLine(), "\n";
                            echo $e->getTraceAsString();
                            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro del tipo de documento.');
                        }
                    } else $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados son similares a otro registro existente, debe modificar los valores necesariamente.');
                } else {
                    $msj = array('result' => 0, 'msj' => 'Error: Existe otro registro de Tipo de Documento con el mismo nombre y/o c&oacute;digo, debe modificarlos dichos datos para que sea aceptada su solicitud.');
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su registro.');
            }
        } else {
            /**
             * Nuevo registro de Tipo de Documento
             */
            $tipoDocumento = $_POST['tipo_documento'];
            $codigo = strtoupper($_POST['codigo']);
            $indispensable = $_POST['indispensable'];
            $idGrupoArchivo = $_POST['grupoarchivo_id'];
            $idTipoPresDoc = $_POST['tipopresdoc_id'];
            $idTipoEmisorDoc = $_POST['tipoemisordoc_id'];
            $idNormativaMod = null;
            if ($_POST['normativamod_id'] > 0) {
                $idNormativaMod = $_POST['normativamod_id'];
            }
            $nivelNivelSalarial = null;
            if($_POST['nivelsalarial_nivel']!=null&&$_POST['nivelsalarial_nivel']!=""){
                $nivelNivelSalarial = $_POST['nivelsalarial_nivel'];
            }
            $idPeriodoPresDoc = $_POST['periodopresdoc_id'];
            $idGenero = $_POST['genero_id'];
            $idTipoPersSolDoc = $_POST['tipoperssoldoc_id'];
            $idTipoFechaEmiDoc = $_POST['tipofechaemidoc_id'];
            $permanente = $_POST['permanente'];
            $eventual = $_POST['eventual'];
            $consultorLinea = $_POST['consultor_linea'];
            $consultorProducto = $_POST['consultor_producto'];
            $rutaCarpeta = $_POST['ruta_carpeta'];
            $nombreCarpeta = $_POST['nombre_carpeta'];
            $formatoArchivoDigital = $_POST['formato_archivo_digital'];
            $resolucionArchivoDigital = $_POST['resolucion_archivo_digital'];
            $alturaArchivoDigital = $_POST['altura_archivo_digital'];
            $anchuraArchivoDigital = $_POST['anchura_archivo_digital'];
            $columnasAux = null;
            if ($_POST['columnas_aux'] != '') {
                $columnasAux = $_POST['columnas_aux'];
            }
            $fechaIni = null;
            if ($_POST['fecha_ini'] != '') {
                $fechaIni = $_POST['fecha_ini'];
            }
            $fechaFin = null;
            if ($_POST['fecha_fin'] != '') {
                $fechaFin = $_POST['fecha_fin'];
            }
            $observacion = $_POST['observacion'];
            if ($tipoDocumento != '' && $codigo != '') {
                $objTipoDocumento = Tiposdocumentos::findFirst(array("(UPPER(tipo_documento) LIKE UPPER('" . $tipoDocumento . "') OR UPPER(codigo)=UPPER('" . $codigo . "')) AND baja_logica = 1"));
                if (!is_object($objTipoDocumento)) {
                    $objTipoDocumento = new Tiposdocumentos();
                    $objTipoDocumento->tipo_documento = $tipoDocumento;
                    $objTipoDocumento->codigo = $codigo;
                    $objTipoDocumento->indispensable = $indispensable;
                    $objTipoDocumento->tipopresdoc_id = $idTipoPresDoc;
                    $objTipoDocumento->periodopresdoc_id = $idPeriodoPresDoc;
                    $objTipoDocumento->tipoperssoldoc_id = $idTipoPersSolDoc;
                    $objTipoDocumento->tipoemisordoc_id = $idTipoEmisorDoc;
                    $objTipoDocumento->tipofechaemidoc_id = $idTipoFechaEmiDoc;
                    $objTipoDocumento->genero_id = $idGenero;
                    $objTipoDocumento->normativamod_id = $idNormativaMod;
                    $objTipoDocumento->nivelsalarial_nivel = $nivelNivelSalarial;
                    $objTipoDocumento->permanente = $permanente;
                    $objTipoDocumento->eventual = $eventual;
                    $objTipoDocumento->consultor_linea = $consultorLinea;
                    $objTipoDocumento->consultor_producto = $consultorProducto;
                    $objTipoDocumento->grupoarchivo_id = $idGrupoArchivo;
                    $objTipoDocumento->ruta_carpeta = $rutaCarpeta;
                    $objTipoDocumento->nombre_carpeta = $nombreCarpeta;
                    $objTipoDocumento->formato_archivo_digital = $formatoArchivoDigital;
                    $objTipoDocumento->resolucion_archivo_digital = $resolucionArchivoDigital;
                    $objTipoDocumento->altura_archivo_digital = $alturaArchivoDigital;
                    $objTipoDocumento->anchura_archivo_digital = $anchuraArchivoDigital;
                    $objTipoDocumento->columnas_aux = $columnasAux;
                    $objTipoDocumento->fecha_ini = $fechaIni;
                    $objTipoDocumento->fecha_fin = $fechaFin;
                    $objTipoDocumento->observacion = $observacion;
                    $objTipoDocumento->estado = 1;
                    $objTipoDocumento->baja_logica = 1;
                    $objTipoDocumento->agrupador = 0;
                    $objTipoDocumento->user_reg_id = $user_mod_id;
                    $objTipoDocumento->fecha_reg = $hoy;
                    try {
                        if ($objTipoDocumento->save()) {
                            $ftd = new Ftiposdocumentos();
                            $upd = $ftd->updAllAuxColumnsInPresentacionDoc($objTipoDocumento->id);
                            if ($upd == 1) {
                                $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se registr&oacute; el Tipo de Documento de modo satisfactorio.');
                            } else {
                                $msj = array('result' => 1, 'msj' => 'Se registr&oacute; el Tipo de Documento de modo satisfactorio, sin embargo, no se actualizaron los campos auxiliares en la tabla de presentaciones de documentos.');
                            }
                        } else {
                            $msj = array('result' => 0, 'msj' => 'Error: No se registro&oacute; el Tipo de Documento.');
                        }
                    } catch (\Exception $e) {
                        echo get_class($e), ": ", $e->getMessage(), "\n";
                        echo " File=", $e->getFile(), "\n";
                        echo " Line=", $e->getLine(), "\n";
                        echo $e->getTraceAsString();
                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro del Tipo de Documento.');
                    }
                } else $msj = array('result' => 0, 'msj' => 'Error: Ya existe un registro de Tipo de Documento con el mismo nombre y/o c&oacute;digo, debe modificarlos dichos datos para que sea aceptada su solicitud.');

            } else {
                $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su registro.');
            }
        }
        echo json_encode($msj);
    }

    /**
     * Función para el la baja del registro de un control de excepción.
     * return array(EstadoResultado,Mensaje)
     * Los valores posibles para la variable EstadoResultado son:
     *  0: Error
     *   1: Procesado
     *  -1: Crítico Error
     *  -2: Error de Conexión
     *  -3: Usuario no Autorizado
     */
    public function downAction()
    {
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        try {
            if (isset($_POST["id"]) && $_POST["id"] > 0) {
                /**
                 * Baja de registro
                 */
                $objTiposDocumentos = Tiposdocumentos::findFirstById($_POST["id"]);
                $objTiposDocumentos->estado = 0;
                $objTiposDocumentos->baja_logica = 0;
                $objTiposDocumentos->user_mod_id = $user_mod_id;
                $objTiposDocumentos->fecha_mod = $hoy;
                if ($objTiposDocumentos->save()) {
                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: Registro de Baja realizado de modo satisfactorio.');
                } else {
                    foreach ($objTiposDocumentos->getMessages() as $message) {
                        echo $message, "\n";
                    }
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se pudo dar de baja al registro del tipo de documento.');
                }
            } else $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se efectu&oacute; la baja debido a que no se especific&oacute; el registro del tipo de documento.');
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro del control de excepci&oacute;n.');
        }
        echo json_encode($msj);
    }

    /**
     * Función para la obtención del listado de grupos de archivos disponibles para organización de los tipos de documentos.
     */
    public function listgruposarchivosAction()
    {
        $this->view->disable();
        $resul = Parametros::find(array("parametro LIKE 'grupoarchivos' AND estado=1 AND baja_logica=1 ORDER BY cast(nivel as integer)"));
        $grupoarchivo = Array();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $grupoarchivo[] = array(
                    'id_grupoarchivo' => $v->nivel,
                    'grupo_archivo' => $v->valor_1,
                    'grupo_archivo_resumido' => $v->valor_2,
                    'ordenador' => $v->valor_3
                );
            }
        }
        echo json_encode($grupoarchivo);
    }

    /**
     * Función para la obtención del listado de tipos de presentación.
     */
    public function listtipospresentaciondocAction()
    {
        $this->view->disable();
        $resul = Parametros::find(array("parametro LIKE 'tipopresdoc' AND estado=1 AND baja_logica=1"));
        $tipospresentaciondoc = Array();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $tipospresentaciondoc[] = array(
                    'id_tipopresdoc' => $v->nivel,
                    'tipo_pres_doc' => $v->valor_1
                );
            }
        }
        echo json_encode($tipospresentaciondoc);
    }

    /**
     * Función para la obtención del listado de tipos de presentación.
     */
    public function listperiodospresdocAction()
    {
        $this->view->disable();
        $resul = Parametros::find(array("parametro LIKE 'periodopresdoc' AND estado=1 AND baja_logica=1 ORDER BY CAST(nivel AS INTEGER)"));
        $periodospresdoc = Array();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $periodospresdoc[] = array(
                    'id_periodopresdoc' => $v->nivel,
                    'periodo_pres_doc' => $v->valor_1
                );
            }
        }
        echo json_encode($periodospresdoc);
    }

    /**
     * Función para la obtención del listado de tipos de persistencia de solicitud de documentos.
     */
    public function listtipoperssoldocAction()
    {
        $this->view->disable();
        $resul = Parametros::find(array("parametro LIKE 'tipoperssoldoc' AND estado=1 AND baja_logica=1"));
        $tipoperssoldoc = Array();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $tipoperssoldoc[] = array(
                    'id_tipoperssoldoc' => $v->nivel,
                    'tipo_pers_sol_doc' => $v->valor_1
                );
            }
        }
        echo json_encode($tipoperssoldoc);
    }

    /**
     * Función para la obtención del listado de tipos de emisores.
     */
    public function listtiposemisoresdocAction()
    {
        $this->view->disable();
        $resul = Parametros::find(array("parametro LIKE 'tipoemisordoc' AND estado=1 AND baja_logica=1"));
        $tipoemisordoc = Array();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $tipoemisordoc[] = array(
                    'id_tipoemisordoc' => $v->nivel,
                    'tipo_emisor_doc' => $v->valor_1
                );
            }
        }
        echo json_encode($tipoemisordoc);
    }

    /**
     * Función para la obtención del listado de tipos de fechas de emisión de documentos.
     */
    public function listtiposfechaemidocAction()
    {
        $this->view->disable();
        $resul = Tipofechaemidoc::find(array("estado=1 AND baja_logica=1 ORDER BY ordenador"));
        $tipofechaemidoc = Array();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $tipofechaemidoc[] = array(
                    'id' => $v->id,
                    'tipo_fecha_emi_doc' => $v->tipo_fecha_emi_doc,
                    'hora' => $v->hora,
                    'dia' => $v->dia,
                    'mes' => $v->mes,
                    'trimestre' => $v->trimestre,
                    'semestre' => $v->semestre,
                    'gestion' => $v->gestion,
                    'descripcion' => $v->descripcion
                );
            }
        }
        echo json_encode($tipofechaemidoc);
    }

    /**
     * Función para la obtención del listado de tipos disponibles de almacenamiento para los campos auxiliares.
     */
    public function listtiposdatoscampoauxAction()
    {
        $this->view->disable();
        $resul = Parametros::find(array("parametro LIKE 'TIPO_DATO_CAMPO_AUXILIAR' AND estado=1 AND baja_logica=1"));
        $tiposdatos = Array();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $tiposdatos[] = array(
                    'id_tipodatoaux' => $v->nivel,
                    'tipo_dato' => $v->valor_1
                );
            }
        }
        echo json_encode($tiposdatos);
    }

    /**
     * Función para obtener el listado de tipos de obligatoriedad para campos auxiliares.
     */
    public function listtiposobligatoriedadcampoauxAction()
    {
        $this->view->disable();
        $resul = Parametros::find(array("parametro LIKE 'TIPO_OBLIGATORIEDAD_CAMPO_AUXILIAR' AND estado=1 AND baja_logica=1"));
        $tipoobligatoriedad = Array();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $tipoobligatoriedad[] = array(
                    'id_tipoobligatoriedad' => $v->nivel,
                    'obligatoriedad' => $v->valor_1
                );
            }
        }
        echo json_encode($tipoobligatoriedad);
    }

    /**
     * Función para la obtención del listado de tipos de normativas por modalidad registrados en el sistema.
     */
    public function listnormativasmodAction()
    {
        $this->view->disable();
        $resul = Normativasmod::find(array("estado=1 AND baja_logica=1"));
        $normativasmod = Array();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $normativasmod[] = array(
                    'id_normativamod' => $v->id,
                    'normativa' => $v->normativa,
                    'modalidad' => $v->modalidad,
                    'denominacion' => $v->denominacion,
                    'permanente' => $v->permanente,
                    'eventual' => $v->eventual,
                    'consultor' => $v->consultor,
                    'observacion' => $v->observacion,
                    'estado' => $v->estado,
                    'visible' => $v->visible,
                    'baja_logica' => $v->baja_logica,
                    'agrupador' => $v->agrupador,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod
                );
            }
        }
        echo json_encode($normativasmod);
    }

    /**
     * Función para la obtención del listado de formatos de archivos digitales.
     */
    public function listformatosarchivosdigitalesAction()
    {
        $this->view->disable();
        $resul = Parametros::find(array("parametro LIKE 'FORMATOS_ARCHIVOS_DIGITALES' AND estado=1 AND baja_logica=1 ORDER BY nivel"));
        $formatoarchivodigital = Array();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $formatoarchivodigital[] = array(
                    'id' => $v->nivel,
                    'formato_archivo_digital' => $v->valor_1
                );
            }
        }
        echo json_encode($formatoarchivodigital);
    }

    /**
     * Función para obtener el detalle de nivel y denominación de escalas salariales activas en una determinada fecha.
     * Si el valor enviado en $fecha es nulo o vacío se calcula en función a la fecha de la consulta.
     */
    public function listnivelessalarialespornivelesAction()
    {
        $this->view->disable();
        $fecha = "";
        if ($_POST["fecha"] != "") $fecha = $_POST["fecha"];
        $objNs = new Nivelsalariales();
        $resul = $objNs->getNivelSalarialActivoEnUnaFecha($fecha);
        $niveles = Array();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $niveles[] = array(
                    'nivel' => $v->nivel,
                    'denominacion' => $v->denominacion
                );
            }
        }
        echo json_encode($niveles);
    }
} 