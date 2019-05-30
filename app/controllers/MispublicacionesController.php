<?php

/**
 *   Oasis - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  2.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  07-07-2016
 */
class MispublicacionesController extends ControllerBase
{
    public $lista = '';

    public function initialize()
    {
        parent::initialize();
    }

    /**
     * Función para la carga de la página de gestión de mis publicaciones.
     */
    public function indexAction()
    {
        $auth = $this->session->get('auth');
        if (isset($auth['version'])) {
            $version = $auth['version'];
        } else $version = "0.0.0";

        $this->assets->addJs('/js/jquery.PrintArea.js?v=' . $version);
        $this->assets->addCss('/assets/css/PrintArea.css?v=' . $version);
        $this->assets->addCss('/assets/css/oasis.principal.css?v=' . $version);
        $this->assets->addCss('/css/oasis.grillas.css?v=' . $version);
        $this->assets->addJs('/js/ckeditor/ckeditor.js?v=' . $version);
        $this->assets->addJs('/js/ckfinder/ckfinder.js?v=' . $version);
        $this->assets->addJs('/js/cropit/dist/jquery.cropit.js?v=' . $version);
        $this->assets->addJs('/js/mispublicaciones/oasis.mispublicaciones.index.js?v=' . $version);
        $this->assets->addJs('/js/mispublicaciones/oasis.mispublicaciones.list.js?v=' . $version);
        $idUsuario = $this->_user->id;
        $usuario = Usuarios::findFirstById($idUsuario);
        $persona = Personas::findFirstById($usuario->persona_id);
        $nombres = $persona->p_apellido . ($persona->s_apellido != '' ? ' ' . $persona->s_apellido : '') . ($persona->c_apellido != '' ? ' ' . $persona->c_apellido : '') . ($persona->p_nombre != '' ? ' ' . $persona->p_nombre : '') . ($persona->s_nombre != '' ? ' ' . $persona->s_nombre : '');
        $this->view->setVar('nombres', $nombres);
        $this->view->setVar('pseudonimo', $usuario->pseudonimo);
        $this->view->setVar('avatarAlmacenado', $usuario->avatar);
        $auth = $this->session->get('auth');
        if ($usuario->avatar != "") {
            $this->view->setVar('avatar', $usuario->avatar);
        } else {
            $this->view->setVar('avatar', $auth['avatar']);
        }
    }

    /**
     * Función para la obtención del registro de publicaciones en el foro.
     */
    public function foroAction()
    {
        $auth = $this->session->get('auth');
        $idUsuario = $auth['id'];
        $pseudonimo = $auth['pseudonimo'];
        $idPersona = $auth['persona_id'];
        $avatar = $auth['avatar'];
        $this->view->disable();
        $publicaciones = [];
        $total_rows = 0;
        $result = 0;
        $opcion = 0;
        $msj = "";
        $offset = 0;
        $limit = 0;
        if (isset($_POST["offset"])) {
            $offset = $_POST["offset"];
        }
        if (isset($_POST["limit"])) {
            $limit = $_POST["limit"];
        }
        if (isset($_POST["opcion"])) {
            $opcion = $_POST["opcion"];
            try {
                if ($idUsuario > 0) {
                    $obj = new Publicaciones();
                    $result = $obj->getPublicaciones($opcion, $idUsuario, 0, "", "", $offset, $limit);
                    if (count($result) > 0) {
                        foreach ($result as $v) {
                            $total_rows = $v->total_rows;
                            $publicacionesSecundarias = "";
                            $this->listar($opcion, $idUsuario, $v->id_publicacion, $publicacionesSecundarias, $pseudonimo, $avatar);
                            $publicaciones[] = array(
                                'id_publicacion' => $v->id_publicacion,
                                'padre_id' => $v->padre_id,
                                'genero_id' => $v->genero_id,
                                'genero_publicacion' => $v->genero_publicacion,
                                'genero_publicacion_opcion' => $v->genero_publicacion_opcion,
                                'publicacion' => $v->publicacion,
                                'compartido' => $v->compartido,
                                'adjuntos' => $v->adjuntos,
                                'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                                'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                                'tiempo_transcurrido' => $v->tiempo_transcurrido,
                                'contabilizador_emoticones' => $v->contabilizador_emoticones,
                                'pseudonimo' => $v->pseudonimo,
                                'emoticon_id' => $v->emoticon_id,
                                'emoticon' => $v->emoticon,
                                'emoticon_class' => $v->emoticon_class,
                                'hijos' => $publicacionesSecundarias,
                                'editable' => $idUsuario == $v->user_reg_id ? true : false,
                                'avatar' => $v->avatar
                            );
                        }
                    }
                } else {
                    $result = -1;
                    $msj = "Error cr&iacute;tico: Usuario no autorizado.";
                }
            } catch (\Exception $e) {
                echo get_class($e), ": ", $e->getMessage(), "\n";
                echo " File=", $e->getFile(), "\n";
                echo " Line=", $e->getLine(), "\n";
                echo $e->getTraceAsString();
                $result = -2;
                $msj = "Error cr&iacute;tico: No se hall&oacute; registros debido a un error en la consulta.";
            }
        }
        $data[] = array(
            'TotalRows' => $total_rows,
            'Rows' => $publicaciones,
            'Result' => $result,
            'Msj' => $msj
        );
        echo json_encode($data);
    }

