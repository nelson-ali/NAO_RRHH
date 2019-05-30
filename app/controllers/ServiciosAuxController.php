<?php

/**
 *   RRHH - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  10-06-2016
 *   Fecha Actualización:  02-05-2018
 */
class ServiciosAuxController extends ControllerBaseOut
{

    public function indexAction()
    {
        $this->view->disable();
        header('Content-Type: application/JSON');
        if ($this->request->isGet()) {
            $ws = new Serviciosweb();
            $clave = '';
            if ($ws->validacion($clave)) {
                $msj = array('result' => 1, 'msj' => 'Usuario autorizado');
            } else {
                $msj = array('result' => 0, 'msj' => 'Usuario no autorizado');
            }
            echo json_encode($msj, JSON_PRETTY_PRINT);
        }
    }

    /**
     * Función para el logueo de ingreso al sistema de marcaciones móviles.
     * @param string $usuario
     * @param string $password
     * @param int $modalidad
     */
    public function loginAction()
    {
        $this->view->disable();
        header('Content-Type: application/JSON');
        $ok = false;
        $msj = array();
        $hoy = date("Y-m-d H:i:s");
        if ($this->request->isPost()) {
            $username = "";
            if ($this->request->getPost("username") != "") {
                $username = $this->request->getPost("username");
            }
            $password = "";
            if ($this->request->getPost("password") != "") {
                $password = $this->request->getPost("password");
            }
            $imei = "";
            if ($this->request->getPost("imei") != "") {
                $imei = $this->request->getPost("imei");
            }
            if ($username != "" && $password != "" && $imei != "") {
                $modalidad = 0;
                $objPrmTag = Parametros::findFirst("parametro LIKE 'ACCESS_KEY' AND nivel LIKE 'A' AND estado>0 AND baja_logica = 1");
                $username = trim(strtolower(str_replace("@viasbolivia.gob.bo", "", $username)));
                $tag = $objPrmTag->valor_1;
                /*$password = hash_hmac('sha256', $password, '2, 4, 6, 7, 9, 15, 20, 23, 25, 30');*/
                $password = hash_hmac('sha256', $password, $tag);
                $user = Usuarios::findFirst(
                    array(
                        "username = :usuario: AND password = :password: AND habilitado= :estado:",
                        "bind" => array('usuario' => $username, 'password' => $password, 'estado' => 1)
                    ));
                if ($user != false) {
                    $user->logins = (($user->logins == null) ? 0 : $user->logins) + 1;
                    /**
                     * Añadir columna para el almacenamiento del último acceso a través de equipos móviles.
                     */
                    $user->last_login_ws = date("Y-m-d H:i:s");
                    $relaboral = Relaborales::findFirst(
                        array(
                            "persona_id=:id_persona: AND estado=:estado: AND baja_logica=:baja_logica:",
                            "bind" => array('id_persona' => $user->persona_id, 'estado' => 1, 'baja_logica' => 1))
                    );
                    if (is_object($relaboral)) {
                        $ok = $user->save();
                        if ($modalidad == 0) {
                            $Modalidadnivel = Modalidadnivel::findFirst(
                                array(
                                    "usuario_id = :id_usuario: AND modalidad = :modalidad: AND estado= :estado: AND baja_logica= :baja_logica: ",
                                    "bind" => array('id_usuario' => $user->id, 'modalidad' => $modalidad, 'estado' => 1, 'baja_logica' => 1)
                                )
                            );
                            if ($Modalidadnivel != false) {
                                $user->nivel = $Modalidadnivel->nivel;
                            } else {
                                $Modalidadnivel = new Modalidadnivel();
                                $Modalidadnivel->usuario_id = $user->id;
                                $Modalidadnivel->modalidad = 0;
                                $Modalidadnivel->nivel = 11;
                                $Modalidadnivel->estado = 1;
                                $Modalidadnivel->baja_logica = 1;
                                $ok = $Modalidadnivel->save();
                                if ($ok) {
                                    $user->nivel = $Modalidadnivel->nivel;
                                }
                            }
                        } else {
                            if ($user->nivel == 0) {
                                $ok = false;
                            }
                        }
                    }
                    if ($ok) {
                        $userService = Usuariosservicios::findFirst(array("usuario_id=:id_usuario:",
                            "bind" => array('id_usuario' => $user->id)));
                        $objPrm = Parametros::findFirst("parametro LIKE 'SERVICE_KEY' AND nivel LIKE 'A' AND estado>0 AND baja_logica = 1");
                        $key = $objPrm->valor_1;
                        $algoritmo = "sha256";
                        if (is_object($userService)) {
                            if ($userService->estado > 0 && $userService->baja_logica == 1) {
                                /**
                                 * El usuario está autorizado para el consumo de servicios web.
                                 */
                                $idRolServicios = 3;
                                $rs = Rolesservicios::findFirstById($idRolServicios);
                                $us = new Usuariosservicios();
                                $token = $us->generaToken($algoritmo, $imei . "." . $hoy, $key);
                                $inicioToken = $us->generaToken($algoritmo, $hoy, $key);
                                $fechaHoraFin = $us->sumaFechasHoras($hoy, $rs->dias_limite, $rs->horas_limite, $rs->minutos_limite, $rs->segundos_limite);
                                $finToken = $us->generaToken($algoritmo, $fechaHoraFin, $key);
                                $userService->imei = $imei;
                                $userService->token = $token;
                                $userService->iat = $inicioToken;
                                $userService->exp = $finToken;
                                $userService->rolservicios_id = $idRolServicios;
                                $userService->dias_limite = $rs->dias_limite;
                                $userService->horas_limite = $rs->horas_limite;
                                $userService->minutos_limite = $rs->minutos_limite;
                                $userService->segundos_limite = $rs->segundos_limite;
                                $userService->last_login = $hoy;
                                if ($userService->save()) {
                                    $objRel = new Frelaborales();
                                    $frel = $objRel->getOneRelaboralConsiderandoUltimaMovilidad($relaboral->id);
                                    $data = array(
                                        "id_relaboral" => $relaboral->id,
                                        "id_persona" => $relaboral->persona_id,
                                        "nombres" => $frel->nombres,
                                        "p_nombre" => $frel->p_nombre,
                                        "s_nombre" => $frel->s_nombre,
                                        "t_nombre" => $frel->t_nombre,
                                        "p_apellido" => $frel->p_apellido,
                                        "s_apellido" => $frel->s_apellido,
                                        "c_apellido" => $frel->c_apellido,
                                        "ci" => $frel->ci,
                                        "expd" => $frel->expd,
                                        "fecha_nac" => $frel->fecha_nac,
                                        "genero" => $frel->genero
                                    );
                                    $objToken = array(
                                        "token" => $token,
                                        "iat" => $inicioToken,
                                        "exp" => $finToken,
                                        "data" => $data,
                                    );
                                    $msj = array('result' => 1, 'msj' => 'Usuario autorizado.', 'token' => $objToken);
                                } else {
                                    $msj = array('result' => 0, 'msj' => 'Error: No se pudo hallar el registro del usuario correspondiente.');
                                }
                            } else {
                                $msj = array('result' => -1, 'msj' => 'Usuario no autorizado para servicios web.');
                            }
                        } else {
                            /**
                             * Nuevo registro
                             */
                            $idRolServicios = 3;
                            $rs = Rolesservicios::findFirstById($idRolServicios);
                            $us = new Usuariosservicios();
                            $token = $us->generaToken($algoritmo, $imei . "." . $hoy, $key);
                            $inicioToken = $us->generaToken($algoritmo, $hoy, $key);
                            $fechaHoraFin = $us->sumaFechasHoras($hoy, $rs->dias_limite, $rs->horas_limite, $rs->minutos_limite, $rs->segundos_limite);
                            $finToken = $us->generaToken($algoritmo, $fechaHoraFin, $key);
                            $userService = new Usuariosservicios();
                            $userService->usuario_id = $user->id;
                            $userService->imei = $imei;
                            $userService->token = $token;
                            $userService->iat = $inicioToken;
                            $userService->exp = $finToken;
                            $userService->rolservicios_id = $idRolServicios;
                            $userService->estado = 1;
                            $userService->baja_logica = 1;
                            $userService->agrupador = 0;
                            $userService->user_reg_id = $user->id;
                            $userService->fecha_reg = $hoy;
                            $userService->dias_limite = $rs->dias_limite;
                            $userService->horas_limite = $rs->horas_limite;
                            $userService->minutos_limite = $rs->minutos_limite;
                            $userService->segundos_limite = $rs->segundos_limite;
                            $userService->last_login = $hoy;
                            if ($userService->save()) {
                                $objRel = new Frelaborales();
                                $frel = $objRel->getOneRelaboralConsiderandoUltimaMovilidad($relaboral->id);
                                $data = array(
                                    "id_relaboral" => $relaboral->id,
                                    "id_persona" => $relaboral->persona_id,
                                    "nombres" => $frel->nombres,
                                    "p_nombre" => $frel->p_nombre,
                                    "s_nombre" => $frel->s_nombre,
                                    "t_nombre" => $frel->t_nombre,
                                    "p_apellido" => $frel->p_apellido,
                                    "s_apellido" => $frel->s_apellido,
                                    "c_apellido" => $frel->c_apellido,
                                    "ci" => $frel->ci,
                                    "expd" => $frel->expd,
                                    "fecha_nac" => $frel->fecha_nac,
                                    "genero" => $frel->genero
                                );
                                $objToken = array(
                                    "token" => $token,
                                    "iat" => $inicioToken,
                                    "exp" => $finToken,
                                    "data" => $data,
                                );
                                $msj = array('result' => 1, 'msj' => 'Usuario creado.', 'token' => $objToken);
                            } else {
                                $msj = array('result' => 0, 'msj' => 'Error: No se pudo crear el registro del usuario correspondiente.');
                            }
                        }
                    } else {
                        $msj = array('result' => -2, 'msj' => 'Usuario no autorizado debido a conclusi&oacute;n de contrato.');
                    }
                } else $msj = array('result' => -3, 'msj' => 'El nombre de usuario o la contrase&ntilde;a son incorrectos.');
            } else {
                $msj = array('result' => -4, 'msj' => 'Error: Datos incompletos.');
            }
            echo json_encode($msj, JSON_PRETTY_PRINT);
        }
    }

