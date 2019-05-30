<?php

class LoginController extends \Phalcon\Mvc\Controller
{

    //login  
    public function indexAction()
    {
        $ok = false;
        $auth = $this->session->get('auth');
        if ($auth) {
            $this->response->redirect('/');
        }
        $this->view->setMainView('login');
        $this->view->setLayout('login');
        if ($this->request->isPost()) {
            $usuario = $this->request->getPost('username');
            $usuario = trim(strtolower(str_replace("@viasbolivia.gob.bo", "", $usuario)));
            $password = $this->request->getPost('password');
            $modalidad = $this->request->getPost('modalidad');
            $password = hash_hmac('sha256', $password, '2, 4, 6, 7, 9, 15, 20, 23, 25, 30');
            $user = Usuarios::findFirst(
                array(
                    "username = :usuario: AND password = :password: AND habilitado= :estado:",
                    "bind" => array('usuario' => $usuario, 'password' => $password, 'estado' => 1)
                ));
            if ($user != false) {
                $relaboral = Relaborales::findFirst(
                    array(
                        "persona_id=:id_persona: AND estado=:estado: AND baja_logica=:baja_logica:",
                        "bind" => array('id_persona' => $user->persona_id, 'estado' => 1, 'baja_logica' => 1))
                );
                if (is_object($relaboral)) {
                    $user->logins = (($user->logins == null) ? 0 : $user->logins) + 1;
                    $user->last_login = date("Y-m-d H:i:s");
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

                } else {
                    $this->flashSession->error('<b>¡Acceso denegado!</b></br> Usuario carece de contrato activo.');
                }
                if ($ok) {
                    $this->_registerSession($user, $modalidad);
                    /**$this->flashSession->success('Bienvenido <i>' . $user->nombre . '</i>');**/
                    $this->response->redirect('/');
                }
            } else $this->flashSession->error('<b>¡Acceso denegado!</b></br> Usuario/Contraseña/Modalidad incorrectos.');
        }
    }

    public function passwordAction()
    {
        $this->view->setMainView('login');
        $this->view->setLayout('login');
        if ($this->request->isPost()) {
            $email = $this->request->getPost('email');
            //buscamos el mail
            $user = Usuarios::findFirst(
                array(
                    "email = :email: AND habilitado= :estado:",
                    "bind" => array('email' => $email, 'estado' => 1)
                ));
            if ($user != false) {
                //acutalizamos la cantidad de ingresos
                $user->logins = $user->logins + 1;
                $user->lastlogin = time();
                $user->save();
                $this->_registerSession($user, 0);
                $this->flashSession->success('Bienvenido <i>' . $user->nombre . '</i>');
                $this->response->redirect('/dashboard');
            }
            $this->flashSession->error('Email inexistente en el sistema, o usuario No habilitado');
        }
    }

    public function registerAction()
    {
        $this->view->setMainView('login');
        $this->view->setLayout('login');
        if ($this->request->isPost()) {
            $email = $this->request->getPost('email');
            //buscamos el mail
            $user = Usuarios::findFirst(
                array(
                    "email = :email: AND habilitado= :estado:",
                    "bind" => array('email' => $email, 'estado' => 1)
                ));
            if ($user != false) {
                //acutalizamos la cantidad de ingresos
                $user->logins = $user->logins + 1;
                $user->lastlogin = time();
                $user->save();
                $this->_registerSession($user, 0);
                $this->flashSession->success('Bienvenido <i>' . $user->nombre . '</i>');
                $this->response->redirect('/dashboard');
            }
            $this->flashSession->error('Email inexistente en el sistema, o usuario No habilitado');
        }
    }

    /**
     * Función para el registro de la sesión.
     * @param $user
     * @param $idRelaboral
     */
    private function _registerSession($user, $modalidad)
    {
        $avatar = "indefinido.jpg";
        $persona = Personas::findFirst(array("id=" . $user->persona_id . " AND baja_logica=1"));
        if (is_object($persona)) {
            if ($persona->genero == "M") {
                $avatar = "hombre.jpg";
            } else {
                $avatar = "mujer.jpg";
            }
        }
        $objV = new Fmarcaciones();
        $version = $objV->obtenerVersionSistema();
        $this->session->set('auth', array(
            'id' => $user->id,
            'persona_id' => $user->persona_id,
            'name' => $user->username,
            'nombre' => $user->nombre,
            'cargo' => $user->cargo,
            'nivel' => $user->nivel,
            'modalidad' => $modalidad,
            'pseudonimo' => $user->pseudonimo,
            'avatar' => $user->avatar != '' ? $user->avatar : $avatar,
            'version' => $version
        ));
    }

    public function exitAction()
    {
        $this->session->remove('auth');
        /**
         * Añadido para el control de permisos 08/06/2015
         */
        $this->session->remove('permisos');
        $this->flash->success('Goodbye!');
        $this->response->redirect('/login');
    }

    /**
     * Función para actualizar el password de un usuario logueado al sistema.
     */
    public function updpasswordAction()
    {
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        if ($user_mod_id > 0) {
            if ($_POST["pass"] != '' && $_POST["pass_a"] != '' && $_POST["pass_b"] != '') {
                if ($_POST["pass"] == $_POST["pass_a"]) {
                    $msj = array('result' => 0, 'msj' => 'Alerta: Modificaci&oacute;n de Password innecesaria.');
                } else {
                    $password = $_POST["pass"];
                    $password = hash_hmac('sha256', $password, '2, 4, 6, 7, 9, 15, 20, 23, 25, 30');
                    $user = Usuarios::findFirst(
                        array(
                            "id = :id: AND password = :password: AND habilitado= :estado:",
                            "bind" => array('id' => $user_mod_id, 'password' => $password, 'estado' => 1)
                        ));
                    if (is_object($user)) {
                        $password_a = $_POST["pass_a"];
                        $password_b = $_POST["pass_b"];
                        if ($password_a == $password_b) {
                            $password_a = hash_hmac('sha256', $password_a, '2, 4, 6, 7, 9, 15, 20, 23, 25, 30');
                            $user->password = $password_a;
                            $user->user_mod_id = $user_mod_id;
                            $user->fecha_mod = $hoy;
                            $user->motivo_mod = "Actualizacion Password desde sistema local";
                            if ($user->save()) {
                                $msj = array('result' => 1, 'msj' => '&Eacute;xito: Modificaci&oacute;n de Password realizada satisfactoriamente.');
                            } else $msj = array('result' => 0, 'msj' => 'Error: El valor para el Password Actual no corresponde. Verifique nuevamente.');
                        } else {
                            $msj = array('result' => -2, 'msj' => 'Error: El Password nuevo y su confirmaci&oacute;n no coinciden. Verifique nuevamente.');
                        }
                    } else {
                        $msj = array('result' => -1, 'msj' => 'Error: El Password actual introducido no corresponde. Verifique nuevamente.');
                    }
                }
            } else  $msj = array('result' => -3, 'msj' => 'Error cr&iacute;tico: Datos enviados incorrectos.');
        } else $msj = array('result' => -2, 'msj' => 'Error cr&iacute;tico: La sesi&oacute;n ha concluido.');
        echo json_encode($msj);
    }
}