    /**
     * Función para obtener el listado de publicaciones en el foro.
     */
    public function listAction()
    {
        $auth = $this->session->get('auth');
        $idUsuario = $auth['id'];
        $this->view->disable();
        $this->lista = "";
        if (isset($_POST["opcion"])) {
            $opcion = $_POST["opcion"];
            if ($idUsuario > 0) {
                $auth = $this->session->get('auth');
                $idUsuario = $auth['id'];
                $lista = '';
                $this->listar($opcion, $idUsuario, 15, $lista);
            } else {

            }
            echo $lista;
        }
    }

    /**
     * Función recursiva para la obtención de las publicaciones anidadas de forma recursiva a una determinada publicación.
     * @param int $orderTab
     * @param $idUsuario
     * @param int $idPublicacion
     * @param string $lista
     * @param string $pseudonimo
     * @param string $avatar
     */
    public function listar($orderTab = 0, $idUsuario, $idPublicacion = 0, &$lista = '', $pseudonimo = '', $avatar = '')
    {
        $obj = new Publicaciones();
        if ($idUsuario > 0 && $idPublicacion > 0) {
            $result = $obj->getPublicaciones($orderTab, $idUsuario, $idPublicacion);
            if ($result->count() > 0) {
                $lista .= '<ul class="media-list push ulComentarios' . $orderTab . '" id="ulComentarios' . $orderTab . '_' . $idPublicacion . '">';
                foreach ($result as $valor) {
                    $lista .= '<li class="media" id="li' . $orderTab . '_' . $valor->id_publicacion . '">';
                    $lista .= '<a href="javascript:void(0)" class="pull-left">';
                    $lista .= '<img src="/images/avatar/' . $valor->avatar . '" alt="' . $valor->pseudonimo . '" class="img-circle" height="50px"';
                    $lista .= 'width="50px">';
                    $lista .= '</a>';
                    $lista .= '<div class="media-body">';
                    $lista .= '<p class="push-bit">';
                    $lista .= '<span class="text-muted pull-right">';
                    $idPadre = 0;
                    if ($valor->padre_id != null && $valor->padre_id > 0) {
                        $idPadre = $valor->padre_id;
                    }
                    if ($valor->user_reg_id == $idUsuario) {
                        $lista .= '<div class="block-options pull-right">';
                        $lista .= '<a id="aEditar' . $orderTab . '_' . $valor->id_publicacion . '" data-id-padre="' . $idPadre . '" data-compartido="' . $valor->compartido . '" data-id-genero="' . $valor->genero_id . '" data-genero-publicacion="' . $valor->genero_publicacion . '" data-genero-publicacion-opcion="' . $valor->genero_publicacion_opcion . '" class="btn btn-alt btn-sm btn-default aEditar' . $orderTab . '" title="" data-toggle="tooltip" href="javascript:void(0)" data-original-title="Editar">';
                        $lista .= '<i class="fa fa-pencil"></i></a>';
                        $lista .= '<a id="aEliminar' . $orderTab . '_' . $valor->id_publicacion . '" data-id-padre="' . $idPadre . '" data-compartido="' . $valor->compartido . '" data-id-genero="' . $valor->genero_id . '" data-genero-publicacion="' . $valor->genero_publicacion . '" data-genero-publicacion-opcion="' . $valor->genero_publicacion_opcion . '" class="btn btn-alt btn-sm btn-default aEliminar' . $orderTab . '" title="" data-toggle="tooltip" href="javascript:void(0)" data-original-title="Eliminar">';
                        $lista .= '<i class="fa fa-times"></i></a></div>';
                    }
                    $lista .= '</span>';
                    $unUna = "un";
                    if ($valor->genero_publicacion_opcion == 1) {
                        $unUna = "una";
                    }
                    $lista .= '<strong id="strPublicador' . $orderTab . '_' . $valor->id_publicacion . '"><a href="javascript:void(0)">' . $valor->pseudonimo . '</a> <label id="lblEventoPublicacion_' . $valor->id_publicacion . '">ha publicado ' . $unUna . ' ' . strtolower($valor->genero_publicacion) . '.</label></strong>';
                    $lista .= '</br>';
                    $lista .= '<span class="text-muted">';
                    $lista .= '<small id="smallTiempoTranscurrido' . $orderTab . '_' . $valor->id_publicacion . '"><em> ' . $valor->tiempo_transcurrido . '</em></small>';
                    $lista .= '</span>';
                    $lista .= '<div class="row"><div class="col-md-12 col-xs-12"><div id="divContenidoPublicacion' . $orderTab . '_' . $valor->id_publicacion . '">' . $valor->publicacion . '</div></div></div>';
                    if ($valor->contabilizador_emoticones != null) {
                        $lista .= '<p id="pContabilizadorEmoticones' . $orderTab . '_' . $valor->id_publicacion . '">' . $valor->contabilizador_emoticones . '</p>';
                    } else {
                        $lista .= '<p id="pContabilizadorEmoticones' . $orderTab . '_' . $valor->id_publicacion . '"></p>';
                    }
                    $lista .= '<p>';
                    if ($valor->emoticon_id > 0) {
                        $lista .= '<a href="javascript:void(0)" class="a_te_gusta' . $orderTab . ' text-info" id="a_te_gusta' . $orderTab . '_' . $valor->id_publicacion . '"><i class="fa fa-thumbs-up i_te_gusta' . $orderTab . '" id="i_te_gusta' . $orderTab . '_' . $valor->id_publicacion . '"></i> <b>Me gusta</b></a>';
                    } else {
                        $lista .= '<a href="javascript:void(0)" class="a_te_gusta' . $orderTab . ' text-gray" id="a_te_gusta' . $orderTab . '_' . $valor->id_publicacion . '"><i class="fa fa-thumbs-up i_te_gusta' . $orderTab . '" id="i_te_gusta' . $orderTab . '_' . $valor->id_publicacion . '"></i> <b>Me gusta</b></a>';
                    }
                    $lista .= '&nbsp;&nbsp;';
                    $lista .= '<a href="javascript:void(0)" id="a_comentar' . $orderTab . '_' . $valor->id_publicacion . '" class="btn-comentar' . $orderTab . ' text-gray"><i class="hi hi-comments"></i> <b>Comentar</b></a>';
                    $lista .= '</p>';
                    $this->listar($orderTab, $idUsuario, $valor->id_publicacion, $lista);
                }
                $lista .= '</ul>';
                $lista .= '<ul class="media-list push ulNuevoComentario' . $orderTab . '" id="ulNuevoComentario' . $orderTab . '_' . $idPublicacion . '">';
                $lista .= '<li class="media" id="li_comentario' . $orderTab . '_' . $idPublicacion . '">';
                $lista .= '<a class="pull-left" href="javascript:void(0)">';
                $lista .= '<img class="img-circle" alt="' . $pseudonimo . '" src="/images/avatar/' . $avatar . '" height="50px" width="50px">';
                $lista .= '</a>';
                $lista .= '<div class="media-body">';
                $lista .= '<form onsubmit="return false;" method="post" id="formNuevoComentario' . $orderTab . '_' . $idPublicacion . '">';
                $lista .= '<textarea id="txtNuevoComentario' . $orderTab . '_' . $idPublicacion . '" class="form-control" placeholder="Escribe un comentario.." rows="1" name="btnGuardarNuevoComentario' . $orderTab . '_' . $idPublicacion . '"></textarea>';
                $lista .= '<button id="btnGuardarNuevoComentario' . $orderTab . '_' . $idPublicacion . '" class="btnGuardarNuevoComentario' . $orderTab . ' btn btn-xs btn-primary" type="submit"><i class="fa fa-pencil"></i> Registrar Comentario</button>';
                $lista .= '</form>';
                $lista .= '</div>';
                $lista .= '</li>';
                $lista .= '</ul>';
            } else {
                $lista .= '<ul class="media-list push ulComentarios' . $orderTab . '" id="ulComentarios' . $orderTab . '_' . $idPublicacion . '"></ul>';
                $lista .= '<ul class="media-list push ulNuevoComentario' . $orderTab . '" id="ulNuevoComentario' . $orderTab . '_' . $idPublicacion . '">';
                $lista .= '<li class="media" id="li_comentario' . $orderTab . '_' . $idPublicacion . '">';
                $lista .= '<a class="pull-left" href="javascript:void(0)">';
                $lista .= '<img class="img-circle" alt="' . $pseudonimo . '" src="/images/avatar/' . $avatar . '" height="50px" width="50px">';
                $lista .= '</a>';
                $lista .= '<div class="media-body">';
                $lista .= '<form onsubmit="return false;" method="post" id="formNuevoComentario' . $orderTab . '_' . $idPublicacion . '">';
                $lista .= '<textarea id="txtNuevoComentario' . $orderTab . '_' . $idPublicacion . '" class="form-control" placeholder="Escribe un comentario.." rows="1" name="btnGuardarNuevoComentario' . $orderTab . '_' . $idPublicacion . '"></textarea>';
                $lista .= '<button id="btnGuardarNuevoComentario' . $orderTab . '_' . $idPublicacion . '" class="btnGuardarNuevoComentario' . $orderTab . ' btn btn-xs btn-primary" type="submit"><i class="fa fa-pencil"></i> Registrar Comentario</button>';
                $lista .= '</form>';
                $lista .= '</div>';
                $lista .= '</li>';
                $lista .= '</ul>';
            }
        }
    }

