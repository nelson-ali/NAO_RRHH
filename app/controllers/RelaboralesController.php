<?php
/**
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  13-10-2014
*/

class RelaboralesController extends ControllerBase


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

        $this->assets->addJs('/js/jquery.PrintArea.js?v=' . $version);
        $this->assets->addCss('/assets/css/PrintArea.css?v=' . $version);
        //$this->assets->addCss('/assets/css/oasis.principal.css?v=' . $version);
        $this->assets->addJs('/js/relaborales/oasis.localizacion.js?v=' . $version);
        $this->assets->addCss('/css/oasis.grillas.css?v=' . $version);
        $this->assets->addJs('/js/ckeditor/ckeditor.js?v=' . $version);


        $this->assets->addJs('/js/relaborales/oasis.relaborales.tab.js?v=' . $version);
        $this->assets->addJs('/js/relaborales/oasis.relaborales.index.js?v=' . $version);
        $this->assets->addJs('/js/relaborales/oasis.relaborales.approve.js?v=' . $version);
        $this->assets->addJs('/js/relaborales/oasis.relaborales.new.js?v=' . $version);
        $this->assets->addJs('/js/relaborales/oasis.relaborales.edit.js?v=' . $version);
        $this->assets->addJs('/js/relaborales/oasis.relaborales.down.js?v=' . $version);
        $this->assets->addJs('/js/relaborales/oasis.relaborales.move.js?v=' . $version);
        $this->assets->addJs('/js/relaborales/oasis.relaborales.view.js?v=' . $version);
        $this->assets->addJs('/js/relaborales/oasis.relaborales.export.js?v=' . $version);
        $this->assets->addJs('/js/relaborales/oasis.relaborales.export.old.js?v=' . $version);
        $this->assets->addJs('/js/relaborales/oasis.relaborales.view.splitter.js?v=' . $version);
        $this->assets->addJs('/js/relaborales/oasis.relaborales.send.js?v=' . $version);

        $ubicaciones = $this->tag->select(
            array(
                'lstUbicaciones',
                Ubicaciones::find(array('baja_logica=1 AND (agrupador=0 OR agrupador=1)', 'order' => 'id ASC')),
                'using' => array('id', "ubicacion"),
                'useEmpty' => true,
                'emptyText' => 'Seleccionar..',
                'emptyValue' => '',
                'class' => 'form-control new-relab'
            )
        );
        $this->view->setVar('ubicaciones', $ubicaciones);

        $categorias = $this->tag->select(
            array(
                'lstCategorias',
                Categorias::find(array('order' => 'id ASC')),
                'using' => array('id', "categoria"),
                'useEmpty' => true,
                'emptyText' => 'Seleccionar..',
                'emptyValue' => '',
                'class' => 'form-control new-relab'
            )
        );
        $this->view->setVar('categorias', $categorias);
        $this->defineViewCols();
    }

    /**
     * Función para definir las columnas visibles y visibles del formulario principal.
     */
    private function defineViewCols()
    {
        /**
         * Los valores para los checks de vista de campos del codigo de forma separada.
         */
        $auth = $this->session->get('auth');
        $idUsuario = $auth['id'];
        $listaDeColumnas = array(
            "hdnNombres", "hdnCi", "hdnExpd", "hdnGenero", "hdnEdad", "hdnFechaNac", "hdnFechaCumple", "hdnGrupoSanguineo", "hdnEstadoDescripcion", "hdnActivo",
            "hdnUbicacion", "hdnCondicion", "hdnGerencia", "hdnDepartamento", "hdnArea", "hdnProcesoContratacion", "hdnFuente", "hdnNivelSalarial",
            "hdnCargo", "hdnHaber", "hdnFechaIng", "hdnFechaIni", "hdnFechaIncor", "hdnFechaFin", "hdnFechaBaja", "hdnMotivoBaja",
            "hdnInternoInst", "hdnCelularPer", "hdnCelularInst", "hdnEmailPer", "hdnEmailInst",
            "hdnCasFechaEmi", "hdnCasFechaPres", "hdnCasFechaFinCal", "hdnCasNumero", "hdnCasCodigoVerificacion", "hdnCasAnios", "hdnCasMeses", "hdnCasDias",
            "hdnMtAnios", "hdnMtMeses", "hdnMtDias","hdnAntiguedadTotalAnios", "hdnantiguedadTotalMeses", "hdnAntiguedadTotalDias",
            "hdnMtFinMesAnios", "hdnMtFinMesMeses", "hdnMtFinMesDias", "hdnObservacion",
        );
        $columnasOcultas = array();
        for ($ii = 0; $ii < count($listaDeColumnas); $ii++) {
            $this->view->setVar($listaDeColumnas[$ii], 0);
            $columnasOcultas[$listaDeColumnas[$ii]] = 0;
        }
        $objGrillaDetallePae = Columnasvisibles::findFirst("divgrilla_id='jqxgrid' AND user_id=" . $idUsuario);
        if (is_object($objGrillaDetallePae)) {
            $jsonCol = json_decode($objGrillaDetallePae->indexes, true);
            if (count($jsonCol)) {
                foreach ($jsonCol as $clave => $valor) {
                    if (isset($listaDeColumnas[$valor])) {
                        $this->view->setVar($listaDeColumnas[$valor], 1);
                        $columnasOcultas[$listaDeColumnas[$valor]] = 1;
                    }
                }
            }
        }
        /**
         * Variable en la cual se envía a la vista las columnas ocultas.
         */
        $this->view->setVar('columnasOcultas', $columnasOcultas);
    }

    /**
     * Función para la carga del primer listado sobre la página de gestión de relaciones laborales.
     * Se inhabilita la vista para el uso de jqwidgets,
     */
    public function listAction()
    {
        $this->view->disable();
        $obj = new Frelaborales();
        $relaboral = Array();
        $resul = $obj->getAllWithPersons();
        $permisoC = true;
        $permisoR = true;
        $permisoU = true;
        $permisoD = true;
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $chk = '';
                $new = '';
                $edit = '';
                $down = '';
                $view = '';
                $chk = '<input type="checkbox" id="chk_' . $v->id_relaboral . '">';
                // Se evalua el permiso de creación de nuevo registro
                if ($permisoC) {
                    if ($v->tiene_contrato_vigente == 0) {
                        $new = '<input type="button" id="btn_new_' . $v->id_relaboral . '" value=Nuevo class=btn_new>';
                    }
                }
                //Se evalua el permiso de edición de registro
                if ($permisoU) {
                    if ($v->estado == 2) {
                        $edit = '<input type="button" id="btn_edit_' . $v->id_relaboral . '" value=Editar class=btn_edit>';
                    }
                }
                //Se evalua
                $aprobar = '<input type="button" id="btn_appr_' . $v->id_relaboral . '" value="Aprobar" class="btn_approve">';
                $down = '<input type="button" id="btn_del_' . $v->id_relaboral . '" value="Baja" class="btn_del">';
                $view = '<input type="button" id="btn_view_' . $v->id_relaboral . '" value="Ver" class="btn_view">';
                $relaboral[] = array(
                    'chk' => $chk,
                    'nro_row' => 0,
                    'nuevo' => $new,
                    'aprobar' => $aprobar,
                    'editar' => $edit,
                    'eliminar' => $down,
                    'ver' => $view,
                    'id_relaboral' => $v->id_relaboral,
                    'id_persona' => $v->id_persona,
                    'p_nombre' => $v->p_nombre,
                    's_nombre' => $v->s_nombre,
                    't_nombre' => $v->t_nombre,
                    'p_apellido' => $v->p_apellido,
                    's_apellido' => $v->s_apellido,
                    'c_apellido' => $v->c_apellido,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'fecha_caducidad' => $v->fecha_caducidad,
                    'num_complemento' => '',
                    'fecha_nac' => $v->fecha_nac != "" ? date("d-m-Y", strtotime($v->fecha_nac)) : "",
                    'edad' => $v->edad,
                    'lugar_nac' => $v->lugar_nac,
                    'genero' => $v->genero,
                    'e_civil' => $v->e_civil,
                    'tiene_item' => $v->tiene_item,
                    'item' => $v->item,
                    'carrera_adm' => $v->carrera_adm,
                    'num_contrato' => $v->num_contrato,
                    'contrato_numerador_estado' => $v->contrato_numerador_estado,
                    'id_solelabcontrato' => $v->id_solelabcontrato,
                    'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                    'solelabcontrato_numero' => $v->solelabcontrato_numero,
                    'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                    'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                    'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                    'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                    'fecha_ing' => $v->fecha_ing != "" ? date("d-m-Y", strtotime($v->fecha_ing)) : "",
                    'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                    'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                    'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                    'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                    'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                    'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                    'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                    'motivo_baja' => $v->motivo_baja,
                    'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                    'descripcion_baja' => $v->descripcion_baja,
                    'descripcion_anu' => $v->descripcion_anu,
                    'id_cargo' => $v->id_cargo,
                    'cargo_codigo' => $v->cargo_codigo,
                    'cargo' => $v->cargo,
                    'cargo_resolucion_ministerial_id' => $v->cargo_resolucion_ministerial_id,
                    'cargo_resolucion_ministerial' => $v->cargo_resolucion_ministerial,
                    'cargo_gestion' => $v->cargo_gestion,
                    'cargo_correlativo' => $v->cargo_correlativo,
                    'id_nivelessalarial' => $v->id_nivelessalarial,
                    'nivelsalarial' => $v->nivelsalarial,
                    'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                    'nivelsalarial_resolucion' => $v->nivelsalarial_resolucion,
                    'numero_escala' => $v->numero_escala,
                    'gestion_escala' => $v->gestion_escala,
                    /*'sueldo' => $v->sueldo,*/
                    'sueldo' => str_replace(".00", "", $v->sueldo),
                    'id_procesocontratacion' => $v->id_procesocontratacion,
                    'proceso_codigo' => $v->proceso_codigo,
                    'id_convocatoria' => $v->id_convocatoria,
                    'convocatoria_codigo' => $v->convocatoria_codigo,
                    'convocatoria_tipo' => $v->convocatoria_tipo,
                    'id_fin_partida' => $v->id_fin_partida,
                    'fin_partida' => $v->fin_partida,
                    'id_condicion' => $v->id_condicion,
                    'condicion' => $v->condicion,
                    'categoria_relaboral' => $v->categoria_relaboral,
                    'id_da' => $v->id_da,
                    'direccion_administrativa' => $v->direccion_administrativa,
                    'organigrama_regional_id' => $v->organigrama_regional_id,
                    'organigrama_regional' => $v->organigrama_regional,
                    'id_regional' => $v->id_regional,
                    'regional' => $v->regional,
                    'regional_codigo' => $v->regional_codigo,
                    'id_departamento' => $v->id_departamento,
                    'departamento' => $v->departamento,
                    'id_provincia' => $v->id_provincia,
                    'provincia' => $v->provincia,
                    'id_localidad' => $v->id_localidad,
                    'localidad' => $v->localidad,
                    'residencia' => $v->residencia,
                    'unidad_ejecutora' => $v->unidad_ejecutora,
                    'cod_ue' => $v->cod_ue,
                    'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                    'gerencia_administrativa' => $v->gerencia_administrativa,
                    'id_departamento_administrativo' => $v->id_departamento_administrativo,
                    'departamento_administrativo' => $v->departamento_administrativo,
                    'id_organigrama' => $v->id_organigrama,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'organigrama_sigla' => $v->organigrama_sigla,
                    'organigrama_orden' => $v->organigrama_orden,
                    'id_area' => $v->id_area,
                    'area' => $v->area,
                    'id_ubicacion' => $v->id_ubicacion,
                    'ubicacion' => $v->ubicacion,
                    'unidades_superiores' => $v->unidades_superiores,
                    'unidades_dependientes' => $v->unidades_dependientes,
                    'partida' => $v->partida,
                    'fuente_codigo' => $v->fuente_codigo,
                    'fuente' => $v->fuente,
                    'organismo_codigo' => $v->organismo_codigo,
                    'organismo' => $v->organismo,
                    'relaboral_previo_id' => $v->relaboral_previo_id,
                    'observacion' => ($v->observacion != null) ? $v->observacion : "",
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'estado_abreviacion' => $v->estado_abreviacion,
                    'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                    'tiene_contrato_vigente_descripcion' => $v->tiene_contrato_vigente == 1 ? "SI" : ($v->tiene_contrato_vigente == 0 ? "NO" : ""),
                    'id_eventual' => $v->id_eventual,
                    'id_consultor' => $v->id_consultor,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                    'persona_user_reg_id' => $v->persona_user_reg_id,
                    'persona_fecha_reg' => $v->persona_fecha_reg,
                    'persona_user_mod_id' => $v->persona_user_mod_id,
                    'persona_fecha_mod' => $v->persona_fecha_mod
                );
            }
        }
        echo json_encode($relaboral);
    }

    /**
     * Función para la obtención del listado de registros de relación laboral considerando una gestión y su paginado.
     */
    public function listpagedAction()
    {
        $this->view->disable();
        $obj = new Frelaborales();
        $relaborales = Array();
        $auth = $this->session->get('auth');
        $idPersonaConsulta = $auth["persona_id"];
        $opcion = 0;
        $gestion = 0;
        $fecha = 0;
        if (isset($_GET["opcion"])) {
            $opcion = $_GET["opcion"];
        }
        if (isset($_GET["gestion"])) {
            $gestion = $_GET["gestion"];
        }
        $where = "";
        $pagenum = $_GET['pagenum'];
        $pagesize = $_GET['pagesize'];
        $total_rows = 0;
        $start = $pagenum * $pagesize;

        // filter data.
        if (isset($_GET['filterscount'])) {
            $filterscount = $_GET['filterscount'];

            if ($filterscount > 0) {
                $where = " WHERE (";
                $tmpdatafield = "";
                $tmpfilteroperator = "";
                for ($i = 0; $i < $filterscount; $i++) {
                    // get the filter's value.
                    $filtervalue = $_GET["filtervalue" . $i];
                    // get the filter's condition.
                    $filtercondition = $_GET["filtercondition" . $i];
                    // get the filter's column.
                    $filterdatafield = $_GET["filterdatafield" . $i];
                    // get the filter's operator.
                    $filteroperator = $_GET["filteroperator" . $i];

                    if ($tmpdatafield == "") {
                        $tmpdatafield = $filterdatafield;
                    } else if ($tmpdatafield <> $filterdatafield) {
                        $where .= ")AND(";
                    } else if ($tmpdatafield == $filterdatafield) {
                        if ($tmpfilteroperator == 0) {
                            $where .= " AND ";
                        } else
                            $where .= " OR ";
                    }
                    switch ($filtercondition) {
                        case 'NOT_EMPTY':
                        case 'NOT_NULL':
                            $where .= ' UPPER(' . $filterdatafield . ') NOT LIKE "' . '' . '"';
                            break;
                        case 'EMPTY':
                        case 'NULL':
                            $where .= ' (UPPER(' . $filterdatafield . ') LIKE "' . '' . '") OR (UPPER(' . $filterdatafield . ') IS NULL)';
                            break;
                        case 'CONTAINS_CASE_SENSITIVE':
                            $where .= ' BINARY  ' . $filterdatafield . ' LIKE "%' . $filtervalue . '%"';
                            break;
                        case 'CONTAINS':
                            $where .= ' UPPER(' . $filterdatafield . ') LIKE UPPER("%' . $filtervalue . '%")';
                            break;
                        case 'DOES_NOT_CONTAIN_CASE_SENSITIVE':
                            $where .= ' BINARY ' . $filterdatafield . ' NOT LIKE UPPER("%' . $filtervalue . '%")';
                            break;
                        case 'DOES_NOT_CONTAIN':
                            $where .= ' UPPER(' . $filterdatafield . ') NOT LIKE UPPER("%' . $filtervalue . '%")';
                            break;
                        case 'EQUAL_CASE_SENSITIVE':
                            $where .= ' BINARY ' . $filterdatafield . ' = "' . $filtervalue . '"';
                            break;
                        case "EQUAL":
                            $where .= ' ' . $filterdatafield . ' = "' . $filtervalue . '"';
                            break;
                        case 'NOT_EQUAL_CASE_SENSITIVE':
                            $where .= ' BINARY ' . $filterdatafield . ' <> "' . $filtervalue . '"';
                            break;
                        case 'NOT_EQUAL':
                            $where .= ' ' . $filterdatafield . ' <> "' . $filtervalue . '"';
                            break;
                        case 'GREATER_THAN':
                            $where .= ' ' . $filterdatafield . ' > "' . $filtervalue . '"';
                            break;
                        case 'LESS_THAN':
                            $where .= ' ' . $filterdatafield . ' < "' . $filtervalue . '"';
                            break;
                        case 'GREATER_THAN_OR_EQUAL':
                            $where .= ' ' . $filterdatafield . ' >= "' . $filtervalue . '"';
                            break;
                        case 'LESS_THAN_OR_EQUAL':
                            $where .= ' ' . $filterdatafield . ' <= "' . $filtervalue . '"';
                            break;
                        case 'STARTS_WITH_CASE_SENSITIVE':
                            $where .= ' BINARY ' . $filterdatafield . ' LIKE "' . $filtervalue . '%"';
                            break;
                        case 'STARTS_WITH':
                            $where .= ' UPPER(' . $filterdatafield . ') LIKE UPPER("' . $filtervalue . '%")';
                            break;
                        case 'ENDS_WITH_CASE_SENSITIVE':
                            $where .= ' BINARY ' . $filterdatafield . ' LIKE "%' . $filtervalue . '"';
                            break;
                        case 'ENDS_WITH':
                            $where .= ' UPPER(' . $filterdatafield . ') LIKE UPPER("%' . $filtervalue . '")';
                            break;
                    }

                    if ($i == $filterscount - 1) {
                        $where .= ')';
                    }

                    $tmpfilteroperator = $filteroperator;
                    $tmpdatafield = $filterdatafield;
                }
            }
        }
        if ($gestion >= 0) {
            $resul = $obj->getPaged($idPersonaConsulta, $opcion, $gestion, 0, $where, '', $start, $pagesize);
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $total_rows = $v->total_rows;
                    $relaborales[] = array(
                        'nro_row' => 0,
                        'id_relaboral' => $v->id_relaboral,
                        'id_persona' => $v->id_persona,
                        'p_nombre' => $v->p_nombre,
                        's_nombre' => $v->s_nombre,
                        't_nombre' => $v->t_nombre,
                        'p_apellido' => $v->p_apellido,
                        's_apellido' => $v->s_apellido,
                        'c_apellido' => $v->c_apellido,
                        'nombres' => $v->nombres,
                        'ci' => $v->ci,
                        'expd' => $v->expd,
                        'fecha_caducidad' => $v->fecha_caducidad,
                        'num_complemento' => '',
                        'fecha_nac' => $v->fecha_nac != "" ? date("d-m-Y", strtotime($v->fecha_nac)) : "",
                        'fecha_cumple' => $v->fecha_cumple != "" ? date("d-m-Y", strtotime($v->fecha_cumple)) : "",
                        'edad' => $v->edad,
                        'lugar_nac' => $v->lugar_nac,
                        'genero' => $v->genero,
                        'e_civil' => $v->e_civil,
                        'grupo_sanguineo' => $v->grupo_sanguineo,
                        'tiene_item' => $v->tiene_item,
                        'item' => $v->item,
                        'carrera_adm' => $v->carrera_adm,
                        'num_contrato' => $v->num_contrato,
                        'contrato_numerador_estado' => $v->contrato_numerador_estado,
                        'id_solelabcontrato' => $v->id_solelabcontrato,
                        'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                        'solelabcontrato_numero' => $v->solelabcontrato_numero,
                        'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                        'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                        'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                        'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                        'fecha_ing' => $v->fecha_ing != "" ? date("d-m-Y", strtotime($v->fecha_ing)) : "",
                        'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                        'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                        'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                        'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                        'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                        'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                        'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                        'motivo_baja' => $v->motivo_baja,
                        'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                        'descripcion_baja' => $v->descripcion_baja,
                        'descripcion_anu' => $v->descripcion_anu,
                        'id_cargo' => $v->id_cargo,
                        'cargo_codigo' => $v->cargo_codigo,
                        'cargo' => $v->cargo,
                        'cargo_resolucion_ministerial_id' => $v->cargo_resolucion_ministerial_id,
                        'cargo_resolucion_ministerial' => $v->cargo_resolucion_ministerial,
                        'cargo_gestion' => $v->cargo_gestion,
                        'cargo_correlativo' => $v->cargo_correlativo,
                        'id_nivelessalarial' => $v->id_nivelessalarial,
                        'nivelsalarial' => $v->nivelsalarial,
                        'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                        'nivelsalarial_resolucion' => $v->nivelsalarial_resolucion,
                        'numero_escala' => $v->numero_escala,
                        'gestion_escala' => $v->gestion_escala,
                        /*'sueldo' => $v->sueldo,*/
                        'sueldo' => str_replace(".00", "", $v->sueldo),
                        'id_procesocontratacion' => $v->id_procesocontratacion,
                        'proceso_codigo' => $v->proceso_codigo,
                        'id_convocatoria' => $v->id_convocatoria,
                        'convocatoria_codigo' => $v->convocatoria_codigo,
                        'convocatoria_tipo' => $v->convocatoria_tipo,
                        'id_fin_partida' => $v->id_fin_partida,
                        'fin_partida' => $v->fin_partida,
                        'id_condicion' => $v->id_condicion,
                        'condicion' => $v->condicion,
                        'categoria_relaboral' => $v->categoria_relaboral,
                        'id_da' => $v->id_da,
                        'direccion_administrativa' => $v->direccion_administrativa,
                        'organigrama_regional_id' => $v->organigrama_regional_id,
                        'organigrama_regional' => $v->organigrama_regional,
                        'id_regional' => $v->id_regional,
                        'regional' => $v->regional,
                        'regional_codigo' => $v->regional_codigo,
                        'id_departamento' => $v->id_departamento,
                        'departamento' => $v->departamento,
                        'id_provincia' => $v->id_provincia,
                        'provincia' => $v->provincia,
                        'id_localidad' => $v->id_localidad,
                        'localidad' => $v->localidad,
                        'residencia' => $v->residencia,
                        'unidad_ejecutora' => $v->unidad_ejecutora,
                        'cod_ue' => $v->cod_ue,
                        'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                        'gerencia_administrativa' => $v->gerencia_administrativa,
                        'id_departamento_administrativo' => $v->id_departamento_administrativo,
                        'departamento_administrativo' => $v->departamento_administrativo,
                        'id_organigrama' => $v->id_organigrama,
                        'unidad_administrativa' => $v->unidad_administrativa,
                        'organigrama_sigla' => $v->organigrama_sigla,
                        'organigrama_orden' => $v->organigrama_orden,
                        'id_area' => $v->id_area,
                        'area' => $v->area,
                        'id_ubicacion' => $v->id_ubicacion,
                        'ubicacion' => $v->ubicacion,
                        'unidades_superiores' => $v->unidades_superiores,
                        'unidades_dependientes' => $v->unidades_dependientes,
                        'partida' => $v->partida,
                        'fuente_codigo' => $v->fuente_codigo,
                        'fuente' => $v->fuente,
                        'organismo_codigo' => $v->organismo_codigo,
                        'organismo' => $v->organismo,
                        'relaboral_previo_id' => $v->relaboral_previo_id,
                        'observacion' => ($v->observacion != null) ? $v->observacion : "",
                        'estado' => $v->estado,
                        'estado_descripcion' => $v->estado_descripcion,
                        'estado_abreviacion' => $v->estado_abreviacion,
                        'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                        'tiene_contrato_vigente_descripcion' => $v->tiene_contrato_vigente == 1 ? "SI" : ($v->tiene_contrato_vigente == 0 ? "NO" : ""),
                        'id_eventual' => $v->id_eventual,
                        'id_consultor' => $v->id_consultor,
                        'user_reg_id' => $v->user_reg_id,
                        'fecha_reg' => $v->fecha_reg != "" ? date("d-m-Y", strtotime($v->fecha_reg)) : "",
                        'user_mod_id' => $v->user_mod_id,
                        'fecha_mod' => $v->fecha_mod != "" ? date("d-m-Y", strtotime($v->fecha_mod)) : "",
                        'persona_user_reg_id' => $v->persona_user_reg_id,
                        'persona_fecha_reg' => $v->persona_fecha_reg != "" ? date("d-m-Y", strtotime($v->persona_fecha_reg)) : "",
                        'persona_user_mod_id' => $v->persona_user_mod_id,
                        'persona_fecha_mod' => $v->persona_fecha_mod != "" ? date("d-m-Y", strtotime($v->persona_fecha_mod)) : "",
                        'id_presentaciondoc' => $v->id_presentaciondoc,
                        'interno_inst' => $v->interno_inst,
                        'celular_per' => $v->celular_per,
                        'celular_inst' => $v->celular_inst,
                        'e_mail_per' => $v->e_mail_per,
                        'e_mail_inst' => $v->e_mail_inst,
                        'cas_fecha_emi' => $v->cas_fecha_emi != "" ? date("d-m-Y", strtotime($v->cas_fecha_emi)) : "",
                        'cas_fecha_pres' => $v->cas_fecha_pres != "" ? date("d-m-Y", strtotime($v->cas_fecha_pres)) : "",
                        'cas_fecha_fin_cal' => $v->cas_fecha_fin_cal != "" ? date("d-m-Y", strtotime($v->cas_fecha_fin_cal)) : "",
                        'cas_numero' => $v->cas_numero,
                        'cas_codigo_verificacion' => $v->cas_codigo_verificacion,
                        'cas_anios' => $v->cas_anios,
                        'cas_meses' => $v->cas_meses,
                        'cas_dias' => $v->cas_dias,
                        'fecha_corte' => $v->fecha_corte != "" ? date("d-m-Y", strtotime($v->fecha_corte)) : "",
                        'mt_anios' => $v->mt_anios,
                        'mt_meses' => $v->mt_meses,
                        'mt_dias' => $v->mt_dias,
                        'tot_anios' => $v->tot_anios,
                        'tot_meses' => $v->tot_meses,
                        'tot_dias' => $v->tot_dias,
                        'mt_fin_mes_anios' => $v->mt_fin_mes_anios,
                        'mt_fin_mes_meses' => $v->mt_fin_mes_meses,
                        'mt_fin_mes_dias' => $v->mt_fin_mes_dias,
                        'fecha_act' => $v->fecha_act != "" ? date("d-m-Y", strtotime($v->fecha_act)) : "",
                        'mt_prox_fecha' => $v->mt_prox_fecha != "" ? date("d-m-Y", strtotime($v->mt_prox_fecha)) : "",
                        'mt_prox_fecha_ant' => $v->mt_prox_fecha_ant != "" ? date("d-m-Y", strtotime($v->mt_prox_fecha_ant)) : "",
                        'mt_prox_gestion' => $v->mt_prox_gestion,
                        'mt_prox_anios' => $v->mt_prox_anios,
                        'mt_prox_meses' => $v->mt_prox_meses,
                        'mt_prox_dias' => $v->mt_prox_dias,
                    );
                }
            }

        }
        $data[] = array(
            'TotalRows' => $total_rows,
            'Rows' => $relaborales
        );
        echo json_encode($data);
    }

    /**
     * Función para obtener el listado de filtros disponibles.
     */
    public function getfiltersAction()
    {
        $this->view->disable();
        $resultado = Array();
        $obj = new Frelaborales();
        $gestion = $_POST["gestion"];
        if ($gestion >= 0) {
            $resultado["expds"] = $obj->getFiltroExpds($gestion);
            $resultado["condiciones"] = $obj->getFiltroCondiciones($gestion);
            $resultado["ubicaciones"] = $obj->getFiltroUbicaciones($gestion);
            $resultado["gerencias"] = $obj->getFiltroGerencias($gestion);
            $resultado["departamentos"] = $obj->getFiltroDepartamentos($gestion);
            $resultado["sueldos"] = $obj->getFiltroSueldos($gestion);
            $resultado["edades"] = $obj->getFiltroEdades($gestion);
        }
        echo json_encode($resultado);
    }

    /**
     * Listado de registros de relación laboral por gestión.
     */
    public function listbygestionAction()
    {
        $this->view->disable();
        $obj = new Frelaborales();
        $relaboral = Array();
        $gestion = $_GET["gestion"];
        $resul = $obj->getAllWithPersonsByGestion($gestion);
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $relaboral[] = array(
                    'nro_row' => 0,
                    'id_relaboral' => $v->id_relaboral,
                    'id_persona' => $v->id_persona,
                    'p_nombre' => $v->p_nombre,
                    's_nombre' => $v->s_nombre,
                    't_nombre' => $v->t_nombre,
                    'p_apellido' => $v->p_apellido,
                    's_apellido' => $v->s_apellido,
                    'c_apellido' => $v->c_apellido,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'fecha_caducidad' => $v->fecha_caducidad,
                    'num_complemento' => '',
                    'fecha_nac' => $v->fecha_nac != "" ? date("d-m-Y", strtotime($v->fecha_nac)) : "",
                    'edad' => $v->edad,
                    'lugar_nac' => $v->lugar_nac,
                    'genero' => $v->genero,
                    'e_civil' => $v->e_civil,
                    'tiene_item' => $v->tiene_item,
                    'item' => $v->item,
                    'carrera_adm' => $v->carrera_adm,
                    'num_contrato' => $v->num_contrato,
                    'contrato_numerador_estado' => $v->contrato_numerador_estado,
                    'id_solelabcontrato' => $v->id_solelabcontrato,
                    'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                    'solelabcontrato_numero' => $v->solelabcontrato_numero,
                    'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                    'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                    'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                    'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                    'fecha_ing' => $v->fecha_ing != "" ? date("d-m-Y", strtotime($v->fecha_ing)) : "",
                    'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                    'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                    'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                    'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                    'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                    'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                    'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                    'motivo_baja' => $v->motivo_baja,
                    'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                    'descripcion_baja' => $v->descripcion_baja,
                    'descripcion_anu' => $v->descripcion_anu,
                    'id_cargo' => $v->id_cargo,
                    'cargo_codigo' => $v->cargo_codigo,
                    'cargo' => $v->cargo,
                    'cargo_resolucion_ministerial_id' => $v->cargo_resolucion_ministerial_id,
                    'cargo_resolucion_ministerial' => $v->cargo_resolucion_ministerial,
                    'cargo_gestion' => $v->cargo_gestion,
                    'cargo_correlativo' => $v->cargo_correlativo,
                    'id_nivelessalarial' => $v->id_nivelessalarial,
                    'nivelsalarial' => $v->nivelsalarial,
                    'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                    'nivelsalarial_resolucion' => $v->nivelsalarial_resolucion,
                    'numero_escala' => $v->numero_escala,
                    'gestion_escala' => $v->gestion_escala,
                    /*'sueldo' => $v->sueldo,*/
                    'sueldo' => str_replace(".00", "", $v->sueldo),
                    'id_procesocontratacion' => $v->id_procesocontratacion,
                    'proceso_codigo' => $v->proceso_codigo,
                    'id_convocatoria' => $v->id_convocatoria,
                    'convocatoria_codigo' => $v->convocatoria_codigo,
                    'convocatoria_tipo' => $v->convocatoria_tipo,
                    'id_fin_partida' => $v->id_fin_partida,
                    'fin_partida' => $v->fin_partida,
                    'id_condicion' => $v->id_condicion,
                    'condicion' => $v->condicion,
                    'categoria_relaboral' => $v->categoria_relaboral,
                    'id_da' => $v->id_da,
                    'direccion_administrativa' => $v->direccion_administrativa,
                    'organigrama_regional_id' => $v->organigrama_regional_id,
                    'organigrama_regional' => $v->organigrama_regional,
                    'id_regional' => $v->id_regional,
                    'regional' => $v->regional,
                    'regional_codigo' => $v->regional_codigo,
                    'id_departamento' => $v->id_departamento,
                    'departamento' => $v->departamento,
                    'id_provincia' => $v->id_provincia,
                    'provincia' => $v->provincia,
                    'id_localidad' => $v->id_localidad,
                    'localidad' => $v->localidad,
                    'residencia' => $v->residencia,
                    'unidad_ejecutora' => $v->unidad_ejecutora,
                    'cod_ue' => $v->cod_ue,
                    'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                    'gerencia_administrativa' => $v->gerencia_administrativa,
                    'id_departamento_administrativo' => $v->id_departamento_administrativo,
                    'departamento_administrativo' => $v->departamento_administrativo,
                    'id_organigrama' => $v->id_organigrama,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'organigrama_sigla' => $v->organigrama_sigla,
                    'organigrama_orden' => $v->organigrama_orden,
                    'id_area' => $v->id_area,
                    'area' => $v->area,
                    'id_ubicacion' => $v->id_ubicacion,
                    'ubicacion' => $v->ubicacion,
                    'unidades_superiores' => $v->unidades_superiores,
                    'unidades_dependientes' => $v->unidades_dependientes,
                    'partida' => $v->partida,
                    'fuente_codigo' => $v->fuente_codigo,
                    'fuente' => $v->fuente,
                    'organismo_codigo' => $v->organismo_codigo,
                    'organismo' => $v->organismo,
                    'relaboral_previo_id' => $v->relaboral_previo_id,
                    'observacion' => ($v->observacion != null) ? $v->observacion : "",
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'estado_abreviacion' => $v->estado_abreviacion,
                    'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                    'tiene_contrato_vigente_descripcion' => $v->tiene_contrato_vigente == 1 ? "SI" : ($v->tiene_contrato_vigente == 0 ? "NO" : ""),
                    'id_eventual' => $v->id_eventual,
                    'id_consultor' => $v->id_consultor,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                    'persona_user_reg_id' => $v->persona_user_reg_id,
                    'persona_fecha_reg' => $v->persona_fecha_reg,
                    'persona_user_mod_id' => $v->persona_user_mod_id,
                    'persona_fecha_mod' => $v->persona_fecha_mod
                );
            }
        }
        echo json_encode($relaboral);
    }

    /**
     * Función para listar todos los contratos activos y en proceso (Estados 1 y 2).
     */
    public function listactivosAction()
    {
        $this->view->disable();
        $obj = new Frelaborales();
        $relaboral = Array();
        $idUbicacion = 0;
        if (isset($_POST["id_ubicacion"]) && $_POST["id_ubicacion"] > 0) {
            $idUbicacion = $_POST["id_ubicacion"];
        }
        $sql = " WHERE estado>=0 ";
        if ($idUbicacion > 0)
            $sql .= " AND id_ubicacion=" . $idUbicacion;
        $resul = $obj->getAll($sql);
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $relaboral[] = array(
                    'nro_row' => 0,
                    'id_relaboral' => $v->id_relaboral,
                    'id_persona' => $v->id_persona,
                    'p_nombre' => $v->p_nombre,
                    's_nombre' => $v->s_nombre,
                    't_nombre' => $v->t_nombre,
                    'p_apellido' => $v->p_apellido,
                    's_apellido' => $v->s_apellido,
                    'c_apellido' => $v->c_apellido,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'fecha_caducidad' => $v->fecha_caducidad,
                    'num_complemento' => '',
                    'fecha_nac' => $v->fecha_nac != "" ? date("d-m-Y", strtotime($v->fecha_nac)) : "",
                    'edad' => $v->edad,
                    'lugar_nac' => $v->lugar_nac,
                    'genero' => $v->genero,
                    'e_civil' => $v->e_civil,
                    'tiene_item' => $v->tiene_item,
                    'item' => $v->item,
                    'carrera_adm' => $v->carrera_adm,
                    'num_contrato' => $v->num_contrato,
                    'contrato_numerador_estado' => $v->contrato_numerador_estado,
                    'id_solelabcontrato' => $v->id_solelabcontrato,
                    'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                    'solelabcontrato_numero' => $v->solelabcontrato_numero,
                    'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                    'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                    'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                    'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                    'fecha_ing' => $v->fecha_ing != "" ? date("d-m-Y", strtotime($v->fecha_ing)) : "",
                    'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                    'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                    'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                    'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                    'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                    'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                    'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                    'motivo_baja' => $v->motivo_baja,
                    'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                    'descripcion_baja' => $v->descripcion_baja,
                    'descripcion_anu' => $v->descripcion_anu,
                    'id_cargo' => $v->id_cargo,
                    'cargo_codigo' => $v->cargo_codigo,
                    'cargo' => $v->cargo,
                    'cargo_resolucion_ministerial_id' => $v->cargo_resolucion_ministerial_id,
                    'cargo_resolucion_ministerial' => $v->cargo_resolucion_ministerial,
                    'id_nivelessalarial' => $v->id_nivelessalarial,
                    'nivelsalarial' => $v->nivelsalarial,
                    'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                    'nivelsalarial_resolucion' => $v->nivelsalarial_resolucion,
                    'numero_escala' => $v->numero_escala,
                    'gestion_escala' => $v->gestion_escala,
                    /*'sueldo' => $v->sueldo,*/
                    'sueldo' => str_replace(".00", "", $v->sueldo),
                    'id_procesocontratacion' => $v->id_procesocontratacion,
                    'proceso_codigo' => $v->proceso_codigo,
                    'id_convocatoria' => $v->id_convocatoria,
                    'convocatoria_codigo' => $v->convocatoria_codigo,
                    'convocatoria_tipo' => $v->convocatoria_tipo,
                    'id_fin_partida' => $v->id_fin_partida,
                    'fin_partida' => $v->fin_partida,
                    'id_condicion' => $v->id_condicion,
                    'condicion' => $v->condicion,
                    'categoria_relaboral' => $v->categoria_relaboral,
                    'id_da' => $v->id_da,
                    'direccion_administrativa' => $v->direccion_administrativa,
                    'organigrama_regional_id' => $v->organigrama_regional_id,
                    'organigrama_regional' => $v->organigrama_regional,
                    'id_regional' => $v->id_regional,
                    'regional' => $v->regional,
                    'regional_codigo' => $v->regional_codigo,
                    'id_departamento' => $v->id_departamento,
                    'departamento' => $v->departamento,
                    'id_provincia' => $v->id_provincia,
                    'provincia' => $v->provincia,
                    'id_localidad' => $v->id_localidad,
                    'localidad' => $v->localidad,
                    'residencia' => $v->residencia,
                    'unidad_ejecutora' => $v->unidad_ejecutora,
                    'cod_ue' => $v->cod_ue,
                    'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                    'gerencia_administrativa' => $v->gerencia_administrativa,
                    'id_departamento_administrativo' => $v->id_departamento_administrativo,
                    'departamento_administrativo' => $v->departamento_administrativo,
                    'id_organigrama' => $v->id_organigrama,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'organigrama_sigla' => $v->organigrama_sigla,
                    'organigrama_orden' => $v->organigrama_orden,
                    'id_area' => $v->id_area,
                    'area' => $v->area,
                    'id_ubicacion' => $v->id_ubicacion,
                    'ubicacion' => $v->ubicacion,
                    'unidades_superiores' => $v->unidades_superiores,
                    'unidades_dependientes' => $v->unidades_dependientes,
                    'partida' => $v->partida,
                    'fuente_codigo' => $v->fuente_codigo,
                    'fuente' => $v->fuente,
                    'organismo_codigo' => $v->organismo_codigo,
                    'organismo' => $v->organismo,
                    'observacion' => ($v->observacion != null) ? $v->observacion : "",
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'estado_abreviacion' => $v->estado_abreviacion,
                    'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                    'tiene_contrato_vigente_descripcion' => $v->tiene_contrato_vigente == 1 ? "SI" : ($v->tiene_contrato_vigente == 0 ? "NO" : ""),
                    'id_eventual' => $v->id_eventual,
                    'id_consultor' => $v->id_consultor,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                    'persona_user_reg_id' => $v->persona_user_reg_id,
                    'persona_fecha_reg' => $v->persona_fecha_reg,
                    'persona_user_mod_id' => $v->persona_user_mod_id,
                    'persona_fecha_mod' => $v->persona_fecha_mod,
                    'agrupador' => 0
                );
            }
        }
        echo json_encode($relaboral);
    }

    /**
     * Función para desplegar los registros de relación laboral activados por gestión.
     */
    public function listactivosengestionAction()
    {
        $this->view->disable();
        $obj = new Frelaborales();
        $relaboral = Array();
        $auth = $this->session->get('auth');
        $idPersona = $auth["persona_id"];
        $idUbicacion = 0;
        if (isset($_POST["id_ubicacion"]) && $_POST["id_ubicacion"] > 0) {
            $idUbicacion = $_POST["id_ubicacion"];
        }
        $sql = " WHERE estado>=0 ";
        if ($idUbicacion > 0)
            $sql .= " AND id_ubicacion=" . $idUbicacion;
        $gestion = 0;
        if ($_GET["gestion"] > 0) {
            $gestion = $_GET["gestion"];
        }
        $resul = $obj->getAllInGestion($idPersona, $gestion, $sql);
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $relaboral[] = array(
                    'nro_row' => 0,
                    'id_relaboral' => $v->id_relaboral,
                    'id_persona' => $v->id_persona,
                    'p_nombre' => $v->p_nombre,
                    's_nombre' => $v->s_nombre,
                    't_nombre' => $v->t_nombre,
                    'p_apellido' => $v->p_apellido,
                    's_apellido' => $v->s_apellido,
                    'c_apellido' => $v->c_apellido,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'fecha_caducidad' => $v->fecha_caducidad,
                    'num_complemento' => '',
                    //'fecha_cumple' => $v->fecha_cumple != "" ? date("d-m-Y", strtotime($v->fecha_cumple)) : "",
                    'edad' => $v->edad,
                    'lugar_nac' => $v->lugar_nac,
                    'genero' => $v->genero,
                    'e_civil' => $v->e_civil,
                    'tiene_item' => $v->tiene_item,
                    'item' => $v->item,
                    'carrera_adm' => $v->carrera_adm,
                    'num_contrato' => $v->num_contrato,
                    'contrato_numerador_estado' => $v->contrato_numerador_estado,
                    'id_solelabcontrato' => $v->id_solelabcontrato,
                    'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                    'solelabcontrato_numero' => $v->solelabcontrato_numero,
                    'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                    'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                    'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                    'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                    'fecha_ing' => $v->fecha_ing != "" ? date("d-m-Y", strtotime($v->fecha_ing)) : "",
                    'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                    'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                    'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                    'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                    'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                    'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                    'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                    'motivo_baja' => $v->motivo_baja,
                    'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                    'descripcion_baja' => $v->descripcion_baja,
                    'descripcion_anu' => $v->descripcion_anu,
                    'id_cargo' => $v->id_cargo,
                    'cargo_codigo' => $v->cargo_codigo,
                    'cargo' => $v->cargo,
                    'cargo_resolucion_ministerial_id' => $v->cargo_resolucion_ministerial_id,
                    'cargo_resolucion_ministerial' => $v->cargo_resolucion_ministerial,
                    'id_nivelessalarial' => $v->id_nivelessalarial,
                    'nivelsalarial' => $v->nivelsalarial,
                    'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                    'nivelsalarial_resolucion' => $v->nivelsalarial_resolucion,
                    'numero_escala' => $v->numero_escala,
                    'gestion_escala' => $v->gestion_escala,
                    /*'sueldo' => $v->sueldo,*/
                    'sueldo' => str_replace(".00", "", $v->sueldo),
                    'id_procesocontratacion' => $v->id_procesocontratacion,
                    'proceso_codigo' => $v->proceso_codigo,
                    'id_convocatoria' => $v->id_convocatoria,
                    'convocatoria_codigo' => $v->convocatoria_codigo,
                    'convocatoria_tipo' => $v->convocatoria_tipo,
                    'id_fin_partida' => $v->id_fin_partida,
                    'fin_partida' => $v->fin_partida,
                    'id_condicion' => $v->id_condicion,
                    'condicion' => $v->condicion,
                    'categoria_relaboral' => $v->categoria_relaboral,
                    'id_da' => $v->id_da,
                    'direccion_administrativa' => $v->direccion_administrativa,
                    'organigrama_regional_id' => $v->organigrama_regional_id,
                    'organigrama_regional' => $v->organigrama_regional,
                    'id_regional' => $v->id_regional,
                    'regional' => $v->regional,
                    'regional_codigo' => $v->regional_codigo,
                    'id_departamento' => $v->id_departamento,
                    'departamento' => $v->departamento,
                    'id_provincia' => $v->id_provincia,
                    'provincia' => $v->provincia,
                    'id_localidad' => $v->id_localidad,
                    'localidad' => $v->localidad,
                    'residencia' => $v->residencia,
                    'unidad_ejecutora' => $v->unidad_ejecutora,
                    'cod_ue' => $v->cod_ue,
                    'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                    'gerencia_administrativa' => $v->gerencia_administrativa,
                    'id_departamento_administrativo' => $v->id_departamento_administrativo,
                    'departamento_administrativo' => $v->departamento_administrativo,
                    'id_organigrama' => $v->id_organigrama,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'organigrama_sigla' => $v->organigrama_sigla,
                    'organigrama_orden' => $v->organigrama_orden,
                    'id_area' => $v->id_area,
                    'area' => $v->area,
                    'id_ubicacion' => $v->id_ubicacion,
                    'ubicacion' => $v->ubicacion,
                    'unidades_superiores' => $v->unidades_superiores,
                    'unidades_dependientes' => $v->unidades_dependientes,
                    'partida' => $v->partida,
                    'fuente_codigo' => $v->fuente_codigo,
                    'fuente' => $v->fuente,
                    'organismo_codigo' => $v->organismo_codigo,
                    'organismo' => $v->organismo,
                    'observacion' => ($v->observacion != null) ? $v->observacion : "",
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'estado_abreviacion' => $v->estado_abreviacion,
                    'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                    'tiene_contrato_vigente_descripcion' => $v->tiene_contrato_vigente == "1" ? "SI" : ($v->tiene_contrato_vigente == 0 ? "NO" : ""),
                    'id_eventual' => $v->id_eventual,
                    'id_consultor' => $v->id_consultor,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                    'persona_user_reg_id' => $v->persona_user_reg_id,
                    'persona_fecha_reg' => $v->persona_fecha_reg,
                    'persona_user_mod_id' => $v->persona_user_mod_id,
                    'persona_fecha_mod' => $v->persona_fecha_mod,
                    'agrupador' => 0
                );
            }
        }
        echo json_encode($relaboral);
    }

    /**
     * Función para listar los registros de relación laboral agrupadas entre las que cumplen el filtro aplicado por las variables.
     */
    public function listagrupadasAction()
    {
        $this->view->disable();
        $obj = new Frelaborales();
        $relaborales = Array();
        $idPerfilLaboral = 0;
        $idUbicacion = 0;
        $fechaIni = "";
        $fechaFin = "";
        if (isset($_POST["id_perfillaboral"]) && $_POST["id_perfillaboral"] > 0) {
            $idPerfilLaboral = $_POST["id_perfillaboral"];
        }
        if (isset($_POST["id_ubicacion"]) && $_POST["id_ubicacion"] > 0) {
            $idUbicacion = $_POST["id_ubicacion"];
        }
        if (isset($_POST["fecha_ini"]) && $_POST["fecha_ini"] != '') {
            $fechaIni = $_POST["fecha_ini"];
        }
        if (isset($_POST["fecha_fin"]) && $_POST["fecha_fin"] != '') {
            $fechaFin = $_POST["fecha_fin"];
        }

        $resul = $obj->getListGroupedByPerfil($idPerfilLaboral, $idUbicacion, $fechaIni, $fechaFin);
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $relaborales[] = array(
                    'nro_row' => 0,
                    'id_relaboral' => $v->id_relaboral,
                    'id_persona' => $v->id_persona,
                    'p_nombre' => $v->p_nombre,
                    's_nombre' => $v->s_nombre,
                    't_nombre' => $v->t_nombre,
                    'p_apellido' => $v->p_apellido,
                    's_apellido' => $v->s_apellido,
                    'c_apellido' => $v->c_apellido,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'fecha_caducidad' => $v->fecha_caducidad,
                    'num_complemento' => '',
                    'fecha_nac' => $v->fecha_nac != "" ? date("d-m-Y", strtotime($v->fecha_nac)) : "",
                    'edad' => $v->edad,
                    'lugar_nac' => $v->lugar_nac,
                    'genero' => $v->genero,
                    'e_civil' => $v->e_civil,
                    'item' => $v->item,
                    'carrera_adm' => $v->carrera_adm,
                    'num_contrato' => $v->num_contrato,
                    'contrato_numerador_estado' => $v->contrato_numerador_estado,
                    'id_solelabcontrato' => $v->id_solelabcontrato,
                    'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                    'solelabcontrato_numero' => $v->solelabcontrato_numero,
                    'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                    'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                    'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                    'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                    'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                    'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                    'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                    'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                    'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                    'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                    'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                    'motivo_baja' => $v->motivo_baja,
                    'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                    'descripcion_baja' => $v->descripcion_baja,
                    'descripcion_anu' => $v->descripcion_anu,
                    'id_cargo' => $v->id_cargo,
                    'cargo_codigo' => $v->cargo_codigo,
                    'cargo' => $v->cargo,
                    'cargo_resolucion_ministerial_id' => $v->cargo_resolucion_ministerial_id,
                    'cargo_resolucion_ministerial' => $v->cargo_resolucion_ministerial,
                    'id_nivelessalarial' => $v->id_nivelessalarial,
                    'nivelsalarial' => $v->nivelsalarial,
                    'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                    'nivelsalarial_resolucion' => $v->nivelsalarial_resolucion,
                    'numero_escala' => $v->numero_escala,
                    'gestion_escala' => $v->gestion_escala,
                    /*'sueldo' => $v->sueldo,*/
                    'sueldo' => str_replace(".00", "", $v->sueldo),
                    'id_procesocontratacion' => $v->id_procesocontratacion,
                    'proceso_codigo' => $v->proceso_codigo,
                    'id_convocatoria' => $v->id_convocatoria,
                    'convocatoria_codigo' => $v->convocatoria_codigo,
                    'convocatoria_tipo' => $v->convocatoria_tipo,
                    'id_fin_partida' => $v->id_fin_partida,
                    'fin_partida' => $v->fin_partida,
                    'id_condicion' => $v->id_condicion,
                    'condicion' => $v->condicion,
                    'categoria_relaboral' => $v->categoria_relaboral,
                    'id_da' => $v->id_da,
                    'direccion_administrativa' => $v->direccion_administrativa,
                    'organigrama_regional_id' => $v->organigrama_regional_id,
                    'organigrama_regional' => $v->organigrama_regional,
                    'id_regional' => $v->id_regional,
                    'regional' => $v->regional,
                    'regional_codigo' => $v->regional_codigo,
                    'id_departamento' => $v->id_departamento,
                    'departamento' => $v->departamento,
                    'id_provincia' => $v->id_provincia,
                    'provincia' => $v->provincia,
                    'id_localidad' => $v->id_localidad,
                    'localidad' => $v->localidad,
                    'residencia' => $v->residencia,
                    'unidad_ejecutora' => $v->unidad_ejecutora,
                    'cod_ue' => $v->cod_ue,
                    'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                    'gerencia_administrativa' => $v->gerencia_administrativa,
                    'id_departamento_administrativo' => $v->id_departamento_administrativo,
                    'departamento_administrativo' => $v->departamento_administrativo,
                    'id_organigrama' => $v->id_organigrama,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'organigrama_sigla' => $v->organigrama_sigla,
                    'organigrama_orden' => $v->organigrama_orden,
                    'id_area' => $v->id_area,
                    'area' => $v->area,
                    'id_ubicacion' => $v->id_ubicacion,
                    'ubicacion' => $v->ubicacion,
                    'unidades_superiores' => $v->unidades_superiores,
                    'unidades_dependientes' => $v->unidades_dependientes,
                    'partida' => $v->partida,
                    'fuente_codigo' => $v->fuente_codigo,
                    'fuente' => $v->fuente,
                    'organismo_codigo' => $v->organismo_codigo,
                    'organismo' => $v->organismo,
                    'observacion' => ($v->observacion != null) ? $v->observacion : "",
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'estado_abreviacion' => $v->estado_abreviacion,
                    'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                    'tiene_contrato_vigente_descripcion' => $v->tiene_contrato_vigente == 1 ? "SI" : ($v->tiene_contrato_vigente == 0 ? "NO" : ""),
                    'id_eventual' => $v->id_eventual,
                    'id_consultor' => $v->id_consultor,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                    'persona_user_reg_id' => $v->persona_user_reg_id,
                    'persona_fecha_reg' => $v->persona_fecha_reg,
                    'persona_user_mod_id' => $v->persona_user_mod_id,
                    'persona_fecha_mod' => $v->persona_fecha_mod,
                    'agrupador' => $v->agrupador
                );
            }
        }
        echo json_encode($relaborales);
    }

    /**
     * Función para obtener el listado de relaciones laborales asignadas a perfil en un rango determinado de fechas.
     */
    public function listasignadasAction()
    {
        $this->view->disable();
        $obj = new Frelaborales();
        $relaborales = Array();
        $idPerfilLaboral = 0;
        $idUbicacion = 0;
        $fechaIni = "";
        $fechaFin = "";
        if (isset($_POST["id_perfillaboral"]) && $_POST["id_perfillaboral"] > 0) {
            $idPerfilLaboral = $_POST["id_perfillaboral"];
        }
        if (isset($_POST["id_ubicacion"]) && $_POST["id_ubicacion"] > 0) {
            $idUbicacion = $_POST["id_ubicacion"];
        }
        if (isset($_POST["fecha_ini"]) && $_POST["fecha_ini"] != '') {
            $fechaIni = $_POST["fecha_ini"];
        }
        if (isset($_POST["fecha_fin"]) && $_POST["fecha_fin"] != '') {
            $fechaFin = $_POST["fecha_fin"];
        }
        $resul = $obj->getListAssignedByPerfil($idPerfilLaboral, $idUbicacion, $fechaIni, $fechaFin);
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $relaborales[] = array(
                    'nro_row' => 0,
                    'id_relaboral' => $v->id_relaboral,
                    'id_persona' => $v->id_persona,
                    'p_nombre' => $v->p_nombre,
                    's_nombre' => $v->s_nombre,
                    't_nombre' => $v->t_nombre,
                    'p_apellido' => $v->p_apellido,
                    's_apellido' => $v->s_apellido,
                    'c_apellido' => $v->c_apellido,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'fecha_caducidad' => $v->fecha_caducidad,
                    'num_complemento' => '',
                    'fecha_nac' => $v->fecha_nac != "" ? date("d-m-Y", strtotime($v->fecha_nac)) : "",
                    'edad' => $v->edad,
                    'lugar_nac' => $v->lugar_nac,
                    'genero' => $v->genero,
                    'e_civil' => $v->e_civil,
                    'item' => $v->item,
                    'carrera_adm' => $v->carrera_adm,
                    'num_contrato' => $v->num_contrato,
                    'contrato_numerador_estado' => $v->contrato_numerador_estado,
                    'id_solelabcontrato' => $v->id_solelabcontrato,
                    'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                    'solelabcontrato_numero' => $v->solelabcontrato_numero,
                    'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                    'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                    'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                    'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                    'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                    'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                    'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                    'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                    'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                    'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                    'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                    'motivo_baja' => $v->motivo_baja,
                    'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                    'descripcion_baja' => $v->descripcion_baja,
                    'descripcion_anu' => $v->descripcion_anu,
                    'id_cargo' => $v->id_cargo,
                    'cargo_codigo' => $v->cargo_codigo,
                    'cargo' => $v->cargo,
                    'cargo_resolucion_ministerial_id' => $v->cargo_resolucion_ministerial_id,
                    'cargo_resolucion_ministerial' => $v->cargo_resolucion_ministerial,
                    'id_nivelessalarial' => $v->id_nivelessalarial,
                    'nivelsalarial' => $v->nivelsalarial,
                    'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                    'nivelsalarial_resolucion' => $v->nivelsalarial_resolucion,
                    'numero_escala' => $v->numero_escala,
                    'gestion_escala' => $v->gestion_escala,
                    /*'sueldo' => $v->sueldo,*/
                    'sueldo' => str_replace(".00", "", $v->sueldo),
                    'id_procesocontratacion' => $v->id_procesocontratacion,
                    'proceso_codigo' => $v->proceso_codigo,
                    'id_convocatoria' => $v->id_convocatoria,
                    'convocatoria_codigo' => $v->convocatoria_codigo,
                    'convocatoria_tipo' => $v->convocatoria_tipo,
                    'id_fin_partida' => $v->id_fin_partida,
                    'fin_partida' => $v->fin_partida,
                    'id_condicion' => $v->id_condicion,
                    'condicion' => $v->condicion,
                    'categoria_relaboral' => $v->categoria_relaboral,
                    'id_da' => $v->id_da,
                    'direccion_administrativa' => $v->direccion_administrativa,
                    'organigrama_regional_id' => $v->organigrama_regional_id,
                    'organigrama_regional' => $v->organigrama_regional,
                    'id_regional' => $v->id_regional,
                    'regional' => $v->regional,
                    'regional_codigo' => $v->regional_codigo,
                    'id_departamento' => $v->id_departamento,
                    'departamento' => $v->departamento,
                    'id_provincia' => $v->id_provincia,
                    'provincia' => $v->provincia,
                    'id_localidad' => $v->id_localidad,
                    'localidad' => $v->localidad,
                    'residencia' => $v->residencia,
                    'unidad_ejecutora' => $v->unidad_ejecutora,
                    'cod_ue' => $v->cod_ue,
                    'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                    'gerencia_administrativa' => $v->gerencia_administrativa,
                    'id_departamento_administrativo' => $v->id_departamento_administrativo,
                    'departamento_administrativo' => $v->departamento_administrativo,
                    'id_organigrama' => $v->id_organigrama,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'organigrama_sigla' => $v->organigrama_sigla,
                    'organigrama_orden' => $v->organigrama_orden,
                    'id_area' => $v->id_area,
                    'area' => $v->area,
                    'id_ubicacion' => $v->id_ubicacion,
                    'ubicacion' => $v->ubicacion,
                    'unidades_superiores' => $v->unidades_superiores,
                    'unidades_dependientes' => $v->unidades_dependientes,
                    'partida' => $v->partida,
                    'fuente_codigo' => $v->fuente_codigo,
                    'fuente' => $v->fuente,
                    'organismo_codigo' => $v->organismo_codigo,
                    'organismo' => $v->organismo,
                    'observacion' => ($v->observacion != null) ? $v->observacion : "",
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'estado_abreviacion' => $v->estado_abreviacion,
                    'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                    'tiene_contrato_vigente_descripcion' => $v->tiene_contrato_vigente == 1 ? "SI" : ($v->tiene_contrato_vigente == 0 ? "NO" : ""),
                    'id_eventual' => $v->id_eventual,
                    'id_consultor' => $v->id_consultor,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                    'persona_user_reg_id' => $v->persona_user_reg_id,
                    'persona_fecha_reg' => $v->persona_fecha_reg,
                    'persona_user_mod_id' => $v->persona_user_mod_id,
                    'persona_fecha_mod' => $v->persona_fecha_mod,
                    'agrupador' => $v->agrupador
                );
            }
        }
        echo json_encode($relaborales);
    }

    /**
     * Función para listar los datos de un registro relaboral en particular.
     */
    public function getoneAction()
    {
        $this->view->disable();
        $obj = new Frelaborales();
        $relaboral = Array();
        $idUbicacion = 0;
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            $resul = $obj->getAllWithPersons(" WHERE id_relaboral=" . $_POST["id"]);
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $relaboral[] = array(
                        'nro_row' => 0,
                        'id_relaboral' => $v->id_relaboral,
                        'id_persona' => $v->id_persona,
                        'p_nombre' => $v->p_nombre,
                        's_nombre' => $v->s_nombre,
                        't_nombre' => $v->t_nombre,
                        'p_apellido' => $v->p_apellido,
                        's_apellido' => $v->s_apellido,
                        'c_apellido' => $v->c_apellido,
                        'nombres' => $v->nombres,
                        'ci' => $v->ci,
                        'expd' => $v->expd,
                        'fecha_caducidad' => $v->fecha_caducidad,
                        'num_complemento' => '',
                        'fecha_nac' => $v->fecha_nac != "" ? date("d-m-Y", strtotime($v->fecha_nac)) : "",
                        'edad' => $v->edad,
                        'lugar_nac' => $v->lugar_nac,
                        'genero' => $v->genero,
                        'e_civil' => $v->e_civil,
                        'item' => $v->item,
                        'carrera_adm' => $v->carrera_adm,
                        'num_contrato' => $v->num_contrato,
                        'contrato_numerador_estado' => $v->contrato_numerador_estado,
                        'id_solelabcontrato' => $v->id_solelabcontrato,
                        'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                        'solelabcontrato_numero' => $v->solelabcontrato_numero,
                        'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                        'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                        'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                        'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                        'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                        'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                        'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                        'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                        'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                        'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                        'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                        'motivo_baja' => $v->motivo_baja,
                        'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                        'descripcion_baja' => $v->descripcion_baja,
                        'descripcion_anu' => $v->descripcion_anu,
                        'id_cargo' => $v->id_cargo,
                        'cargo_codigo' => $v->cargo_codigo,
                        'cargo' => $v->cargo,
                        'cargo_resolucion_ministerial_id' => $v->cargo_resolucion_ministerial_id,
                        'cargo_resolucion_ministerial' => $v->cargo_resolucion_ministerial,
                        'id_nivelessalarial' => $v->id_nivelessalarial,
                        'nivelsalarial' => $v->nivelsalarial,
                        'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                        'nivelsalarial_resolucion' => $v->nivelsalarial_resolucion,
                        'numero_escala' => $v->numero_escala,
                        'gestion_escala' => $v->gestion_escala,
                        /*'sueldo' => $v->sueldo,*/
                        'sueldo' => str_replace(".00", "", $v->sueldo),
                        'id_procesocontratacion' => $v->id_procesocontratacion,
                        'proceso_codigo' => $v->proceso_codigo,
                        'id_convocatoria' => $v->id_convocatoria,
                        'convocatoria_codigo' => $v->convocatoria_codigo,
                        'convocatoria_tipo' => $v->convocatoria_tipo,
                        'id_fin_partida' => $v->id_fin_partida,
                        'fin_partida' => $v->fin_partida,
                        'id_condicion' => $v->id_condicion,
                        'condicion' => $v->condicion,
                        'categoria_relaboral' => $v->categoria_relaboral,
                        'id_da' => $v->id_da,
                        'direccion_administrativa' => $v->direccion_administrativa,
                        'organigrama_regional_id' => $v->organigrama_regional_id,
                        'organigrama_regional' => $v->organigrama_regional,
                        'id_regional' => $v->id_regional,
                        'regional' => $v->regional,
                        'regional_codigo' => $v->regional_codigo,
                        'id_departamento' => $v->id_departamento,
                        'departamento' => $v->departamento,
                        'id_provincia' => $v->id_provincia,
                        'provincia' => $v->provincia,
                        'id_localidad' => $v->id_localidad,
                        'localidad' => $v->localidad,
                        'residencia' => $v->residencia,
                        'unidad_ejecutora' => $v->unidad_ejecutora,
                        'cod_ue' => $v->cod_ue,
                        'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                        'gerencia_administrativa' => $v->gerencia_administrativa,
                        'id_departamento_administrativo' => $v->id_departamento_administrativo,
                        'departamento_administrativo' => $v->departamento_administrativo,
                        'id_organigrama' => $v->id_organigrama,
                        'unidad_administrativa' => $v->unidad_administrativa,
                        'organigrama_sigla' => $v->organigrama_sigla,
                        'organigrama_orden' => $v->organigrama_orden,
                        'id_area' => $v->id_area,
                        'area' => $v->area,
                        'id_ubicacion' => $v->id_ubicacion,
                        'ubicacion' => $v->ubicacion,
                        'unidades_superiores' => $v->unidades_superiores,
                        'unidades_dependientes' => $v->unidades_dependientes,
                        'partida' => $v->partida,
                        'fuente_codigo' => $v->fuente_codigo,
                        'fuente' => $v->fuente,
                        'organismo_codigo' => $v->organismo_codigo,
                        'organismo' => $v->organismo,
                        'observacion' => ($v->observacion != null) ? $v->observacion : "",
                        'estado' => $v->estado,
                        'estado_descripcion' => $v->estado_descripcion,
                        'estado_abreviacion' => $v->estado_abreviacion,
                        'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                        'tiene_contrato_vigente_descripcion' => $v->tiene_contrato_vigente == 1 ? "SI" : ($v->tiene_contrato_vigente == 0 ? "NO" : ""),
                        'id_eventual' => $v->id_eventual,
                        'id_consultor' => $v->id_consultor,
                        'user_reg_id' => $v->user_reg_id,
                        'fecha_reg' => $v->fecha_reg,
                        'user_mod_id' => $v->user_mod_id,
                        'fecha_mod' => $v->fecha_mod,
                        'persona_user_reg_id' => $v->persona_user_reg_id,
                        'persona_fecha_reg' => $v->persona_fecha_reg,
                        'persona_user_mod_id' => $v->persona_user_mod_id,
                        'persona_fecha_mod' => $v->persona_fecha_mod
                    );
                }
            }
        }
        echo json_encode($relaboral);
    }

    /**
     * Función para la obtención del registro relación laboral.
     */
    public function getonerelaboralAction()
    {
        $this->view->disable();
        $obj = new Frelaborales();
        $relaboral = Array();
        $idUbicacion = 0;
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            $resul = $obj->getOneRelaboralConsiderandoUltimaMovilidadInArray($_POST["id"]);
            //comprobamos si hay filas
            //if ($resul$resul->count() > 0) {
            if (count($resul) > 0) {
                foreach ($resul as $v) {
                    $relaboral[] = array(
                        'nro_row' => 0,
                        'id_relaboral' => $v->id_relaboral,
                        'id_persona' => $v->id_persona,
                        'p_nombre' => $v->p_nombre,
                        's_nombre' => $v->s_nombre,
                        't_nombre' => $v->t_nombre,
                        'p_apellido' => $v->p_apellido,
                        's_apellido' => $v->s_apellido,
                        'c_apellido' => $v->c_apellido,
                        'nombres' => $v->nombres,
                        'ci' => $v->ci,
                        'expd' => $v->expd,
                        'fecha_caducidad' => $v->fecha_caducidad,
                        'num_complemento' => '',
                        'fecha_nac' => $v->fecha_nac != "" ? date("d-m-Y", strtotime($v->fecha_nac)) : "",
                        'edad' => $v->edad,
                        'lugar_nac' => $v->lugar_nac,
                        'genero' => $v->genero,
                        'e_civil' => $v->e_civil,
                        'tiene_item' => $v->tiene_item,
                        'item' => $v->item,
                        'carrera_adm' => $v->carrera_adm,
                        'num_contrato' => $v->num_contrato,
                        'contrato_numerador_estado' => $v->contrato_numerador_estado,
                        'id_solelabcontrato' => $v->id_solelabcontrato,
                        'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                        'solelabcontrato_numero' => $v->solelabcontrato_numero,
                        'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                        'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                        'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                        'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                        'fecha_ing' => $v->fecha_ing != "" ? date("d-m-Y", strtotime($v->fecha_ing)) : "",
                        'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                        'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                        'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                        'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                        'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                        'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                        'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                        'motivo_baja' => $v->motivo_baja,
                        'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                        'descripcion_baja' => $v->descripcion_baja,
                        'descripcion_anu' => $v->descripcion_anu,
                        'id_cargo' => $v->id_cargo,
                        'cargo_codigo' => $v->cargo_codigo,
                        'cargo' => $v->cargo,
                        'cargo_resolucion_ministerial_id' => $v->cargo_resolucion_ministerial_id,
                        'cargo_resolucion_ministerial' => $v->cargo_resolucion_ministerial,
                        'id_nivelessalarial' => $v->id_nivelessalarial,
                        'nivelsalarial' => $v->nivelsalarial,
                        'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                        'nivelsalarial_resolucion' => $v->nivelsalarial_resolucion,
                        'numero_escala' => $v->numero_escala,
                        'gestion_escala' => $v->gestion_escala,
                        /*'sueldo' => $v->sueldo,*/
                        'sueldo' => str_replace(".00", "", $v->sueldo),
                        'id_procesocontratacion' => $v->id_procesocontratacion,
                        'proceso_codigo' => $v->proceso_codigo,
                        'id_convocatoria' => $v->id_convocatoria,
                        'convocatoria_codigo' => $v->convocatoria_codigo,
                        'convocatoria_tipo' => $v->convocatoria_tipo,
                        'id_fin_partida' => $v->id_fin_partida,
                        'fin_partida' => $v->fin_partida,
                        'id_condicion' => $v->id_condicion,
                        'condicion' => $v->condicion,
                        'categoria_relaboral' => $v->categoria_relaboral,
                        'id_da' => $v->id_da,
                        'direccion_administrativa' => $v->direccion_administrativa,
                        'organigrama_regional_id' => $v->organigrama_regional_id,
                        'organigrama_regional' => $v->organigrama_regional,
                        'id_regional' => $v->id_regional,
                        'regional' => $v->regional,
                        'regional_codigo' => $v->regional_codigo,
                        'id_departamento' => $v->id_departamento,
                        'departamento' => $v->departamento,
                        'id_provincia' => $v->id_provincia,
                        'provincia' => $v->provincia,
                        'id_localidad' => $v->id_localidad,
                        'localidad' => $v->localidad,
                        'residencia' => $v->residencia,
                        'unidad_ejecutora' => $v->unidad_ejecutora,
                        'cod_ue' => $v->cod_ue,
                        'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                        'gerencia_administrativa' => $v->gerencia_administrativa,
                        'gerencia_codigo' => $v->gerencia_codigo,
                        'id_departamento_administrativo' => $v->id_departamento_administrativo,
                        'departamento_administrativo' => $v->departamento_administrativo,
                        'departamento_codigo' => $v->departamento_codigo,
                        'id_organigrama' => $v->id_organigrama,
                        'unidad_administrativa' => $v->unidad_administrativa,
                        'organigrama_sigla' => $v->organigrama_sigla,
                        'organigrama_orden' => $v->organigrama_orden,
                        'organigrama_codigo' => $v->organigrama_codigo,
                        'id_area' => $v->id_area,
                        'area' => $v->area,
                        'area_codigo' => $v->area_codigo,
                        'id_ubicacion' => $v->id_ubicacion,
                        'ubicacion' => $v->ubicacion,
                        'unidades_superiores' => $v->unidades_superiores,
                        'unidades_dependientes' => $v->unidades_dependientes,
                        'partida' => $v->partida,
                        'fuente_codigo' => $v->fuente_codigo,
                        'fuente' => $v->fuente,
                        'organismo_codigo' => $v->organismo_codigo,
                        'organismo' => $v->organismo,
                        'observacion' => ($v->observacion != null) ? $v->observacion : "",
                        'estado' => $v->estado,
                        'estado_descripcion' => $v->estado_descripcion,
                        'estado_abreviacion' => $v->estado_abreviacion,
                        'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                        'tiene_contrato_vigente_descripcion' => $v->tiene_contrato_vigente == 1 ? "SI" : ($v->tiene_contrato_vigente == 0 ? "NO" : ""),
                        'id_eventual' => $v->id_eventual,
                        'id_consultor' => $v->id_consultor,
                        'user_reg_id' => $v->user_reg_id,
                        'fecha_reg' => $v->fecha_reg,
                        'user_mod_id' => $v->user_mod_id,
                        'fecha_mod' => $v->fecha_mod,
                        'persona_user_reg_id' => $v->persona_user_reg_id,
                        'persona_fecha_reg' => $v->persona_fecha_reg,
                        'persona_user_mod_id' => $v->persona_user_mod_id,
                        'persona_fecha_mod' => $v->persona_fecha_mod
                    );
                }
            }
        }
        echo json_encode($relaboral);
    }

    /**
     * Función para la obtención del listado de cargos al momento de registrar una nueva relación laboral.
     */
    public function listcargosAction()
    {
        /*$auth = $this->session->get('auth');
        if (isset($auth['version'])) {
            $version = $auth['version'];
        } else $version = "0.0.0";

        $this->assets->addJs('/js/relaborales/oasis.relaborales.tab.js?v=' . $version);
        $this->assets->addJs('/js/relaborales/oasis.relaborales.index.js?v=' . $version);
        $this->assets->addJs('/js/relaborales/oasis.relaborales.new.js?v=' . $version);
        $this->assets->addCss('/js/css/oasis.tabla.incrementable.css?v=' . $version);*/
        $this->view->disable();
        $obj = new Fcargos();
        $resul = $obj->getAllCargosAcefalos();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $sueldo = str_replace(".00", "", $v->sueldo);
                $relaboral[] = array(
                    'nro_row' => 0,
                    'seleccionable' => 'seleccionable',
                    'codigo' => $v->codigo,
                    'finpartida' => $v->finpartida,
                    'id_condicion' => $v->id_condicion,
                    'condicion' => $v->condicion,
                    'cargo' => $v->cargo,
                    'id_cargo' => $v->id_cargo,
                    'nivelsalarial' => $v->nivelsalarial,
                    'sueldo' => $sueldo,
                    'jefe' => $v->jefe,
                    'asistente' => $v->asistente,
                    'id_resolucion_ministerial' => $v->id_resolucion_ministerial,
                    'resolucion_ministerial' => $v->resolucion_ministerial,
                    'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                    'nivelsalarial_resolucion' => $v->nivelsalarial_resolucion,
                    'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                    'gerencia_administrativa' => $v->gerencia_administrativa,
                    'id_departamento_administrativo' => $v->id_departamento_administrativo,
                    'departamento_administrativo' => $v->departamento_administrativo,
                    'id_organigrama' => $v->id_organigrama,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'gestion' => $v->gestion,
                    'correlativo' => $v->correlativo
                );
            }
        }
        echo json_encode($relaboral);
    }

    /**
     * Función para la obtención del listado de cargos al momento de registrar una nueva relación laboral.
     */
    public function listcargosbygestionAction()
    {
        $this->view->disable();
        $obj = new Fcargos();
        $gestion = $_GET["gestion"] > 0 ? $_GET["gestion"] : 0;
        $auth = $this->session->get('auth');
        $where = "";$cargos=array();
        $pagenum = $_GET['pagenum'];
        $pagesize = $_GET['pagesize'];
        $total_rows = 0;
        $start = $pagenum * $pagesize;

        // filter data.
        if (isset($_GET['filterscount'])) {
            $filterscount = $_GET['filterscount'];

            if ($filterscount > 0) {
                $where = " WHERE (";
                $tmpdatafield = "";
                $tmpfilteroperator = "";
                for ($i = 0; $i < $filterscount; $i++) {
                    // get the filter's value.
                    $filtervalue = $_GET["filtervalue" . $i];
                    // get the filter's condition.
                    $filtercondition = $_GET["filtercondition" . $i];
                    // get the filter's column.
                    $filterdatafield = $_GET["filterdatafield" . $i];
                    // get the filter's operator.
                    $filteroperator = $_GET["filteroperator" . $i];

                    if ($tmpdatafield == "") {
                        $tmpdatafield = $filterdatafield;
                    } else if ($tmpdatafield <> $filterdatafield) {
                        $where .= ")AND(";
                    } else if ($tmpdatafield == $filterdatafield) {
                        if ($tmpfilteroperator == 0) {
                            $where .= " AND ";
                        } else
                            $where .= " OR ";
                    }
                    switch ($filtercondition) {
                        case 'NOT_EMPTY':
                        case 'NOT_NULL':
                            $where .= ' UPPER(' . $filterdatafield . ') NOT LIKE "' . '' . '"';
                            break;
                        case 'EMPTY':
                        case 'NULL':
                            $where .= ' (UPPER(' . $filterdatafield . ') LIKE "' . '' . '") OR (UPPER(' . $filterdatafield . ') IS NULL)';
                            break;
                        case 'CONTAINS_CASE_SENSITIVE':
                            $where .= ' BINARY  ' . $filterdatafield . ' LIKE "%' . $filtervalue . '%"';
                            break;
                        case 'CONTAINS':
                            $where .= ' UPPER(' . $filterdatafield . ') LIKE UPPER("%' . $filtervalue . '%")';
                            break;
                        case 'DOES_NOT_CONTAIN_CASE_SENSITIVE':
                            $where .= ' BINARY ' . $filterdatafield . ' NOT LIKE UPPER("%' . $filtervalue . '%")';
                            break;
                        case 'DOES_NOT_CONTAIN':
                            $where .= ' UPPER(' . $filterdatafield . ') NOT LIKE UPPER("%' . $filtervalue . '%")';
                            break;
                        case 'EQUAL_CASE_SENSITIVE':
                            $where .= ' BINARY ' . $filterdatafield . ' = "' . $filtervalue . '"';
                            break;
                        case "EQUAL":
                            $where .= ' ' . $filterdatafield . ' = "' . $filtervalue . '"';
                            break;
                        case 'NOT_EQUAL_CASE_SENSITIVE':
                            $where .= ' BINARY ' . $filterdatafield . ' <> "' . $filtervalue . '"';
                            break;
                        case 'NOT_EQUAL':
                            $where .= ' ' . $filterdatafield . ' <> "' . $filtervalue . '"';
                            break;
                        case 'GREATER_THAN':
                            $where .= ' ' . $filterdatafield . ' > "' . $filtervalue . '"';
                            break;
                        case 'LESS_THAN':
                            $where .= ' ' . $filterdatafield . ' < "' . $filtervalue . '"';
                            break;
                        case 'GREATER_THAN_OR_EQUAL':
                            $where .= ' ' . $filterdatafield . ' >= "' . $filtervalue . '"';
                            break;
                        case 'LESS_THAN_OR_EQUAL':
                            $where .= ' ' . $filterdatafield . ' <= "' . $filtervalue . '"';
                            break;
                        case 'STARTS_WITH_CASE_SENSITIVE':
                            $where .= ' BINARY ' . $filterdatafield . ' LIKE "' . $filtervalue . '%"';
                            break;
                        case 'STARTS_WITH':
                            $where .= ' UPPER(' . $filterdatafield . ') LIKE UPPER("' . $filtervalue . '%")';
                            break;
                        case 'ENDS_WITH_CASE_SENSITIVE':
                            $where .= ' BINARY ' . $filterdatafield . ' LIKE "%' . $filtervalue . '"';
                            break;
                        case 'ENDS_WITH':
                            $where .= ' UPPER(' . $filterdatafield . ') LIKE UPPER("%' . $filtervalue . '")';
                            break;
                    }

                    if ($i == $filterscount - 1) {
                        $where .= ')';
                    }

                    $tmpfilteroperator = $filteroperator;
                    $tmpdatafield = $filterdatafield;
                }
            }
        }
        if ($gestion >= 0) {
            $resul = $obj->getAllCargosAcefalosPorGestion($gestion, $where, '', $start, $pagesize);
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $sueldo = str_replace(".00", "", $v->sueldo);
                    $total_rows = $v->total_rows;
                    $cargos[] = array(
                        'nro_row' => 0,
                        'seleccionable' => 'seleccionable',
                        'codigo' => $v->codigo,
                        'finpartida' => $v->finpartida,
                        'id_condicion' => $v->id_condicion,
                        'condicion' => $v->condicion,
                        'cargo' => $v->cargo,
                        'id_cargo' => $v->id_cargo,
                        'nivelsalarial' => $v->nivelsalarial,
                        'sueldo' => $sueldo,
                        'jefe' => $v->jefe,
                        'asistente' => $v->asistente,
                        'id_resolucion_ministerial' => $v->id_resolucion_ministerial,
                        'resolucion_ministerial' => $v->resolucion_ministerial,
                        'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                        'nivelsalarial_resolucion' => $v->nivelsalarial_resolucion,
                        'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                        'gerencia_administrativa' => $v->gerencia_administrativa,
                        'id_departamento_administrativo' => $v->id_departamento_administrativo,
                        'departamento_administrativo' => $v->departamento_administrativo,
                        'id_organigrama' => $v->id_organigrama,
                        'unidad_administrativa' => $v->unidad_administrativa,
                        'gestion' => $v->gestion,
                        'correlativo' => $v->correlativo
                    );
                }
            }
        }
        $data[] = array(
            'TotalRows' => $total_rows,
            'Rows' => $cargos
        );
        echo json_encode($data);
    }

    /**
     * Función para listar los nombres de cargos
     */
    public function listnombrecargosAction()
    {
        $this->view->disable();
        $obj = new cargos();
        $resul = $obj->listNombresCargos();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $cargos[] = array('cargo' => $v->cargo);
            }
        }
        echo json_encode($cargos);
    }

    /**
     * Función para la obtención del listado de procesos disponibles.
     */
    public function listprocesosAction()
    {
        /*$auth = $this->session->get('auth');
        if (isset($auth['version'])) {
            $version = $auth['version'];
        } else $version = "0.0.0";

        $this->assets->addJs('/js/relaborales/oasis.relaborales.tab.js?v=' . $version);
        $this->assets->addJs('/js/relaborales/oasis.relaborales.index.js?v=' . $version);
        $this->assets->addJs('/js/relaborales/oasis.relaborales.new.js?v=' . $version);
        $this->assets->addCss('/js/css/oasis.tabla.incrementable.css?v=' . $version);*/
        $this->view->disable();
        $id_condicion = $_POST["id_condicion"];
        $obj = new Procesoscontrataciones();
        $objProcesosContrataciones = $obj->listaProcesosPorCondicion($id_condicion);
        if ($objProcesosContrataciones->count() > 0) {
            foreach ($objProcesosContrataciones as $v) {
                $procesos[] = array(
                    'id' => $v->id,
                    'codigo_proceso' => $v->codigo_proceso
                );
            }
        }
        echo json_encode($procesos);
    }

    /**
     * Función para la obtención del ubicaciones disponibles.
     */
    public function listubicacionesAction()
    {
        /*$auth = $this->session->get('auth');
        if (isset($auth['version'])) {
            $version = $auth['version'];
        } else $version = "0.0.0";

        $this->assets->addJs('/js/relaborales/oasis.relaborales.tab.js?v=' . $version);
        $this->assets->addJs('/js/relaborales/oasis.relaborales.index.js?v=' . $version);
        $this->assets->addJs('/js/relaborales/oasis.relaborales.new.js?v=' . $version);
        $this->assets->addCss('/js/css/oasis.tabla.incrementable.css?v=' . $version);*/
        $this->view->disable();
        $resul = Ubicaciones::find(array('baja_logica=1 AND (agrupador=0 OR agrupador=1)', 'order' => 'id ASC'));
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $ubicaciones[] = array(
                    'id' => $v->id,
                    'ubicacion' => $v->ubicacion
                );
            }
        }
        echo json_encode($ubicaciones);
    }

    /**
     * Función para la obtención del listado de motivos de baja disponibles.
     */
    public function listmotivosbajasAction()
    {
        /*$auth = $this->session->get('auth');
        if (isset($auth['version'])) {
            $version = $auth['version'];
        } else $version = "0.0.0";


        $this->assets->addJs('/js/relaborales/oasis.relaborales.tab.js?v=' . $version);
        $this->assets->addJs('/js/relaborales/oasis.relaborales.index.js?v=' . $version);
        $this->assets->addJs('/js/relaborales/oasis.relaborales.new.js?v=' . $version);
        $this->assets->addJs('/js/relaborales/oasis.relaborales.down.js?v=' . $version);
        $this->assets->addCss('/js/css/oasis.tabla.incrementable.css?v=' . $version);*/
        $this->view->disable();
        $resul = Motivosbajas::find(array('estado=1 AND baja_logica=1', 'order' => 'id ASC'));
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $motivosbajas[] = array(
                    'id' => $v->id,
                    'motivo_baja' => $v->motivo_baja,
                    'permanente' => $v->permanente,
                    'eventual' => $v->eventual,
                    'consultor' => $v->consultor,
                    'fecha_ren' => $v->fecha_ren,
                    'fecha_acepta_ren' => $v->fecha_acepta_ren,
                    'fecha_agra_serv' => $v->fecha_agra_serv
                );
            }
        }
        echo json_encode($motivosbajas);
    }

    /**
     * Función para el almacenamiento y actualización de un registro de relación laboral.
     * return array(EstadoResultado,Mensaje)
     * Los valores posibles para la variable EstadoResultado son:
     *  0: Error
     *   1: Procesado
     *  -1: Crítico Error
     *  -2: Error de Conexión
     *  -3: Usuario no Autorizado
     */
    public function saveAction()
    {
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $user_reg_id = $auth['id'];
        $msj = Array();
        $boletasCorregidas = 0;
        $gestion_actual = date("Y");
        $hoy = date("Y-m-d H:i:s");
        $fecha_fin = "31/12/" . $gestion_actual;
        $this->view->disable();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            /**
             * Edición de registro
             */
            $objRelaboral = Relaborales::findFirstById($_POST["id"]);
            $id_persona = $_POST['id_persona'];
            $id_cargo = $_POST['id_cargo'];
            $num_contrato = $_POST['num_contrato'];
            $observacion = $_POST['observacion'];
            $cargo = Cargos::findFirstById($id_cargo);
            $id_organigrama = $cargo->organigrama_id;
            $id_finpartida = $cargo->fin_partida_id;
            #region Modificación realizada a objeto de implementar el uso de la variable codigo_nivel en la tabla cargos
            /*$objNS = new Nivelsalariales();
            $nsArr = $objNS->getNivelSalarialActivoByCodigoNivel($cargo->codigo_nivel);
            if(count($nsArr)>0){*/
            /*$nivelsalariales = $nsArr[0];
            $id_nivelsalarial = $nivelsalariales->id;*/
            /**
             * En la modificación es necesario verificar que no se haya cambiado de cargo, si así fue,
             * sólo en ese caso se cambia el nivel salarial,
             * en caso contrarío no se modifica el nivel salarial.
             * -- Ya no se considera --
             */
            /*if($id_cargo!=$objRelaboral->cargo_id){
                $objRelaboral->nivelsalarial_id = $id_nivelsalarial;
            }*/
            /**
             *  El nivel salarial es establecido por el cargo.
             */
            $id_nivelsalarial = $cargo->nivelsalarial_id;
            $id_relaboral = null;
            $finpartida = Finpartidas::findFirstById($id_finpartida);
            $id_condicion = $finpartida->condicion_id;
            $id_area = $_POST['id_area'];
            $id_ubicacion = $_POST['id_ubicacion'];
            $id_regional = $_POST['id_regional'];
            $id_procesocontratacion = $_POST['id_procesocontratacion'];
            $date1 = new DateTime($_POST['fecha_inicio']);
            $date2 = new DateTime($_POST['fecha_incor']);
            $date3 = new DateTime($_POST['fecha_fin']);
            $fecha_ini = $date1->format('Y-m-d');
            $fecha_incor = $date2->format('Y-m-d');
            /**
             * Si la condición es consultoría se debe considerar la fecha enviada en el formulario.
             */
            if ($id_condicion == 2 || $id_condicion == 3 || $id_condicion == 7) {
                $fecha_fin = $date3->format('Y-m-d');
            } else {
                $fecha_fin = $objRelaboral->fecha_fin;
            }
            if ($id_persona > 0 && $id_cargo > 0) {
                try {
                    #region Control del identificador de relación laboral previo
                    $rp = Procesoscontrataciones::findFirst(array('id=' . $id_procesocontratacion));
                    if ($rp->tipoconvocatoria_id == 2) {

                        $resul = $objRelaboral->getIdRelaboralAmpliado($id_persona, $fecha_incor);
                        if ($resul->count() > 0) {
                            $valor = $resul[0];
                            if ($valor->o_resultado > 0) {
                                $objRelaboral->relaboral_previo_id = $valor->o_resultado;
                            } else {
                                $objRelaboral->relaboral_previo_id = null;
                            }
                        }
                    }
                    #endregion Control del identificador de relación laboral previo
                    $objRelaboral->cargo_id = $id_cargo;
                    $objRelaboral->num_contrato = $num_contrato == '' ? null : $num_contrato;
                    $objRelaboral->da_id = 1;
                    $objRelaboral->regional_id = $id_regional;
                    $objRelaboral->organigrama_id = $id_organigrama;
                    $objRelaboral->ejecutora_id = 1;
                    $objRelaboral->procesocontratacion_id = $id_procesocontratacion;
                    $objRelaboral->cargo_id = $id_cargo;
                    $objRelaboral->certificacionitem_id = null;
                    $objRelaboral->finpartida_id = $id_finpartida;
                    $objRelaboral->condicion_id = $id_condicion;
                    $objRelaboral->nivelsalarial_id = $id_nivelsalarial;
                    $objRelaboral->carrera_adm = 0;
                    $objRelaboral->pagado = 0;
                    $objRelaboral->fecha_ini = $fecha_ini;
                    $objRelaboral->fecha_incor = $fecha_incor;
                    $objRelaboral->fecha_fin = $fecha_fin;
                    $objRelaboral->observacion = ($observacion == "") ? null : $observacion;
                    /**
                     * Con este valor eventualmente para regularización de registros pasados
                     * --->
                     */
                    $objRelaboral->estado = 1;
                    $objRelaboral->fecha_baja = null;
                    $objRelaboral->motivobaja_id = null;

                    /*
                     * <---
                     */
                    $objRelaboral->baja_logica = 1;
                    $objRelaboral->user_mod_id = $user_mod_id;
                    $objRelaboral->fecha_mod = $hoy;
                    $objRelaboral->agrupador = 0;
                    $ok = $objRelaboral->save();
                    if ($ok) {
                        /**
                         * En caso de modificación de registro de relación laboral se corrigen todas las boletas que estén desfasadas.
                         */
                        $objHM = new Controlexcepciones();
                        $boletasCorregidas = $objHM->corrigeBoletasDesfasadas(0, 0);
                        /**
                         * Modificar el estado del cargo a adjudicado
                         */
                        #region Registro del área de trabajo
                        if ($id_area > 0) {
                            /*
                             * Verificando la existencia del registro de relación laboral.                             *
                             */
                            $objRA = Relaboralesareas::findFirst(array('relaboral_id=' . $objRelaboral->id, 'order' => 'id ASC'));
                            if ($objRA->id > 0) {
                                $objRA->estado = 1;
                                $objRA->baja_logica = 1;
                                $objRA->organigrama_id = $id_area;
                                $objRA->user_mod_id = $user_reg_id;
                                $objRA->fecha_mod = $hoy;
                                $objRA->save();
                            } else {
                                $objRelArea = new Relaboralesareas();
                                $objRelArea->id = null;
                                $objRelArea->relaboral_id = $objRelaboral->id;
                                $objRelArea->organigrama_id = $id_area;
                                $objRelArea->observacion = null;
                                $objRelArea->estado = 1;
                                $objRelArea->baja_logica = 1;
                                $objRelArea->agrupador = 0;
                                $objRelArea->user_reg_id = $user_reg_id;
                                $objRelArea->fecha_reg = $hoy;
                                $objRelArea->save();
                            }
                        } else {
                            /*
                             * En caso de ser necesario descartar la pertenencia de una persona a un área en la cual se haya registrado con anterioridad
                             */
                            $objRelArea = Relaboralesareas::findFirst(array('relaboral_id=' . $objRelaboral->id, 'order' => 'id ASC'));
                            if ($objRelArea != null && $objRelArea->id > 0) {
                                $objRelArea->estado = 0;
                                $objRelArea->baja_logica = 0;
                                $objRelArea->user_mod_id = $user_reg_id;
                                $objRelArea->fecha_mod = $hoy;
                                $objRelArea->save();
                            }
                        }
                        #endregion Registro del área de trabajo
                        #region Registro de la ubicación de trabajo
                        //Si se ha registrado correctamente la relación laboral y se ha definido una ubicación de trabajo
                        if ($id_ubicacion > 0) {
                            //$ru = new Relaboralesubicaciones();
                            $ru = Relaboralesubicaciones::findFirst(array('relaboral_id=:relaboral_id1:'/*,'baja_logica=:activo1:','estado=:estado1:'*/, 'bind' => array('relaboral_id1' => $objRelaboral->id,/*'activo1'=>'1','estado1'=>1*/), 'order' => 'id ASC'));
                            if ($ru->id > 0) {
                                /**
                                 * Si existia el registro de ubicación
                                 */
                                $ru->ubicacion_id = $id_ubicacion;
                                $ru->fecha_ini = $objRelaboral->fecha_ini;
                                $ru->estado = 1;
                                $ru->baja_logica = 1;
                                $ru->agrupador = 0;
                                if ($ru->save()) {
                                    //Si se ha especificado un area para la especificación de la dependencia de la persona.
                                    /*if($id_area>0){


                                    }*/
                                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se guard&oacute; correctamente.', 'id_r' => $objRelaboral->id, 'boletas_desfasadas_corregidas' => $boletasCorregidas);
                                } else {
                                    $msj = array('result' => 0, 'msj' => 'Error: No se guard&oacute; la ubicaci&oacute;n del trabajo.', 'id_r' => $objRelaboral->id, 'boletas_desfasadas_corregidas' => $boletasCorregidas);
                                }
                            } else {
                                /**
                                 * Si no se tenía registro de ubicación
                                 */
                                $ru = new Relaboralesubicaciones();
                                $ru->relaboral_id = $objRelaboral->id;
                                $ru->ubicacion_id = $id_ubicacion;
                                $ru->fecha_ini = $objRelaboral->fecha_ini;
                                $ru->estado = 1;
                                $ru->baja_logica = 1;
                                $ru->agrupador = 0;
                                if ($ru->save()) {
                                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se guard&oacute; correctamente.', 'id_r' => $objRelaboral->id, 'boletas_desfasadas_corregidas' => $boletasCorregidas);
                                } else {
                                    $msj = array('result' => 0, 'msj' => 'Error: No se guard&oacute; la ubicaci&oacute;n del trabajo.', 'id_r' => $objRelaboral->id, 'boletas_desfasadas_corregidas' => $boletasCorregidas);
                                }
                            }
                        } else {
                            $msj = array('result' => 0, 'msj' => 'Error: No se guard&oacute; la ubicaci&oacute;n del trabajo.', 'id_r' => $objRelaboral->id, 'boletas_desfasadas_corregidas' => $boletasCorregidas);
                        }
                        #region de registro de la ubicación de trabajo
                    } else {
                        foreach ($objRelaboral->getMessages() as $message) {
                            echo $message, "\n";
                        }
                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de relaci&oacute;n laboral.', 'boletas_desfasadas_corregidas' => $boletasCorregidas);
                    }
                } catch (\Exception $e) {
                    echo get_class($e), ": ", $e->getMessage(), "\n";
                    echo " File=", $e->getFile(), "\n";
                    echo " Line=", $e->getLine(), "\n";
                    echo $e->getTraceAsString();
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de relaci&oacute;n laboral.', 'boletas_desfasadas_corregidas' => $boletasCorregidas);
                }
            } else {
                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro debido a datos erroneos de la persona o cargo.', 'boletas_desfasadas_corregidas' => $boletasCorregidas);
            }
            /*} else {
                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se hall&oacute; el registro correspondiente al nivel salarial para el registro de realaci&oacute;n laboral.');
            }*/
            #endregion Modificación realizada a objeto de implementar el uso de la variable codigo_nivel en la tabla cargos
        } else {
            /**
             * Nuevo Registro
             */
            if (isset($_POST['id_persona']) && isset($_POST['id_cargo'])) {
                $id_persona = $_POST['id_persona'];
                $id_cargo = $_POST['id_cargo'];
                $num_contrato = $_POST['num_contrato'];
                $observacion = $_POST['observacion'];

                $cargo = Cargos::findFirstById($id_cargo);
                $id_organigrama = $cargo->organigrama_id;
                $id_finpartida = $cargo->fin_partida_id;
                #region Modificación realizada a objeto de implementar el uso de la variable codigo_nivel en la tabla cargos
                /*$objNS = new Nivelsalariales();
                $nsArr = $objNS->getNivelSalarialActivoByCodigoNivel($cargo->codigo_nivel);
                if(count($nsArr)>0){*/
                /*$nivelsalariales = $nsArr[0];
                $id_nivelsalarial = $nivelsalariales->id;*/
                $id_nivelsalarial = $cargo->nivelsalarial_id;
                $id_relaboral = null;
                $finpartida = Finpartidas::findFirstById($id_finpartida);
                $id_condicion = $finpartida->condicion_id;
                $id_area = $_POST['id_area'];
                $id_ubicacion = $_POST['id_ubicacion'];
                $id_regional = $_POST['id_regional'];
                $id_procesocontratacion = $_POST['id_procesocontratacion'];
                $date1 = new DateTime($_POST['fecha_inicio']);
                $date2 = new DateTime($_POST['fecha_incor']);
                $date3 = new DateTime($_POST['fecha_fin']);
                $fecha_ini = $date1->format('Y-m-d');
                $fecha_incor = $date2->format('Y-m-d');
                /**
                 * Si la condición es consultoría se debe considerar la fecha enviada en el formulario.
                 */
                if ($id_condicion == 2 || $id_condicion == 3 || $id_condicion == 7) {
                    $fecha_fin = $date3->format('Y-m-d');
                }
                if ($id_persona > 0 && $id_cargo > 0) {
                    try {
                        $objRelaboral = new Relaborales();
                        #region Control del identificador de relación laboral previo
                        $rp = Procesoscontrataciones::findFirst(array('id=' . $id_procesocontratacion));
                        if ($rp->tipoconvocatoria_id == 2) {

                            $resul = $objRelaboral->getIdRelaboralAmpliado($id_persona, $fecha_incor);
                            if ($resul->count() > 0) {
                                $valor = $resul[0];
                                if ($valor->o_resultado > 0) {
                                    $objRelaboral->relaboral_previo_id = $valor->o_resultado;
                                }
                            }
                        }
                        #endregion Control del identificador de relación laboral previo
                        $objRelaboral->id = null;
                        $objRelaboral->persona_id = $id_persona;
                        $objRelaboral->cargo_id = $id_cargo;
                        $objRelaboral->num_contrato = $num_contrato == '' ? null : $num_contrato;
                        $objRelaboral->da_id = 1;
                        $objRelaboral->regional_id = $id_regional;
                        $objRelaboral->organigrama_id = $id_organigrama;
                        $objRelaboral->ejecutora_id = 1;
                        $objRelaboral->procesocontratacion_id = $id_procesocontratacion;
                        $objRelaboral->cargo_id = $id_cargo;
                        $objRelaboral->certificacionitem_id = null;
                        $objRelaboral->finpartida_id = $id_finpartida;
                        $objRelaboral->condicion_id = $id_condicion;
                        $objRelaboral->carrera_adm = 0;
                        $objRelaboral->pagado = 0;
                        $objRelaboral->nivelsalarial_id = $id_nivelsalarial;
                        $objRelaboral->fecha_ini = $fecha_ini;
                        $objRelaboral->fecha_incor = $fecha_incor;
                        $objRelaboral->fecha_fin = $fecha_fin;
                        $objRelaboral->observacion = ($observacion == "") ? null : $observacion;
                        /*
                         * Modificación expresa debido a la anulación del formulario de aprobación de registros de relación laboral.
                         * El registro de relación laboral
                         * -->
                         */
                        $objRelaboral->estado = 1;
                        /**
                         * <--
                         */
                        $objRelaboral->baja_logica = 1;
                        $objRelaboral->user_reg_id = $user_reg_id;
                        $objRelaboral->fecha_reg = $hoy;
                        $objRelaboral->agrupador = 0;
                        $ok = $objRelaboral->save();
                        if ($ok) {
                            /**
                             * En caso de nuevo registro de relación laboral se corrigen todas las boletas que estén desfasadas.
                             */
                            $objHM = new Controlexcepciones();
                            $boletasCorregidas = $objHM->corrigeBoletasDesfasadas();
                            /**
                             * Se modifica el estado del cargo para que se considere como adjudicado.
                             */
                            //$this->adjudicarCargo($id_cargo,$objRelaboral->user_mod_id);
                            #region Registro del área de trabajo
                            if ($id_area > 0) {
                                $objRelArea = new Relaboralesareas();
                                $objRelArea->id = null;
                                $objRelArea->relaboral_id = $objRelaboral->id;
                                $objRelArea->organigrama_id = $id_area;
                                $objRelArea->observacion = null;
                                $objRelArea->estado = 1;
                                $objRelArea->baja_logica = 1;
                                $objRelArea->agrupador = 0;
                                $objRelArea->user_reg_id = $user_reg_id;
                                $objRelArea->fecha_reg = $hoy;
                                $objRelArea->save();
                            }
                            #endregion Registro del área de trabajo
                            #region Registro de la ubicación de trabajo
                            //Si se ha registrado correctamente la relación laboral y se ha definido una ubicación de trabajo
                            if ($objRelaboral->id > 0 && $id_ubicacion > 0) {
                                $ru = new Relaboralesubicaciones();
                                $ru->relaboral_id = $objRelaboral->id;
                                $ru->ubicacion_id = $id_ubicacion;
                                $ru->fecha_ini = $objRelaboral->fecha_ini;
                                $ru->estado = 1;
                                $ru->baja_logica = 1;
                                $ru->agrupador = 0;
                                if ($ru->save()) {
                                    //Si se ha especificado un area para la especificación de la dependencia de la persona.
                                    /*if($id_area>0){


                                    }*/

                                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se guard&oacute; correctamente.', 'id_r' => $objRelaboral->id, 'boletas_desfasadas_corregidas' => $boletasCorregidas);
                                } else {
                                    $msj = array('result' => 0, 'msj' => 'Error: No se guard&oacute; la ubicaci&oacute;n del trabajo.', 'id_r' => $objRelaboral->id, 'boletas_desfasadas_corregidas' => $boletasCorregidas);
                                }
                            } else {
                                $msj = array('result' => 0, 'msj' => 'Error: No se guard&oacute; la ubicaci&oacute;n del trabajo.', 'id_r' => $objRelaboral->id, 'boletas_desfasadas_corregidas' => $boletasCorregidas);
                            }
                            #region de registro de la ubicación de trabajo
                        } else {
                            foreach ($objRelaboral->getMessages() as $message) {
                                echo $message, "\n";
                            }
                            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de relaci&oacute;n laboral.', 'boletas_desfasadas_corregidas' => $boletasCorregidas);
                        }
                    } catch (\Exception $e) {
                        echo get_class($e), ": ", $e->getMessage(), "\n";
                        echo " File=", $e->getFile(), "\n";
                        echo " Line=", $e->getLine(), "\n";
                        echo $e->getTraceAsString();
                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de relaci&oacute;n laboral.', 'boletas_desfasadas_corregidas' => $boletasCorregidas);
                    }
                } else {
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro debido a datos erroneos de la persona o cargo.', 'boletas_desfasadas_corregidas' => $boletasCorregidas);
                }
                /*}else {
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se hall&oacute; el registro correspondiente al nivel salarial para el registro de realaci&oacute;n laboral.');
                }*/
                #endregion Modificación realizada a objeto de implementar el uso de la variable codigo_nivel en la tabla cargos

            } else {
                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro debido a datos erroneos de la persona o cargo.', 'boletas_desfasadas_corregidas' => $boletasCorregidas);
            }
        }
        echo json_encode($msj);
    }

    /*
     * Función para la aprobación del registro de relación laboral que se encontraba en estado EN PROCESO.
     */
    public function approveAction()
    {
        $user_mod_id = 1;
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            /**
             * Aprobación de registro
             */
            $objRelaboral = Relaborales::findFirstById($_POST["id"]);
            if ($objRelaboral->id > 0 && $objRelaboral->estado == 2) {
                try {
                    $objRelaboral->estado = 1;
                    $objRelaboral->user_mod_id = $user_mod_id;
                    $objRelaboral->fecha_mod = $hoy;
                    $ok = $objRelaboral->save();
                    if ($ok) {
                        //$this->adjudicarCargo($objRelaboral->cargo_id,$user_mod_id);
                        $msj = array('result' => 1, 'msj' => '&Eacute:xito: Se aprob&oacute; correctamente el registro de relaci&oacute;n laboral.');
                    } else {
                        $msj = array('result' => 0, 'msj' => 'Error: No se aprob&oacute; el registro de relaci&oacute;n laboral.');
                    }
                } catch (\Exception $e) {
                    echo get_class($e), ": ", $e->getMessage(), "\n";
                    echo " File=", $e->getFile(), "\n";
                    echo " Line=", $e->getLine(), "\n";
                    echo $e->getTraceAsString();
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de relaci&oacute;n laboral.');
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: El registro de relaci&oacute;n laboral no cumple con el requisito establecido para su aprobaci&oacute;n, debe estar en estado EN PROCESO.');
            }
        } else {
            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se envi&oacute; el identificador del registro de relaci&oacute;n laboral.');
        }
        echo json_encode($msj);
    }

    /**
     * Función para el la baja del registro de una relación laboral..
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
        $ok = true;
        $msj = Array();
        $boletasCorregidas = 0;
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        try {
            if (isset($_POST["id"]) && $_POST["id"] > 0) {
                /**
                 * Baja de registro
                 */
                $objRelaboral = Relaborales::findFirstById($_POST["id"]);
                $id_motivo_baja = (isset($_POST['id_motivobaja'])) ? $_POST['id_motivobaja'] : 0;
                $fecha_baja = (isset($_POST['fecha_baja'])) ? $_POST['fecha_baja'] : '31-12-2014';
                $fecha_acepta_ren = (isset($_POST['fecha_acepta_ren'])) ? $_POST['fecha_acepta_ren'] : null;
                $fecha_agra_serv = (isset($_POST['fecha_agra_serv'])) ? $_POST['fecha_agra_serv'] : "";

                if ($id_motivo_baja > 0 && $fecha_baja != "" && $fecha_baja != null) {
                    /**
                     * Control de fechas necesarias por el tipo de motivo de baja.
                     */
                    $motivobaja = Motivosbajas::findFirstById($id_motivo_baja);
                    if ($motivobaja->id > 0) {
                        /**
                         * Se cargan los datos elementales.
                         */
                        $objRelaboral->motivobaja_id = $id_motivo_baja;
                        $objRelaboral->fecha_baja = $fecha_baja;

                        /**
                         * Si la fecha de renuncia es requerida
                         */
                        if ($motivobaja->fecha_ren > 0) {
                            if (isset($_POST['fecha_ren'])) {
                                $fecha_ren = $_POST['fecha_ren'];
                                $objRelaboral->fecha_ren = $fecha_ren;
                            } elseif ($motivobaja->fecha_ren == 1) {
                                $msj = array('result' => 0, 'msj' => 'Error: Debe registrar la fecha de renuncia si desea usar el tipo de baja seleccionado.');
                                $ok = false;
                            }
                        }
                        /**
                         * Si la fecha de aceptación de renuncia es requerida
                         */
                        if ($motivobaja->fecha_acepta_ren > 0) {
                            if (isset($_POST['fecha_acepta_ren'])) {
                                $fecha_acepta_ren = $_POST['fecha_acepta_ren'];
                                $objRelaboral->fecha_acepta_ren = $fecha_acepta_ren;
                            } elseif ($motivobaja->fecha_acepta_ren == 1) {
                                $msj = array('result' => 0, 'msj' => 'Error: Debe registrar la fecha de aceptaci&oacute;n de la renuncia si desea usar el tipo de baja seleccionado.');
                                $ok = false;
                            }
                        }
                        /**
                         * Si la fecha de agradecimiento es requerida
                         */
                        if ($motivobaja->fecha_agra_serv > 0) {
                            if (isset($_POST['fecha_agra_serv'])) {
                                $fecha_agra_serv = $_POST['fecha_agra_serv'];
                                $objRelaboral->fecha_agra_serv = $fecha_agra_serv;
                            } elseif ($motivobaja->fecha_agra_serv == 1) {
                                $msj = array('result' => 0, 'msj' => 'Error: Debe registrar la fecha de agradecimiento de servicios si desea usar el tipo de baja seleccionado.');
                                $ok = false;
                            }
                        }
                        /**
                         * Si el motivo de renuncia es no incorporación, la fecha de incorporación se establece en nulo.
                         */
                        if ($motivobaja->motivo_baja == "NO SE INCORPORA") {
                            $objRelaboral->fecha_incor = null;
                            $objRelaboral->fecha_baja = $objRelaboral->fecha_ini;
                        }
                        /**
                         * Se verifica que todos los datos requeridos para una baja esten registrados
                         */
                        if ($ok) {
                            $objRelaboral->estado = 0;
                            $objRelaboral->user_mod_id = $user_mod_id;
                            $objRelaboral->fecha_mod = $hoy;
                            if ($objRelaboral->save()) {
                                /**
                                 * En caso de modificación de registro de relación laboral se corrigen todas las boletas que estén desfasadas.
                                 */
                                $objHM = new Controlexcepciones();
                                $boletasCorregidas = $objHM->corrigeBoletasDesfasadas();
                                /**
                                 * Se modifica el estado del cargo a desadjudicado a objeto de permitir su uso.
                                 */
                                //$this->desadjudicarCargo($objRelaboral->cargo_id,$objRelaboral->user_mod_id);
                                $msj = array('result' => 1, 'msj' => '&Eacute;xito: Registro de Baja realizado de modo satisfactorio.', 'boletas_desfasadas_corregidas' => $boletasCorregidas);
                            } else {
                                foreach ($objRelaboral->getMessages() as $message) {
                                    echo $message, "\n";
                                }
                                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de relaci&oacute;n laboral.', 'boletas_desfasadas_corregidas' => $boletasCorregidas);
                            }
                        }
                    } else $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se registr&oacute; la baja de la relaci&oacute;n laboral debido a datos inv&acute;lidos para la tarea.');
                } else $msj = array('result' => 0, 'msj' => 'Error: No se registr&oacute; la baja de la relaci&oacute;n laboral debido a datos inv&acute;lidos para la tarea.');
            } else $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de relaci&oacute;n laboral debido a que no se especific&oacute; el registro de relaci&oacute;n laboral.');
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de relaci&oacute;n laboral.', 'boletas_desfasadas_corregidas' => $boletasCorregidas);
        }
        echo json_encode($msj);
    }

    /**
     * Función para la obtención de los datos referentes a una persona en especifico.
     */
    public function personascontactoAction()
    {
        $personas = Array();
        $this->view->disable();
        try {
            if (isset($_POST["id"]) && $_POST["id"] > 0) {
                $id_persona = $_POST["id"];
                $obj = new Fpersonas();
                $objPersona = $obj->getOne($id_persona);
                //findFirst("id = 1");
                if ($objPersona->count() > 0) {
                    foreach ($objPersona as $v) {
                        $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                        $date = new DateTime($v->fecha_nac);
                        $fecha_nac = $date->format('d') . "-" . $meses[$date->format('m') - 1] . "-" . $date->format('Y');
                        $personas[] = array(
                            'id_persona' => $v->id_persona,
                            'postulante_id' => $v->postulante_id,
                            'p_nombre' => $v->p_nombre,
                            's_nombre' => $v->s_nombre,
                            't_nombre' => $v->t_nombre,
                            'p_apellido' => $v->p_apellido,
                            's_apellido' => $v->s_apellido,
                            'c_apellido' => $v->c_apellido,
                            'tipo_documento' => $v->tipo_documento,
                            'ci' => $v->ci,
                            'expd' => $v->expd,
                            'fecha_caducidad' => $v->fecha_caducidad,
                            'num_complemento' => '',
                            'fecha_nac' => $v->fecha_nac != "" ? date("d-m-Y", strtotime($v->fecha_nac)) : "",
                            'edad' => $v->edad,
                            'lugar_nac' => $v->lugar_nac,
                            'genero' => $v->genero,
                            'e_civil' => $v->e_civil,
                            'codigo' => $v->codigo,
                            'nacionalidad' => $v->nacionalidad,
                            'nit' => $v->nit,
                            'num_func_sigma' => $v->num_func_sigma,
                            'grupo_sanguineo' => $v->grupo_sanguineo,
                            'num_lib_ser_militar' => $v->num_lib_ser_militar,
                            'num_reg_profesional' => $v->num_reg_profesional,
                            'observacion' => $v->observacion,
                            'estado' => $v->estado,
                            'baja_logica' => $v->baja_logica,
                            'agrupador' => $v->agrupador,
                            'user_reg_id' => $v->user_reg_id,
                            'fecha_reg' => $v->fecha_reg,
                            'user_mod_id' => $v->user_mod_id,
                            'fecha_mod' => $v->fecha_mod,
                            'direccion_dom' => ($v->direccion_dom != null) ? $v->direccion_dom : '',
                            'telefono_fijo' => ($v->telefono_fijo != null) ? $v->telefono_fijo : '',
                            'telefono_inst' => ($v->telefono_inst != null) ? $v->telefono_inst : '',
                            'telefono_fax' => ($v->telefono_fax != null) ? $v->telefono_fax : '',
                            'interno_inst' => ($v->interno_inst != null) ? $v->interno_inst : '',
                            'celular_per' => ($v->celular_per != null) ? $v->celular_per : '',
                            'celular_inst' => ($v->celular_inst != null) ? $v->celular_inst : '',
                            'num_credencial' => ($v->num_credencial != null) ? $v->num_credencial : '',
                            'ac_no' => ($v->ac_no != null) ? $v->ac_no : '',
                            'e_mail_per' => ($v->e_mail_per != null) ? $v->e_mail_per : '',
                            'e_mail_inst' => ($v->e_mail_inst != null) ? $v->e_mail_inst : '',
                            'contacto_observacion' => ($v->contacto_observacion != null) ? $v->contacto_observacion : '',
                            'contacto_estado' => ($v->contacto_estado != null) ? $v->contacto_estado : ''
                        );
                    }
                }
            }
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
        }
        echo json_encode($personas);
    }

    /*
     * Función para la obtención de la fotografía de la persona.
     * @param $ci Número de carnet de identidad
     * @param $num_complemento Número complemento
     */
    function obtenerrutafotoAction()
    {
        $this->view->disable();
        $msj = Array();
        $ruta = "";
        $rutaImagenesCredenciales = "/images/personal/";
        $extencionImagenesCredenciales = ".jpg";
        $num_complemento = "";
        if (isset($_POST["num_complemento"])) {
            $num_complemento = $_POST["num_complemento"];
        }
        try {
            if (isset($_POST["ci"])) {
                $ruta = "";
                $nombreImagenArchivo = $rutaImagenesCredenciales . trim($_POST["ci"]);
                if ($num_complemento != "") $nombreImagenArchivo = $nombreImagenArchivo . trim($num_complemento);
                $ruta = $nombreImagenArchivo . $extencionImagenesCredenciales;
                /**
                 * Se verifica la existencia del archivo
                 */
                if (file_exists(getcwd() . $ruta))
                    $msj = array('result' => 1, 'ruta' => $ruta, 'msj' => 'Resultado exitoso.');
                else $msj = array('result' => 0, 'ruta' => '/images/perfil-profesional.jpg', 'msj' => 'No se encontr&oacute; la fotograf&iacute;a. ' . $ruta);
            } else $msj = array('result' => 0, 'ruta' => '', 'msj' => 'No se envi&oacute; n&uacute;mero de documento.');
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
            $msj = array('result' => -1, 'ruta' => $ruta, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de relaci&oacute;n laboral.');
        }
        echo json_encode($msj);
    }

    /*
     *  Función para la obtención de las gestiones en las cuales
     */
    function listgestionesporpersonaAction()
    {
        $gestiones = Array();
        $this->view->disable();
        try {
            if (isset($_POST["id"]) && $_POST["id"] > 0) {
                $obj = new Relaborales();
                $arr = $obj->getCol($_POST["id"]);
                foreach ($arr as $clave => $valor) {
                    $gestiones[] = $valor;
                }
            }
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
            //$msj = array('result' => -1, 'ruta'=>$ruta, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de relaci&oacute;n laboral.');
        }
        echo json_encode($gestiones);
    }

    /**
     * Función para la obtención del listado de gestiones por registro de relaciones laborales.
     */
    function getgestionesrelaboralesAction()
    {
        $gest = Array();
        $this->view->disable();
        $obj = new Relaborales();
        $gestiones = $obj->getAllGestionesRelaboral();
        if ($gestiones->count() > 0) {
            foreach ($gestiones as $v) {
                $gest[] = $v->gestion;
            }
        }
        echo json_encode($gest);
    }

    /**
     * Función para la carga del primer listado sobre la página de gestión de relaciones laborales.
     * Se inhabilita la vista para el uso de jqwidgets,
     */
    public function listhistorialAction()
    {
        $relaboral = Array();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            $gestion = 0;
            if (isset($_POST["gestion"]) && $_POST["gestion"] > 0) {
                $gestion = $_POST["gestion"];
            }
            $this->view->disable();
            $obj = new Frelaborales();
            $resul = $obj->getAllByPerson($_POST["id"], $gestion);
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    #endregion Control de valores para fechas para evitar error al momento de mostrar en grilla
                    $relaboral[] = array(
                        'id_relaboral' => $v->id_relaboral,
                        'id_persona' => $v->id_persona,
                        'p_nombre' => $v->p_nombre,
                        's_nombre' => $v->s_nombre,
                        't_nombre' => $v->t_nombre,
                        'p_apellido' => $v->p_apellido,
                        's_apellido' => $v->s_apellido,
                        'c_apellido' => $v->c_apellido,
                        'nombres' => $v->p_nombre . " " . $v->s_nombre . " " . $v->t_nombre . " " . $v->p_apellido . " " . $v->s_apellido . " " . $v->c_apellido,
                        'ci' => $v->ci,
                        'expd' => $v->expd,
                        'fecha_caducidad' => $v->fecha_caducidad,
                        'num_complemento' => '',
                        'fecha_nac' => $v->fecha_nac != "" ? date("d-m-Y", strtotime($v->fecha_nac)) : "",
                        'edad' => $v->edad,
                        'lugar_nac' => $v->lugar_nac,
                        'genero' => $v->genero,
                        'e_civil' => $v->e_civil,
                        'item' => $v->item,
                        'carrera_adm' => $v->carrera_adm,
                        'num_contrato' => $v->num_contrato,
                        'contrato_numerador_estado' => $v->contrato_numerador_estado,
                        'id_solelabcontrato' => $v->id_solelabcontrato,
                        'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                        'solelabcontrato_numero' => $v->solelabcontrato_numero,
                        'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                        'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                        'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                        'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                        'fecha_ing' => $v->fecha_ing != "" ? date("d-m-Y", strtotime($v->fecha_ing)) : "",
                        'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                        'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                        'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                        'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                        'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                        'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                        'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                        'motivo_baja' => $v->motivo_baja,
                        'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                        'descripcion_baja' => $v->descripcion_baja,
                        'descripcion_anu' => $v->descripcion_anu,
                        'id_cargo' => $v->id_cargo,
                        'cargo_codigo' => $v->cargo_codigo,
                        'cargo' => $v->cargo,
                        'cargo_resolucion_ministerial_id' => $v->cargo_resolucion_ministerial_id,
                        'cargo_resolucion_ministerial' => $v->cargo_resolucion_ministerial,
                        'id_nivelessalarial' => $v->id_nivelessalarial,
                        'nivelsalarial' => $v->nivelsalarial,
                        'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                        'nivelsalarial_resolucion' => $v->nivelsalarial_resolucion,
                        'numero_escala' => $v->numero_escala,
                        'gestion_escala' => $v->gestion_escala,
                        'sueldo' => $v->sueldo,
                        'id_procesocontratacion' => $v->id_procesocontratacion,
                        'proceso_codigo' => $v->proceso_codigo,
                        'id_convocatoria' => $v->id_convocatoria,
                        'convocatoria_codigo' => $v->convocatoria_codigo,
                        'convocatoria_tipo' => $v->convocatoria_tipo,
                        'id_fin_partida' => $v->id_fin_partida,
                        'fin_partida' => $v->fin_partida,
                        'id_condicion' => $v->id_condicion,
                        'condicion' => $v->condicion,
                        'tiene_item' => $v->tiene_item,
                        'categoria_relaboral' => $v->categoria_relaboral,
                        'id_da' => $v->id_da,
                        'direccion_administrativa' => $v->direccion_administrativa,
                        'organigrama_regional_id' => $v->organigrama_regional_id,
                        'organigrama_regional' => $v->organigrama_regional,
                        'id_regional' => $v->id_regional,
                        'regional' => $v->regional,
                        'regional_codigo' => $v->regional_codigo,
                        'id_departamento' => $v->id_departamento,
                        'departamento' => $v->departamento,
                        'id_provincia' => $v->id_provincia,
                        'provincia' => $v->provincia,
                        'id_localidad' => $v->id_localidad,
                        'localidad' => $v->localidad,
                        'residencia' => $v->residencia,
                        'unidad_ejecutora' => $v->unidad_ejecutora,
                        'cod_ue' => $v->cod_ue,
                        'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                        'gerencia_administrativa' => $v->gerencia_administrativa,
                        'id_departamento_administrativo' => $v->id_departamento_administrativo,
                        'departamento_administrativo' => $v->departamento_administrativo,
                        'id_organigrama' => $v->id_organigrama,
                        'unidad_administrativa' => $v->unidad_administrativa,
                        'organigrama_sigla' => $v->organigrama_sigla,
                        'organigrama_orden' => $v->organigrama_orden,
                        'id_area' => $v->id_area,
                        'area' => $v->area,
                        'id_ubicacion' => $v->id_ubicacion,
                        'ubicacion' => $v->ubicacion,
                        'unidades_superiores' => $v->unidades_superiores,
                        'unidades_dependientes' => $v->unidades_dependientes,
                        'partida' => $v->partida,
                        'fuente_codigo' => $v->fuente_codigo,
                        'fuente' => $v->fuente,
                        'organismo_codigo' => $v->organismo_codigo,
                        'organismo' => $v->organismo,
                        'observacion' => ($v->observacion != null) ? $v->observacion : "",
                        'estado' => $v->estado,
                        'estado_descripcion' => $v->estado_descripcion,
                        'estado_abreviacion' => $v->estado_abreviacion,
                        'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                        'tiene_contrato_vigente_descripcion' => $v->tiene_contrato_vigente == 1 ? "SI" : ($v->tiene_contrato_vigente == 0 ? "NO" : ""),
                        'id_eventual' => $v->id_eventual,
                        'id_consultor' => $v->id_consultor,
                        'user_reg_id' => $v->user_reg_id,
                        'fecha_reg' => $v->fecha_reg != "" ? date("d-m-Y", strtotime($v->fecha_reg)) : "",
                        'user_mod_id' => $v->user_mod_id,
                        'fecha_mod' => $v->fecha_mod != "" ? date("d-m-Y", strtotime($v->fecha_mod)) : "",
                        'persona_user_reg_id' => $v->persona_user_reg_id,
                        'persona_fecha_reg' => $v->persona_fecha_reg != "" ? date("d-m-Y", strtotime($v->persona_fecha_reg)) : "",
                        'persona_user_mod_id' => $v->persona_user_mod_id,
                        'persona_fecha_mod' => $v->persona_fecha_mod != "" ? date("d-m-Y", strtotime($v->persona_fecha_mod)) : "",
                    );
                }
            }
        }
        echo json_encode($relaboral);
    }

    /**
     * Función para la carga del historial de movilidad funcionaria.
     */
    public function listhistorialmovilidadAction()
    {
        $this->view->disable();
        $relaboralmovilidad = Array();
        if (isset($_GET["id"]) && $_GET["id"] > 0) {

            $obj = new Frelaboralesmovilidad();
            $resul = $obj->getAllByOne($_GET["id"]);
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    #endregion Control de valores para fechas para evitar error al momento de mostrar en grilla
                    $memorandum = $v->memorandum_correlativo . "/" . $v->memorandum_gestion;
                    $memorandum .= ($v->fecha_mem != "") ? " de " . date("d-m-Y", strtotime($v->fecha_mem)) : "";
                    $relaboralmovilidad[] = array(
                        'id_relaboral' => $v->id_relaboral,
                        'id_relaboralmovilidad' => $v->id_relaboralmovilidad,
                        'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                        'gerencia_administrativa' => $v->gerencia_administrativa,
                        'id_departamento_administrativo' => $v->id_departamento_administrativo,
                        'departamento_administrativo' => $v->departamento_administrativo,
                        'id_organigrama' => $v->id_organigrama,
                        'unidad_administrativa' => $v->unidad_administrativa,
                        'organigrama_sigla' => $v->organigrama_sigla,
                        'organigrama_orden' => $v->organigrama_orden,
                        'id_area' => $v->id_area,
                        'area' => $v->area,
                        'id_ubicacion' => $v->id_ubicacion,
                        'ubicacion' => $v->ubicacion,
                        'numero' => $v->numero,
                        'cargo' => $v->cargo,
                        'evento_id' => $v->evento_id,
                        'evento' => $v->evento,
                        'motivo' => $v->motivo,
                        'id_pais' => $v->id_pais,
                        'pais' => $v->pais,
                        'id_departamento' => $v->id_departamento,
                        'lugar' => $v->lugar,
                        'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                        'hora_ini' => $v->hora_ini,
                        'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                        'hora_fin' => $v->hora_fin,
                        'id_memorandum' => $v->id_memorandum,
                        'id_tipomemorandum' => $v->id_tipomemorandum,
                        'tipo_memorandum' => $v->tipo_memorandum,
                        'memorandum_correlativo' => $v->memorandum_correlativo,
                        'memorandum_gestion' => $v->memorandum_gestion,
                        'fecha_mem' => $v->fecha_mem != "" ? date("d-m-Y", strtotime($v->fecha_mem)) : "",
                        'memorandum' => $memorandum,
                        'observacion' => $v->observacion != null ? $v->observacion : '',
                        'estado' => $v->estado,
                        'estado_descripcion' => $v->estado_descripcion
                    );
                }
            }
        }
        echo json_encode($relaboralmovilidad);
    }

    /**
     * Función para la obtención del listado de áreas administrativas disponibles de acuerdo a un identificador de organigrama.
     * En caso de que dicho valor sea nulo o cero se devolverán todas las areas disponibles en el organigrama.
     */
    public function listareasAction()
    {
        $organigramas = Array();
        $this->view->disable();
        if (isset($_POST["id_padre"]) && $_POST["id_padre"] >= 0) {
            $obj = new Organigramas();
            $resul = $obj->getAreas($_POST["id_padre"]);
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $organigramas[] = array(
                        'id_area' => $v->id_area,
                        'padre_id' => $v->padre_id,
                        'gestion' => $v->gestion,
                        'da_id' => $v->da_id,
                        'regional_id' => $v->regional_id,
                        'unidad_administrativa' => $v->unidad_administrativa,
                        'nivel_estructural_id' => $v->nivel_estructural_id,
                        'sigla' => $v->sigla,
                        'fecha_ini' => $v->fecha_ini,
                        'fecha_fin' => $v->fecha_fin,
                        'codigo' => $v->codigo,
                        'observacion' => $v->observacion,
                        'estado' => $v->estado,
                        'baja_logica' => $v->baja_logica,
                        'user_reg_id' => $v->user_reg_id,
                        'fecha_reg' => $v->fecha_reg,
                        'user_mod_id' => $v->user_mod_id,
                        'fecha_mod' => $v->fecha_mod,
                        'area_sustantiva' => $v->area_sustantiva
                    );
                }
            }
        }
        echo json_encode($organigramas);
    }

    /**
     * Función básica de impresión.
     * Su uso es con fines de verificación de funcionamiento simplemente.
     */
    public function printbasicAction()
    {
        $pdf = new pdfoasis();

        //$pdf->AddPage();

        $miCabecera = array('Nro', 'Nombre', 'Apellido', 'Matrícula');

        $misDatos = array(
            array('nombre' => 'Hugo', 'apellido' => 'Martínez', 'matricula' => '20420423'),
            array('nombre' => 'Araceli', 'apellido' => 'Morales', 'matricula' => '204909'),
            array('nombre' => 'Georgina', 'apellido' => 'Galindo', 'matricula' => '2043442'),
            array('nombre' => 'Luis', 'apellido' => 'Dolores', 'matricula' => '20411122'),
            array('nombre' => 'Mario', 'apellido' => 'Linares', 'matricula' => '2049990'),
            array('nombre' => 'Viridiana', 'apellido' => 'Badillo', 'matricula' => '20418855'),
            array('nombre' => 'Yadira', 'apellido' => 'García', 'matricula' => '20443335')
        );

        // $pdf->tablaHorizontal($miCabecera, $misDatos);

        $pdf->Output(); //Salida al navegador
    }

    /**
     * Función para la obtención del reporte de relaciones laborales en formato PDF.
     * @param $n_rows Cantidad de lineas
     * @param $gestion_consulta
     * @param $columns Array con las columnas mostradas en el reporte
     * @param $filtros Array con los filtros aplicados sobre las columnas.
     * @param $groups String con la cadena representativa de las columnas agrupadas. La separación es por comas.
     * @param $sorteds
     */
    public function exportpdfAction($n_rows, $gestion_consulta, $columns, $filtros, $groups, $sorteds)
    {
        $this->view->disable();
        $columns = base64_decode(str_pad(strtr($columns, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $filtros = base64_decode(str_pad(strtr($filtros, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $groups = base64_decode(str_pad(strtr($groups, '-_', '+/'), strlen($groups) % 4, '=', STR_PAD_RIGHT));
        if ($groups == 'null' || $groups == null || $groups === '""') $groups = "";
        $sorteds = base64_decode(str_pad(strtr($sorteds, '-_', '+/'), strlen($sorteds) % 4, '=', STR_PAD_RIGHT));
        $columns = json_decode($columns, true);
        $filtros = json_decode($filtros, true);
        $sub_keys = array_keys($columns);//echo $sub_keys[0];
        $n_col = count($columns);//echo$keys[1];
        $sorteds = json_decode($sorteds, true);
        $auth = $this->session->get('auth');
        $idPersona = $auth["persona_id"];
        /**
         * Especificando la configuración de las columnas
         */
        $generalConfigForAllColumns = array(
            'nro_row' => array('title' => 'Nro.', 'width' => 8, 'title-align' => 'C', 'align' => 'C', 'type' => 'int4'),
            'ubicacion' => array('title' => 'Ubicacion', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'condicion' => array('title' => 'Condicion', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'estado_descripcion' => array('title' => 'Estado', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'tiene_contrato_vigente_descripcion' => array('title' => 'Activo', 'width' => 11, 'align' => 'C', 'type' => 'varchar'),
            'nombres' => array('title' => 'Nombres', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'ci' => array('title' => 'CI', 'width' => 12, 'align' => 'C', 'type' => 'varchar'),
            'expd' => array('title' => 'Exp.', 'width' => 8, 'align' => 'C', 'type' => 'bpchar'),
            'edad' => array('title' => 'Edad', 'width' => 10, 'align' => 'C', 'type' => 'numeric'),
            'fecha_nac' => array('title' => 'F. Nac', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_cumple' => array('title' => 'F. Cumple', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'grupo_sanguineo' => array('title' => 'T/Sangre', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),
            'fecha_caducidad' => array('title' => 'Fecha Cad', 'width' => 18, 'align' => 'C', 'type' => 'bpchar'),
            'gerencia_administrativa' => array('title' => 'Gerencia', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'departamento_administrativo' => array('title' => 'Departamento', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'area' => array('title' => 'Area', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'fin_partida' => array('title' => 'Fuente', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'proceso_codigo' => array('title' => 'Proceso', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'nivelsalarial' => array('title' => 'Nivel', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'cargo' => array('title' => 'Cargo', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'sueldo' => array('title' => 'Haber', 'width' => 10, 'align' => 'R', 'type' => 'numeric'),
            'fecha_ing' => array('title' => 'Fecha Ing', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_ini' => array('title' => 'Fecha Ini', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_incor' => array('title' => 'Fecha Inc', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_fin' => array('title' => 'Fecha Fin', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_baja' => array('title' => 'Fecha Baja', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'motivo_baja' => array('title' => 'Motivo Baja', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'interno_inst' => array('title' => 'Nro. Interno', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'celular_per' => array('title' => 'Celular Per', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'celular_inst' => array('title' => 'Celular Inst', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'e_mail_per' => array('title' => 'E-mail Per', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'e_mail_inst' => array('title' => 'E-mail Inst', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'cas_fecha_emi' => array('title' => 'Fecha Emi CAS', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'cas_fecha_pres' => array('title' => 'Fecha Pres CAS', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'cas_fecha_fin_cal' => array('title' => 'Nro. CAS.', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),
            'cas_numero' => array('title' => 'Años Ant', 'width' => 18, 'align' => 'C', 'type' => 'int4'),
            'cas_codigo_verificacion' => array('title' => 'Cod. Verif. CAS', 'width' => 18, 'align' => 'C', 'type' => 'int4'),
            'cas_anios' => array('title' => 'Años CAS', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'cas_meses' => array('title' => 'Meses CAS', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'cas_dias' => array('title' => 'Dias CAS', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'mt_anios' => array('title' => 'Años MT->CAS', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'mt_meses' => array('title' => 'Meses MT->CAS', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'mt_dias' => array('title' => 'Dias MT->CAS', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'tot_anios' => array('title' => 'Total Años', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'tot_meses' => array('title' => 'Total Meses', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'tot_dias' => array('title' => 'Total Dias', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'mt_fin_mes_anios' => array('title' => 'Años Ant (Fin Mes)', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'mt_fin_mes_meses' => array('title' => 'Meses Ant (Fin Mes)', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'mt_fin_mes_dias' => array('title' => 'Dias Ant (Fin Mes)', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'observacion' => array('title' => 'Observacion', 'width' => 20, 'align' => 'L', 'type' => 'varchar')
        );
        $agruparPor = ($groups != "") ? explode(",", $groups) : array();
        $widthsSelecteds = $this->DefineWidths($generalConfigForAllColumns, $columns, $agruparPor);
        $ancho = 0;
        if (count($widthsSelecteds) > 0) {
            foreach ($widthsSelecteds as $w) {
                $ancho = $ancho + $w;
            }
            if ($ancho > 215.9) {
                if ($ancho > 270) {
                    $pdf = new pdfoasis('L', 'mm', 'Legal');
                    $pdf->pageWidth = 355;
                } else {
                    $pdf = new pdfoasis('L', 'mm', 'Letter');
                    $pdf->pageWidth = 280;
                }
            } else {
                $pdf = new pdfoasis('P', 'mm', 'Letter');
                $pdf->pageWidth = 215.9;
            }
            $pdf->tableWidth = $ancho;
            #region Proceso de generación del documento PDF
            $pdf->debug = 0;
            $pdf->title_rpt = utf8_decode('Reporte Relación Laboral');
            $pdf->header_title_empresa_rpt = utf8_decode('Empresa Estatal de Transporte por Cable "Mi Teleférico"');
            $alignSelecteds = $pdf->DefineAligns($generalConfigForAllColumns, $columns, $agruparPor);
            $colSelecteds = $pdf->DefineCols($generalConfigForAllColumns, $columns, $agruparPor);
            $colTitleSelecteds = $pdf->DefineTitleCols($generalConfigForAllColumns, $columns, $agruparPor);
            $alignTitleSelecteds = $pdf->DefineTitleAligns(count($colTitleSelecteds));
            $gruposSeleccionadosActuales = $pdf->DefineDefaultValuesForGroups($groups);
            $pdf->generalConfigForAllColumns = $generalConfigForAllColumns;
            $pdf->colTitleSelecteds = $colTitleSelecteds;
            $pdf->widthsSelecteds = $widthsSelecteds;
            $pdf->alignSelecteds = $alignSelecteds;
            $pdf->alignTitleSelecteds = $alignTitleSelecteds;
            if ($pdf->debug == 1) {
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::COLUMNAS::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($columns);
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::FILTROS::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($filtros);
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::GRUPOS::::::::::::::::::::::::::::::::::::::::::::<p>";
                echo "<p>" . $groups;
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::ORDEN::::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($sorteds);
                echo "<p>:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::<p>";
            }
            $where = '';
            $yaConsiderados = array();
            for ($k = 0; $k < count($filtros); $k++) {
                $cant = $this->obtieneCantidadVecesConsideracionPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);
                $arr_val = $this->obtieneValoresConsideradosPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);

                for ($j = 0; $j < $n_col; $j++) {
                    if ($sub_keys[$j] == $filtros[$k]['columna']) {
                        $col_fil = $columns[$sub_keys[$j]]['text'];
                    }
                }
                if ($filtros[$k]['tipo'] == 'datefilter') {
                    $filtros[$k]['valor'] = date("Y-m-d", strtotime($filtros[$k]['valor']));
                }
                $cond_fil = ' ' . $col_fil;
                if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {

                    if (strlen($where) > 0) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'NOT_EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'CONTAINS':
                                $where .= ' AND ';
                                break;
                            case 'EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                        }
                    }
                }
                if ($cant > 1) {
                    if ($pdf->debug == 1) {
                        echo "<p>::::::::::::::::::::::::::::::::::::YA CONSIDERADOS:::::::::::::::::::::::::::::::::::::::::::::::<p>";
                        print_r($yaConsiderados);
                        echo "<p>::::::::::::::::::::::::::::::::::::YA CONSIDERADOS:::::::::::::::::::::::::::::::::::::::::::::::<p>";
                    }
                    if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $cond_fil .= utf8_encode(" que sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                                break;
                            case 'NOT_EMPTY':
                                $cond_fil .= utf8_encode(" que no sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                                break;
                            case 'CONTAINS':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                if ($filtros[$k]['columna'] == "nombres") {
                                    $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                                } else {
                                    $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                                }
                                break;
                            case 'EQUAL':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    if ($pdf->debug == 1) {

                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                        echo $filtros[$k]['columna'] . "-->" . $col;
                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                    }
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " = '" . $col . "'";
                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        } else $where .= " OR ";
                                    }
                                }
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    //$fecha = date("Y-m-d", $col);
                                    $fecha = $col;
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                                $where .= $filtros[$k]['columna'] . " = '" . $fecha . "'";
                                                break;
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                if ($ini == 0) {
                                                    $where .= $filtros[$k]['columna'] . " BETWEEN ";
                                                } else {
                                                    $where .= " AND ";
                                                }
                                                $fecha = date("d-m-Y", strtotime($fecha));
                                                $where .= "'" . $fecha . "'";

                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        }
                                    }
                                }
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                                $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                                break;
                        }
                        $yaConsiderados[] = $filtros[$k]['columna'];
                    }
                } else {
                    switch ($filtros[$k]['condicion']) {
                        case 'EMPTY':
                            $cond_fil .= utf8_encode(" que sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                            break;
                        case 'NOT_EMPTY':
                            $cond_fil .= utf8_encode(" que no sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                            break;
                        case 'CONTAINS':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if ($filtros[$k]['columna'] == "nombres") {
                                $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                            } else {
                                $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                            }
                            break;
                        case 'EQUAL':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                    case 'int4':
                                    case 'numeric':
                                    case 'date':
                                        //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " = '" . $filtros[$k]['valor'] . "'";
                                        break;
                                    case 'varchar':
                                    case 'bpchar':
                                        //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " ILIKE '" . $filtros[$k]['valor'] . "'";
                                        break;
                                }
                            }
                            break;
                        case 'GREATER_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' >= "' . $filtros[$k]['valor'] . '"';
                            break;
                        case 'LESS_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                            break;
                    }
                }

            }
            $obj = new Frelaborales();
            if ($where != "") $where = " WHERE " . $where;
            $groups_aux = "";
            if ($groups != "") {
                /**
                 * Se verifica que no se considere la columna nombres debido a que contenido ya esta ordenado por las
                 * columnas p_apellido, s_apellido, c_apellido, p_anombre, s_nombre, t_nombre
                 */
                if (strrpos($groups, "nombres")) {
                    if (strrpos($groups, ",")) {
                        $arr = explode(",", $groups);
                        foreach ($arr as $val) {
                            if ($val != "nombres")
                                $groups_aux[] = $val;
                        }
                        $groups = implode(",", $groups_aux);
                    } else $groups = "";
                }
                if (is_array($sorteds) && count($sorteds) > 0) {
                    if ($groups != "") $groups .= ",";
                    $coma = "";
                    if ($pdf->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                }
                if ($groups != "")
                    $groups = " ORDER BY " . $groups . ",p_apellido,s_apellido,c_apellido,p_nombre,s_nombre,t_nombre,id_da,fecha_ini";
                if ($pdf->debug == 1) echo "<p>La consulta es: " . $groups . "<p>";
            } else {
                if (is_array($sorteds) && count($sorteds) > 0) {
                    $coma = "";
                    if ($pdf->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                    $groups = " ORDER BY " . $groups;
                }

            }
            if ($pdf->debug == 1) echo "<p>WHERE------------------------->" . $where . "<p>";
            if ($pdf->debug == 1) echo "<p>GROUP BY------------------------->" . $groups . "<p>";
            //$resul = $obj->getAllWithPersonsByGestion($gestion_consulta, $where, $groups);
            $where = str_replace("'", "''", $where);
            $resul = $obj->getPaged($idPersona,1, $gestion_consulta, 0, $where, $groups, 0, 0);
            $relaboral = array();
            foreach ($resul as $v) {
                $relaboral[] = array(
                    'id_relaboral' => $v->id_relaboral,
                    'id_persona' => $v->id_persona,
                    'p_nombre' => $v->p_nombre,
                    's_nombre' => $v->s_nombre,
                    't_nombre' => $v->t_nombre,
                    'p_apellido' => $v->p_apellido,
                    's_apellido' => $v->s_apellido,
                    'c_apellido' => $v->c_apellido,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'fecha_caducidad' => $v->fecha_caducidad,
                    'num_complemento' => '',
                    'fecha_nac' => $v->fecha_nac != "" ? date("d-m-Y", strtotime($v->fecha_nac)) : "",
                    'fecha_cumple' => $v->fecha_cumple != "" ? date("d-m-Y", strtotime($v->fecha_cumple)) : "",
                    'edad' => $v->edad,
                    'lugar_nac' => $v->lugar_nac,
                    'genero' => $v->genero,
                    'e_civil' => $v->e_civil,
                    'grupo_sanguineo' => $v->grupo_sanguineo,
                    'item' => $v->item,
                    'carrera_adm' => $v->carrera_adm,
                    'num_contrato' => $v->num_contrato,
                    'contrato_numerador_estado' => $v->contrato_numerador_estado,
                    'id_solelabcontrato' => $v->id_solelabcontrato,
                    'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                    'solelabcontrato_numero' => $v->solelabcontrato_numero,
                    'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                    'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                    'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                    'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                    'fecha_ing' => $v->fecha_ing != "" ? date("d-m-Y", strtotime($v->fecha_ing)) : "",
                    'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                    'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                    'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                    'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                    'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                    'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                    'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                    'motivo_baja' => $v->motivo_baja,
                    'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                    'descripcion_baja' => $v->descripcion_baja,
                    'descripcion_anu' => $v->descripcion_anu,
                    'id_cargo' => $v->id_cargo,
                    'cargo_codigo' => $v->cargo_codigo,
                    'cargo' => $v->cargo,
                    'id_nivelessalarial' => $v->id_nivelessalarial,
                    'nivelsalarial' => $v->nivelsalarial,
                    'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                    'numero_escala' => $v->numero_escala,
                    'gestion_escala' => $v->gestion_escala,
                    //'sueldo' => $v->sueldo,
                    'sueldo' => str_replace(".00", "", $v->sueldo),
                    'id_procesocontratacion' => $v->id_procesocontratacion,
                    'proceso_codigo' => $v->proceso_codigo,
                    'id_convocatoria' => $v->id_convocatoria,
                    'convocatoria_codigo' => $v->convocatoria_codigo,
                    'convocatoria_tipo' => $v->convocatoria_tipo,
                    'id_fin_partida' => $v->id_fin_partida,
                    'fin_partida' => $v->fin_partida,
                    'id_condicion' => $v->id_condicion,
                    'condicion' => $v->condicion,
                    'categoria_relaboral' => $v->categoria_relaboral,
                    'id_da' => $v->id_da,
                    'direccion_administrativa' => $v->direccion_administrativa,
                    'organigrama_regional_id' => $v->organigrama_regional_id,
                    'organigrama_regional' => $v->organigrama_regional,
                    'id_regional' => $v->id_regional,
                    'regional' => $v->regional,
                    'regional_codigo' => $v->regional_codigo,
                    'id_departamento' => $v->id_departamento,
                    'departamento' => $v->departamento,
                    'id_provincia' => $v->id_provincia,
                    'provincia' => $v->provincia,
                    'id_localidad' => $v->id_localidad,
                    'localidad' => $v->localidad,
                    'residencia' => $v->residencia,
                    'unidad_ejecutora' => $v->unidad_ejecutora,
                    'cod_ue' => $v->cod_ue,
                    'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                    'gerencia_administrativa' => $v->gerencia_administrativa,
                    'id_departamento_administrativo' => $v->id_departamento_administrativo,
                    'departamento_administrativo' => $v->departamento_administrativo,
                    'id_organigrama' => $v->id_organigrama,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'organigrama_sigla' => $v->organigrama_sigla,
                    'organigrama_orden' => $v->organigrama_orden,
                    'id_area' => $v->id_area,
                    'area' => $v->area,
                    'id_ubicacion' => $v->id_ubicacion,
                    'ubicacion' => $v->ubicacion,
                    'unidades_superiores' => $v->unidades_superiores,
                    'unidades_dependientes' => $v->unidades_dependientes,
                    'partida' => $v->partida,
                    'fuente_codigo' => $v->fuente_codigo,
                    'fuente' => $v->fuente,
                    'organismo_codigo' => $v->organismo_codigo,
                    'organismo' => $v->organismo,
                    'observacion' => ($v->observacion != null) ? $v->observacion : "",
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'estado_abreviacion' => $v->estado_abreviacion,
                    'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                    'tiene_contrato_vigente_descripcion' => $v->tiene_contrato_vigente == 1 ? "SI" : ($v->tiene_contrato_vigente == 0 ? "NO" : ""),
                    'id_eventual' => $v->id_eventual,
                    'id_consultor' => $v->id_consultor,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                    'persona_user_reg_id' => $v->persona_user_reg_id,
                    'persona_fecha_reg' => $v->persona_fecha_reg,
                    'persona_user_mod_id' => $v->persona_user_mod_id,
                    'persona_fecha_mod' => $v->persona_fecha_mod,
                    'relaboral_previo_id' => $v->relaboral_previo_id,
                    'id_presentaciondoc' => $v->id_presentaciondoc,
                    'interno_inst' => $v->interno_inst,
                    'celular_per' => $v->celular_per,
                    'celular_inst' => $v->celular_inst,
                    'e_mail_per' => $v->e_mail_per,
                    'e_mail_inst' => $v->e_mail_inst,
                    'cas_fecha_emi' => $v->cas_fecha_emi,
                    'cas_fecha_pres' => $v->cas_fecha_pres,
                    'cas_fecha_fin_cal' => $v->cas_fecha_fin_cal,
                    'cas_numero' => $v->cas_numero,
                    'cas_codigo_verificacion' => $v->cas_codigo_verificacion,
                    'cas_anios' => $v->cas_anios,
                    'cas_meses' => $v->cas_meses,
                    'cas_dias' => $v->cas_dias,
                    'mt_anios' => $v->mt_anios,
                    'mt_meses' => $v->mt_meses,
                    'mt_dias' => $v->mt_dias,
                    'tot_anios' => $v->tot_anios,
                    'tot_meses' => $v->tot_meses,
                    'tot_dias' => $v->tot_dias,
                    'mt_fin_mes_anios' => $v->mt_fin_mes_anios,
                    'mt_fin_mes_meses' => $v->mt_fin_mes_meses,
                    'mt_fin_mes_dias' => $v->mt_fin_mes_dias,
                );
            }
            //$pdf->Open("L");
            /**
             * Si el ancho supera el establecido para una hoja tamaño carta, se la pone en posición horizontal
             */

            $pdf->AddPage();
            if ($pdf->debug == 1) {
                echo "<p>El ancho es:: " . $ancho;
            }
            #region Espacio para la definición de valores para la cabecera de la tabla
            $pdf->FechaHoraReporte = date("d-m-Y H:i:s");
            $j = 0;
            $agrupadores = array();
            //echo "<p>++++++++++>".$groups;
            if ($groups != "")
                $agrupadores = explode(",", $groups);

            $dondeCambio = array();
            $queCambio = array();

            if (count($relaboral) > 0) {
                foreach ($relaboral as $i => $val) {
                    if (($pdf->pageWidth - $pdf->tableWidth) > 0) $pdf->SetX(($pdf->pageWidth - $pdf->tableWidth) / 2);
                    if (count($agrupadores) > 0) {
                        if ($pdf->debug == 1) {
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                            print_r($gruposSeleccionadosActuales);
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                        }
                        $agregarAgrupador = 0;
                        $aux = array();
                        $dondeCambio = array();
                        foreach ($gruposSeleccionadosActuales as $key => $valor) {
                            if ($valor != $val[$key]) {
                                $agregarAgrupador = 1;
                                $aux[$key] = $val[$key];
                                $dondeCambio[] = $key;
                                $queCambio[$key] = $val[$key];
                            } else {
                                $aux[$key] = $val[$key];
                            }
                        }
                        $gruposSeleccionadosActuales = $aux;
                        if ($agregarAgrupador == 1) {
                            $pdf->Ln();
                            $pdf->DefineColorHeaderTable();
                            $pdf->SetAligns($alignTitleSelecteds);
                            //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                            $agr = $pdf->DefineTitleColsByGroups($generalConfigForAllColumns, $dondeCambio, $queCambio);
                            $pdf->Agrupador($agr);
                            $pdf->RowTitle($colTitleSelecteds);
                        }
                        $pdf->DefineColorBodyTable();
                        $pdf->SetAligns($alignSelecteds);
                        if (($pdf->pageWidth - $pdf->tableWidth) > 0) $pdf->SetX(($pdf->pageWidth - $pdf->tableWidth) / 2);
                        $rowData = $pdf->DefineRows($j + 1, $relaboral[$j], $colSelecteds);
                        $pdf->Row($rowData);

                    } else {
                        //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                        $pdf->DefineColorBodyTable();
                        $pdf->SetAligns($alignSelecteds);
                        $rowData = $pdf->DefineRows($j + 1, $val, $colSelecteds);
                        $pdf->Row($rowData);
                    }
                    //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                    $j++;
                }
            }
            $pdf->ShowLeftFooter = true;
            if ($pdf->debug == 0) $pdf->Output('reporte_relaboral.pdf', 'I');
            #endregion Proceso de generación del documento PDF
        }
    }

    /**
     * Función para sólo mostrar los registros de relación laboral que hayan sido registrados. Sin contar a las personas sin un contrato.
     * @param $n_rows
     * @param $gestion_consulta
     * @param $columns
     * @param $filtros
     * @param $groups
     * @param $sorteds
     */
    public function exportactivospdfAction($n_rows, $gestion_consulta, $columns, $filtros, $groups, $sorteds)
    {
        $this->view->disable();
        $columns = base64_decode(str_pad(strtr($columns, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $filtros = base64_decode(str_pad(strtr($filtros, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $groups = base64_decode(str_pad(strtr($groups, '-_', '+/'), strlen($groups) % 4, '=', STR_PAD_RIGHT));
        if ($groups == 'null' || $groups == null || $groups === '""') $groups = "";
        $sorteds = base64_decode(str_pad(strtr($sorteds, '-_', '+/'), strlen($sorteds) % 4, '=', STR_PAD_RIGHT));
        $columns = json_decode($columns, true);
        $filtros = json_decode($filtros, true);
        $sub_keys = array_keys($columns);//echo $sub_keys[0];
        $n_col = count($columns);//echo$keys[1];
        $sorteds = json_decode($sorteds, true);
        $auth = $this->session->get('auth');
        $idPersona = $auth["persona_id"];
        /**
         * Especificando la configuración de las columnas
         */
        $generalConfigForAllColumns = array(
            'nro_row' => array('title' => 'Nro.', 'width' => 8, 'title-align' => 'C', 'align' => 'C', 'type' => 'int4'),
            'ubicacion' => array('title' => 'Ubicacion', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'condicion' => array('title' => 'Condicion', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'estado_descripcion' => array('title' => 'Estado', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'tiene_contrato_vigente_descripcion' => array('title' => 'Activo', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'nombres' => array('title' => 'Nombres', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'ci' => array('title' => 'CI', 'width' => 12, 'align' => 'C', 'type' => 'varchar'),
            'expd' => array('title' => 'Exp.', 'width' => 8, 'align' => 'C', 'type' => 'bpchar'),
            'fecha_caducidad' => array('title' => 'Fecha Cad', 'width' => 18, 'align' => 'C', 'type' => 'bpchar'),
            'gerencia_administrativa' => array('title' => 'Gerencia', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'departamento_administrativo' => array('title' => 'Departamento', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'area' => array('title' => 'Area', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'fin_partida' => array('title' => 'Fuente', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'proceso_codigo' => array('title' => 'Proceso', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'nivelsalarial' => array('title' => 'Nivel', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'cargo' => array('title' => 'Cargo', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'sueldo' => array('title' => 'Haber', 'width' => 10, 'align' => 'R', 'type' => 'numeric'),
            'fecha_ing' => array('title' => 'Fecha Ing', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_ini' => array('title' => 'Fecha Ini', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_incor' => array('title' => 'Fecha Inc', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_fin' => array('title' => 'Fecha Fin', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_baja' => array('title' => 'Fecha Baja', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'motivo_baja' => array('title' => 'Motivo Baja', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'observacion' => array('title' => 'Observacion', 'width' => 20, 'align' => 'L', 'type' => 'varchar')
        );
        $agruparPor = ($groups != "") ? explode(",", $groups) : array();
        $widthsSelecteds = $this->DefineWidths($generalConfigForAllColumns, $columns, $agruparPor);
        $ancho = 0;
        if (count($widthsSelecteds) > 0) {
            foreach ($widthsSelecteds as $w) {
                $ancho = $ancho + $w;
            }
            if ($ancho > 215.9) {
                if ($ancho > 270) {
                    $pdf = new pdfoasis('L', 'mm', 'Legal');
                    $pdf->pageWidth = 355;
                } else {
                    $pdf = new pdfoasis('L', 'mm', 'Letter');
                    $pdf->pageWidth = 280;
                }
            } else {
                $pdf = new pdfoasis('P', 'mm', 'Letter');
                $pdf->pageWidth = 215.9;
            }
            $pdf->tableWidth = $ancho;
            #region Proceso de generación del documento PDF
            $pdf->debug = 0;
            $pdf->title_rpt = utf8_decode('Reporte Relación Laboral');
            $pdf->header_title_empresa_rpt = utf8_decode('Empresa Estatal de Transporte por Cable "Mi Teleférico"');
            $alignSelecteds = $pdf->DefineAligns($generalConfigForAllColumns, $columns, $agruparPor);
            $colSelecteds = $pdf->DefineCols($generalConfigForAllColumns, $columns, $agruparPor);
            $colTitleSelecteds = $pdf->DefineTitleCols($generalConfigForAllColumns, $columns, $agruparPor);
            $alignTitleSelecteds = $pdf->DefineTitleAligns(count($colTitleSelecteds));
            $gruposSeleccionadosActuales = $pdf->DefineDefaultValuesForGroups($groups);
            $pdf->generalConfigForAllColumns = $generalConfigForAllColumns;
            $pdf->colTitleSelecteds = $colTitleSelecteds;
            $pdf->widthsSelecteds = $widthsSelecteds;
            $pdf->alignSelecteds = $alignSelecteds;
            $pdf->alignTitleSelecteds = $alignTitleSelecteds;
            if ($pdf->debug == 1) {
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::COLUMNAS::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($columns);
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::FILTROS::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($filtros);
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::GRUPOS::::::::::::::::::::::::::::::::::::::::::::<p>";
                echo "<p>" . $groups;
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::ORDEN::::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($sorteds);
                echo "<p>:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::<p>";
            }
            $where = '';
            $yaConsiderados = array();
            for ($k = 0; $k < count($filtros); $k++) {
                $cant = $this->obtieneCantidadVecesConsideracionPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);
                $arr_val = $this->obtieneValoresConsideradosPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);

                for ($j = 0; $j < $n_col; $j++) {
                    if ($sub_keys[$j] == $filtros[$k]['columna']) {
                        $col_fil = $columns[$sub_keys[$j]]['text'];
                    }
                }
                if ($filtros[$k]['tipo'] == 'datefilter') {
                    $filtros[$k]['valor'] = date("Y-m-d", strtotime($filtros[$k]['valor']));
                }
                $cond_fil = ' ' . $col_fil;
                if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {

                    if (strlen($where) > 0) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'NOT_EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'CONTAINS':
                                $where .= ' AND ';
                                break;
                            case 'EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                        }
                    }
                }
                if ($cant > 1) {
                    if ($pdf->debug == 1) {
                        echo "<p>::::::::::::::::::::::::::::::::::::YA CONSIDERADOS:::::::::::::::::::::::::::::::::::::::::::::::<p>";
                        print_r($yaConsiderados);
                        echo "<p>::::::::::::::::::::::::::::::::::::YA CONSIDERADOS:::::::::::::::::::::::::::::::::::::::::::::::<p>";
                    }
                    if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $cond_fil .= utf8_encode(" que sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                                break;
                            case 'NOT_EMPTY':
                                $cond_fil .= utf8_encode(" que no sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                                break;
                            case 'CONTAINS':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                if ($filtros[$k]['columna'] == "nombres") {
                                    $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                                } else {
                                    $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                                }
                                break;
                            case 'EQUAL':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    if ($pdf->debug == 1) {

                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                        echo $filtros[$k]['columna'] . "-->" . $col;
                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                    }
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " = '" . $col . "'";
                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        } else $where .= " OR ";
                                    }
                                }
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    //$fecha = date("Y-m-d", $col);
                                    $fecha = $col;
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                                $where .= $filtros[$k]['columna'] . " = '" . $fecha . "'";
                                                break;
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                if ($ini == 0) {
                                                    $where .= $filtros[$k]['columna'] . " BETWEEN ";
                                                } else {
                                                    $where .= " AND ";
                                                }
                                                $fecha = date("d-m-Y", strtotime($fecha));
                                                $where .= "'" . $fecha . "'";

                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        }
                                    }
                                }
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                                $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                                break;
                        }
                        $yaConsiderados[] = $filtros[$k]['columna'];
                    }
                } else {
                    switch ($filtros[$k]['condicion']) {
                        case 'EMPTY':
                            $cond_fil .= utf8_encode(" que sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                            break;
                        case 'NOT_EMPTY':
                            $cond_fil .= utf8_encode(" que no sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                            break;
                        case 'CONTAINS':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if ($filtros[$k]['columna'] == "nombres") {
                                $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                            } else {
                                $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                            }
                            break;
                        case 'EQUAL':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                    case 'int4':
                                    case 'numeric':
                                    case 'date':
                                        //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " = '" . $filtros[$k]['valor'] . "'";
                                        break;
                                    case 'varchar':
                                    case 'bpchar':
                                        //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " ILIKE '" . $filtros[$k]['valor'] . "'";
                                        break;
                                }
                            }
                            break;
                        case 'GREATER_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' >= "' . $filtros[$k]['valor'] . '"';
                            break;
                        case 'LESS_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                            break;
                    }
                }

            }
            $obj = new Frelaborales();
            if ($where != "") $where = " WHERE " . $where;
            $groups_aux = "";
            if ($groups != "") {
                /**
                 * Se verifica que no se considere la columna nombres debido a que contenido ya esta ordenado por las
                 * columnas p_apellido, s_apellido, c_apellido, p_anombre, s_nombre, t_nombre
                 */
                if (strrpos($groups, "nombres")) {
                    if (strrpos($groups, ",")) {
                        $arr = explode(",", $groups);
                        foreach ($arr as $val) {
                            if ($val != "nombres")
                                $groups_aux[] = $val;
                        }
                        $groups = implode(",", $groups_aux);
                    } else $groups = "";
                }
                if (is_array($sorteds) && count($sorteds) > 0) {
                    if ($groups != "") $groups .= ",";
                    $coma = "";
                    if ($pdf->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                }
                if ($groups != "")
                    $groups = " ORDER BY " . $groups . ",p_apellido,s_apellido,c_apellido,p_nombre,s_nombre,t_nombre,id_da,fecha_ini";
                if ($pdf->debug == 1) echo "<p>La consulta es: " . $groups . "<p>";
            } else {
                if (is_array($sorteds) && count($sorteds) > 0) {
                    $coma = "";
                    if ($pdf->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                    $groups = " ORDER BY " . $groups;
                }

            }
            if ($pdf->debug == 1) echo "<p>WHERE------------------------->" . $where . "<p>";
            if ($pdf->debug == 1) echo "<p>GROUP BY------------------------->" . $groups . "<p>";
            //$resul = $obj->getAllInGestion($gestion_consulta, $where, $groups);
            $where = str_replace("'", "''", $where);
            $resul = $obj->getPaged($idPersona,0, $gestion_consulta, 0, $where, $groups, 0, 0);
            $relaboral = array();
            foreach ($resul as $v) {
                $relaboral[] = array(
                    'id_relaboral' => $v->id_relaboral,
                    'id_persona' => $v->id_persona,
                    'p_nombre' => $v->p_nombre,
                    's_nombre' => $v->s_nombre,
                    't_nombre' => $v->t_nombre,
                    'p_apellido' => $v->p_apellido,
                    's_apellido' => $v->s_apellido,
                    'c_apellido' => $v->c_apellido,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'fecha_caducidad' => $v->fecha_caducidad,
                    'num_complemento' => '',
                    'fecha_nac' => $v->fecha_nac != "" ? date("d-m-Y", strtotime($v->fecha_nac)) : "",
                    'fecha_cumple' => $v->fecha_cumple != "" ? date("d-m-Y", strtotime($v->fecha_cumple)) : "",
                    'edad' => $v->edad,
                    'lugar_nac' => $v->lugar_nac,
                    'genero' => $v->genero,
                    'e_civil' => $v->e_civil,
                    'item' => $v->item,
                    'carrera_adm' => $v->carrera_adm,
                    'num_contrato' => $v->num_contrato,
                    'contrato_numerador_estado' => $v->contrato_numerador_estado,
                    'id_solelabcontrato' => $v->id_solelabcontrato,
                    'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                    'solelabcontrato_numero' => $v->solelabcontrato_numero,
                    'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                    'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                    'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                    'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                    'fecha_ing' => $v->fecha_ing != "" ? date("d-m-Y", strtotime($v->fecha_ing)) : "",
                    'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                    'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                    'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                    'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                    'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                    'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                    'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                    'motivo_baja' => $v->motivo_baja,
                    'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                    'descripcion_baja' => $v->descripcion_baja,
                    'descripcion_anu' => $v->descripcion_anu,
                    'id_cargo' => $v->id_cargo,
                    'cargo_codigo' => $v->cargo_codigo,
                    'cargo' => $v->cargo,
                    'id_nivelessalarial' => $v->id_nivelessalarial,
                    'nivelsalarial' => $v->nivelsalarial,
                    'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                    'numero_escala' => $v->numero_escala,
                    'gestion_escala' => $v->gestion_escala,
                    //'sueldo' => $v->sueldo,
                    'sueldo' => str_replace(".00", "", $v->sueldo),
                    'id_procesocontratacion' => $v->id_procesocontratacion,
                    'proceso_codigo' => $v->proceso_codigo,
                    'id_convocatoria' => $v->id_convocatoria,
                    'convocatoria_codigo' => $v->convocatoria_codigo,
                    'convocatoria_tipo' => $v->convocatoria_tipo,
                    'id_fin_partida' => $v->id_fin_partida,
                    'fin_partida' => $v->fin_partida,
                    'id_condicion' => $v->id_condicion,
                    'condicion' => $v->condicion,
                    'categoria_relaboral' => $v->categoria_relaboral,
                    'id_da' => $v->id_da,
                    'direccion_administrativa' => $v->direccion_administrativa,
                    'organigrama_regional_id' => $v->organigrama_regional_id,
                    'organigrama_regional' => $v->organigrama_regional,
                    'id_regional' => $v->id_regional,
                    'regional' => $v->regional,
                    'regional_codigo' => $v->regional_codigo,
                    'id_departamento' => $v->id_departamento,
                    'departamento' => $v->departamento,
                    'id_provincia' => $v->id_provincia,
                    'provincia' => $v->provincia,
                    'id_localidad' => $v->id_localidad,
                    'localidad' => $v->localidad,
                    'residencia' => $v->residencia,
                    'unidad_ejecutora' => $v->unidad_ejecutora,
                    'cod_ue' => $v->cod_ue,
                    'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                    'gerencia_administrativa' => $v->gerencia_administrativa,
                    'id_departamento_administrativo' => $v->id_departamento_administrativo,
                    'departamento_administrativo' => $v->departamento_administrativo,
                    'id_organigrama' => $v->id_organigrama,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'organigrama_sigla' => $v->organigrama_sigla,
                    'organigrama_orden' => $v->organigrama_orden,
                    'id_area' => $v->id_area,
                    'area' => $v->area,
                    'id_ubicacion' => $v->id_ubicacion,
                    'ubicacion' => $v->ubicacion,
                    'unidades_superiores' => $v->unidades_superiores,
                    'unidades_dependientes' => $v->unidades_dependientes,
                    'partida' => $v->partida,
                    'fuente_codigo' => $v->fuente_codigo,
                    'fuente' => $v->fuente,
                    'organismo_codigo' => $v->organismo_codigo,
                    'organismo' => $v->organismo,
                    'observacion' => ($v->observacion != null) ? $v->observacion : "",
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'estado_abreviacion' => $v->estado_abreviacion,
                    'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                    'tiene_contrato_vigente_descripcion' => $v->tiene_contrato_vigente == 1 ? "SI" : ($v->tiene_contrato_vigente == 0 ? "NO" : ""),
                    'id_eventual' => $v->id_eventual,
                    'id_consultor' => $v->id_consultor,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                    'persona_user_reg_id' => $v->persona_user_reg_id,
                    'persona_fecha_reg' => $v->persona_fecha_reg,
                    'persona_user_mod_id' => $v->persona_user_mod_id,
                    'persona_fecha_mod' => $v->persona_fecha_mod,
                    'relaboral_previo_id' => $v->relaboral_previo_id
                );
            }
            //$pdf->Open("L");
            /**
             * Si el ancho supera el establecido para una hoja tamaño carta, se la pone en posición horizontal
             */

            $pdf->AddPage();
            if ($pdf->debug == 1) {
                echo "<p>El ancho es:: " . $ancho;
            }
            #region Espacio para la definición de valores para la cabecera de la tabla
            $pdf->FechaHoraReporte = date("d-m-Y H:i:s");
            $j = 0;
            $agrupadores = array();
            //echo "<p>++++++++++>".$groups;
            if ($groups != "")
                $agrupadores = explode(",", $groups);

            $dondeCambio = array();
            $queCambio = array();

            if (count($relaboral) > 0) {
                foreach ($relaboral as $i => $val) {
                    if (($pdf->pageWidth - $pdf->tableWidth) > 0) $pdf->SetX(($pdf->pageWidth - $pdf->tableWidth) / 2);
                    if (count($agrupadores) > 0) {
                        if ($pdf->debug == 1) {
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                            print_r($gruposSeleccionadosActuales);
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                        }
                        $agregarAgrupador = 0;
                        $aux = array();
                        $dondeCambio = array();
                        foreach ($gruposSeleccionadosActuales as $key => $valor) {
                            if ($valor != $val[$key]) {
                                $agregarAgrupador = 1;
                                $aux[$key] = $val[$key];
                                $dondeCambio[] = $key;
                                $queCambio[$key] = $val[$key];
                            } else {
                                $aux[$key] = $val[$key];
                            }
                        }
                        $gruposSeleccionadosActuales = $aux;
                        if ($agregarAgrupador == 1) {
                            $pdf->Ln();
                            $pdf->DefineColorHeaderTable();
                            $pdf->SetAligns($alignTitleSelecteds);
                            //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                            $agr = $pdf->DefineTitleColsByGroups($generalConfigForAllColumns, $dondeCambio, $queCambio);
                            $pdf->Agrupador($agr);
                            $pdf->RowTitle($colTitleSelecteds);
                        }
                        $pdf->DefineColorBodyTable();
                        $pdf->SetAligns($alignSelecteds);
                        if (($pdf->pageWidth - $pdf->tableWidth) > 0) $pdf->SetX(($pdf->pageWidth - $pdf->tableWidth) / 2);
                        $rowData = $pdf->DefineRows($j + 1, $relaboral[$j], $colSelecteds);
                        $pdf->Row($rowData);

                    } else {
                        //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                        $pdf->DefineColorBodyTable();
                        $pdf->SetAligns($alignSelecteds);
                        $rowData = $pdf->DefineRows($j + 1, $val, $colSelecteds);
                        $pdf->Row($rowData);
                    }
                    //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                    $j++;
                }
            }
            $pdf->ShowLeftFooter = true;
            if ($pdf->debug == 0) $pdf->Output('reporte_relaboral.pdf', 'I');
            #endregion Proceso de generación del documento PDF
        }
    }

    /**
     * Función para la obtención del reporte de relaciones laborales en formato PDF.
     * @param $n_rows Cantidad de lineas
     * @param $gestion_consulta
     * @param $columns Array con las columnas mostradas en el reporte
     * @param $filtros Array con los filtros aplicados sobre las columnas.
     * @param $groups String con la cadena representativa de las columnas agrupadas. La separación es por comas.
     * @param $sorteds
     */
    public function exportforvacpdfAction($n_rows, $gestion_consulta, $columns, $filtros, $groups, $sorteds)
    {
        $this->view->disable();
        $columns = base64_decode(str_pad(strtr($columns, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $filtros = base64_decode(str_pad(strtr($filtros, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $groups = base64_decode(str_pad(strtr($groups, '-_', '+/'), strlen($groups) % 4, '=', STR_PAD_RIGHT));
        if ($groups == 'null' || $groups == null || $groups === '""') $groups = "";
        $sorteds = base64_decode(str_pad(strtr($sorteds, '-_', '+/'), strlen($sorteds) % 4, '=', STR_PAD_RIGHT));
        $columns = json_decode($columns, true);
        $filtros = json_decode($filtros, true);
        $sub_keys = array_keys($columns);//echo $sub_keys[0];
        $n_col = count($columns);//echo$keys[1];
        $sorteds = json_decode($sorteds, true);
        $auth = $this->session->get('auth');
        $idPersona = $auth["persona_id"];
        /**
         * Especificando la configuración de las columnas
         */
        $generalConfigForAllColumns = array(
            'nro_row' => array('title' => 'Nro.', 'width' => 8, 'title-align' => 'C', 'align' => 'C', 'type' => 'int4'),
            'ubicacion' => array('title' => 'Ubicacion', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'condicion' => array('title' => 'Condicion', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'estado_descripcion' => array('title' => 'Estado', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'tiene_contrato_vigente_descripcion' => array('title' => 'Activo', 'width' => 11, 'align' => 'C', 'type' => 'varchar'),
            'nombres' => array('title' => 'Nombres', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'ci' => array('title' => 'CI', 'width' => 12, 'align' => 'C', 'type' => 'varchar'),
            'expd' => array('title' => 'Exp.', 'width' => 8, 'align' => 'C', 'type' => 'bpchar'),
            'edad' => array('title' => 'Edad', 'width' => 10, 'align' => 'C', 'type' => 'numeric'),
            'fecha_nac' => array('title' => 'F. Nac', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_cumple' => array('title' => 'F. Cumple', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'grupo_sanguineo' => array('title' => 'T/Sangre', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),
            'fecha_caducidad' => array('title' => 'Fecha Cad', 'width' => 18, 'align' => 'C', 'type' => 'bpchar'),
            'gerencia_administrativa' => array('title' => 'Gerencia', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'departamento_administrativo' => array('title' => 'Departamento', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'area' => array('title' => 'Area', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'fin_partida' => array('title' => 'Fuente', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'proceso_codigo' => array('title' => 'Proceso', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'nivelsalarial' => array('title' => 'Nivel', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'cargo' => array('title' => 'Cargo', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'sueldo' => array('title' => 'Haber', 'width' => 10, 'align' => 'R', 'type' => 'numeric'),
            'fecha_ing' => array('title' => 'Fecha Ing', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_ini' => array('title' => 'Fecha Ini', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_incor' => array('title' => 'Fecha Inc', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_fin' => array('title' => 'Fecha Fin', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_baja' => array('title' => 'Fecha Baja', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'motivo_baja' => array('title' => 'Motivo Baja', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'interno_inst' => array('title' => 'Nro. Interno', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'celular_per' => array('title' => 'Celular Per', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'celular_inst' => array('title' => 'Celular Inst', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'e_mail_per' => array('title' => 'E-mail Per', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'e_mail_inst' => array('title' => 'E-mail Inst', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'cas_fecha_emi' => array('title' => 'Fecha Emi CAS', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'cas_fecha_pres' => array('title' => 'Fecha Pres CAS', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'cas_fecha_fin_cal' => array('title' => 'Nro. CAS.', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),
            'cas_numero' => array('title' => 'Años Ant', 'width' => 18, 'align' => 'C', 'type' => 'int4'),
            'cas_codigo_verificacion' => array('title' => 'Cod. Verif. CAS', 'width' => 18, 'align' => 'C', 'type' => 'int4'),
            'cas_anios' => array('title' => 'Años CAS', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'cas_meses' => array('title' => 'Meses CAS', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'cas_dias' => array('title' => 'Dias CAS', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'mt_anios' => array('title' => 'Años MT->CAS', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'mt_meses' => array('title' => 'Meses MT->CAS', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'mt_dias' => array('title' => 'Dias MT->CAS', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'tot_anios' => array('title' => 'Total Años', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'tot_meses' => array('title' => 'Total Meses', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'tot_dias' => array('title' => 'Total Dias', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'mt_prox_fecha' => array('title' => 'Fecha (Prox Gestion)', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'mt_prox_anios' => array('title' => 'Años Ant (Prox Gestion)', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'mt_prox_meses' => array('title' => 'Meses Ant (Prox Gestion)', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'mt_prox_dias' => array('title' => 'Dias Ant (Prox Gestion)', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'observacion' => array('title' => 'Observacion', 'width' => 20, 'align' => 'L', 'type' => 'varchar')
        );
        $agruparPor = ($groups != "") ? explode(",", $groups) : array();
        $widthsSelecteds = $this->DefineWidths($generalConfigForAllColumns, $columns, $agruparPor);
        $ancho = 0;
        if (count($widthsSelecteds) > 0) {
            foreach ($widthsSelecteds as $w) {
                $ancho = $ancho + $w;
            }
            if ($ancho > 215.9) {
                if ($ancho > 270) {
                    $pdf = new pdfoasis('L', 'mm', 'Legal');
                    $pdf->pageWidth = 355;
                } else {
                    $pdf = new pdfoasis('L', 'mm', 'Letter');
                    $pdf->pageWidth = 280;
                }
            } else {
                $pdf = new pdfoasis('P', 'mm', 'Letter');
                $pdf->pageWidth = 215.9;
            }
            $pdf->tableWidth = $ancho;
            #region Proceso de generación del documento PDF
            $pdf->debug = 0;
            $pdf->title_rpt = utf8_decode('Reporte Relación Laboral (Antiguedad para Vacacion)');
            $pdf->header_title_empresa_rpt = utf8_decode('Empresa Estatal de Transporte por Cable "Mi Teleférico"');
            $alignSelecteds = $pdf->DefineAligns($generalConfigForAllColumns, $columns, $agruparPor);
            $colSelecteds = $pdf->DefineCols($generalConfigForAllColumns, $columns, $agruparPor);
            $colTitleSelecteds = $pdf->DefineTitleCols($generalConfigForAllColumns, $columns, $agruparPor);
            $alignTitleSelecteds = $pdf->DefineTitleAligns(count($colTitleSelecteds));
            $gruposSeleccionadosActuales = $pdf->DefineDefaultValuesForGroups($groups);
            $pdf->generalConfigForAllColumns = $generalConfigForAllColumns;
            $pdf->colTitleSelecteds = $colTitleSelecteds;
            $pdf->widthsSelecteds = $widthsSelecteds;
            $pdf->alignSelecteds = $alignSelecteds;
            $pdf->alignTitleSelecteds = $alignTitleSelecteds;
            if ($pdf->debug == 1) {
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::COLUMNAS::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($columns);
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::FILTROS::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($filtros);
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::GRUPOS::::::::::::::::::::::::::::::::::::::::::::<p>";
                echo "<p>" . $groups;
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::ORDEN::::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($sorteds);
                echo "<p>:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::<p>";
            }
            $where = '';
            $yaConsiderados = array();
            for ($k = 0; $k < count($filtros); $k++) {
                $cant = $this->obtieneCantidadVecesConsideracionPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);
                $arr_val = $this->obtieneValoresConsideradosPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);

                for ($j = 0; $j < $n_col; $j++) {
                    if ($sub_keys[$j] == $filtros[$k]['columna']) {
                        $col_fil = $columns[$sub_keys[$j]]['text'];
                    }
                }
                if ($filtros[$k]['tipo'] == 'datefilter') {
                    $filtros[$k]['valor'] = date("Y-m-d", strtotime($filtros[$k]['valor']));
                }
                $cond_fil = ' ' . $col_fil;
                if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {

                    if (strlen($where) > 0) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'NOT_EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'CONTAINS':
                                $where .= ' AND ';
                                break;
                            case 'EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                        }
                    }
                }
                if ($cant > 1) {
                    if ($pdf->debug == 1) {
                        echo "<p>::::::::::::::::::::::::::::::::::::YA CONSIDERADOS:::::::::::::::::::::::::::::::::::::::::::::::<p>";
                        print_r($yaConsiderados);
                        echo "<p>::::::::::::::::::::::::::::::::::::YA CONSIDERADOS:::::::::::::::::::::::::::::::::::::::::::::::<p>";
                    }
                    if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $cond_fil .= utf8_encode(" que sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                                break;
                            case 'NOT_EMPTY':
                                $cond_fil .= utf8_encode(" que no sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                                break;
                            case 'CONTAINS':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                if ($filtros[$k]['columna'] == "nombres") {
                                    $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                                } else {
                                    $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                                }
                                break;
                            case 'EQUAL':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    if ($pdf->debug == 1) {

                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                        echo $filtros[$k]['columna'] . "-->" . $col;
                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                    }
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " = '" . $col . "'";
                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        } else $where .= " OR ";
                                    }
                                }
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    //$fecha = date("Y-m-d", $col);
                                    $fecha = $col;
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                                $where .= $filtros[$k]['columna'] . " = '" . $fecha . "'";
                                                break;
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                if ($ini == 0) {
                                                    $where .= $filtros[$k]['columna'] . " BETWEEN ";
                                                } else {
                                                    $where .= " AND ";
                                                }
                                                $fecha = date("d-m-Y", strtotime($fecha));
                                                $where .= "'" . $fecha . "'";

                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        }
                                    }
                                }
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                                $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                                break;
                        }
                        $yaConsiderados[] = $filtros[$k]['columna'];
                    }
                } else {
                    switch ($filtros[$k]['condicion']) {
                        case 'EMPTY':
                            $cond_fil .= utf8_encode(" que sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                            break;
                        case 'NOT_EMPTY':
                            $cond_fil .= utf8_encode(" que no sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                            break;
                        case 'CONTAINS':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if ($filtros[$k]['columna'] == "nombres") {
                                $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                            } else {
                                $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                            }
                            break;
                        case 'EQUAL':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                    case 'int4':
                                    case 'numeric':
                                    case 'date':
                                        //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " = '" . $filtros[$k]['valor'] . "'";
                                        break;
                                    case 'varchar':
                                    case 'bpchar':
                                        //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " ILIKE '" . $filtros[$k]['valor'] . "'";
                                        break;
                                }
                            }
                            break;
                        case 'GREATER_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' >= "' . $filtros[$k]['valor'] . '"';
                            break;
                        case 'LESS_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                            break;
                    }
                }

            }
            $obj = new Frelaborales();
            if ($where != "") $where = " WHERE " . $where;
            $groups_aux = "";
            if ($groups != "") {
                /**
                 * Se verifica que no se considere la columna nombres debido a que contenido ya esta ordenado por las
                 * columnas p_apellido, s_apellido, c_apellido, p_anombre, s_nombre, t_nombre
                 */
                if (strrpos($groups, "nombres")) {
                    if (strrpos($groups, ",")) {
                        $arr = explode(",", $groups);
                        foreach ($arr as $val) {
                            if ($val != "nombres")
                                $groups_aux[] = $val;
                        }
                        $groups = implode(",", $groups_aux);
                    } else $groups = "";
                }
                if (is_array($sorteds) && count($sorteds) > 0) {
                    if ($groups != "") $groups .= ",";
                    $coma = "";
                    if ($pdf->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                }
                if ($groups != "")
                    $groups = " ORDER BY " . $groups . ",p_apellido,s_apellido,c_apellido,p_nombre,s_nombre,t_nombre,id_da,fecha_ini";
                if ($pdf->debug == 1) echo "<p>La consulta es: " . $groups . "<p>";
            } else {
                if (is_array($sorteds) && count($sorteds) > 0) {
                    $coma = "";
                    if ($pdf->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                    $groups = " ORDER BY " . $groups;
                }

            }
            if ($pdf->debug == 1) echo "<p>WHERE------------------------->" . $where . "<p>";
            if ($pdf->debug == 1) echo "<p>GROUP BY------------------------->" . $groups . "<p>";
            //$resul = $obj->getAllWithPersonsByGestion($gestion_consulta, $where, $groups);
            $where = str_replace("'", "''", $where);
            $resul = $obj->getPaged($idPersona,2, $gestion_consulta, 0, $where, $groups, 0, 0);
            $relaboral = array();
            foreach ($resul as $v) {
                $relaboral[] = array(
                    'id_relaboral' => $v->id_relaboral,
                    'id_persona' => $v->id_persona,
                    'p_nombre' => $v->p_nombre,
                    's_nombre' => $v->s_nombre,
                    't_nombre' => $v->t_nombre,
                    'p_apellido' => $v->p_apellido,
                    's_apellido' => $v->s_apellido,
                    'c_apellido' => $v->c_apellido,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'fecha_caducidad' => $v->fecha_caducidad,
                    'num_complemento' => '',
                    'fecha_nac' => $v->fecha_nac != "" ? date("d-m-Y", strtotime($v->fecha_nac)) : "",
                    'fecha_cumple' => $v->fecha_cumple != "" ? date("d-m-Y", strtotime($v->fecha_cumple)) : "",
                    'edad' => $v->edad,
                    'lugar_nac' => $v->lugar_nac,
                    'genero' => $v->genero,
                    'e_civil' => $v->e_civil,
                    'grupo_sanguineo' => $v->grupo_sanguineo,
                    'item' => $v->item,
                    'carrera_adm' => $v->carrera_adm,
                    'num_contrato' => $v->num_contrato,
                    'contrato_numerador_estado' => $v->contrato_numerador_estado,
                    'id_solelabcontrato' => $v->id_solelabcontrato,
                    'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                    'solelabcontrato_numero' => $v->solelabcontrato_numero,
                    'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                    'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                    'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                    'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                    'fecha_ing' => $v->fecha_ing != "" ? date("d-m-Y", strtotime($v->fecha_ing)) : "",
                    'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                    'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                    'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                    'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                    'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                    'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                    'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                    'motivo_baja' => $v->motivo_baja,
                    'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                    'descripcion_baja' => $v->descripcion_baja,
                    'descripcion_anu' => $v->descripcion_anu,
                    'id_cargo' => $v->id_cargo,
                    'cargo_codigo' => $v->cargo_codigo,
                    'cargo' => $v->cargo,
                    'id_nivelessalarial' => $v->id_nivelessalarial,
                    'nivelsalarial' => $v->nivelsalarial,
                    'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                    'numero_escala' => $v->numero_escala,
                    'gestion_escala' => $v->gestion_escala,
                    //'sueldo' => $v->sueldo,
                    'sueldo' => str_replace(".00", "", $v->sueldo),
                    'id_procesocontratacion' => $v->id_procesocontratacion,
                    'proceso_codigo' => $v->proceso_codigo,
                    'id_convocatoria' => $v->id_convocatoria,
                    'convocatoria_codigo' => $v->convocatoria_codigo,
                    'convocatoria_tipo' => $v->convocatoria_tipo,
                    'id_fin_partida' => $v->id_fin_partida,
                    'fin_partida' => $v->fin_partida,
                    'id_condicion' => $v->id_condicion,
                    'condicion' => $v->condicion,
                    'categoria_relaboral' => $v->categoria_relaboral,
                    'id_da' => $v->id_da,
                    'direccion_administrativa' => $v->direccion_administrativa,
                    'organigrama_regional_id' => $v->organigrama_regional_id,
                    'organigrama_regional' => $v->organigrama_regional,
                    'id_regional' => $v->id_regional,
                    'regional' => $v->regional,
                    'regional_codigo' => $v->regional_codigo,
                    'id_departamento' => $v->id_departamento,
                    'departamento' => $v->departamento,
                    'id_provincia' => $v->id_provincia,
                    'provincia' => $v->provincia,
                    'id_localidad' => $v->id_localidad,
                    'localidad' => $v->localidad,
                    'residencia' => $v->residencia,
                    'unidad_ejecutora' => $v->unidad_ejecutora,
                    'cod_ue' => $v->cod_ue,
                    'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                    'gerencia_administrativa' => $v->gerencia_administrativa,
                    'id_departamento_administrativo' => $v->id_departamento_administrativo,
                    'departamento_administrativo' => $v->departamento_administrativo,
                    'id_organigrama' => $v->id_organigrama,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'organigrama_sigla' => $v->organigrama_sigla,
                    'organigrama_orden' => $v->organigrama_orden,
                    'id_area' => $v->id_area,
                    'area' => $v->area,
                    'id_ubicacion' => $v->id_ubicacion,
                    'ubicacion' => $v->ubicacion,
                    'unidades_superiores' => $v->unidades_superiores,
                    'unidades_dependientes' => $v->unidades_dependientes,
                    'partida' => $v->partida,
                    'fuente_codigo' => $v->fuente_codigo,
                    'fuente' => $v->fuente,
                    'organismo_codigo' => $v->organismo_codigo,
                    'organismo' => $v->organismo,
                    'observacion' => ($v->observacion != null) ? $v->observacion : "",
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'estado_abreviacion' => $v->estado_abreviacion,
                    'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                    'tiene_contrato_vigente_descripcion' => $v->tiene_contrato_vigente == 1 ? "SI" : ($v->tiene_contrato_vigente == 0 ? "NO" : ""),
                    'id_eventual' => $v->id_eventual,
                    'id_consultor' => $v->id_consultor,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                    'persona_user_reg_id' => $v->persona_user_reg_id,
                    'persona_fecha_reg' => $v->persona_fecha_reg,
                    'persona_user_mod_id' => $v->persona_user_mod_id,
                    'persona_fecha_mod' => $v->persona_fecha_mod,
                    'relaboral_previo_id' => $v->relaboral_previo_id,
                    'id_presentaciondoc' => $v->id_presentaciondoc,
                    'interno_inst' => $v->interno_inst,
                    'celular_per' => $v->celular_per,
                    'celular_inst' => $v->celular_inst,
                    'e_mail_per' => $v->e_mail_per,
                    'e_mail_inst' => $v->e_mail_inst,
                    'cas_fecha_emi' => $v->cas_fecha_emi,
                    'cas_fecha_pres' => $v->cas_fecha_pres,
                    'cas_fecha_fin_cal' => $v->cas_fecha_fin_cal,
                    'cas_numero' => $v->cas_numero,
                    'cas_codigo_verificacion' => $v->cas_codigo_verificacion,
                    'cas_anios' => $v->cas_anios,
                    'cas_meses' => $v->cas_meses,
                    'cas_dias' => $v->cas_dias,
                    'mt_anios' => $v->mt_anios,
                    'mt_meses' => $v->mt_meses,
                    'mt_dias' => $v->mt_dias,
                    'tot_anios' => $v->tot_anios,
                    'tot_meses' => $v->tot_meses,
                    'tot_dias' => $v->tot_dias,
                    'mt_prox_fecha' => $v->mt_prox_fecha != "" ? date("d-m-Y", strtotime($v->mt_prox_fecha)) : "",
                    'mt_prox_anios' => $v->mt_prox_anios,
                    'mt_prox_meses' => $v->mt_prox_meses,
                    'mt_prox_dias' => $v->mt_prox_dias,
                );
            }
            //$pdf->Open("L");
            /**
             * Si el ancho supera el establecido para una hoja tamaño carta, se la pone en posición horizontal
             */

            $pdf->AddPage();
            if ($pdf->debug == 1) {
                echo "<p>El ancho es:: " . $ancho;
            }
            #region Espacio para la definición de valores para la cabecera de la tabla
            $pdf->FechaHoraReporte = date("d-m-Y H:i:s");
            $j = 0;
            $agrupadores = array();
            //echo "<p>++++++++++>".$groups;
            if ($groups != "")
                $agrupadores = explode(",", $groups);

            $dondeCambio = array();
            $queCambio = array();

            if (count($relaboral) > 0) {
                foreach ($relaboral as $i => $val) {
                    if (($pdf->pageWidth - $pdf->tableWidth) > 0) $pdf->SetX(($pdf->pageWidth - $pdf->tableWidth) / 2);
                    if (count($agrupadores) > 0) {
                        if ($pdf->debug == 1) {
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                            print_r($gruposSeleccionadosActuales);
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                        }
                        $agregarAgrupador = 0;
                        $aux = array();
                        $dondeCambio = array();
                        foreach ($gruposSeleccionadosActuales as $key => $valor) {
                            if ($valor != $val[$key]) {
                                $agregarAgrupador = 1;
                                $aux[$key] = $val[$key];
                                $dondeCambio[] = $key;
                                $queCambio[$key] = $val[$key];
                            } else {
                                $aux[$key] = $val[$key];
                            }
                        }
                        $gruposSeleccionadosActuales = $aux;
                        if ($agregarAgrupador == 1) {
                            $pdf->Ln();
                            $pdf->DefineColorHeaderTable();
                            $pdf->SetAligns($alignTitleSelecteds);
                            //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                            $agr = $pdf->DefineTitleColsByGroups($generalConfigForAllColumns, $dondeCambio, $queCambio);
                            $pdf->Agrupador($agr);
                            $pdf->RowTitle($colTitleSelecteds);
                        }
                        $pdf->DefineColorBodyTable();
                        $pdf->SetAligns($alignSelecteds);
                        if (($pdf->pageWidth - $pdf->tableWidth) > 0) $pdf->SetX(($pdf->pageWidth - $pdf->tableWidth) / 2);
                        $rowData = $pdf->DefineRows($j + 1, $relaboral[$j], $colSelecteds);
                        $pdf->Row($rowData);

                    } else {
                        //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                        $pdf->DefineColorBodyTable();
                        $pdf->SetAligns($alignSelecteds);
                        $rowData = $pdf->DefineRows($j + 1, $val, $colSelecteds);
                        $pdf->Row($rowData);
                    }
                    //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                    $j++;
                }
            }
            $pdf->ShowLeftFooter = true;
            if ($pdf->debug == 0) $pdf->Output('reporte_relaboral.pdf', 'I');
            #endregion Proceso de generación del documento PDF
        }
    }

    /**
     * Función para la exportación del reporte en formato Excel.
     * @param $n_rows Cantidad de lineas
     * @param $columns Array con las columnas mostradas en el reporte
     * @param $filtros Array con los filtros aplicados sobre las columnas.
     * @param $groups String con la cadena representativa de las columnas agrupadas. La separación es por comas.
     * @param $sorteds  Columnas ordenadas .
     */
    public function exportexcelAction($n_rows, $gestion_consulta, $columns, $filtros, $groups, $sorteds)
    {
        $this->view->disable();
        $columns = base64_decode(str_pad(strtr($columns, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $filtros = base64_decode(str_pad(strtr($filtros, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $groups = base64_decode(str_pad(strtr($groups, '-_', '+/'), strlen($groups) % 4, '=', STR_PAD_RIGHT));
        if ($groups == 'null' || $groups == null || $groups === '""') $groups = "";
        $sorteds = base64_decode(str_pad(strtr($sorteds, '-_', '+/'), strlen($sorteds) % 4, '=', STR_PAD_RIGHT));
        $columns = json_decode($columns, true);
        $filtros = json_decode($filtros, true);
        $sub_keys = array_keys($columns);//echo $sub_keys[0];
        $n_col = count($columns);//echo$keys[1];
        $sorteds = json_decode($sorteds, true);
        $auth = $this->session->get('auth');
        $idPersona = $auth["persona_id"];
        /**
         * Especificando la configuración de las columnas
         */
        $generalConfigForAllColumns = array(
            'nro_row' => array('title' => 'Nro.', 'width' => 8, 'title-align' => 'C', 'align' => 'C', 'type' => 'int4'),
            'ubicacion' => array('title' => 'Ubicacion', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'condicion' => array('title' => 'Condicion', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'estado_descripcion' => array('title' => 'Estado', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'tiene_contrato_vigente_descripcion' => array('title' => 'Activo', 'width' => 10, 'align' => 'C', 'type' => 'varchar'),
            'nombres' => array('title' => 'Nombres', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'ci' => array('title' => 'CI', 'width' => 12, 'align' => 'C', 'type' => 'varchar'),
            'expd' => array('title' => 'Exp.', 'width' => 8, 'align' => 'C', 'type' => 'bpchar'),
            'genero' => array('title' => 'Genero', 'width' => 8, 'align' => 'C', 'type' => 'bpchar'),
            'edad' => array('title' => 'Edad', 'width' => 8, 'align' => 'C', 'type' => 'numeric'),
            'fecha_nac' => array('title' => 'F. Nac', 'width' => 8, 'align' => 'C', 'type' => 'date'),
            'fecha_cumple' => array('title' => 'F. Cumple', 'width' => 8, 'align' => 'C', 'type' => 'date'),
            'grupo_sanguineo' => array('title' => 'Tipo Sangre', 'width' => 8, 'align' => 'C', 'type' => 'varchar'),
            'fecha_caducidad' => array('title' => 'Fecha Cad', 'width' => 18, 'align' => 'C', 'type' => 'bpchar'),
            'gerencia_administrativa' => array('title' => 'Gerencia', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'departamento_administrativo' => array('title' => 'Departamento', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'area' => array('title' => 'Area', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'fin_partida' => array('title' => 'Fuente', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'proceso_codigo' => array('title' => 'Proceso', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'nivelsalarial' => array('title' => 'Nivel', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'cargo' => array('title' => 'Cargo', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'sueldo' => array('title' => 'Haber', 'width' => 10, 'align' => 'R', 'type' => 'numeric'),
            'fecha_ing' => array('title' => 'Fecha Ing', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_ini' => array('title' => 'Fecha Ini', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_incor' => array('title' => 'Fecha Inc', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_fin' => array('title' => 'Fecha Fin', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_baja' => array('title' => 'Fecha Baja', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'motivo_baja' => array('title' => 'Motivo Baja', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'interno_inst' => array('title' => 'Nro. Interno', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'celular_per' => array('title' => 'Celular Per', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'celular_inst' => array('title' => 'Celular Inst', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'e_mail_per' => array('title' => 'E-mail Per', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'e_mail_inst' => array('title' => 'E-mail Inst', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'cas_fecha_emi' => array('title' => 'Fecha Emi CAS', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'cas_fecha_pres' => array('title' => 'Fecha Pres CAS', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'cas_fecha_fin_cal' => array('title' => 'Nro. CAS.', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),
            'cas_numero' => array('title' => 'Años Ant', 'width' => 18, 'align' => 'C', 'type' => 'int4'),
            'cas_codigo_verificacion' => array('title' => 'Cod. Verif. CAS', 'width' => 18, 'align' => 'C', 'type' => 'int4'),
            'cas_anios' => array('title' => 'Años CAS', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'cas_meses' => array('title' => 'Meses CAS', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'cas_dias' => array('title' => 'Dias CAS', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'mt_anios' => array('title' => 'Años MT->CAS', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'mt_meses' => array('title' => 'Meses MT->CAS', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'mt_dias' => array('title' => 'Dias MT->CAS', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'tot_anios' => array('title' => 'Total Años', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'tot_meses' => array('title' => 'Total Meses', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'tot_dias' => array('title' => 'Total Dias', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'mt_fin_mes_anios' => array('title' => 'Años (Fin Mes)', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'mt_fin_mes_meses' => array('title' => 'Meses (Fin Mes)', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'mt_fin_mes_dias' => array('title' => 'Dias (Fin Mes)', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'observacion' => array('title' => 'Observacion', 'width' => 15, 'align' => 'L', 'type' => 'varchar')
        );
        $agruparPor = ($groups != "") ? explode(",", $groups) : array();
        $widthsSelecteds = $this->DefineWidths($generalConfigForAllColumns, $columns, $agruparPor);
        $ancho = 0;
        if (count($widthsSelecteds) > 0) {
            foreach ($widthsSelecteds as $w) {
                $ancho = $ancho + $w;
            }
            $excel = new exceloasis();
            $excel->tableWidth = $ancho;
            #region Proceso de generación del documento Excel
            $excel->debug = 0;
            $excel->title_rpt = utf8_decode('Reporte Relacion Laboral');
            $excel->header_title_empresa_rpt = utf8_decode('Empresa Estatal de Transporte por Cable "Mi Teleférico"');
            $alignSelecteds = $excel->DefineAligns($generalConfigForAllColumns, $columns, $agruparPor);
            $colSelecteds = $excel->DefineCols($generalConfigForAllColumns, $columns, $agruparPor);
            $colTitleSelecteds = $excel->DefineTitleCols($generalConfigForAllColumns, $columns, $agruparPor);
            $alignTitleSelecteds = $excel->DefineTitleAligns(count($colTitleSelecteds));
            $formatTypes = $excel->DefineTypeCols($generalConfigForAllColumns, $columns, $agruparPor);
            $gruposSeleccionadosActuales = $excel->DefineDefaultValuesForGroups($groups);
            $excel->generalConfigForAllColumns = $generalConfigForAllColumns;
            $excel->colTitleSelecteds = $colTitleSelecteds;
            $excel->widthsSelecteds = $widthsSelecteds;
            $excel->alignSelecteds = $alignSelecteds;
            $excel->alignTitleSelecteds = $alignTitleSelecteds;

            $cantCol = count($colTitleSelecteds);
            $excel->ultimaLetraCabeceraTabla = $excel->columnasExcel[$cantCol - 1];
            $excel->penultimaLetraCabeceraTabla = $excel->columnasExcel[$cantCol - 2];
            $excel->numFilaCabeceraTabla = 4;
            $excel->primeraLetraCabeceraTabla = "A";
            $excel->segundaLetraCabeceraTabla = "B";
            $excel->celdaInicial = $excel->primeraLetraCabeceraTabla . $excel->numFilaCabeceraTabla;
            $excel->celdaFinal = $excel->ultimaLetraCabeceraTabla . $excel->numFilaCabeceraTabla;
            if ($cantCol <= 9) {
                $excel->defineOrientation("V");
                $excel->defineSize("C");
            } elseif ($cantCol <= 13) {
                $excel->defineOrientation("H");
                $excel->defineSize("C");
            } else {
                $excel->defineOrientation("H");
                $excel->defineSize("O");
            }
            if ($excel->debug == 1) {
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::COLUMNAS::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($columns);
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::FILTROS::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($filtros);
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::GRUPOS::::::::::::::::::::::::::::::::::::::::::::<p>";
                echo "<p>" . $groups;
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::ORDEN::::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($sorteds);
                echo "<p>:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::<p>";
            }
            $where = '';
            $yaConsiderados = array();
            for ($k = 0; $k < count($filtros); $k++) {
                $cant = $this->obtieneCantidadVecesConsideracionPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);
                $arr_val = $this->obtieneValoresConsideradosPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);

                for ($j = 0; $j < $n_col; $j++) {
                    if ($sub_keys[$j] == $filtros[$k]['columna']) {
                        $col_fil = $columns[$sub_keys[$j]]['text'];
                    }
                }
                if ($filtros[$k]['tipo'] == 'datefilter') {
                    $filtros[$k]['valor'] = date("Y-m-d", strtotime($filtros[$k]['valor']));
                }
                $cond_fil = ' ' . $col_fil;
                if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {

                    if (strlen($where) > 0) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'NOT_EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'CONTAINS':
                                $where .= ' AND ';
                                break;
                            case 'EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                        }
                    }
                }
                if ($cant > 1) {
                    if ($excel->debug == 1) {
                        echo "<p>::::::::::::::::::::::::::::::::::::YA CONSIDERADOS:::::::::::::::::::::::::::::::::::::::::::::::<p>";
                        print_r($yaConsiderados);
                        echo "<p>::::::::::::::::::::::::::::::::::::YA CONSIDERADOS:::::::::::::::::::::::::::::::::::::::::::::::<p>";
                    }
                    if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $cond_fil .= utf8_encode(" que sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                                break;
                            case 'NOT_EMPTY':
                                $cond_fil .= utf8_encode(" que no sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                                break;
                            case 'CONTAINS':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                if ($filtros[$k]['columna'] == "nombres") {
                                    $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                                } else {
                                    $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                                }
                                break;
                            case 'EQUAL':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    if ($excel->debug == 1) {

                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                        echo $filtros[$k]['columna'] . "-->" . $col;
                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                    }
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " = '" . $col . "'";
                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        } else $where .= " OR ";
                                    }
                                }
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    //$fecha = date("Y-m-d", $col);
                                    $fecha = $col;
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                                $where .= $filtros[$k]['columna'] . " = '" . $fecha . "'";
                                                break;
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                $fecha = date("d-m-Y", strtotime($fecha));
                                                if ($ini == 0) {
                                                    $where .= $filtros[$k]['columna'] . " BETWEEN ";
                                                } else {
                                                    $where .= " AND ";
                                                }
                                                $where .= "'" . $fecha . "'";

                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        }
                                    }
                                }
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                                $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                                break;
                        }
                        $yaConsiderados[] = $filtros[$k]['columna'];
                    }
                } else {
                    switch ($filtros[$k]['condicion']) {
                        case 'EMPTY':
                            $cond_fil .= utf8_encode(" que sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                            break;
                        case 'NOT_EMPTY':
                            $cond_fil .= utf8_encode(" que no sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                            break;
                        case 'CONTAINS':
                            /*$cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if ($filtros[$k]['columna'] == "nombres") {
                                $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                            } else {
                                $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                            }*/
                            $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                            break;
                        case 'EQUAL':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                    case 'int4':
                                    case 'numeric':
                                    case 'date':
                                        //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " = '" . $filtros[$k]['valor'] . "'";
                                        break;
                                    case 'varchar':
                                    case 'bpchar':
                                        //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " ILIKE '" . $filtros[$k]['valor'] . "'";
                                        break;
                                }
                            }
                            break;
                        case 'GREATER_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' >= "' . $filtros[$k]['valor'] . '"';
                            break;
                        case 'LESS_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                            break;
                    }
                }

            }
            $obj = new Frelaborales();
            if ($where != "") $where = " WHERE " . $where;
            $groups_aux = "";
            if ($groups != "") {
                /**
                 * Se verifica que no se considere la columna nombres debido a que contenido ya esta ordenado por las
                 * columnas p_apellido, s_apellido, c_apellido, p_anombre, s_nombre, t_nombre
                 */
                if (strrpos($groups, "nombres")) {
                    if (strrpos($groups, ",")) {
                        $arr = explode(",", $groups);
                        foreach ($arr as $val) {
                            if ($val != "nombres")
                                $groups_aux[] = $val;
                        }
                        $groups = implode(",", $groups_aux);
                    } else $groups = "";
                }
                if (is_array($sorteds) && count($sorteds) > 0) {
                    if ($groups != "") $groups .= ",";
                    $coma = "";
                    if ($excel->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                }
                if ($groups != "")
                    $groups = " ORDER BY " . $groups . ",p_apellido,s_apellido,c_apellido,p_nombre,s_nombre,t_nombre,id_da,fecha_ini";
                if ($excel->debug == 1) echo "<p>La consulta es: " . $groups . "<p>";
            } else {
                if (is_array($sorteds) && count($sorteds) > 0) {
                    $coma = "";
                    if ($excel->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                    $groups = " ORDER BY " . $groups;
                }

            }
            if ($excel->debug == 1) echo "<p>WHERE------------------------->" . $where . "<p>";
            if ($excel->debug == 1) echo "<p>GROUP BY------------------------->" . $groups . "<p>";
            //$resul = $obj->getAllWithPersonsByGestion($gestion_consulta, $where, $groups);
            $where = str_replace("'", "''", $where);
            $resul = $obj->getPaged($idPersona,1, $gestion_consulta, 0, $where, $groups, 0, 0);
            $relaboral = array();
            foreach ($resul as $v) {
                $relaboral[] = array(
                    'id_relaboral' => $v->id_relaboral,
                    'id_persona' => $v->id_persona,
                    'p_nombre' => $v->p_nombre,
                    's_nombre' => $v->s_nombre,
                    't_nombre' => $v->t_nombre,
                    'p_apellido' => $v->p_apellido,
                    's_apellido' => $v->s_apellido,
                    'c_apellido' => $v->c_apellido,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'fecha_caducidad' => $v->fecha_caducidad,
                    'num_complemento' => '',
                    'fecha_nac' => $v->fecha_nac != "" ? date("d-m-Y", strtotime($v->fecha_nac)) : "",
                    'fecha_cumple' => $v->fecha_cumple != "" ? date("d-m-Y", strtotime($v->fecha_cumple)) : "",
                    'edad' => $v->edad,
                    'lugar_nac' => $v->lugar_nac,
                    'genero' => $v->genero,
                    'e_civil' => $v->e_civil,
                    'grupo_sanguineo' => $v->grupo_sanguineo,
                    'item' => $v->item,
                    'carrera_adm' => $v->carrera_adm,
                    'num_contrato' => $v->num_contrato,
                    'contrato_numerador_estado' => $v->contrato_numerador_estado,
                    'id_solelabcontrato' => $v->id_solelabcontrato,
                    'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                    'solelabcontrato_numero' => $v->solelabcontrato_numero,
                    'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                    'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                    'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                    'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                    'fecha_ing' => $v->fecha_ing != "" ? date("d-m-Y", strtotime($v->fecha_ing)) : "",
                    'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                    'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                    'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                    'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                    'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                    'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                    'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                    'motivo_baja' => $v->motivo_baja,
                    'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                    'descripcion_baja' => $v->descripcion_baja,
                    'descripcion_anu' => $v->descripcion_anu,
                    'id_cargo' => $v->id_cargo,
                    'cargo_codigo' => $v->cargo_codigo,
                    'cargo' => $v->cargo,
                    'id_nivelessalarial' => $v->id_nivelessalarial,
                    'nivelsalarial' => $v->nivelsalarial,
                    'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                    'numero_escala' => $v->numero_escala,
                    'gestion_escala' => $v->gestion_escala,
                    //'sueldo' => $v->sueldo,
                    'sueldo' => str_replace(".00", "", $v->sueldo),
                    'id_procesocontratacion' => $v->id_procesocontratacion,
                    'proceso_codigo' => $v->proceso_codigo,
                    'id_convocatoria' => $v->id_convocatoria,
                    'convocatoria_codigo' => $v->convocatoria_codigo,
                    'convocatoria_tipo' => $v->convocatoria_tipo,
                    'id_fin_partida' => $v->id_fin_partida,
                    'fin_partida' => $v->fin_partida,
                    'id_condicion' => $v->id_condicion,
                    'condicion' => $v->condicion,
                    'categoria_relaboral' => $v->categoria_relaboral,
                    'id_da' => $v->id_da,
                    'direccion_administrativa' => $v->direccion_administrativa,
                    'organigrama_regional_id' => $v->organigrama_regional_id,
                    'organigrama_regional' => $v->organigrama_regional,
                    'id_regional' => $v->id_regional,
                    'regional' => $v->regional,
                    'regional_codigo' => $v->regional_codigo,
                    'id_departamento' => $v->id_departamento,
                    'departamento' => $v->departamento,
                    'id_provincia' => $v->id_provincia,
                    'provincia' => $v->provincia,
                    'id_localidad' => $v->id_localidad,
                    'localidad' => $v->localidad,
                    'residencia' => $v->residencia,
                    'unidad_ejecutora' => $v->unidad_ejecutora,
                    'cod_ue' => $v->cod_ue,
                    'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                    'gerencia_administrativa' => $v->gerencia_administrativa,
                    'id_departamento_administrativo' => $v->id_departamento_administrativo,
                    'departamento_administrativo' => $v->departamento_administrativo,
                    'id_organigrama' => $v->id_organigrama,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'organigrama_sigla' => $v->organigrama_sigla,
                    'organigrama_orden' => $v->organigrama_orden,
                    'id_area' => $v->id_area,
                    'area' => $v->area,
                    'id_ubicacion' => $v->id_ubicacion,
                    'ubicacion' => $v->ubicacion,
                    'unidades_superiores' => $v->unidades_superiores,
                    'unidades_dependientes' => $v->unidades_dependientes,
                    'partida' => $v->partida,
                    'fuente_codigo' => $v->fuente_codigo,
                    'fuente' => $v->fuente,
                    'organismo_codigo' => $v->organismo_codigo,
                    'organismo' => $v->organismo,
                    'observacion' => ($v->observacion != null) ? $v->observacion : "",
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'estado_abreviacion' => $v->estado_abreviacion,
                    'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                    'tiene_contrato_vigente_descripcion' => $v->tiene_contrato_vigente == 1 ? "SI" : ($v->tiene_contrato_vigente == 0 ? "NO" : ""),
                    'id_eventual' => $v->id_eventual,
                    'id_consultor' => $v->id_consultor,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                    'persona_user_reg_id' => $v->persona_user_reg_id,
                    'persona_fecha_reg' => $v->persona_fecha_reg,
                    'persona_user_mod_id' => $v->persona_user_mod_id,
                    'persona_fecha_mod' => $v->persona_fecha_mod,
                    'relaboral_previo_id' => $v->relaboral_previo_id,
                    'id_presentaciondoc' => $v->id_presentaciondoc,
                    'interno_inst' => $v->interno_inst,
                    'celular_per' => $v->celular_per,
                    'celular_inst' => $v->celular_inst,
                    'e_mail_per' => $v->e_mail_per,
                    'e_mail_inst' => $v->e_mail_inst,
                    'cas_fecha_emi' => $v->cas_fecha_emi,
                    'cas_fecha_pres' => $v->cas_fecha_pres,
                    'cas_fecha_fin_cal' => $v->cas_fecha_fin_cal,
                    'cas_numero' => $v->cas_numero,
                    'cas_codigo_verificacion' => $v->cas_codigo_verificacion,
                    'cas_anios' => $v->cas_anios,
                    'cas_meses' => $v->cas_meses,
                    'cas_dias' => $v->cas_dias,
                    'mt_anios' => $v->mt_anios,
                    'mt_meses' => $v->mt_meses,
                    'mt_dias' => $v->mt_dias,
                    'tot_anios' => $v->tot_anios,
                    'tot_meses' => $v->tot_meses,
                    'tot_dias' => $v->tot_dias,
                    'mt_fin_mes_anios' => $v->mt_fin_mes_anios,
                    'mt_fin_mes_meses' => $v->mt_fin_mes_meses,
                    'mt_fin_mes_dias' => $v->mt_fin_mes_dias,
                );
            }
            #region Espacio para la definición de valores para la cabecera de la tabla
            $excel->FechaHoraReporte = date("d-m-Y H:i:s");
            $j = 0;
            $agrupadores = array();
            if ($groups != "")
                $agrupadores = explode(",", $groups);

            $dondeCambio = array();
            $queCambio = array();
            $excel->header();
            $fila = $excel->numFilaCabeceraTabla;
            if (count($relaboral) > 0) {
                $excel->RowTitle($colTitleSelecteds, $fila);
                $celdaInicial = $excel->primeraLetraCabeceraTabla . $fila;
                $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                if ($excel->debug == 1) {
                    echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                    print_r($relaboral);
                    echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                }
                foreach ($relaboral as $i => $val) {
                    if (count($agrupadores) > 0) {
                        if ($excel->debug == 1) {
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                            print_r($gruposSeleccionadosActuales);
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                        }
                        $agregarAgrupador = 0;
                        $aux = array();
                        $dondeCambio = array();
                        foreach ($gruposSeleccionadosActuales as $key => $valor) {
                            if ($valor != $val[$key]) {
                                $agregarAgrupador = 1;
                                $aux[$key] = $val[$key];
                                $dondeCambio[] = $key;
                                $queCambio[$key] = $val[$key];
                            } else {
                                $aux[$key] = $val[$key];
                            }
                        }
                        $gruposSeleccionadosActuales = $aux;
                        if ($agregarAgrupador == 1) {
                            $agr = $excel->DefineTitleColsByGroups($generalConfigForAllColumns, $dondeCambio, $queCambio);
                            if ($excel->debug == 1) {
                                echo "<p>+++++++++++++++++++++++++++AGRUPADO POR++++++++++++++++++++++++++++++++++++++++<p></p>";
                                print_r($agr);
                                echo "<p>+++++++++++++++++++++++++++AGRUPADO POR++++++++++++++++++++++++++++++++++++++++<p></p>";
                            }
                            $excel->borderGroup($celdaInicial, $celdaFinalDiagonalTabla);
                            $fila++;
                            /*
                             * Si es que hay agrupadores, se inicia el conteo desde donde empieza el agrupador
                             */
                            $celdaInicial = $excel->primeraLetraCabeceraTabla . $fila;
                            $excel->Agrupador($agr, $fila);
                            $excel->RowTitle($colTitleSelecteds, $fila);
                        }
                        $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                        $rowData = $excel->DefineRows($j + 1, $relaboral[$j], $colSelecteds);
                        if ($excel->debug == 1) {
                            echo "<p>···································FILA·················································<p></p>";
                            print_r($rowData);
                            echo "<p>···································FILA·················································<p></p>";
                        }
                        $excel->Row($rowData, $alignSelecteds, $formatTypes, $fila);
                        $fila++;

                    } else {
                        $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                        $rowData = $excel->DefineRows($j + 1, $val, $colSelecteds);
                        if ($excel->debug == 1) {
                            echo "<p>···································FILA·················································<p></p>";
                            print_r($rowData);
                            echo "<p>···································FILA·················································<p></p>";
                        }
                        $excel->Row($rowData, $alignSelecteds, $formatTypes, $fila);
                        $fila++;
                    }
                    $j++;
                }
                $fila--;
                $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                $excel->borderGroup($celdaInicial, $celdaFinalDiagonalTabla);
            }
            $excel->ShowLeftFooter = true;
            //$excel->secondPage();
            if ($excel->debug == 0) {
                $excel->display("AppData/reporte_relaboral.xls", "I");
            }
            #endregion Proceso de generación del documento PDF
        }
    }

    /**
     * Función para la exportación en formato excel del listado de personal que estuvo activo o es activo en la empresa.
     * @param $n_rows
     * @param $gestion_consulta
     * @param $columns
     * @param $filtros
     * @param $groups
     * @param $sorteds
     */
    public function exportactivosexcelAction($n_rows, $gestion_consulta, $columns, $filtros, $groups, $sorteds)
    {
        $this->view->disable();
        $columns = base64_decode(str_pad(strtr($columns, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $filtros = base64_decode(str_pad(strtr($filtros, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $groups = base64_decode(str_pad(strtr($groups, '-_', '+/'), strlen($groups) % 4, '=', STR_PAD_RIGHT));
        if ($groups == 'null' || $groups == null || $groups === '""') $groups = "";
        $sorteds = base64_decode(str_pad(strtr($sorteds, '-_', '+/'), strlen($sorteds) % 4, '=', STR_PAD_RIGHT));
        $columns = json_decode($columns, true);
        $filtros = json_decode($filtros, true);
        $sub_keys = array_keys($columns);//echo $sub_keys[0];
        $n_col = count($columns);//echo$keys[1];
        $sorteds = json_decode($sorteds, true);
        $auth = $this->session->get('auth');
        $idPersona = $auth["persona_id"];
        /**
         * Especificando la configuración de las columnas
         */
        $generalConfigForAllColumns = array(
            'nro_row' => array('title' => 'Nro.', 'width' => 8, 'title-align' => 'C', 'align' => 'C', 'type' => 'int4'),
            'nombres' => array('title' => 'Nombres', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'ci' => array('title' => 'CI', 'width' => 12, 'align' => 'C', 'type' => 'varchar'),
            'expd' => array('title' => 'Exp.', 'width' => 8, 'align' => 'C', 'type' => 'bpchar'),
            'ubicacion' => array('title' => 'Ubicacion', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'condicion' => array('title' => 'Condicion', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'estado_descripcion' => array('title' => 'Estado', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'tiene_contrato_vigente_descripcion' => array('title' => 'Activo', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'fecha_caducidad' => array('title' => 'Fecha Cad', 'width' => 18, 'align' => 'C', 'type' => 'bpchar'),
            'gerencia_administrativa' => array('title' => 'Gerencia', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'departamento_administrativo' => array('title' => 'Departamento', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'area' => array('title' => 'Area', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'fin_partida' => array('title' => 'Fuente', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'proceso_codigo' => array('title' => 'Proceso', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'nivelsalarial' => array('title' => 'Nivel', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'cargo' => array('title' => 'Cargo', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'sueldo' => array('title' => 'Haber', 'width' => 10, 'align' => 'R', 'type' => 'numeric'),
            'fecha_ing' => array('title' => 'Fecha Ing', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_ini' => array('title' => 'Fecha Ini', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_incor' => array('title' => 'Fecha Inc', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_fin' => array('title' => 'Fecha Fin', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_baja' => array('title' => 'Fecha Baja', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'motivo_baja' => array('title' => 'Motivo Baja', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'observacion' => array('title' => 'Observacion', 'width' => 15, 'align' => 'L', 'type' => 'varchar')
        );
        $agruparPor = ($groups != "") ? explode(",", $groups) : array();
        $widthsSelecteds = $this->DefineWidths($generalConfigForAllColumns, $columns, $agruparPor);
        $ancho = 0;
        if (count($widthsSelecteds) > 0) {
            foreach ($widthsSelecteds as $w) {
                $ancho = $ancho + $w;
            }
            $excel = new exceloasis();
            $excel->tableWidth = $ancho;
            #region Proceso de generación del documento Excel
            $excel->debug = 0;
            $excel->title_rpt = utf8_decode('Reporte Relacion Laboral');
            $excel->header_title_empresa_rpt = utf8_decode('Empresa Estatal de Transporte por Cable "Mi Teleférico"');
            $alignSelecteds = $excel->DefineAligns($generalConfigForAllColumns, $columns, $agruparPor);
            $colSelecteds = $excel->DefineCols($generalConfigForAllColumns, $columns, $agruparPor);
            $colTitleSelecteds = $excel->DefineTitleCols($generalConfigForAllColumns, $columns, $agruparPor);
            $alignTitleSelecteds = $excel->DefineTitleAligns(count($colTitleSelecteds));
            $formatTypes = $excel->DefineTypeCols($generalConfigForAllColumns, $columns, $agruparPor);
            $gruposSeleccionadosActuales = $excel->DefineDefaultValuesForGroups($groups);
            $excel->generalConfigForAllColumns = $generalConfigForAllColumns;
            $excel->colTitleSelecteds = $colTitleSelecteds;
            $excel->widthsSelecteds = $widthsSelecteds;
            $excel->alignSelecteds = $alignSelecteds;
            $excel->alignTitleSelecteds = $alignTitleSelecteds;

            $cantCol = count($colTitleSelecteds);
            $excel->ultimaLetraCabeceraTabla = $excel->columnasExcel[$cantCol - 1];
            $excel->penultimaLetraCabeceraTabla = $excel->columnasExcel[$cantCol - 2];
            $excel->numFilaCabeceraTabla = 4;
            $excel->primeraLetraCabeceraTabla = "A";
            $excel->segundaLetraCabeceraTabla = "B";
            $excel->celdaInicial = $excel->primeraLetraCabeceraTabla . $excel->numFilaCabeceraTabla;
            $excel->celdaFinal = $excel->ultimaLetraCabeceraTabla . $excel->numFilaCabeceraTabla;
            if ($cantCol <= 9) {
                $excel->defineOrientation("V");
                $excel->defineSize("C");
            } elseif ($cantCol <= 13) {
                $excel->defineOrientation("H");
                $excel->defineSize("C");
            } else {
                $excel->defineOrientation("H");
                $excel->defineSize("O");
            }
            if ($excel->debug == 1) {
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::COLUMNAS::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($columns);
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::FILTROS::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($filtros);
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::GRUPOS::::::::::::::::::::::::::::::::::::::::::::<p>";
                echo "<p>" . $groups;
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::ORDEN::::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($sorteds);
                echo "<p>:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::<p>";
            }
            $where = '';
            $yaConsiderados = array();
            for ($k = 0; $k < count($filtros); $k++) {
                $cant = $this->obtieneCantidadVecesConsideracionPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);
                $arr_val = $this->obtieneValoresConsideradosPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);

                for ($j = 0; $j < $n_col; $j++) {
                    if ($sub_keys[$j] == $filtros[$k]['columna']) {
                        $col_fil = $columns[$sub_keys[$j]]['text'];
                    }
                }
                if ($filtros[$k]['tipo'] == 'datefilter') {
                    $filtros[$k]['valor'] = date("Y-m-d", strtotime($filtros[$k]['valor']));
                }
                $cond_fil = ' ' . $col_fil;
                if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {

                    if (strlen($where) > 0) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'NOT_EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'CONTAINS':
                                $where .= ' AND ';
                                break;
                            case 'EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                        }
                    }
                }
                if ($cant > 1) {
                    if ($excel->debug == 1) {
                        echo "<p>::::::::::::::::::::::::::::::::::::YA CONSIDERADOS:::::::::::::::::::::::::::::::::::::::::::::::<p>";
                        print_r($yaConsiderados);
                        echo "<p>::::::::::::::::::::::::::::::::::::YA CONSIDERADOS:::::::::::::::::::::::::::::::::::::::::::::::<p>";
                    }
                    if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $cond_fil .= utf8_encode(" que sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                                break;
                            case 'NOT_EMPTY':
                                $cond_fil .= utf8_encode(" que no sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                                break;
                            case 'CONTAINS':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                if ($filtros[$k]['columna'] == "nombres") {
                                    $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                                } else {
                                    $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                                }
                                break;
                            case 'EQUAL':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    if ($excel->debug == 1) {

                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                        echo $filtros[$k]['columna'] . "-->" . $col;
                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                    }
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " = '" . $col . "'";
                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        } else $where .= " OR ";
                                    }
                                }
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    //$fecha = date("Y-m-d", $col);
                                    $fecha = $col;
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                                $where .= $filtros[$k]['columna'] . " = '" . $fecha . "'";
                                                break;
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                if ($ini == 0) {
                                                    $where .= $filtros[$k]['columna'] . " BETWEEN ";
                                                } else {
                                                    $where .= " AND ";
                                                }
                                                $fecha = date("d-m-Y", strtotime($fecha));
                                                $where .= "'" . $fecha . "'";

                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        }
                                    }
                                }
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                                $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                                break;
                        }
                        $yaConsiderados[] = $filtros[$k]['columna'];
                    }
                } else {
                    switch ($filtros[$k]['condicion']) {
                        case 'EMPTY':
                            $cond_fil .= utf8_encode(" que sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                            break;
                        case 'NOT_EMPTY':
                            $cond_fil .= utf8_encode(" que no sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                            break;
                        case 'CONTAINS':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if ($filtros[$k]['columna'] == "nombres") {
                                $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                            } else {
                                $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                            }
                            break;
                        case 'EQUAL':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                    case 'int4':
                                    case 'numeric':
                                    case 'date':
                                        //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " = '" . $filtros[$k]['valor'] . "'";
                                        break;
                                    case 'varchar':
                                    case 'bpchar':
                                        //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " ILIKE '" . $filtros[$k]['valor'] . "'";
                                        break;
                                }
                            }
                            break;
                        case 'GREATER_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' >= "' . $filtros[$k]['valor'] . '"';
                            break;
                        case 'LESS_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                            break;
                    }
                }

            }
            $obj = new Frelaborales();
            if ($where != "") $where = " WHERE " . $where;
            $groups_aux = "";
            if ($groups != "") {
                /**
                 * Se verifica que no se considere la columna nombres debido a que contenido ya esta ordenado por las
                 * columnas p_apellido, s_apellido, c_apellido, p_anombre, s_nombre, t_nombre
                 */
                if (strrpos($groups, "nombres")) {
                    if (strrpos($groups, ",")) {
                        $arr = explode(",", $groups);
                        foreach ($arr as $val) {
                            if ($val != "nombres")
                                $groups_aux[] = $val;
                        }
                        $groups = implode(",", $groups_aux);
                    } else $groups = "";
                }
                if (is_array($sorteds) && count($sorteds) > 0) {
                    if ($groups != "") $groups .= ",";
                    $coma = "";
                    if ($excel->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                }
                if ($groups != "")
                    $groups = " ORDER BY " . $groups . ",p_apellido,s_apellido,c_apellido,p_nombre,s_nombre,t_nombre,id_da,fecha_ini";
                if ($excel->debug == 1) echo "<p>La consulta es: " . $groups . "<p>";
            } else {
                if (is_array($sorteds) && count($sorteds) > 0) {
                    $coma = "";
                    if ($excel->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                    $groups = " ORDER BY " . $groups;
                }

            }
            if ($excel->debug == 1) echo "<p>WHERE------------------------->" . $where . "<p>";
            if ($excel->debug == 1) echo "<p>GROUP BY------------------------->" . $groups . "<p>";
            //$resul = $obj->getAllInGestion($gestion_consulta, $where, $groups);
            $where = str_replace("'", "''", $where);
            $resul = $obj->getPaged($idPersona,0, $gestion_consulta, 0, $where, $groups, 0, 0);
            $relaboral = array();
            foreach ($resul as $v) {
                $relaboral[] = array(
                    'id_relaboral' => $v->id_relaboral,
                    'id_persona' => $v->id_persona,
                    'p_nombre' => $v->p_nombre,
                    's_nombre' => $v->s_nombre,
                    't_nombre' => $v->t_nombre,
                    'p_apellido' => $v->p_apellido,
                    's_apellido' => $v->s_apellido,
                    'c_apellido' => $v->c_apellido,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'fecha_caducidad' => $v->fecha_caducidad,
                    'num_complemento' => '',
                    'fecha_nac' => $v->fecha_nac != "" ? date("d-m-Y", strtotime($v->fecha_nac)) : "",
                    'fecha_cumple' => $v->fecha_cumple != "" ? date("d-m-Y", strtotime($v->fecha_cumple)) : "",
                    'edad' => $v->edad,
                    'lugar_nac' => $v->lugar_nac,
                    'genero' => $v->genero,
                    'e_civil' => $v->e_civil,
                    'item' => $v->item,
                    'carrera_adm' => $v->carrera_adm,
                    'num_contrato' => $v->num_contrato,
                    'contrato_numerador_estado' => $v->contrato_numerador_estado,
                    'id_solelabcontrato' => $v->id_solelabcontrato,
                    'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                    'solelabcontrato_numero' => $v->solelabcontrato_numero,
                    'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                    'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                    'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                    'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                    'fecha_ing' => $v->fecha_ing != "" ? date("d-m-Y", strtotime($v->fecha_ing)) : "",
                    'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                    'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                    'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                    'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                    'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                    'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                    'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                    'motivo_baja' => $v->motivo_baja,
                    'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                    'descripcion_baja' => $v->descripcion_baja,
                    'descripcion_anu' => $v->descripcion_anu,
                    'id_cargo' => $v->id_cargo,
                    'cargo_codigo' => $v->cargo_codigo,
                    'cargo' => $v->cargo,
                    'id_nivelessalarial' => $v->id_nivelessalarial,
                    'nivelsalarial' => $v->nivelsalarial,
                    'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                    'numero_escala' => $v->numero_escala,
                    'gestion_escala' => $v->gestion_escala,
                    //'sueldo' => $v->sueldo,
                    'sueldo' => str_replace(".00", "", $v->sueldo),
                    'id_procesocontratacion' => $v->id_procesocontratacion,
                    'proceso_codigo' => $v->proceso_codigo,
                    'id_convocatoria' => $v->id_convocatoria,
                    'convocatoria_codigo' => $v->convocatoria_codigo,
                    'convocatoria_tipo' => $v->convocatoria_tipo,
                    'id_fin_partida' => $v->id_fin_partida,
                    'fin_partida' => $v->fin_partida,
                    'id_condicion' => $v->id_condicion,
                    'condicion' => $v->condicion,
                    'categoria_relaboral' => $v->categoria_relaboral,
                    'id_da' => $v->id_da,
                    'direccion_administrativa' => $v->direccion_administrativa,
                    'organigrama_regional_id' => $v->organigrama_regional_id,
                    'organigrama_regional' => $v->organigrama_regional,
                    'id_regional' => $v->id_regional,
                    'regional' => $v->regional,
                    'regional_codigo' => $v->regional_codigo,
                    'id_departamento' => $v->id_departamento,
                    'departamento' => $v->departamento,
                    'id_provincia' => $v->id_provincia,
                    'provincia' => $v->provincia,
                    'id_localidad' => $v->id_localidad,
                    'localidad' => $v->localidad,
                    'residencia' => $v->residencia,
                    'unidad_ejecutora' => $v->unidad_ejecutora,
                    'cod_ue' => $v->cod_ue,
                    'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                    'gerencia_administrativa' => $v->gerencia_administrativa,
                    'id_departamento_administrativo' => $v->id_departamento_administrativo,
                    'departamento_administrativo' => $v->departamento_administrativo,
                    'id_organigrama' => $v->id_organigrama,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'organigrama_sigla' => $v->organigrama_sigla,
                    'organigrama_orden' => $v->organigrama_orden,
                    'id_area' => $v->id_area,
                    'area' => $v->area,
                    'id_ubicacion' => $v->id_ubicacion,
                    'ubicacion' => $v->ubicacion,
                    'unidades_superiores' => $v->unidades_superiores,
                    'unidades_dependientes' => $v->unidades_dependientes,
                    'partida' => $v->partida,
                    'fuente_codigo' => $v->fuente_codigo,
                    'fuente' => $v->fuente,
                    'organismo_codigo' => $v->organismo_codigo,
                    'organismo' => $v->organismo,
                    'observacion' => ($v->observacion != null) ? $v->observacion : "",
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'estado_abreviacion' => $v->estado_abreviacion,
                    'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                    'tiene_contrato_vigente_descripcion' => $v->tiene_contrato_vigente == 1 ? "SI" : ($v->tiene_contrato_vigente == 0 ? "NO" : ""),
                    'id_eventual' => $v->id_eventual,
                    'id_consultor' => $v->id_consultor,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                    'persona_user_reg_id' => $v->persona_user_reg_id,
                    'persona_fecha_reg' => $v->persona_fecha_reg,
                    'persona_user_mod_id' => $v->persona_user_mod_id,
                    'persona_fecha_mod' => $v->persona_fecha_mod,
                    'relaboral_previo_id' => $v->relaboral_previo_id
                );
            }
            #region Espacio para la definición de valores para la cabecera de la tabla
            $excel->FechaHoraReporte = date("d-m-Y H:i:s");
            $j = 0;
            $agrupadores = array();
            if ($groups != "")
                $agrupadores = explode(",", $groups);

            $dondeCambio = array();
            $queCambio = array();
            $excel->header();
            $fila = $excel->numFilaCabeceraTabla;
            if (count($relaboral) > 0) {
                $excel->RowTitle($colTitleSelecteds, $fila);
                $celdaInicial = $excel->primeraLetraCabeceraTabla . $fila;
                $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                if ($excel->debug == 1) {
                    echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                    print_r($relaboral);
                    echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                }
                foreach ($relaboral as $i => $val) {
                    if (count($agrupadores) > 0) {
                        if ($excel->debug == 1) {
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                            print_r($gruposSeleccionadosActuales);
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                        }
                        $agregarAgrupador = 0;
                        $aux = array();
                        $dondeCambio = array();
                        foreach ($gruposSeleccionadosActuales as $key => $valor) {
                            if ($valor != $val[$key]) {
                                $agregarAgrupador = 1;
                                $aux[$key] = $val[$key];
                                $dondeCambio[] = $key;
                                $queCambio[$key] = $val[$key];
                            } else {
                                $aux[$key] = $val[$key];
                            }
                        }
                        $gruposSeleccionadosActuales = $aux;
                        if ($agregarAgrupador == 1) {
                            $agr = $excel->DefineTitleColsByGroups($generalConfigForAllColumns, $dondeCambio, $queCambio);
                            if ($excel->debug == 1) {
                                echo "<p>+++++++++++++++++++++++++++AGRUPADO POR++++++++++++++++++++++++++++++++++++++++<p></p>";
                                print_r($agr);
                                echo "<p>+++++++++++++++++++++++++++AGRUPADO POR++++++++++++++++++++++++++++++++++++++++<p></p>";
                            }
                            $excel->borderGroup($celdaInicial, $celdaFinalDiagonalTabla);
                            $fila++;
                            /*
                             * Si es que hay agrupadores, se inicia el conteo desde donde empieza el agrupador
                             */
                            $celdaInicial = $excel->primeraLetraCabeceraTabla . $fila;
                            $excel->Agrupador($agr, $fila);
                            $excel->RowTitle($colTitleSelecteds, $fila);
                        }
                        $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                        $rowData = $excel->DefineRows($j + 1, $relaboral[$j], $colSelecteds);
                        if ($excel->debug == 1) {
                            echo "<p>···································FILA·················································<p></p>";
                            print_r($rowData);
                            echo "<p>···································FILA·················································<p></p>";
                        }
                        $excel->Row($rowData, $alignSelecteds, $formatTypes, $fila);
                        $fila++;

                    } else {
                        $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                        $rowData = $excel->DefineRows($j + 1, $val, $colSelecteds);
                        if ($excel->debug == 1) {
                            echo "<p>···································FILA·················································<p></p>";
                            print_r($rowData);
                            echo "<p>···································FILA·················································<p></p>";
                        }
                        $excel->Row($rowData, $alignSelecteds, $formatTypes, $fila);
                        $fila++;
                    }
                    $j++;
                }
                $fila--;
                $excel->setWidthForColumns();
                $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                $excel->borderGroup($celdaInicial, $celdaFinalDiagonalTabla);
            }
            $excel->ShowLeftFooter = true;
            //$excel->secondPage();
            if ($excel->debug == 0) {
                $excel->display("AppData/reporte_relaboral.xls", "I");
            }
            #endregion Proceso de generación del documento PDF
        }
    }

    /**
     * Función para la exportación del reporte en formato Excel.
     * @param $n_rows Cantidad de lineas
     * @param $columns Array con las columnas mostradas en el reporte
     * @param $filtros Array con los filtros aplicados sobre las columnas.
     * @param $groups String con la cadena representativa de las columnas agrupadas. La separación es por comas.
     * @param $sorteds  Columnas ordenadas .
     */
    public function exportforvacexcelAction($n_rows, $gestion_consulta, $columns, $filtros, $groups, $sorteds)
    {
        $this->view->disable();
        $columns = base64_decode(str_pad(strtr($columns, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $filtros = base64_decode(str_pad(strtr($filtros, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $groups = base64_decode(str_pad(strtr($groups, '-_', '+/'), strlen($groups) % 4, '=', STR_PAD_RIGHT));
        if ($groups == 'null' || $groups == null || $groups === '""') $groups = "";
        $sorteds = base64_decode(str_pad(strtr($sorteds, '-_', '+/'), strlen($sorteds) % 4, '=', STR_PAD_RIGHT));
        $columns = json_decode($columns, true);
        $filtros = json_decode($filtros, true);
        $sub_keys = array_keys($columns);//echo $sub_keys[0];
        $n_col = count($columns);//echo$keys[1];
        $sorteds = json_decode($sorteds, true);
        $auth = $this->session->get('auth');
        $idPersona = $auth["persona_id"];
        /**
         * Especificando la configuración de las columnas
         */
        $generalConfigForAllColumns = array(
            'nro_row' => array('title' => 'Nro.', 'width' => 8, 'title-align' => 'C', 'align' => 'C', 'type' => 'int4'),
            'ubicacion' => array('title' => 'Ubicacion', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'condicion' => array('title' => 'Condicion', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'estado_descripcion' => array('title' => 'Estado', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'tiene_contrato_vigente_descripcion' => array('title' => 'Activo', 'width' => 10, 'align' => 'C', 'type' => 'varchar'),
            'nombres' => array('title' => 'Nombres', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'ci' => array('title' => 'CI', 'width' => 12, 'align' => 'C', 'type' => 'varchar'),
            'expd' => array('title' => 'Exp.', 'width' => 8, 'align' => 'C', 'type' => 'bpchar'),
            'genero' => array('title' => 'Genero', 'width' => 8, 'align' => 'C', 'type' => 'bpchar'),
            'edad' => array('title' => 'Edad', 'width' => 8, 'align' => 'C', 'type' => 'numeric'),
            'fecha_nac' => array('title' => 'F. Nac', 'width' => 8, 'align' => 'C', 'type' => 'date'),
            'fecha_cumple' => array('title' => 'F. Cumple', 'width' => 8, 'align' => 'C', 'type' => 'date'),
            'grupo_sanguineo' => array('title' => 'Tipo Sangre', 'width' => 8, 'align' => 'C', 'type' => 'varchar'),
            'fecha_caducidad' => array('title' => 'Fecha Cad', 'width' => 18, 'align' => 'C', 'type' => 'bpchar'),
            'gerencia_administrativa' => array('title' => 'Gerencia', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'departamento_administrativo' => array('title' => 'Departamento', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'area' => array('title' => 'Area', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'fin_partida' => array('title' => 'Fuente', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'proceso_codigo' => array('title' => 'Proceso', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'nivelsalarial' => array('title' => 'Nivel', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'cargo' => array('title' => 'Cargo', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'sueldo' => array('title' => 'Haber', 'width' => 10, 'align' => 'R', 'type' => 'numeric'),
            'fecha_ing' => array('title' => 'Fecha Ing', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_ini' => array('title' => 'Fecha Ini', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_incor' => array('title' => 'Fecha Inc', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_fin' => array('title' => 'Fecha Fin', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_baja' => array('title' => 'Fecha Baja', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'motivo_baja' => array('title' => 'Motivo Baja', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'interno_inst' => array('title' => 'Nro. Interno', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'celular_per' => array('title' => 'Celular Per', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'celular_inst' => array('title' => 'Celular Inst', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'e_mail_per' => array('title' => 'E-mail Per', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'e_mail_inst' => array('title' => 'E-mail Inst', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'cas_fecha_emi' => array('title' => 'Fecha Emi CAS', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'cas_fecha_pres' => array('title' => 'Fecha Pres CAS', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'cas_fecha_fin_cal' => array('title' => 'Nro. CAS.', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),
            'cas_numero' => array('title' => 'Años Ant', 'width' => 18, 'align' => 'C', 'type' => 'int4'),
            'cas_codigo_verificacion' => array('title' => 'Cod. Verif. CAS', 'width' => 18, 'align' => 'C', 'type' => 'int4'),
            'cas_anios' => array('title' => 'Años CAS', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'cas_meses' => array('title' => 'Meses CAS', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'cas_dias' => array('title' => 'Dias CAS', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'mt_anios' => array('title' => 'Años MT->CAS', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'mt_meses' => array('title' => 'Meses MT->CAS', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'mt_dias' => array('title' => 'Dias MT->CAS', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'tot_anios' => array('title' => 'Total Años', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'tot_meses' => array('title' => 'Total Meses', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'tot_dias' => array('title' => 'Total Dias', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'mt_prox_fecha' => array('title' => 'Fecha (Prox Gestion)', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'mt_prox_anios' => array('title' => 'Años (Prox Gestion)', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'mt_prox_meses' => array('title' => 'Meses (Prox Gestion)', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'mt_prox_dias' => array('title' => 'Dias (Prox Gestion)', 'width' => 18, 'align' => 'C', 'type' => 'numeric'),
            'observacion' => array('title' => 'Observacion', 'width' => 15, 'align' => 'L', 'type' => 'varchar')
        );
        $agruparPor = ($groups != "") ? explode(",", $groups) : array();
        $widthsSelecteds = $this->DefineWidths($generalConfigForAllColumns, $columns, $agruparPor);
        $ancho = 0;
        if (count($widthsSelecteds) > 0) {
            foreach ($widthsSelecteds as $w) {
                $ancho = $ancho + $w;
            }
            $excel = new exceloasis();
            $excel->tableWidth = $ancho;
            #region Proceso de generación del documento Excel
            $excel->debug = 0;
            $excel->title_rpt = utf8_decode('Reporte Relacion Laboral (Antiguedad para Vacacion)');
            $excel->header_title_empresa_rpt = utf8_decode('Empresa Estatal de Transporte por Cable "Mi Teleférico"');
            $alignSelecteds = $excel->DefineAligns($generalConfigForAllColumns, $columns, $agruparPor);
            $colSelecteds = $excel->DefineCols($generalConfigForAllColumns, $columns, $agruparPor);
            $colTitleSelecteds = $excel->DefineTitleCols($generalConfigForAllColumns, $columns, $agruparPor);
            $alignTitleSelecteds = $excel->DefineTitleAligns(count($colTitleSelecteds));
            $formatTypes = $excel->DefineTypeCols($generalConfigForAllColumns, $columns, $agruparPor);
            $gruposSeleccionadosActuales = $excel->DefineDefaultValuesForGroups($groups);
            $excel->generalConfigForAllColumns = $generalConfigForAllColumns;
            $excel->colTitleSelecteds = $colTitleSelecteds;
            $excel->widthsSelecteds = $widthsSelecteds;
            $excel->alignSelecteds = $alignSelecteds;
            $excel->alignTitleSelecteds = $alignTitleSelecteds;

            $cantCol = count($colTitleSelecteds);
            $excel->ultimaLetraCabeceraTabla = $excel->columnasExcel[$cantCol - 1];
            $excel->penultimaLetraCabeceraTabla = $excel->columnasExcel[$cantCol - 2];
            $excel->numFilaCabeceraTabla = 4;
            $excel->primeraLetraCabeceraTabla = "A";
            $excel->segundaLetraCabeceraTabla = "B";
            $excel->celdaInicial = $excel->primeraLetraCabeceraTabla . $excel->numFilaCabeceraTabla;
            $excel->celdaFinal = $excel->ultimaLetraCabeceraTabla . $excel->numFilaCabeceraTabla;
            if ($cantCol <= 9) {
                $excel->defineOrientation("V");
                $excel->defineSize("C");
            } elseif ($cantCol <= 13) {
                $excel->defineOrientation("H");
                $excel->defineSize("C");
            } else {
                $excel->defineOrientation("H");
                $excel->defineSize("O");
            }
            if ($excel->debug == 1) {
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::COLUMNAS::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($columns);
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::FILTROS::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($filtros);
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::GRUPOS::::::::::::::::::::::::::::::::::::::::::::<p>";
                echo "<p>" . $groups;
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::ORDEN::::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($sorteds);
                echo "<p>:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::<p>";
            }
            $where = '';
            $yaConsiderados = array();
            for ($k = 0; $k < count($filtros); $k++) {
                $cant = $this->obtieneCantidadVecesConsideracionPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);
                $arr_val = $this->obtieneValoresConsideradosPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);

                for ($j = 0; $j < $n_col; $j++) {
                    if ($sub_keys[$j] == $filtros[$k]['columna']) {
                        $col_fil = $columns[$sub_keys[$j]]['text'];
                    }
                }
                if ($filtros[$k]['tipo'] == 'datefilter') {
                    $filtros[$k]['valor'] = date("Y-m-d", strtotime($filtros[$k]['valor']));
                }
                $cond_fil = ' ' . $col_fil;
                if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {

                    if (strlen($where) > 0) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'NOT_EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'CONTAINS':
                                $where .= ' AND ';
                                break;
                            case 'EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                        }
                    }
                }
                if ($cant > 1) {
                    if ($excel->debug == 1) {
                        echo "<p>::::::::::::::::::::::::::::::::::::YA CONSIDERADOS:::::::::::::::::::::::::::::::::::::::::::::::<p>";
                        print_r($yaConsiderados);
                        echo "<p>::::::::::::::::::::::::::::::::::::YA CONSIDERADOS:::::::::::::::::::::::::::::::::::::::::::::::<p>";
                    }
                    if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $cond_fil .= utf8_encode(" que sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                                break;
                            case 'NOT_EMPTY':
                                $cond_fil .= utf8_encode(" que no sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                                break;
                            case 'CONTAINS':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                if ($filtros[$k]['columna'] == "nombres") {
                                    $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                                } else {
                                    $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                                }
                                break;
                            case 'EQUAL':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    if ($excel->debug == 1) {

                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                        echo $filtros[$k]['columna'] . "-->" . $col;
                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                    }
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " = '" . $col . "'";
                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        } else $where .= " OR ";
                                    }
                                }
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    //$fecha = date("Y-m-d", $col);
                                    $fecha = $col;
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                                $where .= $filtros[$k]['columna'] . " = '" . $fecha . "'";
                                                break;
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                $fecha = date("d-m-Y", strtotime($fecha));
                                                if ($ini == 0) {
                                                    $where .= $filtros[$k]['columna'] . " BETWEEN ";
                                                } else {
                                                    $where .= " AND ";
                                                }
                                                $where .= "'" . $fecha . "'";

                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        }
                                    }
                                }
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                                $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                                break;
                        }
                        $yaConsiderados[] = $filtros[$k]['columna'];
                    }
                } else {
                    switch ($filtros[$k]['condicion']) {
                        case 'EMPTY':
                            $cond_fil .= utf8_encode(" que sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                            break;
                        case 'NOT_EMPTY':
                            $cond_fil .= utf8_encode(" que no sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                            break;
                        case 'CONTAINS':
                            /*$cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if ($filtros[$k]['columna'] == "nombres") {
                                $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                            } else {
                                $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                            }*/
                            $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                            break;
                        case 'EQUAL':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                    case 'int4':
                                    case 'numeric':
                                    case 'date':
                                        //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " = '" . $filtros[$k]['valor'] . "'";
                                        break;
                                    case 'varchar':
                                    case 'bpchar':
                                        //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " ILIKE '" . $filtros[$k]['valor'] . "'";
                                        break;
                                }
                            }
                            break;
                        case 'GREATER_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' >= "' . $filtros[$k]['valor'] . '"';
                            break;
                        case 'LESS_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                            break;
                    }
                }

            }
            $obj = new Frelaborales();
            if ($where != "") $where = " WHERE " . $where;
            $groups_aux = "";
            if ($groups != "") {
                /**
                 * Se verifica que no se considere la columna nombres debido a que contenido ya esta ordenado por las
                 * columnas p_apellido, s_apellido, c_apellido, p_anombre, s_nombre, t_nombre
                 */
                if (strrpos($groups, "nombres")) {
                    if (strrpos($groups, ",")) {
                        $arr = explode(",", $groups);
                        foreach ($arr as $val) {
                            if ($val != "nombres")
                                $groups_aux[] = $val;
                        }
                        $groups = implode(",", $groups_aux);
                    } else $groups = "";
                }
                if (is_array($sorteds) && count($sorteds) > 0) {
                    if ($groups != "") $groups .= ",";
                    $coma = "";
                    if ($excel->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                }
                if ($groups != "")
                    $groups = " ORDER BY " . $groups . ",p_apellido,s_apellido,c_apellido,p_nombre,s_nombre,t_nombre,id_da,fecha_ini";
                if ($excel->debug == 1) echo "<p>La consulta es: " . $groups . "<p>";
            } else {
                if (is_array($sorteds) && count($sorteds) > 0) {
                    $coma = "";
                    if ($excel->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                    $groups = " ORDER BY " . $groups;
                }

            }
            if ($excel->debug == 1) echo "<p>WHERE------------------------->" . $where . "<p>";
            if ($excel->debug == 1) echo "<p>GROUP BY------------------------->" . $groups . "<p>";
            //$resul = $obj->getAllWithPersonsByGestion($gestion_consulta, $where, $groups);
            $where = str_replace("'", "''", $where);
            $resul = $obj->getPaged($idPersona,2, $gestion_consulta, 0, $where, $groups, 0, 0);
            $relaboral = array();
            foreach ($resul as $v) {
                $relaboral[] = array(
                    'id_relaboral' => $v->id_relaboral,
                    'id_persona' => $v->id_persona,
                    'p_nombre' => $v->p_nombre,
                    's_nombre' => $v->s_nombre,
                    't_nombre' => $v->t_nombre,
                    'p_apellido' => $v->p_apellido,
                    's_apellido' => $v->s_apellido,
                    'c_apellido' => $v->c_apellido,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'fecha_caducidad' => $v->fecha_caducidad,
                    'num_complemento' => '',
                    'fecha_nac' => $v->fecha_nac != "" ? date("d-m-Y", strtotime($v->fecha_nac)) : "",
                    'fecha_cumple' => $v->fecha_cumple != "" ? date("d-m-Y", strtotime($v->fecha_cumple)) : "",
                    'edad' => $v->edad,
                    'lugar_nac' => $v->lugar_nac,
                    'genero' => $v->genero,
                    'e_civil' => $v->e_civil,
                    'grupo_sanguineo' => $v->grupo_sanguineo,
                    'item' => $v->item,
                    'carrera_adm' => $v->carrera_adm,
                    'num_contrato' => $v->num_contrato,
                    'contrato_numerador_estado' => $v->contrato_numerador_estado,
                    'id_solelabcontrato' => $v->id_solelabcontrato,
                    'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                    'solelabcontrato_numero' => $v->solelabcontrato_numero,
                    'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                    'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                    'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                    'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                    'fecha_ing' => $v->fecha_ing != "" ? date("d-m-Y", strtotime($v->fecha_ing)) : "",
                    'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                    'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                    'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                    'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                    'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                    'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                    'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                    'motivo_baja' => $v->motivo_baja,
                    'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                    'descripcion_baja' => $v->descripcion_baja,
                    'descripcion_anu' => $v->descripcion_anu,
                    'id_cargo' => $v->id_cargo,
                    'cargo_codigo' => $v->cargo_codigo,
                    'cargo' => $v->cargo,
                    'id_nivelessalarial' => $v->id_nivelessalarial,
                    'nivelsalarial' => $v->nivelsalarial,
                    'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                    'numero_escala' => $v->numero_escala,
                    'gestion_escala' => $v->gestion_escala,
                    //'sueldo' => $v->sueldo,
                    'sueldo' => str_replace(".00", "", $v->sueldo),
                    'id_procesocontratacion' => $v->id_procesocontratacion,
                    'proceso_codigo' => $v->proceso_codigo,
                    'id_convocatoria' => $v->id_convocatoria,
                    'convocatoria_codigo' => $v->convocatoria_codigo,
                    'convocatoria_tipo' => $v->convocatoria_tipo,
                    'id_fin_partida' => $v->id_fin_partida,
                    'fin_partida' => $v->fin_partida,
                    'id_condicion' => $v->id_condicion,
                    'condicion' => $v->condicion,
                    'categoria_relaboral' => $v->categoria_relaboral,
                    'id_da' => $v->id_da,
                    'direccion_administrativa' => $v->direccion_administrativa,
                    'organigrama_regional_id' => $v->organigrama_regional_id,
                    'organigrama_regional' => $v->organigrama_regional,
                    'id_regional' => $v->id_regional,
                    'regional' => $v->regional,
                    'regional_codigo' => $v->regional_codigo,
                    'id_departamento' => $v->id_departamento,
                    'departamento' => $v->departamento,
                    'id_provincia' => $v->id_provincia,
                    'provincia' => $v->provincia,
                    'id_localidad' => $v->id_localidad,
                    'localidad' => $v->localidad,
                    'residencia' => $v->residencia,
                    'unidad_ejecutora' => $v->unidad_ejecutora,
                    'cod_ue' => $v->cod_ue,
                    'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                    'gerencia_administrativa' => $v->gerencia_administrativa,
                    'id_departamento_administrativo' => $v->id_departamento_administrativo,
                    'departamento_administrativo' => $v->departamento_administrativo,
                    'id_organigrama' => $v->id_organigrama,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'organigrama_sigla' => $v->organigrama_sigla,
                    'organigrama_orden' => $v->organigrama_orden,
                    'id_area' => $v->id_area,
                    'area' => $v->area,
                    'id_ubicacion' => $v->id_ubicacion,
                    'ubicacion' => $v->ubicacion,
                    'unidades_superiores' => $v->unidades_superiores,
                    'unidades_dependientes' => $v->unidades_dependientes,
                    'partida' => $v->partida,
                    'fuente_codigo' => $v->fuente_codigo,
                    'fuente' => $v->fuente,
                    'organismo_codigo' => $v->organismo_codigo,
                    'organismo' => $v->organismo,
                    'observacion' => ($v->observacion != null) ? $v->observacion : "",
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'estado_abreviacion' => $v->estado_abreviacion,
                    'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                    'tiene_contrato_vigente_descripcion' => $v->tiene_contrato_vigente == 1 ? "SI" : ($v->tiene_contrato_vigente == 0 ? "NO" : ""),
                    'id_eventual' => $v->id_eventual,
                    'id_consultor' => $v->id_consultor,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                    'persona_user_reg_id' => $v->persona_user_reg_id,
                    'persona_fecha_reg' => $v->persona_fecha_reg,
                    'persona_user_mod_id' => $v->persona_user_mod_id,
                    'persona_fecha_mod' => $v->persona_fecha_mod,
                    'relaboral_previo_id' => $v->relaboral_previo_id,
                    'id_presentaciondoc' => $v->id_presentaciondoc,
                    'interno_inst' => $v->interno_inst,
                    'celular_per' => $v->celular_per,
                    'celular_inst' => $v->celular_inst,
                    'e_mail_per' => $v->e_mail_per,
                    'e_mail_inst' => $v->e_mail_inst,
                    'cas_fecha_emi' => $v->cas_fecha_emi,
                    'cas_fecha_pres' => $v->cas_fecha_pres,
                    'cas_fecha_fin_cal' => $v->cas_fecha_fin_cal,
                    'cas_numero' => $v->cas_numero,
                    'cas_codigo_verificacion' => $v->cas_codigo_verificacion,
                    'cas_anios' => $v->cas_anios,
                    'cas_meses' => $v->cas_meses,
                    'cas_dias' => $v->cas_dias,
                    'mt_anios' => $v->mt_anios,
                    'mt_meses' => $v->mt_meses,
                    'mt_dias' => $v->mt_dias,
                    'tot_anios' => $v->tot_anios,
                    'tot_meses' => $v->tot_meses,
                    'tot_dias' => $v->tot_dias,
                    'mt_prox_fecha' => $v->mt_prox_fecha != "" ? date("d-m-Y", strtotime($v->mt_prox_fecha)) : "",
                    'mt_prox_anios' => $v->mt_prox_anios,
                    'mt_prox_meses' => $v->mt_prox_meses,
                    'mt_prox_dias' => $v->mt_prox_dias,
                );
            }
            #region Espacio para la definición de valores para la cabecera de la tabla
            $excel->FechaHoraReporte = date("d-m-Y H:i:s");
            $j = 0;
            $agrupadores = array();
            if ($groups != "")
                $agrupadores = explode(",", $groups);

            $dondeCambio = array();
            $queCambio = array();
            $excel->header();
            $fila = $excel->numFilaCabeceraTabla;
            if (count($relaboral) > 0) {
                $excel->RowTitle($colTitleSelecteds, $fila);
                $celdaInicial = $excel->primeraLetraCabeceraTabla . $fila;
                $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                if ($excel->debug == 1) {
                    echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                    print_r($relaboral);
                    echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                }
                foreach ($relaboral as $i => $val) {
                    if (count($agrupadores) > 0) {
                        if ($excel->debug == 1) {
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                            print_r($gruposSeleccionadosActuales);
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                        }
                        $agregarAgrupador = 0;
                        $aux = array();
                        $dondeCambio = array();
                        foreach ($gruposSeleccionadosActuales as $key => $valor) {
                            if ($valor != $val[$key]) {
                                $agregarAgrupador = 1;
                                $aux[$key] = $val[$key];
                                $dondeCambio[] = $key;
                                $queCambio[$key] = $val[$key];
                            } else {
                                $aux[$key] = $val[$key];
                            }
                        }
                        $gruposSeleccionadosActuales = $aux;
                        if ($agregarAgrupador == 1) {
                            $agr = $excel->DefineTitleColsByGroups($generalConfigForAllColumns, $dondeCambio, $queCambio);
                            if ($excel->debug == 1) {
                                echo "<p>+++++++++++++++++++++++++++AGRUPADO POR++++++++++++++++++++++++++++++++++++++++<p></p>";
                                print_r($agr);
                                echo "<p>+++++++++++++++++++++++++++AGRUPADO POR++++++++++++++++++++++++++++++++++++++++<p></p>";
                            }
                            $excel->borderGroup($celdaInicial, $celdaFinalDiagonalTabla);
                            $fila++;
                            /*
                             * Si es que hay agrupadores, se inicia el conteo desde donde empieza el agrupador
                             */
                            $celdaInicial = $excel->primeraLetraCabeceraTabla . $fila;
                            $excel->Agrupador($agr, $fila);
                            $excel->RowTitle($colTitleSelecteds, $fila);
                        }
                        $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                        $rowData = $excel->DefineRows($j + 1, $relaboral[$j], $colSelecteds);
                        if ($excel->debug == 1) {
                            echo "<p>···································FILA·················································<p></p>";
                            print_r($rowData);
                            echo "<p>···································FILA·················································<p></p>";
                        }
                        $excel->Row($rowData, $alignSelecteds, $formatTypes, $fila);
                        $fila++;

                    } else {
                        $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                        $rowData = $excel->DefineRows($j + 1, $val, $colSelecteds);
                        if ($excel->debug == 1) {
                            echo "<p>···································FILA·················································<p></p>";
                            print_r($rowData);
                            echo "<p>···································FILA·················································<p></p>";
                        }
                        $excel->Row($rowData, $alignSelecteds, $formatTypes, $fila);
                        $fila++;
                    }
                    $j++;
                }
                $fila--;
                $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                $excel->borderGroup($celdaInicial, $celdaFinalDiagonalTabla);
            }
            $excel->ShowLeftFooter = true;
            //$excel->secondPage();
            if ($excel->debug == 0) {
                $excel->display("AppData/reporte_relaboral.xls", "I");
            }
            #endregion Proceso de generación del documento PDF
        }
    }


    /**
     * Función para la generación del array con los anchos de columna definido, en consideración a las columnas mostradas.
     * @param $generalWiths Array de los anchos y alineaciones de columnas disponibles.
     * @param $columns Array de las columnas con las propiedades de oculto (hidden:1) y visible (hidden:null).
     * @return array Array con el listado de anchos por columna a desplegarse.
     */
    function DefineWidths($widthAlignAll, $columns, $exclude = array())
    {
        $arrRes = Array();
        $arrRes[] = 8;
        foreach ($columns as $key => $val) {
            if (isset($widthAlignAll[$key])) {
                if (!isset($val['hidden']) || $val['hidden'] != true) {
                    if (!in_array($key, $exclude) || count($exclude) == 0)
                        $arrRes[] = $widthAlignAll[$key]['width'];
                }
            }
        }
        return $arrRes;
    }

    /**
     * Función para obtener la cantidad de veces que se considera una misma columna en el filtrado.
     * @param $columna
     * @param $array
     * @return int
     */
    function obtieneCantidadVecesConsideracionPorColumnaEnFiltros($columna, $array)
    {
        $cont = 0;
        if (count($array) >= 1) {
            foreach ($array as $key => $val) {
                if (in_array($columna, $val)) {
                    $cont++;
                }
            }
        }
        return $cont;
    }

    /**
     * Función para la obtención de los valores considerados en el filtro enviado.
     * @param $columna Nombre de la columna
     * @param $array Array con los registro de busquedas.
     * @return array Array con los valores considerados en el filtrado enviado.
     */
    function obtieneValoresConsideradosPorColumnaEnFiltros($columna, $array)
    {
        $arr_col = array();
        $cont = 0;
        if (count($array) >= 1) {
            foreach ($array as $key => $val) {
                if (in_array($columna, $val)) {
                    $arr_col[] = $val["valor"];
                }
            }
        }
        return $arr_col;
    }

    /**
     * Función para la obtención del listado de tipos de memorándum.
     */
    function listtiposmemorandumsmovilidadAction()
    {
        $this->view->disable();
        $resul = Tiposmemorandums::find(array('estado=:estado1: and movilidad=:movilidad1:', 'bind' => array('estado1' => 1, 'movilidad1' => 1), 'order' => 'id ASC'));
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $tipomemorandum[] = array(
                    'id' => $v->id,
                    'id_agrupado' => $v->id . "-" . $v->fecha_fin . "-" . $v->hora_fin . "-" . $v->cargo . "-" . $v->organigrama . "-" . $v->ubicacion . "-" . $v->motivo . "-" . $v->pais . "-" . $v->ciudad . "-" . $v->lugar,
                    'tipo_memorandum' => $v->tipo_memorandum,
                    'cabecera' => $v->cabecera,
                    'abreviacion' => $v->abreviacion,
                    'fecha_fin' => $v->fecha_fin,
                    'hora_fin' => $v->hora_fin,
                    'cargo' => $v->cargo,
                    'organigrama' => $v->organigrama,
                    'ubicacion' => $v->ubicacion,
                    'observacion' => $v->observacion,
                    'estado' => $v->estado,
                    'agrupador' => $v->agrupador
                );
            }
        }
        echo json_encode($tipomemorandum);
    }

    /**
     * Función para la obtención del listado de gestiones disponibles para la generación de memorándums.
     * Se muestra la gestión actual menos la gestión pasada.
     */
    function listgestionesmemorandumsAction()
    {
        $this->view->disable();
        $gestionActual = date("Y");
        $gestionPasada = $gestionActual - 1;
        $gestiones = array();
        for ($ges = $gestionActual; $ges >= $gestionPasada; $ges--) {
            $gestiones[] = array('gestion' => $ges);
        }
        echo json_encode($gestiones);
    }

    /**
     * Función para la obtención del listado de gerencias administrativas.
     */
    function listgerenciasAction()
    {
        $this->view->disable();
        $gerencias = array();
        $idResolucionMinisterial = 0;
        if ($_GET["id_gerencia"] > 0) {
            $organigrama = Organigramas::findFirstById($_GET["id_gerencia"]);
            if (is_object($organigrama) > 0) {

                $idResolucionMinisterial = $organigrama->resolucion_ministerial_id;
            }
        }
        $org = new Organigramas();
        $resul = $org->getGerencias($idResolucionMinisterial);
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $gerencias[] = array(
                    'id' => $v->id,
                    'padre_id' => $v->padre_id,
                    'gestion' => $v->gestion,
                    'da_id' => $v->da_id,
                    'regional_id' => $v->regional_id,
                    'gestion_unidad_administrativa' => $v->gestion . " " . $v->unidad_administrativa,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'nivel_estructural_id' => $v->nivel_estructural_id,
                    'sigla' => $v->sigla,
                    'fecha_ini' => $v->fecha_ini,
                    'fecha_fin' => $v->fecha_fin,
                    'orden' => $v->orden,
                    'observacion' => $v->observacion,
                    'estado' => $v->estado,
                    'baja_logica' => $v->baja_logica,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                    'area_sustantiva' => $v->area_sustantiva,
                    'visible' => $v->visible
                );
            }
        }
        echo json_encode($gerencias);
    }

    /**
     * Función para la obtención del listado de departamentos administrativos.
     * El nombre de la función se especifica así pues se prevé necesitarse el listado de los departamentos del país.
     */
    function listdepartamentosadministrativosAction()
    {
        $this->view->disable();
        $org = new Organigramas();
        $padre_id = $_GET["padre_id"];
        $resul = $org->getDepartamentosAdministrativosPorGerencia($padre_id);
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $gerencias[] = array(
                    'id' => $v->id,
                    'padre_id' => $v->padre_id,
                    'gestion' => $v->gestion,
                    'da_id' => $v->da_id,
                    'regional_id' => $v->regional_id,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'nivel_estructural_id' => $v->nivel_estructural_id,
                    'sigla' => $v->sigla,
                    'fecha_ini' => $v->fecha_ini,
                    'fecha_fin' => $v->fecha_fin,
                    'orden' => $v->orden,
                    'observacion' => $v->observacion,
                    'estado' => $v->estado,
                    'baja_logica' => $v->baja_logica,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                    'area_sustantiva' => $v->area_sustantiva,
                    'visible' => $v->visible
                );
            }
        }
        echo json_encode($gerencias);
    }

    /**
     * Función para la obtención del listado de áreas administrativas.
     */
    function listareasadministrativasAction()
    {
        $this->view->disable();
        $org = new Organigramas();
        $padre_id = $_GET["padre_id"];
        $resul = $org->getAreasAdministrativas($padre_id);
        //comprobamos si hay filas
        $areas = array();
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $areas[] = array(
                    'id' => $v->id,
                    'padre_id' => $v->padre_id,
                    'gestion' => $v->gestion,
                    'da_id' => $v->da_id,
                    'regional_id' => $v->regional_id,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'nivel_estructural_id' => $v->nivel_estructural_id,
                    'sigla' => $v->sigla,
                    'fecha_ini' => $v->fecha_ini,
                    'fecha_fin' => $v->fecha_fin,
                    'orden' => $v->orden,
                    'observacion' => $v->observacion,
                    'estado' => $v->estado,
                    'baja_logica' => $v->baja_logica,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                    'area_sustantiva' => $v->area_sustantiva,
                    'visible' => $v->visible
                );
            }
        }
        echo json_encode($areas);
    }

    /**
     * Función para el registro de una nueva movilidad funcionaria
     */
    public function savemovilidadAction()
    {
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $user_reg_id = $auth['id'];
        $msj = Array();
        $gestion_actual = date("Y");
        $hoy = date("Y-m-d H:i:s");
        $fecha_fin = "31/12/" . $gestion_actual;
        $this->view->disable();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            /**
             * Edición de registro
             */
            $id_relaboralmovilidad = $_POST["id"];
            $objRM = new Frelaboralesmovilidad();
            //$resultRelaboralMovilidad = $objRM->getOne($id_relaboralmovilidad);
            $objRelaboralMovilidad = Relaboralesmovilidades::findFirst(array("id=" . $id_relaboralmovilidad));
            if ($objRelaboralMovilidad != null && $objRelaboralMovilidad->id > 0) {

                if (isset($_POST['id_relaboral'])) {
                    $id_relaboral = $_POST['id_relaboral'];
                    $id_da = $_POST['id_da'];
                    $id_regional = $_POST['id_regional'];
                    $id_organigrama = $_POST['id_organigrama'];
                    $id_area = $_POST['id_area'];
                    $id_ubicacion = $_POST['id_ubicacion'];
                    $id_memorandum = $_POST['id_memorandum'];
                    if (isset($_POST['cargo']['value'])) {
                        $cargo = $_POST['cargo']['value'];
                    } elseif (isset($_POST['cargo'])) {
                        $cargo = $_POST['cargo'];
                    }
                    $id_evento = $_POST['id_evento'];
                    $motivo = $_POST['motivo'];
                    $id_pais = $_POST['id_pais'];
                    $id_departamento = $_POST['id_departamento'];
                    $lugar = $_POST['lugar'];
                    $id_tipomemorandum = $_POST['id_tipomemorandum'];
                    $correlativo = $_POST['correlativo'];
                    $gestion = $_POST['gestion'];
                    $datefm = new DateTime($_POST['fecha_mem']);
                    $fecha_mem = $datefm->format('Y-m-d');
                    $contenido_memorandum = $_POST['contenido'];
                    $datefi = new DateTime($_POST['fecha_ini']);
                    $fecha_ini = $datefi->format('Y-m-d');
                    /**
                     * Calculamos la fecha previa al inicio para uso posterior.
                     */
                    $datefiaux = new DateTime($_POST['fecha_ini']);
                    $datefiaux->format('Y-m-d');
                    $fecha_fin_aux = date('Y-m-d', strtotime('-1 days', strtotime($_POST['fecha_ini'])));

                    $hora_ini = $_POST['hora_ini'];
                    $dateff = new DateTime($_POST['fecha_fin']);
                    $fecha_fin = $dateff->format('Y-m-d');
                    $hora_fin = $_POST['hora_fin'];
                    $observacion = $_POST['observacion'];
                    $obj = new Frelaborales();
                    $resul = $obj->getOne($id_relaboral);
                    $frelaboral = $resul[0];
                    /**
                     * Una movilidad de personal sólo debería poderse registrar para registros de relación laboral ACTIVOS o EN PROCESO.
                     */
                    if ($frelaboral->id_relaboral > 0 && $frelaboral->estado >= 1) {
                        $memorandum = Memorandums::findFirst(array("id=" . $id_memorandum));
                        if ($memorandum != null && $memorandum->id > 0) {
                            $memorandum->relaboral_id = $frelaboral->id_relaboral;
                            $memorandum->finpartida_id = $frelaboral->id_fin_partida;
                            $memorandum->fecha_mem = $fecha_mem;
                            $memorandum->correlativo = $correlativo;
                            $memorandum->gestion = $gestion;
                            $memorandum->da_id = $frelaboral->id_da;
                            $memorandum->regional_id = $frelaboral->id_regional;
                            $memorandum->tipomemorandum_id = $id_tipomemorandum;
                            $memorandum->estado = 1;
                            $memorandum->baja_logica = 1;
                            $memorandum->agrupador = 0;
                            $memorandum->user_mod_id = $user_mod_id;
                            $memorandum->fecha_mod = $hoy;
                            try {
                                if ($memorandum->save()) {
                                    $objRelaboralMovilidad->relaboral_id = $frelaboral->id_relaboral;
                                    $objRelaboralMovilidad->da_id = $frelaboral->id_da;
                                    $objRelaboralMovilidad->regional_id = $frelaboral->id_regional;

                                    $modalidadmemorandum = Modalidadmemorandum::findFirst(array("tipomemorandum_id=" . $id_tipomemorandum));
                                    if ($modalidadmemorandum->id > 0) {
                                        $objRelaboralMovilidad->modalidadmemorandum_id = $modalidadmemorandum->id;
                                    }
                                    $objRelaboralMovilidad->memorandum_id = $memorandum->id;

                                    /**
                                     * Obtener la cantidad de memos registrados + 1
                                     */
                                    /*
                                    $objRel = Relaboralesmovilidades::find(array("baja_logica=1 AND relaboral_id=".$id_relaboral));
                                    $objRelaboralMovilidad->numero = $objRel->count()+1;*/

                                    if ($cargo != '') {
                                        $objRelaboralMovilidad->cargo = $cargo;
                                    }
                                    $objRelaboralMovilidad->fecha_ini = $fecha_ini;
                                    if ($hora_ini != '') {
                                        $objRelaboralMovilidad->hora_ini = $hora_ini;
                                    }
                                    #region Evaluación de la obligatoriedad de registro de algunos datos
                                    $tipomemorandum = Tiposmemorandums::findFirst(array("id=" . $id_tipomemorandum));
                                    if ($tipomemorandum->fecha_fin >= 1) {
                                        $objRelaboralMovilidad->fecha_fin = $fecha_fin;
                                        /**
                                         * Para el control de la hora es necesario que si o si se registre la fecha de finalización
                                         */
                                        if ($hora_fin != '') $objRelaboralMovilidad->hora_fin = $hora_fin;
                                    } else {
                                        /**
                                         * Evaluar la necesidad de registro de la fecha de finalización del registro de relación laboral como fecha de finalización de la movilidad.
                                         */
                                    }

                                    if ($tipomemorandum->cargo >= 1 && $cargo != '') {
                                        $objRelaboralMovilidad->cargo = $cargo;
                                    }

                                    if ($tipomemorandum->motivo >= 1 && $motivo != '') {
                                        $objRelaboralMovilidad->motivo = $motivo;
                                    }

                                    if ($tipomemorandum->pais >= 1 && $id_pais > 0) {
                                        $objRelaboralMovilidad->pais_id = $id_pais;
                                    }

                                    if ($tipomemorandum->ciudad >= 1 && $id_departamento > 0) {
                                        $objRelaboralMovilidad->departamento_id = $id_departamento;
                                    }

                                    if ($tipomemorandum->lugar >= 1 && $lugar != '') {
                                        $objRelaboralMovilidad->lugar = $lugar;
                                    }
                                    /**
                                     * Es necesario verificar si se ha especificado un valor para el organigrama,
                                     * si no es así se registra el correspondiente al registro de relación laboral.
                                     */
                                    if ($tipomemorandum->organigrama >= 1 && $id_organigrama > 0) {
                                        $objRelaboralMovilidad->organigrama_id = $id_organigrama;
                                        /**
                                         * Verificando el identificador del área enviada
                                         */
                                        if ($id_area > 0) {
                                            /**
                                             * Se evalua si el identificador del área enviada corresponde validamente al identificador del organigrama enviado.
                                             */
                                            $org = new Organigramas();
                                            $okArea = $org->verificarCorrectaCorrespondeciaArea($id_organigrama, $id_area);
                                            if ($okArea != null && $okArea > 0) {
                                                $objRelaboralMovilidad->area_id = $id_area;
                                            }
                                        }
                                    }
                                    if ($tipomemorandum->ubicacion >= 1) {
                                        //$relaboralesmovilidades->ubicacion_id = $id_ubicacion;

                                        /**
                                         * Verificando el identificador de la ubicación enviada
                                         */
                                        if ($id_ubicacion > 0) {
                                            $objRelaboralMovilidad->ubicacion_id = $id_ubicacion;
                                        } elseif ($id_ubicacion < 0) {
                                            /**
                                             * Este valor establece que se debe ubicar a la asignación de funciones en el mismo lugar donde
                                             * se encuentra registrado el cargo del jefe, pues se ha seleccionado para el registro.
                                             */
                                            $objCargo = new Cargos();
                                            $objSuperior = $objCargo->getCargoSuperiorPorRelaboral($frelaboral->id_relaboral);
                                            if ($objSuperior != null && count($objSuperior) > 0) {
                                                $cargoSup = $objSuperior[0];
                                                /**
                                                 * Se selecciona el último registro que haya sido usado por el jefe
                                                 */
                                                $relaboralAux = Relaborales::findFirst(array("cargo_id = " . $cargoSup->id, 'order' => 'fecha_ini DESC'));
                                                if ($relaboralAux != null && $relaboralAux->id > 0) {
                                                    $relaboralUbicacionAux = Relaboralesubicaciones::findFirst(array("estado=1 AND relaboral_id=" . $relaboralAux->id));
                                                    if ($relaboralUbicacionAux != null) {
                                                        $objRelaboralMovilidad->ubicacion_id = $relaboralUbicacionAux->ubicacion_id;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    #endregion Evaluación de la obligatoriedad de registro de algunos datos
                                    $objRelaboralMovilidad->observacion = $observacion;
                                    /**
                                     * Verificar que no haya otro memorándum de movilidad de personal que este activo,
                                     * si existe otro, darlo de baja inicialmente.
                                     */
                                    $objRelaboralMovilidad->estado = 1;
                                    $objRelaboralMovilidad->baja_logica = 1;
                                    $objRelaboralMovilidad->agrupador = 0;
                                    $objRelaboralMovilidad->user_mod_id = $user_mod_id;
                                    $objRelaboralMovilidad->fecha_mod = $hoy;
                                    try {
                                        /**
                                         * Es necesario verificar que no exista un registro, sea ACTIVO o PASIVO que tenga el mismo tipo de modalidad y tenga cruce de fechas con el registro que se desea realizar
                                         * Pueden tener la misma fecha, pues la variación puede ser en horas.
                                         */
                                        $swAnterior = true;
                                        $anteriorRelaboralMovilidadDelMismoTipo = Relaboralesmovilidades::findFirst(array("baja_logica=1 and modalidadmemorandum_id=" . $objRelaboralMovilidad->modalidadmemorandum_id . " AND relaboral_id = " . $objRelaboralMovilidad->relaboral_id . " AND id!=" . $id_relaboralmovilidad));
                                        if ($anteriorRelaboralMovilidadDelMismoTipo != null && $anteriorRelaboralMovilidadDelMismoTipo->id > 0) {
                                            /**
                                             * Viendo si hay cruce de fechas, si lo hay se impide el registro
                                             */
                                            if ($anteriorRelaboralMovilidadDelMismoTipo->fecha_fin == null || $anteriorRelaboralMovilidadDelMismoTipo->fecha_fin == '' ||
                                                $anteriorRelaboralMovilidadDelMismoTipo->fecha_fin > $objRelaboralMovilidad->fecha_ini
                                            ) {
                                                $anteriorRelaboralMovilidadDelMismoTipo->fecha_fin = $fecha_fin_aux;
                                                $anteriorRelaboralMovilidadDelMismoTipo->estado = 0;
                                                $anteriorRelaboralMovilidadDelMismoTipo->user_mod_id = $user_reg_id;
                                                $anteriorRelaboralMovilidadDelMismoTipo->fecha_mod = $hoy;
                                                $datetime1 = new DateTime($anteriorRelaboralMovilidadDelMismoTipo->fecha_ini);
                                                $datetime2 = new DateTime($fecha_fin_aux);
                                                if ($datetime1 > $datetime2) {
                                                    $swAnterior = false;
                                                } else $anteriorRelaboralMovilidadDelMismoTipo->save();
                                            }
                                        }
                                        if ($swAnterior) {
                                            if ($objRelaboralMovilidad->save()) {
                                                $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se guard&oacute; correctamente el registro de Movilidad de Personal.');
                                            } else $msj = array('result' => 0, 'msj' => 'Error: No se registr&oacute; la movilidad de personal.');
                                        } else $msj = array('result' => 0, 'msj' => 'Error: No se registr&oacute; la movilidad de personal debido a que presenta una inconsistencia de fechas con un registro anterior de Movilidad de Personal del mismo tipo. Verifique la fecha de inicio. (' . $objRelaboralMovilidad->relaboral_id . ':' . $objRelaboralMovilidad->modalidadmemorandum_id . ')');
                                    } catch (\Exception $e) {
                                        echo get_class($e), ": ", $e->getMessage(), "\n";
                                        echo " File=", $e->getFile(), "\n";
                                        echo " Line=", $e->getLine(), "\n";
                                        echo $e->getTraceAsString();
                                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se registr&oacute; la movilidad de personal.');
                                    }
                                } else $msj = array('result' => 0, 'msj' => 'Error: No se registr&oacute; el memor&aacute;ndum.');
                            } catch (\Exception $e) {
                                echo get_class($e), ": ", $e->getMessage(), "\n";
                                echo " File=", $e->getFile(), "\n";
                                echo " Line=", $e->getLine(), "\n";
                                echo $e->getTraceAsString();
                                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se registr&oacute; la movilidad de personal.');
                            }
                        } else {
                            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se registr&oacute; la movilidad de personal debido a que no se hall&oacute; registro del memor&acute;ndum.');
                        }
                    } else {
                        $msj = array('result' => 0, 'msj' => 'Error cr&iacute;tico: No se registr&oacute; la movilidad de personal. Verifique los datos enviados.');
                    }

                } else {
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro debido a datos erroneos de la relaci&oacute;n laboral.');
                }
            } else {
                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro debido a datos erroneos al identificador del registro de relaci&oacute;n laboral por movilidad.');
            }
        } else {
            /**
             * Nuevo Registro
             */
            if (isset($_POST['id_relaboral'])) {
                $id_relaboral = $_POST['id_relaboral'];
                $id_da = $_POST['id_da'];
                $id_regional = $_POST['id_regional'];
                $id_organigrama = $_POST['id_organigrama'];
                $id_area = $_POST['id_area'];
                $id_ubicacion = $_POST['id_ubicacion'];
                if (isset($_POST['cargo']['value'])) {
                    $cargo = $_POST['cargo']['value'];
                } elseif (isset($_POST['cargo'])) {
                    $cargo = $_POST['cargo'];
                }
                $id_evento = $_POST['id_evento'];
                $motivo = $_POST['motivo'];
                $id_pais = $_POST['id_pais'];
                $id_departamento = $_POST['id_departamento'];
                $lugar = $_POST['lugar'];
                $id_tipomemorandum = $_POST['id_tipomemorandum'];
                $correlativo = $_POST['correlativo'];
                $gestion = $_POST['gestion'];
                $datefm = new DateTime($_POST['fecha_mem']);
                $fecha_mem = $datefm->format('Y-m-d');
                $contenido_memorandum = $_POST['contenido'];
                $datefi = new DateTime($_POST['fecha_ini']);
                $fecha_ini = $datefi->format('Y-m-d');
                /**
                 * Calculamos la fecha previa al inicio para uso posterior.
                 */
                $datefiaux = new DateTime($_POST['fecha_ini']);
                $datefiaux->format('Y-m-d');
                $fecha_fin_aux = date('Y-m-d', strtotime('-1 days', strtotime($_POST['fecha_ini'])));

                $hora_ini = $_POST['hora_ini'];
                $dateff = new DateTime($_POST['fecha_fin']);
                $fecha_fin = $dateff->format('Y-m-d');
                $hora_fin = $_POST['hora_fin'];
                $observacion = $_POST['observacion'];
                $obj = new Frelaborales();
                $resul = $obj->getOne($id_relaboral);
                $frelaboral = $resul[0];
                /**
                 * Una movilidad de personal sólo debería poderse registrar para registros de relación laboral ACTIVOS o EN PROCESO.
                 */
                if ($frelaboral->id_relaboral > 0 && $frelaboral->estado >= 1) {
                    $memorandum = new Memorandums();
                    $memorandum->relaboral_id = $frelaboral->id_relaboral;
                    $memorandum->finpartida_id = $frelaboral->id_fin_partida;
                    $memorandum->fecha_mem = $fecha_mem;
                    $memorandum->correlativo = $correlativo;
                    $memorandum->gestion = $gestion;
                    $memorandum->da_id = $frelaboral->id_da;
                    $memorandum->regional_id = $frelaboral->id_regional;
                    $memorandum->tipomemorandum_id = $id_tipomemorandum;
                    $memorandum->estado = 1;
                    $memorandum->baja_logica = 1;
                    $memorandum->agrupador = 0;
                    $memorandum->user_reg_id = $user_reg_id;
                    $memorandum->fecha_reg = $hoy;
                    try {
                        if ($memorandum->save()) {
                            $objRelaboralMovilidad = new Relaboralesmovilidades();
                            $objRelaboralMovilidad->relaboral_id = $frelaboral->id_relaboral;
                            $objRelaboralMovilidad->da_id = $frelaboral->id_da;
                            $objRelaboralMovilidad->regional_id = $frelaboral->id_regional;

                            $modalidadmemorandum = Modalidadmemorandum::findFirst(array("tipomemorandum_id=" . $id_tipomemorandum));
                            if ($modalidadmemorandum->id > 0) {
                                $objRelaboralMovilidad->modalidadmemorandum_id = $modalidadmemorandum->id;
                            }
                            $objRelaboralMovilidad->memorandum_id = $memorandum->id;

                            /**
                             * Obtener la cantidad de memos registrados + 1
                             */
                            $objRel = Relaboralesmovilidades::find(array("baja_logica=1 and relaboral_id=" . $id_relaboral));
                            $objRelaboralMovilidad->numero = $objRel->count() + 1;

                            if ($cargo != '') {
                                $objRelaboralMovilidad->cargo = $cargo;
                            }
                            $objRelaboralMovilidad->fecha_ini = $fecha_ini;
                            if ($hora_ini != '') {
                                $objRelaboralMovilidad->hora_ini = $hora_ini;
                            }
                            #region Evaluación de la obligatoriedad de registro de algunos datos
                            $tipomemorandum = Tiposmemorandums::findFirst(array("id=" . $id_tipomemorandum));
                            if ($tipomemorandum->fecha_fin >= 1) {
                                $objRelaboralMovilidad->fecha_fin = $fecha_fin;
                                /**
                                 * Para el control de la hora es necesario que si o si se registre la fecha de finalización
                                 */
                                if ($hora_fin != '') $objRelaboralMovilidad->hora_fin = $hora_fin;
                            } else {
                                /**
                                 * Evaluar la necesidad de registro de la fecha de finalización del registro de relación laboral como fecha de finalización de la movilidad.
                                 */
                            }

                            if ($tipomemorandum->cargo >= 1 && $cargo != '') {
                                $objRelaboralMovilidad->cargo = $cargo;
                            }

                            if ($tipomemorandum->motivo >= 1 && $motivo != '') {
                                $objRelaboralMovilidad->motivo = $motivo;
                            }

                            if ($tipomemorandum->pais >= 1 && $id_pais > 0) {
                                $objRelaboralMovilidad->pais_id = $id_pais;
                            }

                            if ($tipomemorandum->ciudad >= 1 && $id_departamento > 0) {
                                $objRelaboralMovilidad->departamento_id = $id_departamento;
                            }

                            if ($tipomemorandum->lugar >= 1 && $lugar != '') {
                                $objRelaboralMovilidad->lugar = $lugar;
                            }
                            /**
                             * Es necesario verificar si se ha especificado un valor para el organigrama,
                             * si no es así se registra el correspondiente al registro de relación laboral.
                             */
                            if ($tipomemorandum->organigrama >= 1 && $id_organigrama > 0) {
                                $objRelaboralMovilidad->organigrama_id = $id_organigrama;
                                /**
                                 * Verificando el identificador del área enviada
                                 */
                                if ($id_area > 0) {
                                    /**
                                     * Se evalua si el identificador del área enviada corresponde validamente al identificador del organigrama enviado.
                                     */
                                    $org = new Organigramas();
                                    $okArea = $org->verificarCorrectaCorrespondeciaArea($id_organigrama, $id_area);
                                    if ($idArea != null && $okArea > 0) {
                                        $objRelaboralMovilidad->area_id = $id_area;
                                    }
                                }
                            }
                            if ($tipomemorandum->ubicacion >= 1) {
                                //$relaboralesmovilidades->ubicacion_id = $id_ubicacion;

                                /**
                                 * Verificando el identificador de la ubicación enviada
                                 */
                                if ($id_ubicacion > 0) {
                                    $objRelaboralMovilidad->ubicacion_id = $id_ubicacion;
                                } elseif ($id_ubicacion < 0) {
                                    /**
                                     * Este valor establece que se debe ubicar a la asignación de funciones en el mismo lugar donde
                                     * se encuentra registrado el cargo del jefe, pues se ha seleccionado para el registro.
                                     */
                                    $objCargo = new Cargos();
                                    $objSuperior = $objCargo->getCargoSuperiorPorRelaboral($frelaboral->id_relaboral);
                                    if ($objSuperior != null && count($objSuperior) > 0) {
                                        $cargoSup = $objSuperior[0];
                                        /**
                                         * Se selecciona el último registro que haya sido usado por el jefe
                                         */
                                        $relaboralAux = Relaborales::findFirst(array("cargo_id = " . $cargoSup->id, 'order' => 'fecha_ini DESC'));
                                        if ($relaboralAux != null && $relaboralAux->id > 0) {
                                            $relaboralUbicacionAux = Relaboralesubicaciones::findFirst(array("estado=1 AND relaboral_id=" . $relaboralAux->id));
                                            if ($relaboralUbicacionAux != null) {
                                                $objRelaboralMovilidad->ubicacion_id = $relaboralUbicacionAux->ubicacion_id;
                                            }
                                        }
                                    }
                                }
                            }
                            #endregion Evaluación de la obligatoriedad de registro de algunos datos
                            $objRelaboralMovilidad->observacion = $observacion;
                            /**
                             * Verificar que no haya otro memorándum de movilidad de personal que este activo,
                             * si existe otro, darlo de baja inicialmente.
                             */
                            $objRelaboralMovilidad->estado = 1;
                            $objRelaboralMovilidad->baja_logica = 1;
                            $objRelaboralMovilidad->agrupador = 0;
                            $objRelaboralMovilidad->user_reg_id = $user_reg_id;
                            $objRelaboralMovilidad->fecha_reg = $hoy;
                            try {
                                /**
                                 * Es necesario verificar que no exista un registro, sea ACTIVO o PASIVO que tenga el mismo tipo de modalidad y tenga cruce de fechas con el registro que se desea realizar
                                 * Pueden tener la misma fecha, pues la variación puede ser en horas.
                                 */
                                $swAnterior = true;
                                $anteriorRelaboralMovilidadDelMismoTipo = Relaboralesmovilidades::findFirst(array("baja_logica=1 and modalidadmemorandum_id=" . $objRelaboralMovilidad->modalidadmemorandum_id . " AND relaboral_id=" . $objRelaboralMovilidad->relaboral_id));
                                if ($anteriorRelaboralMovilidadDelMismoTipo != null && $anteriorRelaboralMovilidadDelMismoTipo->id > 0) {
                                    /**
                                     * Viendo si hay cruce de fechas, si lo hay se impide el registro
                                     */
                                    if ($anteriorRelaboralMovilidadDelMismoTipo->fecha_fin == null || $anteriorRelaboralMovilidadDelMismoTipo->fecha_fin == '' ||
                                        $anteriorRelaboralMovilidadDelMismoTipo->fecha_fin > $objRelaboralMovilidad->fecha_ini
                                    ) {
                                        $anteriorRelaboralMovilidadDelMismoTipo->fecha_fin = $fecha_fin_aux;
                                        $anteriorRelaboralMovilidadDelMismoTipo->estado = 0;
                                        $anteriorRelaboralMovilidadDelMismoTipo->user_mod_id = $user_reg_id;
                                        $anteriorRelaboralMovilidadDelMismoTipo->fecha_mod = $hoy;
                                        $datetime1 = new DateTime($anteriorRelaboralMovilidadDelMismoTipo->fecha_ini);
                                        $datetime2 = new DateTime($fecha_fin_aux);
                                        if ($datetime1 > $datetime2) {
                                            $swAnterior = false;
                                        } else $anteriorRelaboralMovilidadDelMismoTipo->save();
                                    }
                                }
                                if ($swAnterior) {
                                    if ($objRelaboralMovilidad->save()) {
                                        $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se guard&oacute; correctamente el registro de Movilidad de Personal.');
                                    } else {
                                        $memorandum->delete();
                                    }

                                } else {
                                    $msj = array('result' => 0, 'msj' => 'Error: No se registr&oacute; la movilidad de personal debido a que presenta una inconsistencia de fechas con un registro anterior de Movilidad de Personal del mismo tipo. Verifique la fecha de inicio. (' . $objRelaboralMovilidad->relaboral_id . ':' . $objRelaboralMovilidad->modalidadmemorandum_id . ')');
                                    /**
                                     * Es necesario dar de baja el registro del memorandum
                                     */
                                    $memorandum->delete();
                                }
                            } catch (\Exception $e) {
                                echo get_class($e), ": ", $e->getMessage(), "\n";
                                echo " File=", $e->getFile(), "\n";
                                echo " Line=", $e->getLine(), "\n";
                                echo $e->getTraceAsString();
                                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se registr&oacute; la movilidad de personal.');
                            }
                        } else $msj = array('result' => 0, 'msj' => 'Error: No se registr&oacute; el memor&aacute;ndum.');
                    } catch (\Exception $e) {
                        echo get_class($e), ": ", $e->getMessage(), "\n";
                        echo " File=", $e->getFile(), "\n";
                        echo " Line=", $e->getLine(), "\n";
                        echo $e->getTraceAsString();
                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se registr&oacute; la movilidad de personal.');
                    }
                } else {
                    $msj = array('result' => 0, 'msj' => 'Error: No se registr&oacute; la movilidad de personal. Verifique los datos enviados.');
                }

            } else {
                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro debido a datos erroneos de la relaci&oacute;n laboral.');
            }
        }
        echo json_encode($msj);
    }

    /**
     * Función para el registro de la baja de relación laboral por movilidad.
     */
    public function downmovilidadAction()
    {
        $user_mod_id = 1;
        $msj = Array();
        $gestion_actual = date("Y");
        $hoy = date("Y-m-d H:i:s");
        $fecha_fin = "31/12/" . $gestion_actual;
        $this->view->disable();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            $id_relaboralmovilidad = $_POST["id"];
            $dateff = new DateTime($_POST['fecha_fin']);
            $fecha_fin = $dateff->format('Y-m-d');
            $objRM = Relaboralesmovilidades::findFirst(array("id=" . $id_relaboralmovilidad));
            if ($objRM != null && $objRM->id > 0) {
                $objRM->fecha_fin = $fecha_fin;
                $objRM->estado = 0;
                $objRM->user_mod_id = $user_mod_id;
                $objRM->fecha_mod = $hoy;
                /**
                 * Es necesario verificar que la fecha de finalización registrada en la baja no tenga conflictos con las fechas de otros registros del mismo tipo.
                 */
                $swAnterior = true;
                $anteriorRelaboralMovilidadDelMismoTipo = Relaboralesmovilidades::findFirst(array("baja_logica=1 and modalidadmemorandum_id=" . $objRM->modalidadmemorandum_id . " AND relaboral_id=" . $objRM->relaboral_id . " AND id!=" . $objRM->id . " AND CAST('" . $fecha_fin . "' AS DATE) BETWEEN fecha_ini and fecha_fin"));
                if ($anteriorRelaboralMovilidadDelMismoTipo != null && $anteriorRelaboralMovilidadDelMismoTipo->id > 0) {
                    $swAnterior = false;
                }
                if ($swAnterior) {
                    if ($objRM->save()) {
                        $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se di&oacute; de baja correctamente el registro de Movilidad de Personal.');
                    } else $msj = array('result' => 0, 'msj' => 'Error: No se registr&oacute; la baja del registro de movilidad de personal. Verifique los datos enviados.');
                } else $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se registr&oacute; la baja del registro de movilidad de personal debido a que la fecha de finalizaci&oacute;n presenta una inconsistencia en relaci&oacute; a otro registro del mismo tipo . Verifique la fecha de finalizaci&oacute;n.');
            } else {
                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se registr&oacute; la baja debido a que no se hall&oacute; registro de la relaci&oacute;n laboral por movilidad.');
            }
        }
        echo json_encode($msj);
    }

    /**
     * Función para obtener registro correspondiente al cargo del inmediato superior considerando el identificador de la relación laboral.
     */
    public function getcargosuperiorrelaboralAction()
    {
        $cargo = Array();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            $this->view->disable();
            $id_relaboral = $_POST["id"];
            $obj = new Cargos();
            $result = $obj->getCargoSuperiorPorRelaboral($id_relaboral);
            if (count($result) > 0) $cargo = $result[0];
        }
        echo json_encode($cargo);
    }

    /**
     * Función para la obtención del listado de paises.
     */
    public function listpaisesAction()
    {
        $this->view->disable();
        $resul = Paises::find(array("estado=1 AND baja_logica=1"));
        //comprobamos si hay filas
        $paises = array();
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $paises[] = array(
                    'id' => $v->id,
                    'iso2' => $v->iso2,
                    'iso3' => $v->iso3,
                    'prefijo' => $v->prefijo,
                    'pais' => $v->pais,
                    'continente' => $v->continente,
                    'subcontinente' => $v->subcontinente,
                    'iso_moneda' => $v->iso_moneda,
                    'nombre_moneda' => $v->nombre_moneda,
                    'nacionalidad' => $v->nacionalidad,
                    'observacion' => $v->observacion,
                    'estado' => $v->estado,
                    'baja_logica' => $v->baja_logica,
                    'agrupador' => $v->agrupador
                );
            }
        }
        echo json_encode($paises);
    }

    /**
     * Función para la obtención del listado de departamentos.
     */
    public function listdepartamentosAction()
    {
        $this->view->disable();
        $paises = array();
        if (isset($_GET["pais_id"])) {
            $pais_id = $_GET["pais_id"];
            $resul = Departamentos::find(array("estado=1 AND baja_logica=1 AND pais_id=" . $pais_id));
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $paises[] = array(
                        'id' => $v->id,
                        'pais_id' => $v->pais_id,
                        'departamento' => $v->departamento,
                        'observacion' => $v->observacion,
                        'estado' => $v->estado,
                        'baja_logica' => $v->baja_logica,
                        'agrupador' => $v->agrupador
                    );
                }
            }
        }
        echo json_encode($paises);
    }

    /**
     * Funcion para la eliminacion lógica de un registro de movilidad de personal.
     *  0: Error
     *   1: Procesado
     *  -1: Crítico Error
     *  -2: Error de Conexión
     *  -3: Usuario no Autorizado
     */
    public function delmovilidadAction()
    {
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $msj = Array();
        $gestion_actual = date("Y");
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            $objRelaboralmovilidad = Relaboralesmovilidades::findFirstById($_POST["id"]);
            $objRelaboralmovilidad->estado = 0;
            $objRelaboralmovilidad->baja_logica = 0;
            $objRelaboralmovilidad->user_mod_id = $user_mod_id;
            $objRelaboralmovilidad->fecha_mod = $hoy;
            try {
                $ok = $objRelaboralmovilidad->save();
                if ($ok) {
                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: Eliminaci&oacute;n exitosa del registro.');
                } else {
                    $msj = array('result' => 1, 'msj' => 'Error: No se pudo realizar la eliminaci&oacute;n del registro.');
                }
            } catch (\Exception $e) {
                echo get_class($e), ": ", $e->getMessage(), "\n";
                echo " File=", $e->getFile(), "\n";
                echo " Line=", $e->getLine(), "\n";
                echo $e->getTraceAsString();
                $msj = array('result' => -2, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de movilidad de personal.');
            }
        } else $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de movilidad de personal debido a error en el envio de datos.');
        echo json_encode($msj);
    }

    /**
     * Función para la obtención de la ruta validada para una imagen, considerando su CI.
     * @param $ci
     * @return string
     */
    public function determinaRutaImagen($ci)
    {
        $ruta = "";
        $rutaImagenesCredenciales = "/images/personal/";
        $extencionImagenesCredenciales = ".jpg";
        if (isset($ci)) {
            $ruta = "";
            $nombreImagenArchivo = $rutaImagenesCredenciales . trim($ci);
            $ruta = $nombreImagenArchivo . $extencionImagenesCredenciales;
            if (!file_exists(getcwd() . $ruta)) $ruta = '/images/perfil-profesional.jpg';
        }
        return $ruta;
    }

    /**
     * Función para el despliegue de los destinatarios principales para
     */
    function getinmediatosuperiorAction()
    {
        $relaboral = Array();

        $this->view->disable();
        if (isset($_POST["id_relaboral"]) && $_POST["id_relaboral"] > 0) {
            $idRelaboral = $_POST['id_relaboral'];
            if ($idRelaboral > 0) {
                $obj = new Frelaborales();
                $arr = $obj->getInmediatoSuperiorRelaboral($idRelaboral);
                if (count($arr) > 0) {
                    foreach ($arr as $v) {
                        $relaboral[] = array(
                            'ruta_foto' => $this->determinaRutaImagen($v->ci),
                            'id_relaboral' => $v->id_relaboral,
                            'id_persona' => $v->id_persona,
                            'p_nombre' => $v->p_nombre,
                            's_nombre' => $v->s_nombre,
                            't_nombre' => $v->t_nombre,
                            'p_apellido' => $v->p_apellido,
                            's_apellido' => $v->s_apellido,
                            'c_apellido' => $v->c_apellido,
                            'nombres' => $v->nombres,
                            'ci' => $v->ci,
                            'expd' => $v->expd,
                            'fecha_caducidad' => $v->fecha_caducidad,
                            'num_complemento' => '',
                            'fecha_nac' => $v->fecha_nac,
                            'edad' => $v->edad,
                            'lugar_nac' => $v->lugar_nac,
                            'genero' => $v->genero,
                            'e_civil' => $v->e_civil,
                            'tiene_item' => $v->tiene_item,
                            'item' => $v->item,
                            'carrera_adm' => $v->carrera_adm,
                            'num_contrato' => $v->num_contrato,
                            'contrato_numerador_estado' => $v->contrato_numerador_estado,
                            'id_solelabcontrato' => $v->id_solelabcontrato,
                            'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                            'solelabcontrato_numero' => $v->solelabcontrato_numero,
                            'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                            'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                            'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                            'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                            'fecha_ing' => $v->fecha_ing != "" ? date("d-m-Y", strtotime($v->fecha_ing)) : "",
                            'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                            'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                            'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                            'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                            'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                            'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                            'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                            'motivo_baja' => $v->motivo_baja,
                            'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                            'descripcion_baja' => $v->descripcion_baja,
                            'descripcion_anu' => $v->descripcion_anu,
                            'id_cargo' => $v->id_cargo,
                            'cargo_codigo' => $v->cargo_codigo,
                            'cargo' => $v->cargo,
                            'cargo_resolucion_ministerial_id' => $v->cargo_resolucion_ministerial_id,
                            'cargo_resolucion_ministerial' => $v->cargo_resolucion_ministerial,
                            'id_nivelessalarial' => $v->id_nivelessalarial,
                            'nivelsalarial' => $v->nivelsalarial,
                            'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                            'nivelsalarial_resolucion' => $v->nivelsalarial_resolucion,
                            'numero_escala' => $v->numero_escala,
                            'gestion_escala' => $v->gestion_escala,
                            /*'sueldo' => $v->sueldo,*/
                            'sueldo' => str_replace(".00", "", $v->sueldo),
                            'id_procesocontratacion' => $v->id_procesocontratacion,
                            'proceso_codigo' => $v->proceso_codigo,
                            'id_convocatoria' => $v->id_convocatoria,
                            'convocatoria_codigo' => $v->convocatoria_codigo,
                            'convocatoria_tipo' => $v->convocatoria_tipo,
                            'id_fin_partida' => $v->id_fin_partida,
                            'fin_partida' => $v->fin_partida,
                            'id_condicion' => $v->id_condicion,
                            'condicion' => $v->condicion,
                            'categoria_relaboral' => $v->categoria_relaboral,
                            'id_da' => $v->id_da,
                            'direccion_administrativa' => $v->direccion_administrativa,
                            'organigrama_regional_id' => $v->organigrama_regional_id,
                            'organigrama_regional' => $v->organigrama_regional,
                            'id_regional' => $v->id_regional,
                            'regional' => $v->regional,
                            'regional_codigo' => $v->regional_codigo,
                            'id_departamento' => $v->id_departamento,
                            'departamento' => $v->departamento,
                            'id_provincia' => $v->id_provincia,
                            'provincia' => $v->provincia,
                            'id_localidad' => $v->id_localidad,
                            'localidad' => $v->localidad,
                            'residencia' => $v->residencia,
                            'unidad_ejecutora' => $v->unidad_ejecutora,
                            'cod_ue' => $v->cod_ue,
                            'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                            'gerencia_administrativa' => $v->gerencia_administrativa,
                            'id_departamento_administrativo' => $v->id_departamento_administrativo,
                            'departamento_administrativo' => $v->departamento_administrativo,
                            'id_organigrama' => $v->id_organigrama,
                            'unidad_administrativa' => $v->unidad_administrativa,
                            'organigrama_sigla' => $v->organigrama_sigla,
                            'organigrama_orden' => $v->organigrama_orden,
                            'id_area' => $v->id_area,
                            'area' => $v->area,
                            'id_ubicacion' => $v->id_ubicacion,
                            'ubicacion' => $v->ubicacion,
                            'unidades_superiores' => $v->unidades_superiores,
                            'unidades_dependientes' => $v->unidades_dependientes,
                            'partida' => $v->partida,
                            'fuente_codigo' => $v->fuente_codigo,
                            'fuente' => $v->fuente,
                            'organismo_codigo' => $v->organismo_codigo,
                            'organismo' => $v->organismo,
                            'relaboral_previo_id' => $v->relaboral_previo_id,
                            'observacion' => ($v->observacion != null) ? $v->observacion : "",
                            'estado' => $v->estado,
                            'estado_descripcion' => $v->estado_descripcion,
                            'estado_abreviacion' => $v->estado_abreviacion,
                            'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                            'tiene_contrato_vigente_descripcion' => $v->tiene_contrato_vigente == 1 ? "SI" : ($v->tiene_contrato_vigente == 0 ? "NO" : ""),
                            'id_eventual' => $v->id_eventual,
                            'id_consultor' => $v->id_consultor,
                            'user_reg_id' => $v->user_reg_id,
                            'fecha_reg' => $v->fecha_reg,
                            'user_mod_id' => $v->user_mod_id,
                            'fecha_mod' => $v->fecha_mod,
                            'persona_user_reg_id' => $v->persona_user_reg_id,
                            'persona_fecha_reg' => $v->persona_fecha_reg,
                            'persona_user_mod_id' => $v->persona_user_mod_id,
                            'persona_fecha_mod' => $v->persona_fecha_mod
                        );
                    }
                }
            }
        }
        echo json_encode($relaboral);
    }

    /**
     * Función para encontrar el jefe inmediato superior.
     */
    function getinmediatojefesuperiorAction()
    {
        $relaboral = array();
        $this->view->disable();
        if (isset($_POST["id_relaboral"]) && $_POST["id_relaboral"] > 0) {
            $idRelaboral = $_POST['id_relaboral'];
            if ($idRelaboral > 0) {
                $obj = new Frelaborales();
                $arr = $obj->getJefeInmediatoSuperiorRelaboral($idRelaboral);
                if (count($arr) > 0) {
                    foreach ($arr as $v) {
                        $relaboral[] = array(
                            'ruta_foto' => $this->determinaRutaImagen($v->ci),
                            'id_relaboral' => $v->id_relaboral,
                            'id_persona' => $v->id_persona,
                            'p_nombre' => $v->p_nombre,
                            's_nombre' => $v->s_nombre,
                            't_nombre' => $v->t_nombre,
                            'p_apellido' => $v->p_apellido,
                            's_apellido' => $v->s_apellido,
                            'c_apellido' => $v->c_apellido,
                            'nombres' => $v->nombres,
                            'ci' => $v->ci,
                            'expd' => $v->expd,
                            'fecha_caducidad' => $v->fecha_caducidad,
                            'num_complemento' => '',
                            'fecha_nac' => $v->fecha_nac,
                            'edad' => $v->edad,
                            'lugar_nac' => $v->lugar_nac,
                            'genero' => $v->genero,
                            'e_civil' => $v->e_civil,
                            'tiene_item' => $v->tiene_item,
                            'item' => $v->item,
                            'carrera_adm' => $v->carrera_adm,
                            'num_contrato' => $v->num_contrato,
                            'contrato_numerador_estado' => $v->contrato_numerador_estado,
                            'id_solelabcontrato' => $v->id_solelabcontrato,
                            'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                            'solelabcontrato_numero' => $v->solelabcontrato_numero,
                            'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                            'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                            'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                            'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                            'fecha_ing' => $v->fecha_ing != "" ? date("d-m-Y", strtotime($v->fecha_ing)) : "",
                            'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                            'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                            'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                            'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                            'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                            'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                            'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                            'motivo_baja' => $v->motivo_baja,
                            'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                            'descripcion_baja' => $v->descripcion_baja,
                            'descripcion_anu' => $v->descripcion_anu,
                            'id_cargo' => $v->id_cargo,
                            'cargo_codigo' => $v->cargo_codigo,
                            'cargo' => $v->cargo,
                            'cargo_resolucion_ministerial_id' => $v->cargo_resolucion_ministerial_id,
                            'cargo_resolucion_ministerial' => $v->cargo_resolucion_ministerial,
                            'id_nivelessalarial' => $v->id_nivelessalarial,
                            'nivelsalarial' => $v->nivelsalarial,
                            'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                            'nivelsalarial_resolucion' => $v->nivelsalarial_resolucion,
                            'numero_escala' => $v->numero_escala,
                            'gestion_escala' => $v->gestion_escala,
                            /*'sueldo' => $v->sueldo,*/
                            'sueldo' => str_replace(".00", "", $v->sueldo),
                            'id_procesocontratacion' => $v->id_procesocontratacion,
                            'proceso_codigo' => $v->proceso_codigo,
                            'id_convocatoria' => $v->id_convocatoria,
                            'convocatoria_codigo' => $v->convocatoria_codigo,
                            'convocatoria_tipo' => $v->convocatoria_tipo,
                            'id_fin_partida' => $v->id_fin_partida,
                            'fin_partida' => $v->fin_partida,
                            'id_condicion' => $v->id_condicion,
                            'condicion' => $v->condicion,
                            'categoria_relaboral' => $v->categoria_relaboral,
                            'id_da' => $v->id_da,
                            'direccion_administrativa' => $v->direccion_administrativa,
                            'organigrama_regional_id' => $v->organigrama_regional_id,
                            'organigrama_regional' => $v->organigrama_regional,
                            'id_regional' => $v->id_regional,
                            'regional' => $v->regional,
                            'regional_codigo' => $v->regional_codigo,
                            'id_departamento' => $v->id_departamento,
                            'departamento' => $v->departamento,
                            'id_provincia' => $v->id_provincia,
                            'provincia' => $v->provincia,
                            'id_localidad' => $v->id_localidad,
                            'localidad' => $v->localidad,
                            'residencia' => $v->residencia,
                            'unidad_ejecutora' => $v->unidad_ejecutora,
                            'cod_ue' => $v->cod_ue,
                            'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                            'gerencia_administrativa' => $v->gerencia_administrativa,
                            'id_departamento_administrativo' => $v->id_departamento_administrativo,
                            'departamento_administrativo' => $v->departamento_administrativo,
                            'id_organigrama' => $v->id_organigrama,
                            'unidad_administrativa' => $v->unidad_administrativa,
                            'organigrama_sigla' => $v->organigrama_sigla,
                            'organigrama_orden' => $v->organigrama_orden,
                            'id_area' => $v->id_area,
                            'area' => $v->area,
                            'id_ubicacion' => $v->id_ubicacion,
                            'ubicacion' => $v->ubicacion,
                            'unidades_superiores' => $v->unidades_superiores,
                            'unidades_dependientes' => $v->unidades_dependientes,
                            'partida' => $v->partida,
                            'fuente_codigo' => $v->fuente_codigo,
                            'fuente' => $v->fuente,
                            'organismo_codigo' => $v->organismo_codigo,
                            'organismo' => $v->organismo,
                            'relaboral_previo_id' => $v->relaboral_previo_id,
                            'observacion' => ($v->observacion != null) ? $v->observacion : "",
                            'estado' => $v->estado,
                            'estado_descripcion' => $v->estado_descripcion,
                            'estado_abreviacion' => $v->estado_abreviacion,
                            'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                            'tiene_contrato_vigente_descripcion' => $v->tiene_contrato_vigente == 1 ? "SI" : ($v->tiene_contrato_vigente == 0 ? "NO" : ""),
                            'id_eventual' => $v->id_eventual,
                            'id_consultor' => $v->id_consultor,
                            'user_reg_id' => $v->user_reg_id,
                            'fecha_reg' => $v->fecha_reg,
                            'user_mod_id' => $v->user_mod_id,
                            'fecha_mod' => $v->fecha_mod,
                            'persona_user_reg_id' => $v->persona_user_reg_id,
                            'persona_fecha_reg' => $v->persona_fecha_reg,
                            'persona_user_mod_id' => $v->persona_user_mod_id,
                            'persona_fecha_mod' => $v->persona_fecha_mod
                        );
                    }
                }
            }
        }
        echo json_encode($relaboral);
    }

    /**
     * Función para la obtención del registro de relación laboral del aprobador directo.
     */
    public function getaprobadordirectoAction()
    {
        $this->view->disable();
        $objF = new Frelaborales();
        $relaboral = array();
        $idPersona = 0;
        $fecha = '';
        if (isset($_POST["id_persona"]) && isset($_POST["fecha"])) {
            $idPersona = $_POST["id_persona"];
            $fecha = $_POST["fecha"];
        }
        $arr = $objF->getIdRelaboralAprobadorDirecto($idPersona, $fecha);
        if (count($arr) > 0) {
            foreach ($arr as $v) {
                $relaboral[] = array(
                    'ruta_foto' => $this->determinaRutaImagen($v->ci),
                    'id_relaboral' => $v->id_relaboral,
                    'id_persona' => $v->id_persona,
                    'p_nombre' => $v->p_nombre,
                    's_nombre' => $v->s_nombre,
                    't_nombre' => $v->t_nombre,
                    'p_apellido' => $v->p_apellido,
                    's_apellido' => $v->s_apellido,
                    'c_apellido' => $v->c_apellido,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'fecha_caducidad' => $v->fecha_caducidad,
                    'num_complemento' => '',
                    'fecha_nac' => $v->fecha_nac,
                    'edad' => $v->edad,
                    'lugar_nac' => $v->lugar_nac,
                    'genero' => $v->genero,
                    'e_civil' => $v->e_civil,
                    'tiene_item' => $v->tiene_item,
                    'item' => $v->item,
                    'carrera_adm' => $v->carrera_adm,
                    'num_contrato' => $v->num_contrato,
                    'contrato_numerador_estado' => $v->contrato_numerador_estado,
                    'id_solelabcontrato' => $v->id_solelabcontrato,
                    'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                    'solelabcontrato_numero' => $v->solelabcontrato_numero,
                    'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                    'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                    'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                    'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                    'fecha_ing' => $v->fecha_ing != "" ? date("d-m-Y", strtotime($v->fecha_ing)) : "",
                    'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                    'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                    'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                    'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                    'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                    'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                    'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                    'motivo_baja' => $v->motivo_baja,
                    'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                    'descripcion_baja' => $v->descripcion_baja,
                    'descripcion_anu' => $v->descripcion_anu,
                    'id_cargo' => $v->id_cargo,
                    'cargo_codigo' => $v->cargo_codigo,
                    'cargo' => $v->cargo,
                    'cargo_resolucion_ministerial_id' => $v->cargo_resolucion_ministerial_id,
                    'cargo_resolucion_ministerial' => $v->cargo_resolucion_ministerial,
                    'id_nivelessalarial' => $v->id_nivelessalarial,
                    'nivelsalarial' => $v->nivelsalarial,
                    'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                    'nivelsalarial_resolucion' => $v->nivelsalarial_resolucion,
                    'numero_escala' => $v->numero_escala,
                    'gestion_escala' => $v->gestion_escala,
                    /*'sueldo' => $v->sueldo,*/
                    'sueldo' => str_replace(".00", "", $v->sueldo),
                    'id_procesocontratacion' => $v->id_procesocontratacion,
                    'proceso_codigo' => $v->proceso_codigo,
                    'id_convocatoria' => $v->id_convocatoria,
                    'convocatoria_codigo' => $v->convocatoria_codigo,
                    'convocatoria_tipo' => $v->convocatoria_tipo,
                    'id_fin_partida' => $v->id_fin_partida,
                    'fin_partida' => $v->fin_partida,
                    'id_condicion' => $v->id_condicion,
                    'condicion' => $v->condicion,
                    'categoria_relaboral' => $v->categoria_relaboral,
                    'id_da' => $v->id_da,
                    'direccion_administrativa' => $v->direccion_administrativa,
                    'organigrama_regional_id' => $v->organigrama_regional_id,
                    'organigrama_regional' => $v->organigrama_regional,
                    'id_regional' => $v->id_regional,
                    'regional' => $v->regional,
                    'regional_codigo' => $v->regional_codigo,
                    'id_departamento' => $v->id_departamento,
                    'departamento' => $v->departamento,
                    'id_provincia' => $v->id_provincia,
                    'provincia' => $v->provincia,
                    'id_localidad' => $v->id_localidad,
                    'localidad' => $v->localidad,
                    'residencia' => $v->residencia,
                    'unidad_ejecutora' => $v->unidad_ejecutora,
                    'cod_ue' => $v->cod_ue,
                    'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                    'gerencia_administrativa' => $v->gerencia_administrativa,
                    'id_departamento_administrativo' => $v->id_departamento_administrativo,
                    'departamento_administrativo' => $v->departamento_administrativo,
                    'id_organigrama' => $v->id_organigrama,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'organigrama_sigla' => $v->organigrama_sigla,
                    'organigrama_orden' => $v->organigrama_orden,
                    'id_area' => $v->id_area,
                    'area' => $v->area,
                    'id_ubicacion' => $v->id_ubicacion,
                    'ubicacion' => $v->ubicacion,
                    'unidades_superiores' => $v->unidades_superiores,
                    'unidades_dependientes' => $v->unidades_dependientes,
                    'partida' => $v->partida,
                    'fuente_codigo' => $v->fuente_codigo,
                    'fuente' => $v->fuente,
                    'organismo_codigo' => $v->organismo_codigo,
                    'organismo' => $v->organismo,
                    'relaboral_previo_id' => $v->relaboral_previo_id,
                    'observacion' => ($v->observacion != null) ? $v->observacion : "",
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'estado_abreviacion' => $v->estado_abreviacion,
                    'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                    'tiene_contrato_vigente_descripcion' => $v->tiene_contrato_vigente == 1 ? "SI" : ($v->tiene_contrato_vigente == 0 ? "NO" : ""),
                    'id_eventual' => $v->id_eventual,
                    'id_consultor' => $v->id_consultor,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                    'persona_user_reg_id' => $v->persona_user_reg_id,
                    'persona_fecha_reg' => $v->persona_fecha_reg,
                    'persona_user_mod_id' => $v->persona_user_mod_id,
                    'persona_fecha_mod' => $v->persona_fecha_mod
                );
            }
        }
        echo json_encode($relaboral);
    }

    /**
     * Función para obtener el aprobador directo, sea por que está especificado para él o para la unidad administrativa a la que pertenece.
     */
    public function getaprobadordirectopororgAction()
    {
        $this->view->disable();
        $objF = new Frelaborales();
        $relaboral = array();
        $idPersona = 0;
        $fecha = '';
        $idRelaboral = 0;
        if (isset($_POST["id_relaboral"]) && isset($_POST["id_persona"]) && isset($_POST["fecha"])) {
            $idRelaboral = $_POST["id_relaboral"];
            $idPersona = $_POST["id_persona"];
            $fecha = $_POST["fecha"];
            $objRelaboral = $objF->getOneRelaboralConsiderandoUltimaMovilidad($idRelaboral);
            $codOrganigramas = "";
            if(is_object($objRelaboral)&&$objRelaboral->gerencia_codigo!=""){
                $codOrganigramas="'".$objRelaboral->gerencia_codigo."'";
                if($objRelaboral->departamento_codigo!=""){
                    $codOrganigramas.=",'".$objRelaboral->departamento_codigo."'";
                }
                if($objRelaboral->area_codigo!=""){
                    $codOrganigramas.=",'".$objRelaboral->area_codigo."'";
                }
            }
            $arr = $objF->getIdRelaboralAprobadorDirectoConsiderandoCodigosOrganigramas($idPersona, $fecha, $idRelaboral ,$codOrganigramas);
            if (count($arr) > 0) {
                foreach ($arr as $v) {
                    $relaboral[] = array(
                        'ruta_foto' => $this->determinaRutaImagen($v->ci),
                        'id_relaboral' => $v->id_relaboral,
                        'id_persona' => $v->id_persona,
                        'p_nombre' => $v->p_nombre,
                        's_nombre' => $v->s_nombre,
                        't_nombre' => $v->t_nombre,
                        'p_apellido' => $v->p_apellido,
                        's_apellido' => $v->s_apellido,
                        'c_apellido' => $v->c_apellido,
                        'nombres' => $v->nombres,
                        'ci' => $v->ci,
                        'expd' => $v->expd,
                        'fecha_caducidad' => $v->fecha_caducidad,
                        'num_complemento' => '',
                        'fecha_nac' => $v->fecha_nac,
                        'edad' => $v->edad,
                        'lugar_nac' => $v->lugar_nac,
                        'genero' => $v->genero,
                        'e_civil' => $v->e_civil,
                        'tiene_item' => $v->tiene_item,
                        'item' => $v->item,
                        'carrera_adm' => $v->carrera_adm,
                        'num_contrato' => $v->num_contrato,
                        'contrato_numerador_estado' => $v->contrato_numerador_estado,
                        'id_solelabcontrato' => $v->id_solelabcontrato,
                        'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                        'solelabcontrato_numero' => $v->solelabcontrato_numero,
                        'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                        'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                        'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                        'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                        'fecha_ing' => $v->fecha_ing != "" ? date("d-m-Y", strtotime($v->fecha_ing)) : "",
                        'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                        'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                        'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                        'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                        'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                        'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                        'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                        'motivo_baja' => $v->motivo_baja,
                        'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                        'descripcion_baja' => $v->descripcion_baja,
                        'descripcion_anu' => $v->descripcion_anu,
                        'id_cargo' => $v->id_cargo,
                        'cargo_codigo' => $v->cargo_codigo,
                        'cargo' => $v->cargo,
                        'cargo_resolucion_ministerial_id' => $v->cargo_resolucion_ministerial_id,
                        'cargo_resolucion_ministerial' => $v->cargo_resolucion_ministerial,
                        'id_nivelessalarial' => $v->id_nivelessalarial,
                        'nivelsalarial' => $v->nivelsalarial,
                        'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                        'nivelsalarial_resolucion' => $v->nivelsalarial_resolucion,
                        'numero_escala' => $v->numero_escala,
                        'gestion_escala' => $v->gestion_escala,
                        /*'sueldo' => $v->sueldo,*/
                        'sueldo' => str_replace(".00", "", $v->sueldo),
                        'id_procesocontratacion' => $v->id_procesocontratacion,
                        'proceso_codigo' => $v->proceso_codigo,
                        'id_convocatoria' => $v->id_convocatoria,
                        'convocatoria_codigo' => $v->convocatoria_codigo,
                        'convocatoria_tipo' => $v->convocatoria_tipo,
                        'id_fin_partida' => $v->id_fin_partida,
                        'fin_partida' => $v->fin_partida,
                        'id_condicion' => $v->id_condicion,
                        'condicion' => $v->condicion,
                        'categoria_relaboral' => $v->categoria_relaboral,
                        'id_da' => $v->id_da,
                        'direccion_administrativa' => $v->direccion_administrativa,
                        'organigrama_regional_id' => $v->organigrama_regional_id,
                        'organigrama_regional' => $v->organigrama_regional,
                        'id_regional' => $v->id_regional,
                        'regional' => $v->regional,
                        'regional_codigo' => $v->regional_codigo,
                        'id_departamento' => $v->id_departamento,
                        'departamento' => $v->departamento,
                        'id_provincia' => $v->id_provincia,
                        'provincia' => $v->provincia,
                        'id_localidad' => $v->id_localidad,
                        'localidad' => $v->localidad,
                        'residencia' => $v->residencia,
                        'unidad_ejecutora' => $v->unidad_ejecutora,
                        'cod_ue' => $v->cod_ue,
                        'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                        'gerencia_administrativa' => $v->gerencia_administrativa,
                        'id_departamento_administrativo' => $v->id_departamento_administrativo,
                        'departamento_administrativo' => $v->departamento_administrativo,
                        'id_organigrama' => $v->id_organigrama,
                        'unidad_administrativa' => $v->unidad_administrativa,
                        'organigrama_sigla' => $v->organigrama_sigla,
                        'organigrama_orden' => $v->organigrama_orden,
                        'id_area' => $v->id_area,
                        'area' => $v->area,
                        'id_ubicacion' => $v->id_ubicacion,
                        'ubicacion' => $v->ubicacion,
                        'unidades_superiores' => $v->unidades_superiores,
                        'unidades_dependientes' => $v->unidades_dependientes,
                        'partida' => $v->partida,
                        'fuente_codigo' => $v->fuente_codigo,
                        'fuente' => $v->fuente,
                        'organismo_codigo' => $v->organismo_codigo,
                        'organismo' => $v->organismo,
                        'relaboral_previo_id' => $v->relaboral_previo_id,
                        'observacion' => ($v->observacion != null) ? $v->observacion : "",
                        'estado' => $v->estado,
                        'estado_descripcion' => $v->estado_descripcion,
                        'estado_abreviacion' => $v->estado_abreviacion,
                        'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                        'tiene_contrato_vigente_descripcion' => $v->tiene_contrato_vigente == 1 ? "SI" : ($v->tiene_contrato_vigente == 0 ? "NO" : ""),
                        'id_eventual' => $v->id_eventual,
                        'id_consultor' => $v->id_consultor,
                        'user_reg_id' => $v->user_reg_id,
                        'fecha_reg' => $v->fecha_reg,
                        'user_mod_id' => $v->user_mod_id,
                        'fecha_mod' => $v->fecha_mod,
                        'persona_user_reg_id' => $v->persona_user_reg_id,
                        'persona_fecha_reg' => $v->persona_fecha_reg,
                        'persona_user_mod_id' => $v->persona_user_mod_id,
                        'persona_fecha_mod' => $v->persona_fecha_mod
                    );
                }
            }
        }
        echo json_encode($relaboral);
    }

    /**
     * Función para la obtención de los registros de relación laboral correspondientes a los jefes de línea y encargados de mantenimiento.
     * Niveles intermedios que ejercen control sobre otros cargos.
     */
    public function getintermediariospordepartamentoAction()
    {
        $this->view->disable();
        $obj = new Frelaborales();
        $relaboral = Array();
        $idOrganigrama = 0;
        $palabraArea = '';
        $cargoSuperiorA = "ENCARGAD";
        $cargoSuperiorB = "JEF";
        $cargoSuperiorC = "SUPERVISOR";
        $cargoSuperiorD = "RESPONSABLE";
        if (isset($_POST["id_organigrama"]) && $_POST["id_organigrama"] > 0) {
            $idOrganigrama = $_POST["id_organigrama"];
        }
        if ($palabraArea != '')
            $where = " relaborales.estado>=1 AND (cargos.cargo LIKE ''%" . $cargoSuperiorA . "%" . $palabraArea . "%'' OR cargos.cargo LIKE ''" . $cargoSuperiorB . "%" . $palabraArea . "%'' OR OR cargos.cargo LIKE ''" . $cargoSuperiorC . "%" . $palabraArea . "%'') AND cargos.cargo NOT LIKE ''%JEF% DEPARTAMENTO%'' AND cargos.cargo NOT LIKE ''JEF% DPTO%'' ";
        else
            $where = " relaborales.estado>=1 AND (cargos.cargo LIKE ''%" . $cargoSuperiorA . "%'' OR cargos.cargo LIKE ''" . $cargoSuperiorB . "%'' OR cargos.cargo LIKE ''%" . $cargoSuperiorC . "%'' OR cargos.cargo LIKE ''" . $cargoSuperiorD . "%'') AND cargos.cargo NOT LIKE ''JEF% DEPARTAMENTO%'' AND cargos.cargo NOT LIKE ''JEF% DPTO%'' AND cargos.cargo NOT LIKE ''ENCARGAD% SEGURIDAD''  AND cargos.cargo NOT LIKE ''ENCARGAD% LIMPIEZA%''";

        $resul = $obj->getAllRelaboralesByIdOrganigramaConsiderandoUltimaMovilidad($idOrganigrama, $where);
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $relaboral[] = array(
                    'ruta_foto' => $this->determinaRutaImagen($v->ci),
                    'id_relaboral' => $v->id_relaboral,
                    'id_persona' => $v->id_persona,
                    'p_nombre' => $v->p_nombre,
                    's_nombre' => $v->s_nombre,
                    't_nombre' => $v->t_nombre,
                    'p_apellido' => $v->p_apellido,
                    's_apellido' => $v->s_apellido,
                    'c_apellido' => $v->c_apellido,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'fecha_caducidad' => $v->fecha_caducidad,
                    'num_complemento' => '',
                    'fecha_nac' => $v->fecha_nac,
                    'edad' => $v->edad,
                    'lugar_nac' => $v->lugar_nac,
                    'genero' => $v->genero,
                    'e_civil' => $v->e_civil,
                    'tiene_item' => $v->tiene_item,
                    'item' => $v->item,
                    'carrera_adm' => $v->carrera_adm,
                    'num_contrato' => $v->num_contrato,
                    'contrato_numerador_estado' => $v->contrato_numerador_estado,
                    'id_solelabcontrato' => $v->id_solelabcontrato,
                    'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                    'solelabcontrato_numero' => $v->solelabcontrato_numero,
                    'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                    'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                    'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                    'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                    'fecha_ing' => $v->fecha_ing != "" ? date("d-m-Y", strtotime($v->fecha_ing)) : "",
                    'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                    'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                    'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                    'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                    'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                    'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                    'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                    'motivo_baja' => $v->motivo_baja,
                    'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                    'descripcion_baja' => $v->descripcion_baja,
                    'descripcion_anu' => $v->descripcion_anu,
                    'id_cargo' => $v->id_cargo,
                    'cargo_codigo' => $v->cargo_codigo,
                    'cargo' => $v->cargo,
                    'cargo_resolucion_ministerial_id' => $v->cargo_resolucion_ministerial_id,
                    'cargo_resolucion_ministerial' => $v->cargo_resolucion_ministerial,
                    'id_nivelessalarial' => $v->id_nivelessalarial,
                    'nivelsalarial' => $v->nivelsalarial,
                    'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                    'nivelsalarial_resolucion' => $v->nivelsalarial_resolucion,
                    'numero_escala' => $v->numero_escala,
                    'gestion_escala' => $v->gestion_escala,
                    /*'sueldo' => $v->sueldo,*/
                    'sueldo' => str_replace(".00", "", $v->sueldo),
                    'id_procesocontratacion' => $v->id_procesocontratacion,
                    'proceso_codigo' => $v->proceso_codigo,
                    'id_convocatoria' => $v->id_convocatoria,
                    'convocatoria_codigo' => $v->convocatoria_codigo,
                    'convocatoria_tipo' => $v->convocatoria_tipo,
                    'id_fin_partida' => $v->id_fin_partida,
                    'fin_partida' => $v->fin_partida,
                    'id_condicion' => $v->id_condicion,
                    'condicion' => $v->condicion,
                    'categoria_relaboral' => $v->categoria_relaboral,
                    'id_da' => $v->id_da,
                    'direccion_administrativa' => $v->direccion_administrativa,
                    'organigrama_regional_id' => $v->organigrama_regional_id,
                    'organigrama_regional' => $v->organigrama_regional,
                    'id_regional' => $v->id_regional,
                    'regional' => $v->regional,
                    'regional_codigo' => $v->regional_codigo,
                    'id_departamento' => $v->id_departamento,
                    'departamento' => $v->departamento,
                    'id_provincia' => $v->id_provincia,
                    'provincia' => $v->provincia,
                    'id_localidad' => $v->id_localidad,
                    'localidad' => $v->localidad,
                    'residencia' => $v->residencia,
                    'unidad_ejecutora' => $v->unidad_ejecutora,
                    'cod_ue' => $v->cod_ue,
                    'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                    'gerencia_administrativa' => $v->gerencia_administrativa,
                    'id_departamento_administrativo' => $v->id_departamento_administrativo,
                    'departamento_administrativo' => $v->departamento_administrativo,
                    'id_organigrama' => $v->id_organigrama,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'organigrama_sigla' => $v->organigrama_sigla,
                    'organigrama_orden' => $v->organigrama_orden,
                    'id_area' => $v->id_area,
                    'area' => $v->area,
                    'id_ubicacion' => $v->id_ubicacion,
                    'ubicacion' => $v->ubicacion,
                    'unidades_superiores' => $v->unidades_superiores,
                    'unidades_dependientes' => $v->unidades_dependientes,
                    'partida' => $v->partida,
                    'fuente_codigo' => $v->fuente_codigo,
                    'fuente' => $v->fuente,
                    'organismo_codigo' => $v->organismo_codigo,
                    'organismo' => $v->organismo,
                    'observacion' => ($v->observacion != null) ? $v->observacion : "",
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'estado_abreviacion' => $v->estado_abreviacion,
                    'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                    'tiene_contrato_vigente_descripcion' => $v->tiene_contrato_vigente == 1 ? "SI" : ($v->tiene_contrato_vigente == 0 ? "NO" : ""),
                    'id_eventual' => $v->id_eventual,
                    'id_consultor' => $v->id_consultor,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                    'persona_user_reg_id' => $v->persona_user_reg_id,
                    'persona_fecha_reg' => $v->persona_fecha_reg,
                    'persona_user_mod_id' => $v->persona_user_mod_id,
                    'persona_fecha_mod' => $v->persona_fecha_mod,
                    'agrupador' => 0
                );
            }
        }
        echo json_encode($relaboral);
    }

    /**
     * Función para la obtención del listado de cumpleañeros.
     */
    function getcumplesAction()
    {
        $this->view->disable();
        $obj = new Frelaborales();
        $relaboral = Array();
        $fechaIni = "";
        $fechaFin = "";
        if (isset($_POST["fecha_ini"]) && $_POST["fecha_ini"] != '') {
            $fechaIni = $_POST["fecha_ini"];
        }
        if (isset($_POST["fecha_fin"]) && $_POST["fecha_fin"] != '') {
            $fechaFin = $_POST["fecha_fin"];
        }
        $arrFechaIni = explode("-", $fechaIni);
        $arrFechaFin = explode("-", $fechaFin);
        $gestionIniRango = $arrFechaIni[2];
        $gestionFinRango = $arrFechaFin[2];
        $resul = $obj->getCumples($fechaIni, $fechaFin);
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $fecha = $v->fecha_nac != "" ? date("d-m-Y", strtotime($v->fecha_nac)) : "";
                $arrFecha = explode("-", $fecha);
                if (count($arrFecha) > 0) {
                    $diaIni = $diaFin = $arrFecha[0];
                    $mesIni = $mesFin = $arrFecha[1];
                    if ($gestionIniRango != $gestionFinRango) {
                        if ($mesFin >= 11 && $mesFin <= 12) {
                            $gestionIni = $gestionIniRango;
                            $gestionFin = $gestionIniRango;
                        }
                        if ($mesFin >= 1 && $mesFin <= 2) {
                            $gestionIni = $gestionFinRango;
                            $gestionFin = $gestionFinRango;
                        }
                    } else {
                        $gestionIni = $gestionIniRango;
                        $gestionFin = $gestionIniRango;
                    }
                }
                $relaboral[] = array(
                    'nro_row' => 0,
                    'id_relaboral' => $v->id_relaboral,
                    'id_persona' => $v->id_persona,
                    'p_nombre' => $v->p_nombre,
                    's_nombre' => $v->s_nombre,
                    't_nombre' => $v->t_nombre,
                    'p_apellido' => $v->p_apellido,
                    's_apellido' => $v->s_apellido,
                    'c_apellido' => $v->c_apellido,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'fecha_caducidad' => $v->fecha_caducidad,
                    'fecha_nac' => $v->fecha_nac != "" ? date("d-m-Y", strtotime($v->fecha_nac)) : "",
                    'fecha_inicio' => $v->fecha_nac != "" ? date("d-m-Y", strtotime($diaIni . "-" . $mesIni . "-" . $gestionIni)) : "",
                    'hora_inicio' => "00:00:00",
                    'fecha_final' => $v->fecha_nac != "" ? date("d-m-Y", strtotime($diaFin . "-" . $mesFin . "-" . $gestionFin)) : "",
                    'hora_final' => "00:00:00",
                    'num_complemento' => '',
                    'edad' => $v->edad,
                    'lugar_nac' => $v->lugar_nac,
                    'genero' => $v->genero,
                    'e_civil' => $v->e_civil,
                    'tiene_item' => $v->tiene_item,
                    'item' => $v->item,
                    'carrera_adm' => $v->carrera_adm,
                    'num_contrato' => $v->num_contrato,
                    'contrato_numerador_estado' => $v->contrato_numerador_estado,
                    'id_solelabcontrato' => $v->id_solelabcontrato,
                    'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                    'solelabcontrato_numero' => $v->solelabcontrato_numero,
                    'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                    'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                    'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                    'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                    'fecha_ing' => $v->fecha_ing != "" ? date("d-m-Y", strtotime($v->fecha_ing)) : "",
                    'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                    'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                    'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                    'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                    'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                    'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                    'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                    'motivo_baja' => $v->motivo_baja,
                    'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                    'descripcion_baja' => $v->descripcion_baja,
                    'descripcion_anu' => $v->descripcion_anu,
                    'id_cargo' => $v->id_cargo,
                    'cargo_codigo' => $v->cargo_codigo,
                    'cargo' => $v->cargo,
                    'cargo_resolucion_ministerial_id' => $v->cargo_resolucion_ministerial_id,
                    'cargo_resolucion_ministerial' => $v->cargo_resolucion_ministerial,
                    'id_nivelessalarial' => $v->id_nivelessalarial,
                    'nivelsalarial' => $v->nivelsalarial,
                    'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                    'nivelsalarial_resolucion' => $v->nivelsalarial_resolucion,
                    'numero_escala' => $v->numero_escala,
                    'gestion_escala' => $v->gestion_escala,
                    /*'sueldo' => $v->sueldo,*/
                    'sueldo' => str_replace(".00", "", $v->sueldo),
                    'id_procesocontratacion' => $v->id_procesocontratacion,
                    'proceso_codigo' => $v->proceso_codigo,
                    'id_convocatoria' => $v->id_convocatoria,
                    'convocatoria_codigo' => $v->convocatoria_codigo,
                    'convocatoria_tipo' => $v->convocatoria_tipo,
                    'id_fin_partida' => $v->id_fin_partida,
                    'fin_partida' => $v->fin_partida,
                    'id_condicion' => $v->id_condicion,
                    'condicion' => $v->condicion,
                    'categoria_relaboral' => $v->categoria_relaboral,
                    'id_da' => $v->id_da,
                    'direccion_administrativa' => $v->direccion_administrativa,
                    'organigrama_regional_id' => $v->organigrama_regional_id,
                    'organigrama_regional' => $v->organigrama_regional,
                    'id_regional' => $v->id_regional,
                    'regional' => $v->regional,
                    'regional_codigo' => $v->regional_codigo,
                    'id_departamento' => $v->id_departamento,
                    'departamento' => $v->departamento,
                    'id_provincia' => $v->id_provincia,
                    'provincia' => $v->provincia,
                    'id_localidad' => $v->id_localidad,
                    'localidad' => $v->localidad,
                    'residencia' => $v->residencia,
                    'unidad_ejecutora' => $v->unidad_ejecutora,
                    'cod_ue' => $v->cod_ue,
                    'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                    'gerencia_administrativa' => $v->gerencia_administrativa,
                    'id_departamento_administrativo' => $v->id_departamento_administrativo,
                    'departamento_administrativo' => $v->departamento_administrativo,
                    'id_organigrama' => $v->id_organigrama,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'organigrama_sigla' => $v->organigrama_sigla,
                    'organigrama_orden' => $v->organigrama_orden,
                    'id_area' => $v->id_area,
                    'area' => $v->area,
                    'id_ubicacion' => $v->id_ubicacion,
                    'ubicacion' => $v->ubicacion,
                    'unidades_superiores' => $v->unidades_superiores,
                    'unidades_dependientes' => $v->unidades_dependientes,
                    'partida' => $v->partida,
                    'fuente_codigo' => $v->fuente_codigo,
                    'fuente' => $v->fuente,
                    'organismo_codigo' => $v->organismo_codigo,
                    'organismo' => $v->organismo,
                    'observacion' => ($v->observacion != null) ? $v->observacion : "",
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'estado_abreviacion' => $v->estado_abreviacion,
                    'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                    'tiene_contrato_vigente_descripcion' => $v->tiene_contrato_vigente == 1 ? "SI" : ($v->tiene_contrato_vigente == 0 ? "NO" : ""),
                    'id_eventual' => $v->id_eventual,
                    'id_consultor' => $v->id_consultor,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                    'persona_user_reg_id' => $v->persona_user_reg_id,
                    'persona_fecha_reg' => $v->persona_fecha_reg,
                    'persona_user_mod_id' => $v->persona_user_mod_id,
                    'persona_fecha_mod' => $v->persona_fecha_mod,
                    'agrupador' => 0
                );
            }
        }
        echo json_encode($relaboral);
    }

    /**
     * Función para el envío de mensaje de alerta por nueva incorporación.
     * @throws phpmailerException
     */
    public function sendmessageAction()
    {
        $this->view->disable();
        $hoy = date("Y-m-d H:i:s");
        $search = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú");
        $replace = array("&acute;", "&eacute;", "&iacute;", "&oacute;", "&uacute;", "&Aacute;", "&Eacute;", "&Iacute;", "&Oacute;", "&Uacute;");
        $auth = $this->session->get('auth');
        $user_reg_id = $user_mod_id = $auth['id'];
        $idRelaboral = 0;
        $msj = array();
        $mensajeAdicional = "";
        if (isset($_POST["msj"])) {
            $mensajeAdicional = $_POST["msj"];
        }
        if (isset($_POST["id"]) && $_POST["id"] > 0 && isset($_POST["operacion"]) && $_POST["operacion"] >= 0) {
            $obj = new Frelaborales();
            $operacion = $_POST["operacion"];
            $arrIdRelaborales = array();
            $idRelaboral = $_POST["id"];
            $relaboralOperacion = $obj->getOneRelaboralConsiderandoUltimaMovilidad($_POST["id"]);
            if (is_object($relaboralOperacion)) {
                $mensajeCabecera = "Estimad@s compa&ntilde;er@s:<br>";
                if ($operacion == 1) {
                    $mensajeCabecera .= "Se ha registrado la incorporaci&oacute;n de una nueva persona a la empresa bajo el siguiente detalle: ";
                } else {
                    $mensajeCabecera .= "Se ha registrado la desvinculaci&oacute;n de una persona en la empresa de acuerdo al siguiente detalle: ";
                }
                $mensajePie = "Atte.,<br>";
                $mensajePie .= "Direcci&oacute;n <br>";
                $mensajePie .= "Vias Bolivia</b><br>";
                $parUser = Parametros::findFirst(array("parametro LIKE 'USUARIO_CORREO_RRHH' AND nivel LIKE 'USUARIO' AND estado=1 AND baja_logica=1"));
                $userMail = '';
                if (is_object($parUser)) {
                    $userMail = $parUser->valor_1;
                }
                $parPass = Parametros::findFirst(array("parametro LIKE 'USUARIO_CORREO_RRHH' AND nivel LIKE 'PASSWORD' AND estado=1 AND baja_logica=1"));
                $passMail = '';
                if (is_object($parPass)) {
                    $passMail = $parPass->valor_1;
                }
                $parHost = Parametros::findFirst(array("parametro LIKE 'USUARIO_CORREO_RRHH' AND nivel LIKE 'HOST' AND estado=1 AND baja_logica=1"));
                $hostMail = '';
                if (is_object($parHost)) {
                    $hostMail = $parHost->valor_1;
                }
                $parPort = Parametros::findFirst(array("parametro LIKE 'USUARIO_CORREO_RRHH' AND nivel LIKE 'PORT' AND estado=1 AND baja_logica=1"));
                $portMail = '';
                if (is_object($parPort)) {
                    $portMail = $parPort->valor_1;
                }
                $destinatarios = Parametros::find("parametro LIKE 'RELABOLARES_NOTIFICACION_POR_NUEVA_INCORPORACION' AND estado=1 AND baja_logica=1");
                if ($destinatarios->count() > 0) {
                    $mensaje = $this->getBodyMessage($relaboralOperacion, $operacion, $mensajeCabecera, $mensajePie, $mensajeAdicional);
                    if ($userMail != '' && $passMail != '' && $hostMail != '' && $portMail != '') {
                        $mail = new phpmaileroasis();
                        $mail->IsSMTP();
                        $mail->SMTPAuth = true;
                        $mail->SMTPSecure = "ssl";
                        $mail->Host = $hostMail;
                        $mail->Port = $portMail;
                        $mail->Username = $userMail;
                        $mail->Password = $passMail;
                        $mail->From = $userMail;
                        $mail->FromName = "Sistema de Recursos Humanos";
                        if ($operacion == 1) {
                            $operacionSolicitada = "NUEVA INCORPORACIÓN";
                        } else {
                            $operacionSolicitada = "DESVINCULACIÓN DE PERSONAL";
                        }
                        $mail->Subject = utf8_decode($operacionSolicitada);
                        $mail->MsgHTML(utf8_decode($mensaje));
                        if ($destinatarios->count() > 0) {
                            foreach ($destinatarios as $dest) {
                                $arrIdRelaborales[] = intval($dest->valor_1);
                                if ($dest->agrupador == 1) {
                                    $mail->AddAddress($dest->valor_4, $dest->valor_2);
                                } else {
                                    $mail->AddCC($dest->valor_4, $dest->valor_2);
                                }
                            }
                            $mail->IsHTML(true);
                            $arr = $obj->getInmediatoSuperiorRelaboral($idRelaboral);
                            if ($arr->count() > 0) {
                                $objInmediatoSuperior = $arr[0];
                                if (is_object($objInmediatoSuperior) && !in_array($objInmediatoSuperior->id_relaboral, $arrIdRelaborales)) {
                                    $pc = Personascontactos::findFirst(array("persona_id=" . $objInmediatoSuperior->id_persona . " AND e_mail_inst IS NOT NULL  AND e_mail_inst !=''"));
                                    if (is_object($pc)) {
                                        $mail->AddAddress($pc->e_mail_inst, ucfirst(strtolower($objInmediatoSuperior->p_nombre)) . " " . ucfirst(strtolower($objInmediatoSuperior->p_apellido)));
                                    }
                                }
                            }
                            $mail->smtpConnect([
                                'ssl' => [
                                    'verify_peer' => false,
                                    'verify_peer_name' => false,
                                    'allow_self_signed' => true
                                ]
                            ]);
                            if($operacion!=1){
                                #region Deshabilitación de acceso del usuario
                                $objUsuarios = Usuarios::findFirstByPersonaId($relaboralOperacion->id_persona);
                                if(is_object($objUsuarios)){
                                    $objUsuarios->habilitado = 0;
                                    $objUsuarios->user_desh_id = $user_mod_id;
                                    $objUsuarios->fecha_desh = $hoy;
                                    $objUsuarios->user_mod_id = $user_mod_id;
                                    $objUsuarios->fecha_mod = $hoy;
                                    $objUsuarios->save();
                                }
                                #endregion
                            }
                            if ($mail->Send()) {
                                $msj = array('result' => 1, 'msj' => 'Env&iaacute;o exitoso del aviso de ' . $operacionSolicitada . '.');
                            } else {
                                $msj = array('result' => 0, 'msj' => 'Error, no se pudo enviar el aviso de ' . $operacionSolicitada . '.');
                            }
                        }
                    } else {
                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico, no se pudo enviar el correo por fallo en envío de datos.');
                    }
                } else {
                    $msj = array('result' => -2, 'msj' => 'Error cr&iacute;tico, no se pudo enviar el correo por fallo en envío de datos.');
                }
            } else {
                $msj = array('result' => -3, 'msj' => 'Error cr&iacute;tico, no se pudo enviar el correo por fallo en envío de datos.');
            }
        } else {
            $msj = array('result' => -4, 'msj' => 'Error cr&iacute;tico, no se pudo enviar el correo por fallo en envío de datos para la operaci&oacute;n solicitada.');
        }
        echo json_encode($msj);
    }

    /**
     * Función para armar el cuerpo del mensaje.
     * @param $relaboral
     * @param $tipoEnvio
     * @param $mensajeCabecera
     * @param $mensajePie
     * @param string $mensajeAdicional
     * @return string
     */
    public function getBodyMessage($relaboral, $operacion, $mensajeCabecera, $mensajePie, $mensajeAdicional = '')
    {
        $cuerpo = '<html>';
        $cuerpo .= '<head>';
        if ($operacion == 1) {
            $cuerpo .= '<title>Aviso Nueva Incorporación</title>';
        } else {
            $cuerpo .= '<title>Aviso Desvinculación de Personal</title>';
        }
        $cuerpo .= '<style type="text/css">';
        $cuerpo .= '#datos {';
        $cuerpo .= 'position:relative;';
        $cuerpo .= 'width:780px;';
        $cuerpo .= 'left: 164px;';
        $cuerpo .= 'top: 316px;';
        $cuerpo .= 'text-align: center;';
        $cuerpo .= '} ';
        $cuerpo .= '#apDiv1 #form1 table tr td {';
        $cuerpo .= 'text-align: center;';
        $cuerpo .= 'font-weight: bold;';
        $cuerpo .= '} ';
        $cuerpo .= '#apDiv2 {';
        $cuerpo .= 'position:relative;';
        $cuerpo .= 'width:49px;';
        $cuerpo .= 'height:45px;';
        $cuerpo .= 'z-index:2;';
        $cuerpo .= 'left: 12px;';
        $cuerpo .= 'top: 11px;';
        $cuerpo .= '} ';
        $cuerpo .= '#apDiv1 #notificacion table tr td {';
        $cuerpo .= 'text-align: center;';
        $cuerpo .= '} ';
        $cuerpo .= '#apDiv1 #notificacion table tr td {';
        $cuerpo .= 'text-align: left;';
        $cuerpo .= '} ';
        $cuerpo .= '#apDiv1 #notificacion table tr td {';
        $cuerpo .= 'text-align: center;';
        $cuerpo .= 'font-family: Arial, Helvetica, sans-serif;';
        $cuerpo .= '} ';
        $cuerpo .= '#apDiv3 {';
        $cuerpo .= 'position:relative;';
        $cuerpo .= 'width:833px;';
        $cuerpo .= 'height:115px;';
        $cuerpo .= 'z-index:1;';
        $cuerpo .= 'left: 99px;';
        $cuerpo .= 'text-align: center;';
        $cuerpo .= 'top: 16px;';
        $cuerpo .= '} ';

        $cuerpo .= '#divCabeceraMensaje {';
        $cuerpo .= 'position:relative;';
        $cuerpo .= '} ';
        $cuerpo .= '#divPieMensaje {';
        $cuerpo .= 'position:relative;';
        $cuerpo .= '} ';

        //$cuerpo .= '-->';
        $cuerpo .= '</style>';
        $cuerpo .= '</head>';
        $cuerpo .= '<body>';
        $cuerpo .= '<div id="divCabeceraMensaje">';
        $cuerpo .= $mensajeCabecera;
        $cuerpo .= '</div>';
        $cuerpo .= '<div id="apDiv3">';
        $cuerpo .= '<table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#3085ff">';
        $cuerpo .= '<tr>';
        $cuerpo .= '<td><table width="100%" border="0">';
        $cuerpo .= '<tr>';
        $cuerpo .= '<td>';
        if ($operacion == 1) {
            $cuerpo .= '<p style="font-family: Helvetica LT Condensed; color: #3085ff; font-weight: bold; font-size: 15px; text-align: center;">INCORPORACIÓN DE NUEVO PERSONAL </p></td>';
        } else {
            $cuerpo .= '<p style="font-family: Helvetica LT Condensed; color: #3085ff; font-weight: bold; font-size: 15px; text-align: center;">DESVINCULACIÓN DE PERSONAL </p></td>';
        }
        $cuerpo .= '</tr>';
        $cuerpo .= '<tr>';
        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">Nombre:</span>&nbsp; ' . $relaboral->nombres . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
        $cuerpo .= '</tr>';
        $cuerpo .= '<tr>';
        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">C. I.:</span>&nbsp; ' . $relaboral->ci . ' ' . trim($relaboral->expd) . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
        $cuerpo .= '</tr>';
        $cuerpo .= '<tr>';
        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">Cargo:</span>&nbsp; ' . $relaboral->cargo . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
        $cuerpo .= '</tr>';
        if ($relaboral->departamento_administrativo != '') {
            $cuerpo .= '<tr>';
            $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">Departamento:</span>&nbsp; ' . $relaboral->departamento_administrativo . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
            $cuerpo .= '</tr>';
        }
        $cuerpo .= '<tr>';
        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">Gerencia:</span>&nbsp; ' . $relaboral->gerencia_administrativa . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
        $cuerpo .= '</tr>';
        if ($operacion == 1) {
            $fechaIncor = $relaboral->fecha_incor != "" ? date("d-m-Y", strtotime($relaboral->fecha_incor)) : "";
            $cuerpo .= '<tr>';
            $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">Fecha Incorporaci&oacute;n:</span>&nbsp; ' . $fechaIncor . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
            $cuerpo .= '</tr>';
        } else {
            $fechaBaja = $relaboral->fecha_baja != "" ? date("d-m-Y", strtotime($relaboral->fecha_baja)) : "";
            $cuerpo .= '<tr>';
            $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">Fecha Baja:</span>&nbsp; ' . $fechaBaja . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
            $cuerpo .= '</tr>';
        }
        $fechaYHoraEnvio = date("d-m-Y H:i:s");
        $cuerpo .= '<tr>';
        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Fecha y Hora de Env&iacute;o (Actual Mensaje):</span>&nbsp; ' . $fechaYHoraEnvio . '</td>';
        $cuerpo .= '</tr>';
        if ($mensajeAdicional != '') {
            $cuerpo .= '<tr>';
            $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Mensaje Adicional:</span>&nbsp; ' . $mensajeAdicional . '</td>';
            $cuerpo .= '</tr>';
        }
        $cuerpo .= '</table></td>';
        $cuerpo .= '</tr>';
        $cuerpo .= '</table></td></tr></table></div></br><div id="divPieMensaje"></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>' . $mensajePie . '</div>';
        $cuerpo .= '</body></html>';
        return $cuerpo;
    }

    /**
     * Función para obtener la lista de destinatarios habilitados.
     */
    public function getdestinatariosAction()
    {
        $this->view->disable();
        $destinatarios = Array();
        $arrIdDestinatarios = Array();
        $idRelaboral = 0;
        if (isset($_POST["id"])) {
            $idRelaboral = $_POST["id"];
        }
        if ($idRelaboral > 0) {
            $resul = Parametros::find(array("parametro LIKE 'RELABOLARES_NOTIFICACION_POR_NUEVA_INCORPORACION' AND estado=1 AND agrupador=1 ORDER BY CAST(nivel AS INTEGER)"));
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $arrIdDestinatarios[] = intval($v->valor_1);
                    $destinatarios[] = array(
                        'id' => $v->valor_1,
                        'nombres' => $v->valor_2,
                        'cargo' => $v->valor_3
                    );
                }
            }
            $fr = new Frelaborales();
            try {
                $rs = $fr->getJefeInmediatoSuperiorRelaboral($idRelaboral);
                if (count($rs) > 0) {
                    $rel = $rs[0];
                    if (!in_array($rel->id_persona, $arrIdDestinatarios)) {
                        $destinatarios[] = array(
                            'id' => $rel->id_persona,
                            'nombres' => ucfirst(strtolower($rel->p_nombre)) . " " . ucfirst(strtolower($rel->p_apellido)),
                            'cargo' => $rel->cargo
                        );
                    }
                }

            } catch (\Exception $e) {
                /*echo get_class($e), ": ", $e->getMessage(), "\n";
                echo " File=", $e->getFile(), "\n";
                echo " Line=", $e->getLine(), "\n";
                echo $e->getTraceAsString();*/
            }
        }
        echo json_encode($destinatarios);
    }

    /**
     * Función para el registro de las columnas visibles para el listado principal.
     */
    public function saveviewcolsAction()
    {
        $auth = $this->session->get('auth');
        $idUsuario = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        if (isset($_POST["divgrilla_id"]) && $_POST["divgrilla_id"] != '' && isset($_POST["indexes"])) {
            $jsonIndexes = null;
            if ($_POST["indexes"] != '') {
                $arrIndexes = explode(",", $_POST["indexes"]);
                $jsonIndexes = json_encode($arrIndexes);
            }
            $objColumnasVisibles = Columnasvisibles::findFirst(array("divgrilla_id='" . $_POST["divgrilla_id"] . "' AND user_id=" . $idUsuario));
            if (!is_object($objColumnasVisibles)) {
                $objColumnasVisibles = new Columnasvisibles();
                $objColumnasVisibles->user_id = $idUsuario;
                $objColumnasVisibles->divgrilla_id = $_POST["divgrilla_id"];
            }
            $objColumnasVisibles->indexes = $jsonIndexes;
            if ($objColumnasVisibles->save()) {
                $msj = array('result' => 1, 'msj' => '&Eacute;xito: Registro de la configuraci&oacute;n de vista de columnas.');
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: No se pudo registrar la configuraci&oacute;n de vistas de columnas.');
            }
        } else {
            $msj = array('result' => -4, 'msj' => 'Error Cr&iacute;tico: Los datos enviados son insuficientes.');
        }
        echo json_encode($msj);
    }

    /**
     * Función para el cálculo del tiempo transcurrido entre dos fechas.
     * @param $fechaInicio
     * @param $fechaFin
     * @return string
     */
    function tiempoTranscurridoFechas($fechaInicio, $fechaFin)
    {
        $fecha1 = new DateTime($fechaInicio);
        $fecha2 = new DateTime($fechaFin);
        $fecha = $fecha1->diff($fecha2);
        $tiempo = "";

        //años
        if ($fecha->y > 0) {
            $tiempo .= $fecha->y;

            if ($fecha->y == 1)
                $tiempo .= " año, ";
            else
                $tiempo .= " años, ";
        }

        //meses
        if ($fecha->m > 0) {
            $tiempo .= $fecha->m;

            if ($fecha->m == 1)
                $tiempo .= " mes, ";
            else
                $tiempo .= " meses, ";
        }

        //dias
        if ($fecha->d > 0) {
            $tiempo .= $fecha->d;

            if ($fecha->d == 1)
                $tiempo .= " día, ";
            else
                $tiempo .= " días, ";
        }

        //horas
        if ($fecha->h > 0) {
            $tiempo .= $fecha->h;

            if ($fecha->h == 1)
                $tiempo .= " hora, ";
            else
                $tiempo .= " horas, ";
        }

        //minutos
        if ($fecha->i > 0) {
            $tiempo .= $fecha->i;

            if ($fecha->i == 1)
                $tiempo .= " minuto, ";
            else
                $tiempo .= " minutos, ";
        }
        //segundos
        if ($fecha->s > 0) {
            $tiempo .= $fecha->s;
            if ($fecha->s == 1)
                $tiempo .= " segundo, ";
            else
                $tiempo .= " segundos, ";
        }
        $tiempo = trim($tiempo);
        $tiempo .= ",";
        $tiempo = str_replace(",,", "", $tiempo);
        return $tiempo;
    }

    /**
     * Función para la obtención del listado de datos relacionados a la antiguedad de trabajo por parte de la persona solicitada.
     */
    function getantiguedadbypersonAction(){
        $this->view->disable();
        $idPersona = $_POST["id"];
        $relAntiguedad = array();
        if($idPersona>0){
            $objPersonaAntiguedad = new Frelaborales();
            $relAntiguedad = $objPersonaAntiguedad->getOneByAntiguedad($idPersona);
        }
        echo json_encode($relAntiguedad);
    }

    /**
     * Función para la obtención del registro relaboral con la información de antiguedad para una determinada persona.
     */
    function getoneforvacAction(){
        $this->view->disable();
        $idPersona = $_POST["id"];
        $relaborales = array();
        if($idPersona>0){
            $objPersonaAntiguedad = new Frelaborales();
            $resul = $objPersonaAntiguedad->getPaged(0,2,0,$idPersona);
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $total_rows = $v->total_rows;
                    $relaborales = array(
                        'nro_row' => 0,
                        'id_relaboral' => $v->id_relaboral,
                        'id_persona' => $v->id_persona,
                        'p_nombre' => $v->p_nombre,
                        's_nombre' => $v->s_nombre,
                        't_nombre' => $v->t_nombre,
                        'p_apellido' => $v->p_apellido,
                        's_apellido' => $v->s_apellido,
                        'c_apellido' => $v->c_apellido,
                        'nombres' => $v->nombres,
                        'ci' => $v->ci,
                        'expd' => $v->expd,
                        'fecha_caducidad' => $v->fecha_caducidad,
                        'num_complemento' => '',
                        'fecha_nac' => $v->fecha_nac != "" ? date("d-m-Y", strtotime($v->fecha_nac)) : "",
                        'fecha_cumple' => $v->fecha_cumple != "" ? date("d-m-Y", strtotime($v->fecha_cumple)) : "",
                        'edad' => $v->edad,
                        'lugar_nac' => $v->lugar_nac,
                        'genero' => $v->genero,
                        'e_civil' => $v->e_civil,
                        'grupo_sanguineo' => $v->grupo_sanguineo,
                        'tiene_item' => $v->tiene_item,
                        'item' => $v->item,
                        'carrera_adm' => $v->carrera_adm,
                        'num_contrato' => $v->num_contrato,
                        'contrato_numerador_estado' => $v->contrato_numerador_estado,
                        'id_solelabcontrato' => $v->id_solelabcontrato,
                        'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                        'solelabcontrato_numero' => $v->solelabcontrato_numero,
                        'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                        'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                        'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                        'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                        'fecha_ing' => $v->fecha_ing != "" ? date("d-m-Y", strtotime($v->fecha_ing)) : "",
                        'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                        'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                        'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                        'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                        'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                        'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                        'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                        'motivo_baja' => $v->motivo_baja,
                        'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                        'descripcion_baja' => $v->descripcion_baja,
                        'descripcion_anu' => $v->descripcion_anu,
                        'id_cargo' => $v->id_cargo,
                        'cargo_codigo' => $v->cargo_codigo,
                        'cargo' => $v->cargo,
                        'cargo_resolucion_ministerial_id' => $v->cargo_resolucion_ministerial_id,
                        'cargo_resolucion_ministerial' => $v->cargo_resolucion_ministerial,
                        'cargo_gestion' => $v->cargo_gestion,
                        'cargo_correlativo' => $v->cargo_correlativo,
                        'id_nivelessalarial' => $v->id_nivelessalarial,
                        'nivelsalarial' => $v->nivelsalarial,
                        'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                        'nivelsalarial_resolucion' => $v->nivelsalarial_resolucion,
                        'numero_escala' => $v->numero_escala,
                        'gestion_escala' => $v->gestion_escala,
                        /*'sueldo' => $v->sueldo,*/
                        'sueldo' => str_replace(".00", "", $v->sueldo),
                        'id_procesocontratacion' => $v->id_procesocontratacion,
                        'proceso_codigo' => $v->proceso_codigo,
                        'id_convocatoria' => $v->id_convocatoria,
                        'convocatoria_codigo' => $v->convocatoria_codigo,
                        'convocatoria_tipo' => $v->convocatoria_tipo,
                        'id_fin_partida' => $v->id_fin_partida,
                        'fin_partida' => $v->fin_partida,
                        'id_condicion' => $v->id_condicion,
                        'condicion' => $v->condicion,
                        'categoria_relaboral' => $v->categoria_relaboral,
                        'id_da' => $v->id_da,
                        'direccion_administrativa' => $v->direccion_administrativa,
                        'organigrama_regional_id' => $v->organigrama_regional_id,
                        'organigrama_regional' => $v->organigrama_regional,
                        'id_regional' => $v->id_regional,
                        'regional' => $v->regional,
                        'regional_codigo' => $v->regional_codigo,
                        'id_departamento' => $v->id_departamento,
                        'departamento' => $v->departamento,
                        'id_provincia' => $v->id_provincia,
                        'provincia' => $v->provincia,
                        'id_localidad' => $v->id_localidad,
                        'localidad' => $v->localidad,
                        'residencia' => $v->residencia,
                        'unidad_ejecutora' => $v->unidad_ejecutora,
                        'cod_ue' => $v->cod_ue,
                        'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                        'gerencia_administrativa' => $v->gerencia_administrativa,
                        'id_departamento_administrativo' => $v->id_departamento_administrativo,
                        'departamento_administrativo' => $v->departamento_administrativo,
                        'id_organigrama' => $v->id_organigrama,
                        'unidad_administrativa' => $v->unidad_administrativa,
                        'organigrama_sigla' => $v->organigrama_sigla,
                        'organigrama_orden' => $v->organigrama_orden,
                        'id_area' => $v->id_area,
                        'area' => $v->area,
                        'id_ubicacion' => $v->id_ubicacion,
                        'ubicacion' => $v->ubicacion,
                        'unidades_superiores' => $v->unidades_superiores,
                        'unidades_dependientes' => $v->unidades_dependientes,
                        'partida' => $v->partida,
                        'fuente_codigo' => $v->fuente_codigo,
                        'fuente' => $v->fuente,
                        'organismo_codigo' => $v->organismo_codigo,
                        'organismo' => $v->organismo,
                        'relaboral_previo_id' => $v->relaboral_previo_id,
                        'observacion' => ($v->observacion != null) ? $v->observacion : "",
                        'estado' => $v->estado,
                        'estado_descripcion' => $v->estado_descripcion,
                        'estado_abreviacion' => $v->estado_abreviacion,
                        'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                        'tiene_contrato_vigente_descripcion' => $v->tiene_contrato_vigente == 1 ? "SI" : ($v->tiene_contrato_vigente == 0 ? "NO" : ""),
                        'id_eventual' => $v->id_eventual,
                        'id_consultor' => $v->id_consultor,
                        'user_reg_id' => $v->user_reg_id,
                        'fecha_reg' => $v->fecha_reg != "" ? date("d-m-Y", strtotime($v->fecha_reg)) : "",
                        'user_mod_id' => $v->user_mod_id,
                        'fecha_mod' => $v->fecha_mod != "" ? date("d-m-Y", strtotime($v->fecha_mod)) : "",
                        'persona_user_reg_id' => $v->persona_user_reg_id,
                        'persona_fecha_reg' => $v->persona_fecha_reg != "" ? date("d-m-Y", strtotime($v->persona_fecha_reg)) : "",
                        'persona_user_mod_id' => $v->persona_user_mod_id,
                        'persona_fecha_mod' => $v->persona_fecha_mod != "" ? date("d-m-Y", strtotime($v->persona_fecha_mod)) : "",
                        'id_presentaciondoc' => $v->id_presentaciondoc,
                        'interno_inst' => $v->interno_inst,
                        'celular_per' => $v->celular_per,
                        'celular_inst' => $v->celular_inst,
                        'e_mail_per' => $v->e_mail_per,
                        'e_mail_inst' => $v->e_mail_inst,
                        'cas_fecha_emi' => $v->cas_fecha_emi != "" ? date("d-m-Y", strtotime($v->cas_fecha_emi)) : "",
                        'cas_fecha_pres' => $v->cas_fecha_pres != "" ? date("d-m-Y", strtotime($v->cas_fecha_pres)) : "",
                        'cas_fecha_fin_cal' => $v->cas_fecha_fin_cal != "" ? date("d-m-Y", strtotime($v->cas_fecha_fin_cal)) : "",
                        'cas_numero' => $v->cas_numero,
                        'cas_codigo_verificacion' => $v->cas_codigo_verificacion,
                        'cas_anios' => $v->cas_anios,
                        'cas_meses' => $v->cas_meses,
                        'cas_dias' => $v->cas_dias,
                        'fecha_corte' => $v->fecha_corte != "" ? date("d-m-Y", strtotime($v->fecha_corte)) : "",
                        'mt_anios' => $v->mt_anios,
                        'mt_meses' => $v->mt_meses,
                        'mt_dias' => $v->mt_dias,
                        'tot_anios' => $v->tot_anios,
                        'tot_meses' => $v->tot_meses,
                        'tot_dias' => $v->tot_dias,
                        'mt_fin_mes_anios' => $v->mt_fin_mes_anios,
                        'mt_fin_mes_meses' => $v->mt_fin_mes_meses,
                        'mt_fin_mes_dias' => $v->mt_fin_mes_dias,
                        'fecha_act' => $v->fecha_act != "" ? date("d-m-Y", strtotime($v->fecha_act)) : "",
                        'mt_prox_fecha' => $v->mt_prox_fecha != "" ? date("d-m-Y", strtotime($v->mt_prox_fecha)) : "",
                        'mt_prox_fecha_ant' => $v->mt_prox_fecha_ant != "" ? date("d-m-Y", strtotime($v->mt_prox_fecha_ant)) : "",
                        'mt_prox_gestion' => $v->mt_prox_gestion,
                        'mt_prox_anios' => $v->mt_prox_anios,
                        'mt_prox_meses' => $v->mt_prox_meses,
                        'mt_prox_dias' => $v->mt_prox_dias,
                    );
                    $msj = array("result"=>1, "msj"=>"&Eacute;xito: Registro hallado.", "data"=>$relaborales);
                }
            }else{
                $msj = array("result"=>0, "msj"=>"No se hall&oacute; registro activo de la persona solicitada.", "data"=>$relaborales);
            }
        }else{
            $msj = array("result"=>-1, "msj"=>"Error: Datos incompletos.", "data"=>$relaborales);
        }
        echo json_encode($msj);
    }
}