    /**
     * Función para la carga del primer listado sobre la página de gestión de relaciones laborales.
     * Se inhabilita la vista para el uso de jqwidgets,
     */
    public function listhistorialAction()
    {
        $this->view->disable();
        header('Content-Type: application/JSON');
        $ok = false;
        $msj = array();
        $relaboral = Array();
        $hoy = date("Y-m-d H:i:s");
        $master = 1;
        if ($this->request->isPost()) {
            $token = "";
            if ($this->request->getPost("token") != "") {
                $token = $this->request->getPost("token");
            }
            $id = 0;
            if ($this->request->getPost("id") > 0) {
                $id = $this->request->getPost("id");
            }
            $gestion = 0;
            if ($this->request->getPost("gestion") > 0) {
                $gestion = $this->request->getPost("gestion");
            }
            if ($token != "" && $id > 0 && $gestion > 0) {
                $objUs = Usuariosservicios::findFirst("token LIKE '" . $token . "' AND estado=1 AND baja_logica = 1");
                if (is_object($objUs)) {
                    $us = new Usuariosservicios();
                    $rs = Rolesservicios::findFirstById($objUs->rolservicios_id);
                    $fechaHoraIni = date("Y-m-d H:i:s", strtotime($objUs->last_login));
                    /**
                     * Se establece el uso por defecto del tiempo aplicado por usuario, siendo por ésta razón modificable por usuario.
                     * Es decir, se puede ampliar una sesión en particular de un usuario, sin tener que modificar el tiempo de sesión otorgado por defecto debido a su rol.
                     */
                    if ($master == 1) {
                        $fechaHoraFin = $us->sumaFechasHoras($fechaHoraIni, $objUs->dias_limite, $objUs->horas_limite, $objUs->minutos_limite, $objUs->segundos_limite);
                    } else {
                        $fechaHoraFin = $us->sumaFechasHoras($fechaHoraIni, $rs->dias_limite, $rs->horas_limite, $rs->minutos_limite, $rs->segundos_limite);
                    }
                    $okSession = $us->validaTokenTiempoAutorizacion($fechaHoraFin);
                    if ($id > 0 && $gestion > 0 && $okSession) {
                        $obj = new Frelaborales();
                        $resul = $obj->getAllByPerson($id, $gestion);

                        if ($resul->count() > 0) {
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
                                    'nombres' => $v->p_nombre . " " . $v->s_nombre . " " . $v->t_nombre . " " . $v->p_apellido . " " . $v->s_apellido . " " . $v->c_apellido,
                                    'ci' => $v->ci,
                                    'expd' => $v->expd,
                                    'fecha_caducidad' => $v->fecha_caducidad,
                                    'num_complemento' => '',
                                    'fecha_nac' => $v->fecha_nac,
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
                        echo json_encode($relaboral, JSON_PRETTY_PRINT);
                    } else {
                        if (!$okSession) {
                            $msj = array("result" => -1, "msj" => "Usuario no autorizado. Tiempo de uso se agotado para su token.");
                        } else {
                            $msj = array("result" => 0, "msj" => "Datos incompletos.");
                        }
                        echo json_encode($msj, JSON_PRETTY_PRINT);
                    }
                } else {
                    $msj = array("result" => -3, "msj" => "Usuario no autorizado.");
                    echo json_encode($msj, JSON_PRETTY_PRINT);
                }
            } else {
                $msj = array("result" => -4, "msj" => "Datos incompletos.");
                echo json_encode($msj, JSON_PRETTY_PRINT);
            }
        }
    }

    /**
     * Función para la carga del historial de movilidad funcionaria.
     */
    public function listhistorialmovilidadAction()
    {

        $this->view->disable();
        header('Content-Type: application/JSON');
        $ok = false;
        $msj = array();
        $relaboralmovilidad = Array();
        $hoy = date("Y-m-d H:i:s");
        $master = 1;
        if ($this->request->isPost()) {
            $token = "";
            if ($this->request->getPost("token") != "") {
                $token = $this->request->getPost("token");
            }
            $id = 0;
            if ($this->request->getPost("id") > 0) {
                $id = $this->request->getPost("id");
            }
            if ($token != "" && $id > 0 ) {
                $objUs = Usuariosservicios::findFirst("token LIKE '" . $token . "' AND estado=1 AND baja_logica = 1");
                if (is_object($objUs)) {
                    $us = new Usuariosservicios();
                    $rs = Rolesservicios::findFirstById($objUs->rolservicios_id);
                    $fechaHoraIni = date("Y-m-d H:i:s", strtotime($objUs->last_login));
                    /**
                     * Se establece el uso por defecto del tiempo aplicado por usuario, siendo por ésta razón modificable por usuario.
                     * Es decir, se puede ampliar una sesión en particular de un usuario, sin tener que modificar el tiempo de sesión otorgado por defecto debido a su rol.
                     */
                    if ($master == 1) {
                        $fechaHoraFin = $us->sumaFechasHoras($fechaHoraIni, $objUs->dias_limite, $objUs->horas_limite, $objUs->minutos_limite, $objUs->segundos_limite);
                    } else {
                        $fechaHoraFin = $us->sumaFechasHoras($fechaHoraIni, $rs->dias_limite, $rs->horas_limite, $rs->minutos_limite, $rs->segundos_limite);
                    }
                    $okSession = $us->validaTokenTiempoAutorizacion($fechaHoraFin);
                    if ($id > 0 && $okSession) {
                        $obj = new Frelaboralesmovilidad();
                        $resul = $obj->getAllByOne($id);
                        if ($resul->count() > 0) {
                            foreach ($resul as $v) {
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
                        echo json_encode($relaboralmovilidad, JSON_PRETTY_PRINT);
                    } else {
                        if (!$okSession) {
                            $msj = array("result" => -1, "msj" => "Usuario no autorizado. Tiempo de uso se agotado para su token.");
                        } else {
                            $msj = array("result" => 0, "msj" => "Datos incompletos.");
                        }
                        echo json_encode($msj, JSON_PRETTY_PRINT);
                    }
                } else {
                    $msj = array("result" => -3, "msj" => "Usuario no autorizado.");
                    echo json_encode($msj, JSON_PRETTY_PRINT);
                }
            } else {
                $msj = array("result" => -4, "msj" => "Datos incompletos.");
                echo json_encode($msj, JSON_PRETTY_PRINT);
            }
        }
    }


    /**
     * Funcion para el despliegue del listado de eventos disponibles para registro de marcaciones.
     * @param $clave
     */
    public function listAction()
    {
        $this->view->disable();
        header('Content-Type: application/JSON');
        $ok = false;
        $msj = array();
        $total_rows = 0;
        $eventosmarcaciones = array();
        if ($this->request->isPost()) {
            $obj = json_decode(file_get_contents('php://input'));
            $clave = "";
            $id_eventomarcacion = 0;
            if (isset($obj->clave)) {
                $clave = $obj->clave;
            }
            if (isset($obj->id_eventomarcacion)) {
                $id_eventomarcacion = $obj->id_eventomarcacion;
            }
            $ws = new Serviciosweb();
            if ($ws->validacion($clave)) {
                $model = new Feventosmarcaciones();
                $result = $model->getAll(0, $id_eventomarcacion);
                if (count($result) > 0) {
                    foreach ($result as $v) {
                        $total_rows = $v->total_rows;
                        $eventosmarcaciones[] = array(
                            'id_eventomovil' => $v->id_eventomovil,
                            'evento_movil' => $v->evento_movil,
                            'grupo' => $v->grupo,
                            'eventomovil_descripcion' => $v->eventomovil_descripcion,
                            'eventomovil_observacion' => $v->eventomovil_observacion,
                            'eventomovil_estado' => $v->eventomovil_estado,
                            'eventomovil_estado_descripcion' => $v->eventomovil_estado_descripcion,
                            'id_eventomarcacion' => $v->id_eventomarcacion,
                            'num_marcacion' => $v->num_marcacion,
                            'tipo_marcacion' => $v->tipo_marcacion,
                            'tipo_marcacion_descripcion' => $v->tipo_marcacion_descripcion,
                            'referencia' => $v->referencia,
                            'latitud' => $v->latitud,
                            'longitud' => $v->longitud,
                            'radio' => $v->radio,
                            'planillable' => $v->planillable,
                            'planillable_descripcion' => $v->planillable_descripcion,
                            'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y H:i:s", strtotime($v->fecha_ini)) : "",
                            'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y H:i:s", strtotime($v->fecha_fin)) : "",
                            'plazo' => $v->plazo,
                            'plazo_descripcion' => $v->plazo_descripcion,
                            'eventomarcacion_descripcion' => $v->eventomarcacion_descripcion,
                            'eventomarcacion_telefonos' => $v->eventomarcacion_telefonos,
                            'eventomarcacion_observacion' => $v->eventomarcacion_observacion,
                            'eventomarcacion_estado' => $v->eventomarcacion_estado,
                            'eventomarcacion_estado_descripcion' => $v->eventomarcacion_estado_descripcion,
                            /*'eventomovil_user_reg_id' => $v->eventomovil_user_reg_id,
                            'eventomovil_fecha_reg' => $v->eventomovil_fecha_reg != "" ? date("d-m-Y H:i:s", strtotime($v->eventomovil_fecha_reg)) : "",
                            'eventomarcacion_user_reg_id' => $v->eventomarcacion_user_reg_id,
                            'eventomarcacion_fecha_reg' => $v->eventomarcacion_fecha_reg != "" ? date("d-m-Y H:i:s", strtotime($v->eventomarcacion_fecha_reg)) : "",
                            'eventomovil_user_mod_id' => $v->eventomovil_user_mod_id,
                            'eventomovil_fecha_mod' => $v->eventomovil_fecha_mod != "" ? date("d-m-Y H:i:s", strtotime($v->eventomovil_fecha_mod)) : "",
                            'eventomarcacion_user_mod_id' => $v->eventomarcacion_user_mod_id,
                            'eventomarcacion_fecha_mod' => $v->eventomarcacion_fecha_mod != "" ? date("d-m-Y H:i:s", strtotime($v->eventomarcacion_fecha_mod)) : ""*/
                        );
                    }
                }
                $msj = array('result' => 1, 'msj' => 'Usuario autorizado.', "total_rows" => $total_rows, "data" => $eventosmarcaciones);
            } else {
                $msj = array('result' => 0, 'msj' => 'Acci&oacute;n no autorizada.', "total_rows" => $total_rows, $eventosmarcaciones);
            }
        }
        echo json_encode($msj, JSON_PRETTY_PRINT);
    }

    /**
     * Función provisional para el despliegue de la lista sin ninguna restricción.
     */
    public function listfreeAction()
    {
        $this->view->disable();
        header('Content-Type: application/JSON');
        $msj = array();
        $total_rows = 0;
        $eventosmarcaciones = array();
        if ($this->request->isGet()) {
            $obj = json_decode(file_get_contents('php://input'));
            $id_eventomarcacion = 0;
            $model = new Feventosmarcaciones();
            $result = $model->getAll(0, $id_eventomarcacion);
            if (count($result) > 0) {
                foreach ($result as $v) {
                    $total_rows = $v->total_rows;
                    $eventosmarcaciones[] = array(
                        'id_eventomovil' => $v->id_eventomovil,
                        'evento_movil' => $v->evento_movil,
                        'grupo' => $v->grupo,
                        'eventomovil_descripcion' => $v->eventomovil_descripcion,
                        'eventomovil_observacion' => $v->eventomovil_observacion,
                        'eventomovil_estado' => $v->eventomovil_estado,
                        'eventomovil_estado_descripcion' => $v->eventomovil_estado_descripcion,
                        'id_eventomarcacion' => $v->id_eventomarcacion,
                        'num_marcacion' => $v->num_marcacion,
                        'tipo_marcacion' => $v->tipo_marcacion,
                        'tipo_marcacion_descripcion' => $v->tipo_marcacion_descripcion,
                        'referencia' => $v->referencia,
                        'latitud' => $v->latitud,
                        'longitud' => $v->longitud,
                        'radio' => $v->radio,
                        'planillable' => $v->planillable,
                        'planillable_descripcion' => $v->planillable_descripcion,
                        'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y H:i:s", strtotime($v->fecha_ini)) : "",
                        'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y H:i:s", strtotime($v->fecha_fin)) : "",
                        'plazo' => $v->plazo,
                        'plazo_descripcion' => $v->plazo_descripcion,
                        'eventomarcacion_descripcion' => $v->eventomarcacion_descripcion,
                        'eventomarcacion_telefonos' => $v->eventomarcacion_telefonos,
                        'eventomarcacion_observacion' => $v->eventomarcacion_observacion,
                        'eventomarcacion_estado' => $v->eventomarcacion_estado,
                        'eventomarcacion_estado_descripcion' => $v->eventomarcacion_estado_descripcion,
                        /*'eventomovil_user_reg_id' => $v->eventomovil_user_reg_id,
                        'eventomovil_fecha_reg' => $v->eventomovil_fecha_reg != "" ? date("d-m-Y H:i:s", strtotime($v->eventomovil_fecha_reg)) : "",
                        'eventomarcacion_user_reg_id' => $v->eventomarcacion_user_reg_id,
                        'eventomarcacion_fecha_reg' => $v->eventomarcacion_fecha_reg != "" ? date("d-m-Y H:i:s", strtotime($v->eventomarcacion_fecha_reg)) : "",
                        'eventomovil_user_mod_id' => $v->eventomovil_user_mod_id,
                        'eventomovil_fecha_mod' => $v->eventomovil_fecha_mod != "" ? date("d-m-Y H:i:s", strtotime($v->eventomovil_fecha_mod)) : "",
                        'eventomarcacion_user_mod_id' => $v->eventomarcacion_user_mod_id,
                        'eventomarcacion_fecha_mod' => $v->eventomarcacion_fecha_mod != "" ? date("d-m-Y H:i:s", strtotime($v->eventomarcacion_fecha_mod)) : ""*/
                    );
                }
            }
            //$msj = array('result' => 1, 'msj' => 'Usuario autorizado.', "total_rows" => $total_rows, "data" => $eventosmarcaciones);
            $msj = $eventosmarcaciones;

        }
        echo json_encode($msj, JSON_PRETTY_PRINT);
    }

    /**
     * Función para la descarga masiva de registros almacenados en la Base de Datos del celular habilitado.
     * @param string $clave
     * @param string $sql
     */
    public function downloadallmarksAction($clave = '', $sql = '')
    {
        $this->view->disable();
        $total_rows = 0;
        $eventosmarcaciones = array();
        header('Content-Type: application/JSON');
        $ws = new Serviciosweb();
        if ($ws->validacion($clave)) {
            if ($this->request->isPost()) {
                $db = $this->getDI()->get('db');
                if ($db->execute($sql)) {
                    $msj = array('result' => 1, 'msj' => 'Descarga finalizada con &eacute;xito.', "total_rows" => $total_rows, "data" => $eventosmarcaciones);
                } else $msj = array('result' => 0, 'msj' => 'No se pudo realizar la descarga.', "total_rows" => $total_rows, "data" => $eventosmarcaciones);
            }
        } else {
            $msj = array('result' => 0, 'msj' => 'Acci&oacute;n no autorizada.', "total_rows" => $total_rows, $eventosmarcaciones);
        }
        echo json_encode($msj, JSON_PRETTY_PRINT);
    }

    /**
     * Función para el registro de una marcación.
     * @param string $clave
     */
    public function savemarkAction($clave = '')
    {
        $this->view->disable();
        header('Content-Type: application/JSON');
        $ws = new Serviciosweb();
        if ($ws->validacion($clave)) {
            if ($this->request->isPost()) {
                $userWs = $ws->getOneByKey($clave);
                if (is_object($userWs) && $userWs->id > 0) {
                    $obj = json_decode(file_get_contents('php://input'));
                    $marcacionMovil = new Marcacionesmoviles();
                    $marcacionMovil->eventomarcacion_id = $obj->id_eventomarcacion;
                    $marcacionMovil->codigo = $obj->codigo;
                    $marcacionMovil->fecha = $obj->fecha;
                    $marcacionMovil->hora = $obj->hora;
                    $marcacionMovil->latitud = $obj->latitud;
                    $marcacionMovil->longitud = $obj->longitud;
                    $marcacionMovil->observacion = $obj->observacion;
                    $marcacionMovil->estado = 1;
                    $marcacionMovil->baja_logica = 1;
                    $marcacionMovil->agrupador = 1;
                    $marcacionMovil->user_reg_id = $userWs->id;
                    $marcacionMovil->fecha_reg = date("Y-m-d H:i:s");
                    if ($marcacionMovil->save()) {
                        $msj = array('result' => 1, 'msj' => 'Registro exitoso de marcaci&oacute;n m&oacute;vil.', "object" => $marcacionMovil);
                    }
                } else {
                    $msj = array('result' => 0, 'msj' => 'Error de registro: El usuario no es autorizado.');
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'Acci&oacute;n no autorizada.');
            }
        } else {
            $msj = array('result' => 0, 'msj' => 'Error: Usuario no autorizado.');
        }
        echo json_encode($msj, JSON_PRETTY_PRINT);
    }

    public function formdenunciasAction($id = 0)
    {
        $this->view->disable();
        header('Content-Type: application/JSON');
        if ($this->request->isGet()) {
            if ($id != 0) {
                $u = Wsdenuncias::findFirstById($id);
                $result = array();
                $result[] = array(
                    'id' => $u->id,
                    'nombres' => $u->nombres,
                    'apellidos' => $u->apellidos,
                    'ci' => $u->ci,
                    'celular' => $u->celular,
                    'correo' => $u->correo,
                    'denunciados' => $u->denunciados,
                    'detalle_denuncia' => $u->detalle_denuncia,
                    'fecha_reg' => $u->fecha_reg,
                );
            } else {
                $model = Wsdenuncias::find();
                $result = array();
                foreach ($model as $u) {
                    $result[] = array(
                        'id' => $u->id,
                        'nombres' => $u->nombres,
                        'apellidos' => $u->apellidos,
                        'ci' => $u->ci,
                        'celular' => $u->celular,
                        'correo' => $u->correo,
                        'denunciados' => $u->denunciados,
                        'detalle_denuncia' => $u->detalle_denuncia,
                        'fecha_reg' => $u->fecha_reg,
                    );
                }
            }

            echo json_encode($result, JSON_PRETTY_PRINT);
        }

        if ($this->request->isPost()) {
            $obj = json_decode(file_get_contents('php://input'));
            $model = new Wsdenuncias();
            $model->nombres = $obj->nombres;
            $model->apellidos = $obj->apellidos;
            $model->ci = $obj->ci;
            $model->celular = $obj->celular;
            $model->correo = $obj->correo;
            $model->denunciados = $obj->denunciados;
            $model->detalle_denuncia = $obj->detalle_denuncia;
            $model->fecha_reg = date("Y-m-d H:i:s");
            $model->save();

            echo json_encode($model, JSON_PRETTY_PRINT);
        }
    }


    public function formsugerenciasAction($id = 0)
    {
        $this->view->disable();
        header('Content-Type: application/JSON');
        if ($this->request->isGet()) {
            if ($id != 0) {
                $u = Wssugerencias::findFirstById($id);
                $result = array();
                $result[] = array(
                    'id' => $u->id,
                    'nombres' => $u->nombres,
                    'apellidos' => $u->apellidos,
                    'ci' => $u->ci,
                    'celular' => $u->celular,
                    'correo' => $u->correo,
                    'sugerencia' => $u->sugerencia,
                    'fecha_reg' => $u->fecha_reg,
                );
            } else {
                $model = Wssugerencias::find();
                $result = array();
                foreach ($model as $u) {
                    $result[] = array(
                        'id' => $u->id,
                        'nombres' => $u->nombres,
                        'apellidos' => $u->apellidos,
                        'ci' => $u->ci,
                        'celular' => $u->celular,
                        'correo' => $u->correo,
                        'sugerencia' => $u->sugerencia,
                        'fecha_reg' => $u->fecha_reg,
                    );
                }
            }

            echo json_encode($result, JSON_PRETTY_PRINT);
        }

        if ($this->request->isPost()) {
            $obj = json_decode(file_get_contents('php://input'));
            $model = new Wssugerencias();
            $model->nombres = $obj->nombres;
            $model->apellidos = $obj->apellidos;
            $model->ci = $obj->ci;
            $model->celular = $obj->celular;
            $model->correo = $obj->correo;
            $model->sugerencia = $obj->sugerencia;
            $model->fecha_reg = date("Y-m-d H:i:s");
            $model->save();

            echo json_encode($model, JSON_PRETTY_PRINT);
        }
    }

    public function forminformacionesAction($id = 0)
    {
        $this->view->disable();
        header('Content-Type: application/JSON');
        if ($this->request->isGet()) {
            if ($id != 0) {
                $u = Wsinformaciones::findFirstById($id);
                $result = array();
                $result[] = array(
                    'id' => $u->id,
                    'nombres' => $u->nombres,
                    'apellidos' => $u->apellidos,
                    'ci' => $u->ci,
                    'celular' => $u->celular,
                    'correo' => $u->correo,
                    'fecha_solicitud' => $u->fecha_solicitud,
                    'detalle_solicitud' => $u->detalle_solicitud,
                    'motivo_solicitud' => $u->motivo_solicitud,
                    'fecha_reg' => $u->fecha_reg,
                );
            } else {
                $model = Wsinformaciones::find();
                $result = array();
                foreach ($model as $u) {
                    $result[] = array(
                        'id' => $u->id,
                        'nombres' => $u->nombres,
                        'apellidos' => $u->apellidos,
                        'ci' => $u->ci,
                        'celular' => $u->celular,
                        'correo' => $u->correo,
                        'fecha_solicitud' => $u->fecha_solicitud,
                        'detalle_solicitud' => $u->detalle_solicitud,
                        'motivo_solicitud' => $u->motivo_solicitud,
                        'fecha_reg' => $u->fecha_reg,
                    );
                }
            }

            echo json_encode($result, JSON_PRETTY_PRINT);
        }

        if ($this->request->isPost()) {
            $obj = json_decode(file_get_contents('php://input'));
            $model = new Wsinformaciones();
            $model->nombres = $obj->nombres;
            $model->apellidos = $obj->apellidos;
            $model->ci = $obj->ci;
            $model->celular = $obj->celular;
            $model->correo = $obj->correo;
            $model->fecha_solicitud = date("Y-m-d H:i:s");
            $model->detalle_solicitud = $obj->detalle_solicitud;
            $model->motivo_solicitud = $obj->motivo_solicitud;
            $model->fecha_reg = date("Y-m-d H:i:s");
            $model->save();
            echo json_encode($model, JSON_PRETTY_PRINT);
        }
    }

    public function formreclamosAction($id = 0)
    {
        $this->view->disable();
        header('Content-Type: application/JSON');
        if ($this->request->isGet()) {
            if ($id != 0) {
                $u = Wsreclamos::findFirstById($id);
                $result = array();
                $result[] = array(
                    'id' => $u->id,
                    'nombres' => $u->nombres,
                    'apellidos' => $u->apellidos,
                    'ci' => $u->ci,
                    'celular' => $u->celular,
                    'correo' => $u->correo,
                    'lugar_incidente' => $u->lugar_incidente,
                    'fecha_incidente' => $u->fecha_incidente,
                    'descripcion_reclamo' => $u->descripcion_reclamo,
                    'fecha_reg' => $u->fecha_reg,
                );
            } else {
                $model = Wsreclamos::find();
                $result = array();
                foreach ($model as $u) {
                    $result[] = array(
                        'id' => $u->id,
                        'nombres' => $u->nombres,
                        'apellidos' => $u->apellidos,
                        'ci' => $u->ci,
                        'celular' => $u->celular,
                        'correo' => $u->correo,
                        'lugar_incidente' => $u->lugar_incidente,
                        'fecha_incidente' => $u->fecha_incidente,
                        'descripcion_reclamo' => $u->descripcion_reclamo,
                        'fecha_reg' => $u->fecha_reg,
                    );
                }
            }

            echo json_encode($result, JSON_PRETTY_PRINT);
        }

        if ($this->request->isPost()) {
            $obj = json_decode(file_get_contents('php://input'));
            $model = new Wsreclamos();
            $model->nombres = $obj->nombres;
            $model->apellidos = $obj->apellidos;
            $model->ci = $obj->ci;
            $model->celular = $obj->celular;
            $model->correo = $obj->correo;
            $model->lugar_incidente = $obj->lugar_incidente;
            $model->fecha_incidente = date("Y-m-d", strtotime($obj->fecha_incidente));
            $model->descripcion_reclamo = $obj->descripcion_reclamo;
            $model->fecha_reg = date("Y-m-d H:i:s");
            $model->save();
            echo json_encode($model, JSON_PRETTY_PRINT);
        }
    }

    /**
     * Función para la verificación de la existencia de la imagen correspondiente a la foto de perfil de una persona.
     * @param string $ci
     */
    public function imgexistsAction($ci = '')
    {
        $this->view->disable();
        $msj = array();
        header('Content-Type: application/JSON');
        if ($this->request->isGet()) {
            if ($ci != '') {
                $rutaImagenesCredenciales = "/images/personal/";
                $extencionImagenesCredenciales = ".jpg";
                $nombreImagenArchivo = $rutaImagenesCredenciales . trim($ci) . $extencionImagenesCredenciales;
                if (file_exists(getcwd() . $nombreImagenArchivo)) $msj = array("result" => 1);
                else $msj = array("result" => 0);
            }
            echo json_encode($msj, JSON_PRETTY_PRINT);
        }
    }

}