    /**
     * Función para el registro de una publicación.
     */
    function savedataAction()
    {
        $this->view->disable();
        $auth = $this->session->get('auth');
        $user_reg_id = $auth['id'];
        $user_mod_id = $auth['id'];
        $hoy = date("Y-m-d H:i:s");
        $genero_id = 1;
        if (isset($_POST["id_genero"])) {
            $genero_id = $_POST["id_genero"];
        }
        if ($user_reg_id > 0 && isset($_POST["data"]) && isset($_POST["data"])) {
            if (isset($_POST["id"]) && $_POST["id"] > 0) {
                /**
                 * Edición de publicación.
                 */
                $pub = Publicaciones::findFirst(array("id=" . $_POST["id"] . " AND estado=1 AND baja_logica=1"));
                if (is_object($pub)) {
                    $pub->genero_id = $genero_id;
                    $pub->publicacion = $_POST["data"];;
                    $pub->estado = 1;
                    $pub->baja_logica = 1;
                    $pub->agrupador = 0;
                    $pub->user_mod_id = $user_mod_id;
                    $pub->fecha_mod = $hoy;
                    if ($pub->save()) {
                        $msj = array("result" => 1, "msj" => "&Eacute;xito: Publicaci&oacute;n realizada exitosamente.");
                    } else {
                        $msj = array("result" => 0, "msj" => "Error: No se pudo realizar el registro de la publicaci&oacute;n.");
                    }
                } else {
                    $msj = array("result" => -1, "msj" => "Error: La publicaci&oacute;n ya no esta&aacute; disponible para su modificaci&oacute;n.");
                }
            } else {
                /**
                 * Nueva publicación.
                 */
                $pub = new Publicaciones();
                $pub->genero_id = $genero_id;
                $pub->publicacion = $_POST["data"];;
                $pub->compartido = 0;
                $pub->estado = 1;
                $pub->baja_logica = 1;
                $pub->agrupador = 0;
                $pub->user_reg_id = $user_reg_id;
                $pub->fecha_reg = $hoy;
                if ($pub->save()) {
                    $msj = array("result" => 1, "msj" => "&Eacute;xito: Publicaci&oacute;n realizada exitosamente.");
                } else {
                    $msj = array("result" => 0, "msj" => "Error: No se pudo realizar el registro de la publicaci&oacute;n.");
                }
            }

        } else {
            $msj = array("result" => 0, "msj" => "Error cr&iacute;tico: Los datos enviados no son correctos.");
        }
        echo json_encode($msj);
    }

    /**
     * Función para el registro y actualización de un registro de emoticon sobre un registro de publicación
     */
    public function saveemoticonAction()
    {
        $this->view->disable();
        $auth = $this->session->get('auth');
        $user_mod_id = $user_reg_id = $auth['id'];
        $hoy = date("Y-m-d H:i:s");
        $msj = array();
        if ($user_reg_id > 0 && isset($_POST["id"]) && isset($_POST["opcion"])) {
            $idPublicacion = $_POST["id"];
            $opcion = $_POST["opcion"];
            $idEmoticon = $_POST["ide"];
            $publicacion = Publicacionesemoticones::findFirst(array("publicacion_id=" . $idPublicacion . " AND user_reg_id=" . $user_reg_id));
            if (is_object($publicacion)) {
                if ($opcion == 1) {
                    $publicacion->emoticon_id = $idEmoticon;
                    $publicacion->estado = 1;
                    $publicacion->baja_logica = 1;
                    $publicacion->user_mod_id = $user_mod_id;
                    $publicacion->fecha_mod = $hoy;
                } else {
                    $publicacion->emoticon_id = $idEmoticon;
                    $publicacion->estado = 1;
                    $publicacion->baja_logica = 0;
                    $publicacion->user_mod_id = $user_mod_id;
                    $publicacion->fecha_mod = $hoy;
                }
            } else {
                $publicacion = new Publicacionesemoticones();
                if ($opcion == 1) {
                    $publicacion->publicacion_id = $idPublicacion;
                    $publicacion->emoticon_id = $idEmoticon;
                    $publicacion->estado = 1;
                    $publicacion->baja_logica = 1;
                    $publicacion->user_reg_id = $user_mod_id;
                    $publicacion->fecha_reg = $hoy;
                } else {
                    $publicacion->publicacion_id = $idPublicacion;
                    $publicacion->emoticon_id = $idEmoticon;
                    $publicacion->estado = 1;
                    $publicacion->baja_logica = 0;
                    $publicacion->user_reg_id = $user_mod_id;
                    $publicacion->fecha_reg = $hoy;
                }
            }
            if ($publicacion->save()) {
                $msj = array("result" => 1, "msj" => "&Eacute;xito: El registro del emoticon fue satisfactorio.");
            } else {
                $msj = array("result" => 0, "msj" => "Error: No se pudo realizar el registro del emoticon.");
            }
        } else {
            $msj = array("result" => -1, "msj" => "Error cr&iacute;tico: Se envi&oacute; datos incompletos.");
        }
        echo json_encode($msj);
    }

    /**
     * Función para registrar o modificar un comentario sobre una publicación.
     */
    public function savecommentAction()
    {
        $this->view->disable();
        $auth = $this->session->get('auth');
        $user_mod_id = $user_reg_id = $auth['id'];
        $pseudonimo = $auth['pseudonimo'];
        $avatar = $auth['avatar'];
        $hoy = date("Y-m-d H:i:s");
        $msj = array();
        $idPublicacion = 0;
        if (isset($_POST["id_publicacion"])) {
            $idPublicacion = $_POST["id_publicacion"];
        }
        $idComentario = 0;
        if (isset($_POST["id_comentario"])) {
            $idComentario = $_POST["id_comentario"];
        }
        $comentario = "";
        if (isset($_POST["comentario"])) {
            $comentario = $_POST["comentario"];
        }
        if ($user_reg_id > 0 && $idPublicacion > 0) {
            if ($idComentario > 0) {
                /**
                 * Nuevo Comentario.
                 */
                $objPublicacion = Publicaciones::findFirstById($idComentario);
                if (is_object($objPublicacion)) {
                    $objPublicacion->publicacion = $objPublicacion;
                    $objPublicacion->user_mod_id = $user_mod_id;
                    $objPublicacion->fecha_mod = $hoy;
                    if ($objPublicacion->save()) {
                        $objPublicacion->tiempo_transcurrido = 'Justo ahora';
                        $objPublicacion->pseudonimo = $pseudonimo;
                        $objPublicacion->avatar = $avatar;
                        $msj = array("result" => 1, "msj" => "Modificaci&oacute;n Exitosa!", "obj" => $objPublicacion);
                    } else {
                        $msj = array("result" => 0, "msj" => "Error cr&iacute;tico: No se pudo realizar la modificación del registro.", "obj" => null);
                    }
                } else {
                    $objPublicacion = new Publicaciones();
                    $objPublicacion->padre_id = $idPublicacion;
                    $objPublicacion->genero_id = 4;
                    $objPublicacion->publicacion = $comentario;
                    $objPublicacion->compartido = 0;
                    $objPublicacion->estado = 1;
                    $objPublicacion->baja_logica = 1;
                    $objPublicacion->agrupador = 0;
                    $objPublicacion->user_reg_id = $user_reg_id;
                    $objPublicacion->fecha_reg = $hoy;
                    if ($objPublicacion->save()) {
                        $objPublicacion->tiempo_transcurrido = 'Justo ahora';
                        $objPublicacion->pseudonimo = $pseudonimo;
                        $objPublicacion->genero_id = 4;
                        $objPublicacion->genero_publicacion = 'COMENTARIO';
                        $objPublicacion->genero_publicacion_opcion = 0;
                        $objPublicacion->avatar = $avatar;
                        $msj = array("result" => 1, "msj" => "&Eacute;xito!: Registro exitoso del comentario.", "obj" => $objPublicacion);
                    } else {
                        $msj = array("result" => 0, "msj" => "Error cr&iacute;tico: No se pudo realizar la modificación del registro.", "obj" => null);
                    }
                }
            } else {
                /**
                 * Modificación de un comentario.
                 */
                $objPublicacion = new Publicaciones();
                $objPublicacion->padre_id = $idPublicacion;
                $objPublicacion->genero_id = 4;
                $objPublicacion->publicacion = $comentario;
                $objPublicacion->compartido = 0;
                $objPublicacion->estado = 1;
                $objPublicacion->baja_logica = 1;
                $objPublicacion->agrupador = 0;
                $objPublicacion->user_reg_id = $user_reg_id;
                $objPublicacion->fecha_reg = $hoy;
                if ($objPublicacion->save()) {
                    $objPublicacion->tiempo_transcurrido = 'Justo ahora';
                    $objPublicacion->pseudonimo = $pseudonimo;
                    $objPublicacion->genero_id = 4;
                    $objPublicacion->genero_publicacion = 'COMENTARIO';
                    $objPublicacion->genero_publicacion_opcion = 0;
                    $objPublicacion->avatar = $avatar;
                    $msj = array("result" => 1, "msj" => "&Eacute;xito!: Registro exitoso del comentario.", "obj" => $objPublicacion);
                } else {
                    $msj = array("result" => 0, "msj" => "Error cr&iacute;tico: No se pudo realizar el registro del comentario.", "obj" => null);
                }
            }
        } else {
            $msj = array("result" => -1, "msj" => "Error cr&iacute;tico: Se envi&oacute; datos incompletos.", "obj" => null);
        }
        echo json_encode($msj);
    }

    /**
     * Función para la obtención del contabilizador de emoticones.
     */
    public function getemoticonscountAction()
    {
        $auth = $this->session->get('auth');
        $user_reg_id = $auth['id'];
        $this->view->disable();
        $msj = array();
        $obj = new Publicaciones();
        $idPublicacion = 0;
        $contabilizador = "";
        if (isset($_POST["id_publicacion"])) {
            $idPublicacion = $_POST["id_publicacion"];
        }
        if ($idPublicacion > 0) {
            $contabilizador = $obj->getContadorEmoticones($idPublicacion, $user_reg_id);
        }
        echo $contabilizador;
    }

    /**
     * Función para la eliminación de un registro de publicación.
     */
    public function delAction()
    {
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $this->view->disable();
        $idPublicacion = 0;
        $hoy = date("Y-m-d H:i:s");
        $msj = array();
        if (isset($_POST["id_publicacion"])) {
            $idPublicacion = $_POST["id_publicacion"];
        }
        if ($user_mod_id > 0 && $idPublicacion > 0) {
            $publicacion = Publicaciones::findFirstById($idPublicacion);
            if (is_object($publicacion)) {
                $publicacion->baja_logica = 0;
                $publicacion->user_mod_id = $user_mod_id;
                $publicacion->fecha_mod = $hoy;
                if ($publicacion->save()) {
                    $msj = array("result" => 1, "msj" => "&Eacute;xitos: Registro eliminado de forma satisfactoria.", "obj" => $publicacion);
                } else {
                    $msj = array("result" => -1, "msj" => "Error: No se pudo eliminar el registro.", "obj" => $publicacion);
                }
            } else {
                $msj = array("result" => -1, "msj" => "Error cr&iacute;tico: Se envi&oacute; datos incompletos.", "obj" => null);
            }
        } else {
            $msj = array("result" => -1, "msj" => "Error cr&iacute;tico: Se envi&oacute; datos incompletos.", "obj" => null);
        }
        echo json_encode($msj);

    }

    /**
     * Función para el registro de los datos de usuario anónimo.
     */
    public function saveuserAction()
    {
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $this->view->disable();
        $hoy = date("Y-m-d H:i:s");
        $msj = array();
        $pseudonimo = "";
        $avatar = "";
        if (isset($_POST["pseudonimo"])) {
            $pseudonimo = $_POST["pseudonimo"];
        }
        if (isset($_POST["avatar"])) {
            $avatar = $_POST["avatar"];
        }
        if ($user_mod_id > 0 && $pseudonimo != "") {
            $us = Usuarios::findFirstById($user_mod_id);
            if (is_object($us)) {
                /*if ($us->pseudonimo != $pseudonimo) {*/
                $usAux = Usuarios::findFirst(array("id!=" . $user_mod_id . " AND UPPER(pseudonimo) LIKE UPPER('" . $pseudonimo . "')"));
                if (!is_object($usAux)) {
                    if ($avatar != "") {
                        $aux = $this->save_base64_image($avatar, $user_mod_id, "images/avatar/");
                        if ($aux != "") {
                            $us->avatar = $aux;
                        }
                    }
                    $us->pseudonimo = $pseudonimo;
                    $us->user_mod_id = $user_mod_id;
                    $us->fecha_mod = $hoy;
                    if ($us->save()) {
                        $msj = array("result" => 1, "msj" => "&Eacute;xitos: Registro satisfactorio del pseud&oacute;nimo.");
                    } else {
                        $msj = array("result" => 0, "msj" => "Error: no se pudo realizar la modificaci&oacute;n solicitada.");
                    }
                } else {
                    $msj = array("result" => 0, "msj" => "Error: El pseudonimo seleccionado ya existe debe registrar otro.");
                }
                /*}else{

                }*/
            } else {
                $msj = array("result" => -1, "msj" => "Error cr&iacute;tico: Se envi&oacute; datos incompletos.");
            }
        } else {
            $msj = array("result" => -1, "msj" => "Error cr&iacute;tico: Se envi&oacute; datos incompletos.");
        }
        echo json_encode($msj);
    }

    /**
     * Función para eliminar la fotografía del avatar definido para un usuario.
     */
    public function delavatarAction()
    {
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $this->view->disable();
        $hoy = date("Y-m-d H:i:s");
        $msj = array();
        if ($user_mod_id > 0) {
            $us = Usuarios::findFirstById($user_mod_id);
            if (is_object($us)) {
                if ($us->avatar != '') {
                    $us->avatar = null;
                    $us->user_mod_id = $user_mod_id;
                    $us->fecha_mod = $hoy;
                    if ($us->save()) {
                        $msj = array("result" => 1, "msj" => "&Eacute;xito: Eliminaci6oacute;n exitosa del avatar definido.");
                    } else {
                        $msj = array("result" => 0, "msj" => "Error: No se pudo eliminar la imagen de usuario an&oacute;nimo.");
                    }
                }
            } else {
                $msj = array("result" => -1, "msj" => "Error cr&iacute;tico: No se encontr&oacute; al usuario.");
            }
        } else {
            $msj = array("result" => -1, "msj" => "Error cr&iacute;tico: No se encontr&oacute; al usuario.");
        }
        echo json_encode($msj);
    }

    /**
     * Función para la obtención del listado de tipos de publicaciones.
     */
    public function listtipospublicacionesAction()
    {
        $auth = $this->session->get('auth');
        $idUsuario = $auth['id'];
        $nivel = $auth['nivel'];
        $this->view->disable();
        $generoPublicacion = array();
        if ($idUsuario > 0) {
            $generoPublicacion[] = array(
                'id' => 1,
                'genero' => "IDEA",
                'opcion' => 1
            );
            $permisos = $this->obtenerPermisosPorControladorMasIdentificador(strtolower(str_replace("Controller.php", "", basename(__FILE__))), "boolEsPosiblePublicarNoticiasComunicados");
            $obj = json_decode($permisos);
            $ver = $obj->v;
            /**
             * Si el usuario tiene el permiso de publicar noticias y comunicados
             */
            if ($ver == 1) {
                $generoPublicacion[] = array('id' => 2, 'genero' => "COMUNICADO", 'opcion' => 0);
                $generoPublicacion[] = array('id' => 3, 'genero' => "NOTICIA", 'opcion' => 1);
            }
        }
        echo json_encode($generoPublicacion);
    }

    /**
     * Función para la conversión de un código en base 64 representativo de una imagen a un archivo físico en una dirección determinada.
     * @param $base64_image_string
     * @param $output_file_without_extentnion
     * @param string $path_with_end_slash
     * @return string
     */
    function save_base64_image($base64_image_string, $output_file_without_extentnion, $path_with_end_slash = "")
    {
        //usage:  if( substr( $img_src, 0, 5 ) === "data:" ) {  $filename=save_base64_image($base64_image_string, $output_file_without_extentnion, getcwd() . "/application/assets/pins/$user_id/"); }
        //
        //data is like:    data:image/png;base64,asdfasdfasdf
        $splited = explode(',', substr($base64_image_string, 5), 2);
        $mime = $splited[0];
        $data = $splited[1];

        $mime_split_without_base64 = explode(';', $mime, 2);
        $mime_split = explode('/', $mime_split_without_base64[0], 2);
        $output_file_with_extentnion = "";
        if (count($mime_split) == 2) {
            $extension = $mime_split[1];
            if ($extension == 'jpeg') $extension = 'jpg';
            //if($extension=='javascript')$extension='js';
            //if($extension=='text')$extension='txt';
            $output_file_with_extentnion .= $output_file_without_extentnion . '.' . $extension;
        }
        file_put_contents($path_with_end_slash . $output_file_with_extentnion, base64_decode($data));
        return $output_file_with_extentnion;
    }
}